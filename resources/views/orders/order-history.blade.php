<x-waiter-layout>
    <div class="container max-w-max px-2 processing-orders">
        <h1 class="text-2xl font-semibold w-full align-top text-center">Orders {{ ucfirst(basename(Request::url()) )}} </h1>
        <div class="flex flex-wrap" id="orders-list">
            @if($orders->isEmpty())
                <p class="text-gray-500 w-full">No order history available.</p>
            @else
                @foreach($orders as $order)
                    <x-order-component-for-waiter :order="$order"/>
                @endforeach
            @endif
        </div>
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
                var pending_order_sync_time = {{ $restaurantDetails->pending_order_sync_time*1000 }}; // 5 seconds
                var pickup_order_sync_time = {{ $restaurantDetails->waiter_sync_time*1000 }} // 5 seconds
               // Check the last segment of the URL
                var lastSegment = window.location.pathname.split('/').pop();

                // Call the appropriate function based on the last segment
                if (lastSegment === 'running') {
                    //setInterval(checkOrderUpdates, 5000);
                } else if (lastSegment === 'ready-for-pickup') {
                    setInterval(checkPickUpOrderUpdates, 5000);
                }
        });

        function markAsServed(orderId){
            $.ajax({
                type: "POST",
                url: "{{ route('waiter.order.mark.as.served',[],false) }}",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                data: {
                    orderId: orderId
                },
                success: function (response) {
                    console.log(response);
                    if(response.status === 'success'){
                        $('#order'+orderId).remove();
                    }else{
                        alert('Something went wrong');
                    }
                }
            });
        }

        // function checkOrderUpdates() {
        //     var lastOrderId = getLastOrderId();
        //     console.log('Last Order Id:', lastOrderId);
        //     $.ajax({
        //         url: "{{ route('sync.pending.orders',[], false) }}",
        //         method: 'GET',
        //         dataType: 'json',
        //         data:{
        //             lastOrderId: lastOrderId
        //         },
        //         success: function (data) {
        //             $("#orders-list").prepend(data.html);
        //         },
        //         error: function (error) {
        //             console.error('Error checking for updates:', error);
        //         }
        //     });
        // }

        function checkPickUpOrderUpdates() {
            var lastOrderId = getLastOrderId();
            $.ajax({
                url: "{{ route('sync.pickup.orders',[], false) }}",
                method: 'GET',
                dataType: 'json',
                data:{
                    lastOrderId: lastOrderId
                },
                success: function (data) {
                    $("#orders-list").prepend(data.html);
                },
                error: function (error) {
                    console.error('Error checking for updates:', error);
                }
            });
        }


        function getLastOrderId() {
            if($("#orders-list").children().length === 1){
                return -1;
            }

            var id =$("#orders-list").children().first().attr('id');
            return id.replace('order','');
        }

    </script>
</x-waiter-layout>
