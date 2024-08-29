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
                <h2 class="category-header">{{ $location->name }} Tables</h2>
                <div class="tables-container">
                    @foreach ($tables->where('location', $location) as $table)
                        @php
                            $tableColor = $table_colors[$table->status->value];
                            $tableTotal = $table->taken_at
                                ? $table->orders->where('status', '!=', App\Enums\OrderStatus::Closed)->sum('total')
                                : null;
                            $isTableToBeBilled =
                                $table->status != App\Enums\TableStatus::Printed &&
                                $table->status != App\Enums\TableStatus::Available;
                            $isTableToBePaid = $table->status == App\Enums\TableStatus::Printed;
                        @endphp

                        <div id="{{ $table->id }}" onclick="selectTable({{ $table->id }})"
                            class="table-item text-white text-center" style="background-color: {{ $tableColor }}"
                            data-table-status="{{ $table->status->value }}">

                            <p class="elapsed-time" data-taken-at="{{ $table->taken_at }}"></p>

                            <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                            @if ($tableTotal)
                                <p>{{ $tableTotal }}</p>
                            @endif

                            <div class="flex table-options justify-center align-middle">
                                @if ($isTableToBeBilled)
                                    <div onclick="event.stopPropagation(); showOrders({{ $table->id }})">
                                        <button class="btn" style="background-color: white; color: black"><i
                                                class="fas fa-eye"></i></button>
                                    </div>
                                    <div onclick="event.stopPropagation(); printTable({{ $table->id }})">
                                        <button class="btn" style="background-color: white; color: black"><i
                                                class="fas fa-print"></i></button>
                                    </div>
                                @endif
                                @if ($isTableToBePaid)
                                    <div onclick="event.stopPropagation(); triggerPaymentModal({{ $table->id }})">
                                        <button class="btn" style="background-color: white; color: black"><i
                                                class="fas fa-save"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Payment Modal --}}
    <div id="paymentModal" class="modal">
        <div class="modal-overlay" tabindex="-1" data-close="paymentModal"></div>
        <div class="modal-container">
            <div class="modal-header">
                <span class="modal-title text-2xl font-bold">Payment Options</span>
                <span class="modal-close cursor-pointer" data-close="paymentModal">&times;</span>
            </div>
            <div class="modal-body">
                <input type="hidden" id="paymentTableId" value="0">
                @foreach ($paymentTypes as $payment)
                    <div>
                        <input type="radio" name="payment-type" value="{{ $payment }}"
                            id="{{ $payment }}">
                        <label for="{{ $payment }}">{{ $payment }}</label>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn mr-2" data-close="paymentModal">Close</button>
                <button type="button" class="btn bg-green-500" id="savePaymentDataBtn">Save And Settle Table</button>
            </div>
        </div>
    </div>

    <script>
        const runningTables = [];
        const billTableUrl = "{{ route('pos.table.bill', [], false) }}";
        const indexUrl = "{{ route('pos.tables', [], false) }}";
        const settleTableUrl = "{{ route('pos.table.settle', [], false) }}";
        const selectTableURL = "{{ route('pos.main', [], false) }}";


        $(document).ready(function() {
            $(".table-item[data-table-status!='available']").each(function() {
                runningTables.push({
                    tableId: $(this).attr("id"),
                    takenAt: $(this).find(".elapsed-time").data("taken-at")
                });
            });

            setInterval(updateElapsedTimes, 1000);

            $("#Takeaway").on("click", function() {
                selectTable("takeaway");
            });

            $('[data-close="paymentModal"]').on("click", function() {
                $("#paymentModal").hide();
            });

            $("#savePaymentDataBtn").on("click", function() {
                const tableId = $("#paymentTableId").val();
                const paymentType = $("input[name='payment-type']:checked").val();
                settleTable(tableId, paymentType);
                $("#paymentModal").hide();
            });
        });

        function selectTable(tableId) {
            window.location.href = `${selectTableURL}?tableId=${tableId}`;
        }

        function updateElapsedTimes() {
            runningTables.forEach(table => {
                const elapsedString = getElapsedMinutes(table.takenAt);
                $("#" + table.tableId).find(".elapsed-time").text(elapsedString);
            });
        }

        function getElapsedMinutes(originalTime) {
            const takenTime = new Date(originalTime);
            const elapsedTime = Date.now() - takenTime.getTime();
            const elapsedHours = Math.floor(elapsedTime / (1000 * 60 * 60));
            const elapsedMinutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
            return `${elapsedHours}H:${elapsedMinutes}M`;
        }

        function showOrders(tableId) {
            const url = "{{ route('pos.table.orders', ['tableId' => ':id'], false) }}".replace(':id', tableId);
            window.open(url, '_blank');
        }

        function triggerPaymentModal(tableId) {
            $("#paymentModal").show();
            $("#paymentTableId").val(tableId);
        }


        function settleTable(tableId, paymentType) {

            if (tableId == null || paymentType == null) {
                alert("Table Settlement Failed, data is missing reload the page and try again");
                return;
            }
            event.stopPropagation();
            var csrf_token = $('meta[name="csrf-token"]').attr("content");
            $.ajax({

                url: settleTableUrl,
                type: "POST",
                data: {
                    tableId: tableId,
                    paymentType: paymentType,
                },
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                },
                contentType: "application/x-www-form-urlencoded",
                success: function(response) {
                    console.log(response);
                    if (response.status === "success") {
                        $("#cancel-order").click();
                        window.location.replace(indexUrl);
                    } else {
                        alert("Table Settlement Failed");
                    }
                },
                error: function(error) {
                    console.log(error);
                    alert("Table Settlement Failed");
                },
            });

        }

        function printTable(tableId) {
            event.stopPropagation()
            let csrf_token = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: billTableUrl,
                type: "POST",
                data: {
                    tableId: tableId,
                },
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                },
                contentType: "application/x-www-form-urlencoded",
                success: function(response) {
                    console.log(response);
                    if (response.status === "success") {
                        window.location.replace(indexUrl);
                    } else {
                        alert("Table Billing Failed");
                    }
                },
                error: function(error) {
                    console.log(error);
                    alert("Table Billing Failed");
                },
            });
        }
    </script>
</x-pos-layout>
