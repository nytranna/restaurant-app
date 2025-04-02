<?php

namespace App\UI\Home;

use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter {

    public function __construct(
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\OpeningHoursFacade $openingHoursFacade,
            private \App\Model\Facade\WebSectionsFacade $webSectionsFacade,
            private \App\Model\Facade\NewsFacade $newsFacade,
            private \App\Model\Facade\ImageFacade $imageFacade,
            private \App\Model\Facade\MenuCategoryFacade $menuCategoryFacade,
            private \App\Model\Facade\MenuItemFacade $menuItemFacade,
            private \App\Model\Facade\MenuItemVariantFacade $menuItemVariantFacade
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

        $images = $this->imageFacade->getAll();
        $this->template->images = $images;

        $menuCategories = $this->menuCategoryFacade->getAll();
        $this->template->menuCategories = $menuCategories;

        $menuItems = $this->menuItemFacade->getAll();
        $this->template->menuItems = $menuItems;
        
        $menuItemVariants = $this->menuItemVariantFacade->getAll();
        $this->template->menuItemVariants = $menuItemVariants;

        $today = date('N');

        $openingHourToday = $this->openingHoursFacade->getAll(['id' => $today]);
        $this->template->openingHourToday = $openingHourToday;
    }
}
