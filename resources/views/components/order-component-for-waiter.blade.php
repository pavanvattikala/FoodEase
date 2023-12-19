<x-main-order-component :order="$order" >
    @if (Request::url()  == route('waiter.orders.ready.for.pickup') )
    <div class="flex justify-center options">
        <button class="m-2 p-2 rounded" id="markAsServed" onclick="markAsServed({{ $order->id }})">✅</button>
    </div> 
    @endif
</x-main-order-component>