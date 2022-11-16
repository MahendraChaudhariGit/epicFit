@extends('blank')
@section('plugin-css')
{!! Html::style('assets/css/plugins.css') !!}
<!-- start: Bootstrap datepicker --> 
{!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}
<!-- end: Bootstrap datepicker --> 

<!-- Start: NEW timepicker css -->  
{!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
<!-- End: NEW timepicker css -->

<!-- Start: NEW datetimepicker css -->
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
<!-- End: NEW datetimepicker css -->

<!-- start: Bootstrap calendar --> 
{!! Html::style('assets/css/goal-buddy.css?v='.time()) !!}
<!-- start: Bootstrap calendar -->

<!-- Start: Activities planner -->
{!! HTML::style('assets/plugins/fitness-planner/custom/style.css?v='.time()) !!} 
{!! Html::style('assets/plugins/fitness-planner/css/api.css?v='.time()) !!}
<!-- End: Activities planner -->

<!-- Start: Invoice Modal -->
{!! HTML::style('assets/css/invoice.css?v='.time()) !!} 
<!-- End: Invoice Modal -->
{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
{!! Html::style('result/plugins/dropzone/cropper.css') !!}
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <style type="text/css">
    /*.grid-table{
    height: 542px;
    }*/
    .openWebcamera{
    background: white !important;
    color: #253746 !important;
    font-size: 34px
    }
    *{
    touch-action: manipulation;
    }
    .zoombox{
    position: absolute;
    top: 68px;
    left: 17px;
    }
    .dataTables_length > label {
    margin-top: 0px;
    }
    .img-magnifier-glass {
    /* position: absolute;
    border: 3px solid #000;
    border-radius: 50%;
    cursor: none;
    width: 100px;
    height: 100px; */
    }
    @media(min-width: 768px){
    .openmobcamera{
    display: none !important;
    }
    }
    .picCropModel .preview {
    opacity: 0.7 !important;
    }
    .jcrop-holder{
    background: none !important
    }
    #posture-analysis-modal .m-btn{
    padding: 6px 10px;
    background: #253746;
    color: white;
    border-radius: 3px;
    flex: 1 1 auto!important;
    margin: 0px 5px;
    max-width: 70px;
    }
    #posture-analysis-modal .justify-content-center{
    justify-content: center;
    }
    @media(max-width: 767px){
    #posture-analysis-modal .modal-dialog{
    width: 90%
    }
    .picCropModel .center::-webkit-scrollbar {
    -webkit-appearance: none;
    height: 2px;
    }
    .picCropModel .center::-webkit-scrollbar-thumb {
    border-radius: 2px;
    background-color: rgba(0,0,0,.5);
    -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
    }
    .picCropModel .center{
    overflow-x: scroll;
    padding: 20px 0px;
    }
    .jcrop-holder{
    width: 100% !important;
    min-width: 100% !important;
    }
    .picCropModel .preview{
    width: auto !important;
    height: auto !important;
    max-height: 600px !important;
    max-width: none !important
    }
    .openmobcamera label {
    cursor: pointer;
    /* Style as you please, it will become the visible UI component. */
    }
    .openmobcamera label i{
    color: #253746;
    font-size: 34px;
    }
    .openmobcamera input {
    opacity: 0;
    position: absolute;
    z-index: -1;
    }
    .openWebcamera{
    display: none;
    }
    .zoombox{
    position: relative;
    width: 283px;
    left: 10px;
    height: 95px;
    top: -9px;
    overflow: hidden;
    }
    #app .app-content{
    overflow-x: hidden;
    }
    .picCropModel .modal-dialog{
    width: 94%;
    }
    }
    @media(min-width: 768px){
    #toggleCamera{
    display: none;
    }
    }
    #webcamera{
    margin-left: auto !important;
    margin-right: auto;
    }
    }
    .gridLine{
    text-align: center;
    }
    #posture-analysis-modal .tablenumber td {
    border: 1px solid #8080807a;
    width: 21px;
    height: 15px;
    }
    #posture-analysis-modal .h553 {
    height: 604px;
    max-width: 338px;
    }
    .image1-posture-pre, .image2-posture-pre, .image3-posture-pre, .image4-posture-pre{
    max-width: 100% !important;
    max-height: 538px;
    min-width: 222px;
    }
    .h553{
    height: 553px;
    max-width: 222px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
    }
    #canvasPic {
    background-size: contain;
    background-repeat: no-repeat;
    }
    #imagessize, #rightimagessize , #leftimagessize, #backimagessize{
    float: left;
    }
    .size_zoom_image {
    width: 60px;
    height: 60px;
    border: 3px solid #253746;
    position: relative;
    bottom: 66px;
    }
    .size_zoom_image img{
    width: 100%;
    }
    .tablenumber{
    border-collapse: collapse;
    /*border: 1px solid gray;*/
    /*  border-left: 1px solid gray;
    border-right: 1px solid gray;*/
    border-top: 1px solid #8080807a;
    font-size: 12px;
    }
    .tablenumber:first-child tr:first-child td{
    border-top: 2px solid #8080807a;
    }
    .tablenumber td:nth-child(1){
    border-left: 2px solid #8080807a;
    }
    .tablenumber td:last-child{
    border-right: 2px solid #8080807a;
    }
    .tablenumber:last-child tr:last-child td{
    border-bottom: 2px solid #8080807a;
    }
    .tablenumber td{
    border:1px solid #8080807a;
    width: 14px;
    /*height: 14px;
    line-height: 14px;*/
    }
    tr { border: .4pt }
    .tablenumber td:nth-child(4),table td:nth-child(8),table td:nth-child(12){
    border-right:2px solid #8080807a;
    }
    .tablenumber{
    position: relative;
    z-index: 999;
    }
    #posture .tablenumber{
    height: 77px;
    min-height: 77px;
    table-layout: fixed;
    }
    .posture-d{
    position: absolute;
    width: 100%;
    }
 </style>
<style type="text/css">
    .pac-container{
        z-index: 9999;
    }
</style>
<style>
    #recurClassDeleteModal{
        z-index: 99999 !important;
    }
</style>
@stop

@php
use App\Http\Controllers\Mobile_Detect;
$detect = new Mobile_Detect;
@endphp

@section('page-title')
<span data-realtime="firstName">{{ $clients->firstname }}</span> <span data-realtime="lastName">{{ $clients->lastname }}</span>
@stop

@section('content')
@if (session()->has('message'))
<div class="alert alert-info note-text" id="success_message">
    {{ session('message') }}
</div>
@endif
<div class="alert alert-danger" id="sales_msg" style="display: none;">
    
<a class="close" data-dismiss="alert" href="#"></a>
    Event must be in past.
    </div>
<div id="waitingShield" class="text-center waitingShield" data-slug="">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>
<div id="client-overview">
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
    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    <!--start: Notes Model -->
    @include('includes/partials/notes_model',['notesCat' => $notesCat])
    <!-- End: Notes Model -->

    <!-- start: Recure class delete modal -->
    @include('includes.partials.recur_class_delete_modal', ['futureRecureClassesProRate' => $futureRecureClassesProRate, 'futureRecureClassesNextCycle' => $futureRecureClassesNextCycle])
    <!-- end: Recure class delete modal -->
    
    <!-- start: Edit Field Model -->
    @include('includes.partials.edit_field_modal', ['entity' => "client", 'entityId' => $clients->id])
    <!-- end: Edit Field Model -->

    <!-- start: Appoinment Model -->
    @include('includes.partials.appointment_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
    <!-- end: Appoinment Model -->

    <!-- start: Appoinment Cancel Modal -->
    @include('includes.partials.appointment_cancel_modal', ['reasons' => $serviceCancelReasons])
    <!-- end: Appoinment Cancel Modal -->

    <!-- start: Class Modal -->
    @include('includes.partials.class_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
    <!-- end: Class Modal -->

    <!--Start: create invoice Modal  -->
    @include('includes.partials.invoice_modal', ['alltax'=>$alltax,'paymenttype'=>$paymenttype,'userInfo'=>$userInfo, 'clientDetails' => $clients ? $clients : null])
    <!--Start: create invoice Modal  -->

    @if(isUserEligible(['Admin'], 'manage-client-membership'))
    <!-- Start: Edit Membership Subscription Modal -->
    @include('includes.partials.edit_memb_sub', ['clientId' => $clients->id, 'memberships' => $allMemberShipData, 'clientMembership' => $activeMemb,'paymenttype'=>$paymenttype])
    <!-- End: Edit Membership Subscription Modal -->
    @endif

    @if(hasPermission('edit-parq'))
    <!-- Start: Edit referred by -->
    @include('includes.partials.referredBy_modal', ['parq'=>$parq])
    <!-- End: Edit referred by -->

    <!-- Start: Edit cunsation date expiration duration -->
    @include('includes.partials.expiring_duration_modal')
    <!-- Start: Edit cunsation date expiration duration -->
    @endif

    <div class="row">
        <div class="col-sm-12 subviewPar">
            <div id="subview" class="subview">
                <iframe id="iframe"></iframe>
            </div>
            <div class="tabbable">
<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue epic-mobile-tab" id="myTab4">
               <li class="{{$activeTab == 'overview' ? 'active' : ''}}">
                   <a id="panel_overview_tab" href="{{ route('clients.show', $clients->id)}}">
                      Overview
                  </a>
               </li>
              <li class="{{$activeTab == 'assess_progress' ? 'active' : ''}}">
                <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'assess_progress']) }}">
                    EPIC Process
                </a>
              </li>

            <li class="{{$activeTab == 'benchmarks' ? 'active' : ''}}">
                <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'benchmarks']) }}">
                    Benchmarks 
                </a>
            </li>
            <li class="{{$activeTab == 'movements' ? 'active' : ''}}">
            <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'movements']) }}">
                Movements
            </a>
             </li>      
            <li class="{{$activeTab == 'appointments' ? 'active' : ''}}">
                    <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'appointments']) }}">
                        Appointments
                    </a>
            </li>      
            <li class="{{$activeTab == 'membership' ? 'active' : ''}}">
                <a  href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'membership']) }}">
                    Membership
                </a>
            </li>
            @if(isUserEligible(['Admin'], 'view-client-notes'))
            <li class="{{$activeTab == 'notes' ? 'active' : ''}}">
                <a  href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'notes']) }}">
                    Notes
                </a>
            </li>
            @endif
            <li class="{{$activeTab == 'makeup' ? 'active' : ''}}">
                    <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'makeup']) }}">
                    EPIC Credit
                </a>
            </li>
            
            @if(Session::has('businessId') && Auth::user()->hasPermission(Auth::user(), 'client-activity-tab')) 
            <li class="desktop_url">
            <a href="{{route('clients.createActvityPlan',['id' => $clients->id])}}">
                    Activity Plan
                </a>
            </li>
         
            <li class="mobile_url {{$activeTab == 'activity-plan' ? 'active' : ''}}">
                <a aria-expanded="true" href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'activity-plan']) }}">
                    Activity Plan
                </a>
            </li> 
            @endif
            <li class="{{$activeTab == 'invoice' ? 'active' : ''}}">
                <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'invoice']) }}">
                    Invoices
                </a>
            </li>
            @if(Session::has('businessId') && Auth::user()->hasPermission(Auth::user(), 'client-setting-tab'))  
                <li class="{{$activeTab == 'settings' ? 'active' : ''}}">
                    <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'settings']) }}">
                        Settings
                    </a>
                </li>
                @endif
                {{-- <li class="{{$activeTab == 'galleryTab' ? 'active' : ''}}">
                    <a  href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'galleryTab']) }}">
                        Gallery
                    </a>
                </li>
                <li class="{{$activeTab == 'BeforeAfter' ? 'active' : ''}}">
                    <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'BeforeAfter']) }}">
                        Before After
                    </a>
                </li> --}}
                <li class="{{$activeTab == 'progress' ? 'active' : ''}}">
                    <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'progress']) }}">
                        Gallery
                    </a>
                </li>
                <li class="{{$activeTab == 'posture' ? 'active' : ''}}">
                  <a href="{{ route('clients.show',['id' => $clients->id, 'tab' => 'posture']) }}">
                  Posture Section
                  </a>
               </li>
</ul>
<div class="tab-content">
    <input type='hidden' name='calendarSettingVal' value ='{{ json_encode($calendarSettingVal) }}' >
    <input type="hidden" name="discount-data" class="discount-data-cls" value='<?php echo json_encode($discount); ?>'>
    <input type="hidden" name="client_id" id="clientId" value='{{$clients->id}}'>
    <input type="hidden" name="gender" value='{{$parq->gender}}'>

    @if($activeTab == "overview")
    <div id="panel_overview" class="tab-pane in subviewPar {{$activeTab == 'overview'? 'active' : ''}}">
                            <!--<div id="subview" class="subview">
                                <iframe id="iframe"></iframe>
                            </div>-->
                            <div id="contact_note" class="subview" style="z-index:999">
                                <!--<div class="subview" id="contactSubview">
                                    <iframe></iframe>
                                </div>-->
                                <div class="page-header">
                                    <h3>Contact Note</h3>
                                </div>
                                {!! Form::open(['class' => '']) !!}
                                <div class="row">
                                    <div class="col-sm-5 col-md-4">
                                        <div class="user-left">
                                            <div class="center">
                                                <h4>
                                                    <span data-realtime="firstName">{{ $clients->firstname }}</span> 
                                                    <span data-realtime="lastName">{{ $clients->lastname }}</span>
                                                </h4>
                                                <div >
                                                    <div class="user-image">
                                                        <div class="thumbnail">
                                                            dgdfgjdfk
                                                            <img src="{{ dpSrc($clients->profilepic, $clients->gender) }}" class="img-responsive clientPreviewPics previewPics" id="profile-userpic-img" alt="{{ $clients->firstname }} {{ $clients->lastname }}" data-realtime="gender">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            <input type="hidden" id="currentClientId" value="{{ $clients->id }}" />
                                            <table class="table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3">Client Information</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>First Name</td>
                                                        <td data-realtime="firstName">{{ $clients->firstname }}</td>
                                                        <td><a href="#" class="editFieldModal" data-label="First Name" data-value="{{ $clients->firstname }}" data-required="true" data-realtime="firstName"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Last Name</td>
                                                        <td data-realtime="lastName">{{ $clients->lastname }}</td>
                                                        <td><a href="#" class="editFieldModal" data-label="Last Name" data-value="{{ $clients->lastname }}" data-required="true" data-realtime="lastName"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Status</td>
                                                        <td data-realtime="accStatus">
                                                            @if($clients->account_status == 'Active' || $clients->account_status == 'Contra')
                                                            <?php $class = 'info'; ?>
                                                            @else
                                                            <?php $class = 'warning'; ?>
                                                            @endif
                                                            <span class="label label-{{$class}}">{{ ucfirst($clients->account_status) }}</span>
                                                        </td>
                                                        <td><a href="#" class="editFieldModal" data-label="Status" data-value="{{ $clients->account_status_backend }}" data-required="true" data-realtime="accStatus"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gender</td>
                                                        <td data-realtime="gender">{{ $clients->gender }}</td>
                                                        <td><a href="#" class="editFieldModal" data-label="Gender" data-value="{{ $clients->gender }}" data-required="true" data-realtime="gender"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                                    </tr>
                                                    @if(hasPermission('edit-parq') || hasPermission('view-parq'))
                                                    <tr>
                                                        <td>Goals</td>
                                                        <td data-realtime="goals">{{ $parq->goalHealthWellnessRaw }}</td>
                                                        <td>
                                                            @if(hasPermission('edit-parq'))
                                                            <a href="#" class="editFieldModal" data-label="Goals" data-value="{{ json_encode($parq->goalHealthWellness) }}" data-realtime="goals"><i class="fa fa-pencil edit-user-info"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date of Birth</td>
                                                        <td data-realtime="dob">{{ $overviewDob }}</td>
                                                        <td>
                                                            @if(hasPermission('edit-parq'))
                                                            <a href="#" class="editFieldModal" data-label="Date of Birth" data-value="{{ $parq->dob }}" data-required="true" data-realtime="dob"><i class="fa fa-pencil edit-user-info"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td>Referred by</td>
                                                        <td data-realtime="referralNetwork">
                                                            {!! renderParqReference($parq) !!}
                                                        </td>
                                                        <td>
                                                            @if(hasPermission('edit-parq'))
                                                            <a href="#referredByModal" data-toggle="modal"><i class="fa fa-pencil edit-user-info"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email</td>
                                                        <td><a href="mailto:{!! $clients->email or '' !!}" data-realtime="email">{!! $clients->email or '' !!}</a></td>
                                                        <td><a href="#" class="editFieldModal" data-label="Email" data-value="{{ $clients->email }}" data-required="true" data-realtime="email"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone</td>
                                                        <td><a href="tel:{!! $clients->phonenumber or '' !!}" data-realtime="phone">{!! $clients->phonenumber or '' !!}</a></td>
                                                        <td><a href="#" class="editFieldModal" data-label="Phone" data-value="{{ $clients->phonenumber }}" data-required="true" data-realtime="phone"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-md-8">
                                        <!-- start: Contact sales process -->
                                        @include('includes.partials.contact_sales_process', ['clients' => $clients, 'noteArray' => $allNotes])
                                        <!-- end: Contact sales process -->
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>

                            {!! membExpireNotif($endDateReaching, $activeMemb) !!}
                            {!! membBillNotif($dueDateReaching, $activeMemb) !!}

                            <div class="row">
                            <div class="col-sm-5 col-md-4">
                                <div class="user-left">
                                    <div class="center">
                                    <h4>
                                        <span data-realtime="firstName">{{ $clients->firstname }}</span> 
                                        <span data-realtime="lastName">{{ $clients->lastname }}</span>
                                    </h4>
                                    <div>
                                    <div class="user-image">
                                        <div class="thumbnail">
                                            <a href="{{ dpSrc($clients->profilepic, $clients->gender) }}" data-lightbox="image-1" 
                                                >
                                            <img src="{{ dpSrc($clients->profilepic, $clients->gender) }}" class="img-responsive clientPreviewPics previewPics" id="profile-userpic-img" alt="{{ $clients->firstname }} {{ $clients->lastname }}" data-realtime="gender" style="max-width: 120px !important;"></a>
                                        
                                    </div>
                                    <div class="form-group upload-group">
                                        <input type="hidden" name="prePhotoName" value="{{ $clients->profilepic }}">
                                        <input type="hidden" name="entityId" value="{{$parq->client_id}}">
                                        <input type="hidden" name="saveUrl" value="client/photo/save">
                                        <input type="hidden" name="photoHelper" value="client">
                                        <input type="hidden" name="cropSelector" value="square">
                                        <div>
                                            <label class="btn btn-primary btn-file">
                                                <span>Change Photo</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="user-image-buttons" style="display:none;">
                                        <span class="btn btn-teal btn-file btn-sm"><span class="fileupload-new"><i class="fa fa-pencil"></i></span><span class="fileupload-exists"><i class="fa fa-pencil"></i></span>
                                        <input type="file">
                                    </span>
                                    <a href="#" class="btn fileupload-exists btn-bricky btn-sm" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th colspan="3">Contact Information</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Email:</td>
                        <td><a href="mailto:{{ $clients->email ?? '' }}" data-realtime="email">{{ $clients->email ?? '' }}</a></td>
                        <td><a href="#" class="editFieldModal" data-label="Email" data-value="{{ $clients->email }}" data-required="true" data-realtime="email"><i class="fa fa-pencil edit-user-info"></i></a></td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td><a href="tel:{{ $clients->phonenumber ?? '' }}" data-realtime="phone">{{ $clients->phonenumber ?? '' }}</a></td>
                        <td><a href="#" class="editFieldModal" data-label="Phone" data-value="{{ $clients->phonenumber }}" data-required="true" data-realtime="phone"><i class="fa fa-pencil edit-user-info"></i></a></td>
                    </tr>
                    @if($parq->addressline1)
                    <tr>
                        <td>Address:</td>
                        <td>{{ $parq->addressline1.', '.$parq->addressline2.', '.$parq->city.', '.$parq->stateName.', '.$countries[$parq->country].', '.$parq->postal_code }}</td>
                        <td></td>
                    </tr>
                    @endif
                    </tbody>
            </table>
            <div class="form-group {{ $errors->has('login_with_email') ? 'has-error' : ''}}">
                        <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="login_with_email" id="login_with_email_client" value="1" class="email-login" {{ isset($clients) && $clients->login_with_email?'checked':'' }} data-old-login-with-email="{{ isset($clients) && $clients->login_with_email?1:0 }}">
                        <label for="login_with_email_client">
                            <strong>Allow client to log in with email</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please ensure that if you change your email address that you change your username when logging in"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('login_with_email', '<p class="help-block">:message</p>') !!}
                    </div>
                    </div> 

            <div class=" js-pwdFieldset m-t-0" >
            
            
                <div class="form-group">
                    {!! Form::label('clientNewPwd', 'New Password', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the client members new password"><i class="fa fa-question-circle"></i></span>
                    {!! Form::text('clientNewPwd', isset($pwd)?$pwd:null, ['class' => 'form-control', 'minlength' => 6, 'autocomplete' => 'off']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('clientNewPwdCnfm', 'Confirm Password', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the client members new password confirmation"><i class="fa fa-question-circle"></i></span>
                    <input id="clientNewPwdCnfm" class="form-control customValField" value="{{ isset($pwd)?$pwd:null }}" name="clientNewPwdCnfm" type="password" />  
                    <span class="help-block m-b-0"></span>
                </div>
                <button class="btn btn-primary btn-wide pull-right updatePassword">
                <i class="fa fa-edit"></i> Update Password
                </button>
            </div>
            <table class="table table-condensed table-hover">
            <thead>
                <tr>
                    <th colspan="3">General information</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Risk Factor</td>
                <td>
                    {!! ($clients->risk_factor >= 2)?'<span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This client has high risk factor"><i class="fa fa-warning"></i></span>':'' !!}
                    {{ $clients->risk_factor }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td>EPIC Credit</td>
                <td>
                    ${{$clients->epic_credit_balance}}
                </td>
                <td></td>
            </tr>
            <tr>
            <td>Occupation</td>
            <td data-realtime="occupation">{{ $clients->occupation }}</td>
            <td><a href="#" class="editFieldModal" data-label="Occupation" data-value="{{ $clients->occupation }}" data-realtime="occupation"><i class="fa fa-pencil edit-user-info"></i></a></td>
        </tr>
        <tr>
            <td>Status</td>
            <td data-realtime="accStatus">
            @if($clients->account_status == 'Active' || $clients->account_status == 'Contra')
            <?php $class = 'info'; ?>
            @else
            <?php $class = 'warning'; ?>
            @endif
            <span class="label label-{{$class}}">{{ ucfirst($clients->account_status) }}</span>
        </td>
        <td><a href="#" class="editFieldModal" data-label="Status" data-value="{{ $clients->account_status_backend }}" data-required="true" data-realtime="accStatus"><i class="fa fa-pencil edit-user-info"></i></a></td>
            </tr>
            <tr>
                <td>PAR-Q Status</td>
                <td data-realtime="parqStatus">
                @if($parq->parq5 == 'completed')
                <span class="label label-info">Completed</span>
                @else
                @if($parq->parq4 == 'completed')
                {{ '', $leftSteps = '1 Step Left' }}
                @elseif($parq->parq3 == 'completed')
                {{ '', $leftSteps = '2 Steps Left' }}
                @elseif($parq->parq2 == 'completed')
                {{ '', $leftSteps = '3 Steps Left' }}
                @elseif($parq->parq1 == 'completed')
                {{ '', $leftSteps = '4 Steps Left' }}
                @else
                {{ '', $leftSteps = '5 Steps Left' }}
                @endif
                <span class="label label-warning">{{ $leftSteps }}</span>
                @endif
            </td>
            <td></td>
            </tr>
            <tr>
            <td>Referred By</td>
            <td data-realtime="referralNetwork">
                {!! renderParqReference($parq) !!}
            </td>
            <td>
            @if(hasPermission('edit-parq'))
            <a href="#referredByModal" data-toggle="modal"><i class="fa fa-pencil edit-user-info"></i></a>
            @endif
            </td>
            </tr>
                                                <!--<tr>
                                                    <td>Amount Owing</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Credit Amount</td>
                                                    <td>${{ $clients->credit_total_amount }}</td>
                                                    <td></td>
                                                </tr>-->
                                                @php
                                                $name = \App\Models\Access\User\User::where('id',$parq->waiver_id)->first();
                                                @endphp
                                                <tr>
                                                    <td>Date Created</td>
                                                    <td>{{ setLocalToBusinessTimeZone($clients->created_at, 'dateString') }}</td>
                                                    <td></td>
                                                </tr>
                                                {{-- {{dd($parq)}} --}}
                                                <tr>
                                                    <td>Consultation form(&nbsp;{{$parq->firstName." ".$parq->lastName}} )</td>
                                                    <td> {{ Carbon\Carbon::parse($parq->waiverDate)->format('D, d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Consultation form(&nbsp;{{$name->name." ".$name->last_name}} )</td>
                                                    <td>{{ Carbon\Carbon::parse($parq->trainerwaiverDate)->format('D, d M Y') }}</td>
                                                    <td></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td>Consultation Date</td>
                                                    <td data-realtime="consultation">
                                                        <span class="consultation_date">
                                                            {{ isset($clients->consultation_date)?dbDateToDateString(\Carbon\Carbon::parse($clients->consultation_date)):'' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="editFieldModal consult_date" data-label="Date of consultation" data-value="{{ $clients->consultation_date }}" data-required="true" data-realtime="consultation"><i class="fa fa-pencil edit-user-info"></i></a>
                                                    </td>
                                                </tr> -->
                                                <!-- <tr>
                                                    <td>Consultation Duration</td>
                                                    <td data-realtime="exp-duration">
                                                        {!! $clients->consul_exp_duration or '' !!} {!! $clients->consul_exp_type or '' !!}{{ isset($clients->consul_exp_type)?'(s)':''}}
                                                    </td>
                                                    <td>
                                                        <a href="#expiringDurationModal" data-toggle="modal" class="exp-dur-class" data-type="{{ $clients->consul_exp_type }}" data-duration="{{ $clients->consul_exp_duration }}" data-required="true" data-realtime="exp-duration"><i class="fa fa-pencil edit-user-info"></i></a>
                                                    </td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                        @if(hasPermission('edit-parq') || hasPermission('view-parq'))
                                        <table class="table table-condensed table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="3">Personal information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Date of Birth</td>
                                                <td data-realtime="dob">{{ $overviewDob }}</td>
                                                <td>
                                                    @if(hasPermission('edit-parq'))
                                                    <a href="#" class="editFieldModal" data-label="Date of Birth" data-value="{{ $parq->dob }}" data-required="true" data-realtime="dob"><i class="fa fa-pencil edit-user-info"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Age</td>
                                                <td><span data-realtime="dob_age">{{ $age }}</span>
                                                    <td>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @endif
                                        
                                        <!-- Start: Membership -->
                                        @include('includes.partials.membership_overview', ['selectedMemberShip' => $selectedMemberShip, 'activeMemb' => $activeMemb])
                                        <!-- End: Membership -->
                                        <!-- LDC Class Status -->
                                        <table class="table table-condensed table-hover">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">LDC Class</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Status</td>
                                                    <td data-realtime="ldcStatus">
                                                        @if($clients->ldc_status == \App\Clients::LDC_ACTIVE)
                                                        <?php $class = 'info'; ?>
                                                        @else
                                                        <?php $class = 'warning'; ?>
                                                        @endif
                                                        <span class="label label-{{$class}}">{{ $clients->ldc_status == \App\Clients::LDC_ACTIVE ?"Active":"Inactive" }}</span>
                                                    </td>
                                                    <td>
                                                    <a href="#" class="editFieldModal" data-label="LDC Class" data-value="{{$clients->ldc_status}}"  data-ldc-session="{{$clients->ldc_session_id}}" data-required="true" data-realtime="ldcStatus"><i class="fa fa-pencil edit-user-info"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- //LDC Class Status -->
                                    </div>
                                </div>
                                <div class="col-sm-7 col-md-8">
                                <div class="row">
                                    <div class="col-sm-3">
                                    <button class="btn btn-icon btn-block">
                                        <i class="clip-clip block"></i>
                                        Projects <span class="badge badge-info"> 4 </span>
                                    </button>
                                </div>
                                <div class="col-sm-3">
                                <button class="btn btn-icon btn-block pulsate">
                                    <i class="clip-bubble-2 block"></i>
                                    Messages <span class="badge badge-info"> 23 </span>
                                </button>
                            </div>
                            <div class="col-sm-3">
                            <button class="btn btn-icon btn-block">
                                <i class="clip-calendar block"></i>
                                Calendar <span class="badge badge-info"> 5 </span>
                            </button>
                        </div>
                        <div class="col-sm-3">
                        <button class="btn btn-icon btn-block">
                            <i class="clip-list-3 block"></i>
                            Notifications <span class="badge badge-info"> 9 </span>
                        </button>
                    </div>
                </div>
                
                <!-- start: Sales Process Panel -->
                @include('includes.partials.client_sales_process', ['client' => $clients])
                <!-- end: Sales Process Panel --> 

                <!-- start: Appointments accordian -->
                    <div class="panel panel-white">
                        <!-- start: PANEL HEADING -->
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <span class="icon-group-left">
                                    <i class="fa fa-calendar"></i>
                                </span> 
                                Appointments
                                <span class="icon-group-right">
                                    <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="client-overview">
                                        <i class="fa fa-chevron-down"></i>
                                    </a>
                                </span>
                            </h5>
                        </div>
                        <!-- end: PANEL HEADING -->
                        <!-- start: PANEL BODY -->
                        <div class="panel-body">
                            @include('includes.partials.overview-events', ['latestPastEvent' => $latestPastEvent, 'oldestFutureEvent' => $oldestFutureEvent])
                        </div>
                        <!-- end: PANEL BODY -->
                    </div>
                <!-- end: Appointments accordian -->

                <!-- start: Sales accordian -->
                    <div class="panel panel-white">
                        <!-- start: PANEL HEADING -->
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <span class="icon-group-left">
                                    <i class="fa fa-file-o"></i>
                                </span> 
                                Sales
                                <span class="icon-group-right">
                                    <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="client-overview">
                                        <i class="fa fa-chevron-down"></i>
                                    </a>
                                </span>
                            </h5>
                        </div>
                        <!-- end: PANEL HEADING -->
                        <!-- start: PANEL BODY -->
                        <div class="panel-body">
                            <p>Services</p>
                            <p>Products</p>
                        </div>
                        <!-- end: PANEL BODY -->
                    </div>
                <!-- end: Sales accordian -->

                <!-- start: Notes accordian -->
                    @if(isUserEligible(['Admin'], 'view-client-notes'))
                    @include('includes.partials.sales_proces_notes', ['noteArray' => $noteArray])
                    @endif
                <!-- end: Notes accordian -->
                
                                    <!--<div class="panel panel-white">
                                        <!-- start: PANEL HEADING ->
                                        <div class="panel-heading">
                                            <h5 class="panel-title">
                                                <span class="icon-group-left">
                                                    <i class="clip-menu"></i>
                                                </span>
                                                Recent Activities
                                                <span class="icon-group-right">
                                                    <a class="btn btn-xs pull-right panel-close" href="#">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                    <a class="btn btn-xs panel-refresh pull-right" href="#">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                    <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                                                        <i class="fa fa-wrench"></i>
                                                    </a>
                                                    <a class="btn btn-xs pull-right panel-collapse" href="#">
                                                        <i class="fa fa-chevron-down"></i>
                                                    </a>
                                                </span>
                                            </h5>
                                        </div>
                                        <!-- start: PANEL HEADING ->
                                        <!-- start: PANEL BODY ->
                                        <div class="panel-body panel-scroll" style="height:300px">
                                            <ul class="activities">
                                                <li>
                                                    <a class="activity" href="javascript:void(0)">
                                                        <i class="clip-upload-2 circle-icon circle-green"></i>
                                                        <span class="desc">You uploaded a new release.</span>
                                                        <div class="time">
                                                            <i class="fa fa-time bigger-110"></i>
                                                            2 hours ago
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="activity" href="javascript:void(0)">
                                                        <img alt="image" src="{{ asset('assets/images/avatar-2.jpg') }}">
                                                        <span class="desc">Nicole Bell sent you a message.</span>
                                                        <div class="time">
                                                            <i class="fa fa-time bigger-110"></i>
                                                            3 hours ago
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="activity" href="javascript:void(0)">
                                                        <i class="clip-data circle-icon circle-bricky"></i>
                                                        <span class="desc">DataBase Migration.</span>
                                                        <div class="time">
                                                            <i class="fa fa-time bigger-110"></i>
                                                            5 hours ago
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="activity" href="javascript:void(0)">
                                                        <i class="clip-clock circle-icon circle-teal"></i>
                                                        <span class="desc">You added a new event to the calendar.</span>
                                                        <div class="time">
                                                            <i class="fa fa-time bigger-110"></i>
                                                            8 hours ago
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="activity" href="javascript:void(0)">
                                                        <i class="clip-images-2 circle-icon circle-green"></i>
                                                        <span class="desc">Kenneth Ross uploaded new images.</span>
                                                        <div class="time">
                                                            <i class="fa fa-time bigger-110"></i>
                                                            9 hours ago
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="activity" href="javascript:void(0)">
                                                        <i class="clip-image circle-icon circle-green"></i>
                                                        <span class="desc">Peter Clark uploaded a new image.</span>
                                                        <div class="time">
                                                            <i class="fa fa-time bigger-110"></i>
                                                            12 hours ago
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>-->
                                </div>
                            </div>
    </div>
    @endif

    @if($activeTab == 'assess_progress')               
    <div id="panel_assess_progress" class="tab-pane {{$activeTab == 'assess_progress'? 'active' : ''}}">
      @include('parq.view.assess_progress')
    </div>
    @endif

    @if($activeTab == 'benchmarks')
    <div id="benchmarks" class="tab-pane {{$activeTab == 'benchmarks' ? 'active' :''}}">
            <div class="page-header">
                <h1>Progression
                    @if(hasPermission('create-benchmark'))
                    <button class="btn btn-primary m-t-10 pull-right" id="showBenchmarkForm">Create benchmark</button>
                    @endif
                    <button class="btn btn-default m-t-10 pull-right hidden " id="hideBenchmarkForm"> Cancel</button>

                </h1>
            </div>
        <div>
            <input type ="hidden" value = "{{ $clients->id }}" class = "client-id">
             <div id="createBenchmark" class="hidden">
                @include('benchmark')
            </div>
            <div id="benchmark-list" class="container-fluid container-fullw" style="border-bottom:none !important; padding-bottom:0 !important">
                <div class="row accordion-div">
                    @include('includes.partials.benchmark_list',['clients_details'=>$clients])  
                </div>
            </div>
        </div>
        <!-- start: benchmarke Details Field -->
        <div id="benchmarke-details-field" class="hidden">
          <div id="benchmark-data-area">
            <p><strong>Day:</strong>&nbsp;<span id="npsDay"></span></p>
            <p><strong>Time:</strong>&nbsp;<span id="npsTimeHour"></span>&nbsp;Hour &nbsp;<span id="npsTimeMin"></span>&nbsp;Minutes</p>
            <p><strong>Stress:</strong>&nbsp;<span id="stress"></span> </p>
            <p><strong>Sleep:</strong>&nbsp;<span id="sleep"></span></p>
            <p><strong>Nutrition:</strong>&nbsp;<span id="nutrition"></span> </p>
            <p><strong>Hydration:</strong>&nbsp;<span id="hydration"></span> </p>
            <p><strong>Humidity:</strong>&nbsp;<span id="humidity"></span> </p>
            <p><strong>Temperature:&nbsp;<span id="benchmarkTemperature"></span></strong></p>
            <p><strong>Waist: </strong>&nbsp;<span id="waist"></span>&nbsp;cm</p>
            <p><strong>Hips:</strong>&nbsp;<span id="hips"></span>&nbsp;cm</p>
            <p><strong>Height: </strong>&nbsp;<span id="height"></span>&nbsp;cm </p>
            <p><strong>Weight: </strong>&nbsp;<span id="weight"></span>&nbsp;kg </p>
            <p><strong>Pressups:</strong>&nbsp;<span id="pressups"></span></p>
            <p><strong>Plank:</strong>&nbsp;<span id="plank"></span> </p>
            <p><strong>3km Time Trial Bike:</strong>&nbsp;<span id="timetrial3k"></span></strong> </p>
            <p><strong>Cardio Test BPM1:</strong>&nbsp;<span id="cardiobpm1"></span></p>
            <p><strong>Cardio Test BPM2:</strong>&nbsp;<span id="cardiobpm2"></span></p>
            <p><strong>Cardio Test BPM2:</strong>&nbsp;<span id="cardiobpm3"></span></p>
            <p><strong>Cardio Test BPM4:</strong>&nbsp;<span id="cardiobpm4"></span></p>
            <p><strong>Cardio Test BPM5:</strong>&nbsp;<span id="cardiobpm5"></span></p>
            <p><strong>Cardio Test BPM6:</strong>&nbsp;<span id="cardiobpm6"></span></p>
            <p><strong>Datetime:</strong>&nbsp;<span id="createdAt"></span></p>
         </div>
            </div> 
            <!-- end: benchmarke Details Field -->    
        </div>
    @endif

        @if($activeTab == 'movements')
        <div id="movements" class="tab-pane {{$activeTab == 'movements' ? 'active' :''}}">
                @include('includes.partials.movement_modal',['parq'=>$parq,'moveStep'=>$movementStep])
                @include('includes.partials.movement_step_setup_modal',['movementStep'=>$movementStep])
                <div class="page-header">
                    <h1>Movements
                        <!--<button class="btn btn-primary m-t-10 pull-right" data-toggle="modal" data-target="#movementModal">Create Movement</button> -->
                        <button class="btn btn-primary m-t-10 pull-right" data-toggle="modal" data-target="#movementStepSetupModal">Create Movement</button>
                        <button class="btn btn-default m-t-10 pull-right hidden " id="hideMovementForm"> Cancel</button>
                    </h1>
                </div>
                <div>
                    <input type ="hidden" value = "{{ $clients->id }}" class = "client-id">
                    <div id="movement-list" class="container-fluid container-fullw" style="border-bottom:none !important; padding-bottom:0 !important">
                        <div class="row accordion-div">
                        @include('includes.partials.movement_list',['movementData'=>$movementData])  
                    </div>
                </div>
            </div>
            <!-- end: movement Details Field -->    
        </div>
      @endif

      <!-- start: Events List -->
      @if($activeTab == 'appointments')
     
      @include('includes.partials.events_list', ['pastEvents' => $pastEvents, 'futureEvents' => $futureEvents, 'makeUpCount' => $makeUpCount, 'calendarSettingVal' => $calendarSettingVal])
      <!-- end: Events List -->  
       @endif
      @if($activeTab == 'membership')
      <div id="membershipTab" class="tab-pane {{$activeTab == 'membership' ? 'active' :''}}">
       @include('includes.partials.membership_detail')
      </div> 
      @endif
      <!-- Start: notes tab -->
   @if($activeTab == 'notes')
   @if(isUserEligible(['Admin'], 'view-client-notes'))
   <div id="notes-details" class="tab-pane {{$activeTab == 'notes' ? 'active' :''}}">
    @include('includes.partials.notes_detail',['allnotes' => $allNotes,'notesCat'=>$notesCat,'client_id'=>$clients->id,'measurement_data'=>$measurement_data]) 
   </div> 
   @endif
    @endif
<!-- End: notes tab -->
<!--Start: makeup tab -->
    @if($activeTab == 'makeup')
    <div id="makeup" class="tab-pane {{$activeTab == 'makeup' ? 'active' :''}}">
        @include('includes.partials.makeup_detail',['allmakeup'=>$allMakeup,'allnotes' => $allNotes]) 
    </div> 
    @endif
<!--End: makeup tab -->
<!--Start: makeup tab -->
    @if($activeTab == 'activity-plan')
    <div id="activity-plan" class="tab-pane {{$activeTab == 'activity-plan' ? 'active' :''}}">
        <div class="page-header">
            <h1>GENERATE, CHOOSE OR DESIGN YOUR PERSONALISED PLAN</h1>
        </div>
        @include('includes.partials.add_exercise_modal',['exerciseData'=>$exerciseData])
        @include('includes.partials.activities_planner_tools',['parq'=>$parq]) 
    </div> 
    @endif

<!--Emd: makeup tab -->
<!--Start: Invoices tab -->
    @if($activeTab == 'invoice')
    <div id="invoices" class="tab-pane {{$activeTab == 'invoice' ? 'active' :''}}">
        @include('includes.partials.view_invoice',['allInvoices'=>$allInvoices,'totalAmount'=>$totalAmount,'totalPaid'=>$totalPaid, 'taxdata' => $taxdata, 'discount' => $discount, 'alltax' => $alltax, 'paymenttype' => $paymenttype, 'userInfo' => $userInfo])
    </div> 
    @endif
<!--Emd: invoices tab -->
<!--Start: settings tab -->
    @if($activeTab == 'settings')
    <div id="settings" class="tab-pane {{$activeTab == 'settings' ? 'active' :''}}">
        @include('includes.partials.menues_settings', ['allMenuOptions' => $allMenuOptions, 'selectedMenuOptions' => $selectedMenuOptions, 'clientId' => $clients->id])
    </div> 
    @endif
<!--Emd: settings tab -->
<!--Start: Gallery tab -->
    @if($activeTab == 'galleryTab')
    <div id="galleryTab" class="tab-pane {{$activeTab == 'galleryTab' ? 'active' :''}}">
        <div class="row">
            <div class="col-md-12 addphotolbtn"> 
                <form method="POST" action="{{ url('client/add-gallery-image') }}" id="add-gallery-image-form" enctype="multipart/form-data">
                @csrf
                    <input type="file" name="images[]" multiple="multiple"  id="upload-gallery-images">
                    <input type="hidden" name="images_name" id="images_name">
                    <input type="hidden" name="client_id" value="{{$clients->id}}">
                </form>          
                <label for="upload-gallery-images" class="mb-2">Add Gallery <i class="fa fa-plus"></i></label>
                <span id="valid_file_msg" style="color: red;display: none;"><b>File must be png,jpg,jpeg type</b>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="gallery-lightbox">
                @if($gallery)
                @foreach($gallery as $value)
                <div class="col-md-3 col-sm-4 col-xs-6 gallerybox">
                    <div class="galleyIMG">
                        <img alt="picture" src="{{asset('result/gallery-images')}}/{{$value->image}}" class="img-fluid">
                    </div>
                    <h3>{{$value->image_name}}</h3>
                </div>
                <h3>Title name here</h3>
                @endforeach
                @else
                <div>No record found</div>
                @endif
            </div>
        </div>
    </div> 
    @endif
<!--Emd: Gallery tab -->
<!--Start: Before After tab -->
    @if($activeTab == 'BeforeAfter')
    <div id="BeforeAfter" class="tab-pane {{$activeTab == 'galleryTab' ? 'active' :''}}">
        <div class="beforeafter-section">
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-primary mb-2 add-before-after-btn" href="#" data-toggle="modal" id="add-before-after-btn" data-type="add">Add Before and after
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table width="100%" cellpadding="0" cellspacing="0" class="table-border">
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Before Image</th>
                    <th>After Image</th>
                    <th>Action</th>
                </tr>
                @if($before_after)
                @foreach($before_after as $value)
                <tr>
                    <td>
                        {{date('Y-m-d',strtotime($value->created_at))}}
                    </td>
                    <td>
                        {{$value->title}}
                    </td>
                    <td>
                        @if($value->before_image)
                        <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->before_image}}" class="img-fluid" style="height: 130px" />
                        @endif
                    </td>
                    <td>
                        @if($value->after_image)
                        <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->after_image}}" class="img-fluid" style="height: 130px" />
                        @endif
                    </td>
                    <td>
                        @if($value->before_image || $value->after_image)
                        <a class="actiontext view-after-before" href="#viewAfterBefore" data-toggle="modal" data-id="{{$value->id}}">View</a>
                        @endif
                        <a class="actiontext  add-before-after-btn" href="#editAfterBefore" data-toggle="modal"  data-id="{{$value->id}}" data-type="edit" data-item="{{$value->title}}">Edit</a>
                        <a class="actiontext delete-before-after-btn" href="#deleteAfterBefore" data-toggle="modal" data-id="{{$value->id}}">Delete</a>
                    </td>
                </tr>
                <!--   view before after Model start -->
                <div class="modal fade" id="view-after-before-modal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content gallerymodel">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h4 class="modal-title">Preview photo</h4>
                            </div>
                            <div class="modal-body">
                                <div class="preview-photo">
                                    <h3>{{$value->title}}</h3>
                                    <div class="photolist">
                                        <div class="photo-b-a">
                                            @if($value->before_image)
                                            <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->before_image}}" class="img-fluid" />
                                            @endif
                                        </div>
                                        <div class="photo-b-a">
                                         @if($value->after_image)
                                         <img alt="picture" src="{{asset('result/before-after-images')}}/{{$value->after_image}}" class="img-fluid" />
                                         @endif
                                     </div>
                                 </div>
                             </div>
                             <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- view before after Model end  -->
                @endforeach
                @else
                <span>No record found</span>
                @endif
            </table>
        </div>
    </div>
    @endif 
<!--Emd: Before After tab -->
@if($activeTab == 'progress')
<div id="GalleryBeforeAfter" class="tab-pane {{$activeTab == 'progress' ? 'active' :''}}">
    <div class="row">
        <div class="col-md-12 addphotolbtn">       
             <a href="{{url('client/add-progress')}}/{{$clients->id}}"><label  class="mb-2">Add Gallery <i class="fa fa-plus"></i></label></a>
            <span id="valid_file_msg" style="color: red;display: none;"><b>File must be png,jpg,jpeg type</b>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <ul class="gallerylist">
                @if($progress)
                @foreach($progress as $value)
                @php
                    if($value->only_admin_manage == 'yes' && Auth::User()->account_type != 'Admin' && Auth::User()->account_type != 'Super Admin')
                    {
                        $show = 'show_button';
                    }
                    else
                    {
                        $show = '';
                    }
                @endphp
                <li>
                    <div class="galleyIMG">
                         <div class="col-md-12 padding-0">
                        <h3 class="float-left">{{ucfirst($value->title)}}</h3>
                    <span class="float-right edit-button {{$show}}"><a href="{{url('client/gallery')}}/{{$value->gallery_id}}"><i class="fa fa-edit"></i></a></span>
                    </div>
                       
                       <!--  <img alt="picture" src="{{asset('result/final-progress-photos')}}/{{$value->image}}" class="img-fluid show-gallery-img" data-id="{{$value->id}}" data-item="{{$value->image}}"> -->
                       <div class="show-gallery-img" data-id="{{$value->id}}" data-item="{{$value->image}}" style="background-image: url({{asset('result/final-progress-photos')}}/{{$value->image}});">
                           
                       </div>
                    </div>
                       
                    <div class="date">{{date('d-m-Y',strtotime($value->date))}}</div>
                    <div class="pose">{{ucfirst($value->pose_type)}}</div>
                   
                    <h3>{{ucfirst($value->image_type)}}</h3>
                    <!--   view Model start -->
                    <div class="modal fade" id="view-gallery-image{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content gallerymodel">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                    <h4 class="modal-title">{{ucfirst($value->title)}}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="preview-photo">
                                        <h3>{{ucfirst($value->pose_type)}} Pose</h3>
                                        <div class="photolist">
                                            <div class="photo-b-a">
                                                <img alt="picture" src="{{asset('result/final-progress-photos')}}/{{$value->image}}" class="img-fluid "/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-default delete-gallery-img {{$show}}" data-dismiss="modal" data-id="{{$value->id}}" data-item="{{$value->image}}">Delete</button>
                                <a href="{{ route('image.download',['id'=>$value->id,'name'=>$clients->firstname.$clients->lastname]) }}"  class="btn btn-default" data-id="{{$value->id}}">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- view modal end  -->
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
@endif
<div id="posture" class="tab-pane {{$activeTab == 'posture' ? 'active' :''}}">
  <div class="posture-list row">
     <div class="col-xs-12" style="margin-bottom: 10px;">
        {{-- @if(!empty($postures[0]['unit'])) --}}
        @php
            if($clients->unit == 'Imperial'){
                $imperial_btn_color = 'btn-primary';
                $imperial_disable = 'disabled';
                $metric_btn_color = 'btn-default';
                $metric_disable = '';
            }elseif($clients->unit == 'Metric'){
                $imperial_btn_color = 'btn-default';
                $imperial_disable = '';
                $metric_btn_color = 'btn-primary';
                $metric_disable = 'disabled';
            }else{
                $imperial_btn_color = 'btn-primary';
                $imperial_disable = 'disabled';
                $metric_btn_color = 'btn-default';
                $metric_disable = '';
            }
        @endphp
        <a class="btn {{ $imperial_btn_color }} float-left" data-client-id="{{ $clients->id }}" id="convertM" href="javascript:void(0)" {{ $imperial_disable }}>Show Imperial</a>
        <a class="btn {{ $metric_btn_color }} float-left" data-client-id="{{ $clients->id }}" id="convertI" href="javascript:void(0)" {{ $metric_disable }}>Show Metric</a>
        {{-- @endif --}}
        <a class="btn btn-primary new-posture float-right" data-posture-mode="new" href="javascript:void(0)">Add New Posture</a>
     </div>
     <div class="col-xs-12">
        <table class="table table-striped table-bordered table-hover m-t-10" id="data-table">
           <thead>
              <tr>
                 <th>S.No.</th>
                 <th>Name</th>
                 <th>Date</th>
                 <th class="center">Actions</th>
              </tr>
           </thead>
           <tbody>
              @foreach ($postures as $key => $posture)
              @php
              if($posture['added_from'] == 0){
              $color = 'background-color: #f94211';
              $text_color = 'color: white !important;';
              }else{
              $color = '';
              $text_color = '';
              }
              @endphp 
              <tr style="{{ $color }}">
                 <td style="{{ $text_color }}"> {{ $key + 1 }}  </td>
                 <td style="{{ $text_color }}">{{ $clients->firstname }} {{ $clients->lastname }}</td>
                 <td style="{{ $text_color }}"> {{ date('Y-m-d',strtotime($posture['updated_at'])) }} </td>
                 <td class="center" style="{{ $text_color }}">
                    <div>
                       <a class="btn btn-xs btn-default tooltips posture-preview" href="javascript:void(0)" data-placement="top" data-original-title="Posture Preview" data-posture-id="{{ $posture['id'] }}" data-client-id="{{ $posture['client_id'] }}">
                       <i class="fa fa-eye" style="color:#253746;"></i>
                       </a>
                       <a class="btn btn-xs btn-default tooltips posture-update" href="javascript:void(0)" data-placement="top" data-original-title="Edit Posture" data-posture-id="{{ $posture['id'] }}" data-client-id="{{ $posture['client_id'] }}" data-posture-mode="edit">
                       <i class="fa fa-pencil" style="color:#253746;"></i>
                       </a>
                       <a class="btn btn-xs btn-default tooltips " href="{{ url('generate/pdf/'.$posture['id']) }}" data-placement="top" data-original-title="Generate PDF">
                       <i class="fa fa-download" style="color:#253746;"></i>
                       </a>
                       <a class="btn btn-xs btn-default tooltips mailReport" href="javascript:void(0)" data-posture-id="{{ $posture['id'] }}" data-original-title="Send Mail" data-client-id="{{ $posture['client_id'] }}">
                       <i class="fa fa-envelope" style="color:#253746;" ></i>
                       </a>
                       <a class="btn btn-xs btn-default tooltips deleteReport" href="javascript:void(0)" data-posture-id="{{ $posture['id'] }}"  data-original-title="Delete Posture" data-client-id="{{ $posture['client_id'] }}">
                       <i class="fa fa-trash" style="color:#253746;"></i>
                       </a>
                    </div>
                 </td>
              </tr>
              @endforeach
           </tbody>
        </table>
     </div>
  </div>
  <div class="create-posture hidden">
     @include('posture.create-posture')
  </div>
  <div class="edit-posture hidden">
     {{-- @include('posture.edit-posture') --}}
  </div>
  <div class="view-posture hidden">
     {{-- @include('posture.view-posture') --}}
  
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Start: add_more_modal blade for category management -->
@include('includes.partials.add_more_modal') 
<!-- Start: add_more_modal blade for category management --> 

<!-- Modal -->
<div id="myModal22" class="modal fade modal-add-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">

        <div class="row">
         <div class="col-md-6 col-md-offset-3">
           <p><i class="fa fa-exclamation-circle"></i>  There is already a photo for the Back pose on 20 oct 2020. Are you sure you wish to replace this one?</p>
           
       </div>

   </div>
</div>
<div class="modal-footer">
   <button type="button" class="btn btn-primary" data-dismiss="modal">Yes</button>
   <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
</div>
</div>

</div>
</div>
<!--   Add Gallery Model start -->
<div class="modal fade" id="gallery-images-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content gallerymodel">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Upload image</h4>
            </div>
            <div class="modal-body">
                <div class="uploadlist">
                    <ul id="preview-gallery-images">
                        <li>
                            <div class="row">

                            </div>
                        </li>
                    </ul>  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="upload-images-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!--   Add Gallery Model end -->

<!--   Add before after Model start -->
<div class="modal fade" id="add-before-after-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ url('client/add-before-after') }}" id="add-before-after-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="client_id" value="{{$clients->id}}">
            <div class="modal-content gallerymodel">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Add before and after photo</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required  data-parsley-trigger="focusout" data-parsley-required-message="Title is required">
                        <input type="hidden" name="before_after_id" id="before-after-id" value="">
                    </div>
                    <div class="form-group">
                        <label>Choose before photo</label>
                        <div class="addphotolbtn">
                            <input type="file" class="before" name="before_image" id="before-image-upload" data-item="before" data-type="upload">
                            <label for="before-image-upload" class="mb-2">Upload Photo <i class="fa fa-plus"></i></label>

                            <!-- <input type="hidden" class="before" name="before_image_capture" id="before-image-capture"> -->
                            <input type="file" class="before" accept="image/*" capture name="before_image_capture" id="before-image-capture" data-item="before" data-type="capture">
                            <!-- <label for="beforeTakeimg" class="mb-2 openWebcam" data-item='before'>Take Photo <i class="fa fa-plus"></i></label> -->
                            @if ( $detect->isMobile() || $detect->isTablet() ) 
                            <label for="before-image-capture" class="mb-2" data-item='before'>Take Photo <i class="fa fa-plus"></i></label>
                            @endif
                            <span id="before_success_message"></span>
                        </div>
                        <span id="before-msg"></span>
                    </div>
                    <div class="form-group">
                        <label>Choose after photo</label>
                        <div class="addphotolbtn">
                            <input type="file" class="after" name="after_image" id="after-image-upload" data-item="after" data-type="upload">
                            <label for="after-image-upload" class="mb-2">Upload Photo <i class="fa fa-plus"></i></label>

                            <!--  <input type="hidden" class="after" name="after_image_capture" id="after-image-capture"> -->
                            <input type="file" class="after" accept="image/*" capture name="after_image_capture" id="after-image-capture" data-item="after" data-type="capture">
                            <!-- <label for="afterTakeimg" class="mb-2 openWebcam" data-item="after">Take Photo <i class="fa fa-plus"></i></label> -->
                            @if ( $detect->isMobile() || $detect->isTablet() ) 
                            <label for="after-image-capture" class="mb-2" data-item="after">Take Photo <i class="fa fa-plus"></i></label>
                            @endif
                            <span id="after_success_message"></span>
                        </div>
                        <span id="after-msg"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="close-before-after">Close</button>
                    <button type="button" class="btn btn-primary before-after-save-btn" id="add-before-after-save">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--   Add before after Model end -->

<!-- Modal for webcam -->
<div class="modal fade" id="webcam-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Click a Picture</h4>
            </div>
            <div class="modal-body">
                <div id="camera" class="camera_section"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-webcam">Cancel</button>
                <button type="button" class="btn btn-info snap">Take picture</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal for webcam -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
     <div class="modal-content">
        <div class="modal-header">
           <h5 class="modal-title" id="cropperModalLabel">Cropper</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
           </button>
        </div>
        <div class="modal-body" style="height: 100%">
           <input type="hidden" name="photoName">
           <div class="img-container">
              <img id="imageCrop" src="" alt="Picture" style="width:100%;height:auto">
           </div>
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-success cropImg">Crop</button>
           <button type="button" class="btn btn-secondary saveImg" data-dismiss="modal">Close</button>
        </div>
     </div>
  </div>
</div>
</div>
<div class="modal fade" id="webcamera-modal">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close close-webcam" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Click a Picture</h4>
     </div>
     <div class="modal-body">
        <div id="webcamera" class="center_line" style="margin-left:112px;"></div>
        <input type="range" id="zoom-slider" min="0", max="0">
        <output id="zoom-slider-value"></output>
     </div>
     <div class="modal-footer">
        <button type="button" class="btn btn-info" id="toggleCamera" data-toggle="environment">Change to front</button>
        <button type="button" class="btn btn-default close-webcam">Cancel</button>
        <button type="button" class="btn btn-info takesnap">Take picture</button>
     </div>
  </div>
</div>
</div>
<!-- Modal for posture analysis -->
<div class="modal" id="posture-analysis-modal" data-view="" data-weight="{{$parq->weight}}" data-weight-unit="{{$parq->weightUnit}}" data-height-unit="{{$parq->heightUnit}}" data-height="{{$parq->height}}" data-gender="{{$parq->gender}}" data-posture-image="">
<div class="modal-dialog">
  <div class="modal-content">
     <div class="modal-header">
        <div class="row">
           <div class="col-md-12 col-xs-12 text-center d-flex justify-content-center"> 
              <a class="m-btn" href="javascript:void(0)" id="back-analysis">Back</a>
              <a class="m-btn" href="javascript:void(0)" id="reset-analysis">Reset</a>
              <a class="m-btn" href="javascript:void(0)" id="undo-analysis">Undo</a>
              <a class="m-btn" href="javascript:void(0)" id="next-analysis">Next</a>
           </div>
        </div>
     </div>
     <div class="modal-body row">
        <div></div>
        <div class="col-md-12 col-sm-12 posture-div">
           <div class="alert alert-warning" id="posMsg" data-alert-type="rightEye">Place a marker on a right eye</div>
           <div class="zoombox">
              <img id="zoomImage" width="200%">
           </div>
           <div class="h553">
              <div class="posture-d gridLine">
                 <table class="tablenumber row7">
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                 </table>
                 <table class="tablenumber row6">
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                 </table>
                 <table class="tablenumber row5">
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                 </table>
                 <table class="tablenumber row4">
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                 </table>
                 <table class="tablenumber row3">
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                 </table>
                 <table class="tablenumber row2">
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                 </table>
                 <table class="tablenumber row1">
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                    </tr>
                 </table>
              </div>
              <div class="" style="height: auto;">
                 <canvas id="canvasPic" class="" style="cursor:crosshair;"></canvas>
                 {{-- 
                 <canvas id="Canvas" width="700" height="700"></canvas>
                 --}}
              </div>
              <div class="size_zoom_image">
                 <img src="{{asset('assets/images/right-eye.png')}}">
              </div>
              <div>
                 <canvas id="canvasDup" style="display: none" class="" style="cursor:crosshair;"></canvas>
              </div>
           </div>
        </div>
     </div>
     <div class="modal-footer">
        {{-- <button type="button" class="btn btn-default hide" id="calAnalysis">Calculate</button> --}}
        <button type="button" class="btn btn-default" id="hide-analysis">Cancel</button>
        {{-- <button type="button" class="btn btn-info">Take picture</button> --}}
     </div>
  </div>
</div>
</div>
<!-- end Modal for posture analysis -->
{{-- measurement model --}}
<div class="modal fade" id="measurement-model" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
     <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Please Enter Your Weight <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
           </button>
        </h5>
     </div>
     <div class="modal-body">
        <div class="col-xs-12 m-b-20">
           <div class="col-sm-9">
              <div class="input-group">
                 <input type="text" class="form-control" id="weight_save" name="weight" value="">
                 <span class="input-group-addon kg">Kg</span>
                 <span class="input-group-addon pound hidden">Pounds</span>
              </div>
           </div>
           <div class="col-sm-3">
              <button type="button" class="btn btn-primary btn-sm pull-right" id="convertPound" style="margin-top: 2px;width: 100%">Show Imperial</button>
              <button type="button" class="btn btn-primary btn-sm pull-right hidden " id="convertKg" style="margin-top: 2px;width: 100%">Show Metric</button>
              <input type="hidden" name="weightPosture" value="Metric">
              <span class="help-block m-b-0"></span>
           </div>
        </div>
        <div class="col-xs-12">
           <div class="col-sm-9">
              <div class="input-group">
                 <input type="number" name="height" value="" id="height_m" class="form-control">
                 <span class="input-group-addon cm_show">(cm)</span>
                 <span class="input-group-addon inches_show hidden">(inches)</span></label>
              </div>
           </div>
           <div class="col-sm-3">
              <button type="button" class="btn btn-primary btn-sm pull-right" id="convert-inches" style="margin-top: 2px;width: 100%">Show in inches</button>
              <button type="button" class="btn btn-primary btn-sm pull-right hidden " id="convert-cm" style="margin-top: 2px;width: 100%">Show in cm</button>
              <input type="hidden" name="heightUnit" value="cm">
              <span class="help-block m-b-0"></span>
           </div>
        </div>
     </div>
     <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary saveHeightWeight">Save</button>
     </div>
  </div>
</div>
</div>
@endsection
@section('script')
<script src="https://cdn.rawgit.com/magicien/undo-canvas/v0.1.3/undo-canvas.js"></script>
{!! Html::script('assets/js/form-wizard-clients.js?v='.time()) !!}
{!! Html::script('assets/js/form-wizard-benchmark.js?v='.time()) !!}
{!! Html::script('assets/js/form-wizard-movement.js?v='.time()) !!}
{!! Html::script('assets/js/form-wizard-goal-buddy.js?v='.time()) !!}
{!! Html::script('assets/js/benchmark.js?v='.time()) !!}
<!-- {!! Html::script('assets/js/benchmark-helper.js') !!} -->

<!-- start: Summernote -->
{!! Html::script('assets/plugins/summernote/dist/summernote.min.js') !!}
<!-- end: Summernote -->

<!-- start: Rating -->
{!! Html::script('assets/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
<!-- end: Rating -->

<!-- start: Bootstrap Typeahead -->
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}  
<!-- end: Bootstrap Typeahead --> 

<!-- start: Bootstrap timepicker
{!! Html::script('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}
end: Bootstrap timepicker -->

<!-- Start:  NEW datetimepicker js -->
{!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}
<!-- End: NEW datetimepicker js -->

<!-- Start:  NEW timepicker js -->
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
<!-- End: NEW timepicker js --> 

<!-- start: Bootstrap calendar -->
{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
<!-- end: Bootstrap calendar -->

{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}

<!-- start: Dirty Form -->
{!! Html::script('assets/js/dirty-form.js?v='.time()) !!}
<!-- end: Dirty Form -->

{!! Html::script('assets/js/helper.js?v='.time()) !!}

<!-- start: Events -->
<script>    
    var loggedInUser = {
        //type: '{{ Session::get('userType') }}',
        type: '{{ Auth::user()->account_type }}',
        id: {{ Auth::user()->account_id }},
        userId: {{ Auth::id() }},
        name: '{{ Auth::user()->fullName }}'
    },
    popoverContainer = $('#client-overview');
</script>
@if(request()->get('tab') != 'assess_progress')
{!! Html::script('assets/js/calendar.js?v='.time()) !!}

@endif

{!! Html::script('assets/js/events.js?v='.time()) !!}
{!! Html::script('assets/js/events-list.js?v='.time()) !!}
<!-- end: Events -->
{!! Html::script('assets/plugins/web-rtc/record-rtc.js') !!}
<!-- start: goal buddy -->
{!! Html::script('assets/js/goal-buddy.js?v='.time()) !!}
{!! Html::script('assets/js/goal-buddy-calendar.js?v='.time()) !!} 
<!-- end: goal buddy -->
{!! Html::script('assets/plugins/web-rtc/record-rtc.js') !!}
<script src="https://www.webrtc-experiment.com/DetectRTC.js"> </script>
{!! Html::script('assets/plugins/web-rtc/common.js') !!}
<!-- start: Details update realtime -->
{!! Html::script('assets/js/edit-field-realtime.js?v='.time()) !!}
<!-- end: Details update realtime -->

<!-- start: Client-Membership Modal -->
{!! Html::script('assets/js/client-membership.js?v='.time()) !!}
<!-- end: Client-Membership Modal -->

<!-- start: Clients -->
{!! Html::script('assets/js/clients.js?v='.time()) !!}
<!-- end: Clients -->

<!-- start: goal buddy -->
{!! Html::script('assets/js/goal-buddy.js?v='.time()) !!}
{!! Html::script('assets/js/goal-buddy-calendar.js?v='.time()) !!} 
<!-- end: goal buddy -->
{!! Html::script('assets/plugins/web-rtc/record-rtc.js') !!}
<script src="https://www.webrtc-experiment.com/DetectRTC.js"> </script>
{!! Html::script('assets/plugins/web-rtc/common.js') !!}
<!-- Start: Movement -->
{!! Html::script('assets/js/movement.js?v='.time()) !!}
<!-- End: Movement -->

<!-- start: Invoice filters -->  
{!! Html::script('assets/js/invoice.js?v='.time()) !!}
<!-- start: Invoice filters -->

<!-- Start: Activity Planner -->
{!! Html::script('assets/js/fitness-planner/api.js?v='.time()) !!} 
{!! Html::script('assets/js/fitness-planner/bodymapper.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/jquery.json-2.4.min.js') !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.placeholder.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.ui.touch-punch.min.js') !!}
{!! Html::script('assets/plugins/fitness-planner/custom/jwplayer/jwplayer.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/js/jquery.ui.labeledslider.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/popup.js?v=1') !!}
{!! Html::script('assets/js/fitness-planner/fitness-planner.js?v='.time()) !!}

{!! Html::script('js/sleep-questionnaire.js?v='.time()) !!}
{!! Html::script('assets/js/posture.js?v='.time()) !!}
{!! Html::script('result/plugins/dropzone/cropper.js') !!}
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
<!-- ENd: Activity Planner -->
<script>
    
    jQuery(document).ready(function() {
        $('.waitingShield').hide();
        
        $( ".panel-collapse.closed" ).trigger( "click" );
        $('#data-table').DataTable({searching: false});    });
</script>

<script type="text/javascript">
    $(document).on('click','.show-gallery-img',function(){
    var id = $(this).attr('data-id');
    $('#view-gallery-image'+id).modal();
    });

    $(document).on('click','.delete-gallery-img',function(){
    var id = $(this).attr('data-id');
    var image = $(this).attr('data-item');
    $.ajax({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    },
    method:"POST",
    url:"{{url('client/delete-gallery-image')}}",
    data : 
    {
        id : id,
        image : image
    },
    success: function(data) {
        if(data.status == true)
        {
            location.reload();
        }
    }
    });

});
</script>
<script>
    var placeSearch, autocomplete;
    var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      sublocality_level_1: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'short_name',
      country: 'short_name',
      postal_code: 'short_name'
    };
    function initAutocomplete() {
      autocomplete = new google.maps.places.Autocomplete(
          document.getElementById('autocomplete'), {types: ['geocode']});
      console.log(autocomplete);
      autocomplete.setFields(['address_component']);
      autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
      var place = autocomplete.getPlace();
      console.log(place);
      $('input[name="addressline1"]').val('');
      $('input[name="addressline2"]').val('');
      $('input[name="city"]').val('');
      $('input[name="postal_code"]').val(val);
      $('#country').selectpicker('refresh');
      $('select#addrState').selectpicker('refresh');
      var streetNumber = route = sublocality_level_1 = city = stateCode = countryCode = postalCode = '';
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        var val = place.address_components[i][componentForm[addressType]];
        if (addressType == 'street_number') {
            streetNumber = val;
        }else if(addressType == 'route'){
            route = val;
        }else if(addressType == 'sublocality_level_1'){
            sublocality_level_1 = val;
        }else if(addressType == 'locality'){
            city = val;
        }else if(addressType == 'administrative_area_level_1'){
            stateCode = val;
        }else if(addressType == 'country'){
            countryCode = val;
        }else if(addressType == 'postal_code'){
            postalCode = val;
        }
      }
        $('input[name="addressline1"]').val(streetNumber+' '+route);
        $('input[name="addressline2"]').val(sublocality_level_1);
        $('input[name="city"]').val(city);
        $('#country option').each(function(){
            if($(this).val() == countryCode){
                $(this).attr('selected','selected');
                $('#country').trigger('change');
                var country_code = countryCode,
                    selectedStates = $('select#addrState');
                    
                if(country_code == "" || country_code == "undefined" || country_code == null){
                    selectedStates.html('<option value="">-- Select --</option>');
                    selectedStates.selectpicker('refresh');
                }
                else{       
                    $.ajax({
                        url: public_url+'countries/'+country_code,
                        method: "get",
                        data: {},
                        success: function(data) {
                            var defaultState = stateCode,
                                formGroup = selectedStates.closest('.form-group');

                            selectedStates.html("");
                            $.each(data, function(val, text){
                                var option = '<option value="' + val + '"';
                                if(defaultState != '' && defaultState != null && val == defaultState)
                                    option += ' selected';
                                option += '>' + text + '</option>';
                                selectedStates.append(option);
                            });

                            $('#country').selectpicker('refresh');
                            selectedStates.selectpicker('refresh');
                            setFieldValid(formGroup, formGroup.find('span.help-block'))
                        }
                    });
                }
            }
        });
        $('input[name="postal_code"]').val(postalCode);
    }
    function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          var circle = new google.maps.Circle(
              {center: geolocation, radius: position.coords.accuracy});
          autocomplete.setBounds(circle.getBounds());
        });
      }
    }

    $(document).on('click','.edit-mode', function(){
        $(".review-mode").hide();
        $(".editable-mode").show();
    })

    $(document).on('click','.remove-edit-mode', function(){
        $(".editable-mode").hide();
        $(".review-mode").show();
    })

    $(document).on('click','.edit-mode1', function(){
        $(".review-mode1").hide();
        $(".editable-mode1").show();
    })

    $(document).on('click','.remove-edit-mode1', function(){
        $(".editable-mode1").hide();
        $(".review-mode1").show();
    })

    $(document).on('click','.edit-mode2', function(){
        $(".review-mode2").hide();
        $(".editable-mode2").show();
    })

    $(document).on('click','.remove-edit-mode2', function(){
        $(".editable-mode2").hide();
        $(".review-mode2").show();
    })
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg&libraries=places&callback=initAutocomplete"
    async defer></script>
    @stop

    @section('script-handler-for-this-page')
    FormWizard.init();
    FormWizardBenchMark.init('#benchmarkWizard');
    FormWizardGoalBuddy.init('#goalBuddyWizard');
    @stop
