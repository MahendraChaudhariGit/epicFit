@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    @parent
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->
@stop

@section('page-title')
  Add Video
@stop

@section('form')
    <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->
    {!! Form::open(['id' => 'video-form', 'class'=>'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @include('ActivityBuilder.videos.form',['abWorkouts' => $abWorkouts])
    
@stop

@section('script')
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    /* Start: Upload Video File */


    <script type="text/javascript">
        function fileSelectHandlerVideo(elem) {

            toggleWaitShield('show');
            var fileInput = elem.files[0];
            var fileUrl = window.URL.createObjectURL(elem.files[0]);
            $(".video").attr("src", fileUrl);



            var oFile = elem.files[0];
            var form_data = new FormData();                  
            form_data.append('fileToUpload', oFile);
            form_data.append('source', 'actvityVideo');

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
                   var data = JSON.parse(data);
                   $("#activityVideo").val(data.name);
                   $("#addFile").html('<i class="fa fa-times"></i> Remove');
                   $("#uploadVideo").addClass("remove-uploaded-video");
                   $('#appt-time').val(data.videoDuration);
                   toggleWaitShield('hide');
                }
            });     
        }
        $('body').on('click', '.remove-video', function(e){
			e.preventDefault();
			var $this = $(this),
				preVidPath = $this.find('source').attr('src'),
				preVid = preVidPath.substring(preVidPath.lastIndexOf("/")+1);

			$.post("{{url('/remove/video')}}", {preVid:preVid,source:'activityVideo'}, function(response){
				var data = JSON.parse(response);
				if(data.status == 'success')
				{
					$this.remove();
                    $( "#video-area" ).load(window.location.href + " #video-area" );
                    $('#appt-time').val("00:00:00");
				}else{
                    swal('something went wrong!');
                }
			})
        });
        $('#video-form').validate();
        $('.saveVideoBtn').click(function(){
            if($('#video-form').valid()){
                var formData = {};
                formData['title'] = $('input[name="title"]').val();
                formData['description'] = $('textarea[name="description"]').val();
                formData['workout_id'] = $('select[name="workout_id"]').val();
                formData['video'] = $('#activityVideo').val();
                formData['thumbnail'] = $('#thumbnail').val();
                formData['video_duration'] = $('#appt-time').val();
                var videoMovements = $('.movementRow').find('.add-movement-section');
                var videoMovementData = [];
                videoMovements.each(function(){
                    var time = $(this).find('input[name="movement_time_h"]').val()+':'+$(this).find('input[name="movement_time_m"]').val()+':'+$(this).find('input[name="movement_time_s"]').val();
                    videoMovementData.push({'name':$(this).find('input[name="movement_name"]').val(),'time':time});
                });
                formData['movementData'] = videoMovementData;
                toggleWaitShield("show");
                $.post("{{route('videos.store')}}",formData,function(response){
                    toggleWaitShield("hide");
                    if(response.status == 'ok'){
                        swal({
                            title: 'Success',
                            text:'Video Added Successfully',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            confirmButtonText: 'ok',
                            confirmButtonColor: '#ff4401'
                        }, 
                        function(isConfirm){
                            if(isConfirm){
                                window.location.href = "{{route('videos.list')}}";
                            }
                        });
                    }else{
                        console.log(response.error)
                        swal('something went wrong!');
                    }
                },'json')
            }
        });
    </script>
    <script>
        $(document).ready(function(){
          $(".add-movement-btn").click(function(){
    
            var add_div=$('<div class="add-movement-section col-md-12 alert"> <div class="col-md-6 pr-0"> <div class="form-group "> <input type="text" name="movement_name" class="form-control" value="" placeholder="Enter movement"> </div> </div> <div class="col-md-2 pr-0"> <div class="form-group "> <input type="text" class="form-control" name="movement_time_h" value="00" required> </div> </div> <div class="col-md-2 pr-0"> <div class="form-group "> <div class="form-group"> <span class="time">:</span> <input type="text" class="form-control ms" name="movement_time_m" value="00" required> </div> </div> </div> <div class="col-md-2 pr-0"> <div class="form-group"> <span class="time">:</span> <input type="text" class="form-control ms" value="00" name="movement_time_s" required> </div> </div> <a href="#" class="close close-movement-section" data-dismiss="alert" aria-label="close">&times;</a></div>');
    
            $(".movementRow").append(add_div);
        });
    
      });
    </script>
@stop