<link rel="stylesheet" href="{{ asset('css/datatable/dataTables.min.css') }} ">
<link rel="stylesheet" href="{{ asset('css/datatable/buttons.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('css/flatpickr.css') }}">
<script src="{{ asset('js/datatable/dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.buttons.js') }}"></script>
<script src="{{ asset('js/datatable/buttons.dataTables.js') }}"></script>
<script src="{{ asset('js/datatable/jszip.min.js') }}"></script>
<script src="{{ asset('js/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/datatable/html5.min.js') }}"></script>
<script src="{{ asset('js/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ mix('js/datepicker.js') }}"></script>

<style>
    td,
    th {
        text-align: center !important;
    }

    main {
        padding: 0px !important;
        margin: 0px !important;
    }
</style>


<script>
    function formatCurrency(amount) {

        // Ensure the amount is a number
        amount = parseFloat(amount);

        // If the conversion fails, return an empty string or handle the error
        if (isNaN(amount)) {
            console.error("Invalid amount:", amount);
            return '';
        }
        // Convert the amount to a string with 2 decimal places
        let amountString = amount.toFixed(2);

        // Split the amount into integer and decimal parts
        let [integerPart, decimalPart] = amountString.split('.');

        // Add commas to the integer part according to Indian numbering system

        integerPart = integerPart.replace(/(\d)(?=(\d\d)+\d$)/g, "$1,") // 1,23,45,678

        // Combine the integer part, decimal part, and add the Rupee symbol
        return `₹ ${integerPart}.${decimalPart}`;
    }
</script>
