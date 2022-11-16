/* Start: update edit field value */
function updateEditedField(data, extraData){

	var fieldName = data['entity']+'-'+data['name']+'-'+data['type'],
		fieldObj = $('.'+fieldName),
		value = '';

	/* update data attribute value */
	$("[data-field='"+fieldName+"']").data('fieldvalue', data['value']);
	

	if(data['type'] == 'select')
		value = extraData;
	else
		value = data['value'];


	/* update all class field text */
	$.each(fieldObj, function(i, field){
		var nodeType = $(field).prop('nodeName');
		if(nodeType == 'TD' || nodeType == 'H4')
			$(field).html(value);
		else if(nodeType == 'P' && data['type']=='color')
			$(field).css('background-color',value);
		else
			$(field).html(value);
	})
}
/* End: update edit field value */

/* Start: prepare multiselect data */
function prepMultiSelect($field) {
	var val = $field.val();
	if(val != null)
		return val;
	return '';
}
/* End: prepare multiselect data */

function selectedText(form, $type){

	if($type == 'multiple'){
		var arr = [];
		$.each(form.find('select.selectfield option:selected'), function(i, field){
			arr.push($(field).text());
		});
		return arr;
	}
	else{
		return form.find('select.selectfield option:selected').text();
	}
}

$(document).ready(function(){
	/* Start: Edit modal open after field created */
	$('.show-edit-modal').click(function(e){
		e.preventDefault();

		var $this = $(this), 
			modal = $('#editFieldFromViewModal'),
			form = modal.find('form'),
			formGroup = form.find('.form-group'),
			appendField = form.find('#field-area'),
			fieldValue = $this.data('fieldvalue'),
			log  =  $this.data('log'),
			field = $this.data('field'),
			label = $this.data('label');

			/* get field name and type */
			var combineData = field.split('-'),
				fieldName = combineData[1],
				fieldtype = combineData[2];
			
			/* Set field value in modal form */
			form.find('[name="log"]').val(log);
			formGroup.find('label').attr('for',fieldName).text(label);

			/* Create dynamic field according to given data */
			var html = '';
			if(fieldtype == 'text'){
				html = '<input type="text" value="'+fieldValue+'" name="'+fieldName+'" class="form-control editValueField" required="required">';
				appendField.append(html);
			}
			else if(fieldtype == 'number'){
				html = '<input type="number" value="'+fieldValue+'" name="'+fieldName+'" class="form-control price-field editValueField" required="required">';
				appendField.append(html);
			}
			else if(fieldtype == 'textarea' && log == 'ckeditor'){
				html = '<textarea name="'+fieldName+'" class="form-control textarea editValueField ckeditor cutomck-validation" required="required">'+fieldValue+'</textarea>';
				appendField.append(html);
				CKEDITOR.replace(fieldName);
			}
			else if(fieldtype == 'textarea'){
				html = '<textarea name="'+fieldName+'" class="form-control textarea editValueField" required="required">'+fieldValue+'</textarea>';
				appendField.append(html);
			}
			else if(fieldtype == 'select'){
				var selectFIeld = $this.find('input[name="selectData"]'),
					selectType = selectFIeld.data('select-type'),
					selectData = JSON.parse(selectFIeld.val());
				if(typeof selectType != 'undefined' && selectType == 'multiple'){
					fieldValue = fieldValue.toString();
					var selectedValue = fieldValue.split(',');
					selectedValue = $.map( selectedValue, function(val) { return parseInt(val); });
					html = '<select id="'+fieldName+'" class="form-control selectfield editValueField" name="'+fieldName+'" multiple>';
				}
				else{
					var selectedValue = fieldValue;
					html = '<select id="'+fieldName+'" class="form-control selectfield editValueField" name="'+fieldName+'">';
				}

				$.each(selectData, function(key,val){
					html += '<option value="'+key+'">'+val+'</option>';
				})
				html += '</select>';
				appendField.append(html);
				
				var dd = form.find('.selectfield');
				dd.val(selectedValue);
				dd.selectpicker('refresh');			
			}
			else if(fieldtype == 'color'){
				html = '<input type="color" value="'+fieldValue+'" name="'+fieldName+'" class="form-control editValueField" required="required">';
				appendField.append(html);
			}

			/* open modal*/
			modal.modal('show');
	});
	/* End: Edit modal open after field created */

	/* Start: save edit field in db */
	$('#editFieldFromViewModal .submit').click(function(e){
		e.preventDefault();
		var formData = {},
			modal = $('#editFieldFromViewModal'),
			form = modal.find('form'),
			log = form.find('[name="log"]').val(),
			isFormValid = form.valid();

		if(isFormValid){
			var entityType = form.find('input[name="entityType"]').val(),
				ajaxurl = public_url+entityType+'/edit-view-field',
				field = form.find('.editValueField'),
				value = '',
				type = '';

				formData['entityId'] = form.find('input[name="entityId"]').val();

				if(field.is("select") && $("select[multiple]").length){
					field = form.find('select.editValueField');
					formData[field.attr('name')] = prepMultiSelect(field);
					value = prepMultiSelect(field).join(',');
					type = 'select';
					extra = selectedText(form, 'multiple');
				}
				else if(field.is("select")){
					field = form.find('select.editValueField');
					formData[field.attr('name')] = field.val();
					value = field.val();
					type = 'select';
					extra = selectedText(form, 'single');
				}
				else if(field.is("textarea")){
					if(typeof log != 'undefined' && log == 'ckeditor'){
						var field_name = field.attr('name'),
							field_val =CKEDITOR.instances[field_name].getData();

						formData[field_name] = field_val;
						value = field_val;
					}
					else{
						formData[field.attr('name')] = field.val();
						value = field.val();
					}
					type = 'textarea';
					extra = '';
				}
				else{
					formData[field.attr('name')] = field.val();
					value = field.val();
					type = field.attr('type');
					extra = '';
				}

			var upData = {name: field.attr('name'), value: value, type: type, entity: entityType};
			
			updateEditedField(upData, extra);
			$.ajax({
                url : ajaxurl,
                type : 'POST',
                data : formData,
                success : function(response) {
                   	var data = JSON.parse(response);
                   	if(data.status == "updated"){
                    	updateEditedField(upData, extra);
                    	modal.modal('hide');
                   	}
                },

            });
		}
	})
	/* End: save edit field in db */

	/* Start: Remove existing input field */
	$('#editFieldFromViewModal').on('hide.bs.modal', function(e){
		var form = $(this).find('form'),
			field = form.find('#field-area'),
			formGroup = form.find('.form-group');
			formGroup.removeClass('has-success');
			formGroup.removeClass('has-error');
			field.empty();
	})
	/* End: Remove existing input field */
})