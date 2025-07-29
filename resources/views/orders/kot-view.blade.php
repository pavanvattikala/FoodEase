<x-master-layout>
    @section('title', 'KOT View')

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

    <div class="min-h-screen bg-gray-100 p-4">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Kitchen Order Tickets (KOT)</h1>
            <p class="text-gray-500">Live Orders in Progress</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="flex flex-col space-y-4">
                <h2
                    class="text-2xl font-semibold text-gray-700 text-center bg-white p-3 rounded-lg shadow-sm sticky top-0 z-10">
                    <i class="fas fa-utensils mr-2"></i> Table Orders
                </h2>
                <div class="space-y-6">
                    @php
                        $tableOrders = $orders->where('table_id', '!=', null)->groupBy('table_id');
                    @endphp

                    @forelse ($tableOrders as $tableId => $ordersForTable)
                        <div class="bg-white p-4 rounded-xl shadow-lg">
                            <h3 class="text-xl font-bold text-blue-600 mb-3">Table
                                {{ $tables->find($tableId)->name ?? 'N/A' }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach ($ordersForTable as $order)
                                    <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-500">
                            <p>No active table orders.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col space-y-4">
                <h2
                    class="text-2xl font-semibold text-gray-700 text-center bg-white p-3 rounded-lg shadow-sm sticky top-0 z-10">
                    <i class="fas fa-shopping-bag mr-2"></i> Takeaway Orders
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @php
                        $takeawayOrders = $orders->where('table_id', null);
                    @endphp

                    @forelse ($takeawayOrders as $order)
                        <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                    @empty
                        <div class="col-span-full text-center py-10 text-gray-500">
                            <p>No active takeaway orders.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Your existing scripts can remain here --}}
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
