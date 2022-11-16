'use strict';
var FormWizard = function () {
    var wizardContent = "";
    var wizardForm = '#form';
    var numberOfSteps = $('.swMain > ul > li').length;
    var ifSendAjax = false;
    var initWizard = function () {
        // function to initiate Wizard Form
        wizardContent.smartWizard({
            //selected: openStep,
            //enableAllSteps: $('#wizard').data('is-edit'),
            keyNavigation: false,
            onLeaveStep: leaveAStepCallback,
            onShowStep: onShowStep,
        });
        var numberOfSteps = 0;
        initValidator();
    };
    
     
    var initValidator = function () {
        
        $.validator.setDefaults({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            ignore: ':hidden',
            rules: {
                //firstName: {
                //    minlength: 2,
                //    required: true
                //},
                //lastName: {
                //    minlength: 2,
                //    required: true
                //},
                // email: {
                //    required: true,
                //    email: true
                //},
                //password: {
                //    minlength: 6,
                //    required: true
                //},
                //password2: {
                //    required: true,
                //    minlength: 5,
                //    equalTo: "#password"
                //}
            },
            messages: {
                firstName: "First Name is required",
                lastName: "Last Name is required"
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });
    };
    var displayConfirm = function () {
        $('.display-value', form).each(function () {
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
    };
    var onShowStep = function (obj, context) {
		$(".skipnextbutton").unbind("click").click(function(e){
			wizardContent.smartWizard("goForward");
		});

    	if(context.toStep == numberOfSteps){
    		$('.anchor').children("li:nth-child(" + context.toStep + ")").children("a").removeClass('wait');
            //displayConfirm();
    	}
        $(".next-step").unbind("click").click(function (e) {
			e.preventDefault();
            ifSendAjax = true;
            wizardContent.smartWizard("goForward");
        });
        $(".back-step").unbind("click").click(function (e) {
            e.preventDefault();
            wizardContent.smartWizard("goBackward");
        });
    };
    var leaveAStepCallback = function (obj, context) {
    	var formId = wizardForm + '-' + context.fromStep,
			fw = $(formId),
			msgElem = fw.find('.sucMes'),
			isNextStepDone = $('a[href="#step-'+context.toStep+'"]').hasClass('done');

		if(!msgElem.hasClass('hidden'))
			msgElem.addClass('hidden');

		/*//if(context.fromStep == '1' || context.fromStep == '2' || context.fromStep == '3' || context.fromStep == '4' || context.fromStep == '5') {
			if(context.fromStep == '1'){
				$("input.businessId").val(45);
				
			}
			$('select').selectpicker('refresh');
				jumpToNextStep(wizardForm, context);
				return true;
			
		//}*/
		

		if(ifSendAjax){
			ifSendAjax = false;
			var isValid = validateSteps(context.fromStep, context.toStep, fw);

			/* REMOVE */
			/*if(context.fromStep == '1'|| context.fromStep == '2' || context.fromStep == '3' || context.fromStep == '4' || context.fromStep == '6'){

				$("input.businessId").val(300);
				jumpToNextStep(wizardForm, context);

				return true;
			}*/
			
			if(context.fromStep == '1') {
				if(!(validateUrl(fw.find("input[name='website']"))))
					isValid = false;

				if(!(validateUrl(fw.find("input[name='facebook']"))))
					isValid = false;

				if(!(validateUpload(fw.find("input[name='logo']"))))
					isValid = false;

				if(!(validateContryCode(fw.find("input[name='phone']"))))
					isValid = false;

				/*if(!(validateBussSlug(fw.find("input[name='cp_web_url']"))))
					isValid = false;*/
			}
			else if (context.fromStep == '2' && fw.find("input[name='venue']").val() == 'Location') {
				if(!(validateUrl(fw.find("input[name='website']"))))
					isValid = false;

				if(!(validateUrl(fw.find("input[name='facebook']"))))
					isValid = false;

				if(!(validateContryCode(fw.find("input[name='phone']"))))
					isValid = false;
			}
			else if (context.fromStep == '3') {
				var loginWithEmailField = fw.find("input[name='login_with_email']"),
				newPwdField = fw.find("input[name='staffNewPwdd']"),
				newPwdCnfmField = fw.find("input[name='staffNewPwdCnfmm']");
				if(loginWithEmailField.is(':checked') && !validateNewPassword({newPwdField:newPwdField, newPwdCnfmField:newPwdCnfmField}))
					isValid = false;
				else{
					setFieldNeutral(newPwdField);
					setFieldNeutral(newPwdCnfmField);
				}

				if(!(validateUrl(fw.find("input[name='website']"))))
					isValid = false;

				if(!(validateUrl(fw.find("input[name='facebook']"))))
					isValid = false;

				if(!(validateUpload(fw.find("input[name='staffProfilePicture']"))))
					isValid = false;

				if(!(validateContryCode(fw.find("input[name='phone']"))))
					isValid = false;

				var gendVal = fw.find("input[name='gender']:checked").val();
				var formGroup = fw.find("input[name='gender']").closest('.form-group');
				if(gendVal == null || gendVal == ""){
					isValid = false;
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a valid gender.')
				}
				else
					setFieldValid(formGroup);

				var day = fw.find("select[name='day']");
				var dayVal = day.val();
				var month = fw.find("select[name='month']");
				var monthVal = month.val();
				var year = fw.find("select[name='year']");
				var yearVal = year.val();
				var formGroup = day.closest('.form-group');
				var isDobValid = true;
				if(dayVal == null || dayVal == ""){
					isDobValid = false;
					day.val('')
				}
				if(monthVal == null || monthVal == ""){
					isDobValid = false;
					month.val('')
				}
				if(yearVal == null || yearVal == ""){
					isDobValid = false;
					year.val('')
				}
				if(!isDobValid){
					isValid = false;
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a valid date of birth.')
				}
				else
					setFieldValid(formGroup);

				var startDateField = fw.find("input[name='hourly_start_date']"),
					endDateField = fw.find("input[name='hourly_end_date']"),
					startDate = startDateField.val(),
					endDate = endDateField.val(),
					formGroup = startDateField.closest('.form-group');

				if(startDate != '' && endDate != '' && moment(startDate, 'ddd, D MMM YYYY').isAfter(moment(endDate, 'ddd, D MMM YYYY'))){
					setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a valid date.')
					isValid = false;
				}
				else
					setFieldValid(formGroup);
			}
			else if (context.fromStep == '5') {
				if(!(validateUpload(fw.find("input[name='productImage']"))))
					isValid = false;

				if(!(validateCkeditor(fw.find("textarea[name='description']"))))
					isValid = false;

				if(!(validateManualDate(fw.find('input[name="expirey_date"]'))))
					isValid = false;
			}
			else if (context.fromStep == '6') {
				/*var $emailFieldVal = fw.find('input[name="email"]').val().trim();
				var $phoneNumbField = fw.find('input[name="numb"]');
				var $phoneNumbFieldVal = fw.find('input[name="numb"]').val().trim();
				if ($emailFieldVal == '' && $phoneNumbFieldVal == '') {
					isValid = false;
					$('#reqMsg').removeClass('hidden');
				}
				else if ($phoneNumbFieldVal != '') {
					var cntryData = $phoneNumbField.intlTelInput("getNumber")
					if (cntryData)
						$phoneNumbField.val(cntryData)
				}
				if ($emailFieldVal != '' || $phoneNumbFieldVal != '')
					$('#reqMsg').addClass('hidden');*/
				var emailFieldVal = fw.find('input[name="email"]').val().trim(),
					phoneNumbField = fw.find('input[name="numb"]'),
					phoneNumbFieldVal = phoneNumbField.val().trim(),
					newClientReqMsg = $('#reqMsg');

				if(emailFieldVal == '' && phoneNumbFieldVal == ''){
					newClientReqMsg.removeClass('hidden');
					isValid = false;
				}
				else if(emailFieldVal != '' || phoneNumbFieldVal != ''){
					newClientReqMsg.addClass('hidden');
					if(phoneNumbFieldVal != '' && !(validateContryCode(phoneNumbField)))
						isValid = false;
				}


				var loginWithEmailField = fw.find("input[name='login_with_email']"),
					ReqMsgloginEmail = $('#reqMsgloginEmail'),
					newPwdField = fw.find("input[name='clientNewPwd']"),
					newPwdCnfmField = fw.find("input[name='clientNewPwdCnfm']");

				if(loginWithEmailField.is(':checked') && !validateNewPassword({newPwdField:newPwdField, newPwdCnfmField:newPwdCnfmField}))
						isValid = false;
				else{
					setFieldNeutral(newPwdField);
					setFieldNeutral(newPwdCnfmField);
				}
					
				if(loginWithEmailField.is(':checked') && emailFieldVal == ''){
					ReqMsgloginEmail.removeClass('hidden');
					isValid = false;
				}
				else
					ReqMsgloginEmail.addClass('hidden');
			}

			if (isValid) {
				var form = $(wizardContent).find(formId),
					requestUrl = form.attr('action'),
					allData = $(wizardContent).find(formId).serializeArray(),
					formatedData = {};


				$(allData).each(function (key, data) {
					var name = data['name'];
					formatedData[name] = data['value'];
				});

				if(context.fromStep == '1'){
					var businessId = $("input.businessId").val();
					if(businessId){
						requestUrl = $('meta[name="public_url"]').attr('content')+'settings/businesses/'+businessId;
						formatedData['_method'] = 'PATCH';
					}

					formatedData['phone'] = getFullNumb(fw);
				}
				else if(context.fromStep == '2'){
					if(formatedData.venue == 'Area')
						formatedData['stuff_selection'] = fw.find('select#stuff_selection').val();
					else
						formatedData['phone'] = getFullNumb(fw);
				}
				else if(context.fromStep == '3'){
					formatedData['staff_services'] = prepareMultiSel(fw.find('select[name="staff_services"]'));
					formatedData['staffClasses'] = prepareMultiSel(fw.find('select[name="staffClasses"]'));
					formatedData['phone'] = getFullNumb(fw);
				}
				else if (context.fromStep == '4'){
					if(formatedData.form_type_opt == 'Service'){
						if(formatedData['catText'] == '1 on 1')
							formatedData['one_on_one_staffs'] = prepareMultiSel(fw.find('select[name="one_on_one_staffs"]'));
						else if(formatedData['catText'] == 'TEAM Training')
							formatedData['team_staffs'] = prepareMultiSel(fw.find('select[name="team_staffs"]'));

						formatedData['area'] = prepareMultiSel(fw.find('select[name="area"]'));
					}
					else{
						formatedData['classAreas'] = prepareMultiSel(fw.find('select[name="classAreas"]'));
						formatedData['classStaffs'] = prepareMultiSel(fw.find('select[name="classStaffs"]'));
					}
				}
				else if (context.fromStep == '5'){
					formatedData['pro_category'] = prepareMultiSel(fw.find('select[name="pro_category"]'));
					formatedData['pro_size'] = prepareMultiSel(fw.find('select[name="pro_size"]'));
					
					var expirey_date = fw.find('input[name="expirey_date"]').val();
					var expirey_date_after_format = '';
					if(expirey_date)
						expirey_date_after_format = dateStringToDbDate(expirey_date);

					if(expirey_date_after_format != 'Invalid date')
						formatedData['expirey_date'] = expirey_date_after_format;
					else
						formatedData['expirey_date'] = '';

					formatedData['description'] = CKEDITOR.instances.description.getData();
					/*var hisotyrArr = formatedData['history'].split('-'),
						firstDate = moment(hisotyrArr[0], 'MMMM D,YYYY').format("YYYY/MM/DD"),
						lastDate = moment(hisotyrArr[1], 'MMMM D,YYYY').format("YYYY/MM/DD");

					formatedData['history'] = firstDate + '-'+ lastDate;*/
				}
				else if (context.fromStep == '6'){
					/*var phoneNumbField = fw.find('input[name="numb"]');
					if(phoneNumbField.val() != '')
						formatedData['numb'] = phoneNumbField.intlTelInput("getNumber");*/
					formatedData['numb'] = getFullNumb(fw, fw.find('input[name="numb"]'));
				
					var $referralNetworkFieldVal = fw.find('input[name="referralNetwork"]:checked').val();
					if ($referralNetworkFieldVal == 'Client')
						formatedData['referralId'] = fw.find('input[name="clientId"]').val()
					else if ($referralNetworkFieldVal == 'Staff')
						formatedData['referralId'] = fw.find('input[name="staffId"]').val()
					else if ($referralNetworkFieldVal == 'Professional network')
						formatedData['referralId'] = fw.find('input[name="proId"]').val()
					else
						formatedData['referralId'] = '';

					formatedData['goalHealthWellness'] = prepareMultiSel(fw.find('select[name="goalHealthWellness"]'));
				}

				$.ajax({
					url: requestUrl,
					method: "POST",
					data: formatedData,
					success: function (data) {
						var data = JSON.parse(data);
						if(data.status == "added") {
							if(context.fromStep == '1') {
								$("input.businessId").val(data.insertId);
								populateStep2AddrFields(formatedData);
							}
							else if(context.fromStep == '2') {
								if (formatedData.venue == 'Location') {
									updateRelatedDd($('select.location'), data.insertId, formatedData.location_training_area);

									var step7Form = $('#form-7');
									$(step7Form).find("input[name='address_line_one']").val(formatedData.address_line_one);
									$(step7Form).find("input[name='address_line_two']").val(formatedData.address_line_two);
									$(step7Form).find("input[name='city']").val(formatedData.city);
									$(step7Form).find("select[name='country']").val(formatedData.country);
									$(step7Form).find("select[name='country']").trigger("changed");
									$(step7Form).find("select.states").data('selected', formatedData.state);
									$(step7Form).find("input[name='postal_code']").val(formatedData.postal_code);
								}
							}
							else if (context.fromStep == '3'){
								if(!data.linkedWithArea)
									$('#form-4 select.staff').removeClass('staff')
								updateRelatedDd($('select.staff'), data.insertId, formatedData.first_name + ' ' + formatedData.last_name);
								$('#form-4 select.classStaff, #form-4 select.serviceStaff').addClass('staff')
							}
							else if(context.fromStep == '4' && formatedData.form_type_opt == 'Service'){
								if(formatedData.category == 1)
									updateRelatedDd($('select.service'), data.insertId, formatedData.team_name)
								else if(formatedData.category == 2)
									updateRelatedDd($('select.service'), data.insertId, formatedData.one_on_one_name)
							}
							
							$('select').selectpicker('refresh');
							jumpToNextStep(/*fw, */wizardForm, context);
						}
						else if(data.status == "updated"){
							if(context.fromStep == '1'){
								populateStep2AddrFields(formatedData);
                           		fw.find("input[name='cp_web_url']").val(data.businessSlug);
                           	}

							$('select').selectpicker('refresh');
							jumpToNextStep(/*fw, */wizardForm, context);
						}
						else if(data.status == "error"){
							$.each(data.errorData, function(key, val){
								$.each(this, function(errorType, message){
									if(errorType == "phoneExist" ){
										if(context.fromStep == '6')
											var field = $(form).find("input[name='numb']");
										else
											var field = $(form).find("input[name='phone']");
									}
									else if(errorType == "urlExist")
										var field = fw.find("input[name='cp_web_url']");
									else if(errorType == "emailExist" )
										var field = fw.find("input[name='email']");								
									else if(errorType == "locNameExist" )
										var field = fw.find("input[name='location_training_area']");
									
									setFieldInvalid(field.closest('.form-group'), field.next("span"), message)
								});							
							});
							wizardContent.smartWizard("goBackward");
							jumpToPrevStep(wizardForm, context);

							if(!isNextStepDone)
								setTimeout(function(){wizardContent.smartWizard('disableStep', context.toStep)}, 300);
						}
						/*else if (data.status == "emailExistError") {
							var $emailField = fw.find("input[name='email']");
							var $emailFormGroup = $emailField.closest('.form-group');
							setFieldInvalid($emailFormGroup, $emailField.next("span"), data.message)

							wizardContent.smartWizard("goBackward");

							if(!isNextStepDone)
								setTimeout(function(){wizardContent.smartWizard('disableStep', context.toStep)}, 300);
						}
						else if (data.status == "locNameExistError") {
							var locNameField = fw.find("input[name='location_training_area']");
							var locNameFormGroup = locNameField.closest('.form-group');
							setFieldInvalid(locNameFormGroup, locNameField.next("span"), data.message)

							wizardContent.smartWizard("goBackward");

							if(!isNextStepDone)
								setTimeout(function(){wizardContent.smartWizard('disableStep', context.toStep)}, 300);
						}*/
					}
				});
				return true
			}
			else
				return false;
		}
		else{
			jumpToNextStep(/*fw, */wizardForm, context);
			return true;
		}
    };
    var onFinish = function (obj, context) {
        if (validateAllSteps()) {
            alert('form submit function');
            $('.anchor').children("li").last().children("a").removeClass('wait').removeClass('selected').addClass('done').children('.stepNumber').addClass('animated tada');
            //wizardForm.submit();
        }
    };
    var populateStep2AddrFields = function(data){
		var step2Form = $('#form-2'),
			addrOne = step2Form.find("input[name='address_line_one']"),
			addrTwo = step2Form.find("input[name='address_line_two']"),
			city = step2Form.find("input[name='city']"),
			country = step2Form.find("select[name='country']"),
			state = step2Form.find("select.states"),
			postalCode = step2Form.find("input[name='postal_code']"),
			timeZone = step2Form.find("select[name='time_zone']");
		
		if (data.venue_location && data.venue_location == '1') {
			addrOne.val(data.address_line_one);
			addrTwo.val(data.address_line_two);
			city.val(data.city);
			country.val(data.country);
			state.data('selected', data.state);
			postalCode.val(data.postal_code);
			timeZone.val(data.time_zone);
		}
		else{
			addrOne.val();
			addrTwo.val('');
			city.val('');
			country.val('');
			state.data('selected', '');
			postalCode.val('');
			timeZone.val('');
		}
		country.trigger("changed");
	};
    var getFullNumb = function(form, numbField){
		if(numbField == null)
			numbField = form.find('input[name="phone"]');

		if(numbField.val() != ''){
			/*var selCountry = numbField.intlTelInput("getSelectedCountryData");
			if(Object.keys(selCountry).length)
				return numbField.intlTelInput("getNumber");
			else
				return numbField.val();*/
			return numbField.intlTelInput("getNumber");
		}
		return '';
	}
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
    var prepareMultiSel = function ($elem) {
		var val = $elem.val();
		if(val != null)
			return val;
		return '';
	}
	var jumpToNextStep = function (/*fw, */wizardForm, context){
		//scrollToTop(fw);
		var toStepId = wizardForm + '-' +context.toStep,
			fromStepId = wizardForm + '-' +context.fromStep;

		$(toStepId).closest('.panel').find('.panel-heading .js-chevron').trigger('click');
		$(fromStepId).closest('.panel').find('.panel-heading .icon-group-left i').attr('class', 'fa fa-check');
	}
	var jumpToPrevStep = function (wizardForm, context){
		var toStepId = wizardForm + '-' +context.fromStep,
			fromStepId = wizardForm + '-' +context.toStep;

		$(toStepId).closest('.panel').find('.panel-heading .js-chevron').trigger('click');
		$(fromStepId).closest('.panel').find('.panel-heading .icon-group-left i').attr('class', 'fa fa-ellipsis-v');
	}
	/*var scrollToTop = function ($elem) {
		$("body, html").animate({ 
			scrollTop:$elem.closest('.panel').offset().top 
		}, 200);
	}*/
	var updateRelatedDd = function ($dd, value, text) {
		$dd.append('<option value="' + value + '">' + text + '</option>');
		$dd.selectpicker('refresh');
	}
	var validateUpload = function ($uploadField) {
		var uploadVal = $uploadField.val();
		var formGroup = $uploadField.closest('.form-group');
		if(uploadVal == null || uploadVal == ""){
			setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please upload a proper file.')
			return false;
		}
		else{
			setFieldValid(formGroup);
			return true;
		}
	}
	var validateUrl = function ($urlField) {
		var urlVal = $.trim($urlField.val());
		//if (urlVal != null && urlVal != "" && urlVal.indexOf("www.") < 0) {
		if(urlVal != "") {
			var x=urlVal.split('.');
			if( urlVal.indexOf(' ') >= 0 || x.length == 1 ){
				setFieldInvalid($urlField.closest('.form-group'), $urlField.next("span"), 'Please enter a valid URL.')
				return false;
			}
		}
		return true;
	}
	var validateBussSlug = function (slugField) {
		var slug = slugField.val();
		if(slug && !slug.match(/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/)){
			setFieldInvalid(slugField.closest('.form-group'), slugField.next("span"), 'URL must contain a alphabet.')
			return false;
		}
		return true;
	}
	var setFieldInvalid = function ($formGroup, $helpBlock, helpBlockMsg) {
		if($formGroup.hasClass('has-success'))
			$formGroup.removeClass('has-success')
		$formGroup.addClass('has-error');
		$helpBlock.html(helpBlockMsg);
		$helpBlock.show();
	}
	var setFieldValid = function ($formGroup) {
		if($formGroup.hasClass('has-error'))
			$formGroup.removeClass('has-error')
		$formGroup.addClass('has-success');
	}
    var validateSteps = function (stepnumber, nextstep, fw) {
        var isStepValid = false;
        var wizardForm = fw;
        
        if (numberOfSteps >= nextstep && nextstep > stepnumber) {
        	
            // cache the form element selector
            if (wizardForm.valid()) { // validate the form
                wizardForm.validate().focusInvalid();
                for (var i=stepnumber; i<=nextstep; i++){
        		$('.anchor').children("li:nth-child(" + i + ")").not("li:nth-child(" + nextstep + ")").children("a").removeClass('wait').addClass('done').children('.stepNumber').addClass('animated tada');
        		}
                //focus the invalid fields
                isStepValid = true;
                return true;
            };
        } else if (nextstep < stepnumber) {
        	for (i=nextstep; i<=stepnumber; i++){
        		$('.anchor').children("li:nth-child(" + i + ")").children("a").addClass('wait').children('.stepNumber').removeClass('animated tada');
        	}
            
            return true;
        } 
    };
    var validateAllSteps = function () {
        var isStepValid = true;
        // all step validation logic
        return isStepValid;
    };

    return {
        init: function (wizard) {
            wizardContent = $(wizard);
            initWizard();
        }
    };
}();