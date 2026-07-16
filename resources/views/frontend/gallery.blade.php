@extends('frontend.layouts.app')

@section('title')
    {{ __('Gallery') }}
@endsection

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-14">
        <div class="mb-8 rounded-[1.8rem] border border-[color:var(--soh-gray)]/50 bg-[linear-gradient(145deg,#FFFFFF_0%,#F7ECF5_55%,#F2D8EC_100%)] p-6 shadow-[0_24px_56px_rgba(140,3,117,0.14)] sm:p-8">
            <p class="text-xs font-semibold tracking-[0.16em] text-[color:var(--soh-purple)] uppercase">{{ __('Gallery') }}</p>
            <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">{{ __('School Moments') }}</h1>
            <p class="mt-3 max-w-3xl text-base text-gray-600 sm:text-lg">{{ __('A visual snapshot of performances, rehearsals, and musical life at SOHMC.') }}</p>
        </div>

        @if ($galleryItems->isEmpty())
            <div class="rounded-2xl border border-[color:var(--soh-gray)]/50 bg-[color:var(--soh-surface)] px-6 py-14 text-center">
                <p class="text-xl font-semibold text-[color:var(--soh-black)]">{{ __('No gallery images available yet') }}</p>
                <p class="mt-2 text-sm text-gray-600">{{ __('Upload or seed images to populate the gallery.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($galleryItems as $index => $item)
                    @php
                        $isWide = $index % 5 === 0;
                        $imageUrl = $item->getFirstMediaUrl('gallery', 'gallery-lg') ?: $item->getFirstMediaUrl('gallery');
                    @endphp

                    @if ($imageUrl)
                        <article class="group relative overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/45 bg-white shadow-[0_16px_38px_rgba(140,3,117,0.14)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_24px_48px_rgba(140,3,117,0.2)] {{ $isWide ? 'sm:col-span-2 lg:col-span-2' : '' }}">
                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $item->title }}"
                                class="h-64 w-full object-cover object-center transition-transform duration-500 group-hover:scale-[1.03] {{ $isWide ? 'sm:h-80 lg:h-96' : 'sm:h-72' }}"
                            />

                            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(17,24,39,0.04)_42%,rgba(140,3,117,0.7)_100%)] opacity-80 transition-opacity duration-300 group-hover:opacity-100"></div>
                            <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5">
                                <h2 class="text-lg font-semibold text-white sm:text-xl">{{ $item->title }}</h2>
                                @if ($item->caption)
                                    <p class="mt-1 line-clamp-2 text-sm text-white/90">{{ $item->caption }}</p>
                                @endif
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>
        @endif
    </section>
@endsection
