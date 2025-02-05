<?php

namespace App\Forms;

interface SignInFormFactory
{
    public function create(): SignInFormControl;
}