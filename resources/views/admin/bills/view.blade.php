<x-master-layout>
    @section('title', 'View Bill')

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bill #{{ $bill->created_at->format('Ymd') }}{{ $bill->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-end gap-3 mb-6">
                <a href="#" onclick="window.print(); return false;"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-print mr-2"></i> Print Bill
                </a>
                <a href="{{ route('admin.bills.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-sm transition-colors">
                    Back to Bills List
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800">Bill Information</h3>
                </div>
                {{-- Flexbox implementation starts here --}}
                <div class="flex flex-wrap p-6">
                    <div class="w-full sm:w-1/2 lg:w-1/4 p-3">
                        <span class="block text-sm font-medium text-gray-500">Bill Number</span>
                        <p class="mt-1 text-md text-gray-900 font-semibold">
                            {{ $bill->created_at->format('Ymd') }}{{ $bill->id }}</p>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/4 p-3">
                        <span class="block text-sm font-medium text-gray-500">Grand Total</span>
                        <p class="mt-1 text-md text-gray-900 font-bold">Rs {{ number_format($bill->grand_total, 2) }}
                        </p>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/4 p-3">
                        <span class="block text-sm font-medium text-gray-500">Order Type</span>
                        <p class="mt-1 text-md text-gray-900">
                            @if ($bill->table_id == null)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Take Away
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Dine In - {{ $bill->table->name }}
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/4 p-3">
                        <span class="block text-sm font-medium text-gray-500">Status</span>
                        @if ($bill->trashed())
                            <p
                                class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-700 text-white">
                                Bill Deleted
                            </p>
                        @else
                            <p
                                class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Cleared
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800">Order Details (KOTs)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    KOT ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waiter</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Items</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bill->orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $order->KOT }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $order->waiter->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <ul class="list-disc list-inside">
                                            @foreach ($order->orderDetails as $orderDetail)
                                                <li>{{ $orderDetail->menu->name }} x {{ $orderDetail->quantity }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No orders
                                        found for this bill.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-master-layout>
