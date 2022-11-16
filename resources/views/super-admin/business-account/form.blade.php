{!! Form::open(['route' => ['superadmin.businessAccount.update',$businessAccount->id], 'id' => 'form-7', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison','method' => 'post']) !!}
  {{csrf_field()}}
  	
	<div class="row">
		<div class="col-md-6">
			<fieldset class="padding-15">
				<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
					<label class="strong">First Name</label>
					<input type="text" name="name" class="form-control" value="{{isset($businessAccount)?$businessAccount->name:''}}">
					{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
				</div>
				<div class="form-group{{ $errors->has('last_name') ? 'has-error' : ''}}">
					<label class="strong">Last Name</label>
					<input type="text" name="last_name" class="form-control" value="{{isset($businessAccount)?$businessAccount->last_name:''}}">
					{!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
				</div>
				<div class="form-group{{ $errors->has('web_url') ? 'has-error' : ''}}">
					<label class="strong">Web Url</label>
					<div class="row no-margin">
                        <div class="form-group col-md-6 no-padding">
                            {!! Form::input('text', null, url('/').'/login/', ['class' => 'form-control', 'title' => 'Required URL', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="form-group col-md-6 no-padding">
							<input type="text" name="web_url" class="form-control" value="{{isset($businessAccount)?$businessAccount->web_url:''}}">
                      </div>
                  </div>
					{{-- <input type="text" name="web_url" class="form-control" value="{{isset($businessAccount)?url('/login/'.$businessAccount->web_url):''}}"> --}}
					{!! $errors->first('web_url', '<p class="help-block">:message</p>') !!}
				</div>
				<div class="form-group{{ $errors->has('email') ? 'has-error' : ''}}">
					<label class="strong">E-mail Address</label>
					<input type="email" name="email" class="form-control" value="{{isset($businessAccount)?$businessAccount->email:''}}">
					{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
				</div>
				<div class="form-group{{ $errors->has('telephone') ? 'has-error' : ''}}">
					<label class="strong">Telephone</label>
					<input type="hidden" name="country_code" id="country_code">
					<input type="tel" name="telephone" class="form-control countryCode numericField" value="{{isset($businessAccount)?$businessAccount->telephone:''}}">
					{!! $errors->first('telephone', '<p class="help-block">:message</p>') !!}
				</div>
				<div class="form-group{{ $errors->has('referral') ? 'has-error' : ''}}">
					<label class="strong">Referral</label>
					<input type="text" name="referral" class="form-control" value="{{isset($businessAccount)?$businessAccount->referral:''}}">
					{!! $errors->first('referral', '<p class="help-block">:message</p>') !!}
				</div>
				<div class="form-group">
					<label class="strong">What are your expectations?</label>
					<div class="panel-body  no-padding">
                        <div class="checkbox clip-check check-primary">
                        	<input type="hidden" name="client_management" value="0">
                            <input id="client_management" name="client_management" type="checkbox" value="1" {{isset($businessAccount) && $businessAccount->client_management == '1'?'checked':''}}> 
                            <label for="client_management">Client Management</label>
                        </div>
                        <div class="checkbox clip-check check-primary">
                        	<input type="hidden" name="business_support" value="0">
                            <input id="business_support" name="business_support" type="checkbox" value="1" {{isset($businessAccount) && $businessAccount->business_support == '1'?'checked':''}}>
                            <label for="business_support">Business Support</label>
                        </div>
                        <div class="checkbox clip-check check-primary">
                        	<input type="hidden" name="Knowledge" value="0">
                            <input id="Knowledge" name="Knowledge" type="checkbox" value="1" {{isset($businessAccount) && $businessAccount->Knowledge == '1'?'checked':''}}>
                            <label for="Knowledge">Knowledge</label>
                        </div>
                        <div class="checkbox clip-check check-primary">
                        	<input type="hidden" name="resources" value="0">
                            <input id="resources" name="resources" type="checkbox" value="1" {{isset($businessAccount) && $businessAccount->resources == '1'?'checked':''}}>
                            <label for="resources">Tools &amp; Resources</label>
                        </div>
                        <div class="checkbox clip-check check-primary">
                        	<input type="hidden" name="mentoring" value="0">
                            <input id="mentoring" name="mentoring" type="checkbox" value="1" {{isset($businessAccount) && $businessAccount->mentoring == '1'?'checked':''}}>
                            <label for="mentoring">Mentoring</label>
                        </div>
                    </div>
				</div>
				<div class="form-group">
					<label class="strong">User Limit</label>
					<div class="panel-body  no-padding">
                        <select class="form-control" name="user_limit_id">
                        	<option value="">--Select--</option>
                        	@foreach($userLimits as $userLimit)
                        	<option value="{{$userLimit->id}}" {{$userLimit->id == $businessAccount->user_limit_id?'selected':''}}>{{$userLimit->maximum_users}}</option>
                        	@endforeach
                        </select>
                    </div>
                </div>
				<div class="form-group">
					<label class="strong">Verification Status</label>
					<div class="panel-body  no-padding">
                        <select class="form-control" name="confirmed">
                        	<option value="">--Select--</option>
                        	<option value="0" {{isset($businessAccount) && $businessAccount->confirmed == '0'?'selected':''}}>In Process</option>
                        	<option value="2" {{isset($businessAccount) && $businessAccount->confirmed == '2'?'selected':''}}>Under Review</option>
                        	<option value="3" {{isset($businessAccount) && $businessAccount->confirmed == '3'?'selected':''}}>On Hold</option>
                        	<option value="1" {{isset($businessAccount) && $businessAccount->confirmed == '1'?'selected':''}}>Activate</option>
                        </select>
                    </div>
                </div>
				<div class="form-group form-actions">
                    <button type="submit" class="btn btn-primary pull-left">Update</button>
                </div>
			</fieldset>
		</div>
	</div>
{!! Form::close() !!}