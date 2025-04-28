<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class InfoFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\WebSectionsFacade $webSectionFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addHidden('id');

        $form->addText('name', 'Název restaurace:');

        $form->addText('sentence', 'Úvodní věta:');

        $form->addTextArea('about_us', 'O nás:', null, 15);

        $form->addText('address', 'Adresa:');

        $form->addText('email', 'Email:');

        $form->addText('phone', 'Telefonní číslo:');

        $form->addText('ico', 'IČO:');

        $form->addText('facebook', 'Facebook:');

        $form->addText('instagram', 'Instagram:');

        $form->addText('tripadvisor', 'Tripadvisor:');

        foreach ($this->webSectionFacade->getAll() as $w) {
            if ($w->href != 'hero') {
                $webSections[$w->id] = $w->name;
            }
        }

        $form->addCheckboxList('webSections', 'Webové sekce:', $webSections);

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
            'ico' => $data->ico,
            'facebook' => $data->facebook,
            'instagram' => $data->instagram,
            'tripadvisor' => $data->tripadvisor
        ];

        $checkedWebSections = [];
        foreach ($this->webSectionFacade->getAll(['is_shown' => 1]) as $s) {
            $checkedWebSections[] = $s->id;
        }        

        $data['webSections'] = $checkedWebSections;

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
            'ico' => $data->ico,
            'facebook' => $data->facebook,
            'instagram' => $data->instagram,
            'tripadvisor' => $data->tripadvisor
        ];

        foreach ($this->webSectionFacade->getAll() as $s) {
            $s->update(['is_shown' => 0]);
        }


        foreach ($data->webSections as $w) {
            $this->webSectionFacade->getOne(['id' => $w])->update(['is_shown' => 1]);
        }

        $this->restaurantFacade->getOne(['id' => $data->id])->update($infoData);

        $form->getPresenter()->flashMessage('Změna údajů proběhla úspěšně.', 'success');
        $form->getPresenter()->redirect('Info:default');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_infoForm.latte');
    }
}
