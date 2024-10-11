<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Confirm your email address</title>

  <style>
    @import url('https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap');

    body {
      background-color: #ffffff;
      margin: 0;
      padding: 0;
      line-height: 1.6;
    }

    .email-container {
      max-width: 500px;
      margin: 30px auto;
      background-color: #d1e3f1;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
      max-width: 500px;
      margin: 30px auto;
      text-align: left;
      margin-bottom: 20px;
      margin-top: 60px;
    }

    .header img {
      max-width: 60px;
    }

    h2 {
      font-family: 'Inter', Arial, sans-serif;
      color: #333333;
      font-size: 64px;
      text-align: left;
      line-height: 1;
      margin-bottom: 20px;
    }

    p {
      font-family: 'Inter', Arial, sans-serif;
      color: #424242;
      font-size: 16px;
      margin: 15px 0;
      text-align: left;
    }

    .button {
      font-family: 'Inter', Arial, sans-serif;
      display: inline-block;
      color: #ffffff;
      background-color: #007BFF;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      text-align: center;
      margin: 20px auto;
      display: block;
      width: 60%;
    }

    .footer {
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
      margin-top: 30px;
      text-align: left;
    }

    .footer a {
      font-family: 'Inter', Arial, sans-serif;
      color: #007BFF;
      text-decoration: none;
    }

    .footer p {
      font-family: 'Inter', Arial, sans-serif;
      font-size: 12px;
      margin: 10px 0;
      color: #b8b8b8;
    }
  </style>

</head>

<body>

  <div class="header">
    <img
      alt="Ultrashots Logo"
      src="{{ $message->embed($logoCid) }}" />
  </div>

  <div class="email-container">

    <h2>Confirm your email address</h2>

    <p>
      Thank you for subscribing to my newsletter. Please click the link below to confirm
      your email address.
    </p>

    <a href="{{ $confirmationUrl }}" class="button">
      Confirm Email
    </a>

    <p>— {{ \App\Models\User::first()->first_name }}</p>

  </div>

  <div class="footer">
    <p>
      <span style="font-size: 14px;">©{{ date('Y') }} <strong>{{ config('app.name') }}</strong>. All Rights Reserved.</span>
    </p>

    <p>
      <hr>
    </p>

    <p>
      You received this email because you requested for a subscription for your email at <strong>ultrashots</strong>.
      If you didn't request a subscription you can safely delete this email. <br />
    </p>
  </div>

</body>
</html>
