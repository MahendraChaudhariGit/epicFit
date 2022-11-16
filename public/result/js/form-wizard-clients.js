var FormWizard = function() {
	var wizardContent = $('#wizard');
	var wizardForm = $('#wizard-form');
	var ifSendAjax = false;
	var stepCompleted = 0;
	var initWizard = function() {
		// function to initiate Wizard Form
	

    var steps= $('#selected-step').val();
    wizardContent.smartWizard({
			selected : steps,
			keyNavigation : false,
			onLeaveStep : leaveAStepCallback,
			onShowStep : onShowStep,
		});
		var numberOfSteps = 0;
		animateBar();
		initValidator();
		/*var temp = wizardForm.find('input[name="step_status"]');
		if(temp.length){
			var step_status = temp.val().split(',');
			for(i=0; i<step_status.length; i++){
				wizardContent.smartWizard('enableStep', i+1)
				if($.trim(step_status[i]) != 'completed')
					break;
				stepCompleted++;
			}
		}*/
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
				/*username : {
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
				}*/
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
		if(flag){
			var temp = $('input[name="step_status"]');
			if(temp.length){
				var step_status = temp.val().split(',');
				for(i=0; i<step_status.length; i++){
						// wizardContent.smartWizard('enableStep', i+1)
					if($.trim(step_status[i]) != 'completed')
						break;
					stepCompleted++;
				}
			}
		}
		else{
			stepCompleted = 0;
		}
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
		
		
		$(".submit-step").unbind("click").click(function(e) {
			ifSendAjax = true;
			e.preventDefault();
			leaveAStepCallback(obj, context, false, $(this).data("step"),$(this));
		});
	};
	var submitStepData = function(obj, stepNumb, toStep, showSuccess,instance = undefined){
		var formValid = true;
		if(showSuccess == undefined)
			showSuccess = false;
			
		var stepId = obj.attr('href'),
			formData = {};

			if(toStep != null && toStep != '')
				isNextStepDone = $('a[href="#step-'+toStep+'"]').hasClass('done');
			else
				isNextStepDone = true;
			
		jQuery.each(jQuery(wizardForm).find(stepId).find(':input').serializeArray(), function(i, obj){
			var value = obj.value;
			formData[obj.name] = value.toString();
		});

		if(stepNumb == 1){
			$(".cntryCode").each(function(){
				if($(this).val() != '' && $(this).val() != undefined){
					var cntryData = $(this).intlTelInput("getNumber")
					if(cntryData)
						formData[$(this).attr('name')] = cntryData;
				}
				/*numbField = $(this);
				if(numbField.val() != ''){
					var selCountry = numbField.intlTelInput("getSelectedCountryData");
					if(Object.keys(selCountry).length)
						formData[numbField.attr('name')] =  numbField.intlTelInput("getNumber");
					else
						formData[numbField.attr('name')] =  numbField.val();
				}*/
			});


			/*if(formData['referralNetwork'] == 'Client'){
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
			else
				formData['referralId'] = ''; */
			/********** Validate Form ********/
			// var addressModal = $('#addressModal');
			// if(addressModal.find('input[name="addressline1"]').val() == '' || addressModal.find('input[name="addressline2"]').val() == '' || addressModal.find('input[name="city"]').val() == '' || addressModal.find('input[name="postal_code"]').val() == '' || addressModal.find('select[name="country"]').val() == '' || addressModal.find('select[name="addrState"]').val() == '' || addressModal.find('select[name="timezone"]').val() == '' || addressModal.find('select[name="currency"]').val() == ''){
			// 	swal('All address Fields is required')
			// 	formValid = false;
			// }else{
			// 	$('.addrHelp').text('');
			// }
			// var checkImg = $('#check-img').val();
			// if(checkImg == ''){
			// 	$('.propic').show();
            //     formValid = false;
            //     	$('html, body').animate({
            //    scrollTop: $(".valid-pic").offset().top
            //     }, 2000);
			// }
         



		}
		else if(stepNumb == 2){
			/*$("select.preferredTraingDays").each(function(){
				if($(this).val() != null)
					formData[$(this).attr('name')] = $(this).val();
				else
					formData[$(this).attr('name')] = '';
			});*/
			// $("select.intensity").each(function(){
			// 	if($(this).val() != null)
			// 		formData[$(this).attr('name')] = $(this).val();
			// 	else
			// 		formData[$(this).attr('name')] = '';
			// });
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

			
   //        var smartGoalNotesOPtion=$('select[name="smart_goal_option"]').val();
			// formData['smart_goal_option'] =smartGoalNotesOPtion;

             
		}
		formData['client_id'] = jQuery(wizardForm).find('[name="client_id"]').val();
		formData['_token'] = jQuery(wizardForm).find('[name="_token"]').val();
		formData['stepNumb'] = stepNumb;

		// 	swal({
		//         title: "Are you sure to update the PARQ?",
		//         type: 'warning',
		//         allowOutsideClick: true,
		//         showCancelButton: true,
		//         confirmButtonText: 'Yes',
		//         confirmButtonColor: '#ff4401'
		//     }, 
		//     function(isConfirm){
		//     	// alert(isConfirm);
		// 		if(isConfirm)
		// 			{
		// 				jQuery.ajax({
		// 	url: public_url+'saveparq',
		// 	type: 'POST',
		// 	data: {
		// 		'formData':formData,
		// 		'_token':formData['_token']
		// 	},
		// 	success: function(data){
		// 		var data = JSON.parse(data);
		// 		if(data.status == "updated"){
		// 			if(stepNumb == 1){
		// 				if(formData['referrer'] != 'onlinesocial' && formData['referrer'] != 'mediapromotions')
		// 					wizardForm.find('input[name="referencewhere"]').val('')
		// 				realTimeUpdate('email', formData['primEm']);
		// 				realTimeUpdate('phone', formData['contactNo']);
		// 				realTimeUpdate('firstName', formData['firstName']);
		// 				realTimeUpdate('lastName', formData['lastName']);
		// 				realTimeUpdate('occupation', formData['occupation']);
		// 				realTimeUpdate('gender', formData['gender']);
		// 				realTimeUpdate('dob', formData['yyyy']+'-'+formData['mm']+'-'+formData['dd']);
		// 				realTimeUpdate('referralNetwork', formData['referralNetwork']+'|'+formData['referralId']+'|'+formData['referralName']);
		// 			}

		// 			if(stepNumb > stepCompleted){
		// 				//realTimeUpdate('parqStatus', 5-stepNumb);
		// 				stepCompleted = stepNumb;
		// 			}

		// 			var $sucMesElem = $(stepId).find('.sucMes');
		// 			if(showSuccess){
		// 				$sucMesElem.html(data.message);
		// 				$sucMesElem.removeClass('hidden');
		// 			}
		// 			else{
		// 				$sucMesElem.html('');
		// 				$sucMesElem.addClass('hidden');
		// 			}
					
		// 			if(stepNumb == 5)
		// 				window.location.reload();
		// 		}
		// 		else if(data.status == "error"){
		// 			$.each(data.errorData, function(key, val){
		// 				$.each(this, function(errorType, message){
		// 					if(errorType == "emailExist" )
		// 						var field = $('input[name="primEm"]');	

		// 					setFieldInvalid(field.closest('.form-group'), field.next("span"), message)
		// 				});							
		// 			});
		// 			wizardContent.smartWizard("goBackward");

		// 			if(!isNextStepDone)
		// 				setTimeout(function(){wizardContent.smartWizard('disableStep', toStep)}, 300);
		// 		}
		// 		scrollToTop(stepId);
		// 	}
		// });
			// 		}
			// 		else
			// 		{
			// 			return false;			
			// 		}
			// });
			console.log(formData);
		if(formValid){
			jQuery.ajax({
				url: public_url+'saveparq',
				type: 'POST',
				data: {
					'formData':formData,
					'_token':formData['_token']
				},
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "updated"){
						if(instance.hasClass('injuryAlert')){
							instance.closest('.modal').find('.injuryShowAlert').show();
						}
						else if(stepNumb != 5)
						{
							swal({
								title: 'Success',
								text:'Data Added Successfully',
								allowOutsideClick: false,
								showCancelButton: false,
								confirmButtonText: 'ok',
								confirmButtonColor: '#ff4401',
								animation: false
							}, 
							function(isConfirm){
								if(isConfirm){
									if(instance != undefined){
										if(instance.hasClass('nextStepBtn')){
											window.location.href = instance.data('step-url');
										}
										else{
											// window.location.reload();
										}
									}else{
										
										// window.location.reload();
										}
									}
								
							});
						}
					
						if(stepNumb == 1){
	
							if(formData['referrer'] != 'onlinesocial' && formData['referrer'] != 'mediapromotions')
								wizardForm.find('input[name="referencewhere"]').val('')
							realTimeUpdate('email', formData['primEm']);
							realTimeUpdate('phone', formData['contactNo']);
							realTimeUpdate('firstName', formData['firstName']);
							realTimeUpdate('lastName', formData['lastName']);
							realTimeUpdate('occupation', formData['occupation']);
							realTimeUpdate('gender', formData['gender']);
							realTimeUpdate('dob', formData['yyyy']+'-'+formData['mm']+'-'+formData['dd']);
							realTimeUpdate('referralNetwork', formData['referralNetwork']+'|'+formData['referralId']+'|'+formData['referralName']);
						}
	
						if(stepNumb > stepCompleted){
							//realTimeUpdate('parqStatus', 5-stepNumb);
							stepCompleted = stepNumb;
						}
	
						var $sucMesElem = $(stepId).find('.sucMes');
						if(showSuccess){
							$sucMesElem.html(data.message);
							$sucMesElem.removeClass('hidden');
						}
						else{
							$sucMesElem.html('');
							$sucMesElem.addClass('hidden');
						}
						// if(stepNumb == 5)
						// 	window.location.reload();
					}
					else if(data.status == "error"){
						$.each(data.errorData, function(key, val){
							$.each(this, function(errorType, message){
								if(errorType == "emailExist" )
									var field = $('input[name="primEm"]');	
	
								setFieldInvalid(field.closest('.form-group'), field.next("span"), message)
							});							
						});
						wizardContent.smartWizard("goBackward");
	
						if(!isNextStepDone)
							setTimeout(function(){wizardContent.smartWizard('disableStep', toStep)}, 300);
					}
					// scrollToTop(stepId);
				}
			});
		}


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
			if(fromStep && validateSteps(fromStep, fromStep)){
				submitStepData(obj, fromStep, '', true,instance);
				ifSafeToSwitch = true;
			}
			else if(!fromStep && finalStep && validateSteps(5, 5)){
				/*if($("#finish-parq").data('submit') == true)
					submitStepData(obj, 5);
				else
					$("#waiverModal").modal();*/

				
				var smartGoalNotes= $('#smartGoalNotes').val();
				var smartGoalSpecific=$('input[name="smartGoalSpecific"]').val();
				var smartGoalMeasurable=$('input[name="smartGoalMeasurable"]').val();
				var smartGoalAchievable=$('input[name="smartGoalAchievable"]').val();
				var smartGoalRelevent=$('input[name="smartGoalRelevent"]').val();
				var smartGoalTime=$('input[name="smartGoalTime"]').val();
				if(smartGoalNotes=='' && (smartGoalSpecific== '' &&smartGoalMeasurable=='' && smartGoalAchievable=='' && smartGoalRelevent== '' && smartGoalTime=='')){
				$('.smartGoal').show();
				$('html, body').animate({
               scrollTop: $("#accordion-xs").offset().top
                }, 2000);
					}
			    else{
				$('.smartGoal').hide();
				submitStepData(obj, 5);
				$("#waiverModal").modal();
				
				ifSafeToSwitch = true;
			}
			}
			else if(!fromStep && !finalStep && validateSteps(context.fromStep, context.toStep)){
				submitStepData(obj, context.fromStep, context.toStep, '' ,instance);
				ifSafeToSwitch = true;
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
		if(wizardForm.selector == '#undefined-form')
			wizardForm = $('#wizard-form')
		
		var isFormValid = wizardForm.valid(),
			$cont = $('#step-'+stepnumber);

		if(stepnumber == 1){
			var gendVal = $cont.find("input[name='gender']").val(),
				$formGroup = $cont.find("input[name='gender']").closest('.form-group');

			if(gendVal == null || gendVal == ""){
				isFormValid = false;
				setFieldInvalid($formGroup, $formGroup.find("span.help-block"), 'Please select a valid gender.')
			}
			else
				setFieldValid($formGroup);

			$(".cntryCode").each(function(){
				if(!(validateContryCode($(this)))){
				swal('Please fill Country Code');
                  isFormValid = false;
				}
			});

			var genderValue=$('#gender0').val();
			if(genderValue == '')
			{
          	   $('.genderValidation').show();
          	   $('html, body').animate({
               scrollTop: $(".genderShow").offset().top
				}, 2000);
				isFormValid = false;
			}

			var day = $cont.find("select[name='dd']"),
				dayVal = day.val();
				month = $cont.find("select[name='mm']"),
				monthVal = month.val(),
				year = $cont.find("select[name='yyyy']"),
				yearVal = year.val(),
				formGroup = day.closest('.vp-item'),
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
				formGroup.find('.vp-tooltip').show();
				$('html, body').animate({
			   		scrollTop: $("#dobSection").offset().top
			    }, 2000);
				// setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a valid date of birth.')
			}
			else
				formGroup.find('.vp-tooltip').hide();
				// setFieldValid(formGroup);
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
		init : function(wizard) {
			if(typeof wizard == 'undefined')
				wizardForm = $('#wizard-form');
			else
				wizardForm = $(wizard+'-form');

			if(wizard == '#wizard')
				flag = true;
			else
				flag = false;

			wizardContent = $(wizard);
			initWizard();
		}
	};
}();


$('body').on('click','.res-btn-save',function(e){


     var physicalActivity = $('#physical-activity').val();

if(physicalActivity=='' || physicalActivity==undefined){

    $('.physical_activity').show();

    }

});

$('body').on('click','.phys_activity',function(e){

 var physicalActivity=$('.vp-wrap-custom-value').val();
    if(physicalActivity != ''){

    $('.physical_activity').hide();


        }
    });

$('body').on('click','.res-btn-save',function(e){


     var activityWeek= $('#activity-per-week').val();

if(activityWeek=='' || activityWeek==undefined){

    $('.activity-week').show();

    }

});

$('body').on('click','.activityweek',function(e){

 var weekActivity=$('.vp-wrap-custom-value').val();
    if(weekActivity != ''){

    $('.activity-week').hide();


        }
    });


$('body').on('click','.res-btn-save',function(e){


     var currentAverage= $('#current-av').val();

if(currentAverage=='' || currentAverage== undefined){

    $('.currentaverage').show();

    }

});

$('body').on('click','.current-avg',function(e){

 var currentAverage=$('.vp-wrap-custom-value').val();
    if(currentAverage != ''){

    $('.currentaverage').hide();


        }
    });

$('body').on('click','.res-btn-save',function(e){
var enjoyActivity= $('#enjoy_activity').val();
if(enjoyActivity=='' || enjoyActivity== undefined){
$('.enjoy-activity').show();
}
});

$('body').on('input','#paEnjoy',function(e){
var enjoyActivity=$('#paEnjoy').val();
    if(enjoyActivity != ''){
 $('.enjoy-activity').hide();
} });


$('body').on('click','.res-btn-savedata',function(e){
var vpAllergies= $('#vp-allergies').val();
if(vpAllergies=='' || vpAllergies== undefined){
$('.any-allergies').show();
}
});

$('body').on('click','.allergies-any',function(e){
var vpAllergies=$('.vp-wrap-custom-value').val();
    if(vpAllergies != ''){
 $('.any-allergies').hide();
} });

$('body').on('click','.res-btn-savedata',function(e){
var chronicMedication= $('#chronic-medication').val();
if(chronicMedication=='' || chronicMedication== undefined){
$('.chronic-medic').show();
}
});

$('body').on('click','.chronicmedication',function(e){
var chronicMedication=$('.vp-wrap-custom-value').val();
    if(chronicMedication != ''){
 $('.chronic-medic').hide();
} });

$('body').on('click','.res-btn-savedata',function(e){
var smoked= $('#smoked').val();
if(smoked=='' || smoked== undefined){
$('.smoked_last_months').show();
}
});

$('body').on('click','.smoked-value',function(e){
var smoked = $('.vp-wrap-custom-value').val();
    if(smoked != ''){
 $('.smoked_last_months').hide();
} });

$('body').on('click','.res-btn-savedata',function(e){
var personallySuffered= $('#personally-suffered').val();
if(personallySuffered=='' || personallySuffered== undefined){
$('.suffered-from').show();
}
});

$('body').on('click','#medicalCondition',function(e){
var personallySuffered = $('#medicalCondition').val();
    if(personallySuffered != ''){
 $('.suffered-from').hide();
} });

$('body').on('click','.res-btn-savedata',function(e){
var familyMember= $('#familymember').val();
if(familyMember=='' || familyMember== undefined){
$('.family_member').show();
}
});

$('body').on('click','#relMedicalCondition',function(e){
var familyMember = $('#relMedicalCondition').val();
    if(familyMember != ''){
 $('.family_member').hide();
} });

$('body').on('click','.res-btn-step-one',function(e){
var emergencyContact= $('#emergency-contact').val();
if(emergencyContact=='' || emergencyContact== undefined){
$('.emergency-con').show();
}
});

$('body').on('input','#ecName',function(e){
var emergencyContact=$('#ecName').val();
    if(emergencyContact != ''){
 $('.emergency-con').hide();
} });

// $('body').on('click','.res-btn-step-one',function(e){
// var validNumber= $('#valid-number').val();
// if(validNumber=='' || validNumber== undefined){
// $('.valid-contact').show();
// }
// });

// $('body').on('input','#ecNumber',function(e){
// var validNumber=$('#ecNumber').val();
//     if(validNumber != ''){
//  $('.valid-contact').hide();
// } });

$('.hidevalidation').click(function()
{
$('.propic').hide();
});

$('.showRelation').click(function()
{
	$('#ecrelationModal').hide();

var ecRelation=$('#ecRelation').val();
					var html = '<div class="form-group ">\
					<label class="strong">Relationship</label>\
					<input class="form-control" value="'+ecRelation+'">\
					</div>';
					$('.showNotes').append(html);
				
});


            $('#changeRelation').on('input',function(){
	        var changeRelation=$('#changeRelation').val();
	        $('#ecRelation').val(changeRelation);
                });

            $('body').on('click', '.remove-img', function(e){
			e.preventDefault();
			var $this = $(this),
				preImgPath = $this.find('img').attr('src'),
				preImg = preImgPath.substring(preImgPath.lastIndexOf("/")+1);

			$.post(public_url+'remove/image', {preImg:preImg}, function(response){
				var data = JSON.parse(response);
				if(data.status == 'success')
				$('.clientpicPreviewPics').attr('src','');
             	$('input[name="clientpic"]').val('');
				$('#userPic').removeClass('hide');
			
				$('.openCamera').removeClass('hide');
				$('.takePic').addClass('hide');
				$('.picRemove').addClass('hide');


			});
		});

$('.genderShow').on('click',function()
{
	$('.genderValidation').hide();
})
Webcam.set({
    width: 320,
    height: 240,
    image_format: 'jpeg',
	jpeg_quality: 90,
	constraints: constraints,
});
$('body').on('click','.openCamera',function(e) {
	$('#webcam-modal').modal('show');
		Webcam.attach('#camera');

		// start();

});
$('body').on('click','.snap',function(){
	Webcam.snap(function(data_uri) {
		$('#imageCrop').attr('src',data_uri);
		$.post(public_url+'photo/capture-save',{data:data_uri},function(file, response){
			// $('#imageCrop').attr('src',data_uri);
			$('#cropperModal').find('input[name="photoName"]').val(file);
			$('#cropperModal').modal('show');
		});
		Webcam.reset();
		$('#webcam-modal').modal('hide');
		$('#cropperModal').modal('show');
	});
});
$('.close-webcam').click(function(){
	Webcam.reset();
	$('#webcam-modal').modal('hide');
})
$('.openStep').on('click',function(){
	var url=$(this).data('url');
	window.location.href= url;

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

$('document').ready(function(){
	$('html, body').animate({
        scrollTop: $("#wizard").offset().top
    }, 1000);
})

$('.alertHide').click(function(){
	var Modal= $(this);
	Modal.closest('.modal').find('.injuryShowAlert').hide();
})

$('.hightlightNext').click(function(){
	$('.nextStepBtn').attr('style', 'background-color:#f94211');
})

$('.hightlightFinish').click(function(){
	$('#finish-parq').attr('style', 'background-color:#f94211 !important; color:#ffffff;');
})