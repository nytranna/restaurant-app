<?php

namespace App\Forms;

interface ResetFormFactory
{
    public function create(): ResetFormControl;
}