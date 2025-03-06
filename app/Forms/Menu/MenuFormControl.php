<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class MenuFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\MenuCategoryFacade $menuCategoryFacade,
            private \App\Model\Facade\MenuItemFacade $menuItemFacade,
            private \App\Model\Facade\MenuItemVariantFacade $menuItemVariantFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addHidden('id');

//        $form->addText('name', 'Název restaurace:');
//
//        $form->addTextArea('about_us', 'O nás:', null, 5);

        //$form->onValidate[] = [$this, 'validated'];

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function setDefaults($data) {

//        $data = ['id' => $data->id,
//            'name' => $data->name,
//            'sentence' => $data->sentence
//        ];
//
//        $this['form']->setDefaults($data);
    }

    public function submitted(Form $form, \stdClass $data): void {

//
//        $menuData = [
//            'name' => $data->name,
//            'sentence' => $data->sentence
//        ];
//
//        $this->restaurantFacade->getOne(['id' => $data->id])->update($menuData);
//
//        $form->getPresenter()->flashMessage('Změna údajů proběhla úspěšně.', 'success');
//        $form->getPresenter()->redirect('Info:default');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_menuForm.latte');
    }
}
