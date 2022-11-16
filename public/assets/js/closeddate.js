$(document).ready(function(){
	initCustomValidator();

    var hiddenId = $('input[name="closedDateId"]').val();
    if(!hiddenId){
        var currentDate = moment().format('D MMM YYYY');
        $('input[name="startDate"]').val(currentDate);
        $('input[name="endDate"]').val(currentDate);
    }
    
	$('.startDatepicker').datepicker({
        autoclose:true,
        dateFormat:"d M yy",
        format:"dd-mm-yyyy",
        minDate: 0,
        startDate: new Date()
    });

     $('.endDatepicker').datepicker({
        autoclose:true,
        dateFormat:"d M yy",
        format:"dd-mm-yyyy",
        minDate: 0,

    }); 

    $(".startDatepicker").change(function(){
		$('.endDatepicker').datepicker('destroy');
		$( '.endDatepicker' ).datepicker({
			minDate:$(this).val(),
            //maxDate:$(this).val(),
			dateFormat:"d M yy",
		});
	});

    /*$(".endDatepicker").change(function(){
        $('.startDatepicker').datepicker('destroy');
        $( '.startDatepicker' ).datepicker({ 
            minDate:0,
            maxDate:$(this).val(),
            dateFormat:"d M yy",
        });
    });*/

});



function validateStartEndDate(fields){
    var startDateField = fields.startDateField,
        endDateField = fields.endDateField,
        startDate = startDateField.val(),
        endDate = endDateField.val(),
        formGroup = endDateField.closest('.form-group');

    if( moment(startDate).isAfter(moment(endDate)) ){
        setFieldInvalid(formGroup, 'Please select a valid date.')
        return false;
    }
    else{
        setFieldValid(formGroup);
        return true;
    }
}

$(".closedatebtn").on( "click", function(e) {
	//alert("dsfds");
	e.preventDefault();   
    var formData = {},
        form = $('#closeddateform'),
        isFormValid = form.valid(),
        overlapErrElem = $('#overlapErr');

    overlapErrElem.addClass('hidden');

    if(!validateStartEndDate({startDateField:form.find("input[name='startDate']"), endDateField:form.find("input[name='endDate']")}))
        isFormValid = false;

	if(isFormValid){
		formData['startdate'] = moment($('input[name="startDate"]').val()).format("YYYY-MM-DD");
		formData['enddate'] = moment($('input[name="endDate"]').val()).format("YYYY-MM-DD");
		formData['description'] = $('#description').val();
        formData['closeddateid'] = $('input[name="closedDateId"]').val();

        if(!formData['closeddateid']){
            var ajaxType = 'POST',
                action = '';
        }
        else{
            var ajaxType = 'PATCH',
                action = '/'+formData['closeddateid'];
        }
        $.ajax({
            url : public_url+'settings/business/closeddate'+action,
            type : ajaxType,
            data : formData,
            success : function(response) {
               var data = JSON.parse(response);
               if(data.status == "updated"){
                saveSuccess(form, {action:'show', type:'update'});
                //window.location.href = public_url+'settings/business/closeddate';
                window.location.href = document.referrer;
                }
                else if(data.status == "added"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+'settings/business/closeddate';
                }
                else if(data.status == "error"){
                    $.each(data.errorData, function(){
                        $.each(this, function(errorType, message){                        
                            if(errorType == "overlappingDates"){
                                var errData = JSON.parse(message);

                                overlapErrElem.find('span.text').text(errData.kase);

                                var datesHtml = '';
                                $.each(errData.dates, function(key, val){
                                    datesHtml += '<li>'+val+'</li>'
                                });
                                overlapErrElem.find('ul').empty().append(datesHtml);

                                overlapErrElem.removeClass('hidden');
                            }
                        });                         
                    });
                }
            },

        });
        scrollToTop(form);	
	}
});