<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recruiter OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #3b71ca;">Recruiter Password Reset</h2>
        <p>Hello,</p>
        <p>Your One-Time Password (OTP) for resetting your password is:</p>
        <h1 style="text-align: center; background: #e4e4e4; padding: 10px; border-radius: 6px;">{{ $otp }}</h1>
        <p>This OTP is valid for 5 minutes. If you did not request this, you can safely ignore this email.</p>
        <br>
        <p>Regards,<br>{{ setting('site_name', 'Your Website') }}</p>
    </div>
</body>
</html>
