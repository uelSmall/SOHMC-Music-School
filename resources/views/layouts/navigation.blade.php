<nav x-data="{ open: false, profileMenu: false }" @keydown.escape.window="open = false; profileMenu = false" class="soh-nav">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="inline-flex items-center" aria-label="Go to homepage">
                    <img
                        src="{{ asset('img/sohmc-nav-logo.png') }}"
                        alt="Sounds of Harmony Music Centre"
                        class="h-12 w-auto"
                    />
                </a>

                <div class="hidden items-center gap-2 sm:flex">
                    @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('administrator'))
                        <a href="{{ route('backend.dashboard') }}" class="soh-nav-link {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}">Admin</a>
                    @endif

                    @if (auth()->user()->hasRole('parent'))
                        <a href="{{ route('parent.dashboard') }}" class="soh-nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">Parent</a>
                    @endif

                    <livewire:notifications.notification-bell />

                </div>
            </div>

            <div class="relative hidden items-center sm:flex" @click.outside="profileMenu = false">
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
                    <span class="flex flex-col items-start leading-tight">
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
                        <a @click="profileMenu = false" href="{{ route(auth()->user()->dashboardRouteName()) }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">Dashboard</a>

                        <a @click="profileMenu = false" href="{{ route('notifications.index') }}" class="mt-1 block rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">My Notifications</a>

                        @if (auth()->user()->hasRole('student'))
                            <a @click="profileMenu = false" href="{{ route('lessons.index') }}" class="mt-1 block rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">My Lessons</a>
                        @endif

                        <a @click="profileMenu = false" href="{{ route('profile.edit') }}" class="mt-1 block rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">Profile Settings</a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-[color:var(--soh-gray)]/20 pt-2">
                            @csrf
                            <button type="submit" class="block w-full rounded-xl px-3 py-2.5 text-left text-sm font-medium text-gray-700 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:hidden">
                <livewire:notifications.notification-bell />

                <button @click="open = ! open" class="rounded-md p-2 text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden border-t border-white/20 sm:hidden">
        <div class="space-y-1 px-4 py-3">
            @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('administrator'))
                <a @click="open = false" href="{{ route('backend.dashboard') }}" class="soh-nav-link block">Admin Dashboard</a>
            @endif

            @if (auth()->user()->hasRole('parent'))
                <a @click="open = false" href="{{ route('parent.dashboard') }}" class="soh-nav-link block">Parent Dashboard</a>
            @endif

            <a @click="open = false" href="{{ route('notifications.index') }}" class="soh-nav-link block">My Notifications</a>

            <a @click="open = false" href="{{ route('profile.edit') }}" class="soh-nav-link block">Profile</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="soh-nav-link w-full text-left">Logout</button>
            </form>
        </div>
    </div>
</nav>
