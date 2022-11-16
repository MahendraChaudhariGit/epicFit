@extends('blank')

@section('page-title')
    Class list 
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-class'))
        <a class="btn btn-primary pull-right" href="{{ route('classes.create') }}"><i class="ti-plus"></i> Add Class</a>
    @endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-class'))
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
                <table class="table table-striped table-bordered table-hover m-t-10" id="class-datatable">
                    <thead>
                        <tr>
                            <th class="center mw-70 w70">Logo</th>
                            <th>Name</th>
                            <th class="">Location</th>
                            <th>Category</th>
                            <!--th class="hidden-xxs">Duration</th-->
                            <th class="">Price</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $canEdit = hasPermission('edit-class'); ?>
                    @foreach($classes as $class)
                        <tr>
                           <td class="center mw-70 w70">
                                <a href="{{ route('classes.show', $class->cl_id) }}">
                                    <img src="{{ dpSrc($class->cl_logo) }}" alt="" class="mw-50 mh-50">
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('classes.show', $class->cl_id) }}">{!! $class->cl_name !!}</a>
                                @if($canEdit) 
                                    <div class="m-t-5 mw-250">
                                        {!! Form::color('color', $class->cl_colour, ['class' => 'form-control w100 change-color', 'data-id' => $class->cl_id, 'data-type' => 'classes']) !!}
                                    </div>
                                @else
                                    <div class="colorStrip m-t-5">
                                        <div style="background-color:{!! $class->cl_colour !!}">&nbsp;</div>
                                    </div>
                                @endif
                            </td>
                            <!--td class="hidden-xxs">
                                {{ $class->cl_duration }} min
                            </td-->
                             <td class="">
                                @if($class->location)
                                    @if(Auth::user()->hasPermission(Auth::user(), 'view-location'))
                                        <a href="{{ route('locations.show', $class->cl_location_id) }}">
                                            {{ $class->location->location_training_area }}
                                        </a> 
                                    @else
                                        {{ $class->location->location_training_area }}
                                    @endif
                                @else
                                    <span class="text-danger">Deleted</span>
                                @endif
                             </td>
                              <td>
                                @if($class->cat)
                                    {{ $class->cat->clcat_value }}
                                @endif
                            </td>
                            <td class="">
                                ${{ $class->cl_price }}
                            </td>
                            <td class="center">
                                <div>
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-class'))
                                    <a href="{{ route('classes.show', $class->cl_id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View">
                                        <i class="fa fa-share" style="color:#253746;"></i>
                                    </a>
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-class'))
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('classes.edit', $class->cl_id) }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil" style="color:#253746;"></i>
                                    </a>
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-class'))
                                        <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('classes.destroy', $class->cl_id) }}" data-placement="top" data-original-title="Delete" data-entity="class" data-association-route="{{ route('classes.checkAssociation', $class->cl_id) }}">
                                            <i class="fa fa-trash-o" style="color:#253746;"></i>
                                        </a>
                                    @endif 
                                    <!--<a class="btn btn-xs btn-default tooltips delLink" href="{{ route('classes.destroy', $class->cl_id) }}" data-placement="top" data-original-title="Delete" data-token="{{ csrf_token() }}" data-entity="class" data-alert-text="Any associated events will also be deleted.">
                                        <i class="fa fa-trash-o" style="color:#253746;"></i>
                                    </a>-->
                                </div>
                            </td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
                <!-- start: Paging Links -->
                @include('includes.partials.paging', ['entity' => $classes])
                <!-- end: Paging Links -->
            <!--</div>-->
        </div>
    </div>
@stop
@section('script')
<script>
var cookieSlug = "class";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#class-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop