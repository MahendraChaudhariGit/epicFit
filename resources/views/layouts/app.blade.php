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
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
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
    {!! Html::style('assets/css/custom-style.css') !!}
    {!! Html::style('assets/css/clip-two/main-navigation.css') !!}
    {!! Html::style('assets/css/plugins.css') !!}
    @yield('plugin-css')
    {!! Html::style('assets/css/themes/theme-orange.css') !!}
    {!! Html::style('assets/css/theme_light.css') !!}
    <!-- end: CLIP-TWO CSS -->
    
    {!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
    {!! Html::style('assets/css/custom.css') !!}

    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('required-styles-for-this-page')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    
    {!! Html::script('vendor/jquery/jquery.min.js') !!}
    {!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}

    <!-- Start: MAIN CSS -->
    <!-- end: MAIN CSS -->
</head>
<!-- end: HEAD -->
<body class="{{ (isset($subview))?'bg-white':'' }}, myDiv" onload="getNotify()" onunload="">
    <!-- Start: Lock screen alert -->
    @include('includes.partials.lock_screen_alert')
    <!-- End: Lock screen alert -->

    <!-- Start: Lock screen html --> 
    <div id="lock-screen-div" class="{{ (Session::has('lockstatus'))?'':'hidden'}} lock-screen-main">   
        @include('lock_screen',['user'=>Auth::user()])
    </div>
    <!-- End: Lock screen html -->

    <!-- For calendar -->
    <div id="calPopupHelper" class="hidden"></div>
    @include('includes.partials.waiting_shield')
    {!! Form::hidden('upcomingTasks', null) !!}

    @if(!isset($subview))
    <div id="app" class="app-sidebar-closed a-s-c">
        <!-- end: HEADER -->
        
        <!-- side bar -->
{{-- {{dd(session()->get('adminData')->account_type,session()->all())}} --}}
        @include( session()->get('adminData')->account_type == 'Super Admin' ? 'super-admin.layout.nav' : 'layouts.includes.sidebar')
       

        <!-- start: APP CONTENT -->
        <div class="app-content">

            <!-- top nav bar -->
            
            @include('layouts.includes.navbar')   
            

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
        @include('includes.partials.reminder_modal')
        <!--End: Modal for upcoming task reminder -->

        <!-- Start: Modal for expried service -->
        @include('includes.partials.expired_service_modal')
        <!-- Start: Modal for expried service -->    
    </div>
    @else
    @yield('content')
    @endif
    <!-- start: MAIN JAVASCRIPTS -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
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
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js') !!}
    
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

{!! Html::script('assets/js/set-moment-timezone.js') !!}
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
        type: '{{ Auth::user()->account_type }}',
        id: {{ Auth::user()->account_id }},
        userId: {{ Auth::id() }},
        name: '{{ Auth::user()->fullName }}'
    };

    
    $(document).ready(function(){
        var businessExist= '<?php echo Session::has('businessId'); ?>';
        if(businessExist){
            getUpcomingTasks();
            getExpriedService();
        }
    })
    
</script>
<!-- end: JavaScript Event Handlers for this page -->

@yield('script-after-page-handler')

<!-- Start: lock screen js -->
{!! Html::script('assets/js/lock-screen.js') !!}
<!-- End: lock screen js -->
 <!-- GOogle Places Api -->
    <script>
        var placeSearch, autocomplete;
        var componentForm = {
          street_number: 'short_name',
          route: 'long_name',
          sublocality_level_1: 'long_name',
          locality: 'long_name',
          administrative_area_level_1: 'short_name',
          country: 'short_name',
          postal_code: 'short_name'
        };
        function initAutocomplete() {
          autocomplete = new google.maps.places.Autocomplete(
              document.getElementById('autocomplete'), {types: ['geocode']});
          autocomplete.setFields(['address_component']);
          autocomplete.addListener('place_changed', fillInAddress);
        }
        function fillInAddress() {
          var place = autocomplete.getPlace();
          $('input[name="address_line_one"]').val('');
          $('input[name="address_line_two"]').val('');
          $('input[name="city"]').val('');
          $('input[name="postal_code"]').val(val);
          $('.countries').selectpicker('refresh');
          $('select.states').selectpicker('refresh');
          var streetNumber = route = sublocality_level_1 = city = stateCode = countryCode = postalCode = '';
          for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            var val = place.address_components[i][componentForm[addressType]];
            if (addressType == 'street_number') {
                streetNumber = val;
            }else if(addressType == 'route'){
                route = val;
            }else if(addressType == 'sublocality_level_1'){
                sublocality_level_1 = val;
            }else if(addressType == 'locality'){
                city = val;
            }else if(addressType == 'administrative_area_level_1'){
                stateCode = val;
            }else if(addressType == 'country'){
                countryCode = val;
            }else if(addressType == 'postal_code'){
                postalCode = val;
            }
          }
            $('input[name="address_line_one"]').val(streetNumber+' '+route);
            $('input[name="address_line_two"]').val(sublocality_level_1);
            $('input[name="city"]').val(city);
            $('.countries option').each(function(){
                if($(this).val() == countryCode){
                    $(this).attr('selected','selected');
                    $('.countries').trigger('change');
                    var country_code = countryCode,
                        selectedStates = $('select.states');
                        
                    if(country_code == "" || country_code == "undefined" || country_code == null){
                        selectedStates.html('<option value="">-- Select --</option>');
                        selectedStates.selectpicker('refresh');
                    }
                    else{       
                        $.ajax({
                            url: public_url+'countries/'+country_code,
                            method: "get",
                            data: {},
                            success: function(data) {
                                var defaultState = stateCode,
                                    formGroup = selectedStates.closest('.form-group');

                                selectedStates.html("");
                                $.each(data, function(val, text){
                                    var option = '<option value="' + val + '"';
                                    if(defaultState != '' && defaultState != null && val == defaultState)
                                        option += ' selected';
                                    option += '>' + text + '</option>';
                                    selectedStates.append(option);
                                });

                                $('.countries').selectpicker('refresh');
                                selectedStates.selectpicker('refresh');
                                setFieldValid(formGroup, formGroup.find('span.help-block'))
                            }
                        });
                    }
                }
            });
            $('input[name="postal_code"]').val(postalCode);
        }
        function geolocate() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
              var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
              };
              var circle = new google.maps.Circle(
                  {center: geolocation, radius: position.coords.accuracy});
              autocomplete.setBounds(circle.getBounds());
            });
          }
        }
    </script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg&libraries=places&callback=initAutocomplete"
        async defer></script>

    <script type="text/javascript">
        var d = new Date();
        var n = d.getTime();
        $('script').each(function() {
            var attrSrc = $(this).attr("src");
            if(attrSrc != undefined && attrSrc != ''){
                var src = $.trim($(this).attr('src'));
                $(this).attr('src', src+'?v='+n);
            }
        });
    </script>
</body>
</html>