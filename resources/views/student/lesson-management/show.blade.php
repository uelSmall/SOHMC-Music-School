@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
            ['label' => 'My Lessons', 'route' => route('student.lesson-management.index')],
            ['label' => 'Lesson Details', 'current' => true],
        ]" />

        <div class="flex flex-col gap-3">
            <a href="{{ route('student.lesson-management.index') }}" class="soh-link text-sm">&larr; Back to My Lessons</a>
            <div>
                <h1 class="soh-page-title">Lesson Details</h1>
                <p class="soh-page-subtitle">Review the lesson information and notes from the lesson request.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="soh-card p-6 lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-black">Lesson Information</h2>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $lesson->status === \Modules\Booking\Enums\LessonStatus::Scheduled ? 'bg-yellow-100 text-yellow-800' : ($lesson->status === \Modules\Booking\Enums\LessonStatus::Completed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">{{ $lesson->status->label() }}</span>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Teacher</p>
                        <p class="mt-1 text-base font-semibold text-black">{{ $lesson->teacher?->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Instrument</p>
                        <p class="mt-1 text-base text-black">{{ $lesson->instrument?->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Lesson Date</p>
                        <p class="mt-1 text-base text-black">{{ $lesson->lesson_date?->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Lesson Time</p>
                        <p class="mt-1 text-base text-black">{{ \Illuminate\Support\Carbon::parse($lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lesson->lesson_end_time)->format('g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Duration</p>
                        <p class="mt-1 text-base text-black">{{ $lesson->lesson_duration }} minutes</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Completion Time</p>
                        <p class="mt-1 text-base text-black">{{ $lesson->completed_at?->format('M d, Y g:i A') ?? 'Not completed yet' }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-gray-200 p-4">
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Student Notes</p>
                    <p class="mt-2 text-sm text-gray-700">{{ $lesson->lessonRequest?->student_note ?: 'No notes provided.' }}</p>
                </div>

                <div class="mt-4 rounded-2xl border border-gray-200 p-4">
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Teacher Notes</p>
                    <p class="mt-2 text-sm text-gray-700">{{ $lesson->lessonRequest?->teacher_note ?: 'No notes provided.' }}</p>
                </div>

                @if ($lesson->cancellation_reason)
                    <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4">
                        <p class="text-xs font-semibold tracking-wide text-red-700 uppercase">Cancellation Reason</p>
                        <p class="mt-2 text-sm text-red-900">{{ $lesson->cancellation_reason }}</p>
                    </div>
                @endif

                @if ($lesson->rescheduled_at)
                    <div class="mt-4 rounded-2xl border border-purple-200 bg-purple-50 p-4">
                        <p class="text-xs font-semibold tracking-wide text-purple-700 uppercase">Last Rescheduled</p>
                        <p class="mt-2 text-sm text-purple-900">{{ $lesson->rescheduled_at?->format('M d, Y g:i A') }}</p>
                    </div>
                @endif
            </section>

            <section class="soh-card p-6">
                <h2 class="text-lg font-semibold text-black">What You Can Do</h2>
                <p class="mt-2 text-sm text-gray-600">Students can review lesson details here, but cannot edit lesson information.</p>

                <div class="mt-4 space-y-3">
                    <a href="{{ route('student.lesson-management.index') }}" class="soh-btn-outline w-full">Back to My Lessons</a>
                    <a href="{{ route('student.lesson-requests.index') }}" class="soh-btn-primary w-full">View Lesson Requests</a>
                </div>
            </section>
        </div>
    </div>
@endsection