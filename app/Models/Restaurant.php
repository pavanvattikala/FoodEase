<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'address',
        'phone',
        'email',
        'website',
        'GST',
        'pending_order_sync_time',
        'waiter_sync_time',
        'minimum_delivery_time',
        'minimum_preparation_time',
        'order_live_view',
        'kot_live_view',
    ];
}
