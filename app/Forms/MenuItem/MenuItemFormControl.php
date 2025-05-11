<?php

namespace App\Forms;

//use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

//use Nette\Database\Explorer;
//use Nette\Application\UI\Multiplier;
//use Nette\Forms\Container;

class MenuItemFormControl extends Control {

    private $item;

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

        $categories = $this->menuCategoryFacade->getAll(['menu_type' => $menuType]);

        $idsNO = [];

//        foreach ($categories as $c) {
//            if ($c->id_menu_category) {
//                $idsNO[] = $c->id_menu_category;
//            }
//        }

        if (!empty($idsNO)) {
            $idsCategoriesWoSub = $this->menuCategoryFacade->getAll(['menu_type' => $menuType, 'id NOT IN' => $idsNO]);
        } else {
            $idsCategoriesWoSub = $this->menuCategoryFacade->getAll(['menu_type' => $menuType]);
        }

        $categoriesWoSub = [];
        foreach ($idsCategoriesWoSub as $i) {
            $categoriesWoSub[$i->id] = $i->name;
        }

        $form->addText('name', 'Název nové položky:')
                ->setRequired();

        $form->addText('description', 'Popis:');

        $form->addSelect('category', 'Zvolte kategorii:', $categoriesWoSub)
                ->setRequired();

        $form->addHidden('menu_type', $menuType);

        $form->addHidden('id_menu_item');

        $form->addHidden('id');

        $form->addSubmit('addField', '+ Přidat variantu')
                ->setHtmlAttribute('class', 'ajax')
                ->setValidationScope([])
                ->onClick[] = [$this, 'addRow'];

        $form->addSubmit('send', 'Uložit')
                ->setHtmlAttribute('class', 'btn btn-success');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function createContainer(Form $form, $name, $return_string = false) {

        $container = $form['variants'] ?? $form->addContainer('variants');

        $sub = $container->addContainer($name);

        $sub->addText('size', 'Velikost')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Velikost');

        $sub->addText('price', 'Cena (Kč)')
                ->setRequired()
                ->setHtmlType('number')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Cena (Kč)');

        if ($return_string) {
            $latte = new \Latte\Engine;

            $latte->addExtension(new \Nette\Bridges\ApplicationLatte\UIExtension($this->presenter));
            $latte->addExtension(new \Nette\Bridges\FormsLatte\FormsExtension);

            $parameters = array('name' => $name, '_control' => $this, '_form' => $form);
            $html = $latte->renderToString(__DIR__ . '/_addRow.latte', $parameters);

            return $html;
        }
    }

    public function addRow(Form $form): void {
        if ($this->presenter->isAjax()) {
            $row = $this->createContainer($form, \Nette\Utils\Random::generate(), true);
            $this->presenter->payload->add_row = $row;
            $this->presenter->sendPayload();
        }
    }

    public function setDefaults($data) {

        $this->item = $data;

        $idsVariant = [];
        foreach ($data->related('menu_item_variant', 'id_menu_item') as $menu_item_variant) {
            $idsVariant[] = $menu_item_variant->id;
        }

        foreach ($idsVariant as $idV) {

            $menuItemVariants = $this->menuItemVariantFacade->getAll(['id' => $idV]);

            foreach ($menuItemVariants as $variant) {
                $name = \Nette\Utils\Random::generate();
                $this->createContainer($this['form'], $name);

                $this['form']['variants'][$name]['size']->setDefaultValue($variant->size);
                $this['form']['variants'][$name]['price']->setDefaultValue($variant->price);
            }
        }

        $defaultsData = [
            'id' => $data->id,
            'idItem' => $data->related('menu_item_variant', 'id_menu_item'),
            'name' => $data->name,
            'description' => $data->description,
            'category' => $data->category
        ];

        $this['form']->setDefaults($defaultsData);
    }

    public function submitted(Form $form, \stdClass $data): void {

        $maxOrderRow = $this->menuItemFacade->getMaxOrderRow([
            'id_menu_category' => $data->category
        ]);
        $nextOrder = $maxOrderRow ? $maxOrderRow->order + 1 : 1;

//        $itemData = [
//            'name' => $data->name,
//            'description' => $data->description,
//            'id_menu_category' => $data->category,
//            'order' => $nextOrder
//        ];

        $dataVariant = ($form->getHttpData());

        $sizes = [];
        $prices = [];

        if (isset($dataVariant['variants'])) {
            foreach ($dataVariant['variants'] as $variant) {
                if (!($variant['size'] === null && $variant['price'] === null)) {
                    $sizes[] = $variant['size'];
                    $prices[] = $variant['price'];
                }
            }
        }

        if ($data->id) {
            $this->menuItemFacade->getDatabase()->beginTransaction();
            try {
                $itemData = [
                    'name' => $data->name,
                    'description' => $data->description,
                    'id_menu_category' => $data->category
                ];

                $this->menuItemFacade->getOne(['id' => $data->id])->update($itemData);

                $variants = $this->menuItemVariantFacade->getAll(['id_menu_item' => $data->id]);
                foreach ($variants as $variant) {
                    $variant->delete();
                }

                foreach ($sizes as $i => $size) {
                    $this->menuItemVariantFacade->insert([
                        'id_menu_item' => $data->id,
                        'size' => $size,
                        'price' => $prices[$i] ?? null
                    ]);
                }
            } catch (\Exception $e) {
                $this->menuItemFacade->getDatabase()->rollback();
                throw $e;
            }
            $this->menuItemFacade->getDatabase()->commit();
        } else {
            $itemData = [
                'name' => $data->name,
                'description' => $data->description,
                'id_menu_category' => $data->category,
                'order' => $nextOrder
            ];
            
            $newItem = $this->menuItemFacade->insert($itemData);

            foreach ($sizes as $i => $size) {
                $this->menuItemVariantFacade->insert([
                    'id_menu_item' => $newItem,
                    'size' => $size,
                    'price' => $prices[$i] ?? null
                ]);
            }
        }

        if (!$form->hasErrors()) {

            $message = $data->id ? 'Upravení položky proběhlo úspěšně.' : 'Vytvoření nové položky proběhlo úspěšně.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('Menu:listItem', ['menu_type' => $data->menu_type]);
        }
    }

    public function render() {

        $presenter = $this->getPresenter();
        $menu_type = $presenter->getParameter('menu_type');
        $this->template->menu_type = $menu_type ?? 'menu';

        $this->template->render(__DIR__ . '/_menuItemForm.latte');
    }
}
