<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('layouts.scripts')
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1 0 auto;
        }

        .main-title {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .docs-link {
            font-size: 1.25rem;
            color: #10b981;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <header class="shadow-md">
        <nav class="container px-6 py-4 mx-auto flex justify-between items-center">
            <div class="flex items-start flex-col">
                <h1
                    class="main-title font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500 md:text-2xl hover:text-green-400"">
                    Food Ease
                </h1>
                <br>
                <h2 class="font-bold pt-2">Open-source Restaurant Management App <br>
                    Built with Laravel
                </h2>
            </div>
            <div class="flex items-center">
                <span class="logo-text text-lg mr-2">A product of</span>
                <img src="{{ asset('images/edge_ease_logo.png') }}" alt="Edge Ease Logo" class="w-32 rounded-md">
            </div>
        </nav>
    </header>
    <div class="content bg-gray-100 p-2">
        {{ $slot }}
        <div class="flex justify-center underline pt-4">
            <a href="https://docs-foodease.vercel.app" target="_blank" class="docs-link">Learn More About Foodease</a>
        </div>
    </div>
    <footer class="bg-gray-800 border-t border-gray-200">
        <div class="container flex flex-wrap items-center justify-center px-4 py-8 mx-auto lg:justify-between">
            <div class="flex flex-wrap justify-center">
                <ul class="flex items-center space-x-4 text-white">
                    <a href="https://docs-foodease.vercel.app">Home</a>
                    <a href="https://docs-foodease.vercel.app/about">About</a>
                    <a href="https://docs-foodease.vercel.app/contact">Contact</a>
                    <a href="https://docs-foodease.vercel.app/terms-and-conditons">Terms & Conditions</a>
                </ul>
            </div>
            <div class="flex flex-wrap justify-end text-white">
                <div class="flex items-center">
                    <span>Edge Ease Ltd.</span>
                    <span class="ml-4">Contact: info@edgeease.com</span>
                </div>
            </div>
            <div class="flex justify-center mt-4 lg:mt-0">
                <a class="ml-3" href="https://www.instagram.com/edge_ease/">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" class="w-6 h-6 text-pink-400" viewBox="0 0 24 24">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                    </svg>
                </a>
                <a class="ml-3" href="https://www.linkedin.com/company/edgeease/">
                    <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="0" class="w-6 h-6 text-blue-500" viewBox="0 0 24 24">
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
