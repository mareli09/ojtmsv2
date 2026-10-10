<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset your OJTMS password</title>
</head>
<body style="margin:0; padding:24px; background-color:#edeef0; font-family:Arial, Helvetica, sans-serif; color:#0a0a29;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:0 auto; background-color:#ffffff; border-radius:8px; overflow:hidden;">
    <tr>
        <td style="background-color:#000156; padding:20px 24px;">
            <h1 style="margin:0; font-size:20px; color:#f4b61b;">OJT Monitoring System</h1>
            <p style="margin:4px 0 0; font-size:13px; color:#ffffff;">Password reset request</p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px;">
            <p style="margin:0 0 14px; font-size:15px;">Hello {{ $name }},</p>

            <p style="margin:0 0 20px; font-size:15px; line-height:1.6;">
                We received a request to reset the password for your OJTMS account.
                Click the button below to choose a new one.
            </p>

            <p style="margin:0 0 24px;">
                <a href="{{ $resetUrl }}"
                   style="display:inline-block; padding:12px 24px; background-color:#000156; color:#f4b61b; font-weight:bold; font-size:15px; text-decoration:none; border-radius:5px;">
                    Reset Password
                </a>
            </p>

            <p style="margin:0 0 14px; font-size:13px; color:#555; line-height:1.6;">
                This link expires in {{ $expiresInMinutes }} minutes and can only be used once.
                If you did not request a password reset, you can safely ignore this email &mdash;
                your password will stay the same.
            </p>

            <p style="margin:0; font-size:12px; color:#777; line-height:1.6; word-break:break-all;">
                If the button does not work, copy this link into your browser:<br>
                {{ $resetUrl }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 24px; background-color:#edeef0; font-size:12px; color:#555;">
            This is an automated message from the OJT Monitoring System.
        </td>
    </tr>
</table>

</body>
</html>
