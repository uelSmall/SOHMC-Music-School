<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" dir="{{ language_direction() }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased" style="background: linear-gradient(160deg, #A6128D 0%, #8C0375 30%, #6B025E 60%, #4A0140 100%);">
        <div class="pointer-events-none fixed -top-24 -right-24 h-[400px] w-[400px] rounded-full bg-white/5 blur-[60px]"></div>
        <div class="pointer-events-none fixed -bottom-32 -left-32 h-[500px] w-[500px] rounded-full bg-[#D991CD]/10 blur-[80px]"></div>

        <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10 relative z-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-center" wire:navigate>
                    <span class="flex h-20 w-20 items-center justify-center rounded-[1.4rem] bg-white shadow-[0_18px_40px_rgba(13,13,13,0.22)]">
                        <x-app-logo-icon variant="crest" class="h-[3.5rem] w-[3.5rem]" />
                    </span>
                </a>

                <div class="rounded-3xl border border-white/20 bg-white/95 px-8 py-8 shadow-[0_24px_56px_rgba(13,13,13,0.18)] backdrop-blur-sm sm:px-10 sm:py-9">
                    {{ $slot }}
                </div>

                <p class="text-center text-xs text-white/40">&copy; {{ date('Y') }} Sounds of Harmony Music Centre</p>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
