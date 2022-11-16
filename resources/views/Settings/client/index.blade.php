@extends('layouts.master')

@section('content')

    <h1>Client <a href="{{ url('settings/client/create') }}" class="btn btn-primary pull-right btn-sm">Add New Client</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th>First Name</th><th>Last Name</th><th>Client Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($client as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td><a href="{{ url('settings/client', $item->id) }}">{{ $item->first_name }}</a></td><td>{{ $item->last_name }}</td><td>{{ $item->client_status }}</td>
                    <td>
                        <a href="{{ url('settings/client/' . $item->id . '/edit') }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['settings/client', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $client->render() !!} </div>
    </div>

@endsection
