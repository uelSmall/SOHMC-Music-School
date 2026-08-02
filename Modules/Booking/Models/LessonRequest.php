<?php

namespace Modules\Booking\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Database\Factories\LessonRequestFactory;
use Modules\Booking\Enums\LessonRequestStatus;

class LessonRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'instrument_id',
        'requested_date',
        'requested_start_time',
        'requested_end_time',
        'lesson_duration',
        'suggested_date',
        'suggested_start_time',
        'suggested_end_time',
        'status',
        'student_note',
        'teacher_note',
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'suggested_date' => 'date',
            'lesson_duration' => 'integer',
            'status' => LessonRequestStatus::class,
        ];
    }

    protected static function newFactory(): Factory
    {
        return LessonRequestFactory::new();
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

    public function lesson(): HasOne
    {
        return $this->hasOne(BookedLesson::class, 'lesson_request_id');
    }
}