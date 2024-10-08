<x-waiter-layout>
    <style>
        main {
            margin: 0px !important;
            padding: 0px !important;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-content button {
            width: 100%;
            padding: 1rem;
            margin: 0.5rem;
        }

        #close-btn {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
    </style>
    <div class="container" id="select-tables">
        @foreach (App\Enums\TableLocation::cases() as $location)
            <div class="mb-4">
                <h2 class="flex justify-between text-xl font-semibold mb-2 cursor-pointer border border-gray-300 rounded p-2 bg-gray-100"
                    onclick="toggleDropdown('{{ $location->name }}')">
                    {{ $location->name }} Tables
                    <span id="{{ $location->name }}Arrow">&#9662;</span>
                </h2>
                <div id="{{ $location->name }}Dropdown" class="flex flex-wrap">
                    @foreach ($tables->where('location', $location) as $table)
                        @if ($table->status === App\Enums\TableStatus::Available)
                            <div id="{{ $table->id }}" onclick="selectTable({{ $table->id }})"
                                class="w-40 m-4 p-4 bg-green-500 border border-gray-300 rounded cursor-pointer text-center">
                                <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                                <div class="flex flex-wrap ">
                                    <p>Guests: {{ $table->guest_number }}</p>
                                </div>
                            </div>
                        @elseif($table->status === App\Enums\TableStatus::Running)
                            <div onclick="reOrder({{ $table->id }})" id="table{{ $table->id }}"
                                style="margin-right: 0.5rem; background-color: blue"
                                class="w-40 m-4 p-4 border rounded cursor-pointer text-center  opacity-40 text-white">
                                <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                                <div class="flex flex-wrap">
                                    <p class="text-white" id="timer{{ $table->id }}"></p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div id="modal-window" class="w-full h-full flex flex-col items-center justify-center pt-20"
        style="background-color: #6b6e5c">

        <div class="modal-content">
            <div class="m-2  options">
                <button class="m-2 p-2 rounded " id="reOrder" style="background-color: #42d1f5">Re Order</button>
                <button class="m-2 p-2 rounded" id="subitForBilling" style="background-color: #42f55a">Submit to
                    Billing</button>
            </div>
        </div>

        <button id="close-btn" class="m-2 p-2 rounded" style="background-color: #e84343">Close</button>

    </div>

    <script>
        function toggleDropdown(categoryName) {
            const dropdown = document.getElementById(`${categoryName}Dropdown`);
            const arrow = document.getElementById(`${categoryName}Arrow`);
            dropdown.classList.toggle('hidden');
            arrow.innerHTML = dropdown.classList.contains('hidden') ? '&#9662;' : '&#9652;';
        }

        function selectTable(tableId) {

            const url = "{{ url()->route('waiter.table.add.toSession', [], false) }}";

            const button = document.getElementById(tableId);
            disableButton(button);

            var csrf_token = "{{ csrf_token() }}";

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    tableId: tableId
                },
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    console.log('table is selected:', response.message);

                    if (response.message == "true") {
                        //alert("Table " + tableId + " is selected");
                        window.location.replace(" {{ route('order.step.one') }}");
                    } else {
                        alert("You cannot select it");
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        //for unavaliable tables


        function reOrder(tableId) {
            const url = "{{ route('waiter.table.add.toSession', [], false) }}";
            const csrf_token = "{{ csrf_token() }}";

            const button = document.getElementById(tableId);
            disableButton(button);

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    tableId: tableId,
                    reOrder: "true"
                },
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    console.log('table is selected:', response.message);

                    if (response.message == "true") {
                        window.location.replace("{{ route('order.step.one') }}");
                    } else {
                        alert("You cannot select it");
                        enableButton(button);
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                    enableButton(button);
                }
            });
        }

        function subitForBilling(tableId) {
            const url = "{{ route('waiter.table.submit.for.billing', [], false) }}";
            const csrf_token = "{{ csrf_token() }}";

            const button = document.getElementById(`submitForBillingButton_${tableId}`);
            disableButton(button);

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    tableId: tableId
                },
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    if (response.status == "success") {
                        window.location.replace("{{ route('order.step.one') }}");
                    } else {
                        alert("You cannot submit it");
                        enableButton(button);
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                    enableButton(button);
                }
            });
        }

        // JavaScript to update the timer
        function updateTimer(element, updatedAt) {
            setInterval(function() {
                const now = new Date();
                const diff = now - new Date(updatedAt);
                const hours = Math.floor(diff / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);

                const timerText = `${hours}h ${minutes}m ${seconds}s`;
                element.textContent = timerText;
            }, 1000); // Update every second
        }

        function getAllTables() {
            // Call updateTimer function for each table
            const tables = {!! json_encode($takenTables) !!};
            console.log(tables);

            tables.forEach(table => {
                const timerElement = document.getElementById(`timer${table.id}`);
                updateTimer(timerElement, table.taken_at);
            });
        }

        // Enable / disable buttons
        function disableButton(button) {
            button.disabled = true;
            button.classList.add("disabled");
        }

        function enableButton(button) {
            button.disabled = false;
            button.classList.remove("disabled");
        }
    </script>
</x-waiter-layout>
