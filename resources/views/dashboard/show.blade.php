@extends('layouts.app')
@section('required-styles-for-this-page')
{!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
{!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}
    <!-- start: Bootstrap Select Master -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    <!-- end: Bootstrap Select Master -->

<!-- start: Sweet alert css -->
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
    <!-- end: Sweet alert css -->

<!-- Start: NEW timepicker css -->  
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
    <!-- End: NEW timepicker css -->
<style type="text/css">
    .li_checkbox{

    }
.li_checkbox input {
    display: none;
}
.li_checkbox input + label {
    padding-left: 35px;
    border-bottom: 1px solid #E4E6EB;
    margin-left: 0 !important;
    padding: 10px 10px 10px 35px !important;
    width: 100%;
}
    
.li_checkbox input + label:before {
    position: absolute;
    content: "\f096";
    left: 25px;
    font: normal normal normal 14px/1 FontAwesome;
    color: #C7CBD5;
    font-size: 18px;
}
.li_checkbox input:checked + label:before{
    content: "\f046";
}
.li_checkbox input:checked + label span{
    opacity: 0.25;
    text-decoration: line-through;
}
</style>
@stop()
@section('page-title')
<!-- start: Dashboard Title -->
<div class="row">
    <div class="col-sm-7">
        <h1 class="mainTitle">Dashboard</h1>
    </div>
    <!--<div class="col-sm-5"> -->
    <!-- start: MINI STATS WITH SPARKLINE -->
    <!--<ul class="mini-stats pull-right">
        <li>
        	<div class="sparkline-1">
        		<span ></span>
        	</div>
        	<div class="values">
        		<strong class="text-dark">18304</strong>
        		<p class="text-small no-margin">
        			Sales
        		</p>
        	</div>
        </li>
        <li>
        	<div class="sparkline-2">
        		<span ></span>
        	</div>
        	<div class="values">
        		<strong class="text-dark">&#36;3,833</strong>
        		<p class="text-small no-margin">
        			Earnings
        		</p>
        	</div>
        </li>
        <li>
        	<div class="sparkline-3">
        		<span ></span>
        	</div>
        	<div class="values">
        		<strong class="text-dark">&#36;848</strong>
        		<p class="text-small no-margin">
        			Referrals
        		</p>
        	</div>
        </li>
        </ul>-->
        <!-- end: MINI STATS WITH SPARKLINE -->
    <!--</div> -->
</div>
<!-- end: DASHBOARD TITLE -->
@stop
@section('content')

<!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->

<!-- start: PANEL WITH HEADING -->
<div class="container-fluid container-fullw bg-white">
    <!-- Start: Manage panel-->
    @if(Session::has("businessId"))
    <div class="row">
        <div class="col-sm-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="{{ route('calendar-new') }}">
                        <span class="fa-stack fa-2x">
                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                        <i class="fa fa-calendar fa-stack-1x fa-inverse"></i>
                        </span>
                        <h2 class="StepTitle">Bookings</h2>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="{{ url('clients') }}">
                        <span class="fa-stack fa-2x">
                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                        <i class="fa fa-users fa-stack-1x fa-inverse"></i>
                        </span>
                        <h2 class="StepTitle">Clients</h2>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-sm-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="{{ route('contacts') }}">
                        <span class="fa-stack fa-2x">
                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                        <i class="fa fa-fax fa-stack-1x fa-inverse"></i>
                        </span>
                        <h2 class="StepTitle">Contacts</h2>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="{{ route('memberships') }}">
                        <span class="fa-stack fa-2x">
                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                        <i class="fa fa-newspaper-o fa-stack-1x fa-inverse"></i>
                        </span>
                        <h2 class="StepTitle">Memberships</h2>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="{{ route('staffs') }}">
                        <span class="fa-stack fa-2x">
                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                        <i class=" fa fa-user-md fa-stack-1x fa-inverse"></i>
                        </span>
                        <h2 class="StepTitle">Staff roster</h2>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    @endif
    <!-- End: Manage panel-->
    <!-- Start: Gym+Business Setup panel-->    
    @if( !Session::has("businessId") || !Session::has("ifBussHasLocations") || !Session::has("ifBussHasStaffs") || (!Session::has("ifBussHasClasses") && $business->is_class_step_complete != '1') || !Session::has("ifBussHasServices") ||  !Session::has("ifBussHasClients")) 
    <div class="row">
        <div class="col-md-4">
            <h5 class="over-title margin-bottom-15 clearfix">Gym <span class="text-bold">setup checklist</span>
                <i class="fa fa-check pull-right"></i>
            </h5>
            <p class="margin-top-20">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
            </p>
        </div>
        <div class="col-md-8">
            <div class="panel panel-white" id="panel1">
                <div class="panel-heading" >
                    <h4 class="panel-title">Business Setup </h4>
                </div>
                <!-- start: PANEL HEADING -->
                <!-- start: PANEL BODY -->
                <div class="panel-body" >
                    <ul class="todo">
                        <li>	
                            @if(Session::has("businessId"))
                            <a class="todo-actions" href="#">
                            {!! iconfunction("checked","Business Details") !!}
                            </a>
                            @else
                            <a class="todo-actions" href="{{ url('settings/business/create') }}">
                            {!! iconfunction("unchecked","Business Details") !!}
                            </a>   
                            @endif
                        </li>
                        <li>
                            {!! verifyexistence("locations"); !!}
                        </li>
                        
                        <li>
                            {!! verifyexistence("staffs"); !!}
                        </li>
                        <li>
                                
                            <!--{!! verifyexistence("services"); !!}-->
                            @if(Session::has("businessId") && $default_completed_service >= 2 )
                                <a class="todo-actions" href="#">
                                {!! iconfunction("checked","Service Information") !!}
                                </a>
                            @elseif(Session::has("businessId") && $default_completed_service < 2)
                                <a class="todo-actions" href="{{ route('services') }}">
                                {!! iconfunction("unchecked","Service Information") !!}
                                </a>
                            @else
                                <a class="todo-actions" href="#">
                                {!! iconfunction("unchecked","Service Information") !!}
                                </a>   
                            @endif
                        </li>
                        {{-- <li>
                            {!! verifyexistence("classes"); !!}
                        </li> --}}
                        <li class="li_checkbox">
                            <input type="checkbox" id="classdetail" name="class_step" {{$business != null && $business->is_class_step_complete == '1'?'checked':''}}>
                            <label for="classdetail"><span>Class Details</span></label>
                        </li>
                        <li>
                            {!! verifyexistence("clients"); !!}
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                    </button>
                    <h4 class="modal-title">Panel Configuration</h4>
                </div>
                <div class="modal-body">
                    Here will be a configuration form
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                    </button>
                    <button type="button" class="btn btn-primary">
                    Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- End: Gym+Business Setup panel-->
@endif
<!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->
<!-- start :left side panel -->
    <div class="row">
        @if($business)
        <div class="col-sm-5 col-lg-4">
            <div class="user-left">
                <div class="center">
                    <h4>{{ $business->trading_name }}</h4>
                    <div>
                        <div class="user-image">
                            <div class="thumbnail">
                                <img src="{{ dpSrc($business->logo) }}" class="img-responsive logoPreviewPics previewPics" id="profile-userpic-img" alt="{{ $business->trading_name }}">
                            </div>
                            <div class="form-group upload-group">
                                <input type="hidden" name="prePhotoName" value="{{ dpSrc($business->logo) }}">
                                <input type="hidden" name="entityId" value="{{$business->id}}">
                                <input type="hidden" name="saveUrl" value="business/photo/save">
                                <input type="hidden" name="photoHelper" value="logo">
                                <input type="hidden" name="cropSelector" value="">
                                <div>
                                    <label class="btn btn-primary btn-file">
                                    <span>Change Logo</span>
                                    <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ( $business->website || $business->facebook )
                    <hr>
                    <div class="social-icons block">
                        <ul>
                            @if($business->website)
                            <li data-placement="top" data-original-title="Twitter" class="social-github tooltips">
                                <a href="http://{{$business->website}}" target="_blank">Twitter</a>
                            </li>
                            @endif
                            @if($business->facebook)
                            <li data-placement="top" data-original-title="Facebook" class="social-facebook tooltips">
                                <a href="http://{{ $business->facebook }}" target="_blank">Facebook</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <hr>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th colspan="3">General Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Business Type:</td>
                                <td>{{ $business->typeName->bt_value }}</td>
                                <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                            </tr>
                            <tr>
                                <td>Currency:</td>
                                <td>{{ $business->currencyInFull }}</td>
                                <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                            </tr>
                            <tr>
                                <td>Time Zone:</td>
                                <td>{{ $business->time_zone }}</td>
                                <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th colspan="3">Contact Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Email:</td>
                                <td><a href="mailto:{{ $business->email }}">{{ $business->email }}</a></td>
                                <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                            </tr>
                            <tr>
                                <td>Phone:</td>
                                <td><a href="tel:{{ $business->phone }}">{{ $business->phone }}</a></td>
                                <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>{{ $business->address_line_one.', '.$business->address_line_two.', '.$business->city.', '.$business->stateName.', '.$countries[$business->country].', '.$business->postal_code }}</td>
                                <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th colspan="3">Users Limit Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($usersLimitData)
                            <tr>
                                <td>Users(Upto):</td>
                                <td>{{ $usersLimitData['usersLimit'] }}</a></td>
                            </tr>
                            <tr>
                                <td>Price:</td>
                                <td>${{ $usersLimitData['price'] }}</a></td>
                            </tr>
                            @else
                            <tr><td>Not Defined<td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if(!Session::has("ifBussHasClients"))
            @include('includes.partials.dashboard_addtask')
        @endif
       
            @if(Session::has("ifBussHasClients"))
                <!--Start: Show when clint is created -->
                <div class="col-sm-7 col-lg-8">
                    <div class="panel panel-white no-radius" id="visits">
                        <div class="panel-heading border-light">
                            <h4 class="panel-title"> Client Registrations </h4>
                        </div>
                        <div collapse="visits" class="panel-wrapper">
                            <div class="panel-body">
                                <div class="height-350">
                                    <canvas id="chart2" class="full-width" width="589" height="350" style="width: 589px; height: 350px;"></canvas>
                                    <div class="clearfix">
                                        <div class="inline pull-left">
                                            <div id="chart2Legend" class="chart-legend"></div>
                                            <!--Start: legend -->
                                            <div id="barLegend" class="chart-legend">
                                                <ul class="bar-legend">
                                                    <li>
                                                        <span style="background-color:rgba(66,134,244,0.5)"></span>Active Clients
                                                    </li>
                                                    <li>
                                                        <span style="background-color:rgba(151,187,205,0.5)"></span>Inactive Clients
                                                    </li>
                                                    <li>
                                                        <span style="background-color:rgba(224,204,130,0.5)"></span>On-Hold Clients
                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End: legend -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Start: Show when clint is created -->
            @endif 
        @endif
    </div>
    <div class="row">
        @if($business && Session::has("ifBussHasClients"))
        <div class="col-md-4 col-lg-4 col-sm-6">
            <div class="panel panel-white no-radius">
                <div class="panel-heading border-bottom">
                    <h4 class="panel-title">Clients 
                        <a href="#chartSetting" class="text-primary pull-right" data-toggle="modal" data-type="clientsChart" data-settingdata="{{ isset($clients_chart)?$clients_chart:'' }}"><i class="ti-settings"></i>
                        </a>
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="text-center" align="center">
                        <span class="mini-pie pie-style">
                            <canvas id="chart3" class="full-width"></canvas>
                            <span>{{ $totalclients }}</span>
                        </span>
                    </div>
                    <div class="margin-top-20 text-center legend-xs inline">
                        <div id="chart3Legend" class="chart-legend"></div>
                    </div>
                </div>
               
                <?php
                     $check_border=3;
                     $client_chart=$sale_chart=[];
                    if(isset($clients_chart) && isset($sales_chart)){
                        $client_chart[]=json_decode($clients_chart);
                        $sale_chart[]=json_decode($sales_chart);
                    } 
                ?>
                
                @if(count($client_chart))

                <div class="panel-footer">
                    <div class="clearfix padding-5 space5">
                        @if(array_key_exists('active', $client_chart))
                        <?php $check_border=1;?>
                        <div class="col-xs-4 text-center no-padding margin-bottom-15">
                            
                                <h3 class="text-bold block no-margin">{{ $count_active }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_active }}%</span>
                                <span class="text-bold">Active</span>
                            
                        </div>
                        @endif
                        @if(array_key_exists('contra', $client_chart))
                        <div class="col-xs-4 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 3){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_contra }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_contra }}%</span>
                                <span class="text-bold">Contra</span>
                            </div>
                        </div>
                        @endif
                        @if(array_key_exists('inactive', $client_chart))
                        <div class="col-xs-4 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 3){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_inactive }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_inactive }}%</span>
                                <span class="text-bold">Inactive</span>
                            </div>
                        </div>
                        @endif
                        @if(array_key_exists('on_hold', $client_chart))
                        <div class="col-xs-4 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 3){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_onhold }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_onhold }}%</span>
                                <span class="text-bold">On Hold</span>
                            </div>
                        </div>
                        @endif
                        @if(array_key_exists('pending', $client_chart))
                        <div class="col-xs-4 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 3 ){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_pending }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_pending }}%</span>
                                <span class="text-bold">Pending</span>
                            </div>
                        </div>
                        @endif
                        @if(array_key_exists('other', $client_chart))
                        <div class="col-xs-4 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 3){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_other }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_other }}%</span>
                                <span class="text-bold">Other</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-4 col-lg-4  col-sm-6">
            <div class="panel panel-white no-radius">
                <div class="panel-heading border-bottom">
                    <h4 class="panel-title">Clients in Sales Process 
                      <a href="#chartSetting" class="text-primary pull-right" data-toggle="modal" data-type="salesProChart" data-settingdata="{{ isset($sales_chart)?$sales_chart:'' }}"><i class="ti-settings"></i>
                      </a>
                    </h4>
                    
                </div>
                <div class="panel-body">
                    <div class="text-center">
                        <span class="mini-pie pie-style">
                            <canvas id="pie_chart2" class="full-width"></canvas>
                            <span>{{ $totalclients2 }}</span>
                        </span>
                    </div>
                    <div class="margin-top-20 text-center legend-xs inline">
                        <div id="pie_2_Legend" class="chart-legend"></div>
                    </div>
                </div>
                @if(count($sale_chart))
                <?php $check_border=2; ?>
                <div class="panel-footer">
                    <div class="clearfix padding-5 space5">
                        @if(array_key_exists('sales_pending', $sale_chart))
                        <?php $check_border=1;?>
                        <div class="col-xs-6 text-center no-padding margin-bottom-15">
                            <div>
                                <h3 class="text-bold block no-margin">{{ $count_lead }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_lead }}%</span>
                                <span class="text-bold">Pending</span>
                            </div>
                        </div>
                        @endif
                        @if(array_key_exists('pre_consultation', $sale_chart))
                        <div class="col-xs-6 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 2){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_pre_preconsult }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_pre_preconsult }}%</span>
                                <span class="text-bold">Pre-Consultation</span>
                            </div>
                        </div>
                        @endif
                        @if(array_key_exists('pre_benchmark', $sale_chart))
                        <div class="col-xs-6 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 2){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_pre_benchmark }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_pre_benchmark }}%</span>
                                <span class="text-bold">Pre-Benchmark</span>
                            </div>
                        </div>
                        @endif
                        @if(array_key_exists('pre_training', $sale_chart))
                        <div class="col-xs-6 text-center no-padding margin-bottom-15">
                            <div class="<?php if($check_border < 2){ echo 'border-left border-dark'; $check_border++;}else{$check_border=1;} ?>">
                                <h3 class="text-bold block no-margin">{{ $count_pre_training }}</h3>
                                <span class="text-light block text-extra-large">{{ $total_pre_training }}%</span>
                                <span class="text-bold">Pre-Training</span>
                            </div>
                        </div>
                        @endif
                        <!--<div class="col-xs-4 text-center no-padding">
                            <div class="border-right border-dark">
                            	<span class="text-bold block text-extra-large"> </span>
                            	<span class="text-light">Custom</span>
                            </div>
                            </div>-->
                        <!--<div class="col-xs-4 text-center no-padding">
                            <span class="text-bold block text-extra-large"></span>
                            	<span class="text-light">Other</span>
                            </div> -->
                    </div>
                </div>
               @endif
            </div>
        </div>
    <!-- Start: Chart setting  Model -->
            @include('includes.partials.chart_settings',['client_chart' => $client_chart, 'sales_chart' => $sale_chart])
    <!-- End: Chart setting  Model -->
    
    <!-- Start: rapido theme -->
         
            @include('includes.partials.dashboard_addtask')
         @endif
    <!-- End: rapido theme -->
   </div>
 

<!-- Start: Sales bar chart -->
   
    <div class="row">
       @if($business)
             @include('includes.partials.dashboard_sales_bar_chart')
       @endif
    </div>

   <!-- End: Sales bar chart -->
</div>

   

<!--Start: Rapido Add Task Model-->
@if($business)
<div class="modal fade" id="addtask" role="dialog" tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Task</h4>
            </div>
            <div class="modal-body bg-white">
            
                {!! Form::open(['url' => 'dashboard/task', 'role' => 'form', 'id' =>'taskForm']) !!}
                <input type="hidden" name="taskFormId" value="">
                <input type="hidden" name="taskRepeat" value="">
                <!-- <input type="hidden" name="authId" value=""> -->
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="padding-15"> <!-- class="client-form" -->
                            <legend>
                                Task &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            <div class="form-group">
                                {!! Form::label('taskName', 'Task Name *', ['class' => 'strong']) !!}
                                {!! Form::text('taskName', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('taskDueDate', 'Task Due Date *', ['class' => 'strong']) !!}
                                <div class="row">
                                    <div class="col-md-8">
                                        {!! Form::text('taskDueDate', null, ['class' => 'form-control eventDatepicker onchange-set-neutral', 'autocomplete' => 'off', 'required', 'id'=> 'taskDueDate']) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="taskDueTime" class="form-control  no-clear timepicker1" data-default-time="9:30 AM" id="taskDueTime" required="required">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <!-- <div class="form-group">
                                {!! Form::label('taskDueDate', 'Task Due Date *', ['class' => 'strong']) !!}
                                {!! Form::text('taskDueDate', null, ['class' => 'form-control eventDatepicker onchange-set-neutral', 'autocomplete' => 'off', 'required', 'id'=> 'taskDueDate']) !!}
                            </div>
                            {!! Form::label('taskDueTime', 'Task Due Time *', ['class' => 'strong']) !!}
                            <div class="form-group input-group bootstrap-timepicker timepicker">    
                                <input type="text" name="taskDueTime" class="form-control input-sm no-clear timepicker1" data-default-time="9:30 AM" id="taskDueTime" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div> -->

                            <div class="form-group">
                            {!! Form::label('taskCategory', 'Task Category *', ['class' => 'strong']) !!}
                            {!! Form::select('taskCategory', $tc , null, ['class' => 'form-control onchange-set-neutral','required' => 'required', 'id'=>'taskcategoryid']) !!}
                            </div>

                            <div class="form-group">
                            {!! Form::label('taskNote', 'Task Note *', ['class' => 'strong']) !!}
                            {!! Form::textarea('taskNote', null, ['class' => 'form-control textarea']) !!}
                            </div>
                            
                           <!-- <div class="input-group bootstrap-timepicker timepicker">
                                {!! Form::text('task_due_time', null, ['class' => 'form-control input-sm no-clear timepicker1','data-default-time' => '9:30 AM']) !!}
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div>-->

                            
                            <!--<label for="ventRepeat" class="strong">Repeat</label>
                             <input class="mw-100 eventDatepicker onchange-set-neutral hasDatepicker rapidoDatePicker" autocomplete="off" required type="text"> -->

                        <div class="form-group form-inline">
                            <div class="checkbox clip-check m-b-0 check-primary">
                                <input type="checkbox" class="onchange-set-neutral" id="remindercheck" name="reminder" value="1">
                                <label for="remindercheck" class="m-r-0 no-error-label">
                                    <strong>Remind Me Before</strong> 
                                </label>
                                {!! Form::select('reminderVal', ["" => "-- Select --", 1 => '1 hour', 2 => '2 hours', 3 => '3 hours', 4 => '4 hours', 5 => '5 hours', 6 => '6 hours',7 => '7 hours',8 => '8 hours',9 => '9 hours',10 => '10 hours',11 => '11 hours', 12 => '12 hours', 13 => '13 hours', 14 => '14 hours', 15 => '15 hours', 16 => '16 hours',17 => '17 hours',18 => '18 hours', 19 => '19 hours', 20 => '20 hours', 21 => '21 hours', 22 => '22 hours', 23 => '23 hours', 24 => '24 hours'] , null, ['class' => 'mw-100 onchange-set-neutral', 'id'=>'remindercheckid','disabled']) !!}
                                <span class="help-block m-y-0" style="display: none;"></span>
                            </div>
                        </div>

                        <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" value="Fri" id="rcheck">
                                <label for="rcheck"><strong>Repeat </strong></label>
                                </div>
                        </div>                                                
                        </fieldset>

                        <fieldset class="padding-15 event-reccur" id="recurrencefield"><!-- class="client-form" -->
                            <legend>
                                Recurrence &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            
                            <!--<div>-->
                                <div class="form-group">
                                    {!! Form::label('eventRepeat', 'Repeat', ['class' => 'strong']) !!}
                                    {!! Form::select('eventRepeat', ['' => '-- Select --', 'None' => 'None', 'Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly'], null, ['class' => 'form-control', 'id' => 'eventrepeatid']) !!}
                                </div>

                                <div class="eventRepeatFields">
                                        <div class="form-group">
                                            {!! Form::label('eventRepeatInterval', 'Repeat every *', ['class' => 'strong']) !!}
                                            <div>
                                                {!! Form::select('eventRepeatInterval', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-92p onchange-set-neutral', 'required', 'id' => 'eventrepeatintervalid']) !!} 
                                                <span class="eventRepeatIntervalUnit">days</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label(null, 'Ends *', ['class' => 'strong']) !!}
                                            <div class="moveErrMsg no-error-labels">
                                                <div class="radio clip-radio radio-primary">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndAfter" value="After">
                                                    <label for="appointEventRepeatEndAfter">
                                                        After
                                                    </label>
                                                    {!! Form::select('eventRepeatEndAfterOccur', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-120 onchange-set-neutral', 'id' => 'eventrepeatendafteroccurid']) !!}
                                                    occurrences
                                                </div>
                                                <div class="radio clip-radio radio-primary">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndOn" value="On">
                                                    <label for="appointEventRepeatEndOn">
                                                        On
                                                    </label>
                                                    {!! Form::text('eventRepeatEndOnDate', null, ['class' => 'form-control mw-120 inlineBlckDisp eventDatepicker onchange-set-neutral', 'autocomplete' => 'off']) !!}
                                                </div>
                                                <div class="radio clip-radio radio-primary m-b-0">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndNever" value="Never">
                                                    <label for="appointEventRepeatEndNever">
                                                        Never
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="help-block placeErrMsg m-t-0"></span>
                                            <div class="eventRepeatWeekdays no-error-labels">
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays0" value="Mon" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays0"> Mon </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays1" value="Tue" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays1"> Tue </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays2" value="Wed" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays2"> Wed </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays3" value="Thu" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays3"> Thu </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays4" value="Fri" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays4"> Fri </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays5" value="Sat" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays5"> Sat </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays6" value="Sun" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays6"> Sun </label>
                                                </div>
                                            </div>
                                            <span class="help-block m-t-0"></span>
                                        </div>
                                </div>       
                            <!--</div>--> 
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Close
                </button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                {!! Form::submit('Submit', ['class' => 'btn btn-primary' , 'id' => 'taskbtn']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif

<!--Start: Rapido Add Category Model-->
@if($business)
<div class="modal fade" id="addcategory" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title categorylabel" id="myModalLabel">Add Category</h4>
            </div>
            <div class="modal-body bg-white">
            	{!! Form::open(['url' => 'dashboard/category', 'role' => 'form', 'id'=>'categoryForm']) !!}
                <input type="hidden" name="hiddenCategId" value="">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="padding-15 "><!-- class="client-form" -->
                            <legend>
                                Category &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            <div class="form-group">
                                {!! Form::label('categoryName', 'Category Name *', ['class' => 'strong']) !!}
                                {!! Form::text('categoryName', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <!--<div class="form-group">
                                {!! Form::submit('Add Category', ['class' => 'btn btn-primary form-control']) !!}
                            </div>-->
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Close
                </button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                 {!! Form::submit('Submit', ['class' => 'btn btn-primary' , 'id' => 'categorybtn']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif
<!--End: Rapido Add Category Model-->

<!--- temprary modal here-->
@if($business)
    @include('includes.partials.reminder_modal');
@endif

<!-- end: PANEL WITH HEADING -->
@stop
@section('required-script-for-this-page')

 {!! Html::script('assets/js/jquery-ui.min.js') !!} 
<!--{!! Html::script('assets/plugins/datepicker/js/bootstrap-datepicker.js') !!}-->
<!-- start: moment -->
<!-- {!! Html::script('vendor/moment/moment.min.js') !!}
{!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
{!! Html::script('assets/js/set-moment-timezone.js') !!}  -->
<!-- end : moment  -->
<!-- start:  jquery validation -->
<!-- {!! Html::script('vendor/Chart.js/Chart.min.js') !!} -->
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script> 

{!! Html::script('vendor/jquery.sparkline/jquery.sparkline.min.js') !!}

<!-- end:  jquery validation -->

<script src="{{ asset('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') }}" ></script>
<script src="{{ asset('assets/plugins/Jcrop/js/script.js') }}" ></script>

<!-- Start:  NEW timepicker js -->
   {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!} 
    <!-- End: NEW timepicker js -->

<!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    <!-- end: Bootstrap Select Master -->
<!-- start: jquery validation -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
    <!-- end: jquery validation -->
<!-- start: Full Calendar Custom Script -->
    {!! Html::script('assets/js/helper.js') !!}
    <!-- end: Full Calendar Custom Script -->
    
    {!! Html::script('assets/js/recurrence.js') !!}

    {!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}



<script>

/*var loggedInUser = {
        //type: '{{ Session::get('userType') }}',
        type: '{{ Auth::user()->account_type }}',
        id: {{ Auth::user()->account_id }},
        userId: {{ Auth::id() }},
        name: '{{ Auth::user()->fullName }}'
};
*/
var shownPopover = [];
var calPopupHelper = $('#calPopupHelper');
var popoverContainer = $('#container');

var deleteReccurEventPopoverOpt = {
        placement: 'left',
        html: true,
        content: "Would you like to cancel only this task,<br> or this and all following tasks in the<br> series?<a class='btn btn-default btn-block delete-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block delete-event' href='#' data-target-event='this'>This only</a>",
        container: popoverContainer,
        title: "<strong>Cancel recurring task?</strong>",
        trigger: 'manual'
    };

var editReccurEventPopoverOpt = {
        placement: 'left',
        html: true,
        content: "Would you like to change only this task,<br> or this and all following tasks in the<br> series?<a class='btn btn-default btn-block update-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block update-event' href='#' data-target-event='this'>This only</a>",
        container: popoverContainer,
        title:"<strong>Edit recurring task?</strong>",
        trigger: 'manual'
};

$(document).ready(function(){
   initCustomValidator();

  


   $("#taskFilter").datepicker({
        showOn: 'button',
        buttonText: moment().format('D MMM YYYY'),
        buttonImageOnly: false,
        dateFormat:"d M yy",
        
        onSelect: function( newText ){
            //$('#taskFilterSection img').attr('alt',newText);
            $('.ui-datepicker-trigger').text(newText);
            var dropDownName=$('.rapidodd').text();
            var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
            $(triggerChange).trigger('click');
        }
    });

   $('.ui-datepicker-trigger').addClass('btn btn-sm btn-transparent-white');
   

});    

    var maxClientRegNum = 84,
        count_active = {{ $count_active }},
    	count_contra = {{ $count_contra }},
    	count_inactive = {{ $count_inactive }},
    	count_onhold = {{ $count_onhold }},
    	count_pending = {{ $count_pending }},
    	count_other = {{ $count_other }},
    	count_lead = {{ $count_lead }},
    	count_pre_preconsult = {{ $count_pre_preconsult }},
    	count_pre_benchmark = {{ $count_pre_benchmark }},
    	count_pre_training = {{ $count_pre_training }},
    	MaxNumofClients={{ $MaxNumofClients }},
    	total_inactive_clients_permonth={{ json_encode($count_inactive_clients) }},
        total_onhold_clients_permonth={{ json_encode($count_onhold_clients) }},
    	// total_new_client_permonth={{ json_encode($count_new_client) }},
        total_new_client_permonth={{ json_encode($count_new_client) }},
        total_confirmed={{ json_encode($final_conf) }},
        total_pencilledin={{ json_encode($final_pencil) }},
        total_conf_time={{ json_encode($conf_time,1) }},
        total_pen_time={{ json_encode($pen_time) }},
        total_notshow_time={{ json_encode($notshow_time) }},
        total_attended_time={{ json_encode($attended_time) }},
        total_busy_time={{ json_encode($busy_time) }},
        total_cls_time={{ json_encode($cls_time) }},
        total_working_time={{ json_encode($total_working_time) }}, 
        max_time={{ $maxTime }},
        max_value={{ $maxVal }};
    var bladeType = "Dashboard";
    var sales_chart=<?php echo isset($sales_chart)?$sales_chart:0; ?>;
    var clients_chart=<?php echo isset($clients_chart)?$clients_chart:0; ?>;       
    
       
    //var dateA = moment().subtract('months', 6);
    ///var x=dateA.fromNow();
    var formatDate= 1399919400000;
    var responseDate = moment(formatDate).format('DD/MM/YYYY');
    //console.log(x);
    //alert(responseDate);
    dateTo = moment().format('YYYY-MM-DD');
    dateFrom = moment().subtract(6,'M').format('YYYY-MM-DD');
    //$preconsult = Clients::where('account_status','Pre-Consultation')->get();
    //$test= Clients::whereBetween('business_id', array(30, 37))->get();
    //alert(dateFrom);
    //console.log(moment('20-04-1994').format('MM/DD/YYYY'));
    
    //$('.eventDatepicker').click(function(){
    /*$('.rDatepicker').datepicker({
        autoclose:true,
        dateFormat:"d M yy",
        format:"dd-mm-yyyy",
        minDate: 0,
        startDate: new Date()
    });*/
    //$('.eventDatepicker').datepicker({autoclose:true, dateFormat:"d M yy", minDate: 0, startDate: new Date()});
    //});

   $('.todo-actions:not(.taskDiv)').click(function(e){
      e.preventDefault();
      var todoUrl = $(this).attr('href');
      if(todoUrl != '#' && todoUrl != undefined)
          window.location.replace(todoUrl);
   });

@if(!Session()->has('businessId'))
swal({
    title: "Let's start by setting up your business",
    allowOutsideClick: false,
    showCancelButton: false,
    confirmButtonText: 'Yes',
    confirmButtonColor: '#ff4401',
    cancelButtonText: "No"
}, 
function(isConfirm){
    if(isConfirm){
       window.location.href = "{{route('business.create')}}"; 
    }
});
@endif   
</script>

<!-- start: new-dashboard jquery -->
{!! Html::script('assets/js/new-dashboard.js?v='.time()) !!}
<!-- end: new-dashboard jquery -->
@if($business)
   <!-- start: Index jquery -->
   @if(Session::has("ifBussHasClients"))
    {!! Html::script('assets/js/index.js?v='.time()) !!} 
   @endif   
    <!-- end: Index jquery -->
    <!-- start:dashboard Jquery for sales bar and productivity bar -->
    {!! Html::script('assets/js/dashboard.js?v='.time()) !!}
    <!-- end: dashboard Jquery for sales bar and productivity bar -->

    <!-- {!! Html::script('assets/js/new-dashboard2.js') !!} -->
@endif

@stop()
@section('script-handler-for-this-page')

@if(Session::has("ifBussHasClients"))
  Index.init();
@endif
@stop()