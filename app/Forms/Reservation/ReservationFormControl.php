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
            private User $user
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
                ->setRequired('Prosím vyplňte datum a čas.');

        $form->addText('guest_count', 'Počet osob:')
                ->setRequired('Prosím vyplňte počet osob.')
                ->addRule(Form::INTEGER, 'Zadejte pouze číslo.');

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
            'status' => $data->status
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
            'last_user' => $loggedInUserName            
        ];

        $reservation = $this->reservationFacade->getOne(['id' => $data->id]);

        if ($data->id) {
            $this->reservationFacade->getOne(['id' => $data->id])->update($reservationData);

            if ($data->status == 'confirmed' && !$reservation->email_send) {
//                $this->sendEmail($data->customer_email); //odkomentovat!!!             
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

    public function sendEmail(string $email) {

        $mail = new Message();
        $mail->setFrom('Anna <anna.nytrova@email.cz>')
                ->addTo($email)
                ->setSubject('Potvrzení rezervace')
                ->setHtmlBody("<h1>Potvrzení rezervace</h1><p>Potvrzujeme Vaší rezervaci. Těšíme se na Vás.</p>");

        $mailer = new SendmailMailer();
        $mailer->send($mail);
    }

    public function render() {
        $this->template->render(__DIR__ . '/_reservationForm.latte');
    }
}
