var rowCount = 0,
selectedLength = 0,
selectedData = [],
serviceOption = '';
sessionOption = '';

/* start: Client-Membership modal submit */
function submitClientMemb(action){
	var modal = $('#editMembSub'),
	form = modal.find('form'),
	formData = {};

	formData['clientId'] = form.find('input[name="clientId"]').val();
	formData['clientMembId'] = form.find('input[name="clientMembId"]').val();
	formData['membership'] = form.find('#membership').val();
	if(formData['clientMembId'])
		formData['updateOpt'] = $('#updateClientMemb input[name="updateOpt"]:checked').val();

	var currMembId = formData['clientMembId'] != null && formData['clientMembId'] != undefined && formData['clientMembId'] != '' ? getCurrMembId(formData['clientMembId']) : [];
	var currMembDetails = currMembId != null && currMembId != undefined && currMembId != '' ? getMembershipDetails(currMembId) : [];
	var nextMembDetails = getMembershipDetails(formData['membership']);
	var futureRecurClasses = getFutureRecureClasses(formData['clientId']);
	if(currMembDetails && nextMembDetails && parseInt(currMembDetails.me_class_limit_length) > parseInt(nextMembDetails.me_class_limit_length) && futureRecurClasses.length > 0){
		// modal.modal('hide');
		$('#updateClientMemb').modal('hide');

		if(formData['updateOpt'] == '1') {
			$('#recurClassDeleteModal').find('#recurClassDeleteFormNextCycle').hide();
			$('#recurClassDeleteModal').find('#recurClassDeleteFormProRate').show();

		} else if(formData['updateOpt'] == '2') {
			$('#recurClassDeleteModal').find('#recurClassDeleteFormNextCycle').show();
			$('#recurClassDeleteModal').find('#recurClassDeleteFormProRate').hide();
		}

		if(futureRecurClasses.length <= nextMembDetails.me_class_limit_length)
			$('#recurClassDeleteModal').find('#proceedWithoutDeleting').show();
		else
			$('#recurClassDeleteModal').find('#proceedWithoutDeleting').hide();

		$('#recurClassDeleteModal').modal('show');
	} else {
		newSubmitClientMemb(action, [], []);
	}
}
/* end: Client-Membership modal submit */


/* start: Client-Membership modal new submit */
function newSubmitClientMemb(action, deleteRecureClassesProRate, deleteRecureClassesNextCycle, type='', ServiceDay){
	toggleWaitShield('show');
	action == (action == undefined || action == null)? '' : action;
	var modal = $('#editMembSub'),
	form = modal.find('form'),
	formData = {},
	serviceData = {},
	sessionData = {},
	membStartDateField = form.find('#membStartDate');

	formData['clientId'] = form.find('input[name="clientId"]').val();
	formData['clientMembId'] = form.find('input[name="clientMembId"]').val();
	formData['membership'] = form.find('#membership').val();
	formData['payPlan'] = form.find('#payPlan').val();
	formData['membStartDate'] = dateStringToDbDate(membStartDateField.val());
	formData['membEndDate'] = form.find('input[name="membEndDate"]').val();
	formData['payBy'] = form.find('select#payBy').val();
	formData['payById'] = form.find('select#payBy option:selected').data('paytype-id');
	if(formData['clientMembId'])
		formData['updateOpt'] = $('#updateClientMemb input[name="updateOpt"]:checked').val();

	if(formData['updateOpt'] == '1' && deleteRecureClassesProRate.length > 0) 
		formData['deleteRecureClasses'] = deleteRecureClassesProRate;
	else if(formData['updateOpt'] == '2' && deleteRecureClassesNextCycle.length > 0)
		formData['deleteRecureClasses'] = deleteRecureClassesNextCycle;
	else if(type == 'service'){
		if(deleteRecureClassesProRate.length > 0 || deleteRecureClassesNextCycle.length > 0){
			formData['deleteRecureClasses'] = deleteRecureClassesProRate.length > 0 ? deleteRecureClassesProRate : deleteRecureClassesNextCycle;
			formData['type'] = type;
			formData['serviceDay']  = ServiceDay;
		}
	}
    

	if(form.find('#applyDisc').is(':checked')){
		var  disAmntType = form.find('#discAmountType').val();
		var disType = form.find('#discType').val();

		if(disType == 'fixed'){
			if(disAmntType == 'total'){
				formData['discMembAmt'] = form.find('#discAmt').val();
			}else if(disAmntType == 'unit'){
				formData['discMembUnitFixed'] = form.find('#discAmt').val();
				formData['discMembAmt'] = form.find('#discUnitTotal').val();
			}
		}else{
			if(disAmntType == 'total'){
				formData['discMembPercentage'] = form.find('#disPercent').val();
				formData['discMembAmt'] = form.find('#discPerAmt').val();
			}else if(disAmntType == 'unit'){
				formData['discMembPercentage'] = form.find('#disPercent').val();
				formData['discMembAmt'] = form.find('#discUnitTotal').val();
				formData['discMemUnitPrice'] = form.find('#discPerAmt').val();

			}
		}
		formData['isMembDisc'] = 1;
	
		formData['discMembAmtType'] = disAmntType;
		formData['discMembType'] =form.find('#discType').val();
		formData['discDur'] = form.find('#discDur').val();
		 
	}else{
		formData['isMembDisc'] = 0;
	}
	
	formData['memUnitOriginalPrice'] = $('select#membership option:selected').data('membership-unit-price');
	formData['memTotalOriginalPrice'] = $('select#membership option:selected').data('instamount');
	formData['totalOriginal'] = form.find('input[name="grandtotalprice"]').data('total-original');
	$.each(form.find('#service-group .new-row input,#service-group .new-row select'), function(){
		var discountType = $(this).data('discount-type'),
		discountAmount = $(this).data('discount-amount'),
		discountPrice = $(this).data('service-discount-price'),
		rowCount = $(this).data('row-count');
		if(discountType != undefined && discountAmount != undefined && discountPrice != undefined){
			serviceData['mem_discount_type'+rowCount] = discountType;
			serviceData['mem_discount_amount'+rowCount] = discountAmount;
			serviceData['mem_discount_price'+rowCount] = discountPrice;

		}
		serviceData[$(this).attr('name')] = $(this).attr('value');
	});
	formData['services'] = serviceData;

	$.each(form.find('#session-group .new-row input,#session-group .new-row select'), function(){
		var discountType = $(this).data('session-discount-type'),
		discountAmount = $(this).data('session-discount-amount'),
		discountPrice = $(this).data('session-discount-price'),
		
		rowCount = $(this).data('row-count');
		if(discountType != undefined && discountAmount != undefined && discountPrice != undefined){
			sessionData['session_mem_discount_type'+rowCount] = discountType;
			sessionData['session_mem_discount_amount'+rowCount] = discountAmount;
			sessionData['session_mem_discount_price'+rowCount] = discountPrice;

		}
		sessionData[$(this).attr('name')] = $(this).attr('value');
	});
	formData['session'] = sessionData;
	formData['totalAmount'] = form.find('input[name="grandtotalprice"]').val();
	formData['membTotal'] = form.find('input[name="membTotalPrice"]').val();
	$.post(form.attr('action'), formData, function(data){
		var data = JSON.parse(data);
		toggleWaitShield('hide');
		$('#recurClassDeleteModal').modal('hide');
		$('#recurSessionDeleteModal').modal('hide');
		if(data.status == "updated"){
			$('#updateClientMemb').modal('hide');
			var cb = form.find('input[name="cb"]').val();
			var activeNumb = form.find('input[name="stepNumb"]').val();
			if(activeNumb != ''){
				modal.modal('hide');
				updateSalesStepAjax({ action: "complete", stepNumb: activeNumb, status:'Active' });
			}
			else if(cb){
				modal.modal('hide');
				eval(cb);
			}
			else {

				if(action == 'openClientAccStatusPopup') {
					var editFieldModal = $("#editFieldModal"),
					entityField = editFieldModal.find('input[name="entity"]'),
					entityIdField = editFieldModal.find('input[name="entityId"]'),
					otherTargetsField = editFieldModal.find('input[name="otherTargets"]'),
					label = editFieldModal.find('label'),
					formGroup = editFieldModal.find('.form-group'),
					fields = editFieldModal.find('.field'),
					field_type = 'accStatus';

					if(fields.hasClass('editFieldModal-cntryCode'))
						fields.intlTelInput("destroy");
					if(fields.length)
						fields.remove();

					formGroup.removeClass().addClass('form-group').find('span.help-block').remove();
					editFieldModal.addClass('fromEditMemPopup');
					editFieldModal.find('input[name="entityProperty"]').val(field_type);
					var field = $('<select id="'+field_type+'" class="form-control field" name="'+field_type+'"><option value="pending">Pending</option><option value="active-lead">Active lead</option><option value="inactive-lead">Inactive lead</option><option value="pre-consultation">Pre Consultation</option><option value="pre-benchmarking">Pre Benchmarking</option><option value="pre-training">Pre Training</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="on-hold">On hold</option><option value="contra">Contra</option></select>').insertAfter(label);
					field.attr('required', 'true')
					var labelText = 'Status *';
					label.text(labelText)
					label.attr('for', field_type);
					field.val($('.clientProfileAccStatus').attr('data-value'));
					field.selectpicker('refresh');

					modal.modal('hide');
					editFieldModal.modal();
				}
			}
		}
		else {
			$('#editMembStatus').removeClass('hidden').text(data.errorData[0].invalidRecord);
			$('#updateClientMemb').modal('hide');
		}
	});
}
/* end: Client-Membership modal new submit */

/* start: Get current membership id */
function getCurrMembId(clientMembId) {
	var membershipId = 0;
	$.ajax({
		url : public_url+'client/membership/'+clientMembId,
		type : 'GET',
		data : {},
		async: false,
		success : function(response) {
			if(response.status == true)
				membershipId = response.membershipId;
		}
	});

	return membershipId;
}
/* end: Get current membership id */

/* start: Get membership details */
function getMembershipDetails(membershipId) {
	var membershipDetails = {};
	$.ajax({
		url : public_url+'membership/'+membershipId,
		type : 'GET',
		data : {},
		async: false,
		success : function(response) {
			// data = JSON.parse(response);
			if(response.status == true)
				membershipDetails = response.membership;
		}
	});

	return membershipDetails;
}
/* end: Get membership details */


/* start: Get client future recure classes */
function getFutureRecureClasses(clientId) {
	var futureRecurClasses = {};
	$.ajax({
		url : public_url+'client/future-recure-classes/'+clientId,
		type : 'GET',
		data : {},
		async: false,
		success : function(response) {
			if(response.status == true)
				futureRecurClasses = response.futureRecureClasses;
		}
	});

	return futureRecurClasses;
}
/* end: Get client future recure classes */

/* start: Calculate and set membership end date */
function setMembEndDate(callback){
	var membershipDd = $('#membership'),
	startDate = $('#membStartDate').val(),
	endDate = '';
	
	if(membershipDd.val() && startDate){
		var membershipOpt = membershipDd.find('option:selected'),
		membLength = membershipOpt.data('length'),
		membLengthUnit = membershipOpt.data('length-unit');

		endDate = moment(startDate, 'ddd, D MMM YYYY').add(membLength, membLengthUnit).subtract(1, "days").format("YYYY-MM-DD");
	}
	$('input[name="membEndDate"]').val(endDate)

	if(typeof callback != 'undefined')
		callback();
}
/* end: Calculate and set membership end date */

/* start: Toggle discount fields based on input */
function toggleMembdiscFields(cb){
	if(cb.is(':checked')) {
		var disType = $('select[name="discType"] option:selected').val();
		if(disType == 'fixed'){
			if($('.discAmt').hasClass('hide')){
			$('.discAmt').removeClass('hide');
			}
		}
		$('#discFields').show();

	}
	else{
		$('#discFields').hide();
		var membTotalPrice = $('select#membership option:selected').data('instamount'),
		membUnitPrice = $('select#membership option:selected').data('membership-unit-price');
		if(membTotalPrice != undefined && membTotalPrice != ''){
		$('#membTotalPrice').val(parseFloat(membTotalPrice).toFixed(2));
		$('#membUnitPrice').val(membUnitPrice);
		$('#discAmt').val(0);
		$('#disPercent').val(0);
		$('#discPerAmt').val(0);
	}
}
totalMembPrice();
}
/* end: Toggle discount fields based on input */

/* Start: Create new row for service */
function createNewRow(serviceId, data, serviceOption, membValidityType){
	console.log(data);
	var modal = $('#editMembSub'),
	fieldset = modal.find('#service-group'),
	newRow = fieldset.children().last().clone();

	rowCount++;
	newRow.removeClass('hidden');
	newRow.addClass('new-row');
	var ddGroup = newRow.find('.serviceGroup');
	var serviceField = ddGroup.find('select.mem_service');
	ddGroup.empty();
	if(typeof serviceOption != 'undefined' && serviceOption !=''){
		serviceField.empty();
		serviceField.append(serviceOption);
	}
	ddGroup.append(serviceField);
	ddGroup.find('select.mem_service').val(serviceId).attr('name','mem_service'+rowCount).prop('required',true).selectpicker('refresh');

	// ddGroup.find('select.mem_service option:selected').trigger('change');
	newRow.find('.mem_limit').val(data.limit).attr('name', 'mem_limit'+rowCount).prop('required',true);
	var price = parseFloat(ddGroup.find('select.mem_service option:selected').data('price'));
	var editedPrice = parseFloat(ddGroup.find('select.mem_service option:selected').data('editedprice'));
	var limit = newRow.find('.mem_limit').val();
	if(isNaN(price))
		price = 0;

	if(isNaN(editedPrice) || editedPrice == 0)
		price = servicePriceAccOfType(membValidityType, data.limit_type, price, limit);
	else
		price = editedPrice.toFixed(2);
	newRow.find('.mem_price').data('row-count',rowCount);	
	newRow.find('.mem_price').val(price).attr('name', 'mem_price'+rowCount).prop('required',true);

	newRow.find('.mem_unit_price').val(data.mem_unit_price == undefined ? '0' : parseFloat(data.mem_unit_price).toFixed(2)).attr('name', 'mem_unit_price'+rowCount).prop('required',true);

	newRow.find('.mem_unit_price').data('service-unit-total',data.mem_unit_price);
	newRow.find('.mem_price').data('service-original-total',price);
	newRow.find('.mem_price').data('discount-type',data.discount_type);
	newRow.find('.mem_price').data('discount-amount',data.discount_amount);
 	newRow.find('.mem_price').data('service-discount-price',data.discount_price_type);
	newRow.find('.service-discount-type-data').val(data.discount_amount);
	newRow.find('.service-discount-per-data').val(data.serviceDiscountPerData);
	newRow.find('.serviceDiscountName').addClass('hide');
	if(data.discount_type == 'fixed'){
	newRow.find('.serviceDiscountName').text('$');
	newRow.find('.serviceDiscountName').removeClass('hide');
	

	}
	if(data.discount_price_type == 'serviceUnitPrice'){
		if(data.discount_type == 'fixed'){
			var service =  newRow.find('.service-discount-type-data').closest('.form-group');
			service.find('.serviceFinalDiscount').empty().append('<h5>Unit price after discount</h5>');
		}else{
			var service = newRow.find('.serviceDiscountPerAmount').closest('.form-group');
          service.find('.servicePercentFinal').empty().append('<h5>Unit price after discount</h5>');
		}
	}else if(data.discount_price_type == 'service_price'){
		if(data.discount_type == 'fixed'){
			var service = newRow.find('.service-discount-type-data').closest('.form-group');
			service.find('.serviceFinalDiscount').empty().append('<h5>Total price after discount</h5>');
		}else{
			var service = newRow.find('.serviceDiscountPerAmount').closest('.form-group');
          service.find('.servicePercentFinal').empty().append('<h5>Total price after discount</h5>');
		}
	}else{
		var service = newRow.find('.service-discount-type-data').closest('.form-group');
			service.find('.serviceFinalDiscount').empty();
			var service = newRow.find('.serviceDiscountPerAmount').closest('.form-group');
			service.find('.servicePercentFinal').empty();
	}
	if(data.discount_type == 'percent'){
		newRow.find('.serviceDiscountName').text('%');
		newRow.find('.serviceDiscountName').removeClass('hide');
	}
	if((data.discount_price_type == 'serviceUnitPrice' || data.discount_price_type == 'service_price') && data.discount_type == 'percent'){
        newRow.find('.serviceDiscountPerAmount').removeClass('hide');
	}
	var ddGroup2 = newRow.find('.limitTypeGroup');
	var dd2 = ddGroup2.find('select.mem_type');
	ddGroup2.empty();
	ddGroup2.append(dd2);
	ddGroup2.find('select.mem_type').val(data.limit_type).attr('name','mem_type'+rowCount).prop('required',true).selectpicker('refresh');

	fieldset.append(newRow);
	totalMembPrice();
	var discountPopoverOpt = {
		html: true,
		content: "<div class='row popoverContent' data-action='apply_coupon'><div class='col-md-12'><select class='form-control discount-type' name='discount_type'><option value=''>Choose discount</option><option value='fixed'>Fixed</option><option value='percent'>Percent</option></select></div><div class='col-md-12' style='margin-top:10px; margin-bottom:10px;'><div class='form-group m-b-0 servicePriceOption' style='margin-bottom: 0px'><div class='radio clip-radio radio-primary radio-inline m-b-0'><input type='radio' id='serviceUnitPrice' name='price' value='serviceUnitPrice' checked><label for='serviceUnitPrice'>Unit Price</label></div><div class='radio clip-radio radio-primary radio-inline m-b-0'><input type='radio' name='price' id='serviceTotalPrice' value='service_price'><label for='serviceTotalPrice'>Total Price</label></div><span class='help-block' style='margin-bottom: 0px'></span></div><input type='number' name='disamount' class= 'amount hide'><input type ='hidden' name='row_num' class='discount-row-num'/><input type ='hidden' name='totalAmount' class='discount-total-amount'/></div></div><div class='clearfix'><div class ='row'><div class ='col-md-6'><button class='btn btn-primary closePopup' type='button'> Cancel </button></div><div class ='col-md-3'><button class='btn btn-primary service-discount-submit' type='button' style ='width:80px;white-space:normal;'> Apply</button></div></div></div>",
		container: $('#editMembSub'),
		title: "<strong>Apply a discount</strong>",
		placement: 'top',
		trigger: 'click'
	};
	
	modal.find('.apply-service-discount').each(function(){
		$(this).popover(discountPopoverOpt);
	});
	
}
/* End: Create new row for service */

/* Start: Display services according to memebership */
function displayServiceRow(response){
	var memberVailidityType = $('select#membership option:selected').data('length-unit');
	var service = response.service;
	var serviceData = response.extradata;
	serviceOption = '';

	if(Object.keys(service).length > 0){
		$.each(service, function(key, value){
			serviceOption += `<option value='${key}' data-price='${value.price}' data-editedprice='${value.editedPrice}'>${value.name}</option>`;
		});
	}

	if(Object.keys(serviceData).length > 0){
		$.each(serviceData, function(key, value){
			createNewRow(key, value, serviceOption, memberVailidityType);
		});
	}
	
}
/* End: Display services according to memebership */

/* Start: get services which link to memebership */
function getServicesOfClient(membershipId){
	var serviceGroup = $('#service-group'),
	clientId = $('#editMembSub').find('input[name="clientId"]').val();
	if(typeof membershipId != 'undefined' && membershipId){
		serviceGroup.removeClass('hidden');
		$.get(public_url+'clients/membership/services', {membershipId:membershipId, clientId:clientId}, function(response){
			resetServiceRow($('#editMembSub'));
			displayServiceRow(response);
		},'JSON');
	}
	else{
		serviceGroup.addClass('hidden');
	}
}
/* Start: get services which link to memebership */

/* Start: reset row of membership service */
function resetServiceRow(modal){
	var fieldset = modal.find('#service-group');
	fieldset.find('.new-row').remove();
}
/* End: reset row of membership service */

/* Start: update service price */
function updateServicePrice($this){
	var row =  $this.closest('.row'), 
	memberVailidityType = $('select#membership option:selected').data('length-unit'),
	price = parseFloat(row.find('select.mem_service option:selected').data('price')),
	type = row.find('select.mem_type').val();
	var limit = row.find('.mem_limit').val();
	if(isNaN(price) /*|| isNaN(unit)*/)
		price = 0;

	var cost = servicePriceAccOfType(memberVailidityType, type, price, limit);
	row.find('.mem_price').val(parseFloat(cost).toFixed(2));
	row.find('.mem_unit_price').val(parseFloat(price).toFixed(2));
	row.find('.mem_price').data('service-original-total', cost);
	row.find('.mem_unit_price').data('service-unit-total', price);
	totalMembPrice();
}
/* End: update service price */

/* Start: Calculate total price */
function totalMembPrice(){
	var modal = $('#editMembSub'),
	form = modal.find('form'),
    disAmntType = $('#discAmountType').val();
	totalOriginalAmount = parseFloat(form.find('select#membership option:selected').data('instamount'));
	if(form.find('#applyDisc').is(':checked')){
		var disType = form.find('#discType').val();
		console.log(disType,disAmntType);
		if(disType == 'fixed'){
			if(disAmntType == 'total'){
				originalAmount =parseFloat( form.find('#discAmt').val());
			}else if(disAmntType == 'unit'){
				originalAmount =parseFloat( form.find('#discUnitTotal').val());
			}
		}else{
			if(disAmntType == 'total'){
				originalAmount= parseFloat(form.find('#discPerAmt').val());
				console.log(originalAmount);
			}else if(disAmntType == 'unit'){
				originalAmount =parseFloat(form.find('#discUnitTotal').val());
			}
		}
		
	}else{

	originalAmount = parseFloat(form.find('#membTotalPrice').val());
	}
	total = 0;
	totalOriginal = 0;
	$.each(form.find('#service-group .new-row'), function(){
		var discAmountType = $(this).find('.mem_price').data('service-discount-price');
		var disType = $(this).find('.mem_price').data('discount-type');
		if(disType == 'fixed'){
			if(discAmountType == 'service_price'){
				var price = parseFloat($(this).find('.service-discount-type-data').val());
			}else if(discAmountType == 'serviceUnitPrice'){
				var price = parseFloat($(this).find('.mem_price').val());
			}
		}else if(disType == 'percent'){
			if(discAmountType == 'service_price'){
			var price = parseFloat($(this).find('.service-discount-per-data').val());
			}else if(discAmountType == 'serviceUnitPrice'){
				var price = parseFloat($(this).find('.mem_price').val());
			}
		}
	
		if(price == 0 || price == null || isNaN(price)){
			var price = parseFloat($(this).find('.mem_price').val());
		}
		// unit = parseInt($(this).find('.mem_limit').val());
		if(isNaN(price))
			price = 0;
		// if(isNaN(unit))
		// 	unit = 0;
		total += price;
		totalOriginal += price;
	})

	$.each(form.find('#session-group .new-row'), function(){
		var discAmountType = $(this).find('.session_mem_price').data('session-discount-price');
		var disType = $(this).find('.session_mem_price').data('session-discount-type');
		if(disType == 'fixed'){
			if(discAmountType == 'session_price'){
				var price = parseFloat($(this).find('.discount-type-data').val());
			}else if(discAmountType == 'sessionUnitPrice'){
				var price =  parseFloat($(this).find('.session_mem_price').val());
			}

		}else if(disType == 'percent'){
			if(discAmountType == 'session_price'){
			var price = parseFloat($(this).find('.session-discount-per-data').val());
		}else if(discAmountType == 'sessionUnitPrice'){
			var price =  parseFloat($(this).find('.session_mem_price').val());
		}
		}
		if(price == 0 || price == null || isNaN(price)){
			var price = parseFloat($(this).find('.session_mem_price').val());
		}
		// unit = parseInt($(this).find('.session_mem_limit').val());
		if(isNaN(price))
			price = 0;
		// if(isNaN(unit))
		// 	unit = 0;
		total += price;
		totalOriginal += price;
	})

	if(!isNaN(originalAmount)){
		total += originalAmount;
		totalOriginal +=totalOriginalAmount;
	}


	form.find('input[name="grandtotalprice"]').val(total.toFixed(2));
	var payPlanType = $('select[name="payPlan"] option:selected').val();
	if(payPlanType == 'week'){
      payAmount = total * 12/52;
	}else if(payPlanType == 'fortnight'){
	  payAmount = total * 12/26;  
	}else if(payPlanType == 'month'){
		payAmount = total; 
	}else if(payPlanType == '3month'){
		payAmount = total * 3; 
	}else if(payPlanType == '6month'){
		payAmount = total * 6; 
	}else if(payPlanType == 'year'){
		payAmount = total * 12; 
	}
	$('#paymentAmount').val(parseFloat(payAmount).toFixed(2));
	form.find('input[name="grandtotalprice"]').data('total-original',totalOriginal);
	return total.toFixed(2);

}
/* End: Calculate total price */

/* Start: remove Class Confirm function */
function removeClassConfirm(callback){
	swal({
		title: "Are you sure to remove this class?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#d43f3a",
		confirmButtonText: "Yes, remove it!",
		allowOutsideClick: false,
		customClass: 'delete-alert'
	}, 
	function(confirm){
		if(typeof confirm != 'undefined')
			callback(confirm)
	});
}
/* End: remove Class Confirm function */

/* Start: Calculate service price according to memebership */
function servicePriceAccOfType(membValidityType, limitType, cost, limit){
	if(membValidityType == 'day'){
		switch(limitType){ 
			case 'every_month':
			cost = (cost * 12) / 365;
			break;
			case 'every_fortnight':
			cost = (cost * 24.5) / 365;
			break;
			case 'every_week':
			cost = (cost / 7);
		}
	}
	else if(membValidityType == 'week'){
		switch(limitType){ 
			case 'every_month':
			cost = cost * 12/52;
			break;
			case 'every_fortnight':
			cost = cost / 2;
			break;
			case 'every_week':
			cost = cost;
		}
	}
	else if(membValidityType == 'month'){
		switch(limitType){ 
			case 'every_month':
			cost = cost;
			break;
			case 'every_fortnight':
			cost = cost * 2;
			break;
			case 'every_week':
			cost = cost * 52/12;
		}
	}
	else if(membValidityType == 'year'){
		switch(limitType){ 
			case 'every_month':
			cost = cost * 12;
			break;
			case 'every_fortnight':
			cost = cost * 24.5;
			break;
			case 'every_week':
			cost = cost * 52;
		}
	}
	cost = cost * limit;
	return cost.toFixed(2);
}
/* End: Calculate service price according to memebership */

jQuery(document).ready(function(){
	/* Start: Client-Membership Modal */

	/* Reset remove class hidden field */
	$('input[name="removeClasses"]').val('');

	/* Initialized datepicker */
$('#membStartDate').datepicker({dateFormat:"D, d M yy"/*, minDate:0*/});

/* start: Client-Membership modal reset */
$('#editMembSub').on('show.bs.modal', function(){
	var modal = $(this),
	membershipDd = modal.find('select#membership option:selected'),
	payPlanDd = modal.find('#payPlan'),
	applyDiscCb = modal.find('#applyDisc'),
	discAmtField = modal.find('#discAmt'),
	discPerField = modal.find('#disPercent'),
	discAmountType = modal.find('select#discAmountType').val();
	discType = modal.find('#discType').val();
	discDurDd = modal.find('#discDur'),
	membStartDateField = modal.find('#membStartDate'),
			membStatusRow = modal.find('#membStatus')/*,
			renwDateDd = modal.find('select[name="renwDate"]'),
			renwDayDd = modal.find('select[name="renwDay"]')*/;

		// get service data
		console.log(membershipDd.val());
		getServicesOfClient(membershipDd.val());
		getSessionOfClient(membershipDd.val());
		console.log($('#membUnitPrice').val());
		if(hasData(modal, 'opened')){ //create
			//Modal is being opened for second time or greater
			membershipDd.val(membershipDd.data('val'));
			setFieldNeutral(membershipDd);

			payPlanDd.val(payPlanDd.data('val'))
			setFieldNeutral(payPlanDd);

			applyDiscCb.prop('checked', applyDiscCb.data('checked'))

			discAmtField.val(discAmtField.data('val'))
			setFieldNeutral(discAmtField);

			discPerField.val(discPerField.data('val'))
			setFieldNeutral(discPerField);
			  

			discDurDd.val(discDurDd.data('val'))
			setFieldNeutral(discDurDd);

			setFieldNeutral(membStartDateField);

			if(membStatusRow.length){
				var status = membStatusRow.data('val');
				membStatusRow.find('input[value="'+status+'"]').prop('checked', true);
			}

			/*renwDateDd.val(renwDateDd.data('val'))		
			setFieldNeutral(renwDateDd);

			renwDayDd.val(renwDayDd.data('val'))		
			setFieldNeutral(renwDayDd);*/
			var memAmount = parseFloat(modal.find('select#membership option:selected').data('instamount'));
			var memTax = parseFloat(modal.find('select#membership option:selected').data('membership-unit-price'));
			var classLimit = $('select#membership option:selected').data('class-limit');
			var discountPerAmount = discPerField.val();
			var discountFixAmount = discAmtField.val();
			console.log($('#membUnitPrice').val());
			// if(discAmountType == 'total'){
			// 	if(discType == 'percent' && discountPerAmount != ''){
			// 		$('#membTotalPrice').val(parseFloat(memAmount).toFixed(2));
			// 	}else if(discType == 'fixed' && discountFixAmount != ''){
			// 		$('#membTotalPrice').val(parseFloat(memAmount).toFixed(2));
			// 	}
			// }

			modal.find('select').selectpicker('refresh')

		}
		else{//edit 
			//Modal is being opened for first time
			modal.data('opened', true);
			membershipDd.data('val', membershipDd.val());
			payPlanDd.data('val', payPlanDd.val())
			discAmtField.data('val', discAmtField.val())
			discPerField.data('val', discPerField.val())
			discDurDd.data('val', discDurDd.val())
          
			var val = membStartDateField.val();
			if(val)
				val = dbDateToDateString(membStartDateField.val());
			membStartDateField.data('val', val)
			if(applyDiscCb.is(':checked'))
				var checked = 'checked';
			else
				var checked = false;
			applyDiscCb.data('checked', checked)
			if(membStatusRow.length){
				var status = membStatusRow.find('input:checked').val();
				membStatusRow.data('val', status);
			}
			/*var val = renwDateDd.val();
			renwDateDd.data('val', val?val:'')

			var val = renwDayDd.val();
			renwDayDd.data('val', val?val:'')*/
			var memAmount = parseFloat(modal.find('select#membership option:selected').data('instamount'));
			var memUnitPrice = parseFloat(modal.find('select#membership option:selected').data('membership-unit-price'));
			var classLimit = $('select#membership option:selected').data('class-limit');
			var discountPerAmount = discPerField.val();
			var discountFixAmount = discAmtField.val();
			// if(discAmountType == 'total'){
			// 	if(discType == 'percent' && discountPerAmount != ''){
			// 		$('#membTotalPrice').val(parseFloat(memAmount).toFixed(2));
			// 	}else if(discType == 'fixed' && discountFixAmount != ''){
			// 		$('#membTotalPrice').val(parseFloat(memAmount).toFixed(2));
			// 	}
			// }else if(discAmountType == 'unit'){
			// 	console.log()
			// 	if(discType == 'percent' && discountPerAmount != ''){
			// 		$('#membTotalPrice').val(parseFloat(memAmount).toFixed(2));
			// 	}else if(discType == 'fixed' && discountFixAmount != ''){
			// 		$('#membTotalPrice').val(parseFloat(memAmount).toFixed(2));
			// 	}
			// }

		}

		membStartDateField.val(membStartDateField.data('val'))
		toggleMembdiscFields(applyDiscCb);
		// setMembEndDate();
		//toggleRenwPeriod(instllPlanDd.val());
		// var modal = $('#editMembSub'),
		var disType = modal.find('#service-group input[name="discountType"]').val(),
		disAmount= modal.find('#service-group input[name="discountAmount"]').val();
		total =	totalMembPrice();
		if(disType == 'percent'){
			var disPrice = disAmount/total * 100;
			modal.find('#service-group #disPercent').val(parseFloat(disPrice).toFixed(2));
		}
		var disType = modal.find('#session-group input[name="discountType"]').val(),
		disAmount= modal.find('#session-group input[name="discountAmount"]').val();
		total =	totalMembPrice();
		if(disType == 'percent'){
			var disPrice = disAmount/total * 100;
			modal.find('#session-group #disPercent').val(parseFloat(disPrice).toFixed(2));
		}
		if(isNaN(memAmount)){
			memAmount = 0;
		}
		$('#membTotalPrice').val(parseFloat(memAmount).toFixed(2));
		$('select[name="payPlan"]').trigger('change');
	// $('#membTotalPrice').val($('#membTotalPrice').val() -discAmtField.val());

});

$('#editMembSub').on('hide.bs.modal', function(){
	$(this).find('input[name="cb"]').val('');
	$(this).find('input[name="clientStatus"]').val('');
	resetServiceRow($(this));
});
/* end: Client-Membership modal reset */

/* start: Toggle discount fields based on input */
$('#applyDisc').change(function(){
	toggleMembdiscFields($(this));
})
/* end: Toggle discount fields based on input */

/* start: Calculate and set membership end date */
$('#membStartDate').change(function(){
	setMembEndDate();
})
/* end: Calculate and set membership end date */

/* start: Client-Membership modal submit */
$('#editMembSubModelSubmit').click(function(e){
	swal({
		title:'Are you sure?',
		text: 'Sessions will be deleted if downgrade but if upgrade existing sessions will stay and extra can be added',
		allowOutsideClick: true,
		showCancelButton: true,
		confirmButtonText: 'Yes',
		confirmButtonColor: '#ff4401',
		cancelButtonText: "No"
	}, 
	function(isConfirm){
		if(isConfirm){
			var form = $('#editMembSub form'),
			membStartDateField = form.find('#membStartDate'),
			isFormValid = form.valid();

			if(!membStartDateField.val()){
				isFormValid = false;
				setFieldInvalid(membStartDateField.closest('.form-group'), 'This field is required.');
			}

			if(isFormValid){
				$('#updateClientMemb :input').prop('checked', false);
				var updateCase = false;
				if(form.find('input[name="clientMembId"]').val()){
					updateCase = true
					var membershipDd = form.find('#membership');
					if(membershipDd.val() == membershipDd.data('val')){
						var payPlanDd = form.find('#payPlan');
						if(payPlanDd.val() == payPlanDd.data('val')){
							var applyDiscCb = form.find('#applyDisc');
							if(applyDiscCb.prop('checked') == applyDiscCb.data('checked')){
								if(applyDiscCb.is(':checked')){
									var discType = form.find('#discType');
									var discAmtField = form.find('#discAmt');
									if(discAmtField.val() == discAmtField.data('val')){
										var discDurDd = form.find('#discDur');
										if(discDurDd.val() == discDurDd.data('val')){	
											updateCase = false;
										}
									}
								}
								else
									updateCase = false;
							}
						}
					}
				}					

				if(updateCase) 
					$('#updateClientMemb').modal('show'); 
				else 
					submitClientMemb('openClientAccStatusPopup');
			}
		}
	});
})
/* end: Client-Membership modal submit */

/* start: Client-Membership update modal submit */
$('#updateClientMembSubmit').click(function(){
	var form = $('#updateClientMemb form');

	if(validateRadioButton(form, 'updateOpt'))
		submitClientMemb('openClientAccStatusPopup');
	else{
		formGroup = form.find("input[name='updateOpt']").closest('.form-group');
		setFieldInvalid(formGroup,'Please select an option.');
	}
})
/* end: Client-Membership update modal submit */

/* start: Prompt to cancel selected membership */
$('#cancelMembOpt').click(function(e){
	e.preventDefault();
	var delPopover = cancelMembPopoverOpt;
	showPopoverOverModal($(this), delPopover);
});
/* end: Prompt to cancel selected membership */

/* start: Future recure classes delete modal submit */
$('#recurClassDeleteSubmit').click(function(){
	var recureDeleteClassProRate = [];
	var recureDeleteClassNextCycle = [];

	/* For membership update with pro rate */
	$('#recurClassDeleteFormProRate').find('input').each(function(index){
		if($(this).is(":checked"))
			recureDeleteClassProRate.push( $(this).val() );
	});

	/* For membership update with next cycle */
	$('#recurClassDeleteFormNextCycle').find('input').each(function(index){
		if($(this).is(":checked"))
			recureDeleteClassNextCycle.push( $(this).val() );
	});

	newSubmitClientMemb('openClientAccStatusPopup', recureDeleteClassProRate, recureDeleteClassNextCycle);

	$('#recurClassDeleteModal').modal('hide');
});
/* end:  Future recure classes delete modal submit */

/* start: Future recure classes delete modal submit */
$('#proceedWithoutDeleting').click(function(){
	// console.log('Procced Without delete');
	var recureDeleteClassProRate = [];
	var recureDeleteClassNextCycle = [];

	newSubmitClientMemb('openClientAccStatusPopup', recureDeleteClassProRate, recureDeleteClassNextCycle);

	$('#recurClassDeleteModal').modal('hide');
});
/* end:  Future recure classes delete modal submit */

/* Start: Cancel selected membership */
$('body').on("click", '#cancelMemb', function(e){
	toggleWaitShield('show');
	e.preventDefault();
	hidePopoverOverModal($('#cancelMembOpt'), cancelMembPopoverOpt);
	var clientid = $('#editMembSub input[name="clientId"]').val();
	var makeupModal = $('#raiseMakeUpModelForMemebership');
	$.post(public_url+'clients/'+clientid+'/membership/delete', function(data){
		toggleWaitShield('hide');
		var data = JSON.parse(data);
		if(data.status == "deleted"){
			location.reload(true);
		}
		else if(data.status == "epicBal"){
			hidePopoverOverModal($('#cancelMembOpt'), cancelMembPopoverOpt);
			$('#editMembSub').modal('hide');
			makeupModal.find('#makeup-check').closest('div').hide();
			makeupModal.find('input[name="clientId"]').val(clientid);
			makeupModal.find('input[name="makeupCreate"]').val('makeupCreate');
			makeupModal.find('select#purpose').val('manual').selectpicker('refresh');
			makeupModal.find('#amount').val(parseFloat(data.amount));
			makeupModal.find('#netamount').text(data.epicCredit);
			makeupModal.modal('show');
		}  
	});
})
/* Start: Cancel selected membership */

/* Start: Makeup submit modal */
$('#raiseMakeUpModelForMemebership .submit').click(function(e){
	$(this).prop('disabled', true);
	e.preventDefault();
	var formData = {},
	modal = $('#raiseMakeUpModelForMemebership'),
	form = modal.find('form'),
	isFormValid = form.valid();

	if(isFormValid){
		$.each(form.serializeArray(), function(i, field){
			formData[field.name] = field.value;
		})

		$.post(public_url+'clients/membership/makeup', formData, function(data){
			var data = JSON.parse(data);
			$(this).prop('disabled', false);
			if(data.status == "added"){
				location.reload(true);
			}
		});
	}
})
/* End: Makeup submit modal */

$('#delete-memeb').click(function(e){
	e.preventDefault();
	var clientid = $('#editMembSub input[name="clientId"]').val();
	$.post(public_url+'clients/'+clientid+'/membership/delete', {'preventepic':'yes'}, function(data){
		var data = JSON.parse(data);
		if(data.status == "deleted"){
			location.reload(true);
		}
	});
})

/* Start: add more row for service */
$('#add-member-service').click(function(e){
	e.preventDefault();
	var memberVailidityType = $('select#membership option:selected').data('length-unit');
	createNewRow(0, {limit:'1',limit_type:''}, serviceOption, memberVailidityType);
});
/* Start: add more row for service */

/* Start: add more row for class */
$('#add-member-session').click(function(e){
	e.preventDefault();
	var memberVailidityType = $('select#membership option:selected').data('length-unit');
	createNewRowSession(0, {limit:'1',limit_type:''}, sessionOption, memberVailidityType);
});
/* Start: add more row for class */

/* Start: delete row for service */
$('body').on('click', '.remove-member-service-row', function(e){
	e.preventDefault();
	var $this = $(this);
	fieldset = $this.closest('fieldset');
	rowLength = fieldset.find('.remove-memb-service-row').length;

	$this.closest('.row').remove();

	if(rowLength <= 2)
		fieldset.find('.service-warning').removeClass('hidden');
	totalMembPrice();
});
/* Start: delete row for service */

/* Start: on chnage membership display service */
$('select#membership').change(function(){
	var modal = $(this).closest('#editMembSub');
	var serviceGroupLength = modal.find('#service-group').find('.serviceGroup').length;
	var sessionGroupLength = modal.find('#session-group').find('.sessionGroup').length;
	var classLimit = $('select#membership option:selected').data('class-limit');
	// resetServiceRow(modal)
	// setMembEndDate();
	modal.find('input[name="membTotalPrice"]').val(0);
     if(sessionGroupLength == 1)
	      getSessionOfClient($(this).val());

	if(serviceGroupLength == 1)
		getServicesOfClient($(this).val());
		var memAmount = parseFloat(modal.find('select#membership option:selected').data('instamount'));
		var membUnitPrice = parseFloat(modal.find('select#membership option:selected').data('membership-unit-price'));

		if(isNaN(memAmount)){
			modal.find('input[name="membTotalPrice"]').val(0);
			modal.find('input[name="membUnitPrice"]').val(0);
		}else{
			
			modal.find('input[name="membUnitPrice"]').val(membUnitPrice.toFixed(2));
			modal.find('input[name="membTotalPrice"]').val(memAmount.toFixed(2));
		}
		if(modal.find('#applyDisc').is(':checked')){
			disPer = $('#discType').val();
			var disAmntType = $('#discAmountType').val();
			
			var unitPrice = modal.find('input[name="membUnitPrice"]').val();
			if(disPer == 'fixed'){
				discAmt = $('#discAmt').val();
				if(disAmntType == 'total'){
					if(!$('.discUnitTotal').hasClass('hide')){
						$('.discUnitTotal').addClass('hide');
					}
					membUpdatePrice =  discAmt;
					membUpdateUnitPrice = ((membUpdatePrice * 12)/52)/classLimit;
					$('#membUnitPrice').val(membUpdateUnitPrice.toFixed(2));
					$('input[name="discUnitTotal"]').val('0');
				}else if(disAmntType == 'unit'){
					$('.discUnitTotal').removeClass('hide');
					memDiscUnitTotal  =  ((discAmt * 52)/12)* classLimit;
					$('input[name="discUnitTotal"]').val(memDiscUnitTotal.toFixed(2));
					
				}
			
			}else{
				discAmt = $('#disPercent').val();
				if(disAmntType == 'total'){
					if(!$('.discUnitTotal').hasClass('hide')){
						$('.discUnitTotal').addClass('hide');
					}
					newAmt = membTotalPrice * discAmt/100;
					membUpdatePrice = membTotalPrice - newAmt;
					membUpdateUnitPrice = ((membUpdatePrice * 12)/52)/classLimit;
					$('input[name="discPerAmt"]').val(membUpdatePrice.toFixed(2));
					$('#membUnitPrice').val(membUpdateUnitPrice.toFixed(2));
					$('input[name="discUnitTotal"]').val('0');
				}else if(disAmntType == 'unit'){
					$('.discUnitTotal').removeClass('hide');
					newAmt = membUnitPrice * discAmt/100;
					membUpdatePrice = membUnitPrice - newAmt;
					memDiscUnitTotal  =  ((membUpdatePrice * 52)/12)* classLimit;
					$('input[name="discPerAmt"]').val(membUpdatePrice.toFixed(2));
					$('input[name="discUnitTotal"]').val(memDiscUnitTotal.toFixed(2));
	
				}
			}
			
			modal.find('input[name="membTotalPrice"]').val(memAmount.toFixed(2));
			// modal.find('input[name="membUnitPrice"]').val(newUnitAmt.toFixed(2));
		}
	$(".new-row").remove();
	totalMembPrice();
})
/* End: on chnage membership display service */

/* Start: Onchange service change its prices */
$('body').on('change','select.mem_service', function(){
	updateServicePrice($(this));
})
/* End: Onchange service change its prices */


/* Start: Onchange service change its prices */
$('body').on('change','select.mem_type', function(){
	var $this = $(this),
	row = $this.closest('.row'),
	memberVailidityType = $('select#membership option:selected').data('length-unit'),
	unitprice = row.find('.mem_unit_price').val(),
	type = $this.val();
	limit = row.find('.mem_limit').val();
	var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(unitprice), limit);
	row.find('.mem_price').val(parseFloat(cost).toFixed(2));
	row.find('.mem_unit_price').val(parseFloat(unitprice).toFixed(2));
	row.find('.mem_price').data('service-original-total', cost);
	row.find('.mem_unit_price').data('service-unit-total', unitprice);
	totalMembPrice();
})
/* End: Onchange service change its prices */

/* Start: Onchange service limit change its prices */
// $('body').on('change','.mem_limit', function(){
// 	totalMembPrice();
// })
/* End: Onchange service limit change its prices */

/* Start: Onchange service price change its total prices */
$('body').on('blur','.mem_price', function(){
	totalMembPrice();
})
/* End: Onchange service price change its total prices */

/* Start: when deselect selected classes */
selectedData = prepareMultiSel($('select#mem_Classes'));
selectedLength = selectedData.length;

$('select#mem_Classes').bind('change', function(){
	var currSelcVal = prepareMultiSel($(this));
	currSelcLength = currSelcVal.length;

	if(selectedLength > currSelcLength){
		removeClassConfirm(function(confirm){
			if(confirm){
				selectedLength = currSelcLength;
				selectedData = currSelcVal;
			}
			else{
				$('#mem_Classes').val(selectedData);
				$('#mem_Classes').selectpicker('refresh');
			}
		});
	}
	else{
		selectedLength = currSelcLength;
		selectedData = currSelcVal;
	}  
})
/* End: when deselect selected classes */

});

$('#discType').on('change', function(){
	var disPer = $('#discType').val();
	
	$('.discAmt, .disPercent, .discPerAmt').removeClass('hide');
    if(disPer == 'fixed'){
			$('.disPercent').addClass('hide');
			$('.discPerAmt').addClass('hide');
			
		
	}else{
		if(!$('.discUnitTotal').hasClass('hide')){
			$('discUnitTotal').addClass('hide');
		}
		$('#discUnitTotal').val('0');

			$('.discAmt').addClass('hide');
		
	}
	totalMembPrice();
})

$('#discAmountType').on('change',function(){
	var discAmount = $('#discAmountType').val();
	if(discAmount == 'total'){
		if(!$('.discUnitTotal').hasClass('hide')){
			$('discUnitTotal').addClass('hide');
		}
	
		
	}else if(discAmount == 'unit'){
		if($('.discUnitTotal').hasClass('hide')){
			$('discUnitTotal').removeClass('hide');
		}
	}
	$('input[name="discAmt"]').val('');
	$('#discUnitTotal').val('');
	$('#disPercent').val('');
	totalMembPrice();
})

$('body').on('click', '.apply-service-discount', function(e){
	var row = $(this).closest('.row'),
	popoverId = $(this).attr('aria-describedby'),
	currentPopover = $('#'+popoverId),
	discountAmount =  row.find('.mem_price').data('discount-amount'),
	discountPrice =  row.find('.mem_price').data('service-discount-price'),
	discountType = row.find('.mem_price').data('discount-type');
	givenDiscountAmount = row.find('.service-discount-type-data').val();
	if(givenDiscountAmount > 0){
	currentPopover.find('select.discount-type').val(discountType).change();
	currentPopover.find('input[name="disamount"]').val(discountAmount);
	currentPopover.find('input[value="'+discountPrice+'"]').prop('checked', true);
	}else{
	currentPopover.find('select.discount-type').val('').change();
	currentPopover.find('input[name="disamount"]').val('');
	currentPopover.find('input[value="serviceUnitPrice"]').prop('checked', true);
	}


	  $('.popover').removeClass('hide');


});

$('body').on('click', '.service-discount-submit', function(e){
	e.preventDefault();
	var $this = $(this),
	popoverId = $this.closest('.popover').attr('id'), 
	currentPopover = $this.closest('.popover'),
	discount_type = currentPopover.find('select[name="discount_type"]').val(),
	price = currentPopover.find('input[name="disamount"]').val();

	 
	$('.apply-service-discount').each(function(){
	  var ariaDescribed =  $(this).attr('aria-describedby');
	  	if(ariaDescribed != undefined && ariaDescribed == popoverId){
			var row = $(this).closest('.row'),
			totalPrice = row.find('.mem_price').data('service-original-total');
			unitPrice = row.find('select.mem_service option:selected').data('price');
            memberVailidityType = $('select#membership option:selected').data('length-unit'),
			type = row.find('select.mem_type').val();
			limit =row.find('.mem_limit').val();
			var serviceFixed = row.find('.service-discount-type-data').closest('.form-group');
			var servicePercent = row.find('.serviceDiscountPerAmount').closest('.form-group');

	
			
			priceType = currentPopover.find('input[name="price"]:checked').val();
			if(discount_type == 'fixed'){
				servicePercent.find('.servicePercentFinal').empty();
				if(priceType == 'serviceUnitPrice'){
					var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(price), limit);
					// row.find('.mem_unit_price').val(disPrice.toFixed(2));
					row.find('.mem_price').val(parseFloat(cost).toFixed(2));
					serviceFixed.find('.serviceFinalDiscount').empty().append('<h5>Unit price after discount</h5>');

				}
				else{
					disPrice = price;
					var cost = calculateUnitPrice(memberVailidityType, type, parseFloat(disPrice), limit);

					// row.find('.mem_price').val(disPrice.toFixed(2));
					serviceFixed.find('.serviceFinalDiscount').empty().append('<h5>Total price after discount</h5>');
					row.find('.mem_unit_price').val(parseFloat(cost).toFixed(2));
				}
				row.find('.mem_price').data('discount-type',discount_type);
				row.find('.mem_price').data('discount-amount',price);
				row.find('.mem_price').data('service-discount-price',priceType);
				row.find('.service-discount-type-data').val(parseFloat(price).toFixed(2));
				
				var disType= row.find('.serviceDiscountName');
				if(disType.hasClass('hide')){
					row.find('.serviceDiscountName').removeClass('hide');
				}
				row.find('.serviceDiscountName').text('$');
			}else{
				serviceFixed.find('.serviceFinalDiscount').empty();
				if(priceType == 'serviceUnitPrice'){
					servicePercent.find('.servicePercentFinal').empty().append('<h5>Total price after discount</h5>');
					row.find('.serviceDiscountPerAmount').removeClass('hide');
					disPerPrice = unitPrice * price/100 ;
					disPrice = unitPrice - disPerPrice;
					var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(disPrice), limit);
					// row.find('.mem_unit_price').val(parseFloat(disPrice).toFixed(2));
					row.find('input[name="serviceDiscountPerData"]').val(disPrice.toFixed(2));
					row.find('.mem_price').val(parseFloat(cost).toFixed(2));
				}else{
					servicePercent.find('.servicePercentFinal').empty().append('<h5>Total price after discount</h5>');
					row.find('.serviceDiscountPerAmount').removeClass('hide');
					disPerPrice = totalPrice * price/100 ;
					disPrice = totalPrice - disPerPrice;
					// var cost = calculateUnitPrice(memberVailidityType, type, parseFloat(disPrice), limit);
					row.find('input[name="serviceDiscountPerData"]').val(disPrice.toFixed(2));
				}
				row.find('.mem_price').data('discount-type',discount_type);
				row.find('.mem_price').data('discount-amount',price);
				row.find('.mem_price').data('service-discount-price',priceType);
				row.find('.service-discount-type-data').val(parseFloat(price).toFixed(2));
				var disType= row.find('.serviceDiscountName');
				if(disType.hasClass('hide')){
					row.find('.serviceDiscountName').removeClass('hide');
				}
				row.find('.serviceDiscountName').text('%').trigger('change');
				
			}

		}	
	});
	currentPopover.popover('hide');
    totalMembPrice();

});

/* Start: Create new row for session */
function createNewRowSession(serviceId, data, sessionOption, membValidityType){
	var modal = $('#editMembSub'),
	fieldset = modal.find('#session-group'),
	newRow = fieldset.children().last().clone();

	rowCount++;
	newRow.removeClass('hidden');
	newRow.addClass('new-row');
	var ddGroup = newRow.find('.sessionGroup');
	var sessionField = ddGroup.find('select.mem_session');
	ddGroup.empty();
	if(typeof sessionOption != 'undefined' && sessionOption !=''){
		sessionField.empty();
		sessionField.append(sessionOption);
	}
	ddGroup.append(sessionField);
	// ddGroup.find('select.mem_session').val('').selectpicker('refresh');

	ddGroup.find('select.mem_session').val(serviceId).attr('name','mem_session'+rowCount).prop('required',true).selectpicker('refresh');

	newRow.find('.session_mem_limit').val(data.limit).attr('name', 'session_mem_limit'+rowCount).prop('required',true);

	var price = parseFloat(ddGroup.find('select.mem_session option:selected').data('price'));
	var editedPrice = parseFloat(ddGroup.find('select.mem_session option:selected').data('editedprice'));
    var limit = newRow.find('.session_mem_limit').val(); 
	if(isNaN(price))
		price = 0;

	if(isNaN(editedPrice) || editedPrice == 0)
		price = servicePriceAccOfType(membValidityType, data.limit_type, price, limit);
	else
		price = editedPrice.toFixed(2);
	newRow.find('.session_mem_price').data('row-count',rowCount);	
	newRow.find('.session_mem_price').val(price).attr('name', 'session_mem_price'+rowCount).prop('required',true);
	newRow.find('.session_mem_unit_price').val(data.mem_unit_price == undefined ? '0' : parseFloat(data.mem_unit_price).toFixed(2)).attr('name', 'session_mem_unit_price'+rowCount).prop('required',true);
	
	newRow.find('.session_mem_unit_price').data('session-original-unit',data.mem_unit_price);
	newRow.find('.session_mem_price').data('session-original-total',price);
	newRow.find('.session_mem_price').data('session-discount-type',data.discount_type);
	newRow.find('.session_mem_price').data('session-discount-amount',data.discount_amount);
	newRow.find('.session_mem_price').data('session-discount-price',data.discount_price_type);
	newRow.find('.discount-type-data').val(data.discount_amount);
	newRow.find('.session-discount-per-data').val(data.sessionDiscountPerData);
	newRow.find('.discountName').addClass('hide');
	if(data.discount_type == 'fixed'){
	newRow.find('.discountName').text('$');
	newRow.find('.discountName').removeClass('hide');
	}
	if(data.discount_type == 'percent'){
		newRow.find('.discountName').text('%');
		newRow.find('.discountName').removeClass('hide');
	}
	if((data.discount_price_type == 'sessionUnitPrice' || data.discount_price_type == 'session_price') && data.discount_type == 'percent'){
        newRow.find('.sessionDiscountPerAmount').removeClass('hide');
	}

	if(data.discount_price_type == 'sessionUnitPrice'){
		if(data.discount_type == 'fixed'){
			var service = newRow.find('.discount-type-data').closest('.form-group');
			service.find('.sessionFinalDiscount').empty().append('<h5>Unit price after discount</h5>');
		}else{
			var service = newRow.find('.sessionDiscountPerAmount').closest('.form-group');          service.find('.sessionPercentFinal').empty().append('<h5>Unit price after discount</h5>');
		}
	}else if(data.discount_price_type == 'session_price'){
		if(data.discount_type == 'fixed'){
			var service = newRow.find('.discount-type-data').closest('.form-group');
			service.find('.sessionFinalDiscount').empty().append('<h5>Total price after discount</h5>');
		}else{
			var service = newRow.find('.sessionDiscountPerAmount').closest('.form-group');
          service.find('.sessionPercentFinal').empty().append('<h5>Total price after discount</h5>');
		}
	}else{
		var service = newRow.find('.discount-type-data').closest('.form-group');
			service.find('.sessionFinalDiscount').empty();
			var service = newRow.find('.sessionDiscountPerAmount').closest('.form-group');          service.find('.sessionPercentFinal').empty();
	}

	var ddGroup2 = newRow.find('.limitTypeGroup');
	var dd2 = ddGroup2.find('select.session_mem_type');
	ddGroup2.empty();
	ddGroup2.append(dd2);
	ddGroup2.find('select.session_mem_type').val(data.limit_type).attr('name','session_mem_type'+rowCount).prop('required',true).selectpicker('refresh');

	fieldset.append(newRow);
	totalMembPrice();
	var discountPopoverOpt = {
		html: true,
		content: "<div class='row popoverContent' data-action='apply_coupon'><div class='col-md-12'><select class='form-control discount-type' name='discount_type'><option value=''>Choose discount</option><option value='fixed'>Fixed</option><option value='percent'>Percent</option></select></div><div class='col-md-12' style='margin-top:10px; margin-bottom:10px;'><div class='form-group m-b-0 priceOption' style='margin-bottom: 0px'><div class='radio clip-radio radio-primary radio-inline m-b-0'><input type='radio' id='sessionUnitPrice' name='price' value='sessionUnitPrice' checked><label for='sessionUnitPrice'>Unit Price</label></div><div class='radio clip-radio radio-primary radio-inline m-b-0'><input type='radio' name='price' id='sessionTotalPrice' value='session_price'><label for='sessionTotalPrice'>Total Price</label></div><span class='help-block' style='margin-bottom: 0px'></span></div><input type='number' name='disamount' class= 'amount hide'><input type ='hidden' name='row_num' class='discount-row-num'/><input type ='hidden' name='totalAmount' class='discount-total-amount'/></div></div><div class='clearfix'><div class ='row'><div class ='col-md-6'><button class='btn btn-primary closePopup' type='button'> Cancel </button></div><div class ='col-md-3'><button class='btn btn-primary session-discount-submit' type='button' style ='width:80px;white-space:normal;'> Apply</button></div></div></div>",
		container: $('#editMembSub'),
		title: "<strong>Apply a discount</strong>",
		placement: 'top',
		trigger: 'click'
	};
	
	modal.find('.apply-session-discount').each(function(){
		$(this).popover(discountPopoverOpt);
	});
	
}
/* End: Create new row for session */

/* Start: get classes which link to memebership */
function getSessionOfClient(membershipId){
	var sessionGroup = $('#session-group'),
	clientId = $('#editMembSub').find('input[name="clientId"]').val();
	if(typeof membershipId != 'undefined' && membershipId){
		sessionGroup.removeClass('hidden');
		$.get(public_url+'settings/business/classes/all-class-type', {membershipId:membershipId, clientId:clientId}, function(response){
			resetSessionRow($('#editMembSub'));
			displaySessionRow(response);
		},'JSON');
	}
	else{
		sessionGroup.addClass('hidden');
	}
}
/* Start: get classes which link to memebership */



/* Start: reset row of membership class */
function resetSessionRow(modal){
	var fieldset = modal.find('#session-group');
	fieldset.find('.new-row').remove();
}
/* End: reset row of membership class */

/* Start: Display class according to memebership */
function displaySessionRow(response){
	var memberVailidityType = $('select#membership option:selected').data('length-unit');
	var session = response.session;
	sessionOption ='';
	sessionData = response.sessionData;
	if(Object.keys(session).length > 0){
		$.each(session, function(key, value){
			sessionOption += `<option value='${value.id}' data-price='${value.price}' data-editedprice='${value.editedPrice}'>${value.name}</option>`;
		});
	}
	if(Object.keys(sessionData).length > 0){
		$.each(sessionData, function(key, value){
			createNewRowSession(key, value, sessionOption, memberVailidityType);
		});
	}
		
	
}
/* End: Display class according to memebership */

/* Start: Onchange session change its prices */
$('body').on('change','select.mem_session', function(){
	updateSessionPrice($(this));
})
/* End: Onchange session change its prices */

/* Start: update session price */
function updateSessionPrice($this){
	var row =  $this.closest('.row'), 
	memberVailidityType = $('select#membership option:selected').data('length-unit'),
	price = parseFloat(row.find('select.mem_session option:selected').data('price')),
	type = row.find('select.session_mem_type').val();
	limit = row.find('.session_mem_limit').val();
	if(isNaN(price) /*|| isNaN(unit)*/)
		price = 0;

	var cost = servicePriceAccOfType(memberVailidityType, type, price, limit);
	row.find('.session_mem_price').val(parseFloat(cost).toFixed(2));
	row.find('.session_mem_unit_price').val(parseFloat(price).toFixed(2));
	row.find('.session_mem_price').data('session-original-total', cost);
	row.find('.session_mem_unit_price').data('session-original-unit', price);
	totalMembPrice();
}
/* End: update session price */

/* Start: Onchange service change its prices */
$('body').on('change','select.session_mem_type', function(){
	var $this = $(this),
	row = $this.closest('.row'),
	memberVailidityType = $('select#membership option:selected').data('length-unit'),
	unitprice = row.find('.session_mem_unit_price').val(),
	type = $this.val();
	limit = row.find('.session_mem_limit').val();

	var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(unitprice), limit);
	row.find('.session_mem_price').val(parseFloat(cost).toFixed(2));
	row.find('.session_mem_unit_price').val(parseFloat(unitprice).toFixed(2));
	row.find('.session_mem_price').data('session-original-total', cost);
	row.find('.session_mem_unit_price').data('session-original-unit', unitprice);
	totalMembPrice();
})
/* End: Onchange service change its prices */

$('body').on('click', '.apply-session-discount', function(e){
	var row = $(this).closest('.row'),
	popoverId = $(this).attr('aria-describedby'),
	currentPopover = $('#'+popoverId),
	discountAmount =  row.find('.session_mem_price').data('session-discount-amount'),
	discountType = row.find('.session_mem_price').data('session-discount-type');
	discountPrice = row.find('.session_mem_price').data('session-discount-price');
	givenDiscountAmount = row.find('.discount-type-data').val();
	if(givenDiscountAmount > 0){

	currentPopover.find('select.discount-type').val(discountType).change();
	currentPopover.find('input[value="'+discountPrice+'"]').prop('checked', true);
	currentPopover.find('input[name="disamount"]').val(discountAmount);
	}else{
	currentPopover.find('select.discount-type').val('').change();
	currentPopover.find('input[value="sessionUnitPrice"]').prop('checked', true);
	currentPopover.find('input[name="disamount"]').val('');
	}

	  $('.popover').removeClass('hide');


});

$('body').on('click', '.session-discount-submit', function(e){
	e.preventDefault();
	var $this = $(this),
	popoverId = $this.closest('.popover').attr('id'), 
	currentPopover = $this.closest('.popover'),
	discount_type = currentPopover.find('select[name="discount_type"]').val(),
	price = currentPopover.find('input[name="disamount"]').val();

	 
	$('.apply-session-discount').each(function(){
	  var ariaDescribed =  $(this).attr('aria-describedby');
	  	if(ariaDescribed != undefined && ariaDescribed == popoverId){
			var row = $(this).closest('.row'),
			totalPrice = row.find('.session_mem_price').data('session-original-total');
			unitPrice = row.find('select.mem_session option:selected').data('price');
			priceType = currentPopover.find('input[name="price"]:checked').val();
			
			memberVailidityType = $('select#membership option:selected').data('length-unit'),
			type = row.find('select.session_mem_type').val();
			limit =row.find('.session_mem_limit').val();
			var serviceFixed = row.find('.discount-type-data').closest('.form-group');
			var servicePercent = row.find('.sessionDiscountPerAmount').closest('.form-group');

			if(discount_type == 'fixed'){
				servicePercent.find('.sessionPercentFinal').empty();
				if(priceType == 'sessionUnitPrice'){
				
					var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(price), limit);
					// row.find('.session_mem_unit_price').val(disPrice.toFixed(2));
					row.find('.session_mem_price').val(cost);
					serviceFixed.find('.sessionFinalDiscount').empty().append('<h5>Unit price after discount</h5>');

				}
				else{
				disPrice = price;
				serviceFixed.find('.sessionFinalDiscount').empty().append('<h5>Total price after discount</h5>');

				var cost = calculateUnitPrice(memberVailidityType, type, parseFloat(disPrice), limit);
				// row.find('.session_mem_price').val(disPrice.toFixed(2));
				row.find('.session_mem_unit_price').val(cost);
				}
				row.find('.session_mem_price').data('session-discount-type',discount_type);
				row.find('.session_mem_price').data('session-discount-amount',price);
				row.find('.session_mem_price').data('session-discount-price',priceType);
				row.find('.discount-type-data').val(price);
				var disType= row.find('.discountName');
				if(disType.hasClass('hide')){
					row.find('.discountName').removeClass('hide');
				}
				row.find('.discountName').text('$').trigger('change');
			}else{

				serviceFixed.find('.sessionFinalDiscount').empty();
				if(priceType == 'sessionUnitPrice'){
					servicePercent.find('.sessionPercentFinal').empty().append('<h5>Unit price after discount</h5>');

					row.find('.sessionDiscountPerAmount').removeClass('hide');
					disPerPrice = unitPrice * price/100 ;
					disPrice = unitPrice - disPerPrice;
					var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(disPrice), limit);
					// row.find('.session_mem_unit_price').val(disPrice.toFixed(2));
					row.find('.session_mem_price').val(parseFloat(cost).toFixed(2));
					row.find('input[name="sessionDiscountPerData"]').val(disPrice.toFixed(2));

				}else{
					servicePercent.find('.sessionPercentFinal').empty().append('<h5>Total price after discount</h5>');
					row.find('.sessionDiscountPerAmount').removeClass('hide');
					disPerPrice = totalPrice * price/100 ;
					disPrice = totalPrice - disPerPrice;
					// var cost = calculateUnitPrice(memberVailidityType, type, parseFloat(disPrice), limit);
					// row.find('.session_mem_price').val(disPrice.toFixed(2));
					row.find('input[name="sessionDiscountPerData"]').val(disPrice.toFixed(2));
				}
				row.find('.session_mem_price').data('session-discount-type',discount_type);
				row.find('.session_mem_price').data('session-discount-amount',price);
				row.find('.session_mem_price').data('session-discount-price',priceType);
				row.find('.discount-type-data').val(price);
				var disType= row.find('.discountName');
				if(disType.hasClass('hide')){
					row.find('.discountName').removeClass('hide');
				}
				row.find('.discountName').text('%').trigger('change');
			}

		}	
	});
	currentPopover.popover('hide');
	totalMembPrice();


});

$('#disPercent, #discAmt').on('change',function(){
	var membTotalPrice = $('select#membership option:selected').data('instamount'),
	membUnitPrice = $('select#membership option:selected').data('membership-unit-price'),
	classLimit = $('select#membership option:selected').data('class-limit');
	var disAmntType = $('#discAmountType').val();

	if(membTotalPrice != undefined && membTotalPrice != ''){

	    disPer = $('#discType').val();
		
		if(disPer == 'fixed'){
			discAmt = $('#discAmt').val();
			if(disAmntType == 'total'){
				if(!$('.discUnitTotal').hasClass('hide')){
					$('.discUnitTotal').addClass('hide');
				}
				membUpdatePrice =  discAmt;
				membUpdateUnitPrice = ((membUpdatePrice * 12)/52)/classLimit;
				$('#membUnitPrice').val(membUpdateUnitPrice.toFixed(2));
				$('input[name="discUnitTotal"]').val('0');
			}else if(disAmntType == 'unit'){
				$('.discUnitTotal').removeClass('hide');
				memDiscUnitTotal  =  ((discAmt * 52)/12)* classLimit;
				$('input[name="discUnitTotal"]').val(memDiscUnitTotal.toFixed(2));
				
			}
		
	    }else{
			discAmt = $('#disPercent').val();
			if(disAmntType == 'total'){
				if(!$('.discUnitTotal').hasClass('hide')){
					$('.discUnitTotal').addClass('hide');
				}
				newAmt = membTotalPrice * discAmt/100;
				membUpdatePrice = membTotalPrice - newAmt;
				membUpdateUnitPrice = ((membUpdatePrice * 12)/52)/classLimit;
				$('input[name="discPerAmt"]').val(membUpdatePrice.toFixed(2));
				$('#membUnitPrice').val(membUpdateUnitPrice.toFixed(2));
				$('input[name="discUnitTotal"]').val('0');
			}else if(disAmntType == 'unit'){
				$('.discUnitTotal').removeClass('hide');
				newAmt = membUnitPrice * discAmt/100;
				membUpdatePrice = membUnitPrice - newAmt;
				memDiscUnitTotal  =  ((membUpdatePrice * 52)/12)* classLimit;
				$('input[name="discPerAmt"]').val(membUpdatePrice.toFixed(2));
				$('input[name="discUnitTotal"]').val(memDiscUnitTotal.toFixed(2));

			}
		}
		
		
		
		totalMembPrice();
	}
})

$('body').on('input','.mem_limit', function(){
	var $this = $(this),
	row = $this.closest('.row'),
	memberVailidityType = $('select#membership option:selected').data('length-unit'),
	unitprice = row.find('.mem_unit_price').val(),
	type = row.find('select.mem_type').val();
	limit = $this.val();
	var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(unitprice), limit);
	row.find('.mem_price').val(parseFloat(cost).toFixed(2));
	row.find('.mem_unit_price').val(parseFloat(unitprice).toFixed(2));
	row.find('.mem_price').data('service-original-total', cost);
	row.find('.mem_unit_price').data('service-unit-total', unitprice);
	totalMembPrice();
});

$('body').on('input','.session_mem_limit', function(){
	var $this = $(this),
	row = $this.closest('.row'),
	memberVailidityType = $('select#membership option:selected').data('length-unit'),
	unitprice = row.find('.session_mem_unit_price').val(),
	type = row.find('select.session_mem_type').val();
	limit = $this.val();

	var cost = servicePriceAccOfType(memberVailidityType, type, parseFloat(unitprice), limit);
	row.find('.session_mem_price').val(parseFloat(cost).toFixed(2));
	row.find('.session_mem_unit_price').val(parseFloat(unitprice).toFixed(2));
	row.find('.session_mem_price').data('session-original-total', cost);
	row.find('.session_mem_unit_price').data('session-original-unit', unitprice);
	totalMembPrice();
})

$('select[name="payPlan"]').change(function(){
	var totalPrice = $('input[name="grandtotalprice"]').val();
	var payPlanType = $('select[name="payPlan"] option:selected').val();
	if(payPlanType == 'week'){
      payAmount = totalPrice * 12/52;
	}else if(payPlanType == 'fortnight'){
	  payAmount = totalPrice * 12/26;  
	}else if(payPlanType == 'month'){
		payAmount = totalPrice; 
	}else if(payPlanType == '3month'){
		payAmount = totalPrice * 3; 
	}else if(payPlanType == '6month'){
		payAmount = totalPrice * 6; 
	}else if(payPlanType == 'year'){
		payAmount = totalPrice * 12; 
	}
	$('#paymentAmount').val(parseFloat(payAmount).toFixed(2));

});

function calculateUnitPrice(membValidityType, limitType, cost, limit){
	if(membValidityType == 'day'){
		switch(limitType){ 
			case 'every_month':
			cost = (cost * 365) / 12;
			break;
			case 'every_fortnight':
			cost = (cost * 365) / 24.5;
			break;
			case 'every_week':
			cost = (cost * 7);
		}
	}
	else if(membValidityType == 'week'){
		switch(limitType){ 
			case 'every_month':
			cost = cost * 52/12;
			break;
			case 'every_fortnight':
			cost = cost * 2;
			break;
			case 'every_week':
			cost = cost;
		}
	}
	else if(membValidityType == 'month'){
		switch(limitType){ 
			case 'every_month':
			cost = cost;
			break;
			case 'every_fortnight':
			cost = cost / 2;
			break;
			case 'every_week':
			cost = cost * 12/52;
		}
	}
	else if(membValidityType == 'year'){
		switch(limitType){ 
			case 'every_month':
			cost = cost / 12;
			break;
			case 'every_fortnight':
			cost = cost / 24.5;
			break;
			case 'every_week':
			cost = cost / 52;
		}
	}
	cost = cost / limit;
	return cost.toFixed(2);
}

$('body').on('click','.deleteDiscountSession',function(){
	updateSessionPrice($(this));
	var row =  $(this).closest('.row');
	row.find('input[name="discountTypeData"]').val(0);
	row.find('input[name="sessionDiscountPerData"]').val(0);
	row.find('.sessionDiscountPerAmount').addClass('hide');
	totalMembPrice();
	
})

$('body').on('click','.deleteDiscountService',function(){
	updateServicePrice($(this));
	var row =  $(this).closest('.row');
	row.find('input[name="serviceDiscountTypeData"]').val(0);
	row.find('input[name="serviceDiscountPerData"]').val(0);
	row.find('.serviceDiscountPerAmount').addClass('hide');
	totalMembPrice();
	
})

	
$('body').on('change','.session_mem_limit', function(){
	var $this = $(this),
	row = $this.closest('.row'),
	classTypeId = row.find('select.mem_session option:selected').val();
	 limit = $this.val();
	 clientId = $('#editMembSub').find('input[name="clientId"]').val();
	 type = row.find('select.session_mem_type').val();
	 var  sessionType = 'class';
	 var futureRecurSessions = {};
	$.ajax({
		url : public_url+'client/future-recure-sessions',
		type : 'GET',
		data : { limit:limit, clientId:clientId,classTypeId:classTypeId,type:type,sessionType:sessionType},
		async: false,
		success : function(response) {
			if(response.status == true){
			futureRecurSessions = response.futureRecureSessions;
			$('#recurSessionDeleteModal').find('input[name="sessionType"]').val(response.type);
			var html = '';
			$.each(futureRecurSessions, function(key, value){
				html += ` <li>
				<div class="fc-event-container">
					<a class="calendEventWrap" id="sessions">
						<div class="fc-content tooltipstered calendEvent"> <input id="${value.sec_secr_id}" name="class-${value.sec_secr_id}" type="checkbox" value="${value.sec_secr_id}"> <div class="eventStatusIcon">
						</div>
						<strong>
							<span class="eventTimeRange">
								${value.sec_start_datetime}
							</span>
							
						</strong>
						<br>
								${value.clas_with_trashed.cl_name}
									</br>
								</label>
							</input>
						</div>
					</a>
				</div></li>`;
			});
			$('#recurSessionDeleteModal').find('.sessionExtra').empty().append(html);
			$('#recurSessionDeleteModal').modal('show');
		}
	}
});


});

$('#recurSessionDeleteSubmit').click(function(){
	var recureDeleteClassProRate = [];
	var recureDeleteClassNextCycle = [];
	var type = $('#recurSessionDeleteModal').find('input[name="sessionType"]').val();
	var ServiceDay = [];
	upadteOption = $('#updateClientMemb input[name="updateOpt"]:checked').val() == undefined ? '1' :$('#updateClientMemb input[name="updateOpt"]:checked').val();

	/* For membership update with pro rate */
	$('#recurSessionDeleteFormProRate').find('input').each(function(index){
		if($(this).is(":checked")){
			if(type == 'service'){
				ServiceDay.push($(this).data('day'));
			}
			if(upadteOption == 1){
			recureDeleteClassProRate.push( $(this).val());
			}else{
				recureDeleteClassNextCycle.push( $(this).val());
			}
		}
	});
	 newSubmitClientMemb('openClientAccStatusPopup', recureDeleteClassProRate, recureDeleteClassNextCycle, type, ServiceDay);

	$('#recurSessionDeleteModal').modal('hide');
});

$('body').on('change','.mem_limit', function(){
	var $this = $(this),
	row = $this.closest('.row'),
	type = row.find('select.mem_type').val();
	limit = $this.val();
	classTypeId = row.find('select.mem_service option:selected').val();
	clientId = $('#editMembSub').find('input[name="clientId"]').val();
	var futureRecurSessions = {};
	var  sessionType = 'service';
	$.ajax({
		url : public_url+'client/future-recure-sessions',
		type : 'GET',
		data : { limit:limit, clientId:clientId,classTypeId:classTypeId,type:type,sessionType:sessionType},
		async: false,
		success : function(response) {
			if(response.status == true){
			futureRecurSessions = response.futureRecureSessions;
			$('#recurSessionDeleteModal').find('input[name="sessionType"]').val(response.type);
			var html = '';
			$.each(futureRecurSessions, function(key, value){
				dateDay =moment(value.sess_start_datetime,'YYYY-MM-DD H:mm:ss').format("ddd");
				html += ` <li>
				<div class="fc-event-container">
					<a class="calendEventWrap" id="sessions">
						<div class="fc-content tooltipstered calendEvent"> <input id="${value.sess_service_id}" name="class-${value.sess_service_id}" type="checkbox" value="${value.sess_service_id}" data-day="${dateDay}"> <div class="eventStatusIcon">
						</div>
						<strong>
							<span class="eventTimeRange">
								${value.sess_start_datetime}
							</span>
							
						</strong>
						<br>
								${value.service.category == 1 ? value.service.team_name : value.service.one_on_one_name  }
									</br>
								</label>
							</input>
						</div>
					</a>
				</div></li>`;
			});
			$('#recurSessionDeleteModal').find('.sessionExtra').empty().append(html);
			$('#recurSessionDeleteModal').modal('show');
		}
	}
});

});

