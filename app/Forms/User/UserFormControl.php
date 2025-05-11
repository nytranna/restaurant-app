<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Random;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class UserFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\UserFacade $userFacade,
            private Nette\Security\Passwords $passwords,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\PasswordResetFacade $passwordResetFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addText('name', 'Jméno:')
                ->setRequired('Prosím vyplňte jméno.');

        $form->addText('email', 'Email:')
                ->setRequired('Prosím vyplňte email.')
                ->addRule($form::Email, 'Zadejte platný e-mail.');

        $role = ['admin' => 'admin', 'staff' => 'personál'];

        $form->addRadioList('role', 'Role:', $role)
                ->setRequired('Prosím vyberte roli uživatele.');

        $form->addHidden('id');

        $form->addSubmit('send', 'Uložit');

        $form->onValidate[] = [$this, 'validated'];

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function setDefaults($data) {

        $data = ['id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role
        ];

        $this['form']->setDefaults($data);
    }

    public function validated(Form $form, \stdClass $data): void {
        
    }

    public function submitted(Form $form, \stdClass $data): void {

        $hash = Nette\Utils\Random::generate(30);

        $userData = [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role,
        ];

        try {
            if ($data->id) {
                $this->userFacade->getOne(['id' => $data->id])->update($userData);
            } else {
                $this->userFacade->insert($userData);

                $user = $this->userFacade->getOne(['email' => $data->email]);

                $this->passwordResetFacade->insert(['hash' => $hash, 'id_user' => $user->id]);

                $emailSend = $this->restaurantFacade->getOne()->email_send;

                $this->sendEmail($data->email, $hash, $emailSend); //odkomentovat
            }
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $form->addError('Tento e-mail už je zaregistrován.');
        }

        if (!$form->hasErrors()) {

            $message = $data->id ? 'Změna uživatele proběhla úspěšně' : 'Vytvoření nového uživatele proběhlo úspěšně. E-mail byl odeslán.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('Users:default');
        } else {
            foreach ($form->getOwnErrors() as $e) {
                $form->getPresenter()->flashMessage($e, 'danger');
            }
        }
    }

    public function sendEmail(string $email, string $hash, string $emailSend) {

        $url = $this->presenter->link('//Sign:resetPassword', ['hash' => $hash]);

        $mail = new Message();
        $mail->setFrom('RestaurantApp <' . $emailSend . '>')
                ->addTo($email)
                ->setSubject('RestaurantApp - vytvoření hesla')
                ->setHtmlBody("<h1>Vytvoření hesla do RestaurantApp</h1><p>Pro vytvoření hesla klikněte <a href='$url'>zde</a>.</p>");

        $mailer = new SendmailMailer();
        $mailer->send($mail);

//        $this->presenter->flashMessage('E-mail byl úspěšně odeslán.', 'success');

//        $this->flashMessage('E-mail byl úspěšně odeslán.', 'success');
//        $this->redirect('this');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_userForm.latte');
    }
}
