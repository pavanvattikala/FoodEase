<x-pos-layout>

    @section('title', 'Choose Table')

    <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen font-sans">

        <header class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <h1 class="text-3xl font-bold text-gray-900">Table View</h1>
            <div class="flex items-center gap-3">
                <button onclick="location.reload()"
                    class="flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition-colors shadow-sm">
                    <i class="fa fa-refresh mr-2"></i>Refresh
                </button>
                <button id="Takeaway"
                    class="flex items-center justify-center px-4 py-2 bg-green-600 rounded-lg text-white font-semibold hover:bg-green-700 transition-colors shadow-sm">
                    Pick Up
                </button>
            </div>
        </header>

        <div class="flex flex-wrap justify-end items-center mb-8 gap-x-6 gap-y-2">
            @foreach ($table_colors as $status => $color)
                <div class="flex items-center gap-2 text-sm text-gray-600 font-medium">
                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $color }};"></span>
                    <span>{{ ucfirst($status) }}</span>
                </div>
            @endforeach
        </div>

        <div class="space-y-10">
            @foreach ($tablesWithLocations as $location => $tables)
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                        {{ ucfirst($location) }} Tables</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-5">
                        @foreach ($tables as $table)
                            <div id="{{ $table->id }}" onclick="selectTable({{ $table->id }})"
                                class="table-item relative flex flex-col rounded-xl shadow-lg p-3 text-white cursor-pointer transition-transform transform hover:-translate-y-1"
                                data-table-status="{{ $table->status->value }}">

                                <p class="elapsed-time absolute top-2 right-2 text-xs font-bold bg-black bg-opacity-20 px-1.5 py-0.5 rounded-full"
                                    data-taken-at="{{ $table->taken_at }}">
                                </p>

                                <div class="text-center">
                                    <h2 class="text-3xl font-bold tracking-wider">{{ $table->name }}</h2>
                                    @if ($table->order_sum)
                                        <p id="tableTotal" class="text-lg font-semibold mt-1">Rs {{ $table->order_sum }}
                                        </p>
                                    @else
                                        <p id="tableTotal" class="text-lg font-semibold mt-1"></p> {{-- Ensure element exists for clearing --}}
                                    @endif
                                </div>


                                <div class="table-options bottom-3 left-0 right-0 flex justify-center gap-2">
                                    <div id="showOrdersBtn">
                                        <button onclick="event.stopPropagation(); showOrders({{ $table->id }})"
                                            class="action-btn">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="printTableBtn">
                                        <button onclick="event.stopPropagation(); printTable({{ $table->id }})"
                                            class="action-btn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                    <div id="settleTableBtn">
                                        <button
                                            onclick="event.stopPropagation(); triggerPaymentModal({{ $table->id }})"
                                            class="action-btn">
                                            <i class="fas fa-dollar-sign"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div id="paymentModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            style="display: none;">
            <div class="modal-overlay fixed inset-0" data-close="paymentModal"></div>
            <div class="modal-container bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 m-4 relative">
                <div class="modal-header flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Settle Bill</h3>
                    <button data-close="paymentModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="modal-body space-y-3">
                    <input type="hidden" id="paymentTableId" value="0">
                    @foreach ($paymentTypes as $payment)
                        <label
                            class="flex items-center p-3 rounded-lg hover:bg-gray-50 border has-[:checked]:bg-green-50 has-[:checked]:border-green-400">
                            <input type="radio" name="payment-type" value="{{ $payment }}"
                                id="payment-{{ $payment }}"
                                class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                            <label for="payment-{{ $payment }}"
                                class="ml-3 text-gray-700 font-medium">{{ strtoupper($payment) }}</label>
                        </label>
                    @endforeach
                </div>
                <div class="modal-footer mt-6 flex justify-end gap-3">
                    <button type="button"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold"
                        data-close="paymentModal">Cancel</button>
                    <button type="button" id="savePaymentDataBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">Save &
                        Settle</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .action-btn {
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in-out;
        }

        .action-btn:hover {
            background-color: white;
            transform: scale(1.1);
        }

        .table-item {
            min-height: 100px !important;
        }
    </style>

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
                // Correctly target the elapsed-time <p> inside the specific table div
                $(`#${table.tableId}`).find(".elapsed-time").text(elapsedString);
            });
        }

        function getElapsedMinutes(originalTime) {
            if (!originalTime) return '';
            const takenTime = new Date(originalTime);
            const elapsedTime = Date.now() - takenTime.getTime();
            const hours = Math.floor(elapsedTime / (1000 * 60 * 60));
            const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
            return hours > 0 ? `${hours}H:${minutes}M` : `${minutes}M`;
        }

        // Show orders for a specific table
        function showOrders(tableId) {
            const url = "{{ route('pos.table.orders', ['tableId' => ':id'], false) }}".replace(':id', tableId);

            const target = '{{ env('LINK_TARGET', '_blank') }}';
            if (target === '_self') {
                window.location.href = url;
            } else {
                window.open(url, target);
            }
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
