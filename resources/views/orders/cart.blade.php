<x-waiter-layout>
    <div class="container">
        <h1 class="text-2xl font-semibold mb-4">Your Shopping Cart</h1>

        @if (empty($cart))
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
                        @foreach ($cart as $menuId => $item)
                            @if (is_array($item))
                                <tr class="text-center">
                                    <td class="border-b p-2">{{ $sno++ }}</td>
                                    <td class="border-b p-2">{{ $item['name'] }}</td>
                                    <td class="border-b p-2">{{ $item['quantity'] }}</td>
                                    <td class="border-b p-2">{{ $item['price'] }}</td>
                                    <td class="border-b p-2">{{ $item['quantity'] * $item['price'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <p class="text-lg font-semibold">Total: Rs {{ $cart['total'] ? $cart['total'] : 0 }}</p>
            </div>
            <br>
            <div class="form-group mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Select a
                    predefined note:</label>
                <div class="flex flex-wrap">
                    @foreach ($predefinedNotes as $note)
                        <div class="w-1/3 pr-2 mb-2 mr-2">
                            <input type="checkbox" name="notes" id="{{ $note }}" class="mr-1">
                            <label for="{{ $note }}" class="capitalize">{{ $note }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label for="customNotes" class="block text-sm font-medium text-gray-700 mb-2">Or type a
                    new note:</label>
                <textarea name="extra-notes" id="customNotes" class="w-full p-2 border rounded-md"></textarea>
            </div>
            <button onclick="submitToKitchen()" class="bg-green-500 text-white px-4 py-2 rounded relative m-2">
                Submit To Kitchen
            </button>



        @endif
    </div>

    <script>
        // submit to kitchen call
        function submitToKitchen() {
            const url = {{ route('') }}

            let special_instructions = $("#special_instructions").val();

            if (!special_instructions) {
                special_instructions = null;
            }

            const redirectUrl = "{{ route('waiter.order.step.one', [], false) }}";

            var csrf_token = "{{ csrf_token() }}";

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                data: {
                    special_instructions: special_instructions,
                    source: 'waiter'
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    alert(response.message);
                    window.location.replace(redirectUrl);
                },
                error: function(error) {
                    console.error('Error ', error);
                }
            });
        }
    </script>
</x-waiter-layout>
