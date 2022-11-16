
$(document).ready(function(){
       initCustomValidator();
       //initTelInput($(".countryCode"));       
       $(document).on("countrychange", ".countryCode", function(){
            initTelInputOnChange($(this));
        });


});



$('.countries').on('changed.bs.select', function(e){
        updateState($(this));
});

$(".savebtn").on("click", function(e){
    //alert("save function");
    e.preventDefault();   
    var formData = {};
    var form = $('#adminForm');
    var isFormValid = form.valid();
    if(!(validateUpload($(form).find("input[name='adminProfilePicture']"))))
            isFormValid = false;
    if(!(validateContryCode($(form).find("input[name='phone']"))))
            isFormValid = false;
    //if($(this).hasClass('editMode')){
        var newPwdField = form.find("input[name='newPwdField']"),
            newPwdCnfmField = form.find("input[name='newPwdCnfmField']");
        if(!validateNewPassword({newPwdField:newPwdField, newPwdCnfmField:newPwdCnfmField}))
                    isFormValid = false;
        else{
            setFieldNeutral(newPwdField);
            setFieldNeutral(newPwdCnfmField);
           // $('.js-adminPwdFieldset').find('.help-block').text("");
            formData['newPassword']=newPwdField.val();
            formData['newCnfmPassword']=newPwdCnfmField.val();
         } 
     //}   

    if(isFormValid){
        formData['firstname'] = $('input[name="firstName"]').val();
        formData['lastname'] = $('input[name="lastName"]').val();
        formData['email'] = $('input[name="email"]').val();
        formData['address_line_one'] = $('input[name="address_line_one"]').val();
        formData['address_line_two'] = $('input[name="address_line_two"]').val();
        formData['city'] = $('input[name="city"]').val();
        formData['country'] = $('select[name="country"]').val();
        formData['state'] = $('select[name="state"]').val();
        formData['postal_code'] = $('input[name="postal_code"]').val();
        formData['photoName'] = $('input[name="adminProfilePicture"]').val();
        formData['adminId'] = $('input[name="adminId"]').val();
        formData['admin_permissions'] = $('select[name="admin_permissions"]').val();
        formData['phone'] = getFullNumb(form);
        
        
        

        /*if(!formData['adminId']){
            $.post(public_url+'settings/business/admin', formData, function(response){
                var data = JSON.parse(response);
                if(data.status == "error"){
                    $.each(data.errorData, function(key, val){
                        $.each(this, function(errorType, message){                        
                            if(errorType == "emailExist" )
                                var field = $(form).find("input[name='email']");
                            setFieldInvalid(field.closest('.form-group'), message, field.next("span"))
                        });                         
                    });
                }
                else if(data.status == "added"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+"settings/business/admin"; //+data.adminId
                }
            });
        }
        else{
            $.ajax({
                url : public_url+'settings/business/admin/'+formData['adminId'],
                type : 'PATCH',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "updated"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = document.referrer;
                    }
                },
            });
        }*/

        if(!formData['adminId']){
            var ajaxType = 'POST',
                action = '';
        }
        else{
            var ajaxType = 'PATCH',
                action = '/'+formData['adminId'];
        }

        $.ajax({
            url : public_url+'settings/business/admin'+action,
            type : ajaxType,
            data : formData,
            success : function(response) {
               var data = JSON.parse(response);
                if(data.status == "error"){
                    $.each(data.errorData, function(key, val){
                        $.each(this, function(errorType, message){                        
                            if(errorType == "emailExist" )
                                var field = $(form).find("input[name='email']");
                            else if(errorType == "phoneExist" )
                                var field = $(form).find("input[name='phone']");
                            setFieldInvalid(field.closest('.form-group'), message, field.next("span"))
                        });                         
                    });
                }
                else if(data.status == "added"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+"settings/business/admin"; //+data.adminId
                }
                else if(data.status == "updated"){
                    saveSuccess(form, {action:'show', type:'update'});
                    window.location.href = document.referrer;
                }
            },
        });
    }
    scrollToTop(form);          
});

function updateState(contryDd){
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
}
