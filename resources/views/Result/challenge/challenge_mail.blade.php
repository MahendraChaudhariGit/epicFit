<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
	<style type="text/css">
	.button-style{
		color: white;
		margin-bottom: 15px;
		display: inline-block;
		font-weight: 400;
		padding: .375rem .75rem;
		font-size: 1rem;
		line-height: 1.5;
		border-radius: .25rem;
   }
</style>
</head>

<body>
	<p>Hi {{ $challenge_friend_email['client']['firstname'] }},</p>

	<p>You have got an challenge invitation from <strong>{{ $challenge_friend_email['challenge']['client']['firstname'] }} {{ $challenge_friend_email['challenge']['client']['lastname'] }} </strong>and below are the details:</p>
	<p><strong>Challenge name:</strong> {{ $challenge_friend_email['challenge']['name'] }} </p>
	<p><strong>Challenge date:</strong> {{ $challenge_friend_email['challenge']['date'] }} </p>
    
     <a href="{{$accept_url}}" type="button" class="btn btn-default button-style" style="background: green;" target="_blank">ACCEPT</a>
     <a href="{{$reject_url}}" type="button" class="btn btn-default button-style" style="background: red;" target="_blank">NO,I AM BUSY</a>

	<p>Thanks.</p>

	
</body>
</html>