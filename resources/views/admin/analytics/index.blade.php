<x-master-layout>
    @section('title', 'Reporting')

    <!-- show clickable links -->

    <a href="{{ route('reporting.view', ['sales-by-item']) }}"
        class="bg-blue-500 hover:bg-blue-700  font-bold py-2 px-4 rounded">Sales By
        Item</a>

    <a href="{{ route('reporting.view', ['sales-by-category']) }}"
        class="bg-blue-500 hover:bg-blue-700  font-bold py-2 px-4 rounded">Sales By
        Category</a>



</x-master-layout>
