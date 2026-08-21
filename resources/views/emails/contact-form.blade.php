<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { background: #A6128D; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .body { background: #f9f9f9; padding: 20px; border: 1px solid #eee; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 12px; }
        .label { font-weight: bold; color: #555; }
        .value { margin-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">New Contact Form Message</h2>
    </div>
    <div class="body">
        <div class="field">
            <div class="label">Name</div>
            <div class="value">{{ $data['name'] }}</div>
        </div>
        <div class="field">
            <div class="label">Email</div>
            <div class="value"><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></div>
        </div>
        @if(!empty($data['phone']))
        <div class="field">
            <div class="label">Phone</div>
            <div class="value">{{ $data['phone'] }}</div>
        </div>
        @endif
        <div class="field">
            <div class="label">Subject</div>
            <div class="value">{{ $data['subject'] }}</div>
        </div>
        <div class="field">
            <div class="label">Message</div>
            <div class="value">{{ $data['message'] }}</div>
        </div>
    </div>
</body>
</html>
