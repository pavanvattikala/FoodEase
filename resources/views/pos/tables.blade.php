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

        .table-item {
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            width: 100px;

        }

        .unavailable-table-item {
            cursor: not-allowed;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
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
            /* Adjust the width as needed */
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .modal-title {
            font-size: 18px;
        }

        .modal-close {
            cursor: pointer;
            font-size: 20px;
        }

        .modal-body {
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }
    </style>
    <div class="flex flex-row mb-2 mt-2" id="table-main-nav">
        <div class="flex flex-row mw-60">
            <h1 class="font-bold">Table View</h1>
        </div>
        <div id="order-type-options" class="mw-40 flex flex-row align-middle" style="justify-content: space-evenly">
            <button class="btn h-full" id="reload" onclick="location.reload()"><i class="fa fa-refresh">Refresh</i>
            </button>
            <button class="btn h-full" id="Takeaway">Pick Up</button>
        </div>

    </div>
    <div class="flex flex-row mb-2 mt-2" id="table-second-nav">
        <div class="flex flex-row mw-60 new_options" style="justify-content: flex-start">
            {{-- <div>
                <a href="{{ route('reservations.step.one') }}">
                    <button class="btn">Table Reservation</button>
                </a>
            </div>
            <div>
                <button class="btn">contatless</button>
            </div> --}}

        </div>
        <div id="table_type_options" class="mw-40 flex flex-row align-middle" style="justify-content: space-evenly">

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

                            if ($table->taken_at != null) {
                                $tableTotal = $table->orders
                                    ->where('status', '!=', App\Enums\OrderStatus::Closed)
                                    ->sum('total');
                            }

                            $isTableToBeBilled = false;
                            $isTableToBePaid = false;

                            if ($table->status == App\Enums\TableStatus::Printed) {
                                $isTableToBePaid = true;
                            }
                            if (
                                $table->status != App\Enums\TableStatus::Printed &&
                                $table->status != App\Enums\TableStatus::Available
                            ) {
                                $isTableToBeBilled = true;
                            }
                        @endphp

                        <div id="{{ $table->id }}" onclick="selectTable({{ $table->id }})"
                            class="table-item text-white text-center" style="background-color: {{ $tableColor }}"
                            data-table-staus="{{ $table->status->value }}">

                            <p id="elapsed-time" data-taken-at="{{ $table->taken_at }}"> </p>

                            <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                            @if ($table->taken_at != null && $tableTotal)
                                <p>{{ $tableTotal }}</p>
                            @endif

                            <div class="flex table-options justify-center align-middle">
                                @if ($isTableToBeBilled == true)
                                    <div onclick="showorders({{ $table->id }})">
                                        <button style="background-color: white; color: black" class="btn"
                                            id="settle-order"><i class="fas fa-eye"></i></button>
                                    </div>
                                    <div onclick="printTable({{ $table->id }})">
                                        <button style="background-color: white; color: black" class="btn"
                                            id="print-table"><i class="fas fa-print"></i></button>
                                    </div>
                                @endif
                                @if ($isTableToBePaid == true)
                                    <div onclick="setttleTable({{ $table->id }})">
                                        <button style="background-color: white; color: black" class="btn"
                                            id="settle-order"><i class="fas fa-save"></i></button>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach


    </div>

    {{-- modal window for payment options --}}

    <div id="paymentModal" class="modal">
        <div class="modal-overlay" tabindex="-1" data-close="paymentModal"></div>
        <div class="modal-container bg-white mx-auto mt-10 p-6 rounded-lg shadow-lg w-1/2">
            <div class="modal-header flex justify-between items-center border-b pb-4">
                <span class="text-2xl font-bold">Payment Options</span>
                <span class="modal-close cursor-pointer" data-close="paymentModal">&times;</span>
            </div>
            <div class="modal-body mt-4 flex flex-wrap justify-between">
                <input type="number" value="0" id="paymentTableId" hidden>

                @foreach ($paymentTypes as $payment)
                    <div>
                        <input type="radio" name="payment-type" value="{{ $payment }}"
                            id="{{ $payment }}">
                        <label>{{ $payment }}</label>
                    </div>
                @endforeach
                {{-- <div>
                    <input id="cash" type="radio" name="payment-type" value="cash" selected="true">
                    <label>Cash</label>
                </div>
                <div>
                    <input id="card" type="radio" value="card" name="payment-type">
                    <label>Card</label>
                </div>
                <div>
                    <input id="upi" type="radio" value="upi" name="payment-type">
                    <label>UPI</label>
                </div>
                <div>
                    <input type="radio" name="payment-type" value="radio" id="due">
                    <label>Due</label>
                </div> --}}
            </div>
            <div class="modal-footer mt-4 flex justify-end">
                <button type="button" class="btn  mr-2" data-close="paymentModal">Close</button>
                <button type="button" class="btn bg-green-500" id="savePaymentDataBtn">Save And Settle Table</button>
            </div>
        </div>
    </div>

    <script>
        // global variables

        var runningTables = [];

        var billTableUrl = "{{ route('pos.table.bill', [], false) }}";
        var indexUrl = "{{ route('pos.tables', [], false) }}";
        var settleTableUrl = "{{ route('pos.table.settle', [], false) }}";
        const selectTableURL = "{{ route('pos.main', [], false) }}";

        // DOM loaded
        $(document).ready(function() {
            // Select table
            // Select all elements with class "table-item" where data-table-staus is not available
            $(".table-item[data-table-staus!='available']").each(function() {
                // Get the table ID and taken_at value
                var tableId = $(this).prop("id");
                var takenAt = $(this).find("#elapsed-time").attr("data-taken-at");

                // Push an object with tableId and takenAt into the runningTables array
                runningTables.push({
                    tableId: tableId,
                    takenAt: takenAt
                });
            });
        });


        $("#Takeaway").on("click", function() {
            selectTable("takeaway");
        });

        function selectTable(tableId) {
            const url = selectTableURL + "?tableId=" + tableId;
            window.location.href = url;
        }

        setInterval(() => {
            runningTables.forEach(table => {
                var elapsedString = getElapsedMinutes(table.takenAt);
                $("#" + table.tableId).find("#elapsed-time").text(elapsedString);
            }, 1000);
        });

        function getElapsedMinutes(originalTime) {
            var takenTime = new Date(originalTime);
            var currentTime = new Date();
            var elapsedTime = currentTime - takenTime;
            var elapsedMinutes = Math.floor(elapsedTime / (1000 * 60));
            var elapsedHours = Math.floor(elapsedTime / (1000 * 60 * 60));
            let elapsedString = "";

            if (elapsedHours > 0) {
                elapsedMinutes = elapsedMinutes - (elapsedHours * 60);
            }
            if (elapsedHours > 0) {
                elapsedString = elapsedString + elapsedHours + "h ";
            }
            if (elapsedMinutes > 0) {
                elapsedString = elapsedString + elapsedMinutes + "m";
            }


            return elapsedString;
        }

        // Payment Modal Open or settle table

        function setttleTable(tableId) {
            event.stopPropagation()
            document.getElementById("paymentModal").style.display = "block";
            $("#cash").prop("checked", true);
            document.getElementById("paymentTableId").value = tableId;
        }

        // Payment Modal Close
        document
            .querySelectorAll('[data-close="paymentModal"]')
            .forEach(function(element) {
                element.addEventListener("click", function() {
                    document.getElementById("paymentModal").style.display = "none";
                });
            });

        // Customer Modal Save Button
        document
            .getElementById("savePaymentDataBtn")
            .addEventListener("click", function() {
                console.log($("#paymentTableId"));
                const tableId = $("#paymentTableId").val();
                const paymentType = $("input[name='payment-type']:checked").val();
                settleTable(tableId, paymentType);
                document.getElementById("paymentModal").style.display = "none";
            });

        // Customer Modal End


        function settleTable(tableId, paymentType) {

            if (tableId == null || paymentType == null) {
                alert("Table Settlement Failed data is missing reload the page and try again");
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

        function showorders(tableId) {
            event.stopPropagation();

            const url = "{{ route('pos.table.orders', ['tableId' => ':id'], false) }}".replace(':id', tableId);
            window.open(url, '_blank');
        }
    </script>

</x-pos-layout>
