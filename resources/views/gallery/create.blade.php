@extends('blank')

@section('required-styles')
<!-- link your Style sheet here --> 
{{-- {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!} --}}
@stop

@section('page-title')
@if(isset($category))
<span >Edit Category</span> 
@else
<span >Add Category</span> 
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
            {{-- <fieldset class="padding-15"> --}}
                {{-- <legend>
                    General
                </legend> --}}
                @if(isset($category))
                <form action="{{ route('category.update') }}" method="post">
                @else
                <form action="{{ route('save.gallery.category') }}" method="post">
                @endif
                
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        @if(isset($category))
                        <input type="hidden" name="cat_id" value="{{ $category->id }}">
                        @endif
                        <div class="col-md-4 col-md-offset-4 form-group">
                            <label for="cat_name" class="strong">Category Name *</label>
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                            <input type="text" name="cat_name" id="cat_name" class="form-control" value="@if(isset($category) && !empty($category->cat_name)) {{ $category->cat_name }} @endif" required>
                            @error('cat_name')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>    
                    <div class="col-md-12 text-center">
                        @if(isset($category))
                            <button type="submit" class="btn btn-primary btn-wide"> <i class="fa fa-edit"></i> Update Category</button>
                        @else
                            <button type="submit" class="btn btn-primary btn-wide"> <i class="fa fa-plus"></i> Add Category</button>
                        @endif
                    </div>         
                </div>
            </form>
            {{-- </fieldset> --}}
        </div>
    </div>
</div>
{{-- @include('includes.partials.pic_crop_model')
@include('includes.partials.add_serving_size'); --}}

@endsection

@section('script')
{!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop