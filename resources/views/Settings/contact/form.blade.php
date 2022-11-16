@if(!isset($businessId))
    {!! Form::open(['url' => 'settings/contact', 'id' => 'form-7', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison']) !!}
    {!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}
    <div class="row">
        <div class="col-xs-12">
            <p class="margin-top-5 italic">Tracking and monitoring your heart rate and graphing the result over time.</p>
        </div>
    </div>
    <div class="row margin-top-90">
@else
    @if(isset($contact))
        {!! Form::model($contact, ['method' => 'patch', 'route' => ['contacts.update', $contact->id], 'id' => 'form-7', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @else
        {!! Form::open(['route' => ['contacts.store'], 'id' => 'form-7', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
    {!! Form::hidden('subview_refresh' ,isset($subview)?'reload':'') !!}
    <div class="row">
@endif
    <div class="sucMes hidden"></div>
    <div class="col-md-6">
        <fieldset class="padding-15">
            <legend>
                General
            </legend>
            <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                {!! Form::label('type', 'Contact Type *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the type of service the contact will deliver, this is service provider, supplier, medical network, trainer etc"><i class="fa fa-question-circle"></i></span>
                <!-- <a href="javascript:void(0)"  class="pull-right btn-add-more">+ Add New Type</a> -->
                <a href="{{ route('contact.getType') }}" class="pull-right add-more" data-modal-title="Contact Types" data-field="contType">Manage Types</a>
                {!! Form::hidden('btn-add-more-action', 'contactType', ['class' => 'no-clear']) !!}
                <div>
                    {!! Form::select('type', $contactTypes, null, ['class' => 'form-control contType', 'required' => 'required']) !!}
                    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                    <input type="checkbox" name="is_epic_trainer" id="is_epic_trainer" value="1" {{ isset($contact) && $contact->is_epic_trainer?'checked':'' }}>
                    <label for="is_epic_trainer">
                        <strong>Is this trainer an EPIC Trainer?</strong>
                    </label>
                </div>
            </div>
            <div class="form-group {{ $errors->has('company_name') ? 'has-error' : ''}}">
                {!! Form::label('company_name', 'Company Name *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the name that the contact will be known as"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('company_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('company_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('service_offered') ? 'has-error' : ''}}">
                {!! Form::label('service_offered', 'Services Offered *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="Tooltip Text"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('service_offered', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('service_offered', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('location') ? 'has-error' : ''}}">
                {!! Form::label('location', 'Location *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the location that this contact is associated with"><i class="fa fa-question-circle"></i></span>
                @if(!isset($subview))
                <a href="#" class="pull-right callSubview" data-target-subview="location">+ Add New Location</a>
                @endif
                <div>
                    <select name="location" class="form-control location" required='required' multiple='multiple'> 
                    <?php 
                        if(isset($contact))
                            $location_select = explode(',', $contact->location);
                        elseif(isset($loc))
                            $location_select[]=$loc;
                        else
                            $location_select=[];

                        if(isset($businessId)){
                            foreach ($locs as $value => $name) {
                                $sel='';
                                if(in_array($value, $location_select))
                                     $sel='selected="selected"';
                                echo '<option value="'.$value.'"'.$sel.'>'.$name.'</option>';
                            }
                        }
                    ?>
                    </select>
                    
                    {!! $errors->first('location', '<p class="help-block">:message</p>') !!} 
                </div>
            </div>
            <div class="form-group {{ $errors->has('contact_name') ? 'has-error' : ''}}">
                {!! Form::label('contact_name', 'Contact Name *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the contact person at the specific contact who we communicate with"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('contact_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('contact_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('preferred_name') ? 'has-error' : ''}}">
                {!! Form::label('preferred_name', 'Preferred Name *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="Determines the way we will search and communicate with this contact, ie business name or contact person name"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('preferred_name', ['' => '-- Select --', 'Company Name' => 'Company Name', 'Contact Name' => 'Contact Name'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('preferred_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('notes') ? 'has-error' : ''}}">
                {!! Form::label('notes', 'Notes', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This will allow for additional information to be supplied for later requirements "><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('notes', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset class="padding-15">
            <legend>
                Inventory
            </legend>
            <div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
                {!! Form::label('website', 'Contact Website Address', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the main corporate website for the contact"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('website', null, ['class' => 'form-control customValField']) !!}
                    <span class="help-block m-b-0"></span>
                    {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('facebook') ? 'has-error' : ''}}">
                {!! Form::label('facebook', 'Contact Facebook Page', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the main Facebook page"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('facebook', null, ['class' => 'form-control customValField']) !!}
                    <span class="help-block m-b-0"></span>
                    {!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email', 'Contact Email *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="Supply contact email as default for outgoing email correspondence."><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
                {!! Form::label('phone', 'Contact Phone *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the primary contact detail for the contact"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::tel('phone', null, ['class' => 'form-control countryCode numericField', 'required' => 'required', 'maxlength' => '16', 'minlength' => '5']) !!}
                    {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('address_line_one') ? 'has-error' : ''}}">
                {!! Form::label('address_line_one', 'Address Line 1 *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical contact address for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('address_line_one', null, ['class' => 'form-control', 'required' => 'required','id' => 'autocomplete','onFocus' => 'geolocate()','autocomplete' => 'off']) !!}
                    {!! $errors->first('address_line_one', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('address_line_two') ? 'has-error' : ''}}">
                {!! Form::label('address_line_two', 'Address Line 2 *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical contact address for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('address_line_two', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('address_line_two', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('city') ? 'has-error' : ''}}">
                {!! Form::label('city', 'City *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical contact address for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('city', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
                {!! Form::label('country', 'Country *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical contact address for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('country', $country, null, ['class' => 'form-control countries', 'required' => 'required']) !!}
                    {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                {!! Form::label('state', 'State / Region *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical contact address for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('state', isset($contact)?$states:['' => '-- Select --'], isset($contact)?$contact->state:null, ['class' => 'form-control states', 'required' => 'required']) !!}
                    {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('postal_code') ? 'has-error' : ''}}">
                {!! Form::label('postal_code', 'Postal Code *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the physical contact address for any correspondence"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('postal_code', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('postal_code', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </fieldset>
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
                <button class="btn btn-primary btn-o finish btn-add-more-form btn-wide pull-right">
                    Finish
                </button>
                <button class="btn btn-primary btn-wide pull-right margin-right-15 btn-add-more-form">
                    <i class="fa fa-plus"></i> Add Contact
                </button>
                <button class="btn btn-primary btn-wide pull-right margin-right-15 skipbutton finish hidden" type="button">
                    Skip to Finish
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
                    @if(isset($contact))
                        <i class="fa fa-edit"></i> Update Contact
                    @else
                        <i class="fa fa-plus"></i> Add Contact
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