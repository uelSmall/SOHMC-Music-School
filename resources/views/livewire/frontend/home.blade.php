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

                <h1 class="mt-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Welcome to {{ app_name() }}</h1>
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
                    <a href="{{ route('terms') }}" class="soh-btn-outline" wire:navigate>
                        @lang('View Terms')
                    </a>
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
        <h2 class="soh-page-title text-2xl">{{ __('Platform Preview') }}</h2>
        <p class="soh-page-subtitle">{{ __('A quick look at key areas of the platform.') }}</p>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="soh-card p-3 sm:p-4">
                <img loading="lazy" src="https://github.com/nasirkhan/laravel-starter/assets/396987/1cf5ce5a-f374-4bae-b5a3-69e8d7ff684d" alt="Page preview" />
            </div>
            <div class="soh-card p-3 sm:p-4">
                <img loading="lazy" src="https://github.com/nasirkhan/laravel-starter/assets/396987/93341711-60dd-4624-8cd7-82f1c611287d" alt="Page preview" />
            </div>
            <div class="soh-card p-3 sm:p-4">
                <img loading="lazy" src="https://github.com/nasirkhan/laravel-starter/assets/396987/0f6b8201-6f6a-429f-894b-4e491cc5eba4" alt="Page preview" />
            </div>
            <div class="soh-card p-3 sm:p-4">
                <img loading="lazy" src="https://github.com/nasirkhan/laravel-starter/assets/396987/f8131011-2ecc-4a11-961f-85e02cb8f7a1" alt="Page preview" />
            </div>
        </div>
    </section>
</div>
