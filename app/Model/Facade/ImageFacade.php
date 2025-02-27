<?php

namespace App\Model\Facade;

final class ImageFacade {

    use FacadeTrait;

    const TABLE_NAME = 'image';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }

}
