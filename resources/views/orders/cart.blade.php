<x-waiter-layout>
    <div class="container">
        <h1 class="text-2xl font-semibold mb-4">Your Shopping Cart</h1>

        @if(empty($cart))
            <p>Your cart is empty.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border-b p-2">#</th>
                            <th class="border-b p-2">Item Name</th>
                            <th class="border-b p-2">Quantity</th>
                            <th class="border-b p-2">Price</th>
                            <th class="border-b p-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sno = 1; @endphp
                        @foreach($cart as $menuId => $item)
                            @if(is_array($item))
                                <tr class="text-center">
                                    <td class="border-b p-2">{{ $sno++ }}</td>
                                    <td class="border-b p-2">{{ $item['name'] }}</td>
                                    <td class="border-b p-2">{{ $item['quantity'] }}</td>
                                    <td class="border-b p-2">{{ $item['price'] }}</td>
                                    <td class="border-b p-2">Rs {{ $item['quantity'] * $item['price'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <p class="text-lg font-semibold">Total: Rs {{ ($cart['total']) ? $cart['total'] : 0 }}</p>
            </div>
            <br>
            <h3>Enter Special instuctions</h3>
            <textarea class="m-4 p-4" name="special_instructions" id="special_instructions" cols="30" rows="5"></textarea>

            <button onclick="submitToKitchen()" class="bg-green-500 text-white px-4 py-2 rounded relative m-2">
                Submit To Kitchen
            </button>
        

            
        @endif
    </div>

    <script>


        // submit to kitchen call
        function submitToKitchen() {
            const url = "submit";  

            let special_instructions = $("#special_instructions").val();

            if(!special_instructions){
                special_instructions =null;
            }

            const redirectUrl = "{{ route("waiter.order.step.one",[],false) }}";

            var csrf_token = "{{ csrf_token()  }}";

            $.ajax({
                type: "POST",
                url: url,
                headers: { 'X-CSRF-TOKEN': csrf_token },
                data:{special_instructions:special_instructions},
                contentType:'application/x-www-form-urlencoded',
                success: function (response) {
                    alert(response.message);
                    window.location.replace(redirectUrl);        
                },
                error: function (error) {
                    console.error('Error ', error);
                }
            });
        }
    </script>
</x-waiter-layout>
