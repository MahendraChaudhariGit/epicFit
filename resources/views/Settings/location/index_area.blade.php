@extends('blank')

@section('page-title')
    Location Area List
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-location-area'))
        <a class="btn btn-primary pull-right" href="{{ route('areas.create', Request::segment(4)) }}"><i class="ti-plus"></i> Add Location Area</a> 
    @endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-location-area'))
    <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->
@endif

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <!-- start: Datatable Header -->
            @include('includes.partials.datatable_header')
            <!-- end: Datatable Header -->
            </div>
            <!--<div class="table-responsive">-->
                <table class="table table-striped table-bordered table-hover m-t-10" id="area-datatable">
                    <thead>
                        <tr>
                            <th class="center mw-70 w70">Logo</th>
                           	<th>Name</th>
                           	<th class="hidden-xxs">Location</th>
                            <th class="hidden-xs">Staff</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($allLocationAreas as $locationareas)

               		<tr>
                            <td class="center mw-70 w70">
                            	<a href="{{ route('areas.show', $locationareas->la_id) }}"><!--url('show-location-areas/'.$locationareas->la_id)-->
                                	<img src="{{ dpSrc($locationareas->la_logo, '') }}" alt="{{ $locationareas->la_name}}" class="mw-50 mh-50">
                                </a>
                            </td>
                            <td>
                            <a href="{{ route('areas.show', $locationareas->la_id) }}">{{ $locationareas->la_name ?? '' }} </a><!--url('show-location-areas/'.$locationareas->la_id)-->
                        </td>
                			
                          <td class="hidden-xxs">
							{!! $locName !!} <!--location->--> <!--locationareas->location_training_area or ''-->
                          </td>
                          <td class="hidden-xs">
                            @if(array_key_exists($locationareas['la_id'], $areaLocationStaff))
                                {{ implode(', ',$areaLocationStaff[$locationareas['la_id']]) }}
                            @endif
                            <?php //echo();?>
                          </td>
                            <td class="center">
                                <div>
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-location-area'))
                                    <a href="{{ route('areas.show', $locationareas->la_id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a><!--url('show-location-areas/'.$locationareas->la_id)-->
                                    @endif
                                    
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-location-area'))
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('areas.edit', $locationareas->la_id) }}" data-placement="top" data-original-title="Edit" >
                                        <i class="fa fa-pencil text-primary"></i>
                                    </a>
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-location-area'))
                                        <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('areas.destroy', $locationareas->la_id) }}" data-placement="top" data-original-title="Delete" data-entity="area">
                                            <i class="fa fa-trash-o text-primary"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
                <!-- start: Paging Links -->
                @include('includes.partials.paging', ['entity' => $allLocationAreas])
                <!-- end: Paging Links -->
            <!--</div>-->
        </div>
    </div>
@stop
@section('script')
<script>
var cookieSlug = "area";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#area-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop