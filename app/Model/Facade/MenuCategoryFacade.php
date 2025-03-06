<?php

namespace App\Model\Facade;

final class MenuCategoryFacade {

    use FacadeTrait;

    const TABLE_NAME = 'menu_category';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }

}
