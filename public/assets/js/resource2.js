
$(document).ready(function(){
	   initCustomValidator();
});


$(".submitresource").on( "click", function(e) {
	//alert("fss");
	e.preventDefault();   
	var formData = {};
	var form = $('#resourceForm');
	var isFormValid = form.valid();
    formData['resourceId'] = $('input[name="resourceId"]').val();

	if(isFormValid){
		$.each($(form).find(':input').serializeArray(), function(i, obj){
			formData[obj.name] = obj.value
		});
		console.log(formData);
		if(!formData['resourceId']){
            $.post(public_url+'settings/business/resources', formData, function(response){
            	var data = JSON.parse(response);
                if(data.status == "added"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+"settings/business/resources"; //+data.discountid
                }
            });
        }
        else{
            $.ajax({
                url : public_url+'settings/business/resources/'+formData['resourceId'],
                type : 'PATCH',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "updated"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+"settings/business/resources";
                    }
                },

            });
        }  

        scrollToTop(form);
    }//validate close
    return false
});

