<?php

namespace App\Support\Calendar;

class LessonCalendarColorResolver
{
    public static function forStatus(string $status): array
    {
        return match ($status) {
            'scheduled', 'teacher_confirmed' => [
                'backgroundColor' => '#DBEAFE',
                'borderColor' => '#2563EB',
                'textColor' => '#1E3A8A',
            ],
            'completed', 'student_accepted' => [
                'backgroundColor' => '#DCFCE7',
                'borderColor' => '#16A34A',
                'textColor' => '#14532D',
            ],
            'cancelled', 'student_declined' => [
                'backgroundColor' => '#FEE2E2',
                'borderColor' => '#DC2626',
                'textColor' => '#7F1D1D',
            ],
            'pending' => [
                'backgroundColor' => '#FEF3C7',
                'borderColor' => '#D97706',
                'textColor' => '#78350F',
            ],
            'teacher_rescheduled' => [
                'backgroundColor' => '#F3E8FF',
                'borderColor' => '#7E22CE',
                'textColor' => '#581C87',
            ],
            default => [
                'backgroundColor' => '#E5E7EB',
                'borderColor' => '#6B7280',
                'textColor' => '#111827',
            ],
        };
    }
}