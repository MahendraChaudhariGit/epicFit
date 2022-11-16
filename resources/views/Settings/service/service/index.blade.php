@extends('blank')

@section('page-title')
    Service list 
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-service'))
        <a class="btn btn-primary pull-right" href="{{ route('services.create') }}"><i class="ti-plus"></i> Add Service</a>
    @endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-service'))
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
            <table class="table table-striped table-bordered table-hover m-t-10" id="service-datatable">
                <thead>
                    <tr>
                        <th class="center mw-70 w70">Logo</th>
                        <th>Name</th>
                        <th class="">Location</th>
                        <th>Category</th>
                        <th class="">Price</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($services->count())
                        <?php $canEdit = hasPermission('edit-service'); ?>
                        @foreach($services as $service)
                       	    <tr>
                                <td class="center mw-70 w70">
                                	<a href="{{ route('services.show', $service->id) }}">
                                        <img src="{{ dpSrc($service->logo) }}" alt="" class="mw-50 mh-50">
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        @if($service->name)
                                            <a href="{{ route('services.show', $service->id) }}">
                                                {{ $service->name }}
                                            </a>
                                        @else
                                            {{ $service->name }}
                                        @endif

                                        @if($service->Color)
                                            @if($canEdit)
                                                <div class="m-t-5 mw-250">
                                                    {!! Form::color('color', $service->Color, ['class' => 'form-control w100 change-color', 'data-id' => $service->id, 'data-type' => 'services']) !!}
                                                </div>
                                            @else
                                                <div class="colorStrip m-t-5">   
                                                    <div style="background-color:{!! $service->Color !!}">&nbsp;</div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                

                                <td class="">
                                  @if( $service->location )
                                    @if($service->locationn)
                                        @if(Auth::user()->hasPermission(Auth::user(), 'view-location'))
                                            <a href="{{ route('locations.show', $service->location) }}">
                                                {{ $service->locationn->location_training_area }}
                                            </a> 
                                        @else
                                            {{ $service->locationn->location_training_area }}
                                        @endif
                                    @else
                                        <span class="text-danger">Deleted</span>
                                    @endif
                                  @endif
                                </td>

                               <td>
                                    @if($service->catDeletedAt == null)
                                        <div>{{ $service->sc_value }}</div>
                                    @endif
                                </td>

                                <td class="">
                                    @if( $service->price )
                                        ${{ $service->price }}
                                    @endif
                                </td>
                                
                                <td class="center">
                                    <div>
                                        @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-service'))
                                            <a href="{{ route('services.show', $service->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share" style="color:#253746;"></i></a>
                                        @endif

                                        @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-service'))
                                            <a class="btn btn-xs btn-default tooltips" href="{{ route('services.edit', $service->id) }}" data-placement="top" data-original-title="Edit">
                                            <i class="fa fa-pencil" style="color:#253746;"></i>
                                        @endif

                                        @if( !$service->is_default && isUserType(['Admin'])  && Auth::user()->hasPermission(Auth::user(), 'delete-service'))
                                            <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('services.destroy', $service->id) }}" data-placement="top" data-original-title="Delete" data-entity="service" data-association-route="{{ route('services.checkAssociation', $service->id) }}">
                                                <i class="fa fa-trash-o" style="color:#253746;"></i>
                                            </a>
                                        @endif
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach 
                    @endif
                </tbody>
            </table>
            <!-- start: Paging Links -->
            @include('includes.partials.paging', ['entity' => $services])
            <!-- end: Paging Links -->
        </div>
    </div>
@stop
@section('script')
<script>
var cookieSlug = "service";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#service-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop