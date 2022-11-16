@extends('administration.layouts.app')

@section('title')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles-for-this-page')
@stop()

@section('page-title')
    Administration  <small>Users</small>
@stop()

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.access.users.active') }}</h3>

            <div class="box-tools pull-right">
                @include('administration.access.includes.partials._header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.access.users.table.id') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.name') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.email') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.roles') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.other_permissions') }}</th>
                        <th class="visible-lg">{{ trans('labels.backend.access.users.table.created') }}</th>
                        <th class="visible-lg">{{ trans('labels.backend.access.users.table.last_updated') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{!! $user->id !!}</td>
                            <td>{!! $user->name !!}</td>
                            <td>{!! link_to("mailto:".$user->email, $user->email) !!}</td>
                            <td>{!! $user->confirmed_label !!}</td>
                            <td>
                                @if ($user->roles()->count() > 0)
                                    @foreach ($user->roles as $role)
                                        {!! $role->name !!}<br/>
                                    @endforeach
                                @else
                                    {{ trans('labels.general.none') }}
                                @endif
                            </td>
                            <td>
                                @if ($user->permissions()->count() > 0)
                                    @foreach ($user->permissions as $perm)
                                        {!! $perm->display_name !!}<br/>
                                    @endforeach
                                @else
                                    {{ trans('labels.general.none') }}
                                @endif
                            </td>
                            <td class="visible-lg">{!! $user->created_at->diffForHumans() !!}</td>
                            <td class="visible-lg">{!! $user->updated_at->diffForHumans() !!}</td>
                            {{--<td>{!! $user->action_buttons !!}</td>--}}
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ url('administration/access/users/'.$user->id.'/edit') }}">
                                    <i class="fa fa-pencil" title="" data-placement="top" data-toggle="tooltip" data-original-title="Edit"></i>
                                </a>
                                <a class="btn btn-xs btn-info" href="{{ url('administration/access/user/'.$user->id.'/password/change') }}">
                                    <i class="fa fa-refresh" title="" data-placement="top" data-toggle="tooltip" data-original-title="Change Password"></i>
                                </a>
                                <a class="btn btn-xs btn-warning" href="{{ url('administration/access/user/'.$user->id.'/mark/0') }}">
                                    <i class="fa fa-pause" title="" data-placement="top" data-toggle="tooltip" data-original-title="Deactivate"></i>
                                </a>
                                <a class="btn btn-xs btn-danger" data-trans-title="Are you sure?" data-trans-button-confirm="Delete" data-trans-button-cancel="Cancel" data-method="delete" style="cursor:pointer;" onclick="$(this).find("form").submit();">
                                <i class="fa fa-trash" title="" data-placement="top" data-toggle="tooltip" data-original-title="Delete"></i>
                                    <form style="display:none" name="delete_item" method="POST" action="{{ url('administration/access/users/'.$user->id) }}">
                                        <input type="hidden" value="delete" name="_method">
                                        {!! Form::token() !!}
                                    </form>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pull-left">
                {!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }}
            </div>

            <div class="pull-right">
                {!! $users->render() !!}
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div> <!-- /.box -->
@stop()

@section('required-script-for-this-page')
@stop()

@section('script-handler-for-this-page')
@stop()