<div class="max-w-xs bg-slate-400 border border-gray-500 rounded pt-4 m-2 font-bold" id="order{{$order->id}}">
    <div class="flex">
        <div class="w-1/4">
            <p class="mb-2 px-4">{{ $order->created_at->format('g:i a') }}</p>
            <p class="mb-2 px-4">Table: {{ $order->table_id }}</p>
        </div>
        <div class="w-3/4">
            <p class="mb-2 px-4">{{ $order->waiter->name }}</p>
            <p class="mb-2 px-4">{{ \App\Enums\OrderType::getDescription($order->order_type) }}</p>                  
        </div>                        
    </div>
    <div>
        <p class="mb-2 px-4">KOT: {{ $order->KOT }}</p>
    </div>
    
    {{-- Loop through order details --}}

    <div class="mt-4 px-4">
        @foreach($order->orderDetails as $orderDetail)
            <div class="border-t border-gray-500 pt-2">
                <p class="mb-1">{{ $orderDetail->quantity }} x {{ $orderDetail->menu->name }} </p>
            </div>
        @endforeach
    </div>
    <div class="flex justify-center options">
        <button class="m-2 p-2 rounded" id="completeOrder" onclick="completeOrder({{ $order->id }})">✅</button>
    </div>                     
</div>
