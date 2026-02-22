<nav x-data="{ open: false }" class="soh-nav">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="soh-brand soh-brand-lockup soh-brand-dark" aria-label="Go to homepage">
                    <span class="soh-monogram md:hidden">SOHMC</span>
                    <span class="soh-brand-wordmark hidden md:flex">
                        <span class="text-xl font-extrabold leading-none tracking-tight">SOHMC</span>
                        <span class="text-sm font-semibold text-white/90">Sounds of Harmony Music Centre</span>
                    </span>
                </a>

                <div class="hidden items-center gap-2 sm:flex">
                    @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('administrator'))
                        <a href="{{ route('backend.dashboard') }}" class="soh-nav-link {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}">Admin</a>
                    @endif

                    @if (auth()->user()->hasRole('teacher'))
                        <a href="{{ route('teacher.dashboard') }}" class="soh-nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">Teacher</a>
                    @endif

                    @if (auth()->user()->hasRole('student'))
                        <a href="{{ route('student.dashboard') }}" class="soh-nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('lessons.index') }}" class="soh-nav-link {{ request()->routeIs('lessons.index') ? 'active' : '' }}">My Lessons</a>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-2 sm:flex">
                <a href="{{ route('profile.edit') }}" class="soh-nav-link">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="soh-avatar-chip inline-flex items-center gap-2 px-3 py-2 text-sm font-medium">
                        <span>{{ Auth::user()->name }}</span>
                        <span>· Logout</span>
                    </button>
                </form>
            </div>

            <div class="flex items-center sm:hidden">
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
                <a href="{{ route('backend.dashboard') }}" class="soh-nav-link block">Admin Dashboard</a>
            @endif

            @if (auth()->user()->hasRole('teacher'))
                <a href="{{ route('teacher.dashboard') }}" class="soh-nav-link block">Teacher Dashboard</a>
            @endif

            @if (auth()->user()->hasRole('student'))
                <a href="{{ route('student.dashboard') }}" class="soh-nav-link block">Dashboard</a>
                <a href="{{ route('lessons.index') }}" class="soh-nav-link block">My Lessons</a>
            @endif

            <a href="{{ route('profile.edit') }}" class="soh-nav-link block">Profile</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="soh-nav-link w-full text-left">Logout</button>
            </form>
        </div>
    </div>
</nav>
