@extends('blank')
@section('required-styles')

@stop
    
@section('page-title')
  <div class="col-md-8">
    Gallery
  </div>
  {{-- <div class="col-md-4" align="right">
    <a class="btn btn-primary" href="{{ route('add.gallery.category') }}"><i class="fa fa-plus"></i> Add Category</a>
  </div> --}}
@stop

@section('content')
{!! displayAlert()!!}
  <!-- start: Delete Form -->
  {{-- @include('includes.partials.delete_form') --}}
  <!-- end: Delete Form --> 

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <!-- start: Datatable Header -->
            {{-- @include('includes.partials.datatable_header') --}}
            <!-- end: Datatable Header -->
        </div>
        {{-- @if(session()->has('msg'))
            <div class="px-6">
                <div class="alert alert-dismissable alert-success">
                    <span> {!! session()->get('msg') !!} </span>
                    <button type="button" class="flex items-center p-1 focus:outline-none ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif --}}
        <div class="row">
            @if(count($categories) > 0)
            @foreach($categories as $key => $category)
            
            <div class="col-sm-2">
                <div class="panel panel-white no-radius text-center">
                    <div class="panel-body">
                        <a href="{{ route('images', ['id' => $category->id]) }}">
                            <span class="fa-stack fa-2x">
                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                            <i class="fa fa-image fa-stack-1x fa-inverse"></i>
                            </span>
                            <h5>{{ $category->cat_name }}</h5>
                        </a>
                    </div>
                </div>
            </div>
            
            @endforeach
            @else
            <div class="col-sm-12 text-center">
               <h3> Category not found </h3> 
            </div>
          @endif
        </div>
              
        <!-- start: Paging Links -->
        {{-- @include('includes.partials.paging', ['entity' => $categories]) --}}
        <!-- end: Paging Links --> 
    </div>
</div>
@stop
@section('script')
  {!! Html::script('assets/js/helper.js') !!}
@stop
