<div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <x-frontend.breadcrumbs :items="[
        ['label' => 'Student Dashboard', 'current' => true],
    ]" />

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
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('lessons.index') }}" class="soh-btn-primary">
                Go to My Lessons
            </a>
            <a href="{{ route('student.booking-management.index') }}" class="soh-btn-outline">
                Booking Management
            </a>
            <a href="{{ route('student.lesson-requests.create') }}" class="soh-btn-outline">
                Book a Lesson
            </a>
            <a href="{{ route('student.lesson-requests.index') }}" class="soh-btn-outline">
                My Lesson Requests
            </a>
        </div>
    </div>

    @include('partials.lesson-calendar', [
        'title' => 'My Lesson Calendar',
        'description' => 'Confirmed lessons only. Click an event to view details.',
        'eventsUrl' => route('student.calendar.events'),
        'viewerRole' => 'student',
    ])

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="soh-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-black">My Next Lesson</h2>
            @if($nextLesson)
                <div class="space-y-2">
                    <div class="font-semibold text-black">{{ $nextLesson->lesson->title ?? 'Lesson' }}</div>
                    <div class="text-sm text-gray-600">
                        Teacher: {{ $nextLesson->lesson->teacher->name ?? 'N/A' }}
                        @if(! empty($nextLesson->lesson->instrument))
                            · Instrument: {{ ucfirst($nextLesson->lesson->instrument) }}
                        @endif
                    </div>
                    @if($nextLesson->due_date)
                        <div class="text-xs font-medium" style="color:#A6128D;">Due: {{ $nextLesson->due_date->format('M d, Y') }}</div>
                    @else
                        <div class="text-xs text-gray-500">No due date set</div>
                    @endif
                    <div class="pt-2">
                        <a href="{{ route('lessons.index') }}" class="soh-btn-outline">Open Lessons</a>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No pending lessons right now.</p>
            @endif
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
                <div class="font-semibold text-black">{{ $assignment->lesson->title ?? 'Lesson' }}</div>
                <div class="text-sm text-gray-600">
                    Teacher: {{ $assignment->lesson->teacher->name ?? 'N/A' }}
                    @if(! empty($assignment->lesson->instrument))
                        · Instrument: {{ ucfirst($assignment->lesson->instrument) }}
                    @endif
                </div>
                <div class="mt-1 text-xs font-medium" style="color:#A6128D;">Due: {{ optional($assignment->due_date)->format('M d, Y') }}</div>
            </div>
        @empty
            <p class="text-gray-500">No upcoming assignment due dates.</p>
        @endforelse
    </div>
</div>
