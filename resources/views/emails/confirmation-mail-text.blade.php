{{ config('app.name') }} - Confirm your email address

CONFIRM YOUR EMAIL ADDRESS

Thank you for subscribing to my newsletter! To ensure you receive all my updates, insights, and exclusive content, please confirm your email address.

CONFIRMATION LINK:
{{ $confirmationUrl }}

Copy and paste the link above into your browser if clicking doesn't work.

WHAT YOU'LL GET:
- Latest insights and tutorials
- Exclusive project updates
- Free resources and downloads
- Industry tips and best practices

SECURITY NOTICE:
This confirmation link will expire in 24 hours for your security. If you didn't subscribe to this newsletter, you can safely ignore this email.

Best regards,
{{ \App\Models\User::first()->first_name ?? 'Kingsley' }}

---
© {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.

You received this email because you requested a subscription at {{ config('app.name') }}.
If you didn't request this subscription, you can safely delete this email.
