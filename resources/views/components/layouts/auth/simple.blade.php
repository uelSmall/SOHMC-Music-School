<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" dir="{{ language_direction() }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased" style="background: linear-gradient(135deg, #A6128D 0%, #8C0375 100%);">
        <div class="flex min-h-svh flex-col items-center justify-center gap-8 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-5">
                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center" wire:navigate>
                        <span class="flex h-24 w-24 items-center justify-center rounded-[1.6rem] bg-white/12 shadow-[0_18px_40px_rgba(13,13,13,0.22)] backdrop-blur-sm ring-1 ring-white/20">
                            <x-app-logo-icon variant="crest" class="h-[4.5rem] w-[4.5rem]" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    <div class="soh-card px-8 py-7 sm:px-10 sm:py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
