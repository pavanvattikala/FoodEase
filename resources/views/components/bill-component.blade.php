<tr class="bg-white border-b hover:bg-gray-50">
    <td class="px-6 py-4 text-sm font-medium text-gray-900">
        {{ $index + 1 }}
    </td>
    <td class="px-6 py-4 text-sm text-gray-700">
        {{ $bill->bill_id }}
    </td>
    <td class="px-6 py-4 text-sm text-gray-700">
        @if ($bill->table_id == null)
            {{-- Take Away Badge --}}
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                Take Away
            </span>
        @else
            {{-- Dine In Badge --}}
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                Dine In - {{ $bill->table->name }}
            </span>
        @endif
    </td>
    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
        Rs {{ number_format($bill->grand_total, 2) }}
    </td>
    <td class="px-6 py-4 text-right">
        <div class="flex items-center justify-end space-x-4">
            @if ($bill->trashed())
                {{-- Deleted State Badge --}}
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-700 text-white">
                    Deleted
                </span>
            @else
                {{-- Action Icons --}}
                <a href="{{ route('admin.view.bill', $bill->id) }}" title="View Bill"
                    class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.stream.bill', $bill->id) }}" title="Print Bill" target="_blank"
                    class="text-green-600 hover:text-green-800 transition-colors">
                    <i class="fas fa-print"></i>
                </a>
                <form method="POST" action="{{ route('admin.bill.destroy', $bill->id) }}"
                    onsubmit="return confirm('Are you sure you want to delete this bill?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Delete Bill"
                        class="text-red-600 hover:text-red-800 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            @endif
        </div>
    </td>
</tr>
