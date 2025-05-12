<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class ResetPasswordFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\PasswordResetFacade $passwordReset,
            private Nette\Security\Passwords $passwords,
            private \App\Model\Facade\UserFacade $userFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addPassword('password', 'Nové heslo:')
                ->setRequired('Prosím vyplňte nové heslo.');

        $form->addPassword('passwordAgain', 'Nové heslo znovu:')
                ->setRequired('Prosím vyplňte nové heslo znovu.')
                ->addRule($form::EQUAL, 'Hesla se musí shodovat.', $form['password']);

        $form->addHidden('hash');

        $form->addSubmit('send', 'Obnovit heslo.');

        $form->onSuccess[] = [$this, 'submitted'];

        return $form;
    }

    public function setDefaults($data) {

        $data = ['hash' => $data->hash];

        $this['form']->setDefaults($data);
    }

    public function submitted(Form $form, \stdClass $data): void {
        
        $userId = $this->passwordReset->getOne(['hash' => $data->hash])->id_user;

        $password = $data->password;

        $hashPassword = $this->passwords->hash($password);

        $this->userFacade->getOne(['id' => $userId])->update(['password' => $hashPassword]);

        $form->getPresenter()->flashMessage('Změna hesla proběhla úspěšně', 'success');
        $this->presenter->redirect('Sign:in');
    }

    public function validated(Form $form, \stdClass $data): void {

        $password = $data->password;
        $passwordAgain = $data->passwordAgain;

        if ($password !== $passwordAgain) {

            $form->addError('Nová hesla se neshodují.');
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_resetPasswordForm.latte');
    }
}
