<div class="max-w-xs bg-slate-400 border border-gray-500 rounded pt-4 m-2 font-bold order-item"
    id="order{{ $order->id }}">
    <div class="flex">
        <div class="w-1/4">
            <p class="mb-2 px-4 order-time">{{ $order->created_at->format('g:i a') }}</p>
            <p class="mb-2 px-4">Table: {{ $order->table_id }}</p>
        </div>
        <div class="w-3/4">
            <p class="mb-2 px-4 waiter-name">{{ $order->waiter->name }}</p>
            <p class="mb-2 px-4 order-type">{{ \App\Enums\OrderType::getDescription($order->order_type) }}</p>
        </div>
    </div>
    <div>
        <p class="mb-2 px-4">KOT: {{ $order->KOT }}</p>
    </div>

    {{-- Loop through order details --}}

    <div class="mt-4 px-4 order-details">
        @foreach ($order->orderDetails as $orderDetail)
            <div class="border-t border-gray-500 pt-2">
                <p class="mb-1 order-name-quantity">{{ $orderDetail->quantity }} x {{ $orderDetail->menu->name }} </p>
            </div>
        @endforeach
    </div>

    {{ $slot }}
</div>
