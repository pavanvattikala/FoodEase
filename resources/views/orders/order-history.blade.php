<x-waiter-layout>
    <div class="container max-w-max px-2 processing-orders">
        <h1 class="text-2xl font-semibold w-full align-top text-center">Orders {{ ucfirst(basename(Request::url())) }}
        </h1>
        <div class="flex flex-wrap" id="orders-list">
            @if ($orders->isEmpty())
                <p id="noOrders" class="text-gray-500 w-full">No order history available.</p>
            @else
                @foreach ($orders as $order)
                    <x-order-component-for-waiter :order="$order" />
                @endforeach
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // Check the last segment of the URL
            var lastSegment = window.location.pathname.split('/').pop();

            // Call the appropriate function based on the last segment
            if (lastSegment === 'running') {
                //setInterval(checkOrderUpdates, 5000);
            } else if (lastSegment === 'ready-for-pickup') {
                @isset($waiterSyncTime)
                    var waiterSyncTime = {{ $waiterSyncTime }};
                @endisset
                setInterval(checkPickUpOrderUpdates, waiterSyncTime);
            }
        });

        function markAsServed(orderId) {
            $.ajax({
                type: "POST",
                url: "{{ route('order.mark.as.served', [], false) }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    orderId: orderId
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        $('#order' + orderId).remove();
                    } else {
                        alert('Something went wrong');
                    }
                }
            });
        }

        // function checkOrderUpdates() {
        //     var lastOrderId = getLastOrderId();
        //     console.log('Last Order Id:', lastOrderId);
        //     $.ajax({
        //         url: "{{ route('sync.pending.orders', [], false) }}",
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
                url: "{{ route('sync.pickup.orders', [], false) }}",
                method: 'GET',
                dataType: 'json',
                data: {
                    lastOrderId: lastOrderId
                },
                success: function(data) {
                    $("#orders-list").prepend(data.html);
                },
                error: function(error) {
                    console.error('Error checking for updates:', error);
                }
            });
        }


        function getLastOrderId() {
            if ($("#noOrders").length === 1) {
                return -1;
            }

            var id = $("#orders-list").children().first().attr('id');
            return id.replace('order', '');
        }
    </script>
</x-waiter-layout>
