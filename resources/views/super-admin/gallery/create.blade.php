@extends('super-admin.layout.master')

@section('page-title')
{{-- @if(isset($category))
<span >Edit Category</span> 
@else --}}
<span >Add Category</span> 
{{-- @endif --}}

@stop
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@section('content')
<div id="panel_edit_account" class="tab-pane active">               
    <div class="row swMain">
        
        <div class="col-md-12">
            @if(session()->has('msg'))
            <div class="px-6">
                <div class="alert alert-dismissable alert-success">
                    <span> {!! session()->get('msg') !!} </span>
                    <button type="button" class="flex items-center p-1 focus:outline-none ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
            {{-- <fieldset class="padding-15"> --}}
                {{-- <legend>
                    General
                </legend> --}}
                {{-- @if(isset($category))
                <form action="{{ route('category.update') }}" method="post">
                @else --}}
                <form action="{{ route('superadmin.save.gallery.category') }}" method="post">
                {{-- @endif --}}
                
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        {{-- @if(isset($category))
                        <input type="hidden" name="cat_id" value="{{ $category->id }}">
                        @endif --}}
                        {{-- <div class="col-md-3 col-md-offset-4 form-group">
                            <label for="client_id" class="strong">Clients Name *</label>
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                            <select class="form-control select2" id="client_id" name="client_id" required>
                                <option value="">select client</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client['id'] }}">{{ $client['name'] }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="col-md-4 col-md-offset-4 form-group">
                            <label for="cat_name" class="strong">Category Name *</label>
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                            <input type="text" name="cat_name" id="cat_name" class="form-control" value="" required>
                            @error('cat_name')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>    
                    <div class="col-md-12 text-center">
                        {{-- @if(isset($category))
                            <button type="submit" class="btn btn-primary btn-wide"> <i class="fa fa-edit"></i> Update Category</button>
                        @else --}}
                            <button type="submit" class="btn btn-primary btn-wide"> <i class="fa fa-plus"></i> Add Category</button>
                        {{-- @endif --}}
                    </div>         
                </div>
            </form>
            {{-- </fieldset> --}}
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $(".select2").select2({
            tags: true,
        })
        $(".select2-container").css('width',322)
    })
</script>
@stop