<?php

namespace App\Http\Controllers;

use App\Models\Payment;

class ReceiptController extends Controller
{
    public function show(string $token)
    {
        $payment = Payment::query()
            ->where('receipt_token', $token)
            ->with('student')
            ->firstOrFail();

        $logoBase64 = receipt_logo_base64();

        return view('receipts.receipt', compact('payment', 'logoBase64'));
    }
}