@php
    $adminLayout = 'admin-layout';
    $adminOrderComponent = 'order-running-component-for-admin';
    $waiterLayout = 'waiter-layout';
    $waiterOrderComponent = 'order-component-for-waiter';

    $currentLayout = auth()
        ->user()
        ->hasPermission(1)
        ? $adminLayout
        : $waiterLayout;

    $currentOrderComponent = auth()
        ->user()
        ->hasPermission(1)
        ? $adminOrderComponent
        : $waiterOrderComponent;
@endphp
{{-- choosing dynamic layouts based on user permissions --}}
<x-dynamic-component :component="$currentLayout">
    <div class="container max-w-max px-2 processing-orders">
        <h1 class="text-2xl font-semibold w-full align-top text-center">Orders
            {{ ucfirst(basename(Request::url())) }}
        </h1>
        <div class="flex flex-wrap" id="orders-list">
            @if ($orders->isEmpty())
                <p id="noOrders" class="text-gray-500 w-full">No order history available.</p>
            @else
                @foreach ($orders as $order)
                    {{-- choosing dynamic order components based on user permissions --}}
                    <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
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
        var markAsPreparedRoute = "{{ route('order.mark.as.prepared', [], false) }}";
    </script>
    <script src="{{ asset('js/order.js') }}"></script>
</x-dynamic-component>
