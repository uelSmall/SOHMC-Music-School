@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
            ['label' => 'My Lesson Requests', 'current' => true],
        ]" />

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="soh-page-title">My Lesson Requests</h1>
                <p class="soh-page-subtitle">Review your booking requests, see teacher responses, and keep track of confirmed lessons.</p>
            </div>

            <a href="{{ route('student.lesson-requests.create') }}" class="soh-btn-primary inline-flex items-center justify-center px-5 py-3">
                Book a Lesson
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <article class="soh-card p-5">
                <p class="text-sm font-medium text-gray-500">Pending</p>
                <p class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">{{ $pendingRequests->count() }}</p>
            </article>
            <article class="soh-card p-5">
                <p class="text-sm font-medium text-gray-500">Confirmed Lessons</p>
                <p class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">{{ $confirmedLessons->count() }}</p>
            </article>
            <article class="soh-card p-5">
                <p class="text-sm font-medium text-gray-500">Reschedule Suggestions</p>
                <p class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">{{ $rescheduleSuggestions->count() }}</p>
            </article>
            <article class="soh-card p-5">
                <p class="text-sm font-medium text-gray-500">Cancelled</p>
                <p class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">{{ $cancelledRequests->count() }}</p>
            </article>
        </div>

        @php
            $statusMeta = [
                'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
                'teacher_confirmed' => ['label' => 'Teacher Confirmed', 'class' => 'bg-blue-100 text-blue-800'],
                'teacher_rescheduled' => ['label' => 'Reschedule Suggested', 'class' => 'bg-purple-100 text-purple-800'],
                'student_accepted' => ['label' => 'Accepted', 'class' => 'bg-green-100 text-green-800'],
                'student_declined' => ['label' => 'Declined', 'class' => 'bg-red-100 text-red-800'],
                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-gray-100 text-gray-800'],
                'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-blue-100 text-blue-800'],
                'completed' => ['label' => 'Completed', 'class' => 'bg-green-100 text-green-800'],
            ];
        @endphp

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <section class="soh-card p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-[color:var(--soh-black)]">Pending Requests</h2>
                    <span class="text-sm text-gray-500">Awaiting teacher review</span>
                </div>

                <div class="space-y-4">
                    @forelse ($pendingRequests as $request)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-[color:var(--soh-black)]">{{ $request->teacher?->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $request->instrument?->name }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusMeta[$request->status->value]['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusMeta[$request->status->value]['label'] ?? ucfirst($request->status->value) }}
                                </span>
                            </div>

                            <div class="mt-4 grid gap-3 text-sm text-gray-600 sm:grid-cols-2">
                                <p><span class="font-medium text-gray-800">Date:</span> {{ $request->requested_date?->format('M d, Y') }}</p>
                                <p><span class="font-medium text-gray-800">Time:</span> {{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}</p>
                                <p><span class="font-medium text-gray-800">Duration:</span> {{ $request->lesson_duration }} minutes</p>
                                <p><span class="font-medium text-gray-800">Note:</span> {{ $request->student_note ?: 'No note provided' }}</p>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No pending requests yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="soh-card p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-[color:var(--soh-black)]">Confirmed Lessons</h2>
                    <span class="text-sm text-gray-500">Approved bookings</span>
                </div>

                <div class="space-y-4">
                    @forelse ($confirmedLessons as $request)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-[color:var(--soh-black)]">{{ $request->teacher?->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $request->instrument?->name }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusMeta[$request->lesson?->status->value ?? 'scheduled']['class'] ?? 'bg-blue-100 text-blue-800' }}">
                                    {{ $statusMeta[$request->lesson?->status->value ?? 'scheduled']['label'] ?? 'Scheduled' }}
                                </span>
                            </div>

                            @if ($request->lesson)
                                <div class="mt-4 grid gap-3 text-sm text-gray-600 sm:grid-cols-2">
                                    <p><span class="font-medium text-gray-800">Date:</span> {{ $request->lesson->lesson_date?->format('M d, Y') }}</p>
                                    <p><span class="font-medium text-gray-800">Time:</span> {{ \Illuminate\Support\Carbon::parse($request->lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->lesson->lesson_end_time)->format('g:i A') }}</p>
                                </div>
                            @else
                                <p class="mt-3 text-sm text-gray-600">This request is ready for lesson creation once the teacher confirms the final schedule.</p>
                            @endif
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No confirmed lessons yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="soh-card p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-[color:var(--soh-black)]">Reschedule Suggestions</h2>
                    <span class="text-sm text-gray-500">Teacher proposed a new time</span>
                </div>

                <div class="space-y-4">
                    @forelse ($rescheduleSuggestions as $request)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-[color:var(--soh-black)]">{{ $request->teacher?->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $request->instrument?->name }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusMeta[$request->status->value]['class'] ?? 'bg-purple-100 text-purple-800' }}">
                                    {{ $statusMeta[$request->status->value]['label'] ?? ucfirst($request->status->value) }}
                                </span>
                            </div>

                            @if ($request->suggested_date)
                                <div class="mt-4 grid gap-3 text-sm text-gray-600 sm:grid-cols-2">
                                    <p><span class="font-medium text-gray-800">Suggested Date:</span> {{ $request->suggested_date?->format('M d, Y') }}</p>
                                    <p><span class="font-medium text-gray-800">Suggested Time:</span> {{ \Illuminate\Support\Carbon::parse($request->suggested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->suggested_end_time)->format('g:i A') }}</p>
                                </div>
                            @endif

                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <form method="POST" action="{{ route('student.lesson-requests.accept-suggestion', $request) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="soh-btn-primary px-4 py-2 text-sm">
                                        Accept Suggested Time
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No reschedule suggestions yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="soh-card p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-[color:var(--soh-black)]">Cancelled Requests</h2>
                    <span class="text-sm text-gray-500">Closed requests</span>
                </div>

                <div class="space-y-4">
                    @forelse ($cancelledRequests as $request)
                        <article class="rounded-2xl border border-gray-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-[color:var(--soh-black)]">{{ $request->teacher?->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $request->instrument?->name }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusMeta[$request->status->value]['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusMeta[$request->status->value]['label'] ?? ucfirst($request->status->value) }}
                                </span>
                            </div>

                            <div class="mt-4 grid gap-3 text-sm text-gray-600 sm:grid-cols-2">
                                <p><span class="font-medium text-gray-800">Date:</span> {{ $request->requested_date?->format('M d, Y') }}</p>
                                <p><span class="font-medium text-gray-800">Time:</span> {{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}</p>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-600">No cancelled requests yet.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection