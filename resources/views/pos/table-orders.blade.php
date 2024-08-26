<x-pos-layout>
    @section('title', 'View Table Orders')
    <style>
        .waiter-orders {
            display: flex;
            flex-wrap: wrap;
        }
    </style>

    @php
        $currentOrderComponent = 'order-running-component-for-admin';
    @endphp
    <div class="container">

        @php
            $ordersByWaiter = $orders->where('table_id', $table->id)->groupBy('waiter_id');
        @endphp

        <div class="table-box">
            <h2 class="text-2xl font-semibold w-full align-top text-center sticky">Unbilled Order Table
                {{ $table->name }}</h2>
            <input type="text" name="tableId" value="{{ $table->id }}" hidden>
            <div class="table-orders flex gap-4">
                @foreach ($ordersByWaiter as $waiterId => $orders)
                    <div class="waiter-orders">
                        @foreach ($orders as $order)
                            <x-dynamic-component :component="$currentOrderComponent" :order="$order" />
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>


    </div>

</x-pos-layout>
