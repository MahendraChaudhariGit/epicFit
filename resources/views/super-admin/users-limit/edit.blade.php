@extends('super-admin.layout.master')

@section('page-title')
   Update User Limit
@stop


@section('content')
	
	{!! Form::open(['route' => ['users-limit.update',$userLimit->id], 'id' => 'form-7', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison','method' => 'put']) !!}
    @include('super-admin.users-limit.form',array('userLimit'=>$userLimit,'button' => 'Update'))
    {!! Form::close() !!}
@stop