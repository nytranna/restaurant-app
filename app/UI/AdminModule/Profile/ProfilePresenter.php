<?php

namespace App\UI\Admin\Profile;

use App\Model\PostFacade;
use Nette;
use App\Forms\ProfileFormControl;
use App\Forms\ProfileFormFactory;

final class ProfilePresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private ProfileFormFactory $profileFormFactory
    ) {
        
    }

    public function renderDefault(): void {
  
    }

    protected function createComponentProfileForm(): ProfileFormControl {
           return $this->profileFormFactory->create();
    }
}
