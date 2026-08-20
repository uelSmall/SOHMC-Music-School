@extends('frontend.layouts.app')

@section('title')
    {{ __('Gallery') }}
@endsection

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#A6128D] via-[#8C0375] to-[#4A0140] px-4 py-20 text-center sm:px-6 lg:px-10 lg:py-24">
        <div class="pointer-events-none absolute -top-24 -right-24 h-[400px] w-[400px] rounded-full bg-white/5 blur-[60px]"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-32 h-[500px] w-[500px] rounded-full bg-[#D991CD]/10 blur-[80px]"></div>
        <div class="relative z-10 mx-auto max-w-7xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/30 px-5 py-2 text-xs font-bold tracking-[0.12em] text-white/70 uppercase">
                <svg class="h-4 w-4 text-[#D991CD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                Gallery
            </span>
            <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">School <span class="text-[#D991CD]">Moments</span></h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg text-white/75 leading-relaxed">A visual snapshot of performances, rehearsals, and musical life at SOHMC.</p>
            @unless ($galleryItems->isEmpty())
                <div class="mt-6 inline-flex items-center gap-2 rounded-full bg-white/12 border border-white/20 px-5 py-2 text-sm font-semibold text-white/80 backdrop-blur-sm">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    {{ $galleryItems->count() }} {{ __('photos') }}
                </div>
            @endunless
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="block w-full" viewBox="0 0 1440 60" fill="none" style="height:56px"><path d="M0 60V20C240 0 480 0 720 20C960 40 1200 40 1440 20V60H0Z" fill="var(--soh-surface)"/></svg>
        </div>
    </section>

    {{-- Gallery Grid --}}
    <section class="bg-[color:var(--soh-surface)] px-4 py-14 sm:px-6 lg:px-10 lg:py-16" x-data="galleryLightbox()">
        <div class="mx-auto max-w-7xl">
            @if ($galleryItems->isEmpty())
                <div class="rounded-3xl border border-[color:var(--soh-gray)]/40 bg-white p-14 text-center shadow-lg">
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
                                class="group relative cursor-pointer overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/30 bg-white shadow-lg transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl {{ $isWide ? 'sm:col-span-2 lg:col-span-2' : '' }}"
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
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-100"></div>

                                {{-- Content --}}
                                <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6 translate-y-4 opacity-0 transition-all duration-400 group-hover:translate-y-0 group-hover:opacity-100">
                                    <h2 class="text-lg font-semibold text-white sm:text-xl">{{ $item->title }}</h2>
                                    @if ($item->caption)
                                        <p class="mt-1 line-clamp-2 text-sm text-white/80">{{ $item->caption }}</p>
                                    @endif
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
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm sm:p-8"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.self="close()"
                @keydown.escape.window="close()"
            >
                <button
                    @click="close()"
                    class="absolute top-4 right-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white transition-colors hover:bg-white/25 sm:top-6 sm:right-6"
                    aria-label="Close lightbox"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

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
                    <div x-show="imageTitle || imageCaption" class="mt-4 text-center">
                        <h3 class="text-lg font-semibold text-white" x-text="imageTitle"></h3>
                        <p x-show="imageCaption" class="mt-1 text-sm text-white/70" x-text="imageCaption"></p>
                    </div>
                </div>

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
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold text-white backdrop-blur-sm" x-text="`${currentIndex + 1} / ${total}`"></div>
                @endif
            </div>
        </template>
    </section>

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#A6128D] to-[#6B025E] px-4 py-20 text-center sm:px-6 lg:px-10">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_50%_60%_at_50%_100%,rgba(217,145,205,0.2),transparent_70%)]"></div>
        <div class="relative z-10 mx-auto max-w-7xl">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Want to Be Part of the Story?</h2>
            <p class="mx-auto mt-4 max-w-xl text-white/70 leading-relaxed">Join SOHMC and start creating your own musical moments. Enroll today.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[color:var(--soh-purple-dark)] shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">Enroll Now</a>
                <a href="{{ route('frontend.contact') }}" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:border-white/50 hover:bg-white/10">Contact Us</a>
            </div>
        </div>
    </section>

    @push('after-styles')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
