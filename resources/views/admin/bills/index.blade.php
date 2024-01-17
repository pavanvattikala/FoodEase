<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex m-2 p-2 justify-between">
                <a href="{{ route('admin.menus.create') }}"
                    class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">New Menu</a>

                <div class="date-pickers flex flex-row justify-between">
                    <div class="">
                        <label for="startDatePicker">Start Date</label>
                        <input type="text" class="datepicker" id="startDatePicker" placeholder="Select Start date">
                    </div>
                    <div class="">
                        <label for="endDatePicker">End Date</label>
                        <input type="text" class="datepicker" id="endDatePicker" placeholder="Select End date">
                    </div>
                    <div class="">
                        <button id="searchByDate"
                            class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Search</button>
                    </div>
                </div>
            </div>


            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                            <table class="min-w-full">
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
            startDate = new Date(getSelectPickrFormattedDate(startDateObject));
            endDate = getSelectPickrFormattedDate(endDateObject);


            if (startDate > endDate) {
                alert("Start date should be less than end date");
                return;
            }

            startDate = formatDateToYYYYMMDD(startDate);
            endDate = formatDateToYYYYMMDD(endDate);
            console.log("Start Date:", startDate);
            console.log("End Date:", endDate);

            let url = "{{ route('admin.bills.by.date', [], false) }}";
            url += "?startDate=" + startDate;
            url += "&endDate=" + endDate;

            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    var bills = data.bills;
                    var tableBody = document.getElementById("bills-table-body");
                    tableBody.innerHTML = bills;
                    let noOfBills = tableBody.rows.length;

                    let sno = 1;
                    for (let i = 0; i < noOfBills; i++) {
                        let bill = bills[i];
                        let billSno = tableBody.rows[i].cells[0];
                        billSno.innerHTML = sno;
                        sno++;
                    }

                },
                error: function(error) {
                    // Handle the error response
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
</x-admin-layout>
