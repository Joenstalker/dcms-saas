<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ $message->embed(public_path('images/dcms-logo.png')) }}" alt="DCMS Logo" style="max-height: 80px;">
</div>

# Welcome to DCMS – Your Clinic Portal

Hello {{ $tenantName }},

Your clinic has been onboarded to DCMS.

<x-mail::button :url="$loginUrl">
Login to Your Portal
</x-mail::button>

Or copy and paste this link: {{ $loginUrl }}

**Your Credentials:**

* **Email:** {{ $email }}
* **Password:** {{ $password }}

*Please change your password immediately after your first login.*

Thank you,
{{ config('app.name') }} Provider Team
</x-mail::message>
