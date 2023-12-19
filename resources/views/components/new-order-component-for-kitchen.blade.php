
<x-main-order-component :order="$order">
    <div class="flex justify-center options">
        <button class="m-2 p-2 rounded" id="acceptOrder" onclick="acceptOrder({{ $order->id }})">✅</button>
        <button class="m-2 p-2 rounded" id="discardOrder" onclick="discardOrder({{ $order->id }})">❌</button>
    </div> 
</x-main-order-component>

