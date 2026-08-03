<?php

namespace App\Http\Requests\Student;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Models\LessonRequest;

class StoreLessonRequestSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasRole('student');
    }

    public function rules(): array
    {
        return [
            'instrument_id' => ['required', 'integer', 'exists:instruments,id'],
            'teacher_id' => ['required', 'integer', 'exists:users,id'],
            'requested_date' => ['required', 'date', 'after_or_equal:today'],
            'requested_start_time' => ['required', 'date_format:H:i'],
            'requested_end_time' => ['required', 'date_format:H:i', 'after:requested_start_time'],
            'lesson_duration' => ['required', 'integer', Rule::in([30, 45, 60])],
            'student_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'instrument_id.required' => 'Please select an instrument.',
            'teacher_id.required' => 'Please select a teacher.',
            'requested_date.required' => 'Please choose a preferred lesson date.',
            'requested_start_time.required' => 'Please choose a preferred start time.',
            'requested_end_time.required' => 'Please choose a preferred end time.',
            'lesson_duration.required' => 'Please choose a lesson duration.',
            'lesson_duration.in' => 'Please choose a valid lesson duration.',
            'student_note.max' => 'Your note may not be longer than 2000 characters.',
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $instrumentId = $this->integer('instrument_id');
                $teacherId = $this->integer('teacher_id');
                $studentId = $this->user()?->id;

                if (! $instrumentId || ! $teacherId || ! $studentId) {
                    return;
                }

                $teacherTeachesInstrument = User::query()
                    ->whereKey($teacherId)
                    ->whereHas('teachingInstruments', function ($query) use ($instrumentId): void {
                        $query->whereKey($instrumentId);
                    })
                    ->exists();

                if (! $teacherTeachesInstrument) {
                    $validator->errors()->add(
                        'teacher_id',
                        'The selected teacher does not teach the selected instrument.'
                    );
                }

                $duplicatePendingRequest = LessonRequest::query()
                    ->where('student_id', $studentId)
                    ->where('teacher_id', $teacherId)
                    ->where('instrument_id', $instrumentId)
                    ->whereDate('requested_date', $this->date('requested_date'))
                    ->where('requested_start_time', $this->string('requested_start_time')->toString())
                    ->where('requested_end_time', $this->string('requested_end_time')->toString())
                    ->where('status', LessonRequestStatus::Pending)
                    ->exists();

                if ($duplicatePendingRequest) {
                    $validator->errors()->add(
                        'teacher_id',
                        'You already have a pending request for that teacher at the same date and time.'
                    );
                }
            },
        ];
    }
}