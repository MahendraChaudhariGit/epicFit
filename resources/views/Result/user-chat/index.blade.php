@extends('masters.app')

@section('page-title')
<span >User Lists</span>
@stop

@section('required-styles')
    
@stop

@section('content')
{!! displayAlert()!!}

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover m-t-10" id="client-chat">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->count())
                        @foreach($users as $user)
                        <tr>
                           <td>
                                <a href="{{route('message.read', $user->id)}}">
                                    <img src="{{ dpSrc($user->profile_picture) }}" alt="{{ $user->name }} {{ $user->last_name }}" class="mw-50 mh-50" /> &nbsp;&nbsp;&nbsp;
                                    {{ $user->name.' '.$user->last_name }}
                                </a>
                           </td>
                           <td>
                                <a class="btn btn-xs btn-default tooltips chat" href="{{route('message.read', $user->id)}}" data-placement="top" data-original-title="Chat">
                                    <i class="fa fa-comments" style="color:#ff4401;"></i>
                                </a>
                           </td> 
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    
@stop

@section('required-script')
<script>
    $('.tooltips').tooltip();
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#client-chat').dataTable({"searching": false, "paging": false, "info": false });
</script>
@stop