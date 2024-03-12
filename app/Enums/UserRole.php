<?php

namespace App\Enums;

enum UserRole: int
{
    case Admin = 1;
    case Waiter = 2;
    case Kitchen = 3;
    case Biller = 4;
}
