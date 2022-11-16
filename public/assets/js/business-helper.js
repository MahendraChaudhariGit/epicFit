var/* public_url = $('meta[name="public_url"]').attr('content'),*/
	formRowsCreated = 0;


$(document).ready(function(){
	$(document).ready(function() {
		//$('.datepicker').datepicker({autoclose:true, format:"D, d M yyyy"});
		$('.datepicker').datepicker({autoclose:true, dateFormat:"D, d M yy"});
	});

	
	/*var customValRadField = $('.customValRadField');
	if(customValRadField.length){
		$(document).on("change", ".customValRadField", function(){
			var formGroup = $(this).closest('.form-group');
			setFieldValid(formGroup, formGroup.find("span.help-block"))
		})
	}*/

	$(document).on("countrychange", ".countryCode", function(){
		initTelInputOnChange($(this))
    	/*var formGroup = $(this).closest('.form-group');
		setFieldValid(formGroup, formGroup.find("span.help-block"));*/
	});

	$('body').on('change', 'select[name!="client-datatable_length"]', function(){
		var self = $(this);
		var value = self.val();
		if(value != "" && value != 'undefined') {
			self.closest('.form-group').removeClass('has-error').addClass('has-success').find('.help-block').remove();
		}
	})

	$('body').on('click', '.btn-add-new-time', function(e){
		e.preventDefault();
		formRowsCreated++;

		var self = $(this),
			parCont = self.closest('.form-group').find('.clearfix'),
			timeRow = getRow(),
			day = self.data('dayname');

		var startTimeField = timeRow.find('input.start-day');
		startTimeField.attr('name', day+'_start'+formRowsCreated);
		workingHoursTimePicker(startTimeField);

		var endTimeField = timeRow.find('input.end-day');
		endTimeField.attr('name', day+'_end'+formRowsCreated);
		workingHoursTimePicker(endTimeField);
		
		addNewTimeHelper(timeRow, parCont);
	});

	$('body').on('click', '.btn-add-new-time-cancel', function(e){
		var formGroup = $(this).closest('.form-group');
		var currentRow = $(this).closest('.row');
		$(this).closest('.copy-row').remove();
		if(currentRow.children().length < 1){
			formGroup.find('.showHours').prop('checked', false);
			toggleHours(formGroup.find('.showHours'));
		}
	});

	$('body').on('change', '.showHours', function(e){
		toggleHours($(this));
	});
	//$('input.showHours').trigger('change');
	var editCheck = $('.workingHrs'); 
	$('.showHours').each(function(){
		if(editCheck.length > 0)
			$(this).prop('checked', false);
		else
			$(this).prop('checked', true);
		toggleHours($(this));
	})
	

	$( ".selectable_fixed_location" ).selectable({
		stop: function() {
			var selectedVal = $(".ui-selected", this).text().trim();
			var form = $(this).closest('form');
			$(this).next('.form-group').find('input').val(selectedVal);
			var notForMoblocFields = $(form).find('.notForMobloc');
			if(selectedVal == 'Fixed Location')
				$(notForMoblocFields).show(); 
			else
				$(notForMoblocFields).hide();
		}
	});

	$('.countries').on('changed.bs.select', function(e){
		updateState($(this));
	});

	populateHoursEditMode();

	/* start: add service row */
	$('body').on('click', '.addMoreAttendance', function(e){
		e.preventDefault();
		formRowsCreated++;
         var perSessionRateType=$('#per_session_rate_options').val();

		var $this = $(this),
			serviceRow = $this.closest('.row').clone()
			parCont = $this.closest('.form-group'),
			serviceAttendees = serviceRow.find('input.sessionattendance'),
			serviceAttendanceTo = serviceRow.find('input.sessionattendanceto'),
			servicePrice = serviceRow.find('input.sessionPrice');
			//datetimepicker = serviceRow.find('.datetimepicker');
	
		serviceRow.find('.addMoreAttendance').parent().html('<a class="btn btn-primary removeAttendance" href="#"><i class="fa fa-remove"></i></a>');
          
          if(perSessionRateType == 'base_rate_attendees'){
		        serviceAttendees.prop('name', 'per_session_attendees'+formRowsCreated);
				serviceAttendanceTo.prop('name', 'per_session_attendeeto'+formRowsCreated);
				servicePrice.prop('name', 'per_session_price'+formRowsCreated);
	      }else{
		      	serviceAttendees.prop('name', 'per_session_tier'+formRowsCreated);
				serviceAttendanceTo.prop('name', 'per_session_tierto'+formRowsCreated);
				servicePrice.prop('name', 'per_session_tierprice'+formRowsCreated);
	      }
	      serviceAttendees.val('');
	      serviceAttendanceTo.val('');
	      servicePrice.val('');

		//serviceRow.find('input[type="hidden"]').prop('name', 'attendanceIsDeleted'+formRowsCreated).val('false');

		

		parCont.append(serviceRow);
	});
	/* end: add service row */

	/* start: remove service row */
	$('body').on('click', '.removeAttendance', function(e){
		e.preventDefault();
		var $this = $(this);
		$this.closest('.row').remove();
	});


});	

/*function setFieldValid($formGroup, $helpBlock){
	if($formGroup.hasClass('has-error'))
		$formGroup.removeClass('has-error')
	$formGroup.addClass('has-success');
	if($helpBlock != null)
		$helpBlock.hide();
}*/


function addNewTimeHelper(timeRow, parCont){
	timeRow.addClass('remove-hidden');
	/*timeRow = timeRow.append('<div class="col-xs-3"><button class="btn btn-xs btn-danger btn-add-new-time-cancel" type="button">Remove</button></div>');*/
	parCont.append(timeRow);

	/*var startTimeField = timeRow.find("input[type='text']:first");
	workingHoursTimePicker(startTimeField);

	var endTimeField = timeRow.find("input[type='text']:not(:first)");
	workingHoursTimePicker(endTimeField);*/
	/*$('.datetimepicker').datetimepicker({
		format: 'LT',
		stepping: 5
	}); */
}

function toggleHours(self){
	var formGroup = self.closest('.form-group');
	var parCont = formGroup.find('.clearfix');
	var notWork = formGroup.find('.notWork')
	var showHoursElem = formGroup.find('.showHoursElem')
	if(self.is(':checked')){
		notWork.hide();
		showHoursElem.removeClass('hidden');
	}
	else{
		showHoursElem.addClass('hidden');
		notWork.show();
	}
}

function populateHoursEditMode(){
	$('.workingHrs').each(function(){
		var $this = $(this),
			oldHours = $this.data('old-hours');
		$this.find('input.showHours').each(function(){
			hourReset($(this), 'Area');	
		});
		if(oldHours){
			var daysCovered = [];
			$.each(oldHours, function(key, value){
				var day = (value.hr_day).toLowerCase(),
					checkbox = $this.find('input[name="'+day+'"]');
					/*timeRow = getRow();*/

				if($.inArray(day, daysCovered) == -1){
					daysCovered.push(day);
					
					checkbox.prop('checked', true);
					toggleHours(checkbox);

					timeRow = checkbox.closest('.form-group').find('.clearfix');

					setDbTimeToTimeString(timeRow.find('input.start-day'), value.hr_start_time)
					setDbTimeToTimeString(timeRow.find('input.end-day'), value.hr_end_time)
				}
				else{
					formRowsCreated++;

					var parCont = checkbox.closest('.form-group').find('.clearfix'),
						timeRow = getRow()/*timeRow.clone()*/,
						startTimeField = timeRow.find('input.start-day'),
						endTimeField = timeRow.find('input.end-day');

					setDbTimeToTimeString(startTimeField, value.hr_start_time)
					setDbTimeToTimeString(endTimeField, value.hr_end_time)
					startTimeField.attr('name', day+'_start'+formRowsCreated);
					endTimeField.attr('name', day+'_end'+formRowsCreated);
					
					addNewTimeHelper(timeRow, parCont);
				}
			});
		}
	});
}

function hourReset(self, section){
	var parCont = self.closest('.clearfix');
	parCont.find('.row:not(:first)').remove();
	
	var checkbox = $(parCont).find('input[type="checkbox"]');
	var day = checkbox.attr('name');
	if(section == null || section == 'Location'){
		if(day == 'sunday' || day == 'saturday')
			hourResetHelper(checkbox, self);
		else{
			if(!checkbox.is(':checked')){
				checkbox.prop('checked', true);
				toggleHours(self);
			}
		}
	}
	else
		hourResetHelper(checkbox, self);

	hourResetTime(parCont);
}
function hourResetHelper(checkbox, self){
	if(checkbox.is(':checked')){
		checkbox.prop('checked', false);
		toggleHours(self);
	}
}
function hourResetTime($container){
	/*var startTimeField = $container.find("input[type='text']:first");
	startTimeField.val('6:00 AM').data('default-time', '6:00 AM');
	workingHoursTimePicker(startTimeField);

	var endTimeField = $container.find("input[type='text']:not(:first)");
	endTimeField.val('7:00 PM').data('default-time', '7:00 PM');
	workingHoursTimePicker(endTimeField);*/
	setFieldForTimepicker($container.find("input[type='text']:first"), '6:00 AM')
	setFieldForTimepicker($container.find("input[type='text']:not(:first)"), '7:00 PM')
}

//function setDbTimeToTimeString(field, dbTime){
	/*var time = moment(dbTime, 'HH:mm:ss').format("h:mm A");
	field.val(time).data('default-time', time);
	workingHoursTimePicker(field)*/
	//field.val(moment(dbTime, 'HH:mm:ss').format("h:mm A"));
	/*setFieldForTimepicker(field, moment(dbTime, 'HH:mm:ss').format("h:mm A"))
}*/

/*function scrollToTop(elem){
	$("body, html").animate({ 
		scrollTop:$(elem).offset().top -70 
	}, 100);
}*/

/*function prepareMultiSel($elem){
	var val = $elem.val();
	if(val != null)
		return val;
	return '';
}*/

function validateUrl ($urlField) {
	var urlVal = $.trim($urlField.val());
	/*var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
		  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
		  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
		  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
		  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
		  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator*/
	var pattern = /[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/;
	  if(urlVal != null && urlVal != '' && !pattern.test(urlVal)) {
		//setFieldInvalid($urlField.closest('.form-group'), $urlField.next("span"), 'Please enter a valid URL.')
		setFieldInvalid($urlField.closest('.form-group'), 'Please enter a valid URL.', $urlField.next("span"))
		return false;
	}
	
	return true;
}

function validateBussSlug(slugField){
	var slug = slugField.val();
	//if(slug && !slug.match(/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/)){
	if(slug && !slug.match(/[a-z]/i)){
		setFieldInvalid(slugField.closest('.form-group'), 'URL must contain a alphabet.', slugField.next("span"))
		return false;
	}
	return true;
}

/*function validateUpload($uploadField){
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
}*/

/*function saveSuccess(form, data){
	var msgElem = $(form).find('.sucMes');
	if(data.action == 'show'){
		if(data.type == 'store')
			var message = 'Data has been saved successfully';
		else if(data.type == 'update')
			var message = 'Data has been updated successfully';
		else if(data.type == 'loginAccountCreate')
			var message = 'Your account was successfully created. We have sent you details and confirmation e-mail to your mailbox.';

		msgElem.append('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+message+'</div>');
		msgElem.removeClass('hidden');
	}
	else if(data.action == 'hide' && !msgElem.hasClass('hidden')){
		msgElem.html('');
		msgElem.addClass('hidden');
	}
}*/

/*function validateContryCode(numbField){
	if(numbField.val() != ''){
		var selCountry = numbField.intlTelInput("getSelectedCountryData");
		if(!Object.keys(selCountry).length){
			var formGroup = numbField.closest('.form-group');
			//setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a country.')
			setFieldInvalid(formGroup, 'Please select a country.')
			return false;
		}
	}
	return true;
}*/

/*function getFullNumb(form, numbField){
	if(numbField == null)
		numbField = form.find('input[name="phone"]');

	if(numbField.val() != ''){
		/*var selCountry = numbField.intlTelInput("getSelectedCountryData");
		if(Object.keys(selCountry).length)
			return numbField.intlTelInput("getNumber");
		else
			return numbField.val();*/
		/*return numbField.intlTelInput("getNumber");
	}
	return '';
}*/

function updateState(contryDd){
	if(contryDd.length){
		var country_code = contryDd.val(),
			selectedStates = contryDd.closest('form').find('select.states');
			
		if(country_code == "" || country_code == "undefined" || country_code == null){
			selectedStates.html('<option value="">-- Select --</option>');
			selectedStates.selectpicker('refresh');
		}
		else{		
			$.ajax({
				url: public_url+'countries/'+country_code,
				method: "get",
				data: {},
				success: function(data) {
					var defaultState = selectedStates.data('selected'),
						formGroup = selectedStates.closest('.form-group');

					selectedStates.html("");
					$.each(data, function(val, text){
						var option = '<option value="' + val + '"';
						if(defaultState != '' && defaultState != null && val == defaultState)
							option += ' selected';
						option += '>' + text + '</option>';
						selectedStates.append(option);
					});

					contryDd.selectpicker('refresh');
					selectedStates.selectpicker('refresh');
					setFieldValid(formGroup, formGroup.find('span.help-block'))
				}
			});
		}
	}
}

function validateDob(form, fieldsName){
	var day = form.find("select[name='"+fieldsName.day+"']"),
		dayVal = day.val(),
		month = form.find("select[name='"+fieldsName.month+"']"),
		monthVal = month.val(),
		year = form.find("select[name='"+fieldsName.year+"']"),
		yearVal = year.val(),
		formGroup = day.closest('.form-group'),
		isDobValid = true;
	
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
		setFieldInvalid(formGroup, 'Please select a valid date of birth.')
		return false;
	}
	else{
		setFieldValid(formGroup);
		return true;
	}
}

function validateStartEndDate(fields){
	var startDateField = fields.startDateField,
		endDateField = fields.endDateField,
		startDate = startDateField.val(),
		endDate = endDateField.val(),
		formGroup = startDateField.closest('.form-group');


	if(startDate != '' && endDate != '' && moment(startDate, 'ddd, D MMM YYYY').isAfter(moment(endDate, 'ddd, D MMM YYYY'))){
		setFieldInvalid(formGroup, 'Please select a valid date.')
		return false;
	}
	else{
		setFieldValid(formGroup);
		return true;
	}
}

    $( document ).on( 'change', '#per_session_rate_options', function() {
		 	var sessionRatevalue = $(this).val();
		 	changeattendeesSection(sessionRatevalue);
 	});
 	
    $( document ).on( 'change', 'select[name="staff_services"]', function() {
       var SessionServicesID=[];
      SessionServicesID= prepareMultiSel($('select[name="per_session_service"]'));
    	
    $('select[name="per_session_service"] option').remove();
  	   $('select[name="staff_services"] option:selected').each(function(){
  	   	
  	   	if($.inArray($(this).val(),SessionServicesID) !== -1)
  	   		updateRelatedDd($('select[name="per_session_service"]'), $(this).val(), $(this).text(), true);
 	    else
 	    	updateRelatedDd($('select[name="per_session_service"]'), $(this).val(), $(this).text());
 	      
 		});

 	});

<!-- start: SCRIPTS FOR ADD MORE BUTTON -->
$(document).ready(function() {
	$('body').on('click', '.btn-add-more', function(e){	
		e.preventDefault();
		var parentFormGroup = $(this).closest('.form-group');
		$(parentFormGroup).find('.btn-add-more-msg').remove();
		
		var field = $(parentFormGroup).find('.btn-add-more-value');
		if(field.length > 0)
			return false;
		
		var inputAddMore = $(this).after('<div class="form-group padding-bottom-25"><div class="col-xs-10 no-padding"><input class="form-control input-sm btn-add-more-value" type="text"></div><div class="col-xs-1"><button type="button" class="btn btn-sm btn-success pull-right btn-add-more-ok"><i class="fa fa-check"></i></button></div><div class="col-xs-1"><button type="button" class="btn btn-sm btn-danger pull-right btn-add-more-cancel"><i class="fa fa-times"></i></button></div></div>');	
		
		$(parentFormGroup).find('.btn-add-more-ok').on('click', function(){
			$(parentFormGroup).find('.btn-add-more-msg').remove(); 
			var fieldVal = $(parentFormGroup).find('.btn-add-more-value').val().trim();
			if(fieldVal != "" && fieldVal != "undefined"){  
				var dropdown = $(parentFormGroup).find('select');  
				var valExist = false;
				$(dropdown).find('option').each(function(){
					if(fieldVal.toLowerCase() == $(this).text().toLowerCase()){
						valExist = true;
						return false;
					}
				});
				if(!valExist){
					var action = public_url+'settings/business/'+$(parentFormGroup).find('input[name="btn-add-more-action"]').val();
					var ownerId = $(this).closest('form').find('input.businessId').val();
					if(!ownerId)
						ownerId = '';
					if(action){
						$.post(action, {value:fieldVal, ownerId:ownerId})
						.done(function(data){
							if(data != '0'){
								if(!$(dropdown).attr("multiple"))
								$(dropdown).find("option:selected").removeAttr("selected");
								var option = '<option value="'+data+'" selected>'+fieldVal+'</option>';
								$(dropdown).append(option);
								$(dropdown).trigger( "change" );
								$('select').selectpicker('refresh');
								$(".btn-add-more-cancel").trigger("click");
								$(parentFormGroup).find('.btn-add-more').after('<p class="text-success btn-add-more-msg">Item has been added successfully!</p>');
							}
							else
								$(parentFormGroup).find('.btn-add-more').after('<p class="text-danger btn-add-more-msg">There is some problem in saving the item!</p>');
						});
					}
				}
			}
		});
		
		$(parentFormGroup).find('.btn-add-more-cancel').click(function() {
			$(this).closest('.form-group').remove();
		})
	});
})
<!-- end: SCRIPTS FOR ADD MORE BUTTON --> 

function updateRelatedDd($dd, value, text, setSel){
	if(setSel == null)
		$dd.append('<option value="' + value + '">' + text + '</option>');
	else
		$dd.append('<option value="' + value + '" selected>' + text + '</option>');
	$dd.selectpicker('refresh');
}

$( document ).on( 'change', 'select[name="staffClasses"]', function() {
	var SessionClassID=[];
    	  SessionClassID= prepareMultiSel($('select[name="per_session_class"]'));

     $('select[name="per_session_class"] option').remove();
 	   $('select[name="staffClasses"] option:selected').each(function(){
 	     if($.inArray($(this).val(),SessionClassID) !== -1)
 	     	updateRelatedDd($('select[name="per_session_class"]'), $(this).val(), $(this).text(),SessionClassID);
 	     else
 	     	updateRelatedDd($('select[name="per_session_class"]'), $(this).val(), $(this).text());
 		});

 	});

 

	function changeattendeesSection(SessionRateval){

		 if(SessionRateval =='base_rate_attendees'){
	    	$('.sessionattendeescls').show();
	        $('.sessionattendeestiercls').hide();}
	    else if(SessionRateval =='base_rate_tier'){
	    	$('.sessionattendeestiercls').show();
	        $('.sessionattendeescls').hide();}
	    else{
	    	$('.sessionattendeestiercls').hide();
	    	$('.sessionattendeescls').hide();
	    }

   }
$(document).ready(function(){
	var staffAttendee =$('.staffAttendeeArrCls');
      if(staffAttendee.length){
      	var staffAttendeeVal=staffAttendee.val();

      	if(staffAttendeeVal){
	     var staffAttendeeArr=JSON.parse(staffAttendeeVal); 
      	
			if(staffAttendeeArr.length){
				var createdIdx = 0,
				     perSessionRateType=$('#per_session_rate_options').val(),
					 i = 0;

		             changeattendeesSection(perSessionRateType);
			   
					$.each(staffAttendeeArr, function(key, value){
						
							

			               if(perSessionRateType == 'base_rate_attendees'){
			               	    if(createdIdx){
								   $('.sessionattendeescls').find('.addMoreAttendance').trigger('click');
							     }
			               
							$('.sessionattendeescls').find('input[name="per_session_attendees'+createdIdx+'"]').val(value.sa_per_session_attendees);
							$('.sessionattendeescls').find('input[name="per_session_attendeeto'+createdIdx+'"]').val(value.sa_per_session_attendeeto);
							$('.sessionattendeescls').find('input[name="per_session_price'+createdIdx+'"]').val(value.sa_per_session_price);
							}else{

								if(createdIdx){
								   $('.sessionattendeestiercls').find('.addMoreAttendance').trigger('click');
							     }
							$('.sessionattendeestiercls').find('input[name="per_session_tier'+createdIdx+'"]').val(value.per_session_tier);
							$('.sessionattendeestiercls').find('input[name="per_session_tierto'+createdIdx+'"]').val(value.per_session_tierto);
							$('.sessionattendeestiercls').find('input[name="per_session_tierprice'+createdIdx+'"]').val(value.per_session_tierprice);
							}
							//if(!createdIdx)
								createdIdx++;
							i++;
						
					});
					
				}
	  }
     }
	});


/* Time row */
function getRow(){
	var timeRow = '<div class="col-xs-12 m-t-5 showHoursElem copy-row">\
        <div class="col-xs-4 no-padding">\
            <div class="input-group bootstrap-timepicker timepicker">\
                <input type="text" name="" class="form-control input-sm timepicker1 start-day no-clear" data-default-time="6:00 AM" >\
                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>\
            </div>\
        </div>\
        <div class="col-xs-2 no-padding text-center text-bold">&#95;&#95;&#95;&#95;</div>\
        <div class="col-xs-4 no-padding">\
            <div class="input-group bootstrap-timepicker timepicker">\
                <input type="text" name="" class="form-control input-sm timepicker1 end-day no-clear" data-default-time="7:00 PM" >\
                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>\
            </div>\
        </div>\
        <div class="col-xs-2">\
            <button class="btn btn-xs btn-danger m-t-5 btn-add-new-time-cancel" type="button">Remove</button>\
        </div>\
    </div>';
    return $(timeRow);
}