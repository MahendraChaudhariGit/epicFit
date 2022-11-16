@extends('blank')

@section('page-title')
    Service list 
    <a class="btn btn-primary pull-right" href="{{ url('settings/business/'.Session::get('businessId').'/edit#4') }}"><i class="ti-plus"></i> Add Service</a>
@stop

@section('content')
{!! displayAlert()!!}

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover m-t-10" id="client-datatable">
                    <thead>
                        <tr>
                            <th class="center">Logo</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            
                    @foreach($allServicesDetails as $key=>$services)
                  
                   		 <tr>
                            <td class="center">
                            	<a href="{{ url('service/'.$services['id']) }}">
                                <?php if($services['catogory'] == '1 on 1') { ?>
                                	<img src="{{ dpSrc($services['one_logo']) }}" alt="">
                                 <?php } else if ($services['catogory'] == 'TEAM Training') { ?>
                                    <img src="{{ dpSrc($services['team_logo']) }}" alt="">
                                 <?php } else { ?>
									  <img src="{{ dpSrc() }}" alt="">
								<?php } ?>
                                </a>
                            </td>
                            <td>
                               {!! $services['type'] or '' !!} <br>
                            </td>
                            <td >
                                <div>{!! $services['catogory'] or '' !!}</div>
                            </td>
                            <td>
                                <div{{ url('service/'.$services['id']) }}">{!! $services['location'] or '' !!} </div> <br>
                            </td>
                            <td class="center">
                                <div class="visible-md visible-lg hidden-sm hidden-xs">
                                    <a href="{{ url('service/'.$services['id']) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share" style="color:#ff4401;"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
@stop