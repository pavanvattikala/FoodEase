<x-master-layout>
    @section('title', 'View Bill')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Bill') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Bill Details -->
                    <div class="mb-4">
                        <h1 class="text-lg font-semibold mb-2">Bill Details</h1>
                        <div class="space-y-4">
                            <!-- Bill Number -->
                            <div>
                                <span class="block text-sm font-medium text-gray-700">Bill Number:</span>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $bill->created_at->format('Ymd') }}{{ $bill->id }}
                                </p>
                            </div>

                            <!-- Grand Total -->
                            <div>
                                <span class="block text-sm font-medium text-gray-700">Grand Total:</span>
                                <p class="mt-1 text-sm text-gray-900">
                                    Rs {{ number_format($bill->grand_total, 2) }}
                                </p>
                            </div>

                            <!-- Table -->
                            <div>
                                <span class="block text-sm font-medium text-gray-700">Table:</span>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if ($bill->table_id == null)
                                        <span class="text-red-500">Take Away</span>
                                    @else
                                        Dine In - {{ $bill->table->name }}
                                    @endif
                                </p>
                            </div>
                            <!-- Status -->

                            <div>
                                <span class="block text-sm font-medium text-gray-700">Status:</span>
                                @if ($bill->trashed())
                                    {{-- Check if the bill is soft-deleted --}}
                                    <p class="px-4 py-2 bg-red-700 rounded-lg  text-white disabled text-center">
                                        This Bill is Deteted
                                    </p>
                                @endif

                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="mb-4">
                        <h1 class="text-lg font-semibold mb-2">Orders</h1>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            KOT</th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Waiter ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Menu</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($bill->orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->KOT }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->waiter->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <ul>
                                                    @foreach ($order->orderDetails as $orderDetail)
                                                        <li>{{ $orderDetail->menu->name }} x
                                                            {{ $orderDetail->quantity }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="flex justify-end">
                        <a href="{{ route('admin.bills.index') }}"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-700 rounded-lg text-white">
                            Back to Bills
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-master-layout>
