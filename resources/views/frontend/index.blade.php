@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }}
@endsection

@section("content")
    <section class="relative overflow-hidden bg-[radial-gradient(circle_at_12%_20%,rgba(166,18,141,0.2),transparent_44%),radial-gradient(circle_at_88%_80%,rgba(166,18,141,0.14),transparent_40%),linear-gradient(180deg,#ECEEF3_0%,#F3F5F9_100%)] px-4 py-18 sm:px-6 lg:px-10">
        <div class="pointer-events-none absolute inset-0 opacity-35" style="background-image: linear-gradient(rgba(13,13,13,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(13,13,13,0.08) 1px, transparent 1px); background-size: 48px 48px;"></div>

        <div class="relative mx-auto grid max-w-7xl gap-6 lg:grid-cols-[1.02fr_0.98fr] lg:items-stretch">
            <div class="relative overflow-hidden rounded-[1.9rem] border border-[color:var(--soh-gray)]/60 bg-[linear-gradient(160deg,#FFFFFF_0%,#F5EAF3_100%)] p-6 shadow-[0_16px_48px_rgba(140,3,117,0.14)] sm:p-8">
                <div class="absolute -inset-10 bg-[radial-gradient(circle_at_35%_20%,rgba(166,18,141,0.2),transparent_60%)]"></div>
                <div class="relative flex min-h-[450px] items-center justify-center lg:min-h-[560px]">
                    <img
                        class="max-h-[520px] w-full object-contain"
                        src="{{ asset('img/sohmc-logo-wordmark.jpg') }}"
                        alt="Sounds of Harmony Music Centre logo"
                    />
                </div>
            </div>


            <div class="flex h-full flex-col justify-center rounded-[1.9rem] border border-[color:var(--soh-gray)]/50 bg-white/85 p-8 shadow-[0_16px_48px_rgba(140,3,117,0.1)] backdrop-blur-sm sm:p-10 lg:min-h-[560px]">
                <span class="inline-flex w-fit items-center rounded-full border border-[color:var(--soh-gray)] bg-white px-4 py-1.5 text-xs font-semibold tracking-[0.12em] text-[color:var(--soh-purple)] uppercase">
                    Sounds of Harmony Music Centre
                </span>

                <h1 class="mt-5 text-4xl leading-tight font-semibold text-[color:var(--soh-black)] sm:text-5xl">
                    Welcome to SOHMC
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-relaxed text-gray-700 sm:text-lg">
                    Your central portal for lessons, assignments, and progress tracking.
                    Students, teachers, and administrators each have dedicated dashboards designed for their day-to-day workflow.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="soh-btn-primary px-6 py-3">{{ __('Go to Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="soh-btn-primary px-6 py-3">{{ __('Sign In') }}</a>
                        <a href="{{ route('register') }}" class="soh-btn-outline px-6 py-3">{{ __('Sign Up') }}</a>
                    @endauth
                </div>

                <p class="mt-4 text-sm text-gray-600">{{ __('Need help accessing your account? Contact the school office for support.') }}</p>
            </div>
        </div>

        @include("frontend.includes.messages")
    </section>

    <section class="px-4 py-16 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold tracking-[0.12em] text-[color:var(--soh-purple)] uppercase">Portal Overview</p>
                    <h2 class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">Role-Based Dashboards That Keep Everyone Connected</h2>
                </div>
                <a href="{{ route('login') }}" class="soh-link text-sm">{{ __('Access your account') }}</a>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <article class="soh-card p-6">
                    <h3 class="text-xl font-semibold text-[color:var(--soh-black)]">Student Dashboard</h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        Review assigned lessons, track homework progress, and update activity status in a focused learning space.
                    </p>
                    <a href="{{ route('login') }}" class="soh-link mt-5 inline-block text-sm">{{ __('Student Login') }}</a>
                </article>

                <article class="soh-card p-6">
                    <h3 class="text-xl font-semibold text-[color:var(--soh-black)]">Teacher Dashboard</h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        Upload materials, create lessons, assign coursework, and monitor student completion from one workflow.
                    </p>
                    <a href="{{ route('login') }}" class="soh-link mt-5 inline-block text-sm">{{ __('Teacher Login') }}</a>
                </article>

                <article class="soh-card p-6">
                    <h3 class="text-xl font-semibold text-[color:var(--soh-black)]">Admin Dashboard</h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        Manage users, oversee school operations, and keep platform content organized with role-aware controls.
                    </p>
                    <a href="{{ route('login') }}" class="soh-link mt-5 inline-block text-sm">{{ __('Admin Login') }}</a>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-white/80 px-4 py-16 sm:px-6 lg:px-10">
        <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-3">
            <article class="soh-card p-6">
                <h3 class="text-lg font-semibold text-[color:var(--soh-black)]">About SOHMC</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600">
                    Learn about our learning philosophy, school values, and how we support musical development at every level.
                </p>
                <a href="{{ url('/about') }}" class="soh-link mt-4 inline-block text-sm">{{ __('Read More') }}</a>
            </article>

            <article class="soh-card p-6">
                <h3 class="text-lg font-semibold text-[color:var(--soh-black)]">Gallery</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600">
                    Explore moments from classes, rehearsals, and student performances that reflect life at SOHMC.
                </p>
                <a href="{{ url('/gallery') }}" class="soh-link mt-4 inline-block text-sm">{{ __('View Gallery') }}</a>
            </article>

            <article class="soh-card p-6">
                <h3 class="text-lg font-semibold text-[color:var(--soh-black)]">Contact</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600">
                    Reach the school directly for schedule details, general information, and platform support.
                </p>
                <a href="{{ url('/contact') }}" class="soh-link mt-4 inline-block text-sm">{{ __('Get In Touch') }}</a>
            </article>
        </div>
    </section>
@endsection
