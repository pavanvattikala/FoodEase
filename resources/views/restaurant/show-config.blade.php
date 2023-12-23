<!-- resources/views/restaurant/config.blade.php -->
<x-admin-layout>
    <style>
        li{
            list-style: none;
            margin-bottom: 20px;
        }
        input{
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
        }
        label{
            display: inline-block;
            width: 300px;
        }
        button[type=submit]{
            padding: 10px 20px;
            background: #1fb118;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <h1 class="text-center text-lg font-bold">Restaurant Configurations</h1>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('restaurant.update.config') }}">
        @csrf
        <input type="hidden" name="id" value="{{  $restaurantConfig['id'] }}">
        <ul>
            <li>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ $restaurantConfig['name'] }}">
            </li>
            <li>
                <label for="tagline">Tagline:</label>
                <input type="text" id="tagline" name="tagline" value="{{ $restaurantConfig['tagline'] }}">
            </li>
            <li>
                <label for="address">Address:</label>
                <textarea id="address" name="address">{{ $restaurantConfig['address'] }}</textarea>
            </li>
            <li>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="{{ $restaurantConfig['phone'] }}">
            </li>
            <li>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ $restaurantConfig['email'] }}">
            </li>
            <li>
                <label for="website">Website:</label>
                <input type="text" id="website" name="website" value="{{ $restaurantConfig['website'] }}">
            </li>
            <li>
                <label for="pending_order_sync_time">Pending Order Sync Time:</label>
                <select name="pending_order_sync_time" id="pending_order_sync_time">
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '5')  selected @endif value="5">5 Seconds</option>
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '15') selected @endif value="15">15 Seconds</option>
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '30') selected @endif value="10">30 Seconds</option>
                    <option @if ($restaurantConfig['pending_order_sync_time'] == '60') selected @endif value="60">1 Minitute</option>
                </select>
            </li>
            <li>
                <label for="waiter_sync_time">waiter Sync Time:</label>
                <select name="waiter_sync_time" id="waiter_sync_time">
                    <option @if ($restaurantConfig['waiter_sync_time'] == '5')  selected @endif value="5">5 Seconds</option>
                    <option @if ($restaurantConfig['waiter_sync_time'] == '15') selected @endif value="15">15 Seconds</option>
                    <option @if ($restaurantConfig['waiter_sync_time'] == '30') selected @endif value="10">30 Seconds</option>
                    <option @if ($restaurantConfig['waiter_sync_time'] == '60') selected @endif value="60">1 Minitute</option>
                </select>
            </li>
            <li>
                <label for="minimum_delivery_time">Minimum Delivery Time (minutes):</label>
                <select name="minimum_delivery_time" id="minimum_delivery_time">
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '5')  selected @endif value="5">5 Seconds</option>
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '15') selected @endif value="15">15 Seconds</option>
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '30') selected @endif value="10">30 Seconds</option>
                    <option @if ($restaurantConfig['minimum_delivery_time'] == '60') selected @endif value="60">1 Minitute</option>
                </select>
            </li>
            <li>
                <label for="minimum_preparation_time">Minimum Preparation Time (minutes):</label>
                <select name="minimum_preparation_time" id="minimum_preparation_time">
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '5')  selected @endif value="5">5 Seconds</option>
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '15') selected @endif value="15">15 Seconds</option>
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '30') selected @endif value="10">30 Seconds</option>
                    <option @if ($restaurantConfig['minimum_preparation_time'] == '60') selected @endif value="60">1 Minitute</option>
                </select>
            </li>
            <li>
                <label for="order_live_view">Order Live View:</label>

                <input type="radio" @if ($restaurantConfig['order_live_view'] == 'asc') checked @endif name="order_live_view" id="order_live_view_asc" value="asc"> ASC
                <input type="radio"  @if ($restaurantConfig['order_live_view'] == 'desc') checked @endif name="order_live_view" id="order_live_view_desc" value="desc"> DESC

            </li>
            <li>
                <label for="kot_live_view">KOT Live View:</label>
                <input type="radio" @if ($restaurantConfig['kot_live_view'] == 'asc') checked @endif name="kot_live_view" id="kot_live_view_asc" value="asc"> ASC
                <input type="radio"  @if ($restaurantConfig['kot_live_view'] == 'desc') checked @endif name="kot_live_view" id="kot_live_view_desc" value="desc"> DESC
            </li>
            <!-- Add more configurations as needed -->
        </ul>

        <button type="submit">Save Changes</button>
    </form>
</x-admin-layout>
