<?php
namespace App\UI\Admin\Dashboard;

use App\Model\PostFacade;
use Nette;

final class DashboardPresenter extends Nette\Application\UI\Presenter
{
        use \App\Core\PresenterTrait;
        
        
        

	public function __construct(
		private \App\Model\Facade\UserFacade $userFacade,
	) {
	}

	public function renderDefault(): void
	{
            //\Tracy\Debugger::dump($this->user->identity->data['username']);
            //dd($this->userFacade->getOne(['id' => 2]));
            //$this->userFacade->insert(['name'=>'Test', 'email'=>'test@test.te', 'password'=>'heslo', 'role'=> 'owner' ]);
            //$this->userFacade->getOne(['id'=>3])->delete();
            //$this->userFacade->getOne(['id'=>2])->update(['name'=>'PéŤa']);
            
//            $this->flashMessage('Položka byla smazána.', 'success');
//            $this->flashMessage('Položka byla smazána.', 'danger');
	}
	
}
