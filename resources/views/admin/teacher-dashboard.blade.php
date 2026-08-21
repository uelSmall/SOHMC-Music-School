<div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <x-frontend.breadcrumbs :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Teacher Dashboard', 'current' => true],
    ]" />

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#A6128D] via-[#8C0375] to-[#6B025E] p-8 text-white shadow-xl sm:p-10">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 h-48 w-48 rounded-full bg-[#D991CD]/20 blur-2xl"></div>
        <div class="relative">
            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Teacher Dashboard</h1>
            <p class="mt-2 max-w-xl text-lg text-white/80">Your lessons, assignments, and bookings at a glance.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('teacher.lessons.create') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
                    <x-heroicon-o-plus class="h-4 w-4" />
                    Create Lesson
                </a>
                <a href="{{ route('teacher.lesson-requests.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
                    <x-heroicon-o-inbox class="h-4 w-4" />
                    Lesson Requests
                </a>
            </div>
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
