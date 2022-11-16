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
    {!! Html::style('assets/css/clip-two/main-navigation.css?v='.time()) !!}
    {!! Html::style('assets/css/plugins.css?v='.time()) !!}
    {!! Html::style('assets/css/themes/theme-orange.css?v='.time()) !!}
    <!-- end: CLIP-TWO CSS -->
    
    {!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
    {!! Html::style('assets/css/custom.css?v='.time()) !!}

    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: Bootstrap Select Master -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Bootstrap datepicker -->
    {!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}
    <!-- end: Bootstrap datepicker -->

    <!-- start: Bootstrap datetimepicker -->
    <!--{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}-->
    <!-- end: Bootstrap datetimepicker -->

    <!-- Start: NEW timepicker css -->  
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
    <!-- End: NEW timepicker css -->

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

    {!! Html::script('vendor/jquery/jquery.min.js') !!}
    {!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}
</head>
<!-- end: HEAD -->
<body class="bg-white">
<!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->

@yield('content')

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
{!! Html::script('vendor/moment/moment.min.js') !!}
{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('vendor/jquery-smart-wizard/jquery.smartWizard.js') !!}
{!! Html::script('assets/js/form-wizard.js?v='.time()) !!}
{!! Html::script('vendor/jquery-ui/jquery-ui.min.js') !!}

<!-- start: Bootstrap datepicker -->
{!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
<!-- end: Bootstrap datepicker -->

<!-- start: Bootstrap timepicker -->
<!--{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}-->
<!-- end: Bootstrap timepicker -->

<!-- Start:  NEW timepicker js -->
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!}
<!-- End: NEW timepicker js --> 

<!-- start: Bootstrap daterangepicker -->
{!! Html::script('assets/plugins/bootstrap-daterangepicker/daterangepicker.js?v='.time()) !!}
<!-- end: Bootstrap daterangepicker -->

<!-- start: Bootstrap Select Master -->
{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Country Code Selector -->
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
<!-- end: Country Code Selector -->

<!-- start: Bootstrap Typeahead -->
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
<!-- end: Bootstrap Typeahead -->

<!-- start: JCrop -->
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
<!-- end: JCrop -->

{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/business-helper.js?v='.time()) !!}
{!! Html::script('assets/js/business.js?v='.time()) !!}
<!-- end: JavaScript required for this page -->

<!-- start: JavaScript Event Handlers for this page -->
<script>
    var initValidator = function (){
        $.validator.setDefaults({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            ignore: ':hidden',
            rules: {
                //firstName: {
                //    minlength: 2,
                //    required: true
                //},
                //lastName: {
                //    minlength: 2,
                //    required: true
                //},
                // email: {
                //    required: true,
                //    email: true
                //},
                //password: {
                //    minlength: 6,
                //    required: true
                //},
                //password2: {
                //    required: true,
                //    minlength: 5,
                //    equalTo: "#password"
                //}
            },
            messages: {
                firstName: "First Name is required",
                lastName: "Last Name is required"
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });
    };
    jQuery(document).ready(function() {
        Main.init();
        initValidator();
    });
</script>
<!-- end: JavaScript Event Handlers for this page -->
</body>
</html>