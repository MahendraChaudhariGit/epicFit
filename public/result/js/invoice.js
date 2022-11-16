$(document).ready(function(){
	var public_url = $('meta[name="public_url"]').attr('content');
    var clientAppointModal = $('#clientAppointModal'),
        appointCheckboxsChecked = 0,
	    paymentAddModal = $('#paymentAddModal'),
        paymentMethod = paymentAddModal.find('.payment-method');
        //$('.make-payment').show();


    /* Initializing datepicker */
    $('.datepicker').datepicker({autoclose:true, format:"d M yyyy"}); 


    /* Select all appointments of client */

    $('body').on('change', 'input[name="clientAppointCheckAll"]', function(){
        var appointCheckboxs = clientAppointModal.find('.appoint-checkboxs');

        if($(this).is(':checked')){
            appointCheckboxs.prop('checked', true)
            appointCheckboxsChecked = appointCheckboxs.length;
        }
        else{
            appointCheckboxs.prop('checked', false)
            appointCheckboxsChecked = 0;
        }
    }); 

    /* Toggle 'select all' checkobx in client appointments modal */
    $('body').on('change', '.appoint-checkboxs', function(){
        var clientAppointCheckAll = clientAppointModal.find('input[name="clientAppointCheckAll"]');

        if($(this).is(':checked')){
            appointCheckboxsChecked++;

            if(appointCheckboxsChecked == clientAppointModal.find('.appoint-checkboxs').length)
                clientAppointCheckAll.prop('checked', true)
        }
        else{
            appointCheckboxsChecked--;
            clientAppointCheckAll.prop('checked', false)
        }
    }); 


    /* Payment delete sweetalert and ajax */
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
            $this.parent().remove();
				$.ajax({
					url: public_url+'delete-payment',
					method: "POST",
					data: {'id':pId,'amount':$this.data('amount'),'invoice_id':$this.data('invoice')},
					success: function(data){
						var data = JSON.parse(data);
						if(data.status == "ok"){
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

    /* Invoice delete sweetalert and ajax */
    $('.delete-invoice').click(function(e){
        e.preventDefault();
        var $this = $(this);
		var invId = $this.data('id');
		swal({
            title: "Are you sure?",
            text: "Any associated payments will also be deleted.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d43f3a",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: true
        }, 
        function(){
             $.ajax({
				url: public_url+'delete-invoice',
				method: "POST",
				data: {'id':invId},
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "ok"){
						window.location.href =  public_url+'view-invoices';
					}
				}
		  });    
        });
    }); 

    /* Toggle payment fields based on payment type */
    paymentMethod.click(function(e){
        e.preventDefault();
        var $this = $(this),
            targetFieldId = $this.attr('href');

        paymentAddModal.find('.payment-fields').hide();
        paymentMethod.removeClass('active');
        $this.addClass('active');

        if(targetFieldId != '#')
           paymentAddModal.find(targetFieldId).show();
    });
    paymentMethod.first().trigger('click');
	
	 /* send invoice with mail and ajax */
	 
	$('#emailInvModal .submit').click(function(e){
		isFormValid = true;
		var formData = {},
		modal = $(this).closest('div.modal'),
		form = modal.find('form');
		var emailFieldVal = $('#emailInvEmail').val();
		var emailMsg = $('#emailInvMsg').val();
		var osamount = $('.total-nzd').text();
		var dueamount = $('.due-amount').text();
		var invoiceId = $('.delete-invoice').data('id');
		setTimeout(function(){ 
		var isFormValid = form.valid();
		if(emailFieldVal == ''){
			isFormValid = false;
		}
	}, 200);
	if(isFormValid) {
		$.ajax({
				url: public_url+'send-invoice-mail',
				method: "POST",
				data: {'email_id':emailFieldVal,'msg':emailMsg,'os_amount':osamount,'due_amount':dueamount,'invoice_id':invoiceId},
				success: function(response){
					var resp = JSON.parse(response);
					if(resp.status == "ok"){
						$('#emailInvModal').modal('hide');
						location.reload();
					}
				}
	    	});
	}
	});
	$('.payment-method').click(function(){
		var paymentMethod = $(this).text();
		var totalAmount = $('.payment-fields').find('.pay-amount').find('input[name="invoiceAmount"]').attr('value');
		$('.payment-fields').find('.pay-amount').find('input[name="paymAmount"]').val(' ');
		$('.payment-fields').find('.pay-ref').find('input[name="paymReference"]').val(' ');
		$('.payment-fields').find('.pay-amount').find('input[name="paymAmount"]').val(totalAmount);
		$('.payment-method-name').val(paymentMethod);
	});
	$('.make-payment').click(function(){
		$('.payment-method').removeClass('active');
		$('.default').addClass('active');
		$('#nonaccount-fields').hide();
	});
	$(".submit",paymentAddModal).click(function(){
		var paymentClass = $('.payment-fields').find('.pay-amount').find('.input-group');
		var paymentAmount = $('.payment-fields').find('.pay-amount').find('#paymAmount').attr('value');
		var totalAmount = $('.payment-fields').find('.pay-amount').find('input[name="invoiceAmount"]').attr('value');
		var remainingAmount = $('.payment-fields').find('.pay-amount').find('input[name="remainingAmount"]').attr('value');
		if(parseFloat(paymentAmount) > parseFloat(totalAmount)){
			$('.payment-fields').find('.pay-amount').find('#paymAmount').val(totalAmount);
		}
		if(paymentAmount == ''){
			setFieldInvalid(paymentClass);
		} else {
			var data = $('#make-payment').serialize();
			var requestUrl = public_url+'save-payment';
			$.ajax({
				url: requestUrl,
				method: "POST",
				data: {'form_data':data},
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "ok"){
						$('#paymentAddModal').modal('hide');
						location.reload();
					}
				}
			});
		}
	});
});
function setFieldInvalid($formGroup){
	$formGroup.addClass('has-error');
}
