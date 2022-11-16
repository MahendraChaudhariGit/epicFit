<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD --><head>
    <title>@yield('title', app_name())</title>
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <meta name="_token" content="{{ csrf_token() }}" />
    <meta name="public_url" content="{{ asset('') }}">
    <!-- end: META -->

    <!-- start: GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <!-- end: GOOGLE FONTS -->

    <!-- start: MAIN CSS -->
    {!! Html::style('vendor/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('vendor/fontawesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/fonts/style.css') !!}
    {!! Html::style('vendor/themify-icons/themify-icons.min.css') !!}
    {!! Html::style('vendor/animate.css/animate.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/switchery/switchery.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/tooltipster-master/tooltipster.css') !!}
    {!! Html::style('vendor/jquery-ui/jquery-ui-1.10.1.custom.css') !!}
    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    {!! Html::style('assets/css/styles-orange.css') !!}
    {!! Html::style('assets/css/clip-two/main-navigation.css') !!}
    {!! Html::style('assets/css/plugins.css') !!}
    {!! Html::style('assets/css/themes/theme-orange.css') !!}
    <!-- end: CLIP-TWO CSS -->
    
    {!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
    {!! Html::style('assets/css/custom.css') !!}

    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('required-styles-for-this-page')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

    {!! Html::script('vendor/jquery/jquery.min.js') !!}
    {!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}
</head>
<!-- end: HEAD -->
<body class="bg-white">
@yield('content')

<!-- start: MAIN JAVASCRIPTS -->
{!! Html::script('vendor/bootstrap/js/tethr.js?v='.time()) !!}
{!! Html::script('vendor/bootstrap/js/bootstrap.js?v='.time()) !!}
{!! Html::script('vendor/modernizr/modernizr.js?v='.time()) !!}
{!! Html::script('vendor/jquery-cookie/jquery.cookie.js?v='.time()) !!}
{!! Html::script('vendor/perfect-scrollbar/perfect-scrollbar.min.js?v='.time()) !!}
{!! Html::script('vendor/switchery/switchery.min.js?v='.time()) !!}
{!! Html::script('vendor/selectFx/classie.js?v='.time()) !!}
{!! Html::script('vendor/selectFx/selectFx.js?v='.time()) !!}
<!-- end: MAIN JAVASCRIPTS -->

<script>
/**
* 01.01 : Initial configuration for sending ajax
*/
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
	}
});
</script>

<!-- start: CLIP-TWO JAVASCRIPTS -->
{!! Html::script('assets/js/main.js?v='.time()) !!}
<!-- start: CLIP-TWO JAVASCRIPTS -->

<!-- start: JavaScript required for this page -->
@yield('required-script-for-this-page')
<!-- end: JavaScript required for this page -->

<!-- start: JavaScript Event Handlers for this page -->
<script>
    jQuery(document).ready(function() {
        @yield('script-handler-for-this-page')
    });
</script>
<!-- end: JavaScript Event Handlers for this page -->
</body>
</html>