@if(!isset($businessId))
    {!! Form::open(['url' => 'settings/location', 'id' => 'form-2', 'class' => 'margin-bottom-30 location form-2', 'data-form-mode' => 'unison']) !!}
    {!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}
    <div class="row">
        <div class="col-xs-12">
            <p class="margin-top-5 italic">This is a brief summary of the location of your venue or venues.</p>
        </div>
    </div>
    <div class="row margin-top-90">
@else
    @if(isset($location))
        {!! Form::model($location, ['method' => 'patch', 'route' => ['locations.update', $location->id], 'id' => 'form-2', 'class' => 'margin-bottom-30 location form-2', 'data-form-mode' => 'standAlone']) !!}
    @elseif(isset($area))
        {!! Form::model($area, ['method' => 'patch', 'route' => ['areas.update', $area->la_id], 'id' => 'form-2', 'class' => 'margin-bottom-30 location form-2', 'data-form-mode' => 'standAlone']) !!}
    @elseif(isset($entityType))
        @if($entityType == 'location')
            {!! Form::open(['route' => ['locations.store'], 'id' => 'form-2', 'class' => 'margin-bottom-30 location form-2', 'data-form-mode' => 'standAlone']) !!}
        @elseif($entityType == 'area')
            {!! Form::open(['route' => ['areas.store'], 'id' => 'form-2', 'class' => 'margin-bottom-30 location form-2', 'data-form-mode' => 'standAlone']) !!}
        @endif
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
    <div class="row">
@endif
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
                        @if(!isset($businessId) || isset($location) || (isset($entityType) && $entityType == 'location'))
                            <li class="col-xs-6 ui-widget-content ui-selected">Location</li>
                        @endif
                        @if(!isset($businessId) || isset($area) || (isset($entityType) && $entityType == 'area'))
                            <li class="col-xs-6 ui-widget-content {{ isset($area) || (isset($entityType) && $entityType == 'area')?'ui-selected':'' }}">Area</li>
                        @endif
                    </ul>
                    <div class="form-group {{ $errors->has('venue') ? 'has-error' : ''}}">
                        <div>
                            {!! Form::text('venue', null, ['class' => 'form-control hide no-clear', 'id' => 'select-result', 'required' => 'required']) !!}
                            {!! $errors->first('venue', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            @if(!isset($businessId) || isset($area) || (isset($entityType) && $entityType == 'area'))
            <div class="form-group notForLoc {{ $errors->has('location') ? 'has-error' : ''}}">
                {!! Form::label('location', 'Location *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This relates to either the location of the training area or if it is a training area within a location add the location first through add a location button"><i class="fa fa-question-circle"></i></span>
                <div>
                
                <?php $selectedVal='';
                    if(isset($area))
                        $selectedVal=$area->la_location_id;
                    elseif(isset($location_id))
                        $selectedVal=$location_id;
                    else
                        $selectedVal=null;
                   /* echo ((isset($area))?$area->la_location_id : (isset($location_id))?$location_id : null)*/
                ?>
             
                    {!! Form::select('location', (isset($entityType) && $entityType == 'area') || isset($area)?$locs:[], $selectedVal, ['class' => 'form-control location', 'required'=>'required']) !!}
                    {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            @endif

            @if(!isset($businessId) || isset($location) || (isset($entityType) && $entityType == 'location'))
            <div class="form-group notForArea {{ $errors->has('location_training_area') ? 'has-error' : ''}}">
                {!! Form::label('location_training_area', 'Location / Training Area Name *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This does not have to be the same as the Business Name and relates to a revenue generating area that may be either another venue or another training area within a specific venue."><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('location_training_area', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('location_training_area', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            @endif

            @if(!isset($businessId) || isset($area) || (isset($entityType) && $entityType == 'area'))
            <div class="form-group notForLoc {{ $errors->has('areaName') ? 'has-error' : ''}}">
                {!! Form::label('areaName', 'Location / Training Area Name *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This does not have to be the same as the Business Name and relates to a revenue generating area that may be either another venue or another training area within a specific venue."><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('areaName', isset($area)?$area->la_name:null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('areaName', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            @endif

            @if(!isset($businessId) || isset($location) || (isset($entityType) && $entityType == 'location'))
            <div class="form-group upload-group notForArea">
                {!! Form::label(null, 'Upload Location Logo', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This logo will be displayed on all this specific locations correspondence to staff and clients"><i class="fa fa-question-circle"></i></span>
                <input type="hidden" name="prePhotoName" value="{{ isset($location)?$location->logo:'' }}" class="no-clear">
                <input type="hidden" name="entityId" value="" class="no-clear">
                <input type="hidden" name="saveUrl" value="" class="no-clear">
                <input type="hidden" name="photoHelper" value="locationLogo" class="no-clear">
                <input type="hidden" name="cropSelector" value="">
                <div>
                    <label class="btn btn-primary btn-file">
                        <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                    </label>
                    <div class="m-t-10">
                        @if(isset($location) && $location->logo)
                        <img class="locationLogoPreviewPics previewPics removePic" src="{{ dpSrc($location->logo) }}" />
                        <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($location)?$location->id:'' }}" data-entity="location">Remove</span>
                        @else
                        <img class="hidden locationLogoPreviewPics previewPics removePic" />
                        <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($location)?$location->id:'' }}" data-entity="location">Remove</span>
                        @endif
                    </div>
                </div>
                <input class="photoName" type="hidden" name="locationLogo" value="{{ isset($location) && $location->logo?$location->logo:'' }}">
            </div>
            @endif

            @if(!isset($businessId) || isset($area) || (isset($entityType) && $entityType == 'area'))
            <div class="form-group upload-group notForLoc">
                {!! Form::label(null, 'Upload Area Logo', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This logo will be displayed on all this specific locations correspondence to staff and clients"><i class="fa fa-question-circle"></i></span>
                <input type="hidden" name="prePhotoName" value="{{ isset($area)?$area->la_logo:'' }}" class="no-clear">
                <input type="hidden" name="entityId" value="" class="no-clear">
                <input type="hidden" name="saveUrl" value="" class="no-clear">
                <input type="hidden" name="photoHelper" value="areaLogo" class="no-clear">
                <input type="hidden" name="cropSelector" value="">
                <div>
                    <label class="btn btn-primary btn-file">
                        <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                    </label>
                    <div class="m-t-10">
                        @if(isset($area) && $area->la_logo)
                        <img class="areaLogoPreviewPics previewPics removePic" src="{{ dpSrc($area->la_logo) }}" />
                        <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($area)?$area->la_id:'' }}" data-entity="area">Remove</span>
                        @else
                        <img class="hidden areaLogoPreviewPics previewPics removePic" />
                        <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($area)?$area->la_id:'' }}" data-entity="area">Remove</span>
                        @endif
                    </div>
                </div>
                <input class="photoName" type="hidden" name="areaLogo" value="{{ isset($area) && $area->la_logo?$area->la_logo:'' }}">
            </div>

            <div class="form-group notForLoc {{ $errors->has('stuff_selection') ? 'has-error' : ''}}">
                {!! Form::label('stuff_selection', 'Staff *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="The connects staff and trainers to specific venues and training area"><i class="fa fa-question-circle"></i></span>
                @if(!isset($subview))
                    <a href="#" class="pull-right callSubview" data-target-subview="staff">+ Add New Staff</a>
                @endif
                <div>
                    {!! Form::select('stuff_selection', (isset($entityType) && $entityType == 'area') || isset($area)?$stff:[], isset($area) && count($aresStaffs)?$aresStaffs:null, ['class' => 'form-control staff', 'required'=>'required', 'multiple']) !!}
                    {!! $errors->first('stuff_selection', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            @endif
        </fieldset>

        @if(isset($location))
            <fieldset class="padding-15 workingHrs" data-old-hours="{{ count($location->hours)?$location->hours:'' }}">
        @elseif(isset($area))
            <fieldset class="padding-15 workingHrs" data-old-hours="{{ count($area->hours)?$area->hours:'' }}">
        @elseif(isset($locationHour))
            <fieldset class="padding-15 workingHrs" data-old-hours="{{ count($locationHour)?json_encode($locationHour):'' }}">
        @else
            <fieldset class="padding-15">    
        @endif
            <legend>
                Hours *
                <span class="epic-tooltip" data-toggle="tooltip" title="Choose the operating times of this location or training area"><i class="fa fa-question-circle"></i></span>
            </legend>
            <div class="form-group {{ $errors->has('monday') ? 'has-error' : ''}}" data-day="monday">
            <div class="row m-b-10">
                <div class="col-xs-8">
                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                        <input type="checkbox" name="monday" id="monday_loc" value="1" checked class="showHours no-clear">
                        <label for="monday_loc" class="m-r-0"><strong>Monday</strong></label>
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
                            <input type="text" name="monday_start0" class="form-control input-sm start-day no-clear timepicker1" data-default-time="6:00 AM" ><!-- -->
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
                        <input type="checkbox" name="tuesday" id="tuesday_loc" value="1" checked class="showHours no-clear">
                        <label for="tuesday_loc" class="m-r-0"><strong>Tuesday</strong></label>
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
                        <input type="checkbox" name="wednesday" id="wednesday_loc" value="1" checked class="showHours no-clear">
                        <label for="wednesday_loc" class="m-r-0"><strong>Wednesday</strong></label>
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
                        <input type="checkbox" name="thursday" id="thursday_loc" value="1" checked class="showHours no-clear">
                        <label for="thursday_loc" class="m-r-0"><strong>Thursday</strong></label>
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
                        <input type="checkbox" name="friday" id="friday_loc" value="1" checked class="showHours no-clear">
                        <label for="friday_loc" class="m-r-0"><strong>Friday</strong></label>
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
                        <input type="checkbox" name="saturday" id="saturday_loc" value="1" checked class="showHours no-clear">
                        <label for="saturday_loc" class="m-r-0"><strong>Saturday</strong></label>
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
                        <input type="checkbox" name="sunday" id="sunday_loc" value="1" checked class="showHours no-clear">
                        <label for="sunday_loc" class="m-r-0"><strong>Sunday</strong></label>
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
        @if(!isset($businessId) || isset($location) || (isset($entityType) && $entityType == 'location'))
        <fieldset class="padding-15 notForArea">
            <legend>
                Contact
            </legend>
            <div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
                {!! Form::label('website', 'Location Website Address ', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the main website for this specific location"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('website', null, ['class' => 'form-control customValField']) !!}
                    <span class="help-block m-b-0"></span>
                    {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('facebook') ? 'has-error' : ''}}">
                {!! Form::label('facebook', 'Location Facebook Page ', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the main Facebook page for this specific location"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('facebook', null, ['class' => 'form-control customValField']) !!}
                    <span class="help-block m-b-0"></span>
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
                    {!! Form::tel('phone', null, ['class' => 'form-control countryCode numericField', 'required' => 'required', 'maxlength' => '16', 'minlength' => '5']) !!}
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
                        @if(isset($location))
                            <li class="col-xs-6 ui-widget-content {{ $location->fixed_location?'ui-selected':'' }}">Fixed Location</li>
                            <li class="col-xs-6 ui-widget-content {{ !$location->fixed_location?'ui-selected':'' }}">Mobile Selection</li>
                        @else
                            <li class="col-xs-6 ui-widget-content ui-selected">Fixed Location</li>
                            <li class="col-xs-6 ui-widget-content">Mobile Selection</li>
                        @endif
                    </ul>
                    <div class="form-group {{ $errors->has('fixed_location') ? 'has-error' : ''}}">
                        <div>
                            @if(isset($location))
                            {!! Form::text('fixed_location', $location->fixed_location?'Fixed Location':'Mobile Selection', ['class' => 'form-control fixed_location hide no-clear', 'required' => 'required']) !!}
                            @else
                            {!! Form::text('fixed_location', 'Fixed Location', ['class' => 'form-control fixed_location hide no-clear', 'required' => 'required']) !!}
                            @endif
                            {!! $errors->first('fixed_location', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group notForMobloc {{ isset($location) && !$location->fixed_location?'remove':'' }} {{ $errors->has('address_line_one') ? 'has-error' : ''}}">            
                {!! Form::label('address_line_one', 'Address Line 1 *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('address_line_one', isset($bussAddr['address_line_one'])?$bussAddr['address_line_one']:null, ['class' => 'form-control address_line_one', 'required' => 'required','id' => 'autocomplete','onFocus' => 'geolocate()','autocomplete' => 'off']) !!}
                    {!! $errors->first('address_line_one', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group notForMobloc {{ isset($location) && !$location->fixed_location?'remove':'' }} {{ $errors->has('address_line_two') ? 'has-error' : ''}}">
                {!! Form::label('address_line_two', 'Address Line 2 *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('address_line_two', isset($bussAddr['address_line_two'])?$bussAddr['address_line_two']:null, ['class' => 'form-control address_line_two', 'required' => 'required']) !!}
                    {!! $errors->first('address_line_two', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('city') ? 'has-error' : ''}}">
                {!! Form::label('city', 'City *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('city', isset($bussAddr['city'])?$bussAddr['city']:null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
                {!! Form::label('country', 'Country *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('country', $country, isset($bussAddr['country'])?$bussAddr['country']:null, ['class' => 'form-control countries', 'required' => 'required']) !!}
                    {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                {!! Form::label('state', 'State / Region *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    @if(isset($location))
                        {!! Form::select('state', ['' => '-- Select --'], null, ['class' => 'form-control states', 'required' => 'required', 'data-selected' => $location->state]) !!}
                    @else
                        {!! Form::select('state', ['' => '-- Select --'], null, ['class' => 'form-control states', 'required' => 'required', 'data-selected' => isset($bussAddr['state'])?$bussAddr['state']:'']) !!}
                    @endif
                    {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('postal_code') ? 'has-error' : ''}}">
                {!! Form::label('postal_code', 'Postal Code *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical address for location for clients to attend and for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('postal_code', isset($bussAddr['postal_code'])?$bussAddr['postal_code']:null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('postal_code', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('time_zone') ? 'has-error' : ''}}">
                {!! Form::label('time_zone', 'Time Zone *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="Time zones are important for numerous reasons including client communication and reminders"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('time_zone', $time_zone, isset($bussAddr['time_zone'])?$bussAddr['time_zone']:null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('time_zone', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('disp_location_web') ? 'has-error' : ''}}">
                <div class="checkbox clip-check check-primary m-b-0">
                    <input type="checkbox" name="disp_location_web" id="disp_location_web_loc" value="1" {{ isset($location) && $location->disp_location_web?'checked':'' }}>
                    <label for="disp_location_web_loc">
                        <strong>Display location on the EPIC Trainer Website</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please read terms and conditions"><i class="fa fa-question-circle"></i></span>
                    </label>
                    {!! $errors->first('disp_location_web', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('disp_location_online') ? 'has-error' : ''}}">
                <div class="checkbox clip-check check-primary m-b-0">
                    <input type="checkbox" name="disp_location_online" id="disp_location_online_loc" value="1" {{ isset($location) && $location->disp_location_online?'checked':'' }}>
                    <label for="disp_location_online_loc">
                        <strong>Clients can view and book this location online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
                    </label>
                    {!! $errors->first('disp_location_online', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <!--<div class="input-group bootstrap-timepicker timepicker">
                <input class="timepicker1" type="text" class="form-control input-small" >
                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
            </div>-->
        </fieldset>
        @endif
    </div>
</div>
@if(!isset($businessId))
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <button class="btn btn-primary btn-o back-step btn-wide pull-left">
                    <i class="fa fa-circle-arrow-left"></i> Back
                </button>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <button class="btn btn-primary btn-o next-step btn-wide pull-right">
                    Next <i class="fa fa-arrow-circle-right"></i>
                </button>
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 btn-add-more-form hide">
                    <i class="fa fa-plus"></i> <span>Add Location</span>
                </button>
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 skipnextbutton skipbutton hidden">
                    Skip to next
                </button>
                @if(isset($subview))
                    <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                        Close
                    </button>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-primary btn-wide pull-right btn-add-more-form">
                    @if(isset($location))
                        <i class="fa fa-edit"></i> Update Location
                    @elseif(isset($area))
                        <i class="fa fa-edit"></i> Update Area
                    @elseif(isset($entityType))
                        @if($entityType == 'location')
                            <i class="fa fa-plus"></i> Add Location
                        @elseif($entityType == 'area')
                            <i class="fa fa-plus"></i> Add Area
                        @endif
                    @endif
                </button>
                @if(isset($subview))
                    <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                        Close
                    </button>
                @endif
            </div>
        </div>
    </div>
@endif
{!! Form::close() !!}