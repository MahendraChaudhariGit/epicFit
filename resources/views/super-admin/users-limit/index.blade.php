@extends('super-admin.layout.master')

@section('page-title')
    Users Limit
    <a class="btn btn-primary pull-right" href="{{ route('users-limit.create') }}"><i class="ti-plus"></i> Add User Limit</a>
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
                        <th>Sr No</th>
                        <th>Users(upto)</th>
                        <th>Price</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($userLimits as $userLimit)
                    <tr>
                        <td>
                            {{$loop->iteration}}
                        </td>
                        <td>
                           {{$userLimit->maximum_users}} 
                        </td>
                        <td>
                           {{$userLimit->price}}
                        </td>
                        <td class="center">
                            <div>
                                <a href="{{route('users-limit.show',['users_limit' => $userLimit->id])}}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a>
                                <a class="btn btn-xs btn-default tooltips" href="{{route('users-limit.edit',['users_limit' => $userLimit->id])}}" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary"></i>
                                </a>
                                <a class="btn btn-xs btn-default tooltips delLink" data-del-url="{{route('users-limit.delete',['id' => $userLimit->id])}}" data-placement="top" data-original-title="Delete" data-entity="contact">
                                    <i class="fa fa-trash-o text-primary"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            <!-- start: Paging Links -->
            @include('includes.partials.paging', ['entity' => $userLimits])
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