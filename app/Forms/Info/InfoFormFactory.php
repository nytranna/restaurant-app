<?php

namespace App\Forms;

interface InfoFormFactory
{
    public function create(): InfoFormControl;
}