@if(!isset($business))
	{!! Form::open(['url' => 'settings/business', 'id' => 'form-1', 'class' => 'margin-bottom-30']) !!}
	{!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}
	<div class="row">
		<div class="col-xs-12">
			<p class="margin-top-5 italic">This is a brief summary of the basic business information and allows for customisation of your correspondence and other media.</p>
		</div>
	</div>
	<div class="row margin-top-90">
@else
	{!! Form::model($business, ['method' => 'patch', 'route' => ['business.updatee', $business->id], 'id' => 'form-1', 'class' => 'margin-bottom-30']) !!}
	{!! Form::hidden('businessId', $business->id , ['class' => 'businessId no-clear']) !!}
	<div class="row">
		<div class="sucMes hidden"></div>
@endif
	<div class="col-md-6 col-sm-6">
		<fieldset class="padding-15">
			<legend>
				General &nbsp;&nbsp;&nbsp;&nbsp;
			</legend>
			<div class="form-group {{ $errors->has('trading_name') ? 'has-error' : ''}}">
				{!! Form::label('trading_name', 'Business Trading Name * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title="This is the name clients and contacts will know the business as."><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('trading_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('trading_name', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
				{!! Form::label('type', 'Business Type * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This relates to the type of services and products, location and clients that you have."><i class="fa fa-question-circle"></i></span>
				<a href="{{ route('business.getType') }}" class="pull-right add-more" data-modal-title="Business Types" data-field="businessType">Manage Business Types</a>
				<!-- <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Business Type</a> -->
				
				{!! Form::hidden('btn-add-more-action', 'type', ['class' => 'no-clear']) !!}
				<div>
					{!! Form::select('type', $businessTypes, null, ['class' => 'form-control businessType', 'required' => 'required']) !!}<!--, 'multiple'-->
					{!! $errors->first('type', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
				{!! Form::label('description', 'Business Description * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="A brief description of what services and products the company offers as well as a mission
					statement."><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::textarea('description', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('description', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('cp_first_name') ? 'has-error' : ''}}">
				{!! Form::label('cp_first_name', 'Contact Person First Name * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="Person to contact relating to billing and administrative duties, will receive all notifications from
					EPIC Trainer relating to account details and financial information"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('cp_first_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('cp_first_name', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('cp_last_name') ? 'has-error' : ''}}">
				{!! Form::label('cp_last_name', 'Contact Person Last Name * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="Person to contact relating to billing and administrative duties, will receive all notifications from
					EPIC Trainer relating to account details and financial information"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('cp_last_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('cp_last_name', '<p class="help-block">:message</p>') !!}
				</div>
			</div>

            <div class="form-group {{ $errors->has('cp_web_url') ? 'has-error' : ''}}">
				{!! Form::label('cp_web_url', 'Business URL', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is identifier for your login"><i class="fa fa-question-circle"></i></span>
				<div>
				    <div class="row no-margin">
                        <div class="form-group col-md-6 no-padding">
                            {!! Form::input('text', null, url('/').'/login/', ['class' => 'form-control', 'title' => 'Required URL', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="form-group col-md-6 no-padding">
                          {!! Form::text('cp_web_url', isset($web_url)?$web_url:null, ['class' => 'form-control','required' => 'required']) !!}
                      </div>
                  </div>
					{{-- {!! Form::text('cp_web_url', isset($web_url)?url('/login/'.$web_url):null, ['class' => 'form-control','required' => 'required']) !!} --}}
					{!! $errors->first('cp_web_url', '<p class="help-block">:message</p>') !!}
				<span class="help-block m-b-0"></span>
				</div>
			</div>

			<div class="form-group {{ $errors->has('relationship') ? 'has-error' : ''}}">
				{!! Form::label('relationship', 'Contact persons relationship to the business * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This relates to the person who is completing this business setup."><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::select('relationship', ['' => '-- Select --','Administrator' => 'Administrator', 'Head Trainer' => 'Head Trainer', 'Manager' => 'Manager', 'Owner' => 'Owner' ], null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('relationship', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('currency') ? 'has-error' : ''}}">
				{!! Form::label('currency', 'Currency * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="The currency that the business will trade in."><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::select('currency', $currency, null, ['class' => 'form-control', 'required' => 'required','data-live-search' => 'true']) !!}
					{!! $errors->first('currency', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('time_zone') ? 'has-error' : ''}}">
				{!! Form::label('time_zone', 'Time Zone * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="Time zones are important for numerous reasons including client communication and reminders"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::select('time_zone', $time_zone, null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('time_zone', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
		</fieldset>
		<fieldset class="padding-15" id="business-logo-fieldset">
			<legend>
				Business Logo *
				<span class="epic-tooltip" data-toggle="tooltip" title="To appear on invoices, email correspondence & websites"><i class="fa fa-question-circle"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;
			</legend>
			<div class="form-group upload-group">
				<input type="hidden" name="prePhotoName" value="{{ isset($business)?$business->logo:'' }}" class="no-clear">
				<input type="hidden" name="entityId" value="" class="no-clear">
				<input type="hidden" name="saveUrl" value="" class="no-clear">
				<input type="hidden" name="photoHelper" value="logo" class="no-clear">
				<input type="hidden" name="cropSelector" value="">
				<label class="btn btn-primary btn-file">
					<span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
				</label>
				<div class="m-t-10">
					@if(isset($business))
						<img class="logoPreviewPics previewPics" src="{{ dpSrc($business->logo) }}" />
					@else
						<img class="hidden logoPreviewPics previewPics"/>
					@endif
				</div>
				<span class="help-block m-b-0"></span>
				<input type="hidden" name="logo" value="{{ isset($business)?$business->logo:''  }}">
			</div>
		</fieldset>
	</div>
	<div class="col-md-6 col-sm-6">
		<fieldset class="padding-15">
			<legend>
				Contact &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</legend>
			<div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
				{!! Form::label('website', 'Business Website Address ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the main corporate website for the business"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('website', null, ['class' => 'form-control customValField']) !!}
					<span class="help-block m-b-0"></span>
					{!! $errors->first('website', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('facebook') ? 'has-error' : ''}}">
				{!! Form::label('facebook', 'Business Facebook Page ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the main Facebook page"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('facebook', null, ['class' => 'form-control customValField']) !!}
					<span class="help-block m-b-0"></span>
					{!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
				{!! Form::label('email', 'Business Email * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="Supply business email as default for outgoing email correspondence."><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
				{!! Form::label('phone', 'Business Phone * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the primary contact detail for the business"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::tel('phone', null, ['class' => 'form-control countryCode numericField', 'required' => 'required', 'maxlength' => '16', 'minlength' => '5']) !!}
					{!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('address_line_one') ? 'has-error' : ''}}">
				{!! Form::label('address_line_one', 'Address Line 1 * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the physical business address for any correspondence"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('address_line_one', null, ['class' => 'form-control', 'required' => 'required','id' => 'autocomplete','onFocus' => 'geolocate()','autocomplete' => 'off']) !!}
					{!! $errors->first('address_line_one', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('address_line_two') ? 'has-error' : ''}}">
				{!! Form::label('address_line_two', 'Address Line 2 * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the physical business address for any correspondence"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('address_line_two', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('address_line_two', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('city') ? 'has-error' : ''}}">
				{!! Form::label('city', 'City * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the physical business address for any correspondence"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('city', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('city', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
				{!! Form::label('country', 'Country * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the physical business address for any correspondence"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::select('country', $country, null, ['class' => 'form-control countries', 'required' => 'required']) !!}
					{!! $errors->first('country', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
				{!! Form::label('state', 'State / Region * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the physical business address for any correspondence"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::select('state', ['' => '-- Select --'], null, ['class' => 'form-control states', 'required' => 'required', 'data-selected' => isset($business)?$business->state:'']) !!}
					{!! $errors->first('state', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('postal_code') ? 'has-error' : ''}}">
				{!! Form::label('postal_code', 'Postal Code * ', ['class' => 'strong']) !!}
				<span class="epic-tooltip" data-toggle="tooltip" title="This is the physical business address for any correspondence"><i class="fa fa-question-circle"></i></span>
				<div>
					{!! Form::text('postal_code', null, ['class' => 'form-control', 'required' => 'required']) !!}
					{!! $errors->first('postal_code', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('venue_location') ? 'has-error' : ''}}">
				<div class="checkbox clip-check check-primary m-b-0">
					<input type="checkbox" name="venue_location" id="venue_location" value="1" {{ isset($business) && $business->venue_location?'checked':''  }}>
					<label for="venue_location">
						<strong>Same as venue or location</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Check this box if this business address is the same as the venue or training location"><i class="fa fa-question-circle"></i></span>
					</label>
					{!! $errors->first('venue_location', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="form-group {{ $errors->has('billing_info') ? 'has-error' : ''}}">
				<div class="checkbox clip-check check-primary m-b-0">
					<input type="checkbox" name="billing_info" id="billing_info" value="1" {{ isset($business) && $business->billing_info?'checked':''  }}>
					<label for="billing_info">
						<strong>Use these details for billing information</strong> <span class="epic-tooltip" data-toggle="tooltip" title="These details will be used on all correspondence relating to clients and staff correspondence"><i class="fa fa-question-circle"></i></span>
					</label>
					{!! $errors->first('billing_info', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
		</fieldset>
	</div>
</div>
@if(!isset($business))
    <div class="row">
		<div class="col-sm-6"></div>
		<div class="col-sm-6">
			<div class="form-group">
				<button class="btn btn-primary btn-o next-step btn-wide pull-right">
					Next <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>
		</div>
	</div>
@else
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-primary btn-wide pull-right btn-add-more-form">
                    <i class="fa fa-edit"></i> Update Business
                </button>
            </div>
        </div>
    </div>
@endif
{!! Form::close() !!}