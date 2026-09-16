<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payment Receipt {{ $payment->receipt_number }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#A6128D; padding:26px 32px;">
                            <p style="margin:0; color:#ffffff; font-size:20px; font-weight:bold;">Sounds of Harmony Music Centre</p>
                            <p style="margin:4px 0 0; color:#E8D5F0; font-size:13px;">Learning music, living harmony</p>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding:30px 32px;">
                            <p style="margin:0 0 16px; font-size:15px; color:#1f2937; line-height:1.6;">Dear <b>{{ $payment->student->name }}</b>,<br>Thank you for your payment! Your official receipt is below, and a PDF copy is attached to this email.</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:10px;">
                                <tr>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#6b7280;">Receipt No.</td>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; font-weight:bold; color:#1f2937; text-align:right;">{{ $payment->receipt_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#6b7280;">Date</td>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#1f2937; text-align:right;">{{ $payment->payment_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#6b7280;">Method</td>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#1f2937; text-align:right;">{{ $payment->payment_method }}</td>
                                </tr>
                                @if($payment->reference)
                                <tr>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#6b7280;">Reference</td>
                                    <td style="padding:12px 18px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#1f2937; text-align:right;">{{ $payment->reference }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:12px 18px; font-size:13px; color:#6b7280;">Amount Paid</td>
                                    <td style="padding:12px 18px; font-size:17px; font-weight:bold; color:#A6128D; text-align:right;">{{ $payment->amount_formatted }}</td>
                                </tr>
                            </table>

                            <p style="margin:26px 0 10px; font-size:13px; color:#6b7280;">You can also view this receipt on your phone:</p>
                            <p style="margin:0; text-align:center;">
                                <a href="{{ $receiptUrl }}" style="display:inline-block; background-color:#A6128D; color:#ffffff; text-decoration:none; padding:13px 28px; border-radius:10px; font-size:14px; font-weight:bold;">View Receipt</a>
                            </p>
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px; background-color:#f9fafb; border-top:1px solid #f3f4f6; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#9ca3af;">For questions about this receipt, email <a href="mailto:info@sohmc.com" style="color:#A6128D;">info@sohmc.com</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>