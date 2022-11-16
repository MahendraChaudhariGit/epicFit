<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Welcome to {{ app_name() }}!</h2>
    <div>
       Thanks for creating an account with the us. Below are your login details:
       <p>
			Login E-mail: {{ $email }}       	
			<br>
			Password: {{ $password }}	
       </p>
       Confirm your account and enjoy the epic services of {{ app_name() }}. Confirmation email is on its way to your mail box.
    </div>
</body>
</html>