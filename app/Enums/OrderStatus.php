<?php
namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case Processing = 'processing';
    case ReadyForPickup = 'ready_for_pickup';
    case Served = 'served';
    case Closed = 'closed';
}