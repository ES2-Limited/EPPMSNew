<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #e9ecef; margin: 0; padding: 40px 0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #e9ecef;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="text-align: left;">
                            <h2 style="color: #333333;">Hello, {{ $name }} 👋</h2>
                            <p style="color: #555555; font-size: 16px;">
                                Thanks for registering! Please verify your email address by clicking the button below:
                            </p>
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $verificationUrl }}" style="background-color: #0d6efd; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                                    Verify Email
                                </a>
                            </div>
                            <p style="color: #888888; font-size: 14px;">
                                If this account creation isn't for you, no further action is required.
                            </p>
                            <p style="color: #888888; font-size: 14px;">
                                Regards,<br> {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
