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
</script>
