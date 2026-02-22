<div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <div>
        <h1 class="soh-page-title">Student Dashboard</h1>
        <p class="soh-page-subtitle">Track assignments, due dates, and progress in one place.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Assigned Total</p>
            <p class="soh-kpi-value mt-2">{{ $stats['assigned_total'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Assigned</p>
            <p class="soh-kpi-value mt-2">{{ $stats['assigned'] }}</p>
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
        <h2 class="mb-4 text-xl font-semibold text-black">Quick Actions</h2>
        <a href="{{ route('lessons.index') }}" class="soh-btn-primary">
            Go to My Lessons
        </a>
    </div>

    <div class="soh-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-black">Upcoming Due Dates</h2>
        @forelse($upcomingAssignments as $assignment)
            <div class="border-b border-gray-200 py-3 last:border-b-0">
                <div class="font-semibold text-black">{{ $assignment->lesson->title ?? 'Lesson' }}</div>
                <div class="text-sm text-gray-600">
                    Teacher: {{ $assignment->lesson->teacher->name ?? 'N/A' }}
                    @if(! empty($assignment->lesson->instrument))
                        · Instrument: {{ ucfirst($assignment->lesson->instrument) }}
                    @endif
                </div>
                <div class="mt-1 text-xs font-medium" style="color:#6A1B9A;">Due: {{ optional($assignment->due_date)->format('M d, Y') }}</div>
            </div>
        @empty
            <p class="text-gray-500">No upcoming assignment due dates.</p>
        @endforelse
    </div>
</div>
