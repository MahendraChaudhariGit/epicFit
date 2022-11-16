@if(isset($allnotes))

@if(isSuperUser())
  @include('includes.partials.delete_form')
@endif
<style>
      .custom-file-input {
  color: transparent;
}
.custom-file-input::-webkit-file-upload-button {
  visibility: hidden;
}
.custom-file-input::before {
content: 'upload images';
    color: #253746;
    display: inline-block;
    background: url(../../../../assets/images/upload-icon.png) #e6e6e6 no-repeat;
    border-radius: 3px;
    padding: 7px 8px;
    outline: none;
    white-space: nowrap;
    -webkit-user-select: none;
    cursor: pointer;
    text-shadow: 1px 1px #fff;
    font-weight: 700;
    font-size: 10pt;
    width: 100%;
    background-size: 10%;
    background-position: right;
}
.custom-file-input:hover::before {
  border-color: black;
}
.custom-file-input{
  padding: 0px;
}
</style>
<div class="page-header">
    <h1>Notes
    <div class="pull-right">
        <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#notesCategory" type="button">Add Category</button>  -->
        <a href="{{ route('note.getCat') }}" class="btn btn-primary add-more" data-modal-title="Notes Categories" data-field="noteCat">Manage Categories</a>   
        <a class="btn btn-primary check-notes-btn" data-toggle="modal" href="#notesModal" type="button">
         Add notes
        </a>
    </div>
    </h1>
</div>
<div class="tabbable tabs-left" style="min-height: 500px;">
    <ul class="nav nav-tabs col-sm-4 col-md-3 noteCat">
        <!-- <li class="">
            <a href="#notesCategory" data-toggle="modal"  type="button" >
                Add Category
            </a>
        </li> -->
        <li class="active">
            <a href="#contact-notes" data-toggle="tab">
                Contact
            </a>
        </li>
        <li class="">
            <a href="#general-notes" data-toggle="tab">
                General
            </a>
        </li>
        <li class="">
            <a href="#makeup-notes" data-toggle="tab">
                Makeup
            </a>
        </li>
        <li class="">
            <a href="#upload-note" data-toggle="tab">
                Uploads
            </a>
        </li>
        @if($notesCat)
        @foreach($notesCat as $key => $value)
        <li class="">
            <a href="#{{ $key }}-notes" data-toggle="tab">
                {{ $value }}
            </a>
        </li>
        @endforeach
        @endif
        <!-- <li class="">
            <a href="#notesCategory" data-toggle="modal"  type="button">
                Add Category
            </a>
        </li> -->
        
    </ul>
    <div class="tab-content " style="min-height: 500px;" id="noteTabField"> <!-- mh-300 -->
        <div class="tab-pane fade in active" id="contact-notes">
         @foreach($allnotes as $allnote)
            @if($allnote->cn_type=='contact')
                <div class="contact-{{$allnote->cn_id}}">
                    <p>
                        @if($allnote->cn_source)
                            <small>({!! $allnote->cn_source !!})</small>
                        @endif
                    </p>
                    <p>
                         {!! $allnote->cn_notes !!}
                    </p>
                    <p> 
                        <small> Created on: {{ setLocalToBusinessTimeZone($allnote->created_at, 'dateString') }} 
                        </small>
                        @if(isSuperUser())
                            <a href="#notesModal" class="contact-edit text-primary m-r-10 m-l-10 check-notes-btn" data-toggle="modal" data-notes="{!! $allnote->cn_notes !!}" data-notesid="{{ $allnote->cn_id }}" data-type="contact">
                                <i class="fa fa-pencil"></i>
                            </a>    
                            <!-- <a href="{{ route('clients-notes.destroy', $allnote->cn_id) }}" class="text-primary delLink" data-entity="note">
                                <i class="fa fa-trash-o"></i>
                            </a> -->
                            <a href="#" class="text-primary delete-notes" data-notesid="{{$allnote->cn_id}}" data-notestype="contact">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        @endif
                    </p>
                    
                    <hr class="notes-hr">
                </div>
            @endif    
         @endforeach
        </div>
        <div class="tab-pane fade" id="general-notes">
         @foreach($allnotes as $allnote)
             @if($allnote->cn_type=='general')
                <div class="general-{{$allnote->cn_id}}">
                    <p>
                        @if($allnote->cn_source)
                            <small>({!! $allnote->cn_source !!})</small>
                        @endif
                    </p>
                    <p>
                         {!! $allnote->cn_notes !!}
                    </p>
                    <p> 
                        <small> Created on: {{ setLocalToBusinessTimeZone($allnote->created_at, 'dateString') }}</small>
                        @if(isSuperUser())
                            <a href="#notesModal" class="general-edit text-primary m-r-10 m-l-10 check-notes-btn" data-toggle="modal" data-notes="{!! $allnote->cn_notes !!}" data-notesid="{{ $allnote->cn_id }}" data-type="general">
                                <i class="fa fa-pencil"></i>
                            </a>    
                            <!-- <a href="{{ route('clients-notes.destroy', $allnote->cn_id) }}" class="text-primary delLink" data-entity="note">
                                <i class="fa fa-trash-o"></i>
                            </a> -->

                            <a href="#" class="text-primary delete-notes" data-notesid="{{$allnote->cn_id}}" data-notestype="general">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        @endif
                    </p>
                    <hr class="notes-hr">   
                </div>
            @endif    
         @endforeach
        </div>
        <div class="tab-pane fade " id="makeup-notes">
         @foreach($allnotes as $allnote)
             @if($allnote->cn_type=='makeup')
                <div class="makeup-{{$allnote->cn_id}}">
                    <p>
                        @if($allnote->cn_source)
                            <small>({!! $allnote->cn_source !!})</small>
                        @endif
                    </p>
                    <p>
                         {!! $allnote->cn_notes !!}
                    </p>
                    <p>
                       <small>Created on: {{ setLocalToBusinessTimeZone($allnote->created_at, 'dateString') }}</small>
                        @if(isSuperUser())
                            <a href="#notesModal" class="makeup-edit text-primary m-r-10 m-l-10 check-notes-btn" data-toggle="modal" data-notes="{!! $allnote->cn_notes !!}" data-notesid="{{ $allnote->cn_id }}" data-type="makeup">
                                <i class="fa fa-pencil"></i>
                            </a>    
                            <!-- <a href="{{ route('clients-notes.destroy', $allnote->cn_id) }}" class="text-primary delLink" data-entity="note">
                                <i class="fa fa-trash-o"></i>
                            </a> -->
                            <a href="#" class="text-primary delete-notes" data-notesid="{{$allnote->cn_id}}" data-notestype="makeup">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        @endif
                    </p>
                   <hr class="notes-hr">
                </div>
            @endif    
         @endforeach
        </div>
        <div class="tab-pane fade" id="upload-note">
           
            <div class="row file-listing container-fluid">
                <div class="col-xs-12" style="margin-bottom: 10px;">
                    <a class="btn btn-primary float-right add-file" href="javascript:void(0)"><i class="fa fa-plus-circle" aria-hidden="true"></i> Upload file </a>
                </div>
                <div class="col-xs-12" style="padding: 0px">
            <table class="table table-striped table-bordered table-hover m-t-10" id="data-table">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Date</th>
                        <th>File name</th>
                        <th>Description</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody class="dynamic-data">
                   @foreach ($measurement_data as $key => $measurement)
                   <tr>
                      <td> {{ $key + 1 }}  </td>
                      <td> {{ $measurement->created_at }} </td>
                      @php
                          $file_name = explode('.',$measurement->file_name);
                          $original_name = explode('_',$file_name[0])
                      @endphp
                      <td>{{ $original_name[0].'.'.$file_name[1] }}</td>
                      <td>{{ $measurement->description }}</td>
                      <td class="center">
                         <div>
                            <a class="btn btn-xs btn-default tooltips measurement-update" data-id="{{ $measurement->id }}" href="javascript:void(0)" data-placement="top" data-original-title="edit upload">
                                <i class="fa fa-pencil" style="color:#253746;"></i>
                            </a>
                            <a class="btn btn-xs btn-default tooltips" data-id="{{ $measurement->id }}" href="{{ asset('attachment-file/'.$measurement->file_name) }}" data-placement="top" data-original-title="download file" download>
                             <i class="fa fa-download" style="color:#253746;"></i>
                           </a>
                           <a class="btn btn-xs btn-default tooltips measurement-delete" data-id="{{ $measurement->id }}" href="javascript:void(0)" data-placement="top" data-original-title="delete upload" >
                            <i class="fa fa-trash" style="color:#253746;"></i>
                        </a>
                          </div>
                      </td>
                    </tr> 
                     
                   @endforeach
                </tbody>
            </table>
        </div>
        </div>
        <div class="row file-form hidden">
            <div class="col-xs-12" style="margin-bottom: 10px;">
                <a class="btn btn-primary float-right go-listing" href="javascript:void(0)">Go back </a>
            </div>
            <div class="row">
                <div class="col-xs-12  col-md-6 col-md-offset-3">
                    <form id="save-data" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="client_id" value="{{ $client_id }}">
                        <input type="hidden" name="id" id="measurement_id" value="">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div>
                                    <label for="Date" class="strong">Description <span class="text-danger">*</span></label>
                                </div>
                                <textarea class="form-control" required="required" name="description" id="description" type="text"></textarea>
                            </div>

                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div>
                                    <label for="Date" class="strong">Choose file <span class="text-danger">*</span></label>
                                </div>
                                <input type="file" name="file_name" id="file_name" class="form-control custom-file-input select-image" required >
                                <div class="flex flex-col dz-started" >
                                    <span class="ml-2 truncate preview"></span>
                                </div>
                                <img class="img-responsive preview-image hidden" />
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12 text-center">
                            <input type="submit" class="btn btn-primary" value="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        @if($notesCat)
        @foreach($notesCat as $key => $value)
            <div class="tab-pane fade " id="{{ $key }}-notes">

              @foreach($allnotes as $allnote)
                @if($allnote->cn_type==$key)
                <div class="{{$key}}-{{$allnote->cn_id}}">
                    <p>
                        @if($allnote->cn_source)
                            <small>({!! $allnote->cn_source !!})</small>
                        @endif
                    </p>
                    <p>
                         {!! $allnote->cn_notes !!}
                    </p>
                    <p>
                       <small>Created on: {{ setLocalToBusinessTimeZone($allnote->created_at, 'dateString') }}</small>
                        @if(isSuperUser())
                            <a href="#notesModal" class="{{ $key }}-edit text-primary m-r-10 m-l-10 check-notes-btn" data-toggle="modal" data-notes="{!! $allnote->cn_notes !!}" data-notesid="{{ $allnote->cn_id }}" data-type="{{ $key }}">
                                <i class="fa fa-pencil"></i>
                            </a>    
                            <!-- <a href="{{ route('clients-notes.destroy', $allnote->cn_id) }}" class="text-primary delLink" data-entity="note">
                                <i class="fa fa-trash-o"></i>
                            </a> -->
                            <a href="#" class="text-primary delete-notes" data-notesid="{{$allnote->cn_id}}" data-notestype="{{ $key }}">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        @endif
                    </p>
                   <hr class="notes-hr">
                </div>
                @endif    
              @endforeach
            </div>
        @endforeach
        @endif

    </div>
</div>

    
@endif



 
<script>
    public_url = $('meta[name="public_url"]').attr('content');
    $(document).on('click','.add-file',function(){
        $("#measurement_id").val('');
        $("#description").val('');
        $("#file_name").prop('required',true);
        // $('.preview-image').attr('src','');
        // $('.preview-image').addClass('hidden');
        $(".preview").html('');
        $(".file-listing").addClass('hidden');
        $(".file-form").removeClass('hidden');
    })
    $(document).on('click','.go-listing',function(){
        $(".file-form").addClass('hidden');
        $(".file-listing").removeClass('hidden');
    })

    $(document).on('change','.select-image',function(){
        var filename = $(this).val();
        if (filename.substring(3,11) == 'fakepath') {
            filename = filename.substring(12);
        } 
        $(this).siblings('div').children('span').html(filename)
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("submit","#save-data", function(e){
       e.preventDefault();
       let formData = new FormData(this);
       $('#image-input-error').text('');

       $.ajax({
            type:'POST',
            url: `/clients/save/measurement`,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $('#waitingShield').removeClass('hidden');
            },
            success: (response) => {
                if (response.status == true) {
                    this.reset();
                    $(".preview").html('');
                    var trHTML = '';  
                    $.each(response.data, function (index, value) { 
                        var file_name = value.file_name.split('.');
                        var original_name = file_name[0].split('_');
                        trHTML += '<tr>'
                        trHTML += '<td>' + (index+1) + '</td>'
                        trHTML += '<td>' + value.created_at + '</td>'
                        trHTML += '<td>' + original_name[0]+'.'+file_name[1] +'</td>'
                        trHTML += '<td>' + value.description + '</td>'
                        trHTML += '<td>'
                        trHTML += '<div>'
                        trHTML += '<a class="btn btn-xs btn-default tooltips measurement-update" data-id="'+ value.id +'" href="javascript:void(0)" data-placement="top" data-original-title="edit upload">'
                        trHTML += '<i class="fa fa-pencil" style="color:#253746;"></i>'
                        trHTML += '</a>'
                        trHTML += '<a class="btn btn-xs btn-default tooltips" href="'+public_url+'attachment-file/'+value.file_name+'" data-placement="top" data-original-title="download file" download>'
                        trHTML += '<i class="fa fa-download" style="color:#253746;"></i>'
                        trHTML += '</a>'
                        trHTML += '<a class="btn btn-xs btn-default tooltips measurement-delete" data-id="'+ value.id +'" href="javascript:void(0)" data-placement="top" data-original-title="delete upload" >'
                        trHTML += '<i class="fa fa-trash" style="color:#253746;"></i>'
                        trHTML += '</a>'
                        trHTML += '</div>'
                        trHTML += '</td>'
                        trHTML += '</tr>';
                    });  
                    $('.dynamic-data').html(trHTML);
                    $(".file-form").addClass('hidden');
                    $(".file-listing").removeClass('hidden');
                    // $('#data-table').DataTable();
                    swal(response.msg);
                }
            },
            complete: function(){
                $('#waitingShield').addClass('hidden');
            }
       });
  });

  $(document).on('click','.measurement-delete',function(){
      var id = $(this).attr('data-id');
      swal({
		title: 'Are you sure?',
		type: 'warning',
		allowEscapeKey: false,
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		confirmButtonColor: '#ff4401',
		closeOnConfirm: false
	}, 
	function(isConfirm){
		if(isConfirm){
            swal.close();
			$.ajax({
                url: `/clients/delete/measurement/`+id,
                method: 'get',
                beforeSend: function(){
                    $('#waitingShield').removeClass('hidden');
                },
                success:function(response) {
                    if(response.status == true){

                    var trHTML = '';  
                    $.each(response.data, function (index, value) { 
                        var file_name = value.file_name.split('.');
                        var original_name = file_name[0].split('_');
                        trHTML += '<tr>'
                        trHTML += '<td>' + (index+1) + '</td>'
                        trHTML += '<td>' + value.created_at + '</td>'
                        trHTML += '<td>' + original_name[0]+'.'+file_name[1] +'</td>'
                        trHTML += '<td>' + value.description + '</td>'
                        trHTML += '<td>'
                        trHTML += '<div>'
                        trHTML += '<a class="btn btn-xs btn-default tooltips measurement-update" data-id="'+ value.id +'" href="javascript:void(0)" data-placement="top" data-original-title="edit upload">'
                        trHTML += '<i class="fa fa-pencil" style="color:#253746;"></i>'
                        trHTML += '</a>'
                        trHTML += '<a class="btn btn-xs btn-default tooltips" href="'+public_url+'attachment-file/'+value.file_name+'" data-placement="top" data-original-title="download file" download>'
                        trHTML += '<i class="fa fa-download" style="color:#253746;"></i>'
                        trHTML += '</a>'
                        trHTML += '<a class="btn btn-xs btn-default tooltips measurement-delete" data-id="'+ value.id +'" href="javascript:void(0)" data-placement="top" data-original-title="delete upload" >'
                        trHTML += '<i class="fa fa-trash" style="color:#253746;"></i>'
                        trHTML += '</a>'
                        trHTML += '</div>'
                        trHTML += '</td>'
                        trHTML += '</tr>';
                    });  
                    $('.dynamic-data').html(trHTML);
                    $(".file-form").addClass('hidden');
                    $(".file-listing").removeClass('hidden');
                  swal(response.msg);
                  
                }else{
                    swal(response.msg);
                }
                },
                complete: function(){
                    $('#waitingShield').addClass('hidden');
                }
            });
		}
		else{
			swal.close();
		}
	});
  })

  $(document).on('click','.measurement-update',function(){
      var id = $(this).attr('data-id');
      $.ajax({
            type:'get',
            url: `/clients/edit/measurement/`+id,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $('#waitingShield').removeClass('hidden');
            },
            success: (response) => {
                if(response.status == true){
                    $("#measurement_id").val(response.data.id);
                    $("#description").val(response.data.description);
                    $("#file_name").prop('required',false);
                    // $('.preview-image').attr('src',public_url +'attachment-file/'+response.data.file_name);
                    $(".file-form").removeClass('hidden');
                    $(".file-listing").addClass('hidden');
                    // $('.preview-image').removeClass('hidden');
                    $(".preview").html(response.data.file_name);
                }
            },
            complete: function(){
                $('#waitingShield').addClass('hidden');
            }
        });
  });
</script>

