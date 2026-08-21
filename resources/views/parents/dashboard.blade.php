<div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <x-frontend.breadcrumbs :items="[
        ['label' => 'Parent Dashboard', 'current' => true],
    ]" />

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#A6128D] via-[#8C0375] to-[#6B025E] p-8 text-white shadow-xl sm:p-10">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 h-48 w-48 rounded-full bg-[#D991CD]/20 blur-2xl"></div>
        <div class="relative">
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="mt-2 max-w-xl text-lg text-white/80">Monitor your children's lessons, progress, and teacher updates.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('lessons.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
                    View Children's Lessons
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Children</p>
            <p class="soh-kpi-value mt-2">{{ $stats['children_total'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Assignments</p>
            <p class="soh-kpi-value mt-2">{{ $stats['assignments_total'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">In Progress</p>
            <p class="soh-kpi-value mt-2">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Completed</p>
            <p class="soh-kpi-value mt-2">{{ $stats['completed'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Due in 7 Days</p>
            <p class="soh-kpi-value mt-2">{{ $stats['due_soon'] }}</p>
        </div>
    </div>

    <div class="soh-card p-6">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-black">Quick Actions</h2>
                <p class="text-sm text-gray-600">Open your children’s lessons and check current progress.</p>
            </div>
            <a href="{{ route('lessons.index') }}" class="soh-btn-primary">
                View Children’s Lessons
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="space-y-4">
            <div class="soh-card p-6">
                <h2 class="mb-4 text-xl font-semibold text-black">My Children</h2>
                @forelse($childrenSummaries as $summary)
                    <div class="mb-5 rounded-2xl border border-[color:var(--soh-gray)]/45 bg-[linear-gradient(180deg,#FFFFFF_0%,#FAF7FB_100%)] p-5 shadow-sm last:mb-0">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="text-lg font-semibold text-black">{{ $summary['child']->name }}</div>
                                <div class="text-sm text-gray-600">{{ $summary['assignments_total'] }} assignments • {{ $summary['completed'] }} completed</div>
                            </div>
                            <div class="rounded-full px-3 py-1 text-xs font-semibold" style="background:rgba(166,18,141,0.12); color:#A6128D;">{{ $summary['progress'] }}% complete</div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-xl bg-[color:var(--soh-surface)] p-3">
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">In Progress</div>
                                <div class="mt-1 text-lg font-bold text-black">{{ $summary['in_progress'] }}</div>
                            </div>
                            <div class="rounded-xl bg-[color:var(--soh-surface)] p-3">
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Due Soon</div>
                                <div class="mt-1 text-lg font-bold text-black">{{ $summary['due_soon'] }}</div>
                            </div>
                            <div class="rounded-xl bg-[color:var(--soh-surface)] p-3">
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Next Lesson</div>
                                <div class="mt-1 text-sm font-semibold text-black">{{ $summary['nextAssignment']->lesson->title ?? 'None yet' }}</div>
                            </div>
                        </div>

                        @if($summary['nextAssignment'])
                            <div class="mt-4 text-sm text-gray-600">
                                Teacher: {{ $summary['nextAssignment']->lesson->teacher->name ?? 'N/A' }}
                                @if (! empty($summary['nextAssignment']->lesson->instrument))
                                    · Instrument: {{ ucfirst($summary['nextAssignment']->lesson->instrument) }}
                                @endif
                                @if($summary['nextAssignment']->due_date)
                                    · Due: {{ $summary['nextAssignment']->due_date->format('M d, Y') }}
                                @endif
                            </div>
                            @if($summary['nextAssignment']->latestComment)
                                <div class="mt-3 rounded-xl border border-[color:var(--soh-gray)]/45 bg-white p-4 text-sm text-gray-700">
                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Private Teacher Note</div>
                                    <p class="mt-2">{{ $summary['nextAssignment']->latestComment->body }}</p>
                                    <p class="mt-2 text-xs text-gray-500">
                                        {{ $summary['nextAssignment']->latestComment->teacher->name ?? 'Teacher' }} · {{ $summary['nextAssignment']->latestComment->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            @elseif(! empty($summary['nextAssignment']->lesson->global_note))
                                <div class="mt-3 rounded-xl border border-[color:var(--soh-gray)]/45 bg-white p-4 text-sm text-gray-700">
                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Global Lesson Note</div>
                                    <p class="mt-2">{{ $summary['nextAssignment']->lesson->global_note }}</p>
                                </div>
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('lessons.show', $summary['nextAssignment']->lesson) }}" class="soh-btn-outline inline-flex">Open Lesson</a>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No children linked yet.</p>
                @endforelse
            </div>
        </div>

        <div class="soh-card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-black">Notifications</h2>
                @if($notifications->isNotEmpty())
                    <button wire:click="markAllNotificationsAsRead" class="text-sm font-medium" style="color:#A6128D;">Mark all read</button>
                @endif
            </div>
            @forelse($notifications as $notification)
                <div class="border-b border-gray-200 py-3 last:border-b-0">
                    <p class="font-semibold text-black">{{ $notification->data['title'] ?? 'Update' }}</p>
                    <p class="text-sm text-gray-600">{{ $notification->data['message'] ?? '' }}</p>
                    <div class="mt-2 flex items-center gap-3">
                        @if(! empty($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="text-sm font-medium" style="color:#A6128D;">View</a>
                        @endif
                        <button wire:click="markNotificationAsRead('{{ $notification->id }}')" class="text-sm text-gray-500">Dismiss</button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">You’re all caught up.</p>
            @endforelse
        </div>
    </div>

    <div class="soh-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-black">Upcoming Due Dates</h2>
        @forelse($upcomingAssignments as $assignment)
            <div class="border-b border-gray-200 py-3 last:border-b-0">
                <div class="font-semibold text-black">{{ $assignment->student->name ?? 'Child' }} • {{ $assignment->lesson->title ?? 'Lesson' }}</div>
                <div class="text-sm text-gray-600">
                    Teacher: {{ $assignment->lesson->teacher->name ?? 'N/A' }}
                    @if(! empty($assignment->lesson->instrument))
                        · Instrument: {{ ucfirst($assignment->lesson->instrument) }}
                    @endif
                </div>
                <div class="mt-1 text-xs font-medium" style="color:#A6128D;">Due: {{ optional($assignment->due_date)->format('M d, Y') }}</div>
                @if($assignment->latestComment)
                    <div class="mt-3 rounded-xl border border-[color:var(--soh-gray)]/45 bg-[linear-gradient(180deg,#FFFFFF_0%,#FAF7FB_100%)] p-4 text-sm text-gray-700">
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Private Teacher Note</div>
                        <p class="mt-2">{{ $assignment->latestComment->body }}</p>
                    </div>
                @elseif(! empty($assignment->lesson->global_note))
                    <div class="mt-3 rounded-xl border border-[color:var(--soh-gray)]/45 bg-[linear-gradient(180deg,#FFFFFF_0%,#FAF7FB_100%)] p-4 text-sm text-gray-700">
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Global Lesson Note</div>
                        <p class="mt-2">{{ $assignment->lesson->global_note }}</p>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">No upcoming assignment due dates.</p>
        @endforelse
    </div>
</div>
