/*-- start: Helper Functions --*/
//var public_url = $('meta[name="public_url"]').attr('content');
	//formRowsCreated = 0;

/*function scrollToTop(elem){
	$("body, html").animate({ 
		scrollTop:$(elem).offset().top -70 
	}, 100);
};*/

/*function checkNan(value){
	value = parseInt(value,10)
	if(isNaN(value))
		return 0;
	return value
}*/

/*function clearForm(form) {
	$(':input', form).each(function(){
		var type = this.type;
		var tag = this.tagName.toLowerCase(); 
		//if(/*type == 'text' || type == 'password'*type != 'hidden' || tag == 'textarea')
		if(!$(this).hasClass('no-clear')){
			if(tag == 'textarea')
				this.value = "";
			else if(type == 'checkbox' || type == 'radio')
				this.checked = false;
			else if(tag == 'select')
				this.selectedIndex = -1;
			else
				this.value = "";
		}
	});
	$(".remove-hidden", form).remove();
	$('.upload-group img', form).removeAttr('src');
	$(form).find('.form-group.has-success').each(function(index, element){
		$(element).removeClass('has-success');
	});
}*/
function clearFormBusiness(form){
	$(".remove-hidden", form).remove();
	$('.upload-group img', form).removeAttr('src');
	$(form).find('.form-group.has-success').each(function(index, element){
		$(element).removeClass('has-success');
	});
}

$(document).on("click", ".input-group-addon", function(){
    	defaultvalue = $(this).siblings().val();
    	//$(this).siblings().attr('data-default-time',defaultvalue);
    	//$(this).siblings().trigger('click');
        //$('.timepicker').timepicker('setTime', defaultvalue);    	
});

$(document).ready(function(){
	/* Start: Category Dropdown */
	$('#proCatgory').on('show.bs.modal', function (e) {
		$(this).find('input[name="product_category"]').val('');
		$(this).find('input[name="categoryImage"]').val('');

		//$(this).find('[name="product_id"]').val($(e.relatedTarget).data('productid'));
	});

	$('#saveNewCategory').click(function(e){
		e.preventDefault();
		var formData={};
		var form=$('#categoryForm');
		var isFormValid = form.valid();
		var uploadImg=$(form).find("input[name='categoryImage']");
		if(!(validateUpload(uploadImg)))
				isFormValid = false;
		if(isFormValid){
			formData['category_name']=$(form).find('[name="product_category"]').val();
			formData['category_img']=uploadImg.val();
			//formData['product_id']=$('[name="product_id"]').val();

			$.ajax({
                url : public_url+'products/category/save',
                type : 'POST',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "added"){
                   	$('#proCatgory').modal('hide');
                   	updateRelatedDd($('select.category'), data.insertId, formData.category_name)
                    //saveSuccess(form, {action:'show', type:'update'});
                    //window.location.href = public_url+"settings/business/resources";
                    }
                },

            });
		}
	});
	/* End: Category Dropdown */
	//parent.$('#form-4 select[name="classStaffs"]').addClass('staff')
	parent.$('#form-4 select.classStaff, #form-4 select.serviceStaff').addClass('staff')
	//workingHoursTimePicker();
	
	/*$(document).on("keydown", ".numericField", function(e){
		if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8))
			return false;
	});

	/*$(document).on("focus", ".customValField", function(){
		var formGroup = $(this).closest('.form-group');

		formGroup.removeClass('has-error')
		formGroup.find('span.help-block').hide();
	});*/

	/*$(document).on("change", ".customValRadField", function(){
		var formGroup = $(this).closest('.form-group');
		setFieldValid(formGroup, formGroup.find("span.help-block"))
	})*/

	/*$('.countryCode').intlTelInput({
        initialCountry: "auto",
        geoIpLookup:function(callback){
            $.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp){
                var countryCode = (resp && resp.country)?resp.country:"";
                callback(countryCode);
                //setFieldNeutral($('.countryCode'))
            });
        },
        preferredCountries: ['nz', 'au', 'za']
    }); */

    /*$(".countryCode").on("countrychange", function(e, countryData){
    	var formGroup = $(this).closest('.form-group');
		setFieldValid(formGroup, formGroup.find("span.help-block"));
	}); */
 
    /*$('.buss-form-lnk').click(function(e){
    	e.preventDefault();
    	window.location.href = $(this).attr('href');
    	if(location.href.lastIndexOf("#") >= 0)
	        location.reload();
    })*/
});
<!-- end: Helper Functions -->

<!-- start: Client Form Reference -->
$(document).ready(function(){
	
	$.get(public_url+'clients/all', function(data){
		$('#form-6').find('#clientList').typeahead({
            source:data,
            items:'all',
            afterSelect:function(selection){
                $('input[name="clientId"]').val(selection.id);
            }
        });
    },'json');
    $.get(public_url+'staffs/all', function(data){
    	$('#form-6').find('#staffList').typeahead({
            source:data,
            items:'all',
            afterSelect:function(selection){
                $('input[name="staffId"]').val(selection.id);
            }
        });
    },'json');
    /*$.get(public_url+'contacts/all', function(data){
    	$('#proList,.proContact').typeahead({
    		highlighter: function(item){
    			var data = item.split('|');
    			return data[0]+'<br><span>'+data[1];
    		},
            source:data,
            items:'all',
            afterSelect:function(selection){
                $('input[name="proId"]').val(selection.id);
            }
        });
    },'json');*/
    getAllContactOnload();
    $('#form-6').find('input[name="referralNetwork"]').change(function(){
        toggleReference($(this).val());
    });
    toggleReference('');

   /* $('.subviewContct').click(function(){
    	getAllContactOnload();
    })*/
});

function getAllContactOnload(){
	var elem = $('.proContact');
	if(typeof elem != 'undefined')
		elem.typeahead('destroy');
	
	$.get(public_url+'contacts/all', function(data){
		contactTypeahead($('#proList'), data)
		contactTypeahead($('.proContact'), data)
    },'json');
}
/*function cloneField(){
	$('.proContactClon').typeahead('destroy');
		$('.clone-class').not('.hidden').remove();
	    var self = $('.clone-class');
	    if(typeof self != 'undefined'){
			var cloneRow = self.clone();
			    cloneRow.removeClass('hidden');
				cloneRow.find('input[type="text"]').attr('name','proContact').addClass('proContactClon');
			self.closest('.form-group').append(cloneRow);
			console.log(cloneRow)
		}	
}*/
function toggleReference(val){
    var form6 = $('#form-6');
    var clientList = form6.find('#clientList');
    var staffList = form6.find('#staffList');
    var proList = form6.find('#proList');
    if(!val)
        val = form6.find('input[name="referralNetwork"]:checked').val()

    if(val == 'Client'){
        clientList.removeClass('hidden');
        staffList.addClass('hidden');
        proList.addClass('hidden');
    }
    else if(val == 'Staff'){
        staffList.removeClass('hidden');
        proList.addClass('hidden');
        clientList.addClass('hidden');
    }
    else if(val == 'Professional network'){
        proList.removeClass('hidden');
        clientList.addClass('hidden');
        staffList.addClass('hidden');
    }
    else{
        clientList.addClass('hidden');
        proList.addClass('hidden');
        staffList.addClass('hidden');
    }
}
<!-- end: Client Form Reference -->

<!-- start: EPIC TOOLTIP -->
$(document).ready(function() {
	//$('.epic-tooltip').tooltipster();
});
<!-- end: EPIC TOOLTIP -->

<!-- start: Select all dropdown options -->
$(document).ready(function() {
	/*$('body').on('click', '.selAllDd', function(){
		var formGroup = $(this).closest('.form-group');
		var options = formGroup.find('option');
		if($(this).is(":checked")){
			options.prop('selected', true);
			if(formGroup.hasClass('has-error'))
				formGroup.removeClass('has-error')
			formGroup.find('.help-block').html('');
		}
		else
			options.prop('selected', false);

		formGroup.find('select').selectpicker('refresh');
	});*/
});
<!-- end: Select all dropdown options -->

<!-- start: Stock Popup -->
$(document).ready(function() {
	$('body').on('click', '.closePopup', function(){
		closePopup($(this));
	});

	/*$('body').on('click', '.addStock', function(e){
		e.preventDefault();

		var $this = $(this),
			formGroup = $this.closest('.popover').closest('.form-group'),
			stockLevel = formGroup.find('input[name="stockLevel"]'),
			prevStockLevel = stockLevel.val(),
			amount = formGroup.find('input[name="amount"]').val();
		
		if(amount){
			var stockLevelHistory = formGroup.find('input[name="stockLevelHistory"]'),
				preStockLevelHistory = stockLevelHistory.val(),
				reason = formGroup.find('select[name="reason"]').val();

			if(prevStockLevel != 'Unlimited'){
				var stockLevelUi = formGroup.find('#stockLevelUi'),
					amount = parseInt(amount, 10),
					prevStockLevel = parseInt(prevStockLevel, 10),
					newAmount = prevStockLevel+amount;

				stockLevel.val(newAmount);
				stockLevelUi.text(newAmount);

				formGroup.find('.stockHelperFields').removeClass('hidden');
			}
			
			if(preStockLevelHistory)
				preStockLevelHistory += '|';
			stockLevelHistory.val(preStockLevelHistory+'increase,'+moment().format('YYYY-MM-DD HH:mm:ss')+','+amount+' units were added. Reason given as "'+reason+'".')
		}

		closePopup($this);
	});*/

	$('body').on('click', '.toggleStock', function(e){
		e.preventDefault();

		var $this = $(this),
			formGroup = $this.closest('.popover').closest('.form-group'),
			stockLevel = formGroup.find('input[name="stockLevel"]'),
			prevStockLevel = stockLevel.val(),
			toggleValueTo = $this.data('toggle-value');

		if(prevStockLevel != toggleValueTo){
			var stockLevelUi = formGroup.find('#stockLevelUi'),
				stockLevelHistory = formGroup.find('input[name="stockLevelHistory"]'),
				preStockLevelHistory = stockLevelHistory.val();

			stockLevel.val(toggleValueTo);
			stockLevelUi.text(toggleValueTo);

			/*if(preStockLevelHistory)
				preStockLevelHistory += '|';
			stockLevelHistory.val(preStockLevelHistory+'increase,'+moment().format('YYYY-MM-DD HH:mm:ss')+',Units were made unlimited.')*/

			if(toggleValueTo == 'Unlimited'){
				updateStockLevelHistory(formGroup, 'increase', 'Units were made unlimited.')
				formGroup.find('.stockHelperFields').addClass('hidden');
			}
			else{
				updateStockLevelHistory(formGroup, 'decrease', 'Units were reset to zero.')
				formGroup.find('.stockHelperFields').removeClass('hidden');
			}
		}
			
		closePopup($this);
	});

	$('body').on('click', '.submitStock', function(e){
		e.preventDefault();

		var $this = $(this),
			formGroup = $this.closest('.popover').closest('.form-group'),
			stockLevel = formGroup.find('input[name="stockLevel"]'),
			prevStockLevel = stockLevel.val(),
			amount = formGroup.find('input[name="amount"]').val();
		
		if(amount){
			var reason = formGroup.find('select[name="reason"]').val(),
				submitAction = $this.data('action');

			if(prevStockLevel != 'Unlimited'){
				var stockLevelUi = formGroup.find('#stockLevelUi'),
					amount = parseInt(amount, 10),
					prevStockLevel = parseInt(prevStockLevel, 10);
					
				if(submitAction == 'add'){
					newAmount = prevStockLevel+amount;
				}
				else if(submitAction == 'remove'){
					if(prevStockLevel > amount)
						newAmount = prevStockLevel - amount;
					else
						newAmount = 0;
				}

				stockLevel.val(newAmount);
				stockLevelUi.text(newAmount);

				formGroup.find('.stockHelperFields').removeClass('hidden');
			}

			if(submitAction == 'add')
				updateStockLevelHistory(formGroup, 'increase', amount+' units were added. Reason given as "'+reason+'".')
			else
				updateStockLevelHistory(formGroup, 'decrease', amount+' units were removed. Reason given as "'+reason+'".')			
		}

		closePopup($this);
	});

	/*$('body').on('click', '.zeroStock', function(e){
		e.preventDefault();

		var $this = $(this),
			formGroup = $this.closest('.popover').closest('.form-group'),
			stockLevel = formGroup.find('input[name="stockLevel"]'),
			prevStockLevel = stockLevel.val();

		if(prevStockLevel != 0){
			var stockLevelUi = formGroup.find('#stockLevelUi'),
				stockLevelHistory = formGroup.find('input[name="stockLevelHistory"]'),
				preStockLevelHistory = stockLevelHistory.val();

			stockLevel.val(0);
			stockLevelUi.text(0);

			if(preStockLevelHistory)
				preStockLevelHistory += '|';
			stockLevelHistory.val(preStockLevelHistory+'decrease,'+moment().format('YYYY-MM-DD HH:mm:ss')+',Units were reset to zero.')

			formGroup.find('.stockHelperFields').removeClass('hidden');
		}

		closePopup($this);
	});*/

	
});
function updateStockLevelHistory(formGroup, action, text){
	var stockLevelHistory = formGroup.find('input[name="stockLevelHistory"]'),
		preStockLevelHistory = stockLevelHistory.val()

	if(preStockLevelHistory)
		preStockLevelHistory += '|';

	stockLevelHistory.val(preStockLevelHistory+action+','+moment().format('YYYY-MM-DD HH:mm:ss')+','+text)
}

function closePopup(elem){
	elem.closest('.popover').popover('hide');
}
/*$(document).ready(function() {
	$('body').on('click', '.submitStockPop', function(e){
		submitStockPop($(this));
		e.preventDefault();
	});

	$('body').on('click', '.closePopup', function(){
		var action = getPopupAction($(this));
		closePopup(action+'_pop')
	});
});
function submitStockPop($elem){
	var content = $elem.closest('.popover').find('.popoverContent');

	var formGroup = content.closest('.form-group');
	var action = getPopupAction($elem);
	var stock_amount_total = formGroup.find('input[name="stock_amount_total"]');
	var stock_amount_total_ui = formGroup.find('#stock_amount_total');
	var newAmount;
	var amount;
	var reason;
	var stockHelperFieldsAction;

	var toggleTo = $elem.data('toggle-stock');
	if(toggleTo == 0 || toggleTo){
		newAmount = toggleTo;
		amount = toggleTo;
		reason = '';
		if(toggleTo == 'Unlimited')
			stockHelperFieldsAction = 'hide';
		else
			stockHelperFieldsAction = 'show';
	}
	else{
		amount = checkNan(content.find('input[name="amount"]').val().trim());
		reason = content.find('select[name="reason"]').val().trim();

		if(amount != 0 && reason != ""){  
			var stock_amount_totalVal = checkNan(stock_amount_total.val().trim());
			if(action == 'stock_add'){
				var newAmount = stock_amount_totalVal+amount
				var popupClass = 'stockAddPop';
				amount = '+'+amount
			}
			else{
				var newAmount = stock_amount_totalVal-amount
				amount = '-'+amount
			}

			stockHelperFieldsAction = 'show'
		}
	}

	if(newAmount == 0 || newAmount){
		stock_amount_total.val(newAmount);
		stock_amount_total_ui.html(newAmount);
		var fields = '<input type="hidden" name="'+action+'_amount'+$('.'+action+'_amount').length+'" value="'+amount+'" class="'+action+'_amount remove-hidden"><input type="hidden" name="'+action+'_reason'+$('.'+action+'_reason').length+'" value="'+reason+'" class="'+action+'_reason remove-hidden">';
		formGroup.prepend(fields);

		toggleStockHelperFields(formGroup, stockHelperFieldsAction);

		closePopup(action+'_pop')
	}
}
function toggleStockHelperFields($formGroup, action){
	if(action == 'show')
		$formGroup.find('.stockHelperFields').removeClass('hidden');
	else
		$formGroup.find('.stockHelperFields').addClass('hidden');
}
function getPopupAction($elem){
	return $elem.closest('.popover').find('.popoverContent').data('action');
}
function closePopup(popupClass){
	$('.'+popupClass).popover('hide');
}*/
<!-- end: Stock Popup -->

<!-- start: Bootstrap timepicker -->
$(function(){
	/*$('.datetimepicker').datetimepicker({
		format: 'LT',
		useCurrent: true,
		stepping: 5
	});*/
});
<!-- end: Bootstrap timepicker -->


<!-- start: Bootstrap daterangepicker -->
function cb(start, end){
	var string = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
	$('#daterange').val(string).text(string);
}
$(function(){
	var dateRange = $('#daterange'),
		defaultVal = dateRange.val();

	if(defaultVal){
		var hisotyrArr = defaultVal.split('-'),
			start = moment(hisotyrArr[0], 'YYYY/MM/DD'),
			end = moment(hisotyrArr[1], 'YYYY/MM/DD');
	}
	else{
		var start = moment().subtract(29, 'days'),
			end = moment();
	}

	dateRange.daterangepicker({
		startDate: start,
		endDate:end,
        locale: {
	      	format: 'MMMM D, YYYY'
	    },
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
	}, cb);
	
	cb(start, end);
});
<!-- end: Bootstrap daterangepicker -->

<!-- start: Bootstrap Select Master -->
$(function(){
	//$('select').selectpicker('val', '');

	/*$('select').each(function(){
		$(this).prepend('<option value="">-- Select --</option>');
		$(this)[0].selectedIndex = -1;
	})*/
	/*$('select').selectpicker({
		noneSelectedText: " -- Select -- "
	});	*/

	//$('select').on('change', function() {
	/*$('body').on('change', 'select', function(){
		var self = $(this);
		var value = self.val();
		if(value != "" && value != 'undefined') {
			self.closest('.form-group')
					.removeClass('has-error')
					.addClass('has-success')
					.find('.help-block').remove();
		}
	})*/
});
<!-- end: Bootstrap Select Master -->
function toggleServiceCatFieldset(dd, selText){
	var form = dd.closest('form');
	if(selText == null)
		selText = dd.find('option:selected').text()
	var oneOnOne = form.find('#oneOnOne');
	var team = form.find('#team');
	var catText = form.find('input[name="catText"]');
	if(selText == '1 on 1'){
		oneOnOne.show();
		team.hide();
		catText.val('1 on 1');
	}
	else if(selText == 'TEAM Training'){
		oneOnOne.hide();
		team.show();
		catText.val('TEAM Training');
	}
	else{
		oneOnOne.hide();
		team.hide();
		catText.val('');
	}
}
function toggleTrainerCb(dd){
	var selText = $(dd).find('option:selected').text()
	var cbDiv = $(dd).closest('.form-group').find('.checkbox')
	if(selText == 'Personal Trainer')
		cbDiv.show();
	else
		cbDiv.hide();
}
function populateAreaDd($dd){
	$dd.each(function(){
		var $this = $(this),
			locVal = $this.val(),
			areaDdFormGroup = $this.closest('.form-group').next('div.form-group'),
			areaDd = areaDdFormGroup.find('select.area');
		
		areaDd.html('');
		setFieldNeutral(areaDd);
		areaDdFormGroup.find(':checkbox').attr('checked', false)
		if(locVal != '' && locVal != null){
			$.get(public_url+'location/'+locVal+'/areas', function(data){
				var defaultArea = areaDd.data('selected'),
					option = '';

				if(defaultArea.length > 1)
					defaultAreas = defaultArea.split(',');
				else
					defaultAreas = [defaultArea.toString()];

				$.each(data, function(val, text){
					option = '<option value="' + val + '"';
					if(defaultArea != '' && defaultArea != null && jQuery.inArray(val, defaultAreas) >= 0)
						option += ' selected';
					
					option += '>' + text + '</option>';
					areaDd.append(option);
				});
				areaDd.selectpicker('refresh');
				if(option)
					areaDdFormGroup.show();
				else
					areaDdFormGroup.hide();
				
				populateStaffBasedOnArea();
		    },'json');
		}
		else{
			areaDd.selectpicker('refresh');
			areaDdFormGroup.hide();
			populateStaffBasedOnArea();
		}
	})
}
function populateStaffBasedOnArea(){
	var form = $('#form-4');

	if(form.find('input[name="form_type_opt"]').val() == 'Class'){
		var areaDd = form.find('select[name="classAreas"]'),
			staffDd = form.find('select[name="classStaffs"]');
	}
	else{
		var areaDd = form.find('select[name="area"]'),
			staffDd = form.find('select.serviceStaff');
	}
	/*,
		params = {};

	var staffDd = $('#form-4 select[name="classStaffs"]');*/
	staffDd.html('');
	staffDd.closest('.form-group').find(':checkbox').attr('checked', false)

	var areas = areaDd.val();
	if(areas){
		/*params = {areasId: areas};
	}
	}
	else{
		var locDd = form.find('select[name="classLoc"]'),
			loc = locDd.val();

		if(loc)
			params = {locId: loc};
	}

	if(!$.isEmptyObject(params)){
		$.get(public_url+'settings/business/areas/common-staffs', params, function(data){*/
		$.get(public_url+'settings/business/areas/common-staffs', {areasId: areas}, function(data){
			if(data.length){
				staffDd.each(function(){
					var option = '',
						$this = $(this),
						defaultStaff = $this.data('selected');

					if(defaultStaff.length > 1)
						defaultStaff = defaultStaff.split(',').map(Number);
					else
						defaultStaff = [defaultStaff];

					$.each(data, function(key, val){
						option += '<option value="'+val.id+'"';
						if(defaultStaff && $.inArray(val.id, defaultStaff) >= 0)
							option += ' selected';
						
						option += '>'+val.name+'</option>';
					});
					$this.append(option);
				});
			}
			staffDd.selectpicker('refresh');
		},'json');
	}
	else
		staffDd.selectpicker('refresh');
}
function togglePaddingFields(){
	var form = $('#form-4'),
		ifPadding = form.find('input[name="classIfPadding"]'),
		paddingFields = form.find('.paddingFields');

	if(ifPadding.is(':checked'))
		paddingFields.show();
	else
		paddingFields.hide();
}
function togglePasswordFieldset(cb){
	if(typeof cb == 'undefined')
		cb = $('.js-ifCreateLogin');

	cb.each(function(){
		var $this = $(this),
			pwdFieldset = $this.closest('form').find('.js-pwdFieldset');

		if(pwdFieldset.length){
			if(/*$this.data('old-login-with-email') &&*/ $this.is(':checked'))
				pwdFieldset.show();
			else
				pwdFieldset.hide();
		}
	});
}
/*function passwordFieldsetAfterSubmit(data){
	var cb = data.cb,
		name = cb.attr('name');

	if(name in data.formData){
		clearPassword({newPwdField:data.newPwdField, newPwdCnfmField:data.newPwdCnfmField});
		cb.data('old-login-with-email', 1)
	}
	else
		cb.data('old-login-with-email', 0)

	togglePasswordFieldset(cb)
}*/

$(document).ready(function() {
    $('input[name="expirey_date"]').datepicker({autoclose:true, dateFormat:"D, d M yy",minDate: new Date()});

	$('#form-6 .js-ifCreateLogin').change(function(){
		$('#reqMsgloginEmail').addClass('hidden');
	});

	$('.js-ifCreateLogin').change(function(){
		togglePasswordFieldset($(this))
	});
	togglePasswordFieldset()

	$('#form-4').find('select[name="category"]').change(function(){
		toggleServiceCatFieldset($(this));
		var formMode = $('#form-4').data('form-mode');
		if(formMode=='standAlone'){
			resourceOnCategChange($(this));
		}
	});
	toggleServiceCatFieldset($('#form-4').find('select[name="category"]'))


	$('#form-4').find('select.location').change(function(){
		populateAreaDd($(this))
	});
	populateAreaDd($('#form-4').find('select.location'))

	//$('#form-4 select[name="classAreas"], #classAreasAll').change(function(){
	$('#form-4 select.area, #classAreasAll, #serviceAreasAll').change(function(){
		populateStaffBasedOnArea()
	});

	$('#form-4').find('input[name="classIfPadding"]').change(function(){
		togglePaddingFields();
	});
	togglePaddingFields();

	$('#form-7').find('select[name="type"]').change(function(){
		toggleTrainerCb(this)
	});
	toggleTrainerCb($('#form-7').find('select[name="type"]'))
});



<!-- start: SCRIPTS FOR ADD NEW TIME BUTTON -->
$(document).ready(function() {
	/*$('body').on('click', '.btn-add-new-time', function(e){
		e.preventDefault();
		formRowsCreated++;

		var self = $(this),
			parCont = self.closest('div'),
			timeRow = self.next().clone(),
			day = timeRow.find('input[type="checkbox"]').attr('name');

		timeRow.find('input[name="'+day+'_start0"]').prop('name', day+'_start'+formRowsCreated);
		timeRow.find('input[name="'+day+'_end0"]').prop('name', day+'_end'+formRowsCreated);
		addNewTimeHelper(timeRow, parCont);
		/*var timeRow = parCont.find('.row').eq(0).clone();
		
		var day = timeRow.find('input[type="checkbox"]').attr('name');
		var rowNumb = parCont.find('.row').length;
		timeRow.find('input[type="text"]:first').prop('name', day+'_start'+rowNumb);
		timeRow.find('input[type="text"]:not(:first)').prop('name', day+'_end'+rowNumb);
		addNewTimeHelper(timeRow, parCont);*
	});*/
});
/*$('body').on('click', '.btn-add-new-time-cancel', function(e){
	$(this).closest('.row').remove();
});*/
/*function addNewTimeHelper(timeRow, parCont){
	timeRow.addClass('remove-hidden');
	timeRow.find('.checkbox').remove();//input[type="checkbox"]
	timeRow = timeRow.append('<div class="col-xs-3"><button class="btn btn-xs btn-danger btn-add-new-time-cancel" type="button">Remove</button></div>');
	parCont.append(timeRow);
	/*parCont.find('.btn-add-new-time-cancel').click(function() {
		$(this).closest('.row').remove();
	})*
	$('.datetimepicker').datetimepicker({
		format: 'LT',
		stepping: 5
	});
}*/
<!-- end: SCRIPTS FOR ADD NEW TIME BUTTON -->
    
<!-- start: SCRIPTS FOR TOGGLING HOURS -->
/*function toggleHours(self){
	var parCont = self.closest('.clearfix');
	var notWork = parCont.find('.col-xs-10')
	var showHoursElem = parCont.find('.showHoursElem')
	var timeRow = parCont.find('.row:not(:first)');
	if(self.is(':checked')){
		notWork.addClass('hidden');
		showHoursElem.removeClass('hidden');
		timeRow.removeClass('hidden');
	}
	else{
		timeRow.addClass('hidden');
		showHoursElem.addClass('hidden');
		notWork.removeClass('hidden');
	}
}*/
$(document).ready(function() {
	/*$('.showHours').each(function(){
		toggleHours($(this))	
	});*/
	
	/*$('body').on('change', '.showHours', function(e){
		toggleHours($(this));
	});
	$('.showHours').trigger('change');*/
});
<!-- end: SCRIPTS FOR TOGGLING HOURS -->

<!-- start: SCRIPTS FOR RESETTING HOURS -->
/*function hourReset(self, section){
	var parCont = self.closest('.clearfix');
	parCont.find('.row:not(:first)').remove();
	
	var checkbox = $(parCont).find('input[type="checkbox"]');
	var day = checkbox.attr('name');
	if(section == null || section == 'Location'){
		if(day == 'sunday' || day == 'saturday')
			hourResetHelper(checkbox, self);
		else{
			if(!checkbox.is(':checked')){
				checkbox.prop('checked', true);
				toggleHours(self);
			}
		}
	}
	else
		hourResetHelper(checkbox, self);

	hourResetTime(parCont);
}
function hourResetHelper(checkbox, self){
	if(checkbox.is(':checked')){
		checkbox.prop('checked', false);
		toggleHours(self);
	}
}
function hourResetTime($container){
	$container.find("input[type='text']:first").val('6:00 AM');
	$container.find("input[type='text']:not(:first)").val('7:00 PM');
}*/
<!-- end: SCRIPTS FOR RESETTING HOURS -->

<!-- start: SCRIPTS FOR POPULATING HOURS BASED ON LOCATION -->
/*function populateHoursEditMode(){
	$('.workingHrs').each(function(){
		var $this = $(this),
			oldHours = $this.data('old-hours');

		$this.find('input.showHours').each(function(){
			hourReset($(this), 'Area');	
		});

		if(oldHours){
			var daysCovered = [];
			$.each(oldHours, function(key, value){
				var day = (value.hr_day).toLowerCase(),
					checkbox = $this.find('input[name="'+day+'"]'),
					timeRow = checkbox.closest('.row');

				if($.inArray(day, daysCovered) == -1){
					daysCovered.push(day);
					
					checkbox.prop('checked', true);
					toggleHours(checkbox);

					setDbTimeToTimeString(timeRow.find('input[name="'+day+'_start0"]'), value.hr_start_time)
					setDbTimeToTimeString(timeRow.find('input[name="'+day+'_end0"]'), value.hr_end_time)
				}
				else{
					formRowsCreated++;

					var parCont = checkbox.closest('.clearfix'),
						timeRow = timeRow.clone(),
						startTimeField = timeRow.find('input[name="'+day+'_start0"]'),
						endTimeField = timeRow.find('input[name="'+day+'_end0"]');

					setDbTimeToTimeString(startTimeField, value.hr_start_time)
					setDbTimeToTimeString(endTimeField, value.hr_end_time)
					startTimeField.prop('name', day+'_start'+formRowsCreated);
					endTimeField.prop('name', day+'_end'+formRowsCreated);
					
					addNewTimeHelper(timeRow, parCont);
				}
			});
		}
	});
}*/

function populateHours(val, form){
	$(form).find('.showHours').each(function(){
		hourReset($(this), 'Area');	
	});
	if(val != null && val != ''){
		$.get(public_url+'location/'+val+'/hours', function(data){

			var daysCovered = [];
			$.each(data, function(key, value){
				var day = (value.hr_day).toLowerCase(),
					checkbox = $('#form-2 input[name="'+day+'"]'),
					timeRow = checkbox.closest('.row');

				if($.inArray(day, daysCovered) == -1){
					daysCovered.push(day);
					
					checkbox.prop('checked', true);
					toggleHours(checkbox);

					setDbTimeToTimeString(timeRow.find('input[name="'+day+'_start0"]'), value.hr_start_time)
					setDbTimeToTimeString(timeRow.find('input[name="'+day+'_end0"]'), value.hr_end_time)
				}
				else{
					formRowsCreated++;

					var parCont = checkbox.closest('.clearfix'),
						timeRow = timeRow.clone(),
						startTimeField = timeRow.find('input[name="'+day+'_start0"]'),
						endTimeField = timeRow.find('input[name="'+day+'_end0"]');

					setDbTimeToTimeString(startTimeField, value.hr_start_time)
					setDbTimeToTimeString(endTimeField, value.hr_end_time)
					startTimeField.prop('name', day+'_start'+formRowsCreated);
					endTimeField.prop('name', day+'_end'+formRowsCreated);
					
					addNewTimeHelper(timeRow, parCont);
				}
			});
		});
	}
}
$(document).ready(function() {
	$('body').on('change', '#form-2 select[name="location"]', function(){	
		populateHours($(this).val(), $('#form-2'));
	});
	//populateHoursEditMode();
});
<!-- end: SCRIPTS FOR POPULATING HOURS BASED ON LOCATION-->

<!-- start: SCRIPTS FOR SUBVIEW-->
$('.callSubview').click(function(e){
	e.preventDefault();

	var $this = $(this),
		form = $this.closest('form'),
		data = '';

	if(form.attr('id') == 'form-4' && $this.data('target-subview') == 'staff'){
		if(form.find('input[name="form_type_opt"]').val() == 'Class'){
			var areaDd = form.find('select[name="classAreas"]'),
				locDd = form.find('select[name="classLoc"]');
		}
		else{
			var areaDd = form.find('select[name="area"]'),
				locDd = form.find('select[name="location"]');
		}

		var selectedAreas = areaDd.val();
		if(selectedAreas == null){
			var selectedLoc = locDd.val();
			if(selectedLoc)
				data = 'locToLink='+selectedLoc;
		}
		else
			data = 'areasToLink='+selectedAreas;
	}
	else if(form.attr('id') == 'form-5' && $this.data('target-subview') == 'contact'){
		data = 'location_id='+form.find('select[name="stock_location"]').val();
	}


	openSubview($this, data);
	/*var subview = $('#subview'),
		targetSubview = $(this).data('target-subview')/*,
		businessId = $(this).closest('form').find('input[name="businessId"]').val()*/;

	/*if(targetSubview == 'location')
		subview.find("iframe").attr("src", public_url+'settings/business/locations/create?subview=true');//location/subview?bussId='+businessId);
	else if(targetSubview == 'staff')
		subview.find("iframe").attr("src", public_url+'settings/business/staffs/create?subview=true');
	else if(targetSubview == 'service')
		subview.find("iframe").attr("src", public_url+'settings/business/services/create?subview=true');
	/*else if(targetSubview == 'staff')
		subview.find("iframe").attr("src", public_url+'staff/subview?bussId='+businessId);
	else if(targetSubview == 'service')
		subview.find("iframe").attr("src", public_url+'service/subview?bussId='+businessId);*/

	/*subview.show("slide", {direction:"right"}, 200);

	//var targetSubview = $('#'+$(this).data('target-subview'));
	
	//window.open($('meta[name="public_url"]').attr('content')+'location/subview','_blank','width=800,height=900,scrollbars=yes'); 
	//$("#frame").attr("src", $('meta[name="public_url"]').attr('content')+'location/subview');
	
	//var targetSubview = $('#'+$(this).data('target-subview'));
	/*var timeRow = $('#step-2').clone(true, true);
	timeRow.show();
	timeRow.find('form').children('.row:not(.margin-top-90)').remove();
	timeRow.find('.row.margin-top-90').removeClass('margin-top-90');
	clearForm(timeRow.find('form'));
	timeRow = timeRow.append('<div class="row"><div class="col-sm-6"></div><div class="col-sm-6"><button class="btn btn-primary pull-right" type="submit">Submit</button><button class="btn btn-default pull-right margin-right-15 closeSubView" type="button" data-target-subview="location_subview">Cancel</button></div></div>');
	
	targetSubview.append(timeRow);*/
	
	//targetSubview.show("slide", {direction:"right"}, 200);
});
/*$('body').on('click', '.closeSubView', function(){
	parent.closeSubview(/*$(this).closest('form').data('subview')*)
});*/
/*function closeSubview(subviewData){
	$('#subview').hide("slide", {direction:"right"}, 200);

	if(typeof subviewData != 'undefined')
		updateRelatedDd($('select.'+subviewData.dd), subviewData.insertId, subviewData.data, true)
	
	//$('#'+targetSubview).hide("slide", {direction:"right"}, 200);
}*/
<!-- end: SCRIPTS FOR SUBVIEW-->

<!-- start: SCRIPTS FOR BOOTSTRAP DATEPICKER -->
/*$(document).ready(function() {
	$('.datepicker').datepicker({autoclose:true, format:"d M yyyy"});
	//$('.datepicker').datepicker()
});*/
<!-- end: SCRIPTS FOR BOOTSTRAP DATEPICKER -->

<!-- start: SCRIPTS FOR SUBMITTING FORM -->
/*function prepareMultiSel($elem){
	var val = $elem.val();
	if(val != null)
		return val;
	return '';
}*/

/*function validateUrl ($urlField) {
	var urlVal = $.trim($urlField.val());
	/*var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
		  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
		  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
		  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
		  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
		  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator*
	var pattern = /[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/;
	  if(urlVal != null && urlVal != '' && !pattern.test(urlVal)) {
		setFieldInvalid($urlField.closest('.form-group'), $urlField.next("span"), 'Please enter a valid URL.')
		return false;
	}
	
	return true;
}*/
/*function validateUpload($uploadField){
	var uploadVal = $uploadField.val();
	var formGroup = $uploadField.closest('.form-group');
	if(uploadVal == null || uploadVal == ""){
		setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please upload a proper file.')
		return false;
	}
	else{
		setFieldValid(formGroup);
		return true;
	}
}*/
/*function setFieldInvalid($formGroup, $helpBlock, helpBlockMsg){
	if($formGroup.hasClass('has-success'))
		$formGroup.removeClass('has-success')
	$formGroup.addClass('has-error');
	$helpBlock.html(helpBlockMsg);
	$helpBlock.show();
}*/
/*function setFieldValid($formGroup, $helpBlock){
	if($formGroup.hasClass('has-error'))
		$formGroup.removeClass('has-error')
	$formGroup.addClass('has-success');
	if($helpBlock != null)
		$helpBlock.hide();
}*/
/*function setFieldNeutral($elem){
	alert('hmm')
	var formGroup = $elem.closest('.form-group');

	formGroup.removeClass('has-error has-success')
	formGroup.find('span.help-block').hide();
}
/*function setFieldNeutral($elem){
	var formGroup = $elem.closest('.form-group');

	formGroup.removeClass('has-error')
	formGroup.find('span.help-block').hide();
}*/
function resetSelFixLoc(form){
	var ulSelect = $(form).find('ul.selectable_fixed_location.ui-selectable');
	$(ulSelect).find('li').each(function(index, element){
		$element = $(element);
		if($element.text() == 'Fixed Location' && !$element.hasClass('ui-selected'))
			$element.addClass('ui-selected')
		
		else if($element.text() != 'Fixed Location' && $element.hasClass('ui-selected'))
			$element.removeClass('ui-selected')
		
		$element.next('.form-group').find('input').val('Fixed Location');
		$(form).find('.notForMobloc').show(); 
	});
}

function showskipbutton(form){
	$(form).find(".skipbutton").removeClass("hidden");
}
/*function saveSuccess(form, data){
	var msgElem = $(form).find('.sucMes');
	if(data.action == 'show'){
		if(data.type == 'store')
			var message = 'Data has been saved successfully';
		else if(data.type == 'update')
			var message = 'Data has been updated successfully';
		else if(data.type == 'loginAccountCreate')
			var message = 'Your account was successfully created. We have sent you details and confirmation e-mail to your mailbox.';

		msgElem.append('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+message+'</div>');
		msgElem.removeClass('hidden');
	}
	else if(data.action == 'hide' && !msgElem.hasClass('hidden')){
		msgElem.html('');
		msgElem.addClass('hidden');
	}
}*/
/*function validateContryCode(numbField){
	if(numbField.val() != ''){
		var selCountry = numbField.intlTelInput("getSelectedCountryData");
		if(!Object.keys(selCountry).length){
			var formGroup = numbField.closest('.form-group');
			setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a country.')
			return false;
		}
	}
	return true;
}*/
/*function getFullNumb(form, numbField){
	if(numbField == null)
		numbField = form.find('input[name="phone"]');

	if(numbField.val() != ''){
		/*var selCountry = numbField.intlTelInput("getSelectedCountryData");
		if(Object.keys(selCountry).length)
			return numbField.intlTelInput("getNumber");
		else
			return numbField.val();*
		return numbField.intlTelInput("getNumber");
	}
	return '';
}*/
function redirectToBusinessEdit(){ 
	window.location.href = public_url + 'settings/businesses/edit';
}

 $(document).ready(function() { 
 		/* Start: if no tax rate is present then */
 	   /* var noOfTaxRateCol=$()*/
 	/* Start: if no tax rate is present then */
 	$('.skipbutton.finish').click(redirectToBusinessEdit);

 	/* Start: Button action for only service and class */
 	$('.applyDateCls').datepicker({autoclose:true, dateFormat:"D, d M yy"});
 	$('.btn-edit-service-class').on('click', function(){
 		var form = $(this).closest('form'),
 			formType = form.find('input[name="form_type_opt"]').val(),
 			servCat = form.find('input[name="catText"]').val(),
 			newPrice = 0.0, 
 			oldPrice = 0.0,
 			isFormValid = $(form).valid();

 		form.find('input[name="effectiveDate"]').val('');

 		if(isFormValid){
			if(formType == 'Service'){
				if(servCat == '1 on 1'){
					newPrice = form.find('input[name="one_on_one_price"]').val();
					oldPrice = form.find('input[name="one_on_one_price_hidden"]').val();
				}
				else if(servCat == 'TEAM Training'){
					newPrice = form.find('input[name="team_price"]').val();
					oldPrice = form.find('input[name="team_price_hidden"]').val();
				}
			}
			else if(formType == 'Class'){
				newPrice = form.find('input[name="classPrice"]').val();
				oldPrice = form.find('input[name="classPrice_hidden"]').val();
			}
			
			if(newPrice != oldPrice){
				$('.applyDateCls').val(moment().format("ddd, D MMM YYYY"));
				$('#priceUpdateModal').modal('show');
			}
			else if(newPrice == oldPrice)
				$('.btn-add-more-form').trigger('click');
		}
			
 	});
 	/* End: Button action for only service and class */

 	/* Start: Submit new price */
 	$('#newPriceSubmit').on('click', function(){
 		var modal = $(this).closest('.modal'),
 			date = modal.find('input[name="applyDate"]').val(),
 			form = $('#form-4');

 		form.find('input[name="effectiveDate"]').val(dateStringToDbDate(date));
 		modal.modal('hide');
 		$('.btn-add-more-form').trigger('click');
 	})
 	/* End: Submit new price */

	$('.btn-add-more-form').on('click', function() { 
		//var submitBtn = $(this)
		var form = $(this).closest('form');
		var step = $(form).attr('id');		
		saveSuccess(form, {action:'hide'});
		
		var isFormValid = $(form).valid();		
		if(step == 'form-1') {
			if(!(validateUrl($(form).find("input[name='website']"))))
				isFormValid = false;

			if(!(validateUrl($(form).find("input[name='facebook']"))))
				isFormValid = false;

			if(!(validateUpload($(form).find("input[name='logo']"))))
				isFormValid = false;

			if(!(validateContryCode($(form).find("input[name='phone']"))))
				isFormValid = false;

			if(!(validateBussSlug($(form).find("input[name='cp_web_url']"))))
				isFormValid = false;
		}
		else if(step == 'form-2' && $(form).find("input[name='venue']").val() == 'Location'){
			if(!(validateUrl($(form).find("input[name='website']"))))
				isFormValid = false;

			if(!(validateUrl($(form).find("input[name='facebook']"))))
				isFormValid = false;

			if(!(validateContryCode($(form).find("input[name='phone']"))))
				isFormValid = false;
		}
		else if(step == 'form-3'){
			if(!(validateUrl($(form).find("input[name='website']"))))
				isFormValid = false;

			if(!(validateUrl($(form).find("input[name='facebook']"))))
				isFormValid = false;
			
            /*if(!(validateUpload($(form).find("input[name='staffBiography']"))))
				isFormValid = false;*/

			if(!(validateUpload($(form).find("input[name='staffProfilePictureUpload']"))))
				isFormValid = false;

			if(!(validateContryCode($(form).find("input[name='phone']"))))
				isFormValid = false;

			if(!validateStartEndDate({startDateField:form.find("input[name='hourly_start_date']"), endDateField:form.find("input[name='hourly_end_date']")}))
				isFormValid = false;

			if(!validateStartEndDate({startDateField:form.find("input[name='per_session_start_date']"), endDateField:form.find("input[name='per_session_end_date']")}))
				isFormValid = false;

			if(!validateStartEndDate({startDateField:form.find("input[name='commission_date_range_start']"), endDateField:form.find("input[name='commission_date_range_end']")}))
				isFormValid = false;

			var loginWithEmailField = form.find("input[name='login_with_email']"),
				newPwdField = form.find("input[name='staffNewPwdd']"),
				newPwdCnfmField = form.find("input[name='staffNewPwdCnfmm']");
			if(loginWithEmailField.is(':checked') && /*loginWithEmailField.data('old-login-with-email') &&*/ !validateNewPassword({newPwdField:newPwdField, newPwdCnfmField:newPwdCnfmField}))
					isFormValid = false;
			else{
				setFieldNeutral(newPwdField);
				setFieldNeutral(newPwdCnfmField);
			}
			
				/*var staffNewPwdCnfmField = $(form).find("input[name='staffNewPwdCnfm']"),
					staffNewPwd = $(form).find("input[name='staffNewPwd']").val(),
					formGroup = staffNewPwdCnfmField.closest('.form-group');

				if(staffNewPwd && staffNewPwdCnfmField.val() != staffNewPwd){
					//setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please renter new password.');
					setFieldInvalid(formGroup, 'Please renter new password.');
					isFormValid = false;
				}
				else
					setFieldValid(formGroup);*/
				//validateNewPassword(form);
			//}

			/*var gendVal = $(form).find("input[name='gender']:checked").val();
			var formGroup = $(form).find("input[name='gender']").closest('.form-group');
			if(gendVal == null || gendVal == ""){
				isFormValid = false;
				//setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a valid gender.')
				setFieldInvalid(formGroup, 'Please select a valid gender.')
			}
			else
				setFieldValid(formGroup);*/

			if(!validateGender(form, 'gender'))
				isFormValid = false;

			/*var day = $(form).find("select[name='day']");
			var dayVal = day.val();
			var month = $(form).find("select[name='month']");
			var monthVal = month.val();
			var year = $(form).find("select[name='year']");
			var yearVal = year.val();
			var formGroup = day.closest('.form-group');
			var isDobValid = true;
			if(dayVal == null || dayVal == ""){
				isDobValid = false;
				day.val('')
			}
			if(monthVal == null || monthVal == ""){
				isDobValid = false;
				month.val('')
			}
			if(yearVal == null || yearVal == ""){
				isDobValid = false;
				year.val('')
			}
			if(!isDobValid){
				isFormValid = false;
				//setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please select a valid date of birth.')
				setFieldInvalid(formGroup, 'Please select a valid date of birth.')
			}
			else
				setFieldValid(formGroup);*/
			if(!validateDob(form, {day:'day', month:'month', year:'year'}))
				isFormValid = false;
		}
		else if(step == 'form-4'){
			/*var ddArray = [];
			var commonValues = [];
			var existedDd = $('#resource-form .resource-clone-class:not(.hidden) select.resource-option');
			var existedItem = $('#resource-form .resource-clone-class:not(.hidden) select.item-op');
			//setFieldNeutral(existedDd);
			//setFieldNeutral(existedItem);

			if(existedDd.length){
				existedDd.each(function(){
					existedDdValue = $(this).val();
					if($.inArray(existedDdValue,ddArray) != -1){
						commonValues.push(existedDdValue);
						setFieldInvalid($(this).closest('.resItemLocDd'));
						setFieldInvalid($(this).closest('.resource-clone-class').find('.resItemLocDd2'));
						isFormValid = false;
					}
					else
						ddArray.push(existedDdValue);
				})
			}*/
			if(!validateResourceForm())
				isFormValid = false;
		}
		else if(step == 'form-5'){
			if(!(validateUpload($(form).find("input[name='productImage']"))))
				isFormValid = false;
			if(!(validateCkeditor($(form).find("textarea[name='description']"))))
				isFormValid = false;
			if(!(validateManualDate($(form).find('input[name="expirey_date"]'))))
				isFormValid = false;
		}
		else if(step == 'form-6'){
			/*var $emailFieldVal = $(form).find('input[name="email"]').val().trim(),
				$phoneNumbField = $(form).find('input[name="numb"]'),
				phoneNumbFieldVal = $phoneNumbField.val().trim();

			if($emailFieldVal == '' && phoneNumbFieldVal == ''){
				$('#reqMsg').removeClass('hidden');
				isFormValid = false;
			}
			else if(phoneNumbFieldVal != ''){
				var cntryData = $phoneNumbField.intlTelInput("getNumber")
				if(cntryData)
					$phoneNumbField.val(cntryData)
			}
			if($emailFieldVal != '' || phoneNumbFieldVal != '')
				$('#reqMsg').addClass('hidden');*/
            // var day = $(form).find("select[name='day']");
			// var dayVal = day.val();
			// var month = $(form).find("select[name='month']");
			// var monthVal = month.val();
			// var year = $(form).find("select[name='year']");
			// var yearVal = year.val();
			// var formGroup = day.closest('.form-group');
            // var dob =new Date(yearVal, monthVal, dayVal);
            // var today = new Date();
            // var age = Math.floor((today-dob)/(365.25*24*60*60*1000));
            // if(age < 13){
            // 	isFormValid = false;
            // 	var message='Age can not be less than 13 years.';
            // 	swal(message);


            // }
          
			var emailFieldVal = $(form).find('input[name="email"]').val().trim(),
				phoneNumbField = $(form).find('input[name="numb"]'),
				phoneNumbFieldVal = phoneNumbField.val().trim(),
				newClientReqMsg = $('#reqMsg');

			if(emailFieldVal == '' && phoneNumbFieldVal == ''){
				newClientReqMsg.removeClass('hidden');
				isFormValid = false;
			}
			else if(emailFieldVal != '' || phoneNumbFieldVal != ''){
				newClientReqMsg.addClass('hidden');
				if(phoneNumbFieldVal != '' && !(validateContryCode(phoneNumbField)))
					isFormValid = false;
			}

			var loginWithEmailField = form.find("input[name='login_with_email']"),
				ReqMsgloginEmail = $('#reqMsgloginEmail'),
				newPwdField = form.find("input[name='clientNewPwd']"),
				newPwdCnfmField = form.find("input[name='clientNewPwdCnfm']");

			if(loginWithEmailField.is(':checked') && /*loginWithEmailField.data('old-login-with-email') &&*/ !validateNewPassword({newPwdField:newPwdField, newPwdCnfmField:newPwdCnfmField}))
					isFormValid = false;
			else{
				setFieldNeutral(newPwdField);
				setFieldNeutral(newPwdCnfmField);
			}
			if(loginWithEmailField.is(':checked') && emailFieldVal == ''){
				ReqMsgloginEmail.removeClass('hidden');
				isFormValid = false;
			}
			else
				ReqMsgloginEmail.addClass('hidden');
		}
		else if(step == 'form-7'){
			if(!(validateUrl($(form).find("input[name='website']"))))
				isFormValid = false;

			if(!(validateUrl($(form).find("input[name='facebook']"))))
				isFormValid = false;

			if(!(validateContryCode($(form).find("input[name='phone']"))))
				isFormValid = false;
		}
		else if(step == 'form-8'){
           	if(!validateStartEndDate({startDateField:form.find("input[name='enrollment_start_date']"), endDateField:form.find("input[name='enrollment_end_date']")}))
				isFormValid = false;
			
			moveErrorMsg(form);
		}

		var subview = form.closest('.swMain').data('is-subview');

		if(isFormValid){
		 	formData = {}
			$.each($(form).find(':input').serializeArray(), function(i, obj){
				formData[obj.name] = obj.value
			});
			console.log(formData);
			
			if(step == 'form-1')
				formData['phone'] = getFullNumb($(form));
			else if(step == 'form-2'){
				if(formData.venue == 'Area')
					formData['stuff_selection'] = $(form).find('select#stuff_selection').val();
				else
					formData['phone'] = getFullNumb($(form));
			}
			else if(step == 'form-3'){
				formData['staff_areas'] = prepareMultiSel($(form).find('select[name="staff_areas"]'));
				formData['staff_services'] = prepareMultiSel($(form).find('select[name="staff_services"]'));
				formData['staffClasses'] = prepareMultiSel($(form).find('select[name="staffClasses"]'));
				formData['phone'] = getFullNumb($(form));

				formData['commission_role'] = prepareMultiSel($(form).find('select[name="commission_role"]'));
				formData['commission_income_category'] = prepareMultiSel($(form).find('select[name="commission_income_category"]'));
				formData['commission_referral_source'] = prepareMultiSel($(form).find('select[name="commission_referral_source"]'));

				formData['per_session_role'] = prepareMultiSel($(form).find('select[name="per_session_role"]'));
				formData['per_session_service'] = prepareMultiSel($(form).find('select[name="per_session_service"]'));
				formData['per_session_class'] = prepareMultiSel($(form).find('select[name="per_session_class"]'));

			}
			else if(step == 'form-4'){
				if(formData.form_type_opt == 'Service'){
					if(formData['catText'] == '1 on 1')
						formData['one_on_one_staffs'] = prepareMultiSel($(form).find('select[name="one_on_one_staffs"]'));
					else if(formData['catText'] == 'TEAM Training')
						formData['team_staffs'] = prepareMultiSel($(form).find('select[name="team_staffs"]'));

					formData['area'] = prepareMultiSel($(form).find('select[name="area"]'));
				
					/*$.each($('#resource-form').find(':input').serializeArray(), function(i, obj){
						formData[obj.name] = obj.value
					});*/
				}
				else{
					formData['classStaffs'] = prepareMultiSel($(form).find('select[name="classStaffs"]'));
					formData['classAreas'] = prepareMultiSel($(form).find('select[name="classAreas"]'));
					formData['classMembership'] = prepareMultiSel($(form).find('select[name="classMembership"]'));
				}
			}
			else if(step == 'form-5'){
		
				formData['pro_category'] = prepareMultiSel($(form).find('select[name="pro_category"]'));
				formData['pro_size'] = prepareMultiSel($(form).find('select[name="pro_size"]'));
				var expirey_date = $(form).find('input[name="expirey_date"]').val();
				var expirey_date_after_format = '';
				if(expirey_date)
					expirey_date_after_format = dateStringToDbDate(expirey_date);

				if(expirey_date_after_format !='Invalid date')
					formData['expirey_date'] = expirey_date_after_format;
				else
					formData['expirey_date'] = '';
				
				formData['description'] = CKEDITOR.instances.description.getData();
				
			}
			else if(step == 'form-6'){
				
				formData['numb'] = getFullNumb($(form), $(form).find('input[name="numb"]'));

				var $referralNetworkFieldVal = $(form).find('input[name="referralNetwork"]:checked').val();
				if($referralNetworkFieldVal == 'Client'){                    
					formData['referralId'] = $(form).find('input[name="clientId"]').val();
					formData['referralName'] = $(form).find('.clientreferralName').val();

					}                      
				else if($referralNetworkFieldVal == 'Staff'){
					formData['referralId'] = $(form).find('input[name="staffId"]').val();
					formData['referralName'] =$(form).find('.staffreferralName').val();

				}
				else if($referralNetworkFieldVal == 'Professional network'){
					formData['referralId'] = $(form).find('input[name="proId"]').val();
					formData['referralName'] = $(form).find('.professionalreferralName').val();

				}
				else{
					formData['referralId'] = '';
					formData['referralName'] = '';
				}
					
				formData['goalHealthWellness'] = prepareMultiSel($(form).find('select[name="goalHealthWellness"]'));
			}
			else if(step == 'form-7'){
				formData['location'] = prepareMultiSel($(form).find('select[name="location"]'));
				formData['phone'] = getFullNumb($(form));
			}
			else if(step == 'form-8'){
				formData['member_category'] = prepareMultiSel($(form).find('select[name="member_category"]'));
				formData['member_added_group'] = prepareMultiSel($(form).find('select[name="member_added_group"]'));
				formData['mem_Classes'] = prepareMultiSel($(form).find('select[name="mem_Classes"]'));
				formData['notify_staff'] = prepareMultiSel($(form).find('select[name="notify_staff"]'));

				/*formData['mem_services'] = prepareMultiSel($(form).find('select[name="mem_services"]'));*/
				//console.log(formData);
			}
 			
 			var submitBtn = $(this)	
 			if(form.find("input[name='salesProcessCompleted']").length){
 				if((formData['client_status'] != 'active' && formData['client_status'] != 'contra') || canMakeClientActive('updateClient('+JSON.stringify(formData)+')')){
 					updateClient(formData)
					/*salesProcessUpgradeCheck(formData.client_status, function(){
							businessStepsFormAjax(submitBtn, formData)
						}, function(){ 
							updateClientWithoutStatus(submitBtn, formData)
						});*/
				}
			}
			else
				console.log(formData);
				businessStepsFormAjax(submitBtn, formData);
		}

		scrollToTop(form);
		if(subview)
			parent.scrollToTop(form);

		return false;
	});
});
/* end: SCRIPTS FOR SUBMITTING FORM -*/

function updateClient(formData){
	var submitBtn = $('.btn-add-more-form');
	
	salesProcessUpgradeCheck(formData.client_status, function(){
		businessStepsFormAjax(submitBtn, formData)
	}, function(){ 
		updateClientWithoutStatus(submitBtn, formData)
	});
}

function updateClientWithoutStatus(submitBtn, formData){
	delete formData.client_status;
	businessStepsFormAjax(submitBtn, formData)
}

function businessStepsFormAjax(submitBtn, formData){
	console.log(formData);
	var //submitBtn = $('.btn-add-more-form'),
		form = submitBtn.closest('form'),
		step = form.attr('id');
		toggleWaitShield('show');
	$.ajax({
		url: $(form).attr('action'),
		method: "POST",
		data: formData,
		success: function(data) {
			toggleWaitShield('hide');
			var data = JSON.parse(data);
			console.log(data);
            if(data.status == "hourFailed"){
				swal({
					title: data.msg,
					type: "warning",
					showCancelButton: false,
					confirmButtonColor: "#d43f3a",
					confirmButtonText: "Done",
					allowOutsideClick: true,
				});
			}else if(data.status == "added"){
				var subview = form.closest('.swMain').data('is-subview');
				if(subview){
					if(step == 'form-2')
						var subviewData = {dd:'location', insertId:data.insertId, data:formData.location_training_area};
					else if(step == 'form-3'){
						if(!data.linkedWithArea)
							//parent.$('#form-4 select[name="classStaffs"]').removeClass('staff')
							parent.$('#form-4 select.staff').removeClass('staff')
						
						var subviewData = {dd:'staff', insertId:data.insertId, data:formData.first_name + ' ' + formData.last_name};
					}
					else if(step == 'form-4' && formData.form_type_opt == 'Service'){
						if(formData.category == 1)
							var subviewData = {dd:'service', insertId:data.insertId, data:formData.team_name};
						else if(formData.category == 2)
							var subviewData = {dd:'service', insertId:data.insertId, data:formData.one_on_one_name};
					}
					else if(step == 'form-4')
						var subviewData = {dd:'class', insertId:data.insertId, data:formData.className};
					else if(step == 'form-6')
						parent.addSubviewClient({id:data.insertId, name:formData.first_name+' '+formData.last_name, email:formData.email, phone:formData.numb, accStatus:formData.client_status});
					else if(step == 'form-7'){
						parent.getAllContactOnload();
					}

					if(typeof subviewData != 'undefined')
						parent.closeSubview("updateRelatedDd($('select."+subviewData.dd+"'), "+subviewData.insertId+", '"+subviewData.data+"', true)");
					// else if(step == 'form-')
						//parent.closeSubview();
					else
						parent.closeSubview();
				}
				else{
					clearForm($(form), clearFormBusiness());
					if(step == 'form-2'){
						if(formData.venue == 'Location') {
							if($(form).data('form-mode') == 'unison'){
								updateRelatedDd($('select.location'), data.insertId, formData.location_training_area);

								var step7Form = $('#form-7');
								$(step7Form).find("input[name='address_line_one']").val(formData.address_line_one);
								$(step7Form).find("input[name='address_line_two']").val(formData.address_line_two);
								$(step7Form).find("input[name='city']").val(formData.city);
								$(step7Form).find("select[name='country']").val(formData.country);
								$(step7Form).find("select[name='country']").trigger("changed");
								$(step7Form).find("select.states").data('selected', formData.state);
								$(step7Form).find("input[name='postal_code']").val(formData.postal_code);
							}

							//$(form).find('select[name="state"]').closest('.form-group').hide();

							resetSelFixLoc(form);
							$(form).find("select[name='country']").trigger("changed");
						}

						$(form).find('.showHours').each(function(){
							hourReset($(this), formData.venue);	
						});

						if($(form).data('form-mode') == 'unison')
							showskipbutton(form);
						else{
							if(formData.venue == 'Location'){
								if($(form).data('form-mode') == 'standAlone')
							    window.location.href = public_url+"settings/business/locations/"+data.insertId;
							}
							else{
								//redirect to area 
								if($(form).data('form-mode') == 'standAlone')
							    window.location.href = public_url+"settings/business/areas/"+data.insertId;
							}
						}
					}
					else if(step == 'form-3'){ 
						resetSelFixLoc(form);
						//showskipbutton(form);

						$(form).find("select[name='country']").trigger("changed");

						$(form).find('.showHours').each(function(){
							hourReset($(this));	
						});
						
						if($(form).data('form-mode') == 'unison'){
							showskipbutton(form);
							if(!data.linkedWithArea)
								$('#form-4 select.staff').removeClass('staff')
							updateRelatedDd($('select.staff'), data.insertId, formData.first_name + ' ' + formData.last_name);
							$('#form-4 select.classStaff, #form-4 select.serviceStaff').addClass('staff')
						}
						else 
						 window.location.href = public_url+"settings/business/staffs/"+data.insertId;

					}
					else if(step == 'form-4'){
						//showskipbutton(form);
						if(formData.form_type_opt == 'Service'){ 
							if($(form).data('form-mode') == 'unison'){
								if(formData.category == 1)
									updateRelatedDd($('select.service'), data.insertId, formData.team_name)
								else if(formData.category == 2)
									updateRelatedDd($('select.service'), data.insertId, formData.one_on_one_name)
							}

							toggleServiceCatFieldset($(form).find('select[name="category"]'), '');
							populateAreaDd($(form).find('select[name="location"]'))
						}
						else
							populateAreaDd($(form).find('select[name="classLoc"]'))

						if($(form).data('form-mode') == 'unison')
							showskipbutton(form);
						else{
							if(formData.form_type_opt == 'Service'){
								if($(form).data('form-mode') == 'standAlone')
							    window.location.href = public_url+"settings/business/services/"+data.insertId;
							}
							else{
								//redirect to class
								if($(form).data('form-mode') == 'standAlone')
							    window.location.href = public_url+"settings/business/classes/"+data.insertId;
							}
							//remove all resource rows after submit
							$('#resource-form .resource-clone-class:not(.hidden)').remove();
						}
					}
					else if(step == 'form-5'){
						//showskipbutton(form);

						//if($(form).data('form-mode') == 'standAlone')
							//window.location.href = public_url+"settings/business/products/"+data.insertId;

						cb(moment().subtract(29, 'days'), moment());

						$(form).find('span#stockLevelUi').html('Unlimited');		
						$(form).find('input[name="stockLevel"]').val('Unlimited');
						$(form).find('.stockHelperFields').addClass('hidden');	

						if($(form).data('form-mode') == 'unison')
							showskipbutton(form);
						else //if($(form).data('form-mode') == 'standAlone')
							window.location.href = public_url+"settings/business/products/"+data.insertId;
					}
					else if(step == 'form-6'){
						if($(form).data('form-mode') == 'standAlone')
							window.location.href = public_url+"client/"+data.insertId;
						else{
							showskipbutton(form);
							toggleReference('');
						}
					}
					else if(step == 'form-7'){
						toggleTrainerCb($(form).find('select[name="type"]'));
						$(form).find("select[name='country']").trigger("changed");

						if(submitBtn.hasClass('finish'))
							redirectToBusinessEdit();
						else{
							if($(form).data('form-mode') == 'unison')
								showskipbutton(form);
							else{
								//redirect
								if($(form).data('form-mode') == 'standAlone')
							    window.location.href = public_url+"settings/business/contacts/"+data.insertId;
							}
						}
					}
					else if(step == 'form-8' ){ //membership form
						location.href = public_url+"settings/business/memberships";
					}

					$(form).find('select').selectpicker('refresh');

					saveSuccess(form, {action:'show', type:'store'});
					if(step == 'form-3' && formData.login_with_email)
						saveSuccess(form, {action:'show', type:'loginAccountCreate'});
					else if(step == 'form-6' && formData.login_with_email)
						saveSuccess(form, {action:'show', type:'loginAccountCreate'});
				}
			}
			else if(data.status == "updated"){
				if(step == 'form-1'){
	               $(form).find("input[name='cp_web_url']").val(data.businessSlug);
	               
				}

				else if(step == 'form-3'){

					//cb = $(form).find('.js-ifCreateLogin');
					//passwordFieldsetAfterSubmit({cb:cb, formData:formData, newPwdField:form.find("input[name='staffNewPwd']"), newPwdCnfmField:form.find("input[name='staffNewPwdCnfm']")});
					/*cb.each(function(){
						var $this = $(this),
							name = $this.attr('name');

						if(name in formData){
							clearPassword({newPwdField:form.find("input[name='staffNewPwd']"), newPwdCnfmField:form.find("input[name='staffNewPwdCnfm']")});
							$this.data('old-login-with-email', 1)
						}
						else
							$this.data('old-login-with-email', 0)

						togglePasswordFieldset($this)
					});*/

					$(form).find('input[name="prevServices"]').val(formData['staff_services'])
				}
				else if(step == 'form-5')
					$(form).find('input[name="stockLevelHistory"]').val('')

				else if(step == 'form-6'){
					//cb = $(form).find('.js-ifCreateLogin');
					//passwordFieldsetAfterSubmit({cb:cb, formData:formData, newPwdField:form.find("input[name='clientNewPwd']"), newPwdCnfmField:form.find("input[name='clientNewPwdCnfm']")});

					//history.back(1);

					/*var cookieName = calcRecord_indexCookieName('client'),
						cookie = $.cookie(cookieName);
					if(cookie){
						cookie = JSON.parse(cookie);
						cookie.use = true;
						$.cookie(cookieName, JSON.stringify(cookie), {path:'/'})
					}
					window.location.href = public_url+"clients";*/
				}
				
	            if(step != 'form-1'){
	            	window.location.href = document.referrer;
	            }
	             
				saveSuccess(form, {action:'show', type:'update'});
			}
			else if(data.status == "error"){
				$.each(data.errorData, function(key, val){
					$.each(this, function(errorType, message){
						if(errorType == "phoneExist"){
							if(step == 'form-6')
								var field = $(form).find("input[name='numb']");
							else
								var field = $(form).find("input[name='phone']");
						}
	                    else if(errorType == "urlExist")
							var field = $(form).find("input[name='cp_web_url']");
						else if(errorType == "emailExist" )
							var field = $(form).find("input[name='email']");									
						else if(errorType == "locNameExist" )
							var field = $(form).find("input[name='location_training_area']");
						else if(errorType == "maximumLimit")
						{
							swal(message);
						}
						//setFieldInvalid(field.closest('.form-group'), field.next("span"), message)
						setFieldInvalid(field.closest('.form-group'), message, field.next("span"))
					});							
				});
			}
			else if(data.status == "reload"){
				//location.reload();
				//parent.closeSubview();
				parent.location.reload(true);
			}
		}
	});
}

/*start: UI SELECTABLE */
/*$(function() {
	$(".selectable").selectable({
		stop: function() {
			toggleStep2Fields(this, true);
			/*$( ".ui-selected", this ).each(function() {
				var text = $( this ).text( );
				result.val( text );
				alert( result.val());
			});*/
		/*}
	});
});*/
$(document).ready(function() {
	$(".selectable").selectable({
		stop: function() {
			toggleStep2Fields(this, true);
		}
	});
	toggleStep2Fields($( ".selectable" ));  

	$(".form-type-selectable").selectable({
		stop: function() {
			toggleStep4FormTypeFields($(this));
		}
	});
	toggleStep4FormTypeFields($('.form-type-selectable'));
});
function toggleStep2Fields(element, ifResetHours){
	if(ifResetHours == undefined)
		ifResetHours = false;
	var selectedVal = $(".ui-selected", element).text().trim();
	var form = $(element).closest('form');
	$(element).next('.form-group').find('input').val(selectedVal);
	var notForLocFields = $(form).find('.notForLoc');
	var notForAreaFields = $(form).find('.notForArea');
	var addmoreBtn = $(form).find('.btn-add-more-form');
	if(selectedVal == 'Location'){
		$(notForLocFields).hide(); 
		$(notForAreaFields).show();
		$(addmoreBtn).find('span').text('Add Location');
	}
	else{
		$(notForLocFields).show();
		$(notForAreaFields).hide();
		$(addmoreBtn).find('span').text('Add Area');
	}
	if(ifResetHours){
		form.find('.showHours').each(function(){
			hourReset($(this), selectedVal);	
		});

		if(selectedVal != 'Location'){
			var val = $('#form-2 select[name="location"]').val();
			if(val)
				populateHours(val, $('#form-2'));	
		}
	}
	$(addmoreBtn).removeClass('hide');
}

function toggleStep4FormTypeFields($element){
	var selectedVal = $(".ui-selected", $element).text().trim(),
		form = $element.closest('form'),
		formMode = form.data('form-mode'),
		formTypeClassFields = form.find('.form-type-class-fields'),
		formTypeServiceFields = form.find('.form-type-service-fields'),
		serviceCat = form.find('select[name="category"]'),
		addmoreBtn = form.find('.btn-add-more-form');;

	$element.find('input').val(selectedVal);
	if(selectedVal == 'Service'){
		formTypeServiceFields.show(); 
		formTypeClassFields.hide();
		toggleServiceCatFieldset(serviceCat)
		if(formMode == 'unison')
			form.prop('action', public_url+'settings/service')
		addmoreBtn.find('span').text('Add Service');
	}
	else{
		formTypeServiceFields.hide();
		formTypeClassFields.show();
		toggleServiceCatFieldset(serviceCat, '')
		if(formMode == 'unison')
			form.prop('action', public_url+'settings/class/create')
		addmoreBtn.find('span').text('Add Class');
	}
}
/* end: UI SELECTABLE */

/* start: FIXED LOCATION TOGGLER */
$(function() {
	/*$( ".selectable_fixed_location" ).selectable({
		stop: function() {
			var selectedVal = $(".ui-selected", this).text().trim();
			var form = $(this).closest('form');
			$(this).next('.form-group').find('input').val(selectedVal);
			var notForMoblocFields = $(form).find('.notForMobloc');
			if(selectedVal == 'Fixed Location')
				$(notForMoblocFields).show(); 
			else
				$(notForMoblocFields).hide();
		}
	});*/
});
/* end: FIXED LOCATION TOGGLER -*/

/* start: UPDATE STATE DROPDOWNS */
/*function updateState(contryDd){
	if(contryDd.length){
		var country_code = contryDd.val(),
			selectedStates = contryDd.closest('form').find('select.states');
			
		if(country_code == "" || country_code == "undefined" || country_code == null){
			selectedStates.html('<option value="">-- Select --</option>');
			selectedStates.selectpicker('refresh');
		}
		else{		
			$.ajax({
				url: public_url+'countries/'+country_code,
				method: "get",
				data: {},
				success: function(data) {
					var defaultState = selectedStates.data('selected'),
						formGroup = selectedStates.closest('.form-group');

					selectedStates.html("");
					$.each(data, function(val, text){
						var option = '<option value="' + val + '"';
						if(defaultState != '' && defaultState != null && val == defaultState)
							option += ' selected';
						option += '>' + text + '</option>';
						selectedStates.append(option);
					});

					contryDd.selectpicker('refresh');
					selectedStates.selectpicker('refresh');
					setFieldValid(formGroup, formGroup.find('span.help-block'))
				}
			});
		}
	}
}*/
//$('body').on('changed.bs.select', '.countries', function(e){
/*$('.countries').on('changed.bs.select', function(e){
	updateState($(this));
});*/
$(document).ready(function() {
	updateState($('#form-1').find('select.countries'));
	updateState($('#form-2').find('select.countries')); 
});
/* end: UPDATE STATE DROPDOWNS */

/* start: EPIC ACCORDIAN */
$(document).ready(function(){
	var wizard = $('#wizard');
	if(wizard.length){
		FormWizard.init('#wizard');

		var epicAccordianElem = $('.epic-accordion');
		epicAccordianElem.find('.panel-body').css({'display': 'none', 'padding-top': 0, 'padding-bottom': 0});
		
		epicAccordianElem.find('.panel-heading .js-chevron').on('click', function(){
			toggleSections($(this));
		});
		toggleSections(epicAccordianElem.find('.panel-heading[data-step="1"] .fa-chevron-up'));
		//toggleSections(epicAccordianElem.find('.panel-heading .fa-chevron-down'));//[openStep]
	}
});
function toggleSections(elem){
	if(elem.hasClass('fa-chevron-up'))
		elem.removeClass('fa-chevron-up').addClass('fa-chevron-down')
	else
		elem.removeClass('fa-chevron-down').addClass('fa-chevron-up')

	var selfHeading = elem.closest('.panel-heading'),
		selfPanel = elem.closest('.panel'),
		otherPanels = selfPanel.siblings();

	otherPanels.find('.panel-body').slideUp(600);
	otherPanels.find('.panel-heading .fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-up');

	selfPanel.find('.panel-body').slideToggle(600);


	/*var pclass = $(elem).attr('class');
	if( pclass == 'fa fa-chevron-up pull-right') {
		$(elem).attr('class', 'fa fa-chevron-down pull-right');
	} else if( pclass == 'fa fa-chevron-down pull-right' ) {
		$(elem).attr('class', 'fa fa-chevron-up pull-right');
	}
	var self = $(elem).closest('.panel-heading');
	self.closest('.panel').siblings().find('.panel-heading .fa-chevron-up').attr('class', 'fa fa-chevron-down pull-right');
	self.closest('.panel').siblings().find('.panel-body').removeClass('panel-top-90');
	self.closest('.panel').siblings().find('.panel-body').slideUp(600);
	self.closest('.panel').find('.panel-body').slideToggle(600);*/

	var step = selfHeading.attr('data-step'),
		top = (step * 35) + 90;
	if(top == NaN)
		top = 224;
	$('#wizard > ul.anchor').css({'top': top+'px'});
}
/* end: EPIC ACCORDIAN */

/*function setDbTimeToTimeString(field, dbTime){
	field.val(moment(dbTime, 'HH:mm:ss').format("h:mm A"));
}*/


    //$(document).on( 'click', '.panel', function() {
	//$(this)/*.closest('.panel')*/.find('.panelcls').removeClass('panel-collapse collapse');
	//});
	/*$( document ).on( 'click', '.pay_for', function() {
		var checked = $(this).is(":checked");
		var perSessionRateType=$('#per_session_rate_options').val();

		if(!checked){
			$('.sessionattendeestiercls').hide();
    	   $('.sessionattendeescls').hide();
		}
		else{
			if(perSessionRateType =='base_rate_attendees'){
		    	$('.sessionattendeescls').show();
		        $('.sessionattendeestiercls').hide();}
		            else if(perSessionRateType =='base_rate_tier'){
		    	$('.sessionattendeestiercls').show();
		        $('.sessionattendeescls').hide();}

		}
		
        
    });	*/	
$(document).ready(function() {
	var formMode = $('#form-4').data('form-mode');
	/*if(formMode=='unison'){
		$('#resource-form').removeClass('hidden');
		$('#resource-form').empty();
		$('#resource-form').append('<legend>Resource</legend><div class="row">xyz</div>');
	}*/
	if(formMode=='standAlone'){
		var hiddenType = $('input[name="form_type_opt"]');
		if(hiddenType.length && hiddenType.val()=='Service'){
			resourceOnCategChange($('select#category'));
		}
	}
});

function resourceOnCategChange(elem){
	categoryValue = elem.val();
	 if(categoryValue !=1 && categoryValue !=2)
	 	$('#resource-form').addClass('hidden');
	 else
	 	$('#resource-form').removeClass('hidden');
}

// $('body').on('change','.taxable',function(){
//   console.log('hi');
// });

	$('body').on('change','.installment_amount,select.gstTaxable',function(){
		console.log('ho');
		calculateMembPrice();
	});

  function calculateMembPrice(){
	var tax= $('.gstTaxable').find('option:selected').val();
	var amount = parseFloat($('.installment_amount').val());
	calTax = parseFloat(amount * 15/100);
	if(amount != '' && !isNaN(amount)){
		if(tax == 'Excluding'){
			calTotalPrice= amount + calTax;
			$('.installment_amount').val(amount.toFixed(2));
			$('.total-price').val(calTotalPrice.toFixed(2));
		}else if(tax == 'Including'){
			calTotalPrice= amount/1.15;
			$('.total-price').val(amount.toFixed(2));
			$('.installment_amount').val(calTotalPrice.toFixed(2));
		}else{
			$('.total-price').val(amount.toFixed(2));
			$('.installment_amount').val(amount.toFixed(2));
		}
	}
  }

//   $(document).ready(function(){
// 	calculateMembPrice();
//   })
  
