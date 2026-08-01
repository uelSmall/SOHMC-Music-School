<div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <x-frontend.breadcrumbs :items="[
        ['label' => 'Teacher Dashboard', 'current' => true],
    ]" />

    <div>
        <h1 class="soh-page-title">Teacher Dashboard</h1>
        <p class="soh-page-subtitle">Manage lessons, assignments, and student progress efficiently.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
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
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Pending Lesson Requests</p>
            <p class="soh-kpi-value mt-2">{{ $stats['lesson_requests_pending'] }}</p>
        </div>
    </div>

    <div class="soh-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-black">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('teacher.lessons.create') }}" class="soh-btn-primary">
                Create Lesson
            </a>
            <a href="{{ route('teacher.lessons.index') }}" class="soh-btn-outline">
                Manage Lessons
            </a>
            <a href="{{ route('teacher.lesson-requests.index') }}" class="soh-btn-outline">
                Lesson Requests
            </a>
            <a href="{{ route('teacher.assignments.index') }}" class="soh-btn-outline">
                Manage Assignments
            </a>
            <a href="{{ route('teacher.booking-management.index') }}" class="soh-btn-outline">
                Booking Management
            </a>
        </div>
    </div>

    <div class="soh-card p-6">
        <div class="mb-4 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-black">Booking Management</h2>
                <p class="text-sm text-gray-600">Track scheduled lessons through completion, cancellation, and rescheduling.</p>
            </div>
            <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-sm font-medium">View all bookings</a>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Today</p>
                <p class="soh-kpi-value mt-2">{{ $lessonManagementStats['today'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Upcoming</p>
                <p class="soh-kpi-value mt-2">{{ $lessonManagementStats['upcoming'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Completed</p>
                <p class="soh-kpi-value mt-2">{{ $lessonManagementStats['completed'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Cancelled</p>
                <p class="soh-kpi-value mt-2">{{ $lessonManagementStats['cancelled'] }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Today&apos;s Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>

                <div class="space-y-3">
                    @forelse($todaysLessons as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>Date: {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>Time: {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                <div>Duration: {{ $lesson->lesson_duration }} minutes</div>
                                <div><a href="{{ route('teacher.booking-management.show', $lesson) }}" class="soh-link text-sm font-medium">View details</a></div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No lessons scheduled for today.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Upcoming Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingLessons->take(5) as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>Date: {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>Time: {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                <div>Duration: {{ $lesson->lesson_duration }} minutes</div>
                                <div><a href="{{ route('teacher.booking-management.show', $lesson) }}" class="soh-link text-sm font-medium">View details</a></div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No upcoming lessons scheduled.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Completed Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>

                <div class="space-y-3">
                    @forelse($completedLessons as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>Date: {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>Time: {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                <div>Duration: {{ $lesson->lesson_duration }} minutes</div>
                                <div><a href="{{ route('teacher.booking-management.show', $lesson) }}" class="soh-link text-sm font-medium">View details</a></div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No completed lessons yet.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Cancelled Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>

                <div class="space-y-3">
                    @forelse($cancelledLessons as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>Date: {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>Time: {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                <div>Duration: {{ $lesson->lesson_duration }} minutes</div>
                                <div><a href="{{ route('teacher.booking-management.show', $lesson) }}" class="soh-link text-sm font-medium">View details</a></div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No cancelled lessons yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="soh-card p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-black">Notifications</h2>
            @if($notifications->isNotEmpty())
                <button wire:click="markAllNotificationsAsRead" class="soh-link text-sm font-medium">Mark all read</button>
            @endif
        </div>
        @forelse($notifications as $notification)
            <div class="border-b border-gray-200 py-3 last:border-b-0">
                <p class="font-semibold text-black">{{ $notification->data['title'] ?? 'Update' }}</p>
                <p class="text-sm text-gray-600">{{ $notification->data['message'] ?? '' }}</p>
                <div class="mt-2 flex items-center gap-3">
                    @if(! empty($notification->data['url']))
                        <a href="{{ $notification->data['url'] }}" class="soh-link text-sm font-medium">View</a>
                    @endif
                    <button wire:click="markNotificationAsRead('{{ $notification->id }}')" class="text-sm text-gray-500">Dismiss</button>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No new notifications.</p>
        @endforelse
    </div>

    <div class="soh-card p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-black">Lesson Requests</h2>
            <a href="{{ route('teacher.lesson-requests.index') }}" class="soh-link text-sm font-medium">View all</a>
        </div>

        @forelse($pendingLessonRequests as $request)
            <div class="border-b border-gray-200 py-3 last:border-b-0">
                <div class="font-semibold text-black">{{ $request->student?->name ?? 'Student' }} <span class="font-normal text-gray-500">({{ $request->instrument?->name ?? 'Instrument' }})</span></div>
                <div class="text-sm text-gray-600">
                    {{ $request->requested_date?->format('M d, Y') }}
                    {{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}
                </div>
                <div class="mt-2">
                    <a href="{{ route('teacher.lesson-requests.show', $request) }}" class="soh-link text-sm font-medium">Review request</a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No pending lesson requests.</p>
        @endforelse
    </div>

    @include('partials.lesson-calendar', [
        'title' => 'Teaching Calendar',
        'description' => 'Confirmed lessons assigned to you. Click an event to view details.',
        'eventsUrl' => route('teacher.calendar.events'),
        'viewerRole' => 'teacher',
    ])

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
                    <div class="soh-link mt-1 text-xs font-medium">Due: {{ optional($assignment->due_date)->format('M d, Y') }}</div>
                </div>
            @empty
                <p class="text-gray-500">No upcoming assignment due dates.</p>
            @endforelse
        </div>
    </div>
</div>
