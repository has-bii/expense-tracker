<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; padding: 20px; color: #333;">
    <h2>Password Reset Request</h2>
    <p>You requested a password reset. Click the link below:</p>
    <a href="{{ config('app.frontend_url') }}/reset-password?token={{ $token }}&email={{ $email }}">
        Click here to reset your password
    </a>
    <p>This token expires in <strong>60 minutes</strong>.</p>
    <p>If you did not request this, you can safely ignore this email.</p>
</body>
</html>