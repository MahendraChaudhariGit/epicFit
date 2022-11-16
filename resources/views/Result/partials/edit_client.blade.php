
    
 <div class="row swMain" >
@if(isset($businessId))
    @if(isset($client))
        {!! Form::model($client, ['route' => ['profile.update', $client->id], 'id' => 'form-6', 'class' => 'margin-bottom-30']) !!}
        {!! method_field('patch') !!}
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
    <div class="col-md-12">
@endif      
    <div class="sucMes hidden"></div>   
    {!! displayAlert()!!}
    <div class="alert alert-danger hidden" id="reqMsg">
        At least one field is required out of Email address and Phone number.
    </div>
    <fieldset class="padding-15">
        <legend>
            General Details
        </legend>
        <div class="row">
            <div class="col-md-6 {{ $errors->has('first_name') ? 'has-error' : ''}}">
                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                    <div>
                        {!! Form::label('first_name', 'What is your first name *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the clients first name">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::text('first_name', isset($client)?$client->firstname:null, ['class' => 'form-control', 'required' => 'required' ,'data-realtime' =>'firstName']) !!}
                    {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group  ">
                    <div>
                        {!! Form::label('last_name', 'What is your last name', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the clients last name">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::text('last_name', isset($client)?$client->lastname:null, ['class' => 'form-control','data-realtime' =>'lastName']) !!}

                </div>
                <!--<div class="form-group {{ $errors->has('client_status') ? 'has-error' : ''}}">
                    <div>
                        {!! Form::label('client_status', 'What is client status *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    
                    {!! Form::select('client_status', ['' => '-- Select --', 'lead' => 'Lead', 'pre-consultation' => 'Pre Consultation', 'pre-benchmarking' => 'Pre Benchmarking', 'pre-training' => 'Pre Training', 'active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending', 'on-hold' => 'On hold', 'active-lead' => 'Active lead', 'inactive-lead' => 'Inactive lead'], isset($client)?$client->account_status_backend:null, ['class' => 'form-control', 'required' => 'required','data-realtime' =>'accStatus','disabled'=>'disabled'] ) !!}
                    {!! $errors->first('client_status', '<p class="help-block">:message</p>') !!}
                </div>-->
                <div class="form-group">
                    <div>
                        {!! Form::label(null, 'I Identify my gender as', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This relates to the gender of the client">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="gender" id="gender-male" value="Male" {{($client->gender)?'disabled':''}} {{ isset($client) && $client->gender == 'Male'?'checked':'' }}>
                            <label for="gender-male">
                                Male
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="gender" id="gender-female" value="Female" {{($client->gender)?'disabled':''}} {{ isset($client) && $client->gender == 'Female'?'checked':'' }}>
                            <label for="gender-female">
                                Female
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <div>
                        {!! Form::label('goalHealthWellness', 'What are your goals', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::select('goalHealthWellness', array('Health &amp; wellness' => 'Health &amp; wellness', 'Increased energy' => 'Increased energy', 'Tone' => 'Tone', 'Injury recovery' => 'Injury recovery', 'Improved nutrition' => 'Improved nutrition', 'Lose weight' => 'Lose weight', 'Improved performance' => 'Improved performance', 'Improved endurance' => 'Improved endurance', 'Improved Strength & Conditioning' => 'Improved Strength & Conditioning'), isset($client) && count($client->goalHealthWellness)?$client->goalHealthWellness:null, ['class' => 'form-control', 'multiple']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group clearfix" >
                    <div>
                        {!! Form::label('day', 'When were you born', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This relates to the client date of birth">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::select('day', array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'), isset($parq)?$parq->birthDay:null, ['class' => 'form-control js-day', 'title' => 'DAY','data-realtime'=>'dob' ,!empty($parq->birthDay)?'disabled':'']) !!}
                        </div>
                        <div class="col-md-4">
                        
                            <select class="form-control js-month" title="MONTH" name="month" data-realtime="dob" {{!empty($parq->birthMonth)?'disabled':''}}>
                            @if(isset($parq))
                                {!! monthDdOptions($parq->birthMonth) !!}
                            @else
                                {!! monthDdOptions() !!}
                            @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control js-year" title="YEAR" name="year" data-realtime="dob" {{!empty($parq->birthYear)?'disabled':''}}>
                                @if(isset($parq))
                                    {!! yearDdOptions($parq->birthYear) !!}
                                @else
                                    {!! yearDdOptions() !!}
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <!--<div class="form-group">
                    <div>
                        {!! Form::label(null, 'Referred by?', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork0" value="Client" data-realtime="referralNetwork" {{ isset($client->parq) && $client->parq->referralNetwork == 'Client'?'checked':'' }}>
                            <label for="referralNetwork0">
                                Client
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork1" value="Staff" data-realtime="referralNetwork" {{ isset($client->parq) && $client->parq->referralNetwork == 'Staff'?'checked':'' }}>
                            <label for="referralNetwork1">
                                Staff
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork2" value="Professional network" data-realtime="referralNetwork" {{ isset($client->parq) && $client->parq->referralNetwork == 'Professional network'?'checked':'' }}>
                            <label for="referralNetwork2">
                                Professional network
                            </label>
                        </div>
                    </div>
                    {!! Form::text(null, isset($client->parq) && $client->parq->referralNetwork == 'Client'?$parq->clientName:null, ['class' => 'form-control', 'id' => 'clientList', 'autocomplete' => 'off','data-realtime' =>'referralNetwork']) !!}
                    {!! Form::text(null, isset($client->parq) && $client->parq->referralNetwork == 'Staff'?$parq->staffName:null, ['class' => 'form-control', 'id' => 'staffList', 'autocomplete' => 'off' ,'data-realtime' =>'referralNetwork']) !!}
                    {!! Form::text(null, isset($client->parq) && $client->parq->referralNetwork == 'Professional network'?$parq->proName:null, ['class' => 'form-control', 'id' => 'proList', 'autocomplete' => 'off','data-realtime' =>'referralNetwork']) !!}
                    {!! Form::hidden('clientId', isset($client->parq) && $client->parq->referralNetwork == 'Client'?$client->parq->referralId:null) !!}
                    {!! Form::hidden('staffId', isset($client->parq) && $client->parq->referralNetwork == 'Staff'?$client->parq->referralId:null) !!}
                    {!! Form::hidden('proId', isset($client->parq) && $client->parq->referralNetwork == 'Professional network'?$client->parq->referralId:null) !!}
                </div>-->
                <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                    <div>
                        {!! Form::label('email', 'Please provide your primary email address *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This email is the default for outgoing email correspondence and promotional materials for this client">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::email('email', isset($client)?$client->email:null, ['class' => 'form-control' ,'data-realtime' =>'email','disabled'=>'disabled']) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('numb') ? 'has-error' : ''}}">
                    <div>
                        {!! Form::label('numb', 'Please provide your phone number *', ['class' => 'strong ']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the primary contact detail for this specific client">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::tel('numb', isset($client)?$client->phonenumber:null, ['class' => 'form-control countryCode numericField', 'maxlength' => '16', 'minlength' => '5' ,'data-realtime' =>'phone','disabled'=>'disabled']) !!}
                    {!! $errors->first('numb', '<p class="help-block">:message</p>') !!}
                </div>
                 <div class="form-group hidden {{ $errors->has('client_notes') ? 'has-error' : ''}}">
                     <div>
                        {!! Form::label('client_notes', 'Client Notes', ['class' => 'strong']) !!}
                         <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is notes relating to the client that may be relevant at a later date.">
                            <i class="fa fa-question-circle"></i>
                        </span>
                     </div>
                        {!! Form::textarea('client_notes', isset($client)?$client->notes:null, ['class' => 'form-control']) !!}
                        {!! $errors->first('client_notes', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            </div>
    </fieldset>

    
</div>
   
    <div class="col-sm-6">
     <fieldset class="padding-15">
    <legend>
        Password
    </legend>

    <div class="form-group">
        {!! Form::label('clientNewPwd', 'New Password', ['class' => 'strong']) !!}
        <span class="epic-tooltip" data-toggle="tooltip" title="This is your new password"><i class="fa fa-question-circle"></i></span>
        {!! Form::password('clientNewPwd', ['class' => 'form-control ', 'minlength' => 6]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('clientNewPwdCnfm', 'Confirm Password', ['class' => 'strong']) !!}
        <span class="epic-tooltip" data-toggle="tooltip" title="This is your new password confirmation"><i class="fa fa-question-circle"></i></span>
        {!! Form::password('clientNewPwdCnfm', ['class' => 'form-control onchange-set-neutral']) !!}
        <span class="help-block m-b-0"></span>
    </div>
    </fieldset>
    </div>
    

    <div class="col-md-12">
    <div class="col-sm-8">
                        By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions.
                    </div>
        <div class="col-sm-4">
            <div class="form-group">
                <button class="btn btn-primary btn-wide pull-right btn-add-more-form update-client">
                    @if(isset($client))
                        <i class="fa fa-edit"></i> Update Client
                    @else
                        <i class="fa fa-plus"></i> Add Client
                    @endif
                </button>
            </div>
        </div>
    </div>

{!! Form::close() !!}
</div>