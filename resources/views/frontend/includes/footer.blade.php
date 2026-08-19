<footer class="border-t border-[color:var(--soh-gray)]/35 bg-[linear-gradient(180deg,#FCF7FB_0%,#F4E7F1_100%)]" role="contentinfo" aria-label="Site footer">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 py-10 sm:py-14">
        <!-- Main grid -->
        <div class="grid grid-cols-1 gap-8 rounded-[1.75rem] border border-[color:var(--soh-gray)]/40 bg-white/90 p-6 shadow-[0_22px_60px_rgba(140,3,117,0.12)] backdrop-blur-sm sm:p-8 lg:grid-cols-[1.15fr_0.75fr_0.7fr] lg:gap-10 lg:p-10">
            
            <!-- Logo + description -->
            <div>
                <a class="inline-flex flex-col items-start" href="/" wire:navigate aria-label="Go to homepage">
                    <span class="inline-flex min-w-[210px] justify-center rounded-lg bg-[color:var(--soh-purple)]/88 px-2.5 py-1.5 shadow-[0_5px_14px_rgba(140,3,117,0.14)]">
                        <img class="h-12 w-auto" src="{{ asset('img/sohmc-nav-logo.png') }}" alt="Sounds of Harmony Music Centre" />
                    </span>
                    <span class="mt-2 text-xs font-semibold uppercase tracking-[0.12em] text-[color:var(--soh-purple)]/70">
                        Music School Portal
                    </span>
                </a>

                <p class="mt-5 max-w-md text-sm leading-7 text-gray-600 sm:text-[0.96rem]">
                    {!! setting("meta_description") !!}
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="soh-btn-primary min-w-[210px] justify-center px-5 py-3" wire:navigate>
                            @lang('Open Dashboard')
                        </a>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="soh-btn-primary min-w-[210px] justify-center px-5 py-3" wire:navigate>
                            @lang('Log In')
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="soh-btn-outline min-w-[210px] justify-center px-5 py-3" wire:navigate>
                                @lang('Create Account')
                            </a>
                        @endif
                    @endguest
                </div>
            </div>

            <!-- Navigation -->
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--soh-purple)]">@lang('Navigation')</p>
                <div class="mt-5">
                    <x-frontend.dynamic-menu
                        location="frontend-footer"
                        cssClass="grid grid-cols-1 gap-3 text-sm font-medium text-gray-700"
                    />
                </div>
            </div>

            <!-- Connect -->
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--soh-purple)]">@lang('Connect')</p>
                <div class="mt-5 rounded-2xl bg-[color:var(--soh-surface)] px-5 py-5">
                    <p class="text-sm leading-7 text-gray-600">
                        @lang('Stay connected with school updates, media, and portal access in one place.')
                    </p>
                    <x-frontend.social.all-social-url />
                </div>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="mt-6 flex flex-col gap-4 border-t border-[color:var(--soh-gray)]/30 pt-5 text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between">
            <div class="max-w-3xl leading-6">{!! setting("footer_text") !!}</div>
            <div class="text-xs font-medium tracking-[0.02em] text-[color:var(--soh-purple)]/75">
                {{ __('Built by') }} <span class="text-[color:var(--soh-purple)]">UEL Tech Solutions</span>
            </div>
        </div>
    </div>
</footer>
