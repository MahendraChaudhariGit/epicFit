@extends('super-admin.layout.master')

@section('page-title')
   Add User Limit
@stop


@section('content')
	
	{!! Form::open(['route' => ['users-limit.store'], 'id' => 'form-7', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison','method' => 'post']) !!}
    @include('super-admin.users-limit.form',array('button' => 'Create'))
    {!! Form::close() !!}
@stop