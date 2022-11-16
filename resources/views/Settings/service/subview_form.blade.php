@extends('Settings.subview')

@section('content')
<div class="swMain scrollToTop" id="step-4">
    {!! Form::open(['url' => 'settings/service', 'id' => 'form-4', 'class' => 'margin-bottom-30', 'data-is-subview' => true]) !!}
    {!! Form::hidden('businessId', $businessId, ['class' => 'businessId no-clear']) !!}
    {!! Form::hidden('catText', 'TEAM Training') !!}

    <div class="row">
        <div class="sucMes hidden"></div>
        <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    General
                </legend>
                <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                    {!! Form::label('type', 'Type *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the type of service that you offer if you have multiple fields such as Training, Nutrition, Coaching etc..."><i class="fa fa-question-circle"></i></span>
                    <a href="#" class="pull-right btn-add-more">+ Add New Type</a>
                    {!! Form::hidden('btn-add-more-action', 'serviceType', ['class' => 'no-clear']) !!}
                    <div>
                        {!! Form::select('type', $serviceTypes, null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('category') ? 'has-error' : ''}}">
                    {!! Form::label('category', 'Category *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the category below the service type"><i class="fa fa-question-circle"></i></span>
                    <a href="#" class="pull-right btn-add-more">+ Add New Category</a>
                    {!! Form::hidden('btn-add-more-action', 'serviceCat', ['class' => 'no-clear']) !!}
                    <div>
                        {!! Form::select('category', $serviceCats, null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('category', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('location') ? 'has-error' : ''}}">
                    {!! Form::label('location', 'Location *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the location that these specific services are offered"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('location', $locs, null, ['class' => 'form-control', 'required'=>'required']) !!}
                        {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('area', 'Area', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the areas that these specific services are offered"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('area', ['' => '-- Select --'], null, ['class' => 'form-control area', 'multiple']) !!}
                        {!! $errors->first('area', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
            <fieldset class="padding-15" id="oneOnOne">
                <legend>
                    1 On 1
                </legend>
                <div class="form-group {{ $errors->has('one_on_one_name') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_name', 'Service Name *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the name of the specific service offered"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('one_on_one_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_description') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_description', 'Service Description *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This describes the service and what the service offers"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::textarea('one_on_one_description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_description', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group upload-group">
                    {!! Form::label(null, 'Trainers profile picture', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for a identification logo to be inputted allowing it to be displayed on the calendar"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="oneOnoneLogo" class="no-clear">
                    <div>
                        <label class="btn btn-orange btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            <img class="hidden oneOnoneLogoPreviewPics previewPics" />
                        </div>
                    </div>
                    <input type="hidden" name="oneOnoneLogo">
                </div>
                <div class="form-group {{ $errors->has('one_on_one_colour') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_colour', 'Service Colour *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the colour customization to easily identify services on the calendar"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::color('one_on_one_colour', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_colour', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_call_client_online') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="one_on_one_call_client_online" id="one_on_one_call_client_online" value="1">
                        <label for="one_on_one_call_client_online">
                            <strong>Can client book this service online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows clients to book this trainer and services from the online hub"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('one_on_one_call_client_online', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_duration') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_duration', 'Duration *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This indicates the time that is allocated to the specific service"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('one_on_one_duration', ['' => '-- Select --', '5 min' => '5 mins', '10 min' => '10 min', '15 min' => '15 min', '20 min' => '20 min', '25 min' => '25 min', '30 min' => '30 min', '35 min' => '35 min', '40 min' => '40 min', '45 min' => '45 min', '50 min' => '50 min', '55 min' => '55 min', '60 min' => '60 min'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_duration', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_capacity') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_capacity', 'Capacity *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This limits the amount of clients who are able to partake in this service at one time"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('one_on_one_capacity', null, ['class' => 'form-control numericField', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_capacity', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_staffs') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_staffs', 'Staff Selection', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This displays a list of trainers or staff that are able to deliver the service"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('one_on_one_staffs', $stff, null, ['class' => 'form-control', 'multiple']) !!}
                        {!! $errors->first('one_on_one_staffs', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                        <input type="checkbox" id="one_on_one_staffs_all" class="selAllDd">
                        <label for="one_on_one_staffs_all" class="no-error-label">
                            <strong>Select All</strong>
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_price') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_price', 'Price *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the price charged for the service"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::tel('one_on_one_price', null, ['class' => 'form-control numericField', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_price', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_tax') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_tax', 'Tax *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the price to be either inclusive or exclusive of tax"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('one_on_one_tax', ['' => '-- Select --', 'Including' => 'Including', 'Excluding' => 'Excluding', 'N/A' => 'N/A'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_tax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="padding-15" id="team">
                <legend>
                    T.E.A.M &nbsp;&nbsp;&nbsp;&nbsp;
                </legend>
                <div class="form-group {{ $errors->has('team_name') ? 'has-error' : ''}}">
                    {!! Form::label('team_name', 'Service Name *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the name of the specific service offered"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('team_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_description') ? 'has-error' : ''}}">
                    {!! Form::label('team_description', 'Service Description *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This describes the service and what the service offers"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::textarea('team_description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_description', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group upload-group">
                    {!! Form::label(null, 'Upload T.E.A.M Training Logo', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for a identification logo to be inputted allowing it to be displayed on the calendar"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="teamLogo" class="no-clear">
                    <div>
                        <label class="btn btn-orange btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            <img class="hidden teamLogoPreviewPics previewPics" />
                        </div>
                    </div>
                    <input type="hidden" name="teamLogo">
                </div>
                <div class="form-group {{ $errors->has('team_colour') ? 'has-error' : ''}}">
                    {!! Form::label('team_colour', 'Service Colour *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the colour customization to easily identify services on the calendar"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::color('team_colour', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_colour', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_can_book_online') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="team_can_book_online" id="team_can_book_online" value="1">
                        <label for="team_can_book_online">
                            <strong>Can client book this service online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows clients to book this trainer and services from the online hub"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('team_can_book_online', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_duration') ? 'has-error' : ''}}">
                    {!! Form::label('team_duration', 'Duration *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This indicates the time that is allocated to the specific service"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('team_duration', ['' => '-- Select --', '5 min' => '5 min', '10 min' => '10 min', '15 min' => '15 min', '20 min' => '20 min', '25 min' => '25 min', '30 min' => '30 min', '35 min' => '35 min', '40 min' => '40 min', '45 min' => '45 min', '50 min' => '50 min', '55 min' => '55 min', '60 min' => '60 min'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_duration', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_capacity') ? 'has-error' : ''}}">
                    {!! Form::label('team_capacity', 'Capacity *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This limits the amount of clients who are able to partake in this service at one time"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('team_capacity', null, ['class' => 'form-control numericField', 'required' => 'required']) !!}
                        {!! $errors->first('team_capacity', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_staffs') ? 'has-error' : ''}}">
                    {!! Form::label('team_staffs', 'Staff Selection', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This displays a list of trainers or staff that are able to deliver the service"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('team_staffs', $stff, null, ['class' => 'form-control', 'multiple']) !!}
                        {!! $errors->first('team_staffs', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                        <input type="checkbox" id="team_staffs_all" class="selAllDd">
                        <label for="team_staffs_all" class="no-error-label">
                            <strong>Select All</strong>
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_price') ? 'has-error' : ''}}">
                    {!! Form::label('team_price', 'Price *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the price charged for the service"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::tel('team_price', null, ['class' => 'form-control numericField', 'required' => 'required']) !!}
                        {!! $errors->first('team_price', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_tax') ? 'has-error' : ''}}">
                    {!! Form::label('team_tax', 'Tax *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the price to be either inclusive or exclusive of tax"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('team_tax', ['' => '-- Select --', 'Including' => 'Including', 'Excluding' => 'Excluding', 'N/A' => 'N/A'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_tax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
            <button type="button" class="btn btn-primary pull-right btn-add-more-form">
                Add Service
            </button>
            <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                Cancel
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop()