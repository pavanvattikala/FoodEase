<x-main-order-component :order="$order">
    @if ($order->status == 'processing')
        <div class="flex justify-center options">
            <button class="bg-green-600 w-full m-2 p-2 rounded " id="markAsServed"
                onclick="markAsServed({{ $order->id }})">Mark As Served</button>
        </div>
    @elseif ($order->status == 'ready for pickup')
        <div class="flex justify-center options">
            <button class="bg-green-600 w-full m-2 p-2 rounded " id="markAsServed"
                onclick="markAsServed({{ $order->id }})">Mark As Served</button>
        </div>
    @endif
</x-main-order-component>
