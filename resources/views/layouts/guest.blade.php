<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('layouts.scripts') {{-- Ensure this includes your compiled Tailwind CSS --}}
</head>

<body class="font-sans text-gray-900 antialiased min-h-screen flex flex-col">
    <header class="shadow-md bg-white"> {{-- Added bg-white for consistency --}}
        <nav class="container px-4 py-4 mx-auto flex flex-col md:flex-row justify-between items-center">
            {{-- Food Ease Title and Tagline --}}
            <div class="flex flex-col items-center md:items-start mb-4 md:mb-0 text-center md:text-left">
                <h1
                    class="text-3xl sm:text-4xl lg:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 transition-colors duration-300">
                    Food Ease
                </h1>
                <h2 class="font-bold text-base sm:text-lg mt-2 text-gray-700">
                    Open-source Restaurant Management App <br> Built with Laravel
                </h2>
            </div>

            {{-- Edge Ease Logo --}}
            <div class="flex items-center">
                <span class="logo-text text-base sm:text-lg mr-2 text-gray-600">A product of</span>
                <img src="{{ asset('images/edge_ease_logo.png') }}" alt="Edge Ease Logo"
                    class="w-24 sm:w-32 h-auto rounded-md">
            </div>
        </nav>
    </header>

    {{-- Main Content Slot --}}
    <div class="flex-grow bg-gray-100 p-4 sm:p-6 lg:p-8"> {{-- Use flex-grow to push footer to bottom --}}
        {{ $slot }}
        <div class="flex justify-center underline pt-4">
            <a href="https://docs-foodease.vercel.app" target="_blank"
                class="text-blue-600 hover:text-blue-800 text-lg sm:text-xl font-semibold transition-colors duration-200">Learn
                More About Foodease</a>
        </div>
    </div>

    {{-- Footer Section --}}
    <footer class="bg-gray-800 border-t border-gray-700 p-4"> {{-- Adjusted border color for contrast --}}
        <div
            class="container mx-auto flex flex-col lg:flex-row items-center justify-between text-center lg:text-left text-white">

            {{-- Navigation Links --}}
            <div class="flex flex-wrap justify-center space-x-4 mb-4 lg:mb-0">
                <ul class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <li><a href="https://docs-foodease.vercel.app"
                            class="hover:text-gray-300 transition-colors duration-200">Home</a></li>
                    <li><a href="https://docs-foodease.vercel.app/about"
                            class="hover:text-gray-300 transition-colors duration-200">About</a></li>
                    <li><a href="https://docs-foodease.vercel.app/contact"
                            class="hover:text-gray-300 transition-colors duration-200">Contact</a></li>
                    <li><a href="https://docs-foodease.vercel.app/terms-and-conditons"
                            class="hover:text-gray-300 transition-colors duration-200">Terms & Conditions</a></li>
                </ul>
            </div>

            {{-- Company Info --}}
            <div
                class="flex flex-col sm:flex-row items-center justify-center lg:justify-end mb-4 lg:mb-0 text-center sm:text-left">
                <span class="mb-2 sm:mb-0">Edge Ease Ltd.</span>
                <span class="sm:ml-4">Contact: info@edgeease.com</span>
            </div>

            {{-- Social Media Icons --}}
            <div class="flex justify-center lg:justify-end space-x-4">
                <a class="text-pink-400 hover:text-pink-300 transition-colors duration-200"
                    href="https://www.instagram.com/edge_ease/" target="_blank" aria-label="Instagram">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" class="w-6 h-6" viewBox="0 0 24 24">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                    </svg>
                </a>
                <a class="text-blue-500 hover:text-blue-400 transition-colors duration-200"
                    href="https://www.linkedin.com/company/edgeease/" target="_blank" aria-label="LinkedIn">
                    <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="0" class="w-6 h-6" viewBox="0 0 24 24">
                        <path stroke="none"
                            d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z">
                        </path>
                        <circle cx="4" cy="4" r="2" stroke="none"></circle>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
</body>

</html>
