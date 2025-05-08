<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Security\User;

class ReservationFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\ReservationFacade $reservationFacade,
            private \App\Model\Facade\UserFacade $userFacade,
            private User $user,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addHidden('id');

        $form->addText('customer_name', 'Jméno:')
                ->setRequired('Prosím vyplňte jméno.');

        $form->addEmail('customer_email', 'Email:')
                ->setRequired('Prosím vyplňte email.');

        $form->addText('customer_phone', 'Telefon:')
                ->setRequired('Prosím vyplňte telefon.')
                ->addRule(Form::PATTERN, 'Zadejte platné telefonní číslo.', '^\+?[0-9 ]{9,20}$');

        $form->addDateTime('reservation_date', 'Datum a čas:')
                ->setRequired('Prosím vyplňte datum a čas.')
                ->setHtmlAttribute('class', 'form-control datetimepicker');

        $form->addText('guest_count', 'Počet osob:')
                ->setRequired('Prosím vyplňte počet osob.')
                ->addRule(Form::INTEGER, 'Zadejte pouze číslo.');
        
        $form->addText('note', 'Poznámka:');
        
        

        $status = ['pending' => 'Nevyřízeno', 'confirmed' => 'Potvrzeno', 'cancelled' => 'Zrušeno'];

        $form->addRadioList('status', 'Status:', $status);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function setDefaults($data) {

        $data = ['id' => $data->id,
            'customer_name' => $data->customer_name,
            'customer_email' => $data->customer_email,
            'customer_phone' => $data->customer_phone,
            'reservation_date' => $data->reservation_date,
            'guest_count' => $data->guest_count,
            'status' => $data->status,
            'note' => $data->note
        ];

        $this['form']->setDefaults($data);
    }

    public function submitted(Form $form, \stdClass $data): void {

        $loggedInUserId = $this->user->getId();
        $loggedInUserName = $this->userFacade->getOne(['id' => $loggedInUserId])->name;

        if ($data->status == null) {
            $data->status = 'pending';
        }

        $reservationData = [
            'customer_name' => $data->customer_name,
            'customer_email' => $data->customer_email,
            'customer_phone' => $data->customer_phone,
            'reservation_date' => $data->reservation_date,
            'guest_count' => $data->guest_count,
            'status' => $data->status,
            'is_new' => 0,
            'email_send' => 1,
            'last_user' => $loggedInUserName,
            'note' => $data->note
        ];

        $reservation = $this->reservationFacade->getOne(['id' => $data->id]);
        $restaurantName = $this->restaurantFacade->getOne()->name;
        $emailSend = $this->restaurantFacade->getOne()->email_send;
        $formattedDate = $data->reservation_date->format('Y-m-d H:i');

        if ($data->id) {
            $this->reservationFacade->getOne(['id' => $data->id])->update($reservationData);

            if ($data->status == 'confirmed' && !$reservation->email_send) {
                
                $this->sendEmail($restaurantName, $emailSend, $data->customer_email, $data->customer_name, $data->customer_phone, $formattedDate, $data->guest_count, $data->note);    
                
            }
        } else {
            $this->reservationFacade->insert($reservationData);
        }

        if (!$form->hasErrors()) {

            $message = $data->id ? 'Změna údajů proběhla úspěšně' : 'Vytvoření nové rezervace proběhlo úspěšně.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('Reservation:default');
        }
    }

    public function sendEmail(string $restaurantName, string $emailSend, string $email, string $name, $phone, $date, $guests, $note) {

        $mail = new Message();
        $mail->setFrom('Restaurace '.$restaurantName.' <'.$emailSend.'>')
                ->addTo($email)
                ->setSubject('Potvrzení rezervace')
                ->setHtmlBody("<p>Dobrý den,<br>potvrzujeme Vaší rezervaci. "
                        . "<br>Těšíme se na Vás."
                        . "<br><br>Informace o rezervaci:"
                        . "<br>Jméno: " . $name
                        . "<br>Telefon: " . $phone
                        . "<br>Datum a čas: " . $date
                        . "<br>Počet osob: " . $guests
                        . "<br>Poznámka: " . $note
                        . "</p>");

        $mailer = new SendmailMailer();
        $mailer->send($mail);
    }

    public function render() {
        $this->template->render(__DIR__ . '/_reservationForm.latte');
    }
}
