<nav
    x-data="{ mobileMenu: false, profileMenu: false }"
    @keydown.escape.window="mobileMenu = false; profileMenu = false"
    class="border-b-2 border-[#8C0375] bg-[#A6128D] shadow-md"
    role="navigation"
    aria-label="Main navigation"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
        <div class="flex items-center justify-between h-16">
            
            <!-- Left: Logo -->
            <div class="flex-shrink-0">
                <a href="/" wire:navigate aria-label="Go to homepage">
                    <img src="{{ asset('img/sohmc-nav-logo.png') }}" alt="SOHMC Logo" class="h-10 w-auto sm:h-12" />
                </a>
            </div>

            <!-- Center: Nav links (desktop only) -->
            <div class="hidden md:flex md:flex-1 md:justify-center">
                <ul class="flex space-x-6 font-medium text-white">
                    <li>
                        <a href="{{ route('frontend.index') }}"
                           class="px-3 py-2 rounded-md transition-colors duration-200
                                  hover:bg-[#8C0375] hover:text-white
                                  {{ request()->routeIs('frontend.index') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">
                           Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.about') }}"
                           class="px-3 py-2 rounded-md transition-colors duration-200
                                  hover:bg-[#8C0375] hover:text-white
                                  {{ request()->routeIs('frontend.about') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">
                           About
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.gallery') }}"
                           class="px-3 py-2 rounded-md transition-colors duration-200
                                  hover:bg-[#8C0375] hover:text-white
                                  {{ request()->routeIs('frontend.gallery') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">
                           Gallery
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.contact') }}"
                           class="px-3 py-2 rounded-md transition-colors duration-200
                                  hover:bg-[#8C0375] hover:text-white
                                  {{ request()->routeIs('frontend.contact') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">
                           Contact
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Right: Auth links (desktop + mobile) -->
            <div class="flex items-center space-x-6 text-white">
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white">
                        <!-- Register icon -->
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             viewBox="0 0 24 24">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.267 0 .529 .026 .781 .076" />
                            <path d="M19 16l-2 3h4l-2 3" />
                        </svg>
                        Register
                    </a>

                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white">
                        <!-- Login icon -->
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             viewBox="0 0 24 24">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path d="M21 12h-13l3 -3" />
                            <path d="M11 15l-3 -3" />
                        </svg>
                        Login
                    </a>
                @endguest

                @auth
                    <!-- Desktop: Profile dropdown -->
                    <div class="relative hidden md:block" @click.outside="profileMenu = false">
                        <button
                            @click="profileMenu = ! profileMenu"
                            type="button"
                            class="inline-flex items-center gap-2.5 rounded-full border border-white/20 bg-white/14 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-white/20"
                            aria-haspopup="true"
                            x-bind:aria-expanded="profileMenu.toString()"
                        >
                            <img
                                class="h-8 w-8 rounded-full border border-white/25 object-cover"
                                src="{{ asset(Auth::user()->avatar) }}"
                                alt="{{ Auth::user()->name }}"
                            />
                            <span class="flex flex-col items-start leading-tight">
                                <span class="font-semibold">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-white/70">{{ str(Auth::user()->dashboardRouteName())->before('.')->headline() }}</span>
                            </span>
                            <svg class="h-4 w-4 text-white/70 transition-transform duration-200" :class="profileMenu ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div
                            x-cloak
                            x-show="profileMenu"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 top-[calc(100%+0.5rem)] z-50 w-64 overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/35 bg-white shadow-[0_22px_50px_rgba(13,13,13,0.16)]"
                        >
                            <!-- User info header -->
                            <div class="border-b border-[color:var(--soh-gray)]/25 bg-[linear-gradient(180deg,rgba(166,18,141,0.08)_0%,rgba(217,145,205,0.12)_100%)] px-5 py-4">
                                <p class="text-sm font-semibold text-[color:var(--soh-black)]">{{ Auth::user()->name }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.14em] text-[color:var(--soh-purple)]">{{ str(Auth::user()->dashboardRouteName())->before('.')->headline() }}</p>
                            </div>

                            <!-- Menu items -->
                            <div class="px-3 py-3">
                                <a @click="profileMenu = false" href="{{ route(Auth::user()->dashboardRouteName()) }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">
                                    <svg class="h-4 w-4 text-[color:var(--soh-purple)]/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    Dashboard
                                </a>

                                <a @click="profileMenu = false" href="{{ route('profile.edit') }}" class="mt-1 flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">
                                    <svg class="h-4 w-4 text-[color:var(--soh-purple)]/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Profile Settings
                                </a>

                                <div class="my-2 border-t border-[color:var(--soh-gray)]/20"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">
                                        <svg class="h-4 w-4 text-[color:var(--soh-purple)]/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                <!-- Mobile menu toggle -->
                <button
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-white hover:bg-white/15 focus:ring-2 focus:ring-white/30 md:hidden"
                    @click="mobileMenu = !mobileMenu"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    aria-label="Toggle navigation menu"
                >
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!mobileMenu" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                    <svg x-show="mobileMenu" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu (vertical layout, no duplicate auth links) -->
    <div x-cloak x-show="mobileMenu" id="mobile-menu" class="md:hidden px-4 pb-4 space-y-2 bg-[#A6128D]">
        <a href="{{ route('frontend.index') }}" class="block text-white px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white {{ request()->routeIs('frontend.index') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">Home</a>
        <a href="{{ route('frontend.about') }}" class="block text-white px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white {{ request()->routeIs('frontend.about') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">About</a>
        <a href="{{ route('frontend.gallery') }}" class="block text-white px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white {{ request()->routeIs('frontend.gallery') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">Gallery</a>
        <a href="{{ route('frontend.contact') }}" class="block text-white px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white {{ request()->routeIs('frontend.contact') ? 'bg-[#8C0375] text-white font-semibold' : '' }}">Contact</a>

        @auth
            <div class="border-t border-white/15 pt-2 mt-2">
                <div class="px-3 py-2">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/60 uppercase tracking-wider">{{ str(Auth::user()->dashboardRouteName())->before('.')->headline() }}</p>
                </div>
                <a href="{{ route(Auth::user()->dashboardRouteName()) }}" class="block text-white px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="block text-white px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white">Profile Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left text-white px-3 py-2 rounded-md transition-colors duration-200 hover:bg-[#8C0375] hover:text-white">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</nav>
