<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ConfirmLessonRequest;
use App\Http\Requests\Teacher\RejectLessonRequest;
use App\Http\Requests\Teacher\RescheduleLessonRequest;
use App\Support\Notifications\LessonRequestNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\LessonRequest;

class LessonRequestController extends Controller
{
    public function index(Request $request): View
    {
        $teacherId = (int) $request->user()->id;

        $lessonRequests = LessonRequest::query()
            ->where('teacher_id', $teacherId)
            ->with([
                'student:id,name',
                'instrument:id,name',
                'lesson:id,lesson_request_id,lesson_date,lesson_start_time,lesson_end_time,lesson_duration,status',
            ])
            ->latest()
            ->get();

        $pendingRequests = $lessonRequests->filter(function (LessonRequest $lessonRequest): bool {
            return $lessonRequest->status === LessonRequestStatus::Pending;
        });
        $rescheduleRequests = $lessonRequests->filter(function (LessonRequest $lessonRequest): bool {
            return $lessonRequest->status === LessonRequestStatus::TeacherRescheduled;
        });
        $confirmedLessons = $lessonRequests->filter(function (LessonRequest $lessonRequest): bool {
            return $lessonRequest->lesson !== null;
        });

        return view('teacher.lesson-requests.index', compact(
            'lessonRequests',
            'pendingRequests',
            'rescheduleRequests',
            'confirmedLessons'
        ));
    }

    public function show(LessonRequest $lessonRequest): View
    {
        $this->authorize('view', $lessonRequest);

        $lessonRequest->load([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lesson:id,lesson_request_id,lesson_date,lesson_start_time,lesson_end_time,lesson_duration,status',
        ]);

        return view('teacher.lesson-requests.show', compact('lessonRequest'));
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
            ->where(function ($query) use ($lessonRequest): void {
                $query->where('lesson_start_time', '<', $lessonRequest->requested_end_time)
                    ->where('lesson_end_time', '>', $lessonRequest->requested_start_time);
            })
            ->exists();

        if ($hasOverlap) {
            return back()->with('notify', [
                'message' => 'You already have a confirmed lesson that overlaps with this time.',
                'type' => 'error',
            ]);
        }

        DB::transaction(function () use ($request, $lessonRequest): void {
            $lockedRequest = LessonRequest::query()
                ->whereKey($lessonRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->status !== LessonRequestStatus::Pending) {
                return;
            }

            $lockedRequest->update([
                'status' => LessonRequestStatus::TeacherConfirmed,
                'teacher_note' => $request->string('teacher_note')->toString() ?: $lockedRequest->teacher_note,
            ]);

            BookedLesson::query()->firstOrCreate(
                ['lesson_request_id' => $lockedRequest->id],
                [
                    'student_id' => $lockedRequest->student_id,
                    'teacher_id' => $lockedRequest->teacher_id,
                    'instrument_id' => $lockedRequest->instrument_id,
                    'lesson_date' => $lockedRequest->requested_date,
                    'lesson_start_time' => $lockedRequest->requested_start_time,
                    'lesson_end_time' => $lockedRequest->requested_end_time,
                    'lesson_duration' => $lockedRequest->lesson_duration,
                    'status' => LessonStatus::Scheduled,
                ]
            );
        });

        $lessonRequest->refresh();
        $notifications->notifyStudentLessonConfirmed($lessonRequest);

        return redirect()
            ->route('teacher.lesson-requests.show', $lessonRequest)
            ->with('notify', [
                'message' => 'Lesson request confirmed and lesson scheduled.',
                'type' => 'success',
            ]);
    }

    public function reschedule(RescheduleLessonRequest $request, LessonRequest $lessonRequest, LessonRequestNotificationService $notifications): RedirectResponse
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

        return redirect()
            ->route('teacher.lesson-requests.show', $lessonRequest)
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

        return redirect()
            ->route('teacher.lesson-requests.index')
            ->with('notify', [
                'message' => 'Lesson request has been rejected.',
                'type' => 'success',
            ]);
    }
}