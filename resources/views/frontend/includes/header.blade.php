<nav
    x-data="{ profileMenu: false }"
    class="border-b-2 border-[#8C0375] bg-[#A6128D] shadow-md dark:border-[#8C0375] dark:bg-[#A6128D]"
    role="navigation"
    aria-label="Main navigation"
>
    <!-- Skip to main content link for accessibility -->
    <a
        href="#main-content"
        class="sr-only z-50 rounded-md bg-blue-600 px-4 py-2 text-white focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    >
        Skip to main content
    </a>

    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a class="inline-flex items-center" href="/" wire:navigate aria-label="Go to homepage">
            <img
                src="{{ asset('img/sohmc-nav-logo.png') }}"
                alt="Sounds of Harmony Music Centre"
                class="h-12 w-auto"
            />
        </a>
        <div class="flex items-center justify-end space-x-1 text-white/90 md:order-2 md:space-x-0 rtl:space-x-reverse">
            @guest
                @if (user_registration())
                    <a
                        class="inline-flex cursor-pointer items-center justify-center rounded-sm p-2 text-sm font-medium text-white/90 hover:bg-white/10 hover:text-white sm:px-4 sm:py-2"
                        href="{{ route("register") }}"
                        wire:navigate
                    >
                        <svg
                            class="icon icon-tabler icons-tabler-outline icon-tabler-user-bolt"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.267 0 .529 .026 .781 .076" />
                            <path d="M19 16l-2 3h4l-2 3" />
                        </svg>
                        <span class="ms-2 hidden sm:block">
                            {{ __("Register") }}
                        </span>
                    </a>
                @endif

                <a
                    class="inline-flex cursor-pointer items-center justify-center rounded-sm p-2 text-sm font-medium text-white/90 hover:bg-white/10 hover:text-white sm:px-4 sm:py-2"
                    href="{{ route("login") }}"
                    wire:navigate
                >
                    <svg
                        class="icon icon-tabler icons-tabler-outline icon-tabler-login"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M21 12h-13l3 -3" />
                        <path d="M11 15l-3 -3" />
                    </svg>
                    <span class="ms-2 hidden sm:block">
                        {{ __("Login") }}
                    </span>
                </a>
            @endguest

            @auth
                <div class="relative" @click.outside="profileMenu = false">
                    <button
                        @click="profileMenu = ! profileMenu"
                        type="button"
                        class="soh-avatar-chip inline-flex items-center gap-3 px-3 py-2 text-sm font-medium"
                        aria-haspopup="true"
                        x-bind:aria-expanded="profileMenu.toString()"
                    >
                        <img
                            class="h-9 w-9 rounded-full border border-white/25 object-cover"
                            src="{{ asset(Auth::user()->avatar) }}"
                            alt="{{ Auth::user()->name }}"
                        />
                        <span class="hidden flex-col items-start leading-tight sm:flex">
                            <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-white/75">{{ str(auth()->user()->dashboardRouteName())->before('.')->headline() }}</span>
                        </span>
                        <svg class="h-4 w-4 text-white/80 transition-transform" x-bind:class="profileMenu ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div
                        x-cloak
                        x-show="profileMenu"
                        x-transition.origin.top.right
                        class="absolute right-0 top-[calc(100%+0.75rem)] z-50 w-72 overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/35 bg-white shadow-[0_22px_50px_rgba(13,13,13,0.16)]"
                    >
                        <div class="border-b border-[color:var(--soh-gray)]/25 bg-[linear-gradient(180deg,rgba(166,18,141,0.08)_0%,rgba(217,145,205,0.12)_100%)] px-5 py-4">
                            <p class="text-sm font-semibold text-[color:var(--soh-black)]">{{ Auth::user()->name }}</p>
                            <p class="mt-1 text-xs uppercase tracking-[0.14em] text-[color:var(--soh-purple)]">{{ str(auth()->user()->dashboardRouteName())->before('.')->headline() }}</p>
                        </div>

                        <div class="px-3 py-3">
                            <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]" wire:navigate>
                                Dashboard
                            </a>

                            @if (auth()->user()->hasRole('student'))
                                <a href="{{ route('lessons.index') }}" class="mt-1 block rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]" wire:navigate>
                                    My Lessons
                                </a>
                            @endif

                            <a href="{{ route('frontend.users.profileEdit') }}" class="mt-1 block rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]" wire:navigate>
                                Profile Settings
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-[color:var(--soh-gray)]/20 pt-2">
                                @csrf
                                <button type="submit" class="block w-full rounded-xl px-3 py-2.5 text-left text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

            <button
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-white hover:bg-white/15 focus:ring-2 focus:ring-white/30 focus:outline-hidden md:hidden"
                data-collapse-toggle="navbar-language"
                type="button"
                aria-controls="navbar-language"
                aria-expanded="false"
                aria-label="Toggle navigation menu"
            >
                <span class="sr-only">Open main menu</span>
                <svg
                    class="h-5 w-5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 17 14"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15"
                    />
                </svg>
            </button>
        </div>

        <div class="hidden w-full items-center justify-between md:order-1 md:flex md:w-auto" id="navbar-language">
            <ul class="flex flex-col rounded-lg p-4 font-medium md:mt-0 md:flex-row md:space-x-1 md:border-0 md:bg-transparent md:p-0 rtl:space-x-reverse">
                <li>
                    <a
                        href="{{ route('frontend.index') }}"
                        class="soh-nav-link {{ request()->routeIs('home') || request()->routeIs('frontend.index') ? 'active' : '' }}"
                        wire:navigate
                    >
                        {{ __('Home') }}
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('frontend.about') }}"
                        class="soh-nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}"
                        wire:navigate
                    >
                        {{ __('About') }}
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('frontend.gallery') }}"
                        class="soh-nav-link {{ request()->routeIs('frontend.gallery') ? 'active' : '' }}"
                        wire:navigate
                    >
                        {{ __('Gallery') }}
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('frontend.contact') }}"
                        class="soh-nav-link {{ request()->routeIs('frontend.contact') ? 'active' : '' }}"
                        wire:navigate
                    >
                        {{ __('Contact') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
