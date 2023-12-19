<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('layouts.scripts')
</head>

<body class="font-sans antialiased">
    <div class="flex-col w-full md:flex md:flex-row md:min-h-screen">
        <div @click.away="open = false"
            class="flex flex-col flex-shrink-0 w-full text-gray-700 bg-slate-100 md:w-64 dark:text-gray-200 dark:bg-gray-800"
            x-data="{ open: false }">
            <div class="flex flex-row items-center justify-between flex-shrink-0 px-8 py-4">
                <a href="#"
                    class="text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark:text-white focus:outline-none focus:shadow-outline">Waiter
                    Panel</a>
                <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path x-show="!open" fill-rule="evenodd"
                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                        <path x-show="open" fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <nav :class="{'block': open, 'hidden': !open}"
                class="flex-grow px-4 pb-4 md:block md:pb-0 md:overflow-y-auto">
                <x-waiter-nav-link :href="route('waiter.choose.table')"
                    :active="request()->routeIs('waiter.choose.table')">
                    {{ __('Create Order') }}
                </x-waiter-nav-link>
                <x-waiter-nav-link :href="route('waiter.orders.running')"
                    :active="request()->routeIs('waiter.orders.running')">
                    {{ __('Running Orders') }}
                </x-waiter-nav-link>
                <x-waiter-nav-link :href="route('waiter.orders.history')"
                    :active="request()->routeIs('waiter.orders.history')">
                    {{ __('Order History') }}
                </x-waiter-nav-link>
                <x-waiter-nav-link :href="route('waiter.orders.ready.for.pickup')"
                    :active="request()->routeIs('waiter.orders.ready.for.pickup')">
                    {{ __('Ready For Pickup') }}
                </x-waiter-nav-link>
                <x-waiter-nav-link :href="route('waiter.get.running.tables')"
                    :active="request()->routeIs('waiter.get.running.tables')">
                    {{ __('Running Tables') }}
                </x-waiter-nav-link>
            </nav>
        </div>
        <main class="m-2 p-8 w-full">
            @if (session()->has('danger'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                    role="alert">
                    <span class="font-medium">{{ session()->get('danger') }}!</span>
                </div>
            @endif
            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                    role="alert">
                    <span class="font-medium">{{ session()->get('success') }}!</span>
                </div>
            @endif
            @if (session()->has('warning'))
                <div class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800"
                    role="alert">
                    <span class="font-medium">{{ session()->get('warning') }}!</span

>
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>
</body>

</html>