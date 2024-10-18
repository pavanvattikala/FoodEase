<x-master-layout>
    @section('title', 'View Menus')
    <style>
        main {
            margin: 0 !important;
            padding: 0 !important;
        }

        .category-window {
            margin: 1rem;
            display: flex;
            flex-direction: column;
            width: 45%;
            overflow-y: auto;
            /* Changed from scroll to auto */
            max-height: 500px;
        }

        .menus-body {
            max-height: 400px;
            /* Adjusted max height */
            overflow-y: auto;
            /* Changed from scroll to auto */
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

    <div class="flex flex-wrap" id="main-window">
        @foreach ($categoriesWithMenu as $category)
            <div class="category-window">
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
</x-master-layout>
