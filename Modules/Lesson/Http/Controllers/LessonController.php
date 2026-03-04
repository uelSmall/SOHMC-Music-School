<?php

namespace Modules\Lesson\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Lesson\Http\Requests\StoreLessonRequest;
use Modules\Lesson\Http\Requests\UpdateLessonRequest;
use Modules\Lesson\Models\Lesson;

class LessonController extends Controller
{
    public function index()
    {
        return view('backend.lessons.index', [
            'routePrefix' => $this->resolveRoutePrefix(),
        ]);
    }

    public function create()
    {
        return view('backend.lessons.create', [
            'routePrefix' => $this->resolveRoutePrefix(),
        ]);
    }

    public function store(StoreLessonRequest $request)
    {
        $data = $request->validated();

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
        $routePrefix = $this->resolveRoutePrefix();

        return view('backend.lessons.show', compact('lesson', 'routePrefix'));
    }

    public function edit(Lesson $lesson)
    {
        $routePrefix = $this->resolveRoutePrefix();

        return view('backend.lessons.edit', compact('lesson', 'routePrefix'));
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $data = $request->validated();

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
}
