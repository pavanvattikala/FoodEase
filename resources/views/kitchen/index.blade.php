<x-kitchen-layout>
    <div class="container flex flex-row">
        <div class="container max-w-xs px-4 pending-orders">
            <h1 class="text-2xl font-semibold w-full text-center mb-4">Pending Orders</h1>
            @if($orders->isEmpty())
                <p class="text-gray-500 w-full">No order available.</p>
            @else
                @foreach($orders as $order)
                    <x-new-order-component-for-kitchen :order="$order" />
                @endforeach
            @endif
        </div>
        <div class="container max-w-max px-2 processing-orders">
            <h1 class="text-2xl font-semibold w-full align-top text-center">Current Orders</h1>
    
            @if($orders->isEmpty())
                <p class="text-gray-500 w-full">No order available.</p>
            @else
                <div class="flex flex-wrap">
                    @foreach($orders as $order)
                        <x-order-processing-component-for-kitchen :order="$order" />
                    @endforeach
                </div>
            @endif
        </div>
        <div class="container max-w-xs items-counts">
            <h1 class="text-2xl font-semibold w-full mb-4">Orders Count</h1>
            @if($orders->isEmpty())
                <p class="text-gray-500 w-full">No order available.</p>
            @else
                    {{-- <div class="max-w-max bg-slate-400 border border-gray-500 rounded pt-4 m-2 font-bold">
                        <div class="border-t border-gray-500 pt-2">
                            <p class="mb-1">{{ $orderDetail->quantity }} x {{ $orderDetail->menu->name }} </p>
                        </div>

                        <div class="border-t border-gray-500 pt-2">
                            <p class="mb-1">{{ $orderDetail->quantity }} x {{ $orderDetail->menu->name }} </p>
                        </div>
  
                    </div> --}}
            @endif
        </div>
    </div>
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.Echo.channel('orderSubmitted')
                .listen('OrderSubmittedToKitchen', (event) => {
                    console.log('Order submitted to kitchen:', event.cart);
                    // Handle the event data as needed
                });
        });

        $('#acceptOrder').on('click', function () {
            console.log('Order accepted');
            $.ajax({
                url: '/kitchen/accept',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: '{{ $order->id }}'
                },
                success: function (response) {
                    console.log(response);
                }
            })
        });

        $('#discardOrder').on('click', function () {
            console.log('Order discarded');
            // Handle the event data as needed
        });

    </script>
</x-kitchen-layout>
