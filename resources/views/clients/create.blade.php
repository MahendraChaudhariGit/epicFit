@extends('layouts.app')

@section('meta_description')
@stop()

@section('meta_author')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles-for-this-page')
	{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
@stop()

@section('page-title')
    Add New Client
@stop

@section('content')
<div class="modal fade" id="clientStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        	{!! Form::open() !!}
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Client Status</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="clientStatus" class="strong">
                                Select Status *
                            </label>
                            <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                                <i class="fa fa-question-circle"></i>
                            </span>
                            <select class="form-control" id="clientStatus" required>
                                <option value="">Select</option>
                                <option value="lead">Lead</option>
                                <option value="pre-consultation">Pre-Consultation</option>
                                <option value="pre-benchmarking">Pre-Benchmarking</option>
                                <option value="pre-training">Pre-Training</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                                <option value="on-hold">On hold</option>
                                <option value="active-lead">Active lead</option>
                                <option value="inactive-lead">Inactive lead</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	{!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div id="form-container" class="container-fluid container-fullw bg-white">
	{!! displayAlert()!!}
    <div class="alert alert-danger hidden" id="reqMsg">
        Atleast one field is required out of Email address and Phone number.
    </div>
	{!! Form::open(['url' => 'client/save', 'class' => 'margin-bottom-30', 'id' => 'createForm']) !!}
    	{!! Form::hidden('clientStatus') !!}
        {!! Form::hidden('referralId') !!}
    	<fieldset class="padding-15">
    		<legend>General Details</legend>
        	<div class="row">
        		<div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('fname', 'What is your first name *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the clients first name">
                            <i class="fa fa-question-circle"></i>
                        </span>
                        {!! Form::text('fname', null, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('lname', 'What is your last name', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the clients last name">
                            <i class="fa fa-question-circle"></i>
                        </span>
                        {!! Form::text('lname', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label(null, 'I Identify my gender as', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This relates to the gender of the client">
                                <i class="fa fa-question-circle"></i>
                        </span>
                        <div>
                        	<div class="radio clip-radio radio-primary radio-inline m-b-0">
                            	<input type="radio" name="gender" id="male" value="Male">
                                <label for="male">
                                    Male
                                </label>
                            </div>
                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            	<input type="radio" name="gender" id="female" value="Female">
                                <label for="female">
                                    Female
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    	{!! Form::label('goalHealthWellness', 'What are your goals', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                            <i class="fa fa-question-circle"></i>
                        </span>
                        {!! Form::select('goalHealthWellness[]', array('Health &amp; wellness' => 'Health &amp; wellness', 'Increased energy' => 'Increased energy', 'Tone' => 'Tone', 'Injury recovery' => 'Injury recovery', 'Improved nutrition' => 'Improved nutrition', 'Lose weight' => 'Lose weight', 'Improved performance' => 'Improved performance', 'Improved endurance' => 'Improved endurance', 'Improved Strength & Conditioning' => 'Improved Strength & Conditioning'), null, ['class' => 'form-control', 'multiple']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group clearfix">
                        {!! Form::label('day', 'When were you born', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This relates to the client date of birth">
                                <i class="fa fa-question-circle"></i>
                        </span>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::select('day', array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'), null, ['class' => 'form-control', 'title' => 'DAY']) !!}
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
                    </div>
                    <div class="form-group">
                        {!! Form::label(null, 'Referred by?', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Here will be tooltip text">
                            <i class="fa fa-question-circle"></i>
                        </span>
                        <div>
                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                <input type="radio" name="referralNetwork" id="referralNetwork0" value="Client">
                                <label for="referralNetwork0">
                                    Client
                                </label>
                            </div>
                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                <input type="radio" name="referralNetwork" id="referralNetwork1" value="Staff">
                                <label for="referralNetwork1">
                                    Staff
                                </label>
                            </div>
                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                <input type="radio" name="referralNetwork" id="referralNetwork2" value="Professional network">
                                <label for="referralNetwork2">
                                    Professional network
                                </label>
                            </div>
                        </div>
                        {!! Form::text(null, null, ['class' => 'form-control', 'id' => 'clientList', 'autocomplete' => 'off']) !!}
                        {!! Form::text(null, null, ['class' => 'form-control', 'id' => 'staffList', 'autocomplete' => 'off']) !!}
                        {!! Form::text(null, null, ['class' => 'form-control', 'id' => 'proList', 'autocomplete' => 'off']) !!}
                        {!! Form::hidden('clientId') !!}
                        {!! Form::hidden('staffId') !!}
                        {!! Form::hidden('proId') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('eaddr', 'Please provide your primary email address *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This email is the default for outgoing email correspondence and promotional materials for this client">
                                <i class="fa fa-question-circle"></i>
                        </span>
                        {!! Form::email('eaddr', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('numb', 'Please provide your phone number *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This is the primary contact detail for this specific client">
                            <i class="fa fa-question-circle"></i>
                        </span>
                        {!! Form::tel('numb', null, ['class' => 'form-control numericField', 'maxlength' => '16', 'minlength' => '5']) !!}
                    </div>
                </div>
        	</div>
		</fieldset>
        <div class="form-group clearfix">
            {!! Form::Button('Submit <i class="fa fa-arrow-circle-right"></i>', ['class' => 'btn btn-primary pull-right', 'type' => 'submit']) !!}
        </div>
    {!! Form::close() !!}
</div>
@stop()

@section('required-script-for-this-page')
    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js') !!}  -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
    {!! Html::script('assets/js/form-wizard-client-create.js') !!}
    {!! Html::script('vendor/jquery-ui/jquery-ui.min.js') !!}
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    <script src="{{ asset('co/assets/plugins/intl-tel-input-master/build/js/utils.js') }}" ></script>
    <script src="{{ asset('co/assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('co/assets/plugins/bootstrap3-typeahead.min.js') }}" ></script>
    <script>
	function toggleReference(val){
		var refNet = 'input[name="referralNetwork"]';
		var clientList = $('#clientList');
		var staffList = $('#staffList');
		var proList = $('#proList');
		if(!val)
			val = $(refNet+':checked').val()

		if(val == 'Client'){
			clientList.removeClass('hidden');
			staffList.addClass('hidden');
			proList.addClass('hidden');
		}
		else if(val == 'Staff'){
			staffList.removeClass('hidden');
			proList.addClass('hidden');
			clientList.addClass('hidden');
		}
		else if(val == 'Professional network'){
			proList.removeClass('hidden');
			clientList.addClass('hidden');
			staffList.addClass('hidden');
		}
		else{
			clientList.addClass('hidden');
			proList.addClass('hidden');
			staffList.addClass('hidden');
		}
	}
    </script>
@stop()

@section('script-handler-for-this-page')
    <!-- start: EPIC TOOLTIP -->
    $('.epic-tooltip').tooltipster();
    <!-- end: EPIC TOOLTIP -->

    $('.numericField').keydown(function(e){
        if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8))
            return false;
    });

	$('select').selectpicker({
        noneSelectedText: " -- Select -- "
    });
    $('input[name="numb"]').intlTelInput({
		initialCountry: "auto",
		geoIpLookup:function(callback){
			$.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
				var countryCode = (resp && resp.country)?resp.country:"";
				callback(countryCode);
			});
		},
		preferredCountries: ['nz', 'au', 'za']
	});
    
    var forceSubmit = false;
    $('#createForm').submit(function(){
    	if(!forceSubmit){
            var $emailFieldVal = $(this).find('input[name="eaddr"]').val().trim();
            var $phoneNumbField = $(this).find('input[name="numb"]');
            var $phoneNumbFieldVal = $(this).find('input[name="numb"]').val().trim();
            if($emailFieldVal == '' && $phoneNumbFieldVal == '')
            	$('#reqMsg').removeClass('hidden');
			else if($phoneNumbFieldVal != ''){
                var cntryData = $phoneNumbField.intlTelInput("getNumber")
                    if(cntryData)
                        $phoneNumbField.val(cntryData)
            }
            if($emailFieldVal != '' || $phoneNumbFieldVal != ''){
            	$('#clientStatusModal').modal('show');
                $('#reqMsg').addClass('hidden');
            }
            
            var $referralNetworkFieldVal = $(this).find('input[name="referralNetwork"]:checked').val();
            var $referralIdField = $(this).find('input[name="referralId"]');
            if($referralNetworkFieldVal == 'Client')
            	$referralIdField.val($(this).find('input[name="clientId"]').val())
            else if($referralNetworkFieldVal == 'Staff')
            	$referralIdField.val($(this).find('input[name="staffId"]').val())
			else if($referralNetworkFieldVal == 'Professional network')
            	$referralIdField.val($(this).find('input[name="proId"]').val())
			else
				$referralIdField.val('');
                
            return false;
        }
    })
    
    $('#clientStatusModal form').on('submit',function(){
    	var createForm = $('#createForm');
        createForm.find('input[name="clientStatus"]').val($(this).find('select').val());
        $("#clientStatusModal").modal("hide");
        forceSubmit = true;
        createForm.submit();
		return false;
	});
    
    $.get('../clients/all', function(data){
		$("#clientList").typeahead({ 
			source:data, 
			items:'all', 
			afterSelect:function(selection){
				$('input[name="clientId"]').val(selection.id);
			}
		});
	},'json');
	$.get('../staffs/all', function(data){
		$("#staffList").typeahead({ 
			source:data, 
			items:'all', 
			afterSelect:function(selection){
				$('input[name="staffId"]').val(selection.id);
			}
		});
	},'json');
	$.get('../contacts/all', function(data){
		$("#proList").typeahead({ 
			source:data, 
			items:'all', 
			afterSelect:function(selection){
				$('input[name="proId"]').val(selection.id);
			}
		});
	},'json');
    $('input[name="referralNetwork"]').change(function(){
		toggleReference($(this).val());
	});
    toggleReference()
@stop()

@section('script-after-page-handler')
@stop()