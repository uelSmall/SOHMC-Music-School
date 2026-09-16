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

        $path = public_path('img/sohmc-piano-icon.png');
        $logoBase64 = '';
        if (file_exists($path)) {
            $logoBase64 = 'data:'.mime_content_type($path).';base64,'.base64_encode(file_get_contents($path));
        }

        return view('receipts.receipt', compact('payment', 'logoBase64'));
    }
}