<?php

namespace App\UI\Admin\Menu;

use App\Model\PostFacade;
use Nette;
use App\Forms\MenuFormControl;
use App\Forms\MenuFormFactory;

final class MenuPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private MenuFormFactory $menuFormFactory,
            private \App\Model\Facade\MenuCategoryFacade $menuCategoryFacade,
            private \App\Model\Facade\MenuItemFacade $menuItemFacade,
            private \App\Model\Facade\MenuItemVariantFacade $menuItemVariantFacade
    ) {
        
    }

    public function renderDefault(): void {
        $menuCategory = $this->menuCategoryFacade->getOne();
        $this->template->menuCategory = $menuCategory;

        $menuItem = $this->menuItemFacade->getOne();
        $this->template->menuItem = $menuItem;

        $menuItemVariant = $this->menuItemVariantFacade->getOne();
        $this->template->menuItemVariant = $menuItemVariant;
    }

    public function renderEdit($id = null): void {


    }

    protected function createComponentMenuForm(): MenuFormControl {
        return $this->MenuFormFactory->create();
    }
}
