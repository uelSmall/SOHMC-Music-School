<?php

namespace Modules\Lesson\Enums;

enum AssignmentStatus: string
{
    case Assigned = 'assigned';
    case Started = 'started';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Assigned => 'Assigned',
            self::Started => 'Started',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Assigned => 'gray',
            self::Started => 'blue',
            self::InProgress => 'yellow',
            self::Completed => 'green',
        };
    }
}
