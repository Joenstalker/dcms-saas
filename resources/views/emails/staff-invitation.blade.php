<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ $message->embed(public_path('images/dcms-logo.png')) }}" alt="DCMS Logo" style="max-height: 80px;">
</div>

# Welcome to {{ $clinicName }}, {{ $name }}!

Hello {{ $name }},

You have been invited to join **{{ $clinicName }}** as a **{{ $roleLabel }}**.

<x-mail::button :url="$loginUrl">
Login to Your Portal
</x-mail::button>

Or copy and paste this link: {{ $loginUrl }}

---

**Your Login Credentials:**

* **Role:** {{ $roleLabel }}
* **Email:** {{ $email }}
* **Temporary Password:** `{{ $tempPassword }}`

---

> ⚠️ **Important:** Please change your password immediately after your first login for security purposes.

If you have any questions, please contact your clinic administrator.

Thank you,<br>
{{ config('app.name') }} Team
</x-mail::message>
