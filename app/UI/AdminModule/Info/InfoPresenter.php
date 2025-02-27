<?php

namespace App\UI\Admin\Info;

use App\Model\PostFacade;
use Nette;
use App\Forms\InfoFormControl;
use App\Forms\InfoFormFactory;

final class InfoPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private InfoFormFactory $infoFormFactory,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade
    ) {
        
    }

    public function renderDefault(): void {
        $restaurant = $this->restaurantFacade->getOne();
        $this->template->restaurant = $restaurant;
        
//        dd($restaurant);
    }

    public function renderEdit($id = null): void {

        $restaurant = $this->restaurantFacade->getOne(['id' => $id]);
        
//        dd($restaurant);

        if (!$restaurant) {
            $this->error('Info o restauraci nenalezeny.');
        }

        $this['infoForm']->setDefaults($restaurant);

        $this->template->infoData = $restaurant ?? null;
    }

    protected function createComponentInfoForm(): InfoFormControl {
        return $this->infoFormFactory->create();
    }
}
