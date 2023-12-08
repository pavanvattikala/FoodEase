<?php

namespace App\Listeners;

use App\Events\OrderSubmittedToKitchen;
use GuzzleHttp\Promise\Create;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use App\Models\Menu;

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
        dd($event->cart);
    }
}
