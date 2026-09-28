<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreLessonRequestSubmissionRequest;
use App\Http\Requests\Teacher\CancelLessonRequest;
use App\Http\Requests\Teacher\CompleteLessonRequest;
use App\Http\Requests\Teacher\ConfirmLessonRequest;
use App\Http\Requests\Teacher\RejectLessonRequest;
use App\Http\Requests\Teacher\RescheduleBookedLessonRequest;
use App\Http\Requests\Teacher\RescheduleLessonRequest;
use App\Models\User;
use App\Support\Notifications\BookedLessonNotificationService;
use App\Support\Notifications\LessonRequestNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\Instrument;
use Modules\Booking\Models\LessonRequest;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->hasAnyRole(['administrator', 'super admin'])) {
            return $this->adminIndex($request);
        }

        if ($user->hasRole('teacher')) {
            return $this->teacherIndex($request);
        }

        return $this->studentIndex($request);
    }

    public function show(Request $request, string $booking): View
    {
        $user = $request->user();

        // Try BookedLesson first, then LessonRequest
        $lesson = BookedLesson::query()->find($booking);
        if ($lesson) {
            return $this->showLesson($request, $lesson);
        }

        $lessonRequest = LessonRequest::query()->find($booking);
        if ($lessonRequest) {
            return $this->showRequest($request, $lessonRequest);
        }

        abort(404);
    }

    private function showLesson(Request $request, BookedLesson $booking): View
    {
        $booking->load([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lessonRequest:id,student_note,teacher_note',
        ]);

        $user = $request->user();

        if ($user->hasAnyRole(['administrator', 'super admin'])) {
            return view('bookings.show', ['lesson' => $booking, 'role' => 'admin']);
        }

        if ($user->hasRole('teacher')) {
            $this->authorize('view', $booking);

            return view('bookings.show', ['lesson' => $booking, 'role' => 'teacher']);
        }

        $this->authorize('view', $booking);

        return view('bookings.show', ['lesson' => $booking, 'role' => 'student']);
    }

    public function showRequest(Request $request, LessonRequest $booking): View
    {
        $booking->load([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lesson:id,lesson_request_id,lesson_date,lesson_start_time,lesson_end_time,lesson_duration,status',
        ]);

        $user = $request->user();

        if ($user->hasAnyRole(['administrator', 'super admin'])) {
            return view('bookings.show-request', ['lessonRequest' => $booking, 'role' => 'admin']);
        }

        if ($user->hasRole('teacher')) {
            $this->authorize('view', $booking);

            return view('bookings.show-request', ['lessonRequest' => $booking, 'role' => 'teacher']);
        }

        if ((int) $booking->student_id !== (int) $user->id) {
            abort(403);
        }

        return view('bookings.show-request', ['lessonRequest' => $booking, 'role' => 'student']);
    }

    public function create(Request $request): View
    {
        $instruments = Instrument::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        $teachers = User::query()
            ->select('id', 'name')
            ->whereHas('roles', fn ($q) => $q->where('name', 'teacher'))
            ->with(['teachingInstruments:id'])
            ->orderBy('name')
            ->get();

        return view('bookings.create', compact('instruments', 'teachers'));
    }

    public function store(StoreLessonRequestSubmissionRequest $request, LessonRequestNotificationService $notifications): RedirectResponse
    {
        $lessonRequest = LessonRequest::create([
            'student_id' => $request->user()->id,
            'teacher_id' => $request->integer('teacher_id'),
            'instrument_id' => $request->integer('instrument_id'),
            'requested_date' => $request->date('requested_date'),
            'requested_start_time' => $request->string('requested_start_time'),
            'requested_end_time' => $request->string('requested_end_time'),
            'lesson_duration' => $request->integer('lesson_duration'),
            'status' => LessonRequestStatus::Pending,
            'student_note' => $request->string('student_note')->toString() ?: null,
        ]);

        $notifications->notifyTeacherOfNewRequest($lessonRequest);

        log_activity(
            'submitted a new lesson booking request',
            $lessonRequest,
            route('bookings.show', ['booking' => $lessonRequest->id])
        );

        return redirect()
            ->route('bookings.index')
            ->with('notify', [
                'message' => 'Your lesson request has been submitted and is now pending teacher review.',
                'type' => 'success',
            ]);
    }

    public function acceptSuggestion(Request $request, LessonRequest $lessonRequest, LessonRequestNotificationService $notifications): RedirectResponse
    {
        abort_unless((int) $lessonRequest->student_id === (int) $request->user()->id, 403);

        if ($lessonRequest->status !== LessonRequestStatus::TeacherRescheduled) {
            return back()->with('notify', [
                'message' => 'Only rescheduled requests can be accepted.',
                'type' => 'error',
            ]);
        }

        DB::transaction(function () use ($lessonRequest): void {
            $locked = LessonRequest::query()->whereKey($lessonRequest->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== LessonRequestStatus::TeacherRescheduled) {
                return;
            }

            $locked->update(['status' => LessonRequestStatus::StudentAccepted]);

            BookedLesson::query()->firstOrCreate(
                ['lesson_request_id' => $locked->id],
                [
                    'student_id' => $locked->student_id,
                    'teacher_id' => $locked->teacher_id,
                    'instrument_id' => $locked->instrument_id,
                    'lesson_date' => $locked->suggested_date,
                    'lesson_start_time' => $locked->suggested_start_time,
                    'lesson_end_time' => $locked->suggested_end_time,
                    'lesson_duration' => $locked->lesson_duration,
                    'status' => LessonStatus::Scheduled,
                ]
            );
        });

        $lessonRequest->refresh()->loadMissing('lesson');
        $notifications->notifyTeacherSuggestionAccepted($lessonRequest);

        log_activity(
            'accepted the suggested lesson time',
            $lessonRequest,
            route('bookings.show', ['booking' => $lessonRequest->id])
        );

        return redirect()
            ->route('bookings.index')
            ->with('notify', [
                'message' => 'Your suggested lesson time has been accepted.',
                'type' => 'success',
            ]);
    }

    public function confirm(ConfirmLessonRequest $request, LessonRequest $lessonRequest, LessonRequestNotificationService $notifications): RedirectResponse
    {
        $this->authorize('update', $lessonRequest);

        if ($lessonRequest->status !== LessonRequestStatus::Pending) {
            return back()->with('notify', [
                'message' => 'Only pending requests can be confirmed.',
                'type' => 'error',
            ]);
        }

        $teacherCanTeachInstrument = $request->user()
            ->teachingInstruments()
            ->whereKey($lessonRequest->instrument_id)
            ->exists();

        if (! $teacherCanTeachInstrument) {
            return back()->with('notify', [
                'message' => 'You are not assigned to teach this instrument.',
                'type' => 'error',
            ]);
        }

        $hasOverlap = BookedLesson::query()
            ->where('teacher_id', $request->user()->id)
            ->whereDate('lesson_date', $lessonRequest->requested_date)
            ->where('status', '!=', LessonStatus::Cancelled->value)
            ->where(fn ($q) => $q
                ->where('lesson_start_time', '<', $lessonRequest->requested_end_time)
                ->where('lesson_end_time', '>', $lessonRequest->requested_start_time)
            )
            ->exists();

        if ($hasOverlap) {
            return back()->with('notify', [
                'message' => 'You already have a confirmed lesson that overlaps with this time.',
                'type' => 'error',
            ]);
        }

        DB::transaction(function () use ($request, $lessonRequest): void {
            $locked = LessonRequest::query()->whereKey($lessonRequest->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== LessonRequestStatus::Pending) {
                return;
            }

            $locked->update([
                'status' => LessonRequestStatus::TeacherConfirmed,
                'teacher_note' => $request->string('teacher_note')->toString() ?: $locked->teacher_note,
            ]);

            BookedLesson::query()->firstOrCreate(
                ['lesson_request_id' => $locked->id],
                [
                    'student_id' => $locked->student_id,
                    'teacher_id' => $locked->teacher_id,
                    'instrument_id' => $locked->instrument_id,
                    'lesson_date' => $locked->requested_date,
                    'lesson_start_time' => $locked->requested_start_time,
                    'lesson_end_time' => $locked->requested_end_time,
                    'lesson_duration' => $locked->lesson_duration,
                    'status' => LessonStatus::Scheduled,
                ]
            );
        });

        $lessonRequest->refresh();
        $notifications->notifyStudentLessonConfirmed($lessonRequest);

        log_activity(
            'confirmed a booking request from '.($lessonRequest->student?->name ?? 'a student'),
            $lessonRequest,
            route('bookings.show', ['booking' => $lessonRequest->id])
        );

        return redirect()
            ->route('bookings.show', $lessonRequest->lesson)
            ->with('notify', [
                'message' => 'Lesson request confirmed and lesson scheduled.',
                'type' => 'success',
            ]);
    }

    public function teacherReschedule(RescheduleLessonRequest $request, LessonRequest $lessonRequest, LessonRequestNotificationService $notifications): RedirectResponse
    {
        $this->authorize('update', $lessonRequest);

        if ($lessonRequest->status !== LessonRequestStatus::Pending) {
            return back()->with('notify', [
                'message' => 'Only pending requests can be rescheduled.',
                'type' => 'error',
            ]);
        }

        $lessonRequest->update([
            'suggested_date' => $request->date('suggested_date'),
            'suggested_start_time' => $request->string('suggested_start_time')->toString(),
            'suggested_end_time' => $request->string('suggested_end_time')->toString(),
            'teacher_note' => $request->string('teacher_note')->toString() ?: null,
            'status' => LessonRequestStatus::TeacherRescheduled,
        ]);

        $notifications->notifyStudentLessonRescheduled($lessonRequest);

        log_activity(
            'suggested a new time for a booking request from '.($lessonRequest->student?->name ?? 'a student'),
            $lessonRequest,
            route('bookings.show', ['booking' => $lessonRequest->id])
        );

        return redirect()
            ->route('bookings.show', $lessonRequest->id)
            ->with('notify', [
                'message' => 'A new schedule was suggested to the student.',
                'type' => 'success',
            ]);
    }

    public function reject(RejectLessonRequest $request, LessonRequest $lessonRequest, LessonRequestNotificationService $notifications): RedirectResponse
    {
        $this->authorize('update', $lessonRequest);

        if ($lessonRequest->status !== LessonRequestStatus::Pending) {
            return back()->with('notify', [
                'message' => 'Only pending requests can be rejected.',
                'type' => 'error',
            ]);
        }

        $lessonRequest->update([
            'teacher_note' => $request->string('teacher_note')->toString() ?: null,
            'status' => LessonRequestStatus::Cancelled,
        ]);

        $notifications->notifyStudentLessonRejected($lessonRequest);

        log_activity(
            'rejected a booking request from '.($lessonRequest->student?->name ?? 'a student'),
            $lessonRequest,
            route('bookings.show', ['booking' => $lessonRequest->id])
        );

        return redirect()
            ->route('bookings.index')
            ->with('notify', [
                'message' => 'Lesson request has been rejected.',
                'type' => 'success',
            ]);
    }

    public function complete(CompleteLessonRequest $request, BookedLesson $lesson): RedirectResponse
    {
        $this->authorize('complete', $lesson);

        $lesson->update([
            'status' => LessonStatus::Completed,
            'completed_at' => now(),
        ]);

        log_activity(
            'marked a lesson as completed',
            $lesson,
            route('bookings.show', $lesson)
        );

        return redirect()
            ->route('bookings.show', $lesson)
            ->with('notify', [
                'message' => 'Lesson marked as completed.',
                'type' => 'success',
            ]);
    }

    public function cancel(CancelLessonRequest $request, BookedLesson $lesson, BookedLessonNotificationService $notifications): RedirectResponse
    {
        $this->authorize('cancel', $lesson);

        $lesson->update([
            'status' => LessonStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_reason' => $request->string('cancellation_reason')->toString() ?: null,
        ]);

        $notifications->notifyStudentLessonCancelled($lesson);

        log_activity(
            'cancelled a lesson',
            $lesson,
            route('bookings.show', $lesson)
        );

        return redirect()
            ->route('bookings.show', $lesson)
            ->with('notify', [
                'message' => 'Lesson cancelled successfully.',
                'type' => 'success',
            ]);
    }

    public function rescheduleLesson(RescheduleBookedLessonRequest $request, BookedLesson $lesson, BookedLessonNotificationService $notifications): RedirectResponse
    {
        $this->authorize('reschedule', $lesson);

        $newDate = $request->date('new_date');
        $newStartTime = $request->string('new_start_time')->toString();
        $newEndTime = $request->string('new_end_time')->toString();

        if (
            $lesson->lesson_date?->toDateString() === $newDate?->toDateString()
            && $lesson->lesson_start_time === $newStartTime
            && $lesson->lesson_end_time === $newEndTime
        ) {
            return redirect()
                ->route('bookings.show', $lesson)
                ->with('notify', [
                    'message' => 'Lesson already uses that schedule.',
                    'type' => 'info',
                ]);
        }

        $lesson->update([
            'lesson_date' => $newDate,
            'lesson_start_time' => $newStartTime,
            'lesson_end_time' => $newEndTime,
            'rescheduled_at' => now(),
        ]);

        $notifications->notifyStudentLessonRescheduled($lesson);
        $notifications->notifyTeacherLessonRescheduled($lesson);

        log_activity(
            'rescheduled a lesson',
            $lesson,
            route('bookings.show', $lesson)
        );

        return redirect()
            ->route('bookings.show', $lesson)
            ->with('notify', [
                'message' => 'Lesson rescheduled successfully.',
                'type' => 'success',
            ]);
    }

    /**
     * Permanently delete a confirmed lesson booking. Deleting the linked
     * LessonRequest cascades the BookedLesson via the lesson_requests →
     * booked_lessons FK, so the whole chain is removed.
     *
     * Separate route from destroyRequest() on purpose: the two tables have
     * independent id sequences, so a single {booking} route could resolve to
     * the wrong record.
     */
    public function destroyLesson(Request $request, BookedLesson $lesson): RedirectResponse
    {
        $this->authorize('manage', $lesson);

        $label = trim(($lesson->student?->name ?? 'a student').' — '.($lesson->instrument?->name ?? 'lesson'));

        DB::transaction(function () use ($lesson): void {
            if ($lesson->lessonRequest) {
                $lesson->lessonRequest->delete();
            } else {
                $lesson->delete();
            }
        });

        log_activity('deleted a booking for '.($lesson->student?->name ?? 'a student'), null, null, 'default', $request->user());

        return redirect()
            ->route('bookings.index')
            ->with('notify', [
                'message' => 'Booking deleted: '.$label.'.',
                'type' => 'success',
            ]);
    }

    /**
     * Permanently delete a lesson request (pending/rescheduled). Cascades the
     * linked BookedLesson when one exists.
     */
    public function destroyRequest(Request $request, LessonRequest $lessonRequest): RedirectResponse
    {
        $this->authorize('update', $lessonRequest);

        $label = trim(($lessonRequest->student?->name ?? 'a student').' — '.($lessonRequest->instrument?->name ?? 'lesson'));
        $lessonRequest->delete();

        log_activity('deleted a booking request from '.($lessonRequest->student?->name ?? 'a student'), null, null, 'default', $request->user());

        return redirect()
            ->route('bookings.index')
            ->with('notify', [
                'message' => 'Booking deleted: '.$label.'.',
                'type' => 'success',
            ]);
    }

    private function studentIndex(Request $request): View
    {
        $studentId = (int) $request->user()->id;
        $today = now()->toDateString();

        $lessons = BookedLesson::query()
            ->where('student_id', $studentId)
            ->with([
                'teacher:id,name',
                'instrument:id,name',
                'lessonRequest:id,student_note,teacher_note',
            ])
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->get();

        $lessonRequests = $request->user()->lessonRequestsAsStudent()
            ->with([
                'teacher:id,name',
                'instrument:id,name',
                'lesson:id,lesson_request_id,lesson_date,lesson_start_time,lesson_end_time,status',
            ])
            ->latest()
            ->get();

        $all = $lessonRequests->concat($lessons)->sortByDesc(function ($item) {
            return $item instanceof LessonRequest
                ? ($item->created_at ?? now())
                : ($item->lesson_date ?? now());
        })->values();

        $pendingRequests = $lessonRequests->filter(fn (LessonRequest $r) => $r->status === LessonRequestStatus::Pending);
        $activeLessons = $lessons->filter(fn (BookedLesson $l) => $l->status === LessonStatus::Scheduled);
        $completedLessons = $lessons->where('status', LessonStatus::Completed);

        return view('bookings.index', compact(
            'all',
            'pendingRequests',
            'activeLessons',
            'completedLessons',
            'lessonRequests',
            'lessons',
        ))->with('role', 'student');
    }

    private function teacherIndex(Request $request): View
    {
        $today = now()->toDateString();

        $lessonsQuery = BookedLesson::query()
            ->with([
                'student:id,name',
                'instrument:id,name',
                'lessonRequest:id,student_note,teacher_note',
            ]);

        if (! $request->user()->hasAnyRole(['administrator', 'super admin'])) {
            $lessonsQuery->where('teacher_id', $request->user()->id);
        }

        $lessonRequestsQuery = LessonRequest::query()
            ->with([
                'student:id,name',
                'instrument:id,name',
                'lesson:id,lesson_request_id,lesson_date,lesson_start_time,lesson_end_time,lesson_duration,status',
            ]);

        if (! $request->user()->hasAnyRole(['administrator', 'super admin'])) {
            $lessonRequestsQuery->where('teacher_id', $request->user()->id);
        }

        $lessons = $lessonsQuery->orderBy('lesson_date')->orderBy('lesson_start_time')->get();
        $lessonRequests = $lessonRequestsQuery->latest()->get();

        $todaysLessons = $lessons->filter(fn (BookedLesson $l) => $l->status === LessonStatus::Scheduled && $l->lesson_date?->toDateString() === $today)->values();
        $upcomingLessons = $lessons->filter(fn (BookedLesson $l) => $l->status === LessonStatus::Scheduled && $l->lesson_date?->toDateString() > $today)->values();
        $completedLessons = $lessons->where('status', LessonStatus::Completed)->values();
        $cancelledLessons = $lessons->where('status', LessonStatus::Cancelled)->values();

        $pendingRequests = $lessonRequests->filter(fn (LessonRequest $r) => $r->status === LessonRequestStatus::Pending);
        $rescheduleRequests = $lessonRequests->filter(fn (LessonRequest $r) => $r->status === LessonRequestStatus::TeacherRescheduled);

        $statistics = [
            'today' => $todaysLessons->count(),
            'upcoming' => $upcomingLessons->count(),
            'completed' => $completedLessons->count(),
            'cancelled' => $cancelledLessons->count(),
            'pending_requests' => $pendingRequests->count(),
            'reschedule_requests' => $rescheduleRequests->count(),
        ];

        return view('bookings.index', compact(
            'lessons',
            'lessonRequests',
            'todaysLessons',
            'upcomingLessons',
            'completedLessons',
            'cancelledLessons',
            'pendingRequests',
            'rescheduleRequests',
            'statistics',
        ))->with('role', 'teacher');
    }

    private function adminIndex(Request $request): View
    {
        $today = now()->toDateString();

        $lessons = BookedLesson::query()
            ->with([
                'student:id,name',
                'teacher:id,name',
                'instrument:id,name',
                'lessonRequest:id,student_note,teacher_note',
            ])
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->get();

        $todaysLessons = $lessons->filter(fn (BookedLesson $l) => $l->status === LessonStatus::Scheduled && $l->lesson_date?->toDateString() === $today)->values();
        $upcomingLessons = $lessons->filter(fn (BookedLesson $l) => $l->status === LessonStatus::Scheduled && $l->lesson_date?->toDateString() > $today)->values();
        $completedLessons = $lessons->where('status', LessonStatus::Completed)->values();
        $cancelledLessons = $lessons->where('status', LessonStatus::Cancelled)->values();

        $statistics = [
            'today' => $todaysLessons->count(),
            'upcoming' => $upcomingLessons->count(),
            'completed' => $completedLessons->count(),
            'cancelled' => $cancelledLessons->count(),
        ];

        return view('bookings.index', compact(
            'lessons',
            'todaysLessons',
            'upcomingLessons',
            'completedLessons',
            'cancelledLessons',
            'statistics',
        ))->with('role', 'admin');
    }
}
