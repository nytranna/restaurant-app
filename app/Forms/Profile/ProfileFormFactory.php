<?php

namespace App\Forms;

interface ProfileFormFactory
{
    public function create(): ProfileFormControl;
}