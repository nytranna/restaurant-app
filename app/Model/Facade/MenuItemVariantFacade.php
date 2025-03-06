<?php

namespace App\Model\Facade;

final class MenuItemVariantFacade {

    use FacadeTrait;

    const TABLE_NAME = 'menu_item_variant';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }

}
