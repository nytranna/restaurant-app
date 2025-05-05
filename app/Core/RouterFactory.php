<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory {

    use Nette\StaticClass;

    public static function createRouter(): RouteList {
        $router = new RouteList;

//        $router->addRoute('admin/<presenter>/<action>/<menu_type>[/<id>][/<do>]',
//                ['module' => 'Admin', 'presenter' => 'Menu', 'action' => 'listCategory']);
        // Cesta pro admin/menu/list-category s parametrem menu_type
//        $router->addRoute('admin/menu/list-category/<menu_type>', ['module' => 'Admin', 'presenter' => 'Menu', 'action' => 'listCategory']);
//
//        // Cesta pro aktualizaci pořadí
//        $router->addRoute('admin/menu/list-category/<menu_type>/updateOrder', ['module' => 'Admin', 'presenter' => 'Menu', 'action' => 'updateOrder']);

        $router->addRoute('admin/<presenter>/<action>[/<id>]', ['module' => 'Admin', 'presenter' => 'Dashboard', 'action' => 'default']);
        $router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
        return $router;
    }
}
