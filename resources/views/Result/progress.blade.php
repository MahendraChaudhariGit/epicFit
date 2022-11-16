@extends('Result.masters.app')
@section('page-title')
    <span >Weight And Date </span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css?v='.time()) !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css?v='.time()) !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css?v='.time()) !!}
{!! Html::style('result/plugins/bootstrap-datepicker/css/datepicker.css?v='.time()) !!}
{!! Html::style('result/plugins/nestable-cliptwo/jquery.nestable.css?v='.time()) !!}


<!-- {!! Html::style('result/plugins/sweetalert/sweet-alert.css?v='.time()) !!} -->

{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}


{!! Html::style('result/css/custom.css?v='.time()) !!}

@php
use App\Http\Controllers\Mobile_Detect;
$detect = new Mobile_Detect;
@endphp

<style>
    .swMain.wizard-headding-style > ul{position:static;margin-bottom:25px}
    .wizard-headding-style .control-label{text-align:left}
</style>
<style type="text/css">
#app .app-content{
    height: auto;
}
.app-sidebar-fixed footer {
    margin-left: 134px !important;
}
</style>

<!-- VpForm -->
{!! Html::style('result/vendor/vp-form/css/vp-form.css?v='.time()) !!}
@stop
@section('content')

<div id="waitingShield" class="text-center" style="display: none;">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>
<div id="GalleryBeforeAfter" class="tab-pane">
    

    <div class="galleryformsection">
          <form method="POST" action="{{ url('measurements/save-final-progress') }}" enctype="multipart/form-data" id="save-final-progress-form">
            @csrf
        <div class="top_field">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input type="text" name="title" placeholder="Title" class="form-control" required  data-parsley-trigger="focusout" data-parsley-required-message="Title is required" required>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input type="date" name="date" placeholder="Title" class="form-control" id="date">
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="beforeCheckfield" value="before" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="">
                        <label for="beforeCheckfield">
                            <strong>Before</strong> 
                        </label>
                   </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="afterCheckfield" value="after" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="">
                        <label for="afterCheckfield">
                            <strong>After</strong> 
                        </label>
                   </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="progressionCheckfield" value="progression" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="">
                        <label for="progressionCheckfield">
                            <strong>Progression</strong> 
                        </label>                    
                   </div>
                </div>
                <input type="hidden" name="" id="image_type">
                <input type="hidden" name="selected_pose_type" id="selected_pose_type">
                <input type="hidden" name="client_id" id="client_id" value="{{$client_id}}">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="galleryCheckfield" value="other" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="">
                        <label for="galleryCheckfield">
                            <strong>Other</strong> 
                        </label>                    
                   </div>
                </div>
            </div>
        </div>
        <div class="galleryDragAndDrop">
            <div id="pose_type_div" class="col-md-4 col-md-offset-4">
                <div class="form-group">
                <select name="pose_type" id="pose_type" class="form-control">
                    <option value="">Select pose</option>
                    <option value="front">Front</option>
                    <option value="right">Right</option>
                    <option value="back">Back</option>
                    <option value="left">Left</option>
                </select>
            </div>
            </div><div class="col-md-12">
            <input type="file" name="file" id="upload-file">
            <input type="hidden" name="drag_file" id="drag-file">
            <label for="upload-file" class="drag-area">
                <img src="{{asset('/1.png')}}">                
                <div class="text upload-area" >
                    Drag image here<br> Or <br>Click here to choose a file.
                </div>
            </label><br>
            <span id="valid_file_msg" style="color: red;display: none;"><b>File must be png,jpg,jpeg type</b>
            </span>
        </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <ul class="gallerylist" id="preview-uploaded-images">
                </ul>
            </div>
            <div class="col-md-12 text-right" style="display:none;" id="save-progress-btn">
            <button class="btn btn-primary">Submit</button>
            <!-- Trigger the modal with a button -->
            </div>
        </div>
    </form>
    </div>
</div>

<!-- Modal -->
<div id="progress-photo-exist-modal" class="modal fade modal-add-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">

        <div class="row">
         <div class="col-md-6 col-md-offset-3" id="exist-progress-photo-msg">
           
           
       </div>

   </div>
</div>
<div class="modal-footer">
   <button type="button" class="btn btn-primary" data-dismiss="modal" id="progress-photo-replace-btn" data-id="">Yes</button>
   <button type="button" class="btn btn-default" data-dismiss="modal" id="progress-msg-modal-close-btn">No</button>
</div>
</div>

</div>
</div>
@endsection

@section('custom-script')
<script src="https://parsleyjs.org/dist/parsley.js"></script>
<script>
$('input').parsley();
$('#save-final-progress-form').parsley();
var client_id = $('#client_id').val();
var form_data = new FormData();
form_data.append('client_id',client_id);
form_data.append('remove_uploaded_photo','yes');
uploadImage(form_data); 

var file_exist;
var data_uploaded_image_type;
var last_selected_image_type;
var now = new Date();
var day = ("0" + now.getDate()).slice(-2);
var month = ("0" + (now.getMonth() + 1)).slice(-2);
var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
$('#date').val(today);

    $(function() {

    // preventing page from redirecting
    $(".drag-area").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        // $(".drag-area").text("Drag here");
    });

    $(".drag-area").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $('.upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        // $("h1").text("Drop");
    });

    // Drag over
    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        // $("h1").text("Drop");
    });

    // Drop
    $('.upload-area').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var selected_pose_type = $('#selected_pose_type').val();
        var selected_image_type =  $('#image_type').val();
        var validImageTypes = ["image/jpeg","image/png","image/jpg"];
        var file = e.originalEvent.dataTransfer.files;
        file_exist = file[0];
        var fileType = file[0]['type'];
        if(selected_pose_type.length === 0 && selected_image_type != 'other')
        {
            swal("Warning", "Please select pose type first", "warning");
        }
        else if($.inArray(fileType, validImageTypes) < 0)
        {
            $("#valid_file_msg").show();
        }
        else
        {

            $("#drag-file").val('yes');
            $("#valid_file_msg").hide();
            var selected_image_type = $('#image_type').val();
            var selected_pose_type = $('#selected_pose_type').val();
            var date = $('#date').val();
            var client_id = $('#client_id').val();
            replaceExistProgressPhoto(file[0],selected_image_type,selected_pose_type,date,client_id);


            // var form_data = new FormData();
            // form_data.append('file',file[0]);
            // form_data.append('image_type',selected_image_type);
            // form_data.append('pose_type',selected_pose_type);
            // form_data.append('date',date);
            // form_data.append('client_id',client_id);
            // uploadImage(form_data);   
        }
    });

    // Open file selector on div click
    $("#uploadfile").click(function(){
        $("#file").click();
    });

    // file selected
    $("#upload-file").change(function(){
        var validImageTypes = ["image/jpeg","image/png","image/jpg"];
        var file = $('#upload-file')[0].files[0];
        file_exist = $('#upload-file')[0].files[0];
        var fileType = file['type'];
        var selected_image_type =  $('#image_type').val();
        var selected_pose_type = $('#selected_pose_type').val();
        if(selected_pose_type.length === 0 && selected_image_type != 'other')
        {
            swal("Warning", "Please select pose type first", "warning");
        }
        else if($.inArray(fileType, validImageTypes) < 0)
        {
            $("#valid_file_msg").show();
        }
        else
        {
            $("#valid_file_msg").hide();
            var selected_image_type = $('#image_type').val();
            var selected_pose_type = $('#selected_pose_type').val();
            var date = $('#date').val();
            var client_id = $('#client_id').val();
            replaceExistProgressPhoto(file,selected_image_type,selected_pose_type,date,client_id);
        }
    });

    $('#progress-photo-replace-btn').on('click',function(){
        var id = $(this).attr('data-id');
        var client_id = $('#client_id').val();
        var file = file_exist;
        var form_data = new FormData();
        form_data.append('file',file);
        form_data.append('client_id',client_id);
        form_data.append('is_exist','yes');
        form_data.append('id',id);
        uploadImage(form_data); 

    });
});

// Sending AJAX request and upload file
function uploadImage(form_data){

    $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method:"POST",
            url:"{{url('measurements/save-progress')}}",
            data : form_data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                if(data.status == true && data.data != null)
                {
                    var total_file = data.data.length;
                    if(total_file > 0)
                    {
                        $('#save-progress-btn').show();
                        data_uploaded_image_type = 'yes';
                    }
                    else
                    {
                        $('#save-progress-btn').hide();
                        data_uploaded_image_type = 'no';

                    }
                    $('#preview-uploaded-images').empty();
                    for(var i=0;i<total_file;i++)
                    {

                        var url = "{{asset('/')}}"+"result/temp-progress-photos/"+data.data[i]['image'];
                        console.log(data.data[i]['pose_type']);
                        if(data.data[i]['pose_type'] != null)
                        {
                            var pose_type = data.data[i]['pose_type'].toUpperCase();
                        }
                        else
                        {
                            var pose_type = '';
                        }



                    $('#preview-uploaded-images').append("<li><div alt='picture' class='galleyIMG'><div class='view-photo-modal' id='"+data.data[i]['id']+"'></div></div><div class='date'>"+data.data[i]['date']+"</div><div class='pose'>"+pose_type+"</div><h3>"+data.data[i]['image_type'].toUpperCase()+"</h3></li><div class='modal fade' id='view-modal"+data.data[i]['id']+"' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'><div class='modal-dialog'><div class='modal-content gallerymodel'><div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button><h4 class='modal-title'>Preview photo</h4></div><div class='modal-body'><div class='preview-photo'><div class='photolist'><div class='photo-b-a'><img alt='picture' src='"+url+"' class='img-fluid' /></div></div></div><div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>Close</button><button type='button' class='btn btn-default delete-preview-img' data-dismiss='modal' data-id='"+data.data[i]['id']+"'>Delete</button></div></div> </div></div></div>");
                     $('.view-photo-modal').css('background-image', 'url(' +url+ ')');
                    }
                    // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    // location.reload();
                }
                else
                {
                    data_uploaded_image_type = 'no';
                    $('#preview-uploaded-images').empty();
                    $('#save-progress-btn').hide();
                }
            }
            }); 
}

// Added thumbnail
function replaceExistProgressPhoto(file,selected_image_type,selected_pose_type,date,client_id)
{
    $.ajax({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    },
    method:"POST",
    url:"{{url('measurements/check-progress-photo-exist')}}",
    data : 
    {
        client_id : client_id,
        image_type : selected_image_type,
        pose_type :  selected_pose_type
    },
    success: function(data) {
        if(data.status == true)
        {console.log(data.data);
            $('#exist-progress-photo-msg').empty();
            $('#progress-photo-replace-btn').attr('data-id',data.data.id);
            $('<p><i class="fa fa-exclamation-circle"></i> There is already a photo for the '+data.data.pose_type+' pose on '+data.data.date+'. Are you sure you wish to replace this one?</p>').appendTo('#exist-progress-photo-msg');
            $('#progress-photo-exist-modal').modal();
        }
        else
        {
            var form_data = new FormData();
            form_data.append('file',file);
            form_data.append('image_type',selected_image_type);
            form_data.append('pose_type',selected_pose_type);
            form_data.append('date',date);
            form_data.append('client_id',client_id);
            uploadImage(form_data); 
        }
    }
    });
}


$("input[type='radio']").on('click',function(){
    var image_type = $(this).val();
    $('#image_type').val(image_type);
    if(image_type == 'other')
    {
        $('#selected_pose_type').val('other');
        $('#pose_type_div').hide();
    }
    else
    {
        if(data_uploaded_image_type == 'yes')
        {
        swal({
        title: "Do you want to change image type ? if you click on 'YES' button then all data will be deleted for previous image type section if exist",
        type: 'warning',
        allowEscapeKey: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText:'No',
        confirmButtonColor: '#ff4401'
        },
        function(isConfirm) {
        if(isConfirm) 
        {  
            last_selected_image_type = image_type;
            var client_id = $('#client_id').val();
            var form_data = new FormData();
            var selected_image_type = $('#image_type').val();
            form_data.append('client_id',client_id);
            form_data.append('remove_uploaded_photo','yes');
            uploadImage(form_data); 
        } 
        else{
            $(this).prop('checked', false);
            if(last_selected_image_type == 'before')
            {
                $('#beforeCheckfield').prop('checked',true);
                $('#image_type').val(last_selected_image_type);
            }
            else if(last_selected_image_type == 'after')
            {
                $('#afterCheckfield').prop('checked',true);
                $('#image_type').val(last_selected_image_type);

            }
            else if(last_selected_image_type == 'progression')
            {
                $('#progressionCheckfield').prop('checked',true);
                $('#image_type').val(last_selected_image_type);

            }
        }
        });
        }
        else
        {
            last_selected_image_type = image_type;
        }
        $('#pose_type_div').show();
    }
});

$('#pose_type').change(function(){ 

    $('#selected_pose_type').val($(this).val());
    var selected_image_type = $('#image_type').val();
    if(selected_image_type.length === 0)
    {
        $('#pose_type').prop('selectedIndex',0);
        $('#selected_pose_type').val('');
        swal("Warning", "Please select image type first(i.e:Before,After,Progression)", "warning");
    }
});

/* View image*/
$(document).on('click','.view-photo-modal',function(){
    var modal_id = $(this).attr('id');
    console.log(modal_id);
    $('#view-modal'+modal_id).modal();
});

/* delete image*/
$(document).on('click','.delete-preview-img',function(){
    var id = $(this).attr('data-id');
    var client_id = $('#client_id').val();
    var form_data = new FormData();
    form_data.append('client_id',client_id);
    form_data.append('id',id);
    form_data.append('delete_preview_img','yes');
    uploadImage(form_data); 
});



</script>
@stop