<?php

namespace App\Model\Facade;

final class MenuCategoryFacade {

    use FacadeTrait;

    const TABLE_NAME = 'menu_category';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }

    public function getMaxOrderRow(array $where = []): ?\Nette\Database\Table\ActiveRow {
        return $this->getData($where)->order('`order` DESC')->limit(1)->fetch();
    }
}
