
<x-main-order-component :order="$order">
    <div class="flex justify-center options">
        <button class="m-2 p-2 rounded" id="completeOrder" onclick="completeOrder({{ $order->id }})">✅</button>
    </div>  
</x-main-order-component>


