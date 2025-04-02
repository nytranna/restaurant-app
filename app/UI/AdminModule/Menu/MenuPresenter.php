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

    public function renderEdit(string $menu_type): void {

        /*
          $menuCategories = $this->menuCategoryFacade->getAll(['menu_type' => $menu_type]);

          // Získání ID kategorií pro filtrování menu itemů
          $categoryIds = array_map(fn($category) => $category->id, $menuCategories);

          // Načtení menu položek, které patří do vybraných kategorií
          $menuItems = $this->menuItemFacade->getAll(['id_menu_category' => $categoryIds]);

          // Získání ID položek pro filtrování variant
          $menuItemIds = array_map(fn($item) => $item->id, $menuItems);

          // Načtení variant pouze pro vybrané položky
          $menuItemVariants = $this->menuItemVariantFacade->getAll(['id_menu_item' => $menuItemIds]);

          // Odeslání dat do šablony
          $this->template->menuCategories = $menuCategories;
          $this->template->menuItems = $menuItems;
          $this->template->menuItemVariants = $menuItemVariants;
          $this->template->menuType = $menu_type; // Můžeš ho použít v šabloně
         */

        //pouze kategorie podle typu menu - dostaneme id těchto kategorií
        $menuCategories = $this->menuCategoryFacade->getAll(['menu_type' => $menu_type]);
        $this->template->menuCategories = $menuCategories;

        //pouze položky kategorií výše - vypíše id těchto položek
        $menuItems = $this->menuItemFacade->getAll(['id_menu_category' => $menuCategories]);
        $this->template->menuItems = $menuItems;

        //všechny varianty položek - vypíše id variant
        $menuItemVariants = $this->menuItemVariantFacade->getAll();
        $this->template->menuItemVariants = $menuItemVariants;
    }

    public function renderList(string $menu_type): void {


        $menuCategory = $this->menuCategoryFacade->getOne(['menu_type' => $menu_type]);
        $this->template->menuCategory = $menuCategory;
        

        //pouze kategorie podle typu menu - dostaneme id těchto kategorií
        $menuCategories = $this->menuCategoryFacade->getAll(['menu_type' => $menu_type]);
        $this->template->menuCategories = $menuCategories;

        //pouze položky kategorií výše - vypíše id těchto položek
        $menuItems = $this->menuItemFacade->getAll(['id_menu_category' => $menuCategories]);
        $this->template->menuItems = $menuItems;

        //všechny varianty položek - vypíše id variant
        $menuItemVariants = $this->menuItemVariantFacade->getAll();
        $this->template->menuItemVariants = $menuItemVariants;
    }

    protected function createComponentMenuForm(): MenuFormControl {
        return $this->menuFormFactory->create();
    }
}
