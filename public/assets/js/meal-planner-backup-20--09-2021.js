var index = 0, rowCreated = 100000;
$(document).ready(function(){
	// $('.tags').multipleInTextbox();
	/* Initilize timepicker */
	$('.timepickerCls').timepicker();

	/* Initialize multiple text value */
	$('#tags').multipleInTextbox();

	/* Start: Submit data from food */
	$('.submit_meal').click(function(e){
		e.preventDefault();
		var isValidName = true;
	
		console.log($('.mealName').closest('.form-group').hasClass('has-error'));
		if($('.mealName').closest('.form-group').hasClass('has-error')){
			isValidName = false;
		}
		// console.log($('input[name=prep_time_hrs]').val());
		/* time validation */
		// var $inputs = $('input[name=prep_time_hrs],input[name=prep_time_mins]');
		// $inputs.on('input', function () {
		// 	var total = $('input[name=prep_time_hrs]').val().length + $('input[name=prep_time_mins]').val().length;
		// 	$inputs.not(this).prop('required', !total);
	
		// });
		//  var $inputs = $('input[name=cook_time_hrs],input[name=cook_time_mins]');
		//  if (myForm.telephone.value == '' && myForm.mobile.value == '') {
		// 	alert('You have to enter at least one phone number.');
		// 	return false;
		// }
		$('.prep-time-error').css('display','none');
		$('.cook-time-error').css('display','none');
		if($('input[name=prep_time_hrs]').val() == '' && $('input[name=prep_time_mins]').val() == '') {			
			$('.prep-time-error').css('display','block');
			$("html, body").animate({ scrollTop: 100 }, "slow");
			 return false;
		 }
		 if ($('input[name=cook_time_hrs]').val() == '' && $('input[name=cook_time_mins]').val() == '') {	
			 $('.cook-time-error').css('display','block');
			 $("html, body").animate({ scrollTop: 100 }, "slow");
			return false;
		}
	 /* end */
		if(isValidName){
			var form = $('#meal-form'),
			formData = {},
			isFormValid = form.valid(),
			isImage = false;
			form.find('.imgError').addClass('hidden').empty();
			
			/* image validate */
			// var mImage = $('input[name="main_image"]'),
			// 	tmImage = $('input[name="thumb_image"]');
		      var mImage = $('input[name="mealPicture"]'),
			      tmImage = $('input[name="thumb_image"]');
			  var edit_image =  $('input[name="mealPicture2"]');
			  console.log('edit_image ', edit_image );
			  var result = window.location.pathname.split('/');
			  var Param = result[result.length - 3];
			  var Param1 = result[result.length - 2];
			  console.log(Param, Param1);
			  if ((Param == 'meals') && (Param1 == 'edit')) {
				if(edit_image.val() == ''){
					setFieldInvalid(mImage.closest('.form-group'), 'Please upload a proper image.')
					isFormValid = false;
				 } 
			  } else {
				if(mImage.val() == ''){
					setFieldInvalid(mImage.closest('.form-group'), 'Please upload a proper image.')
					isFormValid = false;
				}
				if(tmImage.val() == ''){
					setFieldInvalid(tmImage.closest('.form-group'), 'Please upload a proper image.')
					isFormValid = false;
				}
			  }

			
		
	
			/** Validate Nutrition **/
			$('.nutritional-info').each(function(){
				if($(this).val() != '' && $(this).val() != undefined && $(this).val() != null && !$.isNumeric($(this).val())){
					setFieldInvalid($(this).closest('.form-group'), 'Please Enter a valid Value')
					isFormValid = false;
				}else{
					setFieldNeutral($(this));
				}
			});

			/* table data */
			var ingredient_table = {};
			$('.ingredient-table-tr').each(function(){
				var item_name = $(this).find('.ingredient-item').text();
				var qty = $(this).find('.ingredient-qty').text();
				console.log('food_name', item_name);
				ingredient_table[item_name+qty]={
					'qty': qty,
					'measure': $(this).find('.ingredient-measure').text(),
					'item': item_name,
					// 'item': $(this).find('.ingredient-food').text(),
					'calories': $(this).find('.ingredient-calories').text(),
					'weight': $(this).find('.ingredient-weight').text()
				}
			});
			/* ingredient */
			var ingredient_data_1 = {};
			$('.ingredient-1-div').each(function(){
                let item_name = $(this).find('.ingredient-item-1').val();
				if(item_name){
					ingredient_data_1[item_name]={
					'qty': $(this).find('.ingredient-input-1').val(),
					'measure': $(this).find('.ingredient_measurement_value_1').val(),
					'item': item_name,
			      }
				}	
			});

			var ingredient_data_2 = {};
			$('.ingredient-2-div').each(function(){
                let item_name = $(this).find('.ingredient-item-2').val();
				if(item_name){
					ingredient_data_2[item_name]={
					'qty': $(this).find('.ingredient-input-2').val(),
					'measure': $(this).find('.ingredient_measurement_value_2').val(),
					'item': item_name,
			      }
				}	
			});

			
			var preparation_data_1 = {};
			$('.preparation-1-div').each(function(){
                let data_step = $(this).find('.preparation-1').attr('data-step');
				let text = $(this).find('.preparation-1-input').val();
				console.log(data_step, 'text====', text);
				if(text){
					preparation_data_1[data_step]={
					'text': text,
			      }
				}	
			});

			var preparation_data_2 = {};
			$('.preparation-2-div').each(function(){
                let data_step = $(this).find('.preparation-2').attr('data-step');
				let text = $(this).find('.preparation-2-input').val();
				console.log(data_step, 'text====', text);
				if(text){
					preparation_data_2[data_step]={
					'text': text,
			      }
				}	
			});

          // console.log('ingredient_table==',ingredient_table);
			/* end table data */
	
			// var disc = CKEDITOR.instances.description.getData(),
			// 	method = CKEDITOR.instances.method.getData(),
			// 	tips = '';
			// 	ingredients = CKEDITOR.instances.ingredients.getData();
			// 	tips = CKEDITOR.instances.tips.getData();
			// if(!validateCKEditor(form.find('#description'), disc,true,disc.length,200))
			// 	isFormValid = false;
			// if(!validateCKEditor(form.find('#method'), method,true,method.length,850))
			// 	isFormValid = false;
			/*if(!validateCKEditor(form.find('#tips'), tips,false,tips.length,200))
				isFormValid = false;*/
			// if(!validateCKEditor(form.find('#ingredients'), ingredients,true,ingredients.length,700))
			// 	isFormValid = false;
	
			if(isFormValid){
				$.each($(form).find(':input'), function(){
					var elem = $(this),
						name = elem.attr('name');
					if(!elem.hasClass('nutritional-info')){
						if(typeof name != 'undefined'){
							if(name.indexOf("mealPicture") >= 0 && elem.val() != "")
								isImage = true;
	
							if(name != '' && name != 'prePhotoName' && name != 'photoHelper' && name != 'saveUrl' && name != 'cropSelector' && name != 'entityId'){ 
								formData[name] = elem.val();
							}
						}
					}	
				});
	             console.log('isImage', isImage);
				/** Prepare Nutrition Data **/
				var nutritionalInfo = {};
				$('.nutritional-info').each(function(){
					if($(this).val() == '' || $(this).val() == null || $(this).val() == undefined){
						nutritionalInfo[$(this).attr('name')] = '0';
					}else{
						nutritionalInfo[$(this).attr('name')] = $(this).val();
					}
				});
				if($('input[name="listing_status"]').is(':checked')){
					formData['listing_status'] = '1';
				}
				console.log(nutritionalInfo);
				if(isImage){
					let format_type = $('.reipe_format').val();
					var ingr_1 = Object.keys(ingredient_data_1).length;
					var ingr_2 = Object.keys(ingredient_data_2).length;
					var prep_1 = Object.keys(preparation_data_1).length;
					var prep_2 = Object.keys(preparation_data_2).length;
					var ingr_name_1 = $("input[name='ingredient_name_1']").val().length;
					var ingr_name_2 = $("input[name='ingredient_name_2']").val().length;
					console.log(ingr_name_1, ingr_name_2);
					switch(format_type) {
						case '1': 								
								if(ingr_1 == 0){
									
									$('.ingredient-help-block').css('display','block');
								}
								if(prep_1 == 0){
									$('.preparation-help-block').css('display','block');
								}
								if(ingr_1 == 0 || prep_1 == 0){
									$("html, body").animate({ scrollTop: 100 }, "slow");
									return false;
								}
							break;
						
						case '2': 				    
								if(ingr_1 == 0){
								  $('.ingredient-help-block').css('display','block');
								}
								if(ingr_2 == 0){
									$('.ingredient-help-block-2').css('display','block');
								  }
								if(prep_1 == 0){
								   $('.preparation-help-block').css('display','block');
								}
								if(ingr_name_1==0){
									$('.ingredient-name-1-help-block').css('display','block');
								 }
								 if(ingr_name_2==0){
									$('.ingredient-name-2-help-block').css('display','block');
								 }
								if(ingr_1 == 0 || ingr_2 == 0 || prep_1 == 0 || ingr_name_1== 0 || ingr_name_2 == 0 ){
									$("html, body").animate({ scrollTop: 100 }, "slow");
									return false;
								}
							break;
						
						case '3':					    	
								if(ingr_1 == 0){
								   $('.ingredient-help-block').css('display','block');
								}
								if(ingr_2 == 0){
									$('.ingredient-help-block-2').css('display','block');
								}
								if(prep_1 == 0){
								  $('.preparation-help-block').css('display','block');
								}
								if(prep_2 == 0){
									$('.preparation-help-block-2').css('display','block');
								  }	 
							   if(ingr_name_1 == 0){  
									$('.ingredient-name-1-help-block').css('display','block');
								 }
							   if(ingr_name_2 == 0){
									$('.ingredient-name-2-help-block').css('display','block');
								}
							   if(ingr_1 == 0 || ingr_2 == 0 || prep_1 == 0 || prep_1 == 0 || ingr_name_1== 0 || ingr_name_2 == 0 ){
							    	$("html, body").animate({ scrollTop: 100 }, "slow");
									return false;
								}
						 	break;
						
						}
			
					console.log('ingredient_data_1', ingredient_data_1);
					console.log('ingredient_data_1===', Object.keys(ingredient_data_1).length);
					console.log($('.reipe_format').val());
					console.log('ingredient_data_2', ingredient_data_2);
					console.log('preparation_data_1', preparation_data_1);
					console.log('preparation_data_2', preparation_data_2);
					console.log('ingredient_data_1trtgettt===', Object.keys(preparation_data_2).length);
					// formData['description'] = disc;
					// //formData['ingredients'] = form.find('textarea#ingredients').val();
					// formData['ingredients'] = ingredients;
					// formData['method'] = method;
					// formData['tips'] = tips;
					formData['category_id'] = prepareMultiSel(form.find('select#meal_cat'));
					formData['nutritionalInfo'] = nutritionalInfo; 
					formData['ingredient_table'] = ingredient_table; 
					formData['ingredient_data_1'] = ingredient_data_1; 
					formData['ingredient_data_2'] = ingredient_data_2; 
					formData['preparation_data_1'] = preparation_data_1; 
					formData['preparation_data_2'] = preparation_data_2; 
					console.log('formData======', formData);
					var url = form.attr('action');
					console.log('url====', url,formData);
					$.ajax({
						url  : url,
						type : 'POST',
						data : formData,
						success: function(response){
						 console.log(response);
					       var data = JSON.parse(response);
							if(data.status == 'success'){
								window.location.href = public_url+'meal-planner/meals';
							}
						}
					})
				}
				else{
					// if(!isFood)
					// 	form.find('#foodError').removeClass('hidden');
					if(!isImage){
						$("html, body").animate({ scrollTop: 1000 }, "slow");
						form.find('.imgError').removeClass('hidden').append(prepareNotific('error','Image field is required.'));
					}
					   
				}
			}else{
				$('html, body').animate({
					scrollTop: (form.find('.has-error:first').offset().top - 300)
				   }, 2000);
				form.find('.has-error:first').focus();
			}
		}
	})
	/* End: Submit data from food */

	/* Start: Clone image row */ 
	$('#add-img-row').click(function(e){
		e.preventDefault();
		$('#meal-form').find('.imgError').addClass('hidden').empty();
		index++;
	    var formGroup = $('#image-area'),  
	    	row = formGroup.children().last();
	    	if(row.find('input[name="mealPicture"]').val() != ''){
	    		row.find('.help-block').remove();
		    	cloneRow = row.clone();
		    	cloneRow.removeClass('clone-image-row');
		    	cloneRow.find('img').attr('src',public_url+'assets/images/no-image.jpg');
		    	cloneRow.find('input[name="prePhotoName"]').val('');
		    	cloneRow.find('input[name="mealPicture"]').val('');

		    	row.closest('.form-group').append(cloneRow);

		    	prevRow = formGroup.children().last().prev();

		    	prevRow.removeClass('btn-file').addClass('remove-img');
		    	img = prevRow.find('input[name="mealPicture"]').val();
		    	prevRow.find('input[name="mealPicture"]').attr('name','mealpic'+index).val(img);

		    	prevRow.find('img').removeClass('previewPics').removeClass('mealPicturePreviewPics');
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
		var checkstr =  confirm('are you sure you want to delete this?');
		if(checkstr){
			
		var $this = $(this),
			preImgPath = $this.find('img').attr('src'),
			preImg = preImgPath.substring(preImgPath.lastIndexOf("/")+1);

		$.post(public_url+'meal-planner/remove/image', {preImg:preImg}, function(response){
			var data = JSON.parse(response);
			if(data.status == 'success')
				$this.remove();
				$('.edit-image-div').css('display','block');
		})
	  } else {
		  return false;
	  }
	})

	$('body').on('click', '.removeImage', function(e){
		var checkstr =  confirm('are you sure you want to delete this?');
		if(checkstr){
	      let scr_val = $('.mealPicturePreviewPics').attr('data-src');
		  $('.mealPicturePreviewPics').attr('src', scr_val);
	      $('input[name="mealPicture"]').val('');
		  $('input[name="prePhotoName"]').val('');
		  $('.remove-preview').remove();
		  $('.image-uploder-label-style').css('display','block');
		   let image_input = $('input[type="file"]');
		    image_input.wrap('<form>').closest('form').get(0).reset();
		//   e.unwrap();
		
		}else{
		return false;
		}	 
	})
	
	$('body').on('click', '.model-cancel-btn', function(e){
		   let image_input = $('input[type="file"]');
		    image_input.wrap('<form>').closest('form').get(0).reset();
		//   e.unwrap();
	})
	
	/* End: Clone image row delete */

	/* Start: Remove error message */ 
	$('body').on('click', '.btn-file', function(e){
		$('#meal-form').find('.imgError').addClass('hidden').empty();
	})
	/* End: Remove error message */

})

/* Start: Ck editor custom validator */
function validateCKEditor($field, $value,isRequired = true,length = 0,maxLength = 0){
	var formGroup = $field.closest('.form-group');
	formGroup.find('.ckErrorCustom').remove();
	if($value == null || $value == ""){
		if(isRequired){
			if(formGroup.hasClass('has-success'))
				formGroup.removeClass('has-success');
				$('.error-message').css('display','block');
				$('.error-message').html('This field is required.');
			 	// formGroup.addClass('has-error');
				// formGroup.append('<span class="ckErrorCustom">This field is required.</span>');
			return false;
		}else{
			$('.error-message').css('display','error')
			formGroup.removeClass('has-error');
			formGroup.find('.ckErrorCustom').remove();
			return true;
		}		
	}
	else{
		if(length > maxLength){
			if(formGroup.hasClass('has-success'))
				formGroup.removeClass('has-success')

			formGroup.addClass('has-error');
			formGroup.append('<span class="ckErrorCustom">Content limit exceeded.</span>');
			return false;
		}
		formGroup.removeClass('has-error');
		formGroup.find('.ckErrorCustom').remove();
		return true;
	}
}
/* End: Ck editor custom validator */

/* Start:Meal Deatil Modal */
$('body').on('click','.viewModal', function () {
   var mealId = $(this).data('id');
   console.log(mealId);
   toggleWaitShield("show");
	$.ajax({
		url : public_url+'meal-planner/meal/'+mealId,
		type : 'GET',
		data : {},
		success : function(response) {
	        toggleWaitShield("hide");
	        if(response.status == 'ok'){
	        	var mealData = response.mealData;
	        	$('#recipeTitle').text(mealData.name);
	        	$('#mealImage').attr('src',mealData.image);
	        	$('.description_data').html(mealData.description);
	        	$('#preparationData').html(mealData.method);
	        	$('#tipsData').html(mealData.tips);
	        	$('#ingredientPara').html(mealData.ingredients);
	        	$('#preprationTime').text(mealData.prepration_time);
	        	$('#servingSize').text(mealData.serving_size);
	        	var nutritionInfo = mealData.nutritional_information;
	        	$('#calories').text(nutritionInfo.energ_kcal);
	        	$('#nutriData').html('<table class="bottom-table"><tr><th><span>Fat</span>:</th><th><span>Saturated Fat</span>:</th><th><span>Sugar</span>:</th><th><span>Carbohydrate</span>:</th><th><span>Sodium</span>:</th><th><span>Fiber</span>:</th><th><span>Protein</span>:</th><th><span>Cholesterol</span>:</td></tr><tr><td>  <span>'+nutritionInfo.fat+'</span> g</td><td>  <span>'+nutritionInfo.fa_sat+'</span> g</td><td>  <span>'+nutritionInfo.sugar+'</span> g</td><td>  <span>'+nutritionInfo.carbohydrate+'</span> g</td><td>  <span>'+nutritionInfo.sodium+'</span> mg</td><td>  <span>'+nutritionInfo.sugar+'</span> g</td><td>  <span>'+nutritionInfo.protein+'</span> g</td><td>  <span>'+nutritionInfo.cholesterol+'</span> mg</td></tr></table>');
	        	$('#viewmealsmodal').modal('show');

	        }
	    },
	});
})
/* End:Meal Deatil Modal */

/**
 * Validate Meal Name
 */
$('body').on('change','.mealName',function(){
	$this = $(this);
	var formData = {};
	formData['name'] = $(this).val();
	formData['mealId'] = $('input[name="meal_id"]').val();
	$.get(public_url+'meal-planner/validate-meal-name',formData,function(response){
		if(response.ifNameExist){
			setFieldInvalid($this.closest('.form-group'), 'This Recipe name has already been taken')
		}else{
			setFieldNeutral($this);
		}
	},'json');
});




	$('.analyze_data').click(function(){
		$('.ingredient-help-block').css('display','none');
		var title= $("#name").val(),
	       count_1 = $('.ingredient-input-1').length,
		   count_2 = $('.ingredient-input-2').length;
		    // ingr = $("#ingredients").val().split(/\n|\r/);
		if($('.reipe_format').val() == 1){
			count_2 = 0;
		  }
		var newInge =[];
		console.log(count_1, count_2);
		for(i=0; i < count_1; i++){
			var item_val_first = $("input[name='ingredient_item_1["+0+"]']").val();
			if($.trim(item_val_first) != null && $.trim(item_val_first) != ''){
			var item_val = $("input[name='ingredient_item_1["+i+"]']").val();
			if($.trim(item_val) != null && $.trim(item_val) != ''){
				let ingr_val = '';
				ingr_val +=	$("input[name='ingredient_quantity_1["+i+"]']").val();
				ingr_val += ' ';
				ingr_val += $("input[name='ingredient_measurement_value_1["+i+"]']").val();
				ingr_val += ' ';
				ingr_val += $("input[name='ingredient_item_1["+i+"]']").val();
				var trimStr = $.trim(ingr_val);
				if(trimStr != '' && trimStr != null){
					newInge.push(trimStr);
			 	}
		      }
		    } else{

				$('.ingredient-help-block').css('display','block');
				return false;
			}

            
	     }
		 console.log('newInge1==', newInge);
		 for(j=0; j < count_2; j++){
			var item_val = $("input[name='ingredient_item_2["+j+"]']").val();
			if($.trim(item_val) != null && $.trim(item_val) != ''){
				let ingr_val = '';
				ingr_val +=	$("input[name='ingredient_quantity_2["+j+"]']").val();
				ingr_val += ' ';
				ingr_val +=  $("input[name='ingredient_measurement_value_2["+j+"]']").val();
				ingr_val += ' ';
				ingr_val += $("input[name='ingredient_item_2["+j+"]']").val();
				var trimStr = $.trim(ingr_val);
				if(trimStr != '' && trimStr != null){
					newInge.push(trimStr);
				}
			}
           
	     }
	     console.log('newInge==', newInge);
		// console.log(ingr);
		// var newInge =[];
		// $.each(ingr,function(key,value){
		// 	newValue= value.trim();
		// 	data = removeTags(newValue);
		// 	if(data != "" && data != false){
		// 	newInge.push(data);
		// 	}
		// })
		var yield = $('#serves').val();
	  $.post(public_url+'meal-planner/ingredients', {title:title,ingr:newInge,yield:yield}, function(data){
		  console.log('data==', data);
		 
		  if ($.isEmptyObject(data.totalNutrients)) {
		    	$('#energ_kcal').val(0);
				$('#fat').val(0);
				$('#fa_sat').val(0);
				$('#carbohydrate').val(0);
				$('#sugar').val(0);
				$('#sodium').val(0);
				$('#fiber').val(0);
				$('#protein').val(0);
				$('#cholesterol').val(0);
				$('#vitamin').val(0);
			    $('#calcium').val(0);
				$('#iron').val(0);
				$('#potassium').val(0);
				$('#trans_fat').val(0);
				/* ---- */
			} else{
				var ingredient_list = data.ingredients;
				$(".meallist .ingredient-table-tr").remove(); 
				//  $('.meallist').css('display','block')
				$.each(ingredient_list, function( index, value ) {
					// console.log( index + ": " + value.parsed[0].food );
					if(value.parsed){
						var calories = parseFloat(value.parsed[0].nutrients.ENERC_KCAL.quantity).toFixed(2);
						var weight =  parseFloat(value.parsed[0].weight).toFixed(2);
					$('.meallist > tbody:last-child').append(
						'<tr class="ingredient-table-tr">'// need to change closing tag to an opening `<tr>` tag.
						+'<td class="ingredient-qty">'+value.parsed[0].quantity+'</td>'
						+'<td class="ingredient-measure">'+value.parsed[0].measure+'</td>'
						+'<td class="ingredient-item">'+value.parsed[0].foodMatch+'</td>'
						+'<td class="ingredient-calories">'+calories+'</td>'
						+'<td class="ingredient-weight">'+weight+'</td>'
						+'</tr>');

					}
					
				  });
			}
			/*  new */

		if ($.isEmptyObject(data.totalNutrientsKCal)) {


	   	 }else{
			
		   if (typeof(data.totalNutrientsKCal.ENERC_KCAL) != "undefined") {
				var toatl_cal = Math.round(data.totalNutrientsKCal.ENERC_KCAL.quantity);
				$('#total_energ_kcal').attr('value',toatl_cal);
			} 
			if ( typeof(data.totalNutrientsKCal.CHOCDF_KCAL) != "undefined") {
				var toatl_carbohydrates = Math.round(data.totalNutrientsKCal.CHOCDF_KCAL.quantity);
				$('#cal_from_carbohydrates').attr('value',toatl_carbohydrates);
			} 
			if (typeof(data.totalNutrientsKCal.FAT_KCAL) != "undefined") {
				var toatl_fat = Math.round(data.totalNutrientsKCal.FAT_KCAL.quantity);
				$('#cal_from_fat').attr('value',toatl_fat);
			} 
			if (typeof(data.totalNutrientsKCal.PROCNT_KCAL) != "undefined") {
				var toatl_protein = Math.round(data.totalNutrientsKCal.PROCNT_KCAL.quantity);
				$('#cal_from_protein').attr('value',toatl_protein);
			} 
		}
			/*  new */

		if (typeof(data.totalNutrients.ENERC_KCAL) != "undefined") {
				totalCal = Math.round(data.totalNutrients.ENERC_KCAL.quantity);
				$('#energ_kcal').val(totalCal);
			} 
		if (typeof(data.totalNutrients.FAT) != "undefined") {
			FAT = Math.round(data.totalNutrients.FAT.quantity*10)/10;
			$('#fat').val(FAT);
		}
		if (typeof(data.totalNutrients.FASAT) != "undefined") {
			FASAT = Math.round(data.totalNutrients.FASAT.quantity*10)/10;
			$('#fa_sat').val(FASAT);
		}
		// if (typeof(data.totalNutrients.FATRN) != "undefined") {
		// 	FATRN = Math.round(data.totalNutrients.FATRN.quantity*10)/10+' '+data.totalNutrients.FATRN.unit;
		// 	$('#fa_sat').val();
		// }
		if (typeof(data.totalNutrients.CHOCDF) != "undefined") {
			CHOCDF = Math.round(data.totalNutrients.CHOCDF.quantity*10)/10;
			$('#carbohydrate').val(CHOCDF);
		} 
		if (typeof(data.totalNutrients.SUGAR) != "undefined") {
			SUGAR = Math.round(data.totalNutrients.SUGAR.quantity*10)/10;
			$('#sugar').val(SUGAR);
		}
		if (typeof(data.totalNutrients.NA) != "undefined") {
			NA = Math.round(data.totalNutrients.NA.quantity*10)/10;
			$('#sodium').val(NA);
		} 
		if (typeof(data.totalNutrients.FIBTG) != "undefined") {
			FIBTG = Math.round(data.totalNutrients.FIBTG.quantity*10)/10;
			$('#fiber').val(FIBTG);
		} 
		if (typeof(data.totalNutrients.PROCNT) != "undefined") {
			PROCNT = Math.round(data.totalNutrients.PROCNT.quantity*10)/10;
			$('#protein').val(PROCNT);
		}
		if (typeof(data.totalNutrients.CHOLE) != "undefined") {
			CHOLE = Math.round(data.totalNutrients.CHOLE.quantity*10)/10;
			$('#cholesterol').val(CHOLE);
		}
		if (typeof(data.totalNutrients.VITD) != "undefined") {
			VITD = Math.round(data.totalNutrients.VITD.quantity*10)/10;
			$('#vitamin').val(VITD);
		} 
		if (typeof(data.totalNutrients.CA) != "undefined") {
			CA = Math.round(data.totalNutrients.CA.quantity*10)/10;
			$('#calcium').val(CA);
		}
		if (typeof(data.totalNutrients.FE) != "undefined") {
			FE = Math.round(data.totalNutrients.FE.quantity*10)/10;
			$('#iron').val(FE);
		} 
		if (typeof(data.totalNutrients.K) != "undefined") {
			K = Math.round(data.totalNutrients.K.quantity*10)/10;
			$('#potassium').val(K);
		}
		if (typeof(data.totalNutrients.FATRN) != "undefined") {
			FATRN = Math.round(data.totalNutrients.FATRN.quantity*10)/10;
			console.log(FATRN);
			$('#trans_fat').val(FATRN);
		}
		

		})
		
	});

	function removeTags(str) { 
		if ((str===null) || (str==='')) 
			return false; 
		else
			str = str.toString(); 

		// Regular expression to identify HTML tags in  
		// the input string. Replacing the identified  
		// HTML tag with a null string. 
		return str.replace( /(<([^>]+)>)/ig, ''); 
	} 
 
	$('#updateNutritionServe').on('click',function(){
		var serves = $('#serves').val();
		console.log(serves);
		$('#updated-value').removeAttr('style');
		// $("#updateNutritionServe").attr('style','display:none;');
		$('#energ_kcal_serve').val($('#energ_kcal').val()/serves);
		$('#fat_serve').val($('#fat').val()/serves);
		$('#fa_sat_serve').val($('#fa_sat').val()/serves);
		$('#carbohydrate_serve').val($('#carbohydrate').val()/serves);
		$('#sugar_serve').val($('#sugar').val()/serves);
		$('#sodium_serve').val($('#sodium').val()/serves);
		$('#fiber_serve').val($('#fiber').val()/serves);
		$('#protein_serve').val($('#protein').val()/serves);
		$('#cholesterol_serve').val($('#cholesterol').val()/serves);
		$('#vitamin_serve').val($('#vitamin').val()/serves);
		$('#calcium_serve').val($('#calcium').val()/serves);
		$('#iron_serve').val($('#iron').val()/serves);
		$('#potassium_serve').val($('#potassium').val()/serves);
		$('#trans_fat_serve').val($('#trans_fat').val()/serves);

	})