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

	$('body').on('click', '.removeImage', function(e){
		var row = $(this).closest('.clone-image-row');
		row.find('.help-block').remove();
		var cloneRow = row.clone();
		cloneRow.find('.removeImage').remove();
		var name = cloneRow.find('input[name="photoHelper"]').val();
		cloneRow.find('img').attr('src',public_url+'assets/images/no-image.jpg');
		cloneRow.find('input[name="prePhotoName"]').val('');
		cloneRow.find('input[name="'+name+'"]').val('');
		row.closest('.form-group').append(cloneRow);
		row.remove();
	});

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

					if($this.closest('.form-group').attr('id') == 'image-thumbnail-area'){
						$('input[name="thumbnailProgram"]').val('');
					}
					$this.remove();
			})
		})

		$('body').on('click', '.remove-video', function(e){
			e.preventDefault();
			var $this = $(this),
				preVidPath = $this.find('source').attr('src'),
				preVid = preVidPath.substring(preVidPath.lastIndexOf("/")+1);

			$.post(public_url+'remove/video', {preVid:preVid}, function(response){
				var data = JSON.parse(response);
				console.log(data.status);
				if(data.status == 'success')
				{
					$this.remove();
					$( "#video-area" ).load(window.location.href + " #video-area" );
				}
			})
		})

		$('body').on('click', '.remove-tutorial', function(e){
			e.preventDefault();
			var $this = $(this),
				preVidPath = $this.find('source').attr('src'),
				preVid = preVidPath.substring(preVidPath.lastIndexOf("/")+1);

			$.post(public_url+'remove/video', {preVid:preVid}, function(response){
				var data = JSON.parse(response);
				console.log(data.status);
				if(data.status == 'success')
				{
					$this.remove();
					$( "#tutorial-area" ).load(window.location.href + " #tutorial-area" );
				}
			})
		})

		$('body').on('click', '.remove-uploaded-video', function(e){
			e.preventDefault();
			var $this = $(this),
				preVidPath = $this.find('video').attr('src'),
				preVid = preVidPath.substring(preVidPath.lastIndexOf("/")+1);

			$.post(public_url+'remove/video', {preVid:preVid}, function(response){
				var data = JSON.parse(response);
				// console.log(data.status);
				if(data.status == 'success')
				{
					$this.remove();
					$( "#video-area" ).load(window.location.href + " #video-area" );
				}
			})
		})
	/* End: Clone image row delete */
// *Tutorail Video*
		$('body').on('click', '.remove-tutorial-video', function(e){
			e.preventDefault();
			var $this = $(this),
				preVidPath = $this.find('video').attr('src'),
				preVid = preVidPath.substring(preVidPath.lastIndexOf("/")+1);

			$.post(public_url+'remove/video', {preVid:preVid}, function(response){
				var data = JSON.parse(response);
				console.log(data.status);
				if(data.status == 'success')
				{
					$this.remove();
					$( "#tutorial-area" ).load(window.location.href + " #tutorial-area" );
				}
			})
		})


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
			var isValidName = true;
			if($('.exerciseName').closest('.form-group').hasClass('has-error')){
				isValidName = false;
			}
			if(isValidName){
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
							// console.log('name :' +name);
							// console.log( 'value : ' + elem.val() );
						if(typeof name != 'undefined'){
							if(name.indexOf("exercisePicture") >= 0 && elem.val() != "")
								isImage = true;
	
							if(name != '' && name != 'prePhotoName' && name != 'photoHelper' && name != 'saveUrl' && name != 'cropSelector' && name != 'entityId'){
								formData[name] = elem.val();
							}
						}	
					});
					if($('input[name="listing_status"]').is(':checked')){
					formData['listing_status'] = '1';
					/** Equipment Extra **/
				}
					if(isImage){
						if(formData['isClone']){
							var url = 'activity-builder/exercise/save';
						}else if(formData['exerciseId'] != ''){
							var url = 'activity-builder/exercise/update/'+formData['exerciseId'];
	
						}else{
							var url = 'activity-builder/exercise/save';	
						}
						var extraEquipment = []
						$('select.exeEquipmentClone').each(function(){
							extraEquipment.push($(this).val());
						});
						formData['equipmentextra'] = extraEquipment;
						var extraPattern = []
						$('select.exePatternClone').each(function(){
							extraPattern.push($(this).val());
						});
						formData['patternExtra'] = extraPattern;
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
$('.generate_desc').click(function()
{

    var name =$('input[name ="name"]').val();
    var equipment =$('select[name ="equipment"] option:selected').text();
    var movement_load =$('select[name ="movement_load"] option:selected').text();
    var ability =$('select[name ="ability"] option:selected').text();
     if(ability == 'Rehabilitation')
     {
     	var select = 'No';
     }
     else if(ability == 'Beginner')
     {
     	var select = 'Low';
     }
     else if(ability == 'Intermediate')
     {
     	var select = 'Moderate';
     }
     else{
     	var select = 'High';
     }
    var bodyPart = "";
    var length =$('select[name ="bodypart"] option:selected').length;
	$('select[name ="bodypart"] option:selected').each(function(key,value){

		if(key == parseInt(length)-1){
		 bodyPart += $(this).text();   
		}else{
			bodyPart += $(this).text()+", ";
		}


    });
     var equipmentextra = "";
   $('select[name ="equipmentextra"] option:selected').each(function(key,value){

		if(key == parseInt(length)-1){
		 equipmentextra += $(this).text();   
		}else{
			equipmentextra += ", "+$(this).text();
		}

    });

   var patternExtra = "";
   $('select[name ="patternExtra"] option:selected').each(function(key,value){

		if(key == parseInt(length)-1){
		 patternExtra += $(this).text();   
		}else{
			patternExtra += ", "+$(this).text();
		}

    });

	var exerciseTypeID =$('select[name ="exerciseTypeID"] option:selected').text();
	var movement_type =$('select[name ="movement_type"] option:selected').text();
	var movement_pattern =$('select[name ="movement_pattern"] option:selected').text();
    var desc ='Performing the EXERCISENAME movement with EXERCISEEQUIPMENT EXERCISEEXTRAEQUIPMENT is an MOVEMENTLOAD movement.\n\nIt is best suited for a EXERCISEABILITY level as it requires SELECT skill to achieve.\n\nIt is the perfect exercise for developing the MUSCLEGROUP , which allows for an increase in strength & improvement in range of motion.\n\nThis EXERCISETYPE movement is a MOVEMENTTYPE movement type, which comprises of a MOVEMENTPATTERN EXTRAPATTERN pattern.\n\nALWAYS PUT PROPER FORM BEFORE TEMPO OR WEIGHT.';
    var Obj = { 
            EXERCISENAME: name, 
            EXERCISEEQUIPMENT: equipment,
            EXERCISEEXTRAEQUIPMENT: equipmentextra,
            MOVEMENTLOAD: movement_load,
            EXERCISEABILITY:ability,
            SELECT:select,
            MUSCLEGROUP:bodyPart,
            EXERCISETYPE:exerciseTypeID,
            MOVEMENTTYPE:movement_type,
            MOVEMENTPATTERN:movement_pattern,
            EXTRAPATTERN:patternExtra
        };  
   innerHTML = desc.replace(/EXERCISENAME|EXERCISEEQUIPMENT|EXERCISEEXTRAEQUIPMENT|MOVEMENTLOAD|EXERCISEABILITY|SELECT|MUSCLEGROUP|EXERCISETYPE|MOVEMENTTYPE|MOVEMENTPATTERN|EXTRAPATTERN/gi, function(matched){ 
                return Obj[matched]; 
            }); 
    var text =$('textarea[name ="exerciseDesc"]').val(innerHTML); 

  
 
});

$('body').on('click','.addEquipment',function(){
	var optionHtml = '';
	$.each($(this).data('options'),function(key,value){
		optionHtml += '<option value="'+key+'">'+value+'</option>'
	});
	var html = '<div class="form-group ">\
		<label for="equipment" class="strong">Training Equipment *</label>\
		<span class="epic-tooltip tooltipstered" data-toggle="tooltip"><i class="fa fa-question-circle"></i></span>\
		<div>\
			<select class="form-control onchange-set-neutral exeEquipmentClone" title="--select--" required="required" name="equipmentextra">'+optionHtml+'</select>\
		</div>\
		<div class="removebtn">Remove</div>\
	</div>';
	$('.equipment-row').append(html);
	$('.equipment-row').find('.exeEquipmentClone').selectpicker();
});


$('body').on('click','.addPattern',function(){
	var optionHtml = '';
	$.each($(this).data('options'),function(key,value){
		optionHtml += '<option value="'+key+'">'+value+'</option>'
	});
	var html = '<div class="form-group ">\
		<label for="pattern" class="strong">Movement Pattern *</label>\
		<span class="epic-tooltip tooltipstered" data-toggle="tooltip"><i class="fa fa-question-circle"></i></span>\
		<div>\
			<select class="form-control onchange-set-neutral exePatternClone" title="--select--" required="required" name="patternExtra">'+optionHtml+'</select>\
		</div>\
		<div class="removebtn">Remove</div>\
	</div>';
	$('.pattern-row').append(html);
	$('.pattern-row').find('.exePatternClone').selectpicker();
});

$('body').on('click','.removebtn',function(){
	$(this).closest('.form-group').remove();
});

$('body').on('change','.exerciseName',function(){
	$this = $(this);
	var formData = {};
	formData['name'] = $(this).val();
	formData['exerciseId'] = $('input[name="exerciseId"]').val();
	$.get(public_url+'activity-builder/exercise/validate-exercise-name',formData,function(response){
		if(response.ifNameExist){
			setFieldInvalid($this.closest('.form-group'), 'This Exercise name has already been taken')
		}else{
			setFieldNeutral($this);
		}
	},'json');
});
