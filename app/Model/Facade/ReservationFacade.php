<?php

namespace App\Model\Facade;

final class ReservationFacade {
    
    use FacadeTrait;

    const TABLE_NAME = 'reservation';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }
    
    
    
    
}
