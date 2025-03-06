<?php

namespace App\Model\Facade;

final class MenuItemFacade {

    use FacadeTrait;

    const TABLE_NAME = 'menu_item';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }

}
