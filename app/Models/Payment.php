<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    /**
     * Next receipt number, derived from the Postgres id sequence.
     *
     * We deliberately read the sequence instead of counting rows: a hard
     * delete would otherwise cause the next receipt to reuse a number that
     * may already have been emailed out. The sequence never rolls back, so
     * numbers are never reissued (gaps after deletes are normal).
     */
    public static function nextReceiptNumber(): string
    {
        $seq = DB::selectOne('SELECT last_value, is_called FROM payments_id_seq');

        $nextId = $seq->is_called ? $seq->last_value + 1 : $seq->last_value;

        return 'SOHMC-'.now()->year.'-'.str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * wa.me share URL for this receipt. Prefills the student's mobile number
     * when one is on file (international format, no +); otherwise opens
     * WhatsApp with the message ready so the sender picks the contact.
     */
    public function whatsappShareUrl(): string
    {
        $link = route('frontend.receipts.view', $this->receipt_token);

        $message = 'Sounds of Harmony Music Centre — Receipt '.$this->receipt_number
            .'. View or download your receipt here: '.$link;

        $phone = (string) ($this->student->mobile ?? '');
        $phone = ltrim(preg_replace('/[^0-9]/', '', $phone), '0');

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}