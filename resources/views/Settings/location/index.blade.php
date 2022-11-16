@extends('blank')

@section('page-title')
    Location List
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-location'))
        <a class="btn btn-primary pull-right" href="{{ route('locations.create') }}"><i class="ti-plus"></i> Add Location</a>
    @endif
@stop
@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-location'))
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
                <table class="table table-striped table-bordered table-hover m-t-10" id="location-datatable">
                    <thead>
                        <tr>
                            <th class="center mw-70 w70">Logo</th>
                           	<th>Name</th>
                            <th class="">Email</th>
                            <th class="">Phone</th>
                            <th class ="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                   	@foreach($allLocations as $locations)
                        <tr>
                            <td class="center mw-70 w70">
                            	<a href="{{ route('locations.show', $locations->id) }}"><!-- url('location/'.$locations->id) -->
                                	<img src="{{ dpSrc($locations->logo, '') }}" alt="{{ $locations->name}}" class="mw-50 mh-50">
                                </a>
                            </td>
                            <td>
                            <a href="{{ route('locations.show', $locations->id) }}">{{ $locations->location_training_area ?? '' }} </a> <br><!--url('location/'.$locations->id)-->
                            </td>
                            <td class="">
                                <a href="mailto:{{ $locations->email ?? '' }}">{{ $locations->email ?? '' }}</a>
                          </td>
                          <td class="">
                              <a href="tel:{{ $locations->phone ?? '' }}">  {{ $locations->phone ?? '' }}</a>
                                <br>
                          </td>
                            <td class="center">
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-location')) 
                                    <a href="{{ route('locations.show', $locations->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share" style="color:#253746;"></i></a><!--url('location/'.$locations->id) }}-->
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-location')) 
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('locations.edit', $locations->id) }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil" style="color:#253746;"></i>
                                    </a>
                                    @endif
                                
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-location-area'))
                                    <a href="{{ route('locations.areas', $locations->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View Area List" ><i class="fa fa-location-arrow" style="color:#253746;"></i></a><!--url('location-areas-list/'.$locations->id)-->
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-location'))
                                        <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('locations.destroy', $locations->id) }}" data-placement="top" data-original-title="Delete" data-entity="location">
                                            <i class="fa fa-trash-o" style="color:#253746;"></i>
                                        </a>
                                    @endif
                            </td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
                <!-- start: Paging Links -->
                @include('includes.partials.paging', ['entity' => $allLocations])
                <!-- end: Paging Links -->
            <!--</div>-->
        </div>
    </div>
   
@stop
@section('script')
<script>
var cookieSlug = "location";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#location-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js') !!}
@stop