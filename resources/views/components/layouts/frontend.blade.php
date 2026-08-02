<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->currentLocale()) }}" dir="{{ language_direction() }}">
    <head>
        <meta charset="utf-8" />
        <link href="{{ asset("img/sohmc-logo-icon.jpg") }}" rel="apple-touch-icon" sizes="76x76" />
        <link type="image/jpeg" href="{{ asset("img/sohmc-logo-icon.jpg") }}" rel="icon" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>{{ $title ?? config("app.name") }} | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="{{ setting("meta_description") }}" />
        <meta name="keyword" content="{{ setting("meta_keyword") }}" />
        @include("frontend.includes.meta")
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Shortcut Icon -->
        <link href="{{ asset("img/sohmc-logo-icon.jpg") }}" rel="shortcut icon" />
        <link type="image/jpeg" href="{{ asset("img/sohmc-logo-icon.jpg") }}" rel="icon" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @vite(["resources/css/app-frontend.css", "resources/js/app-frontend.js"])

        @livewireStyles

        @stack("after-styles")

        <x-google-analytics />
    </head>

    <body>
        @include("frontend.includes.header")

        <main class="bg-white dark:bg-gray-800">
            {{ $slot }}
        </main>

        @include("frontend.includes.footer")

        <!-- Scripts -->
        @livewireScripts
        @stack("after-scripts")
    </body>
</html>
