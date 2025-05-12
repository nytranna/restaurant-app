<?php

namespace App\Model\Facade;

final class PasswordResetFacade {
    
    use FacadeTrait;

    const TABLE_NAME = 'password_reset';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }
}
