<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugReport extends Model
{
    use HasFactory;

    public const STATUS_REPORTED = 'reported';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_WONT_FIX = 'wont_fix';

    public const STATUSES = [
        self::STATUS_REPORTED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_RESOLVED,
        self::STATUS_WONT_FIX,
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'page',
        'browser',
        'status',
        'admin_notes',
        'handled_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_REPORTED => 'Reported',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_WONT_FIX => "Won't Fix",
            default => ucwords(str_replace('_', ' ', $this->status ?? '')),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_REPORTED => 'bg-red-100 text-red-700',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-700',
            self::STATUS_RESOLVED => 'bg-green-100 text-green-700',
            self::STATUS_WONT_FIX => 'bg-gray-100 text-gray-600',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}