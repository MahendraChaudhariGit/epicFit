var public_url = $('meta[name="public_url"]').attr('content'),
rowno;

/* Custom validation */
function initCustomValidator(){
	$.validator.setDefaults({
		errorElement : "span", // contain the error msg in a small tag
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
		success : function(label, element) {
			label.addClass('help-block valid');
			// mark the current input as valid and display OK icon
			$(element).closest('.form-group').removeClass('has-error');
		},
		highlight : function(element) {
			$(element).closest('.help-block').removeClass('valid');
			// display OK icon
			$(element).closest('.form-group').addClass('has-error');
			// add the Bootstrap error class to the control group
		},
		unhighlight : function(element) {// revert the change done by hightlight
			$(element).closest('.form-group').removeClass('has-error');
			// set error class to the control group
		}
	});
}

/**
 * Check if logged in user is any of the given account type.
 *
 * @param array $types Types to check against
 * @return boolean 
 */ 
 function isUserType(types){
	//if($.inArray('{{ Auth::user()->account_type }}', types) !== -1)
	if($.inArray(loggedInUser.type, types) !== -1)
		return true;
	return false;
}

function saveSuccess(form, data){
	var msgElem = $(form).find('.sucMes');
	if(data.action == 'show'){
		if(data.type == 'store'){
			var message = 'Data has been saved successfully';
			var color="success";
		}
		else if(data.type == 'update'){
			var message = 'Data has been updated successfully';
			var color="success";
		}
		else if(data.type == 'loginAccountCreate'){
			var message = 'Your account was successfully created. We have sent you details and confirmation e-mail to your mailbox.';
			var color="success";
		}
		else if(data.type == 'overrideTax'){
			var message = 'The tax rate has been applied to all of your services, classes, discounts, products and packages';
			var color="success";
		}
		else if(data.type=='cancelAllReason')
		{
			var message='You will not able to cancel the service';
			var color='warning';
		}
		/*else if(data.type=='priceError')
		{
			 var message='You Net amount is less then Total amount';
			 var color='warning';
			}*/

			msgElem.append(prepareNotific(color,message));
			msgElem.removeClass('hidden');

		}
		else if(data.action == 'hide' && !msgElem.hasClass('hidden')){
		//alert('ok');
		msgElem.html('');
		msgElem.addClass('hidden');
	}
}

/* start: Prepare html for notification messages */
function prepareNotific(type, message, alertGroup){
	if(typeof alertGroup == 'undefined')
		alertGroup = '';

	return '<div class="alert alert-'+getAlertsColor(type)+' '+alertGroup+'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+message+'</div>';
}
/* end: Prepare html for notification messages */

/* start: Determine alerts background color based on the type */
function getAlertsColor(type){
	if(type == 'error')
		return 'danger';
	if(type == 'success')
		return 'success';
	if(type == 'warning')
		return 'warning';
}
/* end: Determine alerts background color based on the type */

/* start: Display notification messages */
function showNotific(content, elem){
	if(typeof elem == 'undefined'){
		elem = calendar;
		clearNotific(elem);
		if(content.search("alert-warning") >= 0 || content.search("alert-danger") >= 0)
			scrollToTop(elem)
	}

	setTimeout(function(){ 
		elem.prepend(content);
	}, 400);
}
/* end: Display notification messages */

/* start: Clear notification messages */
function clearNotific(alertGroup){
	if(typeof alertGroup == 'string')
		$('body').find('.'+alertGroup).remove();
	else
		alertGroup.children('.alert').remove();
}
/* start: Clear notification messages */

function scrollToTop(elem){
	$("body, html").animate({ 
		scrollTop:$(elem).offset().top -70 
	}, 100);
}

function initSelectpicker(dd){
	if(dd.length){
		if($("#super-admin").val() != 'Super Admin' && $("#register-val").val() != 'register'){
			dd.selectpicker({
				noneSelectedText: " -- Select -- "
			});
		}
	}
}

function initTelInput($elem){
	//alert("initTelInput function");
	$elem.intlTelInput({
		initialCountry: "nz",
		/*geoIpLookup:function(callback){
			$.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
				var countryCode = (resp && resp.country)?resp.country:"";
				callback(countryCode);
			});
		},*/
		preferredCountries: ['nz', 'au', 'za']
	});
}

function clearPassword(fields){
	fields.newPwdCnfmField.val(''); 
	fields.newPwdField.val('');
}

function validateNewPassword(fields){
	var staffNewPwdCnfmField = fields.newPwdCnfmField,
	staffNewPwd = fields.newPwdField.val(),
	formGroup = staffNewPwdCnfmField.closest('.form-group');

	if(staffNewPwd && staffNewPwdCnfmField.val() != staffNewPwd){
		setFieldInvalid(formGroup, 'Please renter new password.');
		return false;
	}
	else{
		setFieldValid(formGroup);
		return true;
	}
}

function validateContryCode(numbField){
	if(numbField.val() != ''){
		var selCountry = numbField.intlTelInput("getSelectedCountryData");
		if(!Object.keys(selCountry).length){
			var formGroup = numbField.closest('.form-group');
			setFieldInvalid(formGroup, 'Please select a country.', formGroup.find("span.help-block"))
			return false;
		}
	}
	return true;
}

function validateUpload($uploadField){
	var uploadVal = $uploadField.val();
	var formGroup = $uploadField.closest('.form-group');
	if(uploadVal == null || uploadVal == ""){
		//setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please upload a proper file.')
		setFieldInvalid(formGroup, 'Please upload a proper file.')
		return false;
	}
	else{
		setFieldValid(formGroup);
		return true;
	}
}

function validateCkeditor($uploadField){
	//var uploadVal = $uploadField.val();
	var uploadVal = CKEDITOR.instances.description.getData();
	var formGroup = $uploadField.closest('.form-group');
	formGroup.find('.ckErrorCustom').remove();
	if(uploadVal == null || uploadVal == ""){
		if(formGroup.hasClass('has-success'))
			formGroup.removeClass('has-success')

		formGroup.addClass('has-error');
		formGroup.append('<span class="ckErrorCustom">This field is required.</span>');
		return false;
	}
	else{
		formGroup.removeClass('has-error');
		formGroup.find('.ckErrorCustom').remove();
		return true;
	}
}

function validateManualDate($dateField){
	var expirey_date = $dateField.val();
	var formGroup = $dateField.closest('.form-group');
	var isError = false;
	
	formGroup.removeClass('has-error');
	formGroup.find('.date_format_error').remove();

	var dateFormat =  expirey_date.match(/\b(?:(?:Mon)|(?:Tues?)|(?:Wed(?:nes)?)|(?:Thur?s?)|(?:Fri)|(?:Sat(?:ur)?)|(?:Sun))(?:day)?\b[:\-,]?\s+\d{1,2}\s*?\b[:\-]?\s*[a-zA-Z]{3,9}?\s*\d{4}/);
	if(expirey_date != '' && dateFormat == null){
		if(formGroup.hasClass('has-success'))
			formGroup.removeClass('has-success')

		formGroup.addClass('has-error');
		formGroup.append('<span class="date_format_error error">Invalid date format! &nbsp;Ex: Tue, 1 Jan 2018.</span>');
		return false;
	}
	return true;
}

function getFullNumb(form, numbField){
	if(numbField == null)
		numbField = form.find('input[name="phone"]');
	if(numbField.val() != ''){
		/*var selCountry = numbField.intlTelInput("getSelectedCountryData");
		if(Object.keys(selCountry).length)
			return numbField.intlTelInput("getNumber");
		else
			return numbField.val();*/
		//alert(numbField.intlTelInput("getNumber"));
		return numbField.intlTelInput("getNumber");
	}
	return '';
}

function initTelInputOnChange(elem){
	var formGroup = elem.closest('.form-group');
	setFieldValid(formGroup, formGroup.find("span.help-block"));
}

/* Reset form */
function clearForm(form, callback){
	$(':input', form).each(function(){
		var type = this.type,
		tag = this.tagName.toLowerCase(),
		$this = $(this); 

		if(!$this.hasClass('no-clear')){
			if(tag == 'textarea')
				$this.val('');
			else if(type == 'checkbox' || type == 'radio'){
				if($this.data('default-state') == 'checked')
					$this.prop('checked', true);
				else
					$this.prop('checked', false);
			}
			else if(tag == 'select'){
				this.selectedIndex = -1;
				$this.val('');
				$this.selectpicker('refresh');
			}
			else
				$this.val('');
		}
		setFieldNeutral($this);
	});
	if(typeof callback != 'undefined')
		callback(form);
}

/* Style field as invalid */
function setFieldInvalid(formGroup, helpBlockMsg, helpBlock){
	if(typeof helpBlock == 'undefined')
		var helpBlock = formGroup.find('span.help-block')

	if(formGroup.hasClass('has-success'))
		formGroup.removeClass('has-success')

	formGroup.addClass('has-error');
	helpBlock.html(helpBlockMsg);
	helpBlock.show();
}

/* Reset field style */
function setFieldNeutral($elem){
	var formGroup = $elem.closest('.form-group');
	formGroup.removeClass('has-error has-success');
	formGroup.find('span.error').text('');
	formGroup.find('label.error').text('');
	formGroup.find('span.help-block').hide();
}

function setFieldValid($formGroup, $helpBlock){
	if($formGroup.hasClass('has-error'))
		$formGroup.removeClass('has-error')
	$formGroup.addClass('has-success');
	if($helpBlock != null)
		$helpBlock.hide();
}

function prepareMultiSel($elem){
	var val = $elem.val();
	if(val != null)
		return val;
	return '';
}

function validateRadioButton(form, fieldName){
	var gendVal = form.find("input[name='"+fieldName+"']:checked").val(),
	formGroup = form.find("input[name='"+fieldName+"']").closest('.form-group');

	if(gendVal == null || gendVal == ""){
		//setFieldInvalid(formGroup, 'Please select a type.')
		return false;
	}
	else{
		//setFieldValid(formGroup);
		return true;
	}
}


function validateGender(form, fieldName){
	var isValidRadioFunction = validateRadioButton(form, fieldName);
	var gendVal = form.find("input[name='"+fieldName+"']:checked").val(),
	formGroup = form.find("input[name='"+fieldName+"']").closest('.form-group');
	if(isValidRadioFunction){
		setFieldValid(formGroup);
		return true;
	}
	else{
		setFieldInvalid(formGroup, 'Please select a valid gender.')
		return false;
	}
}
/* 
function validateGender(form, fieldName){
	var gendVal = form.find("input[name='"+fieldName+"']:checked").val(),
		formGroup = form.find("input[name='"+fieldName+"']").closest('.form-group');

	if(gendVal == null || gendVal == ""){
		setFieldInvalid(formGroup, 'Please select a valid gender.')
		return false;
	}
	else{
		setFieldValid(formGroup);
		return true;
	}
}
*/

function openSubview(elem, data){
	//var subview = $('#subview');
	var subview = '';
	if(typeof elem == 'string')
		var targetSubview = elem;
	else{
		var targetSubview = elem.data('target-subview');
		if(elem.is("[data-subview]")){
			subviewId = elem.data('subview');
			subview = $('#'+subviewId);
		}
	}
	if(!subview)
		subview = $('#subview');

	if(targetSubview == 'location')
		var src = 'settings/business/locations/create';
	else if(targetSubview == 'staff')
		var src = 'settings/business/staffs/create';
	else if(targetSubview == 'service')
		var src = 'settings/business/services/create';
	else if(targetSubview == 'class')
		var src = 'settings/business/classes/create';
	else if(targetSubview == 'client')
		var src = 'clients/create';
	else if(targetSubview == 'calendar')
		var src = 'dashboard/calendar-new';
	else if(targetSubview == 'contact')
		var src = 'settings/business/contacts/create';
	else if(targetSubview == 'contact-edit'){
		var src = 'settings/business/contacts/'+data+'/edit';
		data = '';
	}

	if(typeof data == 'undefined' || !data)
		data = '';
	else
		data = '&'+data;

	subview.find("iframe").attr("src", public_url+src+'?subview=true'+data);
	subview.show("slide", {direction:"right"}, 200);
}

function closeSubview(callback){
	//$('#subview').hide("slide", {direction:"right"}, 200);
	$('.subview').hide("slide", {direction:"right"}, 200);

	if(typeof salesProcessStepNumb != 'undefined' && salesProcessStepNumb)
		salesProcessStepNumb = 0;
	if(typeof rescheduleClientMode != 'undefined' && rescheduleClientMode)
		rescheduleClientMode = false;
	/*if(typeof makeUpEventId != 'undefined' && makeUpEventId)
	makeUpEventId = 0;*/
	if(typeof createMakeUpMode != 'undefined' && createMakeUpMode)
		createMakeUpMode = false;
	if(typeof createServiceMakeUpMode != 'undefined' && createServiceMakeUpMode)
		createServiceMakeUpMode = false;

	/*if(typeof subviewData != 'undefined')
	updateRelatedDd($('select.'+subviewData.dd), subviewData.insertId, subviewData.data, true)*/
	if(typeof callback != 'undefined')	
		eval(callback)
}

/*function getRecord_indexCookieVal(){
	if(typeof cookieSlug != 'undefined' && cookieSlug != null){
		var cookieName = calcRecord_indexCookieName(cookieSlug);
		if(typeof cookieName != 'undefined' && cookieName != null){
			var cookie = $.cookie(cookieName);

			if(cookie){
				cookie = JSON.parse(cookie);
				if(cookie.use){
					$.removeCookie(cookieName, {path:'/'});
					return parseInt(cookie.rowIndex);
				}
			}
		}
	}
	return 0;
}*/

/*function setDatatableRowsAmount(dd, cookieSlug){
    var cookieName = calcTable_lengthCookieName(cookieSlug),
    	count = $.cookie(cookieName);

    if(count){
        dd.val(count);
        dd.trigger('change')
    }
    else
        $.cookie(cookieName,dd.val())
}
*/

function calcTable_lengthCookieName(cookieSlug){
	var cookieNames = calcCookieName(cookieSlug),
	cookieName = cookieNames.tableLength;

	if(typeof cookieName != 'undefined' && cookieName != null)
		return cookieName;
	return '';
}

/*function calcRecord_indexCookieName(cookieSlug){
	var cookieNames = calcCookieName(cookieSlug);
	return cookieNames.recordIndex;
}*/

function calcCookieName(cookieSlug){
	var cookieNames = {};
	switch (cookieSlug){
		case "location" :
		cookieNames.tableLength = "location-list-table-length";
		break;
		case "area" :
		cookieNames.tableLength = "area-list-table-length";
		break;
		case "staff" :
		cookieNames.tableLength = "staff-list-table-length";
		break;
		case "service" :
		cookieNames.tableLength = "service-list-table-length";
		break;
		case "class" :
		cookieNames.tableLength = "class-list-table-length";
		break;
		case "product" :
		cookieNames.tableLength = "product-list-table-length";
		break;
		case "client" :
		cookieNames.tableLength = "client-list-table-length";
            //cookieNames.recordIndex = "client-list-record-index";
            break;
            case "contact" :
            cookieNames.tableLength = "contact-list-table-length";
            break;
            case "goal" :
            cookieNames.tableLength = "goal-list-table-length";
            break;
            case "membership" :
            cookieNames.tableLength = "membership-list-table-length";
            break;
            case "habit" :
            cookieNames.tableLength = "habit-list-table-length";
            break;
            case "sales-tools-discount" :
            cookieNames.tableLength = "sales-tools-discount-list-table-length";
            break;
            case "closed-date" :
            cookieNames.tableLength = "closed-date-list-table-length";
            break;
            case "resource" :
            cookieNames.tableLength = "resource-list-table-length";
            break;
            case "admin" :
            cookieNames.tableLength = "admin-list-table-length";
            break;
            case "invoice" :
            cookieNames.tableLength = "invoice-list-table-length";
            break;
            case "exercise" :
            cookieNames.tableLength = "exercise-list-table-length";
            break;
            case "libraryPrograms" :
            cookieNames.tableLength = "library-program-list-table-length";
            break;
            case "generatePrograms" :
            cookieNames.tableLength = "genrate-program-list-table-length";
            break;
            case "mealplanner":
            cookieNames.tableLength = "mealplanner-list-table-length";
            break;
            case "foodplanner":
            cookieNames.tableLength = "foodplanner-list-table-length";
            break;
            case "mealcategory":
            cookieNames.tableLength = "mealcategory-list-table-length";
			break;
			case "videos":
            cookieNames.tableLength = "videos-list-table-length";
            break;
        }
        return cookieNames;
    }

    function getUrlParameter(sParam){
    	var urlParams = decodeURIComponent(window.location.search.substring(1)),
    	urlParamsArr = urlParams.split('&');

    	for(var i=0; i<urlParamsArr.length; i++){
    		var param = urlParamsArr[i].split('=');
    		if(param[0] === sParam)
    			return param[1] === undefined?true:param[1];
    	}
    	return false;
    }

    function confirmDelete(elem, warningText){
    	var entity = elem.data('entity');

    	swal({
    		title: "Are you sure to delete this "+entity+"?",
    		text: (typeof warningText != 'undefined' && warningText)?warningText:'',
    		type: "warning",
    		showCancelButton: true,
    		confirmButtonColor: "#d43f3a",
    		confirmButtonText: "Yes, delete it!",
    		allowOutsideClick: true,
    		customClass: 'delete-alert'
    	}, 
    	function(){
    		var action = elem.attr('href');
    		if(elem.is("[data-ajax-callback]")){
    		//send ajax
    		if(entity == 'task' || entity == 'category')
    			toggleWaitShield("show", 'deleteFromhelper');
    		$.ajax({
    			url:action,
    			type:'delete',
    			success:function(msg){
        			//var response = JSON.parse(msg);
        			//var callback=$('.delLink').data('ajax-callback');
        			var callback=elem.data('ajax-callback');
        			eval(callback+'(msg)');
        			if(entity == 'task' || entity == 'category')
        				toggleWaitShield("hide", 'deleteFromhelper');
        		}
        	});
    		
    	}
    	else
    		$('#deleteForm').attr('action', action).submit();
    });
    }

    function sortServices(a, b){
    	var aIsDeleted = (a.isDeleted == 'true')?1:0;
    	var bIsDeleted = (b.isDeleted == 'true')?1:0;

    	return ((aIsDeleted < bIsDeleted) ? -1 : ((aIsDeleted > bIsDeleted) ? 1 : 0));
    }

    function workingHoursTimePicker(elem){
    	if(typeof elem == 'undefined'){
    		var recreate = false;
    		elem = $('.timepicker1');
    	}
    	else
    		var recreate = true;

    	if(elem.length){
    		elem.timepicker({
    			minuteStep: 5,
    			template: 'dropdown',
    			showSeconds: false,
    			showMeridian: true
    		});	

    		if(recreate){
    			elem.timepicker('setTime', elem.data('default-time'));
    		}
    	}
    }

    function materialTimePicker(elem, time){
    	var date = moment(time, "hh:mm A");
    	elem.bootstrapMaterialDatePicker('setTime', date);
    } 

    function setFieldForTimepicker(field, time){
    	time = time.toString();
    	field.val(time);
    	field.data('default-time', time);
    	workingHoursTimePicker(field);
    }

    /* start: Format UI time to database time */
    function timeStringToDbTime(timeString){
    	return moment(timeString, 'h:mm A').format("HH:mm:ss");
    }
    /* end: Format UI time to database time */

    /* start: Format UI date to database date */
    function dateStringToDbDate(dateString){
    	return moment(dateString, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
    }
    /* end: Format UI date to database date */

    /* start: Format database time to UI time */
    function dbTimeToTimeString(dbTime){
    	return moment(dbTime, 'HH:mm:ss').format("h:mm A");
    }
    /* end: Format database time to UI time */

    /* start: Format database date to UI date */
    function dbDateToDateString(dbDate){
    	return moment(dbDate).format("ddd, D MMM YYYY");
    }
    /* start: Format database date to UI date */
    /* start: Formate database datetime to UI datetime*/
    function dbDateTimeToDateTimeString(dbDate){
    	return moment(dbDate).format("ddd, D MMM YYYY h:mm A");
    } 
    /* start: Formate database datetime to UI datetime*/

    /* start: Format database only date to UI date */
    function dbDateToDate(dbDate){
    	return moment(dbDate).format("D MMM YYYY");
    }
    /* start: Format database date to UI date */

    function teamBookingSteps(){
    	return [6,7,8,9,10];
    }
    function teamAttendSteps(){
    	return [11,23,24,25,26];
    }
    function indivBookingSteps(){
    	return [12,13,14,15,16];
    }
    function indivAttendSteps(){
    	return [17,19,20,21,22];
    }
    function sessionSteps(){
    	return teamBookingSteps().concat(teamAttendSteps()).concat(indivBookingSteps()).concat(indivAttendSteps());
    }
    function calcSalesProcessRelatedStatus(stepNumb){
    	var returnData = {};
    	if(stepNumb === 1 || stepNumb == 'contact' || stepNumb == 'pre-consultation'){
        //returnData['clientPrevStatus'] = ['Pending', 'Active Lead', 'Inactive Lead'];
        returnData['clientPrevStatus'] = 'Pending';
        returnData['clientStatus'] = 'Pre-Consultation';
        returnData['salesProcessType'] = 'contact';
        returnData['saleProcessStepNumb'] = 1;
    }
    else if(stepNumb === 2 || stepNumb == 'book_consult'){
    	returnData['clientPrevStatus'] = 'Pre-Consultation';
    	returnData['salesProcessType'] = 'book_consult';
    	returnData['saleProcessStepNumb'] = 2;
    }
    else if(stepNumb === 3 || stepNumb == 'consulted' || stepNumb == 'pre-benchmarking'){
    	returnData['clientPrevStatus'] = 'Pre-Consultation';
    	returnData['clientStatus'] = 'Pre-Benchmarking';
    	returnData['salesProcessType'] = 'consulted';
    	returnData['saleProcessStepNumb'] = 3;
    }
    else if(stepNumb === 4 || stepNumb == 'book_benchmark'){
    	returnData['clientPrevStatus'] = 'Pre-Benchmarking';
    	returnData['salesProcessType'] = 'book_benchmark';
    	returnData['saleProcessStepNumb'] = 4;
    }
    else if(stepNumb === 5 || stepNumb == 'benchmarked' || stepNumb == 'pre-training'){
    	returnData['clientPrevStatus'] = 'Pre-Benchmarking';
    	returnData['clientStatus'] = 'Pre-Training';
    	returnData['salesProcessType'] = 'benchmarked';
    	returnData['saleProcessStepNumb'] = 5;
    }
    else if(stepNumb === 6 || stepNumb == 'book_team'){
    	returnData['clientPrevStatus'] = 'Pre-Training';
    	returnData['salesProcessType'] = 'book_team';
    	returnData['saleProcessStepNumb'] = 6;
    }
    else if(stepNumb === 7){
    	returnData['clientPrevStatus'] = 'Pre-Training';
    	returnData['salesProcessType'] = 'book_team';
    	returnData['saleProcessStepNumb'] = 7;
    }
    else if(stepNumb === 8){
    	returnData['clientPrevStatus'] = 'Pre-Training';
    	returnData['salesProcessType'] = 'book_team';
    	returnData['saleProcessStepNumb'] = 8;
    }
    else if(stepNumb === 9){
    	returnData['clientPrevStatus'] = 'Pre-Training';
    	returnData['salesProcessType'] = 'book_team';
    	returnData['saleProcessStepNumb'] = 9;
    }
    else if(stepNumb === 10){
    	returnData['clientPrevStatus'] = 'Pre-Training';
    	returnData['salesProcessType'] = 'book_team';
    	returnData['saleProcessStepNumb'] = 10;
    }
    else if(stepNumb === /*9*/11 || stepNumb == 'teamed' || stepNumb == 'active'){
    	returnData['clientPrevStatus'] = 'Pre-Training';
    	returnData['clientStatus'] = 'active';
    	returnData['salesProcessType'] = 'teamed';
    	returnData['saleProcessStepNumb'] = /*9*/11;
    }
    else if(stepNumb === /*10*/18 || stepNumb == 'email_price'){
    	returnData['clientPrevStatus'] = 'active';
    	returnData['salesProcessType'] = 'email_price';
    	returnData['saleProcessStepNumb'] = /*10*/18;
    }
    else{
    	returnData['clientPrevStatus'] = '';
    	returnData['clientStatus'] = '';
    	returnData['salesProcessType'] = '';
    	returnData['saleProcessStepNumb'] = 0;
    }

    return returnData;
}

function salesProcessUpgradeCheck(accountStatus, successCallback, cancelCallback){
	successCallback();
	return true;
	var oldSaleProcessStep = $('input[name="salesProcessCompleted"]').val(),
	newSaleProcess = calcSalesProcessRelatedStatus(accountStatus),
	consultationDate = $('input[name="consultationDate"]').val();

	if(((accountStatus == 'pre-training' || accountStatus == 'active' || accountStatus == 'contra') && oldSaleProcessStep < newSaleProcess.saleProcessStepNumb && consultationDate)){
		if(hasConsultDateExpired()){
			swal({
				title: "Consultation date has expired.",
				type: 'warning',
				allowEscapeKey: false,
				showCancelButton: true,
				confirmButtonText: 'Still change',
				confirmButtonColor: '#ff4401'
			}, 
			function(isConfirm){
				if(isConfirm)
					successCallback();
				else if(typeof cancelCallback != 'undefined')
					cancelCallback();
			});
			return false;
		} 
	}
	successCallback();
}

function canMakeClientActive(cb){
	var membStatus = $('input[name="membershipStatus"]').val();
	// return true;
	//alert(membStatus)
	if(membStatus == 'Active'/* || membStatus == 'Unpaid'*/)
		return true;

	var editMembModal = $('#editMembSub');
	if(editMembModal.length){
		swal.close();
		editMembModal.modal('show');
		if(typeof cb != 'undefined')
			editMembModal.find('input[name="cb"]').val(cb)
	}
	return false;
}

function hasConsultDateExpired(consultationDate){
	if(typeof consultationDate == 'undefined')
		consultationDate = $('input[name="consultationDate"]').val();

	var consultationExpDate = $('input[name="consultationExpDate"]').val();

	if(consultationDate){
		if(!consultationExpDate)
			var consultationExpDate = moment(consultationDate).add(15, 'day').format('YYYY-MM-DD');

		if(moment().isSameOrAfter(consultationExpDate))
			return true;
	}
	return false;
}

/*function clientStatus(key){
	status = {};
	status['pending'] = 'Pending';
	status['active-lead'] = 'Active Lead';
	status['inactive-lead'] = 'Inactive Lead';
	status['pre-consultation'] = 'Pre-Consultation';
	status['pre-benchmarking'] = 'Pre-Benchmarking';
	status['pre-training'] = 'Pre-Training';
	status['active'] = 'Active';
	status['inactive'] = 'Inactive';
	status['on-hold'] = 'On Hold';
	status['contra'] = 'Contra';

	if(key in status)
		return status[key];

	return '';
}*/

function reloadPageWithTab(tabId){
	window.location.hash = tabId; 
	location.reload(true);
}

function setDbTimeToTimeString(field, dbTime){
	setFieldForTimepicker(field, moment(dbTime, 'HH:mm:ss').format("h:mm A"))
}

/* start: Set class time as per selected */
function setEventTime(modal, elemName){
	if(typeof elemName == 'undefined')
		elemName = 'eventTime';
	var selectedDatetimeMoment = moment($('input[name="selectedDatetime"]').val(),"YYYY-MM-DD hh:mmA"),
	eventTimeField = modal.find('input[name='+elemName+']');
	setFieldForTimepicker(eventTimeField, selectedDatetimeMoment.format("h:mm A"))
	setFieldNeutral(eventTimeField);
}
/* end: Set class time as per selected */

function populateItemQuant(elem){
	var opt = $(elem).find("option:selected");
	var c=opt.data('noofitem');
	var item=$(elem).closest('.row').find('select.item-op');
	item.find('option').remove().selectpicker('refresh');
	var i;
	item.prepend("<option value=''>-- Select --</option>");
	for(i=1;i<=c;i++){
		item.append("<option value='"+i+"'>"+i+"</option>");
	}

	newelem = elem.find('select.resource-option');
	if(newelem.is('[data-item]')){
		item.val(newelem.data('item'));
		newelem.removeAttr('data-item');
	}
	else if(opt.is("[data-default-quant]")){
		item.val(opt.data('default-quant'));
	}

	item.selectpicker('refresh');
}

function validateResourceForm(){
	var resArray = [],
	commonValues = [],
	resDd = $('#resource-form .resource-clone-class:not(.hidden) select.resource-option'),
		//existedItem = $('#resource-form .resource-clone-class:not(.hidden) select.item-op'),
		isFormValid = true;

		if(resDd.length){
			resDd.each(function(){
				var $this = $(this),
				res = $this.val(),
				formGroup = $this.closest('.resItemLocDd');

				if($.inArray(res, resArray) != -1){
					commonValues.push(res);
					setFieldInvalid(formGroup);
					setFieldInvalid($this.closest('.resource-clone-class').find('.resItemLocDd2'));
					isFormValid = false;
				}
				else{
					resArray.push(res);
					if(!isFieldMarkedAsAvail($this)){
						var isDeleted = $this.data('is-deleted');
						if(isDeleted == 'link')
							var message = 'Unlinked with service'
						else if(isDeleted == 'resource')
							var message = 'Resource deleted'
						setFieldInvalid(formGroup, message, formGroup.children('.help-block'));
						isFormValid = false;
					}
				}
			})
		}
		return isFormValid;
	}

	/* start: check if field is marked as available */
	function isFieldMarkedAsAvail(elem){
		if(elem != null && elem != undefined && elem.data("is-avail") !== undefined)
	//if(elem.is("[data-is-avail]"))
return elem.data('is-avail')
return 1;
}
/* end: check if field is marked as available */

/* start: Check if data attribute exists */
function hasData(elem, attr){
	if(elem.data(attr) !== undefined)
		return true;
	return false;
}
/* end: Check if data attribute exists */

/* Toggle waiting shield */
function toggleWaitShield(action, slug){
	if(typeof slug == 'undefined')
		slug = '';
	var shield = $('#waitingShield');

	if(action == "show"){
		shield.removeClass('hidden')
		if(slug){
			var existSlug = shield.data('slug');
			if(existSlug)
				existSlug += ','+slug;
			else
				existSlug = slug;
			shield.data('slug', existSlug)
		}
		/*if(shield.hasClass('hidden'))
		shield.data('slug', slug).removeClass('hidden')*/
	}
	else if(action == "hide"){
		if(slug){
			var existSlug = shield.data('slug').split(',');
			if(existSlug.length){
				var idx = $.inArray(slug, existSlug);
				if(idx !== -1){
					existSlug.splice(idx, 1);
					if(!existSlug.length)
						shield.addClass('hidden')

					shield.data('slug', existSlug.join(',')) //IMPLODE FIRST
				}
			}
		}
		else
			shield.addClass('hidden')
		/*if(shield.data('slug') == slug)
		shield.addClass('hidden')*/
	}
}
/* Toggle waiting shield */

/* start: Change error message placement */
function moveErrorMsg(modal){
	var moveErrMsgs = modal.find('.moveErrMsg');
	if(moveErrMsgs.length){
		moveErrMsgs.each(function(){
			var moveErrMsg = $(this),
			formGroup = moveErrMsg.closest('.form-group'),
			errors = moveErrMsg.find('.help-block'),
			errorMsg = '';

			errors.each(function(){
				var errorTxt = $(this).text();

				if(errorTxt != ''){
					formGroup.addClass('has-error');
					errorMsg = errorTxt;
					return false;
				}
			});
			formGroup.find('.placeErrMsg').html(errorMsg)
		})
	}
}
/* end: Change error message placement */

/* Start: Confirm to change client status */
function confirmSwalPopup(successCallBack,cancleCallBack){
	swal({
		title: 'Do you want to change status?',
		type: 'warning',
		allowEscapeKey: false,
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		confirmButtonColor: '#ff4401',
		closeOnConfirm: false
	}, 
	function(isConfirm){
		if(isConfirm){
			if(typeof successCallBack != "undefined")
				successCallBack();
		}
		else{
			if(typeof cancleCallBack != "undefined")
				cancleCallBack();
		}
	});
}
/* End: Confirm to change client status */

function addMoreDel(catId){
	if(typeof catId == 'object'){
		if(catId.status == 'success'){
			$('#addMore-row-'+catId.currentId).remove();
			var fieldClass = $('#addMoreModal input[name="field"]').val(),
			field = $('.'+fieldClass);
			field.find('a[href="#'+catId.slugify+'-notes"]').parent().remove();	
		}
	}
	else if(catId != 'error'){
		$('#addMore-row-'+catId).remove();

		var fieldClass = $('#addMoreModal input[name="field"]').val(),
		field = $('select.'+fieldClass);
		field.find('option[value="'+catId+'"]').remove();
		field.selectpicker('refresh');
	}
}

function addRowsToAddMoreModal(data){
	//Adding rows to table
	if(data.length){
		var tableBody = $('#addMoreModal tbody'),
		dummyRow = tableBody.find('tr.hidden');

		$.each(data, function(key, value){
			var row = dummyRow.clone();
			row.removeClass('hidden').attr('id', 'addMore-row-'+value.id).children('td:first').text(value.name);
			row.children('td:nth-child(2)').text(value.price);
			

			var editLink = row.find('.addMore-addEdit');
			editLink.data('entity-id', value.id)

			var delLink = row.find('.delLink');
			delLinkHref = delLink.attr('href');
			delLink.attr('href', delLinkHref+'/'+value.id)
			
			if('gender' in value){
				row.find('.extra-field1').removeClass('hidden').text(value.gender);
				row.data('extrafield-id',value.gender);
				editLink.data('extra','gender')
			}
			else if('size' in value){
				row.find('.extra-field1').removeClass('hidden').text(value.size);
				row.data('extrafield-id', value.size);
				row.data('servtags', value.tags);
				editLink.data('extra','servsize')
			}

			if('noteDel' in value){
				if(value.noteDel=='yes')
					row.find('.delLink').attr('disabled', true);
				else if(value.noteDel=='no')
					row.find('.delLink').attr('disabled', false);
			}
			tableBody.append(row);
		});
	}
}

/* start: Upcoming Task Reminder*/
/*start: Calculate which tasks met their reminder time or due time */
function calcUpcomingTasks(tasksTimestamp/*, modal*/){	
	var now = moment(),
	currTimestamp = moment(now.format('YYYY-MM-DD HH:mm')).valueOf()/1000,
	tasksId = [],
	reminderTasks = [],
	overdueTasks = [],
	dueTasks = [];

	var dueTaks = tasksTimestamp.due;
		if(Object.keys(dueTaks).length){ //If we have due tasks
			$.each(dueTaks, function(datetime, tasks){
				var timestamp = moment(datetime).valueOf()/1000; //Coverting datetime to timestamp
				if(timestamp > currTimestamp) //If task date is in future then skip
					return false;

				
				$.merge(tasksId, tasks)
				if(timestamp == currTimestamp) //Task is in present
					$.merge(dueTasks, tasks)
				else
					$.merge(overdueTasks, tasks) //Task is in past
			})
		}

		var reminder = tasksTimestamp.reminder;
		if(Object.keys(reminder).length){ //If we have reminders
			$.each(reminder, function(datetime, tasks){
				if(tasks.length){
					/* Check if we missed the reminder and task due reached. If yes, then remove from reminder and only show due notification */
					//if($.isArray(tasks)){
						var temp = [];
						$.each(tasks, function(key, task){
							var idx = $.inArray(task,tasksId);
							if(idx < 0)
								temp.push(task);
						})
						reminder[datetime] = temp;
						tasks = temp;
					/*}
					else{
						console.log('else')
						var idx = $.inArray(tasks,tasksId);
						if(idx >= 0){
							reminder[datetime].splice(idx, 1);
							return false;
						}
					}*/

					if(tasks.length){
						var timestamp = moment(datetime).valueOf()/1000; //Coverting datetime to timestamp
						if(timestamp > currTimestamp) //If reminder is in future then skip
							return false;

						$.merge(tasksId, tasks)
						$.merge(reminderTasks, tasks)
					}
				}
			})
			tasksTimestamp.reminder = reminder;
		}

		$('input[name="upcomingTasks"]').val(JSON.stringify(tasksTimestamp));
		if(tasksId.length){
			/*var temp = [];
			$.each(tasksId, function(key, val){ //Removing duplicacy
				if($.inArray(val, temp) == -1) 
					temp.push(val);
			});
			tasksId = temp;*/
			/*if(typeof modal !=undefined && modal)
				getTasksAjax({reminder:reminderTasks, overdue:overdueTasks, due:dueTasks});
				else	*/
					getTasksAjax({reminder:reminderTasks, overdue:overdueTasks, due:dueTasks}, 1);
			}
			else{
				var currMin = now.minute(),
				minModul = currMin%5;

				if(minModul)
					var minGap = 5-minModul;
				else
					var minGap = 5;

				setTimeout(function(){calcUpcomingTasks(tasksTimestamp)}, minGap*60*1000)
			}

		//console.log(tasksId)
		//console.log(tasksTimestamp)
		
		/*var date = new Date(),
			currTimestamp = Math.round(date.getTime()/1000),
			//currTimestamp = 0,
			tasksId = [];

		console.log(currTimestamp);
		return;
		$.each(tasksTimestamp, function(time, tasks){
			time = parseInt(time,10)
			if(time > currTimestamp)
				return false;

			$.merge(tasksId, tasks)
		})
		if(tasksId.length){
			var temp = [];
			$.each(tasksId, function(key, val){
				if($.inArray(val, temp) == -1) 
					temp.push(val);
			});
			tasksId = temp;
			getTasksAjax(tasksId, 1);
		}
		else{
			var currMin = date.getMinutes(),
		    	minModul = currMin%5;

			if(minModul)
				var minGap = 5-minModul;
			else
				var minGap = 5;
			
			setTimeout(function(){calcUpcomingTasks(tasksTimestamp)}, minGap*60*1000)
		}*/
	}
	/*end: Calculate which tasks met their reminder time or due time */

	/*function showUpcomingTasks(){
		return;
		var upcomingTasksTimestamp = $('input[name="upcomingTasksTimestamp"]').val();
		if(upcomingTasksTimestamp){
			upcomingTasksTimestamp = JSON.parse(upcomingTasksTimestamp);
			calcUpcomingTasks(upcomingTasksTimestamp);
		}
	}*/

	/* start: Fetch upcoming tasks*/
	function getUpcomingTasks(){
		return;
		$.get(public_url+'upcoming-tasks?r='+Math.random(), function(data){
			//console.log(data)
			$('input[name="upcomingTasks"]').val(data);
			calcUpcomingTasks(JSON.parse(data));
			/*$('input[name="upcomingTasksTimestamp"]').val(data);
			showUpcomingTasks();*/
		});
	}
	/* end: Fetch upcoming tasks*/


	/* Start: open modal for reminder and overdue task */
	function getTasksAjax(tasks, openModal){
		var modal = $('#tasksReminderModal'),
		currentUserId = loggedInUser.userId;

		if(typeof tasks == 'undefined'){		
			tasks = modal.find('[name="taskIds"]').val();
			if(tasks)
				tasks = JSON.parse(tasks); 
			else
				tasks = [];
		}
		else
			modal.find('[name="taskIds"]').val(JSON.stringify(tasks));

		//if(typeof tasks != 'undefined'){
			var taskIds = [];
			for(var k in tasks)
				taskIds.push.apply(taskIds, tasks[k]);

			//if(typeof taskIds != 'undefined')
			/*modal.find('[name="taskIds"]').val(JSON.stringify(taskIds));
		}
		else{
	        var taskIds = modal.find('[name="taskIds"]').val();
	        if(taskIds)
			  taskIds = JSON.parse(taskIds); 
	        else{
	            getUpcomingTasks()
	        }
	    }*/

	    /*console.log(tasks)
	    console.log(taskIds)
	    console.log(modal.find('[name="taskIds"]').val())*/

	    $.ajax({
	    	url:public_url+'dashboard/tasks',
	    	data: {taskIds:taskIds},
	    	type:'POST',
	    	success:function(response){
	    		var data = JSON.parse(response);
	    		if(data.length){
	    			modal.find("#taskfield").empty();
	    			$.each( data, function( key, value ){
	    				if(value.task_status=="complete" || value.task_status=="not required"){
	    					if(isUserType(['Staff'])){
	    						if(checkboxvalue == value.id )
	    							cboxclass='';
	    						else 
	    							cboxclass='hidden';
	    					}
	    					else
	    						cboxclass='';
	    					opacity='opacity25class';
	    					linethrough='textdecorclass';
	    					hidden='hidden';

	    				}
	    				else{ 
	    					cboxclass='';
	    					opacity='';
	    					linethrough='';
	    					hidden='';

	    				}

	    				/* Start: show date label */
	    				var now = moment().format('YYYY-MM-DD');
	    				var addtomorrow = moment().add(1,'d') .toDate();
	    				var tomorrow = moment(addtomorrow).format('YYYY-MM-DD');
	    				var taskDate = moment(value.task_due_date).format('YYYY-MM-DD');
	    				var inputDate = '';
	    				if(taskDate == now)
	    					inputDate = "Today";
	    				else if(taskDate == tomorrow)
	    					inputDate = "Tomorrow";
	    				else
	    					inputDate = moment(value.task_due_date).format("ddd, D MMM YYYY");
	    				/* End: show date label */

	    				var task_lable=''; 
	    				if(value.task_client_id == 0 || value.task_client_id == 'undefined' || value.task_client_id == '')
	    					task_lable=value.task_name;
	    				else
	    					task_lable='<a class="preventTaskView" href="'+public_url+'client/'+value.task_client_id+'?page=dashboard">'+value.task_name+'</a>';

	    				if(isUserType(['Admin']) || currentUserId==value.task_user_id){

	    					if(value.reminders && value.reminders[0]){
	    						var remindSection = 'data-remindhours='+value.reminders[0].tr_hours+'  data-remindcheckbox='+value.reminders[0].tr_is_set+'';
	    					}
	    					else
	    						var remindSection = "data-remindhours=''  data-remindcheckbox='' ";
	        			// Start:: code for create task UI --------------------------------------------------------
	        		}

	        		var editdeletefield ='<a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="'+value.id+'" data-task-name="'+value.task_name+'" data-auth-id="'+value.task_user_id+'" data-task-duedate="'+value.task_due_date+'" data-task-time="'+value.task_due_time+'" data-task-categ="'+value.task_category+'" data-taskcat="'+value.category_name.t_cat_name+'" data-task-repeat="'+value.is_repeating+'" '+remindSection+' data-tasknote="'+value.task_note+'"><i class="fa fa-pencil-square-o"></i></a> '+((value.is_repeating==1)?'<a class="btn delete-prompt preventTaskView hidden" href="#" style="display: block;" data-original-title="" title=""><i class="fa fa-trash-o"></i></a>':'<a class="btn delLink preventTaskView hidden" data-entity="task" href="'+public_url+'dashboard/'+value.id+'" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask"> <i class="fa fa-trash-o"></i></a>')+'';


	        		if($.inArray(value.id,tasks.reminder) >= 0){
	        			var type = 'reminder';
	        			var label = 'Reminder';
	        			dateTime = value.task_due_date+' '+value.task_due_time;

	        			if(value.reminders && value.reminders[0])
	        				dateTime = value.reminders[0].tr_datetime;
	        		}
	        		else{
	        			var type = 'due';
	        			var label = '';
	        			dateTime = value.task_due_date+' '+value.task_due_time;

	        			if($.inArray(value.id,tasks.overdue) >= 0)
	        				var label = 'Overdue';
	        		}

	        		if(label){
	        			var labeltext = '<span class="label label-warning top-10 '+opacity+'"> '+ label +' </span>';
	        			var label_cls =' label-custom'; 
	        		}
	        		else{
	        			var labeltext = '';
	        			var label_cls = '';
	        		}

	                    /*else if($.inArray(value.id,tasks.overdue) >= 0)
	                        var type = 'overdue';
	                    else if($.inArray(value.id,tasks.due) >= 0)
	                    	var type = 'due';*/
	                    
	                    modal.find("#taskfield").append('<li class="showtaskmodal" data-notific-type="'+type+'" data-task-id="'+value.id+'" data-datetime="'+dateTime+'"><div class="todo-actions clearfix taskDiv"> <div class="btn-group btn-group-xs pull-left preventTaskView"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#" ><span class="checkboxdd"><i '+((value.task_status=="complete")?'class="fa fa-check-square-o"':((value.task_status=="not required")?'class="fa fa-exclamation"':'class="fa fa-square-o"'))+'></i></span>&nbsp; <span class="caret '+cboxclass+'"></span></a><ul role="menu" class="dropdown-menu dropdown-light statusChangeDd '+cboxclass+'"><li><a data-status=""></a></li><li '+((value.task_status=="complete")?'class="hidden"':'')+'><a data-status="complete" data-taskid='+value.id+'>Complete</a></li><li '+((value.task_status==null)?'class="hidden"':'')+'><a data-status="incomplete" data-taskid='+value.id+'>Incomplete</a></li><li '+((value.task_status=="not required")?'class="hidden"':'')+'><a data-status="not required" data-taskid='+value.id+'>Not required</a></li></ul></div> <div class="padding-horizontal-5 pull-left"><div class="block space5 to-do-link"><span class="desc tasknameclass '+opacity+' '+linethrough+'">'+task_lable+'</span> '+((value.is_repeating==1)?'<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>':'')+'<span class="label label-danger top-10 '+opacity+' '+label_cls+'"> '+ inputDate +' </span>'+labeltext+'</div><div class="block"><span class="desc text-small text-light taskdatetimeclass '+opacity+' '+linethrough+'"><i class="fa fa-clock-o">&nbsp;</i> '+dbTimeToTimeString(value.task_due_time)+' </span><div class="todo-tools"></div><div class="btn-group btn-group-sm hidden"> '+editdeletefield+' </div> '+' </div></div></div></div></li>');
	                    // End:: code for create task UI --------------------------------------------------------
	                });

if(typeof openModal != 'undefined' && openModal){
	$('#tasksReminderModal').modal('show');
}
}  
}
});
}
/* End: open modal for reminder and overdue task */

/* start: display all task repeat value on taskShow modal */
function randarValueOnShowModal(modal, responseData){
	var repeatVal = responseData[0].tr_repeat;
	var html = '';
	var intervalText = '';
	if(repeatVal == 'Daily'){
		html = 'Daily';
		intervalText = 'day(s)';
	}
	else if(repeatVal == 'Weekly'){
		days = JSON.parse(responseData[0].tr_repeat_week_days);
		html = 'Weekly ('+days+')';
		intervalText = 'week(s)';
	}
	else{
		intervalText = 'month(s)';
		html = 'Monthly'; 
	}

	modal.find('#eventRepeat').text(html);
	modal.find('#eventRepeatInterval').text(responseData[0].tr_repeat_interval +' '+ intervalText);
	modal.find('#eventRepeatEnd').text(responseData[0].tr_repeat_end);

	var html1='';
	if(responseData[0].tr_repeat_end_after_occur)
		html1 = responseData[0].tr_repeat_end_after_occur +' occurence';
	else if(responseData[0].tr_repeat_end_on_date)
		html1 = dbDateToDateString(responseData[0].tr_repeat_end_on_date);
        /*else
        html1 = 'Never';*/
        modal.find('#eventRepeatNo').text(html1);
    }
    /* End: display all task repeat value on taskShow modal */
    /* end: Upcoming Task Reminder*/

/*function statusMatchSalesStatus(clientStatus, salesStatus){
    if($.isArray(salesStatus))
        return ($.inArray(clientStatus, salesStatus) >= -1);
    return (clientStatus == salesStatus);
} */

function contactTypeahead(elem, data){
	elem.typeahead({
		matcher: function(item){
			var data = item.name.split('|'),
			input = elem.val().toLowerCase();

			if(data[0].indexOf("--") >= 0){
				if(data[0].toLowerCase().indexOf(input) >= 0)
					return true;
			}
			else{
				if(data[1].toLowerCase().indexOf(input) >= 0)
					return true;
			}
			return false;
		},
		highlighter: function(item){
			var data = item.split('|');
			if(data[0].indexOf("--") >= 0)
				data[0] = data[0].replace("--", "")
			else
				data[1] = data[1].replace("--", "")
			return data[0]+'<br><span>'+data[1]+'</span>';
		},
		source:data,
		items:'all',
		afterSelect:function(selection){
			var name = selection.name.split('|');
			if(name[0].indexOf("--") >= 0)
				name = name[0].replace("--", "")
			else
				name = name[1].replace("--", "")

			elem.val(name)
			elem.parent().find('input[name="proId"]').val(selection.id);

        	/*data = selection.name.replace("--", "")
        	elem.val(data)
        	elem.parent().find('input[name="proId"]').val(selection.id);
        	//$('input[name="proId"]').val(selection.id);*/
        }
    });
}

/*Start: get expired servive --*/
function randorExpriedService(ides,callback){
	var modal = $('#epiredSeriveModal'),
	epiredSeriveAppend = modal.find('#epiredSeriveAppend');
	if(typeof ides != 'undefined' && ides){
		var taskIds=ides;
		$.ajax({
			url:public_url+'settings/business/services/expired-tasks',
			data: {taskIds:taskIds},
			type:'GET',
			success:function(response){
				var data = JSON.parse(response);
				if(data.length){
					modal.find(epiredSeriveAppend).empty();
					$.each( data, function( key, value ){
						if(value.service.one_on_one_name != '')
							var serviceName=value.service.one_on_one_name;
						else if(value.service.team_name != '')
							var serviceName=value.service.team_name;

						modal.find(epiredSeriveAppend).append('<div class="panel panel-default "><div class="panel-heading"><a href="#" class="openClassModal" data-event-type="service" data-event-id="'+value.sess_id+'" data-modal-mode="past">'+dbDateToDateString(value.sess_date)+'</a>&nbsp;&nbsp;<span class="label label-gray">'+value.sess_booking_status+'</span></div><div class="panel-body"><div><i class="fa fa-cog" style="color:#400080"></i> '+serviceName+' with '+value.staff.first_name+' '+value.staff.last_name+' at '+dbTimeToTimeString(value.sess_time)+'</div></div></div>');
					})
				}

				if(callback != 'undefined' && callback)
					modal.modal('show');
			}
		});
	}        		
}
/*End: get expired servive --*/

/*Start: Call page on load get */
function getExpriedService(){
		//randorExpriedService([309,304,303,302], 1);
	}
	/*End: Call page on load get */

	function showClientBookedMsg(clientName, invDue, invExist){
		var cancleBtn = false;
		if(typeof clientName != 'undefined' && clientName)
			var text = clientName+' has';
		else
			var text = 'You have';

		if(typeof invDue != 'undefined' && invDue != '')
			var invoice = 'Invoice has been generated with due on '+dbDateToDateString(invDue);
		else if(typeof invExist != 'undefined'){
			cancleBtn = true;
			var invoice = 'Invoice already exist. Do you want to edit it?';
		}
		else
			var invoice = '';

		swal({
			title: text+" been booked successfully.",
			text: invoice,
			type: "success",
			confirmButtonColor: "#ff4401",
			confirmButtonText: (cancleBtn)?"Yes! edit it.":"Okay",
			showCancelButton: cancleBtn,
			cancelButtonText: "No"
		},
		function(isConfirm){
			if(isConfirm && cancleBtn)
				window.location.href = public_url+'invoices/show/'+invExist;
		});
	}

	/* Satrt: isEpic function */
	function isEpic(elem){
		epicDdValue = elem.val();
		if(epicDdValue =="onlinesocial" || epicDdValue=="mediapromotions"){
			$('.referencewhere').removeClass('hidden');
			$('.otherName').addClass('hidden');
			if($('.refShow').hasClass('hidden')){
				
			}else{
			$('.refShow').addClass('hidden');
			}
			if($('.otherName').hasClass('hidden')){
				
			}else{
			$('.otherName').addClass('hidden');
			}
		}
		else if(epicDdValue == "socialmedia"){ 
			if($('.refShow').hasClass('hidden')){
				
			}else{
			$('.refShow').addClass('hidden');
			}
			$('.referencewhere').addClass('hidden');
			$('.otherName').removeClass('hidden');
		}
		else if(epicDdValue == "referral"){
			if($('.otherName').hasClass('hidden')){
				
			}else{
			$('.otherName').addClass('hidden');
			}

			$('.refShow').removeClass('hidden');
			$('.referencewhere').addClass('hidden');
		}
		else{
			if($('.refShow').hasClass('hidden')){
				
			}else{
			$('.refShow').addClass('hidden');
			}
			if($('.otherName').hasClass('hidden')){
				
			}else{
			$('.otherName').addClass('hidden');
			}
			$('.referencewhere').addClass('hidden');
			$('.otherName').addClass('hidden');
		}
	}
	/* End: isEpic function */

/**
 * Ask confirmation image remove or note
 */
 function imageRemoveConfirmation(callback){
 	swal({
 		title: "Are you sure to remove this image?",
 		type: "warning",
 		showCancelButton: true,
 		confirmButtonColor: "#d43f3a",
 		confirmButtonText: "Yes, remove it!",
 		allowOutsideClick: true,
 		customClass: 'delete-alert'
 	}, 
 	function(confirm){
 		callback(confirm);
 	});
 }

/**
 * Money format
 */
 function moneyFormat(nStr){
 	nStr += '';
 	x = nStr.split('.');
 	x1 = x[0];
 	x2 = x.length > 1 ? '.' + x[1] : '';
 	var rgx = /(\d+)(\d{3})/;
 	while (rgx.test(x1)) {
 		x1 = x1.replace(rgx, '$1' + ',' + '$2');
 	}
 	return x1 + x2;
 }

/**
 * Document ready events
 */
 $(document).ready(function(){
 	if(typeof cookieSlug != 'undefined' && cookieSlug != null){

 		var dd = $('#datatableLengthDd');
 		if(dd.length){
 			var cookieName = calcTable_lengthCookieName(cookieSlug);

 			if(cookieName){
 				var length = $.cookie(cookieName);
 				if(length)
 					dd.val(length)
 			}
 			dd.change(function(){
 				$.cookie(cookieName, $(this).val());

 				var url = window.location.href.split('?')[0];
 				searchParam = getUrlParameter('search');

 				if(searchParam)
 					url += '?search='+searchParam;
 				window.location.href = url;
 			})
 		}
 	}

 	/* Initializing timepicker */
 	workingHoursTimePicker();

 	/* Initializing alphaNumeric fields */
 	$(document).on("keypress", '.alphaNumericField', function(e){
 		var regex = new RegExp("^[a-zA-Z0-9.,/]+$");
 		var key = e.key;
 		if (!regex.test(key)) {
 			e.preventDefault();
 			return false; 
 		}
 	});

 	/* Initializing numeric fields */
 	$(document).on("keydown", '.numericField', function(e){
 		if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 37 || e.keyCode == 39))
 			return false;
 	});

 	/* Initializing price fields */
 	$(document).on("keydown", '.price-field', function(e){
 		if($(this).val().indexOf('.') !== -1 && (e.keyCode == 190 || e.keyCode == 110))
 			return false;

 		if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 190 || e.keyCode == 110 || e.keyCode == 37 || e.keyCode == 39))
 			return false;

 	});

 	/* Initializing number and colon fields */
 	$(document).on("keydown", '.numb-colon', function(e){
 		if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 59))
 			return false;

 	});

	/*$(document).on("change", '.cutomck-validation', function(e){
		console.log(e);
	})*/

	/* Initialize tooltip */
	var tooltip = $('.epic-tooltip');
	if(tooltip.length)
		tooltip.tooltipster();

	/* Initializing country code selector */
	var countryCode = $('.countryCode');
	if(countryCode.length){
		initTelInput(countryCode);
	}

	/* Initialize the selectpicker */
	initSelectpicker($('select[name!="client-datatable_length"]'));

	/* Closing subview */
	var closeSubview = $('.closeSubView');
	if(closeSubview.length){
		closeSubview.click(function(){
			parent.closeSubview()
		});
	}

	/* Setting field neutral upon changing value */
	$(document).on("change", '.onchange-set-neutral', function(e){
		setFieldNeutral($(this))
	});

	/*$(document).on("change", ".customValRadField", function(){
		var formGroup = $(this).closest('.form-group');
		setFieldValid(formGroup, formGroup.find("span.help-block"))
	})*/

	/* Start: referrer dd on change show field */

	$('select[name="referrer"]').change(function(){

		isEpic($(this));
	});

	isEpic($('select[name="referrer"]'));
	/* End: referrer dd on change show field */

	$('body').on('click', '.selAllDd', function(){
		var $this = $(this),
		formGroup = $this.closest('.form-group'),
		options = formGroup.find('option');

		if($this.is(":checked")){
			options.prop('selected', true);

			if(formGroup.hasClass('has-error'))
				formGroup.removeClass('has-error')
			formGroup.find('.help-block').html('');
		}
		else
			options.prop('selected', false);

		formGroup.find('select').selectpicker('refresh');
	});

	$('body').on('click', '.delLink', function(e){
		e.preventDefault();

		var $this = $(this),
		entity = $this.data('entity');

		if($this.is("[data-association-route]")){
			$.get($this.data('association-route'), function(data){
				var warningText = '';
				if(data === 1){
					switch(entity){
						case "service":
						warningText = 'Future events with only this service will also be deleted.';
						break;
						case "class":
						warningText = 'Associated future events will also be deleted.';
						break;
					}
				}
				confirmDelete($this, warningText);
			},'json');
		}
		else
			confirmDelete($this);
	});


	$(document).on("focus", ".customValField", function(){
		var formGroup = $(this).closest('.form-group');

		formGroup.removeClass('has-error')
		formGroup.find('span.help-block').hide();
	});

	var resourceElem = $('#service-resource');
	if(resourceElem.length){  
		var resourceForm = $('#resource-form');
		if(resourceForm.length){
			rowno = $('input[name="flagvalue"]').val();
			var existedServiceResource = resourceForm.find('.resource-clone-class:not(.hidden)');
			if(existedServiceResource.length){
				$.each(existedServiceResource, function(){
					populateItemQuant($(this));
				});
			}

			/* start: Add resource row  */
			$('.add-resource-row').click(function(e){
				e.preventDefault();
				var lastRow = resourceForm.find('.resource-clone-class:last');
				var lastRowResourceValue = lastRow.find('.resItemLocDd select.resource-option').val();
				var lastRowItemValue = lastRow.find('.resItemLocDd2 select.item-op').val();

				isExist = resourceForm.find('.resource-clone-class:not(.hidden)').length;
				if(!isExist || (lastRowResourceValue != "" && lastRowItemValue != "")){
					rowno++;

					//lastRow.find('.resItemLocDd select.resource-option option:selected');
					var self=resourceForm.find('.resource-clone-class:eq(0)');
					var resourcerow=self.clone();
					var ddGroup = resourcerow.find('.resItemLocDd');
					var dd = ddGroup.find('select.resource-option');
					ddGroup.empty();
					ddGroup.append(dd);
					ddGroup.append('<span class="help-block"></span>');
					ddGroup.find('select.resource-option').attr('name','newResources'+rowno).prop('required', true).selectpicker('refresh');
					var ddGroup = resourcerow.find('.resItemLocDd2');
					var dd = ddGroup.find('select.item-op');
					ddGroup.empty();
					dd.prepend("<option value=''>-- Select --</option>").prop('required', true);
					ddGroup.append(dd);
					ddGroup.append('<span class="help-block"></span>');
					ddGroup.find('select.item-op').attr('name','newItem'+rowno).selectpicker('refresh');
					resourcerow.removeClass('hidden');
					resourcerow.find('select.item-op').find('option').remove().selectpicker('refresh');
					self.closest('fieldset').append(resourcerow);
				}
			}); 
			/* end: Add resource row  */

			/* start: Delete resource row  */
			$('body').on('click', '.remove-resourcelink-row', function(e){
				e.preventDefault();
				$(this).closest('.row').remove();
			}); 
			/* end: Delete resource row */

			/* start: Add option in item coloumn */
			$('body').on('change', 'select.resource-option', function(e){
				populateItemQuant($(this));
			});
			/* start: Add option in item coloumn */

			$('select.location').change(function(){
				var formMode = $('#form-4').data('form-mode');
				if(formMode=='standAlone'){
					formData = {};
					var locationId = $(this).find("option:selected");
					formData['locationid'] = locationId.val();
					$.get(public_url+'settings/business/resources/'+formData['locationid'], function(response){
						var data = JSON.parse(response);
						var Options ="<option value=''>-- Select --</option>";
						$.each(data,function(key,value){
							Options+="<option value="+value.id+" data-noofitem="+value.items.length+">"+value.res_name+"</option>"; 
						});
						$('#resource-form .resource-clone-class:not(.hidden)').remove();
						var hiddenDd = $('#resource-form .resource-clone-class:eq(0)');
						var hiddenDdOption = hiddenDd.find('.resItemLocDd select.resource-option');
						hiddenDdOption.empty();
						hiddenDdOption.append(Options);
					});
				}
			});
		}
	}

	/* Start: Confirm to change client status */
	$('body').on('change', 'select.onChangePop', function(e){
		var $this=$(this);
		confirmSwalPopup(function(){statusChangeSwalConfirm($this)}, function(){statusChangeSwalCancle()});
	});
	/* End: Confirm to change client status */

	/* Start: Modal for Add more */
	$('.add-more').click(function(e){
		e.preventDefault();

		var $this = $(this),
		href = $this.attr('href');

		$.get(href, function(data){
			data = JSON.parse(data);
			var modal = $('#addMoreModal');
				modal.find('.modal-title').text($this.data('modal-title')); //Setting title

				modal.find('.addMore-addEdit, .delLink').attr('href', href).data('extra', '') //Setting add/edit route

				var catType = $this.data('field');
				modal.find('input[name="field"]').val(catType) //Field class to edit/update

				var extraField1 = $('#extra-field1');
				if(catType == 'prodSize'){
					extraField1.removeClass('hidden');
					extraField1.text('Gender');
					modal.find('.addMore-addEdit').data('extra', 'gender')
				}
				else if(catType == 'servingSize'){
					$('#field-name').text('Size');
					extraField1.removeClass('hidden');
					extraField1.text('Quantity');
					modal.find('.addMore-addEdit').data('extra', 'servsize');
				}
				else
					extraField1.addClass('hidden');

				var tableBody = modal.find('tbody');
				tableBody.find('tr:not(.hidden)').remove(); //Emptying table
				addRowsToAddMoreModal(data)
				modal.modal('show');
			});
	})

	$('body').on('click', '.addMore-addEdit', function(e){	
		e.preventDefault();

		var $this = $(this),
		modal = $('#addMoreAddEdit'),
		heading = modal.find('.modal-title'),
		idField = modal.find('input[name="editId"]'),
		field = modal.find('input[name="text"]'),
		price = modal.find('input[name="price"]'),
		genderField = modal.find('[name="gender"]'),
		servingsizeField = modal.find('input[name="servingsize"]');
		servingtagsField = modal.find('input[name="servingTags"]');
		$('.gender-group').addClass('hidden');
		$('.serving-size').addClass('hidden');
		$('.serving-tags').addClass('hidden');
			if(hasData($this, 'entity-id')){ //Edit Case
				heading.text('Edit');
				idField.val($this.data('entity-id'));

				var value = $this.closest('tr').children('td:first').text();
				var priceVal = $this.closest('tr').children('td:nth-child(2)').text();
				field.val(value);
				price.val(priceVal);

				if($this.data('extra') == 'gender'){
					var gender = $this.closest('tr').data('extrafield-id');
					genderField.val(gender);
					genderField.selectpicker('refresh');
				}
				else if($this.data('extra') == 'servsize'){
					var servsize = $this.closest('tr').data('extrafield-id'),
					servtags = $this.closest('tr').data('servtags'); 
					servingsizeField.val(servsize);
					servingtagsField.val(servtags);
				}
			}
			else{ //Add Case
				heading.text('Add');
				idField.val('');
				field.val('');
				price.val('');
				genderField.val('');
				servingsizeField.val('');
				servingtagsField.val('');
				genderField.selectpicker('refresh');
			}

			var textBoxlable = modal.find('.textbox-lable');
			if($this.data('extra') == 'gender'){
				$('.gender-group').removeClass('hidden');
				textBoxlable.text('Name *');
			}
			else if($this.data('extra') == 'servsize'){
				textBoxlable.text('Size * (like- Gram, Pound)');
				$('.serving-size').removeClass('hidden');
				$('.serving-tags').removeClass('hidden');
			}
			else{
				textBoxlable.text('Name *');
			}

			modal.find('form').attr('action', $this.attr('href'));
			setFieldNeutral(field);
			setFieldNeutral(price);
			setFieldNeutral(genderField);
			modal.modal("show");
		});

		/*$('#addMoreAddEdit .submit').click(function(){
			var form = $('#addMoreAddEdit form'),
				isFormValid = form.valid(),
				formData = {};

			if(!$('.image-group').hasClass('hidden') && !(validateUpload($(form).find("input[name='categoryImage']"))))
				isFormValid = false;

			if(isFormValid){
				formData.image = $('[name="categoryImage"]').val();
				formData.entityId = form.find('input[name="editId"]').val();
				formData.text = form.find('input[name="text"]').val(); 
				$.post(form.attr('action'), formData, function(catId){
					console.log(typeof catId);
					if(catId != 'error'){
						var fieldClass = $('#addMoreModal input[name="field"]').val(),
							field = $('select.'+fieldClass);

						if(!formData.entityId){ //Add Case
							if(formData.image != '')
								var addData = {id:catId, name:formData.text, image:formData.image};
							else
								var addData = {id:catId, name:formData.text};

							addRowsToAddMoreModal([addData])

							field.append('<option value="'+catId+'">'+formData.text+'</option');
						}
						else{ //Edit Case
							$('#addMore-row-'+catId).children('td:first').text(formData.text);
							field.find('option[value="'+catId+'"]').text(formData.text);
							if(formData.image != '')
								$('#addMore-row-'+catId+' .img-body img').attr('src', public_url+'uploads/'+formData.image);
						}
						field.selectpicker('refresh');
						$('#addMoreAddEdit').modal("hide");
					}
				});
			}
		})*/
		$('#addMoreAddEdit .submit').click(function(){
			var form = $('#addMoreAddEdit form'),
			isFormValid = form.valid(),
			formData = {};

			if(isFormValid){
				formData.gender = $('[name="gender"]').val();
				formData.entityId = form.find('input[name="editId"]').val();
				formData.text = form.find('input[name="text"]').val();
				formData.price = form.find('input[name="price"]').val();
				formData.size = form.find('input[name="servingsize"]').val();
				formData.tags = form.find('input[name="servingTags"]').val();  
				$.post(form.attr('action'), formData, function(catId){
					if(typeof catId == 'object'){
						if(catId.status=='success'){
							//var response = JSON.parse()
							var fieldClass = $('#addMoreModal input[name="field"]').val();
							field = $('.'+fieldClass);
							ddfield = $('select.'+fieldClass);
							if(!formData.entityId){ //Add Case
								var addData = {id:catId.currentId, name:formData.text, price:formData.price};

								addRowsToAddMoreModal([addData])
								/*ddfield.append('<option value="'+catId.currentId+'">'+formData.text+'</option');

								field.append('<li class=""><a href="#'+catId.slugify+'-notes" data-toggle="tab">'+formData.text+'</a></li>');
								$('#noteTabField').append('<div class="tab-pane fade" id="'+catId.slugify+'-notes"><div class="'+catId.slugify+'-'+catId.currentId+'"></div></div>');*/
							}
							else{ //Edit Case
								$('#addMore-row-'+catId.currentId).children('td:first').text(formData.text);
								$('#addMore-row-'+catId.currentId).children('td:nth-child(2)').text(formData.price);
								/*field.find('a[href="#'+catId.slugify+'-notes"]').text(formData.text);*/

							}
							$('#addMoreAddEdit').modal("hide");
						}
					}
					else if(catId != 'error'){
						var fieldClass = $('#addMoreModal input[name="field"]').val(),
						field = $('select.'+fieldClass);

						if(!formData.entityId){ //Add Case
							if(formData.gender != '')
								var addData = {id:catId, name:formData.text, price:formData.price, gender:formData.gender};
							else if(formData.size != ''){
								if(formData.tags != '')
									var tagsName = formData.tags+','+(formData.text).toLowerCase();
								else
									var tagsName = (formData.text).toLowerCase();

								var addData = {id:catId, name:formData.text, price:formData.price, size:formData.size, tags:tagsName};
							}
							else
								var addData = {id:catId, name:formData.text ,price:formData.price};

							addRowsToAddMoreModal([addData])
							if(formData.gender != '')
								field.append('<option value="'+catId+'">'+formData.text+' ('+formData.gender+')</option');
							else if(formData.size != '')
								field.append('<option value="'+catId+'">'+formData.size +' '+ formData.text+'</option');
							else{
								field.append('<option value="'+catId+'" data-price="'+formData.price+'">'+formData.text+'</option');
							}
						}
						else{ //Edit Case
							$('#addMore-row-'+catId).children('td:first').text(formData.text);
							$('#addMore-row-'+catId).children('td:nth-child(2)').text(formData.price);
							if(formData.gender != ''){
								field.find('option[value="'+catId+'"]').text(formData.text+' ('+formData.gender+')');
								$('#addMore-row-'+catId+' .extra-field1').removeClass('hidden').text(formData.gender);
								$('#addMore-row-'+catId).data('extrafield-id',formData.gender);
							}
							else if(formData.size != ''){
								field.find('option[value="'+catId+'"]').text(formData.size +' '+ formData.text);
								$('#addMore-row-'+catId+' .extra-field1').removeClass('hidden').text(formData.size);
								$('#addMore-row-'+catId).data('extrafield-id',formData.size);
								if(formData.tags != '')
									var tagsName = formData.tags;
								else
									var tagsName = (formData.text).toLowerCase();

								$('#addMore-row-'+catId).data('servtags', tagsName);
							}
							else{
								field.find('option[value="'+catId+'"]').text(formData.text);
								field.find('option[value="'+catId+'"]').data('price', formData.price);
							}
						}
						field.selectpicker('refresh');
						$('#addMoreAddEdit').modal("hide");
					}
				});
			}
		})

		$('#addMoreModal').on('hide.bs.modal', function(){
			var cls=$('#addMoreModal input[name="field"]').val();
			if(cls=='noteCat')
				reloadPageWithTab('#notes-details');
		});
		/* End: Modal for Add more */

		/* start: Upcoming Task Reminder*/
		$('#tasksReminderModal').on('hide.bs.modal', function(){
			var upcomingTasksField = $('input[name="upcomingTasks"]'),
			upcomingTasks = upcomingTasksField.val();
			if(upcomingTasks){
				upcomingTasks = JSON.parse(upcomingTasks);
				$('#tasksReminderModal li.showtaskmodal').each(function(){
					var $this = $(this),
					type = $this.data('notific-type'),
					datetime = $this.data('datetime'),
					taskId = $this.data('task-id');

					var arr = upcomingTasks[type][datetime],
					idx = $.inArray(taskId, arr)
					arr.splice(idx,1);
				})
				upcomingTasksField.val(JSON.stringify(upcomingTasks))
				calcUpcomingTasks(upcomingTasks)
			}
			//getUpcomingTasks();
		});

		/* Start: for reminder task status change  */
		$('body').on('click', '.statusChangeDd li a', function(e){
			var $this = $(this);
			formData = {};
			formData['status'] = $this.attr("data-status");
			formData['taskid'] = $this.attr("data-taskid");
			$.post(public_url+'dashboard/checkbox', formData, function(response){
				var data = JSON.parse(response);
				if(data.ajaxStatus=="success"){
		            /*checkboxvalue = formData['taskid'];
		            var taskIds = $('input[name="upcomingTasks"]').val();
		            calcUpcomingTasks(JSON.parse(taskIds),1);  */
		            getTasksAjax();
		        }
		    });

		});
		/* Start: for reminder task status change  */

		/* Start: task show modal open */ 
		$('body').on("click", '.showtaskmodal', function(event){
			var target = $(event.target);
			if(target.hasClass('preventTaskView') || target.closest('.preventTaskView').length)
				return;

			$('#showModal').data('trigger', this).modal('show');
		})
		/* End: task show modal open */

		/* Start: showModal Randor value on field */
		$('#showModal').on('show.bs.modal', function (e) {
			var modal = $(this);

			var invoker = $(modal.data('trigger'));
			if(invoker.hasClass('showtaskmodal')){
				var row = invoker;
			}
			else
				var row = invoker.closest('.showtaskmodal')

			var dataElem = row.find('.edittask')
			var taskID = dataElem.data('task-id');

			modal.find('[name="taskID"]').val(taskID);
			modal.find('input[name="taskFormId"]').val(taskID);
			modal.find('#taskName').text(dataElem.data('task-name'));
			modal.find('#taskDate').text(dbDateToDateString(dataElem.data('task-duedate')));
			modal.find('#taskTime').text(dbTimeToTimeString(dataElem.data('task-time')));

			var optionVal = dataElem.data('task-categ');
			var authId = dataElem.data('auth-id');
			if(loggedInUser.userId != authId){
				optionVal += '|'+authId;
			}
			var taskCategoryName = $('#addtask').find('select#taskcategoryid option[value="'+optionVal+'"]').text();
			if(typeof taskCategoryName != 'undefined' && taskCategoryName != '')
				modal.find('#taskCategory').text(taskCategoryName);
			else
				modal.find('#taskCategory').text(dataElem.data('taskcat'));


			var tasknote = dataElem.data('tasknote');
			modal.find('[name="taskNote"]').val(tasknote);

			if(dataElem.data('task-repeat') != ''){
				formData = {};
				formData['taskid'] = taskID;
				toggleWaitShield("show", 'viewTask');
				$.post(public_url+'dashboard/edittask', formData, function(response){
					var data = JSON.parse(response);
					if(data != ""){
						randarValueOnShowModal(modal, data);
					}  
					toggleWaitShield("hide", 'viewTask'); 
				});
				modal.find('.repeat').removeClass('hidden');
			}
			else{
				modal.find('.repeat').addClass('hidden'); 
			}

			var reminderHourVal = dataElem.data('remindhours');
			if(typeof reminderHourVal != 'undefined' && reminderHourVal != ''){
				modal.find('#reminderVal').text(dataElem.data('remindhours')+' hour(s)');
				modal.find('.rmb').removeClass('hidden');
			}
			else{
				modal.find('.rmb').addClass('hidden');
			}

		});
		/* End: showModal Randor value on field */

		/* Start: showModal submit value  */
		$('#showModal .submit').click(function(){
			var modal = $('#showModal'),
			form = modal.find('form');
			formData={};
			var note = form.find('[name="taskNote"]').val();
			formData['note'] = note; 
			var taskId = form.find('[name="taskID"]').val();

			if(note != ''){
				toggleWaitShield("show", 'saveTaskNotes');
				$.ajax({
					url : public_url+'dashboard/edit-task-note/'+taskId,
					type : 'POST',
					data : formData,
					success : function(response) {
		                //var data = JSON.parse(response);
		                if(response == "success"){
		                	$('[data-task-id="'+taskId+'"]').data('tasknote',formData['note']);
		                	modal.modal('hide');
		                }
		                toggleWaitShield("hide", 'saveTaskNotes');
		            },
		        });
			}
			else{
				modal.modal('hide');
			}
		});
		/* End: showModal submit value  */
		/* end: Upcoming Task Reminder*/

		$('.change-color').change(function(){
			var $this = $(this),
			formData = {};

			formData['entityProperty'] = 'color';
			formData['entityId'] = $this.data('id');
			formData['color'] = $this.val();

			$.post(public_url+'settings/business/'+$this.data('type')+'/'+formData.entityId+'/update-field', formData);
		})

		/* Start: image remover */
		$(".removePic").hover(function(){
			$(this).siblings(".removePicBtn").show();
		}, function(){
			$(this).siblings(".removePicBtn").hide();
		});
		$(".removePic").click(function(e){
			e.preventDefault();
			var $this = $(this),
			sibling = $this.siblings(".removePicBtn"),
			formGroup = $this.closest('.form-group'),
			prePhotoField = formGroup.find('input[name="prePhotoName"]'),
			photoField = formGroup.find('.photoName'), 
			formData = {};


			if(photoField.val() != '') 
				formData['photoName'] = photoField.val();
			else 
				formData['photoName'] = prePhotoField.val();

			formData['entityId'] = sibling.data('entity-id');
			formData['entityName'] = sibling.data('entity');
			if(formData['photoName'] != '' && formData['entityId'] != '' && formData['entityName'] != ''){
				imageRemoveConfirmation(function(confirm){
					if(confirm){
						$.post(public_url+'remove/photos',formData, function(response){
							if(response.status == 'success'){
								prePhotoField.val('');
								photoField.val('');
								$this.attr('src','').addClass('hidden');	
							}
						},'JSON');
					}
				})
			}
		});
		/* End: image remover */
	});	

	$('.classCat').on('change',function(){
	  var classCatPrice = $('.classCat').find(':selected').data('price');
	  $('#classPrice').val(classCatPrice);
	})