<x-kitchen-layout>
    @section('title', 'Kitchen')

    <div class="flex flex-col h-screen bg-gray-200 font-sans">
        <header class="flex justify-between items-center bg-white shadow-md p-2">
            <h1 class="text-2xl font-bold text-gray-800 px-4">Kitchen Display</h1>
            <x-user-dropdown />
        </header>

        <div class="flex flex-1 overflow-hidden">

            <div class="w-1/4 bg-gray-100 border-r border-gray-300 flex flex-col">
                <div class="p-4 border-b border-gray-300 bg-white">
                    <h2 class="text-xl font-bold text-center text-gray-800">Pending KOTs</h2>
                </div>
                <div class="pending-orders flex p-3 space-y-3">
                    @foreach ($newOrders as $order)
                        <x-new-order-component-for-kitchen :order="$order" />
                    @endforeach
                </div>
            </div>

            <div class="w-1/2 bg-white flex flex-col">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-center text-gray-800">In Progress</h2>
                </div>
                <div class="processing-orders flex-1 overflow-y-auto p-3">
                    <div class="flex flex-wrap gap-3 content-start">
                        @foreach ($processingOrders as $order)
                            <x-order-processing-component-for-kitchen :order="$order" />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="w-1/4 bg-gray-100 border-l border-gray-300 flex flex-col">
                <div class="p-4 border-b border-gray-300 bg-white">
                    <h2 class="text-xl font-bold text-center text-gray-800">Live Item Counts</h2>
                </div>
                <div class="items-counts flex-1 overflow-y-auto p-3 space-y-4">
                    <div class="dine-in bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold p-3 bg-gray-50 rounded-t-lg border-b">Dine-In Items</h3>
                        <div class="p-3 items space-y-1">
                            {{-- JS will populate this --}}
                        </div>
                    </div>
                    <div class="take-away bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold p-3 bg-gray-50 rounded-t-lg border-b">Take Away Items</h3>
                        <div class="p-3 items space-y-1">
                            {{-- JS will populate this --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- The original script block is preserved --}}
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

                                console.log('New order component received:', response.html);

                                // check if the order already exists

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
            showLoader();
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
                    firstChilderen.attr("class",
                        "py-3 px-6 font-bold text-white bg-green-600 hover:bg-green-700 transition-colors rounded-br-lg"
                    );
                    firstChilderen.attr("onclick", "completeOrder(" + orderId + ")");
                    $('.processing-orders > .flex').append(order);
                    $('.pending-orders > #order' + orderId).remove();
                    console.log('Order accepted');
                    calculateMenuSumByType();
                },
                error: function(error) {
                    console.error('Error ', error);
                },
                complete: function() {
                    hideLoader();
                }
            });
        }

        function discardOrder(orderId) {
            const url = '{{ route('kitchen.discard.order', [], false) }}';
            var csrf_token = "{{ csrf_token() }}";
            showLoader();
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
                },
                complete: function() {
                    hideLoader();
                }
            });
        }

        function completeOrder(orderId) {
            const url = '{{ route('kitchen.complete.order', [], false) }}';
            var csrf_token = "{{ csrf_token() }}";
            showLoader();
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
                },
                complete: function() {
                    hideLoader();
                }
            });
        }

        function calculateMenuSumByType() {
            const dineInSum = {};
            const takeawaySum = {};

            // Iterate over each order card currently in the "In Progress" column
            $('.processing-orders .order-item').each(function() {
                const orderElement = $(this);

                // Check the data attribute to determine if it's dine-in or takeaway
                const isDineIn = orderElement.data('order-type') === 'dine_in';

                // Find each item row within the order card
                orderElement.find('.order-details-list > div').each(function() {
                    const itemRow = $(this);
                    const quantitySpan = itemRow.find('span:first-child');
                    const nameSpan = itemRow.find('span:last-child');

                    console.log('Processing item:', quantitySpan.text(), nameSpan.text());


                    if (quantitySpan.length && nameSpan.length) {
                        // Extract quantity by removing 'x' and parsing as an integer
                        const quantity = parseInt(quantitySpan.text().replace('x', ''), 10);
                        const menuItem = nameSpan.text().trim();

                        if (!isNaN(quantity) && menuItem) {
                            if (isDineIn) {
                                dineInSum[menuItem] = (dineInSum[menuItem] || 0) + quantity;
                            } else {
                                takeawaySum[menuItem] = (takeawaySum[menuItem] || 0) + quantity;
                            }
                        }
                    }
                });
            });

            // The existing updateMenuSumView function will now work correctly
            updateMenuSumView('.dine-in > .items', dineInSum);
            updateMenuSumView('.take-away > .items', takeawaySum);
        }

        function updateMenuSumView(containerSelector, sumObject) {
            const container = $(containerSelector).empty();
            if (Object.keys(sumObject).length === 0) {
                container.append('<p class="text-sm text-gray-500">No items.</p>');
                return;
            }
            Object.entries(sumObject).forEach(([menuItem, sum]) => {
                container.append(
                    `<p class="text-sm text-gray-800"><span class="font-bold">${sum}</span> x ${menuItem}</p>`);
            });
        }
    </script>
</x-kitchen-layout>
