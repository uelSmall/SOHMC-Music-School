@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => $role === 'teacher' ? 'Teacher Dashboard' : ($role === 'admin' ? 'Admin Dashboard' : 'Student Dashboard'), 'route' => $role === 'teacher' ? route('teacher.dashboard') : ($role === 'admin' ? route('admin.dashboard') : route('student.dashboard'))],
            ['label' => 'My Bookings', 'current' => true],
        ]" />

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="soh-page-title">My Bookings</h1>
                <p class="soh-page-subtitle">
                    @if($role === 'teacher')
                        Manage your lesson schedule, requests, and confirmations.
                    @elseif($role === 'admin')
                        All bookings across the school.
                    @else
                        View your lesson requests and scheduled bookings.
                    @endif
                </p>
            </div>
            @if($role === 'student')
                <a href="{{ route('bookings.create') }}" class="soh-btn-primary">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Book a Lesson
                </a>
            @endif
        </div>

        {{-- Stats Grid --}}
        @if($role === 'teacher')
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Today</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['today'] }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Upcoming</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['upcoming'] }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Completed</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['completed'] }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Cancelled</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['cancelled'] }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Pending Requests</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['pending_requests'] }}</p>
                </div>
            </div>
        @elseif($role === 'admin')
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Today</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['today'] }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Upcoming</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['upcoming'] }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Completed</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['completed'] }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Cancelled</p>
                    <p class="soh-kpi-value mt-2">{{ $statistics['cancelled'] }}</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Pending Requests</p>
                    <p class="soh-kpi-value mt-2">{{ $pendingRequests->count() }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Active Lessons</p>
                    <p class="soh-kpi-value mt-2">{{ $activeLessons->count() }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Completed</p>
                    <p class="soh-kpi-value mt-2">{{ $completedLessons->count() }}</p>
                </div>
                <div class="soh-stat-card">
                    <p class="soh-kpi-label">Total Requests</p>
                    <p class="soh-kpi-value mt-2">{{ $lessonRequests->count() }}</p>
                </div>
            </div>
        @endif

        {{-- Tab Navigation --}}
        <div x-data="{ activeTab: '{{ $role === 'teacher' ? 'pending' : 'all' }}' }" class="space-y-6">
            <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-0">
                @if($role === 'student')
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">All</button>
                    <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">
                        Pending
                        @if($pendingRequests->isNotEmpty()) <span class="ml-1 inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-800">{{ $pendingRequests->count() }}</span> @endif
                    </button>
                    <button @click="activeTab = 'active'" :class="activeTab === 'active' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Active Lessons</button>
                    <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Completed</button>
                @elseif($role === 'teacher')
                    <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">
                        Pending Requests
                        @if($pendingRequests->isNotEmpty()) <span class="ml-1 inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-800">{{ $pendingRequests->count() }}</span> @endif
                    </button>
                    <button @click="activeTab = 'reschedule'" :class="activeTab === 'reschedule' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">
                        Reschedule Suggested
                        @if($rescheduleRequests->isNotEmpty()) <span class="ml-1 inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-semibold text-purple-800">{{ $rescheduleRequests->count() }}</span> @endif
                    </button>
                    <button @click="activeTab = 'scheduled'" :class="activeTab === 'scheduled' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Scheduled</button>
                    <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Completed</button>
                    <button @click="activeTab = 'cancelled'" :class="activeTab === 'cancelled' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Cancelled</button>
                @else
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">All</button>
                    <button @click="activeTab = 'scheduled'" :class="activeTab === 'scheduled' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Scheduled</button>
                    <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Completed</button>
                    <button @click="activeTab = 'cancelled'" :class="activeTab === 'cancelled' ? 'border-[color:var(--soh-purple)] text-[color:var(--soh-purple)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition">Cancelled</button>
                @endif
            </div>

            {{-- =================------------- --}}
            {{-- STUDENT TABS --}}
            {{-- =================------------- --}}
            @if($role === 'student')
                {{-- All Tab --}}
                <div x-show="activeTab === 'all'" x-transition>
                    <div class="soh-card p-6">
                        <h2 class="mb-4 text-xl font-semibold text-black">All Bookings</h2>
                        <div class="space-y-3">
                            @forelse($all as $item)
                                @if($item instanceof \Modules\Booking\Models\LessonRequest)
                                    @php $statusValue = $item->status->value; @endphp
                                    <article class="rounded-2xl border border-gray-200 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-800">Request</span>
                                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ match($statusValue) { 'pending' => 'bg-yellow-100 text-yellow-800', 'teacher_rescheduled' => 'bg-purple-100 text-purple-800', 'teacher_confirmed' => 'bg-blue-100 text-blue-800', 'student_accepted' => 'bg-green-100 text-green-800', 'student_declined', 'cancelled' => 'bg-gray-100 text-gray-800', default => 'bg-gray-100 text-gray-800' } }}">{{ $item->status->label() }}</span>
                                                </div>
                                                <div class="mt-1 font-semibold text-black">{{ $item->instrument?->name }} with {{ $item->teacher?->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $item->requested_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($item->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($item->requested_end_time)->format('g:i A') }}</div>
                                            </div>
                                            <a href="{{ route('bookings.show', $item) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                        </div>
                                    </article>
                                @else
                                    @php $statusValue = $item->status->value; @endphp
                                    <article class="rounded-2xl border border-gray-200 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">Lesson</span>
                                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ match($statusValue) { 'scheduled' => 'bg-yellow-100 text-yellow-800', 'completed' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-gray-100 text-gray-800', default => 'bg-gray-100 text-gray-800' } }}">{{ $item->status->label() }}</span>
                                                </div>
                                                <div class="mt-1 font-semibold text-black">{{ $item->instrument?->name }} with {{ $item->teacher?->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $item->lesson_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($item->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($item->lesson_end_time)->format('g:i A') }}</div>
                                            </div>
                                            <a href="{{ route('bookings.show', $item) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                        </div>
                                    </article>
                                @endif
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No bookings yet. Start by requesting a lesson!</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Pending Tab --}}
                <div x-show="activeTab === 'pending'" x-transition>
                    <div class="soh-card p-6">
                        <h2 class="mb-4 text-xl font-semibold text-black">Pending Requests</h2>
                        <div class="space-y-3">
                            @forelse($pendingRequests as $request)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                @if($request->status === \Modules\Booking\Enums\LessonRequestStatus::TeacherRescheduled)
                                                    <span class="rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800">Reschedule Suggested</span>
                                                @else
                                                    <span class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-800">Pending</span>
                                                @endif
                                            </div>
                                            <div class="mt-1 font-semibold text-black">{{ $request->instrument?->name }} with {{ $request->teacher?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $request->requested_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $request) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No pending requests.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Active Tab --}}
                <div x-show="activeTab === 'active'" x-transition>
                    <div class="soh-card p-6">
                        <h2 class="mb-4 text-xl font-semibold text-black">Active Lessons</h2>
                        <div class="space-y-3">
                            @forelse($activeLessons as $lesson)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <span class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-800">Scheduled</span>
                                            <div class="mt-1 font-semibold text-black">{{ $lesson->instrument?->name }} with {{ $lesson->teacher?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $lesson->lesson_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No active lessons.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Completed Tab --}}
                <div x-show="activeTab === 'completed'" x-transition>
                    <div class="soh-card p-6">
                        <h2 class="mb-4 text-xl font-semibold text-black">Completed Lessons</h2>
                        <div class="space-y-3">
                            @forelse($completedLessons as $lesson)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Completed</span>
                                            <div class="mt-1 font-semibold text-black">{{ $lesson->instrument?->name }} with {{ $lesson->teacher?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $lesson->lesson_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No completed lessons yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================------------- --}}
            {{-- TEACHER TABS --}}
            {{-- =================------------- --}}
            @if($role === 'teacher')
                {{-- Pending Requests Tab --}}
                <div x-show="activeTab === 'pending'" x-transition>
                    <div class="soh-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-black">Pending Requests</h2>
                            <span class="text-sm text-gray-500">{{ $pendingRequests->count() }} total</span>
                        </div>

                        <div class="space-y-3">
                            @forelse($pendingRequests as $request)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-black">{{ $request->student?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $request->instrument?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $request->requested_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}</div>
                                            @if($request->student_note)
                                                <div class="mt-1 text-xs text-gray-500" title="{{ $request->student_note }}">Note: {{ Str::limit($request->student_note, 60) }}</div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('bookings.confirm', $request) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-md bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Confirm</button>
                                            </form>
                                            <a href="{{ route('bookings.show', $request) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Reschedule</a>
                                            <form method="POST" action="{{ route('bookings.reject', $request) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No pending requests.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Reschedule Tab --}}
                <div x-show="activeTab === 'reschedule'" x-transition>
                    <div class="soh-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-black">Reschedule Suggested</h2>
                            <span class="text-sm text-gray-500">{{ $rescheduleRequests->count() }} total</span>
                        </div>

                        <div class="space-y-3">
                            @forelse($rescheduleRequests as $request)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-black">{{ $request->student?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $request->instrument?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $request->requested_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $request) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No reschedule suggestions pending.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Scheduled Tab --}}
                <div x-show="activeTab === 'scheduled'" x-transition>
                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="soh-card p-6">
                            <h3 class="mb-3 text-lg font-semibold text-black">Today&apos;s Lessons</h3>
                            <div class="space-y-3">
                                @forelse($todaysLessons as $lesson)
                                    <article class="rounded-2xl border border-gray-200 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                                <div class="text-sm text-gray-600">{{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                            </div>
                                            <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                        </div>
                                    </article>
                                @empty
                                    <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No lessons today.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="soh-card p-6">
                            <h3 class="mb-3 text-lg font-semibold text-black">Upcoming Lessons</h3>
                            <div class="space-y-3">
                                @forelse($upcomingLessons as $lesson)
                                    <article class="rounded-2xl border border-gray-200 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $lesson->lesson_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                            </div>
                                            <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                        </div>
                                    </article>
                                @empty
                                    <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No upcoming lessons.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Completed Tab --}}
                <div x-show="activeTab === 'completed'" x-transition>
                    <div class="soh-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-black">Completed Lessons</h2>
                            <span class="text-sm text-gray-500">{{ $completedLessons->count() }} total</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($completedLessons as $lesson)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }} · {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No completed lessons.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Cancelled Tab --}}
                <div x-show="activeTab === 'cancelled'" x-transition>
                    <div class="soh-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-black">Cancelled Lessons</h2>
                            <span class="text-sm text-gray-500">{{ $cancelledLessons->count() }} total</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($cancelledLessons as $lesson)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-black">{{ $lesson->student?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }} · {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No cancelled lessons.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================------------- --}}
            {{-- ADMIN TABS --}}
            {{-- =================------------- --}}
            @if($role === 'admin')
                {{-- All Tab --}}
                <div x-show="activeTab === 'all'" x-transition>
                    <div class="soh-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-black">All Bookings</h2>
                            <span class="text-sm text-gray-500">{{ $lessons->count() }} total</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        <th class="px-3 py-3">Student</th>
                                        <th class="px-3 py-3">Teacher</th>
                                        <th class="px-3 py-3">Instrument</th>
                                        <th class="px-3 py-3">Date</th>
                                        <th class="px-3 py-3">Time</th>
                                        <th class="px-3 py-3">Status</th>
                                        <th class="px-3 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                                    @forelse($lessons as $lesson)
                                        <tr>
                                            <td class="px-3 py-4 font-medium text-black">{{ $lesson->student?->name }}</td>
                                            <td class="px-3 py-4">{{ $lesson->teacher?->name }}</td>
                                            <td class="px-3 py-4">{{ $lesson->instrument?->name }}</td>
                                            <td class="px-3 py-4">{{ $lesson->lesson_date?->format('M d, Y') }}</td>
                                            <td class="px-3 py-4">{{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</td>
                                            <td class="px-3 py-4">
                                                @php
                                                    $sv = $lesson->status->value;
                                                    $sc = match($sv) { 'scheduled' => 'bg-yellow-100 text-yellow-800', 'completed' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-gray-100 text-gray-800', default => 'bg-gray-100 text-gray-800' };
                                                @endphp
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $sc }}">{{ $lesson->status->label() }}</span>
                                            </td>
                                            <td class="px-3 py-4"><a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium">View</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="px-3 py-8 text-center text-gray-500">No bookings found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Scheduled Tab --}}
                <div x-show="activeTab === 'scheduled'" x-transition>
                    <div class="soh-card p-6">
                        <h2 class="mb-4 text-xl font-semibold text-black">Scheduled Lessons</h2>
                        <div class="space-y-3">
                            @foreach(['Today' => $todaysLessons, 'Upcoming' => $upcomingLessons] as $label => $items)
                                @if($items->isNotEmpty())
                                    <h3 class="text-sm font-semibold text-gray-500 uppercase">{{ $label }}</h3>
                                    @foreach($items as $lesson)
                                        <article class="rounded-2xl border border-gray-200 p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <div class="font-semibold text-black">{{ $lesson->student?->name }} &rarr; {{ $lesson->teacher?->name }}</div>
                                                    <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }} · {{ $lesson->lesson_date?->format('M d, Y') }} · {{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</div>
                                                </div>
                                                <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                            </div>
                                        </article>
                                    @endforeach
                                @endif
                            @endforeach
                            @if($todaysLessons->isEmpty() && $upcomingLessons->isEmpty())
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No scheduled lessons.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Completed Tab --}}
                <div x-show="activeTab === 'completed'" x-transition>
                    <div class="soh-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-black">Completed Lessons</h2>
                            <span class="text-sm text-gray-500">{{ $completedLessons->count() }} total</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($completedLessons as $lesson)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-black">{{ $lesson->student?->name }} &rarr; {{ $lesson->teacher?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }} · {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No completed lessons.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Cancelled Tab --}}
                <div x-show="activeTab === 'cancelled'" x-transition>
                    <div class="soh-card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-black">Cancelled Lessons</h2>
                            <span class="text-sm text-gray-500">{{ $cancelledLessons->count() }} total</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($cancelledLessons as $lesson)
                                <article class="rounded-2xl border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-black">{{ $lesson->student?->name }} &rarr; {{ $lesson->teacher?->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $lesson->instrument?->name }} · {{ $lesson->lesson_date?->format('M d, Y') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.show', $lesson) }}" class="soh-link text-sm font-medium whitespace-nowrap">View</a>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">No cancelled lessons.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Calendar --}}
        @if($role === 'student')
            @include('partials.lesson-calendar', [
                'title' => 'My Lesson Calendar',
                'description' => 'Confirmed lessons only. Click an event to view details.',
                'eventsUrl' => route('student.calendar.events'),
                'viewerRole' => 'student',
            ])
        @elseif($role === 'teacher')
            @include('partials.lesson-calendar', [
                'title' => 'Teaching Calendar',
                'description' => 'Confirmed lessons assigned to you. Click an event to view details.',
                'eventsUrl' => route('teacher.calendar.events'),
                'viewerRole' => 'teacher',
            ])
        @endif
    </div>
@endsection
