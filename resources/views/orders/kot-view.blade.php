<x-master-layout>
    @section('title', 'KOT View')

    @php
        $user = auth()->user();

        if ($user->isAdmin() || $user->isBiller()) {
            $currentOrderComponent = 'order-running-component-for-admin';
        } else {
            $currentOrderComponent = 'order-component-for-waiter';
        }

        $isKitchenStaff = !$user->isWaiter();

        // Pre-process collections in the controller section for cleaner views
        $tableOrders = $orders->where('table_id', '!=', null)->groupBy('table_id');
        $takeawayOrders = $orders->where('table_id', null);
    @endphp

    <div class="min-h-screen bg-gray-100 p-4">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Kitchen Order Tickets (KOT)</h1>
            <p class="text-gray-500">Live Orders in Progress</p>
        </div>

        <div class="grid grid-cols-1 @if ($isKitchenStaff) lg:grid-cols-2 @endif lg:items-start gap-8">

            <div class="flex flex-col space-y-4">
                <h2
                    class="text-2xl font-semibold text-gray-700 text-center bg-white p-3 rounded-lg shadow-sm sticky top-0 z-10">
                    <i class="fas fa-utensils mr-2"></i> Table Orders
                </h2>
                <div class="space-y-6">

                    @if ($tableOrders->isNotEmpty())
                        @foreach ($tableOrders as $tableId => $ordersForTable)
                            <div class="bg-white p-4 rounded-xl shadow-lg">
                                <h3 class="text-xl font-bold text-blue-600 mb-3">Table
                                    {{ $tables->find($tableId)->name ?? 'N/A' }}</h3>
                                <div class="flex flex-wrap -m-2">
                                    @foreach ($ordersForTable as $order)
                                        <div
                                            class="w-full sm:w-1/2 @if ($isKitchenStaff) lg:w-full xl:w-1/2 @endif p-2">
                                            <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-10 text-gray-500 bg-white rounded-lg shadow">
                            <p>No active table orders.</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($isKitchenStaff)
                <div class="flex flex-col space-y-4">
                    <h2
                        class="text-2xl font-semibold text-gray-700 text-center bg-white p-3 rounded-lg shadow-sm sticky top-0 z-10">
                        <i class="fas fa-shopping-bag mr-2"></i> Takeaway Orders
                    </h2>

                    @if ($takeawayOrders->isNotEmpty())
                        <div class="bg-white p-4 rounded-xl shadow-lg">

                            <div class="flex flex-wrap -m-2">
                                @foreach ($takeawayOrders as $order)
                                    <div class="w-full sm:w-1/2 lg:w-1/3 p-2 min-w-fit">
                                        <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="w-full text-center py-10 text-gray-500 bg-white rounded-lg shadow">
                            <p>No active takeaway orders.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        const orderSyncTime = {{ $orderSyncTime ?? 30 }};
        const markAsServedRoute = "{{ route('order.mark.as.served', [], false) }}";
        const markAsPreparedRoute = "{{ route('order.mark.as.prepared', [], false) }}";
        const markAsClosedRoute = "{{ route('order.mark.as.closed', [], false) }}";
        const checkPickUpOrderUpdatesRoute = "{{ route('sync.pickup.orders', [], false) }}";
        const checkOrderUpdatesRoute = "{{ route('sync.pending.orders', [], false) }}";
    </script>
    <script src="{{ asset('js/KOT.js') }}"></script>

</x-master-layout>
