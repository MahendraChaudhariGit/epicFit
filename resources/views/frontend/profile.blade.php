@extends('blank')

@section('plugin-css')
	{!! Html::style('assets/css/plugins.css?v='.time()) !!}
@stop

@section('page-title')
	<span data-realtime="firstName">{{ Auth::user()->fname }}</span> <span data-realtime="lastName">{{ Auth::user()->lname }}</span>
@stop

@section('content')
<!-- start: Edit Field Model -->
@include('includes.partials.edit_field_modal', ['entity' => Auth::user()->account_type, 'entityId' => Auth::user()->pk])
<!-- end: Edit Field Model -->

@if(isUserType(['Staff']))
	<!-- start: Pic crop Model -->
	@include('includes.partials.pic_crop_model')
	<!-- end: Pic crop Model -->

	<!-- start: Appoinment Model -->
	@include('includes.partials.appointment_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
	<!-- end: Appoinment Model -->

	<!-- start: Appoinment Cancel Modal -->
	@include('includes.partials.appointment_cancel_modal', ['reasons' => $reasons])
	<!-- end: Appoinment Cancel Modal -->

	<!-- start: Class Modal -->
	@include('includes.partials.class_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
	<!-- end: Class Modal -->
@endif

<div class="row">
	<div class="col-sm-12 subviewPar">
		<div id="subview" class="subview">
            <iframe id="iframe"></iframe>
        </div>
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
				@if(isUserType(['Staff']))
					<li>
	                    <a data-toggle="tab" href="#appointments">
	                        Appointments
	                    </a>
	                </li>   
	            @endif      
			</ul>
			<div class="tab-content">
				<div id="panel_overview" class="tab-pane in active">
					<div class="row">
						@if(isUserType(['Staff']))
							@include('includes.partials.staff_overview', ['staff' => $staff]) <!--, 'countries' => $countries-->
							<div class="col-sm-7 col-md-8">
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
	                                            <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="staff-overview">
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
							</div>
						@else
							<div class="col-sm-5 col-md-4">
								<div class="user-left">
									<div class="center"> 
										<h4>
											<span data-realtime="firstName">{{ $user->name }}</span> 
											<span data-realtime="lastName">{{ $user->last_name }}</span>
										</h4>
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
												<td><a href="mailto:{!! $user->email or '' !!}" data-realtime="email">{!! $user->email or '' !!}</a></td>
												<td><a href="#" class="editFieldModal" data-label="Email" data-value="{{ $user->email }}" data-required="true" data-realtime="email"><i class="fa fa-pencil edit-user-info"></i></a></td>
											</tr>
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
							                    <td>Permission Group</td>
							                    <td><span class="label label-info">{{ $user->type->ut_name }}</span></td>
							                    <td></td>
							                </tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-sm-7 col-md-8"></div>										
						@endif
					</div>
				</div> 
				<div id="panel_edit_account" class="tab-pane">
					<div class="page-header">
				        <h1>Edit Account</h1>
				    </div>
				    <div>
				    	@if(isUserType(['Staff']))
					    	{!! Form::model($staff, ['method' => 'patch', 'route' => ['staffs.update', Auth::user()->pk]]) !!}
	        					{!! Form::hidden('prevServices', count($staffServices)?implode(',', $staffServices):'' , ['class' => 'no-clear']) !!}
	        					{!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
	        					<div class="sucMes hidden"></div>
		        				<div class="row">
		        					@include('includes.partials.staff_form')
		        				</div>
		        				{!! renderMy_profileSubmitRow() !!}
							{!! Form::close() !!}
						@else
							{!! Form::model($user, ['method' => 'patch', 'route' => ['auth.update']]) !!}
								<div class="sucMes hidden"></div>
		        				<div class="row">
		        					<div class="col-md-6">
									    <fieldset class="padding-15">
									        <legend>
									            General
									        </legend>
									        <div class="form-group">
									            {!! Form::label('adminFname', 'First Name *', ['class' => 'strong']) !!}
									            <span class="epic-tooltip" data-toggle="tooltip" title="This is your first name"><i class="fa fa-question-circle"></i></span>
									            <div>
									                {!! Form::text('adminFname', isset($user)?$user->name:null, ['class' => 'form-control', 'required' => 'required', 'data-realtime' => 'firstName', 'data-other-targets' => 'AdminFirstName']) !!}
									            </div>
									        </div>
									        <div class="form-group">
									            {!! Form::label('adminLname', 'Last Name *', ['class' => 'strong']) !!}
									            <span class="epic-tooltip" data-toggle="tooltip" title="This is your last name"><i class="fa fa-question-circle"></i></span>
									            <div>
									                {!! Form::text('adminLname', isset($user)?$user->last_name:null, ['class' => 'form-control', 'required' => 'required', 'data-realtime' => 'lastname']) !!}
									            </div>
									        </div>
									    </fieldset>
									    <fieldset class="padding-15">
								            <legend>
								                Password
								            </legend>
								            <div class="form-group">
								                {!! Form::label('adminNewPwd', 'New Password', ['class' => 'strong']) !!}
								                <span class="epic-tooltip" data-toggle="tooltip" title="This is your new password"><i class="fa fa-question-circle"></i></span>
								                {!! Form::password('adminNewPwd', ['class' => 'form-control', 'minlength' => 6]) !!}
								            </div>
								            <div class="form-group">
								                {!! Form::label('adminNewPwdCnfm', 'Confirm Password', ['class' => 'strong']) !!}
								                <span class="epic-tooltip" data-toggle="tooltip" title="This is your new password confirmation"><i class="fa fa-question-circle"></i></span>
								                {!! Form::password('adminNewPwdCnfm', ['class' => 'form-control onchange-set-neutral']) !!}
								                <span class="help-block m-b-0"></span>
								            </div>
								        </fieldset>
									</div>
									<div class="col-md-6">
									    <fieldset class="padding-15">
									    	<legend>
								                Contact
								            </legend>
								            <div class="form-group">
									            {!! Form::label('adminEmail', 'Email Address *', ['class' => 'strong']) !!}
									            <span class="epic-tooltip" data-toggle="tooltip" title="This email is the default for outgoing email correspondence and promotional materials for you"><i class="fa fa-question-circle"></i></span>
									            {!! Form::email('adminEmail', isset($user)?$user->email:null, ['class' => 'form-control', 'required' => 'required', 'data-realtime' => 'email']) !!}
									        </div>
								        </fieldset>
									</div>
		        				</div>
		        				{!! renderMy_profileSubmitRow() !!}
							{!! Form::close() !!}
						@endif
				    </div>
				</div>  
				@if(isUserType(['Staff']))
					<!-- start: Events List -->
	                @include('includes.partials.events_list', ['pastEvents' => $pastEvents, 'futureEvents' => $futureEvents, 'calendarSettingVal' => $calendarSettingVal])
	                <!-- end: Events List -->  
	            @endif
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<!-- {!! Html::script('vendor/moment/moment.min.js') !!}
{!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
{!! Html::script('assets/js/set-moment-timezone.js') !!}  -->
@if(isUserType(['Staff']))
	<!-- start: Bootstrap Typeahead -->
	{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}  
	<!-- end: Bootstrap Typeahead --> 

	<!-- start: Bootstrap timepicker -->
	<!--{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}-->
	<!-- end: Bootstrap timepicker -->

	<!-- Start:  NEW timepicker js -->
    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
    <!-- End: NEW timepicker js -->

	<!-- start: Jquery File Upload -->
	<script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-upload fade">
		<td>
		<span class="preview"></span>
		</td>
		<td>
		<p class="name">{%=file.name%}</p>
		{% if (file.error) { %}
		<div><span class="label label-danger">Error</span> {%=file.error%}</div>
		{% } %}
		</td>
		<td>
		<p class="size">{%=o.formatFileSize(file.size)%}</p>
		{% if (!o.files.error) { %}
		<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
		{% } %}
		</td>
		<td>
		{% if (!o.files.error && !i && !o.options.autoUpload) { %}
		<button class="btn btn-primary start">
		<i class="glyphicon glyphicon-upload"></i>
		<span>Start</span>
		</button>
		{% } %}
		{% if (!i) { %}
		<button class="btn btn-warning cancel">
		<i class="glyphicon glyphicon-ban-circle"></i>
		<span>Cancel</span>
		</button>
		{% } %}
		</td>
		</tr>
		{% } %}
	</script>
	<script id="template-download" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download fade">
		<td>
		<span class="preview">
		{% if (file.thumbnailUrl) { %}
		<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
		{% } %}
		</span>
		</td>
		<td>
		<p class="name">
		{% if (file.url) { %}
		<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
		{% } else { %}
		<span>{%=file.name%}</span>
		{% } %}
		</p>
		{% if (file.error) { %}
		<div><span class="label label-danger">Error</span> {%=file.error%}</div>
		{% } %}
		</td>
		<td>
		<span class="size">{%=o.formatFileSize(file.size)%}</span>
		</td>
		<td>
		{% if (file.deleteUrl) { %}
		<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
		<i class="glyphicon glyphicon-trash"></i>
		<span>Delete</span>
		</button>
		<input type="checkbox" name="delete" value="1" class="toggle">
		{% } else { %}
		<button class="btn btn-warning cancel">
		<i class="glyphicon glyphicon-ban-circle"></i>
		<span>Cancel</span>
		</button>
		{% } %}
		</td>
		</tr>
		{% } %}
	</script>
	{!! Html::script('assets/plugins/jquery-file-upload2/vendor/jquery.ui.widget.js') !!}
	<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
	{!! Html::script('assets/plugins/jquery-file-upload2/vendor/javascript-Load-Image/load-image.all.min.js') !!}
	<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
	<script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.iframe-transport.js') !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload.js?v='.time()) !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-process.js') !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-image.js') !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-audio.js') !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-video.js') !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-validate.js') !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/jquery.fileupload-ui.js') !!}
	{!! Html::script('assets/plugins/jquery-file-upload2/main.js') !!} 
	<!-- end: Jquery File Upload -->
@endif	

{!! Html::script('assets/js/helper.js?v='.time()) !!}
<script>
	var loggedInUser = {
	        //type: '{{ Session::get('userType') }}',
	        type: '{{ Auth::user()->account_type }}',
	        id: {{ Auth::user()->account_id }},
	        userId: {{ Auth::id() }},
	        name: '{{ Auth::user()->fullName }}'
	    };
</script>

@if(isUserType(['Staff']))
	<!-- start: Events -->
	<script>    
	var popoverContainer = $('#container');
	</script>
	{!! Html::script('assets/js/events.js?v='.time()) !!}
	{!! Html::script('assets/js/events-list.js?v='.time()) !!}
	<!-- end: Events -->

	{!! Html::script('assets/js/business-helper.js?v='.time()) !!}
@endif
	
{!! Html::script('assets/js/my-profile.js?v='.time()) !!}

<!-- start: Details update realtime -->
{!! Html::script('assets/js/edit-field-realtime.js?v='.time()) !!}
<!-- end: Details update realtime -->	
@stop