<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Code</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #6B48FF, #8E2DE2); padding: 20px; text-align: center; }
        .header h2 { color: white; margin: 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .otp-code { font-size: 36px; font-weight: bold; color: #8E2DE2; letter-spacing: 5px; text-align: center; margin: 30px 0; }
        .footer { text-align: center; margin-top: 20px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CRISPAC LOGISTICS</h2>
        </div>
        <div class="content">
            <h3>Password Reset Request</h3>
            <p>Hello,</p>
            <p>You requested to reset your password. Your verification code is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>This code expires in <strong>10 minutes</strong>.</p>
            <p>If you didn't request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Crispac Logistics. All rights reserved.</p>
        </div>
    </div>
</body>
</html>