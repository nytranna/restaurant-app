<?php

namespace App\UI\Home;

use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter {

    public function __construct(
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\OpeningHoursFacade $openingHoursFacade
    ) {
        
    }

    public function renderDefault(): void {
        $restaurant = $this->restaurantFacade->getOne();

        $this->template->restaurant = $restaurant;
        
        $openingHours = $this->openingHoursFacade->getAll();
        
        //dd($restaurant->address);
        //dd($openingHours);
        $this->template->openingHours = $openingHours;
        


    }
}
