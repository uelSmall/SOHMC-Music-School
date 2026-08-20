@extends('frontend.layouts.app')

@section('title')
    {{ __('Gallery') }}
@endsection

@section('content')
    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-[radial-gradient(circle_at_20%_30%,rgba(166,18,141,0.18),transparent_50%),radial-gradient(circle_at_80%_70%,rgba(166,18,141,0.12),transparent_45%),linear-gradient(160deg,#FDFBFD_0%,#F1D8EC_100%)] px-4 py-14 sm:px-6 lg:px-10 lg:py-16">
        <div class="pointer-events-none absolute inset-0 opacity-25" style="background-image: linear-gradient(rgba(13,13,13,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(13,13,13,0.06) 1px, transparent 1px); background-size: 48px 48px;"></div>

        <div class="relative mx-auto max-w-7xl">
            <div class="rounded-[1.8rem] border border-[color:var(--soh-gray)]/50 bg-[linear-gradient(145deg,#FFFFFF_0%,#F7ECF5_55%,#F2D8EC_100%)] p-6 shadow-[0_24px_56px_rgba(140,3,117,0.14)] sm:p-8">
                <span class="inline-flex w-fit items-center rounded-full border border-[color:var(--soh-gray)] bg-white px-4 py-1.5 text-xs font-semibold tracking-[0.12em] text-[color:var(--soh-purple)] uppercase">
                    {{ __('Gallery') }}
                </span>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-[color:var(--soh-black)] sm:text-5xl">{{ __('School Moments') }}</h1>
                <p class="mt-3 max-w-3xl text-base text-gray-600 sm:text-lg">{{ __('A visual snapshot of performances, rehearsals, and musical life at SOHMC.') }}</p>

                @unless ($galleryItems->isEmpty())
                    <div class="mt-5 inline-flex items-center gap-2 rounded-full border border-[color:var(--soh-purple)]/20 bg-[color:var(--soh-purple)]/5 px-4 py-2 text-sm font-semibold text-[color:var(--soh-purple)]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        {{ $galleryItems->count() }} {{ __('photos') }}
                    </div>
                @endunless
            </div>
        </div>
    </section>

    {{-- Gallery Grid --}}
    <section class="px-4 py-12 sm:px-6 lg:px-10 lg:py-14" x-data="galleryLightbox()">
        <div class="mx-auto max-w-7xl">
            @if ($galleryItems->isEmpty())
                <div class="rounded-2xl border border-[color:var(--soh-gray)]/40 bg-white p-14 text-center shadow-[0_8px_30px_rgba(166,18,141,0.08)]">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[color:var(--soh-purple)]/10">
                        <svg class="h-8 w-8 text-[color:var(--soh-purple)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </div>
                    <p class="mt-4 text-xl font-semibold text-[color:var(--soh-black)]">{{ __('No gallery images available yet') }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Photos will appear here once they are uploaded.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
                    @foreach ($galleryItems as $index => $item)
                        @php
                            $isWide = $index % 5 === 0;
                            $imageUrl = $item->getFirstMediaUrl('gallery', 'gallery-lg') ?: $item->getFirstMediaUrl('gallery');
                            $fullUrl = $item->getFirstMediaUrl('gallery') ?: $imageUrl;
                        @endphp

                        @if ($imageUrl)
                            <article
                                class="group relative cursor-pointer overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/30 bg-white shadow-[0_8px_30px_rgba(140,3,117,0.1)] transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_20px_50px_rgba(140,3,117,0.22)] {{ $isWide ? 'sm:col-span-2 lg:col-span-2' : '' }}"
                                x-intersect.once="$el.classList.add('animate-fade-in')"
                                @click="open({{ $index }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->caption ?? '') }}', '{{ $fullUrl }}')"
                            >
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $item->title }}"
                                    loading="lazy"
                                    class="h-64 w-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105 {{ $isWide ? 'sm:h-80 lg:h-96' : 'sm:h-72' }}"
                                />

                                {{-- Gradient overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-[color:var(--soh-black)]/70 via-[color:var(--soh-black)]/10 to-transparent opacity-60 transition-opacity duration-400 group-hover:opacity-90"></div>

                                {{-- Content --}}
                                <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                                    <div class="translate-y-2 transition-transform duration-400 group-hover:translate-y-0">
                                        <h2 class="text-lg font-semibold text-white sm:text-xl">{{ $item->title }}</h2>
                                        @if ($item->caption)
                                            <p class="mt-1 line-clamp-2 text-sm text-white/80 transition-opacity duration-400 opacity-0 group-hover:opacity-100">{{ $item->caption }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Expand icon --}}
                                <div class="absolute top-4 right-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-white opacity-0 backdrop-blur-sm transition-all duration-300 group-hover:opacity-100">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Lightbox Modal --}}
        <template x-if="active">
            <div
                class="fixed inset-0 z-50 flex items-center justify-center bg-[color:var(--soh-black)]/90 p-4 backdrop-blur-sm sm:p-8"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.self="close()"
                @keydown.escape.window="close()"
            >
                {{-- Close button --}}
                <button
                    @click="close()"
                    class="absolute top-4 right-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white transition-colors hover:bg-white/25 sm:top-6 sm:right-6"
                    aria-label="Close lightbox"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                {{-- Image container --}}
                <div
                    class="relative max-h-[85vh] max-w-5xl"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                >
                    <img
                        :src="imageSrc"
                        :alt="imageTitle"
                        class="max-h-[80vh] w-full rounded-xl object-contain shadow-2xl"
                    />

                    {{-- Caption --}}
                    <div x-show="imageTitle || imageCaption" class="mt-4 text-center">
                        <h3 class="text-lg font-semibold text-white" x-text="imageTitle"></h3>
                        <p x-show="imageCaption" class="mt-1 text-sm text-white/70" x-text="imageCaption"></p>
                    </div>
                </div>

                {{-- Navigation arrows --}}
                @if ($galleryItems->count() > 1)
                    <button
                        @click="prev()"
                        class="absolute left-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white transition-colors hover:bg-white/25 sm:left-4 sm:h-12 sm:w-12"
                        aria-label="Previous image"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button
                        @click="next()"
                        class="absolute right-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white transition-colors hover:bg-white/25 sm:right-4 sm:h-12 sm:w-12"
                        aria-label="Next image"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>

                    {{-- Counter --}}
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold text-white backdrop-blur-sm" x-text="`${currentIndex + 1} / ${total}`"></div>
                @endif
            </div>
        </template>
    </section>

    @push('after-styles')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
    @endpush

    @push('after-scripts')
    <script>
        function galleryLightbox() {
            return {
                active: false,
                currentIndex: 0,
                imageTitle: '',
                imageCaption: '',
                imageSrc: '',
                total: {{ $galleryItems->count() }},
                items: [
                    @foreach ($galleryItems as $item)
                        @php
                            $fullUrl = $item->getFirstMediaUrl('gallery') ?: $item->getFirstMediaUrl('gallery', 'gallery-lg');
                        @endphp
                        @if ($fullUrl)
                        {
                            src: '{{ $fullUrl }}',
                            title: @js($item->title),
                            caption: @js($item->caption ?? ''),
                        },
                        @endif
                    @endforeach
                ],
                open(index, title, caption, src) {
                    const item = this.items.find(i => i.src === src);
                    this.currentIndex = item ? this.items.indexOf(item) : 0;
                    this.imageTitle = title;
                    this.imageCaption = caption;
                    this.imageSrc = src;
                    this.active = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.active = false;
                    document.body.style.overflow = '';
                },
                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.items.length;
                    this.update();
                },
                prev() {
                    this.currentIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
                    this.update();
                },
                update() {
                    const item = this.items[this.currentIndex];
                    this.imageSrc = item.src;
                    this.imageTitle = item.title;
                    this.imageCaption = item.caption;
                }
            }
        }
    </script>
    @endpush
@endsection
