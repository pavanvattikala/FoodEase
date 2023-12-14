<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'KOT',
        'total',
        'table_id',
        'status',
        'special_instructions',
        'order_type',
        'waiter_id',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'order_type' => OrderType::class,
    ];

    public function table()
    {
        return $this->belongsTo(Table::class, 'tableNo');
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
