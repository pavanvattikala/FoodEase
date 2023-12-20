<x-admin-layout>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            
        }
        td,th{
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            width: max-content;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full">
            <div class="flex m-2 p-2">
                <a href="{{ route('admin.bills') }}"
                    class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Bill Index</a>
            </div>
            <div class="m-2 p-2 bg-slate-100 rounded">
                <div class="space-y-8 divide-y divide-gray-200 w-1/2 mt-10">
                    <form method="POST" action="{{ route('admin.bills.update', $bill->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700"> Bill No </label>
                            <div class="mt-1">
                                <input type="text" id="name" name="name" value="{{ $bill->created_at->format('Ymd') }}{{ $bill->id }}"
                                    class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                            </div>
                            @error('name')
                                <div class="text-sm text-red-400">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="sm:col-span-6">
                            <label for="price" class="block text-sm font-medium text-gray-700"> Grand Total </label>
                            <div class="mt-1">
                                <input type="number" min="0.00" max="10000.00" step="0.01" id="price" name="price"
                                    value="{{ $bill->grand_total }}"
                                    class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                            </div>
                            @error('price')
                                <div class="text-sm text-red-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="table-auto">
                            <h1>Orders</h1>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order id</th>
                                        <th>KOT</th>
                                        <th>Table Id</th>
                                        <th>Order Type</th>
                                        <th>Waiter Id</th>
                                        <th>Menu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bill->orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->KOT }}</td>
                                            <td>{{ $order->table_id }}</td>
                                            <td>{{ $order->order_type->name }}</td>
                                            <td>{{ $order->waiter_id }}</td>
                                            <td>
                                                <table class="table-auto">
                                                    <thead>
                                                        <tr>Name</tr>
                                                        <tr>Quantity</tr>
                                                        <tr>Price</tr>
                                                        <tr>Total</tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->orderDetails as $orderDetail)
                                                            <tr>
                                                                <td>{{ $orderDetail->menu->name }} </td>
                                                                <td>{{ $orderDetail->quantity }}</td>
                                                                <td>{{ $orderDetail->menu->price }}</td>
                                                                <td>{{ $orderDetail->total }}</td>
                                                                <td>{{ $orderDetail->menu->price * $orderDetail->quantity }}</td>
                                                            </tr>
                                                        
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>

                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="sm:col-span-6 pt-5">
                            <label for="categories" class="block text-sm font-medium text-gray-700">Orders</label>
                            <div class="mt-1">
                            </div>
                        </div>
                        <div class="mt-6 p-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>
