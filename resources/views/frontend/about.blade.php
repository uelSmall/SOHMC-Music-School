@extends('frontend.layouts.app')

@section('title')
    {{ __('About') }}
@endsection

@section('content')
    <section class="relative overflow-hidden bg-[linear-gradient(160deg,#FDFBFD_0%,#F1D8EC_100%)] px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid items-stretch gap-6 lg:grid-cols-2">
                <article class="soh-card flex h-full flex-col p-8 sm:p-10">
                    <p class="text-xs font-bold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">About SOHMC</p>
                    <h1 class="mt-3 text-4xl font-bold tracking-tight text-[color:var(--soh-black)] sm:text-5xl">Sounds of Harmony Music Centre</h1>
                    <p class="mt-5 text-base leading-relaxed text-gray-700 sm:text-lg">
                        For over 13 years, Sounds of Harmony Music Centre has inspired, educated, and empowered students through
                        the transformative power of music. Our mission is to nurture talent, build confidence, and provide a
                        positive, enriching environment where children, teens, and adults can discover and develop their musical abilities.
                    </p>

                    <div class="mt-6 rounded-xl border border-[color:var(--soh-gray)]/40 bg-[color:var(--soh-surface)] p-4">
                        <p class="text-sm leading-relaxed text-gray-700">
                            At Sounds of Harmony Music Centre, we believe music is more than learning an instrument.
                            It is a pathway to personal growth. We are passionate about empowering young people by keeping them
                            positively engaged, reducing idle time, and encouraging creativity, discipline, and self-expression.
                        </p>
                    </div>
                </article>

                <article class="soh-card flex h-full flex-col p-8 sm:p-10">
                    <div class="mb-4 inline-flex rounded-full border border-[color:var(--soh-purple)]/30 bg-[color:var(--soh-purple)] px-4 py-1.5 text-xs font-semibold tracking-[0.12em] text-white uppercase">
                        Over 13 Years Of Musical Excellence
                    </div>

                    <h2 class="text-2xl font-semibold text-[color:var(--soh-black)]">Why Families Choose Us</h2>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-xl border border-[color:var(--soh-gray)]/40 p-4">
                            <h3 class="text-lg font-semibold text-[color:var(--soh-purple)]">Highly Qualified And Trained Teachers</h3>
                            <p class="mt-2 text-sm leading-relaxed text-gray-700">
                                Our team of highly qualified and professionally trained music instructors is committed to delivering quality music education.
                            </p>
                        </div>

                        <div class="rounded-xl border border-[color:var(--soh-gray)]/40 p-4">
                            <h3 class="text-lg font-semibold text-[color:var(--soh-purple)]">Online Or In-Person Classes</h3>
                            <p class="mt-2 text-sm leading-relaxed text-gray-700">
                                Flexible learning options to suit every student&apos;s needs and schedule.
                            </p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 text-center">
                <p class="text-xs font-semibold tracking-[0.14em] text-[color:var(--soh-purple)] uppercase">Our Impact</p>
                <h2 class="mt-2 text-3xl font-bold text-[color:var(--soh-black)]">Music Education That Transforms Lives</h2>
                <p class="mx-auto mt-3 max-w-3xl text-sm leading-relaxed text-gray-600 sm:text-base">
                    Our music programme helps students develop both musical and life skills that carry into school, family, and future careers.
                </p>
            </div>

            <div class="grid items-stretch gap-6 lg:grid-cols-2">
                <div class="soh-card h-full p-6">
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Build Confidence &amp; Self-Esteem:</span> Develop self-belief and the courage to shine.</li>
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Improve Concentration, Focus &amp; Memory:</span> Strengthen mental skills that last a lifetime.</li>
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Reduce Stress:</span> Promote emotional well-being and peace of mind.</li>
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Increase Math Skills:</span> Build stronger math and problem-solving abilities through music.</li>
                    </ul>
                </div>

                <div class="soh-card h-full p-6">
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Read &amp; Understand Music:</span> Develop the ability to read and understand music fluently.</li>
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Cultivate Discipline, Patience &amp; Perseverance:</span> Learn valuable life skills through consistent practice.</li>
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Prepare For Graded Music Exams:</span> Gain the knowledge, skills, and confidence to excel.</li>
                        <li><span class="font-semibold text-[color:var(--soh-purple)]">Performance Opportunities:</span> Participate in recitals, concerts, showcases, and competitions.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4 pb-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="soh-card p-6 sm:p-8">
                <h2 class="text-center text-2xl font-semibold text-[color:var(--soh-black)]">Instruments &amp; Classes Offered</h2>

                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-8">
                    @foreach ([
                        ['name' => 'Piano', 'icon' => 'piano'],
                        ['name' => 'Guitar', 'icon' => 'guitar'],
                        ['name' => 'Saxophone', 'icon' => 'saxophone'],
                        ['name' => 'Voice / Singing', 'icon' => 'voice'],
                        ['name' => 'Violin', 'icon' => 'violin'],
                        ['name' => 'Keyboard', 'icon' => 'keyboard'],
                        ['name' => 'Steelpan', 'icon' => 'steelpan'],
                        ['name' => 'Music Theory', 'icon' => 'theory'],
                    ] as $class)
                        <div class="flex h-full min-h-[64px] items-center justify-center rounded-lg border border-[color:var(--soh-gray)]/40 bg-[color:var(--soh-surface)] px-3 py-2 text-center text-sm font-semibold leading-tight text-[color:var(--soh-purple)]">
                            <span class="inline-flex items-center gap-2">
                                @switch($class['icon'])
                                    @case('piano')
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                            <path d="M7 9v10M10 9v10M14 9v10M17 9v10"></path>
                                        </svg>
                                        @break
                                    @case('guitar')
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M15 3l6 6-2 2-2-2-4 4a5 5 0 1 1-2-2l4-4-2-2 2-2z"></path>
                                        </svg>
                                        @break
                                    @case('saxophone')
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M18 3v4l-6 6a3 3 0 1 0 4.2 4.2l2.8-2.8"></path>
                                            <circle cx="18" cy="17" r="2"></circle>
                                        </svg>
                                        @break
                                    @case('voice')
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="9" y="3" width="6" height="11" rx="3"></rect>
                                            <path d="M6 11a6 6 0 0 0 12 0M12 17v4M9 21h6"></path>
                                        </svg>
                                        @break
                                    @case('violin')
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M15 3l6 6"></path>
                                            <path d="M11 8a4 4 0 1 0 5 5l-2 2a4 4 0 1 1-5-5l2-2z"></path>
                                        </svg>
                                        @break
                                    @case('keyboard')
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                            <path d="M6 10h2v4H6zM10 10h2v4h-2zM14 10h2v4h-2z"></path>
                                        </svg>
                                        @break
                                    @case('steelpan')
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="8"></circle>
                                            <circle cx="9" cy="10" r="1"></circle>
                                            <circle cx="14.5" cy="9" r="1"></circle>
                                            <circle cx="13" cy="14" r="1"></circle>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M6 4h9l3 3v13H6z"></path>
                                            <path d="M9 9h6M9 13h6M9 17h4"></path>
                                        </svg>
                                @endswitch
                                <span>{{ $class['name'] }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
