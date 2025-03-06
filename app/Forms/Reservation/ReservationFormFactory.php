<?php

namespace App\Forms;

interface ReservationFormFactory
{
    public function create(): ReservationFormControl;
}