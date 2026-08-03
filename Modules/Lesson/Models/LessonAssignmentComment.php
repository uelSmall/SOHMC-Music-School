<?php

namespace Modules\Lesson\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonAssignmentComment extends Model
{
    protected $table = 'lesson_assignment_comments';

    protected $fillable = [
        'lesson_student_assignment_id',
        'teacher_id',
        'body',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(LessonStudentAssignment::class, 'lesson_student_assignment_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
