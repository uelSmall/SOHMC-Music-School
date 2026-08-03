<?php

namespace App\Livewire\Backend\Lessons;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Throwable;
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
    public bool $isTeacher = false;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $content = '';

    #[Validate('nullable|string|max:500')]
    public ?string $description = null;

    #[Validate('nullable|string|max:5000')]
    public ?string $global_note = null;

    #[Validate('nullable|string|max:255')]
    public ?string $instrument = null;

    #[Validate('required|in:draft,published,archived')]
    public string $status = 'draft';

    #[Validate('nullable|date')]
    public ?string $published_at = null;

    #[Validate('nullable|integer|min:1')]
    public ?int $order = null;

    #[Validate('nullable|exists:users,id')]
    public ?int $teacher_id = null;

    #[Validate('nullable|file|max:102400')]
    public $file_path = null;

    #[Validate('nullable|array')]
    public array $student_ids = [];

    public function mount(?Lesson $lesson = null, string $routePrefix = 'backend')
    {
        $user = $this->currentUser();

        $this->routePrefix = $routePrefix;
        $this->isTeacher = $user->hasRole('teacher');

        if ($lesson && $lesson->exists) {
            if ($this->isTeacher && (int) $lesson->teacher_id !== (int) $user->id) {
                abort(403);
            }

            $this->lesson = $lesson;
            $this->title = $lesson->title ?? '';
            $this->content = $lesson->content ?? '';
            $this->description = $lesson->description ?? null;
            $this->global_note = $lesson->global_note ?? null;
            $this->instrument = $lesson->instrument;
            $this->status = $lesson->status?->value ?? 'draft';
            $this->published_at = $lesson->published_at?->toDateString();
            $this->order = $lesson->order;
            $this->teacher_id = $this->isTeacher ? $user->id : ($lesson->teacher_id ?? null);
            $this->student_ids = $lesson->students->pluck('id')->toArray();
        } elseif ($this->isTeacher) {
            $this->teacher_id = $user->id;
        }
    }

    public function save()
    {
        $user = $this->currentUser();

        if ($this->isTeacher && $this->lesson && $this->lesson->exists && (int) $this->lesson->teacher_id !== (int) $user->id) {
            abort(403);
        }

        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'nullable|string|max:500',
            'global_note' => 'nullable|string|max:5000',
            'instrument' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer|min:1',
            'teacher_id' => 'nullable|exists:users,id',
            'file_path' => 'nullable|file|max:102400',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ];

        $this->validate($rules);

        if ($this->isTeacher) {
            $this->teacher_id = $user->id;
        }

        $targetOrder = $this->prepareOrderForSave(
            $this->order,
            $this->teacher_id,
            $this->lesson
        );

        try {
            if ($this->lesson && $this->lesson->id) {
                $data = [
                    'title' => $this->title,
                    'slug' => $this->generateUniqueSlug($this->title, $this->lesson->id),
                    'content' => $this->content,
                    'description' => $this->description,
                    'global_note' => $this->global_note,
                    'instrument' => $this->instrument,
                    'status' => $this->status,
                    'published_at' => $this->published_at,
                    'order' => $targetOrder,
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
                    'slug' => $this->generateUniqueSlug($this->title),
                    'content' => $this->content,
                    'description' => $this->description,
                    'global_note' => $this->global_note,
                    'instrument' => $this->instrument,
                    'status' => $this->status,
                    'published_at' => $this->published_at,
                    'order' => $targetOrder,
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

            session()->flash('notify', [
                'message' => $message,
                'type' => 'success',
            ]);

            $this->redirectRoute($this->routePrefix.'.lessons.index', navigate: true);

            return;
        } catch (Throwable $exception) {
            report($exception);

            $this->dispatch('notify', message: 'Could not save the lesson. Please try again.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.lessons.lesson-form', [
            'statuses' => LessonStatus::cases(),
            'instrumentOptions' => $this->instrumentOptions(),
            'teachers' => User::query()->whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            })->when($this->isTeacher, function ($query) {
                $query->whereKey($this->currentUser()->id);
            })->get(),
            'students' => User::query()->whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })->get(),
        ]);
    }

    private function instrumentOptions(): array
    {
        return [
            'Piano',
            'Guitar',
            'Saxophone',
            'Voice / Singing',
            'Violin',
            'Keyboard',
            'Steelpan',
            'Music Theory',
        ];
    }

    private function currentUser(): User
    {
        return auth()->user();
    }

    private function generateUniqueSlug(string $title, ?int $ignoreLessonId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Lesson::query()
                ->when($ignoreLessonId, fn ($query) => $query->whereKeyNot($ignoreLessonId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function prepareOrderForSave(?int $requestedOrder, ?int $teacherId, ?Lesson $existingLesson = null): int
    {
        return DB::transaction(function () use ($requestedOrder, $teacherId, $existingLesson) {
            $existingLessonId = $existingLesson?->id;

            $scopeQuery = Lesson::query()
                ->when(
                    $teacherId,
                    fn ($query) => $query->where('teacher_id', $teacherId),
                    fn ($query) => $query->whereNull('teacher_id')
                );

            if ($existingLessonId) {
                $scopeQuery->whereKeyNot($existingLessonId);
            }

            $maxOrder = (int) $scopeQuery->max('order');
            $normalizedOrder = $requestedOrder && $requestedOrder > 0
                ? min($requestedOrder, $maxOrder + 1)
                : $maxOrder + 1;

            if (! $existingLessonId) {
                Lesson::query()
                    ->when(
                        $teacherId,
                        fn ($query) => $query->where('teacher_id', $teacherId),
                        fn ($query) => $query->whereNull('teacher_id')
                    )
                    ->where('order', '>=', $normalizedOrder)
                    ->increment('order');

                return $normalizedOrder;
            }

            $previousTeacherId = $existingLesson?->teacher_id;
            $previousOrder = (int) ($existingLesson?->order ?? 0);

            if ($previousTeacherId === $teacherId && $previousOrder === $normalizedOrder) {
                return $previousOrder;
            }

            if ($previousTeacherId === $teacherId) {
                if ($normalizedOrder < $previousOrder) {
                    Lesson::query()
                        ->when(
                            $teacherId,
                            fn ($query) => $query->where('teacher_id', $teacherId),
                            fn ($query) => $query->whereNull('teacher_id')
                        )
                        ->whereBetween('order', [$normalizedOrder, $previousOrder - 1])
                        ->increment('order');
                } elseif ($normalizedOrder > $previousOrder) {
                    Lesson::query()
                        ->when(
                            $teacherId,
                            fn ($query) => $query->where('teacher_id', $teacherId),
                            fn ($query) => $query->whereNull('teacher_id')
                        )
                        ->whereBetween('order', [$previousOrder + 1, $normalizedOrder])
                        ->decrement('order');
                }

                return $normalizedOrder;
            }

            Lesson::query()
                ->when(
                    $previousTeacherId,
                    fn ($query) => $query->where('teacher_id', $previousTeacherId),
                    fn ($query) => $query->whereNull('teacher_id')
                )
                ->where('order', '>', $previousOrder)
                ->decrement('order');

            Lesson::query()
                ->when(
                    $teacherId,
                    fn ($query) => $query->where('teacher_id', $teacherId),
                    fn ($query) => $query->whereNull('teacher_id')
                )
                ->where('order', '>=', $normalizedOrder)
                ->increment('order');

            return $normalizedOrder;
        });
    }

}
