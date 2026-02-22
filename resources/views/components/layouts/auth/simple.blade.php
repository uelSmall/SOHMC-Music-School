<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" dir="{{ language_direction() }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased" style="background:#F2F2F2;">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10" style="background:linear-gradient(180deg,#F2F2F2 0%,#F2F2F2 100%);">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="soh-brand-lockup soh-brand-light mb-2">
                        <x-app-logo-icon />
                        <span class="soh-brand-wordmark">
                            <span class="soh-brand-title">SOHMC</span>
                            <span class="soh-brand-subtitle">Sounds of Harmony Music Centre</span>
                        </span>
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
