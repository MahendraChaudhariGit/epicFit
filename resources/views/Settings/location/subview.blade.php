<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD --><head>
    <title>@yield('title', app_name())</title>
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <meta name="_token" content="{{ csrf_token() }}" />
    <meta name="public_url" content="{{ asset('') }}">
    <!-- end: META -->

    <!-- start: GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <!-- end: GOOGLE FONTS -->

    <!-- start: MAIN CSS -->
    {!! Html::style('vendor/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('vendor/fontawesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/fonts/style.css') !!}
    {!! Html::style('vendor/themify-icons/themify-icons.min.css') !!}
    {!! Html::style('vendor/animate.css/animate.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/switchery/switchery.min.css', ['media' => 'screen']) !!}
    {!! Html::style('vendor/tooltipster-master/tooltipster.css') !!}
    {!! Html::style('vendor/jquery-ui/jquery-ui-1.10.1.custom.css') !!}
    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    {!! Html::style('assets/css/styles-orange.css?v='.time()) !!}
    {!! Html::style('assets/css/clip-two/main-navigation.css') !!}
    {!! Html::style('assets/css/plugins.css') !!}
    {!! Html::style('assets/css/themes/theme-orange.css') !!}
    <!-- end: CLIP-TWO CSS -->
    
    {!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
    {!! Html::style('assets/css/custom.css?v='.time()) !!}

    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: Bootstrap Select Master -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Bootstrap datepicker -->
    {!! Html::style('vendor/bootstrap-datepicker/bootstrap-datepicker3.min.css') !!}
    <!-- end: Bootstrap datepicker -->

    <!-- start: Bootstrap datetimepicker -->
    {!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
    <!-- end: Bootstrap datetimepicker -->

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

    {!! Html::script('vendor/jquery/jquery.min.js') !!}
    {!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}
</head>
<!-- end: HEAD -->
<body>
<!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->

<div class="swMain" id="step-2">
    {!! Form::open(['url' => 'settings/location', 'id' => 'form-2', 'class' => 'margin-bottom-30 location form-2', 'data-is-subview' => true]) !!}
    {!! Form::hidden('businessId', null, ['class' => 'businessId']) !!}
    <div class="row">
        <div class="sucMes hidden"></div>
        <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    General
                </legend>
                <div class="row padding-15 padding-bottom-0">
                    <div class="row padding-15 padding-bottom-0">
                        {!! Form::label('venue', 'What type of location or training area *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates specifically to the training area, this may either be a location or a training area within a specific location if it has more that one training area in a location"><i class="fa fa-question-circle"></i></span>
                    </div>
                    <div class="row">
                        <ul class="selectable">
                            <li class="col-xs-6 ui-widget-content ui-selected">Location</li>
                            <li class="col-xs-6  ui-widget-content">Area</li>
                        </ul>
                        <div class="form-group {{ $errors->has('venue') ? 'has-error' : ''}}">
                            <div>
                                {!! Form::text('venue', null, ['class' => 'form-control hide', 'id' => 'select-result', 'required' => 'required']) !!}
                                {!! $errors->first('venue', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group notForLoc {{ $errors->has('location') ? 'has-error' : ''}}">
                    {!! Form::label('location', 'Location *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This relates to either the location of the training area or if it is a training area within a location add the location first through add a location button"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('location', $locs, null, ['class' => 'form-control location', 'required'=>'required']) !!}
                        {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('location_training_area') ? 'has-error' : ''}}">
                    {!! Form::label('location_training_area', 'Location / Training Area Name *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This does not have to be the same as the Business Name and relates to a revenue generating area that may be either another venue or another training area within a specific venue."><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('location_training_area', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('location_training_area', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>                                

                <div class="form-group upload-group notForArea">
                    {!! Form::label(null, 'Upload Location Logo', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This logo will be displayed on all this specific locations correspondence to staff and clients"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="locationLogo" class="no-clear">
                    <div>
                        <label class="btn btn-orange btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            <img class="hidden locationLogoPreviewPics previewPics" />
                        </div>
                    </div>
                    <input type="hidden" name="locationLogo">
                </div>

                <div class="form-group upload-group notForLoc">
                    {!! Form::label(null, 'Upload Area Logo', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This logo will be displayed on all this specific locations correspondence to staff and clients"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="areaLogo" class="no-clear">
                    <div>
                        <label class="btn btn-orange btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            <img class="hidden areaLogoPreviewPics previewPics" />
                        </div>
                    </div>
                    <input type="hidden" name="areaLogo">
                </div>

                <div class="form-group notForLoc {{ $errors->has('stuff_selection') ? 'has-error' : ''}}">
                    {!! Form::label('stuff_selection', 'Staff *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="The connects staff and trainers to specific venues and training area"><i class="fa fa-question-circle"></i></span>
                    <!--<a href="#" data-target-form="add-new-staff"  class="pull-right btn-add-new-form">+ Staff</a>-->
                    <a href="#" class="pull-right callSubview" data-target-subview="">+ Add New Staff</a>
                    <div>
                        {!! Form::select('stuff_selection', $stff, null, ['class' => 'form-control staff', 'required'=>'required', 'multiple']) !!}
                        {!! $errors->first('stuff_selection', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
            <fieldset class="padding-15">
                <legend>
                    Hours *
                    <span class="epic-tooltip" data-toggle="tooltip" title="Choose the operating times of this location or training area"><i class="fa fa-question-circle"></i></span>
                </legend>
                <div class="form-group {{ $errors->has('monday') ? 'has-error' : ''}}">
                    {!! Form::label('monday_loc', 'Monday ', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="monday" id="monday_loc" value="1" checked class="showHours">
                                        <label for="monday_loc" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='monday_start0' class="form-control input-sm" value="6:00 AM"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='monday_end0' value="7:00 PM" class="form-control input-sm" />
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
                    {!! Form::label('tuesday_loc', 'Tuesday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="tuesday" id="tuesday_loc" value="1" checked class="showHours">
                                        <label for="tuesday_loc" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='tuesday_start0' value="6:00 AM" class="form-control input-sm" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='tuesday_end0' value="7:00 PM" class="form-control input-sm" />
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
                    {!! Form::label('wednesday_loc', 'Wednesday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="wednesday" id="wednesday_loc" value="1" checked class="showHours">
                                        <label for="wednesday_loc" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='wednesday_start0' value="6:00 AM" class="form-control input-sm" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='wednesday_end0' value="7:00 PM" class="form-control input-sm" />
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
                    {!! Form::label('thursday_loc', 'Thursday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="thursday" id="thursday_loc" value="1" checked class="showHours">
                                        <label for="thursday_loc" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='thursday_start0' value="6:00 AM" class="form-control input-sm" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='thursday_end0' value="7:00 PM" class="form-control input-sm" />
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
                    {!! Form::label('friday_loc', 'Friday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="friday" id="friday_loc" value="1" checked class="showHours">
                                        <label for="friday_loc" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='friday_start0' value="6:00 AM" class="form-control input-sm" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='friday_end0' value="7:00 PM" class="form-control input-sm" />
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
                    {!! Form::label('saturday_loc', 'Saturday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="saturday" id="saturday_loc" value="1" class="showHours">
                                        <label for="saturday_loc" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='saturday_start0' class="form-control input-sm" value="6:00 AM" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='saturday_end0' class="form-control input-sm" value="7:00 PM" />
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
                    {!! Form::label('sunday_loc', 'Sunday', ['class' => 'strong']) !!}
                    <div class="clearfix">
                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
                        <div class="row margin-top-5">
                            <div class="col-xs-9 no-padding">
                                <div class="col-xs-2">
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="sunday" id="sunday_loc" value="1" class="showHours">
                                        <label for="sunday_loc" class="m-r-0"></label>
                                    </div>
                                </div>
                                <div class="col-xs-10 no-padding notWork">
                                    Not working on this day
                                </div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker' id=''>
                                        <input type='text' name='sunday_start0' class="form-control input-sm" value="6:00 AM" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                <div class="col-xs-4 no-padding showHoursElem">
                                    <div class='input-group date datetimepicker'>
                                        <input type='text' name='sunday_end0' class="form-control input-sm" value="7:00 PM" />
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
            <fieldset class="padding-15 notForArea">
                <legend>
                    Contact
                </legend>
                <div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
                    {!! Form::label('website', 'Location Website Address ', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the main website for this specific location"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('website', null, ['class' => 'form-control']) !!}
                        {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('facebook') ? 'has-error' : ''}}">
                    {!! Form::label('facebook', 'Location Facebook Page ', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the main Facebook page for this specific location"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('facebook', null, ['class' => 'form-control']) !!}
                        {!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email', 'Location Email *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This email is the default for outgoing email correspondence and promotional materials for this location"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
                    {!! Form::label('phone', 'Location Phone *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the primary contact detail for this specific location"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::tel('phone', null, ['class' => 'form-control numericField', 'required' => 'required', 'maxlength' => '16', 'minlength' => '5']) !!}
                        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="row padding-15 padding-bottom-0">
                    <div class="row padding-15 padding-bottom-0">
                        {!! Form::label('fixed_location', 'Fixed Location (Client comes to your venue) or Mobile ( You cater for client at their venue) *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the location of training, if the trainer is mobile or an online trainer they may not have a fixed location"><i class="fa fa-question-circle"></i></span>
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
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('address_line_one', isset($savedData['address_line_one'])?$savedData['address_line_one']:null, ['class' => 'form-control address_line_one', 'required' => 'required']) !!}
                        {!! $errors->first('address_line_one', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group notForMobloc {{ $errors->has('address_line_two') ? 'has-error' : ''}}">
                    {!! Form::label('address_line_two', 'Address Line 2 *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('address_line_two', isset($savedData['address_line_two'])?$savedData['address_line_two']:null, ['class' => 'form-control address_line_two', 'required' => 'required']) !!}
                        {!! $errors->first('address_line_two', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('city') ? 'has-error' : ''}}">
                    {!! Form::label('city', 'City *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('city', isset($savedData['city'])?$savedData['city']:null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
                    {!! Form::label('country', 'Country *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('country', $country, isset($savedData['country'])?$savedData['country']:null, ['class' => 'form-control countries', 'required' => 'required']) !!}
                        {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                    {!! Form::label('state', 'State / Region *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('state', ['' => '-- Select --'], null, ['class' => 'form-control states', 'required' => 'required', 'data-selected' => isset($savedData['state'])?$savedData['state']:'']) !!}
                        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('postal_code') ? 'has-error' : ''}}">
                    {!! Form::label('postal_code', 'Postal Code *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::tel('postal_code', isset($savedData['postal_code'])?$savedData['postal_code']:null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('postal_code', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('time_zone') ? 'has-error' : ''}}">
                    {!! Form::label('time_zone', 'Time Zone *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="Time zones are important for numerous reasons including client communication and reminders"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('time_zone', $time_zone, null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('time_zone', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('disp_location_web') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="disp_location_web" id="disp_location_web_loc" value="1">
                        <label for="disp_location_web_loc">
                            <strong>Display location on the EPIC Trainer Website</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please read terms and conditions"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('disp_location_web', '<p class="help-block">:message</p>') !!}
                    </div>
                    
                </div>
            
                <div class="form-group {{ $errors->has('disp_location_online') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="disp_location_online" id="disp_location_online_loc" value="1">
                        <label for="disp_location_online_loc">
                            <strong>Clients can view and book this location online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('disp_location_online', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
            <button class="btn btn-primary pull-right btn-add-more-form" type="submit">Submit</button>
            <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">Cancel</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<!-- start: MAIN JAVASCRIPTS -->
{!! Html::script('vendor/bootstrap/js/tethr.js') !!}
{!! Html::script('vendor/bootstrap/js/bootstrap.js') !!}
{!! Html::script('vendor/modernizr/modernizr.js') !!}
{!! Html::script('vendor/jquery-cookie/jquery.cookie.js') !!}
{!! Html::script('vendor/perfect-scrollbar/perfect-scrollbar.min.js') !!}
{!! Html::script('vendor/switchery/switchery.min.js') !!}
{!! Html::script('vendor/selectFx/classie.js') !!}
{!! Html::script('vendor/selectFx/selectFx.js') !!}
<!-- end: MAIN JAVASCRIPTS -->

<script>
/**
* 01.01 : Initial configuration for sending ajax
*/
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
	}
});
</script>

<!-- start: CLIP-TWO JAVASCRIPTS -->
{!! Html::script('assets/js/main.js?v='.time()) !!}
<!-- start: CLIP-TWO JAVASCRIPTS -->

<!-- start: JavaScript required for this page -->
{!! Html::script('vendor/moment/moment.min.js?v='.time()) !!}
{!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
{!! Html::script('vendor/jquery-smart-wizard/jquery.smartWizard.js?v='.time()) !!}
{!! Html::script('assets/js/form-wizard.js?v='.time()) !!}
{!! Html::script('vendor/jquery-ui/jquery-ui.min.js?v='.time()) !!}

<!-- start: Bootstrap datepicker -->
{!! Html::script('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}
<!-- end: Bootstrap datepicker -->

<!-- start: Bootstrap timepicker -->
{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
<!-- end: Bootstrap timepicker -->

<!-- start: Bootstrap daterangepicker -->
{!! Html::script('assets/plugins/bootstrap-daterangepicker/daterangepicker.js?v='.time()) !!}
 <!-- end: Bootstrap daterangepicker -->

<!-- start: Bootstrap Select Master -->
{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Country Code Selector -->
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
<!-- end: Country Code Selector -->

<!-- start: Bootstrap Typeahead -->
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
<!-- end: Bootstrap Typeahead -->

<!-- start: JCrop -->
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
<!-- end: JCrop -->

{!! Html::script('assets/js/business.js?v='.time()) !!}
<!-- end: JavaScript required for this page -->

<!-- start: JavaScript Event Handlers for this page -->
<script>
    jQuery(document).ready(function() {
        Main.init();
    });
</script>
<!-- end: JavaScript Event Handlers for this page -->
</body>
</html>