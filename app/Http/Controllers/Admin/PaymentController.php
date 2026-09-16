<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('student')->latest();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($qq) => $qq->where('name', 'like', "%{$search}%"));
            });
        }

        return view('admin.payments.index', [
            'payments' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.payments.create', [
            'students' => User::whereHas('roles', fn ($q) => $q->where('name', 'student'))
                ->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:1000000'],
            'payment_method' => ['required', 'in:Cash,Card,Bank Transfer,Online Transfer,Other'],
            'reference' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $payment = Payment::create([
            'student_id' => $validated['student_id'],
            'receipt_number' => Payment::nextReceiptNumber(),
            'receipt_token' => Str::random(32),
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference' => $validated['reference'] ?? null,
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        $email = $payment->student()->value('email');

        if ($email) {
            Mail::to($email)->send(new PaymentReceiptMail($payment));
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('status', "Payment recorded — receipt {$payment->receipt_number} created and emailed to the student.");
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', [
            'payment' => $payment->load('student', 'creator'),
        ]);
    }

    public function download(Payment $payment)
    {
        $pdf = Pdf::loadView('receipts.receipt', [
            'payment' => $payment,
            'logoBase64' => $this->logoBase64(),
        ]);

        return $pdf->download($payment->receipt_number.'.pdf');
    }

    public function resend(Payment $payment)
    {
        $email = $payment->student()->value('email');

        if (! $email) {
            return back()->withErrors(['error' => 'This student has no email address on file.']);
        }

        Mail::to($email)->send(new PaymentReceiptMail($payment));

        return back()->with('status', "Receipt {$payment->receipt_number} re-sent to {$email}.");
    }

    protected function logoBase64(): string
    {
        $path = public_path('img/sohmc-piano-icon.png');

        if (file_exists($path)) {
            $mime = mime_content_type($path);

            return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
        }

        return '';
    }
}