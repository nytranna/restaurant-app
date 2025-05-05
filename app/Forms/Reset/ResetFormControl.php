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

            $this->passwordResetFacade->insert(['hash' => $hash, 'id_user' => $user->id]);

            $this->sendEmail($data->email, $hash);
        }

        $form->getPresenter()->flashMessage('Pokud je e-mail registrován, byl odeslán odkaz na reset hesla.', 'success');
        $form->getPresenter()->redirect('this');
    }

    public function sendEmail(string $email, string $hash) {

        $url = $this->presenter->link('//Sign:resetPassword', ['hash' => $hash]); // /admin/sign/reset-password?hash=o731yeguhbykg5z6ls2kc23g2e9e61
        

        $mail = new Message();
        $mail->setFrom('Anna <anna.nytrova@email.cz>')
                ->addTo($email)
                ->setSubject('RestaurantApp - obnovení hesla')
                ->setHtmlBody("<h1>Obnovení hesla do RestaurantApp</h1><p>Pro obnovení hesla klikněte <a href='$url'>zde</a>.<br>Pokud si nepřejete resetovat heslo, tento email ignorujte.</p>");

        $mailer = new SendmailMailer();
        $mailer->send($mail);

//        $this->flashMessage('E-mail byl úspěšně odeslán.', 'success');
//        $this->redirect('this');
    }

    public function render() {
        $this->template->render(__DIR__ . '/_resetForm.latte');
    }
}
