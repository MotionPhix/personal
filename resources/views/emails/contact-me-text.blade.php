{{ config('app.name') }} - New Contact Form Submission

🚨 NEW CONTACT FORM SUBMISSION

From: {{ $name }}
Received: {{ $timestamp->format('F j, Y \a\t g:i A T') }}

MESSAGE:
{{ $user_query }}

CONTACT DETAILS:
- Full Name: {{ $name }}
- Email: {{ $email }}
- Phone: {{ $phone }}
@isset($company)
- Company: {{ $company }}
@endisset

QUICK ACTIONS:
- Reply via email: mailto:{{ $email }}?subject=Re: Your inquiry
- Call: tel:{{ $phone }}

---
This email was automatically generated from your contact form.
Please respond promptly to maintain good customer relations.

© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
Contact form notification system.
