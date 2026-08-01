@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
            ['label' => 'Lesson Requests', 'route' => route('teacher.lesson-requests.index')],
            ['label' => 'Request Details', 'current' => true],
        ]" />

        <div class="flex flex-col gap-3">
            <a href="{{ route('teacher.lesson-requests.index') }}" class="soh-link text-sm">&larr; Back to Lesson Requests</a>
            <div>
                <h1 class="soh-page-title">Lesson Request Details</h1>
                <p class="soh-page-subtitle">Review request details and choose how to proceed with this booking request.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <section class="soh-card p-6 lg:col-span-2">
                <h2 class="mb-4 text-xl font-semibold text-black">Request Information</h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Student</p>
                        <p class="mt-1 text-base font-semibold text-black">{{ $lessonRequest->student?->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Instrument</p>
                        <p class="mt-1 text-base font-semibold text-black">{{ $lessonRequest->instrument?->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Requested Date</p>
                        <p class="mt-1 text-base text-black">{{ $lessonRequest->requested_date?->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Requested Time</p>
                        <p class="mt-1 text-base text-black">{{ \Illuminate\Support\Carbon::parse($lessonRequest->requested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lessonRequest->requested_end_time)->format('g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Duration</p>
                        <p class="mt-1 text-base text-black">{{ $lessonRequest->lesson_duration }} minutes</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Status</p>
                        <p class="mt-1 text-base font-semibold text-black">{{ $lessonRequest->status->label() }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-gray-200 p-4">
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Student Note</p>
                    <p class="mt-2 text-sm text-gray-700">{{ $lessonRequest->student_note ?: 'No note provided.' }}</p>
                </div>

                @if ($lessonRequest->suggested_date)
                    <div class="mt-4 rounded-2xl border border-purple-200 bg-purple-50 p-4">
                        <p class="text-xs font-semibold tracking-wide text-purple-700 uppercase">Last Suggested Time</p>
                        <p class="mt-2 text-sm text-purple-900">
                            {{ $lessonRequest->suggested_date?->format('M d, Y') }}
                            {{ \Illuminate\Support\Carbon::parse($lessonRequest->suggested_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lessonRequest->suggested_end_time)->format('g:i A') }}
                        </p>
                    </div>
                @endif

                @if ($lessonRequest->teacher_note)
                    <div class="mt-4 rounded-2xl border border-gray-200 p-4">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Teacher Note</p>
                        <p class="mt-2 text-sm text-gray-700">{{ $lessonRequest->teacher_note }}</p>
                    </div>
                @endif

                @if ($lessonRequest->lesson)
                    <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4">
                        <p class="text-xs font-semibold tracking-wide text-blue-700 uppercase">Confirmed Lesson</p>
                        <p class="mt-2 text-sm text-blue-900">
                            {{ $lessonRequest->lesson->lesson_date?->format('M d, Y') }}
                            {{ \Illuminate\Support\Carbon::parse($lessonRequest->lesson->lesson_start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($lessonRequest->lesson->lesson_end_time)->format('g:i A') }}
                            ({{ $lessonRequest->lesson->lesson_duration }} minutes)
                        </p>
                    </div>
                @endif
            </section>

            <section class="space-y-6">
                <article class="soh-card p-6">
                    <h2 class="text-lg font-semibold text-black">Confirm Request</h2>
                    <p class="mt-2 text-sm text-gray-600">Confirm the student&apos;s requested schedule and create a scheduled lesson.</p>

                    <form method="POST" action="{{ route('teacher.lesson-requests.confirm', $lessonRequest) }}" class="mt-4 space-y-3">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="confirm_teacher_note" value="Optional Note" />
                            <textarea id="confirm_teacher_note" name="teacher_note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]" placeholder="Optional note for this confirmation">{{ old('teacher_note') }}</textarea>
                        </div>

                        <button type="submit" class="soh-btn-primary w-full" @disabled($lessonRequest->status !== \Modules\Booking\Enums\LessonRequestStatus::Pending)>
                            Confirm Request
                        </button>
                    </form>
                </article>

                <article class="soh-card p-6">
                    <h2 class="text-lg font-semibold text-black">Suggest New Date &amp; Time</h2>
                    <p class="mt-2 text-sm text-gray-600">Propose an alternate schedule for the student to review.</p>

                    <form method="POST" action="{{ route('teacher.lesson-requests.reschedule', $lessonRequest) }}" class="mt-4 space-y-3">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="suggested_date" value="Suggested Date" />
                            <x-text-input id="suggested_date" name="suggested_date" type="date" class="mt-1 block w-full" value="{{ old('suggested_date') }}" />
                        </div>

                        <div>
                            <x-input-label for="suggested_start_time" value="Suggested Start Time" />
                            <x-text-input id="suggested_start_time" name="suggested_start_time" type="time" class="mt-1 block w-full" value="{{ old('suggested_start_time') }}" />
                        </div>

                        <div>
                            <x-input-label for="suggested_end_time" value="Suggested End Time" />
                            <x-text-input id="suggested_end_time" name="suggested_end_time" type="time" class="mt-1 block w-full" value="{{ old('suggested_end_time') }}" />
                        </div>

                        <div>
                            <x-input-label for="reschedule_teacher_note" value="Optional Message" />
                            <textarea id="reschedule_teacher_note" name="teacher_note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[color:var(--soh-purple)] focus:ring-[color:var(--soh-purple)]" placeholder="Explain why this new time is suggested">{{ old('teacher_note') }}</textarea>
                        </div>

                        <button type="submit" class="soh-btn-outline w-full" @disabled($lessonRequest->status !== \Modules\Booking\Enums\LessonRequestStatus::Pending)>
                            Suggest New Schedule
                        </button>
                    </form>
                </article>

                <article class="soh-card border border-red-200 p-6">
                    <h2 class="text-lg font-semibold text-red-700">Reject Request</h2>
                    <p class="mt-2 text-sm text-gray-600">Reject this request if the lesson cannot be scheduled.</p>

                    <form method="POST" action="{{ route('teacher.lesson-requests.reject', $lessonRequest) }}" class="mt-4 space-y-3">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="reject_teacher_note" value="Reason (Optional)" />
                            <textarea id="reject_teacher_note" name="teacher_note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Provide context for the rejection">{{ old('teacher_note') }}</textarea>
                        </div>

                        <button type="submit" class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700" @disabled($lessonRequest->status !== \Modules\Booking\Enums\LessonRequestStatus::Pending)>
                            Reject Request
                        </button>
                    </form>
                </article>
            </section>
        </div>
    </div>
@endsection