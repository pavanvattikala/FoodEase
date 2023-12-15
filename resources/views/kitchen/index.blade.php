<x-kitchen-layout>
    <div>
        <h1>Hello</h1>
    </div>

    <script src="{{ asset('js/kitchen.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.Echo.channel('orderSubmitted')
                .listen('OrderSubmittedToKitchen', (event) => {
                    console.log('Order submitted to kitchen:', event.cart);
                    // Handle the event data as needed
                });
        });
    </script>
</x-kitchen-layout>
