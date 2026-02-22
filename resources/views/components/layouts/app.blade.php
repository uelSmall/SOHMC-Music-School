<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ language_direction() }}">
    <head>
        @include('partials.head')
    </head>

    <body>
        <!-- Header -->
        @include('frontend.includes.header')

        <!-- Main Content -->
        <main class="">
            {{ $slot }}
        </main>

        <!-- Footer -->
        @include('frontend.includes.footer')

        <!-- Scripts -->
        @livewireScripts
        @stack('after-scripts')
    </body>
</html>