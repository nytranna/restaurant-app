<?php

namespace App\Forms;

interface MenuCategoryFormFactory
{
    public function create(): MenuCategoryFormControl;
}