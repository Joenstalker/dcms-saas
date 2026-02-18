<x-mail::message>
<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ $message->embed(public_path('images/dcms-logo.png')) }}" alt="DCMS Logo" style="max-height: 100px;">
</div>

# Hello {{ $name }},

You are receiving this email because we received a password reset request for your account.

To reset your password, please follow these simple steps:

1. **Click the button below** to open the secure password reset page.
2. **Enter your new password** and confirm it in the provided fields.
3. **Submit the form** and you will be redirected to the login page where you can use your new credentials.

<x-mail::button :url="$resetUrl">
Reset Password
</x-mail::button>

*This password reset link will expire in 60 minutes.*

If you did not request a password reset, no further action is required. Your account remains secure.

Regards,  
**DCMS Support Team**

<x-mail::subcopy>
If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
[{{ $resetUrl }}]({{ $resetUrl }})
</x-mail::subcopy>

</x-mail::message>
