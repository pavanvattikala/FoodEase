<?php

namespace App\Enums;

enum PaymentMethods: string
{
    case UPI = 'upi';
    case CASH = 'cash';
    case CARD = 'card';
}
