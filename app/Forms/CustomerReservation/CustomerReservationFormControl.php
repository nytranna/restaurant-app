<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class CustomerReservationFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\ReservationFacade $reservationFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addText('name', 'Vaše jméno')
                ->setRequired('Zadejte prosím své jméno.')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Vaše jméno');

        $form->addEmail('email', 'Váš email')
                ->setRequired('Zadejte prosím email.')
                ->setHtmlType('email')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Váš email');

        $form->addText('phone', 'Telefonní číslo')
                ->setRequired('Zadejte prosím telefonní číslo.')
                ->setHtmlType('tel')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Telefonní číslo');

        $form->addText('date', 'Datum')
                ->setHtmlType('date')
                ->setRequired('Zadejte datum.')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Datum');

        $form->addText('time', 'Čas')
                ->setHtmlType('time')
                ->setRequired('Zadejte čas.')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Čas');

        $form->addInteger('people', 'Počet osob')
                ->setRequired('Zadejte počet lidí.')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('placeholder', 'Počet osob');

        $form->addTextArea('message', 'Poznámky')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('rows', '5')
                ->setHtmlAttribute('placeholder', 'Poznámky');

        $styles = [
            'background' => '#cda45e',
            'color' => '#fff',
            'border-radius' => '50px',
            'padding' => '10px 35px',
            'border' => '0',
            'transition' => '0.4s',
            'font-size' => 'inherit',
            'line-height' => 'inherit'
        ];

        $form->addSubmit('send', 'Odeslat')
                ->setHtmlAttribute('style', $styles);

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function submitted(Form $form, \stdClass $data): void {

        $reservationDateTime = $data->date . ' ' . $data->time;

        $reservationData = [
            'customer_name' => $data->name,
            'customer_email' => $data->email,
            'customer_phone' => $data->phone,
            'reservation_date' => $reservationDateTime,
            'guest_count' => $data->people,
            'note' => $data->message
        ];

        $this->reservationFacade->insert($reservationData);

        if (!$form->hasErrors()) {

            $form->getPresenter()->flashMessage('Rezervace byla úspěšně odeslána', 'success');
            $form->getPresenter()->redirect('this');
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_customerReservationForm.latte');
    }
}
