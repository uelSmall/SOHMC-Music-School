<x-layouts.admin :title="'Users'">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Admin Dashboard', 'route' => route('admin.dashboard')],
            ['label' => 'Users', 'current' => true],
        ]" />

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Users</h1>
                <p class="text-sm text-gray-500">Manage all user accounts by role.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="soh-btn-primary inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add User
            </a>
        </div>

        @if(session('status'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        {{-- Role Tabs --}}
        @php
            $tabs = [
                ['key' => 'all', 'label' => 'All Users', 'count' => $roleCounts['all'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                ['key' => 'student', 'label' => 'Students', 'count' => $roleCounts['students'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'],
                ['key' => 'teacher', 'label' => 'Teachers', 'count' => $roleCounts['teachers'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>'],
                ['key' => 'parent', 'label' => 'Parents', 'count' => $roleCounts['parents'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
            ];
        @endphp

        <div class="soh-card">
            <div class="border-b border-gray-100">
                <nav class="flex gap-1 px-1 pt-1" aria-label="Tabs">
                    @foreach($tabs as $tab)
                        <a href="{{ route('admin.users.index', ['role' => $tab['key']]) }}"
                           class="group relative flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition
                                  {{ $activeTab === $tab['key']
                                      ? 'bg-[#A6128D]/10 text-[#A6128D]'
                                      : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $tab['icon'] !!}</svg>
                            {{ $tab['label'] }}
                            <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold
                                         {{ $activeTab === $tab['key']
                                             ? 'bg-[#A6128D]/20 text-[#A6128D]'
                                             : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200' }}">
                                {{ $tab['count'] }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Search --}}
            <div class="border-b border-gray-100 px-6 py-3">
                <form method="GET" action="{{ route('admin.users.index') }}" class="max-w-sm">
                    @if($activeTab !== 'all')
                        <input type="hidden" name="role" value="{{ $activeTab }}">
                    @endif
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..."
                               class="w-full rounded-lg border border-gray-200 bg-gray-50/50 py-2 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 transition focus:border-[#A6128D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#A6128D]/20" />
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-gray-600">User</th>
                            <th class="px-6 py-3 font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-3 font-semibold text-gray-600">Joined</th>
                            <th class="px-6 py-3 font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            @php
                                $primaryRole = $user->roles->first()?->name ?? 'user';
                                $avatarColors = [
                                    'student' => 'bg-blue-100 text-blue-700',
                                    'teacher' => 'bg-[#A6128D]/10 text-[#A6128D]',
                                    'parent'  => 'bg-orange-100 text-orange-800',
                                ];
                                $roleBadges = [
                                    'student' => 'bg-blue-100 text-blue-700',
                                    'teacher' => 'bg-purple-100 text-purple-700',
                                    'parent'  => 'bg-orange-100 text-orange-800',
                                    'administrator' => 'bg-red-100 text-red-700',
                                    'super admin' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $avatarColors[$primaryRole] ?? 'bg-gray-100 text-gray-600' }} text-sm font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-gray-900 hover:text-[#A6128D] transition">{{ $user->name }}</a>
                                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $roleBadges[$role->name] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-700">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 11.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3A1 1 0 0011 10.586V7z" clip-rule="evenodd"/></svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-[#A6128D] hover:bg-[#A6128D]/5 transition" title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 transition" title="Delete">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <p class="mt-3 text-sm font-medium text-gray-500">No users found</p>
                                    <p class="mt-1 text-xs text-gray-400">Try a different search or add a new user.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $users->appends(['role' => $activeTab])->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
