@extends('Result.masters.app')
@section('page-title')
    <span >Gallery </span> 
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
<!-- <div id="measerment-page" class="tabbable">  -->
    <!-- <ul  class="nav nav-tabs tab-padding tab-space-3 tab-blue ">
        <li class="active">
            <a  href="#Gallery" data-toggle="tab" id="gallery-tab">Gallery</a></li>
        <li><a href="#Before" data-toggle="tab" id="before-after-tab">Before After</a></li>
    </ul> -->

 <!--    <div class="tab-content clearfix">
        <div class="tab-pane active" id="Gallery">
            <div class="row">
                <div class="col-md-12 addphotolbtn">
                    <form method="POST" action="{{ url('measurements/add-gallery-image') }}" id="add-gallery-image-form" enctype="multipart/form-data">
                    @csrf
                        <input type="file" name="images[]" multiple="multiple"  id="upload-gallery-images">
                        <input type="hidden" name="images_name" id="images_name">
                    </form>
                    <label for="upload-gallery-images" class="mb-2">Add Gallery
                        <i class="fa fa-plus"></i></label> <span id="valid_file_msg" style="color: red;display: none;"><b>File must be png,jpg,jpeg type</b></span>
                    @if (session()->has('message'))
                    <div class="alert alert-info note-text" id="success_message">
                    {{ session('message') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="gallery-lightbox">
                    @if($gallery)
                    @foreach($gallery as $value)
                    <div class="col-md-3 col-sm-4 col-xs-6 gallerybox">
                        <div class="galleyIMG">
                            <img alt="picture" src="{{asset('result/gallery-images')}}/{{$value->image}}" class="img-fluid">
                        </div>
                        <h3>{{$value->image_name}}</h3>
                    </div>
                    @endforeach
                    @else
                    <div>No record found</div>
                    @endif
                </div>
            </div>
        {{$gallery->render()}}
        </div>
        <div class="tab-pane" id="Before">
            <div class="beforeafter-section">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-primary mb-2 add-before-after-btn" href="#addBeforeAfter" data-toggle="modal" id="add-before-after-btn" data-type="add">Add Before and after
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="scroll">
                <table width="100%" cellpadding="0" cellspacing="0" class="table-border">
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Before Image</th>
                        <th>After Image</th>
                        <th>Action</th>
                    </tr>
                    @if($before_after)
                    @foreach($before_after as $value)
                    <tr>
                        <td>
                            {{date('Y-m-d',strtotime($value->created_at))}}
                        </td>
                        <td>
                            {{$value->title}}
                        </td>
                        <td>
                            @if($value->before_image)
                             <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->before_image}}" class="img-fluid" style="height: 130px" />
                             @endif
                        </td>
                        <td>
                            @if($value->after_image)
                            <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->after_image}}" class="img-fluid" style="height: 130px" />
                            @endif
                        </td>
                        <td>
                            @if($value->before_image || $value->after_image)
                            <a class="actiontext view-after-before" href="#viewAfterBefore" data-toggle="modal" data-id="{{$value->id}}">View</a>
                            @endif
                            <a class="actiontext  add-before-after-btn" href="#editAfterBefore" data-toggle="modal"  data-id="{{$value->id}}" data-type="edit" data-item="{{$value->title}}">Edit</a>
                            <a class="actiontext delete-before-after-btn" href="#deleteAfterBefore" data-toggle="modal" data-id="{{$value->id}}">Delete</a>
                        </td>
                    </tr>
                  
                    <div class="modal fade" id="view-after-before-modal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content gallerymodel">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                    <h4 class="modal-title">Preview photo</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="preview-photo">
                                        <h3>{{$value->title}}</h3>
                                        <div class="photolist">
                                            <div class="photo-b-a">
                                            @if($value->before_image)
                                                <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->before_image}}" class="img-fluid" />
                                            @endif
                                            </div>
                                            <div class="photo-b-a">
                                            @if($value->after_image)
                                                <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->after_image}}" class="img-fluid" />
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        @endforeach
                        @else
                        <span>No record found</span>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div> -->


<!-- </div> -->
        <div id="GalleryBeforeAfter" class="tab-pane">
            <div class="row">
                <!-- <div class="col-md-12 addphotolbtn">        
                    <a href="{{url('measurements/add-progress')}}/{{Auth::User()->account_id}}"><label class="mb-2">Add Gallery <i class="fa fa-plus"></i></label></a>
                    <span id="valid_file_msg" style="color: red;display: none;"><b>File must be png,jpg,jpeg type</b>
                    </span>
                </div> -->
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <ul class="gallerylist">
                    @if($progress)
                    @foreach($progress as $value)
                    <li>
                    <div class="galleyIMG">
                    <h3>{{ucfirst($value->title)}}</h3>
                    <!-- <img alt="picture" src="{{asset('result/final-progress-photos')}}/{{$value->image}}" class="img-fluid show-gallery-img" data-id="{{$value->id}}" data-item="{{$value->image}}"> -->
                     <div class="show-gallery-img" data-id="{{$value->id}}" data-item="{{$value->image}}" style="background-image: url({{asset('result/final-progress-photos')}}/{{$value->image}});">
                           
                       </div>
                    </div>
                    <div class="date"> {{date('d-m-Y',strtotime($value->date))}}</div>
                    <div class="pose">   {{ucfirst($value->pose_type)}}</div>
                      
                    <h3>{{ucfirst($value->image_type)}}</h3>
                    <!--   view Model start -->
                    <div class="modal fade" id="view-gallery-image{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content gallerymodel">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                    <h4 class="modal-title">{{ucfirst($value->title)}}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="preview-photo">
                                        <h3>{{ucfirst($value->pose_type)}} Pose</h3>
                                        <div class="photolist">
                                            <div class="photo-b-a">
                                                <img alt="picture" src="{{asset('result/final-progress-photos')}}/{{$value->image}}" class="img-fluid "/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <!-- <button type="button" class="btn btn-default delete-gallery-img" data-dismiss="modal" data-id="{{$value->id}}" data-item="{{$value->image}}">Delete</button> -->
                                <a href="{{ url('measurements/download-gallery-image') }}/{{$value->id}}"  class="btn btn-default" data-id="{{$value->id}}">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- view modal end  -->
                    </li>
                    @endforeach
                    @endif

                    </ul>
                </div>
            </div>

</div>
@endsection



<!--   Add Gallery Model start -->
<div class="modal fade" id="gallery-images-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content gallerymodel">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Upload image</h4>
            </div>
            <div class="modal-body">
                <div class="uploadlist">
                    <ul id="preview-gallery-images">
                        <li>
                            <div class="row">
                                
                            </div>
                        </li>
                    </ul>  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="upload-images-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!--   Add Gallery Model end -->

<!--   Add before after Model start -->
<div class="modal fade" id="add-before-after-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ url('measurements/add-before-after') }}" id="add-before-after-form" enctype="multipart/form-data">
        @csrf
        <div class="modal-content gallerymodel">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Add before and after photo</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required  data-parsley-trigger="focusout" data-parsley-required-message="Title is required">
                    <input type="hidden" name="before_after_id" id="before-after-id" value="">
                </div>
                <div class="form-group">
                    <label>Choose before photo</label>
                    <div class="addphotolbtn">
                        <input type="file" class="before"  name="before_image" id="before-image-upload" data-item="before" data-type="upload">
                        <label for="before-image-upload" class="mb-2">Upload Photo <i class="fa fa-plus"></i></label>

                        <!-- <input type="hidden" class="before" name="before_image_capture" id="before-image-capture"> -->
                        <input type="file" class="before" accept="image/*" capture name="before_image_capture" id="before-image-capture" data-item="before" data-type="capture">
                        <!-- <label for="beforeTakeimg" class="mb-2 openWebcam" data-item='before'>Take Photo <i class="fa fa-plus"></i></label> -->
                        @if ( $detect->isMobile() || $detect->isTablet() ) 
                        <label for="before-image-capture" class="mb-2" data-item='before'>Take Photo <i class="fa fa-plus"></i></label>
                        @endif
                        <span id="before_success_message"></span>
                    </div>
                    <span id="before-msg"></span>
                </div>
                <div class="form-group">
                    <label>Choose after photo</label>
                    <div class="addphotolbtn">
                        <input type="file" class="after" name="after_image" id="after-image-upload" data-item="after" data-type="upload">
                        <label for="after-image-upload" class="mb-2">Upload Photo <i class="fa fa-plus"></i></label>
                        
                        <!-- <input type="hidden" class="after" name="after_image_capture" id="after-image-capture"> -->
                         <input type="file" class="after" accept="image/*" capture name="after_image_capture" id="after-image-capture" data-item="after" data-type="capture">
                        <!-- <label for="afterTakeimg" class="mb-2 openWebcam" data-item="after">Take Photo <i class="fa fa-plus"></i></label> -->
                        @if ( $detect->isMobile() || $detect->isTablet() ) 
                        <label for="after-image-capture" class="mb-2" data-item="after">Take Photo <i class="fa fa-plus"></i></label>
                        @endif
                        <span id="after_success_message"></span>
                    </div>
                    <span id="after-msg"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close-before-after">Close</button>
                <button type="button" class="btn btn-primary before-after-save-btn" id="add-before-after-save">Save</button>
            </div>
        </div>
    </form>
    </div>
</div>
<!--   Add before after Model end -->




<!--   view before after Model start -->

<!-- Modal for webcam -->
<div class="modal fade" id="webcam-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Click a Picture</h4>
            </div>
            <div class="modal-body">
                <div id="camera" class="camera_section"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-webcam">Cancel</button>
                <button type="button" class="btn btn-info snap">Take picture</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal for webcam -->

@section('custom-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://parsleyjs.org/dist/parsley.js"></script>
<script>
    var modal_type = '';
    var modal_id = '';
    var url = window.location.href;
    var index = url.indexOf("#");
    if (index !== -1) 
    {
    var hash = url.substring(index + 1);
    }

    if(hash == 'Before')
    {         
        $("#Before").addClass('active');
        $("#Gallery").removeClass('active');
        $('#before-after-tab').trigger('click');
    }
    else if(hash == 'Gallery')
    {
        $("#Before").removeClass('active');
        $("#Gallery").addClass('active');
        $('#gallery-tab').trigger('click');
    }

    $("#gallery-tab").click(function() 
    {
        // window.location.hash = 'Gallery';    
    }); 
    $("#before-after-tab").click(function() 
    {
        // window.location.hash = 'Before';    
    });

    $('input').parsley();
    $(window).load(function() {
    $("#waitingShield").show();
    setTimeout(function () 
    {
        $("#waitingShield").hide();   
    },1000);
    });
    setTimeout(function() {
    $('#success_message').fadeOut("slow");
    }, 3000 );
    var validImageTypes = ["image/jpeg","image/png","image/jpg"];
    
    $("#upload-gallery-images").change(function(){
    $("#valid_file_msg").hide();
    $('#preview-gallery-images').html("");

    var total_file=document.getElementById("upload-gallery-images").files.length;
    var valid_file = true;
    for(var i=0;i<total_file;i++)
    {
        var file = event.target.files[i];
        var fileType = file['type'];
        if($.inArray(fileType, validImageTypes) < 0)
        {
            valid_file = false;
        }
        else
        {
            $('#preview-gallery-images').append("<li><div class='row'><div class='col-md-3 text-center'><img src='"+URL.createObjectURL(event.target.files[i])+"' class='img-fluid'></div><div class='col-md-8'><input type='text' name='images_name[]' class='form-control' required  data-parsley-trigger='focusout' data-parsley-required-message='Image name is required' placeholder='Enter image name'> </div></div></li>");
        }
    }
        if(valid_file === true)
        {
            $("#gallery-images-modal").modal();      
        }
        else
        {
            $('#preview-gallery-images').html("");
            $("#valid_file_msg").show();
        }
    });
    $('#upload-images-btn').click(function () 
    {
        var isValid = true;
        $('#gallery-images-modal input').each( function() 
        {
            if ($(this).parsley().validate() !== true)
            {
                isValid = false;
            }
        });
        if(isValid)
        {
            $("#images_name").val($("input[name='images_name[]']").map(function(){return $(this).val();}).get());
            $('#add-gallery-image-form').submit();
            $("#waitingShield").show();
        }
    });

 /* Take photo from camera js */
    Webcam.set({
    width: 320,
    height: 240,
    image_format: 'jpeg',
    jpeg_quality: 90,
    constraints: constraints,
    });
    $('.openWebcam').on('click',function(e) {
        capture_image_type = $(this).attr('data-item');
        if(capture_image_type === 'before' || capture_image_type === 'after' )
        {
            $('#'+capture_image_type+'-msg').empty();
            $('#'+capture_image_type+'-image-upload').val('');
        }
    $('#webcam-modal').modal('show');
    Webcam.attach('#camera');
    });
    $('.snap').on('click',function()
    {
        Webcam.snap(function(data_uri) 
        {
            $('#'+capture_image_type+'_success_message').empty();
            $('#'+capture_image_type+'-image-capture').val(data_uri);
            Webcam.reset();
            $('#webcam-modal').modal('hide');
            $('<span>'+'Photo uploaded successfully'+'</span>').appendTo("#"+capture_image_type+"_success_message").css('color','green');
        });
    });
    $('.close-webcam').click(function(){
        Webcam.reset();
        $('#webcam-modal').modal('hide');
    });

    /* Manage image uload after and before */
    $('.add-before-after-btn').on('click',function(){
        modal_type = $(this).attr('data-type');
        title = $(this).attr('data-item');
        var before_after_id = $(this).attr('data-id');
        if(modal_type == 'edit')
        {
            $("#before-after-id").val(before_after_id);
            $("input[name='title']").val(title);
        }
        else
        {
            $("#before-after-id").val('');
            $("input[name='title']").val('');
        }
        $('#add-before-after-modal').modal();
    });
    var upload_image_type = ''
    $("input[type='file']").on('click',function()
    {
        upload_image_type = $(this).attr('data-item');
        upload_image_medium = $(this).attr('data-type');
        if(upload_image_type === 'before' || upload_image_type === 'after' )
        {
            $('#'+upload_image_type+'-image-'+upload_image_medium).change(function()
            {
                var validImageTypes = ["image/jpeg", "image/png","image/jpg"];
                var file = this.files[0]
                var fileType = file['type'];
                if($.inArray(fileType, validImageTypes) < 0)
                {
                    $('#'+upload_image_type+'_success_message').empty();
                   $('#'+upload_image_type+'-msg').empty();
                    $('#'+upload_image_type+'-image-upload').val('');
                    $('#'+upload_image_type+'-image-capture').val('');
                   $('<p>' + 'Image must be png,jpg,jpge type' + '</p>').appendTo("#"+upload_image_type+"-msg").css('color','red');
                }
                else
                {
                    $('#'+upload_image_type+'_success_message').empty();
                    $('<span>'+'Photo uploaded successfully'+'</span>').appendTo("#"+upload_image_type+"_success_message").css('color','green');
                    $('#'+upload_image_type+'-msg').empty();
                    if(upload_image_medium == 'upload')
                    {
                        $('#'+upload_image_type+'-image-capture').val(null);   
                    }
                    else
                    {
                        $('#'+upload_image_type+'-image-upload').val(null);   

                    }
                }

            });
        }
    });

    $('.before-after-save-btn').click(function () 
    {
        $("#before-msg").empty();
        $("#after-msg").empty();
        var  before_available = true;
        var  after_available = true;
        var  before_image_upload = $("#before-image-upload").val();
        var  before_image_capture = $("#before-image-capture").val();
        var  after_image_upload = $("#after-image-upload").val();
        var  after_image_capture = $("#after-image-capture").val();
        if(modal_type == 'add')
        {
            if(before_image_upload == '' && before_image_capture == '')
            {
                // before_available = false;
                // $('<p>' + 'Before image is required' + '</p>').appendTo("#before-msg").css('color','red');
                
            }
            if(after_image_upload == '' && after_image_capture == '')
            {
                // after_available = false;
                // $('<p>' + 'After image is required' + '</p>').appendTo("#after-msg").css('color','red');

            }   
        }
        var isValid = true;
        $('#add-before-after-modal input').each( function() 
        {
            if ($(this).parsley().validate() !== true)
            {
                isValid = false;
            }
        });
        if(isValid && before_available && after_available)
        {
            $('#add-before-after-form').submit();
            $("#waitingShield").show();
        }
    });

    $('#add-before-after-modal').on('hidden.bs.modal', function () 
    {
        $('#after-msg').empty();
        $('#before-msg').empty();
        $('.parsley-required').empty();
    });
    
    /* View afer-before uploaded image*/
    $('.view-after-before').on('click',function(){
        var modal_id = $(this).attr('data-id');
        $('#view-after-before-modal'+modal_id).modal();
    });


    /* --------- delete before-after --------------------*/
    $(document).on('click','.delete-before-after-btn', function ()
    {
        var before_after_id = $(this).attr('data-id');
        Swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
        })
        .then((result) => {
        if(result.value) 
        { 
            $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method:"POST",
            url:"{{url('measurements/delete-before-after')}}",
            data : 
            { 
                before_after_id : before_after_id
            },
            success: function(data) {
                if(data.status == true)
                {
                    Swal.fire(
                    'Deleted!',
                     data.message,
                    'success'
                    )
                    location.reload();
                }
            }
            });   
        } 
        else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire(
        'Cancelled',
        'Your file is safe',
        'error'
        )
        }
        });
    });

    $(document).on('click','.show-gallery-img',function(){
    var id = $(this).attr('data-id');
    $('#view-gallery-image'+id).modal();
    });

    $(document).on('click','.delete-gallery-img',function()
    {
        var id = $(this).attr('data-id');
        var image = $(this).attr('data-item');
        $.ajax({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        method:"POST",
        url:"{{url('measurements/delete-gallery-image')}}",
        data : 
        {
            id : id,
            image : image
        },
        success: function(data) {
            if(data.status == true)
            {
                location.reload();
            }
        }
        });
    });


</script>
@stop