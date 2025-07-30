<x-master-layout>
    @section('title', 'All Bills')
    @include('components.analytics.datatable')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Bills
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="mb-6">
                    <x-search-by-date>
                        @if (Auth::user()->isAdmin())
                            <div class="flex items-center justify-start flex-wrap gap-6 mt-4">
                                <label for="includeDeleted" class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="includeDeleted" name="includeDeleted"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Include Deleted Bills</span>
                                </label>
                                <label for="onlyDeleted" class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="onlyDeleted" name="onlyDeleted"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Show Only Deleted Bills</span>
                                </label>
                            </div>
                        @endif
                    </x-search-by-date>
                </div>

                <div class="overflow-x-auto">
                    <table id="bills-table" class="min-w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    S.No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bill ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bill Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grand Total</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bills-table-body" class="bg-white divide-y divide-gray-200">
                            {{-- AJAX will populate this section --}}
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">
                                    Total Sales Amount:
                                </td>
                                <td id="total-sales-amount" class="px-6 py-4 text-left text-sm font-bold text-gray-900">
                                    {{-- AJAX will populate this --}}
                                </td>
                                <td></td> {{-- Empty cell for alignment --}}
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- The original script block is preserved --}}
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

                    var filename = `Bills-${startDate}-To-${endDate}`;
                    if (includeDeleted) {
                        filename += "-IncludeDeleted";
                    }
                    if (onlyDeleted) {
                        filename += "-OnlyDeleted";
                    }
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
            let year = date.getFullYear();
            let month = date.getMonth() + 1;
            let day = date.getDate();
            return `${year}-${month < 10 ? '0' : ''}${month}-${day < 10 ? '0' : ''}${day}`;
        }
    </script>
</x-master-layout>
