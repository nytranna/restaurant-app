<?php

namespace App\UI\Admin\Sign;

use App\Forms\SignInFormControl;
use App\Forms\SignInFormFactory;
use App\Forms\ResetFormControl;
use App\Forms\ResetFormFactory;
use App\Forms\ResetPasswordFormControl;
use App\Forms\ResetPasswordFormFactory;
use App\Model\Facade\UserFacade;
use Nette;
use Nette\Application\UI\Form;

final class SignPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private SignInFormFactory $signInFormFactory,
            private ResetFormFactory $resetFormFactory,
            private ResetPasswordFormFactory $resetPasswordFormFactory,
            private \App\Model\Facade\PasswordResetFacade $passwordResetFacade,
            private UserFacade $userFacade
    ) {
        
    }

    public function renderIn() {
        if ($this->user->isLoggedIn()) {
            $this->redirect('Dashboard:default');
        }
    }

    public function renderResetPassword($hash) {

        $validHash = $this->passwordResetFacade->getOne(['hash' => $hash]);

        if (!$validHash) {
            $this->error('404');
        }

        $createdAt = $validHash->created_at;
        if ($createdAt instanceof \DateTimeInterface) {
            $now = new \DateTimeImmutable();
            $interval = $now->getTimestamp() - $createdAt->getTimestamp();

            if ($interval > 3600) {
                $this->flashMessage('Odkaz pro reset hesla vypršel.', 'danger');
                $this->redirect('Sign:in');
            }
        } else {
            $this->flashMessage('Neplatný formát data odkazu.', 'danger');
            $this->redirect('Sign:in');
        }

        $user = $this->userFacade->getOne(['id' => $validHash->id_user ?? null]);

        if (!$user) {
            $this->flashMessage('Uživatel pro tento odkaz neexistuje.', 'danger');
            $this->redirect('Sign:in');
        }

        $this['resetPasswordForm']->setDefaults($validHash);
    }

    protected function createComponentSignInForm(): SignInFormControl {
        return $this->signInFormFactory->create();
    }

    public function actionOut(): void {
        $this->getUser()->logout(true);
        //$this->flashMessage('Odhlášení bylo úspěšné.', 'success');
        $this->redirect('Sign:in');
    }

    protected function createComponentResetForm(): ResetFormControl {
        return $this->resetFormFactory->create();
    }

    protected function createComponentResetPasswordForm(): ResetPasswordFormControl {

        return $this->resetPasswordFormFactory->create();
    }
}
