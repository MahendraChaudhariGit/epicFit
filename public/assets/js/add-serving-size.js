$(function() {
    /* Start: Modal for Add more */
	$('.add-serving').click(function(e){
		e.preventDefault();

		var $this = $(this),
			href = $this.attr('href');

		$.get(href, function(data){
			data = JSON.parse(data);

			var modal = $('#addServingSize');
			modal.find('.modal-title').text($this.data('modal-title')); //Setting title

			modal.find('.addEdit-servingSize, .delLink').attr('href', href).data('extra', '') //Setting add/edit route

			var catType = $this.data('field');
			modal.find('input[name="field"]').val(catType) //Field class to edit/update

			var tableBody = modal.find('tbody');
			tableBody.find('tr:not(.hidden)').remove(); //Emptying table
			addServingSizeRow(data)
			modal.modal('show');
		});
	})

	$('body').on('click', '.addEdit-servingSize', function(e){	
		e.preventDefault();

		var $this = $(this),
			row = $this.closest('tr'),
			modal = $('#addMoreServingSize'),
			heading = modal.find('.modal-title'),
			idField = modal.find('input[name="editId"]'),
			field = modal.find('input[name="text"]'),
			otherField = modal.find('input[name="other"]'),
			servingsizeField = modal.find('input[name="servingsize"]'),
			servingquantField = modal.find('input[name="servingquant"]'),
			servingtagsField = modal.find('input[name="servingTags"]');
			unitsField = modal.find('input[name="units"]');

		if(hasData($this, 'entity-id')){ //Edit Case
			heading.text('Edit');
			idField.val($this.data('entity-id'));

			field.val(trimSymbole(row.find('.name').text()));
			servingsizeField.val(trimSymbole(row.find('.size').text()));
			servingquantField.val(trimSymbole(row.find('.quantity').text()));
			otherField.val(trimSymbole(row.find('.other').text()));
			unitsField.val(trimSymbole(row.find('.units').text()));
			servingtagsField.val(trimSymbole(row.data('tags')));
		}
		else{ //Add Case
			heading.text('Add');
			idField.val('');
			field.val('');
			servingsizeField.val('');
			servingquantField.val('');
			otherField.val('');
			servingtagsField.val('');
			unitsField.val('');
		}

		modal.find('form').attr('action', $this.attr('href'));
		setFieldNeutral(field);
		setFieldNeutral(servingsizeField);
		setFieldNeutral(servingquantField);
		modal.modal("show");
	});

	$('#addMoreServingSize .submit').click(function(){
		var form = $('#addMoreServingSize form'),
			isFormValid = form.valid(),
			formData = {};

		if(isFormValid){
			formData.entityId = form.find('input[name="editId"]').val();
			formData.text = form.find('input[name="text"]').val();
			formData.other = form.find('input[name="other"]').val();
			formData.size = form.find('input[name="servingsize"]').val();
			formData.quantity = form.find('input[name="servingquant"]').val();
			formData.tags = form.find('input[name="servingTags"]').val(); 
			formData.units = form.find('input[name="units"]').val();
			

			$.post(form.attr('action'), formData, function(catId){
				if(catId != 'error'){
					var fieldClass = $('#addServingSize input[name="field"]').val(),
						field = $('select.'+fieldClass);

					if(!formData.entityId){ //Add Case
						var addData = {id:catId, name:formData.text, size:formData.size, quantity:formData.quantity, other:formData.other, tags:formData.tags, units:formData.units};

						addServingSizeRow([addData])
						field.append('<option value="'+catId+'">'+formData.size +' '+ formData.text+'</option');
					}
					else{ //Edit Case
						var row = $('#addMore-row-'+catId);
						field.find('option[value="'+catId+'"]').text(formData.size +' '+ formData.text);
						row.find('.name').text(formData.text);
						row.find('.size').text(formData.size);
						row.find('.quantity').text(formData.quantity);
						row.find('.other').text(formData.other);
						row.find('.units').text(formData.units);
						row.data('tags', formData.tags);
						
						
						if(formData.tags != '')
							var tagsName = formData.tags;
						else
							var tagsName = (formData.text).toLowerCase();

						row.data('servtags', tagsName);
					}
					$('#addMoreServingSize').modal("hide");
				}
			});
		}
	})
});

function addMoreDel(catId){
	if(catId != 'error'){
		$('#addMore-row-'+catId).remove();

		var fieldClass = $('#addServingSize input[name="field"]').val(),
			field = $('select.'+fieldClass);
		field.find('option[value="'+catId+'"]').remove();
		field.selectpicker('refresh');
	}
}

function addServingSizeRow(data){
	//Adding rows to table
	if(data.length){
		var tableBody = $('#addServingSize tbody'),
			dummyRow = tableBody.find('tr.hidden');
			
		$.each(data, function(key, value){
			var row = dummyRow.clone();
			row.removeClass('hidden').attr('id', 'addMore-row-'+value.id);

			var editLink = row.find('.addEdit-servingSize');
			editLink.data('entity-id', value.id)

			var delLink = row.find('.delLink');
				delLinkHref = delLink.attr('href');
			delLink.attr('href', delLinkHref+'/'+value.id)
			
			row.find('.name').html((value.name)?value.name:'------');
			row.find('.size').html((value.size)?value.size:'------');
			row.find('.quantity').html((value.quantity)?value.quantity:'------');
			row.find('.other').html((value.other)?value.other:'------');
			row.find('.units').html((value.units)?value.units:'------');
			row.data('tags', value.tags);
			row.data('id', value.id);
			tableBody.append(row);
		});
	}
}

function trimSymbole(text){
	if(text == '------')
		return '';
	else
		return text.trim();

}