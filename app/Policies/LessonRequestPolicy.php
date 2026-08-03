<?php

namespace App\Policies;

use App\Models\User;
use Modules\Booking\Models\LessonRequest;

class LessonRequestPolicy
{
    public function view(User $user, LessonRequest $lessonRequest): bool
    {
        return $user->hasRole('teacher') && (int) $lessonRequest->teacher_id === (int) $user->id;
    }

    public function update(User $user, LessonRequest $lessonRequest): bool
    {
        return $this->view($user, $lessonRequest);
    }
}