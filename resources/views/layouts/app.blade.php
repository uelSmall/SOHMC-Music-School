<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />

        <title>{{ config("app.name", "Laravel") }}</title>

        <!-- Scripts -->
        @vite(["resources/css/app-frontend.css"])
        @vite(["resources/js/app-frontend.js"])
    </head>

    <body class="font-sans antialiased">
        <div class="soh-app-shell">
            @include("layouts.navigation")

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-transparent">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

        @if (session()->has('notify'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: @json(session('notify')),
                    }));
                });
            </script>
        @endif
    </body>
</html>
