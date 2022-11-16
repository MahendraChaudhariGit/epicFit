@extends('blank')

@section('page-title')
Ldc list
@if(isUserType(['Admin']))
<a class="btn btn-primary pull-right" href="{{ route('ldc.create') }}"><i class="ti-plus"></i> Add Ldc</a>
@endif
@stop

@section('content')
{!! displayAlert()!!}



{!! Form::hidden('salesProcessCompleted') !!}
{!! Form::hidden('consultationDate') !!}

<div class="panel panel-default">
    <div class="panel-body">
    <div class="row">
        <!-- start: Datatable Header -->
        @include('includes.partials.datatable_header', ['extra' => '<div class="btn-group"><!-- id="changeStatus"--><a class="btn btn-primary btn-o dropdown-toggle" data-toggle="dropdown" href="#">'.($filter?ucwords($filter):'All').' <span class="caret"></span></a><ul role="menu" class="dropdown-menu dropdown-light"><li><a href="'.route('clients').'">All</a></li><li></li><li><a href="'.route('clients').'/pre-consultation">Pre-Consultation</a></li><li><a href="'.route('clients').'/pre-benchmarking">Pre-Benchmarking</a></li><li><a href="'.route('clients').'/pre-training">Pre-Training</a></li><li><a href="'.route('clients').'/active">Active</a></li><li><a href="'.route('clients').'/inactive">Inactive</a></li><li><a href="'.route('clients').'/pending">Pending</a></li><li><a href="'.route('clients').'/on-hold">On hold</a></li><li><a href="'.route('clients').'/active-lead">Active lead</a></li><li><a href="'.route('clients').'/inactive-lead">Inactive lead</a></li><li><a href="'.route('clients').'/contra">Contra</a></li></ul></div>']) <!--<a href="'.route('clients').'/lead">Lead</a>-->
        <!-- end: Datatable Header -->
    </div>
       <input type="hidden" name="operateAsClient" value="">
        <table class="table table-striped table-bordered table-hover m-t-10" id="client-datatable" style="width:100%">
            <thead>
                <tr>
                    <th> Name</th>
                    <th class="">Start Date</th>
                    <th class="">End Date</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>
             
                @foreach($ldcData as $ldc)
                <tr>
                    <td>
                       {{$ldc->ldc_name}}
                        
                    </td>
                    <td class="">
                        {{$ldc->ldc_start_date}}
                    </td>
                    <td class="">
                        {{$ldc->ldc_end_date}}
                    </td>
                    <td class="text-center">
                        {{-- <a href="javascript:void(0)" data-client-id="{{$ldc->id}}" class="btn btn-xs btn-default tooltips" data-original-title="View" data-placement="top">
                            <i class="fa fa-share" style="color:#253746;"></i>
                        </a> --}}
                        <a href="{{ route('ldc.edit',['id'=>$ldc->ldc_id])}}"  class="btn btn-xs btn-default editLdc" data-original-title="Edit" data-placement="top">
                            <i class="fa fa-pencil" style="color:#253746;"></i>
                        </a>
                        <a href="{{ route('ldc.delete',['id'=>$ldc->ldc_id])}}"  class="btn btn-xs btn-default tooltips" data-original-title="Delete" data-placement="top">
                            <i class="fa fa-trash-o" style="color:#253746;"></i>
                        </a>
                    </td>
                   
                </tr>
                @endforeach 
            </tbody>
        </table>
        
        <!-- start: Paging Links -->
        {{-- @include('includes.partials.paging', ['entity' => $allClients]) --}}
        <!-- end: Paging Links -->
    </div>
</div>


{!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!}


@stop