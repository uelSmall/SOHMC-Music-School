<div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <x-frontend.breadcrumbs :items="[
        ['label' => 'Dashboard', 'current' => true],
    ]" />

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#A6128D] via-[#8C0375] to-[#6B025E] p-8 text-white shadow-xl sm:p-10">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 h-48 w-48 rounded-full bg-[#D991CD]/20 blur-2xl"></div>
        <div class="relative">
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="mt-2 max-w-xl text-lg text-white/80">Here&apos;s what&apos;s happening across your entire school today.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
                    <x-heroicon-o-globe-alt class="h-4 w-4" />
                    View Site
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
                    <x-heroicon-o-users class="h-4 w-4" />
                    Manage Users
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- SCHOOL OVERVIEW                                --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div>
        <h2 class="mb-4 text-lg font-bold text-gray-900">School Overview</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Students</p>
                <p class="soh-kpi-value mt-2">{{ $schoolStats['total_students'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Teachers</p>
                <p class="soh-kpi-value mt-2">{{ $schoolStats['total_teachers'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Total Lessons</p>
                <p class="soh-kpi-value mt-2">{{ $schoolStats['total_lessons'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">This Week</p>
                <p class="soh-kpi-value mt-2">{{ $schoolStats['lessons_this_week'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Pending Requests</p>
                <p class="soh-kpi-value mt-2">{{ $schoolStats['pending_requests_all'] }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="soh-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-black">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.index') }}" class="soh-btn-primary">Manage Users</a>
            <a href="{{ route('admin.gallery.index') }}" class="soh-btn-outline">Gallery</a>
            <a href="{{ route('admin.settings.index') }}" class="soh-btn-outline">Settings</a>
            <a href="{{ route('teacher.lessons.create') }}" class="soh-btn-outline">Create Lesson</a>
            <a href="{{ route('teacher.lessons.index') }}" class="soh-btn-outline">My Lessons</a>
            <a href="{{ route('teacher.booking-management.index') }}" class="soh-btn-outline">Bookings</a>
            <a href="{{ route('teacher.lesson-requests.index') }}" class="soh-btn-outline">Lesson Requests</a>
            <a href="{{ route('teacher.assignments.index') }}" class="soh-btn-outline">Assignments</a>
            <a href="{{ route('home') }}" class="soh-btn-outline">View Site</a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- TEACHER WORKLOAD                               --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="soh-card p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-black">Teacher Workload</h2>
            <a href="{{ route('admin.users.index') }}" class="soh-link text-sm font-medium">Manage teachers</a>
        </div>
        @if($teacherWorkload->isEmpty())
            <p class="text-sm text-gray-500">No teachers registered yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="pb-3 pr-4">Teacher</th>
                            <th class="pb-3 px-4 text-center">Content</th>
                            <th class="pb-3 px-4 text-center">Booked</th>
                            <th class="pb-3 px-4 text-center">Completed</th>
                            <th class="pb-3 px-4 text-center">Upcoming</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teacherWorkload as $teacher)
                            <tr class="border-b border-gray-100 last:border-b-0">
                                <td class="py-3 pr-4">
                                    <div class="font-semibold text-black">{{ $teacher['name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $teacher['email'] }}</div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#A6128D]/10 text-xs font-bold text-[#A6128D]">{{ $teacher['content_count'] }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="font-semibold text-black">{{ $teacher['booked_total'] }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700">{{ $teacher['booked_completed'] }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">{{ $teacher['booked_upcoming'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- STUDENT ENROLLMENT TRENDS                      --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="soh-card p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-black">Student Enrollment Trends</h2>
            <span class="text-xs text-gray-500">Last 6 months</span>
        </div>
        @php
            $maxEnrollment = $enrollmentTrends->max('count') ?: 1;
        @endphp
        <div class="flex items-end gap-3" style="height: 140px;">
            @foreach($enrollmentTrends as $trend)
                @php
                    $barHeight = ($trend['count'] / $maxEnrollment) * 100;
                @endphp
                <div class="flex flex-1 flex-col items-center gap-1">
                    <span class="text-xs font-semibold text-[#A6128D]">{{ $trend['count'] }}</span>
                    <div class="w-full rounded-t-lg transition-all duration-300" style="height: {{ max($barHeight, 4) }}%; background: linear-gradient(180deg, #A6128D 0%, #D991CD 100%); min-height: 4px;"></div>
                    <span class="text-xs text-gray-500">{{ $trend['month'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- MY TEACHING OVERVIEW                           --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div>
        <h2 class="mb-4 text-lg font-bold text-gray-900">My Teaching Overview</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="soh-stat-card">
                <p class="soh-kpi-label">My Lessons</p>
                <p class="soh-kpi-value mt-2">{{ $myStats['lessons_total'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Published</p>
                <p class="soh-kpi-value mt-2">{{ $myStats['lessons_published'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Assignments</p>
                <p class="soh-kpi-value mt-2">{{ $myStats['assignments_total'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Due in 7 Days</p>
                <p class="soh-kpi-value mt-2">{{ $myStats['assignments_due_soon'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">My Pending Requests</p>
                <p class="soh-kpi-value mt-2">{{ $myStats['lesson_requests_pending'] }}</p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- MY BOOKING MANAGEMENT                          --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="soh-card p-6">
        <div class="mb-4 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-black">My Booking Management</h2>
                <p class="text-sm text-gray-600">Track your scheduled lessons through completion.</p>
            </div>
            <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-sm font-medium">View all bookings</a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Today</p>
                <p class="soh-kpi-value mt-2">{{ $myLessonStats['today'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Upcoming</p>
                <p class="soh-kpi-value mt-2">{{ $myLessonStats['upcoming'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Completed</p>
                <p class="soh-kpi-value mt-2">{{ $myLessonStats['completed'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Cancelled</p>
                <p class="soh-kpi-value mt-2">{{ $myLessonStats['cancelled'] }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            {{-- My Today's Lessons --}}
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Today&apos;s Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>
                <div class="space-y-3">
                    @forelse($myTodaysLessons as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="shrink-0 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>{{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>{{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">No lessons scheduled for today.</p>
                    @endforelse
                </div>
            </div>

            {{-- My Upcoming Lessons --}}
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Upcoming Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>
                <div class="space-y-3">
                    @forelse($myUpcomingLessons->take(5) as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="shrink-0 rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>{{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>{{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">No upcoming lessons.</p>
                    @endforelse
                </div>
            </div>

            {{-- My Completed Lessons --}}
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Completed Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>
                <div class="space-y-3">
                    @forelse($myCompletedLessons as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>{{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>{{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">No completed lessons yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- My Cancelled Lessons --}}
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black">Cancelled Lessons</h3>
                    <a href="{{ route('teacher.booking-management.index') }}" class="soh-link text-xs font-medium">Open schedule</a>
                </div>
                <div class="space-y-3">
                    @forelse($myCancelledLessons as $lesson)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                </div>
                                <span class="shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">{{ $lesson->status->label() }}</span>
                            </div>
                            <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-2">
                                <div>{{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                <div>{{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">No cancelled lessons yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- SCHOOL CALENDAR (all lessons)                  --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @include('partials.lesson-calendar', [
        'title' => 'School Calendar',
        'description' => 'All lessons across every teacher. Click an event to view details.',
        'eventsUrl' => route('admin.calendar.events'),
        'viewerRole' => 'admin',
    ])

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- MY LESSON REQUESTS                             --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="soh-card p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-black">My Lesson Requests</h2>
            <a href="{{ route('teacher.lesson-requests.index') }}" class="soh-link text-sm font-medium">View all</a>
        </div>
        @forelse($myPendingRequests as $request)
            <div class="border-b border-gray-200 py-3 last:border-b-0">
                <div class="font-semibold text-black">{{ $request->student?->name ?? 'Student' }} <span class="font-normal text-gray-500">({{ $request->instrument?->name ?? 'Instrument' }})</span></div>
                <div class="text-sm text-gray-600">
                    {{ $request->requested_date?->format('M d, Y') }}
                    {{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}
                </div>
                <div class="mt-2">
                    <a href="{{ route('teacher.lesson-requests.show', $request) }}" class="soh-link text-sm font-medium">Review request</a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No pending lesson requests.</p>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- MY PROGRESS & ASSIGNMENTS                      --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="soh-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-black">Progress Summary</h2>
            <div class="space-y-3">
                <div class="flex justify-between"><span class="text-gray-600">Assigned</span><span class="font-semibold text-black">{{ $myProgress['assigned'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Started</span><span class="font-semibold text-black">{{ $myProgress['started'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">In Progress</span><span class="font-semibold text-black">{{ $myProgress['in_progress'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Completed</span><span class="font-semibold text-black">{{ $myProgress['completed'] }}</span></div>
            </div>
        </div>

        <div class="soh-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-black">Upcoming Due Dates</h2>
            @forelse($myUpcomingAssignments as $assignment)
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

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- RECENT STUDENTS & ACTIVITY                     --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="soh-card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-black">Recent Students</h2>
                <a href="{{ route('admin.users.index') }}" class="soh-link text-sm font-medium">View all</a>
            </div>
            @forelse($recentStudents as $student)
                <div class="flex items-center gap-4 border-b border-gray-100 py-3 last:border-b-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#A6128D]/10 text-sm font-bold text-[#A6128D]">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="truncate font-semibold text-black">{{ $student->name }}</div>
                        <div class="truncate text-sm text-gray-500">{{ $student->email }}</div>
                    </div>
                    <div class="shrink-0 text-xs text-gray-400">{{ $student->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No students registered yet.</p>
            @endforelse
        </div>

        <div class="soh-card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-black">Recent Activity</h2>
            </div>
            @forelse($recentActivity as $activity)
                <div class="border-b border-gray-100 py-3 last:border-b-0">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-[#A6128D]"></div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold text-black">{{ $activity->causer?->name ?? 'System' }}</span>
                                {{ $activity->description }}
                                @if($activity->subject)
                                    <span class="font-medium text-black">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                                @endif
                            </p>
                            <p class="mt-0.5 text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No recent activity.</p>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- NOTIFICATIONS                                  --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if($notifications->isNotEmpty())
        <div class="soh-card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-black">Notifications</h2>
                <button wire:click="markAllNotificationsAsRead" class="soh-link text-sm font-medium">Mark all read</button>
            </div>
            @foreach($notifications as $notification)
                <div class="border-b border-gray-100 py-3 last:border-b-0">
                    <p class="font-semibold text-black">{{ $notification->data['title'] ?? 'Update' }}</p>
                    <p class="text-sm text-gray-600">{{ $notification->data['message'] ?? '' }}</p>
                    <div class="mt-2 flex items-center gap-3">
                        @if(! empty($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="soh-link text-sm font-medium">View</a>
                        @endif
                        <button wire:click="markNotificationAsRead('{{ $notification->id }}')" class="text-sm text-gray-500 hover:text-gray-700">Dismiss</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
