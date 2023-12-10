<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

class KitchenHelper{

    public static function generateKOT()
    {
        $uuid = Uuid::uuid4();
        $date = now()->format('Ymd');
        $uuid_string = $uuid->toString();

        return "KOT-$date-$uuid_string";
    }
}

?>

