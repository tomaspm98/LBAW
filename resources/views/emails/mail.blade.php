<!DOCTYPE html>
<html>
<head>
    <title>OnlyFEUP</title>
</head>
<body>
        <h1>Reset Password</h1>
        <p>Hi there,</p>
        <p>We received a request to reset your password. If you did not make this request, please ignore this email.</p>
        <p>To reset your password, please use the following token:</p>
        <p>{{ $token }}</p>
        <p>If you have any questions, feel free to contact us.</p>
        <p>Thanks,<br>{{ config('app.name') }}</p>
    </body>
</html>
