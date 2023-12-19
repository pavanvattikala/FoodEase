
<x-main-order-component :order="$order">
    <div class="m-2 flex justify-center options flex-wrap">
        <button class="m-2 p-2 rounded bg-green-600 w-full" id="acceptOrder" onclick="acceptOrder({{ $order->id }})">Accept</button>
        <button class="m-2 mr-2 p-2 rounded w-full" id="discardOrder" style="background-color: red" onclick="discardOrder({{ $order->id }})">Reject</button>
    </div> 
</x-main-order-component>

