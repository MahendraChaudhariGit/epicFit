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
		dd.selectpicker({
			noneSelectedText: " -- Select -- "
		});
	}
}

function initTelInput($elem){
	//alert("initTelInput function");
	$elem.intlTelInput({
		initialCountry: "auto",
		geoIpLookup:function(callback){
			$.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
				var countryCode = (resp && resp.country)?resp.country:"";
				callback(countryCode);
			});
		},
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
		setFieldInvalid(formGroup, 'Please enter new password.');
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
			else if(tag == 'select')
				this.selectedIndex = -1;
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

function prepareCheckBoxSelect($value) {
	return $value != undefined && $value != null && $value != '' ? JSON.parse($value) : '';
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

	if(typeof data == 'undefined' || !data)
		data = '';
	else
		data = '&'+data;

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
    		$.ajax({
        		url:action,
        		type:'delete',
        		success:function(msg){
        			//var response = JSON.parse(msg);
        			var callback=$('.delLink').data('ajax-callback');
        			eval(callback+'(msg)');
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

		if(recreate)
			elem.timepicker('setTime', elem.data('default-time'))
	}
} 

function setFieldForTimepicker(field, time){
	field.val(time).data('default-time', time);
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
function calcSalesProcessRelatedStatus(stepNumb){
    var returnData = {};
    if(stepNumb === 1 || stepNumb == 'contact' || stepNumb == 'pre-consultation'){
        //returnData['clientPrevStatus'] = 'pending';//'Lead';
        returnData['clientPrevStatus'] = ['pending', 'Active Lead', 'Inactive Lead'];
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
    else if(stepNumb === 9 || stepNumb == 'teamed' || stepNumb == 'active'){
        returnData['clientPrevStatus'] = 'Pre-Training';
        returnData['clientStatus'] = 'active';
        returnData['salesProcessType'] = 'teamed';
        returnData['saleProcessStepNumb'] = 9;
    }
    else if(stepNumb === 10 || stepNumb == 'email_price'){
        returnData['clientPrevStatus'] = 'active';
        returnData['salesProcessType'] = 'email_price';
        returnData['saleProcessStepNumb'] = 10;
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
	var oldSaleProcessStep = $('input[name="salesProcessCompleted"]').val(),
		newSaleProcess = calcSalesProcessRelatedStatus(accountStatus),
		consultationDate = $('input[name="consultationDate"]').val();

	if((accountStatus == 'pre-training' || accountStatus == 'active') && oldSaleProcessStep < newSaleProcess.saleProcessStepNumb && consultationDate){
		/*var lastDateInConsultationPeriod = moment(consultationDate).add(15, 'day').format('YYYY-MM-DD')
		if(moment().isSameOrAfter(lastDateInConsultationPeriod)){*/
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
			return;
		} 
	}
	successCallback();
}

function hasConsultDateExpired(consultationDate){
	if(typeof consultationDate == 'undefined')
		consultationDate = $('input[name="consultationDate"]').val();
	if(consultationDate){
		var lastDateInConsultationPeriod = moment(consultationDate).add(15, 'day').format('YYYY-MM-DD');
		if(moment().isSameOrAfter(lastDateInConsultationPeriod))
			return true;
	}
	return false;
}

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
	var selectedDatetimeMoment = moment($('input[name="selectedDatetime"]').val()),
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
	if(elem.data("is-avail") !== undefined)
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
function toggleWaitShield(action){
	if(action == "show")
		$('#waitingShield').removeClass('hidden')
	else if(action == "hide")
		$('#waitingShield').addClass('hidden')
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


function showClientBookedMsg(clientName){
	if(typeof clientName != 'undefined' && clientName)
		var text = clientName;
	else
		var text = 'Erroe found.';
	swal({
        title: text,
        type: "success",
        confirmButtonColor: "#ff4401",
        confirmButtonText: "Okay"
    });
}

/* Start: Benchmark show details */
function displayBenchmarkData(data){
	$.each(data, function(val, text){
		if(text){
			if(val=='nps_day')
				$("#"+camelize(val)).text(dbDateToDateString(text));
			else if(val=='created_at')
				$("#"+camelize(val)).text(dbDateTimeToDateTimeString(text));
	        else if(val == 'waist' || val == 'hips' || val == 'height')
			    $("#"+camelize(val)).text(text+' cm');
			else if(val == 'weight')
				$("#"+camelize(val)).text(text+' kg');
			else if(val == 'benchmarkTemperature')
				$("#"+camelize(val)).html(text+' &#8451;');
			else if(val == 'stress' || val == 'sleep' || val == 'nutrition' || val == 'hydration' || val == 'humidity')
				$("#"+camelize(val)).html(getStarHtml(text));
			else
				$("#"+camelize(val)).text(text);
		}
	});
}
/* End: Benchmark show details */

/* Strat: Create start with value */
function getStarHtml(val){
	var html = '';
	for(var i = 1; i <= 10; i++){
		if(i <= parseInt(val))
			html += '<span class="fa fa-star text-primary"></span>&nbsp;';
		else
			html += '<span class="fa fa-star star-uncheck-color"></span>&nbsp;';
	}
	return html;
}
/* End: Create start with value */

/* start: Remove Underscor and create Camalcase */
function camelize(str) {
  	return str.replace(/\_+(.)/g, function(match, chr){
        return chr.toUpperCase();
    });
}      
/* end: Remove Underscor and create Camalcase */  


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
		if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8))
			return false;
	});
	
	/* Initializing price fields */
	$(document).on("keydown", '.price-field', function(e){
		if($(this).val().indexOf('.') !== -1 && e.keyCode == 190)
			return false;

		if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 190))
			return false;
	});

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
});










