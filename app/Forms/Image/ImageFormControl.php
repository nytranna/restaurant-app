<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class ImageFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\ImageFacade $imageFacade
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
//        $imageData = [
//            'name' => $data->name,
//            'sentence' => $data->sentence
//        ];
//
//        $this->restaurantFacade->getOne(['id' => $data->id])->update($imageData);
//
//        $form->getPresenter()->flashMessage('Změna údajů proběhla úspěšně.', 'success');
//        $form->getPresenter()->redirect('Info:default');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_imageForm.latte');
    }
}
