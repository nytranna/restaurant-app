<?php

namespace App\UI\Admin\Users;

use App\Model\PostFacade;
use Nette;
use App\Forms\UserFormControl;
use App\Forms\UserFormFactory;

final class UsersPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private \App\Model\Facade\UserFacade $userFacade,
            private UserFormFactory $userFormFactory
    ) {
        
    }

    public function renderDefault(): void {
        $users = $this->userFacade->getAll();

        $this->template->users = $users;
    }

    public function renderEdit($id = null): void {

        if ($id) {
            $user = $this->userFacade->getOne(['id' => $id]);

            if (!$user) {
                $this->error(404);
            }

            $this['userForm']->setDefaults($user);
        }
        
        $this->template->userData = $user ?? null;
    }

    protected function createComponentUserForm(): UserFormControl {
        return $this->userFormFactory->create();
    }
    
    
    #[Requires(methods: 'POST', sameOrigin: true)]
    public function actionDelete(int $id): void {

            $this->userFacade->getOne(['id' => $id])->delete();
            $this->redirect('Users:default');
         
        
    }
    
    //AJAx volání
    /*
    #[Requires(methods: 'POST', sameOrigin: true)]
    public function handleDelete(int $id): void {

        if ($this->isAjax()) {
            $this->userFacade->getOne(['id' => $id])->delete();
            //$this->redirect('Users:default');
            $this->redrawControl('user_table');
        } else {
            $this->redirect('this');
        }
    }
    */
}
