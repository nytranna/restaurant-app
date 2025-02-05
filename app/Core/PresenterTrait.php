<?php

namespace App\Core;

trait PresenterTrait {

    public function startup() {
        parent::startup();
        if (!$this->user->isAllowed($this->name, $this->action)) {
            $this->redirect('Sign:in');
        }
    }
}
