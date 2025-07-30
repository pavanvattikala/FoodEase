<div class="order-item bg-gray-800 text-gray-100 rounded-lg shadow-md flex flex-col h-full font-sans"
    id="order{{ $order->id }}" data-order-type="{{ $order->table ? 'dine_in' : 'take_away' }}">

    <!-- Header -->
    <header class="p-4 border-b border-gray-700">
        <div class="flex justify-between items-start ">
            <div>
                <p class="font-bold text-white order-time">{{ $order->KOT }}</p>
            </div>
            <div class="text-right pl-1">
                <p class="font-semibold text-white waiter-name">{{ $order->waiter->name }}</p>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('g:i a') }}</p>
    </header>

    <!-- Order Details -->
    <main class="p-4 flex-grow order-details">
        <div class="flex justify-left items-center mb-3">
            <span class="text-sm text-gray-400">For:</span>
            <span class="font-semibold text-white order-type">
                @if ($order->table)
                    Table: {{ $order->table->name }}
                @else
                    <span class="text-yellow-400">Take Away</span>
                @endif
            </span>
        </div>

        <div class="space-y-2 order-details-list">
            @foreach ($order->orderDetails as $orderDetail)
                @if ($orderDetail->menu->category->first()->name != 'Drinks')
                    <div class="flex items-center text-lg order-name-quantity">
                        <span class="font-bold text-yellow-400 w-8">{{ $orderDetail->quantity }} x</span>
                        <span class="text-gray-100">{{ $orderDetail->menu->name }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    </main>

    <!-- Footer with Notes and Slotted Buttons -->
    <footer class="p-4 mt-auto border-t border-gray-700">
        @if ($order->special_instructions)
            <div class="mb-3">
                <p class="text-xs font-semibold text-red-400 uppercase">Notes:</p>
                <p class="text-sm text-amber-200 italic">"{{ $order->special_instructions }}"</p>
            </div>
        @endif

        <div class="options flex items-center justify-center pt-2">
            {{ $slot }}
        </div>
    </footer>
</div>
