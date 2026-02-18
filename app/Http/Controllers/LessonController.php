<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Lesson\Models\Lesson;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            $lessonsQuery = Lesson::whereHas('students', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        } elseif ($user->hasRole('teacher')) {
            $lessonsQuery = Lesson::where('teacher_id', $user->id);
        } else {
            // administrator or super admin
            $lessonsQuery = Lesson::query();
        }

        $lessons = $lessonsQuery->orderBy('order')->get();

        // Group by instrument if available, otherwise group under "General"
        $lessonsByInstrument = $lessons->groupBy(function ($lesson) {
            $instrument = null;

            if (isset($lesson->instrument) && $lesson->instrument) {
                $instrument = (string) $lesson->instrument;
            }

            return Str::lower($instrument ?? 'general');
        });

        return view('lessons.index', compact('lessonsByInstrument'));
    }
}
