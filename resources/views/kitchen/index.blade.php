<x-kitchen-layout>
    <div class="container flex flex-row">
        <div class="container max-w-xs px-4 pending-orders">
            <h1 class="text-2xl font-semibold w-full text-center mb-4">Pending Orders</h1>
            @if($newOrders->isEmpty())
                <p class="text-gray-500 w-full">No order available.</p>
            @else
                @foreach($newOrders as $order)
                    <x-new-order-component-for-kitchen :order="$order" />
                @endforeach
            @endif
        </div>
        <div class="container max-w-max px-2 processing-orders">
            <h1 class="text-2xl font-semibold w-full align-top text-center">Current Orders</h1>
    
            @if($processingOrders->isEmpty())
                <p class="text-gray-500 w-full">No order available.</p>
            @else
                <div class="flex flex-wrap">
                    @foreach($processingOrders as $order)
                        <x-order-processing-component-for-kitchen :order="$order" />
                    @endforeach
                </div>
            @endif
        </div>
        {{-- <div class="container max-w-xs items-counts">
            <h1 class="text-2xl font-semibold w-full mb-4">Orders Count</h1>
            @if($orders->isEmpty())
                <p class="text-gray-500 w-full">No order available.</p>
            @else
                    <div class="max-w-max bg-slate-400 border border-gray-500 rounded pt-4 m-2 font-bold">
                        <div class="border-t border-gray-500 pt-2">
                            <p class="mb-1">{{ $orderDetail->quantity }} x {{ $orderDetail->menu->name }} </p>
                        </div>

                        <div class="border-t border-gray-500 pt-2">
                            <p class="mb-1">{{ $orderDetail->quantity }} x {{ $orderDetail->menu->name }} </p>
                        </div>
  
                    </div>
            @endif
        </div> --}}
    </div>
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.Echo.channel('orderSubmitted')
                .listen('OrderSubmittedToKitchen', (event) => {
                    console.log('KOT submitted to kitchen:', event.kot);
                    const url = '{{ route('kitchen.get.new.order.component',[],false) }}';
                    var csrf_token = "{{ csrf_token()  }}";

                    setTimeout(function() {
                        $.ajax({
                            type: "POST",
                            url: url,
                            headers: { 'X-CSRF-TOKEN': csrf_token },
                            data: { kot: event.kot },
                            contentType: 'application/x-www-form-urlencoded',
                            success: function (response) {
                                $('.pending-orders').append(response.html);
                                console.log('Order added');
                            },
                            error: function (error) {
                                console.error('Error ', error);
                            }
                        });
                    }, 5000); // 5 seconds
                });
        });

        function acceptOrder(orderId){
            
            const url = '{{ route('kitchen.accept.order',[],false) }}';
            var csrf_token = "{{ csrf_token()  }}";
            $.ajax({
                type: "POST",
                url: url,
                headers: { 'X-CSRF-TOKEN': csrf_token },
                data:{orderId:orderId},
                contentType:'application/x-www-form-urlencoded',
                success: function (response) {
                    var order = $('#order'+orderId).clone();
                    order.find('.options').remove();
                    $('.processing-orders > .flex').append(order);
                    $('#order' + orderId).remove();

                    console.log('Order accepted');
                },
                error: function (error) {
                    console.error('Error ', error);
                }
            });
        }


        function discardOrder(orderId){
            
            const url = '{{ route('kitchen.discard.order',[],false) }}';
            var csrf_token = "{{ csrf_token()  }}";
            $.ajax({
                type: "POST",
                url: url,
                headers: { 'X-CSRF-TOKEN': csrf_token },
                data:{orderId:orderId},
                contentType:'application/x-www-form-urlencoded',
                success: function (response) {
                    $('#order' + orderId).remove();

                    console.log('Order discarded');
                },
                error: function (error) {
                    console.error('Error ', error);
                }
            });
        }

    </script>
</x-kitchen-layout>
