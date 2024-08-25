<?php

namespace App\Models;

use App\Enums\TableStatus;
use App\Models\TableLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Table extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'guest_number', 'status', 'table_location', 'taken_at'];

    protected $casts = [
        'status' => TableStatus::class,
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function location()
    {
        return $this->belongsTo(TableLocation::class, 'table_location');
    }
}
