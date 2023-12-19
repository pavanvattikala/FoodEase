<x-waiter-layout>
    <div class="container max-w-max px-2 processing-orders">
        <h1 class="text-2xl font-semibold w-full align-top text-center">Current Orders</h1>
        <div class="flex flex-wrap">
            @if($orders->isEmpty())
                <p class="text-gray-500 w-full">No order history available.</p>
            @else
                @foreach($orders as $order)
                    <x-order-component-for-waiter :order="$order" />
                @endforeach
            @endif
        </div>
    </div>
</x-waiter-layout>
