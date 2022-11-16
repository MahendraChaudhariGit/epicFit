<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Verify Your Email Address</h2>
    <div>
        Please follow the link below to verify your email address on {{ app_name() }}.
        <p>{{ HTML::link(route('test.verify', $confirmation_code)) }}</p>
    </div>
</body>
</html>