<?php

namespace App\Forms;

interface ResetPasswordFormFactory
{
    public function create(): ResetPasswordFormControl;
}