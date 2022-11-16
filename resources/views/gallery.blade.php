@extends('blank')

@section('plugin-css')
{!! Html::style('assets/css/plugins.css?v='.time()) !!}
<!-- start: Bootstrap datepicker --> 
{!! Html::style('assets/plugins/datepicker/css/datepicker.css?v='.time()) !!}
<!-- end: Bootstrap datepicker -->

<!-- Start: NEW timepicker css -->  
{!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

<!-- Start: NEW datetimepicker css -->
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css?v='.time()) !!}
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css?v='.time()) !!}
<!-- End: NEW datetimepicker css -->

<!-- start: Bootstrap calendar --> 
{!! Html::style('assets/css/goal-buddy.css?v='.time()) !!}
<!-- start: Bootstrap calendar --> 

<!-- Start: Activities planner -->
{!! HTML::style('assets/plugins/fitness-planner/custom/style.css?v='.time()) !!}
{!! Html::style('assets/plugins/fitness-planner/css/api.css?v='.time()) !!}
<!-- End: Activities planner -->

<!-- Start: Invoice Modal -->
{!! HTML::style('assets/css/invoice.css?v='.time()) !!} 
<!-- End: Invoice Modal -->
@php
use App\Http\Controllers\Mobile_Detect;
$detect = new Mobile_Detect;
@endphp
<style>

    .backto_dashboard a {

border: none;

color: #fff;

text-decoration: none;

transition: background .5s ease;

-moz-transition: background .5s ease;

-webkit-transition: background .5s ease;

-o-transition: background .5s ease;

display: inline-block;

cursor: pointer;

outline: none;

text-align: center;

background:#253746;

position: relative;

font-size: 14px;

font-weight: 600;

-webkit-border-radius: 3px;

-moz-border-radius: 3px;

-ms-border-radius: 3px;

border-radius: 3px;

line-height: 1;

padding: 12px 30px;

}

</style>
<style type="text/css">
    .pac-container{
        z-index: 9999;
    }
</style>
<style>
    #recurClassDeleteModal{
        z-index: 99999 !important;
    }
</style>
@stop

@section('page-title')
@stop

@section('content')
<div id="waitingShield" class="text-center waitingShield" data-slug="" style="display: none;">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>

<div id="GalleryBeforeAfter" class="tab-pane">
    

    <div class="galleryformsection">
          <form method="POST" action="{{ url('') }}" enctype="multipart/form-data" id="save-final-progress-form">
            @csrf
        <div class="top_field">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input type="text" name="title" placeholder="Title" class="form-control" required  data-parsley-trigger="focusout" data-parsley-required-message="Title is required" required value="{{$gallery->title}}" id="title">
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input type="date" name="date" placeholder="Title" class="form-control" id="date" value="{{$gallery->date}}">
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="beforeCheckfield" value="before" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="" @if($gallery->image_type == 'before') checked @endif>
                        <label for="beforeCheckfield">
                            <strong>Before</strong> 
                        </label>
                   </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="afterCheckfield" value="after" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="" @if($gallery->image_type == 'after') checked @endif>
                        <label for="afterCheckfield">
                            <strong>After</strong> 
                        </label>
                   </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="progressionCheckfield" value="progression" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="" @if($gallery->image_type == 'progression') checked @endif>
                        <label for="progressionCheckfield">
                            <strong>Progression</strong> 
                        </label>                    
                   </div>
                </div>
                <input type="hidden" name="" id="image_type" value="{{$gallery->image_type}}">
                <input type="hidden" name="" id="data-uploaded-image-type">
                <input type="hidden" name="selected_pose_type" id="selected_pose_type">
                <input type="hidden" name="last_selected_image_type" id="last_selected_image_type" value="{{$gallery->image_type}}">
                <input type="hidden" name="client_id" id="client_id" value="{{$gallery->client_id}}">
                <input type="hidden" name="gallery_id" id="gallery-id" value="{{$gallery->gallery_id}}">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="m-b-0">
                        <input type="radio" name="image_type" id="galleryCheckfield" value="other" class="email-login"  data-old-login-with-email="1" data-parsley-multiple="" @if($gallery->image_type == 'other') checked @endif>
                        <label for="galleryCheckfield">
                            <strong>Other</strong> 
                        </label>                    
                   </div>
                </div>
            </div>
        </div>
        <div class="galleryDragAndDrop">
            @php
            if($gallery->image_type == 'other')
            {
                $show = 'show_button';
            }
            else
            {
                $show = '';
            }

            @endphp
            <div id="pose_type_div" class="col-md-4 col-md-offset-4 {{$show}}">
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
            </span></div>
        </div><br>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <ul class="gallerylist" id="preview-uploaded-images">
                </ul>
            </div>
            <div class="col-md-12 text-right" style="display:none;" id="save-progress-btn">
                
                  <div class="form-group">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input id="admin_manage" type="checkbox" name="only_admin_manage" value="yes"  @if($gallery->only_admin_manage == 'yes') checked @endif>
                        <label for="admin_manage">
                           Only admin can manage this
                        </label>
                    </div>
                    
                </div>
            <button class="btn btn-primary">Submit</button>
            <!-- Trigger the modal with a button -->
            </div>
        </div>
    </form>
    <div class="backto_dashboard">

        <a  href="javascript:history.go(-1)" class="btn">Back to Gallery</a>

    </div>
    </div>
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
@section('script')

{!! Html::script('assets/js/form-wizard-clients.js?v='.time()) !!}
{!! Html::script('assets/js/form-wizard-benchmark.js?v='.time()) !!}
{!! Html::script('assets/js/form-wizard-movement.js?v='.time()) !!}
{!! Html::script('assets/js/form-wizard-goal-buddy.js?v='.time()) !!}
{!! Html::script('assets/js/benchmark.js?v='.time()) !!}
<!-- {!! Html::script('assets/js/benchmark-helper.js') !!} -->

<!-- start: Summernote -->
{!! Html::script('assets/plugins/summernote/dist/summernote.min.js?v='.time()) !!}
<!-- end: Summernote -->

<!-- start: Rating -->
{!! Html::script('assets/plugins/bootstrap-rating/bootstrap-rating.min.js?v='.time()) !!}
<!-- end: Rating -->

<!-- start: Bootstrap Typeahead -->
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time()) !!}  
<!-- end: Bootstrap Typeahead --> 

<!-- start: Bootstrap timepicker
{!! Html::script('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}
end: Bootstrap timepicker -->

<!-- Start:  NEW datetimepicker js -->
{!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js?v='.time()) !!}
<!-- End: NEW datetimepicker js -->

<!-- Start:  NEW timepicker js -->
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!} -->
<!-- End: NEW timepicker js -->

<!-- start: Bootstrap calendar -->
{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js?v='.time()) !!}
<!-- end: Bootstrap calendar -->

<!-- start: Dirty Form -->
{!! Html::script('assets/js/dirty-form.js?v='.time()) !!}
<!-- end: Dirty Form -->

{!! Html::script('assets/js/helper.js?v='.time()) !!}

<!-- start: Events -->
<script>    
    popoverContainer = $('#client-overview');
</script>

<!-- {!! Html::script('assets/js/calendar.js?v='.time()) !!} -->

<!-- end: Events -->

<!-- start: Details update realtime -->
{!! Html::script('assets/js/edit-field-realtime.js?v='.time()) !!}
<!-- end: Details update realtime -->

<!-- start: Client-Membership Modal -->
{!! Html::script('assets/js/client-membership.js?v='.time()) !!}
<!-- end: Client-Membership Modal -->

<!-- start: goal buddy -->
{!! Html::script('assets/js/goal-buddy.js?v='.time()) !!}
{!! Html::script('assets/js/goal-buddy-calendar.js?v='.time()) !!} 
<!-- end: goal buddy -->

<!-- Start: Movement -->
{!! Html::script('assets/js/movement.js?v='.time()) !!}
<!-- End: Movement -->

<!-- Start: Activity Planner -->
{!! Html::script('assets/js/fitness-planner/api.js?v='.time()) !!} 
{!! Html::script('assets/js/fitness-planner/bodymapper.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/jquery.json-2.4.min.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.placeholder.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.ui.touch-punch.min.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/jwplayer/jwplayer.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/js/jquery.ui.labeledslider.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/popup.js?v=1') !!}
{!! Html::script('assets/js/fitness-planner/fitness-planner.js?v='.time()) !!}
<!-- ENd: Activity Planner -->

<script>
$('input').parsley();
$('#save-final-progress-form').parsley();
var gallery_id = $('#gallery-id').val();
var client_id = $('#client-id').val();
var title = $('#title').val();
var form_data = new FormData();
form_data.append('gallery_id',gallery_id);
form_data.append('image_data_uploaded',$('#data-uploaded-image-type').val());
form_data.append('ajax_show_gallery','yes');
uploadImage(form_data,gallery_id); 

var file_exist;
var data_uploaded_image_type;
var last_selected_image_type = $('#last_selected_image_type').val();
var now = new Date();
var day = ("0" + now.getDate()).slice(-2);
var month = ("0" + (now.getMonth() + 1)).slice(-2);
var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
// $('#date').val(today);

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
        var validImageTypes = ["image/jpeg","image/png","image/jpg"];
        var file = e.originalEvent.dataTransfer.files;
        file_exist = file[0];
        var fileType = file[0]['type'];
        if(selected_pose_type.length === 0)
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
            replaceExistProgressPhoto(file[0],selected_image_type,selected_pose_type,date,client_id,gallery_id,title);

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
            replaceExistProgressPhoto(file,selected_image_type,selected_pose_type,date,client_id,gallery_id,title);
            // $.ajax({
            // headers: {
            // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            // },
            // method:"POST",
            // url:"{{url('client/check-progress-photo-exist')}}",
            // data : 
            // {
            //     client_id : client_id,
            //     image_type : selected_image_type,
            //     pose_type :  selected_pose_type
            // },
            // success: function(data) {
            //     if(data.status == true)
            //     {console.log(data.data);
            //         $('#exist-progress-photo-msg').empty();
            //         $('#progress-photo-replace-btn').attr('data-id',data.data.id);
            //         $('<p><i class="fa fa-exclamation-circle"></i> There is already a photo for the '+data.data.pose_type+' pose on '+data.data.date+'. Are you sure you wish to replace this one?</p>').appendTo('#exist-progress-photo-msg');
            //         $('#progress-photo-exist-modal').modal();
            //     }
            //     else
            //     {
            //         var form_data = new FormData();
            //         form_data.append('file',file);
            //         form_data.append('image_type',selected_image_type);
            //         form_data.append('pose_type',selected_pose_type);
            //         form_data.append('date',date);
            //         form_data.append('client_id',client_id);
            //         uploadImage(form_data); 
            //     }
            // }
            // });
            
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
        uploadImage(form_data,gallery_id); 

    });
});

// Sending AJAX request and upload file
function uploadImage(form_data,gallery_id){

    $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method:"POST",
            url:"{{url('client/ajax-show-gallery')}}"+'/'+gallery_id,
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
                        $('#data-uploaded-image-type').val('no');

                    }
                    $('#preview-uploaded-images').empty();
                    for(var i=0;i<total_file;i++)
                    {

                        var url = "{{asset('/')}}"+"result/final-progress-photos/"+data.data[i]['image'];
                        console.log(data.data[i]['id']);
                        if(data.data[i]['pose_type'] != null)
                        {
                            var pose_type = data.data[i]['pose_type'].toUpperCase();
                        }
                        else
                        {
                            var pose_type = '';
                        }


                    $('#preview-uploaded-images').append("<li><div class='galleyIMG'><div class='view-photo-modal' id='"+data.data[i]['id']+"'></div></div><div class='date'>"+data.data[i]['date']+"</div><div class='pose'>"+data.data[i]['pose_type'].toUpperCase()+"</div><h3>"+data.data[i]['image_type'].toUpperCase()+"</h3></li><div class='modal fade' id='view-modal"+data.data[i]['id']+"' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'><div class='modal-dialog'><div class='modal-content gallerymodel'><div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button><h4 class='modal-title'>Preview photo</h4></div><div class='modal-body'><div class='preview-photo'><div class='photolist'><div class='photo-b-a'><img alt='picture' src='"+url+"' class='img-fluid'/></div></div></div><div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>Close</button><button type='button' class='btn btn-default delete-preview-img' data-dismiss='modal' data-id='"+data.data[i]['id']+"'>Delete</button></div></div> </div></div></div>");
                      $('.view-photo-modal').css('background-image', 'url(' +url+ ')');
                    }
                    // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    // location.reload();
                }
                else
                {
                    data_uploaded_image_type = 'no';
                    $('#data-uploaded-image-type').val('no');
                    $('#preview-uploaded-images').empty();
                    $('#save-progress-btn').hide();
                }
            }
            }); 
}

// Added thumbnail
function replaceExistProgressPhoto(file,selected_image_type,selected_pose_type,date,client_id,gallery_id,title)
{
    $.ajax({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    },
    method:"POST",
    url:"{{url('client/check-gallery-photo-exist')}}",
    data : 
    {
        client_id : client_id,
        image_type : selected_image_type,
        pose_type :  selected_pose_type,
        gallery_id : gallery_id
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
            form_data.append('gallery_id',gallery_id);
            form_data.append('title',title);
            form_data.append('temp_created','yes');
            uploadImage(form_data,gallery_id); 
        }
    }
    });
}


$("input[type='radio']").on('click',function(){
    var image_type = $(this).val();
    // console.log(image_type);
    $('#image_type').val(image_type);
    if(image_type == 'other')
    {
        $('#selected_pose_type').val('other');
        $('#pose_type_div').hide();
    }
    else
    {
        $('#pose_type').prop('selectedIndex',0);
        $('#selected_pose_type').val('');
        $('#pose_type_div').show();
    }
    // else
    // {
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
            $('#pose_type').prop('selectedIndex',0);
            $('#selected_pose_type').val('');
            last_selected_image_type = image_type;
            var client_id = $('#client_id').val();
            var form_data = new FormData();
            var selected_image_type = $('#image_type').val();
            form_data.append('client_id',client_id);
            form_data.append('temp_remove_all_photos','yes');
            uploadImage(form_data,gallery_id); 
        } 
        else{
            $('#pose_type').prop('selectedIndex',0);
            $('#selected_pose_type').val('');
            console.log(last_selected_image_type);
            $(this).prop('checked', false);
            if(last_selected_image_type == 'before')
            {
                $('#pose_type_div').show();
                $('#beforeCheckfield').prop('checked',true);
                $('#image_type').val(last_selected_image_type);
            }
            else if(last_selected_image_type == 'after')
            {
                $('#pose_type_div').show();
                $('#afterCheckfield').prop('checked',true);
                $('#image_type').val(last_selected_image_type);

            }
            else if(last_selected_image_type == 'progression')
            {
                $('#pose_type_div').show();
                $('#progressionCheckfield').prop('checked',true);
                $('#image_type').val(last_selected_image_type);

            }
            else if(last_selected_image_type == 'other')
            {
                $('#pose_type_div').hide();
                $('#galleryCheckfield').prop('checked',true);
                $('#image_type').val(last_selected_image_type);
            }
        }
        });
        }
        else
        {
            last_selected_image_type = $('#last_selected_image_type').val();
        }
        // $('#pose_type_div').show();
    // }
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
    uploadImage(form_data,gallery_id); 
});



</script>

@stop

@section('script-handler-for-this-page')
FormWizard.init();
FormWizardBenchMark.init('#benchmarkWizard');
FormWizardGoalBuddy.init('#goalBuddyWizard');
@stop