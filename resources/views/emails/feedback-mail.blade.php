<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Thank you for contacting me</title>

  <style type="text/css">
    @import url('https://fonts.bunny.net/css?family=roboto:400,500,600,700&display=swap');
    @import url('https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap');

    body {
      margin: 0;
      padding: 0;
      width: 100% !important;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    table {
      border-collapse: collapse;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    .header p {
      max-width: 600px;
      margin: auto;
      text-align: left;
      margin-bottom: 10px;
      padding-top: 20px;
    }

    .header img {
      max-width: 60px;
    }

    .container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #9d1111;
      border-radius: 10px;
    }

    .footer {
      font-size: 12px;
      color: #666666;
      text-align: left;
      margin-top: 20px;
    }
  </style>
</head>
<body style="margin: 0; padding: 0;">

  <table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0">

    <tr>
      <td align="left" class="header">
        <p>
          <img
            alt="Ultrashots Logo"
            style="max-width: 60px; margin-left: 50px;"
            src="{{ $message->embed($logo) }}" />
        </p>
      </td>
    </tr>

    <tr>

      <td align="center" style="padding: 20px 0;">

        <table
          width="600"
          cellpadding="0"
          cellspacing="0"
          border="0"
          style="border-radius: 10px; overflow: hidden; background-color: #f1f1f1;">

          <tr>

            <td style="padding: 20px; font-family: Roboto, Inter, Helvetica, sans-serif;">

              <h1 style="font-family: Roboto, Inter, Helvetica, sans-serif;">Thank you for <br>contacting me!</h1>

              <p style="font-family: Roboto, Inter, Helvetica, sans-serif;">Hi {{ $name }},</p>

              <p style="font-family: Roboto, Inter, Helvetica, sans-serif;">
                Thank you for getting in touch with me. I will provide you with a
                relevant response once I go through the email you sent me, usually
                within 24 hours.
              </p>

              <p
                style="font-family: Roboto, Inter, Helvetica, sans-serif;">
                Should any of your information below be incorrect, please reply to this email, with
                the corrected information so I can get back to you with proper feedback.
              </p>

              <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                  border-collapse: separate;
                  border-spacing: 0;
                  border-radius: 10px;
                  overflow: hidden;
                  width: 100%;
                  max-width: 600px;
                  border: 1px solid #3ad85d;
                  margin: 0 auto;
                  font-family: Roboto, Helvetica, Inter, sans-serif;
                  font-size: 14px;
                  color: #333;">

                <tr>
                  <th
                    align="left"
                    colspan="2"
                    style="background-color: #f2f2f2; padding: 10px; border-bottom: 1px solid #3ad85d;">
                    Contact information
                  </th>
                </tr>

                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #3ad85d;">Full Name</td>
                  <td style="padding: 10px; border-bottom: 1px solid #3ad85d;">{{ $name }}</td>
                </tr>

                @isset($company)
                  <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #3ad85d;">Company</td>
                    <td style="padding: 10px; border-bottom: 1px solid #3ad85d;">{{ $company }}</td>
                  </tr>
                @endisset

                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #3ad85d;">Email Address</td>
                  <td style="padding: 10px; border-bottom: 1px solid #3ad85d;">{{ $email }}</td>
                </tr>

                <tr>
                  <td style="padding: 10px;">Mobile Number</td>
                  <td style="padding: 10px;">{{ $phone }}</td>
                </tr>

              </table>

              <p
                class="footer"
                style="font-family: Roboto, Inter, Helvetica, sans-serif; font-size: 12px; color: #666666; text-align: left; margin-top: 20px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
              </p>

              <p
                style="border-top: 1px solid; padding-top: 4px; font-size: 10px; color: #666666; text-align: left; width: 100%; font-family: Roboto, Inter, Helvetica, sans-serif;">
                You are receiving this email because you contacted me from
                <a href="https://ultrashots.net"><strong>{{ config('app.name') }}</strong></a>. If you didn't mean to be contacted back, you can just ignore and delete this email.
              </p>

            </td>
          </tr>

        </table>

      </td>

    </tr>

  </table>

</body>

</html>
