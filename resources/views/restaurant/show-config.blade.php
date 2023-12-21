<!-- resources/views/restaurant/config.blade.php -->

<x-admin-layout>
    <h1>Restaurant Configurations</h1>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('restaurant.update.config') }}">
        @csrf
        @method('patch')

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
                <input type="time" id="pending_order_sync_time" name="pending_order_sync_time" value="{{ $restaurantConfig['pending_order_sync_time'] }}">
            </li>
            <li>
                <label for="waiter_sync_time">waiter Sync Time:</label>
                <input type="time" id="waiter_sync_time" name="waiter_sync_time" value="{{ $restaurantConfig['waiter_sync_time'] }}">
            </li>
            <li>
                <label for="minimum_delivery_time">Minimum Delivery Time (minutes):</label>
                <input type="number" id="minimum_delivery_time" name="minimum_delivery_time" value="{{ $restaurantConfig['minimum_delivery_time'] }}">
            </li>
            <li>
                <label for="minimum_preparation_time">Minimum Preparation Time (minutes):</label>
                <input type="number" id="minimum_preparation_time" name="minimum_preparation_time" value="{{ $restaurantConfig['minimum_preparation_time'] }}">
            </li>
            <li>
                <label for="order_live_view">Order Live View:</label>
                <select id="order_live_view" name="order_live_view">
                    <option value="asc" {{ $restaurantConfig['order_live_view'] == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ $restaurantConfig['order_live_view'] == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </li>
            <li>
                <label for="kot_live_view">KOT Live View:</label>
                <select id="kot_live_view" name="kot_live_view">
                    <option value="asc" {{ $restaurantConfig['kot_live_view'] == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ $restaurantConfig['kot_live_view'] == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </li>
            <!-- Add more configurations as needed -->
        </ul>

        <button type="submit">Save Changes</button>
    </form>
</x-admin-layout>
