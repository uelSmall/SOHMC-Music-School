<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;

class RescheduleBookedLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'new_date' => ['required', 'date', 'after_or_equal:today'],
            'new_start_time' => ['required', 'date_format:H:i'],
            'new_end_time' => ['required', 'date_format:H:i', 'after:new_start_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_date.required' => 'Please choose a new lesson date.',
            'new_start_time.required' => 'Please choose a new start time.',
            'new_end_time.required' => 'Please choose a new end time.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            /** @var \Modules\Booking\Models\BookedLesson|null $lesson */
            $lesson = $this->route('lesson');

            if (! $lesson instanceof BookedLesson) {
                return;
            }

            $newDate = $this->date('new_date');
            $newStartTime = $this->string('new_start_time')->toString();
            $newEndTime = $this->string('new_end_time')->toString();

            if (! $newDate || ! $newStartTime || ! $newEndTime) {
                return;
            }

            $hasConflict = BookedLesson::query()
                ->where('teacher_id', $lesson->teacher_id)
                ->whereKeyNot($lesson->id)
                ->where('status', LessonStatus::Scheduled->value)
                ->whereDate('lesson_date', $newDate->toDateString())
                ->where(function ($query) use ($newStartTime, $newEndTime): void {
                    $query->where('lesson_start_time', '<', $newEndTime)
                        ->where('lesson_end_time', '>', $newStartTime);
                })
                ->exists();

            if ($hasConflict) {
                $validator->errors()->add('new_start_time', 'The selected time conflicts with another scheduled lesson.');
            }
        });
    }
}