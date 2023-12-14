<x-waiter-layout>
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Order History</h1>

        @if($orders->isEmpty())
            <p class="text-gray-500">No order history available.</p>
        @else
            @foreach($orders as $order)
                <div class="mb-8 p-4 border border-gray-300 rounded">
                    <h2 class="text-xl font-semibold mb-2">Order ID: {{ $order->id }}</h2>
                    <p class="mb-2">Order Total: Rs {{ $order->total }}</p>
                    <p class="mb-2">Order Status: {{ \App\Enums\OrderStatus::getDescription($order->status) }}</p>
                    <p class="mb-2">Order Type: {{ \App\Enums\OrderType::getDescription($order->order_type) }}</p>

                    {{-- Loop through order details --}}
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Order Details:</h3>
                        @foreach($order->orderDetails as $orderDetail)
                            <div class="ml-4 border-t border-gray-300 pt-2">
                                <p class="mb-1">Item: {{ $orderDetail->menu->name }}</p>
                                <p class="mb-1">Quantity: {{ $orderDetail->quantity }}</p>
                                {{-- Add more details as needed --}}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-waiter-layout>
