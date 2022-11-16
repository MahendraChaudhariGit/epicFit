var rowCreated = 0;
var rowCreatedTexRate=0;
var rowCreatedTexName=0;

$(document).ready(function(){
	   initCustomValidator();

 
	$('body').on('click', '.remove-payment-row', function(e){
		e.preventDefault();
		var countLength = $('.payment-type').length;
		if(countLength > 1){
			$(this).closest('.row').remove();
		}else{
			swal({
			  title: "Atleast one payment type is compulsory",
			  icon: "warning",
			  dangerMode: true,
			});
		}
	}); 

	$('#add-payment-row').click(function(e){
		e.preventDefault();
		rowCreated++;
		var paymentRow = '<div class="row payment-type"><div class="col-md-11 col-xs-11"><div class="form-group"><input class="form-control" name="payment_type[]" type="text" value="" required="required"></div></div><div class="col-md-1 col-xs-1 p-l-0"><a class="btn btn-xs btn-red remove-payment-row" href="#"><i class="fa fa-times fa fa-white"></i></a></div></div>';
		// var paymentRow=$("#payment-box-add").clone();
		$("#payment-box-add").append(paymentRow);
	});
	//for texes field add random..............
	$('#add-taxes-row').click(function(e){
		e.preventDefault();
		rowCreatedTexName++;
		rowCreatedTexRate++;

		var taxesRow=$(".taxes-box-area")/*.children().first()*/.clone();
		taxesRow.removeClass('hidden');
		taxesRow.removeClass('taxes-box-area');
		
		/*var textBoxName1 = taxesRow.find('input[type="text"]:eq(0)').attr('name');
		var textBoxName2 = taxesRow.find('input[type="text"]:eq(1)').attr('name');*/
		taxesRow.find('input[name="name_text"]').attr('value',"").attr('name','newTaxName'+rowCreatedTexName).prop('required',true);
		taxesRow.find('input[name="name_rate"]').attr('value',"").attr('name','newTaxRate'+rowCreatedTexRate).prop('required',true);
		taxesRow.find('input[type="hidden"]').remove();
		/*taxesRow.find('.taxusuage').remove();*/
		/*taxesRow.find('.remove-payment-row').remove();*/
		/*taxesRow.find('.onblur-event').removeData('pre-val');
		taxesRow.removeClass('onblur-event');*/
		$("#taxes-row-add").append(taxesRow);
        fieldDisabled();
	});

	//for delete text field of payment...................	
	// $('body').on('click', '.remove-payment-row', function(e){
	// 	e.preventDefault();
	// 	$(this).closest('.row').remove();
	// }); 

	//onblur event in taxes field.....
	$(".onblur-event").blur(function(e){
		e.preventDefault();
		   var oldvalue=$(this).data('pre-val');
           var newvalue=$(this).val();
           if(oldvalue!=newvalue)
	           {
                  fieldDisabled();
                  $(this).data('pre-val',newvalue);
	           }
	});

});

function fieldDisabled()
{
	$("select.createbox-hide").prop("disabled", true).selectpicker('refresh');
    $(".createbox-hide").prop("disabled", true);
}



$(".submitinvoice").on( "click", function(e) {
	e.preventDefault();   
	var formData = {};
	var form = $('#invoiceForm');
	form.validate().settings.ignore = 'select[name="overrideAll"]';
	var isFormValid = form.valid();
	var errorField=$('#taxes-row-add');
    moveErrorMsg(errorField);
/*
	formData['payterms'] = $('select[name="payTerms"]').val();
	formData['invtitle'] = $('input[name="invTitle"]').val();
	formData['bussreg'] = $('input[name="bussReg"]').val();
	formData['bussregtype'] = $('input[name="bussRegType"]').val();
	formData['payinst'] = $('#payInst').val();
	formData['nxtinvnum'] = $('input[name="nxtInvNum"]').val();
	formData['hidebusiness'] = $('input[name="hidebusiness"]').is(':checked');
	formData['hidePayment'] = $('input[name="hidePayment"]').is(':checked');
*/

if(isFormValid){
		$.each($(form).find(':input').serializeArray(), function(i, obj){
			if(obj.name != 'payment_type[]'){
				formData[obj.name] = obj.value
			}
		});
		var paymentTypes = [];
		$('input[name="payment_type[]"]').each(function(){
			paymentTypes.push($(this).val());
		})
		formData['payment_type'] = paymentTypes;
		$.ajax({
                url : public_url+'sales-tools/invoice/'+formData['invoiceId'],
                type : 'PATCH',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "updated"){
                    location.reload(true);
                	}
                },

        });
}

});

$("#applybtn").on("click", function(e){
	e.preventDefault();   
	var formData = {};
	var form = $('#invoiceForm');
	var isFormValid = $('select[name="overrideAll"]').valid();
	//form.validate().element('select[name="overrideAll"]');
	formData['overrideexist'] = 1;
	formData['invoiceId'] = $('input[name="invoiceId"]').val();
	formData['overrideall'] = $('select[name="overrideAll"]').val();
    if(isFormValid){
	    $.ajax({
            url : public_url+'sales-tools/invoice/'+formData['invoiceId'],
            type : 'PATCH',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "updated"){
                    saveSuccess(form,{action:'show',type:'overrideTax'});
                	}
                },
		});
	}
});

