<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LaraBids Verification OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
        
        <div style="text-align: center; margin-bottom: 25px;">
            <h1 style="color: #0d6efd; font-size: 24px; margin: 0;">LaraBids</h1>
        </div>

        <h2 style="text-align: center; color: #333333; margin-bottom: 10px;">Account Verification</h2>
        <p style="color: #666666; text-align: center;">Hello! Please use the following OTP to verify your account:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 36px; font-weight: bold; padding: 12px 30px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border: 2px solid #dee2e6; border-radius: 8px; color: #1a1a2e; letter-spacing: 8px; display: inline-block;">
                {{ $otp }}
            </span>
        </div>

        <p style="color: #666666; text-align: center; font-size: 14px;">This code is valid for a limited time. Please enter it on the verification page to complete your registration.</p>
        <p style="color: #999999; text-align: center; font-size: 13px;">If you did not create an account on LaraBids, please ignore this email.</p>
        
        <hr style="border: 0; border-top: 1px solid #eeeeee; margin: 25px 0;">
        <p style="text-align: center; color: #999999; font-size: 12px;">
            &copy; {{ date('Y') }} LaraBids. All rights reserved.
        </p>
    </div>
</body>
</html>
