<nav
    x-data="{ mobileMenu: false }"
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
    </div>
</nav>
