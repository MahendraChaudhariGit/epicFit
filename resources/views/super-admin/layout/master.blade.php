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

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- start: MAIN CSS -->
    @yield('before-styles-end')
    {!! Html::style('vendor/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('vendor/fontawesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/fonts/style.css?v='.time()) !!}
    {!! Html::style('vendor/themify-icons/themify-icons.min.css') !!}
    {!! Html::style('vendor/animate.css/animate.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/switchery/switchery.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/tooltipster-master/tooltipster.css') !!}
    {!! Html::style('vendor/jquery-ui/jquery-ui-1.10.1.custom.css') !!}
    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    {!! Html::style('assets/css/styles-orange.css?v='.time()) !!}
    {!! Html::style('assets/css/custom-style.css?v='.time()) !!}
    {!! Html::style('assets/css/clip-two/main-navigation.css?v='.time()) !!}
    {!! Html::style('assets/css/plugins.css?v='.time()) !!}
    @yield('plugin-css')
    {!! Html::style('assets/css/themes/theme-orange.css?v='.time()) !!}
    {!! Html::style('assets/css/theme_light.css?v='.time()) !!}
    <!-- end: CLIP-TWO CSS -->
    
    {!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css?v='.time()) !!}
    {!! Html::style('assets/css/custom.css?v='.time()) !!}

    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('required-styles-for-this-page')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    
    {!! Html::script('vendor/jquery/jquery.min.js') !!}
    {!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}

    <!-- Start: MAIN CSS -->
    <!-- end: MAIN CSS -->
</head>
<!-- end: HEAD -->
<body class="{{ (isset($subview))?'bg-white':'' }}" onload="getNotify()" onunload="">
    <!-- For calendar -->
    <div id="calPopupHelper" class="hidden"></div>
    @include('includes.partials.waiting_shield')
    {!! Form::hidden('upcomingTasks', null) !!}

    @if(!isset($subview))
    <div id="app" class="app-sidebar-closed a-s-c">
        <!-- end: HEADER -->
        
        <!-- side bar -->
        @include('super-admin.layout.nav')

        <!-- start: APP CONTENT -->
        <div class="app-content">

            <!-- top nav bar -->
            
            @include('super-admin.layout.navbar')   
            

            <!-- start: MAIN CONTETN -->
            <div class="main-content" >
                <div class="wrap-content container" id="container">
                    @include('layouts.includes.page_title')

                    @include('includes.partials.messages')

                    @yield('content')

                </div>
            </div>
            <!-- end: MAIN CONTETN -->

        </div>
        <!-- end: APP CONTENT -->

        @include('layouts.includes.footer')

        <!--Start: Modal for upcoming task reminder -->
        @include('includes.partials.reminder_modal');
        <!--End: Modal for upcoming task reminder -->

        <!-- Start: Modal for expried service -->
        @include('includes.partials.expired_service_modal')
        <!-- Start: Modal for expried service -->    
        <input type="hidden" id="super-admin" value="{{ Session::get('adminData')->account_type }}">
    </div>
    @else
    @yield('content')
    @endif
    <!-- start: MAIN JAVASCRIPTS -->
    {!! Html::script('vendor/bootstrap/js/tethr.js') !!}
    {!! Html::script('vendor/bootstrap/js/bootstrap.js') !!}
    {!! Html::script('vendor/modernizr/modernizr.js') !!}
    {!! Html::script('vendor/jquery-cookie/jquery.cookie.js') !!}
    {!! Html::script('vendor/perfect-scrollbar/perfect-scrollbar.min.js') !!}
    {!! Html::script('vendor/switchery/switchery.min.js') !!}
    {!! Html::script('vendor/selectFx/classie.js') !!}
    {!! Html::script('vendor/selectFx/selectFx.js') !!}
    <!-- end: MAIN JAVASCRIPTS -->
    {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
    <!-- end: Country Code Selector -->
    
    <script>
        var currTimeZone= '<?php echo (Session::has('timeZone'))?Session::get('timeZone'):''; ?>';
    /**
     * 01.01 : Initial configuration for sending ajax
     */
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    }); 
</script>

{!! Html::script('assets/js/set-moment-timezone.js?v='.time()) !!}
<!-- start: CLIP-TWO JAVASCRIPTS -->
{!! Html::script('assets/js/main.js?v='.time()) !!}
<!-- start: CLIP-TWO JAVASCRIPTS -->
{!! Html::script('assets/js/clients-notification.js?v='.time()) !!}
<!-- start: JavaScript required for this page -->
@yield('required-script-for-this-page')
<!-- end: JavaScript required for this page -->

<!-- start: JavaScript Event Handlers for this page -->
<script>
    jQuery(document).ready(function() {
        Main.init();
        @yield('script-handler-for-this-page')
        
    });


    var loggedInUser = {
        //type: '{{ Session::get('userType') }}',
        type: '{{ Session::get('adminData')->account_type }}',
        id: {{ Session::get('adminData')->account_id }},
        userId: {{ Session::get('adminData')->id }},
        name: '{{ Session::get('adminData')->fullName }}'
    };

    
    // $(document).ready(function(){
    //     var businessExist= '<?php echo Session::has('businessId'); ?>';
    //     if(businessExist){
    //         getUpcomingTasks();
    //         getExpriedService();
    //     }
    // })
    
</script>
<!-- end: JavaScript Event Handlers for this page -->
<script>
	jQuery(document).ready(function() {
	$("#country_code").val($(".country.active").find('.dial-code').html())
	 $(".country").on('click',function(){
            $("#country_code").val($(this).find('.dial-code').html())
        })
	})
</script>
@yield('script-after-page-handler')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    $('.delLink').click(function(){
        var delUrl = $(this).data('del-url');
        swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this Account!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = delUrl;
          }
        });
    });
    $('.sendLink').click(function(){
        var sendUrl = $(this).data('send-url');
        swal({
          title: "Are you sure?",
          text: "Account activation confirmation email!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = sendUrl;
          }
        });
    })
</script>
</body>
</html>