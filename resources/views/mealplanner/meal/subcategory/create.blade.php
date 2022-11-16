@extends('blank')

@section('required-styles')

@stop

@section('page-title')
    <span>Add Sub Category</span>
@stop

@section('content')
<div class="row swMain">
    <form method="POST" action="{{ route('sub-category.store') }}">
        @csrf
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
           <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    Sub Category
                </legend>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name" class="strong">Main Category Name *</label>
                            <span class="epic-tooltip tooltipstered" data-toggle="tooltip"><i class="fa fa-question-circle"></i></span>
                          <select name="main_category_id" required class="form-control">
                    <option value="" selected> --Select Main Category-- </option>
                    @foreach($main_cat_list as $cat)
                      <option  value="{{$cat->id}}" {{ old('main_category_id') == $cat->id ? "selected" : "" }}  >{{$cat->name}}</option>
                    @endforeach
                </select>
                                                      
                        </div>  
                        <div class="form-group">
                            <label for="name" class="strong">MSub Category Name</label>
                            <span class="epic-tooltip tooltipstered" data-toggle="tooltip"><i class="fa fa-question-circle"></i></span>
                             <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                                      
                        </div>                                       
                        <div class="form-group">
                           <button type="submit" class="btn btn-primary btn-wide submit_meal pull-right"> 
                    Submit
                </button>
                        </div>
                    </div>             
                </div>
            </fieldset>
        </div>
       
    </form>
</div>
@endsection

@section('script')
  


@stop
