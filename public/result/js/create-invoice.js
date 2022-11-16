var productInfo,currentDiscountRow,isPopover=0;
var allRows = applyDiscount = 0;
var currentProductId = 0;
var currentStaffValue = '';
var currentProductValue = '';
var currentClientValue = '';
var invoiceModal = $('#invoiceModal'),
discountPopoverOpt = {
			html: true,
			content: "<div class='row popoverContent' data-action='apply_coupon'><div class='col-md-12'><select class='form-control discount-type' name='discount_type'><option value=''>Chosse discount</option><option value='General/Misc'>General/Misc</option></select></div><div class='col-md-12' style='margin-top:10px; margin-bottom:10px;'><input type='text' name='amount' class= 'amount hide' placeholder = 'E.g. 20% or 20'><input type ='hidden' name='row_num' class='discount-row-num'/><input type ='hidden' name='totalAmount' class='discount-total-amount'/></div></div><div class='clearfix'><div class ='row'><div class ='col-md-6'><button class='btn btn-primary closePopup' type='button'> Cancel </button></div><div class ='col-md-3'><button class='btn btn-primary discount-submit' type='button' style ='width:80px;white-space:normal;'> Apply</button></div></div></div>",
			container: invoiceModal,
			title: "<strong>Apply a discount</strong>",
			placement: 'top',
			trigger: 'click'
		};

$(document).ready(function(){
	

  $('.clone').hide();
	var totalPrice = $('.invoice-block').find('.total-nzd').text();
	var amountPaid = $('.invoice-block').find('.amount-paid').text();
	var balance = parseFloat(totalPrice)- parseFloat(amountPaid);
	var remaining = balance.toFixed(2);
	$('.invoice-block').find('.due-amount').text(remaining)
	$('body').on('click', function (e) {
		isPopover = $('#invoiceModal').find('.popover').length;
		if (isPopover == 1) {
				//alert('cdx');
				//$('.popover').addClass('hide');
				//applyDiscount =1;
			}
	});
	$('body').on('click', '.payment-option', function(e){
		e.preventDefault();
		$(this).siblings('.raise-invoice').removeClass('hide');
	});
	$('body').on('click', '.raise-invoice', function(e){
		var sesId = $(this).closest("tr").find('.service-id').val();
		var staffId = $(this).closest("tr").find('.staff-id').val();
		var clientId = $(this).closest("tr").find('.cliend-id').val();
		var invoicedate = $(this).closest("tr").find('.invoice-date').val();
		var price = $(this).closest("tr").find('.price').val();
		var areaId = $(this).closest("tr").find('.location-area-id').val();
		var productName = $(this).closest("tr").find('.item-desc').val();
		var businessId = $('#invoiceModal').find('input[name="business-id"]').val();
		$.ajax({
			url: public_url+'raise-invoice',
			method: "POST",
			data: {'sesId':sesId,'staffId':staffId,'clientId':clientId,'invoicedate':invoicedate,'price':price,'areaId':areaId,'productName':productName,'business_id':businessId},
			success: function(data){
				var data = JSON.parse(data);
				if(data.status == "ok"){
					$('#invoiceModal').modal('hide');
					location.reload();
				}
			}
		});
	});
	$('body').on('click', '.discount-submit', function(e){
		var totalAmount = currentDiscountRow.find('.total').val();
		var amount = $(this).closest('.clearfix').siblings('.popoverContent').find('.amount').val();
		var total = $(this).closest('.discount-section').siblings('.total-price-div').find('.total').val();
		if(amount == ''){
			alert('Please enter amount');
		} else if(parseInt(amount) > total) {
			alert('Please enter less amount from total Amount');
		} else {
			submitDiscountPop($(this),totalAmount);
		}
		e.preventDefault();	
	});
	$('body').on('click', '.update-invoice', function(e){
		e.preventDefault();	
		var staffClass = $('.form-group').find('.col-md-2.staff-name');
		currentStaffValue = $(this).find('option:selected').val();
		var paidAmount = $('.paid-amount').val();
		var totalAmount = $('.price-section').find('.total-amount').val();
		var invoiceId = $('.invoice-id').val();
		if(currentStaffValue == ''){
				setFieldInvalid(staffClass);
			} else if(parseFloat(paidAmount)> parseFloat(totalAmount)){
				$('.modal-body').find('.invoice-error-msg').removeClass('hide');
			} else {
				var data = $('#invoice-form').serialize();
				$.ajax({
					url: public_url+'update-invoice',
					method: "POST",
					data: {'form_data':data},
					success: function(data){
						var data = JSON.parse(data);
						if(data.status == "ok"){
							$('#invoiceModal').modal('hide');

							location.reload();
						}
					}
				});
			}

	});
	$('.apply-discount').each(function(){
		$(this).popover(discountPopoverOpt);
	});


	$('body').on('click', '.apply-discount', function(e){
		if(applyDiscount == 1){
		  $('.popover').removeClass('hide');
		}
		currentDiscountRow = $(this).closest('.row');
		$('.discount-submit').hide();
		$('.discount-type').val('');
		$('.amount').val('');
		$('.amount').addClass('hide');
		e.preventDefault();
	});
		$('body').on('click', '.add-appointment', function(e){
			var totalTr = $('#sample-table-1').find('tr').length;
			if( totalTr== 1){
				$('#sample-table-1').find('tr.all-apointment').addClass('hide');
				$('.emptyMsg').removeClass('hide');
			} else {
				$('.emptyMsg').addClass('hide');
			}
		});
		$('body').on('change', '.discount-type', function(e){
			var optionSelected = $(this).val();
			if(optionSelected != ''){
				$('.discount-submit').show();
				$('.amount').removeClass('hide');
			} else {
				$('.discount-submit').hide();
				$('.amount').addClass('hide');
			} 
		});
		$('.applyDiscount').hide();
		var public_url = $('meta[name="public_url"]').attr('content');
	  	$('.add-appointment').addClass('disabled');
		$.get(public_url+'clients/all', function(data){
			
			$('#clientId').val(data[0].id);
			$('.form-group').find('textarea[id="client_name"]').html(data[0].name);
			// $('#client_name').html(data[0].name);

		$('.clientList').typeahead({
			source:data,
			items:'all',
			afterSelect:function(selection){
				var clientClass = $('.row').find('.col-md-6.client-div');
				clientClass.removeClass('has-error');
				var field = $(this).prop('$element'),
					modal = field.closest('.modal')
					modalId = modal.attr('id');
					currentClientValue = field.val();
				field.siblings('input[name="clientId"]').val(selection.id);
				if(modalId == 'appointModal' || modalId == 'classModal'){
					modal.find('input[name="clientEmail"]').val(selection.email);
					modal.find('input[name="clientNumb"]').intlTelInput("setNumber", selection.phone)
				}
			}
		})
	},'json');
		$('.notes').hide();
		$('.show-calender').datepicker({
			format: 'd M yyyy'
		 });
		$(".show-calender").datepicker('setDate', new Date());
			$("#invoice-date").datepicker({
			  format: 'd M yyyy',
			  autoclose: true,
			}).on('changeDate', function (selected) {
				var startDate = new Date(selected.date.valueOf());
				$('#due-date').datepicker('setStartDate', startDate);
			}).on('clearDate', function (selected) {
				$('#due-date').datepicker('setStartDate', null);
			});
			$("#due-date").datepicker({
			   format: 'd M yyyy',
			   autoclose: true,
			}).on('changeDate', function (selected) {
			   var endDate = new Date(selected.date.valueOf());
			   $('#invoice-date').datepicker('setEndDate', endDate);
			}).on('clearDate', function (selected) {
			   $('#invoice-date').datepicker('setEndDate', null);
			});          

		$('body').on('click', '.addmore-product', function(e){
			e.preventDefault();
			var $this = $(this);
			var rowParent = $this.closest('.row').siblings('.form-group');
			var cloneRow= rowParent.find('.original');
			if(!cloneRow.length){
			  $('.clone').show();
         	  generateRow();
			  $('.product-row').hide();
			} else {
				generateRow();
				var lastDiv = $('.form-group > div:last');
				lastDiv.find('.col-md-2.staff-name').removeClass('has-error');
				lastDiv.find('.col-md-2.product-name').removeClass('has-error');
				currentProductId = 0;
			}
		});
	
	/* Event handler for 'Remove product' in create invoice modal */
	
	$('body').on('click', '.removeProduct', function(e){
		e.preventDefault();
		var i =1,pId,sId,pName,sName,qty,uPrice,tax,total,sStatus,sDate,sLocation,sto,sday,staffId;
		var currentRow = ($(this).closest('.row'));
		var tableControl= document.getElementById('sample-table-1');
		i = currentRow.find('input.row-num').val();
		pId = currentRow.find('input[name="productId'+i+'"]').val();
		sId = currentRow.find('input[name="serviceId'+i+'"]').val();
		staffId = currentRow.find('select[name="staffName'+i+'"]').val();
		pName = currentRow.find('input[name="hideServiceName'+i+'"]').val();
		sName = currentRow.find('input[name="hideStaffName'+i+'"]').val();
		sStatus = currentRow.find('input[name="hideStatus'+i+'"]').val();
		sDate = currentRow.find('input[name="hideDate'+i+'"]').val();
		sLocation = currentRow.find('input[name="hideAreaName'+i+'"]').val();
		sto = currentRow.find('input[name="hideServiceTo'+i+'"]').val();
		sday = currentRow.find('input[name="hideServiceDay'+i+'"]').val();
		qty = currentRow.find('input[name="quantity'+i+'"]').val();
		uPrice = currentRow.find('input[name="unit_price'+i+'"]').val();
		tax = currentRow.find('input[name="tax'+i+'"]').val();
		total = currentRow.find('input[name="item-total'+i+'"]').val();
		$(this).closest('.row').remove();
		allRows--;
		if(allRows < 0){
			$('.tax-value').text(0);
			$('.tax-amount').val(0);
			$('.total-value').text('$'+0);
			$('.total-amount').val(0);
		} else {
			var amountWithTax = taxValue =0;
			amountWithTax = getTotalAmount();
			$('.total-value').text('$'+amountWithTax);
			$('.total-amount').val(amountWithTax);
			taxValue = getTaxAmount();
			$('.tax-value').text(taxValue);
			$('.tax-amount').val(taxValue);
		}
		var tableRowHtml = '';
		if(pName){
			tableRowHtml += '<tr><td><input type="checkbox" name="" value="1" class = "appoint-checkboxs"></td><td class ="s-name" id ="'+pId+'" data = "'+sId+'">'+pName+'</td><td class ="s-price">$'+total+'</td><td class ="s-status"><span class="label label-inverse"> '+sStatus+' </span></td><td class ="s-date"  id ="'+sto+'" data ="'+sday+'"><i class="fa fa-calendar"></i> '+sDate+'</td><td class ="s-staff" id ="'+staffId+'"> <i class="fa fa-user"></i> '+sName+' </td><td class ="s-area"><i class="fa fa-home"></i> '+sLocation+' </td></tr>';
		$(tableControl).append(tableRowHtml);
		}
	});
	$(document).on('keyup','.quantity', function(e){
		var current = $(this).closest('.row');
		var quantity = $(this).val();
		var discount = 0;
		var quantityStock = current.find('input.quantity-stock').val();
		if(quantity != ''){
			if($.isNumeric(quantity)){
			// if(quantity > quantityStock){
				//  alert('Quantity out of stock');
			 //}	
			} else {
			 alert('Please enter Numeric value');	
			}
			var price = current.find('input.unit-price').val();
			var totalUnitPrice = quantity * price;
			if(price < 0){
			  total = 0;
			} else {
			  total  = getTotalAmountAfterTax(totalUnitPrice);
			}
			current.find('input.total').val(total.toFixed(2));
			var amountWithTax = getTotalAmount();
			$('.total-value').text('$'+amountWithTax);
			$('.total-amount').val(amountWithTax);
			var taxValue = getTaxAmount();
			$('.tax-value').text(taxValue);
			$('.tax-amount').val(taxValue);
		} else {
			current.find('input.total').val('0');
		}
	});
	$('.notes-link').on('click', function(e){
		e.preventDefault();
		$('.notes').show();
		$(this).hide();
		});
		var area = $( ".location-area option:selected" ).val();
		$(".clientList").on('change',function(){
			var clientId = $('input[name="clientId"]').val();
			var areaName = $( ".location-area option:selected" ).text();
			if(clientId != ''){
				$('.add-appointment').removeClass('disabled');
				//getAppointment(area,public_url,clientId,areaName);
			}
		});
		getStaff(area,public_url);
		$('.location-area').on('change', function(e){
			var area = $( ".location-area option:selected" ).val();
			if(area != ''){
				$('.add-product').removeClass('disabled');
				getStaff(area,public_url);
			} else {
				$('.clientList').val('');
			}
		});
		$('body').on('click', '.edit-invoice', function(e){
			$('#invoiceModal').find('form').trigger('reset');
			if(allRows > 0){
				for(var j =0;j<allRows;j++){
					$('.form-group').find('.original').addClass('hide');
				}
				var lastDiv = $('.form-group > div:last');
				lastDiv.removeClass('hide');
				$('.form-group').find('.clone').hide();
				
			}
			var totalAmount = 0;
			var invoiceInfo = JSON.parse($('.invoice-info').val());
			var areaId = $('.area-id').val();
			var clientId = $('.client-id').val();
			var clientName = $('.client-name').val();
			var areaName = $('.area-name').val();
			var invoiceDate = $('.invoice-date').val();
			var dueDate = $('.due-date').val();
			//getAppointment(area,public_url,clientId,areaName);
			$.each(invoiceInfo, function(val, text){
				generateRow();
				if(text.type == 'product'){
					currentProductId = text.pd_id;
					$('.form-group').find('input[name="productId'+allRows+'"]').val(text.pd_id);
				} else {
					currentProductId = text.pd_id;
					$('.form-group').find('input[name="serviceId'+allRows+'"]').val(text.pd_id);
				}
				$('select.location-area').val(areaId);
				$('select.location-area').selectpicker('refresh');

				$('input.clientList').val(clientName);
				$('#invoice-date').val(invoiceDate);
				$('#invoice-date').datepicker('setDate',new Date(invoiceDate));
				$('#due-date').val(dueDate);
				$('#due-date').datepicker('setDate',new Date(dueDate));
				$('.form-group').find('a[name="discountRow'+allRows+'"]').show();
				if(text.desc == 'General/Misc'){
					$('.form-group').find('a[name="discountRow'+allRows+'"]').hide();
				}
				$('.form-group').find('textarea[name="productName'+allRows+'"]').val(text.desc);
				$('.form-group').find('select[name="staffName'+allRows+'"]').val(text.staff_id);
				$('.form-group').find('select[name="staffName'+allRows+'"]').selectpicker('refresh');
				$('.form-group').find('input[name="quantity'+allRows+'"]').val(1);
				$('.form-group').find('input[name="unit_price'+allRows+'"]').val(text.unit_price.toFixed(2));
				$('.form-group').find('input[name="tax'+allRows+'"]').val('GST');
				$('.form-group').find('input[name="item-total'+allRows+'"]').val(text.total.toFixed(2));
				totalAmount = parseFloat(totalAmount) + parseFloat(text.total);
				
			});
			var amountWithTax = getTotalAmount();
			$('.total-value').text('$'+amountWithTax);
			$('.total-amount').val(amountWithTax);
			var taxValue = getTaxAmount();
			$('.tax-value').text(taxValue);
			$('.tax-amount').val(taxValue);
			$('.form-group').find('.product-row').remove();
			lastDiv.addClass('hide');
	
	});
		$('body').on('change', 'select.staff-name', function(){
			currentStaffValue = $(this).find('option:selected').val();
			var staffClass = $(this).closest('.col-md-2.staff-name');
			if(currentStaffValue){
				staffClass.removeClass('has-error');
			} else {
				staffClass.addClass('has-error');
			}
		});
		$(".submit",invoiceModal).click(function(){
			var i,isValidate = true;
			if(allRows >0){
				for(i=1;i<=allRows;i++){
					var productValue = $('.form-group').find('textarea[name="productName'+i+'"]').val();
					var staffValue = $('.form-group').find('select[name="staffName'+i+'"]').val();
					if(productValue == ''){
						isValidate = false;
						var currentProductClass = $('.form-group').find('textarea[name="productName'+i+'"]').parents('.col-md-2.product-name');
						currentProductClass.addClass('has-error');
					}
					if(staffValue == ''){
						isValidate = false;
						var currentStaffClass = $('.form-group').find('select[name="staffName'+i+'"]').parents('.col-md-2.staff-name');
						currentStaffClass.addClass('has-error');
					}

				}
			} else {
				var clientClass = $('.row').find('.col-md-6.client-div');
				var productClass = $('.form-group').find('.col-md-2.product-name');
				var staffClass = $('.form-group').find('.col-md-2.staff-name');
				// if(currentClientValue == ''){
				// 	isValidate = false;
				// 	setFieldInvalid(clientClass);
				// }
				   
				if(currentProductValue == ''){
					isValidate = false;
					setFieldInvalid(productClass);
				} 
				// if(currentStaffValue == ''){
				// 	isValidate = false;
				// 	setFieldInvalid(staffClass);
				// }  
			}
			if(isValidate){
			   var invoiceId = $('.invoice-id').val();
				var data = $('#invoice-form').serialize();
				// console.log(data);return false;
				if(invoiceId){
					var requestUrl = public_url+'custom-invoice';
				} else {
					var requestUrl = public_url+'save-invoice';
				}
				//alert(requestUrl);
				$.ajax({
					url: requestUrl,
					method: "POST",
					data: {'form_data':data},
					success: function(data){
						// console.log('abhi');
						// console.log(data);

						var data = JSON.parse(data);
						if(data.status == "ok"){
							$('#invoiceModal').modal('hide');
							var openModal = $('#openModal').val();
							if(openModal.length > 0)
							{
								url = window.location.href;
								url = url.slice(0, url.lastIndexOf('/'));
								window.location.href =url;
							}
							else
							{
								location.reload();
							}
							
						}
					}
				});
			}
		});
		$(".add-to-invoice").click(function(){
			$('body').on('change', 'input[name="clientAppointCheckAll"]', function(){
				var clientAppointModal = $('#clientAppointModal');
				var appointCheckboxs = clientAppointModal.find('.appoint-checkboxs');
				var tableControl = $('#appointment-form').find('#sample-table-1');
				  var objOfValues = {};
				  var lastDiv = $('.form-group > div:last');
				  var i = 0,j,lastRowNum,generateRowCondition = 0;;
				  lastRowNum = lastDiv.find('input.row-num').val();
				  if(lastRowNum != ''){
					 i = parseInt(lastRowNum);
					 generateRowCondition = parseInt(lastRowNum);
					}
				if($(this).is(':checked')){
					appointCheckboxs.prop('checked', true)
					appointCheckboxsChecked = appointCheckboxs.length;
				} else {
					appointCheckboxs.prop('checked', false)
					appointCheckboxsChecked = 0;
				}
				
				$('.appoint-checkboxs:checked', tableControl).each(function() {
					i++;
					objOfValues['name']=$(this).closest('tr').find('td.s-name').text();
					objOfValues['ap_id']=$(this).closest('tr').find('td.s-name').attr('id');
					objOfValues['ser_id']=$(this).closest('tr').find('td.s-name').attr('data');
					objOfValues['price']=$(this).closest('tr').find('td.s-price').text();
					objOfValues['status']=$(this).closest('tr').find('td.s-status').text();
					objOfValues['staff']=$(this).closest('tr').find('td.s-staff').text();
					objOfValues['staff_id']=$(this).closest('tr').find('td.s-staff').attr('id');
					objOfValues['date']=$(this).closest('tr').find('td.s-date').text();
					objOfValues['service_to']=$(this).closest('tr').find('td.s-date').attr('id');
					objOfValues['service_day']=$(this).closest('tr').find('td.s-date').attr('data');
					objOfValues['location']=$(this).closest('tr').find('td.s-area').text();
					var appoinmentValue = objOfValues.name +' with '+ objOfValues.staff + ' on ' +objOfValues.service_day+ ' , ' + objOfValues.date + ' to ' + objOfValues.service_to ;
					var price = objOfValues.price.replace('$', '');
					var total = price * 1;

					if(i > generateRowCondition){
						generateRow();
						$('.form-group').find('a[name="discountRow'+i+'"]').show();
						$('body').on('click', 'a[name="discountRow'+i+'"]', function(e){
							var rownum = $(this).closest('.row').find("input.row-num").val();
							$('#discount-row-num').val(rownum);
							$(this).popover();
						});

						if(currentProductId == 0){
							$('.product-row').remove();
							lastDiv.remove();
						}
						var staffSelect = $('.form-group').find('select[name="staffName'+i+'"]').prev().prev();
						$('.form-group').find('input[name="productId'+i+'"]').val(objOfValues.ap_id);
						$('.form-group').find('input[name="serviceId'+i+'"]').val(objOfValues.ser_id);
						$('.form-group').find('textarea[name="productName'+i+'"]').val(appoinmentValue);
						 currentProductValue = appoinmentValue;
						$('.form-group').find('select[name="staffName'+i+'"]').val(objOfValues.staff_id);
						currentStaffValue = objOfValues.staff_id;
						$('.form-group').find('select[name="staffName'+i+'"]').selectpicker('refresh');
						$('.form-group').find('input[name="quantity'+i+'"]').val(1);
						$('.form-group').find('input[name="quantity'+i+'"]').attr("disabled","disabled")
						var unitprice = calculateUnitPrice(price);
						$('.form-group').find('input[name="unit_price'+i+'"]').val(unitprice.toFixed(2));
						$('.form-group').find('input[name="tax'+i+'"]').val('GST');
						$('.form-group').find('input[name="item-total'+i+'"]').val(total);
						$('.form-group').find('input[name="hideStaffName'+i+'"]').val(objOfValues.staff);
						$('.form-group').find('input[name="hideServiceName'+i+'"]').val(objOfValues.name);
						$('.form-group').find('input[name="hideAreaName'+i+'"]').val(objOfValues.location);
						$('.form-group').find('input[name="hideStatus'+i+'"]').val(objOfValues.status);
						$('.form-group').find('input[name="hideDate'+i+'"]').val(objOfValues.date);
						$('.form-group').find('input[name="hideServiceTo'+i+'"]').val(objOfValues.service_to);
						$('.form-group').find('input[name="hideServiceDay'+i+'"]').val(objOfValues.service_day);
					}
					//$(this).closest('tr').hide();
					//$('#sample-table-1').closest('tr').remove();
					
				});
				
				var amountWithTax = getTotalAmount();
					$('.total-value').text('$'+amountWithTax);
					$('.total-amount').val(amountWithTax);
				});
				var taxValue = getTaxAmount();
				$('.tax-value').text(taxValue);
				$('.tax-amount').val(taxValue);
			});
			$('body').on('click', '.closePopup', function(){
				$(this).closest('.popover').addClass('hide');
				applyDiscount = 1;
			});
			$(".add-to-invoice").click(function(){
				  var tableControl= $('#appointment-form').find('#sample-table-1');
				  var objOfValues = {};
				  var lastDiv = $('.form-group > div:last');
				  var i =1,j,lastRowNum;
					lastRowNum = lastDiv.find('input.row-num').val();
					if(lastRowNum != ''){
					  i = parseInt(lastRowNum)+1;
					}
					$(".appoint-checkboxs:checked",tableControl).each(function() {
						$(this).closest('tr').find('td.s-name').each(function(){
							objOfValues['name']=$(this).text();
							objOfValues['ap_id']=$(this).attr('id');
							objOfValues['ser_id']=$(this).attr('data');
						}).get();
						$(this).closest('tr').find('td.s-price').each(function(){
							objOfValues['price']=$(this).text();
						}).get();
						$(this).closest('tr').find('td.s-staff').each(function(){
							objOfValues['staff']=$(this).text();
							objOfValues['staff_id']=$(this).attr('id');
						}).get();
						$(this).closest('tr').find('td.s-date').each(function(){
							objOfValues['date']=$(this).text();
							objOfValues['service_to']=$(this).attr('id');
							objOfValues['service_day']=$(this).attr('data');
						}).get();
						$(this).closest('tr').find('td.s-area').each(function(){
							objOfValues['location']=$(this).text();
						}).get();
						objOfValues['status']=$(this).closest('tr').find('td.s-status').text();
					var appoinmentValue = objOfValues.name +' with '+ objOfValues.staff + ' on ' +objOfValues.service_day+ ' , '  + objOfValues.date + ' to ' + objOfValues.service_to ;
				var price = objOfValues.price.replace('$', '');
				var unitprice = calculateUnitPrice(price);
				var total = price * 1;
				generateRow();
				var lastDivAfterInsert = $('.form-group > div:last');
				lastDivAfterInsert.find('.apply-discount').show();
				lastDivAfterInsert.find('.discount-section').find('.applyDiscount').show();
				i = allRows;
				//$('.form-group').find('a[name="discountRow'+i+'"]').show();
					$('body').on('click', 'a[name="discountRow'+i+'"]', function(e){
					var rownum = $(this).closest('.row').find("input.row-num").val();
					$('#discount-row-num').val(rownum);
					$(this).popover();
				});
				var staffSelect = $('.form-group').find('select[name="staffName'+i+'"]').prev().prev();
				if(currentProductId == 0){
					$('.product-row .original').remove();
					lastDiv.remove();
				}
				$('.form-group').find('input[name="productId'+i+'"]').val(objOfValues.ap_id);
				$('.form-group').find('input[name="serviceId'+i+'"]').val(objOfValues.ser_id);
				$('.form-group').find('textarea[name="productName'+i+'"]').val(appoinmentValue);
				currentProductValue = appoinmentValue;
				$('.form-group').find('select[name="staffName'+i+'"]').val(objOfValues.staff_id);
				currentStaffValue = objOfValues.staff_id;
				//staffSelect.addClass("hide");
				$('.form-group').find('select[name="staffName'+i+'"]').selectpicker('refresh');
				$('.form-group').find('input[name="quantity'+i+'"]').val(1);
				$('.form-group').find('input[name="quantity'+i+'"]').attr("disabled","disabled");
				$('.form-group').find('input[name="unit_price'+i+'"]').val(unitprice.toFixed(2));
				$('.form-group').find('input[name="tax'+i+'"]').val('GST');
				$('.form-group').find('input[name="item-total'+i+'"]').val(total);
				$('.form-group').find('input[name="hideStaffName'+i+'"]').val(objOfValues.staff);
				$('.form-group').find('input[name="hideServiceName'+i+'"]').val(objOfValues.name);
				$('.form-group').find('input[name="hideAreaName'+i+'"]').val(objOfValues.location);
				$('.form-group').find('input[name="hideStatus'+i+'"]').val(objOfValues.status);
				$('.form-group').find('input[name="hideDate'+i+'"]').val(objOfValues.date);
				$('.form-group').find('input[name="hideServiceTo'+i+'"]').val(objOfValues.service_to);
				$('.form-group').find('input[name="hideServiceDay'+i+'"]').val(objOfValues.service_day);
				$(this).closest('tr').remove();
				}).get();
				var amountWithTax = getTotalAmount();
				$('.total-value').text('$'+amountWithTax);
				$('.total-amount').val(amountWithTax);
				var taxValue = getTaxAmount();
				$('.tax-value').text(taxValue);
				$('.tax-amount').val(taxValue);
		});
		$("#create-invoice").click(function(e){
			$('#invoiceModal').find('form').trigger('reset');
			$(".show-calender").datepicker('setDate', new Date());
			$('.location-div').show();
			$('.add-appointment').show();
			$('.tax-value').text('0');
			$('.total-value').text('$0');
			//$('.applyDiscount').show();
			$('.name-div').text('Invoice to');
			if(allRows > 0){
				for(var j =1;j<=allRows;j++){
					$('.form-group').find('.original').addClass('hide');
				}
				var lastDiv = $('.form-group > div:last');
				lastDiv.removeClass('hide');
				$('.form-group').find('.clone').hide();
			}


			if(productInfo !== undefined)
			{
				//alert('aa '+productInfo.length);
			
			var index = 0;
			var product_arr = $('#products_arr').val();
			var allamount = 0;
			if(product_arr.length > 0)
			{

				product_arr = jQuery.parseJSON($('#products_arr').val());
				$.each(product_arr, function(key,value) {
					var p_id = '';
					var total = 0;
					
					
						$.each(productInfo, function(pkey,p_value) {
							if(p_value['id'] ==  key)
							{
								p_id = pkey;
							}
						});
						var tax;
						if(productInfo[p_id]['tax'] == 'Excluding'){
							tax = 'Exc';
						} else if(productInfo[p_id]['tax'] == 'Including') {
						   tax = 'Inc';	
						} else {
							tax = 'N/A';
						}

						allamount += total;
						total += getTotalAmountAfterTax(productInfo[p_id]['cost_price']);
						// console.log(productInfo[p_id]['id']);
						// console.log(productInfo[p_id]['name']);
						// console.log(productInfo[p_id]['stock']);
						// console.log(tax);
						// console.log(productInfo[p_id]['cost_price']);


					if(index == 0)
					{
						
							$('.form-group').find('input.product-id').val(productInfo[p_id]['id']);
							$('.form-group').find('textarea[name="productName"]').val(productInfo[p_id]['name']);
							$('.form-group').find('input[name="quantity"]').val(value);
							$('.form-group').find('input[name="quantityStock"]').val(productInfo[p_id]['stock']);
							$('.form-group').find('input[name="unit_price"]').val(productInfo[p_id]['cost_price']);
							$('.form-group').find('input[name="tax"]').val(tax);
							$('.form-group').find('input[name="item-total"]').val((total*value).toFixed(2));

							

					}					
					else if(index > 0)
					{
						//alert(key+' <> '+value);

					  $('#addmore-product').trigger('click');
					  		$('.form-group').find('input.product-id'+index).val(productInfo[p_id]['id']);
							$('.form-group').find('textarea[name="productName'+index+'"]').val(productInfo[p_id]['name']);
							$('.form-group').find('input[name="quantity'+index+'"]').val(value);
							$('.form-group').find('input[name="quantityStock'+index+'"]').val(productInfo[p_id]['stock']);
							$('.form-group').find('input[name="unit_price'+index+'"]').val(productInfo[p_id]['cost_price']);
							$('.form-group').find('input[name="tax'+index+'"]').val(tax);
							$('.form-group').find('input[name="item-total'+index+'"]').val((total*value).toFixed(2));

					}
					currentProductValue = productInfo[p_id]['name'];
					var taxValue = getTaxAmount();
					$('.tax-value').text(taxValue);
					$('.tax-amount').val(taxValue);
					var amountWithTax = getTotalAmount();
					$('.total-value').text('$'+amountWithTax);
					$('.total-amount').val(amountWithTax);

					index++;
				  
				});
			}
			
			}
	




		});
		$(".custom-invoice").click(function(e){
			if(allRows > 0){
				for(var j =1;j<=allRows;j++){
					$('.form-group').find('.original').hide();
				}
				var lastDiv = $('.form-group > div:last');
				lastDiv.removeClass('hide');
				$('.form-group').find('.clone').hide();
			}
			e.preventDefault();
			$('#invoiceModal').find('form').trigger('reset');
			$(".show-calender").datepicker('setDate', new Date());
			if(allRows > 0){
				for(var j =1;j<allRows;j++){
					$('.form-group').find('.original').hide();
				}
				$('.form-group').find('.product-row').show();
				$('.form-group').find('.clone').hide();
			}
			var invoiceId = $(this).attr('data');
			$('.invoice-id').val(invoiceId);
			$.ajax({
					url: public_url+'edit-invoice',
					method: "POST",
					data: {'id':invoiceId},
					success: function(data){
						var data = JSON.parse(data);
						var i=0;
						var totalAmount=0;
						var taxAmount = 10;
						if(data.status == "ok"){
							$('select.location-area').val(data.invoice_details.area_id);
							$('select.location-area').selectpicker('refresh');
							$('.add-appointment').hide();
							$('.applyDiscount').show();
							currentClientValue = data.invoice_details.client_name;
							$('#clientName').val(data.invoice_details.client_name);
							$('.form-group').find('input[name="clientId"]').val(data.invoice_details.client_id);
							$('.form-group').find('input.service-id').val(data.invoice_details.ses_id);
							$('.form-group').find('textarea.product-name').val(data.invoice_details.desc);
							currentProductValue = data.invoice_details.desc;
							$('.form-group').find('select[name="staffName"]').val(data.invoice_details.staff_id);
							$('.form-group').find('select[name="staffName"]').selectpicker('refresh');
							currentStaffValue = data.invoice_details.staff_id;
							$('.form-group').find('input.quantity').val(1);
							$('.form-group').find('input.quantity').attr("disabled","disabled");
							$('.form-group').find('input[name="unit_price"]').val(data.invoice_details.price);
							$('.form-group').find('input.tax').val('GST');
							var total = getTotalAmountAfterTax(data.invoice_details.price);
							$('.form-group').find('input[name="item-total"]').val(total);
							var taxValue = parseFloat(total) - parseFloat(data.invoice_details.price); 
							$('.tax-value').text(taxValue.toFixed(2));
							$('.tax-amount').val(taxValue.toFixed(2));
							$('.total-value').text('$'+total.toFixed(2));
							$('.total-amount').val(total.toFixed(2));

						}
					}
				});
			});

			var openModal = $('#openModal').val();
			if(openModal.length > 0)
			{
				$('#create-invoice').trigger('click');
			}



		});
	function populateProductDetails(productDetails, field){
		var productClass = $('.form-group').find('.col-md-2.product-name');
		productClass.removeClass('has-error');

		currentProductValue = field.val();		
		var allamount = 0;
		var modal = field.closest('.modal')
		modalId = modal.attr('id');
		
		//var total = (productDetails.cost_price * 1);
		var total = 0;
		var tax;
		if(productDetails.tax == 'Excluding'){
			tax = 'Exc';
		} else if(productDetails.tax == 'Including') {
		   tax = 'Inc';	
		} else {
			tax = 'N/A';
		}
		field.siblings('input.product-id').val(productDetails.id);
		currentProductId = productDetails.id;
		if(modalId == 'invoiceModal'){
			var current = field.closest('.row');
			allamount += total;
			current.find('input.quantity').val(1);
			current.find('.applyDiscount').show();
			current.find('input.quantity-stock').val(productDetails.stock);
			current.find('input.unit-price').val(productDetails.cost_price);
			total += getTotalAmountAfterTax(productDetails.cost_price);
			current.find('input.tax').val(tax);
			current.find('input.total').val(total.toFixed(2));
			var taxValue = getTaxAmount();
			$('.tax-value').text(taxValue);
			$('.tax-amount').val(taxValue);
			var amountWithTax = getTotalAmount();
			$('.total-value').text('$'+amountWithTax);
			$('.total-amount').val(amountWithTax);
			var price = current.parent().siblings('.price-section').find('.price');

		}
}
function populateProductTypehead(data){
	$('.product-name').typeahead({
		source:data,
		items:'all',
		afterSelect:function(selection){ 
			populateProductDetails(selection, $(this).prop('$element'));
		}
	})
}
	function generateRow(currentEle){
		var lastDiv = $('.form-group > div:last');
			if(currentEle != undefined){
				serviceRow = $(currentEle).clone();
				parCont = $(currentEle);
			} else {
				serviceRow = $(lastDiv).clone();
				parCont = $(lastDiv).closest('.form-group');
			}
			serviceRow.removeClass('product-row');
			allRows++;
			rowNumb = allRows;
			totalPrice = serviceRow.find('input.total');
			unitPrice = serviceRow.find('input.unit-price');
			tax = serviceRow.find('input.tax');
			productId = serviceRow.find('input.product-id');
			serviceId = serviceRow.find('input.service-id');
			rowNum = serviceRow.find('input.row-num');
			hideStaffName = serviceRow.find('input.hide-staff-name');
			hideServiceName = serviceRow.find('input.hide-service-name');
			hideAreaName = serviceRow.find('input.hide-area-name');
			hideStatus = serviceRow.find('input.hide-service-status');
			hideDate = serviceRow.find('input.hide-service-date');
			hideServiceTo = serviceRow.find('input.hide-service-to');
			hideServiceDay = serviceRow.find('input.hide-service-day');
			quantity = serviceRow.find('input.quantity');
			quantityStock = serviceRow.find('input.quantity-stock');
			productName = serviceRow.find('textarea.product-name');
			// staffName = serviceRow.find('select.staff-name');
			// var staffSelect = staffName.prev().prev();
			// staffSelect.addClass("hide");
			// staffName.selectpicker('refresh');
			discountRow = serviceRow.find('a.applyDiscount');
			serviceRow.find('.addmore-product').parent().html('<a class="btn btn-primary removeProduct" href="#"><i class="fa fa-remove"></i></a>');
			totalPrice.prop('name', 'item-total'+rowNumb);
			totalPrice.val('');
			unitPrice.prop('name', 'unit_price'+rowNumb);
			unitPrice.val('');
			tax.prop('name', 'tax'+rowNumb);
			tax.val('');
			productId.prop('name', 'productId'+rowNumb);
			serviceId.prop('name', 'serviceId'+rowNumb);
			hideStaffName.prop('name', 'hideStaffName'+rowNumb);
			hideServiceName.prop('name', 'hideServiceName'+rowNumb);
			hideAreaName.prop('name', 'hideAreaName'+rowNumb);
			hideStatus.prop('name', 'hideStatus'+rowNumb);
			hideDate.prop('name', 'hideDate'+rowNumb);
			hideServiceTo.prop('name', 'hideServiceTo'+rowNumb);
			hideServiceDay.prop('name', 'hideServiceDay'+rowNumb);
			rowNum.val(rowNumb);
			quantity.prop('name', 'quantity'+rowNumb);
			quantityStock.prop('name', 'quantityStock'+rowNumb);
			quantity.val('');
			productName.prop('name', 'productName'+rowNumb);
			productName.val('');
			// staffName.prop('name', 'staffName'+rowNumb);
			discountRow.prop('name', 'discountRow'+rowNumb);
			if(currentEle != undefined){
				serviceRow.insertAfter(parCont);
			} else {
				parCont.append(serviceRow);
			}
			currentProductValue = '';
			currentStaffValue = '';
			var currentDiscount = serviceRow.find('a.applyDiscount');
			currentDiscount.popover(discountPopoverOpt);
			populateProductTypehead(productInfo);
	}
	
	function generateDiscountRow(currentEle){
		    applyDiscount = 1;
			if(currentEle != undefined){
				serviceRow = $(currentEle).clone();
				parCont = $(currentEle);
			}
			allRows++;
			rowNumb = allRows;
			totalPrice = serviceRow.find('input.total');
			unitPrice = serviceRow.find('input.unit-price');
			tax = serviceRow.find('input.tax');
			productId = serviceRow.find('input.product-id');
			serviceId = serviceRow.find('input.service-id');
			rowNum = allRows;
			hideStaffName = serviceRow.find('input.hide-staff-name');
			hideServiceName = serviceRow.find('input.hide-service-name');
			hideAreaName = serviceRow.find('input.hide-area-name');
			hideStatus = serviceRow.find('input.hide-service-status');
			hideDate = serviceRow.find('input.hide-service-date');
			hideServiceTo = serviceRow.find('input.hide-service-to');
			hideServiceDay = serviceRow.find('input.hide-service-day');
			quantity = serviceRow.find('input.quantity');
			quantityStock = serviceRow.find('input.quantity-stock');
			productName = serviceRow.find('textarea.product-name');
			staffName = serviceRow.find('select.staff-name');
			// var staffSelect = staffName.prev().prev();
			// staffSelect.addClass("hide");
			// staffName.selectpicker('refresh');;
			discountRow = serviceRow.find('a.applyDiscount');
			serviceRow.find('.addmore-product').parent().html('<a class="btn btn-primary removeProduct" href="#"><i class="fa fa-remove"></i></a>');
			totalPrice.prop('name', 'item-total'+rowNumb);
			totalPrice.val('');
			unitPrice.prop('name', 'unit_price'+rowNumb);
			unitPrice.val('');
			tax.prop('name', 'tax'+rowNumb);
			tax.val('');
			productId.prop('name', 'productId'+rowNumb);
			serviceId.prop('name', 'serviceId'+rowNumb);
			hideStaffName.prop('name', 'hideStaffName'+rowNumb);
			hideServiceName.prop('name', 'hideServiceName'+rowNumb);
			hideAreaName.prop('name', 'hideAreaName'+rowNumb);
			hideStatus.prop('name', 'hideStatus'+rowNumb);
			hideDate.prop('name', 'hideDate'+rowNumb);
			hideServiceTo.prop('name', 'hideServiceTo'+rowNumb);
			hideServiceDay.prop('name', 'hideServiceDay'+rowNumb);
			serviceRow.find('input[name="rowNum"]').val(rowNumb);
			quantity.prop('name', 'quantity'+rowNumb);
			quantityStock.prop('name', 'quantityStock'+rowNumb);
			quantity.val('');
			productName.prop('name', 'productName'+rowNumb);
			productName.val('');
			staffName.prop('name', 'staffName'+rowNumb);
			discountRow.prop('name', 'discountRow'+rowNumb);
			if(currentEle != undefined){
				serviceRow.insertAfter(parCont);
			}
			$('#invoiceModal').find('.popover').remove();
			//$('.apply-discount').popover(discountPopoverOpt);
	}
	
	function getTotalAmount(){
		var findClass;
		var totalInvoiceAmount = 0;
		var checkOriginalRow = $('.form-group').find('.original').find("input.unit-price");
		if(checkOriginalRow.length > 0) {
			findClass = $('.form-group').find('.original');
		} else {
			findClass = $('.form-group').find('.clone');
		}
		findClass.find("input.total").each(function(){
			var currentAmount = $(this).val();
			if(currentAmount != ''){
				totalInvoiceAmount = parseFloat(totalInvoiceAmount) + parseFloat(currentAmount);
			}
		});
		return totalInvoiceAmount.toFixed(2);
	}
	
	function getTaxAmount(){
		var findClass;
		var totalAmount = totalUnitAmount = totalTaxAmount =0;
		var checkOriginalRow = $('.form-group').find('.original').find("input.unit-price");
		if(checkOriginalRow.length > 0) {
			findClass = $('.form-group').find('.original');
		} else {
			findClass = $('.form-group').find('.clone');
		}
		findClass.find("input.unit-price").each(function(){
			var currentUnitAmount = $(this).val();
			var currentQuantity = $(this).closest('.unit-price-div').siblings('.quantity-div').find('.quantity').val();
			var currentAmount = currentUnitAmount * currentQuantity;
			if(currentAmount != ''){
				totalUnitAmount += parseFloat(currentAmount);
			}
		});
		findClass.find("input.total").each(function(){
			var currentTotalAmount = $(this).val();
			if(currentTotalAmount != ''){
				totalAmount = parseFloat(totalAmount) + parseFloat(currentTotalAmount);
			}
		});
		totalTaxAmount = parseFloat(totalAmount) - parseFloat(totalUnitAmount);
		return totalTaxAmount.toFixed(2);
	}
	function calculateUnitPrice(price){
		var unitprice = price*.11;
		unitprice = price- unitprice;
		return unitprice;
	}
	function getTotalAmountAfterTax(price){
		var totalPrice =0;
		totalPrice = price*.15;
		totalPrice = parseFloat(totalPrice) + parseFloat(price);
		return totalPrice;
	}
	function getStaff(area,public_url){
				$.ajax({
					url: public_url+'getstaff',
					method: "POST",
					data: {'area_id':area},
					async: false,
					success: function(data){
						var data = JSON.parse(data);
						var dd = $('.form-group').find('select.staff-name');
						if(data.status == "ok"){
							//var dd = $('select[name="staffName"]'),
							// options = '<option value="">-- Select --</option>';
							// if(data.staffs){
							// 	$.each(data.staffs, function(val, text){
							// 		options += '<option value="'+val+'">'+text+'</option>';
							// 	});
							// }
							// dd.html(options);
							// dd.selectpicker('refresh');
							//dd.selectpicker('refresh');
							if(data.products){
								productInfo = data.products;
								populateProductTypehead(productInfo);
							}
						}
					}
				});
	}
	
		function getAppointment(area,public_url,clientId,areaname){
				$.ajax({
					url: public_url+'getappointment',
					method: "POST",
					data: {'area_id':area,'client_id':clientId,'area_name':areaname },
					success: function(data){
						var data = JSON.parse(data);
						if(data.status == "ok"){
							$('.add-appointment').removeClass('disabled');
							var dd = $('.appointment-section');
							var tableHtml = '';
							if(data.appointment){
								$('.all-apointment').removeClass('hide');
								$.each(data.appointment, function(val1, text1){
									if(text1.length > 0){
									  $.each(text1, function(val, text){
										var appointmentdate = new Array();
										appointmentdate = text.date.split(",");
										tableHtml += '<tr><td><input type="checkbox" name="" value="1" class = "appoint-checkboxs"></td><td class ="s-name" id ="'+text.ap_id+'" data = "'+text.ser_id+'">'+text.s_name.replace(/\b\w/g, l => l.toUpperCase())+'</td><td class ="s-price">$'+text.price+'</td><td class ="s-status"><span class="label label-inverse"> '+text.status+' </span></td><td class ="s-date"  id ="'+text.service_to+'" data ="'+appointmentdate[0]+'"><i class="fa fa-calendar"></i> '+appointmentdate[1]+' at '+text.service_time+ '</td><td class ="s-staff" id ="'+text.staff_id+'"> <i class="fa fa-user"></i> '+text.staff+' </td><td class ="s-area"><i class="fa fa-home"></i> '+text.area+' </td></tr>';
									});
									}
								});
							} else {
								tableHtml += '<tr>No other uninvoiced appointments found</tr>';
							}
							dd.html(tableHtml);
						}  else {
							$('.add-appointment').addClass('disabled');
						}
					}
				});
	}
	function submitDiscountPop($elem,totalAmount){
		var content = $elem.closest('.popover').find('.popoverContent');
		$('[data-toggle=popover]').popover('hide');
		var formGroup = content.find('.col-md-12');
		var discount_amount = formGroup.find('input[name="amount"]').val();
		if(discount_amount.indexOf("%") >= 0){
			var discount_amount = discount_amount.replace("%",'');
			if(discount_amount >100){
				alert('Please Enter amount less than 100% Percentage');
				return false;
			} else if(discount_amount <0) {
				alert('Please Enter amount less than 0% Percentage');
				return false;
			}
			discount_amount = totalAmount *(discount_amount/100);
			discount_amount = discount_amount.toFixed(2);
		}
		var discount_type = formGroup.find('select[name="discount_type"]').val();
		var unitprice = calculateUnitPrice(discount_amount);
		var total = discount_amount * 1;
		generateDiscountRow(currentDiscountRow);
		$('.popover').addClass('hide');
		var rowindex = allRows;
		var currentstaff = $('select[name="staffName'+rowindex+'"]').val();
		var currentproductId = $('input[name="productId'+rowindex+'"]').val();
		var currentserviceId = $('input[name="serviceId'+rowindex+'"]').val();
		$('.form-group').find('a[name="discountRow'+rowindex+'"]').hide();
		$('.form-group').find('input[name="productId'+rowindex+'"]').val(currentproductId);
		$('.form-group').find('input[name="serviceId'+rowindex+'"]').val(currentserviceId);
		$('.form-group').find('textarea[name="productName'+rowindex+'"]').val(discount_type);
		$('.form-group').find('select[name="staffName'+rowindex+'"]').val(currentstaff);
		$('.form-group').find('input[name="quantity'+rowindex+'"]').val(1);
		$('.form-group').find('input[name="unit_price'+rowindex+'"]').val('-'+unitprice.toFixed(2));
		$('.form-group').find('input[name="tax'+rowindex+'"]').val('GST');
		$('.form-group').find('input[name="item-total'+rowindex+'"]').val('-'+total);
		var discountAmountWithTax = getTotalAmount();
		$('.total-value').text('$'+discountAmountWithTax);
		$('.total-amount').val(discountAmountWithTax);
		var taxValue = getTaxAmount();
		$('.tax-value').text(taxValue);
		$('.tax-amount').val(taxValue);
	}
	function setFieldInvalid($formGroup){
		$formGroup.addClass('has-error');
	}