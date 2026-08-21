<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->currentLocale()) }}" dir="{{ language_direction() }}">
    <head>
        <meta charset="utf-8" />
        <link href="{{ asset("img/sohmc-logo-icon.jpg") }}" rel="apple-touch-icon" sizes="76x76" />
        <link type="image/jpeg" href="{{ asset("img/sohmc-logo-icon.jpg") }}" rel="icon" />
        <title>{{ $title ?? "Admin" }} | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />
        <link href="{{ asset("img/sohmc-logo-icon.jpg") }}" rel="shortcut icon" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @vite(["resources/css/app-frontend.css", "resources/js/app-frontend.js"])
        @livewireStyles
        @stack("after-styles")
    </head>
    <body class="bg-[color:var(--soh-surface)] font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            {{-- Mobile sidebar overlay --}}
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>

            {{-- Sidebar --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 translate-x-0 overflow-y-auto border-r border-[color:var(--soh-gray)]/20 bg-white transition-transform duration-200 lg:translate-x-0">
                <div class="flex h-16 items-center gap-3 border-b border-[color:var(--soh-gray)]/20 px-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('img/sohmc-logo-icon.jpg') }}" alt="SOHMC" class="h-9 w-9 rounded-lg object-cover" />
                        <span class="text-lg font-bold text-[color:var(--soh-purple)]">SOHMC Admin</span>
                    </a>
                </div>

                <nav class="mt-6 space-y-1 px-3">
                    @php
                        $navItems = [
                            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>'],
                            ['label' => 'Teacher Dashboard', 'route' => 'admin.teacher-dashboard', 'icon' => '<path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>'],
                            ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                            ['label' => 'Gallery', 'route' => 'admin.gallery.index', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>'],
                            ['label' => 'Settings', 'route' => 'admin.settings.index', 'icon' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['route'] . '*'); @endphp
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $active ? 'bg-[color:var(--soh-purple)]/10 text-[color:var(--soh-purple)]' : 'text-gray-600 hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]' }}">
                            <svg class="h-5 w-5 {{ $active ? 'text-[color:var(--soh-purple)]' : 'text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="absolute bottom-0 left-0 right-0 border-t border-[color:var(--soh-gray)]/20 p-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-500 transition hover:bg-[color:var(--soh-surface)] hover:text-[color:var(--soh-purple)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        View Site
                    </a>
                </div>
            </aside>

            {{-- Main content --}}
            <div class="lg:pl-72">
                {{-- Top bar --}}
                <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-[color:var(--soh-gray)]/20 bg-white/80 px-4 backdrop-blur-sm sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                    </button>

                    <div class="flex-1"></div>

                    <div x-data="{ open: false }" class="relative" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2.5 rounded-full border border-[color:var(--soh-gray)]/30 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            <img class="h-8 w-8 rounded-full border border-[color:var(--soh-gray)]/25 object-cover" src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" />
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-cloak x-show="open" x-transition class="absolute right-0 top-full z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/30 bg-white shadow-xl">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Page content --}}
                <main class="py-6">
                    @if (session()->has('status'))
                        <div class="mx-auto mb-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mx-auto mb-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
        @stack("after-scripts")
    </body>
</html>
