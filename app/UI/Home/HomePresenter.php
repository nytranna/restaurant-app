<?php

namespace App\UI\Home;

use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter {

    public function __construct(
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\OpeningHoursFacade $openingHoursFacade,
            private \App\Model\Facade\WebSectionsFacade $webSectionsFacade,
            private \App\Model\Facade\NewsFacade $newsFacade,
            private \App\Model\Facade\ImageFacade $imageFacade
    ) {
        
    }

    public function renderDefault(): void {
        $restaurant = $this->restaurantFacade->getOne();
        $this->template->restaurant = $restaurant;

        $openingHours = $this->openingHoursFacade->getAll();
        $this->template->openingHours = $openingHours;

        $webSections = $this->webSectionsFacade->getSections();
        $this->template->webSections = $webSections;

        $news = $this->newsFacade->getAll();
        $this->template->news = $news;
        
        $image = $this->imageFacade->getAll();
        $this->template->image = $image;
        
        $today = date('N');

        $openingHourToday = $this->openingHoursFacade->getAll(['id'=> $today]);
        $this->template->openingHourToday = $openingHourToday;

    }
}
