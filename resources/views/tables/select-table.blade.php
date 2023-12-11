<x-waiter-layout>
    <div class="container w-full px-2 py-2 mx-2">
        @foreach (App\Enums\TableLocation::cases() as $location)
            <div class="mb-4">
                <h2 class="flex items-center justify-between text-xl font-semibold mb-2 cursor-pointer border border-gray-300 rounded p-2 bg-gray-100" onclick="toggleDropdown('{{ $location->name  }}')">
                    {{ $location->name  }} Tables
                    <span id="{{ $location->name }}Arrow">&#9662;</span>
                </h2>
                <div id="{{ $location->name }}Dropdown" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 hidden">
                    @foreach ($tables->where('location', $location) as $table)
                        @php
                            $color ="grey";
                            if($table->status == \App\Enums\TableStatus::Pending){
                                $color="red";
                            }
                            else if($table->status == \App\Enums\TableStatus::Available){
                                $color="green";
                            }
                        @endphp
                        <div id="{{ $table->id }}"  onclick="selectTable({{ $table->id }})" class="w-40 mb-4 p-4 bg-{{ $color }}-500 border border-gray-300 rounded cursor-pointer text-center" >
                            <h2 class="text-xl font-semibold mb-2">{{ $table->name }}</h2>
                            <div class="flex flex-wrap ">
                                <p>Guests: {{ $table->guest_number }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function toggleDropdown(categoryName) {
            const dropdown = document.getElementById(`${categoryName}Dropdown`);
            const arrow = document.getElementById(`${categoryName}Arrow`);
            dropdown.classList.toggle('hidden');
            arrow.innerHTML = dropdown.classList.contains('hidden') ? '&#9662;' : '&#9652;';
        }

        function selectTable(tableId){

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
    </script>
</x-waiter-layout>
