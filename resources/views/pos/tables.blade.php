<x-pos-layout>
    @section('title', 'Choose Table')
    <style>
        .category-header {
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f5f5f5;
        }

        .table-item,
        .unavailable-table-item {
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 130px !important;
            min-width: 110px !important;
            padding: 10px;
            margin: 0 10px 10px 0;
            background-color: #fff;
            width: 100px;
        }

        .unavailable-table-item {
            cursor: not-allowed;
            background-color: #f5a5a5;
        }

        .tables-container {
            display: flex;
            flex-wrap: wrap;
        }

        .mw-60 {
            width: 60%;
        }

        .mw-40 {
            width: 40%;
        }

        .btn {
            background-color: rgb(55, 150, 55);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }

        .new_options div {
            margin-right: 10px !important;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .modal-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 300px;
        }

        .modal-header,
        .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .table-options {
            display: flex;
            justify-content: center;
        }

        .table-options>div:first-child {
            margin-right: 10px;
        }
    </style>

    <div class="flex flex-row mb-2 mt-2" id="table-main-nav">
        <div class="flex flex-row mw-60">
            <h1 class="font-bold">Table View</h1>
        </div>
        <div id="order-type-options" class="mw-40 flex flex-row justify-evenly">
            <button class="btn" id="reload" onclick="location.reload()"><i class="fa fa-refresh">Refresh</i></button>
            <button class="btn" id="Takeaway">Pick Up</button>
        </div>
    </div>

    <div class="flex flex-row mb-2 mt-2" id="table-second-nav">
        <div class="flex flex-row mw-60 new_options">
            <!-- Additional buttons can be added here if needed -->
        </div>
        <div id="table_type_options" class="mw-40 flex flex-row justify-evenly">
            @foreach ($table_colors as $table_option_name => $table_color)
                <div>
                    <center>{{ ucfirst($table_option_name) }}
                        <br>
                        <span style="background-color: {{ $table_color }};">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </center>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container" id="select-tables">
        @foreach ($tableLocations as $location)
            <div class="mb-4">
                <h2 class="category-header">{{ ucfirst($location->name) }} Tables</h2>
                <div class="tables-container">
                    @foreach ($tables->where('location', $location) as $table)
                        <div id="{{ $table->id }}" onclick="selectTable({{ $table->id }})"
                            class="table-item text-white text-center" data-table-status="{{ $table->status->value }}">

                            <p class="elapsed-time" id="elapsedTime" data-taken-at="{{ $table->taken_at }}"></p>

                            <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                            @if ($table->order_sum)
                                <p id="tableTotal">Rs {{ $table->order_sum }}</p>
                            @endif

                            <div class="flex table-options justify-center align-middle">

                                <div id="showOrdersBtn"
                                    onclick="event.stopPropagation(); showOrders({{ $table->id }})">
                                    <button class="btn" style="background-color: white; color: black"><i
                                            class="fas fa-eye"></i></button>
                                </div>
                                <div id="printTableBtn"
                                    onclick="event.stopPropagation(); printTable({{ $table->id }})">
                                    <button class="btn" style="background-color: white; color: black"><i
                                            class="fas fa-print"></i></button>
                                </div>

                                <div id="settleTableBtn"
                                    onclick="event.stopPropagation(); triggerPaymentModal({{ $table->id }})">
                                    <button class="btn" style="background-color: white; color: black"><i
                                            class="fas fa-save"></i></button>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Payment Modal --}}
    <div id="paymentModal" class="modal fixed inset-0 flex items-center justify-center">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50" tabindex="-1" data-close="paymentModal"></div>
        <div class="modal-container bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
            <!-- Modal Header -->
            <div class="modal-header flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Payment Options</h2>
                <button class="text-gray-500 hover:text-gray-700" data-close="paymentModal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="space-y-4 modal-body">
                <input type="hidden" id="paymentTableId" value="0">
                @foreach ($paymentTypes as $payment)
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="payment-type" value="{{ $payment }}" id="{{ $payment }}"
                            class="h-4 w-4 text-green-600">
                        <label for="{{ $payment }}"
                            class="text-gray-700 font-medium">{{ strtoupper($payment) }}</label>
                    </div>
                @endforeach
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 flex justify-end space-x-3 modal-footer">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                    data-close="paymentModal">Close</button>
                <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                    id="savePaymentDataBtn">Save and Settle</button>
            </div>
        </div>
    </div>




    <script>
        const billTableUrl = "{{ route('pos.table.bill', [], false) }}";
        const indexUrl = "{{ route('pos.tables', [], false) }}";
        const settleTableUrl = "{{ route('pos.table.settle', [], false) }}";
        const selectTableURL = "{{ route('pos.main', [], false) }}";
        const tableColors = @json($table_colors);

        let runningTables = [];

        $(document).ready(function() {
            // Initialize the table styles and event listeners
            initTables();
        });

        function initTables() {
            updateTableStyles();
            updateRunningTables();
            updateElapsedTimes();

            // Update elapsed times every minute
            setInterval(updateElapsedTimes, 60000);

            // Event listeners
            $("#Takeaway").on("click", () => selectTable("takeaway"));
            $('[data-close="paymentModal"]').on("click", () => $("#paymentModal").hide());
            $("#savePaymentDataBtn").on("click", handlePayment);
        }

        function handlePayment() {
            const tableId = $("#paymentTableId").val();
            const paymentType = $("input[name='payment-type']:checked").val();
            settleTable(tableId, paymentType);
            $("#paymentModal").hide();
        }

        function selectTable(tableId) {
            window.location.href = `${selectTableURL}?tableId=${tableId}`;
        }

        // Update elapsed time for running tables
        function updateElapsedTimes() {
            runningTables.forEach(table => {
                const elapsedString = getElapsedMinutes(table.takenAt);
                $(`#${table.tableId}`).find(".elapsed-time").text(elapsedString);
            });
        }

        function getElapsedMinutes(originalTime) {
            const takenTime = new Date(originalTime);
            const elapsedTime = Date.now() - takenTime.getTime();
            const elapsedHours = Math.floor(elapsedTime / (1000 * 60 * 60));
            const elapsedMinutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
            return `${elapsedHours}H:${elapsedMinutes}M`;
        }

        // Show orders for a specific table
        function showOrders(tableId) {
            const url = "{{ route('pos.table.orders', ['tableId' => ':id'], false) }}".replace(':id', tableId);
            window.open(url, '_blank');
        }

        // Trigger payment modal
        function triggerPaymentModal(tableId) {
            $("#paymentModal").show();
            $("input[name='payment-type'][value='cash']").prop("checked", true);
            $("#paymentTableId").val(tableId);
        }

        // Settle the table
        function settleTable(tableId, paymentType) {
            if (!tableId || !paymentType) {
                alert("Table Settlement Failed, missing data. Reload the page and try again.");
                return;
            }

            event.stopPropagation();
            showLoader();
            const csrf_token = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: settleTableUrl,
                type: "POST",
                data: {
                    tableId,
                    paymentType
                },
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                },
                contentType: "application/x-www-form-urlencoded",
                success: response => {
                    if (response.status === "success") {
                        updateTableStatus(tableId, "available");
                    } else {
                        alert("Table Settlement Failed");
                    }
                },
                error: () => {
                    alert("Table Settlement Failed");
                },
                complete: hideLoader
            });
        }

        // Print table bill
        function printTable(tableId) {
            event.stopPropagation();
            showLoader();
            const csrf_token = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: billTableUrl,
                type: "POST",
                data: {
                    tableId
                },
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                },
                contentType: "application/x-www-form-urlencoded",
                success: response => {
                    if (response.status === "success") {
                        updateTableStatus(tableId, "printed");
                    } else {
                        alert("Table Billing Failed");
                    }
                },
                error: () => {
                    alert("Table Billing Failed");
                },
                complete: hideLoader
            });
        }

        // Update table styles and buttons visibility
        function updateTableStyles() {
            $(".table-item").each(function() {
                const $table = $(this);
                const tableStatus = $table.attr("data-table-status");

                // Update table background color based on status
                $table.css("background-color", tableColors[tableStatus]);

                // Show/Hide buttons based on table status
                const showOrdersBtn = $table.find("#showOrdersBtn");
                const printTableBtn = $table.find("#printTableBtn");
                const settleTableBtn = $table.find("#settleTableBtn");

                switch (tableStatus) {
                    case 'available':
                        showOrdersBtn.hide();
                        printTableBtn.hide();
                        settleTableBtn.hide();
                        break;
                    case 'running':
                        showOrdersBtn.show();
                        printTableBtn.show();
                        settleTableBtn.hide();
                        break;
                    case 'printed':
                        showOrdersBtn.show();
                        printTableBtn.hide();
                        settleTableBtn.show();
                        break;
                }
            });
        }

        // Update table status and related UI elements
        function updateTableStatus(tableId, status) {
            const $table = $(`#${tableId}`);
            $table.attr("data-table-status", status);

            if (status === 'available') {
                clearElapsedTime(tableId);
                clearTableTotal(tableId);
            }

            updateRunningTables();
            updateTableStyles();
            updateElapsedTimes();
        }

        // Clear elapsed time and total when table is available
        function clearElapsedTime(tableId) {
            $(`#${tableId}`).find(".elapsed-time").attr("data-taken-at", "").text("");
        }

        function clearTableTotal(tableId) {
            $(`#${tableId}`).find("#tableTotal").text("");
        }

        // Update the list of running tables
        function updateRunningTables() {
            runningTables = [];

            $(".table-item").each(function() {
                const $table = $(this);
                const tableStatus = $table.attr("data-table-status");

                if (tableStatus !== 'available') {
                    runningTables.push({
                        tableId: $table.attr("id"),
                        takenAt: $table.find(".elapsed-time").attr("data-taken-at")
                    });
                }
            });
        }
    </script>

</x-pos-layout>
