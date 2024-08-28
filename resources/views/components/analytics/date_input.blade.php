<div class="flex m-2 p-2 justify-between">
    <div class="date-pickers flex flex-row justify-between">
        <div class="">
            <label for="startDatePicker">Start Date</label>
            <input type="text" class="datepicker" id="startDatePicker" placeholder="Select Start date">
        </div>
        <div class="">
            <label for="endDatePicker">End Date</label>
            <input type="text" class="datepicker" id="endDatePicker" placeholder="Select End date">
        </div>
        {{ $slot }}
        <div class="">
            <button id="searchByDate"
                class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Search</button>
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

    function getFormattedDates(startDateObject, endDateObject) {
        let startDate = new Date(getSelectPickrFormattedDate(startDateObject));
        let endDate = new Date(getSelectPickrFormattedDate(endDateObject));

        if (startDate > endDate) {
            alert("Start date should be less than end date");
            // reset the dates
            endDateObject.setDate("today");
            return;
        }

        startDate = formatDateToYYYYMMDD(startDate);
        endDate = formatDateToYYYYMMDD(endDate);

        return [startDate, endDate];
    }
</script>
