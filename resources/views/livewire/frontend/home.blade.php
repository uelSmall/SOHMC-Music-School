<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
    <section class="soh-card overflow-hidden p-8 sm:p-12">
        <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-2">
            <div>
                <div class="soh-brand-lockup soh-brand-light">
                    <img class="soh-brand-image" src="{{ asset('img/sohm-logo-original.jpg') }}" alt="{{ app_name() }}" />
                    <div class="soh-brand-wordmark">
                        <span class="soh-brand-title">{{ app_name() }}</span>
                        <span class="soh-brand-subtitle">Study Hall Online Mastery</span>
                    </div>
                </div>

                <h1 class="mt-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">{{ __('Welcome to SOHMC') }}</h1>
                <p class="mt-4 text-base text-gray-600 sm:text-lg">{!! setting('app_description') !!}</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="soh-btn-primary" wire:navigate>
                            @lang('Go to Dashboard')
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="soh-btn-primary" wire:navigate>
                            @lang('Sign In')
                        </a>
                    @endauth
                </div>
            </div>

            <div>
                <div class="rounded-2xl p-6 text-white" style="background-color: var(--soh-purple);">
                    <h2 class="text-xl font-semibold">@lang('Learn with confidence')</h2>
                    <p class="mt-3 text-sm text-white/85">
                        @lang('Track progress, manage lessons, and keep your learning organized in one clean workflow.')
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            @include('frontend.includes.messages')
        </div>
    </section>

    <section class="mt-8">
        <h2 class="soh-page-title text-2xl">{{ __('What You Can Do') }}</h2>
        <p class="soh-page-subtitle">{{ __('Core features built for SOHMC.') }}</p>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="soh-card p-5">
                <h3 class="text-lg font-semibold text-gray-900">@lang('Lesson Management')</h3>
                <p class="mt-2 text-sm text-gray-600">@lang('Organize lessons, assign teachers, and keep content structured by category.')</p>
            </div>
            <div class="soh-card p-5">
                <h3 class="text-lg font-semibold text-gray-900">@lang('Student Progress')</h3>
                <p class="mt-2 text-sm text-gray-600">@lang('Track assigned lessons and monitor student completion in one place.')</p>
            </div>
            <div class="soh-card p-5">
                <h3 class="text-lg font-semibold text-gray-900">@lang('Role-Based Access')</h3>
                <p class="mt-2 text-sm text-gray-600">@lang('Separate dashboards for admins, teachers, and students with clear permissions.')</p>
            </div>
            <div class="soh-card p-5">
                <h3 class="text-lg font-semibold text-gray-900">@lang('Account Controls')</h3>
                <p class="mt-2 text-sm text-gray-600">@lang('Manage profile details, password, and account preferences securely.')</p>
            </div>
        </div>
    </section>
</div>
