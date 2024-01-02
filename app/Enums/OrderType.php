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
    public static function getValueFromDescription(string $description): ?string
    {
        switch ($description) {
            case 'DineIn':
                return self::DineIn->value;
            case 'Takeaway':
                return self::Takeaway->value;
            default:
                return null;
        }
    }
}
