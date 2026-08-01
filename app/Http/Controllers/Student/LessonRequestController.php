<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreLessonRequestSubmissionRequest;
use App\Support\Notifications\LessonRequestNotificationService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\Instrument;
use Modules\Booking\Models\LessonRequest;

class LessonRequestController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();

        $lessonRequests = $student->lessonRequestsAsStudent()
            ->with([
                'teacher:id,name',
                'instrument:id,name',
                'lesson:id,lesson_request_id,lesson_date,lesson_start_time,lesson_end_time,status',
            ])
            ->latest()
            ->get();

        $pendingRequests = $lessonRequests->filter(function (LessonRequest $lessonRequest): bool {
            return $lessonRequest->status === LessonRequestStatus::Pending;
        });
        $confirmedLessons = $lessonRequests->filter(function (LessonRequest $lessonRequest): bool {
            return $lessonRequest->lesson !== null;
        });
        $rescheduleSuggestions = $lessonRequests->filter(function (LessonRequest $lessonRequest): bool {
            return $lessonRequest->status === LessonRequestStatus::TeacherRescheduled;
        });
        $cancelledRequests = $lessonRequests->filter(function (LessonRequest $lessonRequest): bool {
            return in_array($lessonRequest->status, [
                LessonRequestStatus::StudentDeclined,
                LessonRequestStatus::Cancelled,
            ], true);
        });

        return view('student.lesson-requests.index', compact(
            'lessonRequests',
            'pendingRequests',
            'confirmedLessons',
            'rescheduleSuggestions',
            'cancelledRequests'
        ));
    }

    public function create(Request $request): View
    {
        $instruments = Instrument::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        $teachers = User::query()
            ->select('id', 'name')
            ->whereHas('roles', function ($query): void {
                $query->where('name', 'teacher');
            })
            ->with(['teachingInstruments:id'])
            ->orderBy('name')
            ->get();

        return view('student.lesson-requests.create', compact('instruments', 'teachers'));
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

        return redirect()
            ->route('student.lesson-requests.index')
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
            $lockedRequest = LessonRequest::query()
                ->whereKey($lessonRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->status !== LessonRequestStatus::TeacherRescheduled) {
                return;
            }

            $lockedRequest->update([
                'status' => LessonRequestStatus::StudentAccepted,
            ]);

            BookedLesson::query()->firstOrCreate(
                ['lesson_request_id' => $lockedRequest->id],
                [
                    'student_id' => $lockedRequest->student_id,
                    'teacher_id' => $lockedRequest->teacher_id,
                    'instrument_id' => $lockedRequest->instrument_id,
                    'lesson_date' => $lockedRequest->suggested_date,
                    'lesson_start_time' => $lockedRequest->suggested_start_time,
                    'lesson_end_time' => $lockedRequest->suggested_end_time,
                    'lesson_duration' => $lockedRequest->lesson_duration,
                    'status' => LessonStatus::Scheduled,
                ]
            );
        });

        $lessonRequest->refresh()->loadMissing('lesson');

        $notifications->notifyTeacherSuggestionAccepted($lessonRequest);

        return redirect()
            ->route('student.lesson-requests.index')
            ->with('notify', [
                'message' => 'Your suggested lesson time has been accepted.',
                'type' => 'success',
            ]);
    }
}