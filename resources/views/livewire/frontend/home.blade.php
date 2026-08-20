<div>
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-[linear-gradient(160deg,#A6128D_0%,#8C0375_30%,#6B025E_60%,#4A0140_100%)] min-h-screen flex flex-col justify-center text-center pb-10">
        <div class="pointer-events-none absolute inset-0" style="background: radial-gradient(ellipse 60% 50% at 50% 0%, rgba(217,145,205,0.25) 0%, transparent 70%);"></div>
        <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-white/5 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-32 h-[30rem] w-[30rem] rounded-full bg-[#D991CD]/10 blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-10">
            <div class="relative mx-auto mb-4 inline-block w-[min(90vw,560px)] overflow-hidden rounded-2xl border border-white/50 bg-white/90 shadow-[0_20px_60px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.8)]" style="height:320px;">
                <img
                    src="{{ asset('img/sohm-logo-original.jpg') }}"
                    alt="{{ __('Sounds of Harmony Music Centre') }}"
                    class="absolute left-1/2 top-1/2 w-full -translate-x-1/2 -translate-y-1/2 scale-[1.7] drop-shadow-lg"
                />
            </div>

            <h1 class="text-[2.5rem] font-extrabold leading-tight tracking-tight text-white sm:text-[2.75rem] lg:text-[3rem]">
                {{ __('Where Music') }} <span class="text-[#D991CD]">{{ __('Comes Alive') }}</span>
            </h1>

            <p class="mx-auto mt-2.5 max-w-xl text-[1.05rem] leading-snug text-white/75 lg:text-lg">
                {{ __('Inspiring musical excellence for over 20 years. Lessons, performances, and a community that nurtures every student.') }}
            </p>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[#8C0375] shadow-lg shadow-black/15 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white/95 hover:shadow-xl">
                        {{ __('Go to Dashboard') }}
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[#8C0375] shadow-lg shadow-black/15 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white/95 hover:shadow-xl">
                        {{ __('Start Learning Today') }}
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:border-white/50 hover:bg-white/10">
                        {{ __('Sign In') }}
                    </a>
                @endauth
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-[0.95rem] text-white/50">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#D991CD]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    {{ __('Expert Instructors') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#D991CD]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    {{ __('Online & In-Person') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#D991CD]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    {{ __('All Ages Welcome') }}
                </span>
            </div>

            @include('frontend.includes.messages')
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-10 w-full sm:h-14"><path d="M0 60V20C240 0 480 0 720 20C960 40 1200 40 1440 20V60H0Z" fill="#F2F2F2"/></svg>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="bg-[#F2F2F2] py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="text-center">
                <p class="text-xs font-bold tracking-[0.18em] text-[color:var(--soh-purple)] uppercase">{{ __('Why Choose SOHMC') }}</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[color:var(--soh-black)] sm:text-4xl">{{ __('More Than Just Music Lessons') }}</h2>
                <p class="mx-auto mt-4 max-w-2xl text-base text-gray-600 sm:text-lg">
                    {{ __('We create a nurturing environment where students discover their talent, build confidence, and fall in love with music.') }}
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="group rounded-2xl border border-[color:var(--soh-gray)]/30 bg-white p-7 shadow-[0_8px_30px_rgba(166,18,141,0.08)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(166,18,141,0.15)]">
                    <div class="flex h-13 w-13 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-colors duration-300 group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-[color:var(--soh-black)]">{{ __('Expert Instructors') }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        {{ __('Our professionally trained music instructors bring years of experience and a genuine passion for teaching students at every level.') }}
                    </p>
                </div>

                <div class="group rounded-2xl border border-[color:var(--soh-gray)]/30 bg-white p-7 shadow-[0_8px_30px_rgba(166,18,141,0.08)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(166,18,141,0.15)]">
                    <div class="flex h-13 w-13 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-colors duration-300 group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-[color:var(--soh-black)]">{{ __('Online & In-Person') }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        {{ __('Learn from the comfort of your home or in our modern facilities. Flexible scheduling to fit your family\'s busy life.') }}
                    </p>
                </div>

                <div class="group rounded-2xl border border-[color:var(--soh-gray)]/30 bg-white p-7 shadow-[0_8px_30px_rgba(166,18,141,0.08)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(166,18,141,0.15)] sm:col-span-2 lg:col-span-1">
                    <div class="flex h-13 w-13 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-colors duration-300 group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-[color:var(--soh-black)]">{{ __('Performance Opportunities') }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        {{ __('From recitals to showcases, students gain real stage experience that builds confidence and brings their learning to life.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- INSTRUMENTS --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="text-center">
                <p class="text-xs font-bold tracking-[0.18em] text-[color:var(--soh-purple)] uppercase">{{ __('What We Teach') }}</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[color:var(--soh-black)] sm:text-4xl">{{ __('Instruments & Classes') }}</h2>
                <p class="mx-auto mt-4 max-w-2xl text-base text-gray-600 sm:text-lg">
                    {{ __('From piano to steelpan, voice to theory — find the instrument that speaks to you.') }}
                </p>
            </div>

            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @php
                    $instruments = [
                        ['name' => 'Piano',    'icon' => 'piano'],
                        ['name' => 'Guitar',   'icon' => 'guitar'],
                        ['name' => 'Violin',   'icon' => 'violin'],
                        ['name' => 'Voice',    'icon' => 'voice'],
                        ['name' => 'Saxophone','icon' => 'saxophone'],
                        ['name' => 'Keyboard', 'icon' => 'keyboard'],
                        ['name' => 'Steelpan', 'icon' => 'steelpan'],
                        ['name' => 'Music Theory', 'icon' => 'theory'],
                    ];
                @endphp

                @foreach ($instruments as $instrument)
                    <div class="group relative overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/25 bg-[linear-gradient(160deg,#FFFFFF_0%,#FAF5FC_100%)] p-6 transition-all duration-300 hover:-translate-y-1 hover:border-[color:var(--soh-purple)]/40 hover:shadow-[0_12px_36px_rgba(166,18,141,0.14)]">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-colors duration-300 group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                            @switch($instrument['icon'])
                                @case('piano')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9v10M10 9v10M14 9v10M17 9v10"/></svg>
                                    @break
                                @case('guitar')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3l6 6-2 2-2-2-4 4a5 5 0 1 1-2-2l4-4-2-2 2-2z"/></svg>
                                    @break
                                @case('saxophone')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 3v4l-6 6a3 3 0 1 0 4.2 4.2l2.8-2.8"/><circle cx="18" cy="17" r="2"/></svg>
                                    @break
                                @case('voice')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="3" width="6" height="11" rx="3"/><path d="M6 11a6 6 0 0 0 12 0M12 17v4M9 21h6"/></svg>
                                    @break
                                @case('violin')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3l6 6"/><path d="M11 8a4 4 0 1 0 5 5l-2 2a4 4 0 1 1-5-5l2-2z"/></svg>
                                    @break
                                @case('keyboard')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M6 10h2v4H6zM10 10h2v4h-2zM14 10h2v4h-2z"/></svg>
                                    @break
                                @case('steelpan')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><circle cx="9" cy="10" r="1"/><circle cx="14.5" cy="9" r="1"/><circle cx="13" cy="14" r="1"/></svg>
                                    @break
                                @default
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h9l3 3v13H6z"/><path d="M9 9h6M9 13h6M9 17h4"/></svg>
                            @endswitch
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-[color:var(--soh-black)]">{{ $instrument['name'] }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PORTAL OVERVIEW --}}
    <section class="bg-[#F2F2F2] py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="text-center">
                <p class="text-xs font-bold tracking-[0.18em] text-[color:var(--soh-purple)] uppercase">{{ __('Student Portal') }}</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[color:var(--soh-black)] sm:text-4xl">{{ __('Your Musical Journey, Organized') }}</h2>
                <p class="mx-auto mt-4 max-w-2xl text-base text-gray-600 sm:text-lg">
                    {{ __('Track lessons, view assignments, and monitor progress — all in one place.') }}
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="rounded-2xl border border-[color:var(--soh-gray)]/25 bg-white p-7 shadow-[0_8px_30px_rgba(166,18,141,0.08)]">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-[color:var(--soh-black)]">{{ __('Students') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ __('Review assigned lessons, track homework, and update your activity status.') }}</p>
                    <a href="{{ route('login') }}" class="soh-link mt-4 inline-flex items-center gap-1 text-sm font-semibold">
                        {{ __('Student Login') }}
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                </div>

                <div class="rounded-2xl border border-[color:var(--soh-gray)]/25 bg-white p-7 shadow-[0_8px_30px_rgba(166,18,141,0.08)]">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-violet-50 text-violet-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path d="M12 14v7m0-7l6.16-3.422"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-[color:var(--soh-black)]">{{ __('Teachers') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ __('Upload materials, create lessons, assign coursework, and monitor student progress.') }}</p>
                    <a href="{{ route('login') }}" class="soh-link mt-4 inline-flex items-center gap-1 text-sm font-semibold">
                        {{ __('Teacher Login') }}
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                </div>

                <div class="rounded-2xl border border-[color:var(--soh-gray)]/25 bg-white p-7 shadow-[0_8px_30px_rgba(166,18,141,0.08)]">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 00-2 2v.18a2 2 0 01-1 1.73l-.43.25a2 2 0 01-2 0l-.15-.08a2 2 0 00-2.73.73l-.22.38a2 2 0 00.73 2.73l.15.1a2 2 0 011 1.72v.51a2 2 0 01-1 1.74l-.15.09a2 2 0 00-.73 2.73l.22.38a2 2 0 002.73.73l.15-.08a2 2 0 012 0l.43.25a2 2 0 011 1.73V20a2 2 0 002 2h.44a2 2 0 002-2v-.18a2 2 0 011-1.73l.43-.25a2 2 0 012 0l.15.08a2 2 0 002.73-.73l.22-.39a2 2 0 00-.73-2.73l-.15-.08a2 2 0 01-1-1.74v-.5a2 2 0 011-1.74l.15-.09a2 2 0 00.73-2.73l-.22-.38a2 2 0 00-2.73-.73l-.15.08a2 2 0 01-2 0l-.43-.25a2 2 0 01-1-1.73V4a2 2 0 00-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-[color:var(--soh-black)]">{{ __('Administrators') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ __('Manage users, oversee school operations, and keep platform content organized.') }}</p>
                    <a href="{{ route('login') }}" class="soh-link mt-4 inline-flex items-center gap-1 text-sm font-semibold">
                        {{ __('Admin Login') }}
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-[linear-gradient(160deg,#A6128D_0%,#6B025E_100%)] py-16 sm:py-20">
        <div class="pointer-events-none absolute inset-0" style="background: radial-gradient(ellipse 50% 60% at 50% 100%, rgba(217,145,205,0.2) 0%, transparent 70%);"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 text-center">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                {{ __('Ready to Start Your Musical Journey?') }}
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-base text-white/70 sm:text-lg">
                {{ __('Join hundreds of students discovering their talent with SOHMC. Enroll today and take the first step.') }}
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[#8C0375] shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">
                        {{ __('Enroll Now') }}
                    </a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:border-white/50 hover:bg-white/10">
                        {{ __('Contact Us') }}
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[#8C0375] shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">
                        {{ __('Go to Dashboard') }}
                    </a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:border-white/50 hover:bg-white/10">
                        {{ __('Contact Us') }}
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- QUICK LINKS --}}
    <section class="bg-[#F2F2F2] py-14 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <a href="{{ url('/about') }}" class="group rounded-2xl border border-[color:var(--soh-gray)]/25 bg-white p-6 shadow-[0_6px_24px_rgba(166,18,141,0.06)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_36px_rgba(166,18,141,0.14)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-colors group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[color:var(--soh-black)]">{{ __('About SOHMC') }}</h3>
                            <p class="mt-0.5 text-sm text-gray-500">{{ __('Our story, mission & values') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ url('/gallery') }}" class="group rounded-2xl border border-[color:var(--soh-gray)]/25 bg-white p-6 shadow-[0_6px_24px_rgba(166,18,141,0.06)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_36px_rgba(166,18,141,0.14)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-colors group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[color:var(--soh-black)]">{{ __('Gallery') }}</h3>
                            <p class="mt-0.5 text-sm text-gray-500">{{ __('Moments from classes & performances') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ url('/contact') }}" class="group rounded-2xl border border-[color:var(--soh-gray)]/25 bg-white p-6 shadow-[0_6px_24px_rgba(166,18,141,0.06)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_36px_rgba(166,18,141,0.14)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-colors group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[color:var(--soh-black)]">{{ __('Contact') }}</h3>
                            <p class="mt-0.5 text-sm text-gray-500">{{ __('Get in touch with the school') }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
</div>
