<?php

namespace App\Model\Facade;

final class UserFacade {
    
    use FacadeTrait;

    const TABLE_NAME = 'user';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }
}
