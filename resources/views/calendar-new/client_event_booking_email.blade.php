<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
	@if($action == 'confirm')
	<h2>Booking confirmed</h2>
	@else
	<h2>Booking cancelled</h2>
	@endif

	<p>Hi {{$clientDetails->name}},</p>

	@if($action == 'confirm')
	<p>You are now booked into the following {{$bookingType}}:</p>
	@else
	<p>The following booking has been cancelled.</p>
	@endif

	<p><strong>
		{{ $className }} with {{ $staffName }}
		<br>
		{{ $businessName }}
		<br>
		{{ $datetime }}
	</strong></p>

	<p>At these location:</p>

	@foreach($locDetails as $locDetail)
		<p><strong>
			{{ $locDetail['area'] }}
			<br>
			{{ $locDetail['name'] }}
			<br>
			{{ $locDetail['addr1'] }}
			<br>
			{{ $locDetail['addr2'] }}
			<br>
			{{ $locDetail['city'] }}
			<br>
			{{ $locDetail['state'] }}, {{ $locDetail['country'] }}
		</strong></p>
	@endforeach

	<p>Under your details:</p>

	<p><strong>
		{{ $clientDetails->name }}
		<br>
		{{ $clientDetails->email }}
		@if($clientDetails->number)
		<br>
		Telephone: {{ $clientDetails->number }}
		@endif
	</strong></p>
</body>
</html>