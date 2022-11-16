/*if(typeof public_url == 'undefined')
var public_url = $('meta[name="public_url"]').attr('content');*/
var ldcSession ="";
$(document).ready(function(){
	$('.stopExtraEvent').click(function(event){
		event.stopPropagation();
	})

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
			var field = $('<div class="field"><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-male" value="Male" class="onchange-set-neutral"><label for="editFieldModal-male">Male</label></div><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-female" value="Female" class="onchange-set-neutral"><label for="editFieldModal-female">Female</label></div></div><span class="help-block m-b-0 field"></span>').insertAfter(label);
			
			if($this.data('required'))
				field.find('input').attr('required', 'true')
			field.find('input[value="'+$this.data('value')+'"]').prop('checked', true);
		}
		else if(field_type == 'membStatus'){
			var field = $('<div class="field"><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-paidStatus" value="Active" class="onchange-set-neutral"><label for="editFieldModal-paidStatus">Active</label></div><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-unpaidStatus" value="On Hold" class="onchange-set-neutral"><label for="editFieldModal-unpaidStatus">On Hold</label></div><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-unpaidStatusnew" value="Inactive" class="onchange-set-neutral"><label for="editFieldModal-unpaidStatusnew">Inactive</label></div></div><span class="help-block m-b-0 field"></span>').insertAfter(label);
			
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
		else if(field_type == 'consultation'){
			//formGroup.addClass('clearfix');
			var preDate = $this.data('value');
			if(preDate && preDate != null)
				preDate = dbDateToDateString(preDate);
			else{
				preDate='';
			}
			
			var field = $('<div class="field"><input class="form-control datepicker onchange-set-neutral" value="'+preDate+'" autocomplete="off" readonly="readonly" type="text" name="consultationDate"></div>').insertAfter(label); 

			if($this.data('required'))
				field.find('input').attr('required', 'true')

			field.find('[name="consultationDate"]').datepicker({autoclose:true, dateFormat:"D, d M yy"});
			/*dd.selectpicker('refresh');	*/			
		}
		else if(field_type == 'sales-process-date'){
			//formGroup.addClass('clearfix');
			var stepNo=$this.closest('a').data('step-number');
			
			if(stepNo){
				var preDate = $this.data('value');
				if(preDate && preDate != null)
					preDate = dbDateToDateString(preDate);
				else{
					preDate='';
				}
				
				var field = $('<div class="field"><input class="form-control datepicker onchange-set-neutral" value="'+preDate+'" autocomplete="off" readonly="readonly" type="text" name="salesProcessDate"><input type="hidden" value="'+stepNo+'" name="stepNumber"></div>').insertAfter(label); 

				if($this.data('required'))
					field.find('input').attr('required', 'true')

				field.find('[name="salesProcessDate"]').datepicker({autoclose:true, dateFormat:"D, d M yy"});
				/*dd.selectpicker('refresh');	*/
			}			
		}else if(field_type == 'ldcStatus'){
			var field = $('<div class="field"><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-ldcActive" value="1" class="onchange-set-neutral"><label for="editFieldModal-ldcActive">Active</label></div><div class="radio clip-radio radio-primary radio-inline m-b-0"><input type="radio" name="'+field_type+'" id="editFieldModal-ldcInActive" value="0" class="onchange-set-neutral"><label for="editFieldModal-ldcInActive">Inactive</label></div><div class="row"><div class="col-sm-3 col-xs-6"><div class="form-group hidden ldcList"><select name="ldc_list" class="form-group showList"></select></div></div></div></div><span class="help-block m-b-0 field"></span>').insertAfter(label);
			if($this.data('required'))
				field.find('input').attr('required', 'true')
			field.find('input[value="'+$this.data('value')+'"]').prop('checked', true);
			ldcSession = $(this).data('ldc-session');
        if($this.data('value') == 1){
			$('#editFieldModal-ldcActive').trigger('click');
		}
		}
		
		if(entityField.val() == 'client'){
			if(field_type == 'accStatus'){
				var field = $('<select id="'+field_type+'" class="form-control field" name="'+field_type+'"><option value="pending">Pending</option><option value="active-lead">Active lead</option><option value="inactive-lead">Inactive lead</option><option value="pre-consultation">Pre Consultation</option><option value="pre-benchmarking">Pre Benchmarking</option><option value="pre-training">Pre Training</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="on-hold">On hold</option><option value="contra">Contra</option></select>').insertAfter(label); //<option value="lead">Lead</option>

				if($this.data('required'))
					field.attr('required', 'true')
				field.val($this.data('value'));
				field.selectpicker('refresh');
			}
			else if(field_type == 'goals'){
				var field = $('<select class="form-control field" multiple id="'+field_type+'" name="'+field_type+'"><option value="Health &amp; wellness">Health &amp; wellness</option><option value="Increased energy">Increased energy</option><option value="Tone">Tone</option><option value="Injury recovery">Injury recovery</option><option value="Improved nutrition">Improved nutrition</option><option value="Lose weight">Lose weight</option><option value="Improved performance">Improved performance</option><option value="Improved endurance">Improved endurance</option><option value="Improved Strength &amp; Conditioning">Improved Strength &amp; Conditioning</option></select>').insertAfter(label);
				
				if($this.data('required'))
					field.attr('required', 'true')
				selValmultiselect($this.data('value'), field);
				field.selectpicker('refresh');				
			}
		}
		else if(entityField.val() == 'Staff'){
			if(field_type == 'permGroup'){
				var fieldHtml = '<select id="'+field_type+'" class="form-control field" name="'+field_type+'">';
				$.each($this.data('options'), function(key,val){
					fieldHtml += '<option value="'+key+'">'+val+'</option>';
				})
				fieldHtml += '</select>';
				var field = $(fieldHtml).insertAfter(label);

				if($this.data('required'))
					field.attr('required', 'true')
				field.val($this.data('value'));
				field.selectpicker('refresh');
			}
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
		if(!validateGender(form, 'gender'))
			isFormValid = false;
	}
	else if(entityProperty == 'membStatus'){
		if(!validateRadioButton(form, 'membStatus'))
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
		
		if('consultationDate' in formData)
			formData.consultationDate = dateStringToDbDate(formData['consultationDate']);

			/*else if('salesProcessDate' in formData)
			formData.salesProcessDate = dateStringToDbDate(formData['salesProcessDate']);*/

			if(formData.entity == 'client')
				var url = public_url+'client/'+formData.entityId+'/update-field';
			else if(formData.entity == 'Staff')
				var url = public_url+'settings/business/staffs/'+formData.entityId+'/update-field';
			else if(formData.entity == 'Admin')
				var url = public_url+'my-profile/update-field';

			if(formData.entity == 'client' && entityProperty == 'sales-process-date' && formData.stepNumber==3){
				swal({
					title: "Do you want to change consultation date?",
					type: 'warning',
					allowEscapeKey: false,
					showCancelButton: true,
					confirmButtonText: 'Yes',
					cancelButtonText:'No',
					confirmButtonColor: '#ff4401'
				}, 
				function(isConfirm){
					if(isConfirm)
						realTimeUpdateAjax(url, formData)
				});
				return;
			}

			if(formData.entity == 'client' && entityProperty == 'accStatus'){
				var editFieldModal = $("#editFieldModal");
				if(editFieldModal.hasClass('fromEditMemPopup')) {
					confirmSwalPopup(function(){
						realTimeStatusAjax(formData, url);
					});
				} else {
					confirmSwalPopup(function(){
						if((formData.accStatus != 'active' && formData.accStatus != 'contra') || canMakeClientActive('realTimeStatusAjax('+JSON.stringify(formData)+',"'+url+'")')){
							realTimeStatusAjax(formData, url);
						}
					});
				}
				return;
			}
			if(entityProperty == 'ldcStatus'){
				formData['ldcStatus'] = form.find("input[name='ldcStatus']:checked").val();
				formData['ldcOption'] = form.find("select[name='ldc_list']:checked").val();
				swal({
					title: "Do you want to change LDC Class Status?",
					type: 'warning',
					allowEscapeKey: false,
					showCancelButton: true,
					confirmButtonText: 'Yes',
					cancelButtonText:'No',
					confirmButtonColor: '#ff4401'
				}, 
				function(isConfirm){
					if(isConfirm)
						realTimeUpdateAjax(url, formData)
				});
				return;
			}
			
			if(entityProperty == 'gender')
				formData['gender'] = form.find("input[name='gender']:checked").val()

			else if(entityProperty == 'membStatus')
				formData['membStatus'] = form.find("input[name='membStatus']:checked").val()

			else if(entityProperty == 'goals')
				formData['goals'] = prepareMultiSel(form.find('select[name="goals"]'));

			else if(entityProperty == 'phone'){
				var cntryData = form.find('input[name="phone"]').intlTelInput("getNumber")
				if(cntryData)
					formData['phone'] = cntryData;
			}

			realTimeUpdateAjax(url, formData)
		}
	})

$(document).on("countrychange", ".editFieldModal-cntryCode", function(){
	initTelInputOnChange($(this))
})
});

function realTimeUpdateAjax(url, formData){
	toggleWaitShield('show');
	var modal = $('#editFieldModal');
	$.ajax({
		url: url,
		method: "POST",
		data: formData,
		success: function(data){
			var data = JSON.parse(data);
			toggleWaitShield('hide');
			if(data.status == "updated"){
				modal.modal('hide');
				if(formData.entityProperty=='sales-process-date')
					realTimeSalesDateUpdate(data.value, formData.stepNumber);
				else if(formData.entityProperty=='accStatus')
					location.reload(true);
				else
					realTimeUpdate(formData.entityProperty, data.value, formData.otherTargets);
				
				
				if(formData.entity == 'client' && 'stepCompleted' in data){
					closeContactNoteSubview();
					if(data.action == 'upgrade'){
						/*if('consultationDate' in data)
							fetchStepAndMarkComplete(data.stepCompleted, '', data.consultationDate);
						else
							fetchStepAndMarkComplete(data.stepCompleted);*/
						if('consultationDate' in data)
							var consultationDate = data.consultationDate;
						else
							var consultationDate = '';
						if('salesProcessDate' in data)
							var completedOn = data.salesProcessDate;
						else
							var completedOn = '';
						fetchStepAndMarkComplete(data.stepCompleted, completedOn, consultationDate);
					}
					else if(data.action == 'downgrade'){
						if('consultationDate' in data)
							downgradeSteps(data.oldSaleProcessStep, data.stepCompleted, data.consultationDate);
						else
							downgradeSteps(data.oldSaleProcessStep, data.stepCompleted);
					}

					setStepsBooked(data.stepsBooked);	
				}
			}
			else if(data.status == "emailExistError"){
				var emailField = modal.find('input[name="email"]');						
				setFieldInvalid(emailField.closest('.form-group'), data.message, emailField.next("span"))
			}
			else if(data.status == "numbExistError"){
				var numbField = modal.find('input[name="phone"]');						
				setFieldInvalid(numbField.closest('.form-group'), data.message, numbField.next("span"))
			}
		}
	});
}

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
		//location.reload(true);
		var vals = val.split('|');
		if(vals[1] == 'Active')
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
				//selValmultiselect(valArr, $elem, true);
				$elem.selectpicker('refresh');
			}
			else
				$elem.html(html);
		})
	}
	else if(fieldType == 'permGroup'){
		var vals = val.split('|');
		
		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
			elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', vals[0]);
			else
				$elem.text(vals[1]);
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
		if(vals[0] == 'Client'){
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'client/'+vals[1]+'">'+vals[2]+'</a> (Client)';
			var idFieldName = 'clientId';
		}
		else if(vals[0] == 'Staff'){
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'settings/business/staff/'+vals[1]+'">'+vals[2]+'</a> (Staff)'
			var idFieldName = 'staffId';
		}
		else if(vals[0] == 'Professional network'){
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'settings/business/contacts/'+vals[1]+'">'+vals[2]+'</a> (Professional network)'
			var idFieldName = 'proId';
		}

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
			elemType = $elem.prop('nodeName');


			if(elemType == 'TD' || elemType == 'SPAN')
				$elem.html(html);
			else if(elemType == 'DIV' && !$elem.hasClass('parq-view')){
				$elem.find('input[type="radio"][value="'+vals[0]+'"]').prop('checked', true);
				$elem.find('input[type="text"]').val('');
				$elem.find('input[type="hidden"]').val('');
				
				if($elem.data('modal'))
					var modal = $('#referredByModal');
				else
					var modal = false;

				if(vals[0] == 'Client'){
					if(modal)
						var valFieldName = 'refereModalClientName'
					else
						var valFieldName = 'clientName'
				}
				else if(vals[0] == 'Staff'){
					if(modal)
						var valFieldName = 'refereModalStaffName'
					else
						var valFieldName = 'staffName'
				}
				else if(vals[0] == 'Professional network'){
					if(modal)
						var valFieldName = 'refereModalProName'
					else
						var valFieldName = 'proName'
				}


				$elem.find('input[name="'+valFieldName+'"]').val(vals[2]);
				$elem.find('input[name="'+idFieldName+'"]').val(vals[1]);

				
				$elem.find('[name="referrer"]').val(vals[3]).selectpicker('refresh');
				$elem.find('select#referrer').val(vals[3]).selectpicker('refresh');
				
				if(vals[3]=="onlinesocial" || vals[3]=="mediapromotions"){
					$elem.find('input[name="referencewhere"]').val(vals[4]);
					$elem.find('input[name="otherName"]').val('');
				}
				else if(vals[3]=="socialmedia"){
					$elem.find('input[name="otherName"]').val(vals[5]);
					$elem.find('input[name="referencewhere"]').val('');
				}
				else{
					$elem.find('input[name="otherName"]').val('');
					$elem.find('input[name="referencewhere"]').val('');
				}

				isEpic($('select[name="referrer"]'));
				toggleReference(vals[0], modal);
			}
			else if(elemType == 'DIV' && $elem.hasClass('parq-view')){
				var ddTextField = $elem.find('.referrerCls');
				var htmlview='--';
				if(vals[3]=="onlinesocial")
					htmlview="Online & Social Media";
				else if(vals[3]=="mediapromotions")
					htmlview="Media & Promotions";
				else if(vals[3]=="socialmedia")
					htmlview="Other";
				else if(vals[3]=="referral")
					htmlview="Referral";

				$elem.find('.referrerCls').text(htmlview);
				$elem.find('.promosionCls').removeClass('hidden');
				if(vals[3]=="onlinesocial" || vals[3]=="mediapromotions"){
					$elem.find('.referencewhereCls').text(vals[4]);
					$elem.find('.referrerotherCls').text('');
					$('.referencewhereCls').closest('.form-group').removeClass('hidden');
					$('.referrerotherCls').closest('.form-group').addClass('hidden');
				}
				else if(vals[3]=="socialmedia"){
					$elem.find('.referencewhereCls').text('');
					$elem.find('.referrerotherCls').text(vals[5]);
					$('.referencewhereCls').closest('.form-group').addClass('hidden');
					$('.referrerotherCls').closest('.form-group').removeClass('hidden');
				}
				else{
					$elem.find('.referencewhereCls').text('');
					$elem.find('.referrerotherCls').text('');
					$elem.find('.promosionCls').addClass('hidden');
				}
				$elem.find('.referralNetworkCls').html(html);
			}
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
	else if(fieldType == 'ldcStatus'){
		var ajaxSent = false;

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
			elemType = $elem.prop('nodeName');
			if(val == 1)
				var html = '<span class="label label-info">Active</span>';
			else
				var html = '<span class="label label-warning">Inactive</span>';
			if(elemType == 'INPUT' && $elem.val() == val)
				$elem.prop('checked', true);
			else if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val)
			else
				$elem.html(html)
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
	/*else if(fieldType == 'membershipOption'){
		location.reload();
		var vals = val.split('|');
		
       		var html = ucfirst(vals[1]);

		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', vals[0]);
			else
				$elem.html(html);
		})
	}*/
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
					//$elem.find('option[value="'+valArr[2]+'"]').attr('selected', 'true');

					else if($elem.hasClass('js-month'))
						$elem.val(valArr[1])
					//$elem.find('option[value="'+valArr[1]+'"]').attr('selected', 'true');

					else if($elem.hasClass('js-year'))
						$elem.val(valArr[0])
					//$elem.find('option[value="'+valArr[0]+'"]').attr('selected', 'true');

					$elem.selectpicker('refresh');
				}
				else
					$elem.html(overviewDob);

			})
		var currentDate=moment();
		var dbDate=moment(val);
		var age = currentDate.diff(dbDate, 'year');
		
		$('[data-realtime="'+relatedFields+'_age"]').each(function(){
			$(this).text(age+' Year(s)')
		});
	}
	else if(fieldType == 'membStatus')
		location.reload(true);

	else if(fieldType == 'consultation'){
		$('[data-realtime="'+relatedFields+'"]').each(function(){
			var $elem = $(this),
			elemType = $elem.prop('nodeName');

			if(elemType == 'INPUT')
				$elem.val(val);
			else if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val);
			else if(val == null || val == '')
				$elem.text('');
			else
				$elem.text(dbDateToDateString(val));
			
		});
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

	var endYear = (new Date().getFullYear())-1,
	startYear = endYear-100,
	options = '';

	for(; endYear>startYear; endYear--)
		options += '<option value="'+endYear+'" '+(defaultYear == endYear?'selected':'')+'>'+endYear+'</option>';

	return options;
}

function realTimeSalesDateUpdate(value, step ){
	var elem = $('[data-step-number='+step+']');
	
	elem.find('.compl-date').text(value);
	elem.find('.editFieldModal').data('value',value);

	if(step == 3){
		realTimeUpdate('consultation', dateStringToDbDate(value));
	}
}

function realTimeStatusAjax(formData, url){
	salesProcessUpgradeCheck(formData.accStatus, function(){
		swal.close();
		realTimeUpdateAjax(url, formData)
	})
}

$('body').on('click','#editFieldModal-ldcActive',function(){
	var ldcOption='';
	$.ajax({
		url : public_url+'ldc-data',
		type : 'GET',
		data : {},
		async: false,
		success : function(response) {
			var ldc = JSON.parse(response);
			if(ldc.status == 'success'){
				if(Object.keys(ldc.data).length > 0){
					$.each(ldc.data, function(key, value){
						ldcOption += `<option value=${value.ldc_id}>${value.ldc_name}</option>`;
					});
				}
				$('.showList').empty().append(ldcOption).selectpicker('refresh');
				$('.showList').val(ldcSession).selectpicker('refresh');
				$('.ldcList').removeClass('hidden');
			}
		}
	});
})
$('body').on('click','#editFieldModal-ldcInActive',function(){
	if(!$('.ldcList').hasClass('hidden')){
		$('.ldcList').addClass('hidden');
	}
});