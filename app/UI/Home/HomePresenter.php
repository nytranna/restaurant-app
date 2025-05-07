<?php

namespace App\UI\Home;

use Nette;
use App\Forms\CustomerReservationFormFactory;
use App\Forms\CustomerReservationFormControl;

final class HomePresenter extends Nette\Application\UI\Presenter {

    public function __construct(
            private \App\Model\Facade\RestaurantFacade $restaurantFacade,
            private \App\Model\Facade\OpeningHoursFacade $openingHoursFacade,
            private \App\Model\Facade\WebSectionsFacade $webSectionsFacade,
            private \App\Model\Facade\NewsFacade $newsFacade,
            private \App\Model\Facade\ImageFacade $imageFacade,
            private \App\Model\Facade\MenuCategoryFacade $menuCategoryFacade,
            private \App\Model\Facade\MenuItemFacade $menuItemFacade,
            private \App\Model\Facade\MenuItemVariantFacade $menuItemVariantFacade,
            private CustomerReservationFormFactory $customerReservationFormFactory
    ) {
        
    }

    public function renderDefault(): void {
        $restaurant = $this->restaurantFacade->getOne();
        $this->template->restaurant = $restaurant;

        $openingHours = $this->openingHoursFacade->getAll();
        $this->template->openingHours = $openingHours;

        $webSections = $this->webSectionsFacade->getSections();
        $this->template->webSections = $webSections;

        $reservationSection = $this->webSectionsFacade->getOne(['href' => 'reservation']);
        $this->template->reservationSection = $reservationSection;

        $menuSection = $this->webSectionsFacade->getOne(['href' => 'menu']);
        $this->template->menuSection = $menuSection;

        $news = $this->newsFacade->getAll();
        $this->template->news = $news;

        $images = $this->imageFacade->getAll([], 'order ASC');
        $this->template->images = $images;

        $menuCategories = $this->menuCategoryFacade->getAll([], 'order ASC');
        $this->template->menuCategories = $menuCategories;

        $menuItems = $this->menuItemFacade->getAll([], 'order ASC');
        $this->template->menuItems = $menuItems;

        $menuItemVariants = $this->menuItemVariantFacade->getAll();
        $this->template->menuItemVariants = $menuItemVariants;

        $today = date('N');

        $openingHourToday = $this->openingHoursFacade->getAll(['id' => $today]);
        $this->template->openingHourToday = $openingHourToday;

        $image = $this->imageFacade->getOne(['id' => $restaurant->id_image]);
        $this->template->image = $image->name ?? '';

        $imageEvents = $this->imageFacade->getOne(['id' => $restaurant->id_image_events]);
        $this->template->imageEvents = $imageEvents->name ?? '';

        $imageAboutUs = $this->imageFacade->getOne(['id' => $restaurant->id_image_about_us]);
        $this->template->imageAboutUs = $imageAboutUs->name ?? '';
    }

  

    protected function createComponentReservationForm(): CustomerReservationFormControl {
        return $this->customerReservationFormFactory->create();
    }
}
