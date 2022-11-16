/*if(typeof public_url == 'undefined')
	var public_url = $('meta[name="public_url"]').attr('content');*/

$(document).ready(function(){
	$('.editFieldModal').click(function(e){
		e.preventDefault();
		var editFieldModal = $("#editFieldModal"),
			entityField = editFieldModal.find('input[name="entity"]'),
			entityIdField = editFieldModal.find('input[name="entityId"]'),
			otherTargetsField = editFieldModal.find('input[name="otherTargets"]'),
			label = editFieldModal.find('label'),
			formGroup = editFieldModal.find('.form-group'),
			fields = editFieldModal.find('.field'),

			$this = $(this),
			field_type = $this.data('realtime');




		/* Remove previously created fields */
		if(fields.hasClass('editFieldModal-cntryCode'))
			fields.intlTelInput("destroy");
		if(fields.length)
			fields.remove();

		/* Remove previously success/error class */
		formGroup.removeClass().addClass('form-group').find('span.help-block').remove();

		/* Set entity */
		/*if($this.data('entity'))
			entityField.val($this.data('entity'));
		else
			entityField.val(editFieldModal.find('input[name="origEntity"]').val());*/

		/* Set entity ID */
		/*if($this.data('entity-id'))
			entityIdField.val($this.data('entity-id'));
		else
			entityIdField.val(editFieldModal.find('input[name="origEntityId"]').val());*/

		/* Set entity property */
		editFieldModal.find('input[name="entityProperty"]').val(field_type);

		if($this.data('other-targets'))
			otherTargetsField.val($this.data('other-targets'));
		else
			otherTargetsField.val('')

		/* Set label text */
		var labelText = $this.data('label');
		if($this.data('required'))
			labelText += ' *';
		label.text(labelText)

		/* Set label attributes */
		label.attr('for', field_type);

		/* Create input fields and set attributes */
		if($.inArray(field_type, ['firstName', 'lastName', 'email', 'occupation', 'jobTitle']) !== -1){
			if(field_type == 'email')
				var inputType = 'email';
			else if($.inArray(field_type, ['firstName', 'lastName', 'occupation', 'jobTitle']) !== -1)
				var inputType = 'text';

			var field = $('<input type="'+inputType+'" class="form-control field" name="'+field_type+'" id="'+field_type+'">').insertAfter(label);

			if($this.data('required'))
				field.attr('required', 'true')
			field.val($this.data('value'))
		}
		else if(field_type == 'phone'){
			var field = $('<input class="form-control editFieldModal-cntryCode numericField field" maxlength="16" minlength="5" name="'+field_type+'" type="tel" id="'+field_type+'">').insertAfter(label);

			if($this.data('required'))
				field.attr('required', 'true')
			field.val($this.data('value'));

			initTelInput(field);
		}
		else if(field_type == 'gender'){
			var field = $('<div class="field"><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-male" value="Male" class="customValRadField"><label for="editFieldModal-male">Male</label></div><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-female" value="Female" class="customValRadField"><label for="editFieldModal-female">Female</label></div></div><span class="help-block m-b-0 field"></span>').insertAfter(label);
			
			if($this.data('required'))
				field.find('input').attr('required', 'true')
			field.find('input[value="'+$this.data('value')+'"]').prop('checked', true);
		}
		else if(field_type == 'dob'){
			formGroup.addClass('clearfix');

			var valArr = $this.data('value').split('-'),
				field = $('<div class="row field"><div class="col-md-4"><select class="form-control" title="DAY" name="day"><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select></div><div class="col-md-4"><select class="form-control" title="MONTH" name="month">'+monthDdOptions(valArr[1])+'</select></div><div class="col-md-4"><select class="form-control" title="YEAR" name="year">'+yearDdOptions(valArr[0])+'</select></div></div>').insertAfter(label),
				dd = field.find('select');

			if($this.data('required'))
				dd.attr('required', 'true')

			$(dd[0]).find('option[value="'+valArr[2]+'"]').attr('selected', 'true');

			dd.selectpicker('refresh');				
		}
		if(entityField.val() == 'client'){

		 if(field_type == 'accStatus'){
				var field = $('<select id="'+field_type+'" class="form-control field" name="'+field_type+'"><option value="lead">Lead</option><option value="pre-consultation">Pre Consultation</option><option value="pre-benchmarking">Pre Benchmarking</option><option value="pre-training">Pre Training</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="pending">Pending</option><option value="on-hold">On hold</option><option value="inactive-lead">Inactive lead</option><option value="contra">Contra</option></select>').insertAfter(label);

				if($this.data('required'))
					field.attr('required', 'true')
				field.val($this.data('value'));
				field.selectpicker('refresh');
			}
			/*else if(field_type == 'gender'){
				var field = $('<div class="field"><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+$this.data('type')+'" id="editFieldModal-male" value="Male" class="customValRadField"><label for="editFieldModal-male">Male</label></div><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+$this.data('type')+'" id="editFieldModal-female" value="Female" class="customValRadField"><label for="editFieldModal-female">Female</label></div></div><span class="help-block m-b-0 field"></span>').insertAfter(label);
				
				if($this.data('required'))
					field.find('input').attr('required', 'true')
				field.find('input[value="'+$this.data('value')+'"]').prop('checked', true);
			}*/
			else if(field_type == 'goals'){
				var field = $('<select class="form-control field" multiple id="'+field_type+'" name="'+field_type+'"><option value="Health &amp; wellness">Health &amp; wellness</option><option value="Increased energy">Increased energy</option><option value="Tone">Tone</option><option value="Injury recovery">Injury recovery</option><option value="Improved nutrition">Improved nutrition</option><option value="Lose weight">Lose weight</option><option value="Improved performance">Improved performance</option><option value="Improved endurance">Improved endurance</option><option value="Improved Strength &amp; Conditioning">Improved Strength &amp; Conditioning</option></select>').insertAfter(label);
				
				if($this.data('required'))
					field.attr('required', 'true')
				selValmultiselect($this.data('value'), field);
				field.selectpicker('refresh');				
			}
			/*else if(field_type == 'dob'){
				formGroup.addClass('clearfix');

				var valArr = $this.data('value').split('-'),
					field = $('<div class="row field"><div class="col-md-4"><select class="form-control" title="DAY" name="day"><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select></div><div class="col-md-4"><select class="form-control" title="MONTH" name="month">'+monthDdOptions(valArr[1])+'</select></div><div class="col-md-4"><select class="form-control" title="YEAR" name="year">'+yearDdOptions(valArr[0])+'</select></div></div>').insertAfter(label),
					dd = field.find('select');

				if($this.data('required'))
					dd.attr('required', 'true')

				$(dd[0]).find('option[value="'+valArr[2]+'"]').attr('selected', 'true');

				dd.selectpicker('refresh');				
			}*/
		}
		
		editFieldModal.modal();
	})

    $("#editFieldModal .submit").click(function(){
		var formData = {},
			modal = $(this).closest('div.modal'),
			form = modal.find('form'),
			isFormValid = form.valid(),
			entityProperty = form.find("input[name='entityProperty']").val();

		if(entityProperty == 'gender'){
			/*var gendVal = form.find("input[name='gender']:checked").val(),
				formGroup = form.find("input[name='gender']").closest('.form-group');
			if(gendVal == null || gendVal == ""){
				isFormValid = false;
				setFieldInvalid(formGroup, 'Please select a valid gender.', formGroup.find("span.help-block"))
			}
			else
				setFieldValid(formGroup);*/

			if(!validateGender(form, 'gender'))
				isFormValid = false;
		}
		else if(entityProperty == 'phone'){
			if(!(validateContryCode($(form).find("input[name='phone']"))))
				isFormValid = false;
		}

		if(isFormValid){
			form.find(':input').each(function(){
				formData[$(this).attr('name')] = $(this).val()
			})

			if(entityProperty == 'gender')
				formData['gender'] = form.find("input[name='gender']:checked").val()

			else if(entityProperty == 'goals')
				formData['goals'] = prepareMultiSel(form.find('select[name="goals"]'));

			else if(entityProperty == 'phone'){
				/*numbField = form.find('input[name="phone"]');
				if(numbField.val() != ''){
					var selCountry = numbField.intlTelInput("getSelectedCountryData");
					if(Object.keys(selCountry).length)
						formData['phone'] = numbField.intlTelInput("getNumber");
					else
						formData['phone'] = numbField.val();
				}*/

				var cntryData = form.find('input[name="phone"]').intlTelInput("getNumber")
				if(cntryData)
					formData['phone'] = cntryData;
			}

			if(formData.entity == 'client')
				var url = public_url+'client/'+formData.entityId+'/update-field';
			

			$.ajax({
				url: url,
				method: "POST",
				data: formData,
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "updated"){
						modal.modal('hide');
						/*realTimeUpdate(formData.entityProperty, data.value);

						if(formData.otherTargets){
							var otherTargets = formData.otherTargets.split('|');
							$.each(otherTargets, function(val, text){
								realTimeUpdate(formData.entityProperty, data.value, text);
							});
						}*/
						realTimeUpdate(formData.entityProperty, data.value, formData.otherTargets);
					}
					else if(data.status == "emailExistError"){
						var emailField = form.find('input[name="email"]');						
						setFieldInvalid(emailField.closest('.form-group'), data.message, emailField.next("span"))
					}
				}
			});
		}
	})

	$(document).on("countrychange", ".editFieldModal-cntryCode", function(){
		initTelInputOnChange($(this))
	})
});

function realTimeUpdate(fieldType, value, otherTargets){
	realTimeUpdateHelper(fieldType, value);
	if(typeof otherTargets != 'undefined' && otherTargets){
		var otherTargets = otherTargets.split('|');
		$.each(otherTargets, function(key, val){
			realTimeUpdateHelper(fieldType, value, val);
		});
	}		
}

function realTimeUpdateHelper(fieldType, val, otherTarget){
	if(typeof otherTarget != 'undefined')
		var relatedFields = otherTarget;
	else
		var relatedFields = fieldType;

	if(fieldType == 'email'){
		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A'){
				if($elem.hasClass('editFieldModal'))
					$elem.data('value', val);
				else{
					$elem.attr('href', 'mailto:'+val)
					$elem.text(val)
				}
			}
			else if(elemType == 'INPUT')
				$elem.val(val)
			else
				$elem.text(val)
		})
	}
	else if(fieldType == 'phone'){
		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A'){
				if($elem.hasClass('editFieldModal'))
					$elem.data('value', val);
				else{
					$elem.attr('href', 'tel:'+val)
					$elem.text(val)
				}
			}
			else if(elemType == 'INPUT')
				$elem.intlTelInput("setNumber", val.toString());
		})
	}
	else if($.inArray(fieldType, ['firstName', 'lastName', 'occupation', 'jobTitle']) !== -1){
		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'INPUT')
				$elem.val(val);
			else if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val)
			else
				$elem.text(val)
		})
	}
	else if(fieldType == 'accStatus'){
		var vals = val.split('|');
		//console.log(vals);
		if(vals[1] == 'active')
       		var html = '<span class="label label-info">Active</span>';
       	else
       		var html = '<span class="label label-warning">'+ucfirst(vals[1])+'</span>';

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', vals[0]);
			else if(elemType == 'SELECT'){
				$elem.val(vals[0])
				$elem.selectpicker('refresh');
			}
			else
				$elem.html(html);
		})
	}
	else if(fieldType == 'parqStatus'){
		if(val == 0)
			var html = '<span class="label label-info">Completed</span>';
		else if(val == 1)
			var html = '<span class="label label-warning">'+val+' Step Left</span>';
		else
			var html = '<span class="label label-warning">'+val+' Steps Left</span>';

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'TD')
				$elem.html(html);
		})
	}
	else if(fieldType == 'referralNetwork'){
		var vals = val.split('|');
		console.log(vals);
		if(vals[0] == 'Client')
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'client/'+vals[1]+'">'+vals[2]+'</a> (Client)'
		else if(vals[0] == 'Staff')
			//var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'staff/'+vals[1]+'">'+vals[2]+'</a> (Staff)'
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'settings/business/staff/'+vals[1]+'">'+vals[2]+'</a> (Staff)'
		else if(vals[0] == 'Professional network')
			//var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'contact/'+vals[1]+'">'+vals[2]+'</a> (Professional network)'
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'settings/business/contacts/'+vals[1]+'">'+vals[2]+'</a> (Professional network)'

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'TD')
				$elem.html(html);
		})
	}
	else if(fieldType == 'gender'){
		var ajaxSent = false;

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'INPUT' && $elem.val() == val)
				$elem.prop('checked', true);
			else if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val)
			else if(elemType == 'IMG' && $elem.attr('src').lastIndexOf("public/profiles/") >= 0){
				if(!ajaxSent){
					$.ajax({
						url: $('meta[name="public_url"]').attr('content')+'noimage-src',
						method: "GET",
						data: {
							gender: val
						},
						success: function(data){
							updatePicSrc(data);
						}
					});
					ajaxSent = true;
				}
			}
			else
				$elem.text(val)
		})
	}
	else if(fieldType == 'goals'){
		var valArr = val.split(',');

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', valArr)
			else if(elemType == 'SELECT'){
				selValmultiselect(valArr, $elem, true);
				$elem.selectpicker('refresh');
			}
			else
				$elem.html(val);
		})
	}
	else if(fieldType == 'dob'){
		var overviewDob = moment(val).format("ddd, D MMM YYYY"),//$.datepicker.formatDate('dd M, yy', new Date(val)),
			valArr = val.split('-');

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val)
			else if(elemType == 'SELECT'){
				if($elem.hasClass('js-day'))
					$elem.val(valArr[2])
				
				else if($elem.hasClass('js-month'))
					$elem.val(valArr[1])
				
				else if($elem.hasClass('js-year'))
					$elem.val(valArr[0])
				
				$elem.selectpicker('refresh');
			}
			else
				$elem.html(overviewDob);
		})
	}
}

function selValmultiselect(valArr, dd, resetDd){
	if(resetDd != null && resetDd)
		dd.find('option').removeAttr("selected");

	if(valArr.length){
		$.each(valArr, function(key, value){
			dd.find('option[value="'+value+'"]').attr('selected', 'true')
		});
	}
}

function ucfirst(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function updatePicSrc(src){
	$('img[data-realtime="gender"]').each(function(){
		$(this).attr('src', src)
	})
}

function monthDdOptions(defaultMonth){
	if(defaultMonth == null)
		defaultMonth = '';

	return '<option value="01" '+(defaultMonth == '01'?'selected':'')+'>January</option><option value="02" '+(defaultMonth == '02'?'selected':'')+'>February</option><option value="03" '+(defaultMonth == '03'?'selected':'')+'>March</option><option value="04" '+(defaultMonth == '04'?'selected':'')+'>April</option><option value="05" '+(defaultMonth == '05'?'selected':'')+'>May</option><option value="06" '+(defaultMonth == '06'?'selected':'')+'>June</option><option value="07" '+(defaultMonth == '07'?'selected':'')+'>July</option><option value="08" '+(defaultMonth == '08'?'selected':'')+'>August</option><option value="09" '+(defaultMonth == '09'?'selected':'')+'>September</option><option value="10" '+(defaultMonth == '10'?'selected':'')+'>October</option><option value="11" '+(defaultMonth == '11'?'selected':'')+'>Novemeber</option><option value="12" '+(defaultMonth == '12'?'selected':'')+'>December</option>';
}

function yearDdOptions(defaultYear){
	if(defaultYear == null)
		defaultYear = '';

	var endYear = (new Date().getFullYear())-10,
		startYear = endYear-100,
		options = '';

	for(; endYear>startYear; endYear--)
		options += '<option value="'+endYear+'" '+(defaultYear == endYear?'selected':'')+'>'+endYear+'</option>';

	return options;
}