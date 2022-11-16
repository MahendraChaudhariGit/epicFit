var rowCreated = 0;
var rowLen=$('.rowCount').length;

var inputTime = $('input[name=startTime]').val();
$(document).ready(function(){
	   initCustomValidator();
    
	var elemVal=$('input[name=startTime]').val();
	moment(elemVal, 'HH:mm a').format('hh:mm A');
	$('input[name=startTime]').val(moment(elemVal, 'HH:mm a').format('hh:mm A'));
    // var timedata=elem.data('timevalue');
    
    // //setFieldForTimepicker(elem,timedata);
    // setDbTimeToTimeString(elem,timedata);
 
	$('body').on('click', '.remove-reason-row', function(e){
		e.preventDefault();
		$(this).closest('.row').remove();
		rowLen--;
		if(rowLen==0){
			var elem=$('#sucId');
			saveSuccess(elem,{action:'show',type:'cancelAllReason'});
	     }		
	}); 

	$('#status-row-add').click(function(e){
		e.preventDefault();
		rowCreated++;

		var statusRow=$(".status-row:eq(0)").clone();
		//var textBoxName = paymentRow.find('input[type="text"]').attr('name');
		statusRow.find('input[type="text"]').removeAttr('disabled').attr('value',"").attr('name','newStatus'+rowCreated);
		statusRow.find('.font-14').remove();
		statusRow.append("<div class='col-md-1 p-l-0'><a class='btn btn-xs btn-red remove-reason-row' href='#'><i class='fa fa-times fa fa-white'></i></a></div>");
		$(".status-row").closest('fieldset').append(statusRow);

	});
	//for texes field add random..............
	$('#reason-row-add').click(function(e){
		e.preventDefault();
		rowCreateReason++;
		rowLen++;
		var elem=$('#sucId');
        saveSuccess(elem, {action:'hide'});

		var reasonRow=$(".reason-row").clone();
		reasonRow.removeClass('hidden');
		reasonRow.removeClass('reason-row');
		reasonRow.find('input[type="text"]').attr('value',"").attr('name','reason'+rowCreateReason).attr('required','required');
		reasonRow.find('input[type="hidden"]').remove();
		$(".reason-row").closest('fieldset').append(reasonRow);
	});

	//for delete text field of payment...................	
	$('body').on('click', '.remove-payment-row', function(e){
		e.preventDefault();
		$(this).closest('.row').remove();
	}); 

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
$("#useCurrentTime").click(function() {   
    if($(this).is(":checked")) 
    {
	//   $('input[name=startTime]').prop('disabled', true);
		$('.timeLink').removeClass('newTimePicker');
    }
    else{
	//    $('input[name=startTime]').prop('disabled', false);
		$('.timeLink').addClass('newTimePicker');
		$('.newTimePicker').bootstrapMaterialDatePicker({
			date: false,
			shortTime: true,
			format: 'HH:mm:ss',
			currentDate: inputTime
		}).change(function(e, date) {
			var time = date.format("hh:mm A");
			var timeHH = date.format("HH:mm:ss");
			formGroup = $(e.target).closest('.eventTime');
			console.log(timeHH,$('input[name=startTime]'));
			$('input[name=startTime]').val(time);
			// StatsModal.find('.event-time-span3').text(time);
			// var wake_up =StatsModal.find("input[name='wake_up']").val(timeHH);				
		});	
    }
});



//form submit..
//$allcaledarVal->cs_start_time;
$(".submitcalendar").on( "click", function(e) {
	e.preventDefault();
	//console.log($('input[name=startTime]').val()); 
	 //$('#loading').attr("src",public_url+'assets/images/ajax-loding.gif'); 
	 //$('#loading').addClass('fa fa-spinner'); 
	var formData = {};
	var form = $('#caledarSettingForm');
	var isFormValid = form.valid();



if(isFormValid){
		$.each($(form).find(':input').serializeArray(), function(i, obj){
			formData[obj.name] = obj.value
		});
		var start_time=formData['startTime'];
		formData['startTime'] = timeStringToDbTime(start_time);
		
		/*$.post(public_url+'settings/business/calendar', formData, function(response){
            	var data = JSON.parse(response);
                if(data.status == "added"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+"settings/business/calendar"; //+data.discountid
                }
            });*/

		$.ajax({
                // url : public_url+'settings/business/calendar/'+formData['caledarSettingId'],
                url : public_url+'calendar/settings/'+formData['clientCaledarSettingId'],
                type : 'PATCH',
                data : formData,
                success : function(response) {
                
                   var data = JSON.parse(response);
                   if(data.status == "updated"){
                   	 //$('#loading').removeClass('fa fa-spinner').addClass('fa fa-check');
                   	// $('#loading').attr("src",public_url+'assets/images/ok-icon.png'); 
                    window.location =public_url+'new-dashboard';
                	}
                },

        });
}

});

$('.newTimePicker').bootstrapMaterialDatePicker({
	date: false,
	shortTime: true,
	format: 'HH:mm:ss',
	currentDate: inputTime
}).change(function(e, date) {
	var time = date.format("hh:mm A");
	var timeHH = date.format("HH:mm:ss");
	formGroup = $(e.target).closest('.eventTime');
	console.log(timeHH,$('input[name=startTime]'));
	$('input[name=startTime]').val(time);
	// StatsModal.find('.event-time-span3').text(time);
	// var wake_up =StatsModal.find("input[name='wake_up']").val(timeHH);				
});

