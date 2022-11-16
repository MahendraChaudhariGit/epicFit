var filterName = {};
var serv_size = 1;
var defalutNutraData = {};

/* Scroll window according to height */
function customScrollWindow(height){
	$("html, body").animate({
	    scrollTop: height
	}, "slow");
}

/* Change textarea to div with food value */
function displayFoodData(status, data, servSize){
	var edit = $('#form-group-edit'),
		create = $('#form-group-create'),
		html = "";

	if(status == 'success'){
		calcNutrationData(data, function(nutrData){
			displayNutrationData(nutrData);
		});
	}
	else{
		$('#nutrition-result-area').hide();
	}

	html = '<table class="table table-bordered"><tbody>';
	$.each(data, function(key, value){
		if('name' in value){
			var name = value.name,
				size = value.serving_size,
				servId = value.serving_id;
		}
		else{
			var name = "",
				size = "",
				servId = "";
		}

		var servoption = '<option value="">-- Select --</option>';
		if(Object.keys(servSize).length != -1){
			$.each(servSize, function(key, name){
				if(key == servId)
					servoption += '<option value="'+key+'" selected>'+name+'</option>';
				else
					servoption += '<option value="'+key+'">'+name+'</option>';
			});
		}

		html += '<tr data-id="'+value.id+'" data-dispname="'+$.trim(value.key)+'">\
        			<td style="border-right:none;">\
	        			<div class="col-md-12 food-header-text"><i class="fa fa-quote-left quote-font"></i> '+value.display_name+' <i class="fa fa-quote-right quote-font"></i></div>';
	        			if('name' in value){
	        				html += '<div class="col-md-12 food-text detail-area">'+value.name+' &nbsp;|&nbsp; '+value.energy+' Calories per serving</div>';
	        			}
	        			else{
	        				html += '<div class="col-md-12"><div class="alert alert-warning custom-alert"> <i class="fa fa-exclamation-circle fa-inverse fa-2x" style="float:left;"></i> &nbsp;&nbsp;'+value.msg+'</div></div>';
	        			}

        		html +='<div class="col-md-12 custom-col edit-area-cls">\
        					<div class="col-md-5">\
	        					<input name="food_name" class="form-control food_name" type="text" value="'+name+'">\
	        				</div>\
	        				<div class="col-md-3">\
	        					<input  name="size" class="form-control size" type="text" value="'+size+'">\
	        				</div>\
	        				<div class="col-md-4">\
	        					<select name="serv_size" class="form-control serv_size">'+servoption+'<select>\
	        				</div>\
	        			</div>\
	        			<div class="col-md-12 edit-area-cls">\
	        				<a href="#" class="btn btn-default remove-current-row"> DELETE </a>\
	        				<a href="#" class="btn btn-primary update-current-row"> UPDATE </a>\
	        			</div>\
        			</td>\
        			<td>\
	        			<div class="col-md-1 col-xs-1" >\
	        				<a href="#" class="btn btn-primary btn-xs food-edit-open pull-right" ><i class="fa fa-edit"></i></a>\
	        				<a href="#" class="btn btn-primary btn-xs food-edit-close pull-right" style="display:none;"><i class="fa fa-times"></i></a>\
	        			</div>\
        			</td>\
      			</tr>';	
	});
	html += '<tr>\
  				<td colspan="2">\
  					<div class="col-md-12">\
					  	<div class="input-group add-food-area">\
						    <input type="text" class="form-control" placeholder="1/2 cup chicken stock" name="new_food">\
						    <div class="input-group-btn">\
						      <a href="#" class="btn btn-primary add-extra-food">Done</a>\
						    </div>\
					  	</div>\
  						<a href="#" class="add-food-btn"> <i class="fa fa-plus"></i> Add food</a>\
  					</div>\
  				</td>\
  			</tr></tbody></table>';

	edit.empty();
	edit.append(html);
	create.hide();
	edit.show();
	toggleWaitShield("hide");

	nutrationalData = '';
	$.each(filterName, function(key, value){
		console.log(value);
		nutrationalData += value.name+'\n'; 
	})
	console.log(nutrationalData);
}

/* Calculate nuration from row data */
function calcNutrationData(data, callback){
	var nuratData = {'energy':0,'fa_mono':0,'fa_poly':0,'fa_sat':0,'fiber':0,'iron':0,'potassium':0,'protein':0,'sugar':0,'vit_dmcg':0,'cholestrl':0,'carbohydrate':0,'calcium':0,'sodium':0};

	$.each(data, function(key, value){
		nuratData['energy'] += value.energy;
		nuratData['fa_mono'] += value.fa_mono;
		nuratData['fa_poly'] += value.fa_poly;
		nuratData['fa_sat'] += value.fa_sat;
		nuratData['fiber'] += value.fiber;
		nuratData['iron'] += value.iron;
		nuratData['potassium'] += value.potassium;
		nuratData['protein'] += value.protein;
		nuratData['sugar'] += value.sugar;
		nuratData['vit_dmcg'] += value.vit_dmcg;
		nuratData['cholestrl'] += value.cholestrl;
		nuratData['carbohydrate'] += value.carbohydrate;
		nuratData['calcium'] += value.calcium;
		nuratData['sodium'] += value.sodium;
	})

	defalutNutraData = nuratData;
	if(typeof callback != 'undefined')
		callback(nuratData);
}

/* display nutrational data */
function displayNutrationData(data){
	if(typeof data == 'undefined' || data == '')
		data = defalutNutraData;

	$('#nutrition-result-area').show();
	var nutrFieldset = $('#nutrition-info'),
		calories = ((data.energy) / serv_size).toFixed(0),
		totalFat = ((data.fa_mono + data.fa_poly) / serv_size).toFixed(1),
		saturFat = ((data.fa_sat) / serv_size).toFixed(1),
		cholesterol = ((data.cholestrl) / serv_size).toFixed(1),
		sodium = ((data.potassium) / serv_size).toFixed(1),
		carbohydrate = ((data.carbohydrate) / serv_size).toFixed(1),
		fiber = ((data.fiber) / serv_size).toFixed(1),
		sugar = ((data.sugar) / serv_size).toFixed(1),
		protein = ((data.protein) / serv_size).toFixed(1),
		vitaminD = ((data.vit_dmcg) / serv_size).toFixed(0),
		calcium = ((data.calcium) / serv_size).toFixed(0),
		iron = ((data.iron) / serv_size).toFixed(0),
		potassium = ((data.potassium) / serv_size).toFixed(0);
		
	$('.caloriesCls').text(calories);
	nutrFieldset.find('#servingId').text(serv_size);

	nutrFieldset.find('#totalFatNum').text(totalFat + 'g');
	nutrFieldset.find('#saturFatNum').text(saturFat + 'g');
	nutrFieldset.find('#cholesterolNum').text(cholesterol + 'mg');
	nutrFieldset.find('#sodiumNum').text(sodium + 'mg');
	nutrFieldset.find('#carbohydrateNum').text(carbohydrate + 'g');
	nutrFieldset.find('#fiberNum').text(fiber + 'g');
	nutrFieldset.find('#sugarNum').text(sugar + 'g');
	nutrFieldset.find('#proteinNum').text(protein + 'g');
	nutrFieldset.find('#vitaminDNum').text(vitaminD + 'mcg');
	nutrFieldset.find('#calciumNum').text(calcium + 'mg');
	nutrFieldset.find('#ironNum').text(iron + 'mg');
	nutrFieldset.find('#potassiumNum').text(potassium + 'mg');	
}

/* reset text area */
function resetTextArea(){
	var edit = $('#form-group-edit'),
		create = $('#form-group-create');

	$('#cal-nut-btn-submit').show();
	$('#nutrition-result-area').hide();
	$('.refresh-textarea-one').hide();
	$('textarea#tool-name').val("");
	$('select#serving').val("").selectpicker("refresh");
	edit.empty();
	create.show();
	edit.hide();
	filterName = {};
	defalutNutraData = {};
}

/* Type head for food */

function typeHeading(self) {
	var productNames = new Array();
	var productIds = new Object();
	var queryText = self.val();
    $.getJSON( public_url+'meal-planner/tools/foods', {text: queryText}, function (jsonData){
        $.each(jsonData, function (index, product ){
            productNames.push( product.name );
            productIds[product.name] = product.id;
        });
     	self.typeahead({ 
            source : productNames,
            afterSelect:function(selection){ 
                //self.find('.idfield').val(selection);
            }
        });
    });
}

$(document).ready(function(){
	nutrationalData = $('textarea#tool-name').val();

	/* scroll on top */
	$('.scroll-on-top').click(function(e) {
		e.preventDefault();
		customScrollWindow(0);
	});

	/* Refresh text area */
	$('.refresh-textarea, .refresh-textarea-one').click(function(e){
		e.preventDefault();
		var $this = $(this),
			icon = $this.find('.fa-refresh');

		icon.addClass('fa-spin');
		resetTextArea();
		icon.removeClass('fa-spin');
		customScrollWindow(0);
		
	})

	/* calculation nutration */
	$('#cal-nut-btn-submit').click(function(e){
		e.preventDefault();
		var offset = $('#nutrition-result-area').offset().top,
			form = $('#meal-cal-form'),
			$this = $(this);

		filterName = {};
		serv_size = parseInt(form.find('select#serving').val());

		var name = form.find('textarea#tool-name').val(),
		foodname = name.split("\n");
		var k = 0;
		$.each(foodname, function(i, value){
			if(value != ''){
				filterName['key'+k] = {name: $.trim(value), servid:0};
				k++;
			}
		})

		if(Object.keys(filterName).length > 0){
			//toggleWaitShield("show");
			$.ajax({
				url  : public_url+'meal-planner/tools/nutrition', 
				data : filterName,
				type : 'POST', 
				success: function(response){
					var data = JSON.parse(response);
					$('.refresh-textarea-one').show();
					$this.hide();
					displayFoodData(data.status, data.food, data.sevrSize);
				}
			})
			customScrollWindow(offset - 50);
		}
	});

	/* add extra food and get information */
	$('body').on('click', '.add-extra-food', function(e){
		e.preventDefault();
		var $this = $(this),
			offset = $('#nutrition-result-area').offset().top,
			newFood = $('input[name="new_food"]').val(),
			length = Object.keys(filterName).length;

		if(newFood != ''){
			//toggleWaitShield("show");
			filterName['key'+length] = {name: $.trim(newFood), servid:0}; 
			$.ajax({
				url  : public_url+'meal-planner/tools/nutrition', 
				data : filterName,
				type : 'POST', 
				success: function(response){
					var data = JSON.parse(response);
					displayFoodData(data.status, data.food, data.sevrSize);
				}
			})
			customScrollWindow(offset - 50);
		}
	})

	/* update current row */
	$('body').on('click', '.update-current-row', function(e){
		e.preventDefault();
		var $this = $(this),
			row = $this.closest('tr'),
			formData = {},
			offset = $('#nutrition-result-area').offset().top;

		var foodName = row.find('input[name="food_name"]').val(),
			size = row.find('input[name="size"]').val(),
			servid = row.find('select.serv_size').val(),
			sizeName = row.find('select.serv_size option:selected').text();
			
		if(foodName !='' && size != '' && servid != ''){
			//toggleWaitShield("show");
			var newFood = size+' '+sizeName+' '+foodName;	
			filterName[row.data('dispname')] = {name: newFood, servid:servid};  
			$.ajax({
				url  : public_url+'meal-planner/tools/nutrition', 
				data : filterName,
				type : 'POST', 
				success: function(response){
					var data = JSON.parse(response);
					displayFoodData(data.status, data.food, data.sevrSize);
				}
			})
		}
	})

	/* Edit food show*/
	$('body').on('click', '.food-edit-open', function(e){
		e.preventDefault();

		var $this = $(this),
			row = $this.closest('tr');

		$this.hide();
		row.find('.food-edit-close').show();
		$('.edit-area-cls').hide();
		row.find('.detail-area').hide();
		row.find('.edit-area-cls').show();
	});

	/* Edit food show*/
	$('body').on('click', '.food-edit-close', function(e){
		e.preventDefault();

		var $this = $(this),
			row = $this.closest('tr');

		$this.hide();
		row.find('.food-edit-open').show();
		row.find('.detail-area').show();
		row.find('.edit-area-cls').hide();
	});
	
	/* remove current row */
	$('body').on('click','.remove-current-row', function(e){
		e.preventDefault();

		var tr_length = $(this).closest('.table').find('tr').length,
			row = $(this).closest('tr');
		if(tr_length <= 2){
			row.remove();
			resetTextArea();
		}
		else{
			toggleWaitShield("show");
			delete filterName[row.data('dispname')];
			$.ajax({
				url  : public_url+'meal-planner/tools/nutrition', 
				data : filterName,
				type : 'POST', 
				success: function(response){
					var data = JSON.parse(response);
					row.remove();
					displayFoodData(data.status, data.food, data.sevrSize);
				}
			})
		}
	})

	/* add food row */
	$('body').on('click','.add-food-btn', function(e){
		e.preventDefault();

		$(this).hide();
		$(this).closest('tr').find('.add-food-area').show();
	})

	/* On change serving size */
	$('select#serving').change(function(){
		if(Object.keys(defalutNutraData).length){
			serv_size = parseInt($(this).val());
			displayNutrationData();
		}
	})

	/* get food name */
	$('body').on('keyup', 'input[name="food_name"]', function(e){
		typeHeading($(this));
	})

	/* Sample Gradiant for nutration calculator */
	$('.sampleRecipe').click(function(e){
		e.preventDefault();

		var sample_recipe = '1 slice Bread, gluten-free, whole grain, made with tapioca starch and brown rice flour\n1 tbsp Butter, salted\n6 large Onions, raw, sliced crosswise\n';

		$("#tool-name").val($.trim(sample_recipe));
	})
})
