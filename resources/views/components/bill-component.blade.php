<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="bill-sno py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap  ">

    </td>
    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap  ">
        {{ $bill->bill_id }}
    </td>
    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap  ">
        @if ($bill->table_id == null)
            <span class="text-red-500">Take Away</span>
        @else
            Dine In - {{ $bill->table->name }}
        @endif
    </td>
    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap  ">
        {{ $bill->grand_total }}
    </td>
    <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
        <div class="flex space-x-2">
            <a href="{{ route('admin.view.bill', $bill->id) }}"
                class="px-4 py-2 bg-green-500 hover:bg-green-700 rounded-lg  text-white">
                Edit/View
            </a>
            <a href="{{ route('admin.stream.bill', $bill->id) }} " target="_blank"
                class="px-4 py-2 bg-green-500 hover:bg-green-700 rounded-lg  text-white">
                Print
            </a>
            <form class="px-4 py-2 bg-red-500 hover:bg-red-700 rounded-lg text-white" method="POST"
                action="{{ route('admin.tables.destroy', $bill->id) }}" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    </td>
</tr>
