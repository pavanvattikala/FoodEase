<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FoodEase') }} - @yield('title')</title>

    @include('layouts.scripts')
    @stack('styles')
</head>

<body class="h-full font-sans antialiased">
    <div class="flex flex-col min-h-screen">

        <header class="sticky top-0 z-50 w-full bg-[#1f2937] backdrop-blur-sm shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex h-20 items-center justify-between">

                    <div class="flex items-center gap-x-6">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('FoodEase.png') }}" alt="FoodEase Logo"
                                class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 ">
                        </a>

                        <nav>

                            <x-pos-nav-link :href="route('pos.tables')" :active="request()->routeIs('pos.tables')">
                                {{ __('New Order') }}
                            </x-pos-nav-link>
                        </nav>
                    </div>

                    <nav class="flex items-center gap-x-6">
                        @if (auth()->user()->hasPermission(App\Enums\UserRole::Admin))
                            <x-pos-nav-link :href="route('pos.tables')" :active="request()->routeIs('pos.tables')">
                                {{ __('Tables') }}
                            </x-pos-nav-link>
                            <x-pos-nav-link :href="route('admin.bills.index')" :active="request()->routeIs('admin.bills.index')">
                                {{ __('Bills') }}
                            </x-pos-nav-link>
                        @endif

                        <x-pos-nav-link :href="route('order.KOT.view')" :active="request()->routeIs('order.KOT.view')">
                            {{ __('KOT View') }}
                        </x-pos-nav-link>

                        <x-pos-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-pos-nav-link>
                    </nav>

                </div>
            </div>
        </header>

        <main class="mx-auto flex-grow p-4 sm:p-6 lg:p-8 w-full">



            <x-loader />
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>

</html>
