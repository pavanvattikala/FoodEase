<x-master-layout>
    @section('title', 'Restaurant Configurations')

    {{-- Removed custom CSS section entirely to rely on Tailwind CSS --}}

    <div class="container mx-auto p-4">
        <h1 class="text-center text-3xl font-bold text-gray-800 mb-8">Restaurant Configurations</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form method="post" action="{{ route('admin.restaurant.update.config') }}"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <input type="hidden" name="id" value="{{ $restaurantConfig['id'] }}">

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ $restaurantConfig['name'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="tagline" class="block text-gray-700 text-sm font-bold mb-2">Tagline:</label>
                <input type="text" id="tagline" name="tagline" value="{{ $restaurantConfig['tagline'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                <textarea id="address" name="address"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-24">{{ $restaurantConfig['address'] }}</textarea>
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                <input type="text" id="phone" name="phone" value="{{ $restaurantConfig['phone'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="GST" class="block text-gray-700 text-sm font-bold mb-2">GST:</label>
                <input type="text" id="GST" name="GST" value="{{ $restaurantConfig['GST'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ $restaurantConfig['email'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="website" class="block text-gray-700 text-sm font-bold mb-2">Website:</label>
                <input type="text" id="website" name="website" value="{{ $restaurantConfig['website'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="biller_printer" class="block text-gray-700 text-sm font-bold mb-2">Biller Printer:</label>
                <p class="text-xs text-red-400 font-semibold">Leave blank for no printer</p>
                <input type="text" id="biller_printer" name="biller_printer"
                    value="{{ $restaurantConfig['biller_printer'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="kitchen_printer" class="block text-gray-700 text-sm font-bold mb-2">Kitchen Printer:</label>
                <p class="text-xs text-red-400 font-semibold">Leave blank for no printer</p>
                <input type="text" id="kitchen_printer" name="kitchen_printer"
                    value="{{ $restaurantConfig['kitchen_printer'] }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="pending_order_sync_time" class="block text-gray-700 text-sm font-bold mb-2">Order Sync Time
                    (seconds):</label>
                <select name="pending_order_sync_time" id="pending_order_sync_time"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '5') selected @endif value="5">5 Seconds</option>
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '15') selected @endif value="15">15 Seconds</option>
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '30') selected @endif value="30">30 Seconds</option>
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '60') selected @endif value="60">60 Seconds</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="waiter_sync_time" class="block text-gray-700 text-sm font-bold mb-2">Waiter Sync Time
                    (seconds):</label>
                <select name="waiter_sync_time" id="waiter_sync_time"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option @if ($restaurantConfig['waiter_sync_time'] == '5') selected @endif value="5">5 Seconds</option>
                    <option @if ($restaurantConfig['waiter_sync_time'] == '15') selected @endif value="15">15 Seconds</option>
                    <option @if ($restaurantConfig['waiter_sync_time'] == '30') selected @endif value="30">30 Seconds</option>
                    <option @if ($restaurantConfig['waiter_sync_time'] == '60') selected @endif value="60">60 Seconds</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="minimum_delivery_time" class="block text-gray-700 text-sm font-bold mb-2">Minimum Delivery
                    Time (minutes):</label>
                <select name="minimum_delivery_time" id="minimum_delivery_time"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '300') selected @endif value="300">5 Minutes</option>
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '600') selected @endif value="600">10 Minutes</option>
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '900') selected @endif value="900">15 Minutes</option>
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '1200') selected @endif value="1200">20 Minutes</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="minimum_preparation_time" class="block text-gray-700 text-sm font-bold mb-2">Minimum
                    Preparation Time (minutes):</label>
                <select name="minimum_preparation_time" id="minimum_preparation_time"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '300') selected @endif value="300">5 Minutes</option>
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '600') selected @endif value="600">10 Minutes</option>
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '900') selected @endif value="900">15 Minutes</option>
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '1200') selected @endif value="1200">20 Minutes</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Order Live View:</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" @if ($restaurantConfig['order_live_view'] == 'asc') checked @endif
                            name="order_live_view" id="order_live_view_asc" value="asc" class="form-radio">
                        <span class="ml-2 text-gray-700">ASC</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" @if ($restaurantConfig['order_live_view'] == 'desc') checked @endif
                            name="order_live_view" id="order_live_view_desc" value="desc" class="form-radio">
                        <span class="ml-2 text-gray-700">DESC</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">KOT Live View:</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" @if ($restaurantConfig['kot_live_view'] == 'asc') checked @endif name="kot_live_view"
                            id="kot_live_view_asc" value="asc" class="form-radio">
                        <span class="ml-2 text-gray-700">ASC</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" @if ($restaurantConfig['kot_live_view'] == 'desc') checked @endif name="kot_live_view"
                            id="kot_live_view_desc" value="desc" class="form-radio">
                        <span class="ml-2 text-gray-700">DESC</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Changes
                </button>
            </div>
        </form>

        {{-- Module Management Section --}}
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Module Management</h2>

            {{-- Waiter Module --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-700">Waiter Module</h3>

                    <button id="toggleWaiterModuleBtn"
                        class="text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    </button>

                </div>
                <p id="WaiterModuleMessage" class="mt-2 text-sm"></p>
                <p id="WaiterModuleLink" class="text-gray-600 text-sm mt-2 hidden">
                    Access the Waiter module at: <a href="{{ url('/waiter') }}" target="_blank"
                        class="text-blue-500 hover:underline">Open Waiter Dashboard</a>
                </p>
            </div>

            {{-- Kitchen Module --}}
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-700">Kitchen Module</h3>
                    <button id="toggleKitchenModuleBtn"
                        class="text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    </button>
                </div>
                <p id="KitchenModuleMessage" class="mt-2 text-sm"></p>
                <p id="KitchenModuleLink" class="text-gray-600 text-sm mt-2 hidden">
                    Access the Kitchen module at: <a href="{{ url('/kitchen') }}" target="_blank"
                        class="text-blue-500 hover:underline">Open Kitchen Dashboard</a>
                </p>

                {{-- Pusher Configuration for Kitchen Module - Display Only --}}
                <div id="kitchenPusherSettings" class="mt-4 p-4 border rounded-md bg-gray-50 hidden">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Pusher Configuration for Kitchen Module
                        (Read-Only)</h3>
                    <p class="text-sm text-red-600 mb-4 font-semibold">
                        Note: Pusher settings are managed directly in your application's `.env` file. After making
                        changes, run `php artisan config:clear` in your terminal for them to take effect.
                    </p>
                    <div class="mb-4">
                        <label for="PUSHER_APP_ID"
                            class="block text-gray-700 text-sm font-bold mb-2">PUSHER_APP_ID:</label>
                        <input type="text" id="PUSHER_APP_ID"
                            value="{{ config('broadcasting.connections.pusher.app_id') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200"
                            readonly>
                    </div>
                    <div class="mb-4">
                        <label for="PUSHER_APP_KEY"
                            class="block text-gray-700 text-sm font-bold mb-2">PUSHER_APP_KEY:</label>
                        <input type="text" id="PUSHER_APP_KEY"
                            value="{{ config('broadcasting.connections.pusher.key') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200"
                            readonly>
                    </div>
                    <div class="mb-4">
                        <label for="PUSHER_APP_SECRET"
                            class="block text-gray-700 text-sm font-bold mb-2">PUSHER_APP_SECRET:</label>
                        <input type="text" id="PUSHER_APP_SECRET"
                            value="{{ config('broadcasting.connections.pusher.secret') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200"
                            readonly>
                    </div>
                    <div class="mb-4">
                        <label for="PUSHER_APP_CLUSTER"
                            class="block text-gray-700 text-sm font-bold mb-2">PUSHER_APP_CLUSTER:</label>
                        <input type="text" id="PUSHER_APP_CLUSTER"
                            value="{{ config('broadcasting.connections.pusher.options.cluster') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200"
                            readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Assuming jQuery is loaded globally --}}

    <script>
        $(document).ready(function() {
            const restaurantId = "{{ $restaurantConfig['id'] }}";
            const csrf_token = $('meta[name="csrf-token"]').attr('content');

            // Initial module statuses from the backend
            let waiterModuleEnabled = {{ $moduleStatus['waiter_module_enabled'] ? 'true' : 'false' }};
            let kitchenModuleEnabled = {{ $moduleStatus['kitchen_module_enabled'] ? 'true' : 'false' }};

            // Function to update the UI for a given module
            function updateModuleUI(moduleName, isEnabled, message = '') {

                moduleName = moduleName.charAt(0).toUpperCase() + moduleName.slice(1); // Capitalize module name
                const button = $(`#toggle${moduleName}ModuleBtn`);

                const messageElement = $(`#${moduleName}ModuleMessage`);
                const linkElement = $(`#${moduleName}ModuleLink`);
                const pusherSettings = $(`#kitchenPusherSettings`); // Only for kitchen

                // Update button text and class
                if (isEnabled) {
                    button.text(`Disable ${moduleName} Module`).removeClass('bg-blue-500 hover:bg-blue-700')
                        .addClass('bg-red-500 hover:bg-red-700');
                    linkElement.removeClass('hidden');
                    if (moduleName === 'Kitchen') {
                        pusherSettings.removeClass('hidden');
                    }
                } else {
                    button.text(`Enable ${moduleName} Module`).removeClass('bg-red-500 hover:bg-red-700').addClass(
                        'bg-blue-500 hover:bg-blue-700');
                    linkElement.addClass('hidden');
                    if (moduleName === 'Kitchen') {
                        pusherSettings.addClass('hidden');
                    }
                }


                // Update message
                messageElement.text(message);
                if (message.includes('successfully')) {
                    messageElement.removeClass('text-red-600').addClass('text-green-600');
                } else if (message) {
                    messageElement.removeClass('text-green-600').addClass('text-red-600');
                }
                setTimeout(() => messageElement.text(''), 5000); // Clear message after 5 seconds
            }

            // Initial UI setup on page load
            updateModuleUI('waiter', waiterModuleEnabled);
            updateModuleUI('kitchen', kitchenModuleEnabled);

            // Handle Waiter Module toggle
            $('#toggleWaiterModuleBtn').on('click', function() {
                const targetState = !waiterModuleEnabled;
                const url = targetState ?
                    "{{ route('admin.restaurant.enable_waiter_module') }}" :
                    "{{ route('admin.restaurant.disable_waiter_module') }}";

                showLoader();
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        id: restaurantId
                    },
                    headers: {
                        "X-CSRF-TOKEN": csrf_token,
                    },
                    dataType: "json", // Expect JSON response
                    success: function(response) {
                        if (response.success) {
                            waiterModuleEnabled = targetState; // Update local state
                            updateModuleUI('waiter', waiterModuleEnabled, response.message);
                        } else {
                            updateModuleUI('waiter', waiterModuleEnabled, response.error ||
                                'Operation failed.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", status, error, xhr.responseText);
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred during the operation.';
                        updateModuleUI('waiter', waiterModuleEnabled, errorMessage);
                    },
                    complete: function() {
                        hideLoader();
                    }
                });
            });

            // Handle Kitchen Module toggle
            $('#toggleKitchenModuleBtn').on('click', function() {
                const targetState = !kitchenModuleEnabled;
                const url = targetState ?
                    "{{ route('admin.restaurant.enable_kitchen_module') }}" :
                    "{{ route('admin.restaurant.disable_kitchen_module') }}";

                showLoader();
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        id: restaurantId
                    },
                    headers: {
                        "X-CSRF-TOKEN": csrf_token,
                    },
                    dataType: "json", // Expect JSON response
                    success: function(response) {
                        if (response.success) {
                            kitchenModuleEnabled = targetState; // Update local state
                            updateModuleUI('kitchen', kitchenModuleEnabled, response.message);
                        } else {
                            updateModuleUI('kitchen', kitchenModuleEnabled, response.error ||
                                'Operation failed.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", status, error, xhr.responseText);
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred during the operation.';
                        updateModuleUI('kitchen', kitchenModuleEnabled, errorMessage);
                    },
                    complete: function() {
                        hideLoader();
                    }
                });
            });
        });
    </script>
</x-master-layout>
