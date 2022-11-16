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
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="author" content="@yield('meta_author', '')">
    @yield('meta')
    <!-- end: META -->

    <!-- start: GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <!-- end: GOOGLE FONTS -->

    <!-- start: MAIN CSS -->
    @yield('before-styles-end')
    {!! Html::style('plugins2/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('plugins2/fontawesome/css/font-awesome.min.css') !!}
    {!! Html::style('plugins2/fonts/style.css') !!}
    {!! Html::style('plugins2/themify-icons/themify-icons.min.css') !!}
    {!! Html::style('plugins2/animate.css/animate.min.css', ['media' => 'screen']) !!}
    {!! Html::style('plugins2/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
    {!! Html::style('plugins2/switchery/switchery.min.css', ['media' => 'screen']) !!}
    {!! Html::style('css/intlTelInput.css') !!}
    {!! Html::style('css/tooltipster.css') !!}
    {!! Html::style('plugins2/jquery-ui/jquery-ui-1.10.1.custom.css') !!}
    
    
    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    {!! Html::style('css/styles-orange.css?v='.time()) !!}
    {!! Html::style('plugins2/clip-two/main-navigation.css') !!}
    {!! Html::style('css/plugins.css?v='.time()) !!}
    {!! Html::style('plugins2/themes/theme-orange.css') !!}
    <!-- end: CLIP-TWO CSS -->
    
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('required-styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    
    {!! Html::script('plugins2/jquery/jquery.min.js') !!}
    {!! Html::script('plugins2/jquery/jquery-ui.min.js') !!}
    
    {!! Html::script('js/jquery.tooltipster.min.js') !!}

    @yield('header-scripts')
  
</head>
<!-- end: HEAD -->
<body>
<div id="calPopupHelper" class="hidden"></div>

<div id="app" class="app-sidebar-closed a-s-c">
<!-- end: HEADER -->

    <!-- side bar -->
    @include('partials.includes.sidebar')

    <!-- start: APP CONTENT -->
    <div class="app-content">

        <!-- top nav bar -->
        @include('partials.includes.navbar')


        <!-- start: MAIN CONTETN -->
        <div class="main-content" >
            <div class="wrap-content container" id="container">
                @include('partials.includes.page_title')

                @include('partials.messages')

                @yield('content')

            </div>
        </div>
        <!-- end: MAIN CONTETN -->

    </div>
    <!-- end: APP CONTENT -->



    @include('partials.includes.footer')

</div>
<!-- start: MAIN JAVASCRIPTS -->
{!! Html::script('plugins2/bootstrap/js/tethr.js') !!}
{!! Html::script('plugins2/bootstrap/js/bootstrap.js') !!}
{!! Html::script('plugins2/modernizr/modernizr.js') !!}
{!! Html::script('plugins2/jquery-cookie/jquery.cookie.js') !!}
{!! Html::script('plugins2/perfect-scrollbar/perfect-scrollbar.min.js') !!}
{!! Html::script('plugins2/switchery/switchery.min.js') !!}
{!! Html::script('plugins2/selectFx/classie.js') !!}
{!! Html::script('plugins2/selectFx/selectFx.js') !!}
<!-- end: MAIN JAVASCRIPTS -->

{!! Html::script('plugins2/jquery-validation/jquery.validate.min.js') !!}
    {!! Html::script('plugins2/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}

    {!! Html::script('plugins2/jquery-ui/jquery-ui.min.js') !!}

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
{!! Html::script('js/main.js?v='.time()) !!}
<!-- start: CLIP-TWO JAVASCRIPTS -->

<!-- start: JavaScript required for this page -->
@yield('required-script')
<!-- end: JavaScript required for this page -->

<script>
    jQuery(document).ready(function() {
        Main.init();
        @yield('script-handler-for-this-page')
    });
</script>

@yield('custom-script')
@yield('invoice')
</body>
</html>