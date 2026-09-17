<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $payment->receipt_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page { margin: 36px; }
        body { margin: 0; }
    </style>
</head>
<body>
    @include('receipts.receipt-content')
</body>
</html>