@extends('Result.masters.app')

@section('page-title')
<span data-realtime="firstName">{{ Auth::user()->name }}</span> <span data-realtime="lastName">{{ Auth::user()->last_name }}</span>
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}
{!! Html::style('result/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
{!! Html::style('result/css/custom.css') !!}
{!! Html::style('result/plugins/dropzone/cropper.css') !!}

<!-- Start: NEW timepicker css -->
{!! Html::style('result/css/bootstrap-timepicker.min.css') !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')
{{-- <!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model --> --}}
<!-- start: Edit Field Model -->
@include('Result.partials.edit_field_modal', ['entity' => "client", 'entityId' => $client->id])
<!-- end: Edit Field Model -->
<!-- start: Appoinment Model -->
@include('Result.partials.appointment_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
<!-- end: Appoinment Model -->

<!-- start: Appoinment Cancel Modal -->
@include('Result.partials.appointment_cancel_modal')
<!-- end: Appoinment Cancel Modal -->

<!-- start: Class Modal -->
{{--@include('Result.partials.class_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])--}}
<!-- end: Class Modal -->

<!-- start: Edit Field Model -->

{{-- @include('Result.partials.class_modal_appointment', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt]) --}}
@include('Result.partials.class_modal_client', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
<!-- end: Edit Field Model -->



<div class="row">
    <div class="col-sm-12">
        <div class="tabbable">
            <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue epic-mobile-tab" id="myTab4">
                <li class="active">
                    <a data-toggle="tab" href="#panel_overview">
                        Overview
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#panel_edit_account">
                        Edit Account 
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#appointments" id="appointmenttab">
                        Appointments
                    </a>
                </li>    
                <li>
                    <a data-toggle="tab" href="#memberships" id="membershipTab">
                        Membership
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#makeup-list" id="makeuplisttab">
                        Epic Cash
                    </a>
                </li>    

            </ul>
            <div class="tab-content">
                <div id="panel_overview" class="tab-pane in active" style="position:relative">
                    <div id="contact_note" class="subview" style="display: none;">
                        <div id="subview" class="subview">
                            <iframe id="iframe"></iframe>
                        </div>
                        <div class="page-header">
                            <h3>Contact Note</h3>
                        </div>
                        {!! Form::open(['class' => '']) !!}
                        <div class="row">
                            <div class="col-sm-5 col-md-4">
                                <div class="user-left">
                                    <div class="center">
                                        <h4>
                                            <span data-realtime="firstName">{{ $client->firstname }}</span> 
                                            <span data-realtime="lastName">{{ $client->lastname }}</span>
                                        </h4>
                                        <div >
                                            <div class="user-image">
                                                <div class="thumbnail">
                                                    <img src="{{ dpSrc($client->profilepic, $client->gender) }}" class="img-responsive clientPreviewPics previewPics" id="profile-userpic-img" alt="{{ $client->firstname }} {{ $client->lastname }}" data-realtime="gender">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <input type="hidden" id="currentClientId" value="{{ $client->id }}" />
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th colspan="3">Client Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>First Name</td>
                                                <td data-realtime="firstName">{{ $client->firstname }}</td>
                                                <td><a href="#" class="editFieldModal" data-label="First Name" data-value="{{ $client->firstname }}" data-type="firstName" data-required="true" data-realtime="firstName"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>Last Name</td>
                                                <td data-realtime="lastName">{{ $client->lastname }}</td>
                                                <td><a href="#" class="editFieldModal" data-label="Last Name" data-value="{{ $client->lastname }}" data-type="lastName" data-required="true" data-realtime="lastName"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td data-realtime="accStatus">
                                                    @if($client->account_status == 'active')
                                                    <span class="label label-info">Active</span>
                                                    @else
                                                    <span class="label label-warning">{{ ucfirst($client->account_status) }}</span>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                                <td>Gender</td>
                                                <td data-realtime="gender">{{ $client->gender }}</td>
                                                <td><a href="#" class="editFieldModal" data-label="Gender" data-value="{{ $client->gender }}" data-type="gender" data-required="true" data-realtime="gender"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>Goals</td>
                                                <td data-realtime="goals">{{ $client->goalHealthWellnessRaw }}</td>
                                                <td><a href="#" class="editFieldModal" data-label="Goals" data-value="{{ json_encode($client->goalHealthWellness) }}" data-type="goals" data-realtime="goals"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>Date of Birth</td>
                                                <td data-realtime="dob">{{ $overviewDob }}</td>
                                                <td><a href="#" class="editFieldModal" data-label="Date of Birth" data-value="{{ $overviewDob }}" data-type="dob" data-required="true" data-realtime="dob"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>Referred by</td>
                                                <td data-realtime="referralNetwork">
                                                    {!! renderParqReference($client) !!}


                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td><a href="mailto:{!! $client->email or '' !!}" data-realtime="email">{!! $client->email or '' !!}</a></td>
                                                <td><a href="#" class="editFieldModal" data-label="Email" data-value="{{ $client->email }}" data-type="email" data-required="true" data-realtime="email"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>Phone</td>
                                                <td><a href="tel:{!! $client->phonenumber or '' !!}" data-realtime="phone">{!! $client->phonenumber or '' !!}</a></td>
                                                <td><a href="#" class="editFieldModal" data-label="Phone" data-value="{{ $client->phonenumber }}" data-type="phone" data-required="true" data-realtime="phone"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-7 col-md-8">
                                <div class="row" style="margin:0">
                                    @if(!$client->gender)
                                    <div class="form-group gender">
                                        {!! Form::label(null, 'I Identify gender as', ['class' => 'strong']) !!}
                                        <div>
                                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                                <input type="radio" name="gender" id="male" value="Male">
                                                <label for="male"> Male</label>
                                            </div>
                                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                                <input type="radio" name="gender" id="female" value="Female">
                                                <label for="female"> Female</label>
                                            </div>
                                        </div>                           
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        {!! Form::label('contactStatus', 'Select Status *', ['class' => 'strong']) !!}
                                        {!! Form::select('contactStatus', array('' => '', 'contacted' => 'Contact made', 'messaged' => 'Left a message', 'noanswer' => 'No answer'), null, ['class' => 'form-control', 'required']) !!}
                                    </div>
                                    <div class="form-group callback" style="display:none">
                                        {!! Form::label('contactCbkDate', 'Callback Date *', ['class' => 'strong']) !!}
                                        {!! Form::text('contactCbkDate', null, ['class' => 'form-control', 'required', 'readonly']) !!}
                                    </div>
                                    <div class="form-group noteWrap">
                                        <textarea class="summernote" id="contactNote" placeholder="Write note here..."></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-success callSubview" data-target-subview="calendar">Book Consultation</button>
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::Button('Submit', ['class' => 'btn btn-primary pull-right', 'type' => 'submit']) !!}
                                        {!! Form::Button('Cancel', ['class' => 'btn btn-default pull-right margin-right-15 closeContactNoteSubview']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="row">
                        <div class="col-sm-5 col-md-4">
                            <div class="user-left">
                                <div class="center">
                                    <h4>
                                        <span data-realtime="firstName">{{ $client->firstname }}</span> 
                                        <span data-realtime="lastName">{{ $client->lastname }}</span>
                                    </h4>
                                    <div>
                                        <div class="user-image">
                                            <div class="thumbnail">
                                                <a href=""{{ dpSrc($client->profilepic, $client->gender) }}" data-lightbox="image-1" 
                                                    >
                                                <img src="{{ dpSrc($client->profilepic, $client->gender) }}" class="img-responsive clientPreviewPics previewPics" id="profile-userpic-img" alt="{{ $client->firstname }} {{ $client->lastname }}" data-realtime="gender" style="max-width: 120px !important;"></a>
                                                
                                              
                                            </div>
                                            <div class="form-group upload-group">
                                                <input type="hidden" name="prePhotoName" value="{{ $client->profilepic }}">
                                                <input type="hidden" name="entityId" value="{{$client->id}}">
                                                <input type="hidden" name="saveUrl" value="client/photo/save">
                                                <input type="hidden" name="photoHelper" value="client">
                                                <input type="hidden" name="cropSelector" value="square">
                                                <div>
                                                    <label class="btn btn-primary btn-file">
                                                        <span>Change Photo</span> <input type="file" class="hidden" onChange="fileSelectHandlerNew(this)" accept="image/*">
                                                    </label>
                                                    <label class="btn btn-primary btn-file">
                                                        <span id="openWebcam">Take Photo</span>
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
                                            <td><a href="mailto:{!! $client->email ?? '' !!}" data-realtime="email">{!! $client->email ?? '' !!}</a></td>
                                            <td><!-- <a href="#" class="editFieldModal" data-label="Email" data-value="{{ $client->email }}" data-type="email" data-required="true" data-realtime="email"><i class="fa fa-pencil edit-user-info"></i></a> --></td>
                                        </tr>
                                        <tr>
                                            <td>Phone:</td>
                                            <td><a href="tel:{!! $client->phonenumber ?? '' !!}" data-realtime="phone">{!! $client->phonenumber ?? '' !!}</a></td>
                                            <td><!-- <a href="#" class="editFieldModal" data-label="Phone" data-value="{{ $client->phonenumber }}" data-type="phone" data-required="true" data-realtime="phone"><i class="fa fa-pencil edit-user-info"></i></a> --></td>
                                        </tr>
                                        @if($client->addressline1)
                                        <tr>
                                            <td>Address:</td>
                                            <td>{{ $client->addressline1.', '.$client->addressline2.', '.$client->city.', '.$client->stateName.', '.$countries[$client->country].', '.$client->postal_code }}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
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
                                                {!! ($client->risk_factor >= 2)?'<span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This client has high risk factor"><i class="fa fa-warning"></i></span>':'' !!}
                                                {{ $client->risk_factor }}
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Occupation</td>
                                            <td data-realtime="occupation">{{ $parq->occupation }}</td>
                                            <td><a href="#" class="editFieldModal" data-label="Occupation" data-value="{{ $parq->occupation }}" data-type="occupation" data-realtime="occupation"><i class="fa fa-pencil edit-user-info"></i></a></td>
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
                                            <td>Date Created</td>
                                            <td>{{ dbDateToDateString($client->created_at) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                                                @if(empty($overviewDob))
                                                <a href="#" class="editFieldModal" data-label="Date of Birth" data-value="{{ $parq->dob }}" data-type="dob" data-required="true" data-realtime="dob"><i class="fa fa-pencil edit-user-info"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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

                            <!-- start: Appointments accordian -->
                            <div class="panel panel-white">

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
                                    <div id="calendEvent" class="calendEvent"></div>
                                    <input type="hidden" name="duration_val_in" id="duration_val_in" value="">
                                    @include('Result.partials.overview-events', ['latestPastEvent' => $latestPastEvent, 'oldestFutureEvent' => $oldestFutureEvent])
                                </div>
                                <!-- end: PANEL BODY -->
                            </div>
                            <!-- end: Appointments accordian -->

                            <!-- start: Sales accordian -->

                        </div>
                    </div>
                </div>


                <div id="panel_edit_account" class="tab-pane">
                    <div class="page-header">
                        <h1>Edit Account</h1>
                    </div>
                    <div>

                        @include('Result.partials.edit_client')

                    </div>
                </div>
                <!-- start: Events List -->
                @include('Result.partials.events_list', ['pastEvents' => $pastEvents, 'futureEvents' => $futureEvents])
                <!-- end: Events List --> 

                <!-- start: membership -->
                @include('Result.partials.membership_list', ['pastEvents' => $pastEvents, 'futureEvents' => $futureEvents])
                <!-- end: membership --> 

                <!-- start: makeup -->
                <div id="makeup-list" class="tab-pane">
                    @include('includes.partials.makeup_detail', ['allmakeup' => $allMakeup,'allnotes' => $allNotes,'clients' => $client])
                </div>
                <!-- end: makeup --> 


            </div>
        </div>
    </div>
</div>

<!--Start: Extra field -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cropperModalLabel">Cropper</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="photoName">
          <div class="img-container">
            <img id="imageCrop" src="" alt="Picture" height="340" width="100%">
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


<div class="modal fade" id="webcam-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Click a Picture</h4>
            </div>
            <div class="modal-body">
                <div id="camera" style="margin-left:112px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-webcam">Cancel</button>
                <button type="button" class="btn btn-info snap">Take picture</button>
            </div>
        </div>
    </div>
</div>

<!--End: Extra field -->

@endsection

@section('required-script')
{!! Html::script('result/js/jquery-ui.min.js') !!}
{!! Html::script('result/plugins/moment/moment.min.js') !!}
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
{!! Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
{!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
{!! Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js') !!}
{!! Html::script('result/js/bootstrap-timepicker.js') !!}
{!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}

{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}

<!-- start: image upload js -->
{!! Html::script('result/plugins/dropzone/cropper.js') !!}
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
<!-- start: image upload js -->

{!! Html::script('result/js/helper.js?v='.time()) !!}
<script>
    var loggedInUser = {
        type: '{{ Auth::user()->account_type }}',
        id: '{{ Auth::user()->account_id }}',
        name: '{{ Auth::user()->name }}'
    },
            popoverContainer = $('#container');
</script>
{!! Html::script('result/js/events-list.js?v='.time()) !!}

{!! Html::script('result/js/my-profile.js?v='.time()) !!}
{!! Html::script('result/js/business.js?v='.time()) !!}
{!! Html::script('result/js/edit-field-realtime.js?v='.time()) !!}
{!! Html::script('result/js/events-client.js?v='.time()) !!}
{!! Html::script('result/js/makeup.js?v='.time()) !!}

@stop