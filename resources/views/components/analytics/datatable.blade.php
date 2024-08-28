<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
<link rel="stylesheet" href="{{ mix('css/flatpickr.css') }}">
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
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
        integerPart = integerPart.replace(/\B(?=(\d{2})+(?!\d))/g, ",");

        // Combine the integer part, decimal part, and add the Rupee symbol
        return `₹ ${integerPart}.${decimalPart}`;
    }
</script>
