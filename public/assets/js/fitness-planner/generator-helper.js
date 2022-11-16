

$(document).ready(function(){
	/* Start: Save/Edit plan */
		/*$('#saveGenPlanBtn').click(function(e){
			e.preventDefault();
			var $this = $(this),
				formData = {},
				form = $('#generatePro-form'),
				isformValid = form.valid(),
				img = form.find('input[name="generateImage"]'),
				preImg = form.find('input[name="prePhotoName"]').val(),
				gender = form.find('input[name="genderAdmin"]:checked'),
				programGenerateField = $('#programGenerateField');

				form.find('.sucMes').children().remove().addClass('hidden');

				if(img.val() == '' && preImg == ''){
					isformValid = false;
					setFieldInvalid(img.closest('.form-group'),'Program image is required.');
				}
				
				if(gender.length <= 0){
					isformValid = false;
					setFieldInvalid($('.gender-class'),'This field is required.');
				}

				if(isformValid){
					$.each($(form).find(':input').serializeArray(), function(i, field){
						formData[field.name] = field.value
					});

					FX.setClientid(0);
		            FX.setGender(formData.genderAdmin);
					FX.setPlanType(5);

					if(formData['generateProgramId'] != '')
						var url = 'activity-builder/generate-program/update/'+formData['generateProgramId'];
					else
						var url = 'activity-builder/generate-program/save';
			 
			        $.post(public_url+url, formData, function(response){
		            	var data = JSON.parse(response);
		                if(data.status == "added"){
		                	programGenerateField.addClass('hidden');
		                  	resetTrainingSegments();
		                  	openStep($this);
		                  	FX.setClientPlanId(data.id);
		                }
		                else if(data.status == "updated"){
		                	programGenerateField.addClass('hidden');
					        FX.setClientPlanId(data.id);
					        FX.loadProgram(data.id, resetAndPopulateTrainingSegments); 
					        openStep($this);
					        //$('[data-step="trainingSegment"]').find('.panel-heading .panel-collapse').trigger('click');
		                }
		            });
				}

		})*/
	/* End : Save/Edit plan */

	$('#dataSelector').modal({
	  backdrop: 'static',
	  keyboard: false
	})

	$('#dataSelector-btn').click(function(e){
		e.preventDefault();
		var modal = $(this).closest('.modal'),
			formData = {},
			gender = modal.find('input[name="gender"]:checked').val();
		
		modal.find('.errorMsg').empty();
		if(typeof gender != 'undefined' && gender){
			if(gender == 'male')
				formData['gender'] = 2;
			else
				formData['gender'] = 1;

			$.getJSON(public_url+'activity-builder/generate-program/show', formData , function(response){
				if(response.status=='success'){
				    FX.setClientPlanId(response.clientPlanId);
				    FX.loadProgram(response.clientPlanId, resetAndPopulateTrainingSegments);
				    openStep($('#openGeneratePlan'));
				    FX.setClientid(0);
				    FX.setGender(gender);
				    FX.setPlanType(5);
				    modal.modal('hide');
				}
			})
		}
		else{
			modal.find('.errorMsg').append(prepareNotific('error','Please select gender.'));
		}
	}) 

	

	/* Start: modal close and refresh form */
	$('#generatorModal').on('hide.bs.modal', function(e){
		var form = $(this).find('form');
		console.log(form);
		clearForm(form);
	})
	/* End: modal close and refresh form */

	/* Start: Cancel Set Generate plan Filter modal data */
		$('#generator-cancel').click(function(e){
			var $this = $(this),
			modal = $this.closest('.modal');

			modal.modal('hide');
		})
	/* End: Cancel Set Generate plan Filter modal data */

	/* Start: Save Set Generate plan Filter modal data */
		$('#generator-save').click(function(e){
			var $this = $(this),
			modal = $this.closest('.modal'),
			form = modal.find('form'),
			isformValid = form.valid(),
			formData = {};

			if(isformValid){
				$.each(form.find(':input'), function(){
					var $this = $(this);
					formData[$this.attr('name')] = $this.val();
				})

				FX.addFilterToGenPlan(formData, function(response){
					if(response.status == 'success')
						modal.modal('hide');	
				});
			}
		})
	/* End: Save Set Generate plan Filter modal data */
})