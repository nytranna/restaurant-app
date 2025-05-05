<?php

namespace App\UI\Admin\Info;

use Nette;
use App\Forms\InfoFormControl;
use App\Forms\InfoFormFactory;

final class InfoPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private InfoFormFactory $infoFormFactory,
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\WebSectionsFacade $webSectionsFacade,
            private \App\Model\Facade\OpeningHoursFacade $openinghoursFacade
    ) {
        
    }

    public function renderDefault(): void {
        $restaurant = $this->restaurantFacade->getOne();
        $this->template->restaurant = $restaurant;
        
        $webSections = $this->webSectionsFacade->getAll();
        $this->template->webSections = $webSections;
        
        $openingHours = $this->openinghoursFacade->getAll();
        $this->template->openingHours = $openingHours;
        
        $image = $restaurant->ref('image', 'id_image');

        
    }

    public function renderEdit($id = null): void {

        $restaurant = $this->restaurantFacade->getOne(['id' => $id]);
        
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
