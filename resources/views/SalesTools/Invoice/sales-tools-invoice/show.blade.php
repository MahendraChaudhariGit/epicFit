@extends('layouts.master')

@section('content')

    <h1>SalesToolsInvoice</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th> PayTerms </th><th> InvTitle </th><th> BussReg </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $salestoolsinvoice->id }}</td> %%formBodyHtml%%
                </tr>
            </tbody>    
        </table>
    </div>

@endsection