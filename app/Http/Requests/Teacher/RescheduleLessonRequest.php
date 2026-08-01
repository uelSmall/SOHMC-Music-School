<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class RescheduleLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'suggested_date' => ['required', 'date', 'after_or_equal:today'],
            'suggested_start_time' => ['required', 'date_format:H:i'],
            'suggested_end_time' => ['required', 'date_format:H:i', 'after:suggested_start_time'],
            'teacher_note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}