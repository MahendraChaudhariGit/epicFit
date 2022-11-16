var serviceOption = '',
rowCount = 0,
sessionOption = '';
$('#add-member-service').click(function(e){
	e.preventDefault();
	var service = $(this).data('service-list'),
	serviceOption='';
	console.log(service);
	// var service = JSON.parse(serviceData);
	if(Object.keys(service).length > 0){
		$.each(service, function(key, value){
			serviceOption += `<option value=${value.id}>${value.category == 2?value.one_on_one_name : value.team_name}</option>`;
		});
	}
	createNewRow(0, {limit:'1',limit_type:''}, serviceOption);
});

/* Start: Create new row for service */
function createNewRow(serviceId, data, serviceOption){
	fieldset = $('#service-group'),
	newRow = fieldset.children().last().clone();
	rowCount = fieldset.find('.new-row').length;
	console.log(rowCount);
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

	newRow.find('.mem_limit').val(data.limit).attr('name', 'mem_limit'+rowCount).prop('required',true);
	
	var ddGroup2 = newRow.find('.limitTypeGroup');
	var dd2 = ddGroup2.find('select.mem_type');
	ddGroup2.empty();
	ddGroup2.append(dd2);
	ddGroup2.find('select.mem_type').val(data.limit_type).attr('name','mem_type'+rowCount).prop('required',true).selectpicker('refresh');
	fieldset.append(newRow);
}
/* End: Create new row for service */
/* Start: delete row for service */
$('body').on('click', '.remove-member-service-row', function(e){
	console.log('hi');
	e.preventDefault();
	var $this = $(this);
	$this.closest('.row').remove();
});
/* Start: delete row for service */
$('#add-member-session').click(function(e){
	console.log('hi');
	e.preventDefault();
	var clas =$(this).data('session-list'),
	sessionOption ='';
	console.log(clas);
	if(Object.keys(clas).length > 0){
		$.each(clas, function(key, value){
			sessionOption += "<option value='"+value.clcat_id+"'>"+value.clcat_value+"</option>";
		});
	}
	createNewRowforSession(0, {limit:'1',limit_type:''}, sessionOption);
});

/* Start: Create new row for service */
function createNewRowforSession(serviceId, data, sessionOption){
	console.log('hi');
	fieldset = $('#session-group'),
	newRow = fieldset.children().last().clone();
	rowCount = fieldset.find('.new-row').length;
	console.log(rowCount);
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
	ddGroup.find('select.mem_session').val(serviceId).attr('name','mem_session'+rowCount).prop('required',true).selectpicker('refresh');

	newRow.find('.session_mem_limit').val(data.limit).attr('name', 'session_mem_limit'+rowCount).prop('required',true);
	var ddGroup2 = newRow.find('.limitTypeGroup');
	var dd2 = ddGroup2.find('select.session_mem_type');
	console.log(dd2);
	ddGroup2.empty();
	ddGroup2.append(dd2);
	ddGroup2.find('select.session_mem_type').val(data.limit_type).attr('name','session_mem_type'+rowCount).prop('required',true).selectpicker('refresh');

	fieldset.append(newRow);
}
/* End: Create new row for service */
/* Start: delete row for service */
$('body').on('click', '.remove-member-session-row', function(e){
	console.log('hi');
	e.preventDefault();
	var $this = $(this);
	$this.closest('.row').remove();
});



/* Start: get services which link to memebership */
function resetServiceRow(){
	var fieldset = $('#service-group');
	fieldset.find('.new-row').remove();
}

function displayServiceRow(response){
	var service = response;
		if(Object.keys(service).length > 0){
		$.each(service, function(key, value){
			serviceOption += "<option value='"+key+"' data-price='"+value.price+"' data-editedprice='"+value.editedPrice+"'>"+value.name+"</option>";
		});
	}
	
}

$('#add-pdf').click(function(e){
	e.preventDefault();
	createNewRowPdf(0, {limit:'1',limit_type:''}, serviceOption);
});

function createNewRowPdf(serviceId, data, serviceOption){
	fieldset = $('#pdf-group'),
	newRow = fieldset.children().last().clone();
	rowCount = fieldset.find('.new-row').length;
	rowCount++;
	newRow.removeClass('hidden');
	newRow.addClass('new-row');
	var ddGroup = newRow.find('.pdfGroup');
	var pdfField = ddGroup.find('.pdfName');
	var startPdf = newRow.find('.pdfStartGroup');
	var pdfDateField = startPdf.find('.pdfStartDate');
	var pdfFile = newRow.find('.pdfFile'),
	pdfFileName = newRow.find('.pdfFileName');
	pdfOriginalFileName = newRow.find('.pdfOriginalName');
	console.log(pdfOriginalFileName);
	ddGroup.empty();
	startPdf.empty();
	ddGroup.append(pdfField);
	startPdf.append(pdfDateField);
	ddGroup.append(pdfFileName);
	ddGroup.append(pdfFileName);
	ddGroup.append(pdfOriginalFileName);
	pdfFileName.text('').attr('name','pdfFileName'+rowCount);
	pdfOriginalFileName.val('').attr('name','pdfOriginalName'+rowCount);

	pdfField.val('').attr('name','pdfName'+rowCount);
	pdfDateField.val('').attr('name', 'pdfStartDate'+rowCount);
	pdfFile.val('').attr('name','pdfFile'+rowCount);
	fieldset.append(newRow);
}

$('body').on('click', '.remove-pdf-row', function(e){
	e.preventDefault();
	var $this = $(this);
	$this.closest('.row').remove();
});

$('.saveLdc').click(function(){

var form = $('#createForm'),
isValid = false,
formData = {},
serviceData = {},
sessionData = {},
pdfData = {};
formData['ldcname'] = $('input[name="ldcname"]').val(),
formData['startDate'] = $('input[name="startDate"]').val(),
formData['endDate'] = $('input[name="endDate"]').val();
formData['id'] = $('input[name="ldc_id"]').val();
if(formData['ldcname'] != ''){
	isValid = true;
}
$.each(form.find('#service-group .new-row input,#service-group .new-row select').serializeArray(), function(i, field){
	serviceData[field.name] = field.value;
});
formData['services'] = serviceData;

$.each(form.find('#session-group .new-row input,#session-group .new-row select').serializeArray(), function(i, field){
	sessionData[field.name] = field.value;
});
formData['sessions'] = sessionData;

$.each(form.find('#pdf-group .new-row input'),function(i,field){
	pdfData[field.name] = field.value;
});
formData['pdf'] = pdfData;
console.log(formData);
if(isValid){
$.ajax({
	url: public_url+'save-ldc-data',
	data: formData,
	type: 'post',
	success: function(response){
		var data = JSON.parse(response);
		if(data.status == 'success'){
			swal({
				title: 'Data saved successfully',
				allowOutsideClick: false,
				showCancelButton: false,
				confirmButtonText: 'Ok',
				confirmButtonColor: '#ff4401',
				cancelButtonText: "No"
			 }, 
			 function(isConfirm){
				 swal.close();
				 window.location.href = public_url+'settings/ldc/session';
			 });
		}
	}
});
}else{
	swal({
		title: 'Please fill Ldc Name',
		allowOutsideClick: false,
		showCancelButton: false,
		confirmButtonText: 'Ok',
		confirmButtonColor: '#ff4401',
		cancelButtonText: "No"
	 }, 
	 function(isConfirm){
		 swal.close();
	 });
}
});


$('body').on('change','.pdfName',function(e){
	var file_data = $(this).prop('files')[0];
	var form_data = new FormData(),
	$this = $(this);
	form_data.append('file', file_data);
	$.ajax({ 
		url: public_url+'upload-pdf-file', 
		dataType    : 'text',           
		cache       : false,
		contentType : false,
		processData : false,
		data        : form_data,                         
		type        : 'post',
		success: function(response){
			var data = JSON.parse(response);
			console.log(response);
			if(data.status == 'success'){ 
				console.log( $(this));
				$this.closest('.row').find('.pdfFile').val(data.name);
				$this.closest('.row').find('.pdfName').val('');
				$this.closest('.row').find('.pdfFileName').text(data.fileOriginalName);
				$this.closest('.row').find('.pdfOriginalName').val(data.fileOriginalName);
				
			}else{
				$this.closest('.row').find('.pdfName').val('');
				swal({
					title: 'Invalid File',
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Ok',
					confirmButtonColor: '#ff4401',
					cancelButtonText: "No"
				 }, 
				 function(isConfirm){
					 swal.close();
					 location.reload();
				 });
			} 
			
		}, 
	},'json'); 

});

