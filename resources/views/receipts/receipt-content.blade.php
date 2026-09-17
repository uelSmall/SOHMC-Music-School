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