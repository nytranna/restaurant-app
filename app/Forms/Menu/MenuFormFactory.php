<?php

namespace App\Forms;

interface MenuFormFactory
{
    public function create(): MenuFormControl;
}