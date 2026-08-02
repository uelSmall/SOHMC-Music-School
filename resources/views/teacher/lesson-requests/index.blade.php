@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
            ['label' => 'Lesson Requests', 'current' => true],
        ]" />

        <div>
            <h1 class="soh-page-title">Lesson Requests</h1>
            <p class="soh-page-subtitle">Review student booking requests and confirm, reschedule, or reject from one place.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Pending Requests</p>
                <p class="soh-kpi-value mt-2">{{ $pendingRequests->count() }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Reschedule Suggested</p>
                <p class="soh-kpi-value mt-2">{{ $rescheduleRequests->count() }}</p>
            </div>
            <div class="soh-stat-card">
                <p class="soh-kpi-label">Confirmed Lessons</p>
                <p class="soh-kpi-value mt-2">{{ $confirmedLessons->count() }}</p>
            </div>
        </div>

        <div class="soh-card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-black">All Requests</h2>
                <span class="text-sm text-gray-500">{{ $lessonRequests->count() }} total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                            <th class="px-3 py-3">Student</th>
                            <th class="px-3 py-3">Instrument</th>
                            <th class="px-3 py-3">Date</th>
                            <th class="px-3 py-3">Start</th>
                            <th class="px-3 py-3">End</th>
                            <th class="px-3 py-3">Duration</th>
                            <th class="px-3 py-3">Student Note</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse ($lessonRequests as $request)
                            @php
                                $statusValue = $request->status->value;
                                $statusClasses = match ($statusValue) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'teacher_confirmed' => 'bg-blue-100 text-blue-800',
                                    'teacher_rescheduled' => 'bg-purple-100 text-purple-800',
                                    'student_accepted' => 'bg-green-100 text-green-800',
                                    'student_declined' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <tr>
                                <td class="px-3 py-4 font-medium text-black">{{ $request->student?->name }}</td>
                                <td class="px-3 py-4">{{ $request->instrument?->name }}</td>
                                <td class="px-3 py-4">{{ $request->requested_date?->format('M d, Y') }}</td>
                                <td class="px-3 py-4">{{ \Illuminate\Support\Carbon::parse($request->requested_start_time)->format('g:i A') }}</td>
                                <td class="px-3 py-4">{{ \Illuminate\Support\Carbon::parse($request->requested_end_time)->format('g:i A') }}</td>
                                <td class="px-3 py-4">{{ $request->lesson_duration }} min</td>
                                <td class="px-3 py-4 max-w-[220px] truncate" title="{{ $request->student_note }}">{{ $request->student_note ?: 'No note' }}</td>
                                <td class="px-3 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">{{ $request->status->label() }}</span>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('teacher.lesson-requests.show', $request) }}" class="soh-link text-sm font-medium">View</a>

                                        @if ($request->status === \Modules\Booking\Enums\LessonRequestStatus::Pending)
                                            <form method="POST" action="{{ route('teacher.lesson-requests.confirm', $request) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-md bg-green-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Confirm</button>
                                            </form>

                                            <a href="{{ route('teacher.lesson-requests.show', $request) }}" class="rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Suggest</a>

                                            <form method="POST" action="{{ route('teacher.lesson-requests.reject', $request) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-md bg-red-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Reject</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-8 text-center text-gray-500">No lesson requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection