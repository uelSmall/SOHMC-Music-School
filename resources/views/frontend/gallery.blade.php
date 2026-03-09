@extends('frontend.layouts.app')

@section('title')
    {{ __('Gallery') }}
@endsection

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <div class="mb-6">
            <p class="text-xs font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">{{ __('Gallery') }}</p>
            <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">{{ __('School Moments') }}</h1>
            <p class="mt-3 text-base text-gray-600 sm:text-lg">{{ __('A visual snapshot of activities and musical life at SOHMC.') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="soh-card p-3"><img src="{{ asset('img/logo-square.jpg') }}" alt="SOHMC" class="h-52 w-full rounded-xl object-cover" /></div>
            <div class="soh-card p-3"><img src="{{ asset('img/logo-with-text.jpg') }}" alt="SOHMC" class="h-52 w-full rounded-xl object-cover" /></div>
            <div class="soh-card p-3"><img src="{{ asset('img/sohm-logo-original.jpg') }}" alt="SOHMC" class="h-52 w-full rounded-xl object-cover" /></div>
        </div>
    </section>
@endsection
