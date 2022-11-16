@extends('blank')
@section('required-styles')
    
@stop
 
@section('page-title')
    <div class="col-md-8">
      Main Category List
    </div>
    <div class="col-md-4" align="right">
      <a class="btn btn-primary" href="{{ route('main-category.create') }}"><i class="ti-plus"></i> Add Main Category</a>
      {{-- <label class="btn btn-primary btn-file">
        <span><i class="fa fa-upload"></i> &nbsp;Select csv file</span> 
        <input type="file" data-import-type="meal" class="hidden" onChange="excelFileHandler(this)" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.xlsx,.xls">
      </label> --}}
    </div>
@stop

@section('content')
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif

<table class="table table-striped table-bordered table-hover m-t-10" id="mp-datatable">
    <thead>
        <tr>
             <th>Main Category Name</th>
             <th>Created Date</th>
            <th class="center">Actions</th>
        </tr>
    </thead>
    <tbody>

    @if($main_category_list->count())
      @foreach($main_category_list as $cat)
        <tr class="meal-row">
          
          <td>{{ $cat->name }}</td>
        
          <td> {{ date_format($cat->created_at,'Y-m-d') }}</td>
          <td class="center">
            <div>        
               <a class="btn btn-xs btn-default tooltips" href="{{route('main-category.fetch',$cat->id)}}" data-placement="top" data-original-title="Edit">
                  <i class="fa fa-pencil" style="color:#253746;"></i>
              </a>
              <a class="btn btn-xs btn-default tooltips"  onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('main-category.delete',$cat->id)}}" data-placement="top" data-original-title="Delete" data-entity="Meal">
                  <i class="fa fa-trash-o" style="color:#253746;"></i>
              </a>
              {{-- <a class="btn btn-xs btn-default tooltips " href="{{ route('meals.edit', $mealInfo->id) }}" data-placement="top" data-original-title="Edit">
                  <i class="fa fa-pencil" style="color:#253746;"></i>
              </a>
              <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('meals.destroy', $mealInfo->id) }}" data-placement="top" data-original-title="Delete" data-entity="Meal">
                  <i class="fa fa-trash-o" style="color:#253746;"></i>
              </a> --}}
            </div>
          </td>
        </tr> 
      @endforeach
      @endif 
    
    </tbody>
  </table>
<!-- start: Paging Links -->
@include('includes.partials.paging', ['entity' => $main_category_list])
<!-- end: Paging Links -->


@stop
@section('script')
  {{-- {!! Html::script('assets/js/helper.js') !!}
  {!! Html::script('assets/js/meal-planner.js') !!} --}}
  <script>
  
 
 
  </script>
@stop
