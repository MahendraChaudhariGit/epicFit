var FormWizard = function() {
	var wizardContent = $('#wizard, #wizard1');
	var wizardForm = $('#form');
	var ifSendAjax = false;
	var stepCompleted = 0;
	var initWizard = function() {	
		// function to initiate Wizard Form
		wizardContent.smartWizard({
			selected : 0,
			keyNavigation : false,
			onLeaveStep : leaveAStepCallback,
			onShowStep : onShowStep,
		});
		var numberOfSteps = 0;
		animateBar();
		initValidator();
		var temp = wizardForm.find('input[name="step_status"]');
		if(temp.length){
			var step_status = temp.val().split(',');
			for(i=0; i<step_status.length; i++){
				wizardContent.smartWizard('enableStep', i+1)
				if($.trim(step_status[i]) != 'completed')
					break;
				stepCompleted++;
			}
		}
	};
	var animateBar = function(val) {
		if (( typeof val == 'undefined') || val == "") {
			val = 1;
		};
		numberOfSteps = $('.swMain > ul > li').length;
		var valueNow = Math.floor(100 / numberOfSteps * val);
		$('.step-bar').css('width', valueNow + '%');
	};
	var initValidator = function() {
		$.validator.addMethod("cardExpiry", function() {
			//if all values are selected
			if ($("#card_expiry_mm").val() != "" && $("#card_expiry_yyyy").val() != "") {
				return true;
			} else {
				return false;
			}
		}, 'Please select a month and year');
		$.validator.setDefaults({
			errorElement : "span", // contain the error msg in a span tag
			errorClass : 'help-block',
			errorPlacement : function(error, element) {// render error placement for each input type
				if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {// for chosen elements, need to insert the error after the chosen container
					error.insertAfter($(element).closest('.form-group').children('div').children().last());
				} else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
					error.appendTo($(element).closest('.form-group').children('div'));
				} else {
					error.insertAfter(element);
					// for other inputs, just perform default behavior
				}
			},
			ignore : ':hidden',
			rules : {
				username : {
					minlength : 2,
					required : true
				},
				email : {
					required : true,
					email : true
				},
				password : {
					minlength : 6,
					required : true
				},
				password_again : {
					required : true,
					minlength : 5,
					equalTo : "#password"
				},
				full_name : {
					required : true,
					minlength : 2,
				},
				phone : {
					required : true
				},
				gender : {
					required : true
				},
				address : {
					required : true
				},
				city : {
					required : true
				},
				country : {
					required : true
				},
				card_name : {
					required : true
				},
				card_number : {
					minlength : 16,
					maxlength : 16,
					required : true
				},
				card_cvc : {
					digits : true,
					required : true,
					minlength : 3,
					maxlength : 4
				},
				card_expiry_yyyy : "cardExpiry",
				payment : {
					required : true,
					minlength : 1
				}
			},
			messages : {
				firstname : "Please specify your first name"
			},
			highlight : function(element) {
				$(element).closest('.help-block').removeClass('valid');
				// display OK icon
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
				// add the Bootstrap error class to the control group
			},
			unhighlight : function(element) {// revert the change done by hightlight
				$(element).closest('.form-group').removeClass('has-error');
				// set error class to the control group
			},
			success : function(label, element) {
				label.addClass('help-block valid');
				// mark the current input as valid and display OK icon
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
			}
		});
	};
	/*var displayConfirm = function() {
		$('.display-value', form).each(function() {
			var input = $('[name="' + $(this).attr("data-display") + '"]', form);
			if (input.attr("type") == "text" || input.attr("type") == "email" || input.is("textarea")) {
				$(this).html(input.val());
			} else if (input.is("select")) {
				$(this).html(input.find('option:selected').text());
			} else if (input.is(":radio") || input.is(":checkbox")) {

				$(this).html(input.filter(":checked").closest('label').text());
			} else if ($(this).attr("data-display") == 'card_expiry') {
				$(this).html($('[name="card_expiry_mm"]', form).val() + '/' + $('[name="card_expiry_yyyy"]', form).val());
			}
		});
	};*/
	var onShowStep = function(obj, context) {
		$(".next-step").unbind("click").click(function(e) {
			var formMode = wizardForm.data('form-mode');
			
			if(formMode == 'view')
				ifSendAjax = false;
			else
				ifSendAjax = true;
			e.preventDefault();
			wizardContent.smartWizard("goForward");
		});
		$(".back-step").unbind("click").click(function(e) {
			e.preventDefault();
			wizardContent.smartWizard("goBackward");
		});
		$("#finish-parq").unbind("click").click(function(e) {
			ifSendAjax = true;
			e.preventDefault();
			leaveAStepCallback(obj, context, true);
		});
		$(".submit-step, .submit-step-injury").unbind("click").click(function(e) {
			ifSendAjax = true;
			e.preventDefault();
			leaveAStepCallback(obj, context, false, $(this).data("step"), $(this));
		});
	};
	var submitStepData = function(obj, stepNumb, toStep, showSuccess,instance = undefined){
		if(typeof showSuccess == 'undefined')
			showSuccess = false;
			
		var stepId = obj.attr('href'),
			formData = {};
			
		jQuery.each(jQuery(wizardForm).find(stepId).find(':input').serializeArray(), function(i, obj){
			formData[obj.name] = obj.value
		});

		if(stepNumb == 1){
			$(".cntryCode").each(function(){
				var cntryData = $(this).intlTelInput("getNumber")
				if(cntryData)
					formData[$(this).attr('name')] = cntryData;

				/*numbField = $(this);
				if(numbField.val() != ''){
					var selCountry = numbField.intlTelInput("getSelectedCountryData");
					if(Object.keys(selCountry).length)
						formData[numbField.attr('name')] =  numbField.intlTelInput("getNumber");
					else
						formData[numbField.attr('name')] =  numbField.val();
				}*/
			});
			if(formData['referralNetwork'] == 'Client'){
				formData['referralId'] = formData['clientId'];
				formData['referralName'] = formData['clientName'];
			}
			else if(formData['referralNetwork'] == 'Staff'){
				formData['referralId'] = formData['staffId'];
				formData['referralName'] = formData['staffName'];
			}
			else if(formData['referralNetwork'] == 'Professional network'){
				formData['referralId'] = formData['proId'];
				formData['referralName'] = formData['proName'];
			}
			else{
				formData['referralId'] = '';
				formData['referralName'] = '';
			}
		}
		else if(stepNumb == 2){
			/*$("select.preferredTraingDays").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});*/
			$("select.intensity").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});

			$("select.paPerWeek").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
			$("select.paSession").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
		}
		else if(stepNumb == 3){
			$("select.medCond").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
		}
		else if(stepNumb == 5){
			var fitgoalKey = '';
			var fitgoalVals = JSON.parse($('input[name="goalFitnessComponents"]').val())
			$.each(fitgoalVals, function(key,value){
				$.each(value, function(k,v){
					if(k == 'id')
						fitgoalKey = v;
					if(k == 'children'){
						$.each(v, function(x,y){
							if(!formData[fitgoalKey])
								formData[fitgoalKey] = y.id;
							else
								formData[fitgoalKey] += ','+y.id;
						});
					}
				});
			});
			$("select.goalWantfeel").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
			$("select.lifestyleImprove").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
			$("select.goalWantTobe").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
			$("select.goalWantHave").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
			$("select.motivationImprove").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
			
			$("select.healthGoals").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});
		}
		 // var smartGoalNotesOPtion=$('select[name="smart_goal_option"]').val();
			// formData['smart_goal_option'] =smartGoalNotesOPtion;

		//formData['client_id'] = jQuery(wizardForm).find('[name="client_id"]').val();
		formData['parqId'] = $('#form input[name="parqId"]').val();
		formData['_token'] = jQuery(wizardForm).find('[name="_token"]').val();
		formData['stepNumb'] = stepNumb;
           console.log(formData);
		if(wizardForm.find('input[name="waiverComp"]').val() == 1){
			swal({
		        title: "Are you sure to update the PARQ?",
		        type: 'warning',
		        allowOutsideClick: true,
		        showCancelButton: true,
		        confirmButtonText: 'Yes',
		        confirmButtonColor: '#ff4401'
		    }, 
		    function(isConfirm){
				if(isConfirm)
					submitStepDataAjax(formData, stepNumb, toStep, showSuccess, stepId, instance);
			});
			return false;
		}
		submitStepDataAjax(formData, stepNumb, toStep, showSuccess, stepId, instance);
		//console.log(formData);
	}
	var submitStepDataAjax = function(formData, stepNumb, toStep, showSuccess, stepId, instance){
		if(typeof toStep != 'undefined' && toStep != '')
			isNextStepDone = $('a[href="#step-'+toStep+'"]').hasClass('done');
		else
			isNextStepDone = true;

		jQuery.ajax({
			url: '../saveparq',
			type: 'POST',
			data: {
				'formData':formData,
				'_token':formData['_token']
			},
			success: function(data){
				var data = JSON.parse(data);
				if(data.status == "updated"){
					if(instance != undefined && instance.hasClass('injuryAlert')){
						instance.closest('.modal').find('.injuryShowAlert').show();
					}
					else{
					if(stepNumb == 1){
						if(formData['referrer'] != 'onlinesocial' && formData['referrer'] != 'mediapromotions')
							wizardForm.find('input[name="referencewhere"]').val('');

						if(formData['referrer'] != 'socialmedia')
							wizardForm.find('input[name="otherName"]').val('');

						realTimeUpdate('email', formData['primEm']);
						realTimeUpdate('phone', formData['contactNo']);
						realTimeUpdate('firstName', formData['firstName']);
						realTimeUpdate('lastName', formData['lastName']);
						realTimeUpdate('occupation', formData['occupation']);
						realTimeUpdate('gender', formData['gender']);
						realTimeUpdate('dob', formData['yyyy']+'-'+formData['mm']+'-'+formData['dd']);
						//if(!formData['isReferenceDeleted'])
							realTimeUpdate('referralNetwork', formData['referralNetwork']+'|'+formData['referralId']+'|'+formData['referralName']+'|'+formData['referrer']+'|'+formData['referencewhere']+'|'+formData['otherName']);

					}

					if(stepNumb > stepCompleted){
						realTimeUpdate('parqStatus', 5-stepNumb);
						stepCompleted = stepNumb;
					}

					var $sucMesElem = $(stepId).find('.sucMes');
					if(showSuccess){
						$sucMesElem.html(data.message);
						$sucMesElem.removeClass('hidden');
						if(wizardForm.data('parq-mode') == 'summary'){
							var clientid = $('input[name="client_id"]').val();
							window.location.href = public_url+'client/'+clientid+'?success='+Math.random()+'#panel_assess_progress#step='+stepNumb;//+stepId  #accId=epic-process-panel;	
						}
					}
					else{
						$sucMesElem.html('');
						$sucMesElem.addClass('hidden');
					}

					if(typeof toStep != 'undefined' && toStep != '')
						wizardContent.smartWizard("goToStep", toStep);
					
					if(stepNumb == 5){
						if(wizardForm.data('parq-mode') == 'summary'){
							var clientid = $('input[name="client_id"]').val();
							window.location.href = public_url+'client/'+clientid+'?success='+Math.random()+'#panel_assess_progress#step='+stepNumb;//+stepId  #accId=epic-process-panel;	
						}
						else
							$("#waiverModal").modal();
					}
						
						//window.location.reload();
				
				else if(data.status == "error"){
					$.each(data.errorData, function(key, val){
						$.each(this, function(errorType, message){
							if(errorType == "emailExist" )
								var field = $('input[name="primEm"]');	
							else if(errorType == "phoneExist" )
								var field = $('input[name="contactNo"]');

							setFieldInvalid(field.closest('.form-group'), field.next("span"), message)
						});							
					});
					//wizardContent.smartWizard("goBackward");

					//if(!isNextStepDone)
						//setTimeout(function(){wizardContent.smartWizard('disableStep', toStep)}, 300);
				}
			}
				scrollToTop(stepId);
			}
		}
		});
	}
	var leaveAStepCallback = function(obj, context, finalStep, fromStep,instance = undefined) {
		if(finalStep == undefined)
			finalStep = false;
		if(fromStep == undefined)
			fromStep = '';
		var ifSafeToSwitch = false;
		if(!ifSendAjax){
			ifSafeToSwitch = true;
			scrollToTop(obj.attr('href'));
		}
           else{
			ifSendAjax = false;
			if(instance != undefined && instance.hasClass('injuryAlert')){
				submitStepData(obj, fromStep, '',true ,instance);
			}
			else if(fromStep && validateSteps(fromStep, fromStep)){
				submitStepData(obj, fromStep, '',true ,instance);
				//ifSafeToSwitch = false;
			}
			else if(!fromStep && finalStep && validateSteps(5, 5)){
				//if($("#finish-parq").data('submit') == true)

					submitStepData(obj, 5);
				/*else
					$("#waiverModal").modal();*/

				//ifSafeToSwitch = false;
			}
			else if(!fromStep && !finalStep && validateSteps(context.fromStep, context.toStep)){

				submitStepData(obj, context.fromStep, context.toStep,'',instance);
				//ifSafeToSwitch = false;
			}
		}

		return ifSafeToSwitch;
	};
	var scrollToTop = function(id){
		$("body, html").animate({ 
			scrollTop:$(id).closest('.panel').offset().top 
		}, 200);
	};
	var validateSteps = function(stepnumber, nextstep){
		var isFormValid = wizardForm.valid(),
			$cont = $('#step-'+stepnumber);

		if(stepnumber == 1){
			var gendVal = $cont.find("input[name='gender']:checked").val(),
				$formGroup = $cont.find("input[name='gender']").closest('.form-group');

			if(gendVal == null || gendVal == ""){
				isFormValid = false;
				setFieldInvalid($formGroup, $formGroup.find("span.help-block"), 'Please select a valid gender.')
			}
			else
				setFieldValid($formGroup);

			$(".cntryCode").each(function(){
				if(!(validateContryCode($(this))))
					isFormValid = false;
			});

			var day = $cont.find("select[name='dd']"),
				dayVal = day.val();
				month = $cont.find("select[name='mm']"),
				monthVal = month.val(),
				year = $cont.find("select[name='yyyy']"),
				yearVal = year.val(),
				formGroup = day.closest('.form-group'),
				isDobValid = true;
			
				
			if(dayVal == null || dayVal == "" || dayVal == "DAY"){
				isDobValid = false;
				day.val('')
			}
			if(monthVal == null || monthVal == "" || monthVal == "MONTH"){
				isDobValid = false;
				month.val('')
			}
			if(yearVal == null || yearVal == "" || yearVal == "YEAR"){
				isDobValid = false;
				year.val('')
			}
			if(!isDobValid){
				isFormValid = false;
				setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a valid date of birth.')
			}
			else
				setFieldValid(formGroup);
			
			var heightMetric = $('input[name="height_metric"]').val(),
				heightImperialFt = $('input[name="height_imperial_ft"]').val(),
				heightImperialInch = $('input[name="height_imperial_inch"]').val();

			heightUnit = $('input[name="heightUnit"]').val();
			formGroup = $('#heightUnit').closest('.form-group');
			if(heightUnit == 'Metric'){
				if(!$.isNumeric(heightMetric)){
					isFormValid = false;
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please enter a valid height.')
				}else{
					setFieldValid(formGroup);
				}
			}else{
				if(heightImperialFt == null || !$.isNumeric(heightImperialFt)){
					isFormValid = false;
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please enter a valid height.')
				}else if(heightImperialInch != null && !$.isNumeric(heightImperialInch)){
					isFormValid = false;
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please enter a valid height.')
				}else{
					setFieldValid(formGroup);
				}
			}

			var weightMetric = $('input[name="weight_metric"]').val(),
				weightImperial =$('input[name="weight_imperial"]').val();
			weightUnit = $('input[name="weightUnit"]').val();
			formGroup = $('#weightUnit').closest('.form-group');
			if(weightUnit == 'Metric'){
				if(!$.isNumeric(weightMetric)){
					isFormValid = false;
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please enter a valid weight.')
				}else{
					setFieldValid(formGroup);
				}
			}else{
				if(!$.isNumeric(weightImperial)){
					isFormValid = false;
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please enter a valid weight.')
				}else{
					setFieldValid(formGroup);
				}
			}



		}
		else if(stepnumber == 3){
			if($cont.find("select[name='smoking']").val() == 'Yes'){
				var smokeAmntVal = $cont.find("input[name='smokingPerDay']:checked").val(),
					$formGroup = $cont.find("input[name='smokingPerDay']").closest('#smokeAmnt');

				if(smokeAmntVal == null || smokeAmntVal == ""){
					isFormValid = false;
					setFieldInvalid($formGroup, $formGroup.find("span.help-block"), 'Please select a valid amount.')
				}
				else
					setFieldValid($formGroup);
			}
		}

		if(isFormValid){
			$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").removeClass('wait');
			animateBar(nextstep);
			return true;
		}

		return false;

		/*var isStepValid = false;
		if(numberOfSteps > nextstep && nextstep > stepnumber){
			console.log('1')
			if(wizardForm.valid()){
				//wizardForm.validate().focusInvalid();
				$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").removeClass('wait');
				animateBar(nextstep);
				isStepValid = true;
				return true;
			}
		} 
		else if(nextstep < stepnumber){
			console.log('2')
			$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").addClass('wait');
			animateBar(nextstep);
			return true;
		} 
		else{
			console.log('3')
			if(wizardForm.valid()){
				$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").removeClass('wait');
				displayConfirm();
				animateBar(nextstep);
				return true;
			}
		}*/
	};
	var setFieldInvalid = function ($formGroup, $helpBlock, helpBlockMsg) {
		if($formGroup.hasClass('has-success'))
			$formGroup.removeClass('has-success')
		$formGroup.addClass('has-error');
		$helpBlock.html(helpBlockMsg);
		$helpBlock.show();
	};
	var setFieldValid = function ($formGroup) {
		if($formGroup.hasClass('has-error'))
			$formGroup.removeClass('has-error')
		$formGroup.addClass('has-success');
	};
	var validateContryCode = function(numbField){
		if(numbField.val() != ''){
			var selCountry = numbField.intlTelInput("getSelectedCountryData");
			if(!Object.keys(selCountry).length){
				var formGroup = numbField.closest('.form-group');
				setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a country.')
				return false;
			}
		}
		return true;
	}

	return {
		init : function() {
			initWizard();
		}
	};
}();

$(document).ready(function(){
	$('#referrer').on('change',function() {
    	var referrer =$(this).val();
	    if(referrer == 'referral'){
		 	 $('#refNetwork').show();
		}else{
			$('#refNetwork').hide();
		}
	});
});

$(document).ready(function(){
	$("#referrer").trigger('change');
 })

 $('document').ready(function(){

	
	function setHighlight(step){
		$('#step-'+step+' .bodyPartsDd .dropdown-menu li a').removeClass('highlight');
		$('#step-'+step+' #bodyParts'+step+' option').each(function(){
			$this = $(this);
			var section = $(this).data('part');
			if(section != ''){
				var count = 0;
				$('#step-'+step+' .'+section).find('input[type="checkbox"]').each(function(){
					if($(this).is(':checked')){
						count = count + 1;
					}
				});
				if(count > 0){
					var optionText = $this.text();
					$('#step-'+step+' .bodyPartsDd .dropdown-menu li a').each(function(){
						if($(this).find('span').text() == optionText){
							$(this).addClass('highlight');
						}
					});
				}else{
					if($('#step-'+step+' .'+section).find('textarea').val() != '' && $('#step-'+step+' .'+section).find('textarea').val() != undefined){
						var optionText = $this.text();
						$('#step-'+step+' .bodyPartsDd .dropdown-menu li a').each(function(){
							if($(this).find('span').text() == optionText){
								$(this).addClass('highlight');
							}
						});
					}
				}
			}
			$('#step-'+step+' #bodyParts'+step+'').selectpicker();
		});
	}
	$('#step-3 #bodyParts3').on('show.bs.select', function() {
		setHighlight(3);
	});
	$('#step-5 #bodyParts5').on('show.bs.select', function() {
		setHighlight(5);
	});
});

lightbox.option({
	'resizeDuration': 200,
	'wrapAround': true
  })

  $('.alertHide').click(function(){
	var Modal= $(this);
	Modal.closest('.modal').find('.injuryShowAlert').hide();
})