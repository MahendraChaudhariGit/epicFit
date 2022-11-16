$(document).ready(function () {

    var incomeTaxHtml = '<div class="row"><div class="col-md-12"><div class="Tax">' +
        '<div class="col-md-4"><div class="form-group"><label class="strong" for="tax_type">From</label>' +
        '</div></div><div class="col-md-4"><div class="form-group">' +
        '<label class="strong" for="tax_type">To</label></div></div><div class="col-md-4">' +
        '<div class="form-group col-md-6"><label class="strong" for="tax_type">%</label></div>' +
        '</div></div></div></div><div class="row incomeTax"> ' +
        '<div class="col-md-12"><div class="Tax"><div class="col-md-4">' +
        '<div class="form-group"> <input type="text" class="form-control cfrom_amount" name="from_amount">' +
        '</div> </div><div class="col-md-4"><div class="form-group">' +
        ' <input type="text" class="form-control cto_amount" name="to_amount"></div></div>' +
        '<div class="col-md-4"><div class="form-group col-md-6">' +
        '<input type="text" class="form-control ctax_percentages" name="tax_percentages"></div>' +
        '<button type="button" class="btn btn-primary incTaxBtn btn-o btn-sm p-y-0 pull-right add_income_tax_field_btn"' +
        '  data-id="0" role="button" value="Small Default" title="Add"><i class="fa fa-plus-circle"></i></button>' +
        ' </div> </div> </div> </div>';

    $(document).on('click',".addTax",function () {
        $(document).find("#update-company-tax").hide();
        $(document).find("#update-partnership-tax").hide();
        $(document).find("#update-other-tax").hide();
        $(document).find("#update-gst-tax").hide();
        $(document).find("#save-company-tax").show();
        $(document).find("#save-partnership-tax").show();
        $(document).find("#save-other-tax").show();
        $(document).find("#save-gst-tax").show();

        // default set
        $(document).find('#company_tax_type,#partnership_tax_type,#other_tax_type').selectpicker('val', 'income');
        $(document).find('.companyDynamicSec,.partnershipDynamicSec,.otherDynamicSec').html(incomeTaxHtml);
        $(document).find('input[type="text"]').val('');
    });


    /*================ company tax start ======================*/


    var gstTaxHtml = '<div class="col-md-6"><div class="gstTax"><div class="form-group">' +
        '<label class="strong" for="tax_amount">Tax Amount:</label>' +
        '<input type="text" class="form-control tax_amount" name="tax_amount"></div></div></div>';

    var otherTaxHtml = '<div class="col-md-6"><div class="otherTax"><div class="form-group">' +
        '<label class="strong" for="tax_name">Tax Name:</label>' +
        '<input type="text" class="form-control tax_name" name="tax_name"></div></div></div>' +
        '<div class="col-md-6"><div class="otherTax"><div class="form-group">' +
        '<label class="strong" for="tax_amount">Tax Amount:</label>' +
        '<input type="text" class="form-control tax_amount" name="tax_amount"></div></div></div>';

    $(document).on('change', 'select.tax_type', function () {
        var dynamicHTML = '';
        if ($(this).val() == 'income') {
            dynamicHTML = incomeTaxHtml;
        }
        if ($(this).val() == 'gst') {
            dynamicHTML = gstTaxHtml;
        }
        if ($(this).val() == 'other') {
            dynamicHTML = otherTaxHtml;
        }
        $(document).find('.dynamicSec').html(dynamicHTML);
    });

    $(document).on('click', '.add_income_tax_field_btn', function () {
        var tempContent = $(this).parent().parent().parent().parent();
        tempContent.addClass('tempFieldRow');
        $(document).find('.tempFieldRow').parent().append(tempContent.clone());
        var lastEditedRow = $(document).find('.tempFieldRow:last');
        lastEditedRow.find('input').val('');
        lastEditedRow.find('.add_income_tax_field_btn').removeClass('add_income_tax_field_btn').attr('data-id',0)
            .addClass('remove_income_tax_field_btn').html('<i class="fa fa-minus-circle" ></i>');
        $(document).find('.tempFieldRow').removeClass('tempFieldRow');
    });

    $(document).on('click', '.remove_income_tax_field_btn', function () {
        $(this).parent().parent().parent().parent().remove();
    });
    /*================ company tax end ======================*/

    // save
    $(document).on('click', '#save-company-tax', function () {
        var tax_category = 'company';
        var tax_type = $('#company_tax_type').val();
        var tax_code = $('#company_tax_code').val();
        var country = $('#country').val();
        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code , country,'.companyDynamicSec',0,0);
    });

    $(document).on('click', '#save-partnership-tax', function () {
        var tax_category = 'partnership';
        var tax_type = $('#partnership_tax_type').val();
        var tax_code = $('#partnership_tax_code').val();
        var country = $('#country').val();
        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,'.partnershipDynamicSec',0,0);
    });

    $(document).on('click', '#save-other-tax', function () {
        var tax_category = 'sole-trader';
        var tax_type = $('#other_tax_type').val();
        var tax_code = $('#other_tax_code').val();
        var country = $('#country').val();
        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,'.otherDynamicSec',0,0);
    });

    $(document).on('click', '#save-gst-tax', function () {
        var tax_category = 'gst';
        var tax_type = $('#gst_tax_type').val();
        var tax_code = $('#gst_no').val();
        var country = $('#country').val();
        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,'.gstDynamicSec',0,0);
    });


    // edit
    $(document).on('click', '.companyTaxEdit', function () {
        var ctId = $(this).attr('data-id');
        editTaxPreferences(ctId,'#company-tax-modal','.companyDynamicSec','#company_tax_type');
        $(document).find('#save-company-tax').hide();
        $(document).find('#update-company-tax').attr('data-id',ctId).show();
    });
    $(document).on('click', '.partnershipTaxEdit', function () {
        var ctId = $(this).attr('data-id');
        editTaxPreferences(ctId,'#partnership-tax-modal','.partnershipDynamicSec','#partnership_tax_type');
        $(document).find('#save-partnership-tax').hide();
        $(document).find('#update-partnership-tax').attr('data-id',ctId).show();
    });
    $(document).on('click', '.soleTraderTaxEdit', function () {
        var ctId = $(this).attr('data-id');
        editTaxPreferences(ctId,'#sole-trader-tax-modal','.otherDynamicSec','#other_tax_type');
        $(document).find('#save-other-tax').hide();
        $(document).find('#update-other-tax').attr('data-id',ctId).show();
    });
    $(document).on('click', '.gstTaxEdit', function () {
        var ctId = $(this).attr('data-id');
        editTaxPreferences(ctId,'#gst-tax-modal','.gstDynamicSec','#gst_tax_type');
        $(document).find('#save-gst-tax').hide();
        $(document).find('#update-gst-tax').attr('data-id',ctId).show();
    });


    // update
    $(document).on('click', '#update-company-tax', function () {
        var tax_category = 'company';
        var tax_type = $('#company_tax_type').val();
        var tax_code = $('#company_tax_code').val();
        var country = $('#country').val();

        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,'.companyDynamicSec',1,$(this).attr('data-id'));
    });

    $(document).on('click', '#update-partnership-tax', function () {
        var tax_category = 'partnership';
        var tax_type = $('#partnership_tax_type').val();
        var tax_code = $('#partnership_tax_code').val();
        var country = $('#country').val();
        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,'.partnershipDynamicSec',1,$(this).attr('data-id'));
    });

    $(document).on('click', '#update-other-tax', function () {
        var tax_category = 'sole-trader';
        var tax_type = $('#other_tax_type').val();
        var tax_code = $('#other_tax_code').val();
        var country = $('#country').val();

        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,'.otherDynamicSec',1,$(this).attr('data-id'));
    });

    $(document).on('click', '#update-gst-tax', function () {
        var tax_category = 'gst';
        var tax_type = $('#gst_tax_type').val();
        var tax_code = $('#gst_no').val();
        var country = $('#country').val();

        saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,'.gstDynamicSec',1,$(this).attr('data-id'));
    });

    // delete
    $(document).on('click','.delete-ft-pref',function(){
        var deleteId = $(this).attr('data-id');
        var varD = $(this).parent().parent();
        swal({
            title: "Are you sure you want to remove this Tax?",
            text: "This action cannot be undone.",
            type: "error",
            showCancelButton: true,
            cancelButtonClass: 'btn-secondary waves-effect',
            confirmButtonClass: 'btn-danger waves-effect waves-light',
            confirmButtonText: 'Yes,delete it!'
           }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: 'get',
                    url: public_url+'/financial-tool/settings-and-preferences/delete/'+deleteId,
                    success:function(response){
                        if (response.status == 'success'){
                            varD.remove();
                            swal('Deleted', response.msg,"success");
                            location.reload();
                        }else{
                            swal('Whoops!', response.msg, "error");
                        }
                    }
                });
            }
        });
    });

    /*================ clone start ======================*/
    $('.clone').click(function(){
        var ID = $(this).attr('data-id');
        var url = public_url+'/financial-tool/settings-and-preferences/clone/' + ID;
        $.ajax({
            type: 'post',
            url: url,
            success:function(response){
                if (response.status == 'success'){
                    swal({title:'Clone', text:response.msg, type:"success"},function() {
                        location.reload();
                    });
                }else{
                    swal('Whoops!', response.msg, "error");
                }
            }
        });
    })
    /*================ clone end ======================*/


    /*================ ajax calls ======================*/

    function editTaxPreferences(id,ModalId,Dynamic_class,DynamicDropDown) {
        $.ajax({
            type:'get',
            url:public_url+'/financial-tool/settings-and-preferences/edit/'+id,
            success:function(response){
                console.log(response);
                if (response.status == 'success'){
                    rendorDynamicModal(ModalId,response.data,Dynamic_class,DynamicDropDown);
                }else{
                    swal('Whoops!', response.msg, "error");
                }
            }
        });
    }

    function rendorDynamicModal(ModalId,data,Dynamic_class,DynamicDropDown){
        if (Dynamic_class == '.companyDynamicSec'){
            $('#company_tax_code').val(data.tax_code);
        }
        if (Dynamic_class == '.partnershipDynamicSec'){
            $('#partnership_tax_code').val(data.tax_code);
        }
        if (Dynamic_class == '.otherDynamicSec'){
            $('#other_tax_code').val(data.tax_code);
        }
        if (Dynamic_class == '.gstDynamicSec'){
            $('#gst_tax_code').val(data.tax_code);
        }
        if (data.tax_type == 'income'){
            var selectedModal = $(document).find('#'+data.tax_category+'-tax-modal');
            $(DynamicDropDown).selectpicker('val', 'income');

            $(document).find(Dynamic_class).html('');
            if (data.slabs.length > 0){
                for (var ti in data.slabs){
                    console.log('Id',data.slabs[ti]);
                    rendorIncomeTaxfields(ti,data.slabs[ti].from_amount,data.slabs[ti].to_amount,
                        data.slabs[ti].tax_percentages,Dynamic_class,data.slabs[ti].id);
                }
            }else{
                rendorIncomeTaxfields(0,'','','',Dynamic_class,0);
            }
        }
        if (data.tax_type == 'gst'){
            $(DynamicDropDown).selectpicker('val', 'gst');
            var gstNo = data.tax_code != null ? data.tax_code : '';
            var txAmount = data.tax_amount != null ? data.tax_amount : 0;
            var gstTaxHtml = '<div class="col-md-6"><div class="companyIncomeTax"><div class="form-group">' +
                                '<label class="strong" for="tax_type">GST Number</label>' +
                                '<input type="text" class="form-control tax_code" value="'+gstNo+'" id="gst_no" name="tax_code">' +
                            '</div></div></div>' +
                            '<div class="col-md-6"><div class="gstTax"><div class="form-group">' +
                            '<label class="strong" for="tax_amount">Tax %:</label>' +
                            '<input type="text" class="form-control tax_amount" value="'+txAmount+'" name="tax_amount">' +
                            '</div></div></div>';
            $(document).find(Dynamic_class).html(gstTaxHtml);
        }

        if (data.tax_type == 'other'){
            $(DynamicDropDown).selectpicker('val', 'other');
            var txAmount = data.tax_amount != null ? data.tax_amount : 0;
            var txName = data.tax_name != null ? data.tax_name : ' ';
            var otherTaxHtml = '<div class="col-md-6"><div class="otherTax"><div class="form-group">' +
                '<label class="strong" for="tax_name">Tax Name:</label>' +
                '<input type="text" class="form-control tax_name"  value="'+txName+'"  name="tax_name"></div></div></div>' +
                '<div class="col-md-6"><div class="gstTax"><div class="form-group">' +
                '<label class="strong" for="tax_amount">Tax Amount:</label>' +
                '<input type="text" class="form-control tax_amount"  value="'+txAmount+'" ' +
                'name="tax_amount"></div></div></div>';
            $(document).find(Dynamic_class).html(otherTaxHtml);
        }

        $(document).find(ModalId).modal("show");
    }

    function rendorIncomeTaxfields(index,from_amount,to_amount,tax_percentages,Dynamic_class,slabId){

        var incomeTaxHtml = '';

        if (index == 0){
            incomeTaxHtml = '<div class="row"><div class="col-md-12"><div class="Tax">' +
                '<div class="col-md-4"><div class="form-group"><label class="strong" for="tax_type">From</label>' +
                '</div></div><div class="col-md-4"><div class="form-group">' +
                '<label class="strong" for="tax_type">To</label></div></div><div class="col-md-4">' +
                '<div class="form-group col-md-6"><label class="strong" for="tax_type">%</label></div>' +
                '</div></div></div></div><div class="row incomeTax"> ' +
                '<div class="col-md-12"><div class="Tax"><div class="col-md-4">' +
                '<div class="form-group"> <input type="text" class="form-control cfrom_amount"' +
                ' value="'+from_amount+'" name="from_amount"></div> </div><div class="col-md-4"><div' +
                ' class="form-group"> <input type="text" class="form-control cto_amount"' +
                ' value="'+to_amount+'"  name="to_amount"></div></div> <div class="col-md-4">' +
                '<div class="form-group col-md-6"> <input type="text" class="form-control ctax_percentages"' +
                ' value="'+tax_percentages+'"  name="tax_percentages"></div>' +
                '<button type="button" class="btn btn-primary btn-o btn-sm p-y-0 pull-right' +
                ' incTaxBtn add_income_tax_field_btn" role="button" title="Add" data-id="'+slabId+'"><i class="fa' +
                ' fa-plus-circle"></i></button> </div> </div> </div> </div>';
        }else{
            incomeTaxHtml = '<div class="row incomeTax"> ' +
                '<div class="col-md-12"><div class="Tax"><div class="col-md-4">' +
                '<div class="form-group"> <input type="text" class="form-control cfrom_amount"' +
                ' value="'+from_amount+'" name="from_amount"></div> </div><div class="col-md-4"><div' +
                ' class="form-group"> <input type="text" class="form-control cto_amount"' +
                ' value="'+to_amount+'"  name="to_amount"></div></div> <div class="col-md-4">' +
                '<div class="form-group col-md-6"> <input type="text" class="form-control ctax_percentages"' +
                ' value="'+tax_percentages+'"  name="tax_percentages"></div>' +
                '<button type="button" class="btn btn-primary btn-o btn-sm p-y-0 pull-right' +
                ' remove_income_tax_field_btn  incTaxBtn" role="button" title="Remove"  data-id="'+slabId+'">' +
                '<i class="fa fa-minus-circle"></i></button></div> </div> </div> </div>';
        }
        $(document).find(Dynamic_class).append(incomeTaxHtml);
    }


    function saveOrUpdateTaxPreferences(tax_category,tax_type,tax_code,country,DynamicClass,isUpdate,updateId){

        var IncomeTax = new Array();
        var tax_amount = 0;
        var tax_name = '';
        var url = '';
        var financial_time_frame = $('.financial_time_frame :selected').val();
        if (isUpdate != 1){
            if (tax_type == 'income'){
                var elem  = $(document).find(DynamicClass).children('.incomeTax');
                $.each(elem, function( index, value ) {
                    IncomeTax[index] = [];
                    IncomeTax[index] = {'cfrom_amount': $(value).find('.cfrom_amount').val(),
                        'cto_amount':$(value).find('.cto_amount').val(),
                        'ctax_percentages':$(value).find('.ctax_percentages').val()};
                });
            }else{
                tax_amount = $(document).find(DynamicClass).find('.tax_amount').val();
                if (tax_type == 'other'){
                    tax_name = $(document).find(DynamicClass).find('.tax_name').val();
                }
            }
            url =public_url+'/financial-tool/settings-and-preferences/save';
            var msg = 'Saved';
        }else{
            if (tax_type == 'income'){
                var element  = $(document).find(DynamicClass).children('.incomeTax');
                $.each(element, function( index, value ) {
                    var slbId = $(value).find('.incTaxBtn').attr('data-id');
                    IncomeTax[index] = [];
                    IncomeTax[index] = {'cfrom_amount': $(value).find('.cfrom_amount').val(),
                        'cto_amount':$(value).find('.cto_amount').val(),
                        'ctax_percentages':$(value).find('.ctax_percentages').val(),
                        'slab_id':slbId
                    };
                });
            }else{
                tax_amount = $(document).find(DynamicClass).find('.tax_amount').val();
                if (tax_type == 'other'){
                    tax_name = $(document).find(DynamicClass).find('.tax_name').val();
                }
            }
            // return false;
            url =public_url+'/financial-tool/settings-and-preferences/update/'+updateId;
            var msg = 'Updated';
        }

        $.ajax({
            type: 'post',
            url: url,
            data:{
                tax_category:tax_category,
                tax_type:tax_type,
                tax_code:tax_code,
                tax_amount:tax_amount,
                tax_name: tax_name,
                country: country,
                income_tax:IncomeTax,
                financial_time_frame : financial_time_frame , 
            },

            success:function(response){
                if (response.status == 'success'){
                    swal({title:msg, text:response.msg, type:"success"},function() {
                        location.reload();
                    });
                }else{
                    swal('Whoops!', response.msg, "error");
                }
            }
        });
    }

    /*================ ajax calls end ======================*/

});