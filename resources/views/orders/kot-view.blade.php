<x-master-layout>
    @section('title', 'KOT View')
    <style>
        #orders-list {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            height: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        #table-orders-div {
            width: 50%;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            border-right: 1px solid #000;
        }

        #takeaway-orders-div {
            width: 50%;
            display: flex;
            justify-content: space-between;
            flex-direction: column;
        }

        .takeaway-orders {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            flex-wrap: wrap;

        }

        #headings {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #5975ac;
        }

        .order-box {
            background-color: #5975ac;
            margin-bottom: 10px;
            min-height: 300px;
            max-height: 450px;
            overflow-y: auto;
            border-radius: 10px;

        }
    </style>
    @php
        $adminOrderComponent = 'order-running-component-for-admin';
        $waiterOrderComponent = 'order-component-for-waiter';

        $user = auth()->user();

        if ($user->isAdmin() || $user->isBiller()) {
            $currentOrderComponent = $adminOrderComponent;
        } else {
            $currentOrderComponent = $waiterOrderComponent;
        }
    @endphp

    <div class="container min-w-full px-2 processing-orders">
        <h1 class="text-2xl font-semibold w-full align-top text-center">Orders
            KOT-VIEW
        </h1>
        <div id="headings">
            <h2 class="text-2xl font-semibold w-full align-top text-center sticky">Table Orders</h2>

            <h2 class="text-2xl font-semibold w-full align-top text-center sticky">Takeaway</h2>
        </div>
        <div id="orders-list">
            @if ($orders->isEmpty())
                <p id="noOrders" class="text-gray-500 w-full">No order history available.</p>
            @else
                {{-- KOT for tables --}}
                <div id="table-orders-div">
                    @foreach ($tables as $table)
                        <div class="order-box">
                            <h2 class="text-2xl font-semibold w-full align-top text-center sticky">Table
                                {{ $table->name }}
                            </h2>
                            <div class="table-order">
                                @foreach ($orders->where('table_id', $table->id) as $order)
                                    {{-- choosing dynamic order components based on user permissions --}}
                                    <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- KOT for pickup --}}

                <div id="takeaway-orders-div">

                    <div class="takeaway-orders">
                        @foreach ($orders->where('table_id', null) as $order)
                            {{-- choosing dynamic order components based on user permissions --}}
                            <div class="order-box">
                                <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                            </div>
                        @endforeach
                    </div>
                </div>


            @endif
        </div>
    </div>
    <script>
        const orderSyncTime = {{ $orderSyncTime }};
        const markAsServedRoute = "{{ route('order.mark.as.served', [], false) }}";
        const markAsPreparedRoute = "{{ route('order.mark.as.prepared', [], false) }}";
        const markAsClosedRoute = "{{ route('order.mark.as.closed', [], false) }}";


        const checkPickUpOrderUpdatesRoute = "{{ route('sync.pickup.orders', [], false) }}";
        const checkOrderUpdatesRoute = "{{ route('sync.pending.orders', [], false) }}";
    </script>
    <script src="{{ asset('js/KOT.js') }}"></script>
</x-master-layout>
