<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class MenuCategoryFormControl extends Control {

    private $category;

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\MenuCategoryFacade $menuCategoryFacade,
            private \App\Model\Facade\MenuItemFacade $menuItemFacade,
            private \App\Model\Facade\MenuItemVariantFacade $menuItemVariantFacade
    ) {
        
    }

    public function createComponentForm(): Form {

        $form = $this->baseFormFactory->create();

        $presenter = $this->getPresenter();
        $menuType = $presenter->getParameter('menu_type');

        if ($this->category) {
            $categories = $this->menuCategoryFacade->getAll(['menu_type' => $menuType, 'id != ?' => $this->category->id]);
        } else {
            $categories = $this->menuCategoryFacade->getAll(['menu_type' => $menuType]);
        }

        $upperCategories = ['' => ''];

        foreach ($categories as $c) {
            if (!$c->id_menu_category) {
                $upperCategories[$c->id] = $c->name;
            }
        }

        $form->addText('name', 'Název nové kategorie:');

        $form->addSelect('parent_category', 'Zvolte nadkategorii:', $upperCategories);

        $form->addHidden('menu_type', $menuType);

        $form->addHidden('id');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function setDefaults($data) {

        $this->category = $data;

        $data = [
            'id' => $data->id,
            'name' => $data->name,
            'parent_category' => $data->id_menu_category
        ];

        $this['form']->setDefaults($data);
    }

    public function submitted(Form $form, \stdClass $data): void {

        if ($data->parent_category === '') {
            $data->parent_category = null;
        }

        $maxOrderRow = $this->menuCategoryFacade->getMaxOrderRow([
            'menu_type' => $data->menu_type,
            'id_menu_category' => $data->parent_category,
        ]);

        $nextOrder = $maxOrderRow ? $maxOrderRow->order + 1 : 1;

//        $categoryData = [
//            'name' => $data->name,
//            'id_menu_category' => $data->parent_category,
//            'menu_type' => $data->menu_type,
//            'order' => $nextOrder
//        ];

        if ($data->id) {
            $categoryData = [
                'name' => $data->name,
                'id_menu_category' => $data->parent_category,
                'menu_type' => $data->menu_type
            ];

            $this->menuCategoryFacade->getOne(['id' => $data->id])->update($categoryData);
        } else {
            $categoryData = [
                'name' => $data->name,
                'id_menu_category' => $data->parent_category,
                'menu_type' => $data->menu_type,
                'order' => $nextOrder
            ];
            $this->menuCategoryFacade->insert($categoryData);
        }

        if (!$form->hasErrors()) {

            $message = $data->id ? 'Přidání kategorie proběhlo úspěšně' : 'Vytvoření nové kategorie proběhlo úspěšně.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('Menu:listCategory', ['menu_type' => $data->menu_type]);
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_menuCategoryForm.latte');
    }
}
