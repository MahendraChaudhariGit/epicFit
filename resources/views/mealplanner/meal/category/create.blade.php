@extends('blank')

@section('required-styles')

@stop

@section('page-title')
    <span>Add Main Category</span>
@stop

@section('content')
<div class="row swMain">
    <form method="POST" action="{{ route('main-category.store') }}">
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif

        {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
<!--       <div class="col-md-12">
                 
            </div> -->
      
    </form>
</div>
@endsection

@section('script')
   

@stop
