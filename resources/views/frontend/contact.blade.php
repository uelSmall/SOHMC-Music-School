@extends('frontend.layouts.app')

@section('title')
    {{ __('Contact') }}
@endsection

@section('content')
    <section class="px-4 py-12 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <article class="soh-card p-8">
                <p class="text-xs font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">{{ __('Contact') }}</p>
                <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">{{ __('Get In Touch') }}</h1>
                <p class="mt-4 text-base leading-relaxed text-gray-600 sm:text-lg">
                    {{ __('For account support, scheduling information, or school inquiries, reach SOHMC through the details below.') }}
                </p>
            </article>

            <article class="soh-card p-8">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('School Office') }}</h2>
                <p class="mt-3 text-sm text-gray-600">{{ __('Email') }}: info@sohmc.example</p>
                <p class="mt-1 text-sm text-gray-600">{{ __('Phone') }}: +1 (000) 000-0000</p>
                <p class="mt-1 text-sm text-gray-600">{{ __('Hours') }}: Mon - Fri, 9:00 AM - 5:00 PM</p>
            </article>
        </div>
    </section>
@endsection
