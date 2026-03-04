<?php

namespace Modules\Lesson\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Lesson\Http\Requests\StoreLessonRequest;
use Modules\Lesson\Http\Requests\UpdateLessonRequest;
use Modules\Lesson\Models\Lesson;

class LessonController extends Controller
{
    public function index()
    {
        abort_unless($this->canEnterLessonArea(request()->user()), 403);

        return view('backend.lessons.index', [
            'routePrefix' => $this->resolveRoutePrefix(),
        ]);
    }

    public function create()
    {
        abort_unless($this->canEnterLessonArea(request()->user()), 403);

        return view('backend.lessons.create', [
            'routePrefix' => $this->resolveRoutePrefix(),
        ]);
    }

    public function store(StoreLessonRequest $request)
    {
        $user = $request->user();
        abort_unless($this->canEnterLessonArea($user), 403);

        $data = $request->validated();

        if ($this->isTeacher($user)) {
            $data['teacher_id'] = $user->id;
        }

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('lessons', 'public');
        }

        $lesson = Lesson::create($data);

        // Assign students if provided
        if (!empty($data['student_ids'])) {
            $lesson->students()->sync($data['student_ids']);
        }

        return redirect()->route($this->resolveRoutePrefix().'.lessons.index')->with('success', 'Lesson created successfully.');
    }

    public function show(Lesson $lesson)
    {
        abort_unless($this->canAccessLesson(request()->user(), $lesson), 403);

        $routePrefix = $this->resolveRoutePrefix();

        return view('backend.lessons.show', compact('lesson', 'routePrefix'));
    }

    public function edit(Lesson $lesson)
    {
        abort_unless($this->canAccessLesson(request()->user(), $lesson), 403);

        $routePrefix = $this->resolveRoutePrefix();

        return view('backend.lessons.edit', compact('lesson', 'routePrefix'));
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        abort_unless($this->canEnterLessonArea($request->user()), 403);
        abort_unless($this->canAccessLesson($request->user(), $lesson), 403);

        $data = $request->validated();

        if ($this->isTeacher($request->user())) {
            $data['teacher_id'] = $request->user()->id;
        }

        // Handle file upload
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($lesson->file_path) {
                \Storage::disk('public')->delete($lesson->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('lessons', 'public');
        }

        $lesson->update($data);

        // Update student assignments if provided
        if (isset($data['student_ids'])) {
            $lesson->students()->sync($data['student_ids']);
        }

        return redirect()->route($this->resolveRoutePrefix().'.lessons.index')->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson)
    {
        abort_unless($this->canEnterLessonArea(request()->user()), 403);
        abort_unless($this->canAccessLesson(request()->user(), $lesson), 403);

        // Delete associated file
        if ($lesson->file_path) {
            \Storage::disk('public')->delete($lesson->file_path);
        }

        $lesson->delete();

        return redirect()->route($this->resolveRoutePrefix().'.lessons.index')->with('success', 'Lesson deleted successfully.');
    }

    private function resolveRoutePrefix(): string
    {
        return request()->routeIs('teacher.*') ? 'teacher' : 'backend';
    }

    private function canAccessLesson(User $user, Lesson $lesson): bool
    {
        if ($this->canManageAllLessons($user)) {
            return true;
        }

        if ($this->isTeacher($user)) {
            return (int) $lesson->teacher_id === (int) $user->id;
        }

        return false;
    }

    private function canManageAllLessons(User $user): bool
    {
        return $user->hasRole('super admin') || $user->hasRole('administrator');
    }

    private function canEnterLessonArea(User $user): bool
    {
        return $this->canManageAllLessons($user) || $this->isTeacher($user);
    }

    private function isTeacher(User $user): bool
    {
        return $user->hasRole('teacher');
    }
}
