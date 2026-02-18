<?php

namespace Modules\Lesson\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Lesson\Enums\LessonStatus;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_backend');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:lessons,slug,'.$this->route('lesson')->id,
            'content' => 'required|string',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:'.implode(',', array_map(fn ($case) => $case->value, LessonStatus::cases())),
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer|min:0',
            'teacher_id' => 'nullable|exists:users,id',
            'file_path' => 'nullable|file|max:102400',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a lesson title.',
            'slug.unique' => 'This slug is already taken.',
            'content.required' => 'Lesson content is required.',
            'teacher_id.exists' => 'The selected teacher does not exist.',
            'file_path.file' => 'Please upload a valid file.',
            'file_path.max' => 'File size must not exceed 100MB.',
            'student_ids.*.exists' => 'One or more selected students do not exist.',
        ];
    }
}
