<nav
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

    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between p-4">
        <a class="soh-brand-wordmark soh-brand-dark text-white" href="/" wire:navigate aria-label="Go to homepage">
            <span class="text-xl font-extrabold leading-none tracking-tight">SOHMC</span>
            <span class="text-sm font-semibold text-white/90">Sounds of Harmony Music Centre</span>
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
                <button
                    class="inline-flex cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-white/90 hover:bg-white/10 hover:text-white"
                    data-dropdown-toggle="user-dropdown-menu"
                    type="button"
                    aria-label="User menu"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <img
                        class="h-9 rounded-md"
                        src="{{ asset(Auth::user()->avatar) }}"
                        alt="{{ Auth::user()->name }}'s profile picture"
                    />
                    <span class="ms-2 hidden sm:block">
                        {{ Auth::user()->last_name }}
                    </span>
                </button>
                <!-- Dropdown:user-dropdown-menu -->
                <div
                    class="z-50 my-4 hidden list-none divide-y divide-gray-100 rounded-lg bg-white text-base shadow-sm dark:bg-gray-700"
                    id="user-dropdown-menu"
                    role="menu"
                    aria-label="User account menu"
                >
                    <ul class="py-2 font-medium" role="none">
                        @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('administrator'))
                            <li class="border-b-2 border-gray-200">
                                <a
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="{{ route("backend.dashboard") }}"
                                    role="menuitem"
                                    wire:navigate
                                >
                                    <div class="inline-flex items-center">
                                        <svg
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-layout-dashboard"
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
                                            <path
                                                d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1"
                                            />
                                            <path
                                                d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1"
                                            />
                                            <path
                                                d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1"
                                            />
                                            <path
                                                d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1"
                                            />
                                        </svg>
                                        {{ __("Admin Dashboard") }}
                                    </div>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasRole('teacher'))
                            <li class="border-b-2 border-gray-200">
                                <a
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="{{ route("teacher.dashboard") }}"
                                    role="menuitem"
                                    wire:navigate
                                >
                                    <div class="inline-flex items-center">
                                        <i class="fa-solid fa-chalkboard-user me-2"></i>
                                        {{ __("Teacher Dashboard") }}
                                    </div>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasRole('parent'))
                            <li class="border-b-2 border-gray-200">
                                <a
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="{{ route("parent.dashboard") }}"
                                    role="menuitem"
                                    wire:navigate
                                >
                                    <div class="inline-flex items-center">
                                        <i class="fa-solid fa-people-roof me-2"></i>
                                        {{ __("Parent Dashboard") }}
                                    </div>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasRole('parent'))
                            <li class="border-b-2 border-gray-200">
                                <a
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="{{ route("parent.dashboard") }}"
                                    role="menuitem"
                                    wire:navigate
                                >
                                    <div class="inline-flex items-center">
                                        <i class="fa-solid fa-people-roof me-2"></i>
                                        {{ __("Parent Dashboard") }}
                                    </div>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasRole('student'))
                            <li class="border-b-2 border-gray-200">
                                <a
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    href="{{ route("student.dashboard") }}"
                                    role="menuitem"
                                    wire:navigate
                                >
                                    <div class="inline-flex items-center">
                                        <svg
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-layout-dashboard me-2"
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
                                            <path d="M4 4h6v8h-6z" />
                                            <path d="M4 16h6v4h-6z" />
                                            <path d="M14 12h6v8h-6z" />
                                            <path d="M14 4h6v4h-6z" />
                                        </svg>
                                        {{ __("Student Dashboard") }}
                                    </div>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                href="{{ route("frontend.users.profile") }}"
                                role="menuitem"
                                wire:navigate
                            >
                                <div class="inline-flex items-center">
                                    <svg
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-bolt me-2"
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
                                    {{ Auth::user()->name }}
                                </div>
                            </a>
                        </li>
                        <li>
                            <a
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                href="{{ route("frontend.users.profileEdit") }}"
                                role="menuitem"
                                wire:navigate
                            >
                                <div class="inline-flex items-center">
                                    <svg
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-settings-cog me-2"
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
                                        <path
                                            d="M12.003 21c-.732 .001 -1.465 -.438 -1.678 -1.317a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c.886 .215 1.325 .957 1.318 1.694"
                                        />
                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M19.001 15.5v1.5" />
                                        <path d="M19.001 21v1.5" />
                                        <path d="M22.032 17.25l-1.299 .75" />
                                        <path d="M17.27 20l-1.3 .75" />
                                        <path d="M15.97 17.25l1.3 .75" />
                                        <path d="M20.733 20l1.3 .75" />
                                    </svg>
                                    {{ __("Settings") }}
                                </div>
                            </a>
                        </li>
                        <li>
                            <a
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                href="{{ route("logout") }}"
                                role="menuitem"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            >
                                <div class="inline-flex items-center">
                                    <svg
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-logout me-2"
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
                                        <path
                                            d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"
                                        />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg>
                                    {{ __("Logout") }}
                                </div>
                            </a>
                        </li>
                        <form id="logout-form" style="display: none" action="{{ route("logout") }}" method="POST">
                            {{ csrf_field() }}
                        </form>
                    </ul>
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
