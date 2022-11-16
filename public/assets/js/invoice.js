var rowNumb = dicountRowNumb = 0;
var settimeout = null;
/* Start: document ready functionv */
$(document).ready(function(){
	/* inisalize datepicker */
	$('input[name="invoiceDate"]').datepicker({autoclose:true, dateFormat:"D, d M yy"});
	$('input[name="dueDate"]').datepicker({autoclose:true, dateFormat:"D, d M yy"});
	$('input[name="paymentDate"]').datepicker({autoclose:true, dateFormat:"D, d M yy"});

	/* Start: set field nutral */
	$('body').on('change','.inv-set-neutral', function(){
		setInvFieldNeutral($(this));
	})
	/* Start: set field nutral */

	/* Start: Create Discount DD */
	var discountData = JSON.parse($('input[name="discount-data"]').val());
	if(discountData.length > 0){
		var	optionData = "<select class='form-control discount-type' name='discount_type'><option value=''> -- Select -- </option>";
		$.each(discountData, function(val, text){ 
			optionData += "<option value='"+text.name+"' data-price ='"+text.price+"' data-type='"+text.type+"' data-distax='"+text.disTax+"' data-distaxrate='"+text.disTaxRate+"'>"+text.name+"</option>";
		});
		optionData +="</select>"; 
	}
	else{
		optionData = "<a href='"+public_url+"sales-tools/discounts/create' class='btn btn-primary'>Create Discount</a>";
	}

	discountPopoverOpt = {
		html: true,
		content: "<div class='row popoverContent' data-action='apply_coupon'><div class='col-md-12'>"+optionData+"</div><div class='col-md-12' style='margin-top:10px; margin-bottom:10px;'><input type='text' name='amount' class= 'amount hide' placeholder = 'E.g. 20% or 20' value=''><input type ='hidden' name='row_num' class='discount-row-num'/><input type ='hidden' name='totalAmount' class='discount-total-amount'/><input type='hidden' name='disTax' value='' /><input type='hidden' name='disTaxRate' value='' /></div></div><div class='clearfix'><div class ='row'><div class ='col-md-6 col-xs-6'><button class='btn btn-primary closePopup' type='button'> Cancel </button></div><div class ='col-md-3 col-xs-6'><button class='btn btn-primary discount-submit' type='button' style ='width:80px;white-space:normal;'> Apply</button></div></div></div>",
		container: $('#invoiceModal'),
		title: "",
		placement: 'top',
		trigger: 'click'
	};
	/* Start: Create Discount DD */

	/* Start: click on out side popover its close */
	$('body').on('click', function (e) {
	    $('[data-toggle=popover]').each(function () {
	        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0)
	            $(this).popover('hide');
	    });
	});
	/* End: click on out side popover its close */

	/* Start: Discount popover close */
	$('body').on('click', '.closePopup', function(){
		$(this).closest('.popover').popover('hide');
	});
	/* Start: Discount popover close */

	/* Start: create new invoice */
	$('.create-invoice').click(function(e){
		e.preventDefault();
		toggleWaitShield("show");
		var modal = $('#invoiceModal');

		modal.find('.modal-title').html("Add Invoice");
		/* current data and due date */
		var currentDate = moment().format("ddd, D MMM YYYY");
		modal.find('input[name="invoiceDate"]').val(currentDate);
		modal.find('input[name="paymentDate"]').val(currentDate);
		var payTerm = $('input[name="payment-terms"]').val();
		if(payTerm &&  payTerm =='Immediately')
			var due_date = currentDate;
		else if(payTerm && payTerm == 20)
			var due_date = moment().add(1, 'months').startOf('month').add(19, 'days').format("ddd, D MMM YYYY");
		else	
			var due_date = moment().add(payTerm, 'days').format("ddd, D MMM YYYY");

		modal.find('input[name="dueDate"]').val(due_date);

		/*clear and generate new row */
		// generateNewRow('new-product');

		/* modal open */
		modal.modal('show');

		toggleWaitShield("hide");
	})
	/* End: create new invoice */

	/* Start: Edit new invoice */
	$('.edit-invoice').click(function(e){
		e.preventDefault();
		toggleWaitShield("show");
		var dataArea = $('#edit-data'),
			modal = $('#invoiceModal'),
			form = modal.find('#invoice-form'),
			locationId = dataArea.find('input[name="locationId"]').val();
		
		var allInvoiceData = JSON.parse(dataArea.find('input[name="invoiceAllData"]').val());
		/* Invoice modal title */	
		modal.find('.modal-title').html("Edit Invoice");

		modal.find('input[name="paymentDate"]').val(moment().format("ddd, D MMM YYYY"));
		/* set invoice general data */
		form.find('select[name="staffId"]').val(allInvoiceData.staffId).selectpicker("refresh");
		form.find('select[name="termsOfSale"]').val(allInvoiceData.termsOfSale).selectpicker("refresh");
		form.find('select[name="delivery_type"]').val(allInvoiceData.delivery_type).selectpicker("refresh");
		form.find('select[name="delivery_type"]').trigger('change');
		if(allInvoiceData.delivery_type == 'delivery'){
			form.find('input[name="contact_person"]').val(allInvoiceData.contact_person);
			form.find('input[name="address_line_one"]').val(allInvoiceData.address_line_one);
			form.find('input[name="address_line_two"]').val(allInvoiceData.address_line_two);
			form.find('input[name="city"]').val(allInvoiceData.del_city);
			form.find('select.countries').val(allInvoiceData.del_country).selectpicker("refresh");
			getStates(allInvoiceData.del_country,allInvoiceData.del_state);
			form.find('input[name="postal_code"]').val(allInvoiceData.del_postal_code);
			form.find('input[name="shipped_via"]').val(allInvoiceData.shipped_via);
		}
		form.find('input[name="order_number"]').val(allInvoiceData.order_number);
		form.find('input[name="cust_order_number"]').val(allInvoiceData.cust_order_number);
		form.find('select#locationDD').val(locationId).selectpicker("refresh");
		form.find('input[name="invoiceId"]').val(dataArea.find('input[name="invoice_id"]').val());
		form.find('select#locationDD').val(locationId).selectpicker("refresh");
		form.find('input[name="clientName"]').val(dataArea.find('input[name="clientName"]').val());
		form.find('input[name="clientId"]').val(dataArea.find('input[name="clientId"]').val());
		form.find('input[name="invoiceDate"]').val(dbDateToDateString(dataArea.find('input[name="invoice_date"]').val()));
		form.find('input[name="dueDate"]').val(dbDateToDateString(dataArea.find('input[name="due_date"]').val()));

		/* Create row and inject value of invoice */
		var invoiceData = JSON.parse(dataArea.find('input[name="invoiceData"]').val());
		$.each(invoiceData, function(key, value){
			var editData = {};
			editData.product_id = value.pd_id;
			editData.staff_name = value.staff_id;
			editData.quantity_stock = 5; 
			editData.product_name = value.desc;
			editData.product_type = value.type;
			editData.tax_name = value.inp_tax;
			editData.tax_type = value.inp_tax_type;
			editData.quantity = value.quantity;
			editData.unit_price = value.unit_price;
			editData.total_price = value.total;
			editData.hidden_total_price = (parseFloat(value.total) / parseInt(value.quantity));

			generateNewRow('old-product', editData);
		})

		// var invDataNew = JSON.parse(dataArea.find('input[name="invoiceCompData"]').val());
		// $.each(invDataNew,function(key, value){
		// 	var newEditData = {};
		// 	newEditData.staffId = value.staffId;
		// 	newEditData.termsOfSale = value.termsOfSale;
		// 	newEditData.deliveryType = value.delivery_type;
		// 	newEditData.contactPerson = value.contact_person;
		// 	newEditData.addressLine1 = value.address_line_one;
		// 	newEditData.addressLine2 = value.address_line_two;
		// 	newEditData.city = value.city;
		// 	newEditData.state = value.state;
		// 	newEditData.country = value.country;
		// 	newEditData.postalCode = value.postal_code;
		// 	newEditData.shippedVia = value.shipped_via;
		// 	newEditData.orderNumber = value.order_number;
		// 	newEditData.custOrderNumber = value.cust_order_number;
		// });

		calcTotalAmountAndTax();
		modal.modal('show');
		toggleWaitShield("hide")
	})
	/* End: Edit new invoice */

	/* Start: on closed open invoice modal */
	$('#invoiceModal').on('hidden.bs.modal', function (){
		rowNumb = 0;
	 	clearInvoiceForm($(this)); 
	})
	/* End: on closed open invoiced modal */

	/* Start: get staff on change location */
	$('body').on('change', 'select[name="location-area"]', function(e){
		var locationId = $(this).val();
		if(locationId != ''){
			getStaff(locationId);
		} 
		else{
			$('.clientList').val('');
		}
	});
	/* End: get staff on change location */

	/* Start: get staff and product when location id is not blank */
	var locationId = $('#edit-data').find('input[name="locationId"]').val();
	if(typeof locationId != 'undefined' && locationId != "")
		getStaff(locationId);
	/* End: get staff and product when location id is not blank */

	/* Start: Fatch area and create dd */
	$.get(public_url+'invoices/all-area',{}, function(response){
		var areaSelecter = $('select#locationDD'),
		    areaOption = '';

		areaSelecter.empty();
		areaOption += "<option value=''> -- Select --</option>";
		if(Object.keys(response).length > 0){
			$.each(response, function(key, value){
				areaOption += "<option value='"+key+"'>"+value+"</option>";
			})
			areaSelecter.append(areaOption);
			areaSelecter.selectpicker("refresh");
		}
	},'JSON');
	/* End: Fatch area and create dd */

	

	/* Start: Add new product */
	$('.add-new-item').click(function(e){
		e.preventDefault();
		generateNewRow('new-product');
	})
	/* End: Add new product */

	/* Start: Add new custom product */
	$('.add-custom-item').click(function(e){
		e.preventDefault();
		generateNewRow('custom');
	})
	/* End: Add new custom product */

	/* Start: Add new Class product */
	$('.add-class-item').click(function(e){
		e.preventDefault();
		generateNewRow('class');
	})
	/* End: Add new Class product */

	/* Start: Add new Service product */
	$('.add-service-item').click(function(e){
		e.preventDefault();
		generateNewRow('service');
	})
	/* End: Add new Service product */

	/* Start: remove row */
	$('body').on('click', '.removeProduct', function(e){
		e.preventDefault();
		var $this = $(this);

		$this.closest('.row').remove();
		calcTotalAmountAndTax();
	})
	/* End: remove row */

	/* Start: Calculate new price when change unit */
	$('body').on('input', '.quantity', function(){
		var $this = $(this),
			isError = false,
			quantity = $this.val(),
			currentRow = $this.closest('.row');

		if(quantity != ''){
			var taxname = currentRow.find('select.tax-name');
			if(taxname.val() == ''){
				isError = true;
				setFieldInvalid(taxname.closest('.col-md-1'));
				// alert('Please select tax.');	
			}

			var unitprice = currentRow.find('.unit-price');
			if(unitprice.val() == ''){
				isError = true;	
			}

			if(!isError){
				var dbTotalAmount = parseFloat(currentRow.find('input.hidden-totalPrice').val()),
					price = parseFloat(currentRow.find('input.unit-price').val());

				if(price < 0)
					totalPrice = -(Math.abs(dbTotalAmount) * quantity);
				else
					totalPrice = dbTotalAmount * quantity;

				currentRow.find('.total-price').val(totalPrice.toFixed(2));
			
				calcTotalAmountAndTax();
			}
		}	
	})
	/* End: Calculate new price when change unit */

	/* Start: Calculate new price when change unit price */
	$('body').on('blur', '.unit-price', function(){
		var $this = $(this),
			isError = false,
			unitprice = parseFloat($this.val()),
			currentRow = $this.closest('.row'),
			preUnitVal = parseFloat(currentRow.find('.pre-unit-price').val());

		if(unitprice != '' && preUnitVal != unitprice){
			var taxname = currentRow.find('select.tax-name');
			if(taxname.val() == ''){
				isError = true;
				setFieldInvalid(taxname.closest('.col-md-1'));
				// alert('Please select tax.');	
			}

			var quantity = currentRow.find('.quantity');
			if(quantity.val() == ''){
				isError = true;
				setFieldInvalid(quantity.closest('.col-md-1'));
				alert('Please enter quantity.');	
			}

			if(!isError){
				var prodType = currentRow.find('input.product-type').val(),
					taxType = currentRow.find('input.tax-type').val(),
					taxVal = currentRow.find('select.tax-name option:selected').data('tax');

				var priceData = getTax(taxType, unitprice, quantity.val(), taxVal, taxname.val());
				$this.val(priceData['unitprice']);
				currentRow.find('.pre-unit-price').val(priceData['unitprice']);
				currentRow.find('input.total-price').val(priceData['totalprice']);
				currentRow.find('input.hidden-totalPrice').val(priceData['totalprice'] / quantity.val());
				calcTotalAmountAndTax();
			}
		}
	})
	/* End: Calculate new price when change unit price */

	/* Start: On change discount type */
	$('body').on('change', '.discount-type', function(e){
		var $this = $(this),
			selectedOption = $this.find(':selected'),
			discountType = selectedOption.data('type'),
			discountPrice = selectedOption.data('price'),
			discountTax = selectedOption.data('distax'),
			discountTaxRate = selectedOption.data('distaxrate'),
			popoverContainer = $this.closest('.popoverContent');

		if(discountType == 'Percentage'){
			discountPrice = parseInt(discountPrice);
			popoverContainer.find('input[name="amount"]').val(discountPrice+'%');
		}
		else if(discountType == 'Fixed amount'){
			popoverContainer.find('input[name="amount"]').val(discountPrice);
		}
		else{
			popoverContainer.find('input[name="amount"]').val('');
		}
			
		if($this.val() != ''){
			$('.discount-submit').show();
			$('.amount').removeClass('hide');
		} 
		else {
			$('.discount-submit').hide();
			$('.amount').addClass('hide');
		} 

		popoverContainer.find('input[name="disTax"]').val(discountTax);
		popoverContainer.find('input[name="disTaxRate"]').val(discountTaxRate);
	});
	/* End: On change discount type */

	/* Start: submit discount */
	$('body').on('click', '.discount-submit', function(e){
		e.preventDefault();
		var $this = $(this),
			currentPopover = $this.closest('.popover'),
			rowNumber = currentPopover.find('.discount-popover-title').data('discountrow'),
			currentRow = $('#invoiceModal').find('#row_'+rowNumber),
			dicount_type = currentPopover.find('select[name="discount_type"]').val();

		if(dicount_type != ''){
			var discount_amount = currentPopover.find('input[name="amount"]').val(),
				product_amount = currentRow.find('.total-price').val();

			if(discount_amount == ''){
				alert('Please enter discount amount');
				return false;
			} 
			else if(product_amount == ''){
				alert('Please enter product amount');
				return false;
			}
			else if(!(discount_amount.indexOf('%') != -1) && parseFloat(discount_amount) > parseFloat(product_amount)) {
				alert('Please enter dicount amount less than product amount');
				return false;
			}
			else{
				if(discount_amount.indexOf("%") >= 0){
					var discount_amount = discount_amount.replace("%",'');
					if(discount_amount >100){
						alert('Please Enter amount less than 100% Percentage');
						return false;
					} else if(discount_amount <0) {
						alert('Please Enter amount greater than 0% Percentage');
						return false;
					}
					else{
						discount_amount = product_amount * (discount_amount/100);
						discount_amount = discount_amount.toFixed(2);
					}
				}
				else{
					discount_amount = discount_amount;
				}

				var data = {},
					dicount_tax = currentPopover.find('input[name="disTax"]').val(), 
					dicount_tax_rate = currentPopover.find('input[name="disTaxRate"]').val(), 
					quantity = currentRow.find('.quantity').val(),
					unit_price = calculateUnitPriceWithTax(discount_amount, dicount_tax_rate);

				unit_price = (-unit_price);
				data.product_id = currentRow.find('.product-id').val(); 
				data.staff_name = currentRow.find('select.staff-name option:selected').val(); 
				data.quantity_stock = currentRow.find('.quantity-stock').val(); 
				data.product_name = dicount_type; 
				data.product_type = 'Discount'; 
				data.tax_name = dicount_tax; 
				data.tax_type = 'N/A';
				data.quantity = quantity;  
				data.unit_price = unit_price;
				data.total_price = unit_price * quantity;
				data.discount_amount = discount_amount; 
				data.hidden_total_price = parseFloat(unit_price);

				/* set globly discount row for append */
				dicountRowNumb = rowNumber;

				currentPopover.popover('hide');
				generateNewRow('discount', data);
				calcTotalAmountAndTax();
			} 
		}
		else{
			alert('Please select dicount type');
			return false;
		}
	});
	/* End: submit discount */

	/* Start: save invoice data  */
	$("#invoice-submit-btn").click(function(){
		var modal = $(this).closest('.modal'),
			form = modal.find('#invoice-form'),
			paymentField = form.find('.inv-payment-fields'),
			isFormValid = true,
			formData = {};

		form.find('.errorMsg').empty();

		/*var locField = form.find('select#locationDD');
		if(locField.val() == ''){
			isFormValid = false;
			setFieldInvalid(locField.closest('.form-group'));
		}
		*/
		var locationDD = form.find('#locationDD');
		if(locationDD.val() == ''){
			isFormValid = false;
			setFieldInvalid(locationDD.closest('.form-group'));
		}

		var staffDD = form.find('select[name="staffId"]');
		if(staffDD.val() == '' || staffDD.val() == null){
			isFormValid = false;
			setFieldInvalid(staffDD.closest('.form-group'));
		}

		var termsOfSale = form.find('select[name="termsOfSale"]');
		if(termsOfSale.val() == '' || termsOfSale.val() == null){
			isFormValid = false;
			setFieldInvalid(termsOfSale.closest('.form-group'));
		}

		var deliveryType = form.find('#deliveryType');
		if(deliveryType.val() == '' || deliveryType.val() == null){
			isFormValid = false;
			setFieldInvalid(deliveryType.closest('.form-group'));
		}
		if(deliveryType.val() == 'delivery'){
			var contactPerson = form.find('input[name="contact_person"]');
			if(contactPerson.val() == '' || contactPerson.val() == null){
				isFormValid = false;
				setFieldInvalid(contactPerson.closest('.form-group'));
			}
			var address_line_one = form.find('input[name="address_line_one"]');
			if(address_line_one.val() == '' || address_line_one.val() == null){
				isFormValid = false;
				setFieldInvalid(address_line_one.closest('.form-group'));
			}
			var address_line_two = form.find('input[name="address_line_two"]');
			if(address_line_two.val() == '' || address_line_two.val() == null){
				isFormValid = false;
				setFieldInvalid(address_line_two.closest('.form-group'));
			}
			var city = form.find('input[name="city"]');
			if(city.val() == '' || city.val() == null){
				isFormValid = false;
				setFieldInvalid(city.closest('.form-group'));
			}
			var country = form.find('select[name="country"]');
			if(country.val() == '' || country.val() == null){
				isFormValid = false;
				setFieldInvalid(country.closest('.form-group'));
			}
			var state = form.find('select[name="state"]');
			if(state.val() == '' || state.val() == null){
				isFormValid = false;
				setFieldInvalid(state.closest('.form-group'));
			}
			var postal_code = form.find('input[name="postal_code"]');
			if(postal_code.val() == '' || postal_code.val() == null){
				isFormValid = false;
				setFieldInvalid(postal_code.closest('.form-group'));
			}
			var shipped_via = form.find('input[name="shipped_via"]');
			if(shipped_via.val() == '' || shipped_via.val() == null){
				isFormValid = false;
				setFieldInvalid(shipped_via.closest('.form-group'));
			}
		}
		var order_number = form.find('input[name="order_number"]');
		if(order_number.val() == '' || order_number.val() == null){
			isFormValid = false;
			setFieldInvalid(order_number.closest('.form-group'));
		}
		var cust_order_number = form.find('input[name="cust_order_number"]');
		if(cust_order_number.val() == '' || cust_order_number.val() == null){
			isFormValid = false;
			setFieldInvalid(cust_order_number.closest('.form-group'));
		}
		var clientField = form.find('input[name="clientName"]');
		if(clientField.val() == ''){
			isFormValid = false;
			setFieldInvalid(clientField.closest('.form-group'));
		}
		if(isFormValid){
			var itemFields = $('#appendRow .new-row').length;
			if(itemFields == 0){
				isFormValid = false;
				alert('Please add atleast one item.');
			}
		}
		modal.find('.new-row').each(function(){
			var $this = $(this);

			if(isFormValid){
				var productField = $this.find('.product-name'),
					productId = $this.find('.product-id').val();
				if(productField.val() == '' || productId == ''){
					isFormValid = false;
					productField.closest('.inv-group').addClass('has-error');
				}

				var quantity = $this.find('.quantity');
				if(quantity.val() == ''){
					isFormValid = false;
					quantity.closest('.inv-group').addClass('has-error');
				}

				var unitprice = $this.find('.unit-price');
				if(unitprice.val() == ''){
					isFormValid = false;
					unitprice.closest('.inv-group').addClass('has-error');
				}

				var unitprice = $this.find('.unit-price');
				if(unitprice.val() == ''){
					isFormValid = false;
					unitprice.closest('.inv-group').addClass('has-error');
				}
				
				/* only for select picker */
				var tax = $this.find('select.tax-name');
				if(tax.val() == ''){
					isFormValid = false;
					tax.closest('.inv-group').addClass('has-error');
				} 
				else{
					formData[tax.attr('name')] = tax.val();
				}

				var staff = $this.find('select.staff-name'); 
				formData[staff.attr('name')] = staff.val();
			}
		});
		
		if(!paymentField.hasClass('hidden')){
			$.each(paymentField.find(':input'), function(){
				var $this = $(this);
				if($this.attr('type') != 'button'){
					if($this.val() == ''){
						isFormValid = false;
						setFieldInvalid($this.closest('.form-group'));
					}
					var epicCash = parseFloat(modal.find('#client-epic-credit').text()),
						dueAmount = $('#emailInvModal').find('#due-amount').val();
						totalAmount = modal.find('input[name="paymAmount"]').val();
					if($this.attr('name') == "payment_type" && $this.val() == 'EPIC Credit'){
						if(epicCash < totalAmount ){
							isFormValid = false;
							form.find('.errorMsg').append(prepareNotific('error', 'EPIC credit is less than pay amount'));
						}
					}
				}
			})
			dueAmountInvoice = $('#emailInvModal').find('#due-amount').val();
			totalAmountInvoice = modal.find('input[name="paymAmount"]').val();
			if(dueAmountInvoice < totalAmountInvoice){
				isFormValid = false;
				form.find('.errorMsg').append(prepareNotific('error', 'Due Amount is less than Entered amount'));
			} 
		}

		if(isFormValid){ 
			$.each(form.find(':input:not(.leave-cls)').serializeArray(), function(i, obj){
				formData[obj.name] = obj.value;
			});

			if('dueDate' in formData)
				formData['dueDate'] = dateStringToDbDate(formData['dueDate']); 
			if('invoiceDate' in formData)
				formData['invoiceDate'] = dateStringToDbDate(formData['invoiceDate']); 

			if(formData['invoiceId'] == '')
				var requestUrl = public_url+'invoices/save-invoice';
			else
				var requestUrl = public_url+'invoices/update-invoice';

			$.ajax({
				url: requestUrl,
				method: "POST",
				data: formData,
				success: function(response){
					var data = JSON.parse(response);
					if(data.status == "success"){
						modal.modal('hide');
						location.reload();
					}
				}
			});
		}
	});
	/* End: save invoice data  */

	/* Start: Toggel payment method */
	$('.payment-method').click(function(e){
        e.preventDefault();
        var $this = $(this),
        	modal = $this.closest('.modal'),
            targetFieldId = $this.attr('href'),
            payment_method = ($this.text()).trim();

        modal.find('.payment-fields').hide();
        $('.payment-method').removeClass('active');
        $this.addClass('active');

        if(targetFieldId != '#')
           modal.find(targetFieldId).show();

       modal.find('input[name="paymentMethod"]').val(payment_method);
    });
	/* End: Toggel payment method */

	/* Start: make payment modal open*/
	$('.make-payment').click(function(e){
		e.preventDefault();
		var $this = $(this),
			modal = $('#paymentAddModal'),
			invoiceId = $this.data('invoice-id');
			
		modal.find('#inv-id').text(invoiceId);
		modal.find('input[name="invoiceId"]').val(invoiceId);		
		modal.find('input[name="paymentDate"]').val(moment().format("ddd, D MMM YYYY"));
		$('.payment-method').first().trigger('click');
		$.get(public_url+'invoices/payment/detail',{invoiceId:invoiceId}, function(response){
			if(response.status == 'success'){
				modal.find('input[name="invoiceAmount"]').val(parseFloat(response.invoiceAmount));
				modal.find('input[name="totalinvoiceAmount"]').val(parseFloat(response.total));
				modal.find('#inv-total-text').text(response.total);
				modal.find('#inv-due-text').text(response.invoiceAmount);
				modal.find('input[name="paymAmount"]').val(parseFloat(response.invoiceAmount));
				modal.modal("show");
			}
		},'JSON');
	})
	/* ENd: make payment modal open*/

	/* Start: Payment modal submit */
	$('#payment-submit').click(function(e){
		e.preventDefault();
		var formData = {},
			modal = $('#paymentAddModal'),
			form = modal.find('#make-payment'),
			isFormValid = form.valid();

		if(isFormValid){
			$.each(form.find(':input').serializeArray(), function(i, obj){
				formData[obj.name] = obj.value;
			})

			if('paymentDate' in formData)
				formData['paymDate'] = dateStringToDbDate(formData['paymentDate']);

			$.ajax({
				url: public_url+'save-payment',
				method: "POST",
				data: formData,
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "success"){
						modal.modal('hide');
						location.reload();
					}
					else if(data.status == 'overflow'){
						var msgField = modal.find('#overflow-msg');
						msgField.removeClass('hidden');
						msgField.append('<p>$'+data.amount+' EPIC credit is less than pay amount.</p>')
					}else if(data.status == 'overDue'){
						var msgField = modal.find('#overflow-msg');
						msgField.removeClass('hidden');
						msgField.append('<p>Due amount is less than entered amount.</p>')
					}
				}
			});
		}	
	})
	/* End: Payment modal submit */

	/* Start: Payment delete with sweetalert and ajax */
    $('body').on('click', '.delete-payment', function(e){
		e.preventDefault();
        var $this = $(this);
		var pId = $this.data('id');
		swal({
            title: "Are you sure?",
            text: "It will delete the payment for $"+$this.data('amount')+".",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d43f3a",
            confirmButtonText: "Yes, delete it!",
            //closeOnConfirm: true,
            allowOutsideClick: true
        }, 
        function(){
			$.ajax({
				url: public_url+'delete-payment',
				method: "POST",
				data: {'id':pId,'amount':$this.data('amount'),'invoice_id':$this.data('invoice')},
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "success"){
						$this.parent().remove();
						var totalNzd = $('.total-nzd').text();
						var lessamount = $('.amount-paid').text();
						var dueamount = parseFloat(totalNzd) - parseFloat(lessamount);
						$('.due-amount').val(dueamount);
						location.reload();
					}
				}
			});
        });
    });
    /* Start: Payment delete with sweetalert and ajax */

    /* Start: Email send for invoice */
    $('#emailInvModal .submit').click(function(e){
		var modal = $(this).closest('div.modal'),
			form = modal.find('form'),
			isFormValid = form.valid(),
			formData = {};
		formData['client_name'] = $('input[name="clientName"]').val();
		formData['email_id'] = $('#emailInvEmail').val();
		formData['msg'] = $('#emailInvMsg').val();
		formData['os_amount'] = parseFloat($('#os-amount').val());
		formData['due_amount'] = parseFloat($('#due-amount').val());
		formData['invoice_id'] = $('input[name="invoice_id"]').val(); 

		if(isFormValid) {
			$.ajax({
				url: public_url+'invoices/send-invoice-mail',
				method: "POST",
				data: formData,
				success: function(response){
					var resp = JSON.parse(response);
					if(resp.status == "success"){
						swal({
							title:'Success',
							text: "Mail sent successfully",
							type: "success",
							showCancelButton: false,
							confirmButtonColor: "#5cb85c",
							confirmButtonText: "Ok",
							closeOnConfirm: true,
							allowOutsideClick: true
						}, 
						function(){
							$('#emailInvModal').modal('hide');
							location.reload();
						});
						
					}else{
						swal({
							title: "Error",
							text: resp.data,
							type: "error",
						}, 
						function(){
							$('#emailInvModal').modal('hide');
						});
					}
				}
	    	});
		}
	});
    /* End: Email send for invoice */

    /* Start: payment add modal hide */
    $('#paymentAddModal').on('hidden.bs.modal', function(){
    	var $this = $(this),
    		form = $this.find('#make-payment');

    	form.find('input[name="paymentMethod"]').val('');

    	var amountField = form.find('input[name="paymAmount"]');
    	amountField.val('');
    	setFieldNeutral(amountField);

    	var payDateField = form.find('input[name="paymentDate"]');
    	payDateField.val('');
    	setFieldNeutral(payDateField);

    	var paymProcessField =  form.find('select[name="paymProcess"]');
    	paymProcessField.val('');
    	setFieldNeutral(paymProcessField);

    	var paymReferenceField =  form.find('input[name="paymReference"]');
    	paymReferenceField.val('');
    	
    });
    /* End: payment add modal hide */

    /* Start: email invoice modal hide */
    $('#emailInvModal').on('hidden.bs.modal', function(){
    	var $this = $(this),
    		form = $this.find('form');

    	var emailInvEmailField = form.find('input[name="emailInvEmail"]');
    	emailInvEmailField.val('');
    	setFieldNeutral(emailInvEmailField);

    	var emailInvMsgField = form.find('input[name="emailInvMsg"]');
    	emailInvMsgField.val('');
    	
    });
    /* End: email invoice modal hide */

    /* Start: notes area show and hide */
	$('.notes-link').on('click', function(e){
		e.preventDefault();
		$('.notes').removeClass('hidden');
		$(this).addClass('hidden');
	});
	/* End: notes area show and hide */

	/* Start: Toggel make payment*/
	$('.make-payment-btn').on('click', function(e){
		e.preventDefault();
		var modal = $(this).closest('.modal'),
			paymentField = modal.find('.inv-payment-fields');
		if(paymentField.hasClass('hidden'))
			paymentField.removeClass('hidden');
		else{
			modal.find('input[name="paymAmount"]').val('');
			modal.find('select#payment-type').val('Direct Debit').selectpicker("refresh");
			paymentField.addClass('hidden');
		}
	});
	/* End: Toggel make payment*/

	/* Start: get epic amount for selected client */
	$('select#payment-type').on('change', function(){
		getClientEpicCredit();
	});

	//fetch all clients
	$('#client-offline').trigger('change');

});
/* End: document ready functionv */

/* Start: get client epic credit */
function getClientEpicCredit(){
	var modal = $('#invoiceModal'),
		paymentType = modal.find('select#payment-type').val(),
		epicField = modal.find('#client-epic-credit'),
		clientId = modal.find('input[name="clientId"]').val();

	if(clientId && paymentType == 'EPIC Credit'){
		$.get(public_url+'invoices/client/epic',{clientId:clientId}, function(response){
			epicField.text(response);
			epicField.closest('div').removeClass('hidden');
		},'JSON');
	}
	else{
		epicField.text('');
		epicField.closest('div').addClass('hidden');
	}
}
/* End: get client epic credit */

/* Start: Get Staff Details according to area */
function getStaff(areaId){
	$.get(public_url+'invoices/getstaff',{area_id:areaId}, function(response){
		var dd = $('select.staff-name:not(.old-staff)');
		if(response.status == "success"){
			var options = '<option value="">-- Select --</option>';
			if(response.staffs){
				$.each(response.staffs, function(val, text){
					options += '<option value="'+val+'">'+text+'</option>';
				});
			}
			dd.html(options);
			dd.selectpicker('refresh');
			getProduct(areaId);
		}
	},'JSON');
}
/* End: Get Staff details according to area */

/* Start: Get product details according to area */
function getProduct(areaId){
	if(areaId != ''){
		$.get(public_url+'invoices/getproduct',{area_id:areaId}, function(response){
			if(response.status == "success"){
				var data = $(response.products).filter(function( indx ) {
					return response.products[indx].is_deleted === false;
				});
				$('.product-populate').typeahead("destroy");
				$('.product-populate').typeahead({
					source:data,
					items:'all',
					afterSelect:function(selection){
						populateProductDetails(selection, $(this).prop('$element'));
					}
				});	
				$('.add-product').attr('disabled', false);
			}
			else{
				$('.add-product').attr('disabled', true);
			}
		},'JSON');
	}
}
/* End: Get product details according to area */

/* Start: Get Class details according to area */
function getClass(areaId){ 
	if(areaId != ''){
		var url = public_url+'settings/business/classes/all-by-condition';
		$.get(url,{area_id:areaId}, function(response){
			if(response.length > 0){
				var data = response; 
				$('.class-populate').typeahead("destroy");
				$('.class-populate').typeahead({
					source:data,
					items:'all',
					afterSelect:function(selection){
						populateProductDetails(selection, $(this).prop('$element'),'class');
					}
				});	
				$('.add-class').attr('disabled', false);
			}
			else{
				$('.add-class').attr('disabled', true);
			}
		},'JSON');
	}
}
/* End: Get Class details according to area */

/* Start: Get Service details according to area */
function getService(areaId){ 
	if(areaId != ''){
		var url = public_url+'settings/business/services/all-by-condition';
		$.get(url,{area_id:areaId}, function(response){
			if(response.length > 0){
				var data = response; 
				$('.service-populate').typeahead("destroy");
				$('.service-populate').typeahead({
					source:data,
					items:'all',
					afterSelect:function(selection){
						populateProductDetails(selection, $(this).prop('$element'),'service');
					}
				});	
				$('.add-service').attr('disabled', false);
			}
			else{
				$('.add-service').attr('disabled', true);
			}
		},'JSON');
	}
}
/* End: Get Service details according to area */

/* Start: populateProductDetails function */
function populateProductDetails(productDetails, field,type='product'){
	var currentRow = field.closest('.row');
	if(type == 'class' || type == 'service'){
		var id = productDetails.id,
			type = type,
			stock = 5,
			quantity = 1,
			taxType = productDetails.tax,
			salePrice = productDetails.price;
	}else{
		var id = productDetails.id,
		type = type,
		stock = productDetails.stock,
		quantity = 1,
		taxType = productDetails.tax,
		salePrice = productDetails.sale_price;
	}

	currentRow.find('.product-id').val(id);
	currentRow.find('.product-type').val(type);
	currentRow.find('.quantity-stock').val(stock);
	currentRow.find('.quantity').val(quantity);
	currentRow.find('.tax-type').val(taxType);

	var taxData = getTax(taxType, salePrice, 1);
	currentRow.find('.unit-price').val(taxData.unitprice);
	currentRow.find('.pre-unit-price').val(taxData.unitprice);
	currentRow.find('select.tax-name').val(taxData.taxname).selectpicker("refresh");
	currentRow.find('.total-price').val(taxData.totalprice);
	currentRow.find('.hidden-totalPrice').val(taxData.totalprice);

	calcTotalAmountAndTax();
}
/* End: populateProductDetails function */

/* Start: Start: Get tax function */
function getTax(taxType, price, unit, taxVal, taxLabel){
	var taxData = {},
		unitPrice = 0,
		totalPrice = 0;
		if(typeof taxVal != 'undefined'){
			var currentTaxVal = taxVal,
				currentTaxLabel = taxLabel;
		}
		else{
			var currentTaxVal = $('input[name="tax-rat"]').val(),
				currentTaxLabel = $('input[name="tax-label"]').val();
		}

	if(taxType == 'Excluding'){
		unitPrice = parseFloat(price);
		totalPrice = (unitPrice * currentTaxVal)/100;
		totalPrice = parseFloat(totalPrice) + unitPrice;
		taxName = currentTaxLabel;
	}
	else if(taxType == 'Including'){
		totalPrice = parseFloat(price);
		unitPrice = parseFloat(totalPrice / (1 + (currentTaxVal/100)));
		taxName = currentTaxLabel;
	}
	else{
		totalPrice = parseFloat(price);
		unitPrice = parseFloat(price);
		taxName = currentTaxLabel;
	}

	if(typeof unit != 'undefined' && unit)
		unit = unit;
	else
		unit=1;

	taxData['unitprice'] = unitPrice.toFixed(2);
	taxData['totalprice'] = (totalPrice * unit).toFixed(2);
	taxData['taxname'] = taxName;

	return taxData;
}
/* End: Start: Get tax function */

/* Start: total amount and total tax amount calculate */ 
function calcTotalAmountAndTax(){
	var modal = $('#invoiceModal'),
		totalAmount = 0,
		totalUnitPrice = 0;

	modal.find('.new-row').each(function(){
		var $this = $(this),
			quentity = $this.find('.quantity').val(),
			total = $this.find('.total-price').val(),
			unitprice = $this.find('.unit-price').val();

		if(quentity != '' && total != '' && unitprice != ''){	
			totalAmount += parseFloat(total);
			totalUnitPrice += (parseFloat(unitprice) * quentity);
		}
	})

	if(isNaN(totalUnitPrice)){
		var finalTaxAmount = 0,
			finalAmount = 0;
	}
	else{
		var finalTaxAmount = (totalAmount - totalUnitPrice).toFixed(2),
			finalAmount = totalAmount.toFixed(2);
	}

	modal.find('#tax-value').html(finalTaxAmount);
	modal.find('#total-value').html(finalAmount);

	modal.find('input[name="totalTaxAmount"]').val(finalTaxAmount);
	modal.find('input[name="totalAmount"]').val(finalAmount);
}
/* End: total amount and total tax amount calculate */

/* Start: populate invoice data */
function generateNewRow(type, data){
	var prepareData = {};
	if(typeof data != 'undefined' && Object.keys(data).length > 0){
		prepareData = data;
	}
	else if(type == 'custom'){
		prepareData = {staff_name:'', product_name:'', product_id:0, product_type:'custom', quantity:1, quantity_stock:5, tax_name:'', tax_type:'Including', unit_price:'', total_price:'', hidden_total_price:''}
	}
	else if(type == 'class'){
		prepareData = {staff_name:'', product_name:'', product_id:'', product_type:'class', quantity:1, quantity_stock:5, tax_name:'', tax_type:'Including', unit_price:'', total_price:'', discount_amount:'', hidden_total_price:''}
	}
	else if(type == 'service'){
		prepareData = {staff_name:'', product_name:'', product_id:'', product_type:'service', quantity:1, quantity_stock:5, tax_name:'', tax_type:'Including', unit_price:'', total_price:'',discount_amount:'', hidden_total_price:''}
	}
	else{
		prepareData = {staff_name:'', product_name:'', product_id:'', product_type:'', quantity:'', quantity_stock:'', tax_name:'', tax_type:'', unit_price:'', total_price:'', discount_amount:'',hidden_total_price:''}  
	}

	createInvoiceRow(type, prepareData)		
}
/* End: populate invoice data */ 

/* Start: Generate new row */
function createInvoiceRow(type, data){
	var modal = $('#invoiceModal'),
		appendRow = modal.find('#appendRow'),
		newRow = appendRow.find('.clone-row').clone();

	/* increse row number */
	rowNumb++;

	newRow.removeClass('hidden');
	newRow.removeClass('clone-row');
	newRow.addClass('new-row');
	newRow.attr('id','row_'+rowNumb); 

	/* create selectpicker for staff */
	var staffGroup = newRow.find('.staff-name-group');
    var staffdd = staffGroup.find('select.staff-name');
    staffGroup.empty();
    staffGroup.append(staffdd);
    staffGroup.find('select.staff-name').prop('name', 'staffName'+rowNumb).val(data.staff_name).data('title','-- Select --').removeClass('leave-cls').selectpicker('refresh');

    /* create selectpicker for tax */
    var taxGroup = newRow.find('.tax-name-group');
    var taxdd = taxGroup.find('select.tax-name');
    taxGroup.empty();
    taxGroup.append(taxdd);
    var taxNameField = taxGroup.find('select.tax-name');
    taxNameField.prop('name', 'taxName'+rowNumb).val(data.tax_name).removeClass('leave-cls');

    var productField = newRow.find('textarea[name="productName"]'); 
    productField.prop('name', 'productName'+rowNumb).val(data.product_name).removeClass('leave-cls');

    if(type == 'new-product'){
    	taxNameField.prop("disabled", true);
    }
    else if(type == 'class'){
		productField.removeClass('product-populate');
		productField.addClass('class-populate');
    }else if(type == 'service'){
		productField.removeClass('product-populate');
		productField.addClass('service-populate');
	}else{
		productField.removeClass('product-populate');
	}

    taxNameField.selectpicker('refresh');

    newRow.find('input[name="taxType"]').prop('name', 'taxType'+rowNumb).val(data.tax_type).removeClass('leave-cls');

    newRow.find('input[name="productId"]').prop('name', 'productId'+rowNumb).val(data.product_id).removeClass('leave-cls');
    newRow.find('input[name="productType"]').prop('name', 'productType'+rowNumb).val(data.product_type).removeClass('leave-cls');

    newRow.find('input[name="quantity"]').prop('name', 'quantity'+rowNumb).val(data.quantity).removeClass('leave-cls');
    newRow.find('input[name="quantityStock"]').prop('name', 'quantityStock'+rowNumb).val(data.quantity_stock).removeClass('leave-cls');

    newRow.find('input[name="unitPrice"]').prop('name', 'unitPrice'+rowNumb).val(data.unit_price).addClass('unit-price').removeClass('leave-cls');
    newRow.find('input[name="preUnitPrice"]').prop('name', 'preUnitPrice'+rowNumb).val(data.unit_price);
	if(data.product_type == 'Discount' && data.tax_name == 'GST'){
		newRow.find('input[name="totalPrice"]').prop('name', 'totalPrice'+rowNumb).val(-data.discount_amount).addClass('total-price').removeClass('leave-cls');
		newRow.find('input[name="hiddenGstPrice"]').prop('name', 'hiddenGstPrice'+rowNumb).val(-data.discount_amount).removeClass('leave-cls');
	}else{
		newRow.find('input[name="totalPrice"]').prop('name', 'totalPrice'+rowNumb).val(data.total_price).addClass('total-price').removeClass('leave-cls');
	}
    newRow.find('input[name="hiddenTotalPrice"]').prop('name', 'hiddenTotalPrice'+rowNumb).val(data.hidden_total_price).removeClass('leave-cls');

    /* inisialize popover */
    if(type == 'discount'){
    	newRow.find('.applyDiscount').hide();

    	/* Append after dicount row */
		newRow.insertAfter(modal.find('#row_'+dicountRowNumb));
    }
    else{
    	if(data.product_type == 'Discount'){
    		newRow.find('.applyDiscount').hide();
    	}
    	else{
		    discountPopoverOpt.title = "<strong class='discount-popover-title' data-discountrow='"+rowNumb+"'>Apply a discount</strong>";
			newRow.find('.applyDiscount').popover(discountPopoverOpt);
		}
		/* Append new row */
		appendRow.append(newRow);

		/* Get product according to area*/
		if(type == 'new-product') 
			getProduct(getSelectedArea());
		else if(type == 'class')
			getClass(getSelectedArea());
		else if(type == 'service')
			getService(getSelectedArea());
	}
}
/* End: Generate new row */

/* Start: clear new row */
function clearInvoiceForm(modal){
	modal.find('.new-row').remove();
	modal.find('.errorMsg').empty();
	var ClientField = modal.find('input[name="clientName"]'),
		LocationField = modal.find('select#locationDD').val(''),
		PaymentAmount = modal.find('input[name="paymAmount"]'),  
		PaymentType = modal.find('select#payment-type');

	modal.find('input[name="invoiceId"]').val('');
	modal.find('#tax-value').html(0);
	modal.find('#total-value').html(0);
	modal.find('input[name="clientId"]').val('');	
	modal.find('input[name="invoiceDate"]').val('');
	modal.find('input[name="dueDate"]').val('');

	LocationField.selectpicker("refresh");
	ClientField.val('');	
	PaymentAmount.val('');
	PaymentType.val('Direct Debit').selectpicker("refresh");

	setFieldNeutral(PaymentAmount);
	setFieldNeutral(LocationField);
	setFieldNeutral(ClientField);

	modal.find('.inv-payment-fields').addClass('hidden');
}
/* End: clear new row */

/* Start: get selected location area id */
function getSelectedArea(){
	return $('#invoiceModal').find('select[name="location-area"] option:selected').val();
}
/* End: get selected location area id */ 

/* Start: Unit price  */
function calculateUnitPriceWithTax(price, taxRate){
	price = parseFloat(price);
	var unitprice = parseFloat(price / (1 + (taxRate/100)));
	return unitprice.toFixed(2);
}
/* End: Unit price  */

/* Start: Set invoice field reset/nutral */
function setInvFieldNeutral($elem){
	var formGroup = $elem.closest('.inv-group');
	formGroup.removeClass('has-error has-success');
	formGroup.find('span.error').text('');
	formGroup.find('label.error').text('');
	formGroup.find('span.help-block').hide();
}
/* End: Set invoice field reset/nutral */

/* Start: Set invoice field invilid */
function setInvFieldInvalid(formGroup, helpBlockMsg, helpBlock){
	if(typeof helpBlock == 'undefined')
		var helpBlock = formGroup.find('span.help-block')

	if(formGroup.hasClass('has-success'))
		formGroup.removeClass('has-success')

	formGroup.addClass('has-error');
	helpBlock.html(helpBlockMsg);
	helpBlock.show();
}
/* Start: Set invoice field invilid */

/**** Hide Shipping Field ****/
$('#deliveryType').on('change', function(){
	var deliveryType = $(this).val();
	if(deliveryType == 'delivery' || deliveryType == ''){
		$('.hideShippFields').show();
	}else{
		$('.hideShippFields').hide();
	}
});

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  sublocality_level_1: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'short_name',
  postal_code: 'short_name'
};
function initAutocomplete() {
  autocomplete = new google.maps.places.Autocomplete(
	  document.getElementById('autocomplete2'), {types: ['geocode']});
  autocomplete.setFields(['address_component']);
  autocomplete.addListener('place_changed', fillInAddress);
}
function fillInAddress() {
  var place = autocomplete.getPlace();
  $('input[name="address_line_one"]').val('');
  $('input[name="address_line_two"]').val('');
  $('input[name="city"]').val('');
  $('input[name="postal_code"]').val(val);
  $('.countries').selectpicker('refresh');
  $('select.states').selectpicker('refresh');
  var streetNumber = route = sublocality_level_1 = city = stateCode = countryCode = postalCode = '';
  for (var i = 0; i < place.address_components.length; i++) {
	var addressType = place.address_components[i].types[0];
	var val = place.address_components[i][componentForm[addressType]];
	if (addressType == 'street_number') {
		streetNumber = val;
	}else if(addressType == 'route'){
		route = val;
	}else if(addressType == 'sublocality_level_1'){
		sublocality_level_1 = val;
	}else if(addressType == 'locality'){
		city = val;
	}else if(addressType == 'administrative_area_level_1'){
		stateCode = val;
	}else if(addressType == 'country'){
		countryCode = val;
	}else if(addressType == 'postal_code'){
		postalCode = val;
	}
  }
	$('input[name="address_line_one"]').val(streetNumber+' '+route);
	$('input[name="address_line_two"]').val(sublocality_level_1);
	$('input[name="city"]').val(city);
	$('.countries option').each(function(){
		if($(this).val() == countryCode){
			$(this).attr('selected','selected');
			$('.countries').trigger('change');
			var country_code = countryCode,
				selectedStates = $('select.states');
				
			if(country_code == "" || country_code == "undefined" || country_code == null){
				selectedStates.html('<option value="">-- Select --</option>');
				selectedStates.selectpicker('refresh');
			}
			else{       
				getStates(country_code,stateCode);
			}
		}
	});
	$('input[name="postal_code"]').val(postalCode);
}
function geolocate() {
  if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(function(position) {
	  var geolocation = {
		lat: position.coords.latitude,
		lng: position.coords.longitude
	  };
	  var circle = new google.maps.Circle(
		  {center: geolocation, radius: position.coords.accuracy});
	  autocomplete.setBounds(circle.getBounds());
	});
  }
}

function getStates(countryCode,stateCode = ''){
	var selectedStates = $('select.states');
	$.ajax({
		url: public_url+'countries/'+countryCode,
		method: "get",
		data: {},
		success: function(data) {
			var defaultState = stateCode,
				formGroup = selectedStates.closest('.form-group');

			selectedStates.html("");
			$.each(data, function(val, text){
				var option = '<option value="' + val + '"';
				if(defaultState != '' && defaultState != null && val == defaultState)
					option += ' selected';
				option += '>' + text + '</option>';
				selectedStates.append(option);
			});

			$('.countries').selectpicker('refresh');
			selectedStates.selectpicker('refresh');
			setFieldValid(formGroup, formGroup.find('span.help-block'))
		}
	});
}

$('.countries').change(function(){
	var countryCode = $(this).val();
	getStates(countryCode);
});

$('body').on('click','#UseExisting',function(){
	var modal = $('#invoiceModal');
	var form = modal.find('#invoice-form');
	toggleWaitShield('show');
	if($(this).is(":checked")){
		var clientId = $('input[name="clientId"]').val();
		if(clientId != '' && clientId != null){
			$.ajax({
				url: public_url+'client-address/'+clientId,
				method: "get",
				data: {},
				success: function(data) {
					toggleWaitShield('hide');
					if(data.status == 'ok'){
						var addressData = data.addressData;
						form.find('input[name="address_line_one"]').val(addressData.addressline1);
						form.find('input[name="address_line_two"]').val(addressData.addressline2);
						form.find('input[name="city"]').val(addressData.city);
						form.find('select.countries').val(addressData.country).selectpicker("refresh");
						form.find('select.states').empty().selectpicker("refresh");
						if(addressData.country != '' && addressData.country != null){
							getStates(addressData.country,addressData.addrState);
						}
						form.find('input[name="postal_code"]').val(addressData.postal_code);
					}
				}
			});
		}else{
			setFieldInvalid($('input[name="clientId"]').closest('.form-group'));
			toggleWaitShield('hide');
		}
	}else{
		form.find('input[name="address_line_one"]').val('');
		form.find('input[name="address_line_two"]').val('');
		form.find('input[name="city"]').val('');
		form.find('select.countries').val('').selectpicker("refresh");
		form.find('select.states').empty().selectpicker("refresh");
		form.find('input[name="postal_code"]').val('');
		toggleWaitShield('hide');
	}
});

$('body').on('change','.tax-name',function(){
	var element = $(this).parents('.new-row');
	var unitPriceElement = element.find('.unit-price');
	if(unitPriceElement.val() != '' && unitPriceElement.val() != null){
		var $this = unitPriceElement,
			isError = false,
			unitprice = parseFloat($this.val()),
			currentRow = $this.closest('.row'),
			preUnitVal = parseFloat(currentRow.find('.pre-unit-price').val());
		if(unitprice != '' && preUnitVal != unitprice){
			var taxname = currentRow.find('select.tax-name');
			if(taxname.val() == ''){
				isError = true;
				setFieldInvalid(taxname.closest('.col-md-1'));
				// alert('Please select tax.');	
			}

			var quantity = currentRow.find('.quantity');
			if(quantity.val() == ''){
				isError = true;
				setFieldInvalid(quantity.closest('.col-md-1'));
				alert('Please enter quantity.');	
			}

			if(!isError){
				var prodType = element.find('input.product-type').val(),
					taxType = currentRow.find('input.tax-type').val(),
					taxVal = currentRow.find('select.tax-name option:selected').data('tax');

				var priceData = getTax(taxType, unitprice, quantity.val(), taxVal, taxname.val());
				$this.val(priceData['unitprice']);
				currentRow.find('.pre-unit-price').val(priceData['unitprice']);
				currentRow.find('input.total-price').val(priceData['totalprice']);
				currentRow.find('input.hidden-totalPrice').val(priceData['totalprice'] / quantity.val());
				calcTotalAmountAndTax();
			}
		}else if(preUnitVal == unitprice){
			var taxname = currentRow.find('select.tax-name');
			if(taxname.val() == ''){
				isError = true;
				setFieldInvalid(taxname.closest('.col-md-1'));
				// alert('Please select tax.');	
			}

			var quantity = currentRow.find('.quantity');
			if(quantity.val() == ''){
				isError = true;
				setFieldInvalid(quantity.closest('.col-md-1'));
				alert('Please enter quantity.');	
			}

			if(!isError){
				var prodType = element.find('input.product-type').val(),
					taxType = currentRow.find('input.tax-type').val(),
					taxVal = currentRow.find('select.tax-name option:selected').data('tax');
					gstTotal = currentRow.find('input.hidden-gstPrice').val();
				var preUnitPrice = currentRow.find('input.hidden-totalPrice').val();
				var priceData = getTax(taxType, preUnitPrice, quantity.val(), taxVal, taxname.val());
				$this.val(priceData['unitprice']);
				if(prodType == 'Discount' && priceData['taxname'] == 'GST'){
                    currentRow.find('input.total-price').val(gstTotal);
				}else{
					currentRow.find('input.total-price').val(priceData['totalprice']);

				}
				currentRow.find('.pre-unit-price').val(priceData['unitprice']);
				currentRow.find('input.hidden-totalPrice').val(priceData['totalprice'] / quantity.val());
				calcTotalAmountAndTax();
			}
		}
	}
});

/* Start: Fatch All clients */
function fetchAllClients(){
	$.get(public_url+'clients/all', function(data){
		$('.clientList').typeahead({
			source:data,
			items:'all',
			afterSelect:function(selection){
				var field = $(this).prop('$element');
					field.siblings('input[name="clientId"]').val(selection.id);
					getClientEpicCredit();
			}
		})
	},'JSON');
}
/* End : Fatch all Clients */

//JS of set offline client
$('body').on('change','#client-offline',function(){
	let invoiceModel = $('#invoiceModal');
	if($(this).is(':checked')){
		$('.clientList').typeahead("destroy");
	}else{
		fetchAllClients();
	}
})
