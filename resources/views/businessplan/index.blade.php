
@extends('layouts.app')

<!--Start: Page title/ heading name --> 
@section('page-title')
    Business Plan
@stop
<!--Start: Page title/ heading name --> 

<!--Start: Main body of business plan --> 
@section('content')
 @include('businessplan.form');
@stop
<!--End: Main body of business plan --> 
<!--Start: Script --> 
@section('required-script-for-this-page')
    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js?v='.time()) !!} -->
     
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
    {!! Html::script('vendor/jquery-smart-wizard/jquery.smartWizard.js') !!}

    {!! Html::script('vendor/jquery-ui/jquery-ui.min.js') !!}

    <!-- start: Bootstrap datepicker -->
    <!--{!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}-->
    <!-- end: Bootstrap datepicker -->
    
    <!-- start: Bootstrap timepicker -->
    <!--{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}-->
    <!-- end: Bootstrap timepicker -->

    <!-- Start:  NEW timepicker js -->
    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!} 
    <!-- End: NEW timepicker js -->

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::script('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') !!}
    <!-- end: Bootstrap daterangepicker -->
    
    <!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
    <!-- end: JCrop -->
    {!! Html::script('assets/js/form-wizard-businessplan.js?v='.time()) !!}

    
    {!! Html::script('assets/plugins/ckeditor/ckeditor.js') !!}
     {!! Html::script('assets/plugins/ckeditor/adapters/jquery.js') !!}

    {!! Html::script('assets/js/ckeditor.js?v='.time()) !!}

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

        
@stop
@section('script-handler-for-this-page')
	FormWizardBusinessPlan.init('#bunisesspalnWizard');
	//CKEDITOR.replace('full-editor');
	TextEditor.init();
@stop
<!--End: Script --> 