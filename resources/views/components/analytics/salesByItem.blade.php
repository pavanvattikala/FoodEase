<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="bill-sno py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
        {{ $index + 1 }}
    </td>
    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
        {{ $item->menu }}
    </td>
    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap ">
        {{ $item->no_of_sales }}
    </td>
    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap    ">
        {{ $item->price }}
    </td>

    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap  ">
        {{ $item->price * $item->no_of_sales }}
    </td>
</tr>
