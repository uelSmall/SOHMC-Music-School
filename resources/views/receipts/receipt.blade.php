<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $payment->receipt_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page { margin: 36px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; margin: 0; font-size: 13px; line-height: 1.5; }
        .wrap { max-width: 720px; margin: 0 auto; padding: 10px; }
        .brand { display: flex; align-items: center; gap: 12px; border-bottom: 3px solid #A6128D; padding-bottom: 16px; }
        .brand-text { flex: 1; }
        .brand-name { color: #A6128D; font-size: 22px; font-weight: 800; letter-spacing: 0.5px; margin: 0; }
        .brand-sub { color: #6b7280; font-size: 12px; margin: 2px 0 0; }
        .receipt-label { text-align: right; }
        .receipt-label h2 { color: #1f2937; font-size: 18px; margin: 0 0 4px; text-transform: uppercase; letter-spacing: 2px; }
        .receipt-label span { color: #9ca3af; font-size: 12px; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12.5px; }
        .meta td { padding: 4px 0; }
        .meta td:last-child { text-align: right; font-weight: 600; }
        .amount-box { margin-top: 24px; background: #FAF5FB; border: 1px solid #E8D5F0; border-radius: 10px; padding: 18px 22px; }
        .amount-line { display: flex; justify-content: space-between; align-items: center; }
        .amount-line .label { font-size: 14px; color: #6b7280; }
        .amount-line .value { color: #A6128D; font-size: 30px; font-weight: 800; }
        table.details { width: 100%; border-collapse: collapse; margin-top: 24px; }
        table.details th, table.details td { border: 1px solid #e5e7eb; padding: 10px 12px; text-align: left; font-size: 12.5px; }
        table.details th { background: #f9fafb; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-size: 11px; }
        .notes { margin-top: 20px; color: #4b5563; font-size: 12.5px; white-space: pre-wrap; }
        .footer { margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 14px; text-align: center; color: #9ca3af; font-size: 11.5px; }
        .footer b { color: #A6128D; }
    </style>
</head>
<body>
    @include('receipts.receipt-content')
</body>
</html>