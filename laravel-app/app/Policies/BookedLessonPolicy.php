<?php

namespace App\Policies;

use App\Models\User;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;

class BookedLessonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('teacher') || $user->hasRole('student');
    }

    public function view(User $user, BookedLesson $lesson): bool
    {
        return $this->isTeacherOwner($user, $lesson) || $this->isStudentOwner($user, $lesson);
    }

    public function manage(User $user, BookedLesson $lesson): bool
    {
        return $this->isTeacherOwner($user, $lesson);
    }

    public function complete(User $user, BookedLesson $lesson): bool
    {
        return $this->manage($user, $lesson) && $lesson->status === LessonStatus::Scheduled;
    }

    public function cancel(User $user, BookedLesson $lesson): bool
    {
        return $this->manage($user, $lesson) && $lesson->status === LessonStatus::Scheduled;
    }

    public function reschedule(User $user, BookedLesson $lesson): bool
    {
        return $this->manage($user, $lesson) && $lesson->status === LessonStatus::Scheduled;
    }

    private function isTeacherOwner(User $user, BookedLesson $lesson): bool
    {
        return $user->hasRole('teacher') && (int) $lesson->teacher_id === (int) $user->id;
    }

    private function isStudentOwner(User $user, BookedLesson $lesson): bool
    {
        return $user->hasRole('student') && (int) $lesson->student_id === (int) $user->id;
    }
}