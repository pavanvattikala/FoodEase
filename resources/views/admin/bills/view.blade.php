<x-master-layout>
    @section('title', 'Edit Bill')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bill') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Bill Form -->
                    <form method="POST" action="{{ route('admin.bills.update', $bill->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Bill Number -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Bill Number</label>
                            <input type="text" id="name" name="name"
                                value="{{ $bill->created_at->format('Ymd') }}{{ $bill->id }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('name')
                                <div class="text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Grand Total -->
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Grand Total</label>
                            <input type="number" min="0.00" max="10000.00" step="0.01" id="price"
                                name="price" value="{{ $bill->grand_total }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('price')
                                <div class="text-sm text-red-500">{{ $message }}</div>
                            @enderror
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
                                                Order id</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                KOT</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Table Id</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Order Type</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Waiter Id</th>
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
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->table_id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_type->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->waiter_id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <ul>
                                                        @foreach ($order->orderDetails as $orderDetail)
                                                            <li>{{ $orderDetail->menu->name }} -
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

                        <!-- Update Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-master-layout>
