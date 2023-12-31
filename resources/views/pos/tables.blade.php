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
    </style>

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
                                <p>Table Unavailable</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function selectTable(tableId) {
            var tableName = $("#" + tableId + " h2").text();

            const url = "{{ route('pos.table.add.toSession', [], false) }}";
            var csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    tableId: tableId,
                    tableName: tableName
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
