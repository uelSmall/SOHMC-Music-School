@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }}
@endsection

@section("content")
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 py-24 text-center sm:px-12">
            <div class="m-6 flex justify-center">
                <img class="h-24 rounded" src="{{ asset("img/logo-square.jpg") }}" alt="{{ app_name() }}" />
            </div>
            <h1
                class="mb-6 text-4xl leading-none font-extrabold tracking-tight text-gray-900 sm:text-6xl dark:text-white"
            >
                {{ __('Welcome to SOHMC') }}
            </h1>
            <p class="mb-10 text-lg font-normal text-gray-500 sm:px-16 sm:text-2xl xl:px-48 dark:text-gray-400">
                {!! setting("app_description") !!}
            </p>
            <div class="mb-8 flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4 lg:mb-16">
                @auth
                    <a class="inline-flex items-center justify-center rounded-lg bg-gray-700 px-5 py-3 text-center text-base font-medium text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300" href="{{ route('dashboard') }}">
                        {{ __('Go to Dashboard') }}
                    </a>
                @else
                    <a class="inline-flex items-center justify-center rounded-lg bg-gray-700 px-5 py-3 text-center text-base font-medium text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300" href="{{ route('login') }}">
                        {{ __('Sign In') }}
                    </a>
                @endauth
            </div>

            @include("frontend.includes.messages")
        </div>
    </section>

    <section class="bg-gray-100 py-20 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
        <div class="container mx-auto flex flex-col items-center justify-center px-5">
            <div class="w-full text-center lg:w-2/3">
                <h1 class="mb-4 text-3xl font-medium text-gray-800 sm:text-4xl dark:text-gray-200">
                    {{ __('What You Can Do') }}
                </h1>

                <p class="mb-8 leading-relaxed">
                    {{ __('Key platform capabilities for SOHMC.') }}
                </p>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 pb-20 dark:bg-gray-700">
        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">
            <div class="rounded-lg p-6 shadow-lg sm:p-10 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Lesson Management') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Create, organize, and assign lessons efficiently.') }}</p>
            </div>
            <div class="rounded-lg p-6 shadow-lg sm:p-10 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Student Progress') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Track assigned lessons and completion status.') }}</p>
            </div>
            <div class="rounded-lg p-6 shadow-lg sm:p-10 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Role Dashboards') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Focused spaces for admins, teachers, and students.') }}</p>
            </div>
            <div class="rounded-lg p-6 shadow-lg sm:p-10 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Account Controls') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Manage profiles and security settings safely.') }}</p>
            </div>
        </div>
    </section>
@endsection
