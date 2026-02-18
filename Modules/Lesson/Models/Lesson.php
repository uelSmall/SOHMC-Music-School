<?php

namespace Modules\Lesson\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Lesson\Database\Factories\LessonFactory;
use Modules\Lesson\Enums\LessonStatus;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lesson extends BaseModel
{
    use HasFactory;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'lessons';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'description',
        'status',
        'published_at',
        'order',
        'teacher_id',
        'file_path',
        'created_by',
        'updated_by',
    ];

    protected static function newFactory()
    {
        return LessonFactory::new();
    }

    protected function casts(): array
    {
        return [
            'status' => LessonStatus::class,
            'published_at' => 'datetime',
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

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_student', 'lesson_id', 'student_id')
            ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', LessonStatus::Published->value);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
