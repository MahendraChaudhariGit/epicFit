@extends('layouts.master')

@section('content')

    <h1>Client</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>First Name</th><th>Last Name</th><th>Client Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $client->id }}</td> <td> {{ $client->first_name }} </td><td> {{ $client->last_name }} </td><td> {{ $client->client_status }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection