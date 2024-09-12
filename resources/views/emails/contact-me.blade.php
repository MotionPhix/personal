<!DOCTYPE html>
<html>

  <head>
    <title>Thank You for Contacting Me</title>
    <style>
      body {
        font-family: Roboto, Helvetica, Inter, sans-serif;
        margin: 0;
        padding: 0;
      }

      .container {
        width: 100%;
        max-width: 500px;
        margin: auto;
        background-color: #ffffff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 5rem;
      }

      .button {
        background-color: #007BFF;
        color: #ffffff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
      }

      .table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        border: 1px solid #ddd;
      }

      .table th,
      .table td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
      }

      .table tr:last-child td {
        border-bottom: none;
      }

      .footer {
        font-size: 12px;
        color: #666666;
        text-align: left;
        margin-top: 20px;
      }

      /* Responsive styles */
      @media only screen and (max-width: 600px) {

        .table,
        .table th,
        .table td {
          display: block;
          width: 100%;
        }

        .table th {
          display: none;
        }

        .table td {
          position: relative;
          /* padding-left: 50%; */
          text-align: left;
        }

        .table td::before {
          content: attr(data-label);
          /* position: absolute; */
          left: 0;
          /* width: 50%; */
          padding-left: 8px;
          text-align: left;
          font-weight: bold;
        }

        .container {
          box-shadow: none;
        }
      }

    </style>
  </head>

  <body>
    <div class="container">
      <h1>Thank you for contacting me!</h1>
      <p>Hi {{ $name }},</p>
      <p>Thank you for getting in touch with us. We have received your message and would like to thank you for writing to us. We will reply by email as soon as possible, within 24 hours.</p>
      <h2>Please verify your information</h2>
      <p>If any of the below information is incorrect, please contact us immediately.</p>
      <table class="table">
        <tr>
          <th>Information</th>
          <th>Details</th>
        </tr>
        <tr>
          <td data-label="Name">Name</td>
          <td>{{ $name }}</td>
        </tr>
        <tr>
          <td data-label="Email">Email</td>
          <td>{{ $email }}</td>
        </tr>
        <tr>
          <td data-label="Phone">Phone</td>
          <td>{{ $phone }}</td>
        </tr>
      </table>
      <p>If you need to adjust any information or have any further questions, please do not hesitate to contact us at <a href="mailto:support@example.com">support@example.com</a>.</p>
      <p class="footer">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
    <p style="font-size: 12px; color: #666666; text-align: left; width: 100%; max-width: 500px; margin: auto; margin-top: 20px;">You are receiving this email because you contacted me from <a href="https://ultrashots.net"><strong>{{ config('app.name') }}</strong></a>. If you didn't, you can ignore and delete this email.</p>
  </body>

</html>
