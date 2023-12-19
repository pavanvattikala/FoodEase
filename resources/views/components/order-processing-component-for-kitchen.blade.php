
<x-main-order-component :order="$order">
    <div class="flex justify-center options">
        <button class="m-2 p-2 rounded bg-green-600 w-full" id="completeOrder" onclick="completeOrder({{ $order->id }})">Completed</button>
    </div>  
</x-main-order-component>


