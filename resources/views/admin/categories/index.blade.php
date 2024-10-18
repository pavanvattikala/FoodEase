<x-master-layout>
    @section('title', 'Catergories')

    <style>
        .drag-handle {
            cursor: move;
        }
    </style>

    <div class="flex justify-end m-2 p-2">
        <a href="{{ route('admin.categories.create') }}"
            class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">New Category</a>
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-md sm:rounded-lg">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">

                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Name
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Image
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Description
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="sortable">
                            @foreach ($categories as $category)
                                <tr data-categoryId={{ $category->id }}
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white sortable-handle">
                                        <span class="drag-handle"><i class="fas fa-arrows-alt"></i></span>
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $category->name }}
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img src="{{ Storage::url($category->image) }}" class="w-16 h-16 rounded">
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $category->description }}
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                class="px-4 py-2 bg-green-500 hover:bg-green-700 rounded-lg  text-white">Edit</a>
                                            <form class="px-4 py-2 bg-red-500 hover:bg-red-700 rounded-lg text-white"
                                                method="POST"
                                                action="{{ route('admin.categories.destroy', $category->id) }}"
                                                onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/Sortable.js') }}"></script>


    <script>
        $(document).ready(function() {
            new Sortable(document.querySelector('.sortable'), {
                handle: '.drag-handle',
                animation: 150,
                onUpdate: function(evt) {
                    // Handle category reordering here
                    var index = 1;
                    const reorderedCategories = Array.from(evt.target.children).map(row => {
                        return {
                            id: row.dataset
                                .categoryid,
                            rank: index++
                        };
                    });

                    //Send a POST request to your API to update the category order

                    $.ajax({
                        type: "POST",
                        url: '{{ route('admin.categories.updateRanks') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            updatedRankings: reorderedCategories
                        },
                        contentType: 'application/x-www-form-urlencoded',
                        success: function(response) {
                            if (response.status === 'success') {
                                console.log('Ranks updated successfully:', response
                                    .message);
                            } else {
                                console.error('Error updating ranks:', response
                                    .message);
                            }
                        },
                        error: function(error) {
                            console.error('Error updating ranks:', error);
                        }
                    });
                }
            });
        });
    </script>
</x-master-layout>
