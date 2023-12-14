<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

enum OrderStatus: string
{
    case New = 'new';
    case Processing = 'processing';
    case ReadyForPickup = 'ready_for_pickup';
    case Served = 'served';
    case Closed = 'closed';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::New:
                return 'New';
            case self::Processing:
                return 'Processing';
            case self::ReadyForPickup:
                return 'Ready for Pickup';
            case self::Served:
                return 'Served';
            case self::Closed:
                return 'Closed';
            default:
                return '';
        }
    }
}
