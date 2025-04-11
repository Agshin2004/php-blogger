<?php


namespace App;

class PaymentService
{
    public function process(): bool
    {
        echo 'Paid';

        return true;
    }
}