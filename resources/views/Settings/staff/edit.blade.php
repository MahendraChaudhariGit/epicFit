@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    @parent

    <!-- start: Bootstrap datepicker -->
    <!--{!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}-->
    <!-- end: Bootstrap datepicker -->

    <!-- start: Bootstrap datetimepicker -->
   <!-- {!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!} -->
    <!-- end: Bootstrap datetimepicker -->

    <!-- Start: NEW timepicker css -->
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css?v='.time()) !!}
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css?v='.time()) !!}
    <!-- End: NEW timepicker css -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
    <!-- end: JCrop -->

    <!-- start: Jquery File Upload -->
    {!! Html::style('assets/plugins/jquery-file-upload2/css/jquery.fileupload-ui.css?v='.time()) !!}
    <!-- end: Jquery File Upload -->
@stop

@if(!isset($subview))
    @section('page-title')
        @if(isset($staff))
            Edit
        @else
            Add
        @endif
        Staff
    @stop
@endif

@section('form')
    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->
    <!-- start: Add More Model -->
    @include('includes.partials.add_more_modal')
    <!-- end: Add More Model -->
    @include('Settings.staff.form')
@stop

@section('script')
    <!-- start: Bootstrap datepicker -->
    <!--{!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}-->
    <!-- end: Bootstrap datepicker -->
    
    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
    <!-- end: JCrop -->

    <!-- Start:  NEW timepicker js -->
    {{-- {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!} --}}
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
    {!! Html::script('assets/plugins/jquery-file-upload2/vendor/jquery.ui.widget.js?v='.time()) !!}
    <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    {!! Html::script('assets/plugins/jquery-file-upload2/vendor/javascript-Load-Image/load-image.all.min.js?v='.time()) !!}
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.iframe-transport.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-process.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-image.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-audio.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-video.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-validate.js') !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-ui.js?v='.time()) !!}
    {!! Html::script('assets/plugins/jquery-file-upload2/main.js?v='.time()) !!} 
    <!-- end: Jquery File Upload -->
@stop()