<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const CURRENCY = 'TT$';

    protected $fillable = [
        'student_id',
        'receipt_number',
        'receipt_token',
        'amount',
        'payment_method',
        'reference',
        'payment_date',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id')->withTrashed();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function getAmountFormattedAttribute(): string
    {
        return self::CURRENCY.number_format((float) $this->amount, 2);
    }

    public static function nextReceiptNumber(): string
    {
        $last = (int) self::query()->whereYear('created_at', now()->year)->count();

        return 'SOHMC-'.now()->year.'-'.str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
    }
}