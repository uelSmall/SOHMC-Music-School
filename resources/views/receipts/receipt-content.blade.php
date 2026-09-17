<style>
    /* Receipt styles — scoped under .receipt-doc so they are safe both inside
       the standalone PDF page and embedded in the admin preview. */
    .receipt-doc { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; font-size: 13px; line-height: 1.5; }
    .receipt-doc * { box-sizing: border-box; }
    .receipt-doc .wrap { max-width: 720px; margin: 0 auto; padding: 10px; }
    .receipt-doc .brand { display: flex; align-items: center; gap: 12px; border-bottom: 3px solid #A6128D; padding-bottom: 16px; }
    .receipt-doc .brand-text { flex: 1; }
    .receipt-doc .brand-name { color: #A6128D; font-size: 22px; font-weight: 800; letter-spacing: 0.5px; margin: 0; }
    .receipt-doc .brand-sub { color: #6b7280; font-size: 12px; margin: 2px 0 0; }
    .receipt-doc .receipt-label { text-align: right; }
    .receipt-doc .receipt-label h2 { color: #1f2937; font-size: 18px; margin: 0 0 4px; text-transform: uppercase; letter-spacing: 2px; }
    .receipt-doc .receipt-label span { color: #9ca3af; font-size: 12px; }
    .receipt-doc .meta { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12.5px; }
    .receipt-doc .meta td { padding: 4px 0; }
    .receipt-doc .meta td:last-child { text-align: right; font-weight: 600; }
    .receipt-doc .amount-box { margin-top: 24px; background: #FAF5FB; border: 1px solid #E8D5F0; border-radius: 10px; padding: 18px 22px; }
    .receipt-doc .amount-line { display: flex; justify-content: space-between; align-items: center; }
    .receipt-doc .amount-line .label { font-size: 14px; color: #6b7280; }
    .receipt-doc .amount-line .value { color: #A6128D; font-size: 30px; font-weight: 800; }
    .receipt-doc table.details { width: 100%; border-collapse: collapse; margin-top: 24px; }
    .receipt-doc table.details th, .receipt-doc table.details td { border: 1px solid #e5e7eb; padding: 10px 12px; text-align: left; font-size: 12.5px; }
    .receipt-doc table.details th { background: #f9fafb; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-size: 11px; }
    .receipt-doc .notes { margin-top: 20px; color: #4b5563; font-size: 12.5px; white-space: pre-wrap; }
    .receipt-doc .footer { margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 14px; text-align: center; color: #9ca3af; font-size: 11.5px; }
    .receipt-doc .footer b { color: #A6128D; }

    /* Mobile / narrow screens. dompdf renders at page width, so these rules
       never apply to the PDF — only to the on-screen preview and public page. */
    @media screen and (max-width: 600px) {
        .receipt-doc .wrap { padding: 4px; }
        .receipt-doc .brand { flex-direction: column; align-items: flex-start; gap: 10px; padding-bottom: 12px; }
        .receipt-doc .brand img { width: 130px !important; }
        .receipt-doc .brand-name { font-size: 18px; }
        .receipt-doc .receipt-label { text-align: left; }
        .receipt-doc .receipt-label h2 { font-size: 15px; }
        .receipt-doc .meta { font-size: 12px; }
        .receipt-doc .amount-box { padding: 14px 16px; }
        .receipt-doc .amount-line .value { font-size: 26px; }
        .receipt-doc table.details th, .receipt-doc table.details td { padding: 8px; font-size: 11.5px; }
        .receipt-doc .footer { margin-top: 26px; }
    }
</style>

<div class="receipt-doc">
    <div class="wrap">
        <div class="brand">
            <div>
                <img src="{{ $logoBase64 }}" alt="Sounds of Harmony Music Centre" style="width: 170px; height: auto; object-fit: contain;" />
            </div>
            <div class="brand-text">
                <p class="brand-name">Sounds of Harmony Music Centre</p>
                <p class="brand-sub">S.O.H.M.C — Learning music, living harmony</p>
            </div>
            <div class="receipt-label">
                <h2>Receipt</h2>
                <span>{{ $payment->receipt_number }}</span>
            </div>
        </div>

        <table class="meta">
            <tr>
                <td>Receipt No.</td>
                <td>{{ $payment->receipt_number }}</td>
            </tr>
            <tr>
                <td>Date Issued</td>
                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>{{ $payment->payment_method }}</td>
            </tr>
            @if($payment->reference)
                <tr>
                    <td>Reference</td>
                    <td>{{ $payment->reference }}</td>
                </tr>
            @endif
            <tr>
                <td>Student</td>
                <td>{{ $payment->student->name }} @if($payment->student->student_number)({{ $payment->student->student_number }})@endif</td>
            </tr>
        </table>

        <div class="amount-box">
            <div class="amount-line">
                <span class="label">Amount Paid</span>
                <span class="value">{{ $payment->amount_formatted }}</span>
            </div>
            @if($payment->notes)
                <p class="notes" style="margin: 10px 0 0;">{{ $payment->notes }}</p>
            @endif
        </div>

        <table class="details">
            <tr>
                <th>Description</th>
                <th>Date</th>
                <th style="text-align:right;">Amount</th>
            </tr>
            <tr>
                <td>Music education payment</td>
                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                <td style="text-align:right; font-weight:700;">{{ $payment->amount_formatted }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Thank you for your support of Sounds of Harmony Music Centre!<br>
            For any questions regarding this receipt, email <b>info@sohmc.com</b>.</p>
        </div>
    </div>
</div>