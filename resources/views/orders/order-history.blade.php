@if (auth()->user()->hasPermission(1))
    <x-admin-layout>
        <div class="container max-w-max px-2 processing-orders">
            <h1 class="text-2xl font-semibold w-full align-top text-center">Orders
                {{ ucfirst(basename(Request::url())) }}
            </h1>
            <div class="flex flex-wrap" id="orders-list">
                @if ($orders->isEmpty())
                    <p id="noOrders" class="text-gray-500 w-full">No order history available.</p>
                @else
                    @foreach ($orders as $order)
                        <x-order-component-for-waiter :order="$order" />
                    @endforeach
                @endif
            </div>
        </div>
        <script>
            @isset($waiterSyncTime)
                var waiterSyncTime = {{ $waiterSyncTime }};
            @endisset
            var markAsServedRoute = "{{ route('order.mark.as.served', [], false) }}";
            var checkPickUpOrderUpdatesRoute = "{{ route('sync.pickup.orders', [], false) }}";
            var checkOrderUpdatesRoute = "{{ route('sync.pending.orders', [], false) }}";
        </script>
        <script src="{{ asset('js/order.js') }}"></script>
    </x-admin-layout>
@else
    <x-waiter-layout>
        <div class="container max-w-max px-2 processing-orders">
            <h1 class="text-2xl font-semibold w-full align-top text-center">Orders
                {{ ucfirst(basename(Request::url())) }}
            </h1>
            <div class="flex flex-wrap" id="orders-list">
                @if ($orders->isEmpty())
                    <p id="noOrders" class="text-gray-500 w-full">No order history available.</p>
                @else
                    @foreach ($orders as $order)
                        <x-order-component-for-waiter :order="$order" />
                    @endforeach
                @endif
            </div>
        </div>
        <script>
            @isset($waiterSyncTime)
                var waiterSyncTime = {{ $waiterSyncTime }};
            @endisset
            var markAsServedRoute = "{{ route('order.mark.as.served', [], false) }}";
            var checkPickUpOrderUpdatesRoute = "{{ route('sync.pickup.orders', [], false) }}";
            var checkOrderUpdatesRoute = "{{ route('sync.pending.orders', [], false) }}";
        </script>
        <script src="{{ asset('js/order.js') }}"></script>
    </x-waiter-layout>
@endif
