<?php

namespace App\Forms;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class InfoFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\WebSectionsFacade $webSectionFacade,
            private \App\Model\Facade\OpeningHoursFacade $openingHoursFacade,
            private \App\Model\Facade\ImageFacade $imageFacade
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
        
        $form->addText('email_send', 'Email pro odesílání pošty:');

        $form->addText('phone', 'Telefonní číslo:');

        $form->addText('ico', 'IČO:');

        $form->addText('facebook', 'Facebook:');

        $form->addText('instagram', 'Instagram:');

        $form->addText('tripadvisor', 'Tripadvisor:');

        $images = [];
        foreach ($this->imageFacade->getAll() as $img) {
            $images[$img->id] = $img->name;
        }

        $form->addSelect('id_image', 'Úvodní obrázek v pozadí:', $images)
                ->setPrompt('--- bez obrázku ---');

        $form->addSelect('id_image_events', 'Obrázek v pozadí Aktualit:', $images)
                ->setPrompt('--- bez obrázku ---');

        $form->addSelect('id_image_about_us', 'Obrázek v pozadí O nás:', $images)
                ->setPrompt('--- bez obrázku ---');

        $form->addSelect('id_image_about_us_in', 'Obrázek v O nás:', $images)
                ->setPrompt('--- bez obrázku ---');

        foreach ($this->webSectionFacade->getAll() as $w) {
            if ($w->href != 'hero') {
                $webSections[$w->id] = $w->name;
            }
        }
        $form->addCheckboxList('webSections', 'Webové sekce:', $webSections);

        $form->addText('mon_open', 'Pondělí:');
        $form->addText('mon_close', 'Pondělí do:');
        $form->addCheckbox('mon_closed', 'zavřeno');

        $form->addText('tue_open', 'Úterý:');
        $form->addText('tue_close', 'Úterý do:');
        $form->addCheckbox('tue_closed', 'zavřeno');

        $form->addText('wed_open', 'Středa:');
        $form->addText('wed_close', 'Středa do:');
        $form->addCheckbox('wed_closed', 'zavřeno');

        $form->addText('thu_open', 'Čtvrtek:');
        $form->addText('thu_close', 'Čtvrtek do:');
        $form->addCheckbox('thu_closed', 'zavřeno');

        $form->addText('fri_open', 'Pátek:');
        $form->addText('fri_close', 'Pátek do:');
        $form->addCheckbox('fri_closed', 'zavřeno');

        $form->addText('sat_open', 'Sobota:');
        $form->addText('sat_close', 'Sobota do:');
        $form->addCheckbox('sat_closed', 'zavřeno');

        $form->addText('sun_open', 'Neděle:');
        $form->addText('sun_close', 'Neděle do:');
        $form->addCheckbox('sun_closed', 'zavřeno');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function setDefaults($data) {

        $mon = $this->openingHoursFacade->getOne(['day' => 'Pondělí']);
        $tue = $this->openingHoursFacade->getOne(['day' => 'Úterý']);
        $wed = $this->openingHoursFacade->getOne(['day' => 'Středa']);
        $thu = $this->openingHoursFacade->getOne(['day' => 'Čtvrtek']);
        $fri = $this->openingHoursFacade->getOne(['day' => 'Pátek']);
        $sat = $this->openingHoursFacade->getOne(['day' => 'Sobota']);
        $sun = $this->openingHoursFacade->getOne(['day' => 'Neděle']);

        $days = [
            'mon' => $mon,
            'tue' => $tue,
            'wed' => $wed,
            'thu' => $thu,
            'fri' => $fri,
            'sat' => $sat,
            'sun' => $sun,
        ];

        $id_image = $this->restaurantFacade->getOne(['id' => $data->id])->id_image;
        $id_image_events = $this->restaurantFacade->getOne(['id' => $data->id])->id_image_events;
        $id_image_about_us = $this->restaurantFacade->getOne(['id' => $data->id])->id_image_about_us;
        $id_image_about_us_in = $this->restaurantFacade->getOne(['id' => $data->id])->id_image_about_us_in;

        $data = ['id' => $data->id,
            'name' => $data->name,
            'sentence' => $data->sentence,
            'about_us' => $data->about_us,
            'address' => $data->address,
            'email' => $data->email,
            'email_send' => $data->email_send,
            'phone' => $data->phone,
            'ico' => $data->ico,
            'facebook' => $data->facebook,
            'instagram' => $data->instagram,
            'tripadvisor' => $data->tripadvisor,
            'id_image' => $id_image,
            'id_image_events' => $id_image_events,
            'id_image_about_us' => $id_image_about_us,
            'id_image_about_us_in' => $id_image_about_us_in
        ];

        $checkedWebSections = [];
        foreach ($this->webSectionFacade->getAll(['is_shown' => 1]) as $s) {
            $checkedWebSections[] = $s->id;
        }
        $data['webSections'] = $checkedWebSections;

        foreach ($days as $key => $d) {

            if (isset($d->opening_hour)) {
                $data["{$key}_open"] = $d->opening_hour;
            }
            if (isset($d->closing_hour)) {
                $data["{$key}_close"] = $d->closing_hour;
            }
            if (isset($d->is_closed)) {
                $data["{$key}_closed"] = $d->is_closed;
            }
        }

        $this['form']->setDefaults($data);
    }

    public function submitted(Form $form, \stdClass $data): void {


        $infoData = [
            'name' => $data->name,
            'sentence' => $data->sentence,
            'about_us' => $data->about_us,
            'address' => $data->address,
            'email' => $data->email,
            'email_send' => $data->email_send,
            'phone' => $data->phone,
            'ico' => $data->ico,
            'facebook' => $data->facebook,
            'instagram' => $data->instagram,
            'tripadvisor' => $data->tripadvisor,
            'id_image' => $data->id_image,
            'id_image_events' => $data->id_image_events,
            'id_image_about_us' => $data->id_image_about_us,
            'id_image_about_us_in' => $data->id_image_about_us_in
        ];

        foreach ($this->webSectionFacade->getAll() as $s) {
            $s->update(['is_shown' => 0]);
        }


        foreach ($data->webSections as $w) {
            $this->webSectionFacade->getOne(['id' => $w])->update(['is_shown' => 1]);
        }

        $this->restaurantFacade->getOne(['id' => $data->id])->update($infoData);

        $days = [
            'mon' => 'Pondělí',
            'tue' => 'Úterý',
            'wed' => 'Středa',
            'thu' => 'Čtvrtek',
            'fri' => 'Pátek',
            'sat' => 'Sobota',
            'sun' => 'Neděle',
        ];

        foreach ($days as $key => $dayName) {
            $record = $this->openingHoursFacade->getOne(['day' => $dayName]);
            if ($record) {
                $updateData = [
                    'opening_hour' => $data->{$key . '_open'} ?: null,
                    'closing_hour' => $data->{$key . '_close'} ?: null,
                    'is_closed' => !empty($data->{$key . '_closed'}) ? 1 : 0,
                ];

                $record->update($updateData);
            }
        }

        $form->getPresenter()->flashMessage('Změna údajů proběhla úspěšně.', 'success');
        $form->getPresenter()->redirect('Info:default');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_infoForm.latte');
    }
}
