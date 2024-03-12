<?php

use App\Enums\TableStatus;
use App\Models\Table;

return [
    'notes' => [
        'less Spicy',
        'more spicy',
        'leg peice',
        'extra masala',
        'less masala'
    ],
    'table_colors' => [
        TableStatus::Available->value => 'grey',
        TableStatus::Running->value => 'blue',
        TableStatus::Printed->value => 'green',
        TableStatus::Paid->value => 'yellow',
        TableStatus::RunningKOT->value => 'orange',
        TableStatus::Unavaliable->value => 'red',
    ],
    'printer' => [
        'pos' => env('BILLER_PRINTER', 'biller'),
        'kitchen' => env('KITCHEN_PRINTER', 'biller'),
    ]
];
