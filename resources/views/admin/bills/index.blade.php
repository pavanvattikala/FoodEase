@php
    $adminLayout = 'admin-layout';
    $billerLayout = 'biller-layout';

    $user = auth()->user();

    if ($user->hasPermission(App\Enums\UserRole::Admin)) {
        $currentLayout = $adminLayout;
    } elseif ($user->hasPermission(App\Enums\UserRole::Biller)) {
        $currentLayout = $billerLayout;
    }

@endphp
{{-- choosing dynamic layouts based on user permissions --}}
<x-dynamic-component :component="$currentLayout">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
    <style>
        #table-bills {
            color: grey !important;
        }

        main {
            padding: 0px !important;
            margin: 0px !important;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div class="flex m-2 p-2 justify-between">
                <a href="{{ route('pos.index') }}"
                    class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">New Bill</a>

                <div class="date-pickers flex flex-row justify-between">
                    <div class="">
                        <label for="startDatePicker">Start Date</label>
                        <input type="text" class="datepicker" id="startDatePicker" placeholder="Select Start date">
                    </div>
                    <div class="">
                        <label for="endDatePicker">End Date</label>
                        <input type="text" class="datepicker" id="endDatePicker" placeholder="Select End date">
                    </div>
                    <!-- only show if current layout is admin -->
                    @if ($currentLayout == $adminLayout)
                        <div class="flex items-center">
                            <input type="checkbox" id="includeDeleted" name="includeDeleted">
                            <label for="includeDeleted" class="ml-2">Include <br> Deleted Bills</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="onlyDeleted" name="onlyDeleted">
                            <label for="onlyDeleted" class="ml-2">Show Only <br> Deleted Bills</label>
                        </div>
                    @endif
                    <div class="">
                        <button id="searchByDate"
                            class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Search</button>
                    </div>

                </div>
            </div>

            <div class="flex m-2 p-2 justify-start">
                <h1>Total Sales</h1>
                <h1 id="totalSales" class="ml-2"></h1>
            </div>



            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                            <table id="bills-table" class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Sno
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Bill Id
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Bill Type
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Grand Total
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Options
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="bills-table-body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let startDateObject;
        let endDateObject;

        document.addEventListener("DOMContentLoaded", function() {
            startDateObject = flatpickr("#startDatePicker", {
                dateFormat: "d-M-y",
                defaultDate: "today",
                maxDate: "today",
            });

            endDateObject = flatpickr("#endDatePicker", {
                dateFormat: "d-M-y",
                defaultDate: "today",
                maxDate: "today",
            });

            $("#searchByDate").trigger("click");


        });

        function getSelectPickrFormattedDate(date) {
            return date.selectedDates[0]; //.format('MM-D-YYYY');
        }

        document.getElementById("searchByDate").addEventListener("click", function() {
            let startDate = new Date(getSelectPickrFormattedDate(startDateObject));
            let endDate = getSelectPickrFormattedDate(endDateObject);
            let includeDeleted = document.getElementById("includeDeleted").checked ? 'true' : 'false';
            let onlyDeleted = document.getElementById("onlyDeleted").checked ? 'true' : 'false';

            if (startDate > endDate) {
                alert("Start date should be less than end date");
                return;
            }

            startDate = formatDateToYYYYMMDD(startDate);
            endDate = formatDateToYYYYMMDD(endDate);

            let url =
                `{{ route('admin.bills.by.date', [], false) }}?startDate=${startDate}&endDate=${endDate}&includeDeleted=${includeDeleted}&onlyDeleted=${onlyDeleted}`;

            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#bills-table').DataTable().destroy();
                    let bills = data.bills;
                    let tableBody = document.getElementById("bills-table-body");
                    tableBody.innerHTML = bills;

                    let totalSales = data.totalSales;

                    $("#totalSales").text(totalSales);

                    var filename = `Bills-${startDate}-${endDate}`;

                    $('#bills-table').DataTable({
                        "paging": true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'csv',
                                exportOptions: {
                                    columns: ':lt(4)'
                                },
                                filename: filename
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':lt(4)'
                                },
                                filename: filename
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    columns: ':lt(4)'
                                },
                                filename: filename
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':lt(4)'
                                },
                                filename: filename
                            }
                        ]
                    });
                },
                error: function(error) {
                    console.error("Error:", error);
                }
            });
        });

        function formatDateToYYYYMMDD(date) {
            // Extract year, month, and date
            let year = date.getFullYear();
            // Months are zero-based, so we add 1 to get the correct month
            let month = date.getMonth() + 1;
            let day = date.getDate();

            // Format the date as YYYY-MM-DD
            let formattedDate = `${year}-${month < 10 ? '0' : ''}${month}-${day < 10 ? '0' : ''}${day}`;

            return formattedDate;
        }
    </script>
</x-dynamic-component>
