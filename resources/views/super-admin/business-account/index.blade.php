@extends('super-admin.layout.master')

@section('page-title')
    Business Accounts
@stop

@section('content')
{!! displayAlert()!!}
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <!-- start: Datatable Header -->
            @include('includes.partials.datatable_header')
            <!-- end: Datatable Header -->
            </div>
            <table class="table table-striped table-bordered table-hover m-t-10" id="accounts-datatable">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Creared Date</th>
                        <th>Status</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($businessAccounts as $account)
                    <tr>
                        <td>
                            {{$account->getFullNameAttribute()}}
                        </td>
                        <td>
                           {{$account->email}} 
                        </td>
                        <td>
                           {{$account->created_at->format('d-m-Y H:i:s')}} 
                        </td>
                        <td class="">
                           @if($account->confirmed == '0')
                            <span class="label label-warning">In Process</span>
                            @endif
                            @if($account->confirmed == '1')
                            <span class="label label-info">Activated</span>
                            @endif
                            @if($account->confirmed == '2')
                            <span class="label label-warning">Under Review</span>
                            @endif
                            @if($account->confirmed == '3')
                            <span class="label label-warning">On Hold</span>
                            @endif
                        </td>
                        <td class="center">
                            <div>
                                <a href="{{route('superadmin.businessAccount.view',['id' => $account->id])}}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a>
                                <a class="btn btn-xs btn-default tooltips" href="{{route('superadmin.businessAccount.edit',['id' => $account->id])}}" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary"></i>
                                </a>
                                <a class="btn btn-xs btn-default tooltips delLink" data-del-url="{{route('superadmin.businessAccount.delete',['id' => $account->id])}}">
                                    <i class="fa fa-trash-o text-primary"></i>
                                </a>
                                <a class="btn btn-xs btn-default tooltips sendLink" data-send-url="{{route('superadmin.businessAccount.sendConfirmationEmail',['id' => $account->id])}}" data-toggle="tooltip" data-placement="top" title="Send Confirmation Email">
                                    <i class="fa fa-envelope text-primary" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            <!-- start: Paging Links -->
            @include('includes.partials.paging', ['entity' => $businessAccounts])
            <!-- end: Paging Links -->
        </div>
    </div>

@stop
@section('script')
<script>
var cookieSlug = "contact";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#accounts-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js') !!}
@stop