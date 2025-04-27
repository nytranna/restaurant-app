<?php

namespace App\Forms;

interface MenuItemFormFactory
{
    public function create(): MenuItemFormControl;
}