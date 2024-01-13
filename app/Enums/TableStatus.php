<?php

namespace App\Enums;

enum TableStatus: string
{
    case Available = 'available';
    case Running = 'running';
    case RunningKOT = 'runningKOT';
    case Printed = 'printed';
    case Paid = 'paid';
    case Unavaliable = 'unavaliable';
}
