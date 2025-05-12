<?php
namespace App\Forms;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class MenuFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\MenuCategoryFacade $menuCategoryFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $presenter = $this->getPresenter();
        $menuType = $presenter->getParameter('menu_type');

        $form->addText('name', 'Název kategorie:');

        $form->addHidden('menu_type', $menuType);

        $form->addHidden('id');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function submitted(Form $form, \stdClass $data): void {

        $categoryData = [
            'name' => $data->name,
            'menu_type' => $data->menu_type
        ];

        if ($data->id) {

            $this->menuCategoryFacade->getOne(['id' => $data->id])->update($categoryData);
        } else {


            $this->menuCategoryFacade->insert($categoryData);
        }

        if (!$form->hasErrors()) {

            $message = $data->id ? 'Přidání kategorie proběhlo úspěšněú' : 'Vytvoření nové kategorie proběhlo úspěšně.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('Menu:listCategory', ['menu_type' => $data->menu_type]);
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_menuForm.latte');
    }
}
