<style>
    .table-box {
        background-color: #5e7bb3;
        display: flex;
        flex-direction: column;
        width: 250px;
        height: 400px;
        justify-content: space-between;
        margin-bottom: 20px;
        margin-right: 20px;
        overflow: scroll;
        scroll-behavior: smooth;
    }

    .table-orders {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        padding: 10px;
        margin-bottom: 10px;
    }

    .table-orders>div {
        margin-bottom: 10px;
    }

    .takeaway-div {
        background-color: #124dc4;
    }



    .takeaway-orders {

        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-bottom: 20px;
        margin-right: 20px;

    }
</style>
@php
    $adminLayout = 'admin-layout';
    $adminOrderComponent = 'order-running-component-for-admin';
    $waiterLayout = 'waiter-layout';
    $waiterOrderComponent = 'order-component-for-waiter';

    $currentLayout = auth()->user()->hasPermission(1) ? $adminLayout : $waiterLayout;

    $currentOrderComponent = auth()->user()->hasPermission(1) ? $adminOrderComponent : $waiterOrderComponent;
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
                {{-- //KOT for tables --}}
                @foreach ($tables as $table)
                    <div class="table-box">
                        <h2 class="text-2xl font-semibold w-full align-top text-center sticky">Table {{ $table->name }}
                        </h2>
                        <div class="table-orders">
                            @foreach ($orders->where('table_id', $table->id) as $order)
                                {{-- choosing dynamic order components based on user permissions --}}
                                <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                            @endforeach
                        </div>
                    </div>
                @endforeach
                {{-- //KOT for pickup --}}
                <div class="takeaway-div">
                    <h2 class="text-2xl font-semibold w-full align-top text-center sticky">Takeaway</h2>
                    <br>

                    <div class="takeaway-orders">
                        @foreach ($orders->where('table_id', null) as $order)
                            {{-- choosing dynamic order components based on user permissions --}}
                            <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                        @endforeach
                    </div>
                </div>


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
        var markAsClosedRoute = "{{ route('order.mark.as.closed', [], false) }}";
    </script>
    <script src="{{ asset('js/order.js') }}"></script>
</x-dynamic-component>
