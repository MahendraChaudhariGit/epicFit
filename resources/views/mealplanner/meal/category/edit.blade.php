@extends('blank')

@section('required-styles')
@stop

@section('page-title')
    <span>Edit Main Category</span>
@stop

@section('content')
<div class="row swMain">
    <form method="POST" action="{{ route('main-category.update', $main_cat->id) }}">
        {{-- @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif --}}

        @csrf
        <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    Main Category
                </legend>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name" class="strong">Main Category Name *</label>
                            <span class="epic-tooltip tooltipstered" data-toggle="tooltip"><i class="fa fa-question-circle"></i></span>
                         <input type="text" name="name" class="form-control" value="{{old('name', $main_cat->name)}}" required>
                                                      
                        </div>  
                                                        
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary btn-wide submit_meal pull-right"> 
                    Update
                </button>
                        </div>
                    </div>             
                </div>
            </fieldset>
        </div>
        <div class="col-md-6">
            <label></label>
           
        </div>
        <div class="col-md-6">
            <div class="form-group padding-15">
               
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
  
@stop
