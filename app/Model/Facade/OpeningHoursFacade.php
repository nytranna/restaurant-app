<?php

namespace App\Model\Facade;

final class OpeningHoursFacade {
    
    use FacadeTrait;

    const TABLE_NAME = 'opening_hours';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }
 
    
}
