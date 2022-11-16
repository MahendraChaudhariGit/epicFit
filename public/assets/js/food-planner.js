$(document).ready(function(){

	/* Start: typehead for food */
	$('body').on('keyup','input[name="name"]', function(){
		foodsTypeaHead($(this));
	})
	/* End: typehead for food */

	/* Start: Get serving size data */
	$('select#serving-size').change(function(){
		var $this = $(this),
			form = $this.closest('form'), 
			toggelField = $('.food-toggel-field'),
			formData = {};

		formData['name'] = $('input[name="name"]').val();
		formData['serv_size'] = $this.val();
		if(formData['serv_size'] != '' && formData['name'] != ''){
			$.get(public_url+'meal-planner/get-food-data', formData, function(data){
				if(data != null){
					$.each(data, function(key, value){
						if(key == 'is_drink'){
							if(value == 1)
								form.find('#is_drink1').prop('checked', true);
							else
								form.find('#is_drink2').prop('checked', true);
						}
						if(key == 'description'){
							form.find('#description').val(value);
						}
						else if(key == 'food_img'){
							form.find('input[name="food_img"]').val(value);
							form.find('.previewPics').attr('src', public_url+'uploads/thumb_'+value).removeClass('hidden');
						}
						else{
							form.find('input[name="'+key+'"]').val(value);
						}
					})
				}
				else{
					form.find('#description').val("");
					form.find('input[name="food_img"]').val("");
					form.find('.previewPics').attr('src', "").addClass('hidden');
					form.find('input[name="brand"]').val("");
					form.find('input[name="supplier"]').val("");
					$('#updated-input').find(":input").val("");
				}
				toggelField.removeClass('hidden');
			},'JSON');
		}
		else{
			toggelField.addClass('hidden');
			form.find('input[name="pre_food_id"]').val("");
			form.find('input[name="pre_serv_id"]').val("");
		} 

	})
	/* End: Get serving size data */

	/* Start: Submit data from food */
	$('.submit_food').click(function(e){
		e.preventDefault();
		var form = $('#food-form'),
		formData = {},
		isFormValid = form.valid();
		
		if(isFormValid){
			$.each(form.find(':input').serializeArray(), function(i, field){
				if(field.name != 'prePhotoName' && field.name != 'entityId' && field.name != 'saveUrl' && field.name != 'photoHelper' && field.name != 'cropSelector')
					formData[field.name] = field.value;
			})

			if(formData["food_id"] != '' || (formData["pre_food_id"] != '' && formData["pre_serv_id"] == formData["serving_size"])){// edit url/mode
				if(formData["food_id"] != '')
					var foodId = formData["food_id"];
				else
					var foodId = formData["pre_food_id"];

				var url = public_url+"meal-planner/food/update/"+foodId;
			}
			else{// add url/mode
				var url = public_url+"meal-planner/food/store";
			}

			$.ajax({
				url  : url,
				type : 'POST',
				data : formData,
				success: function(response){
					var data = JSON.parse(response);
					if(data.status == 'success'){
						window.location.href = public_url+'meal-planner/food';
					}
				}
			}) 
		}
	})
	/* End: Submit data from food */

})