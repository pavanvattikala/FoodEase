<x-master-layout>
    @section('title', 'View Bills')

    @include('components.analytics.datatable')
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


    @php
        $user = Auth::user();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <x-search-by-date>
                <div class="flex m-2 p-2 justify-between">
                    @if ($user->isAdmin())
                        <div class="flex items-center">
                            <input type="checkbox" id="includeDeleted" name="includeDeleted">
                            <label for="includeDeleted" class="ml-2">Include <br> Deleted Bills</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="onlyDeleted" name="onlyDeleted">
                            <label for="onlyDeleted" class="ml-2">Show Only <br> Deleted Bills</label>
                        </div>
                    @endif
                </div>
            </x-search-by-date>

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

                                <tfoot>
                                    <tr>
                                        <td colspan="3"
                                            class="py-3 px-6 text-xl font-medium text-gray-700 uppercase">
                                            Total Sales Amount:</td>
                                        <td id="total-sales-amount"
                                            class="py-3 px-6 text-xl font-medium text-gray-900 "> </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("searchByDate").addEventListener("click", function() {
            let startDate = new Date(getSelectPickrFormattedDate(startDateObject));
            let endDate = getSelectPickrFormattedDate(endDateObject);
            let includeDeleted = document.getElementById("includeDeleted")?.checked ?? 'false';
            let onlyDeleted = document.getElementById("onlyDeleted")?.checked ?? 'false';

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

                    let totalSales = formatCurrency(data.totalSales);

                    $("#total-sales-amount").text(totalSales);

                    var filename = `Bills-${startDate} To ${endDate}`;

                    // if include deleted is checked, change filename to include deleted
                    if (includeDeleted) {
                        filename += "-IncludeDeleted";
                    }

                    // if only deleted is checked, change filename to only deleted
                    if (onlyDeleted) {
                        filename += "-OnlyDeleted";
                    }

                    //set the page title
                    document.title = filename;

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
</x-master-layout>
