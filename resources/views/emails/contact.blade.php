<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>New Contact Message</title>
</head>
<body style="margin:0; padding:24px; background-color:#edeef0; font-family:Arial, Helvetica, sans-serif; color:#0a0a29;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:0 auto; background-color:#ffffff; border-radius:8px; overflow:hidden;">
    <tr>
        <td style="background-color:#000156; padding:20px 24px;">
            <h1 style="margin:0; font-size:20px; color:#f4b61b;">OJT Monitoring System</h1>
            <p style="margin:4px 0 0; font-size:13px; color:#ffffff;">New message from the website contact form</p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px;">
            <p style="margin:0 0 6px; font-size:12px; font-weight:bold; color:#000156; text-transform:uppercase;">Full Name</p>
            <p style="margin:0 0 18px; font-size:15px;">{{ $senderName }}</p>

            <p style="margin:0 0 6px; font-size:12px; font-weight:bold; color:#000156; text-transform:uppercase;">Email Address</p>
            <p style="margin:0 0 18px; font-size:15px;">
                <a href="mailto:{{ $senderEmail }}" style="color:#000156;">{{ $senderEmail }}</a>
            </p>

            <p style="margin:0 0 6px; font-size:12px; font-weight:bold; color:#000156; text-transform:uppercase;">Subject</p>
            <p style="margin:0 0 18px; font-size:15px;">{{ $subjectLine }}</p>

            <p style="margin:0 0 6px; font-size:12px; font-weight:bold; color:#000156; text-transform:uppercase;">Message</p>
            <div style="margin:0; padding:14px; background-color:#edeef0; border-left:4px solid #f4b61b; font-size:15px; line-height:1.6; white-space:pre-wrap;">{{ $messageBody }}</div>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 24px; background-color:#edeef0; font-size:12px; color:#555;">
            Reply directly to this email to respond to {{ $senderName }}.
        </td>
    </tr>
</table>

</body>
</html>
