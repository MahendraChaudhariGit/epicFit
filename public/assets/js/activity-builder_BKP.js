var index = 0;
$(document).ready(function(){
	/* Start: Clone image row */ 
    	$('#add-img-row').click(function(e){
    		e.preventDefault();
    		$('#exercise-form').find('.imgError').addClass('hidden').empty();
    		index++;
		    var formGroup = $('#image-area'),  
		    	row = formGroup.children().last();
		    	if(row.find('input[name="exercisePicture"]').val() != ''){
		    		row.find('.help-block').remove();
			    	cloneRow = row.clone();
			    	cloneRow.removeClass('clone-image-row');
			    	cloneRow.find('img').attr('src',public_url+'assets/images/no-image.jpg');
			    	cloneRow.find('input[name="prePhotoName"]').val('');
			    	/*cloneRow.find('input[name="entityId"]').val('');
			    	cloneRow.find('input[name="saveUrl"]').val('');
			    	cloneRow.find('input[name="photoHelper"]').val('');
			    	cloneRow.find('input[name="cropSelector"]').val('');*/
			    	cloneRow.find('input[name="exercisePicture"]').val('');

			    	row.closest('.form-group').append(cloneRow);

			    	prevRow = formGroup.children().last().prev();

			    	prevRow.removeClass('btn-file').addClass('remove-img');
			    	img = prevRow.find('input[name="exercisePicture"]').val();
			    	prevRow.find('input[name="exercisePicture"]').attr('name','expic'+index).val(img);

			    	prevRow.find('img').removeClass('previewPics').removeClass('exercisePicturePreviewPics');
			    	prevRow.find('span').html('<i class="fa fa-times"></i> Remove');

			    	prevRow.find('input[name="prePhotoName"]').remove();
			    	prevRow.find('input[name="entityId"]').remove();
			    	prevRow.find('input[name="saveUrl"]').remove();
			    	prevRow.find('input[name="photoHelper"]').remove();
			    	prevRow.find('input[name="cropSelector"]').remove();
			    }
			    else{
			    	var isError = row.find('.img-error');
			    	if(isError.length == 0)
			    		row.append('<span class="help-block img-error">Uploade image</span>');
			    }	
		});
	/* End: Clone image row */

	/* Start: Clone image row delete */ 
		$('body').on('click', '.remove-img', function(e){
			e.preventDefault();
			var $this = $(this),
				preImgPath = $this.find('img').attr('src'),
				preImg = preImgPath.substring(preImgPath.lastIndexOf("/")+1);

			$.post(public_url+'remove/image', {preImg:preImg}, function(response){
				var data = JSON.parse(response);
				console.log(data.status);
				if(data.status == 'success')
					$this.remove();
			})
		})
	/* End: Clone image row delete */

	/* Start: Remove error message */ 
		$('body').on('click', '.btn-file', function(e){
			$('#exercise-form').find('.imgError').addClass('hidden').empty();
		})
	/* End: Remove error message */

	/* Start: Modal for Add more */
		$('.added-more-item').click(function(e){
			e.preventDefault();

			var $this = $(this),
				href = $this.attr('href');

			$.get(href, function(data){
				data = JSON.parse(data);

				var modal = $('#addMoreItemModal');
				modal.find('.modal-title').text($this.data('modal-title')); //Setting title

				modal.find('.item-addEdit, .delLink').attr('href', href).data('extra', '') //Setting add/edit route

				var catType = $this.data('field');
				modal.find('input[name="field"]').val(catType) //Field class to edit/update

				var tableBody = modal.find('tbody');
				tableBody.find('tr:not(.hidden)').remove(); //Emptying table
				
				addedRowsInModal(data);
				modal.modal('show');
			});
		})

		$('body').on('click', '.item-addEdit', function(evt){	
			evt.preventDefault();
			console.log(evt);
			var $this = $(this),
				modal = $('#addMoreAddEditItem'),
				heading = modal.find('.modal-title'),
				idField = modal.find('input[name="editId"]'),
				field = modal.find('input[name="text"]');
				/*prevPicField = modal.find('[name="prePhotoName"]'),
				newPicField = modal.find("[name='categoryImage']"),
				picPreview = modal.find('.previewPics');*/

			/*prevPicField.val('')
			newPicField.val('')
			picPreview.addClass('hidden');
			$('.image-group').addClass('hidden');*/

			if(hasData($this, 'entity-id')){ //Edit Case
				heading.text('Edit');
				idField.val($this.data('entity-id'));

				var value = $this.closest('tr').children('td:first').text();
				field.val(value);

			}
			else{ //Add Case
				heading.text('Add');
				idField.val('');
				field.val('');
			}

			modal.find('form').attr('action', $this.attr('href'));
			setFieldNeutral(field);
			modal.modal("show");
		});
		
		$('#addMoreAddEditItem .submit').click(function(){
			var form = $('#addMoreAddEditItem form'),
				isFormValid = form.valid(),
				formData = {};

			if(isFormValid){
				formData.entityId = form.find('input[name="editId"]').val();
				formData.text = form.find('input[name="text"]').val(); 
				$.post(form.attr('action'), formData, function(catId){
					if(catId != 'error'){
						var fieldClass = $('#addMoreItemModal input[name="field"]').val(),
							field = $('select.'+fieldClass);

						if(!formData.entityId){ //Add Case
							var addData = {id:catId, name:formData.text};

							addedRowsInModal([addData])

							field.append('<option value="'+catId+'">'+formData.text+'</option');
						}
						else{ //Edit Case
							$('#addMore-row-'+catId).children('td:first').text(formData.text);
							field.find('option[value="'+catId+'"]').text(formData.text);
						}
						field.selectpicker('refresh');
						$('#addMoreAddEditItem').modal("hide");
					}
				});
			}
		})
	/* End: Modal for Add more */


	/* Start: Submit exercise data */
		$('.saveExerciseBtn').click(function(e){
			e.preventDefault();
			var $this = $(this),
				formData = {},
				form = $('#exercise-form'),
				isformValid = form.valid(),
				isImage = false;

			form.find('.imgError').addClass('hidden').empty();
			if(isformValid){
				$.each($(form).find(':input')/*.serializeArray()*/, function(){
					var elem = $(this),
						name = elem.attr('name');
					if(typeof name != 'undefined'){
						if(name.indexOf("exercisePicture") >= 0 && elem.val() != "")
							isImage = true;

						if(name != '' && name != 'prePhotoName' && name != 'photoHelper' && name != 'saveUrl' && name != 'cropSelector' && name != 'entityId'){
							formData[name] = elem.val();
						}
					}	
				});
				
				if(isImage){
					if(formData['exerciseId'] != ''){
						var url = 'activity-builder/exercise/update/'+formData['exerciseId'];
					}
					else{
						var url = 'activity-builder/exercise/save';
			        }
			       $.post(public_url+url, formData, function(response){
		            	var data = JSON.parse(response);
		                if(data.status == "added"){
		                    saveSuccess(form, {action:'show', type:'store'});
		                    //location.reload();
		                    window.location.href = public_url+"activity-builder/exercise"; 
		                }
		            });
			   }
			   else{
			   	form.find('.imgError').removeClass('hidden').append(prepareNotific('error','Image field is required.'));
			   }
			} 	
		})
	/* End: Submit exercise data */

	/* Start: Training segments show and hide */ 
		$('.segment-cls').change(function(){
			var $this = $(this),
				name = $this.attr('name'),
				form = $this.closest('form'),
				fieldArea =  $('#'+name+'_area'),
				errorMsgDiv = form.find('.segmentErro');

			if(!errorMsgDiv.hasClass('hidden'))
				errorMsgDiv.children().remove().addClass('hidden');

			if(this.checked){
				fieldArea.show(200);
				addVailidationOnCheckbox(fieldArea, 'add');
			}	
			else{
				fieldArea.hide(200);
				addVailidationOnCheckbox(fieldArea, 'remove');
			}
		})
	/* End: Training segments show and hide */ 

	/* Start: onload traning segment checkbox vailiadtion call */
		$.each($('#exercise-form').find('.segment-cls'), function(){
			var $this = $(this),
				name = $this.attr('name'),
				fieldArea =  $('#'+name+'_area');
			if(this.checked)
				addVailidationOnCheckbox(fieldArea, 'add');
			else
				fieldArea.hide();
		})
	/* End: onload traning segment checkbox vailiadtion call */
})

/* Start: Add vailidation on checkbox */
	function addVailidationOnCheckbox(fieldArea, condition){
		if(condition == 'add'){
			$.each(fieldArea.find(':input'), function(){
				$(this).prop('required',true);
			})
		}
		else if(condition == 'remove'){
			$.each(fieldArea.find(':input'), function(){
				$(this).prop('required',false);
				setFieldNeutral($(this));
			})
		}
	}
/* End: Add vailidation on checkbox */

/* Start: Add rmove row of image */
	function addedRowsDel(catId){
		if(catId != 'error'){
			$('#addMore-row-'+catId).remove();

			var fieldClass = $('#addMoreItemModal input[name="field"]').val(),
				field = $('select.'+fieldClass);

			field.find('option[value="'+catId+'"]').remove();
			field.selectpicker('refresh');
		}
	}
/* End: Add rmove row of image */

/* Start: Add row of image */
	function addedRowsInModal(data){
		//Adding rows to table
		if(data.length){
			var tableBody = $('#addMoreItemModal tbody'),
				dummyRow = tableBody.find('tr.hidden');
				
			$.each(data, function(key, value){

				var row = dummyRow.clone();
				row.removeClass('hidden').attr('id', 'addMore-row-'+value.id).children('td:first').text(value.name);

				var editLink = row.find('.item-addEdit');
				editLink.data('entity-id', value.id)

				var delLink = row.find('.delLink');
					delLinkHref = delLink.attr('href');
				delLink.attr('href', delLinkHref+'/'+value.id)

				row.find('.subCatImg-body').children().attr('src', public_url+'uploads/'+value.image);
			
				tableBody.append(row);
			});
		}
	}
/* End: Add row of image */
