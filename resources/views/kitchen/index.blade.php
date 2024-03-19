<x-kitchen-layout>
    <style>
        .pending-orders {
            min-width: 20%;
            max-width: 20%;

        }

        .processing-orders {
            min-width: 60%;

            max-width: 60%;

        }

        .items-counts {
            min-width: 20%;

            max-width: 20%;

        }
    </style>
    <x-user-dropdown />
    <div class="container flex flex-row">

        <div class="container  px-4 pending-orders">
            <h1 class="text-2xl font-semibold w-full text-center mb-4">Pending Orders</h1>
            @if ($newOrders->isEmpty())
                <p class="text-gray-500 w-full">No order available.</p>
            @else
                @foreach ($newOrders as $order)
                    <x-new-order-component-for-kitchen :order="$order" />
                @endforeach
            @endif
        </div>
        <div class="container px-2 processing-orders">
            <h1 class="text-2xl font-semibold w-full align-top text-center">Current Orders</h1>
            <div class="flex flex-wrap">
                @if ($processingOrders->isEmpty())
                    <p class="text-gray-500 w-full">No order available.</p>
                @else
                    @foreach ($processingOrders as $order)
                        <x-order-processing-component-for-kitchen :order="$order" />
                    @endforeach

                @endif
            </div>
        </div>
        <div class="items-counts">
            <h1 class="text-2xl font-semibold w-full align-top text-center">Items Count</h1>
            <div class="flex flex-col pt-4">

                <div class="dine-in max-w-max bg-slate-400 border border-gray-500 rounded pt-4 m-2 font-bold">
                    <h1 class="text-2xl font-semibold">Dine in Orders</h1>
                    <div class="border-t border-gray-500 p-2 items">
                        <p class=" mb-1 order-name-quantity"> </p>
                    </div>
                </div>
                <div class="take-away max-w-max bg-slate-400 border border-gray-500 rounded pt-4 m-2 font-bold">
                    <h1 class="text-2xl font-semibold">Takeaway Orders</h1>
                    <div class="border-t border-gray-500 p-2 items">
                        <p class="mb-1 order-name-quantity"> </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.Echo.channel('order-submitted-to-kitchen')
                .listen('.OrderSubmittedToKitchen', (event) => {
                    console.log('KOT submitted to kitchen:', event.kot);
                    const url = '{{ route('kitchen.get.new.order.component', [], false) }}';
                    var csrf_token = "{{ csrf_token() }}";

                    setTimeout(function() {
                        $.ajax({
                            type: "POST",
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': csrf_token
                            },
                            data: {
                                kot: event.kot
                            },
                            contentType: 'application/x-www-form-urlencoded',
                            success: function(response) {
                                $('.pending-orders').append(response.html);
                                console.log('Order added');
                            },
                            error: function(error) {
                                console.error('Error ', error);
                            }
                        });
                    }, 5000); // 5 seconds
                });

            calculateMenuSumByType();
        });

        function acceptOrder(orderId) {

            const url = '{{ route('kitchen.accept.order', [], false) }}';
            var csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                data: {
                    orderId: orderId
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    var order = $('.pending-orders > #order' + orderId).clone();
                    order.find('.options').children().last().remove();
                    var firstChilderen = order.find('.options').children().first();
                    firstChilderen.attr("id", "completeOrder");
                    firstChilderen.text("Completed");
                    firstChilderen.attr("onclick", "completeOrder(" + orderId + ")");
                    $('.processing-orders > .flex').append(order);
                    $('.pending-orders > #order' + orderId).remove();
                    console.log('Order accepted');

                    calculateMenuSumByType();


                },
                error: function(error) {
                    console.error('Error ', error);
                }
            });
        }


        function discardOrder(orderId) {

            const url = '{{ route('kitchen.discard.order', [], false) }}';
            var csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                data: {
                    orderId: orderId
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    $('#order' + orderId).remove();
                    calculateMenuSumByType();

                    console.log('Order discarded');

                },
                error: function(error) {
                    console.error('Error ', error);
                }
            });
        }

        function completeOrder(orderId) {

            const url = '{{ route('kitchen.complete.order', [], false) }}';
            var csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                data: {
                    orderId: orderId
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    $('#order' + orderId).remove();
                    console.log('Order No ' + orderId + ' marked as completed');
                    calculateMenuSumByType();

                },
                error: function(error) {
                    console.error('Error ', error);
                }
            });
        }


        // Function to iterate through orders and calculate the sum of menu items based on dine-in or takeaway
        function calculateMenuSumByType() {
            console.log('calculateMenuSumByType');
            // Get all elements with class "order-item"
            const orderElements = document.querySelectorAll('.processing-orders  .order-item');

            // Create objects to store the sum of each menu item for dine-in and takeaway
            const dineInSum = {};
            const takeawaySum = {};

            // Iterate through each order element
            orderElements.forEach((orderElement) => {
                // Get quantity elements
                const quantityElements = orderElement.querySelectorAll('.order-details .order-name-quantity');

                // Get the order type description
                const orderTypeDescription = orderElement.querySelector('.order-type').textContent.trim();

                // Determine if it's dine-in or takeaway based on order type description
                const isDineIn = orderTypeDescription.toLowerCase() === 'dine in';


                quantityElements.forEach((quantityElement) => {
                    // Extract menu item and quantity
                    const [quantity, menuItem] = quantityElement.textContent.split(' x ');

                    // Update the sum for the menu item based on dine-in or takeaway
                    const menuItemKey = menuItem.trim();
                    const quantityValue = parseInt(quantity.trim(), 10);

                    if (isDineIn) {
                        dineInSum[menuItemKey] = (dineInSum[menuItemKey] || 0) + quantityValue;
                    } else {
                        takeawaySum[menuItemKey] = (takeawaySum[menuItemKey] || 0) + quantityValue;
                    }
                });

            });

            $('.dine-in > .items').empty();
            $('.take-away > .items').empty();

            // Output the results
            //console.log('Dine-In Menu Item Sums:');
            Object.entries(dineInSum).forEach(([menuItem, sum]) => {
                //console.log(`${menuItem}: ${sum}`);
                updateMenuSumByType('dinein', menuItem, sum);
            });

            // console.log('\nTakeaway Menu Item Sums:');
            Object.entries(takeawaySum).forEach(([menuItem, sum]) => {
                // console.log(`${menuItem}: ${sum}`);
                updateMenuSumByType('takeaway', menuItem, sum);
            });
        }

        function updateMenuSumByType(type, menuItem, sum) {
            //console.log('updateMenuSumByType');
            if (type === 'dinein')
                $('.dine-in > .items').append('<p class="mb-1 order-name-quantity">' + sum + ' x ' + menuItem + '</p>');
            else {
                $('.take-away > .items').append('<p class="mb-1 order-name-quantity">' + sum + ' x ' + menuItem + '</p>');
            }
        }
    </script>
</x-kitchen-layout>
