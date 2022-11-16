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
            <div style="margin-left: 15px;display: inline-block;margin-top: 16px;float: left;">Serving Size list ({{count($servingSize)}})</div>
        </div>

        <div class="col-sm-6">
          <div class="checkbox clip-check check-primary" style="display: inline-block; margin-top: 13px">
              
          </div>
        </div>

        <div class="col-sm-3">
            <div style="margin-right: 15px; display: inline-block; float: right">
                <a class="btn btn-primary hide" href="" style ="margin-left: 500px;"><i class="ti-printer"></i> Print Meals</a>
                <a class="btn btn-primary pull-right create-serving" style="margin-top: 7px;" href="{{ route('servings.create') }}"><i class="ti-plus"></i> Add New Serving Size</a>
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
                            <th>Servings</th>
                            <th class="">Food Name</th>
                            <th class="">Serving Weight</th>
                            <th class="" width="19%">Created Date</th>
                            <th class="center ">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $i = ($servingSize->currentPage() - 1) * $servingSize->perPage() + 1; ?>
                     @foreach($servingSize as $size)
                        <tr class="servings-row" id = "{{ $size->servingsize_id }}">
                          <input type="hidden" name="servings_id" id ="servings_id" value ="{{$size->servingsize_id}}">
                          <td>
                            <a class="id"><?php echo $i;?></a>
                          </td>
                          <td>
                            {{$size->serving_desc}}
                          </td>                       
                          <td>
                            {{$size->short_desc}}
                          </td>                     
                          <td>
                            {{$size->serving_weight}}
                          </td>                         
                          <td>
                            {{setLocalToBusinessTimeZone($size->created_date, 'dateString')}}
                          </td>
                          <td class="center">
                            <div>
                              
                              <a class="btn btn-xs btn-default tooltips edit-servings" href="{{ route('servings.edit',$size->servingsize_id) }}" data-placement="top" data-original-title="Edit">
                                      <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                  </a>
                              <a class="btn btn-xs btn-default tooltips delete-servings" data-entity="servings"  data-placement="top" data-original-title="delete" data = "{{ $size->servingsize_id }}" style ="">
                                  <i class="fa fa-times" style="color:#ff4401;"></i>
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
                  

            </div>
        </div>
    </div>
    <div class="pull-right">
      {{ $servingSize->links() }}
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
