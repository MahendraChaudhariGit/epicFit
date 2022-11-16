@extends('Settings.subview')

@section('content')
<div class="swMain scrollToTop" id="step-3">
    {!! Form::open(['url' => 'settings/staff', 'id' => 'form-3', 'class' => 'margin-bottom-30', 'data-is-subview' => true]) !!}
    {!! Form::hidden('businessId', $businessId, ['class' => 'businessId no-clear']) !!}
    <div class="row">
        <div class="sucMes hidden"></div>
        <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    General
                </legend>
                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                    {!! Form::label('first_name', 'First Name *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the staff members first name"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                    {!! Form::label('last_name', 'Last Name *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the staff members last name"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
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
                            <input type="radio" name="gender" id="staffMale" value="Male" class="customValRadField">
                            <label for="staffMale">
                                Male
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="gender" id="staffFemale" value="Female" class="customValRadField">
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
                            {!! Form::select('day', array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'), null, ['class' => 'form-control', 'title' => 'DAY', '']) !!}
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" title="MONTH" name="month">
                            {!! monthDdOptions(old('month')) !!}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" title="YEAR" name="year">
                                {!! yearDdOptions(old('year')) !!}
                            </select>
                        </div>
                    </div>
                    <span class="help-block m-b-0"></span>
                </div>
                <div class="form-group {{ $errors->has('job_title') ? 'has-error' : ''}}">
                    {!! Form::label('job_title', 'Job Title *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This describes the staff members job title"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('job_title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('job_title', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('staff_permissions') ? 'has-error' : ''}}">
                    {!! Form::label('staff_permissions', 'Staff Permissions *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the permissions the staff member have and access allowed through the software"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('staff_permissions', ['Main' => 'Top Permission'], null, ['class' => 'form-control', 'multiple', 'required']) !!}
                        {!! $errors->first('staff_permissions', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group upload-group">
                    {!! Form::label(null, 'Upload Biography & CV ', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows the trainer to upload a copy of their CV & Biography"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="staffBiography" class="no-clear">
                    <input type="hidden" name="cropSelector" value="">
                    <div>
                        <label class="btn btn-primary btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            <img class="hidden staffBiographyPreviewPics previewPics" />
                        </div>
                    </div>
                    <input type="hidden" name="staffBiography">
                </div>
                <div class="form-group upload-group">
                    {!! Form::label(null, 'Upload Profile Picture *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows the trainers profile picture of themselves to be uploaded"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="staffProfilePicture" class="no-clear">
                    <input type="hidden" name="cropSelector" value="square">
                    <div>
                        <label class="btn btn-primary btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            <img class="hidden staffProfilePicturePreviewPics previewPics" />
                        </div>
                        <span class="help-block m-b-0"></span>
                        <input type="hidden" name="staffProfilePicture" value="">
                    </div>
                </div>
                <div class="form-group {{ $errors->has('login_with_email') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="login_with_email" id="login_with_email" value="1">
                        <label for="login_with_email">
                            <strong>Allow staff member to log in with email</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please ensure that if you change your email address that you change your username when logging in"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('login_with_email', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('clients_book_staff') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="clients_book_staff" id="clients_book_staff" value="1">
                        <label for="clients_book_staff">
                            <strong>Can clients book this staff member online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows the client to book this trainer online"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('clients_book_staff', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
            <fieldset class="padding-15">
                <legend>
                    Hours *
                    <span class="epic-tooltip" data-toggle="tooltip" title="Availability of bookings and days available"><i class="fa fa-question-circle"></i></span>
                </legend>
                <div class="form-group {{ $errors->has('monday') ? 'has-error' : ''}}">
                    {!! Form::label('monday_staff', 'Monday ', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="monday" id="monday_staff" value="1" checked class="showHours no-clear">
                                        <label for="monday_staff" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='monday_start0' class="form-control input-sm no-clear" value="6:00 AM"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='monday_end0' value="7:00 PM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $errors->first('monday', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('tuesday') ? 'has-error' : ''}}">
                    {!! Form::label('tuesday_staff', 'Tuesday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="tuesday" id="tuesday_staff" value="1" checked class="showHours no-clear">
                                        <label for="tuesday_staff" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='tuesday_start0' value="6:00 AM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='tuesday_end0' value="7:00 PM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $errors->first('tuesday', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('wednesday') ? 'has-error' : ''}}">
                    {!! Form::label('wednesday_staff', 'Wednesday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="wednesday" id="wednesday_staff" value="1" checked class="showHours no-clear">
                                        <label for="wednesday_staff" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='wednesday_start0' value="6:00 AM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='wednesday_end0' value="7:00 PM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $errors->first('wednesday', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('thursday') ? 'has-error' : ''}}">
                    {!! Form::label('thursday_staff', 'Thursday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="thursday" id="thursday_staff" value="1" checked class="showHours no-clear">
                                        <label for="thursday_staff" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='thursday_start0' value="6:00 AM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='thursday_end0' value="7:00 PM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $errors->first('thursday', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('friday') ? 'has-error' : ''}}">
                    {!! Form::label('friday_staff', 'Friday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="friday" id="friday_staff" value="1" checked class="showHours no-clear">
                                        <label for="friday_staff" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='friday_start0' value="6:00 AM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='friday_end0' value="7:00 PM" class="form-control input-sm no-clear" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $errors->first('friday', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('saturday') ? 'has-error' : ''}}">
                    {!! Form::label('saturday_staff', 'Saturday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="saturday" id="saturday_staff" value="1" class="showHours no-clear">
                                        <label for="saturday_staff" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='saturday_start0' class="form-control input-sm no-clear" value="6:00 AM" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='saturday_end0' class="form-control input-sm no-clear" value="7:00 PM" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $errors->first('saturday', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('sunday') ? 'has-error' : ''}}">
                    {!! Form::label('sunday_staff', 'Sunday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="sunday" id="sunday_staff" value="1" class="showHours no-clear">
                                        <label for="sunday_staff" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='sunday_start0' class="form-control input-sm no-clear" value="6:00 AM" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='sunday_end0' class="form-control input-sm no-clear" value="7:00 PM" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
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
                        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
                    {!! Form::label('phone', 'Staff Member Phone *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the primary contact detail for this specific staff member"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::tel('phone', null, ['class' => 'form-control numericField', 'required' => 'required', 'maxlength' => '16', 'minlength' => '5']) !!}
                        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row padding-15 padding-bottom-0">
                    <div class="row padding-15 padding-bottom-0">
                        {!! Form::label('fixed_location', 'Fixed Location (Client comes to your venue) or Mobile (You cater for client at their venue) *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the trainer being located at a set location or as a mobile trainer."><i class="fa fa-question-circle"></i></span>
                    </div>
                    <div class="row">
                        <ul class="selectable_fixed_location">
                            <li class="col-xs-6 ui-widget-content ui-selected">Fixed Location</li>
                            <li class="col-xs-6  ui-widget-content">Mobile Selection</li>
                        </ul>

                        <div class="form-group {{ $errors->has('fixed_location') ? 'has-error' : ''}}">
                            <div>
                                {!! Form::text('fixed_location', 'Fixed Location', ['class' => 'form-control fixed_location hide no-clear', 'required' => 'required']) !!}
                                {!! $errors->first('fixed_location', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group notForMobloc {{ $errors->has('address_line_one') ? 'has-error' : ''}}">
                    {!! Form::label('address_line_one', 'Address Line 1 *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('address_line_one', null, ['class' => 'form-control address_line_one', 'required']) !!}
                        {!! $errors->first('address_line_one', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group notForMobloc {{ $errors->has('address_line_two') ? 'has-error' : ''}}">
                    {!! Form::label('address_line_two', 'Address Line 2 *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('address_line_two', null, ['class' => 'form-control address_line_two', 'required']) !!}
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
                        {!! Form::select('country', $country, null, ['class' => 'form-control countries', 'required' => 'required']) !!}
                        {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                    {!! Form::label('state', 'State / Region *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('state', ['' => '-- Select --'], null, ['class' => 'form-control states', 'required' => 'required']) !!}
                        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('postal_code') ? 'has-error' : ''}}">
                    {!! Form::label('postal_code', 'Postal Code *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for the staff member and address for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::tel('postal_code', null, ['class' => 'form-control', 'required' => 'required']) !!}
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
                <div class="form-group {{ $errors->has('disp_location_web') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="disp_location_web" id="disp_location_web_staff" value="1">
                        <label for="disp_location_web_staff">
                            <strong>Display location on the EPIC Trainer Website</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please read terms and conditions"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('disp_location_web', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('disp_location_online') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="disp_location_online" id="disp_location_online_staff" value="1">
                        <label for="disp_location_online_staff">
                            <strong>Clients can view and book this location online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows the clients to view this location online and allows them to book into services for this location"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('disp_location_online', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
            <fieldset class="padding-15">
                <legend>
                    Services & Rates
                </legend>
                <div class="form-group {{ $errors->has('staff_services') ? 'has-error' : ''}}">
                    {!! Form::label('staff_services', 'Staff Services *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('staff_services', $serv, null, ['class' => 'form-control service', 'multiple']) !!}
                        {!! $errors->first('staff_services', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('staffClasses') ? 'has-error' : ''}}">
                    {!! Form::label('staffClasses', 'Staff Classes', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('staffClasses', $clses, null, ['class' => 'form-control', 'multiple']) !!}
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
                                            {!! Form::text('hourly_start_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start Date']) !!}
                                            {!! $errors->first('hourly_start_date', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        <div class="col-xs-6">
                                            {!! Form::text('hourly_end_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'End Date']) !!}
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
                        <div id="collapseTwo" class="panel-collapse collapse">
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
                                    <div>
                                        {!! Form::select('comission_role', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('commission_role', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('commission_income_category') ? 'has-error' : ''}}">
                                    {!! Form::label('commission_income_category', 'Income Category ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('commission_income_category', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('commission_income_category', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('commission_referral_source') ? 'has-error' : ''}}">
                                    {!! Form::label('commission_referral_source', 'Referral Source ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('commission_referral_source', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('commission_referral_source', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('commission_rate_options') ? 'has-error' : ''}}">
                                    {!! Form::label('commission_rate_options', 'Rate Options ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('commission_rate_options', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('commission_rate_options', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('commission_hourly_rate') ? 'has-error' : ''}}">
                                    {!! Form::label('commission_hourly_rate', 'Hourly Rate ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::text('commission_hourly_rate', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('commission_hourly_rate', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('commission_date_range') ? 'has-error' : ''}}">
                                    {!! Form::label('commission_date_range', 'Date Range ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            {!! Form::text('commission_date_range_start', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start']) !!}
                                            {!! $errors->first('commission_date_range_start', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        <div class="col-xs-6">
                                            {!! Form::text('commission_date_range_end', null, ['class' => 'form-control datepicker', 'placeholder' => 'End']) !!}
                                            {!! $errors->first('commission_date_range_end', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('commission_staff_member') ? 'has-error' : ''}}">
                                    {!! Form::label('commission_staff_member', 'Staff Member ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('commission_staff_member', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('commission_staff_member', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
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
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="form-group {{ $errors->has('per_session_payment') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_payment', 'Payment Label ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::text('per_session_payment', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('per_session_payment', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_role') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_role', 'Role ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('per_session_role', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('per_session_role', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_program_type') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_program_type', 'Program Type ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('per_session_program_type', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('per_session_program_type', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_program_category') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_program_category', 'Program Category ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('per_session_program_category', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('per_session_program_category', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_rate_options') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_rate_options', 'Options ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('per_session_rate_options', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('per_session_rate_options', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_hourly_rate') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_hourly_rate', 'Hourly Rate ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::text('per_session_hourly_rate', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('per_session_hourly_rate', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_attendee') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_attendee', 'Remuneration per attendee ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::text('per_session_attendee', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('per_session_attendee', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_date_range') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_date_range', 'Date Range ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            {!! Form::text('per_session_date_range_start', null, ['class' => 'form-control datepicker', 'placeholder' => 'Start']) !!}
                                            {!! $errors->first('per_session_date_range_start', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        <div class="col-xs-6">
                                            {!! Form::text('per_session_date_range_end', null, ['class' => 'form-control datepicker', 'placeholder' => 'End']) !!}
                                            {!! $errors->first('per_session_date_range_end', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('per_session_staff_member') ? 'has-error' : ''}}">
                                    {!! Form::label('per_session_staff_member', 'Staff Member ', ['class' => 'strong']) !!}
                                    <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                                    <div>
                                        {!! Form::select('per_session_staff_member', ['select 1' => 'Select 1', 'select 2' => 'Select 2'], null, ['class' => 'form-control', 'multiple']) !!}
                                        {!! $errors->first('per_session_staff_member', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
            <button type="button" class="btn btn-primary pull-right btn-add-more-form">
                Add Staff
            </button>
            <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                Cancel
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop()