@extends('layouts.master')

@section('content')

    <h1>Salestoolsinvoice <a href="{{ url('salestoolsinvoice/create') }}" class="btn btn-primary pull-right btn-sm">Add New SalesToolsInvoice</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th> PayTerms </th><th> InvTitle </th><th> BussReg </th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($salestoolsinvoice as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td>{{ $item->payTerms }}</td><td>{{ $item->invTitle }}</td><td>{{ $item->bussReg }}</td>
                    <td>
                        <a href="{{ url('salestoolsinvoice/' . $item->id . '/edit') }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['salestoolsinvoice', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $salestoolsinvoice->render() !!} </div>
    </div>

@endsection
