@extends('super-admin.layout.master')

@section('required-styles')
<!-- link your Style sheet here --> 
{{-- {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!} --}}
<style type="text/css">
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
@stop

@section('page-title')
@if(isset($get_data))
<span >Edit Images</span> 
@else
<span >Add Images</span> 
@endif

@stop

@section('content')
<div id="panel_edit_account" class="tab-pane active">               
    <div class="row swMain">
        @if(session()->has('msg'))
            <div class="px-6">
                <div class="alert alert-dismissable alert-success">
                    <p> {!! session()->get('msg') !!} </p>
                    <button type="button" class="flex items-center p-1 focus:outline-none ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        <div class="col-md-12">
                @if(isset($get_data))
                <form action="{{ route('superadmin.image.update') }}" method="post" enctype="multipart/form-data">
                @else 
                <form action="{{ route('superadmin.save.images') }}" method="post" enctype="multipart/form-data">
                @endif
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="cat_id" value="{{ $cat_id }}">
                        @if(isset($get_data))
                        <input type="hidden" name="id" value="{{ $get_data->id }}">
                        @endif
                        <div class="col-md-4 form-group">
                            <div>
                                <label class="strong">Add Images *</label>
                                <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                                @if(!isset($get_data))
                                <input type="file" name="cat_image[]" accept="image/*" class="form-control custom-file-input select-image" required >
                                @else
                                <input type="file" name="cat_image" accept="image/*" class="form-control custom-file-input select-image" >
                                @endif
                                <div class="flex flex-col dz-started" >
                                    <span class="ml-2 truncate preview"></span>
                                </div>
                                @if(isset($get_data))
                                <img src="{{ asset('category-images/' . $get_data->cat_image) }}"
                                class="img-responsive" />
                                @endif
                                
                                @error('cat_image')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div style="margin-top: 10px">
                                <label class="strong">Images Description</label>
                                <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                                <textarea @if(isset($get_data)) name="image_desc" @else name="image_desc[]" @endif class="form-control">@if(isset($get_data)){{ $get_data->description }}@endif</textarea>
                                @error('image_desc')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if(!isset($get_data))
                        <div class="col-md-2 before-add-more">
                            <a href="javascrpt:void(0)" class="btn btn-primary add-more"> Add More</a>
                        </div>
                        @endif
                        
                    </div>    
                    <div class="col-md-12 text-center">
                        @if(isset($get_data))
                            <button type="submit" class="btn btn-primary btn-wide"> <i class="fa fa-edit"></i> Update Image</button>
                        @else
                            <button type="submit" class="btn btn-primary btn-wide"> <i class="fa fa-plus"></i> Add Images</button>
                        @endif
                    </div> 
                    
                    
                </div>
            </form>
            <div class="copy hidden">
                <div class="col-md-4 form-group remove-div">
                <div>
                    <label class="strong">Add Images *</label> 
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                    <a class="text-danger remove_div"> <span class="text-right">x</span></a>
                    <input type="file" name="cat_image[]" accept="image/*" class="form-control custom-file-input select-image" required>
                    <div class="flex flex-col dz-started" >
                        <span class="ml-2 truncate preview"></span>
                    </div>
                    @error('cat_image')
                        <span class="text-danger"> {{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-top: 10px">
                    <label class="strong">Images Description</label>
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                    <textarea name="image_desc[]" class="form-control"></textarea>
                    @error('image_desc')
                        <span class="text-danger"> {{ $message }}</span>
                    @enderror
                </div>
                
                </div>
            </div>
            {{-- </fieldset> --}}
        </div>
    </div>
</div>
{{-- @include('includes.partials.pic_crop_model')
@include('includes.partials.add_serving_size'); --}}

<script>
    $(document).ready(function() {

    $(".add-more").click(function(){ 
        var html = $(".copy").html();
        $(".before-add-more").before(html);
    });

    $("body").on("click",".remove_div",function(){ 
        $(this).parents(".remove-div").remove();
    });

    });
    
    $(document).on('change','.select-image',function(){
        var filename = $(this).val();
        if (filename.substring(3,11) == 'fakepath') {
            filename = filename.substring(12);
        } 
        $(this).siblings('div').children('span').html(filename)
    })
</script>
@stop