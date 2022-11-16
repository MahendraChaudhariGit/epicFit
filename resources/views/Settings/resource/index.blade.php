@extends('blank')

@section('page-title')
  Resources List
      @if(isUserEligible(['Admin'], 'add-resource'))
        <a class="btn btn-primary pull-right" href="{{ url('settings/business/resources/create') }}"><i class="ti-plus"></i> Add Resource</a>
      @endif   
@stop()

@section('content')
{!! displayAlert()!!}

@if(isUserEligible(['Admin'], 'delete-resource'))
   @include('includes.partials.delete_form')
@endif
    <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            @include('includes.partials.datatable_header')
          </div>
            <table class="table table-striped table-bordered table-hover m-t-10" id="resource-datatable">
                <thead>
                    <tr>
                        <th>Resource name</th>
                        <th>Resource Item</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($allresource as $allresources)
                    <tr>
                       <!--<td class="center mw-70 w70">
                            <a href="">
                                <img src="" alt="">
                            </a>
                        </td>-->
                        <td>
                            {{ $allresources->res_name }}
                        </td>
                        <td>
                          <?php 
                             $body="<div class='row'>
                                       <div class='col-md-6'><strong>Item name</strong></div>
                                        <div class='col-md-6'><strong>Location</strong></div>
                                     </div>";
                                     $body1="";
                               if($allresources->items->count()){ 
                               $loc='';     
                              foreach ($allresources->items as $item) {
                                if(isset($item->location))
                                      $loc=$item->location->location_training_area;
                                else{
                                      $loc="<span class='text-danger'>Deleted</span>";
                                }
                                $body1.="<div class='row'>
                                       <div class='col-md-6'>".$item->ri_name ."</div>
                                       <div class='col-md-6'>".$loc."</div></div>"; 
                                 }
                               }
                               
                                  
                              //$body2="</div>";   
                          ?>
                          <a class="font-14 pull-left" data-content="{{ $body.$body1 }}" data-placement="right" data-toggle="popover" data-trigger="hover" data-title="<strong>Resource Items</strong>" data-html="true">
                          {{ $allresources->items->count() }}&nbsp;Items</a>
                        </td>
                        <td class="center">
                            <div>
                                <!--<a href="" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a>-->
                              @if(isUserEligible(['Admin'], 'edit-resource'))
                                <a class="btn btn-xs btn-default tooltips" href="{{ route('resources.edit',$allresources->id)}}" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary"></i>
                                </a>
                              @endif
                              @if(isUserEligible(['Admin'], 'delete-resource'))      
                                <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('resources.destroy', $allresources->id) }}" data-placement="top" data-original-title="Delete" data-entity="resource">
                                <i class="fa fa-trash-o text-primary"></i>
                                </a>
                              @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('includes.partials.paging', ['entity' => $allresource])
        </div>
    </div>
@stop

@section('script')
    <script>
    var cookieSlug = "resource";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#resource-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {!! Html::script('assets/js/helper.js') !!}
@stop
