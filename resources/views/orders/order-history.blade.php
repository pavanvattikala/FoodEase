<x-waiter-layout>
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold w-full mb-4">Order History</h1>

        @if($orders->isEmpty())
            <p class="text-gray-500 w-full">No order history available.</p>
        @else
            @foreach($orders as $order)
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-4 mb-4">
                    <div class="max-w-md mx-auto bg-slate-400 border border-gray-500 rounded ">
                        <h2 class="text-xl font-semibold mb-2 px-4 pt-4 bg-green-700">Order ID: {{ $order->id }}</h2>
                        <p class="mb-2 px-4">Order Total: Rs {{ $order->total }}</p>
                        <p class="mb-2 px-4">Order Status: {{ \App\Enums\OrderStatus::getDescription($order->status) }}</p>
                        <p class="mb-2 px-4">Order Type: {{ \App\Enums\OrderType::getDescription($order->order_type) }}</p>

                        {{-- Loop through order details --}}
                        <div class="mt-4 px-4">
                            <h3 class="text-lg font-semibold mb-2">Order Details:</h3>
                            @foreach($order->orderDetails as $orderDetail)
                                <div class="border-t border-gray-300 pt-2">
                                    <p class="mb-1">Item: {{ $orderDetail->menu->name }}</p>
                                    <p class="mb-1">Quantity: {{ $orderDetail->quantity }}</p>
                                    {{-- Add more details as needed --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-waiter-layout>
