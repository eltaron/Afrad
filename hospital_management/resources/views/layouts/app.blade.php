<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="/favicon.ico" type="image/x-icon"> {{-- Added Favicon --}}

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet"> {{-- Added Cairo Font --}}

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-themeBlue-950 dark:text-gray-100">
        <div class="flex h-screen">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top navigation (can be simplified or removed) -->
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-themeBlue-900 shadow"> {{-- Adjusted header background --}}
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }} {{-- Header text color will be handled by its own classes --}}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-themeBlue-950">
                    <div class="container mx-auto px-6 py-8">
                         {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
