<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">

  <title>Confirm your email address</title>

  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->

  <style>
    /* Reset and base styles */
    * {
      box-sizing: border-box;
    }

    body, table, td, p, a, li, blockquote {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    table, td {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    img {
      -ms-interpolation-mode: bicubic;
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
    }

    /* Client-specific styles */
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
      line-height: 100%;
    }

    /* Dark mode styles */
    @media (prefers-color-scheme: dark) {
      .dark-mode-bg { background-color: #1a1a1a !important; }
      .dark-mode-text { color: #ffffff !important; }
      .dark-mode-text-muted { color: #cccccc !important; }
      .dark-mode-border { border-color: #333333 !important; }
      .dark-mode-table-bg { background-color: #2a2a2a !important; }
      .dark-mode-button { background-color: #3182ce !important; }
    }

    /* Mobile styles */
    @media screen and (max-width: 600px) {
      .mobile-full-width {
        width: 100% !important;
        max-width: 100% !important;
      }
      .mobile-padding {
        padding-left: 20px !important;
        padding-right: 20px !important;
      }
      .mobile-text-center {
        text-align: center !important;
      }
      .mobile-hide {
        display: none !important;
      }
      .mobile-button {
        width: 100% !important;
        text-align: center !important;
      }
    }
  </style>
</head>

<body style="margin: 0; padding: 0; width: 100%; background-color: #f0f9ff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;" class="dark-mode-bg">

  <!-- Preheader text -->
  <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    Please confirm your email address to complete your newsletter subscription.
  </div>

  <!-- Email wrapper -->
  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f0f9ff;" class="dark-mode-bg">
    <tr>
      <td align="center" style="padding: 40px 20px;">

        <!-- Main email container -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" class="mobile-full-width dark-mode-table-bg">

          <!-- Header with logo -->
          <tr>
            <td style="padding: 40px 40px 20px 40px; text-align: center;" class="mobile-padding">
              <img src="{{ $message->embed($logoCid) }}" alt="{{ config('app.name') }} Logo" width="80" height="80" style="display: block; margin: 0 auto; width: 80px; height: 80px; border-radius: 12px;">
            </td>
          </tr>

          <!-- Main content -->
          <tr>
            <td style="padding: 0 40px 40px 40px; text-align: center;" class="mobile-padding">

              <!-- Icon -->
              <div style="margin: 0 0 24px 0; text-align: center;">
                <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; position: relative;">
                  <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 32px;">📧</span>
                </div>
              </div>

              <!-- Title -->
              <h1 style="margin: 0 0 16px 0; font-size: 32px; font-weight: 700; line-height: 1.2; color: #1a1a1a;" class="dark-mode-text">
                Confirm your email address
              </h1>

              <!-- Subtitle -->
              <p style="margin: 0 0 32px 0; font-size: 18px; line-height: 1.5; color: #4a5568;" class="dark-mode-text-muted">
                You're almost there! Just one more step to complete your subscription.
              </p>

              <!-- Description -->
              <p style="margin: 0 0 32px 0; font-size: 16px; line-height: 1.6; color: #4a5568; text-align: left;" class="dark-mode-text-muted">
                Thank you for subscribing to my newsletter! To ensure you receive all my updates, insights, and exclusive content, please confirm your email address by clicking the button below.
              </p>

              <!-- CTA Button -->
              <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                <tr>
                  <td style="border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); text-align: center;" class="dark-mode-button">
                    <a href="{{ $confirmationUrl }}" style="display: inline-block; padding: 16px 32px; font-size: 16px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px; min-width: 200px;" class="mobile-button">
                      ✅ Confirm Email Address
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Alternative link -->
              <p style="margin: 32px 0 0 0; font-size: 14px; color: #718096; text-align: center;" class="dark-mode-text-muted">
                Button not working? Copy and paste this link into your browser:<br>
                <a href="{{ $confirmationUrl }}" style="color: #3182ce; word-break: break-all; text-decoration: none;">{{ $confirmationUrl }}</a>
              </p>

              <!-- Benefits section -->
              <div style="margin: 40px 0; padding: 24px; background-color: #f7fafc; border-radius: 8px; text-align: left;" class="dark-mode-table-bg">
                <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #2d3748; text-align: center;" class="dark-mode-text">
                  🎯 What you'll get:
                </h3>
                <ul style="margin: 0; padding: 0; list-style: none;">
                  <li style="margin: 0 0 12px 0; padding: 0 0 0 24px; position: relative; color: #4a5568;" class="dark-mode-text-muted">
                    <span style="position: absolute; left: 0; top: 0;">💡</span>
                    Latest insights and tutorials
                  </li>
                  <li style="margin: 0 0 12px 0; padding: 0 0 0 24px; position: relative; color: #4a5568;" class="dark-mode-text-muted">
                    <span style="position: absolute; left: 0; top: 0;">🚀</span>
                    Exclusive project updates
                  </li>
                  <li style="margin: 0 0 12px 0; padding: 0 0 0 24px; position: relative; color: #4a5568;" class="dark-mode-text-muted">
                    <span style="position: absolute; left: 0; top: 0;">🎁</span>
                    Free resources and downloads
                  </li>
                  <li style="margin: 0; padding: 0 0 0 24px; position: relative; color: #4a5568;" class="dark-mode-text-muted">
                    <span style="position: absolute; left: 0; top: 0;">📈</span>
                    Industry tips and best practices
                  </li>
                </ul>
              </div>

              <!-- Security notice -->
              <div style="margin: 32px 0; padding: 16px; background-color: #fef3c7; border-radius: 6px; border-left: 4px solid #f59e0b;">
                <p style="margin: 0; font-size: 14px; color: #92400e; text-align: left;">
                  🔒 <strong>Security Notice:</strong> This confirmation link will expire in 24 hours for your security. If you didn't subscribe to this newsletter, you can safely ignore this email.
                </p>
              </div>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding: 32px 40px; border-top: 1px solid #e2e8f0; background-color: #f7fafc; text-align: center;" class="mobile-padding dark-mode-border dark-mode-table-bg">

              <!-- Signature -->
              <p style="margin: 0 0 16px 0; font-size: 16px; color: #1a1a1a;" class="dark-mode-text">
                Best regards,<br>
                <strong>{{ \App\Models\User::first()->first_name ?? 'Kingsley' }}</strong>
              </p>

              <!-- Social links -->
              <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 24px auto;">
                <tr>
                  <td style="padding: 0 8px;">
                    <a href="#" style="display: inline-block; width: 32px; height: 32px; background-color: #3182ce; border-radius: 50%; text-align: center; line-height: 32px; color: #ffffff; text-decoration: none; font-size: 14px;">📧</a>
                  </td>
                  <td style="padding: 0 8px;">
                    <a href="#" style="display: inline-block; width: 32px; height: 32px; background-color: #1da1f2; border-radius: 50%; text-align: center; line-height: 32px; color: #ffffff; text-decoration: none; font-size: 14px;">🐦</a>
                  </td>
                  <td style="padding: 0 8px;">
                    <a href="#" style="display: inline-block; width: 32px; height: 32px; background-color: #0077b5; border-radius: 50%; text-align: center; line-height: 32px; color: #ffffff; text-decoration: none; font-size: 14px;">💼</a>
                  </td>
                </tr>
              </table>

              <!-- Copyright -->
              <p style="margin: 0 0 12px 0; font-size: 12px; color: #718096;" class="dark-mode-text-muted">
                © {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. All Rights Reserved.
              </p>

              <!-- Unsubscribe notice -->
              <p style="margin: 0; font-size: 11px; color: #a0aec0; line-height: 1.4;" class="dark-mode-text-muted">
                You received this email because you requested a subscription at
                <a href="{{ config('app.url') }}" style="color: #3182ce; text-decoration: none;"><strong>{{ config('app.name') }}</strong></a>.
                If you didn't request this subscription, you can safely delete this email.
              </p>

            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
