<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD -->
<head>
    <title>@yield('title', app_name())</title>
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="public_url" content="{{ asset('') }}">
    <meta name="_token" content="{{ csrf_token() }}" />
    <!-- end: META -->
    <!-- start: GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700" rel="stylesheet">
    <!-- end: GOOGLE FONTS -->

    <!-- Styles -->
    @yield('before-styles-end')
    <!-- start: MAIN CSS -->
    {!! Html::style('vendor/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('vendor/fontawesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/fonts/style.css?v='.time()) !!}
    {!! Html::style('vendor/themify-icons/themify-icons.min.css') !!}
    {!! Html::style('vendor/animate.css/animate.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/switchery/switchery.min.css', ['media' => 'screen']) !!}
    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    {!! Html::style('assets/css/styles-orange.css?v='.time()) !!}
    {!! Html::style('assets/css/custom-style.css?v='.time()) !!}
    {!! Html::style('assets/css/clip-two/main-navigation.css?v='.time()) !!}
    {!! Html::style('assets/css/plugins.css?v='.time()) !!}
    {!! Html::style('assets/css/themes/theme-orange.css?v='.time()) !!}
    <!-- end: CLIP-TWO CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('after-styles-end')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
</head>
<!-- end: HEAD -->
<body class="login">

    @yield('content')

<!-- start: MAIN JAVASCRIPTS -->
{!! Html::script('vendor/jquery/jquery.min.js') !!}
{!! Html::script('vendor/bootstrap/js/bootstrap.min.js') !!}
{!! Html::script('vendor/modernizr/modernizr.js') !!}
{!! Html::script('vendor/jquery-cookie/jquery.cookie.js') !!}
{!! Html::script('vendor/perfect-scrollbar/perfect-scrollbar.min.js') !!}
{!! Html::script('vendor/switchery/switchery.min.js') !!}
<!-- end: MAIN JAVASCRIPTS -->

<!-- start: CLIP-TWO JAVASCRIPTS -->
{!! Html::script('assets/js/main.js?v='.time()) !!}
<!-- end: CLIP-TWO JAVASCRIPTS -->

<!-- start: JavaScript required for this page -->
{!! Html::script('assets/js/index.js?v='.time()) !!}
<!-- end: JavaScript required for this page -->

@yield('scripts')
<!-- end: JavaScript Event Handlers for this page -->
<!-- end: CLIP-TWO JAVASCRIPTS -->
</body>
<!-- end: FOOTER -->
</html>