<x-master-layout>
    @section('title', 'View Menus')
    <style>
        main {
            margin: 0 !important;
            padding: 0 !important;
        }

        .container {
            display: flex;
            flex-direction: row;
            height: 100%;
        }

        .category-sidebar {
            width: 20%;
            background-color: #f7fafc;
            padding: 1rem;
            border-right: 1px solid #e2e8f0;
            max-height: 80%;
            overflow-y: auto;
        }

        .content {
            width: 80%;
            padding: 1rem;
        }

        .category-window {
            margin: 1rem;
        }

        .category-item {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.3s ease;
        }

        .category-item:hover {
            background-color: #e2e8f0;
        }

        .category-item.active {
            background-color: #cbd5e0;
            font-weight: bold;
        }

        .category-window.hidden {
            display: none;
        }

        #main-window {
            display: flex;
            flex-wrap: wrap;
        }
    </style>

    <div class="flex justify-end m-2 p-2">
        <a href="{{ route('admin.menus.create') }}"
            class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">New Menu</a>
    </div>

    <div class="container">
        <!-- Left category-sidebar for Categories -->
        <div class="category-sidebar">
            <h2 class="font-bold text-xl text-center pb-4">Categories</h2>
            @foreach ($categoriesWithMenu as $category)
                <div class="category-item" onclick="showCategory('{{ $category->id }}')">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>

        <!-- Right Content for Menus -->
        <div class="content">
            @foreach ($categoriesWithMenu as $category)
                <div id="category-{{ $category->id }}" class="category-window hidden">
                    <h1 class="text-center font-bold capitalize">{{ $category->name }}</h1>
                    <table class="min-w-full category_menu">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Name
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Short Code
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Price
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    Options
                                </th>
                            </tr>
                        </thead>
                        <tbody class="menus-body">
                            @foreach ($category->menus as $menu)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $menu->name }}
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $menu->shortcode }}
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $menu->price }}
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                                class="px-4 py-2 bg-green-500 hover:bg-green-700 rounded-lg text-white">Edit</a>
                                            <form method="POST" action="{{ route('admin.menus.destroy', $menu->id) }}"
                                                onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-500 hover:bg-red-700 rounded-lg text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Function to show the selected category
        function showCategory(categoryId) {
            // Hide all categories
            document.querySelectorAll('.category-window').forEach(function(category) {
                category.classList.add('hidden');
            });

            // Remove active class from all category items
            document.querySelectorAll('.category-item').forEach(function(item) {
                item.classList.remove('active');
            });

            // Show the selected category
            document.getElementById('category-' + categoryId).classList.remove('hidden');

            // Add active class to the selected category item
            document.querySelector('.category-item[onclick="showCategory(\'' + categoryId + '\')"]').classList.add(
                'active');
        }

        // Show the first category by default
        document.addEventListener('DOMContentLoaded', function() {
            let firstCategory = document.querySelector('.category-item');
            if (firstCategory) {
                firstCategory.click();
            }
        });
    </script>
</x-master-layout>
