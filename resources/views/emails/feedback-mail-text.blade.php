{{ config('app.name') }} - Thank you for contacting me!

Hi {{ $name }},

Thank you for getting in touch with me. I will provide you with a relevant response once I go through your message, usually within 24 hours.

Should any of your information below be incorrect, please reply to this email with the corrected information so I can get back to you with proper feedback.

YOUR CONTACT INFORMATION:
- Full Name: {{ $name }}
- Email: {{ $email }}
- Phone: {{ $phone }}
@isset($company)
- Company: {{ $company }}
@endisset

WHAT'S NEXT?
I'll review your message and get back to you with a personalized response. Feel free to reply to this email if you have any additional information to share.

Best regards,
Kingsley Ultrashots

---
© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.

You are receiving this email because you contacted me from {{ config('app.name') }}.
If you didn't mean to be contacted back, you can safely ignore and delete this email.
