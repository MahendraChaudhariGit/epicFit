<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>
            General
        </legend>
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
            {!! Form::label('first_name', 'First Name *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the staff members first name"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required', 'data-realtime' => 'firstName', 'data-other-targets' => 'StaffFirstName']) !!}
                {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
            {!! Form::label('last_name', 'Last Name *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the staff members last name"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required', 'data-realtime' => 'lastname']) !!}
                {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group">
            <div>
                {!! Form::label(null, 'Gender *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the gender of the staff member"><i class="fa fa-question-circle"></i></span>
            </div>
            <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="gender" id="staffMale" value="Male" class="onchange-set-neutral" data-realtime="gender" {{ isset($staff) && $staff->gender == 'Male'?'checked':'' }}>
                    <label for="staffMale">
                        Male
                    </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="gender" id="staffFemale" value="Female" class="onchange-set-neutral" data-realtime="gender" {{ isset($staff) && $staff->gender == 'Female'?'checked':'' }}>
                    <label for="staffFemale">
                        Female
                    </label>
                </div>
            </div>
            <span class="help-block m-b-0"></span>
        </div>
        <div class="form-group clearfix">
            <div>
                {!! Form::label('day', 'Date Of Birth *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the staff members date of birth"><i class="fa fa-question-circle"></i></span>
            </div>
            <div class="row">
                <div class="col-md-4">
                    {!! Form::select('day', array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'), isset($staff)?str_pad($staff->birthDay, 2, '0', STR_PAD_LEFT):null, ['class' => 'form-control js-day', 'title' => 'DAY', 'data-realtime' => 'dob']) !!}   
                </div> 
                <div class="col-md-4">
                    <select class="form-control js-month" title="MONTH" name="month" data-realtime="dob">
                    @if(isset($staff))
                        {!! monthDdOptions($staff->birthMonth) !!}
                    @else
                        {!! monthDdOptions() !!}
                    @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control js-year" title="YEAR" name="year" data-realtime="dob">
                    @if(isset($staff))
                        {!! yearDdOptions($staff->birthYear) !!}
                    @else
                        {!! yearDdOptions() !!}
                    @endif
                    </select>
                </div>
            </div>
            <span class="help-block m-b-0"></span>
        </div>
        <div class="form-group {{ $errors->has('job_title') ? 'has-error' : ''}}">
            {!! Form::label('job_title', 'Job Title *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This describes the staff members job title"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('job_title', null, ['class' => 'form-control', 'required' => 'required', 'data-realtime' => 'jobTitle']) !!}
                {!! $errors->first('job_title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('staff_areas') ? 'has-error' : ''}}">
            {!! Form::label('staff_areas', 'Areas', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This describes staffs linked to areas"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('staff_areas', $locationAreas, isset($staffAreas)?$staffAreas:null, ['class' => 'form-control','multiple' => 'multiple']) !!}
                {!! $errors->first('staff_areas', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        @if(!isUserType(['Staff']))
            <div class="form-group {{ $errors->has('staff_permissions') ? 'has-error' : ''}}">
                {!! Form::label('staff_permissions', 'Staff Permissions *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the permissions the staff member have and access allowed through the software"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('staff_permissions', $permTyp, isset($staff)?$staff->ut_id:null, ['class' => 'form-control', 'required']) !!}
                    {!! $errors->first('staff_permissions', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        @endif
        <!--<div class="form-group upload-group">
            {!! Form::label(null, 'Upload Biography & CV ', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This allows the trainer to upload a copy of their CV & Biography"><i class="fa fa-question-circle"></i></span>
            <input type="hidden" name="prePhotoName" value="{{ isset($staff)?$staff->biography:'' }}" class="no-clear">
            <input type="hidden" name="entityId" value="" class="no-clear">
            <input type="hidden" name="saveUrl" value="" class="no-clear">
            <input type="hidden" name="photoHelper" value="staffBiography" class="no-clear">
            <input type="hidden" name="cropSelector" value="">
            <input type="hidden" id="file_upload" name="file_upload" value="">
            
                <!--<label class="btn btn-primary btn-file">
                    <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                </label>
              <!--file upload -->
             
             <!--
                <div class="row fileupload-buttonbar" id="fileupload">
                                            <div class="col-lg-12">
                                               
                                                <span class="btn btn-primary fileinput-button" id="addbtn"> <i class="glyphicon glyphicon-plus"></i> <span>Select file</span>
                                                    <input type="file" name="files[]" id="file-upload" value="">
                                                </span>
                                                
                                                
                                                <span class="fileupload-loading"></span>
                                            </div>
                                            
                                            <div class="col-lg-12 fileupload-progress fade">
                                                
                                               <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                                </div>
                                                
                                                <div class="progress-extended">
                                                    &nbsp;
                                                </div>
                                            </div>
             
                  <table role="presentation" class="table table-striped">
                  <tbody class="files file-upload"></tbody>
                  </table>
                 </div>

                               

                <div class="m-t-10">
                    @if(isset($staff) && $staff->biography)
                    <img class="staffBiographyPreviewPics previewPics" src="{{ dpSrc($staff->biography) }}" />
                    @else
                    <img class="hidden staffBiographyPreviewPics previewPics" />
                    @endif
                </div>
            
            <input type="hidden" name="staffBiography" value="{{ isset($staff) && $staff->biography?$staff->biography:'' }}">
        </div>-->
        <!--edited upload group-->
        <div class="form-group">
            {!! Form::label('file-upload', 'Upload Biography & CV ', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This allows the trainer to upload a copy of their CV & Biography"><i class="fa fa-question-circle"></i></span>
            <div class="row fileupload-buttonbar" id="fileupload">
                <div class="col-lg-12">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-primary fileinput-button" id="addbtn"> <i class="glyphicon glyphicon-plus"></i> <span>Select file</span>
                        <input type="file" name="files[]" id="file-upload" accept=".pdf,.doc,.docx">
                    </span>
                    <span class="fileupload-loading"></span>
                </div>
                <!-- The global progress information -->
                <div class="col-lg-12 fileupload-progress fade hidden">
                    <!-- The global progress bar -->
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <!-- The extended global progress information -->
                    <div class="progress-extended">
                        &nbsp;
                    </div>
                </div>
                <table class="table table-striped m-b-0">
                    <tbody class="files"></tbody>
                </table>
                <input type="hidden" name="staffBiography" value="">
            </div>
        </div>

        <div class="form-group upload-group">
            {!! Form::label(null, 'Upload Profile Picture *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This allows the trainers profile picture of themselves to be uploaded"><i class="fa fa-question-circle"></i></span>
            <input type="hidden" name="prePhotoName" value="{{ isset($staff)?$staff->profile_picture:'' }}" class="no-clear">
            <input type="hidden" name="entityId" value="" class="no-clear">
            <input type="hidden" name="saveUrl" value="" class="no-clear">
            <input type="hidden" name="photoHelper" value="staffProfilePictureUpload" class="no-clear">
            <input type="hidden" name="cropSelector" value="square">
            <div>
                <label class="btn btn-primary btn-file">
                    <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                </label>
                <div class="m-t-10">
                    @if(isset($staff))
                    <img class="staffProfilePictureUploadPreviewPics previewPics" src="{{ dpSrc($staff->profile_picture) }}" />
                    @else
                    <img class="hidden staffProfilePictureUploadPreviewPics previewPics" />
                    @endif
                </div>
                <span class="help-block m-b-0"></span>
                <input type="hidden" name="staffProfilePictureUpload" value="{{ isset($staff)?$staff->profile_picture:'' }}">
            </div>
        </div>
        @if(!isUserType(['Staff']))
        <div class="form-group {{ $errors->has('login_with_email') ? 'has-error' : ''}}">
            <div class="checkbox clip-check check-primary m-b-0">
                <input type="checkbox" name="login_with_email" id="login_with_email" value="1" class="js-ifCreateLogin" {{ isset($staff) && $staff->login_with_email?'checked':'' }} data-old-login-with-email="{{ isset($staff) && $staff->login_with_email?1:0 }}">
                <label for="login_with_email">
                    <strong>Allow staff member to log in with email</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please ensure that if you change your email address that you change your username when logging in"><i class="fa fa-question-circle"></i></span>
                </label>
                {!! $errors->first('login_with_email', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        @endif
        <div class="form-group {{ $errors->has('clients_book_staff') ? 'has-error' : ''}}">
            <div class="checkbox clip-check check-primary m-b-0">
                <input type="checkbox" name="clients_book_staff" id="clients_book_staff" value="1" {{ isset($staff) && $staff->clients_book_staff?'checked':'' }}>
                <label for="clients_book_staff">
                    <strong>
                        {{ isUserType(['Staff'])?'Can clients book you online':'Can clients book this staff member online' }}
                    </strong> 
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows the client to book this trainer online"><i class="fa fa-question-circle"></i></span>
                </label>
                {!! $errors->first('clients_book_staff', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </fieldset>

    <fieldset class="padding-15 js-pwdFieldset">
        <legend>
            Password 
        </legend>
        <div class="form-group"> 
           {!! Form::label('staffNewPwdd', 'New Password', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the staff members new password"><i class="fa fa-question-circle"></i></span>
            <input class="form-control" minlength="6" autocomplete="new-password" name="staffNewPwdd" type="password" value="{{isset($pwd)?$pwd:''}}" id="staffNewPwdd">
        </div>
        <div class="form-group">
            {!! Form::label('staffNewPwdCnfmm', 'Confirm Password', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the staff members new password confirmation"><i class="fa fa-question-circle"></i></span>
            <input type="password", value="{{isset($pwd)?$pwd:null}}" autocomplete ='new-password', class="form-control customValField" name="staffNewPwdCnfmm" id="staffNewPwdCnfmm" />
            
            <span class="help-block m-b-0"></span>
        </div>
    </fieldset>
        
    @if(isset($staff))
        <fieldset class="padding-15 workingHrs" data-old-hours="{{ isset($staff) && count($staff->hours)?$staff->hours:'' }}" id="working-hours-check">
    @elseif(isset($locHours))
    <fieldset class="padding-15 workingHrs" data-old-hours="{{ isset($locHours) && count($locHours)?$locHours:'' }}" id="working-hours-check">
    @else
        <fieldset class="padding-15" id="working-hours-check">
    @endif
        <legend>
            Hours *
            <span class="epic-tooltip" data-toggle="tooltip" title="Availability of bookings and days available"><i class="fa fa-question-circle"></i></span>
        </legend>
        <div class="form-group {{ $errors->has('monday') ? 'has-error' : ''}}" data-day="monday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="monday" id="monday_staff" value="1" checked class="showHours no-clear">
                        <label for="monday_staff" class="m-r-0"><strong>Monday</strong></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn-add-new-time showHoursElem margin-left-5 pull-right" data-dayname="monday">+ Add new time</a>
                </div>
            </div>
            <div class="row notWork" style="display: none;">
                <div class="col-xs-12 ">
                    Not working on this day
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 showHoursElem copy-row">
                    <div class="col-xs-4 no-padding ">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="monday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2 no-padding text-center text-bold">&#95;&#95;&#95;&#95;</div>
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="monday_end0" class="form-control input-sm end-day no-clear timepicker1" data-default-time="7:00 PM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>
                    </div>
                </div>
            </div>
            {!! $errors->first('monday', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('tuesday') ? 'has-error' : ''}}" data-day="tuesday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="tuesday" id="tuesday_staff" value="1" checked class="showHours no-clear">
                        <label for="tuesday_staff" class="m-r-0"><strong>Tuesday</strong></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn-add-new-time margin-left-5 showHoursElem pull-right" data-dayname="tuesday">+ Add new time</a>
                </div>
            </div>
            <div class="row notWork" style="display: none;">
                <div class="col-xs-12">
                    Not working on this day
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 showHoursElem copy-row">
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="tuesday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" ><!-- -->
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2 no-padding text-center text-bold ">&#95;&#95;&#95;&#95;</div>
                    <div class="col-xs-4 no-padding ">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="tuesday_end0" class="form-control input-sm end-day no-clear timepicker1" data-default-time="7:00 PM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>
                    </div>
                </div>
            </div>
            {!! $errors->first('tuesday', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('wednesday') ? 'has-error' : ''}}" data-day="wednesday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="wednesday" id="wednesday_staff" value="1" checked class="showHours no-clear">
                        <label for="wednesday_staff" class="m-r-0"><strong>Wednesday</strong></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn-add-new-time margin-left-5 showHoursElem pull-right" data-dayname="wednesday">+ Add new time</a>
                </div>
            </div>
            <div class="row notWork" style="display: none;">
                <div class="col-xs-12">
                    Not working on this day
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 showHoursElem copy-row">
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="wednesday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" ><!-- -->
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2 no-padding text-center text-bold">&#95;&#95;&#95;&#95;</div>
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="wednesday_end0" class="form-control input-sm end-day no-clear timepicker1" data-default-time="7:00 PM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>
                    </div>
                </div>
            </div>
            {!! $errors->first('wednesday', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('thursday') ? 'has-error' : ''}}" data-day="thursday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="thursday" id="thursday_staff" value="1" checked class="showHours no-clear">
                        <label for="thursday_staff" class="m-r-0"><strong>Thursday</strong></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn-add-new-time margin-left-5 showHoursElem pull-right" data-dayname="thursday">+ Add new time</a>
                </div>
            </div>
            <div class="row notWork" style="display: none;">
                <div class="col-xs-12">
                    Not working on this day
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 showHoursElem copy-row">
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="thursday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" ><!-- -->
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2 no-padding text-center text-bold">&#95;&#95;&#95;&#95;</div>
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="thursday_end0" class="form-control input-sm end-day no-clear timepicker1" data-default-time="7:00 PM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>
                    </div>
                </div>
            </div>
            {!! $errors->first('thursday', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('friday') ? 'has-error' : ''}}" data-day="friday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="friday" id="friday_staff" value="1" checked class="showHours no-clear">
                        <label for="friday_staff" class="m-r-0"><strong>Friday</strong></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn-add-new-time margin-left-5 showHoursElem pull-right" data-dayname="friday">+ Add new time</a>
                </div>
            </div>
            <div class="row notWork" style="display: none;">
                <div class="col-xs-12">
                    Not working on this day
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 showHoursElem copy-row">
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="friday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" ><!-- -->
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2 no-padding text-center text-bold">&#95;&#95;&#95;&#95;</div>
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="friday_end0" class="form-control input-sm end-day no-clear timepicker1" data-default-time="7:00 PM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>
                    </div>
                </div>
            </div>
            {!! $errors->first('monday', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('saturday') ? 'has-error' : ''}}" data-day="saturday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="saturday" id="saturday_staff" value="1" checked class="showHours no-clear">
                        <label for="saturday_staff" class="m-r-0"><strong>Saturday</strong></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn-add-new-time margin-left-5 showHoursElem pull-right" data-dayname="saturday">+ Add new time</a>
                </div>
            </div>
            <div class="row notWork" style="display: none;">
                <div class="col-xs-12">
                    Not working on this day
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 showHoursElem copy-row">
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="saturday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" ><!-- -->
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2 no-padding text-center text-bold ">&#95;&#95;&#95;&#95;</div>
                    <div class="col-xs-4 no-padding ">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="saturday_end0" class="form-control input-sm end-day no-clear timepicker1" data-default-time="7:00 PM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>
                    </div>
                </div>
            </div>
            {!! $errors->first('saturday', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('sunday') ? 'has-error' : ''}}" data-day="sunday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="sunday" id="sunday_staff" value="1" checked class="showHours no-clear">
                        <label for="sunday_staff" class="m-r-0"><strong>Sunday</strong></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn-add-new-time margin-left-5 showHoursElem pull-right" data-dayname="sunday">+ Add new time</a>
                </div>
            </div>
            <div class="row notWork" style="display: none;">
                <div class="col-xs-12">
                    Not working on this day
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 showHoursElem copy-row">
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="sunday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" ><!-- -->
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2 no-padding text-center text-bold">&#95;&#95;&#95;&#95;</div>
                    <div class="col-xs-4 no-padding">
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" name="sunday_end0" class="form-control input-sm end-day no-clear timepicker1" data-default-time="7:00 PM" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>
                    </div>
                </div>
            </div>
            {!! $errors->first('sunday', '<p class="help-block">:message</p>') !!}
        </div>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>
            Contact
        </legend>
        <div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
            {!! Form::label('website', 'Staff Member Website Address ', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the main website for this specific staff member"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('website', null, ['class' => 'form-control customValField']) !!}
                <span class="help-block m-b-0"></span>
                {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('facebook') ? 'has-error' : ''}}">
            {!! Form::label('facebook', 'Staff Member Facebook Page ', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the main Facebook page for this specific staff member"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('facebook', null, ['class' => 'form-control customValField']) !!}
                <span class="help-block m-b-0"></span>
                {!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
            {!! Form::label('email', 'Staff Member Email *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This email is the default for outgoing email correspondence and promotional materials for this staff member"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'data-realtime' => 'email']) !!}
                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
            {!! Form::label('phone', 'Staff Member Phone *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the primary contact detail for this specific staff member"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::tel('phone', null, ['class' => 'form-control countryCode numericField', 'required' => 'required', 'maxlength' => '16', 'minlength' => '5', 'data-realtime' => 'phone']) !!}
                {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row padding-15 padding-bottom-0">
            <div class="row padding-15 padding-bottom-0">
                <label for="fixed_location"><strong>Fixed Location (Client comes to your venue) or Mobile (You cater for client at their venue) * </strong><span class="epic-tooltip" data-toggle="tooltip" title="This relates to the trainer being located at a set location or as a mobile trainer."><i class="fa fa-question-circle"></i></span></label>
            </div>
            <div class="row">
                <ul class="selectable_fixed_location">
                    @if(isset($staff))
                        <li class="col-xs-6 ui-widget-content {{ $staff->fixed_location?'ui-selected':'' }}">Fixed Location</li>
                        <li class="col-xs-6 ui-widget-content {{ !$staff->fixed_location?'ui-selected':'' }}">Mobile Selection</li>
                    @else
                        <li class="col-xs-6 ui-widget-content ui-selected">Fixed Location</li>
                        <li class="col-xs-6 ui-widget-content">Mobile Selection</li>
                    @endif
                </ul>

                <div class="form-group {{ $errors->has('fixed_location') ? 'has-error' : ''}}">
                    <div>
                        @if(isset($staff))
                        {!! Form::text('fixed_location', $staff->fixed_location?'Fixed Location':'Mobile Selection', ['class' => 'form-control fixed_location hide no-clear', 'required' => 'required']) !!}
                        @else
                        {!! Form::text('fixed_location', 'Fixed Location', ['class' => 'form-control fixed_location hide no-clear', 'required' => 'required']) !!}
                        @endif
                        {!! $errors->first('fixed_location', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group notForMobloc {{ isset($staff) && !$staff->fixed_location?'remove':'' }} {{ $errors->has('address_line_one') ? 'has-error' : ''}}">
            {!! Form::label('address_line_one', 'Address Line 1 *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('address_line_one', null, ['class' => 'form-control address_line_one', 'required','id' => 'autocomplete','onFocus' => 'geolocate()','autocomplete' => 'off']) !!}
                {!! $errors->first('address_line_one', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group notForMobloc {{ isset($staff) && !$staff->fixed_location?'remove':'' }} {{ $errors->has('address_line_two') ? 'has-error' : ''}}">
            {!! Form::label('address_line_two', 'Address Line 2', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('address_line_two', null, ['class' => 'form-control address_line_two']) !!}
                {!! $errors->first('address_line_two', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('city') ? 'has-error' : ''}}">
            {!! Form::label('city', 'City *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('city', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
            {!! Form::label('country', 'Country *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('country', $country, null, ['class' => 'form-control countries', 'required' => 'required','autocomplete'=>'off']) !!}
                {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
            {!! Form::label('state', 'State / Region *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('state', isset($staff)?$states:['' => '-- Select --'], isset($staff)?$staff->state:null, ['class' => 'form-control states', 'required' => 'required']) !!}
                {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('postal_code') ? 'has-error' : ''}}">
            {!! Form::label('postal_code', 'Postal Code *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('postal_code', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('postal_code', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('time_zone') ? 'has-error' : ''}}">
            {!! Form::label('time_zone', 'Time Zone *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="Time zones are important for numerous reasons including communication and reminders"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('time_zone', $time_zone, null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('time_zone', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        {{-- <div class="form-group {{ $errors->has('disp_location_web') ? 'has-error' : ''}}">
            <div class="checkbox clip-check check-primary m-b-0">
                <input type="checkbox" name="disp_location_web" id="disp_location_web_staff" value="1" {{ isset($staff) && $staff->disp_location_web?'checked':'' }}>
                <label for="disp_location_web_staff">
                    <strong>Display location on the EPIC Trainer Website</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please read terms and conditions"><i class="fa fa-question-circle"></i></span>
                </label>
                {!! $errors->first('disp_location_web', '<p class="help-block">:message</p>') !!}
            </div>
        </div> --}}
        {{-- <div class="form-group {{ $errors->has('disp_location_online') ? 'has-error' : ''}}">
            <div class="checkbox clip-check check-primary m-b-0">
                <input type="checkbox" name="disp_location_online" id="disp_location_online_staff" value="1" {{ isset($staff) && $staff->disp_location_online?'checked':'' }}>
                <label for="disp_location_online_staff">
                    <strong>Clients can view and book this location online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows the clients to view this location online and allows them to book into services for this location"><i class="fa fa-question-circle"></i></span>
                </label>
                {!! $errors->first('disp_location_online', '<p class="help-block">:message</p>') !!}
            </div>
        </div> --}}
    </fieldset>
    <fieldset class="padding-15">
        <legend>
            Services & Rates
        </legend>
        <div class="form-group {{ $errors->has('staff_services') ? 'has-error' : ''}}">
            {!! Form::label('staff_services', 'Staff Services', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
            @if(!isset($subview))
                <a href="#" class="pull-right callSubview" data-target-subview="service">+ Add New Service</a>
            @endif
            <div>
                {!! Form::select('staff_services', isset($businessId)?$serv:[], isset($staff) && count($staffServices)?$staffServices:null, ['class' => 'form-control service', 'multiple']) !!}
                {!! $errors->first('staff_services', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('staffClasses') ? 'has-error' : ''}}">
            {!! Form::label('staffClasses', 'Staff Classes', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('staffClasses', isset($businessId)?$clses:[], isset($staff) && count($staffClasses)?$staffClasses:null, ['class' => 'form-control', 'multiple']) !!}
                {!! $errors->first('staffClasses', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="panel-group accordion" id="accordion">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <i class="icon-arrow"></i> Hourly Rates
                        </a></h5>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('hourly_payment_label') ? 'has-error' : ''}}">
                            {!! Form::label('hourly_payment_label', 'Payment Label ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::text('hourly_payment_label', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('hourly_payment_label', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('hourly_rate') ? 'has-error' : ''}}">
                            {!! Form::label('hourly_rate', 'Hourly Rate ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::text('hourly_rate', null, ['class' => 'form-control price-field']) !!}
                                {!! $errors->first('hourly_rate', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('date_range') ? 'has-error' : ''}}">
                            {!! Form::label('date_range', 'Date Range ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div class="row">
                                <div class="col-xs-6">
                                    {!! Form::text('hourly_start_date', null, ['class' => 'form-control datepicker onchange-set-neutral', 'placeholder' => 'Start Date', 'readonly']) !!}
                                    <span class="help-block m-b-0"></span>
                                    {!! $errors->first('hourly_start_date', '<p class="help-block">:message</p>') !!}
                                </div>
                                <div class="col-xs-6">
                                    {!! Form::text('hourly_end_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'End Date', 'readonly']) !!}
                                    {!! $errors->first('hourly_end_date', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <!--<div class="form-group {{ $errors->has('hourly_member') ? 'has-error' : ''}}">
                            {!! Form::label('hourly_member', 'Staff Member ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('hourly_member', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                {!! $errors->first('hourly_member', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            <i class="icon-arrow"></i> Commission
                        </a></h5>
                </div>
                <div id="collapseTwo" class="panelcls collapse">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('commission_payment') ? 'has-error' : ''}}">
                            {!! Form::label('commission_payment', 'Payment Label ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::text('commission_payment', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('commission_payment', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('commission_role') ? 'has-error' : ''}}">
                            {!! Form::label('commission_role', 'Role ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <!-- <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Role</a> -->
                             <a href="{{ route('commission.getRole') }}" class="pull-right add-more" data-modal-title="Commission Roles" data-field="commissionCat">Manage Roles</a>
                            {!! Form::hidden('btn-add-more-action', 'commission/roles', ['class' => 'no-clear']) !!}
                            <div>
                                {!! Form::select('commission_role', isset($businessId)?$commissionRole:[], isset($staff) && count($staffCommissionRole)?$staffCommissionRole:null, ['class' => 'form-control commissionCat', 'multiple']) !!}
                                {!! $errors->first('commission_role', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input id="comission_role_all" class="selAllDd" type="checkbox">
                                <label for="comission_role_all" class="no-error-label">
                                    <strong>Select All</strong>
                                </label>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('commission_income_category') ? 'has-error' : ''}}">
                            {!! Form::label('commission_income_category', 'Income Category ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <!-- <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Category</a> -->
                            <a href="{{ route('income.getCat') }}" class="pull-right add-more" data-modal-title="Income Categories" data-field="incomeCat">Manage Categories</a>
                            {!! Form::hidden('btn-add-more-action', 'commission/category', ['class' => 'no-clear']) !!}
                            <div>
                                {!! Form::select('commission_income_category', isset($businessId)?$commissionCategory:[], isset($staff) && count($staffCommissionCategory)?$staffCommissionCategory:null, ['class' => 'form-control incomeCat', 'multiple']) !!}
                                {!! $errors->first('commission_income_category', '<p class="help-block">:message</p>') !!}
                            </div>
                         
                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input id="commission_income_category_all" class="selAllDd" type="checkbox">
                                <label for="commission_income_category_all" class="no-error-label">
                                    <strong>Select All</strong>
                                </label>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('commission_referral_source') ? 'has-error' : ''}}">
                            {!! Form::label('commission_referral_source', 'Referral Source ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                             <!-- <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Source</a> -->
                             <a href="{{ route('commission.getSource') }}" class="pull-right add-more" data-modal-title="Commission Sources" data-field="sourceCat">Manage Sources</a>
                            {!! Form::hidden('btn-add-more-action', 'commission/source', ['class' => 'no-clear']) !!}
                            <div>
                                {!! Form::select('commission_referral_source', isset($businessId)?$commissionSource:[], isset($staff) && count($staffCommissionSource)?$staffCommissionSource:null, ['class' => 'form-control sourceCat', 'multiple']) !!}
                                {!! $errors->first('commission_referral_source', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input id="commission_source_all" class="selAllDd" type="checkbox">
                                <label for="commission_source_all" class="no-error-label">
                                    <strong>Select All</strong>
                                </label>
                            </div>
                        </div>
                         <div class="form-group {{ $errors->has('commission_rate') ? 'has-error' : ''}}">
                            {!! Form::label('commission_rate', 'Rate ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::text('commission_rate', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('commission_rate', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('commission_tax') ? 'has-error' : ''}}">
                            {!! Form::label('commission_tax', 'Tax ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('commission_tax', ['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A'], null, ['class' => 'form-control']) !!}
                                {!! $errors->first('commission_tax', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                       
                        <div class="form-group {{ $errors->has('commission_date_range') ? 'has-error' : ''}}">
                            {!! Form::label('commission_date_range', 'Date Range ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div class="row">
                                <div class="col-xs-6">
                                    {!! Form::text('commission_date_range_start', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start']) !!}
                                    {!! $errors->first('commission_date_range_start', '<p class="help-block">:message</p>') !!}
                                    <span class="help-block m-b-0"></span>
                                </div>
                                <div class="col-xs-6">
                                    {!! Form::text('commission_date_range_end', null, ['class' => 'form-control datepicker', 'placeholder' => 'End']) !!}
                                    {!! $errors->first('commission_date_range_end', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <!--div class="form-group {{ $errors->has('commission_staff_member') ? 'has-error' : ''}}">
                            {!! Form::label('commission_staff_member', 'Staff Member ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('commission_staff_member', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                {!! $errors->first('commission_staff_member', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div-->
                    </div>
                </div>
            </div>
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            <i class="icon-arrow"></i> Per Session
                        </a></h5>
                </div>
                <div id="collapseThree" class="panelcls collapse">
                    <div class="panel-body">
                      <div class="form-group {{ $errors->has('per_session_rate_options') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_rate_options', null, ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('per_session_rate_options', ['base_rate' => 'Base Rate Only', 'base_rate_attendees' => 'Base Rate Plus Attendees','base_rate_tier'=>'Base Rate Plus Tier'], null, ['class' => 'form-control']) !!}
                                {!! $errors->first('per_session_rate_options', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('per_session_payment_label') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_payment_label', 'Payment Label ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::text('per_session_payment_label', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('per_session_payment_label', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('per_session_role') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_role', 'Role ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <!-- <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Role</a> -->
                            <a href="{{ route('session.getRole') }}" class="pull-right add-more" data-modal-title="Session Roles" data-field="sessionRole">Manage Roles</a>
                            {!! Form::hidden('btn-add-more-action', 'per-session/roles', ['class' => 'no-clear']) !!}
                            <div>
                                {!! Form::select('per_session_role', isset($businessId)?$sessionRole:[], isset($staff) && count($staffSessionRole)?$staffSessionRole:null, ['class' => 'form-control sessionRole', 'multiple']) !!}
                                {!! $errors->first('per_session_role', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input id="per_session_role_all" class="selAllDd" type="checkbox">
                                <label for="per_session_role_all" class="no-error-label">
                                    <strong>Select All</strong>
                                </label>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('per_session_service') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_service', 'Services', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('per_session_service', isset($sessionStaffService )?$sessionStaffService:[], isset($staff) && count($staffSessionServices)?$staffSessionServices:null, ['class' => 'form-control service', 'multiple']) !!}

                                {!! $errors->first('per_session_service', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input id="per_session_service_all" class="selAllDd" type="checkbox">
                                <label for="per_session_service_all" class="no-error-label">
                                    <strong>Select All</strong>
                                </label>
                            </div>
                        </div>  
                        <!--div class="form-group {{ $errors->has('per_session_program_category') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_program_category', 'Program Category ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('per_session_program_category', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                {!! $errors->first('per_session_program_category', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div-->
                        <div class="form-group {{ $errors->has('per_session_class') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_class', 'Classes', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>

                                {!! Form::select('per_session_class', isset($sessionStaffClass)?$sessionStaffClass:[], isset($staff) && count($staffSessionCategory)?$staffSessionCategory:null, ['class' => 'form-control ', 'multiple']) !!}
                                {!! $errors->first('per_session_class', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input id="per_session_class_all" class="selAllDd" type="checkbox">
                                <label for="per_session_class_all" class="no-error-label">
                                    <strong>Select All</strong>
                                </label>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('per_session_base_rate') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_base_rate', 'Base Rate ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::text('per_session_base_rate', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('per_session_base_rate', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                           <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox"  id="pay_for" name="per_session_pay_for" value="1" class="pay_for" {{ isset($staff) && $staff->per_session_pay_for?'checked':'' }}>
                                <label for="pay_for" class="m-r-0">Pay For no shows</label>
                            </div></div>
              {!! Form::hidden('staffAttendeeArr',isset($staffAttendeeArr)?htmlspecialchars(json_encode($staffAttendeeArr)):null, ['class' => 'staffAttendeeArrCls']) !!}

                        <div class="form-group sessionattendeescls" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Attendees from</strong>
                                </div>
                                <div class="col-md-3">
                                    <strong>Attendees to</strong>
                                </div>
                               
                                <div class="col-md-4 ">
                                    <strong class="attendeespricecls">Pay for each attendee</strong>
                                </div>
                            </div>

                            <div class="row m-t-5">
                                
                                <div class="col-md-3">
                                   
                                    {!! Form::text('per_session_attendees0', null, ['class' => 'form-control numericField sessionattendance']) !!}
                                </div>
                                
                                <div class="col-md-3">
                                  {!! Form::text('per_session_attendeeto0', null, ['class' => 'form-control numericField sessionattendanceto']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('per_session_price0', null, ['class' => 'form-control price-field sessionPrice']) !!}
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-primary addMoreAttendance" href="#">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                          

                          <div class="form-group sessionattendeestiercls" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Attendees from</strong>
                                </div>
                                <div class="col-md-3">
                                    <strong>Attendees to</strong>
                                </div>
                               
                                <div class="col-md-4 ">
                                    <strong class="attendeespricecls">Pay Flat</strong>
                                </div>
                            </div>

                            <div class="row m-t-5">
                                
                                <div class="col-md-3">
                                   
                                    {!! Form::text('per_session_tier0', null, ['class' => 'form-control numericField  sessionattendance']) !!}
                                </div>
                                
                                <div class="col-md-3">
                                  {!! Form::text('per_session_tierto0', null, ['class' => 'form-control numericField  sessionattendanceto']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('per_session_tierprice0', null, ['class' => 'form-control price-field sessionPrice']) !!}
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-primary addMoreAttendance" href="#">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>              
                        <!--div class="form-group {{ $errors->has('per_session_attendee') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_attendee', 'Remuneration per attendee ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::text('per_session_attendee', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('per_session_attendee', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div-->
                        <div class="form-group {{ $errors->has('per_session_date_range') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_date_range', 'Date Range ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div class="row">
                                <div class="col-xs-6">
                                    {!! Form::text('per_session_start_date', null, ['class' => 'form-control datepicker onchange-set-neutral', 'placeholder' => 'Start']) !!}
                                    {!! $errors->first('per_session_start_date', '<p class="help-block">:message</p>') !!}
                                    <span class="help-block m-b-0"></span>
                                </div>
                                <div class="col-xs-6">
                                    {!! Form::text('per_session_end_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'End']) !!}
                                    {!! $errors->first('per_session_end_date', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <!--div class="form-group {{ $errors->has('per_session_staff_member') ? 'has-error' : ''}}">
                            {!! Form::label('per_session_staff_member', 'Staff Member ', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('per_session_staff_member', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                {!! $errors->first('per_session_staff_member', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div-->
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
