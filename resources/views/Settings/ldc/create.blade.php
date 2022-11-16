@extends('layouts.app')

@section('meta_description')
@stop()

@section('meta_author')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles-for-this-page')
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!}

@stop()

@section('page-title')
    Add Ldc
@stop

@section('content')

<div id="form-container" class="container-fluid container-fullw bg-white">
	{!! displayAlert()!!}
    <div class="alert alert-danger hidden" id="reqMsg">
        Atleast one field is required out of Email address and Phone number.
    </div>
	{!! Form::open(['url' => 'ldc/save', 'class' => 'margin-bottom-30', 'id' => 'createForm']) !!}
    	<fieldset class="padding-15">
    		<legend>LDC Details</legend>
        	<div class="row">
          <input type="hidden" name="ldc_id" value="{{$ldcData->ldc_id}}">
            	<div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('ldcname', 'Ldc Name*', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the Ldc name">
                            <i class="fa fa-question-circle"></i>
                        </span>
                        
                        {!! Form::text('ldcname',$ldcData->ldc_name?$ldcData->ldc_name:'', null, ['class' => 'form-control', 'required']) !!}
                    </div>
                   
                    <div class="form-group">
                        <label class="strong">Start Date</label>
                    <input type="date" name="startDate" value="{{$ldcData->ldc_start_date?$ldcData->ldc_start_date:''}}">
                      
                        <span class="help-block"></span>
                      </div>
                      <div class="form-group">
                        <label class="strong">End Date</label>
                        <input type="date" name="endDate" value="{{$ldcData->ldc_end_date?$ldcData->ldc_end_date:''}}">
                       
                        <span class="help-block"></span>
                    </div>
                     <!-- Start: membership service -->
          <fieldset class="padding-15" id="service-group">
            <legend> 
              Services&nbsp;
            <a class="btn btn-xs btn-primary" href="#" id="add-member-service" data-service-list="{{$services}}">
                  <i class=" fa fa-plus fa fa-white"></i>
              </a>
            </legend>
            <div class="row m-b-10 hidden-xs">
              <div class="col-sm-3">
                <strong>Name</strong>
              </div>
              <div class="col-sm-2">
                <strong>Limit</strong>
              </div>
              <div class="col-sm-4">
                <strong>Type</strong>
              </div>
    
            </div>
            <div class="row hidden">
              <div class="col-sm-3 col-xs-6">
                <div class="form-group serviceGroup">
                    {!! Form::select('mem_service',[],null,['class' => 'form-control mem_service ', 'data-title'=>'-- Select -- ', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-2 col-xs-6">
                <div class="form-group">
                    {!! Form::number('mem_limit',null, ['class' => 'form-control numericField mem_limit', 'min'=>'1', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-4 col-xs-6">
                <?php $options = ['every_week'=>'no of services every week','every_month'=>'no of services every month','every_fortnight'=>'no of services every fortnight']; ?>
                <div class="form-group limitTypeGroup">
                  {!! Form::select('mem_type', $options,null,  ['class' => 'form-control mem_type','data-title'=>'-- Select -- ']) !!}
                </div>
              </div>
              
              <div class="col-sm-1 col-xs-2">
                <a class="btn btn-xs btn-red remove-member-service-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
              </div>
            </div>
            @foreach($servicesData as $key => $value)
            <div class="row new-row">
              <div class="col-sm-3 col-xs-6">
                <div class="form-group serviceGroup">
                    {!! Form::select('mem_service'.$value->id, $servicesNew,array_key_exists($key,$servicesNew)?$key:null, ['class' => 'form-control mem_service ', 'data-title'=>'-- Select -- ', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-2 col-xs-6">
                <div class="form-group">
                    {!! Form::number('mem_limit'.$value->id, $value->limit, ['class' => 'form-control numericField mem_limit', 'min'=>'1', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-4 col-xs-6">
                <?php $options = ['every_week'=>'no of services every week','every_month'=>'no of services every month','every_fortnight'=>'no of services every fortnight']; ?>
                <div class="form-group limitTypeGroup">
                  {!! Form::select('mem_type'.$value->id, $options,array_key_exists($value->limit_type,$options)?$value->limit_type:null,  ['class' => 'form-control mem_type','data-title'=>'-- Select -- ']) !!}
                </div>
              </div>
              
              <div class="col-sm-1 col-xs-2">
                <a class="btn btn-xs btn-red remove-member-service-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
              </div>
            </div>
            @endforeach
          </fieldset>
         
          <!-- End : membership service -->
               
               
          <fieldset class="padding-15" id="session-group">
            <legend> 
              Sessions&nbsp;
              <a class="btn btn-xs btn-primary" href="#" id="add-member-session" data-session-list="{{$clsCat}}">
                  <i class=" fa fa-plus fa fa-white"></i>
              </a>
            </legend>
            <div class="row m-b-10 hidden-xs">
              <div class="col-sm-3">
                <strong>Name</strong>
              </div>
              <div class="col-sm-2">
                <strong>Limit</strong>
              </div>
              <div class="col-sm-4">
                <strong>Type</strong>
              </div>
            
            </div>
            <div class="row hidden">
              <div class="col-sm-3 col-xs-6">
                <div class="form-group sessionGroup">
                    {!! Form::select('mem_session',[],null, ['class' => 'form-control mem_session ', 'data-title'=>'-- Select -- ', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-2 col-xs-6">
                <div class="form-group">
                {!! Form::number('session_mem_limit',null, ['class' => 'form-control numericField session_mem_limit', 'min'=>'1', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-4 col-xs-6">
                  <?php $options = ['every_week'=>'no of sessions every week','every_month'=>'no of sessions every month','every_fortnight'=>'no of sessions every fortnight']; ?>
                <div class="form-group limitTypeGroup">
                  {!! Form::select('session_mem_type',$options, null, ['class' => 'form-control session_mem_type','data-title'=>'-- Select -- ']) !!}
                </div>
              </div>
              <div class="col-sm-1 col-xs-2">
                <a class="btn btn-xs btn-red remove-member-session-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
              </div>
        </div>
            @foreach($sessionsData as $key =>$value)

            <div class="row new-row">
                  <div class="col-sm-3 col-xs-6">
                    <div class="form-group sessionGroup">
                        {!! Form::select('mem_session'.$value->id, $clsCatNew,array_key_exists($key,$clsCatNew)?$key:null, ['class' => 'form-control mem_session ', 'data-title'=>'-- Select -- ', 'required'=>'required']) !!}
                    </div>
                  </div>
                  <div class="col-sm-2 col-xs-6">
                    <div class="form-group">
                    {!! Form::number('session_mem_limit'.$value->id, $value->limit, ['class' => 'form-control numericField session_mem_limit', 'min'=>'1', 'required'=>'required']) !!}
                    </div>
                  </div>
                  <div class="col-sm-4 col-xs-6">
                      <?php $options = ['every_week'=>'no of sessions every week','every_month'=>'no of sessions every month','every_fortnight'=>'no of sessions every fortnight']; ?>
                    <div class="form-group limitTypeGroup">
                      {!! Form::select('session_mem_type'.$value->id,$options,array_key_exists($value->limit_type,$options)?$value->limit_type:null,['class' => 'form-control session_mem_type','data-title'=>'-- Select -- ']) !!}
                    </div>
                  </div>
                  <div class="col-sm-1 col-xs-2">
                    <a class="btn btn-xs btn-red remove-member-session-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                  </div>
            </div>
            @endforeach 
          </fieldset>    
            
          <fieldset class="padding-15" id="pdf-group">
            <legend> 
              Add PDF&nbsp;
              <a class="btn btn-xs btn-primary" href="#" id="add-pdf">
                  <i class=" fa fa-plus fa fa-white"></i>
              </a>
            </legend>
            <div class="row m-b-10 hidden-xs">
              <div class="col-sm-5">
                <strong>Choose file</strong>
              </div>
              <div class="col-sm-3">
                <strong>Start Date</strong>
              </div>
            </div>
            <div class="row hidden">
              <input type="hidden" class="pdfFile" name="pdfFile" value="">
              <input type="hidden" class="pdfOriginalName" name="pdfOriginalName" value="">
              <div class="col-md-5 col-xs-6">
                <div class="form-group pdfGroup">
                  <input type="file" class="pdfName" name="pdfName" value="">
                  <span class="pdfFileName"></span>
                </div>
              </div>
              <div class="col-md-5 col-xs-6">
                <div class="form-group pdfStartGroup">
                  <input type="date" class="pdfStartDate" name="pdfStartDate" value="">
                </div>
              </div>
              <div class="col-md-2 col-xs-2">
                <a class="btn btn-xs btn-red remove-pdf-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
              </div>
            </div>
            @foreach ($pdfData as $key => $value)
           
            <div class="row new-row">
            <input type="hidden" class="pdfFile" name="pdfFile{{$value->id}}" value="{{$value->file_name}}">
            <input type="hidden" class="pdfOriginalName" name="pdfOriginalName{{$value->id}}" value="{{$value->original_name}}">

              <div class="col-md-5 col-xs-6">
                <div class="form-group pdfGroup">
                  <input type="file" class="pdfName" name="pdfName{{$value->id}}" value="">
                <span class="pdfFileName">{{$value->original_name}}</span>
                </div>
              </div>
              <div class="col-md-5 col-xs-6">
                <div class="form-group pdfStartGroup">
                <input type="date" class="pdfStartDate" name="pdfStartDate{{$value->id}}" value="{{$value->pdfStartDate}}">
                </div>
              </div>
              <div class="col-md-2 col-xs-2">
                <a class="btn btn-xs btn-red remove-pdf-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
              </div>
            </div>    
            @endforeach
          </fieldset> 
        
        <div class="form-group clearfix">
            {!! Form::Button('Submit <i class="fa fa-arrow-circle-right"></i>', ['class' => 'btn btn-primary pull-right saveLdc', 'type' => 'submit']) !!}
        </div>
    {!! Form::close() !!}
</div>
@stop()

@section('required-script-for-this-page')
{!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!}

{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

{!! Html::script('assets/js/ldc.js') !!}
@stop()

@section('script-handler-for-this-page')
   
@stop()

@section('script-after-page-handler')
@stop()