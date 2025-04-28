<?php

namespace App\Forms;

interface CustomerReservationFormFactory
{
    public function create(): CustomerReservationFormControl;
}