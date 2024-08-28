<x-master-layout>
    @section('title', 'Sales By Item')

    @include('components.analytics.datatable')
    <style>
        #table-sales-by-item {
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
            <!-- search by date -->
            <x-search-by-date />

            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                            <table id="sales-by-item-table" class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Sno
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Menu Item
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            No of Sales
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Price
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Total Amount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="sales-by-item-table-body">

                                </tbody>
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


            if (startDate > endDate) {
                alert("Start date should be less than end date");
                return;
            }

            startDate = formatDateToYYYYMMDD(startDate);
            endDate = formatDateToYYYYMMDD(endDate);

            let url =
                `{{ route('reporting.salesByItemData', [], false) }}?startDate=${startDate}&endDate=${endDate}`;

            $.ajax({
                url: url,
                method: 'get',
                success: function(response) {
                    $('#sales-by-item-table').DataTable().destroy();
                    let salesByItem = response.data;
                    let tableBody = document.getElementById("sales-by-item-table-body");
                    tableBody.innerHTML = salesByItem;

                    var filename = `sales-by-item-${startDate} To ${endDate}`;

                    $('#sales-by-item-table').DataTable({
                        "paging": true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'csv',
                                filename: filename
                            },
                            {
                                extend: 'excel',

                                filename: filename
                            },
                            {
                                extend: 'pdf',

                                filename: filename
                            },
                            {
                                extend: 'print',

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
    </script>
</x-master-layout>
