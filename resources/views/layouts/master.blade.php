<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FoodEase - @yield('title', config('app.name', 'FoodEase'))</title>

    @include('layouts.scripts')

    <script src="{{ mix('js/datepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ mix('css/flatpickr.css') }}">
</head>

<body class="font-sans antialiased">
    <div class="flex-col w-full md:flex md:flex-row md:min-h-screen">
        <div @click.away="open = false"
            class="flex flex-col flex-shrink-0 w-full text-gray-700 bg-slate-100 md:w-64 dark:text-gray-200 dark:bg-gray-800"
            x-data="{ open: false }">
            <div class="flex flex-row items-center justify-between flex-shrink-0 px-8 py-4">
                <a href="#"
                    class="text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark:text-white focus:outline-none focus:shadow-outline">Admin</a>
                <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path x-show="!open" fill-rule="evenodd"
                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                        <path x-show="open" fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <nav :class="{ 'block': open, 'hidden': !open }"
                class="flex-grow px-4 pb-4 md:block md:pb-0 md:overflow-y-auto">

                @php
                    $user = Auth::user();
                @endphp


                @if ($user->isBiller() || $user->isAdmin())
                    <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.index')">
                        {{ __('POS') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.bills.index')" :active="request()->routeIs('admin.bills.index')">
                        {{ __('Bills') }}
                    </x-nav-link>
                    <x-nav-link :href="route('order.KOT.view')" :active="request()->routeIs('order.KOT.view')">
                        {{ __('KOT View') }}
                    </x-nav-link>

                    @php
                        $isKitchenModuleEnabled = env('KITCHEN_MODULE_ENABLED', false);
                    @endphp

                    @if ($isKitchenModuleEnabled)
                        <x-nav-link :href="route('kitchen.index')" :openInNewTab="true" :active="request()->routeIs('kitchen.index')">
                            {{ __('Kitchen View') }}
                        </x-nav-link>
                    @endif

                @endif

                @if ($user->isAdmin())
                    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.index')">
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.menus.index')" :active="request()->routeIs('admin.menus.index')">
                        {{ __('Menus') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.table-location.index')" :active="request()->routeIs('admin.table-location.index')">
                        {{ __('Table Locations') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.tables.index')" :active="request()->routeIs('admin.tables.index')">
                        {{ __('Tables') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users.index')" :openInNewTab="true" :active="request()->routeIs('admin.users.index')">
                        {{ __('Manage Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('restaurant.show.config')" :active="request()->routeIs('restaurant.show.config')">
                        {{ __('Manage Restaurant') }}
                    </x-nav-link>
                @endif

                @if ($user->isWaiter())
                    <x-waiter-nav-link :href="route('waiter.choose.table')" :active="request()->routeIs('waiter.choose.table')">
                        {{ __('Create Order') }}
                    </x-waiter-nav-link>
                    <x-waiter-nav-link :href="route('order.KOT.view')" :active="request()->routeIs('order.KOT.view')">
                        {{ __('KOT View') }}
                    </x-waiter-nav-link>
                @endif
                <x-user-dropdown />
            </nav>
        </div>

        <main class="m-2 p-8 w-full">
            @foreach (['danger', 'success', 'warning'] as $msg)
                @if (session()->has($msg))
                    <div class="p-4 mb-4 text-sm text-{{ $msg }}-700 bg-{{ $msg }}-100 rounded-lg dark:bg-{{ $msg }}-200 dark:text-{{ $msg }}-800"
                        role="alert">
                        <span class="font-medium">{{ session()->get($msg) }}!</span>
                    </div>
                @endif
            @endforeach

            {{ $slot }}
        </main>
    </div>
</body>

</html>
