<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class ProfileFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\UserFacade $userFacade,
            private Nette\Security\Passwords $passwords
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addPassword('password', 'Původní heslo:')
                ->setRequired('Vyplňte původní heslo.');
        $form->addPassword('newPassword', 'Nové heslo:')
                ->setRequired('Vyplňte nové heslo.');
        $form->addPassword('newPasswordAgain', 'Znovu nové heslo:')
                ->setRequired('Vyplňte znovu nové heslo.');

        $form->addSubmit('change', 'Změnit heslo');

        $form->onValidate[] = [$this, 'validated'];

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function submitted(Form $form, \stdClass $data): void {



        $newPassword = $data->newPassword;

        $hashNewPassword = $this->passwords->hash($newPassword);

        $this->userFacade->getOne(['id' => $this->presenter->user->id])->update(['password' => $hashNewPassword]);

        $form->getPresenter()->flashMessage('Změna hesla proběhla úspěšně', 'success');
        
        $this->presenter->redirect('this');


    }

    public function render() {
        $this->template->render(__DIR__ . '/_profileForm.latte');
    }

    public function validated(Form $form, \stdClass $data): void {



        $password = $data->password;
        $hash = $this->userFacade->getOne(['id' => $this->presenter->user->id])->password;

        $newPassword = $data->newPassword;
        $newPasswordAgain = $data->newPasswordAgain;

        if (!$this->passwords->verify($password, $hash)) {
            $form->addError('Původní heslo není správné.');
        }

        if ($newPassword !== $newPasswordAgain) {

            $form->addError('Nová hesla se neshodují.');
        }
    }
}
