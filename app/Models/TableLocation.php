<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TableLocation extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function refreshTableLocations()
    {
        return self::all();
    }

    public static function getCachedTableLocations()
    {
        return Cache::rememberForever('tableLocations', function () {
            return self::refreshTableLocations();
        });
    }

    public static function refreshAndCacheTableLocations()
    {
        Cache::forget('tableLocations');
        self::getCachedTableLocations();
    }


    public function save(array $options = [])
    {
        $saved = parent::save($options);
        self::refreshAndCacheTableLocations();
        return $saved;
    }

    public function delete()
    {
        $deleted = parent::delete();
        self::refreshAndCacheTableLocations();
        return $deleted;
    }
}
