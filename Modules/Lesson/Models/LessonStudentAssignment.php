<?php

namespace Modules\Lesson\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Modules\Lesson\Enums\AssignmentStatus;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LessonStudentAssignment extends Model
{
    use LogsActivity;

    protected $table = 'lesson_student_assignments';

    protected $fillable = [
        'lesson_id',
        'student_id',
        'assigned_at',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => AssignmentStatus::class,
            'assigned_at' => 'datetime',
            'due_date' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName($this->table);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function scopeCompletedByStudent($query, User $student): void
    {
        $query->where('student_id', $student->id)
            ->where('status', AssignmentStatus::Completed->value);
    }

    public function scopeAssignedToStudent($query, User $student): void
    {
        $query->where('student_id', $student->id)
            ->whereIn('status', [
                AssignmentStatus::Assigned->value,
                AssignmentStatus::Started->value,
                AssignmentStatus::InProgress->value,
            ]);
    }
}
