<?php

namespace App\Forms;

interface NewsFormFactory
{
    public function create(): NewsFormControl;
}