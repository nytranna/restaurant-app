<?php

namespace App\Model\Facade;

final class NewsFacade {

    use FacadeTrait;

    const TABLE_NAME = 'news';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }

    public function getSections() {
        
    }
}
