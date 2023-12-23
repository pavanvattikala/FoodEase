<x-main-order-component :order="$order" >
    @if (Route::currentRouteName() == 'waiter.orders.ready.for.pickup' || Route::currentRouteName() == 'sync.pickup.orders')
    <div class="flex justify-center options">
        <button class="bg-green-600 w-full m-2 p-2 rounded " id="markAsServed" onclick="markAsServed({{ $order->id }})">Mark As Served</button>
    </div> 
    @endif
</x-main-order-component>