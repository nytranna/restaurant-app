<?php

namespace App\Forms;

interface ImageFormFactory
{
    public function create(): ImageFormControl;
}