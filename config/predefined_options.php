<?php

use App\Enums\TableStatus;

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
        TableStatus::RunningKOT->value => 'orange'
    ],
    'printer' => [
        'pos' => 'home',
    ]
];
