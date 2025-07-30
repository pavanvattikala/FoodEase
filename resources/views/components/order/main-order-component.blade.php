<div class="bg-slate-700 border border-slate-700 rounded-lg shadow-lg p-4 flex flex-col h-full font-sans text-gray-200 order-item"
    id="order{{ $order->id }}">

    <header class="flex justify-between items-start pb-3 border-b border-slate-600">
        <div>
            <p class="tex]t-xs text-slate-400">KOT</p>
            <p class="font-bold text-lg text-white">{{ $order->KOT }}</p>
        </div>
        <div class="text-right">
            <p class="font-semibold text-white">{{ $order->waiter->name }}</p>
            <p class="text-sm text-slate-400">{{ $order->created_at->format('g:i a') }}</p>
        </div>
    </header>

    <div class="py-3 text-sm">
        <div class="flex justify-between">
            <span class="text-slate-400">For:</span>
            <span class="font-semibold text-white">
                @if ($order->table)
                    Table: {{ $order->table->name }}
                @else
                    {{ \App\Enums\OrderType::getDescription($order->order_type) }}
                @endif
            </span>
        </div>
    </div>


    <main class="flex-grow space-y-2 py-3 border-y border-slate-700">
        @foreach ($order->orderDetails as $orderDetail)
            <div class="flex">
                <p class="font-bold text-lg text-amber-400 mr-3">{{ $orderDetail->quantity }} x</p>
                <p class="text-gray-100">{{ $orderDetail->menu->name }}</p>
            </div>
        @endforeach
    </main>

    <footer class="pt-3">
        @if ($order->special_instructions)
            <div class="mb-3">
                <p class="text-xs font-semibold text-red-400 uppercase">Notes:</p>
                <p class="text-sm text-amber-200 italic">"{{ $order->special_instructions }}"</p>
            </div>
        @endif

        {{-- The slot for dynamic admin/waiter buttons renders here --}}
        <div class="flex items-center justify-center pt-2">
            {{ $slot }}
        </div>
    </footer>
</div>
