<!DOCTYPE html>
<html>
<head>
	<title>EPIC FITNESS LTD - PRIVACY POLICY</title>
	   <!-- start: GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />

    <!-- end: GOOGLE FONTS -->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    {!! Html::style('result/plugins/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('result/plugins/fontawesome/css/font-awesome.min.css') !!}

    {!! Html::style('result/css/styles-orange.css?v='.time()) !!}

    {!! Html::script('result/plugins/jquery/jquery.min.js') !!}
    {!! Html::script('result/plugins/jquery/jquery-ui.min.js') !!}
    <style type="text/css">
    	body{
    		background: #fff;
    	}
    </style>
</head>
<body>
	
@include('includes.partials.privacy-content')

{!! Html::script('result/plugins/bootstrap/js/bootstrap.js') !!}

</body>
</html>
{{-- 
@section('page-title')
<span>EPIC FITNESS LTD - PRIVACY POLICY </span>
@stop
@section('required-styles')
@stop
@section('content')

@endsection --}}