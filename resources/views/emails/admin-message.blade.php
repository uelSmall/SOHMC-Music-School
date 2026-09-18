<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#ffffff; padding:26px 32px; border-bottom:3px solid #A6128D;">
                            <img src="{{ asset('img/sohmc-nav-logo.png') }}" alt="Sounds of Harmony Music Centre" style="display:block; width:170px; height:auto; margin-bottom:6px;" />
                            <p style="margin:0; color:#6b7280; font-size:13px;">S.O.H.M.C — Creating harmony through expression</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:30px 32px;">
                            <p style="margin:0 0 16px; font-size:15px; color:#1f2937; line-height:1.6;">Dear <b>{{ $recipientName }}</b>,</p>

                            <p style="margin:0 0 16px; font-size:13px; color:#6b7280; line-height:1.6;">You have a new message from <b>{{ $from }}</b>:</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:10px; overflow:hidden;">
                                <tr>
                                    <td style="background-color:#faf5ff; padding:14px 18px; border-bottom:1px solid #e5e7eb;">
                                        <p style="margin:0; font-size:14px; font-weight:bold; color:#1f2937;">{{ $title }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:18px; font-size:14px; color:#374151; line-height:1.7;">
                                        {!! nl2br(e($message)) !!}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; font-size:13px; color:#9ca3af; line-height:1.5;">Log in to view this message and any other updates in your notification centre.</p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f9fafb; padding:20px 32px; border-top:1px solid #f3f4f6;">
                            <p style="margin:0; font-size:12px; color:#9ca3af; text-align:center;">
                                Sounds of Harmony Music Centre · <a href="mailto:{{ config('mail.from.address') }}" style="color:#A6128D; text-decoration:none;">{{ config('mail.from.address') }}</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>