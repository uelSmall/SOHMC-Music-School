<?php

namespace Modules\Booking\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Database\Factories\BookedLessonFactory;
use Modules\Booking\Enums\LessonStatus;

class BookedLesson extends Model
{
    use HasFactory;

    protected $table = 'booked_lessons';

    protected $fillable = [
        'lesson_request_id',
        'student_id',
        'teacher_id',
        'instrument_id',
        'lesson_date',
        'lesson_start_time',
        'lesson_end_time',
        'lesson_duration',
        'status',
        'completed_at',
        'cancelled_at',
        'rescheduled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'lesson_date' => 'date',
            'lesson_duration' => 'integer',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'rescheduled_at' => 'datetime',
            'status' => LessonStatus::class,
        ];
    }

    protected static function newFactory(): Factory
    {
        return BookedLessonFactory::new();
    }

    public function lessonRequest(): BelongsTo
    {
        return $this->belongsTo(LessonRequest::class, 'lesson_request_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class, 'instrument_id');
    }
}