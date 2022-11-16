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
{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap datepicker -->
{!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}
<!-- end: Bootstrap datepicker -->

<!-- start: Bootstrap datetimepicker -->
<!--{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}-->
<!-- end: Bootstrap datetimepicker -->

<!-- Start: NEW timepicker css -->  
{!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
<!-- End: NEW timepicker css -->

<!-- start: Bootstrap daterangepicker -->
{!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
<!-- end: Bootstrap daterangepicker -->

<!-- start: JCrop -->
{!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
<!-- end: JCrop -->

<!-- start: Jquery File Upload -->
{!! Html::style('assets/plugins/jquery-file-upload2/css/jquery.fileupload-ui.css') !!}
<!-- end: Jquery File Upload -->

<!-- start: Sweet Alert -->
{!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
<!-- end: Sweet Alert -->
@stop()

@section('page-title')
   {{ isset($business)?'Edit':'Create' }} Business
@stop

@section('content')
<!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->

<!-- start: Add More Model -->
@include('includes.partials.add_more_modal')
<!-- end: Add More Model -->

<!-- start: Add More Model -->
@include('includes.partials.add-edit-subcategory')
<!-- end: Add More Model -->

<div id="form-container" class="container-fluid container-fullw bg-white subviewPar">
    <div class="row">
        <div class="col-md-12">
            <h5 class="over-title margin-bottom-15">Business <span class="text-bold">setup</span></h5>
            <p>
                Some textboxes in this example is required.
            </p>
            <!-- start: WIZARD FORM -->
            <div>
                <div id="wizard" class="swMain"> <!--data-is-edit="{{ isset($business)?true:false }}"-->
                	<!--<iframe id="frame" src="" width="100%" height="700"></iframe>-->
                    <div id="subview" class="subview">
                    	<iframe id="iframe"></iframe>
                    </div>
                    <!-- start: WIZARD SEPS -->
                    <ul>
                        <li>
                            <a href="#step-1">
                                <div class="stepNumber">
                                    1
                                </div>
                                <span class="stepDesc"><small> Business Details </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-2">
                                <div class="stepNumber">
                                    2
                                </div>
                                <span class="stepDesc"> <small> Location Details </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-3">
                                <div class="stepNumber">
                                    3
                                </div>
                                <span class="stepDesc"> <small> Staff Details </small> </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-4">
                                <div class="stepNumber">
                                    4
                                </div>
                                <span class="stepDesc"> <small> Service Details </small> </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-5">
                                <div class="stepNumber">
                                    5
                                </div>
                                <span class="stepDesc"> <small> Product Details </small> </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-6">
                                <div class="stepNumber">
                                    6
                                </div>
                                <span class="stepDesc"> <small> Client Details </small> </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-7">
                                <div class="stepNumber">
                                    7
                                </div>
                                <span class="stepDesc"> <small> Contact Details </small> </span>
                            </a>
                        </li>
                    </ul>
                    <!-- end: WIZARD SEPS -->

                    <!-- start: FORM WIZARD ACCORDION -->
                    <div class="panel-group epic-accordion" id="epic-accordion">
                    	<!-- start: FORM WIZARD PANEL 1 -->
                        <div class="panel panel-white scrollToTop">
                            <div class="panel-heading" data-step="1">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Business Details <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-up pull-right js-chevron"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <!-- start: WIZARD STEP 1 -->
                                <div id="step-1">
                                    @include('Settings.business.form')
                                </div>
                                <!-- end: WIZARD STEP 7 -->
                            </div>
                        </div>
                        <!-- end: FORM WIZARD PANEL 1 -->
                        
                        <!-- start: FORM WIZARD PANEL 2 -->
                        <div class="panel panel-white scrollToTop">
                            <div class="panel-heading" data-step="2"><!--Change id 3 to 2-->
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Location Details <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-up pull-right js-chevron"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <!-- start: WIZARD STEP 2 -->
                                <div id="step-2"><!--Change id 3 to 2-->
                                    @include('Settings.location.form')
                                </div>
                                <!-- end: WIZARD STEP 2 -->
                            </div>
                        </div>
                        <!-- end: FORM WIZARD PANEL 2 -->
                        
                    	<!-- start: FORM WIZARD PANEL 3 -->
                        <div class="panel panel-white scrollToTop">
                            <div class="panel-heading" data-step="3">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Staff Details <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-up pull-right js-chevron"></i></span>
                                </h5>
                            </div>

                            <div class="panel-body">
                                <!-- start: WIZARD STEP 3 -->
                                <div id="step-3">
                                    @include('Settings.staff.form')
                                </div>
                                <!-- end: WIZARD STEP 3 -->
                            </div>
                        </div>
                        <!-- end: FORM WIZARD PANEL 3 -->
                        
                    	<!-- start: FORM WIZARD PANEL 4 -->
                        <div class="panel panel-white scrollToTop">
                            <div class="panel-heading" data-step="4">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Service Details <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-up pull-right js-chevron"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <!-- start: WIZARD STEP 4 -->
                                <div id="step-4">
                                    @include('Settings.service.form')
                                </div>
                                <!-- end: WIZARD STEP 4 -->
                            </div>
                        </div>
                        <!-- end: FORM WIZARD PANEL 4 -->
                        
                        <!-- start: FORM WIZARD PANEL 5 -->
                        <div class="panel panel-white scrollToTop">
                            <div class="panel-heading"  data-step="5">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Product Details <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-up pull-right js-chevron"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <!-- start: WIZARD STEP 5 -->
                                <div id="step-5">
                                    @include('Settings.product.form')
                                </div>
                                <!-- end: WIZARD STEP 5 -->
                            </div>
                        </div>
                        <!-- end: FORM WIZARD PANEL 5 -->

                        <!-- start: FORM WIZARD PANEL 6 -->
                        <div class="panel panel-white scrollToTop">
                            <div class="panel-heading" data-step="6">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Client Details <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-up pull-right js-chevron"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <!-- end: WIZARD STEP 6 -->
                                <div id="step-6">
                                    @include('Settings.client.form')
                                </div>
                                <!-- end: WIZARD STEP 6 -->
                            </div>
                        </div>
                        <!-- end: FORM WIZARD PANEL 6 -->

                        <!-- start: FORM WIZARD PANEL 7 -->
                        <div class="panel panel-white scrollToTop">
                            <div class="panel-heading"  data-step="7">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Contact Details <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-up pull-right js-chevron"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <!-- start: WIZARD STEP 7 -->
                                <div id="step-7">
                                    @include('Settings.contact.form')
                                </div>
                                <!-- end: WIZARD STEP 7 -->
                            </div>
                        </div>
                        <!-- end: FORM WIZARD PANEL 7 -->

                        <div class="clear-widget"></div>
                    </div>
                    <!-- end: FORM WIZARD ACCORDION -->
                </div>
            </div>
            <!-- end: WIZARD FORM -->
        </div>
    </div>
</div>
@stop()

@section('required-script-for-this-page')
    <script>
        /*var index = window.location.href.lastIndexOf('#')
        if(index != -1){
            var openStep = parseInt(window.location.href.slice(index+1),10);
            if(openStep < 1 || openStep > 7 || isNaN(openStep))
                openStep = 0;
            else
                openStep--;

            $('ul.sub-menu li[data-buss-step="'+(openStep+1)+'"]').addClass('active');
        }
        else
            var openStep = 0;*/
    </script>
    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js') !!}  -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
    {!! Html::script('vendor/jquery-smart-wizard/jquery.smartWizard.js?v='.time()) !!}
    {!! Html::script('assets/js/form-wizard.js?v='.time()) !!}
    {!! Html::script('vendor/jquery-ui/jquery-ui.min.js?v='.time()) !!}

    <!-- start: Bootstrap datepicker -->
    <!--{!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}-->
    <!-- end: Bootstrap datepicker -->
    
    <!-- start: Bootstrap timepicker -->
    <!--{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}-->
    <!-- end: Bootstrap timepicker -->

    <!-- Start:  NEW timepicker js -->
    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!} 
   {!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js?v='.time()) !!}
    <!-- End: NEW timepicker js -->

    <script type="text/javascript">
        /* Start: New time picker */
        $('body').on('focus','.timepicker1',function(){
                $(this).bootstrapMaterialDatePicker({
                date: false,
                shortTime: true,
                format: 'hh:mm A',
            });
        });
        /* End: New time picker */
    </script>

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::script('assets/plugins/bootstrap-daterangepicker/daterangepicker.js?v='.time()) !!}
    <!-- end: Bootstrap daterangepicker -->
    
    <!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time()) !!}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
    <!-- end: JCrop -->

    <!-- start: Sweet Alert -->
    {!! Html::script('vendor/sweetalert/sweet-alert.min.js?v='.time()) !!}
    <!-- end: Sweet Alert -->
    
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
    {!! Html::script('assets/js/business-helper.js?v='.time()) !!}
    {!! Html::script('assets/js/business.js?v='.time()) !!}
    {!! Html::script('assets/js/product.js?v='.time()) !!}
    <!-- start: Jquery File Upload -->
    <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-upload fade">
        <td>
        <span class="preview"></span>
        </td>
        <td>
        <p class="name">{%=file.name%}</p>
        {% if (file.error) { %}
        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
        {% } %}
        </td>
        <td>
        <p class="size">{%=o.formatFileSize(file.size)%}</p>
        {% if (!o.files.error) { %}
        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        {% } %}
        </td>
        <td>
        {% if (!o.files.error && !i && !o.options.autoUpload) { %}
        <button class="btn btn-primary start">
        <i class="glyphicon glyphicon-upload"></i>
        <span>Start</span>
        </button>
        {% } %}
        {% if (!i) { %}
        <button class="btn btn-warning cancel">
        <i class="glyphicon glyphicon-ban-circle"></i>
        <span>Cancel</span>
        </button>
        {% } %}
        </td>
        </tr>
        {% } %}
    </script>
    <script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-download fade">
        <td>
        <span class="preview">
        {% if (file.thumbnailUrl) { %}
        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
        {% } %}
        </span>
        </td>
        <td>
        <p class="name">
        {% if (file.url) { %}
        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
        {% } else { %}
        <span>{%=file.name%}</span>
        {% } %}
        </p>
        {% if (file.error) { %}
        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
        {% } %}
        </td>
        <td>
        <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
        {% if (file.deleteUrl) { %}
        <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
        <i class="glyphicon glyphicon-trash"></i>
        <span>Delete</span>
        </button>
        <input type="checkbox" name="delete" value="1" class="toggle">
        {% } else { %}
        <button class="btn btn-warning cancel">
        <i class="glyphicon glyphicon-ban-circle"></i>
        <span>Cancel</span>
        </button>
        {% } %}
        </td>
        </tr>
        {% } %}
    </script>
    {!! Html::script('assets/plugins/jquery-file-upload2/vendor/jquery.ui.widget.js') !!}
    <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    {!! Html::script('assets/plugins/jquery-file-upload2/vendor/javascript-Load-Image/load-image.all.min.js') !!}
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.iframe-transport.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-process.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-image.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-audio.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-video.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-validate.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-ui.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/main.js') !!} 
    <!-- end: Jquery File Upload -->

    <!-- Start: CKEditor -->
    {!! Html::script('assets/plugins/ckeditor/ckeditor.js') !!}
    {!! Html::script('assets/plugins/ckeditor/adapters/jquery.js') !!}
    {!! Html::script('assets/js/ckeditor.js?v='.time()) !!}
    <!-- End CKEditor -->
@stop()

@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop()