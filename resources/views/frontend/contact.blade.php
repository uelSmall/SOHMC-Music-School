@extends('frontend.layouts.app')

@section('title')
    {{ __('Contact') }}
@endsection

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#A6128D] via-[#8C0375] to-[#4A0140] px-4 py-20 text-center sm:px-6 lg:px-10 lg:py-24">
        <div class="pointer-events-none absolute -top-24 -right-24 h-[400px] w-[400px] rounded-full bg-white/5 blur-[60px]"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-32 h-[500px] w-[500px] rounded-full bg-[#D991CD]/10 blur-[80px]"></div>
        <div class="relative z-10 mx-auto max-w-7xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/30 px-5 py-2 text-xs font-bold tracking-[0.12em] text-white/70 uppercase">
                <svg class="h-4 w-4 text-[#D991CD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Contact
            </span>
            <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Get In <span class="text-[#D991CD]">Touch</span></h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg text-white/75 leading-relaxed">For account support, scheduling information, or school inquiries, reach SOHMC through the details below.</p>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="block w-full" viewBox="0 0 1440 60" fill="none" style="height:56px"><path d="M0 60V20C240 0 480 0 720 20C960 40 1200 40 1440 20V60H0Z" fill="var(--soh-surface)"/></svg>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="bg-[color:var(--soh-surface)] px-4 py-14 sm:px-6 lg:px-10 lg:py-16">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-8 lg:grid-cols-2 lg:gap-10">
                {{-- Contact Info --}}
                <div class="flex flex-col gap-4">
                    <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-6 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                        <div class="flex items-center gap-4">
                            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold">Email Us</h3>
                                <p class="mt-1 text-sm text-gray-600"><a href="mailto:soundsofharmony51@gmail.com" class="font-semibold text-[color:var(--soh-purple)] hover:underline">soundsofharmony51@gmail.com</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-6 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                        <div class="flex items-center gap-4">
                            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold">Call Us</h3>
                                <p class="mt-1 text-sm text-gray-600"><a href="tel:+18684782889" class="font-semibold text-[color:var(--soh-purple)] hover:underline">+1 (868) 478-2889</a> | <a href="tel:+18682842340" class="font-semibold text-[color:var(--soh-purple)] hover:underline">+1 (868) 284-2340</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-6 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                        <div class="flex items-center gap-4">
                            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold">Office Hours</h3>
                                <p class="mt-1 text-sm text-gray-600">Monday - Friday<br>9:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-6 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                        <div class="flex items-center gap-4">
                            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold">Location</h3>
                                <p class="mt-1 text-sm text-gray-600">Trinidad &amp; Tobago</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="#" class="flex h-11 w-11 items-center justify-center rounded-xl border border-[color:var(--soh-purple)]/15 bg-white text-[color:var(--soh-purple)] transition-all duration-200 hover:bg-[color:var(--soh-purple)] hover:text-white hover:border-[color:var(--soh-purple)]" aria-label="Facebook">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="flex h-11 w-11 items-center justify-center rounded-xl border border-[color:var(--soh-purple)]/15 bg-white text-[color:var(--soh-purple)] transition-all duration-200 hover:bg-[color:var(--soh-purple)] hover:text-white hover:border-[color:var(--soh-purple)]" aria-label="Instagram">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="flex h-11 w-11 items-center justify-center rounded-xl border border-[color:var(--soh-purple)]/15 bg-white text-[color:var(--soh-purple)] transition-all duration-200 hover:bg-[color:var(--soh-purple)] hover:text-white hover:border-[color:var(--soh-purple)]" aria-label="YouTube">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-7 sm:p-9 shadow-lg shadow-[color:var(--soh-purple)]/8">
                    <h2 class="text-xl font-bold">Send Us a Message</h2>
                    <p class="mt-2 text-sm text-gray-600">Fill out the form below and we'll get back to you as soon as possible.</p>

                    @if(session('flash_success'))
                        <div class="mt-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('flash_success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('frontend.contact.submit') }}" method="POST" class="mt-6 grid gap-4 sm:grid-cols-2">
                        @csrf
                        <div class="flex flex-col sm:col-span-1">
                            <label for="name" class="mb-1.5 text-sm font-semibold text-gray-800">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Your name" class="rounded-xl border border-[color:var(--soh-purple)]/20 bg-[color:var(--soh-surface)] px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-[color:var(--soh-purple)] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]">
                        </div>
                        <div class="flex flex-col sm:col-span-1">
                            <label for="email" class="mb-1.5 text-sm font-semibold text-gray-800">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="you@example.com" class="rounded-xl border border-[color:var(--soh-purple)]/20 bg-[color:var(--soh-surface)] px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-[color:var(--soh-purple)] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]">
                        </div>
                        <div class="flex flex-col sm:col-span-1">
                            <label for="phone" class="mb-1.5 text-sm font-semibold text-gray-800">Phone (Optional)</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+1 (000) 000-0000" class="rounded-xl border border-[color:var(--soh-purple)]/20 bg-[color:var(--soh-surface)] px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-[color:var(--soh-purple)] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]">
                        </div>
                        <div class="flex flex-col sm:col-span-1">
                            <label for="subject" class="mb-1.5 text-sm font-semibold text-gray-800">Subject</label>
                            <select name="subject" id="subject" class="rounded-xl border border-[color:var(--soh-purple)]/20 bg-[color:var(--soh-surface)] px-4 py-3 text-sm text-gray-700 outline-none transition-all duration-200 focus:border-[color:var(--soh-purple)] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)]">
                                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a topic</option>
                                @foreach(['Enrollment Inquiry', 'Schedule & Classes', 'Account Support', 'General Question', 'Other'] as $option)
                                    <option value="{{ $option }}" {{ old('subject') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col sm:col-span-2">
                            <label for="message" class="mb-1.5 text-sm font-semibold text-gray-800">Message</label>
                            <textarea name="message" id="message" rows="5" placeholder="Tell us how we can help..." class="rounded-xl border border-[color:var(--soh-purple)]/20 bg-[color:var(--soh-surface)] px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-[color:var(--soh-purple)] focus:bg-white focus:shadow-[0_0_0_3px_rgba(166,18,141,0.1)] resize-y">{{ old('message') }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[color:var(--soh-purple)] px-8 py-3.5 text-sm font-bold text-white shadow-lg transition-all duration-200 hover:bg-[color:var(--soh-purple-dark)] hover:-translate-y-0.5 hover:shadow-xl">
                                Send Message
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Map --}}
    <section class="bg-white px-4 pb-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="overflow-hidden rounded-3xl border border-[color:var(--soh-purple)]/12 bg-[color:var(--soh-surface)]">
                <div class="flex h-80 flex-col items-center justify-center text-[color:var(--soh-purple)]">
                    <svg class="mb-3 h-12 w-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <p class="text-sm font-semibold opacity-60">Map will display our school location</p>
                </div>
            </div>
        </div>
    </section>
@endsection
