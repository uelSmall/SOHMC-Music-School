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
        'user_id',
        'body',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(LessonStudentAssignment::class, 'lesson_student_assignment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Legacy accessor — maps old 'teacher' references to 'user'.
     */
    public function getTeacherAttribute(): User
    {
        return $this->user;
    }
}
