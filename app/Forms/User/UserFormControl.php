<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

class UserFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\UserFacade $userFacade
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

        $form->addRadioList('role', 'Role:', $role);

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
            'role' => $data->role];

        $this['form']->setDefaults($data);
    }

    public function validated(Form $form, \stdClass $data): void {
        
    }

    public function submitted(Form $form, \stdClass $data): void {


        $userData = [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role
        ];

        try {

            if ($data->id) {

                $this->userFacade->getOne(['id' => $data->id])->update($userData);
            } else {


                $this->userFacade->insert($userData);
            }
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $form->addError('Tento e-mail už je zaregistrován.');
        }



        if (!$form->hasErrors()) {

            $message = $data->id ? 'Změna uživatele proběhla úspěšně' : 'Vytvoření nového uživatele proběhlo úspěšně.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('Users:default');
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_userForm.latte');
    }
}
