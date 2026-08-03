<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class RejectLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'teacher_note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}