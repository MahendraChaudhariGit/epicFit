@extends('blank')

@section('page-title')
   Business list
@stop

@section('content')
    @if(isUserType(['Admin']))
        @include('includes.partials.delete_form')

        {!! displayAlert()!!}
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                <!-- start: Datatable Header -->
                @include('includes.partials.datatable_header')
                <!-- end: Datatable Header -->
                </div>
                
                <table class="table table-striped table-bordered table-hover m-t-10" id="inActiveUser-datatable">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th class="hidden-xxs">Email</th>
                            <th class="hidden-xs">Status</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($allBusiness))
                        @foreach($allBusiness as $business)
                            <tr>
                                <td>
                                    {{ $business->name }} {{ $business->last_name }}
                                </td>
                                <td class="hidden-xxs">
                                    {{ $business->email }}
                                </td>
                                <td class="hidden-xs">
                                    @if($business->confirmed == 0)
                                        Inactive
                                    @elseif($business->confirmed == 1)
                                        Active
                                    @endif    
                                </td>
                                <td class="center">
                                    <div>
                                    @if($business->confirmed == 0)
                                        <a href="{{ route('business.active', $business->id) }}" class="btn btn-xs btn-default tooltips submitConfirm" data-placement="top" data-original-title="Activate">
                                            <i class="fa fa-user-plus" style="color:#253746;"></i>
                                        </a>
                                    @elseif($business->confirmed == 1)
                                        <a href="{{ route('business.inactive', $business->id) }}" class="btn btn-xs btn-default tooltips submitConfirm" data-placement="top" data-original-title="Deactivate">
                                            <i class="fa fa-user-times" style="color:#253746;"></i>
                                        </a>
                                    @endif
                                    
                                    @if(Session::get('businessId') != $business->id)
                                        <a href="{{ route('business.destroy', $business->id) }}" class="btn btn-xs btn-default tooltips delLink" data-placement="top" data-original-title="Delete" data-entity="business">
                                            <i class="fa fa-trash-o" style="color:#253746;"></i>
                                        </a>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif 
                    </tbody>
                </table>
                <!-- start: Paging Links -->
                    @include('includes.partials.paging', ['entity' => $allBusiness]) 
                <!-- end: Paging Links -->
            </div>
        </div>
    @endif
@stop

@section('script')

    <script>
    var cookieSlug = "inactiveuser";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#inActiveUser-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
    {!! Html::script('assets/js/incative-user.js?v='.time()) !!}
@stop