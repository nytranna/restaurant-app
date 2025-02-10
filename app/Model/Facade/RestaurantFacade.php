<?php

namespace App\Model\Facade;

final class RestaurantFacade {
    
    use FacadeTrait;

    const TABLE_NAME = 'restaurant';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }
    
    
    
    
    
}
