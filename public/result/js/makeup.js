$('document').ready(function(){
/* Start: Makeup model */
    $("#raiseMakeUpModel").on('show.bs.modal', function(evt){
        swal.close();
        clearNotific('amountOverFlow');
        var $this = $(this);
        var elem = $(evt.relatedTarget);

        var netamount=elem.data('netamount');
        if(netamount <= 0){
            netamount='0.00';
        }
        $this.find('[name="net_amount"]').val(netamount);
        $this.find('#netamount').text(netamount);

        var purposeFiled=$this.find('[name="purpose"]').val('');
        purposeFiled.selectpicker('refresh');
        setFieldNeutral(purposeFiled);

        $this.find('[name="notes"]').val('');

        var amount=$this.find('[name="amount"]');
        setFieldNeutral(amount);
        amount.val('');

        $this.find('[name="makeupCheck"]').attr('checked', false);

        $this.find('[name="reload_check"]').val(elem.data('check'));
        $this.find('[name="clientId"]').val(elem.data('client-id'));


        /*var clientId = sourceLnk.data('client-id');
            modal.find('input[name="clientId"]').val(clientId);*/

            /*var callback = elem.data('callback');
            $this.find('input[name="callback"]').val(callback);*/

            

    });
     
    $("#raiseMakeUpModelSubmit").click(function(){
        var modal = $(this).closest('.modal'),
        form = modal.find('form'),
        isFormValid = true;
        var formData = {};
        var c_id=0;
        clearNotific('amountOverFlow');
        
        var callback = modal.find('[name="callback"]').val();
        if(typeof callback == 'undefined' || callback == ''){
            isFormValid = form.valid();
            if(isFormValid){
                $.each($(form).find(':input').serializeArray(), function(i, obj){
                    formData[obj.name] = obj.value
                });
                var amountField=form.find('[name="amount"]');
                var amount=parseInt(amountField.val());
                if(amount <= 0){
                    setFieldInvalid(amountField.closest('.form-group'), 'Please enter valid amount.');
                    return false;
                }
                c_id=formData['clientId'];
                var reloadCheck=formData['reload_check'];
                var flag=false;
                /*var amount= parseInt(formData['amount']);*/
                var net_amount=parseInt(formData['net_amount']);

                formData['action']='raise';
                if($('[name="makeupCheck"]').is(":checked")){ 
                    formData['action']='fall';
                    if(amount > net_amount){
                        flag=true;
                         showNotific(prepareNotific('error', "Client does not have enough amount to deducted.", 'amountOverFlow'), form);
                    }
                }
                $(this).prop('disabled', true);
                if(!flag){  
                    $.ajax({
                        url : public_url+'clients/raise-make-up',
                        type : 'POST',
                        data : formData,
                        success : function(response) {
                            $('#raiseMakeUpModelSubmit').prop('disabled', false);
                            var data = JSON.parse(response);
                            if(data.status == "success"){
                                if(reloadCheck=='yes'){
                                    modal.modal('hide');
                                    reloadPageWithTab('#makeup');
                                }
                                else{
                                    modal.modal('hide');
                                    $('.data-btn-'+c_id).data("netamount" , data.netamount);
                                    $('.epic-bal-'+c_id).text(data.netamount);  
                                }      
                            }
                        },

                    });
                }
            }
        
        }
    });  
/* End: Makeup model */
});
