<x-layouts.admin :title="'Bug Reports'">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Admin Dashboard', 'route' => route('admin.dashboard')],
            ['label' => 'Bug Reports', 'current' => true],
        ]" />

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bug Reports</h1>
                <p class="text-sm text-gray-500">Issues reported by users across the site.</p>
            </div>
        </div>

        @if(session('status'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        {{-- Status tabs --}}
        <div class="soh-card overflow-hidden">
            @php
                $statusTabs = [
                    ['key' => '', 'label' => 'All', 'count' => $counts['all'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>'],
                    ['key' => 'reported', 'label' => 'Reported', 'count' => $counts['reported'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
                    ['key' => 'in_progress', 'label' => 'In Progress', 'count' => $counts['in_progress'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['key' => 'resolved', 'label' => 'Resolved', 'count' => $counts['resolved'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['key' => 'wont_fix', 'label' => "Won't Fix", 'count' => $counts['wont_fix'], 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ];
            @endphp

            <div class="border-b border-gray-100">
                <nav class="flex gap-1 overflow-x-auto px-1 pt-1" aria-label="Tabs">
                    @foreach($statusTabs as $tab)
                        <a href="{{ route('admin.bug-reports.index', $tab['key'] ? ['status' => $tab['key']] : []) }}"
                           class="group relative flex items-center gap-2 whitespace-nowrap rounded-lg px-4 py-2.5 text-sm font-medium transition
                                  {{ request('status') === $tab['key']
                                      ? 'bg-[#A6128D]/10 text-[#A6128D]'
                                      : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $tab['icon'] !!}</svg>
                            {{ $tab['label'] }}
                            <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold
                                         {{ request('status') === $tab['key']
                                             ? 'bg-[#A6128D]/20 text-[#A6128D]'
                                             : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200' }}">
                                {{ $tab['count'] }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-gray-600">Reported By</th>
                            <th class="px-6 py-3 font-semibold text-gray-600">Title</th>
                            <th class="px-6 py-3 font-semibold text-gray-600">Page</th>
                            <th class="px-6 py-3 font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 font-semibold text-gray-600">Date</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reports as $report)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#A6128D]/10 text-sm font-bold text-[#A6128D]">
                                            {{ strtoupper(substr($report->user?->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $report->user?->name ?? 'Deleted User' }}</span>
                                    </div>
                                </td>
                                <td class="max-w-[280px] px-6 py-4">
                                    <p class="truncate font-medium text-gray-900">{{ $report->title }}</p>
                                    <p class="truncate text-xs text-gray-400">{{ Str::limit($report->description, 60) }}</p>
                                </td>
                                <td class="max-w-[160px] px-6 py-4">
                                    <p class="truncate text-xs text-gray-500">{{ $report->page ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $report->statusColor() }}">
                                        {{ $report->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $report->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.bug-reports.show', $report) }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-[#A6128D] hover:bg-[#A6128D]/5 transition">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <p class="mt-3 text-sm font-medium text-gray-500">No bug reports here</p>
                                    <p class="mt-1 text-xs text-gray-400">Reports submitted by users will show up in this list.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>