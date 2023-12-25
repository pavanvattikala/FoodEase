<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'table_id',
        'bill_amount',
        'discount',
        'grand_total',
        'payment_method',
        'notes',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'bill_orders')->withTimestamps();
    }
}
