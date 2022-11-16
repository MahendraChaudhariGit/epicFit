
<div class="sucMes hidden"></div>
    <div class="row">
        <div class="col-md-6">
                <fieldset class="padding-15">
                    <legend>
                        General
                    </legend>
                    <div class="form-group">
                        {!! Form::label('firstName', 'First Name *', ['class' => 'strong']) !!}
                        {!! Form::text('firstName', isset($data)?$data->name:null , ['class' => 'form-control', 'required' => 'required']) !!}                        
                    </div>      

                    <div class="form-group">
                        {!! Form::label('lastName', 'Last Name *', ['class' => 'strong']) !!}
                        {!! Form::text('lastName', isset($data)?$data->last_name:null , ['class' => 'form-control', 'required' => 'required']) !!}                        
                    </div>

                    <div class="form-group">
                        {!! Form::label('admin_permissions', 'Admin Permissions *', ['class' => 'strong']) !!}
                        {!! Form::select('admin_permissions', $permTyp, isset($data)?$data->ut_id:null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
                    </div>          

                    <div class="form-group upload-group">
                        {!! Form::label(null, 'Upload Profile Picture *', ['class' => 'strong']) !!}

                        <input type="hidden" name="prePhotoName" value="{{ isset($data)?$data->profile_picture:'' }}" class="no-clear">
                        <input type="hidden" name="entityId" value="" class="no-clear">
                        <input type="hidden" name="saveUrl" value="" class="no-clear">
                        <input type="hidden" name="photoHelper" value="adminProfilePicture" class="no-clear">
                        <input type="hidden" name="cropSelector" value="square">
                        <div>
                            <label class="btn btn-primary btn-file">
                                <span><i class="fa fa-plus"></i> Select Photo</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                            </label>
                            <div class="m-t-10">
                                @if(isset($data))
                                <img class="adminProfilePicturePreviewPics previewPics" src="{{ dpSrc($data->profile_picture) }}" />
                                @else
                                <img class="hidden adminProfilePicturePreviewPics previewPics" />
                                @endif
                            </div>
                            <!--<span class="help-block m-b-0"></span>-->
                            <input type="hidden" name="adminProfilePicture" value="{{ isset($data)?$data->profile_picture:'' }}">
                        </div>
                    </div>
                      <!--<span class="help-block placeErrMsg"></span>-->
                </fieldset>
                <!--Start: Password reset  -->
         <fieldset class="padding-15 js-adminPwdFieldset m-t-0">
            <legend>
                Password
            </legend>
            <div class="form-group">
                {!! Form::label('newPwdField', 'New Password', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the admin members new password"><i class="fa fa-question-circle"></i></span>
                {!! Form::text('newPwdField', isset($pwd)?$pwd:null, ['class' => 'form-control', 'minlength' => 6, 'autocomplete' => 'off']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('newPwdCnfmField', 'Confirm Password', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the admin members new password confirmation"><i class="fa fa-question-circle"></i></span>
                <input id="newPwdCnfmField" class="form-control customValField" name="newPwdCnfmField" value="{{ isset($pwd)?$pwd:null }}" type="password"/>
                <span class="help-block m-b-0"></span>
            </div>
         </fieldset>
                <!-- End:  Password Reset  --> 
        </div>
        <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    Contact
                </legend>
                <div class="form-group ">
                        {!! Form::label('email', 'Email * ', ['class' => 'strong']) !!}
                        {!! Form::email('email',isset($data)?$data->email:null , ['class' => 'form-control', 'required' => 'required']) !!}   
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone', 'Phone *', ['class' => 'strong']) !!}
                        {!! Form::tel('phone', isset($data)?$data->telephone:null, ['class' => 'form-control countryCode numericField', 'required' => 'required', 'maxlength' => '16', 'minlength' => '5', 'data-realtime' => 'phone']) !!}
                    </div>
                    <div class="form-group ">
                        {!! Form::label('address_line_one', 'Address Line 1 * ', ['class' => 'strong']) !!}
                        {!! Form::text('address_line_one', isset($data)?$data->address_line_one:null, ['class' => 'form-control', 'required' => 'required','id' => 'autocomplete','onFocus' => 'geolocate()','autocomplete' => 'off']) !!}
                    </div>
                    <div class="form-group ">
                        {!! Form::label('address_line_two', 'Address Line 2 * ', ['class' => 'strong']) !!}
                        {!! Form::text('address_line_two', isset($data)?$data->address_line_two:null, ['class' => 'form-control', 'required' => 'required']) !!}   
                    </div>
                    <div class="form-group ">
                        {!! Form::label('city', 'City * ', ['class' => 'strong']) !!}
                        {!! Form::text('city', isset($data)?$data->city:null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('country', 'Country * ', ['class' => 'strong']) !!}
                        {!! Form::select('country', $countries, isset($data)?$data->country:null, ['class' => 'form-control countries onchange-set-neutral', 'required' => 'required', 'autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group ">
                        {!! Form::label('state', 'State / Region * ', ['class' => 'strong']) !!}
                        {!! Form::select('state', isset($data)?$states:['' => '-- Select --'], isset($data)?$data->state:null, ['class' => 'form-control states', 'required' => 'required', 'data-selected' => '']) !!}
                    </div>
                    <div class="form-group ">
                        {!! Form::label('postal_code', 'Postal Code * ', ['class' => 'strong']) !!}
                        {!! Form::text('postal_code', isset($data)?$data->postal_code:null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
            </fieldset>
        </div>
    </div>
            