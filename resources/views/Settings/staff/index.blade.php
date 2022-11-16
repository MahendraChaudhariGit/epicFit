@extends('blank')

@section('page-title')
    Staff list 
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-staff'))
    <a class="btn btn-primary pull-right" href="{{ route('staffs.create') }}"><i class="ti-plus"></i> Add Staff</a><!--url('settings/business/'.Session::get('businessId').'/edit#3')-->
    @endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-staff'))
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
                <table class="table table-striped table-bordered table-hover m-t-10" id="staff-datatable" style="width:100%">
                    <thead>
                        <tr>
                            <th class="center mw-70 w70">Photo</th>
                            <th>Full Name</th>
                            <th class="">Contact Details</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($allStaffs as $staffs)
                        <tr>
                            <td class="center mw-70 w70">
                            	<a href="{{ route('staffs.show', $staffs->id) }}"><!--url('staff/'.$staffs->id)-->
                                	<img src="{{ dpSrc($staffs->profile_picture, $staffs->gender) }}" alt="{{ $staffs->first_name }} {{ $staffs->last_name }}" class="mw-50 mh-50">
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('staffs.show', $staffs->id) }}">{{ $staffs->first_name ?? 'Default' }} {{ $staffs->last_name ?? 'Default' }}</a> <br><!--url('staff/'.$staffs->id)-->
                            </td>
                            <td class="">
                                <a href="mailto:{{ $staffs->email ?? '' }}" style="color:#253746;">{{ $staffs->email ?? '' }}</a>
                                <br>
                                <a href="tel:{{ $staffs->phone ?? '' }}">{{ $staffs->phone ?? '' }}</a>
                            </td>
                            <td class="center">
                                <div>
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-staff')) 
                                    <a href="{{ route('staffs.show', $staffs->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share" style="color:#253746;"></i></a><!--url('staff/'.$staffs->id)-->
                                    @endif
                                    
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-staff')) 
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('staffs.edit', $staffs->id) }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil" style="color:#253746;"></i>
                                    </a>
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-staff'))
                                    <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('staffs.destroy', $staffs->id) }}" data-placement="top" data-original-title="Delete" data-entity="staff">
                                    <i class="fa fa-trash-o" style="color:#253746;"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
                <!-- start: Paging Links -->
                @include('includes.partials.paging', ['entity' => $allStaffs])
                <!-- end: Paging Links -->
            <!--</div>-->
            
        </div>
    </div>
@stop
@section('script')
<script>
var cookieSlug = "staff";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#staff-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop