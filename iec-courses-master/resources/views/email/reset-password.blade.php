<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reset Your Password</h1>
    </div>
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>You are receiving this email because we received a password reset request for your account.</p>
        
        <p class="text-center">
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        </p>
        
        <p>This password reset link will expire in 60 minutes.</p>
        
        <p>If you did not request a password reset, no further action is required.</p>
    </div>
    <div class="footer">
        <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
        <p>{{ $resetUrl }}</p>
    </div>
</body>
</html> 