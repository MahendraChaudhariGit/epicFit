var rowCreated = 0;
var rowLen=$('.rowCount').length;

$(document).ready(function(){
	   initCustomValidator();
    
    var elem=$('input[name=startTime]');
    var timedata=elem.data('timevalue');
    console.log(timedata);
    //setFieldForTimepicker(elem,timedata);
    setDbTimeToTimeString(elem,timedata);
 
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
      $('input[name=startTime]').prop('disabled', true);
    }
    else{
       $('input[name=startTime]').prop('disabled', false);	
    }
});



//form submit..
//$allcaledarVal->cs_start_time;
$(".submitcalendar").on( "click", function(e) {
	e.preventDefault();
	var formData = {};
	var form = $('#caledarSettingForm');
	var salesProcessSettings = [];
	var totalNumberField = form.find('[name="teamNumb"]');
	totalNumberField.prop('required', false);
	var isBookTeamChecked = form.find('#saleStepBookTeam').is(':checked');
	if(isBookTeamChecked){
		totalNumberField.prop('required', true);
	}
	var totalNumberField = form.find('[name="indivNumb"]');
	totalNumberField.prop('required', false);
	var isBookIndivChecked = form.find('#saleStepBookIndiv').is(':checked');
	if(isBookIndivChecked){
		totalNumberField.prop('required', true);
	}
	var isFormValid = form.valid();
	if(isFormValid){
		$.each($(form).find(':input').serializeArray(), function(i, obj){
				formData[obj.name] = obj.value
		});
		formData['saleStepGen[]'] = [];
		formData['saleStepSession[]'] = [];
		if(!isBookIndivChecked){
			updateSalesSessionOrder('indiv', 0);
			$('#saleStep17').attr('checked', false);
		}

		if(!isBookTeamChecked){
			updateSalesSessionOrder('team', 0);
			$('#saleStep11').attr('checked', false);
		}
		saleStepGen = [];
		saleStepSession = [];
		$('[name="saleStepGen[]"]').each(function() {
			if($(this).is(':checked')){
				saleStepGen.push($(this).val());
			}
		});
		$('[name="saleStepSession[]"]').each(function() {
			if($(this).is(':checked')){
				saleStepSession.push($(this).val());
			}
		});
		formData['saleStepGen'] = saleStepGen;
		formData['saleStepSession'] = saleStepSession;
		var start_time=formData['startTime'];
		formData['startTime'] = timeStringToDbTime(start_time);
		$.ajax({
                url : public_url+'settings/business/calendar/'+formData['caledarSettingId'],
                type : 'PATCH',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "updated"){
                   	 //$('#loading').removeClass('fa fa-spinner').addClass('fa fa-check');
                   	// $('#loading').attr("src",public_url+'assets/images/ok-icon.png'); 
                    location.reload(true);
                	}
                },
        });
	}

});

/* Start: Sales Process Settings Modal */
$(document).ready(function() {
	var salesDiv=$('.sales-process-div');
	salesDiv.find('.sales_checkbox').attr('checked', false);
	var data = salesDiv.find('[name="salesProccesSettingsData"]').val();
	if(data){
		var dataArr = JSON.parse(data);
		$.each(dataArr.steps, function(key, value) {
	  		salesDiv.find('#saleStep'+value).attr('checked', true);
	  		salesDiv.find('#saleStep'+value).trigger('click');
		});
		
		var cb = salesDiv.find('#saleStepBookTeam');
		if(dataArr.teamCount != ""){
			cb.attr('checked', true);
			cb.trigger('click');
			var totalNumberField = salesDiv.find('[name="teamNumb"]');
			totalNumberField.val(dataArr.teamCount).selectpicker('refresh');
			setFieldNeutral(totalNumberField);
		}
		toggleSalesSession(cb);

		var cb = salesDiv.find('#saleStepBookIndiv');
		if(dataArr.indivCount != ""){
			cb.attr('checked', true);
			cb.trigger('click');
			var totalNumberField = salesDiv.find('[name="indivNumb"]');
			totalNumberField.val(dataArr.indivCount).selectpicker('refresh');
			setFieldNeutral(totalNumberField);
		}
		toggleSalesSession(cb);
	}	
});

$('#saleStep5').change(function(){
	if($(this).is(':checked')){
		$('#saleStep4').attr('checked', true);	
	}
})
$('#saleStep4').change(function(){
	if(!$(this).is(':checked')){
		$('#saleStep5').attr('checked', false);	
	}
})
$('input[name="saleStepSession[]"]').change(function(){
	toggleSalesSession($(this));
})
$('[name="teamNumb"]').change(function(){
	updateSalesSessionOrder('team', $(this).val());
})
$('[name="indivNumb"]').change(function(){
	updateSalesSessionOrder('indiv', $(this).val());
})

function toggleSalesSession(cb){
	var fieldsetId = cb.val(),
	fieldset = $('#'+fieldsetId),
	orderFieldSet = $('#sessionOrder');

	if(cb.is(':checked')){
		fieldset.show();
		if($('[name="saleStepSession[]"]').not(cb).is(':checked'))	
			orderFieldSet.show();
	}
	else{
		fieldset.hide()	
		orderFieldSet.hide();
	}
}

function updateSalesSessionOrder(type, amnt){
	var length = $('#sessionOrder li.'+type).length;
	if(!amnt)
		amnt = 0;
	else
		amnt = parseInt(amnt, 10)
	if(length < amnt){
		if(type == 'team')
			var stepName = 'Team';
		else if(type == 'indiv')
			var stepName = 'Individual';
		var li = '';
		for(i=length+1; i<=amnt; i++)
			li += '<li class="dd-item '+type+'" data-id="'+type+'-'+i+'"><div class="dd-handle">Book '+stepName+'</div></li>';

		$('#sessionOrder .dd-list').append(li);
	}
	else if(length > amnt){
		for(i=length; i>amnt; i--){
			$('#sessionOrder li[data-id="'+type+'-'+i+'"]').remove();
		}
	}
	
	UINestable.update($('#salesNestable').data('output', $('input[name="salesNestable"]')))
}

jQuery(document).ready(function() {
	UINestable.init();
});
/* End: Sales Process Settings Modal */

