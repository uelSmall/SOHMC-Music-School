<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:py-12">
    <section class="relative overflow-hidden rounded-[2rem] border border-[color:var(--soh-gray)]/45 bg-[linear-gradient(160deg,#F7F8FB_0%,#ECEFF5_100%)] p-3 shadow-[0_30px_80px_rgba(140,3,117,0.14)] sm:p-4">
        <div class="pointer-events-none absolute inset-0 opacity-35" style="background-image: linear-gradient(rgba(166,18,141,0.07) 1px, transparent 1px), linear-gradient(90deg, rgba(166,18,141,0.07) 1px, transparent 1px); background-size: 52px 52px;"></div>

        <div class="relative grid grid-cols-1 gap-3 lg:grid-cols-[1.2fr_0.8fr] lg:items-stretch">
            <div class="relative min-h-[430px] overflow-hidden rounded-[1.4rem] border border-[color:var(--soh-gray)]/45 bg-[#ECEEF3] lg:min-h-[640px]">
                <img
                    class="absolute inset-0 h-full w-full object-cover object-center"
                    src="{{ asset('img/sohm-logo-original.jpg') }}"
                    alt="Sounds of Harmony Music Centre"
                />
                <div class="absolute bottom-5 left-5 right-5 rounded-xl border border-white/80 bg-white/86 p-4 text-gray-900 shadow-[0_10px_30px_rgba(17,24,39,0.14)] backdrop-blur-sm sm:p-5">
                    <p class="text-[11px] font-semibold tracking-[0.16em] uppercase text-[color:var(--soh-purple)]">{{ __('School Portal') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-gray-700 sm:text-base">{{ __('One unified environment for classes, assignments, and progress at Sounds of Harmony Music Centre.') }}</p>
                </div>
            </div>

            <div class="flex flex-col rounded-[1.4rem] border border-[color:var(--soh-gray)]/45 bg-white/95 p-7 shadow-[0_18px_44px_rgba(140,3,117,0.1)] backdrop-blur-sm sm:p-9 lg:min-h-[640px]">
                <p class="text-xs font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">Sounds of Harmony Music Centre</p>

                <h1 class="mt-4 text-4xl font-bold tracking-tight text-gray-900 sm:text-[3.3rem] sm:leading-[1.03]">{{ __('Welcome to SOHMC') }}</h1>

                <p class="mt-4 text-base leading-relaxed text-gray-600 sm:text-lg">
                    {{ __('Your school operations hub for teaching, student learning, and day-to-day academic coordination.') }}
                </p>

                <p class="mt-3 text-sm leading-relaxed text-gray-500">{!! setting('app_description') !!}</p>

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

                <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="text-[11px] font-semibold tracking-[0.12em] text-gray-500 uppercase">@lang('Student')</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">@lang('Assigned lessons')</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="text-[11px] font-semibold tracking-[0.12em] text-gray-500 uppercase">@lang('Teacher')</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">@lang('Class delivery')</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="text-[11px] font-semibold tracking-[0.12em] text-gray-500 uppercase">@lang('Admin')</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">@lang('School oversight')</p>
                    </div>
                </div>

                <div class="mt-7 rounded-xl bg-[color:var(--soh-purple)] p-5 text-white">
                    <h2 class="text-xl font-semibold">@lang('Portal access for every role')</h2>
                    <p class="mt-2 text-sm leading-relaxed text-white/90">
                        @lang('Students, teachers, and administrators access one secure platform with role-based dashboards tailored to their workflow.')
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            @include('frontend.includes.messages')
        </div>
    </section>

    <section class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
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
    </section>
</div>
