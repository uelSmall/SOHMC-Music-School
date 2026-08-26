<div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:space-y-8 sm:px-6 sm:py-8 lg:px-8">
    <x-frontend.breadcrumbs :items="[
        ['label' => 'Dashboard', 'current' => true],
    ]" />

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#A6128D] via-[#8C0375] to-[#6B025E] p-6 text-white shadow-xl sm:rounded-3xl sm:p-8 lg:p-10">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 h-48 w-48 rounded-full bg-[#D991CD]/20 blur-2xl"></div>
        <div class="relative">
            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl lg:text-4xl">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="mt-2 max-w-xl text-sm text-white/80 sm:text-lg">Here&apos;s what&apos;s happening across your entire school today.</p>
            <div class="mt-4 flex flex-wrap gap-2 sm:mt-6 sm:gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-xs font-semibold text-white backdrop-blur-sm transition hover:bg-white/25 sm:px-5 sm:py-2.5 sm:text-sm">
                    <x-heroicon-o-globe-alt class="h-4 w-4" />
                    View Site
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-xs font-semibold text-white backdrop-blur-sm transition hover:bg-white/25 sm:px-5 sm:py-2.5 sm:text-sm">
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
        <h2 class="mb-3 text-base font-bold text-gray-900 sm:mb-4 sm:text-lg">School Overview</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Students</p>
                <p class="soh-kpi-value mt-1 sm:mt-2">{{ $schoolStats['total_students'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Teachers</p>
                <p class="soh-kpi-value mt-1 sm:mt-2">{{ $schoolStats['total_teachers'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Total Lessons</p>
                <p class="soh-kpi-value mt-1 sm:mt-2">{{ $schoolStats['total_lessons'] }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">This Week</p>
                <p class="soh-kpi-value mt-1 sm:mt-2">{{ $schoolStats['lessons_this_week'] }}</p>
            </div>
            <div class="soh-stat-card col-span-2 sm:col-span-1">
                <p class="soh-kpi-label">Pending Requests</p>
                <p class="soh-kpi-value mt-1 sm:mt-2">{{ $schoolStats['pending_requests_all'] }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="soh-card p-4 sm:p-6">
        <h2 class="mb-3 text-base font-semibold text-black sm:mb-4 sm:text-xl">Quick Actions</h2>
        <div class="flex flex-wrap gap-2 sm:gap-3">
            <a href="{{ route('admin.teacher-dashboard') }}" class="soh-btn-primary text-xs sm:text-sm">Teacher Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="soh-btn-outline text-xs sm:text-sm">Manage Users</a>
            <a href="{{ route('admin.gallery.index') }}" class="soh-btn-outline text-xs sm:text-sm">Gallery</a>
            <a href="{{ route('admin.settings.index') }}" class="soh-btn-outline text-xs sm:text-sm">Settings</a>
            <a href="{{ route('home') }}" class="soh-btn-outline text-xs sm:text-sm">View Site</a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- TEACHER WORKLOAD                               --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="soh-card p-4 sm:p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-black sm:text-xl">Teacher Workload</h2>
            <a href="{{ route('admin.users.index') }}" class="soh-link text-xs font-medium sm:text-sm">Manage teachers</a>
        </div>
        @if($teacherWorkload->isEmpty())
            <p class="text-sm text-gray-500">No teachers registered yet.</p>
        @else
            {{-- Desktop table --}}
            <div class="hidden overflow-x-auto sm:block">
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
            {{-- Mobile cards --}}
            <div class="space-y-3 sm:hidden">
                @foreach($teacherWorkload as $teacher)
                    <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                        <div class="mb-3">
                            <div class="font-semibold text-black">{{ $teacher['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $teacher['email'] }}</div>
                        </div>
                        <div class="grid grid-cols-4 gap-2 text-center">
                            <div>
                                <p class="text-lg font-bold text-[#A6128D]">{{ $teacher['content_count'] }}</p>
                                <p class="text-[10px] font-medium uppercase text-gray-500">Content</p>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-black">{{ $teacher['booked_total'] }}</p>
                                <p class="text-[10px] font-medium uppercase text-gray-500">Booked</p>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-green-600">{{ $teacher['booked_completed'] }}</p>
                                <p class="text-[10px] font-medium uppercase text-gray-500">Done</p>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-blue-600">{{ $teacher['booked_upcoming'] }}</p>
                                <p class="text-[10px] font-medium uppercase text-gray-500">Upcoming</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- STUDENT ENROLLMENT TRENDS                      --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="soh-card p-4 sm:p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-black sm:text-xl">Student Enrollment Trends</h2>
            <span class="text-xs text-gray-500">Last 6 months</span>
        </div>
        @php
            $maxEnrollment = $enrollmentTrends->max('count') ?: 1;
        @endphp
        <div class="flex items-end gap-2 sm:gap-3" style="height: 120px;">
            @foreach($enrollmentTrends as $trend)
                @php
                    $barHeight = ($trend['count'] / $maxEnrollment) * 100;
                @endphp
                <div class="flex flex-1 flex-col items-center gap-1">
                    <span class="text-[10px] font-semibold text-[#A6128D] sm:text-xs">{{ $trend['count'] }}</span>
                    <div class="w-full rounded-t-md sm:rounded-t-lg transition-all duration-300" style="height: {{ max($barHeight, 4) }}%; background: linear-gradient(180deg, #A6128D 0%, #D991CD 100%); min-height: 4px;"></div>
                    <span class="text-[10px] text-gray-500 sm:text-xs">{{ $trend['month'] }}</span>
                </div>
            @endforeach
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
    {{-- RECENT STUDENTS & ACTIVITY                     --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
        <div class="soh-card p-4 sm:p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-black sm:text-xl">Recent Students</h2>
                <a href="{{ route('admin.users.index') }}" class="soh-link text-xs font-medium sm:text-sm">View all</a>
            </div>
            @forelse($recentStudents as $student)
                <div class="flex items-center gap-3 border-b border-gray-100 py-3 last:border-b-0 sm:gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#A6128D]/10 text-sm font-bold text-[#A6128D] sm:h-10 sm:w-10">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-sm font-semibold text-black">{{ $student->name }}</div>
                        <div class="truncate text-xs text-gray-500">{{ $student->email }}</div>
                    </div>
                    <div class="shrink-0 text-[10px] text-gray-400 sm:text-xs">{{ $student->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No students registered yet.</p>
            @endforelse
        </div>

        <div class="soh-card p-4 sm:p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-black sm:text-xl">Recent Activity</h2>
            </div>
            @forelse($recentActivity as $activity)
                <div class="border-b border-gray-100 py-3 last:border-b-0">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-[#A6128D]"></div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-700 sm:text-sm">
                                <span class="font-semibold text-black">{{ $activity->causer?->name ?? 'System' }}</span>
                                {{ $activity->description }}
                                @if($activity->subject)
                                    <span class="font-medium text-black">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                                @endif
                            </p>
                            <p class="mt-0.5 text-[10px] text-gray-400 sm:text-xs">{{ $activity->created_at->diffForHumans() }}</p>
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
        <div class="soh-card p-4 sm:p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-black sm:text-xl">Notifications</h2>
                <button wire:click="markAllNotificationsAsRead" class="soh-link text-xs font-medium sm:text-sm">Mark all read</button>
            </div>
            @foreach($notifications as $notification)
                <div class="border-b border-gray-100 py-3 last:border-b-0">
                    <p class="text-sm font-semibold text-black">{{ $notification->data['title'] ?? 'Update' }}</p>
                    <p class="text-xs text-gray-600 sm:text-sm">{{ $notification->data['message'] ?? '' }}</p>
                    <div class="mt-2 flex items-center gap-3">
                        @if(! empty($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="soh-link text-xs font-medium sm:text-sm">View</a>
                        @endif
                        <button wire:click="markNotificationAsRead('{{ $notification->id }}')" class="text-xs text-gray-500 hover:text-gray-700 sm:text-sm">Dismiss</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
