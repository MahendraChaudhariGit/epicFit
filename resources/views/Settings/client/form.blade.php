@if(!isset($businessId))
    {!! Form::open(['url' => 'client/save', 'id' => 'form-6', 'class' => '  margin-bottom-30', 'data-form-mode' => 'unison', 'autocomplete'=>'off']) !!}
    {!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}
    <div class="row">
        <div class="col-xs-12">
            <p class="margin-top-5 italic">This is a brief summary of the location of your venue or venues.</p>
        </div>
    </div>
    <div class="row margin-top-90 m-x-0">
@else
    @if(isset($client))
        @if(isUserEligible(['Admin'], 'manage-client-membership'))
            <!-- Start: Edit Membership Subscription Modal -->
            @include('includes.partials.edit_memb_sub', ['clientId' => $client->id, 'memberships' => $allMemberShipData, 'clientMembership' => $activeMemb, 'paymenttype'=>$paymenttype])
            <!-- End: Edit Membership Subscription Modal -->
        @endif
        
        {!! Form::model($client, ['method' => 'patch', 'route' => ['clients.update', $client->id], 'id' => 'form-6', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone', 'autocomplete'=>'off']) !!}
        {!! Form::hidden('salesProcessCompleted', $client->sale_process_step) !!}
        {!! Form::hidden('consultationDate', $client->consultation_date) !!}
        {!! Form::hidden('membershipStatus', $activeMemb?$activeMemb->cm_status:'') !!}
    @else
        {!! Form::open(['route' => ['clients.store'], 'id' => 'form-6', 'class' => '  margin-bottom-30', 'data-form-mode' => 'standAlone', 'autocomplete'=>'off']) !!}
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
    <div class="row">
    <div class="col-md-12">
@endif      
    <div class="sucMes hidden"></div>   
    {!! displayAlert()!!}
    <div class="alert alert-danger hidden" id="reqMsg">
        At least one field is required out of Email address and Phone number.
    </div>
    <div class="alert alert-danger hidden" id="reqMsgloginEmail">
        Email address is required to create login credential.
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
                    {!! Form::text('first_name', isset($client)?$client->firstname:null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group  ">
                    <div>
                        {!! Form::label('last_name', 'What is your last name', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the clients last name">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::text('last_name', isset($client)?$client->lastname:null, ['class' => 'form-control']) !!}

                </div>
                <div class="form-group {{ $errors->has('client_status') ? 'has-error' : ''}}">
                    <div>
                        {!! Form::label('client_status', 'What is client status *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <?php $clientStatus = array('' => '-- Select --') + clientStatuses(); ?>
                    {!! Form::select('client_status', $clientStatus, isset($client)?$client->account_status_backend:"pending", ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('client_status', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group">
                    <div>
                        {!! Form::label(null, 'I Identify my gender as', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This relates to the gender of the client">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="gender" id="male" value="Male" {{ isset($client) && $client->gender == 'Male'?'checked':'' }}>
                            <label for="male">
                                Male
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="gender" id="female" value="Female" {{ isset($client) && $client->gender == 'Female'?'checked':'' }}>
                            <label for="female">
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
                    <?php //dd($client->goalHealthWellness); ?>
                    {!! Form::select('goalHealthWellness', array('Health & wellness' => 'Health & wellness', 'Increased energy' => 'Increased energy','Injury recovery' => 'Injury recovery','Improved endurance' => 'Improved endurance','Improved nutrition' => 'Improved nutrition','Improved performance' => 'Improved performance','Improved Strength & Conditioning' => 'Improved Strength & Conditioning' ,'Lose weight' => 'Lose weight','Tone' => 'Tone'), isset($client) && count($client->goalHealthWellness)?$client->goalHealthWellness:null, ['class' => 'form-control', 'multiple']) !!}
                </div>
                <div class="form-group clearfix">
                    <div>
                        {!! Form::label('day', 'When were you born', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This relates to the client date of birth">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::select('day', array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'), isset($client)?$client->birthDay:null, ['class' => 'form-control', 'title' => 'DAY']) !!}
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" title="MONTH" name="month">
                            @if(isset($client))
                                {!! monthDdOptions($client->birthMonth) !!}
                            @else
                                {!! monthDdOptions() !!}
                            @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" title="YEAR" name="year">
                                @if(isset($client))
                                    {!! yearDdOptions($client->birthYear) !!}
                                @else
                                    {!! yearDdOptions() !!}
                                @endif
                            </select>
                        </div>
                    </div>
                </div>              
            </div>
            <div class="col-md-6">
                <!--Start: Where did you hear about EPIC? -->
                
                <div class="form-group">
                    <label class="strong" for="referrer">Where did you hear about EPIC?</label>
                    <select id="referrer" name="referrer" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="onlinesocial" <?php echo isset($client->parq) && $client->parq->hearUs == 'onlinesocial'?'selected':''; ?>>Online &amp; Social Media</option>
                        <option value="mediapromotions" <?php echo isset($client->parq) && $client->parq->hearUs == 'mediapromotions'?'selected':''; ?>>Media &amp; Promotions</option>
                        <option value="referral" <?php echo isset($client->parq) && $client->parq->hearUs == 'referral'?'selected':''; ?>>Referral</option>
                        <option value="socialmedia" <?php echo isset($client->parq) && $client->parq->hearUs == 'socialmedia'?'selected':''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group referencewhere">
                    <label class="strong">From where?</label>
                    <input type="text" id="referencewhere" name="referencewhere" value="{{ isset($client->parq)?$client->parq->referencewhere :'' }}" class="form-control">
                </div>

                <div class="form-group otherName hidden">
                    <label class="strong">Source</label>
                    <input type="text" id="otherName" name="otherName" value="{{ isset($client->parq)?$client->parq->referrerother:'' }}" class="form-control">
                </div>
                <!--End: Where did you hear about EPIC? --> 
                <div class="form-group refShow hidden">
                    <div>
                        {!! Form::label(null, 'Referred by?', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork0" value="Client" {{ isset($client->parq) && $client->parq->referralNetwork == 'Client'?'checked':'' }}>
                            <label for="referralNetwork0">
                                Client
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork1" value="Staff" {{ isset($client->parq) && $client->parq->referralNetwork == 'Staff'?'checked':'' }}>
                            <label for="referralNetwork1">
                                Staff
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork2" value="Professional network" {{ isset($client->parq) && $client->parq->referralNetwork == 'Professional network'?'checked':'' }}>
                            <label for="referralNetwork2">
                                Professional network
                            </label>
                        </div>
                    </div>
                    {!! Form::text(null, isset($client->parq) && $client->parq->referralNetwork == 'Client'?$client->parq->ref_Name:null, ['class' => 'form-control clientreferralName', 'id' => 'clientList', 'autocomplete' => 'off']) !!}
                    {!! Form::text(null, isset($client->parq) && $client->parq->referralNetwork == 'Staff'?$client->parq->ref_Name:null, ['class' => 'form-control staffreferralName', 'id' => 'staffList', 'autocomplete' => 'off']) !!}
                    {!! Form::text(null, isset($client->parq) && $client->parq->referralNetwork == 'Professional network'?$client->parq->ref_Name:null, ['class' => 'form-control professionalreferralName', 'id' => 'proList', 'autocomplete' => 'off']) !!}
                    {!! Form::hidden('clientId', isset($client->parq) && $client->parq->referralNetwork == 'Client'?$client->parq->referralId:null) !!}
                    {!! Form::hidden('staffId', isset($client->parq) && $client->parq->referralNetwork == 'Staff'?$client->parq->referralId:null) !!}
                    {!! Form::hidden('proId', isset($client->parq) && $client->parq->referralNetwork == 'Professional network'?$client->parq->referralId:null) !!}
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                    <div>
                        {!! Form::label('email', 'Please provide your primary email address *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This email is the default for outgoing email correspondence and promotional materials for this client">
                                <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::email('email', isset($client)?$client->email:null, ['class' => 'form-control']) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="form-group {{ $errors->has('login_with_email') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                    <input type="checkbox" name="login_with_email" id="login_with_email_client" value="1" class="js-ifCreateLogin" {{ isset($client) && $client->login_with_email?'checked':'' }} data-old-login-with-email="{{ isset($client) && $client->login_with_email?1:0 }}">
                    <label for="login_with_email_client">
                        <strong>Allow client to log in with email</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Please ensure that if you change your email address that you change your username when logging in"><i class="fa fa-question-circle"></i></span>
                    </label>
                    {!! $errors->first('login_with_email', '<p class="help-block">:message</p>') !!}
                   </div>
                </div> 

                <div class="form-group">
                    <div class="checkbox clip-check check-primary m-b-0">
                    <input type="checkbox" name="email_to_client" id="email_to_client" value="1" class="" {{ isset($client) && $client->email_to_client?'checked':'' }} data-old-email-to-client="{{ isset($client) && $client->email_to_client?1:0 }}">
                    <label for="email_to_client">
                        <strong>Send details to client email</strong> <span class="epic-tooltip" data-toggle="tooltip" title="">
                            {{-- <i class="fa fa-question-circle"></i> --}}
                        </span>
                    </label>
                   </div>
                </div> 

         <div class=" js-pwdFieldset m-t-0" >
          
            <div class="form-group">
                {!! Form::label('clientNewPwd', 'New Password', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the client members new password"><i class="fa fa-question-circle"></i></span>
                {!! Form::text('clientNewPwd', isset($pwd)?$pwd:null, ['class' => 'form-control', 'minlength' => 6, 'autocomplete' => 'off']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('clientNewPwdCnfm', 'Confirm Password', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the client members new password confirmation"><i class="fa fa-question-circle"></i></span>
                <input id="clientNewPwdCnfm" class="form-control customValField" value="{{ isset($pwd)?$pwd:null }}" name="clientNewPwdCnfm" type="password" />  
                <span class="help-block m-b-0"></span>
            </div>
        </div>
                <div class="form-group {{ $errors->has('numb') ? 'has-error' : ''}}">
                    <div>
                        {!! Form::label('numb', 'Please provide your phone number *', ['class' => 'strong ']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the primary contact detail for this specific client">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::tel('numb', isset($client)?$client->phonenumber:null, ['class' => 'form-control countryCode numericField', 'maxlength' => '16', 'minlength' => '5']) !!}
                    {!! $errors->first('numb', '<p class="help-block">:message</p>') !!}
                </div>
                 <div class="form-group {{ $errors->has('client_notes') ? 'has-error' : ''}}">
                     <div>
                        {!! Form::label('client_notes', 'Client Notes', ['class' => 'strong']) !!}
                         <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is notes relating to the client that may be relevant at a later date.">
                            <i class="fa fa-question-circle"></i>
                        </span>
                     </div>
                     
                        {!! Form::textarea('client_notes', isset($client->note)?$client->note->cn_notes:null, ['class' => 'form-control']) !!}
                        {!! $errors->first('client_notes', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </fieldset>
     </div>
    @if(isset($businessId))
    </div>
    @endif
   
    


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
                <button class="btn btn-primary btn-wide pull-right margin-right-15 btn-add-more-form">
                    <i class="fa fa-plus"></i> Add Client
                </button>
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 skipbutton skipnextbutton hidden">
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
                    @if(isset($client))
                        <i class="fa fa-edit"></i> Update Client
                    @else
                        <i class="fa fa-plus"></i> Add Client
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