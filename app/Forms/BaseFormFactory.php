<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class BaseFormFactory {

    public function __construct() {
        
    }

    public function create(): Form {
        $form = new Form;
        $form->onRender[] = [$this, 'renderer'];

        return $form;
    }


    function renderer(Form $form): void {
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = null;
        $renderer->wrappers['pair']['container'] = 'div class="mb-3 row"';
        $renderer->wrappers['pair']['.error'] = 'has-danger';
        $renderer->wrappers['control']['container'] = 'div class="col-sm-9"';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 col-form-label"';
        $renderer->wrappers['control']['description'] = 'div class="form-text"';
        $renderer->wrappers['control']['errorcontainer'] = 'div class="invalid-feedback"';
        $renderer->wrappers['control']['.error'] = 'is-invalid';

        foreach ($form->getControls() as $control) {
            $type = $control->getOption('type');
            if ($type === 'button') {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-secondary');
                $usedPrimary = true;
            } elseif (in_array($type, ['text', 'textarea', 'select', 'email', 'password'], true)) {
                $control->getControlPrototype()->addClass('form-control');
            } elseif ($type === 'file') {
                $control->getControlPrototype()->addClass('form-control');
            } elseif (in_array($type, ['checkbox', 'radio'], true)) {
                if ($control instanceof \Nette\Forms\Controls\Checkbox) {
                    $control->getLabelPrototype()->addClass('form-check-label');
                    $control->getControlPrototype()->addClass('form-check-input');
                } else {
                    $control->getItemLabelPrototype()->addClass('form-check-label');
                    $control->getControlPrototype()->addClass('form-check-input');
                }
                $control->getSeparatorPrototype()->setName('div')->addClass('form-check');
            }
        }
    }
}
