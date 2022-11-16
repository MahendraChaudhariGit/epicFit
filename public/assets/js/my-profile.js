$(document).ready(function(){
	initCustomValidator();

	$('.js-submitForm').on('click', function(){
		var submitBtn = $(this),
			form = submitBtn.closest('form');

		saveSuccess(form, {action:'hide'});

		var isFormValid = form.valid();

		if(isUserType(['Staff'])){
			if(!validateUrl(form.find("input[name='website']")))
				isFormValid = false;

			if(!validateUrl(form.find("input[name='facebook']")))
				isFormValid = false;

			if(!validateUpload(form.find("input[name='staffProfilePicture']")))
				isFormValid = false;

			if(!validateContryCode(form.find("input[name='phone']")))
				isFormValid = false;

			if(!validateGender(form, 'gender'))
				isFormValid = false;

			if(!validateDob(form, {day:'day', month:'month', year:'year'}))
				isFormValid = false;

			if(!validateStartEndDate({startDateField:form.find("input[name='hourly_start_date']"), endDateField:form.find("input[name='hourly_end_date']")}))
				isFormValid = false;
			
			if(!validateStartEndDate({startDateField:form.find("input[name='per_session_start_date']"), endDateField:form.find("input[name='per_session_end_date']")}))
				isFormValid = false;

			if(!validateStartEndDate({startDateField:form.find("input[name='commission_date_range_start']"), endDateField:form.find("input[name='commission_date_range_end']")}))
				isFormValid = false;

			if(!validateNewPassword({newPwdField:form.find("input[name='staffNewPwd']"), newPwdCnfmField:form.find("input[name='staffNewPwdCnfm']")}))
				isFormValid = false;
		}
		else if(isUserType(['Admin']))
			if(!validateNewPassword({newPwdField:form.find("input[name='adminNewPwd']"), newPwdCnfmField:form.find("input[name='adminNewPwdCnfm']")}))
				isFormValid = false;

		if(isFormValid){
			var formData = {}
			$.each(form.find(':input').serializeArray(), function(i, obj){
				formData[obj.name] = obj.value
			});

			if(isUserType(['Staff'])){
				formData['staff_services'] = prepareMultiSel(form.find('select[name="staff_services"]'));
				formData['staffClasses'] = prepareMultiSel(form.find('select[name="staffClasses"]'));
				formData['phone'] = getFullNumb(form);


				formData['commission_role'] = prepareMultiSel($(form).find('select[name="commission_role"]'));
				formData['commission_income_category'] = prepareMultiSel($(form).find('select[name="commission_income_category"]'));
				formData['commission_referral_source'] = prepareMultiSel($(form).find('select[name="commission_referral_source"]'));

				formData['per_session_role'] = prepareMultiSel($(form).find('select[name="per_session_role"]'));
				formData['per_session_service'] = prepareMultiSel($(form).find('select[name="per_session_service"]'));
				formData['per_session_class'] = prepareMultiSel($(form).find('select[name="per_session_class"]'));
			}

			$.ajax({
				url: form.attr('action'),
				method: "POST",
				data: formData,
				success: function(data){
					var data = JSON.parse(data);

					if(data.status == "updated"){
						if(isUserType(['Staff'])){
							realTimeUpdate('firstName', formData['first_name'], form.find("input[name='first_name']").data('other-targets'));
							realTimeUpdate('lastName', formData['last_name']);
							realTimeUpdate('email', formData['email']);
							realTimeUpdate('phone', formData['phone']);
							realTimeUpdate('gender', formData['gender']);
							realTimeUpdate('jobTitle', formData['job_title']);
							realTimeUpdate('dob', formData['year']+'-'+formData['month']+'-'+formData['day']);

							clearPassword({newPwdField:form.find("input[name='staffNewPwd']"), newPwdCnfmField:form.find("input[name='staffNewPwdCnfm']")});
							form.find('input[name="prevServices"]').val(formData['staff_services'])
						}
						else if(isUserType(['Admin'])){
							realTimeUpdate('firstName', formData['adminFname'], form.find("input[name='adminFname']").data('other-targets'));
							realTimeUpdate('lastName', formData['adminLname']);
							realTimeUpdate('email', formData['adminEmail']);

							clearPassword({newPwdField:form.find("input[name='adminNewPwd']"), newPwdCnfmField:form.find("input[name='adminNewPwdCnfm']")});
						}
						saveSuccess(form, {action:'show', type:'update'});
					}
					else if(data.status == "error"){
						$.each(data.errorData, function(key, val){
							$.each(this, function(errorType, message){
								if(errorType == "emailExist" ){
									if(isUserType(['Admin']))
										var field = form.find("input[name='adminEmail']");									
									else
										var field = form.find("input[name='email']");									
								}
								else if(errorType == "phoneExist" )
									var field = form.find("input[name='phone']");
								
								setFieldInvalid(field.closest('.form-group'), message, field.next("span"))
							});							
						});
					}
				}
			});
		}

		scrollToTop(form);
		return false;
	});

	if(isUserType(['Staff'])){
		/* start: Open specific tab on page load */
		var defaultTab = window.location.hash;
		if(defaultTab)
			switchTab(defaultTab)
		/* end: Open specific tab on page load */
	}
});