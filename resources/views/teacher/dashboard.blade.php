<div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <div>
        <h1 class="soh-page-title">Teacher Dashboard</h1>
        <p class="soh-page-subtitle">Manage lessons, assignments, and student progress efficiently.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="soh-stat-card">
            <p class="soh-kpi-label">My Lessons</p>
            <p class="soh-kpi-value mt-2">{{ $stats['lessons_total'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Published Lessons</p>
            <p class="soh-kpi-value mt-2">{{ $stats['lessons_published'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Total Assignments</p>
            <p class="soh-kpi-value mt-2">{{ $stats['assignments_total'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Due in 7 Days</p>
            <p class="soh-kpi-value mt-2">{{ $stats['assignments_due_soon'] }}</p>
        </div>
    </div>

    <div class="soh-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-black">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('teacher.lessons.index') }}" class="soh-btn-primary">
                Manage Lessons
            </a>
            <a href="{{ route('teacher.assignments.index') }}" class="soh-btn-outline">
                Manage Assignments
            </a>
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
            <p class="text-gray-500">No new notifications.</p>
        @endforelse
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="soh-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-black">Progress Summary</h2>
            <div class="space-y-3">
                <div class="flex justify-between"><span class="text-gray-600">Assigned</span><span class="font-semibold text-black">{{ $progress['assigned'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Started</span><span class="font-semibold text-black">{{ $progress['started'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">In Progress</span><span class="font-semibold text-black">{{ $progress['in_progress'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Completed</span><span class="font-semibold text-black">{{ $progress['completed'] }}</span></div>
            </div>
        </div>

        <div class="soh-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-black">Upcoming Due Dates</h2>
            @forelse($upcomingAssignments as $assignment)
                <div class="border-b border-gray-200 py-2 last:border-b-0">
                    <div class="font-semibold text-black">{{ $assignment->lesson->title ?? 'Lesson' }}</div>
                    <div class="text-sm text-gray-600">Student: {{ $assignment->student->name ?? 'N/A' }}</div>
                    <div class="mt-1 text-xs font-medium" style="color:#A6128D;">Due: {{ optional($assignment->due_date)->format('M d, Y') }}</div>
                </div>
            @empty
                <p class="text-gray-500">No upcoming assignment due dates.</p>
            @endforelse
        </div>
    </div>
</div>
