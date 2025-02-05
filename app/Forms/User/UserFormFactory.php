<?php

namespace App\Forms;

interface UserFormFactory
{
    public function create(): UserFormControl;
}