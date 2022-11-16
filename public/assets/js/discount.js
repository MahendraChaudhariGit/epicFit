$(document).ready(function(){
   initCustomValidator();
   //var selectvalue = $('select#taxid').val();

   $('select#discountid').on("change", function(){
        ddVal = $(this).val();
        changeicon(ddVal);
        /*if($(this).val()=="Percentage"){
            $('#targetDiv i').removeClass('fa-dollar');
            $('#targetDiv i').addClass('fa-xyz');
            console.log($('#targetDiv i'));
        }
        else if($(this).val()=="Fixed amount"){
            $('#targetDiv i').removeClass('fa-xyz');
            $('#targetDiv i').addClass('fa-dollar');
        }*/
    });
    changeicon($('select#discountid').val());
});


function changeicon(elem){
    $('#targetDiv').show();
   if(elem=="Percentage"){
        $('#targetDiv i').removeClass('fa-dollar');
        $('#targetDiv i').text('%');
    }
    else if(elem=="Fixed amount"){
        $('#targetDiv i').text('');
        $('#targetDiv i').addClass('fa-dollar');
    } 
    else{
        $('#targetDiv').hide();
    }
}

/* start: Change error message placement */
function moveErrorMsg(modal){
    var moveErrMsgs = modal.find('.moveErrMsg');
    if(moveErrMsgs.length){
        moveErrMsgs.each(function(){
            var moveErrMsg = $(this),
                formGroup = moveErrMsg.closest('.form-group'),
                errors = moveErrMsg.find('.help-block'),
                errorMsg = '';

            errors.each(function(){
                var errorTxt = $(this).text();

                if(errorTxt != ''){
                    formGroup.addClass('has-error');
                    errorMsg = errorTxt;
                    return false;
                }
            });
            formGroup.find('.placeErrMsg').html(errorMsg)
        })
    }
}
/* end: Change error message placement */

$(".savebtn").on( "click", function(e) {
	e.preventDefault();   
    var formData = {};
	var form = $('#discountForm');
	var isFormValid = form.valid();
    formData['discountid'] = $('input[name="discountId"]').val();

    moveErrorMsg($(this).closest('form'));
	
    if(isFormValid){
		formData['discountName'] = $('input[name="discountname"]').val();
        formData['taxId'] = $('select#taxid').val();
        formData['discountType'] = $('select#discountid').val();
        formData['discountValue'] = $('input[name="discountvalue"]').val();
        //console.log(formData['discountName']);
        //console.log(formData['taxId']);
        //console.log(formData['discountType']);
        //alert(formData['discountid'])
        if(!formData['discountid']){
            $.post(public_url+'sales-tools/discounts', formData, function(response){
            	var data = JSON.parse(response);
                if(data.status == "success"){
                   //saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+"sales-tools/discounts"; //+data.discountid
                }
            });
        }
        else{
            $.ajax({
                url : public_url+'sales-tools/discounts/'+formData['discountid'],
                type : 'PATCH',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "success"){
                    //saveSuccess(form, {action:'show', type:'update'});
                    window.location.href = document.referrer;
                    }
                },

            });
        }  

        scrollToTop(form);

	}
});
	