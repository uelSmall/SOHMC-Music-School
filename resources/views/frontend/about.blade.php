@extends('frontend.layouts.app')

@section('title')
    {{ __('About') }}
@endsection

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#A6128D] via-[#8C0375] to-[#4A0140] px-4 py-20 text-center sm:px-6 lg:px-10 lg:py-24">
        <div class="pointer-events-none absolute -top-24 -right-24 h-[400px] w-[400px] rounded-full bg-white/5 blur-[60px]"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-32 h-[500px] w-[500px] rounded-full bg-[#D991CD]/10 blur-[80px]"></div>
        <div class="relative z-10 mx-auto max-w-7xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/30 px-5 py-2 text-xs font-bold tracking-[0.12em] text-white/70 uppercase">
                <svg class="h-4 w-4 text-[#D991CD]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                Est. 2004
            </span>
            <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Our <span class="text-[#D991CD]">Story</span></h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg text-white/75 leading-relaxed">For over 20 years, Sounds of Harmony Music Centre has inspired, educated, and empowered students through the transformative power of music.</p>
            <div class="mt-10 flex flex-wrap justify-center gap-12">
                <div class="text-center">
                    <div class="font-[Sora] text-4xl font-extrabold text-white">20+</div>
                    <div class="mt-1 text-sm font-semibold text-white/60">Years of Excellence</div>
                </div>
                <div class="text-center">
                    <div class="font-[Sora] text-4xl font-extrabold text-white">300+</div>
                    <div class="mt-1 text-sm font-semibold text-white/60">Students Taught</div>
                </div>
                <div class="text-center">
                    <div class="font-[Sora] text-4xl font-extrabold text-white">6</div>
                    <div class="mt-1 text-sm font-semibold text-white/60">Instruments Offered</div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="block w-full" viewBox="0 0 1440 60" fill="none" style="height:56px"><path d="M0 60V20C240 0 480 0 720 20C960 40 1200 40 1440 20V60H0Z" fill="#fff"/></svg>
        </div>
    </section>

    {{-- Mission --}}
    <section class="bg-white px-4 py-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <p class="text-xs font-bold tracking-[0.18em] text-[color:var(--soh-purple)] uppercase">Our Mission</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Music Is More Than Learning an Instrument</h2>
            </div>
            <div class="mt-12 grid gap-8 lg:grid-cols-2">
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 p-8 sm:p-10 bg-gradient-to-br from-white to-[#FAF5FC] shadow-lg shadow-[color:var(--soh-purple)]/8">
                    <h3 class="text-xl font-bold">Nurturing Talent, Building Confidence</h3>
                    <p class="mt-4 text-[0.95rem] leading-relaxed text-gray-600">Our mission is to nurture talent, build confidence, and provide a positive, enriching environment where children, teens, and adults can discover and develop their musical abilities.</p>
                    <p class="mt-4 text-[0.95rem] leading-relaxed text-gray-600">At SOHMC, we believe music is a pathway to personal growth. We are passionate about empowering young people by keeping them positively engaged, reducing idle time, and encouraging creativity, discipline, and self-expression.</p>
                </div>
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/25 p-8 sm:p-10 bg-gradient-to-br from-[#A6128D] to-[#6B025E] text-white">
                    <h3 class="text-xl font-bold">Why Families Choose Us</h3>
                    <p class="mt-4 text-[0.95rem] leading-relaxed text-white/80">Highly qualified and professionally trained music instructors committed to delivering quality music education. Flexible learning options to suit every student's needs and schedule.</p>
                    <div class="mt-6 flex items-baseline gap-3">
                        <span class="font-[Sora] text-4xl font-extrabold">20+</span>
                        <span class="text-sm text-white/70">Years of musical excellence in our community</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="bg-[color:var(--soh-surface)] px-4 py-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <p class="text-xs font-bold tracking-[0.18em] text-[color:var(--soh-purple)] uppercase">Our Values</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">What Makes SOHMC Different</h2>
            </div>
            <div class="mt-12 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-7 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold">Expert Instructors</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Our team of highly qualified and professionally trained music instructors is committed to delivering quality music education.</p>
                </div>
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-7 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold">Online & In-Person Classes</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Flexible learning options to suit every student's needs and schedule — learn from home or in our modern facilities.</p>
                </div>
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-7 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold">Performance Opportunities</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Participate in recitals, concerts, showcases, and competitions to build real stage experience.</p>
                </div>
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/15 bg-white p-7 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[color:var(--soh-purple)]/12">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold">Safe & Enriching Environment</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">A positive space where students feel encouraged to explore their creativity and grow as musicians.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Impact --}}
    <section class="bg-white px-4 py-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <p class="text-xs font-bold tracking-[0.18em] text-[color:var(--soh-purple)] uppercase">Our Impact</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Music Education That Transforms Lives</h2>
                <p class="mx-auto mt-4 max-w-2xl text-gray-600 leading-relaxed">Our music programme helps students develop both musical and life skills that carry into school, family, and future careers.</p>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2">
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/12 bg-[color:var(--soh-surface)] p-7">
                    <ul class="space-y-0">
                        <li class="border-b border-[color:var(--soh-purple)]/8 py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Build Confidence & Self-Esteem:</strong> Develop self-belief and the courage to shine.</li>
                        <li class="border-b border-[color:var(--soh-purple)]/8 py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Improve Concentration & Memory:</strong> Strengthen mental skills that last a lifetime.</li>
                        <li class="border-b border-[color:var(--soh-purple)]/8 py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Reduce Stress:</strong> Promote emotional well-being and peace of mind.</li>
                        <li class="py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Increase Math Skills:</strong> Build stronger math and problem-solving abilities through music.</li>
                    </ul>
                </div>
                <div class="rounded-3xl border border-[color:var(--soh-purple)]/12 bg-[color:var(--soh-surface)] p-7">
                    <ul class="space-y-0">
                        <li class="border-b border-[color:var(--soh-purple)]/8 py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Read & Understand Music:</strong> Develop the ability to read and understand music fluently.</li>
                        <li class="border-b border-[color:var(--soh-purple)]/8 py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Cultivate Discipline & Patience:</strong> Learn valuable life skills through consistent practice.</li>
                        <li class="border-b border-[color:var(--soh-purple)]/8 py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Prepare For Graded Exams:</strong> Gain the knowledge, skills, and confidence to excel.</li>
                        <li class="py-3 text-sm text-gray-600 leading-relaxed"><strong class="text-[color:var(--soh-purple)] font-bold">Performance Opportunities:</strong> Participate in recitals, concerts, showcases, and competitions.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Instruments --}}
    <section class="bg-white px-4 pb-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <p class="text-xs font-bold tracking-[0.18em] text-[color:var(--soh-purple)] uppercase">What We Teach</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Instruments & Classes Offered</h2>
            </div>
            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach(['Piano/Keyboard' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9v10M10 9v10M14 9v10M17 9v10"/>', 'Guitar' => '<path d="M15 3l6 6-2 2-2-2-4 4a5 5 0 1 1-2-2l4-4-2-2 2-2z"/>', 'Violin' => '<path d="M15 3l6 6"/><path d="M11 8a4 4 0 1 0 5 5l-2 2a4 4 0 1 1-5-5l2-2z"/>', 'Voice' => '<rect x="9" y="3" width="6" height="11" rx="3"/><path d="M6 11a6 6 0 0 0 12 0M12 17v4M9 21h6"/>', 'Saxophone' => '<path d="M18 3v4l-6 6a3 3 0 1 0 4.2 4.2l2.8-2.8"/><circle cx="18" cy="17" r="2"/>', 'Steelpan' => '<circle cx="12" cy="12" r="8"/><circle cx="9" cy="10" r="1"/><circle cx="14.5" cy="9" r="1"/><circle cx="13" cy="14" r="1"/>', 'Music Theory' => '<path d="M6 4h9l3 3v13H6z"/><path d="M9 9h6M9 13h6M9 17h4"/>'] as $name => $svg)
                    <div class="rounded-2xl border border-[color:var(--soh-purple)]/12 bg-white p-5 transition-all duration-300 hover:-translate-y-0.5 hover:border-[color:var(--soh-purple)]/30 hover:shadow-lg hover:shadow-[color:var(--soh-purple)]/14 group">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)] transition-all duration-300 group-hover:bg-[color:var(--soh-purple)] group-hover:text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round">{!! $svg !!}</svg>
                        </div>
                        <h3 class="mt-3.5 text-sm font-bold">{{ $name }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#A6128D] to-[#6B025E] px-4 py-20 text-center sm:px-6 lg:px-10">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_50%_60%_at_50%_100%,rgba(217,145,205,0.2),transparent_70%)]"></div>
        <div class="relative z-10 mx-auto max-w-7xl">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Ready to Start Your Musical Journey?</h2>
            <p class="mx-auto mt-4 max-w-xl text-white/70 leading-relaxed">Join hundreds of students discovering their talent with SOHMC. Enroll today and take the first step.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[color:var(--soh-purple-dark)] shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">Enroll Now</a>
                <a href="{{ route('frontend.contact') }}" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:border-white/50 hover:bg-white/10">Contact Us</a>
            </div>
        </div>
    </section>
@endsection
