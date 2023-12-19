<x-waiter-layout>
    <div class="container w-full px-2 py-2 mx-2">
        @foreach (App\Enums\TableLocation::cases() as $location)
            <div class="mb-4">
                <h2 class="flex justify-between text-xl font-semibold mb-2 cursor-pointer border border-gray-300 rounded p-2 bg-gray-100" onclick="toggleDropdown('{{ $location->name  }}')">
                    {{ $location->name  }} Tables
                    <span id="{{ $location->name }}Arrow">&#9662;</span>
                </h2>
                <div id="{{ $location->name }}Dropdown" class="flex flex-wrap">
                    @foreach ($tables->where('location', $location) as $table)

                    <div id="table{{ $table->id }}" style="margin: 1rem" onclick="closeTable({{ $table->id }})" class="w-40 m-4 p-4 bg-red-500 text-center rounded">
                        <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                        <div class="flex flex-wrap">
                            <p class="text-white" id="timer{{ $table->id }}"></p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            getAllTables();
        });
        
        function toggleDropdown(categoryName) {
            const dropdown = document.getElementById(`${categoryName}Dropdown`);
            const arrow = document.getElementById(`${categoryName}Arrow`);
            dropdown.classList.toggle('hidden');
            arrow.innerHTML = dropdown.classList.contains('hidden') ? '&#9662;' : '&#9652;';
        }

        function closeTable(tableId){

            const url = "{{ url()->route('waiter.table.add.toSession',[],false) }}";
            

            var csrf_token = "{{ csrf_token()  }}";

            $.ajax({
                type: "POST",
                url: url,
                data: { tableId: tableId},  
                headers: { 'X-CSRF-TOKEN': csrf_token },
                contentType:'application/x-www-form-urlencoded',
                success: function (response) {
                    console.log('table is selected:', response.message);

                    if(response.message == "true"){
                        alert("Table "+tableId+" is selected" );
                        window.location.replace(" {{ route('waiter.order.step.one') }}");
                    }

                    else{
                        alert("You cannot select it" );
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }

            // JavaScript to update the timer
            function updateTimer(element, updatedAt) {
                setInterval(function () {
                    const now = new Date();
                    const diff = now - new Date(updatedAt);
                    const hours = Math.floor(diff / 3600000);
                    const minutes = Math.floor((diff % 3600000) / 60000);
                    const seconds = Math.floor((diff % 60000) / 1000);

                    const timerText = `${hours}h ${minutes}m ${seconds}s`;
                    element.textContent = timerText;
                }, 1000); // Update every second
            }

            function getAllTables(){
                // Call updateTimer function for each table
                const tables = {!! json_encode($tables) !!}; // Assuming $tables is an array of table data
                tables.forEach(table => {
                    const timerElement = document.getElementById(`timer${table.id}`);
                    updateTimer(timerElement, table.updated_at);
                });
            }
    </script>
</x-waiter-layout>
