<x-main-order-component :order="$order">
    @if ($order->status == App\Enums\OrderStatus::New || $order->status == App\Enums\OrderStatus::Processing)
        <div class="flex justify-center options">
            <button class="bg-green-600 w-full m-2 p-2 rounded " id="markAsPrepared"
                onclick="markAsPrepared({{ $order->id }})">Mark As Prepared</button>
        </div>
    @endif

    @if ($order->table_id)
        @if ($order->status == App\Enums\OrderStatus::ReadyForPickup)
            <div class="flex justify-center options">
                <button class="bg-green-600 w-full m-2 p-2 rounded " id="markAsServed"
                    onclick="markAsServed({{ $order->id }})">Mark As Served</button>
            </div>
        @endif
    @else
        @if ($order->status == App\Enums\OrderStatus::ReadyForPickup)
            <div class="flex justify-center options">
                <button class="bg-green-600 w-full m-2 p-2 rounded " id="markAsServed"
                    onclick="markAsClosed({{ $order->id }})">Mark As Closed</button>
            </div>
        @endif
    @endif




</x-main-order-component>
