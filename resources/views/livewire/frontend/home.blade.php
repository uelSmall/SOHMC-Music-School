<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:py-12">
    <section class="relative overflow-hidden rounded-[2rem] border border-[color:var(--soh-gray)]/45 bg-[linear-gradient(160deg,#FDFBFD_0%,#F1D8EC_100%)] p-3 shadow-[0_30px_80px_rgba(140,3,117,0.14)] sm:p-4">
        <div class="pointer-events-none absolute inset-0 opacity-35" style="background-image: linear-gradient(rgba(166,18,141,0.07) 1px, transparent 1px), linear-gradient(90deg, rgba(166,18,141,0.07) 1px, transparent 1px); background-size: 52px 52px;"></div>

        <div class="relative grid grid-cols-1 gap-3 lg:grid-cols-[1.24fr_0.76fr] lg:items-stretch">
            <div class="relative min-h-[430px] overflow-hidden rounded-[1.4rem] border border-[color:var(--soh-gray)]/45 bg-[radial-gradient(120%_85%_at_20%_18%,rgba(166,18,141,0.22)_0%,rgba(242,226,238,0.95)_46%,rgba(230,198,222,0.92)_100%)] shadow-[inset_0_1px_0_rgba(255,255,255,0.55),0_20px_46px_rgba(140,3,117,0.22)] lg:min-h-[640px]">
                <img
                    class="absolute inset-0 h-full w-full object-cover object-center"
                    src="{{ asset('img/sohm-logo-original.jpg') }}"
                    alt="Sounds of Harmony Music Centre"
                />
                <div class="absolute inset-0 bg-[linear-gradient(165deg,rgba(255,255,255,0.07)_0%,rgba(166,18,141,0.14)_52%,rgba(140,3,117,0.34)_100%)]"></div>
                <div class="pointer-events-none absolute -inset-[18%] rounded-[2rem] bg-[radial-gradient(72%_62%_at_50%_42%,rgba(166,18,141,0.24)_0%,rgba(166,18,141,0.08)_48%,transparent_76%)] blur-xl"></div>
                <div class="absolute bottom-5 left-5 rounded-lg bg-white/90 px-4 py-2 text-[11px] font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase shadow-sm">
                    {{ __('School Portal') }}
                </div>
            </div>

            <div class="flex flex-col rounded-[1.4rem] border border-[color:var(--soh-gray)]/45 bg-white/96 p-8 shadow-[0_18px_44px_rgba(140,3,117,0.1)] backdrop-blur-sm sm:p-10 lg:min-h-[640px]">
                <p class="text-xs font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">Sounds of Harmony Music Centre</p>

                <h1 class="mt-4 text-xl font-bold tracking-tight text-gray-900 sm:text-2xl sm:leading-tight">{{ __('Welcome to SOHMC Portal') }}</h1>

                <p class="mt-4 text-base leading-relaxed text-gray-600 sm:text-lg">
                    {{ __('Your central portal for teaching, student learning, and daily academic coordination.') }}
                </p>

                <p class="mt-2 text-sm leading-relaxed text-gray-500">{!! setting('app_description') !!}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="soh-btn-primary px-6 py-3" wire:navigate>
                            @lang('Go to Dashboard')
                        </a>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="soh-btn-primary px-6 py-3" wire:navigate>
                            @lang('Sign In')
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="soh-btn-outline px-6 py-3" wire:navigate>
                                @lang('Sign Up')
                            </a>
                        @endif
                    @endguest
                </div>

                <div class="mt-8 rounded-xl bg-[color:var(--soh-purple)] p-5 text-white">
                    <h2 class="text-xl font-semibold">@lang('Portal access in one place')</h2>
                    <p class="mt-2 text-sm leading-relaxed text-white/90">
                        @lang('A single secure platform adapts to each signed-in user and shows the tools they need.')
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            @include('frontend.includes.messages')
        </div>
    </section>

    <section class="mt-12">
        <div class="mb-5">
            <p class="text-xs font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">@lang('Platform Capabilities')</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-[2.1rem]">@lang('Everything you need to run learning smoothly')</h2>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <article class="soh-card p-5">
            <p class="text-[11px] font-semibold tracking-[0.12em] text-[color:var(--soh-purple)] uppercase">@lang('Teaching')</p>
            <h3 class="mt-2 text-lg font-semibold text-gray-900">@lang('Lesson Management')</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">@lang('Organize curriculum, assign teachers, and structure learning by category.')</p>
        </article>
        <article class="soh-card p-5">
            <p class="text-[11px] font-semibold tracking-[0.12em] text-[color:var(--soh-purple)] uppercase">@lang('Learning')</p>
            <h3 class="mt-2 text-lg font-semibold text-gray-900">@lang('Student Progress')</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">@lang('Monitor assigned lessons and completion progress in one place.')</p>
        </article>
        <article class="soh-card p-5">
            <p class="text-[11px] font-semibold tracking-[0.12em] text-[color:var(--soh-purple)] uppercase">@lang('Access')</p>
            <h3 class="mt-2 text-lg font-semibold text-gray-900">@lang('Role-Based Dashboards')</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">@lang('Separate experiences for admins, teachers, and students with clear permissions.')</p>
        </article>
        <article class="soh-card p-5">
            <p class="text-[11px] font-semibold tracking-[0.12em] text-[color:var(--soh-purple)] uppercase">@lang('Security')</p>
            <h3 class="mt-2 text-lg font-semibold text-gray-900">@lang('Account Controls')</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">@lang('Manage profile, password, and account settings with secure authentication.')</p>
        </article>
        </div>
    </section>
</div>
