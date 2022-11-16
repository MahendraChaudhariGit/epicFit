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
    Meal Categories list
    <a class="btn btn-primary pull-right" href="{{ route('meal-category.create') }}"><i class="ti-plus"></i> Add Meal Category</a>
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
                    <th>Sr No.</th>
                    <th>Name</th>                         
                    <th>Created Date</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>                   
              <?php $i = ($mealsCategory->currentPage() - 1) * $mealsCategory->perPage() + 1; ?>
              @foreach($mealsCategory as $mealcat)
                <tr class="meal-row">
                  <td>
                    <?php echo $i;?>
                  </td>
                  <td>
                    {{$mealcat->mealcategoryDesc or ''}}
                  </td>                                                  
                  <td>
                    {{setLocalToBusinessTimeZone($mealcat->created_date, 'dateString')}}
                  </td>
                  <td class="center">
                    <div>
                      <a class="btn btn-xs btn-default tooltips" href="{{ route('meal-categories.edit', $mealcat->id) }}" data-placement="top" data-original-title="Edit">
                        <i class="fa fa-pencil" style="color:#253746;"></i>
                      </a>
                      <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('mealCat.destroy', $mealcat->id) }}" data-placement="top" data-original-title="Delete" data-entity="Meal Category">
                        <i class="fa fa-trash-o" style="color:#253746;"></i>
                      </a>
                    </div>
                        <div>

                        </div>
                    </td>
                </tr> 
                <?php $i++; ?>
              @endforeach 
            </tbody>
        </table>
        <!-- start: Paging Links -->
        @include('includes.partials.paging', ['entity' => $mealsCategory])
        <!-- end: Paging Links -->          
    </div>
</div>

@stop
@section('script')
  {!! Html::script('result/js/jquery-ui.min.js') !!}

  {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
  {!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
  {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
  {!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!} 
  
  {!! Html::script('result/plugins/moment/moment.min.js') !!}
  {!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
  {!! Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js') !!}
  {!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js')) !!}
  {!! HTML::script('result/plugins/select2/select2.js') !!} 

  {!! Html::script('result/js/helper.js?v='.time()) !!}
  {!! Html::script('assets/js/meal-planner.js?v='.time()) !!}

  <script>
    var cookieSlug = "mealcategory";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#mp-datatable').dataTable({"searching": false, "paging": false, "info": false }); 
  </script>
@stop
