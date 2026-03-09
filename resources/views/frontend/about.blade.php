@extends('frontend.layouts.app')

@section('title')
    {{ __('About') }}
@endsection

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <div class="soh-card p-8 sm:p-10">
            <p class="text-xs font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">{{ __('About SOHMC') }}</p>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">{{ __('Sounds of Harmony Music Centre') }}</h1>
            <p class="mt-5 max-w-3xl text-base leading-relaxed text-gray-600 sm:text-lg">
                {{ __('SOHMC is a focused digital and academic environment where students, teachers, and administrators stay connected through structured lesson workflows, assignments, and progress tracking.') }}
            </p>
        </div>
    </section>
@endsection
