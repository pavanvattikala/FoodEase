<?php

namespace App\Enums;

enum OrderType: string
{
    case DineIn = 'dine_in';
    case Takeaway = 'takeaway';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DineIn:
                return 'Dine In';
            case self::Takeaway:
                return 'Takeaway';
            default:
                return '';
        }
    }
}