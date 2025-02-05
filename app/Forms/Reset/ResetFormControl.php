<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class ResetFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\PasswordResetFacade $passwordResetFacade,
            private \App\Model\Facade\UserFacade $userFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addText('email', 'Email:')
                ->setRequired('Prosím vyplňte svůj email.');

        $form->addSubmit('send', 'Poslat odkaz na email');

        //$form->addHidden($hash);

        $form->onSuccess[] = [$this, 'submitted'];

        return $form;
    }

    public function submitted(Form $form, \stdClass $data): void {

        $hash = Nette\Utils\Random::generate(30);

        $user = $this->userFacade->getOne(['email' => $data->email]);

        if ($user) {


            $this->passwordResetFacade->insert(['hash' => $hash, 'user_id' => $user->id]);

            $this->sendEmail($data->email, $hash);
        }

        $form->getPresenter()->flashMessage('Pokud je email registovaný, heslo bylo změněno.', 'success');
        $form->getPresenter()->redirect('Sign:in');
    }

    public function sendEmail(string $email, string $hash) {

        $url = $this->presenter->link('Sign:resetPassword', ['hash' => $hash]);
        
        //dd($url);

        $mail = new Message();
        $mail->setFrom('Anna <anna.nytrova@email.cz>')
                ->addTo($email)
                ->setSubject('Reset Password')
                ->setHtmlBody("<h1>Obnova hesla</h1><p>Pro resetování hesla klikněte <a href='$url'>zde</a></p>");

        $mailer = new SendmailMailer();
        //$mailer->send($mail);

        //$this->flashMessage('E-mail byl úspěšně odeslán.', 'success');
        $this->redirect('this');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_resetForm.latte');
    }
}
