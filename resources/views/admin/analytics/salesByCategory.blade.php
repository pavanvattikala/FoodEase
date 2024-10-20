<x-master-layout>
    @section('title', 'Sales By Category')

    @include('components.analytics.datatable')
    <style>

    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <!-- search by date -->
            <x-search-by-date />

            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                            <table id="sales-by-category-table" class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Sno
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Category
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            No of Sales
                                        </th>
                                        <th scope="col"
                                            class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                            Total Amount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="sales-by-category-table-body">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"
                                            class="py-3 px-6 text-xl font-medium text-gray-700 uppercase">
                                            Total Sales Amount:</td>
                                        <td id="total-sales-amount"
                                            class="py-3 px-6 text-xl font-medium text-gray-900 "></td>
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
        var SalesByItemData = {};
        const tableBody = document.getElementById("sales-by-category-table-body");
        const tdClass = "py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap";
        const trClass = "bg-white border-b dark:bg-gray-800 dark:border-gray-700";


        document.getElementById("searchByDate").addEventListener("click", function(e) {

            // Disable the button to prevent multiple clicks
            let button = e.target;
            button.disabled = true;

            [startDate, endDate] = getFormattedDates(startDateObject, endDateObject)

            let url =
                `{{ route('reporting.salesByCategoryData', [], false) }}?startDate=${startDate}&endDate=${endDate}`;

            $.ajax({
                url: url,
                method: 'get',
                success: function(response) {

                    $('#sales-by-category-table').DataTable().destroy();

                    SalesByItemData = response.data;

                    console.log(SalesByItemData);

                    populateTable();

                    var filename = `sales-by-category-${startDate} To ${endDate}`;

                    $('#sales-by-category-table').DataTable({
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

                    button.disabled = false;
                },
                error: function(error) {
                    console.error("Error:", error);
                    button.disabled = false;
                }
            });
        });

        function populateTable() {
            clearTable();
            let totalSalesAmount = 0;

            SalesByItemData.forEach((item, index) => {

                // skip if no sales
                if (item.no_of_sales == 0) return;
                var tr = document.createElement("tr");
                tr.className = trClass;
                var sno = document.createElement("td");
                sno.className = tdClass;
                sno.id = "category-" + (index + 1);
                sno.innerHTML = index + 1;
                tr.appendChild(sno);

                var menuItem = document.createElement("td");
                menuItem.className = tdClass;
                menuItem.innerHTML = item.category.toUpperCase();
                tr.appendChild(menuItem);

                var noOfSales = document.createElement("td");
                noOfSales.className = tdClass;
                noOfSales.innerHTML = item.no_of_sales;
                tr.appendChild(noOfSales);


                var totalAmount = document.createElement("td");
                totalAmount.className = tdClass;
                let amount = item.total_amount;
                totalAmount.innerHTML = formatCurrency(amount);
                tr.appendChild(totalAmount);


                // Add to the total sales amount
                totalSalesAmount += amount;

                tableBody.appendChild(tr);
            });

            document.getElementById('total-sales-amount').innerHTML = formatCurrency(totalSalesAmount);


        }

        function clearTable() {
            tableBody.innerHTML = "";
        }
    </script>
</x-master-layout>
