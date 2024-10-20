<!DOCTYPE html>
<html>
<head>
  <title>New Logo Fixing Request</title>
</head>
<body>

  <h1>Logo fixing request</h1>

  <p>
    <strong>From:</strong> <br />
    <span>{{ $name }}</span>
  </p>

  @isset($company)
    <p>
      <strong>Company:</strong> <br />
      <span>{{ $company }}</span>
    </p>
  @endisset

  <p>
    <strong>Phone number:</strong> <br />
    <span>{{ $phone }}</span>
  </p>

  <p style="margin-bottom: 1rem">
    <strong>Email:</strong> <br />
    <span>{{ $email }}</span>
  </p>

  {!! $description !!}

</body>
</html>
