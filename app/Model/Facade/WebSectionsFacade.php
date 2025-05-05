<?php

namespace App\Model\Facade;

final class WebSectionsFacade {

    use FacadeTrait;

    const TABLE_NAME = 'web_sections';

    public function __construct(private \Nette\Database\Explorer $database) {
        
    }

    public function getSections() {
        $sections = $this->getAll([], 'order ASC');

        $output = [];

        foreach ($sections as $s) {
            $output[$s->href] = $s;
        }
        return $output;
    }
}
