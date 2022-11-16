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
    {!! Html::style('plugins/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('plugins/fontawesome/css/font-awesome.min.css') !!}
    {!! Html::style('plugins/fonts/style.css') !!}
    {!! Html::style('plugins/themify-icons/themify-icons.min.css') !!}
    {!! Html::style('plugins/animate.css/animate.min.css', ['media' => 'screen']) !!}
    {!! Html::style('plugins/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
    {!! Html::style('plugins/switchery/switchery.min.css', ['media' => 'screen']) !!}
    {!! Html::style('css/intlTelInput.css') !!}
    {!! Html::style('css/tooltipster.css') !!}
    {!! Html::style('plugins/jquery-ui/jquery-ui-1.10.1.custom.css') !!}
    {!! Html::style('plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('plugins/sweetalert/sweet-alert.css') !!} 
    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    {!! Html::style('css/styles-orange.css?v='.time()) !!}
    {!! Html::style('plugins/clip-two/main-navigation.css') !!}
    {!! Html::style('css/plugins.css') !!}
    {!! Html::style('plugins/themes/theme-orange.css') !!}
    {!! Html::style('css/custom-style.css?v='.time()) !!}
    {!! Html::style('css/conversation.css?v='.time()) !!}
    <!-- end: CLIP-TWO CSS -->
    
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('required-styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

    {!! Html::script('plugins/jquery/jquery.min.js') !!}
    {!! Html::script('plugins/jquery/jquery-ui.min.js') !!}
    {!! Html::script('js/jquery.tooltipster.min.js') !!}


</head>

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
{!! Html::script('plugins/bootstrap/js/tethr.js') !!}
{!! Html::script('plugins/bootstrap/js/bootstrap.js') !!}
{!! Html::script('plugins/modernizr/modernizr.js?v='.time()) !!}
{!! Html::script('plugins/jquery-cookie/jquery.cookie.js') !!}
{!! Html::script('plugins/perfect-scrollbar/perfect-scrollbar.min.js') !!}
{!! Html::script('plugins/switchery/switchery.min.js') !!}
{!! Html::script('plugins/selectFx/classie.js') !!}
{!! Html::script('plugins/selectFx/selectFx.js') !!}
{!! Html::script('plugins/moment/moment.min.js') !!}
{!! Html::script('plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
{!! Html::script('plugins/DataTables/media/js/dataTables.bootstrap.min.js') !!}
{!! Html::script('plugins/DataTables/media/js/dataTableDateSort.js') !!}
{!! Html::script('plugins/sweetalert/sweet-alert.min.js') !!} 
<script src='http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js'></script>
<script src="https://js.pusher.com/3.1/pusher.min.js"></script>

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
    {!! Html::script('js/main.js') !!}
<!-- start: CLIP-TWO JAVASCRIPTS -->

<script>
    jQuery(document).ready(function() {
        Main.init();
        
        @yield('script-handler-for-this-page')
    });
</script>

{!! Html::script('js/talk.js?v='.time()) !!}
{!! Html::script('js/helper.js?v='.time()) !!}

<!-- start: JavaScript required for this page -->
    @yield('required-script')
<!-- end: JavaScript required for this page -->

</body>
</html>