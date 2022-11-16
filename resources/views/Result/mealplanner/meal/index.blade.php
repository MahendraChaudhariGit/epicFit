@extends('Result.masters.app')
@section('required-styles')
    {!! Html::style('result/css/custom.css?v='.time()) !!}
    {!! Html::style('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!} 
    {!! HTML::style('result/plugins/select2/select2.css') !!} 
    {!! Html::style('result/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
@stop
 
@section('page-title')
    Meal List
    <a class="btn btn-primary pull-right" href="{{ route('meals.create') }}"><i class="ti-plus"></i> Add Meal</a>
@stop

@section('content')
{!! displayAlert()!!}
  <!-- start: Delete Form -->
  @include('includes.partials.delete_form')
  <!-- end: Delete Form --> 

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <!-- start: Datatable Header -->
            @include('includes.partials.datatable_header')
            <!-- end: Datatable Header -->
        </div>
        <table class="table table-striped table-bordered table-hover m-t-10" id="mp-datatable">
          <thead>
              <tr>
                  <th class="mw-70 w70">Logo</th>
                  <th>Meal Name</th>
                  <th>Meal Description</th>
                  <th>Category</th>
                  <th>Created Date</th>
                  <th class="center">Actions</th>
              </tr>
          </thead>
          <tbody>

            @if($meals->count())
            @foreach($meals as $mealInfo)
              <tr class="meal-row">
                <td class="mw-70 w70">
                  <img src="{{ dpSrc($mealInfo->thumb_image) }}" alt="{{ $mealInfo->mealname }}" class="mw-50 mh-50"/>
                </td>
                <td>
                  {{ $mealInfo->mealname or ''}}
                </td>
                <td>
                  {!! $mealInfo->meal_description or '' !!}
                </td>
                <td>
                  {{ $mealInfo->category->mealcategoryDesc or ''}}
                </td>
                <td>
                  {{setLocalToBusinessTimeZone($mealInfo->created_date, 'dateString')}}
                </td>
                <td class="center">
                  <div>
                    <a class="btn btn-xs btn-default tooltips " href="{{ route('meals.edit', $mealInfo->meal_id) }}" data-placement="top" data-original-title="Edit">
                        <i class="fa fa-pencil" style="color:#253746;"></i>
                    </a>
                    <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('meals.destroy', $mealInfo->meal_id) }}" data-placement="top" data-original-title="Delete" data-entity="Meal">
                        <i class="fa fa-trash-o" style="color:#253746;"></i>
                    </a>
                  </div>
                </td>
              </tr> 
            @endforeach
            @endif 
          </tbody>
        </table>
        <!-- start: Paging Links -->
        @include('includes.partials.paging', ['entity' => $meals])
        <!-- end: Paging Links -->
    </div>
</div>

@stop
@section('required-script')
  {!! Html::script('result/js/jquery-ui.min.js') !!}

  {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
  {!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
  {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
  {!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!} 
  
  {!! Html::script('result/plugins/moment/moment.min.js') !!}
  {!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
  {!! Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js') !!}
  {!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}
  {!! HTML::script('result/plugins/select2/select2.js') !!} 

  {!! Html::script('result/js/helper.js?v='.time()) !!}
  {!! Html::script('result/js/meal-planner.js?v='.time()) !!}

  <script>
    var cookieSlug = "";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#mp-datatable').dataTable({"searching": false, "paging": false, "info": false }); 
  </script>
@stop
