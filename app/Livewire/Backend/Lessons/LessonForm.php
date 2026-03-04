<?php

namespace App\Livewire\Backend\Lessons;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Lesson\Enums\LessonStatus;
use Modules\Lesson\Models\Lesson;

class LessonForm extends Component
{
    use WithFileUploads;

    public ?Lesson $lesson = null;
    public string $routePrefix = 'backend';

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|max:255')]
    public string $slug = '';

    #[Validate('required|string')]
    public string $content = '';

    #[Validate('nullable|string|max:500')]
    public ?string $description = null;

    #[Validate('required|in:draft,published,archived')]
    public string $status = 'draft';

    #[Validate('nullable|date')]
    public ?string $published_at = null;

    #[Validate('nullable|integer|min:0')]
    public int $order = 0;

    #[Validate('nullable|exists:users,id')]
    public ?int $teacher_id = null;

    #[Validate('nullable|file|max:102400')]
    public $file_path = null;

    #[Validate('nullable|array')]
    public array $student_ids = [];

    public function mount(?Lesson $lesson = null, string $routePrefix = 'backend')
    {
        $this->routePrefix = $routePrefix;

        if ($lesson) {
            $this->lesson = $lesson;
            $this->title = $lesson->title ?? '';
            $this->slug = $lesson->slug ?? '';
            $this->content = $lesson->content ?? '';
            $this->description = $lesson->description ?? null;
            $this->status = $lesson->status?->value ?? 'draft';
            $this->published_at = $lesson->published_at?->toDateString();
            $this->order = $lesson->order ?? 0;
            $this->teacher_id = $lesson->teacher_id ?? null;
            $this->student_ids = $lesson->students->pluck('id')->toArray();
        }
    }

    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lessons', 'slug')->ignore($this->lesson?->id),
            ],
            'content' => 'required|string',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer|min:0',
            'teacher_id' => 'nullable|exists:users,id',
            'file_path' => 'nullable|file|max:102400',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ];

        $this->validate($rules);

        if ($this->lesson && $this->lesson->id) {
            $data = [
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'description' => $this->description,
                'status' => $this->status,
                'published_at' => $this->published_at,
                'order' => $this->order,
                'teacher_id' => $this->teacher_id,
            ];

            if ($this->file_path) {
                $data['file_path'] = $this->file_path->store('lessons', 'public');
                if ($this->lesson->file_path) {
                    \Storage::disk('public')->delete($this->lesson->file_path);
                }
            }

            $this->lesson->update($data);

            if (!empty($this->student_ids)) {
                $this->lesson->students()->sync($this->student_ids);
            }

            $message = 'Lesson updated successfully.';
        } else {
            $data = [
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'description' => $this->description,
                'status' => $this->status,
                'published_at' => $this->published_at,
                'order' => $this->order,
                'teacher_id' => $this->teacher_id,
            ];

            if ($this->file_path) {
                $data['file_path'] = $this->file_path->store('lessons', 'public');
            }

            $lesson = Lesson::create($data);

            if (!empty($this->student_ids)) {
                $lesson->students()->sync($this->student_ids);
            }

            $message = 'Lesson created successfully.';
        }

        $this->dispatch('notify', message: $message);
    }

    public function render()
    {
        return view('livewire.backend.lessons.lesson-form', [
            'statuses' => LessonStatus::cases(),
            'teachers' => User::query()->whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            })->get(),
            'students' => User::query()->whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })->get(),
        ]);
    }
}
