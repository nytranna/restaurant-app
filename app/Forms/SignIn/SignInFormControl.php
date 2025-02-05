<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class SignInFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addText('email', 'Email:')
                ->setRequired('Prosím vyplňte svůj email.');

        $form->addPassword('password', 'Heslo:')
                ->setRequired('Prosím vyplňte své heslo.');

        $form->addSubmit('send', 'Přihlásit');
        
          $form->onSuccess[] = [$this, 'submitted'];
          $form->onValidate[] = [$this, 'validated'];

        return $form;
    }
    
    public function validated(Form $form, \stdClass $data): void {}

    public function submitted(Form $form, \stdClass $data): void {

        try {
            $this->presenter->user->login($data->email, $data->password);
            //$this->getUser()->login($data->username, $data->password);
            $this->presenter->redirect('Dashboard:default');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_signInForm.latte');
    }
}
