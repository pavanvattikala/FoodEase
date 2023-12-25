import flatpickr from "flatpickr";
document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d", // Customize the date format as needed
        onChange: function (selectedDates, dateStr) {
            // Handle date selection, e.g., filter bills based on the selected date
            console.log(selectedDates, dateStr);
        },
    });
});
