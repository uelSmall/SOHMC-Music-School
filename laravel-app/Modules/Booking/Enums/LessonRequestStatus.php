<?php

namespace Modules\Booking\Enums;

enum LessonRequestStatus: string
{
    case Pending = 'pending';
    case TeacherConfirmed = 'teacher_confirmed';
    case TeacherRescheduled = 'teacher_rescheduled';
    case StudentAccepted = 'student_accepted';
    case StudentDeclined = 'student_declined';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::TeacherConfirmed => 'Teacher Confirmed',
            self::TeacherRescheduled => 'Teacher Rescheduled',
            self::StudentAccepted => 'Student Accepted',
            self::StudentDeclined => 'Student Declined',
            self::Cancelled => 'Cancelled',
        };
    }
}