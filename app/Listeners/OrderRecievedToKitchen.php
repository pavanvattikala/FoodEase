<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\TableStatus;
use App\Events\OrderSubmittedToKitchen;
use App\Helpers\KitchenHelper;
use GuzzleHttp\Promise\Create;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use Carbon\Carbon;

class OrderRecievedToKitchen
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderSubmittedToKitchen $event)
    {
    }
}
