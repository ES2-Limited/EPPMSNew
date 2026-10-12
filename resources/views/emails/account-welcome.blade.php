<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome to NCAA, {{ $user->fullname }}!</h1>
    <p>Your account has been successfully created.</p>
    <p><a href="{{ url('/') }}">Visit our platform</a></p>
</body>
</html>
