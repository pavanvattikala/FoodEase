<x-pos-layout>
    <style>
        .category-header {
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f5f5f5;
        }

        .table-item {
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            width: 100px;

        }

        .unavailable-table-item {
            cursor: not-allowed;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
            background-color: #f5a5a5;
        }

        .tables-container {
            display: flex;
            flex-wrap: wrap;
        }

        .mw-60 {
            width: 60%;
        }

        .mw-40 {
            width: 40%;
        }

        .btn {
            background-color: rgb(55, 150, 55);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;

        }
    </style>
    <div class="flex flex-row mb-2 mt-2" id="table-main-nav">
        <div class="flex flex-row mw-60">
            <h1>Table View</h1>
        </div>
        <div id="order-type-options" class="mw-40 flex flex-row align-middle" style="justify-content: space-evenly">
            <button class="btn h-full" id="reload" onclick="location.reload()"><i class="fa fa-refresh"
                    aria-hidden="true"></i>
            </button>
            <button class="btn h-full" id="Takeaway">Pick Up</button>
        </div>

    </div>
    <div class="container" id="select-tables">
        @foreach (App\Enums\TableLocation::cases() as $location)
            <div class="mb-4">
                <h2 class="category-header">{{ $location->name }} Tables</h2>
                <div class="tables-container">
                    @foreach ($tables->where('location', $location) as $table)
                        @if ($table->status === App\Enums\TableStatus::Available)
                            <div id="{{ $table->id }}" onclick="selectTable({{ $table->id }})" class="table-item">
                                <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                                <p>Guests: {{ $table->guest_number }}</p>
                            </div>
                        @elseif($table->status === App\Enums\TableStatus::Unavaliable)
                            <div class="unavailable-table-item">
                                <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                                <a href="{{ route('pos.table.checkout', $table->id) }}"
                                    class="btn btn-primary">Checkout</a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        $("#Takeaway").on("click", function() {

            //tableid -1 for takeaway
            selectTable(-1);
        });

        function selectTable(tableId) {
            const url = "{{ route('pos.table.add.toSession', [], false) }}";
            var csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    tableId: tableId,
                },
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    console.log('table is selected:', response.message);

                    if (response.message == "true") {
                        window.location.replace(" {{ route('pos.main') }}");
                    } else {
                        alert("You cannot select it");
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>

</x-pos-layout>
