@extends('masters.app')
@section('required-styles')
    {!! Html::style('css/custom.css') !!}
    {!! Html::style('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('plugins/sweetalert/sweet-alert.css') !!} 
@stop
    

@section('content')
{!! displayAlert()!!}
 <!-- start: Delete Form -->
    @include('partials.delete_form')
    <!-- end: Delete Form --> 


    <div class="row" style="text-align: center">
        <div class="col-sm-3">
            <div style="margin-left: 15px;display: inline-block;margin-top: 16px;float: left;">Meal Log list ({{count($userlogs)}})</div>
        </div>

        <div class="col-sm-6">
          <div class="checkbox clip-check check-primary" style="display: inline-block; margin-top: 13px">
              
          </div>
        </div>

        <div class="col-sm-3">
            <div style="margin-right: 15px; display: inline-block; float: right">
                <a class="btn btn-primary hide" href="" style ="margin-left: 500px;"><i class="ti-printer"></i> Print Meals</a>
                <!-- <a class="btn btn-primary pull-right create-serving" style="margin-top: 7px;" href="{{ route('shoppingcat.create') }}"><i class="ti-plus"></i> Add New Shopping Category</a> -->
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-striped table-bordered table-hover m-t-10" id="mp-datatable">
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <!-- <th>User Name</th> -->
                            <th class="">Day</th>
                            <th class="">Breakfast</th>
                            <th class="">Lunch</th>
                            <th class="">Dinner</th>
                            <th class="">Snacks</th>
                            <!-- <th class="center ">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                      <?php $i = 1;?>
                     @foreach($userlogs as $log)
                        <tr class="category-row" id = "{{ $log->user_id }}">
                          <input type="hidden" name="user_id" id ="user_id" value ="{{$log->user_id }}">
                          <input type="hidden" name="day_id" id ="day_id" value ="{{$log->day_id }}">
                          <td>
                            <a class="id"><?php echo $i;?></a>
                          </td>
                          <!-- <td>
                            {{ $user->fullname }}
                          </td> -->                       
                          <td>
                            {{ $log->day_id }}
                          </td>                     
                          <td>
                            {{ $log->breakfast }}
                          </td> 
                          <td>
                            {{ $log->lunch }}
                          </td>  
                          <td>
                            {{ $log->dinner }}
                          </td>                      
                          <td>
                            {{ $log->evening_snack }}
                          </td>
                          <!-- <td class="center">
                            <div>
                              
                              <a class="btn btn-xs btn-default tooltips edit-meallogs" href="{{ route('meallogs.edit', [$log->user_id,$log->day_id]) }}" data-placement="top" data-original-title="Edit">
                                      <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                  </a> 
                              <a class="btn btn-xs btn-default tooltips delete-meallog" data-entity="meallog"  data-placement="top" data-original-title="delete" data = "{{ $log->user_id }}" style ="">
                                  <i class="fa fa-times" style="color:#ff4401;"></i>
                              </a> 
                            </div>
                                <div>

                                </div>
                            </td> -->
                        </tr> 
                        <?php $i++; ?>
                      @endforeach 
                    </tbody>
                </table>
                  

            </div>
        </div>
    </div>
</div>
@stop
@section('required-script')
{!! Html::script('js/jquery-ui.min.js') !!}

{!! Html::script('plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
 {!! Html::script('plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('plugins/sweetalert/sweet-alert.min.js') !!} 
{!! Html::script('js/helper.js') !!}
{!! Html::script('js/meal-planner.js') !!}

<script>
var cookieSlug = "";
$.fn.dataTable.moment('ddd, D MMM YYYY');
$('#mp-datatable').dataTable({"searching": false, "paging": false, "info": false }); 
jQuery(document).ready(function() {
 
})
</script>
@stop
