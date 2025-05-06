<?php

namespace App\UI\Admin\Menu;

use Nette;
use App\Forms\MenuCategoryFormControl;
use App\Forms\MenuCategoryFormFactory;
use App\Forms\MenuItemFormControl;
use App\Forms\MenuItemFormFactory;
use Nette\Application\Responses\JsonResponse;
use Nette\Http\Request;
use Nette\Database\Explorer;

final class MenuPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private MenuCategoryFormFactory $menuCategoryFormFactory,
            private MenuItemFormFactory $menuItemFormFactory,
            private \App\Model\Facade\MenuCategoryFacade $menuCategoryFacade,
            private \App\Model\Facade\MenuItemFacade $menuItemFacade,
            private \App\Model\Facade\MenuItemVariantFacade $menuItemVariantFacade,
            private Explorer $database,
            private Request $httpRequest
    ) {
        
    }

    public function renderDefault(string $menu_type): void {

        $menuCategory = $this->menuCategoryFacade->getOne(['menu_type' => $menu_type]);
        $this->template->menuCategory = $menuCategory;

        $menuItem = $this->menuItemFacade->getOne();
        $this->template->menuItem = $menuItem;

        $menuItemVariant = $this->menuItemVariantFacade->getOne();
        $this->template->menuItemVariant = $menuItemVariant;

        $menuCategories = $this->menuCategoryFacade->getAll(['menu_type' => $menu_type], 'order ASC');
        $this->template->menuCategories = $menuCategories;

        $menuItems = $this->menuItemFacade->getAll(['id_menu_category' => $menuCategories], 'order ASC');
        $this->template->menuItems = $menuItems;

        $menuItemVariants = $this->menuItemVariantFacade->getAll();
        $this->template->menuItemVariants = $menuItemVariants;
    }

    public function renderListCategory(string $menu_type) {
        $menuCategory = $this->menuCategoryFacade->getOne(['menu_type' => $menu_type]);
        $this->template->menuCategory = $menuCategory;

        $categories = $this->menuCategoryFacade->getAll(['menu_type' => $menu_type], 'order ASC');
        $this->template->categories = $categories;
    }

    public function renderListItem(string $menu_type) {
        $categories = $this->menuCategoryFacade->getAll(['menu_type' => $menu_type]);
        $this->template->categories = $categories;

        $menuCategory = $this->menuCategoryFacade->getOne(['menu_type' => $menu_type]);
        $this->template->menuCategory = $menuCategory;

        $items = $this->menuItemFacade->getAll(['id_menu_category' => $categories]);
        $this->template->items = $items;
    }

    public function renderEditCategory(string $menu_type, $id = null): void {

        if ($id) {
            $category = $this->menuCategoryFacade->getOne(['id' => $id]);

            if (!$category) {
                $this->error(404);
            }

            $this['menuCategoryForm']->setDefaults($category);
        }

        $this->template->categoryData = $category ?? null;
    }

    public function renderEditItem(string $menu_type, $id = null): void {

        if ($id) {
            $item = $this->menuItemFacade->getOne(['id' => $id]);

            if (!$item) {
                $this->error(404);
            }

            $this['menuItemForm']->setDefaults($item);
        }

        $this->template->itemData = $item ?? null;
    }

    #[Requires(methods: 'POST', sameOrigin: true)]
    public function actionDeleteCategory(int $id): void {
        $menuType = $this->getParameter('menu_type');

        $this->menuCategoryFacade->getOne(['id' => $id])->delete();
        $this->redirect('Menu:listCategory', ['menu_type' => $menuType]);
    }

    #[Requires(methods: 'POST', sameOrigin: true)]
    public function actionDeleteItem(int $id): void {
        $menuType = $this->getParameter('menu_type');

        $variants = $this->menuItemVariantFacade->getAll(['id_menu_item' => $id]);

        foreach ($variants as $v) {
            $v->delete();
        }

        $this->menuItemFacade->getOne(['id' => $id])->delete();

        $this->redirect('Menu:listItem', ['menu_type' => $menuType]);
    }

    public function handleUpdateOrder(): void {

        $data = json_decode($_POST['order_data'], true);
        $dbTable = $_POST['db_table'] ?? null;

        foreach ($data as $id => $position) {
            if ($dbTable == 'menu_category') {
                $this->menuCategoryFacade->getOne(['id' => $id])->update(['order' => $position]);
            }
            if ($dbTable == 'menu_item') {
                $this->menuItemFacade->getOne(['id' => $id])->update(['order' => $position]);
            }
        }

        $this->sendJson(['status' => 'ok']);
    }

    protected function createComponentMenuCategoryForm(): MenuCategoryFormControl {
        return $this->menuCategoryFormFactory->create();
    }

    protected function createComponentMenuItemForm(): MenuItemFormControl {
        return $this->menuItemFormFactory->create();
    }
}
