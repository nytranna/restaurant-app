<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class InfoFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addHidden('id');

        $form->addText('name', 'Název restaurace:');

        $form->addText('sentence', 'Úvodní věta:');

        $form->addText('about_us', 'O nás:');

        $form->addText('address', 'Adresa:');

        $form->addText('email', 'Email:');

        $form->addText('phone', 'Telefonní číslo:');

        $form->addText('facebook', 'Facebook:');

        $form->addText('instagram', 'Instagram:');

        $form->addText('tripadvisor', 'Tripadvisor:');

        //$form->onValidate[] = [$this, 'validated'];

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function setDefaults($data) {

        $data = ['id' => $data->id,
            'name' => $data->name,
            'sentence' => $data->sentence,
            'about_us' => $data->about_us,
            'address' => $data->address,
            'email' => $data->email,
            'phone' => $data->phone,
            'facebook' => $data->facebook,
            'instagram' => $data->instagram,
            'tripadvisor' => $data->tripadvisor
        ];

        $this['form']->setDefaults($data);
    }

    public function submitted(Form $form, \stdClass $data): void {


        $infoData = [
            'name' => $data->name,
            'sentence' => $data->sentence,
            'about_us' => $data->about_us,
            'address' => $data->address,
            'email' => $data->email,
            'phone' => $data->phone,
            'facebook' => $data->facebook,
            'instagram' => $data->instagram,
            'tripadvisor' => $data->tripadvisor
        ];

        $this->restaurantFacade->getOne(['id' => $data->id])->update($infoData);

        $form->getPresenter()->flashMessage('Změna údajů proběhla úspěšně.', 'success');
        $form->getPresenter()->redirect('Info:default');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_infoForm.latte');
    }
}
