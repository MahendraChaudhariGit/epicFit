@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    @parent
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->
@stop

@section('page-title')
    @if(isset($exercises))
        Clone
    @else
        Add
    @endif
    Exercise
@stop

@section('form')
    <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->

    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    <!-- start: add more item modal -->
    @include('includes.partials.add_more_item_modal')
    <!-- start: add more item modal -->

    @include('ActivityBuilder.form',['data'=>$data,'isClone' => 1])
    
@stop

@section('script')

    {!! Html::script('assets/plugins/ckeditor/ckeditor.js') !!}
     {!! Html::script('assets/plugins/ckeditor/adapters/jquery.js') !!}

    {!! Html::script('assets/js/ckeditor.js') !!}
   
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}

    <script>
        //CKEDITOR.replace('full-editor');
        TextEditor.init();
    </script>
    /* Start: Upload Video File */


    <script type="text/javascript">
        function fileSelectHandlerVideo(elem) {


            var fileInput = elem.files[0];
            var fileUrl = window.URL.createObjectURL(elem.files[0]);
            $(".video").attr("src", fileUrl);



            var oFile = elem.files[0];
            var form_data = new FormData();                  
            form_data.append('fileToUpload', oFile);

            // var file_data = $('#fileToUpload').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
     
            $.ajax({
                url: "{{url('video/save')}}", // point to server-side PHP script
                data: form_data,
                dataType: 'text', 
                type: 'POST',
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                success: function(data) {
                   console.log(data);
                   $("#exerciseVideo").val(data);
                   $("#addFile").html('<i class="fa fa-times"></i> Remove');
                   $("#uploadVideo").addClass("remove-uploaded-video");
                   // $( "#video-area" ).load(window.location.href + " #video-area" );

                }
            });     
        }
    </script>

    <!-- {!! Html::script('assets/js/helper.js?v='.time()) !!} -->
    {!! Html::script('assets/js/activity-builder.js?v='.time()) !!}
@stop