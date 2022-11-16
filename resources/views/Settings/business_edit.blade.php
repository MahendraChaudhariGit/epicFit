@extends('layouts.app')

@section('meta_description')
@stop()

@section('meta_author')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles-for-this-page')
    <!-- start: Bootstrap Select Master -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    <!-- end: Bootstrap Select Master -->
@stop()

@section('page-title')
   Edit
@stop

@section('content')
<div id="form-container" class="container-fluid container-fullw bg-white subviewPar scrollToTop">
    <div id="subview" class="subview">
    	<iframe id="iframe"></iframe>
    </div>
    <div class="swMain">               
        @yield('form')
    </div>
</div>
@stop()

@section('required-script-for-this-page')
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
    {!! Html::script('vendor/jquery-ui/jquery-ui.min.js?v='.time()) !!}
    <script type="text/javascript">
        $.validator.setDefaults({
            errorElement : "span", // contain the error msg in a small tag
            errorClass : 'help-block',
            errorPlacement : function(error, element) {// render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {// for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
                    error.appendTo($(element).closest('.form-group').children('div'));
                } else {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                }
            },
            ignore : ':hidden',
            success : function(label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error');
            },
            highlight : function(element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').addClass('has-error');
                // add the Bootstrap error class to the control group
            },
            unhighlight : function(element) {// revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            }
        });
    </script>

    <!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time() !!}
    <!-- end: Bootstrap Select Master -->

    {!! Html::script('vendor/moment/moment.min.js?v='.time() !!}

    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js?v='.time() !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time() !!}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time() !!}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: Bootstrap timepicker -->
    {!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js?v='.time() !!}
    <!-- end: Bootstrap timepicker -->

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::script('assets/plugins/bootstrap-daterangepicker/daterangepicker.js?v='.time()) !!}
    <!-- end: Bootstrap daterangepicker -->

    @yield('script')

    {!! Html::script('assets/js/business.js?v='.time()) !!}
@stop()

@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop()