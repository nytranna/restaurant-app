<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class CustomerReservationFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\ReservationFacade $reservationFacade,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade
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
                ->setHtmlAttribute('placeholder', 'Telefonní číslo')
                ->addRule($form::PATTERN, 'Zadejte platné telefonní číslo.', '^[+]?[0-9]{6,15}$');

        $form->addText('date', 'Datum')
                ->setHtmlType('date')
                ->setRequired('Zadejte datum.')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('class', 'form-control datepicker')
                ->setHtmlAttribute('placeholder', 'Datum');

        $form->addText('time', 'Čas')
                ->setHtmlType('time')
                ->setRequired('Zadejte čas.')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('class', 'form-control timepicker')
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

        $emailSend = $this->restaurantFacade->getOne()->email_send;
        $restaurantName = $this->restaurantFacade->getOne()->name;
        $restaurantEmail = $this->restaurantFacade->getOne()->email;

        $reservationData = [
            'customer_name' => $data->name,
            'customer_email' => $data->email,
            'customer_phone' => $data->phone,
            'reservation_date' => $reservationDateTime,
            'guest_count' => $data->people,
            'note' => $data->message,
            'is_new' => 1
        ];

        $this->reservationFacade->insert($reservationData);

        if (!$form->hasErrors()) {

            $this->sendEmailCustomer($restaurantName, $emailSend, $data->email, $data->name, $data->phone, $reservationDateTime, $data->people, $data->message); //odkomentovat
            $this->sendEmailAdmin($emailSend, $restaurantName, $restaurantEmail, $data->name, $data->email, $data->phone, $reservationDateTime, $data->people, $data->message); //odkomentovat

            $form->getPresenter()->flashMessage('Žádost o rezervaci byla úspěšně odeslána.', 'success');
            $form->getPresenter()->redirect('this');
        }
    }

    public function sendEmailCustomer(string $restaurantName, string $emailSend, string $email, string $name, $phone, $date, $guests, $note = null) {

        $mail = new Message();
        $mail->setFrom('Restaurace '.$restaurantName. ' <'.$emailSend.'>')
                ->addTo($email)
                ->setSubject('Rezervace - restaurace '. $restaurantName)
                ->setHtmlBody("<p>Dobrý den,<br>zaznamenali jsme Vaši žádost o rezervaci. Vyčkejte prosím na email s potvrzením."
                        . "<br>Informace o rezervaci:"
                        . "<br>Jméno: " . $name
                        . "<br>Telefon: " . $phone
                        . "<br>Datum a čas: " . $date
                        . "<br>Počet osob: " . $guests
                        . "<br>Poznámka: " . $note
                        . "</p>");

        $mailer = new SendmailMailer();
        $mailer->send($mail);
    }

    public function sendEmailAdmin(string $emailSend, string $restaurantName, string $restaurantEmail, string $name, string $email, $phone, $date, $guests, $note = null) {

        $mail = new Message();
        $mail->setFrom('Restaurace '.$restaurantName.' <'.$emailSend.'>')
                ->addTo($restaurantEmail)
                ->setSubject('Nová rezervace')
                ->setHtmlBody(
                        "<h1>Rezervace</h1>
                        <p>Byla vytvořena nová rezervace."
                        . "<br><br>Jméno: " . $name
                        . "<br>Email: " . $email
                        . "<br>Telefon: " . $phone
                        . "<br>Datum a čas: " . $date
                        . "<br>Počet osob: " . $guests
                        . "<br>Poznámka: " . $note . "</p>"
                );

        $mailer = new SendmailMailer();
        $mailer->send($mail);
    }

    public function render() {
        $this->template->render(__DIR__ . '/_customerReservationForm.latte');
    }
}
