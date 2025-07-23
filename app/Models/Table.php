<?php

namespace App\Models;

use App\Enums\TableStatus;
use App\Models\TableLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    protected static function refreshTables()
    {
        return self::all();
    }

    public static function getCachedTables()
    {
        return Cache::rememberForever('tables', function () {
            return self::refreshTables();
        });
    }

    public static function refreshAndCacheTables()
    {
        Cache::forget('tables');
        self::getCachedTables();
    }

    public function save(array $options = [])
    {
        $saved = parent::save($options);
        self::refreshAndCacheTables();
        return $saved;
    }

    public function delete()
    {
        $deleted = parent::delete();
        self::refreshAndCacheTables();
        return $deleted;
    }
}
