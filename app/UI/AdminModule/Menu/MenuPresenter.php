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

        $menuCategories = $this->menuCategoryFacade->getAll(['menu_type' => $menu_type]);
        $this->template->menuCategories = $menuCategories;

        $menuItems = $this->menuItemFacade->getAll(['id_menu_category' => $menuCategories]);
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

        $data = json_decode($_POST['order_data'], true); // array (5) 1 => 4 2 => 0 43 => 1 45 => 2 46 => 3
        
//        if (!isset($data['order_data']) || !is_array($data['order_data'])) {
//            $this->error('Neplatná data');
//        }

        foreach ($data as $id => $position) {
//            $this->menuCategoryRepository->updateOrder($item['id'], $item['position']);
            $this->menuCategoryFacade->getOne(['id' => $id])->update(['order' => $position]);
        }

//        if (!isset($_POST['order_data'])) {
//            $this->error('order_data není nastaven');
//        }
//
//        $orderData = json_decode($_POST['order_data'], true);
////       
//        if (!is_array($orderData)) {
//            $this->error('order_data není validní JSON');
//        }
//
//        foreach ($orderData as $id => $position) {
////            $this->menuCategoryFacade->updateOrder((int) $id, (int) $position);
//            $this->menuCategoryFacade->getOne(['id' => $id])->update(['order' => $postition]);
//        }



        $this->sendJson(['status' => 'ok']);
    }

//    public function updateOrder(int $id, int $position): void {
//        $this->menuCategoryFacade->getOne(['id' => $id])->update(['order' => $postition]);
//    }
//    #[Requires(methods: 'POST', sameOrigin: true)]
//    public function handleUpdateOrder(): void {
//        
////        dd('hanle');
//        $data = json_decode(file_get_contents('php://input'), true);
//
//        if (!isset($data['order_data']) || !is_array($data['order_data'])) {
//            $this->error('Neplatná data');
//        }
//
//        foreach ($data['order_data'] as $item) {
//            if (!isset($item['id'], $item['order'])) {
//                continue;
//            }
//
//            $this->database->table('menu_category')
//                    ->where('id', $item['id'])
//                    ->update(['order' => $item['order']]);
//        }
//
//        $this->sendJson(['status' => 'ok']);
//    }

    protected function createComponentMenuCategoryForm(): MenuCategoryFormControl {
        return $this->menuCategoryFormFactory->create();
    }

    protected function createComponentMenuItemForm(): MenuItemFormControl {
        return $this->menuItemFormFactory->create();
    }
}
