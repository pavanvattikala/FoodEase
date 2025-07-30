<x-main-order-component :order="$order">
    <div class="options flex items-center justify-between pt-2">
        <button class="py-3 px-6 font-bold text-white bg-red-600 hover:bg-red-700 transition-colors rounded-bl-lg"
            id="discardOrder" onclick="discardOrder({{ $order->id }})">
            Reject
        </button>
        <button class="py-3 px-6 font-bold text-white bg-green-600 hover:bg-green-700 transition-colors rounded-br-lg"
            id="acceptOrder" onclick="acceptOrder({{ $order->id }})">
            Accept
        </button>
    </div>
</x-main-order-component>
