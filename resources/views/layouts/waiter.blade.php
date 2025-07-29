<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FoodEase Waiter - @yield('title', 'Panel')</title>

    @include('layouts.scripts')
</head>

<body class="font-sans antialiased">

    <div class="flex h-screen bg-slate-900 text-gray-200" x-data="{ sidebarOpen: false }">

        <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black bg-opacity-75" @click="sidebarOpen = false" x-cloak>
        </div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col flex-shrink-0 w-64 bg-gray-800 border-r border-gray-700 transform transition-transform duration-300 md:relative md:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
                <a href="#" class="text-lg font-semibold tracking-widest text-white uppercase">
                    Waiter Panel
                </a>
                <button class="text-gray-400 md:hidden hover:text-white" @click="sidebarOpen = false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <nav class="flex-grow px-4 py-4 space-y-2 overflow-y-auto">
                <x-waiter-nav-link :href="route('waiter.tables.index')" :active="request()->routeIs('waiter.tables.index')">
                    {{ __('Choose Table') }}
                </x-waiter-nav-link>

                <x-waiter-nav-link :href="route('order.KOT.view')" :active="request()->routeIs('order.KOT.view')">
                    {{ __('KOT View') }}
                </x-waiter-nav-link>

                <div class="pt-4 mt-auto border-t border-gray-700">
                    <x-user-dropdown />
                </div>
            </nav>
        </aside>

        <div class="flex flex-col flex-grow">
            <header class="flex items-center justify-between p-4 bg-gray-800 shadow-md md:hidden">
                <a href="#" class="text-lg font-semibold text-white">{{ config('app.name', 'FoodEase') }}</a>
                <button @click.stop="sidebarOpen = !sidebarOpen" class="text-gray-400 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </header>

            <main class="flex-grow p-4 md:p-8 overflow-y-auto">
                @foreach (['danger', 'success', 'warning'] as $msg)
                    @if (session()->has($msg))
                        @php
                            $styles = [
                                'success' => 'bg-green-800 text-green-200',
                                'danger' => 'bg-red-800 text-red-200',
                                'warning' => 'bg-yellow-800 text-yellow-200',
                            ];
                        @endphp
                        <div class="p-4 mb-4 text-sm rounded-lg {{ $styles[$msg] ?? 'bg-gray-700 text-gray-200' }}"
                            role="alert">
                            <span class="font-medium">{{ session($msg) }}</span>
                        </div>
                    @endif
                @endforeach

                <x-loader />
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
