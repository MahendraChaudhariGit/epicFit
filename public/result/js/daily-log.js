var public_url = $('meta[name="public_url"]').attr('content');
var StatsModal = $('#myModal1');
var nutritionalForm = $('#NutritionalJournal');
var captureImageModal = $('#captureImageModal');
var cameraModal = $('#cameraModal');
var hydrationJournal = $('#HydrationJournal');
$("#nutritionalFormModal").validate();
/**
 * Camera Js
 */
var constraints = { 
	audio: true,
	video: {
	  facingMode: 'environment'
	},
  };
Webcam.set({
    width: 320,
    height: 240,
    image_format: 'jpeg',
	jpeg_quality: 90,
	constraints: constraints,
});
$('body').on('click','.camera-btn',function(e) {
	// if($('#nutritionalFormModal').valid()){
		// Webcam.attach('#videoCamera' );
		start();
		cameraModal.modal('show');
	// }
});
$('#cameraModal').on('hide.bs.modal',function(e){
	// Webcam.reset();
	stream.getTracks().forEach(track => track.stop());
});
$('body').on('click','.captureImage',function(){
	var width = 320;
	height = videoElement.videoHeight / (videoElement.videoWidth/width);
	var canvas = document.getElementById('canvas');
	canvas.setAttribute('width', width);
	canvas.setAttribute('height', height);
	var context = canvas.getContext('2d');
	context.drawImage(videoElement, 0, 0, width, height);
	var data = canvas.toDataURL('image/jpeg');
	toggleWaitShield('show');
	$.post(public_url+'photo/capture-save',{data:data},function(response){
		toggleWaitShield('hide');
		nutritionalForm.find('#clickedPic').val(response);
		nutritionalForm.find('.meal_image img').attr('src',public_url+'uploads/'+response);
		nutritionalForm.find('#mealImageBox').show()
		$('#cameraModal').modal('hide');
	});
});

function fileSelectHandlerClick(elem){
	var fileInput = elem.files[0];
	var fileUrl = window.URL.createObjectURL(elem.files[0]);
	if($('#myModal1').hasClass('in'))
	{
		var activeForm = nutritionalForm;
	}else{
		var activeForm = $('#customMealplanmodal');
	}
	activeForm.find(".meal_image img").attr("src", fileUrl);
	activeForm.find('#mealImageBox').show();
	var oFile = elem.files[0];
	var form_data = new FormData();                  
	form_data.append('fileToUpload', oFile);
	toggleWaitShield('show');
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': $('meta[name=_token]').attr('content')
		}
	});
	$.ajax({
		url: public_url+'photo/capture-save', // point to server-side PHP script
		data: form_data,
		dataType: 'text', 
		type: 'POST',
		contentType: false, // The content type used when sending data to the server.
		cache: false, // To unable request pages to be cached
		processData: false,
		success: function(data) {
			toggleWaitShield('hide');
			var file = document.querySelector('.chooseFileBtn');
     		file.value = '';
     		
			activeForm.find("#clickedPic").val(data);
		}
	});
}


$('body').on('click','.saveRateForm',function(){
	var mealRate = captureImageModal.find('input[name="meal_rate"]:checked').data('name');
	var portionSize = captureImageModal.find('input[name="portionsize"]:checked').data('name');
	nutritionalForm.find('.sizePortion').text(portionSize);
	nutritionalForm.find('.sizePortion').data('portion-size',captureImageModal.find('input[name="portionsize"]:checked').val());
	nutritionalForm.find('.mealrating').text(mealRate);
	nutritionalForm.find('.mealrating').data('meal-rate',captureImageModal.find('input[name="meal_rate"]:checked').val())
	$('.mealRateBox').show();
	captureImageModal.modal('hide');
});

$('body').on('click','.addMoreRow',function(){
	var html = '<div class="form-group">\
		<div class="row">\
			<div class="col-md-8">\
				<label class="strong">Ingredient</label>\
				<input type="text" class="form-control" name="ingredients" required>\
			</div>\
			<div class="col-md-2">\
				<label class="strong">Quantity </label>\
				<input type="text" name="quantity" class="form-control" required>\
			</div>\
			<div class="col-md-2">\
				<button type="button" class="btn btn-primary crossbtn"><i class="fa fa-times" aria-hidden="true"></i></button>\
			</div>\
		</div>\
	</div>';
	nutritionalForm.find('.row-box').append(html);
});
$('body').on('click','.crossbtn',function(){
	$(this).closest('.row').remove()
})
$('body').on('click','.saveNutritionalFormModal',function(){
	if($('#nutritionalFormModal').valid()){
		var formData = {};

		formData['is_custom'] = 2;
		formData['eventDate'] = StatsModal.find('input[name="eventDate"]').val();
		formData['cat_id'] = nutritionalForm.find('#catId').val();
		nutritionalForm.find('#catId option:selected');
		formData['isSnack'] = nutritionalForm.find('#catId option:selected').data('is-snack');
		if(formData['isSnack']){
			formData['snackType'] = nutritionalForm.find('#catId option:selected').data('snack-type');
		}
		formData['time_opt'] = nutritionalForm.find('#time_opt').val();
		formData['nutritionalTime'] = nutritionalForm.find('#automaticTime').val();
		formData['hungerRate'] = nutritionalForm.find('input[name="hunger_rate"]').val();
		formData['recipeName'] = nutritionalForm.find('input[name="recipe_name"]').val();
		var ingQuantityData = [];
		nutritionalForm.find('.row-box .form-group').each(function(){
			ingQuantityData.push({ingredient:$(this).find('input[name="ingredients"]').val(),quantity:$(this).find('input[name="quantity"]').val()});
		});
		formData['ingQuantityData'] = ingQuantityData;
		formData['servingSize'] = nutritionalForm.find('select[name="serving_size"]').val();
		formData['activityLabel'] = nutritionalForm.find('textarea[name="activity_label"]').val();
		formData['generalNotes'] = nutritionalForm.find('textarea[name="general_notes"]').val();
		formData['clickedImage'] = nutritionalForm.find('#clickedPic').val();
		formData['mealRating'] = nutritionalForm.find('select[name="meal_rating"]').val();
		formData['mealEnjoyed'] = nutritionalForm.find('select[name="enjoyed_meal"]').val();
		toggleWaitShield('show');
		
		$.post(public_url+'/store-nutritional-data',formData,function(response){
			toggleWaitShield('hide');
			if(response.status == 'ok'){
				swal({
					type: 'success',
					title: 'Success!',
					showCancelButton: false,
					allowOutsideClick: false,
					text: 'Data saved successfully',
					showConfirmButton: true,     
				}, 
				function(isConfirm){
					if(isConfirm)
						StatsModal.modal('hide');
				});
			}else{
				swal({
					type: 'error',
					title: 'Error!',
					showCancelButton: false,
					allowOutsideClick: false,
					text: response.message,
					showConfirmButton: true,     
				}, 
				function(isConfirm){
					if(!isConfirm)
						StatsModal.modal('hide');
				});
			}
		},'json')
	}
});


/**
 * Personal Statistics Js Start Here
 */
$('body').on('click','.open-modalp1',function(){
	resetStatisticModal();
	resetNutritionalForm();
    $('.time_opt').each(function(){
        if($(this).hasClass('active')){
            var timeOpt = $(this).data('time-opt');
            $('#time_opt').val(timeOpt);
        }
    });
	var eventDate = $(this).parent('.fc-day').data('date');
	if(eventDate == undefined){
		var eventDate = $(this).parent('.fc-day-header').data('date');
		if(eventDate == undefined){
			var eventDate = $(this).parents('.fc-view').find('.fc-day-header').data('date');
			if(eventDate == undefined){
				var eventDate = moment().format('YYYY-MM-DD');
			}
		}
	}
	StatsModal.find('input[name="eventDate"]').val(eventDate);
	var formData = {}
	formData['eventDate'] = eventDate;
	toggleWaitShield('show');
	$.get(public_url+'get-statistics-data',formData,function(response){
		toggleWaitShield('hide');
		populateStatisticsData(response);
	},'json');
});
$('body').on('click','.saveStats',function(){
	var formData = {};
	formData['event_date'] = StatsModal.find('input[name="eventDate"]').val();
	formData['diaryData'] = {};
	formData['diaryData']['content'] = StatsModal.find('#diaryContent').val();
	formData['diaryData']['stress_rate'] = StatsModal.find('input[name="stress_rate"]').val();
    formData['diaryData']['humidity'] = StatsModal.find('input[name="humidity"]').val();
    formData['diaryData']['temp'] = StatsModal.find('#temperatureEdit').val();
    formData['measurementsData'] = {};
	formData['measurementsData']['height'] = StatsModal.find('#height_m').val();
	formData['measurementsData']['chest'] = StatsModal.find('#chest').val();
	formData['measurementsData']['neck'] = StatsModal.find('#neck').val();
	formData['measurementsData']['bicep_r'] = StatsModal.find('#bicepR').val();
	formData['measurementsData']['bicep_l'] = StatsModal.find('#bicepL').val();
	formData['measurementsData']['forearm_r'] = StatsModal.find('#forearmR').val();
	formData['measurementsData']['forearm_l'] = StatsModal.find('#forearmL').val();
	formData['measurementsData']['waist'] = StatsModal.find('#waist').val();
	formData['measurementsData']['hip'] = StatsModal.find('#hip').val();
	formData['measurementsData']['thigh_r'] = StatsModal.find('#thighR').val();
	formData['measurementsData']['thigh_l'] = StatsModal.find('#thighL').val();
	formData['measurementsData']['calf_r'] = StatsModal.find('#calfR').val();
	formData['measurementsData']['calf_l'] = StatsModal.find('#calfL').val();
	formData['measurementsData']['weight'] = StatsModal.find('#weight_m').val();
	formData['measurementsData']['weightUnit'] = StatsModal.find('input[name="weightUnit"]').val();
	formData['measurementsData']['heightUnit'] = StatsModal.find('input[name="heightUnit"]').val();
	formData['statisticsData'] = {};
	formData['statisticsData']['bfp_kg'] = StatsModal.find('#bfp_kg').val();
	formData['statisticsData']['smm_kg'] = StatsModal.find('#smm_kg').val();
	formData['statisticsData']['bmr_kg'] = StatsModal.find('#bmr_kg').val();
	formData['statisticsData']['bmi_kg'] = StatsModal.find('#bmi_kg').val();
	formData['statisticsData']['sleep_kg'] = StatsModal.find('#sleep_kg').val();
	formData['statisticsData']['h_w_ratio'] = StatsModal.find('#h_w_ratio').val();
	formData['statisticsData']['vis_eat_kg'] = StatsModal.find('#vis_eat_kg').val();
	formData['statisticsData']['pulsed_kg'] = StatsModal.find('#pulsed_kg').val();
	formData['statisticsData']['bp_mm'] = StatsModal.find('#bp_mm').val();
	formData['statisticsData']['bp_hg'] = StatsModal.find('#bp_hg').val();
	formData['statisticsData']['extra_input'] = StatsModal.find('#extra_input').val();

	toggleWaitShield('show');
	$.post(public_url+'store-statistics-data',formData,function(response){
		toggleWaitShield('hide');
		if(response.status == 'ok'){
			swal({
				type: 'success',
				title: 'Success!',
				showCancelButton: false,
				allowOutsideClick: false,
				text: response.message,
				showConfirmButton: true,     
			}, 
			function(isConfirm){
				if(isConfirm)
					StatsModal.modal('hide');
			});
		}else{
			swal({
				type: 'error',
				title: 'Error!',
				showCancelButton: false,
				allowOutsideClick: false,
				text: response.message,
				showConfirmButton: true,     
			}, 
			function(isConfirm){
				if(!isConfirm)
					StatsModal.modal('hide');
			});
		}
	},'json');
});

function resetStatisticModal(){
	StatsModal.find('a[href="#PersonalDiary"]').click();
	StatsModal.find('#diaryContent').val('');
	// $("#starBox").load(location.href + " #starBox");
	StatsModal.find('.stress_rate').val('1');
	StatsModal.find('.humidity').val('1');  
	StatsModal.find('#temperatureEdit').val('');
    StatsModal.find('#chest').val('');
	StatsModal.find('#neck').val('');
	StatsModal.find('#bicepR').val('');
	StatsModal.find('#bicepL').val('');
	StatsModal.find('#forearmR').val('');
	StatsModal.find('#forearmL').val('');
	StatsModal.find('#waist').val('');
	StatsModal.find('#hip').val('');
	StatsModal.find('#thighR').val('');
	StatsModal.find('#thighL').val('');
	StatsModal.find('#calfR').val('');
	StatsModal.find('#calfL').val('');
	StatsModal.find('#weight_m').val('');
	StatsModal.find('#height_m').val('');
	StatsModal.find('#bfp_kg').val('');
	StatsModal.find('#smm_kg').val('');
	StatsModal.find('#bmr_kg').val('');
	StatsModal.find('#bmi_kg').val('');
	StatsModal.find('#sleep_kg').val('');
	StatsModal.find('#bem_kg').val('');
	StatsModal.find('#h_w_ratio').val('');
	StatsModal.find('#vis_eat_kg').val('');
	StatsModal.find('#pulsed_kg').val('');
	StatsModal.find('#bp_mm').val('');
	StatsModal.find('#bp_hg').val('');
	StatsModal.find('#extra_input').val('');
	/******Reset Hydration Form **********/
	StatsModal.find('input[name="liquidtype"]').each(function(){
		$(this).attr('checked',false);
	});
	StatsModal.find('.range-slider_range').val('0');
	$('.range-slider_value').text( '0' );
	hydrationJournal.find('#Hydr_journal').val('');
	/****** Reset Sleep Form ********/
	StatsModal.find('.go_to_bed').val(moment('22:15:00', 'HH:mm a').format('HH:mm A'));
	StatsModal.find('.event-time-span1').text(moment('22:15:00', 'HH:mm a').format('hh:mm A'));
	StatsModal.find('.go_to_sleep').val(moment('23:15:00', 'HH:mm a').format('HH:mm A'));
	StatsModal.find('.event-time-span2').text(moment('23:15:00', 'HH:mm a').format('hh:mm A'));
	StatsModal.find('.wake_up').val(moment('06:15:00', 'HH:mm a').format('HH:mm A'));
	StatsModal.find('.event-time-span3').text(moment('06:15:00', 'HH:mm a').format('hh:mm A'));
	StatsModal.find('.morning_woke_up').val('50');
	StatsModal.find('.end_of_day').val('50');
	StatsModal.find('.general_notes').val('');

}

function resetNutritionalForm(){

	nutritionalForm.find('#catId').val();
	nutritionalForm.find('#catId').selectpicker('refresh');
	nutritionalForm.find('.time_opt').each(function(){
		if($(this).data('time-opt') == 'automatic'){
			nutritionalForm.find('.time_opt').trigger('click');
		}else{
			$(this).removeClass('active');
		}
	});
	nutritionalForm.find('.add_time_manual').hide();
	nutritionalForm.find('#time_hour').val('');
	nutritionalForm.find('#time_hour').selectpicker('refresh');
	nutritionalForm.find('#time_min').val('');
	nutritionalForm.find('#time_min').selectpicker('refresh');
	nutritionalForm.find('input[name="hunger_rate"]').val('1');
	$('#hungerSection').find('.hunger-range-value').text('1');
	nutritionalForm.find('input[name="recipe_name"]').val('');
	nutritionalForm.find('textarea[name="ingredients"]').val('');
	nutritionalForm.find('input[name="quantity"]').val('');
	nutritionalForm.find('textarea[name="serving_size"]').val('');
	nutritionalForm.find('textarea[name="activity_label"]').val('');
	nutritionalForm.find('textarea[name="general_notes"]').val('');
	nutritionalForm.find('.meal_image img').attr('src','');
	nutritionalForm.find('.mealrating').text('');
	nutritionalForm.find('.sizePortion').text('');
	nutritionalForm.find('.mealRateBox').hide();	
	nutritionalForm.find('#clickedPic').val('');
	nutritionalForm.find('.mealrating').data('meal-rate','');
	nutritionalForm.find('.sizePortion').data('portion-size','');
}

function populateStatisticsData(data){
	StatsModal.find('#diaryContent').val(data.diaryData.content);
	// StatsModal.find('#stress_rate input').each(function(){
	// 	if($(this).val() == data.diaryData.stress_rate){
	// 		$(this).attr('checked',true);
	// 	}
	// });
	if(data.diaryData.stress_rate == undefined || data.diaryData.stress_rate == '')
	{
	StatsModal.find('.stress_rate').val('1');
	StatsModal.find('.stress-range-value').text('1');
	}
    else
    {
	StatsModal.find('.stress_rate').val(data.diaryData.stress_rate);
	StatsModal.find('.stress-range-value').text(data.diaryData.stress_rate);
    }

    if(data.diaryData.humidity == undefined || data.diaryData.humidity == '')
    {
    StatsModal.find('.humidity').val('1');
	StatsModal.find('.humidity-range-value').text('1');
    }
    else
    {
    StatsModal.find('.humidity').val(data.diaryData.humidity);
	StatsModal.find('.humidity-range-value').text(data.diaryData.humidity);
   
    }

    StatsModal.find('#temperatureEdit').val(data.diaryData.temperature);
    StatsModal.find('#temperatureEdit').selectpicker('refresh');
	var measurementsData = data.measurementData;
	if(!jQuery.isEmptyObject(measurementsData)){
		StatsModal.find('#height_m').val(measurementsData.height);
		StatsModal.find('input[name="heightUnit"]').val(measurementsData.heightUnit)
		StatsModal.find('#chest').val(measurementsData.chest);
		StatsModal.find('#neck').val(measurementsData.neck);
		StatsModal.find('#bicepR').val(measurementsData.bicep_r);
		StatsModal.find('#bicepL').val(measurementsData.bicep_l);
		StatsModal.find('#forearmR').val(measurementsData.forearm_r);
		StatsModal.find('#forearmL').val(measurementsData.forearm_l);
		StatsModal.find('#waist').val(measurementsData.waist);
		StatsModal.find('#hip').val(measurementsData.hip);
		StatsModal.find('#thighR').val(measurementsData.thigh_r);
		StatsModal.find('#thighL').val(measurementsData.thigh_l);
		StatsModal.find('#calfR').val(measurementsData.calf_r);
		StatsModal.find('#calfL').val(measurementsData.calf_l);
		StatsModal.find('#weight_m').val(measurementsData.weight);
		StatsModal.find('input[name="weightUnit"]').val(measurementsData.weightUnit)
		if(measurementsData.weightUnit == 'Imperial')
		{
		 $('.kg_show').hide();
         $('.pound_show').removeClass('hidden');
         $("#convertP").hide();
         $("#conKg").removeClass('hidden');
		}
		if(measurementsData.heightUnit == 'inches')
		{
		 $('.cm_show').hide();
         $('.inches_show').removeClass('hidden');
         $("#convert-inches").hide();
         $("#convert-cm").removeClass('hidden');
		}
	}
	var statisticsData = data.statisticsData;
	if(!jQuery.isEmptyObject(statisticsData)){
		StatsModal.find('#bfp_kg').val(statisticsData.bfp_kg);
		StatsModal.find('#smm_kg').val(statisticsData.smm_kg);
		StatsModal.find('#bmr_kg').val(statisticsData.bmr_kg);
		StatsModal.find('#bmi_kg').val(statisticsData.bmi_kg);
		StatsModal.find('#sleep_kg').val(statisticsData.sleep_kg);
		StatsModal.find('#bem_kg').val(statisticsData.bem_kg);
		StatsModal.find('#h_w_ratio').val(statisticsData.h_w_ratio);
		StatsModal.find('#vis_eat_kg').val(statisticsData.vis_eat_kg);
		StatsModal.find('#pulsed_kg').val(statisticsData.pulsed_kg);
		StatsModal.find('#bp_mm').val(statisticsData.bp_mm);
		StatsModal.find('#bp_hg').val(statisticsData.bp_hg);
		StatsModal.find('#extra_input').val(statisticsData.extra_input);
	}
	var sleepData = data.sleepData;
	if(!jQuery.isEmptyObject(sleepData)){
		StatsModal.find('.go_to_bed').val(sleepData.go_to_bed);
		StatsModal.find('.event-time-span1').text(moment(sleepData.go_to_bed, 'HH:mm a').format('hh:mm A'));
		StatsModal.find('.go_to_sleep').val(sleepData.go_to_sleep);
		StatsModal.find('.event-time-span2').text(moment(sleepData.go_to_sleep, 'HH:mm a').format('hh:mm A'));
		StatsModal.find('.wake_up').val(sleepData.wake_up);
		StatsModal.find('.event-time-span3').text(moment(sleepData.wake_up, 'HH:mm a').format('hh:mm A'));
		StatsModal.find('.morning_woke_up').val(sleepData.morning_woke_up);
		StatsModal.find('.end_of_day').val(sleepData.end_of_day);
		StatsModal.find('.general_notes').val(sleepData.general_notes);
	}
	var hydrationJournalData = data.hydrationJournalData;
	if(!jQuery.isEmptyObject(hydrationJournalData)){
		StatsModal.find('input[name="required"]').val(hydrationJournalData.required_amount);
		StatsModal.find('.requiredDrinkVolume').text(hydrationJournalData.required_amount+' L');
		StatsModal.find('input[name="consumed"]').val(hydrationJournalData.consumed);
		StatsModal.find('input[name="id"]').val(hydrationJournalData.id);

		StatsModal.find('.consumedDrink').text((hydrationJournalData.consumed/1000).toFixed(1)+' L');

		var consumedHtml = "";
		$.each(hydrationJournalData.consumedHistory,function(key,value){

			consumedHtml += "<span class='non-edit'>"+value.liquidType+"-"+moment(value.time, 'HH:mm a').format('hh:mm A')+" - "+value.volume+"ml<i class='fa fa-pencil editdata' data-id='"+value.id+"' data-time='"+value.time+"'></i></span>";
		});

		StatsModal.find('.consume-history').empty();
		StatsModal.find('.consume-history').append(consumedHtml);
		var consumedPer = (hydrationJournalData.consumed/(hydrationJournalData.required_amount * 1000)) * 100;
		consumedPer = consumedPer.toFixed(2);
		StatsModal.find('.consumed-per').text(consumedPer+"%");
		$("#bottle").css({
			height: function( index, value ) {
				return consumedPer+"%";
			}
		});
	}
	StatsModal.find('#fetchWeight').val(data.weight);
}


$(document).on('input', '.range-slider_range', function() {
	$('.range-slider_value').text( $(this).val() );
	var consumed = StatsModal.find('input[name="consumed"]').val();
	var updatedConsumed = parseFloat(consumed) + parseFloat($(this).val());
	StatsModal.find('.consumedDrink').text((updatedConsumed/1000).toFixed(1)+' L');
	var requiredVoluume = StatsModal.find('input[name="required"]').val();
	var consumedPer = (updatedConsumed/(requiredVoluume * 1000)) * 100;
	consumedPer = consumedPer.toFixed(2);
	StatsModal.find('.consumed-per').text(consumedPer+"%");
	$("#bottle").css({
		height: function( index, value ) {
			return consumedPer+"%";
		}
	});

});

$(document).on('input', '.hunger_rate', function() {
	var count = $(this).val();
	$('#hungerSection').find('.hunger-range-value').text(count);
});

$('.go_to_bed').bootstrapMaterialDatePicker({
	date: false,
	shortTime: true,
	format: 'HH:mm:ss',
	currentDate: '10:15 PM'
}).change(function(e, date) {
	var time = date.format("hh:mm A");
	var timeHH = date.format("HH:mm:ss");
	formGroup = $(e.target).closest('.eventTime');
	StatsModal.find('.event-time-span1').text(time);
	var	go_to_bed = StatsModal.find("input[name='go_to_bed']").val(timeHH);			
});

$('.go_to_sleep').bootstrapMaterialDatePicker({
	date: false,
	shortTime: true,
	format: 'HH:mm:ss',
	currentDate: '11:15 PM'
}).change(function(e, date) {
	var time = date.format("hh:mm A");
	var timeHH = date.format("HH:mm:ss");
	formGroup = $(e.target).closest('.eventTime');
	StatsModal.find('.event-time-span2').text(time);
	var	go_to_sleep = StatsModal.find("input[name='go_to_sleep']").val(timeHH);				
});
		
$('.wake_up').bootstrapMaterialDatePicker({
	date: false,
	shortTime: true,
	format: 'HH:mm:ss',
	currentDate: '06:15 AM'
}).change(function(e, date) {
	var time = date.format("hh:mm A");
	var timeHH = date.format("HH:mm:ss");
	formGroup = $(e.target).closest('.eventTime');
	StatsModal.find('.event-time-span3').text(time);
	var wake_up =StatsModal.find("input[name='wake_up']").val(timeHH);				
});

$('.nutritionDatetimePicker').bootstrapMaterialDatePicker({
	date: false,
	shortTime: true,
	format: 'HH:mm:ss',
	currentDate: '09:00 AM'
}).change(function(e, date) {
	var time = date.format("hh:mm A");
	var timeHH = date.format("HH:mm:ss");
	StatsModal.find('.nutri-time-span').text(time);
	StatsModal.find('.nutri-time-span').data('val',timeHH);
	StatsModal.find("#automaticTime").val(timeHH);			
});
	
$('body').on('click','.saveSleep',function(){
	var formData = {};
	formData['event_date'] = StatsModal.find('input[name="eventDate"]').val();
	formData['go_to_bed'] = $('#SleepJournal').find('input[name="go_to_bed"]').val();
	formData['go_to_sleep'] =$('#SleepJournal').find('input[name="go_to_sleep"]').val();
	formData['wake_up'] = $('#SleepJournal').find('input[name="wake_up"]').val();
	formData['morning_woke_up'] =$('#SleepJournal').find('.morning_woke_up').val();
	formData['end_of_day'] =$('#SleepJournal').find('.end_of_day').val();
	formData['general_notes'] = $('#SleepJournal').find('.general_notes').val();
	toggleWaitShield('show');
	// console.log(formData);
	$.post(public_url+'store-sleep-data',formData,function(response){
		toggleWaitShield('hide');
		if(response.status == 'ok'){
			swal({
				type: 'success',
				title: 'Success!',
				showCancelButton: false,
				allowOutsideClick: false,
				text: response.message,
				showConfirmButton: true,     
			}, 
			function(isConfirm){
				if(isConfirm)
					StatsModal.modal('hide');
			});
		}else{
			swal({
				type: 'error',
				title: 'Error!',
				showCancelButton: false,
				allowOutsideClick: false,
				text: response.message,
				showConfirmButton: true,     
			}, 
			function(isConfirm){
				if(!isConfirm)
					StatsModal.modal('hide');
			});
		}
	},'json');
});
/**
 * Nutritional Journal Js
 */
$('body').on('click', '#customMealplanmodal .time_opt',function(){
	
    var timeOpt = $(this).data('time-opt');
    if(timeOpt == 'automatic'){
        var dt = new Date();
        var time = dt.getHours() + ":" + dt.getMinutes();
        $('#customMealplanmodal #automaticTime').val(time);
    }
    $('#customMealplanmodal #time_opt').val(timeOpt);
});
$('body').on('click', '#myModal1 .time_opt',function(){
    	

    var timeOpt = $(this).data('time-opt');
    if(timeOpt == 'automatic'){
        var dt = new Date();
        var time = dt.getHours() + ":" + dt.getMinutes();
        $('#myModal1 #automaticTime').val(time);
    }else{
		var time = StatsModal.find('.nutri-time-span').data('val');
		$('#myModal1 #automaticTime').val(time);
	}
    $('#myModal1 #time_opt').val(timeOpt);
});

// $("#plus").on( "click", function(){
//   $("#bottle").css({
//     height: function( index, value ) {
// 	return parseFloat( value ) * 1.4;
// 	}
// 	});
// });


// $("#minus").on( "click", function(){
//   $("#bottle").css({
//     height: function( index, value ) {
// 	return parseFloat( value ) / 1.4;
// 	}
// 	});
// });


$('body').on('click','.saveHydration',function(){
	var formData = {};
	formData['event_date'] = StatsModal.find('input[name="eventDate"]').val();
	formData['liquidType'] = hydrationJournal.find('input[name="liquidtype"]:checked').val();
	formData['hydrationText'] = hydrationJournal.find('#Hydr_journal').val();
	formData['drank'] = hydrationJournal.find('.range-slider_range').val();
	formData['time'] = moment().format('HH:mm:ss');
	if(formData['liquidType'] != '' && formData['liquidType'] != undefined){
		toggleWaitShield('show');
		$.post(public_url+"store-hydration-data",formData,function(response){
			toggleWaitShield('hide');
			if(response.status == 'ok'){
				swal({
					type: 'success',
					title: 'Success!',
					showCancelButton: false,
					allowOutsideClick: false,
					text: 'Data saved successfully',
					showConfirmButton: true,     
				}, 
				function(isConfirm){
					if(isConfirm)
						StatsModal.modal('hide');
				});
			}else{
				swal({
					type: 'error',
					title: 'Error!',
					showCancelButton: false,
					allowOutsideClick: false,
					text: response.message,
					showConfirmButton: true,     
				}, 
				function(isConfirm){
					if(!isConfirm)
						StatsModal.modal('hide');
				});
			}
		});
	}else{
		swal({
			type: 'error',
			title: 'Error!',
			showCancelButton: false,
			allowOutsideClick: false,
			text: "Please Select drink type",
			showConfirmButton: true,     
		}, 
		function(isConfirm){
			if(!isConfirm)
				swal.close();
		});
	}
});
$('#checkweight').click(function()
{	
	var weight = $('#myModal1').find('#fetchWeight').val();
	if(weight == 0)
	{
		$('#staticBackdrop').modal('show');
	}
});

$('.saveWeight').click(function()
{ 	var formData = {};
     
	formData['weight']  = $('#weight_save').val();
	formData['weightUnit']  = $('input[name="weightUnit"]').val();
	formData['event_date'] = StatsModal.find('input[name="eventDate"]').val();
	toggleWaitShield('show');
	$.post(public_url+'store-weight-data',formData,function(response){
		toggleWaitShield('hide');
		if(response.status == 'ok'){
			$('#staticBackdrop').modal('hide');
			swal({
				type: 'success',
				title: 'Success!',
				showCancelButton: false,
				allowOutsideClick: false,
				text: 'Data saved successfully',
				showConfirmButton: true,     
			}, 
			function(isConfirm){
				if(isConfirm){
					resetStatisticModal();
					formData['eventDate'] = StatsModal.find('input[name="eventDate"]').val();
					$.get(public_url+'get-statistics-data',formData,function(response){
						populateStatisticsData(response);
					},'json');
				}
			});
		}else{
			swal({
				type: 'error',
				title: 'Error!',
				showCancelButton: false,
				allowOutsideClick: false,
				text: response.message,
				showConfirmButton: true,     
			}, 
			function(isConfirm){
				if(!isConfirm)
					StatsModal.modal('hide');
			});
		}
	});

});

// stress section start
$('body').on('click', '#stressMinus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count;
    $input.val(count);
    $input.trigger('input');
    $('#stressSection').find('.stress-range-value').text(count);
    return false;
});
$('body').on('click', '#stressplus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) + 1;
    count = count > 10 ? 10 : count;
    $input.val(count);
    $input.trigger('input');
    $('#stressSection').find('.stress-range-value').text(count);

    return false;
});

// humidity section start
$('body').on('click', '#humidityMinus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count;
    $input.val(count);
    $input.trigger('input');
    $('#humiditySection').find('.humidity-range-value').text(count);
    return false;
});
$('body').on('click', '#humidityplus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) + 1;
    count = count > 10 ? 10 : count;
    $input.val(count);
    $input.trigger('input');
    $('#humiditySection').find('.humidity-range-value').text(count);

    return false;
});

// hunger section start
$('body').on('click', '#hungerMinus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count;
    $input.val(count);
    $input.trigger('input');
    $('#hungerSection').find('.hunger-range-value').text(count);
    return false;
});
$('body').on('click', '#hungerplus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) + 1;
    count = count > 10 ? 10 : count;
    $input.val(count);
    $input.trigger('input');
    $('#hungerSection').find('.hunger-range-value').text(count);

    return false;
});

// feel when you woke up section start
$('body').on('click', '#wokeupMinus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 50;
    count = count < 0 ? 0 : count;
    $input.val(count);
	$input.trigger('input');
	var text = "";
	if(count == 0)
		text = "Tired";
	else if(count == 50)
		text = "Mediocre";
	else if(count == 100)
		text = "Awake";
    $('#wokeUp').find('.wokeup-range-value').text(text);
});
$('body').on('click', '#wokeupplus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) + 50;
    count = count > 100 ? 100 : count;
    $input.val(count);
	$input.trigger('input');
	var text = "";
	if(count == 0)
		text = "Tired";
	else if(count == 50)
		text = "Mediocre";
	else if(count == 100)
		text = "Awake";
    $('#wokeUp').find('.wokeup-range-value').text(text);

    return false;
});
$(document).on('input', '.morning_woke_up', function() {
     var count = $(this).val();
    $('#wokeUp').find('.wokeup-range-value').text(count);

});
// end of the day section start
$('body').on('click', '#endOfdayMinus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 50;
    count = count < 0 ? 0 : count;
    $input.val(count);
	$input.trigger('input');
	var text = "";
	if(count == 0)
		text = "Tired";
	else if(count == 50)
		text = "Mediocre";
	else if(count == 100)
		text = "Awake";
    $('#endOfday').find('.endOfday-range-value').text(text);
    return false;
});
$('body').on('click', '#endOfdayplus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) + 50;
    count = count > 100 ? 100 : count;
    $input.val(count);
	$input.trigger('input');
	var text = "";
	if(count == 0)
		text = "Tired";
	else if(count == 50)
		text = "Mediocre";
	else if(count == 100)
		text = "Awake";
    $('#endOfday').find('.endOfday-range-value').text(text);

    return false;
});
$(document).on('input', '.end_of_day', function() {
     var count = $(this).val();
    $('#endOfday').find('.endOfday-range-value').text(count);

});

$(document).on('input', '.stress_rate', function() {
     var count = $(this).val();
    $('#stressSection').find('.stress-range-value').text(count);

});
$(document).on('input', '.humidity', function() {
     var count = $(this).val();
    $('#humiditySection').find('.humidity-range-value').text(count);

});

$('#customMealplanmodal').on('show.bs.modal', function (e) {
	$('#customMealplanmodal').find('#catId').val();
	$('#customMealplanmodal').find('#catId').selectpicker('refresh');
	$('#customMealplanmodal').find('.time_opt').each(function(){
		if($(this).data('time-opt') == 'manual'){
			$(this).addClass('active');
			$('#customMealplanmodal').find('#time_opt').val('manual');
		}else{
			$(this).removeClass('active');
		}
	});
	$('#customMealplanmodal').find('.add_time_manual').show();
	$('#customMealplanmodal').find('#time_hour').val('');
	$('#customMealplanmodal').find('#time_hour').selectpicker('refresh');
	$('#customMealplanmodal').find('#time_min').val('');
	$('#customMealplanmodal').find('#time_min').selectpicker('refresh');
	$('#customMealplanmodal').find('input[name="hunger_rate"]').val('1');
	$('#hungerSection').find('.hunger-range-value').text('1');
	$('#customMealplanmodal').find('input[name="recipe_name"]').val('');
	$('#customMealplanmodal').find('textarea[name="ingredients"]').val('');
	$('#customMealplanmodal').find('input[name="quantity"]').val('');
	$('#customMealplanmodal').find('textarea[name="serving_size"]').val('');
	$('#customMealplanmodal').find('textarea[name="activity_label"]').val('');
	$('#customMealplanmodal').find('textarea[name="general_notes"]').val('');
	$('#customMealplanmodal').find('.meal_image img').attr('src','');
	$('#customMealplanmodal').find('.mealrating').text('');
	$('#customMealplanmodal').find('.sizePortion').text('');
	$('#customMealplanmodal').find('.mealRateBox').hide();	
	$('#customMealplanmodal').find('#clickedPic').val('');
	$('#customMealplanmodal').find('.mealrating').data('meal-rate','');
	$('#customMealplanmodal').find('.sizePortion').data('portion-size','');

});
// sleep section start
// $('body').on('click', '#wokeupMinus', function(){
//     var $input = $(this).parent().find('input');
//     var count = parseInt($input.val()) - 1;
//     count = count < 1 ? 1 : count;
//     $input.val(count);
//     $input.trigger('input');
//     $('#humiditySection').find('.humidity-range-value').text(count);
//     return false;
// });
// $('body').on('click', '#wokeupplus', function(){
//     var $input = $(this).parent().find('input');
//     var count = parseInt($input.val()) + 1;
//     count = count > 10 ? 10 : count;
//     $input.val(count);
//     $input.trigger('input');
//     $('#humiditySection').find('.humidity-range-value').text(count);

//     return false;
// });


$('#convertPound').click(function()
{
    var weight = parseFloat($("#weight_save").val());
    weightInPounds = (weight*2.2046226218); 
    result = weightInPounds.toFixed(2);
    $("#weight_save").val(result);
    $("#convertPound").hide();
    $("#convertKg").removeClass('hidden');
    $('.kg').hide();
    $('.pound').removeClass('hidden');
    $('input[name="weightUnit"]').val('Imperial');


});
$('#convertKg').click(function()
{
    var weight = parseFloat($("#weight_save").val());
    weightInPounds = (weight/2.2046226218);
    result = weightInPounds.toFixed(0);
    $("#weight_save").val(result);
    $("#convertPound").show();
    $('.kg').show();
    $('.pound').addClass('hidden');

    $("#convertKg").addClass('hidden');
    $('input[name="weightUnit"]').val('Metric');


});

$('#convertP').click(function()
{
    var weight = parseFloat($("#weight_m").val());
    weightInPounds = (weight*2.2046226218); 
    result = weightInPounds.toFixed(2);
    $("#weight_m").val(result);
    $("#convertP").hide();
    $("#conKg").removeClass('hidden');
    $('.kg_show').hide();
    $('.pound_show').removeClass('hidden');
    $('input[name="weightUnit"]').val('Imperial');


});
$('#conKg').click(function()
{
    var weight = parseFloat($("#weight_m").val());
    weightInPounds = (weight/2.2046226218);
    result = weightInPounds.toFixed(0);
    $("#weight_m").val(result);
    $("#convertP").show();
    $('.kg_show').show();
    $('.pound_show').addClass('hidden');

    $("#conKg").addClass('hidden');
    $('input[name="weightUnit"]').val('Metric');


});

$('#convert-inches').click(function()
{
    var height = parseFloat($("#height_m").val());
    heightInCm = (height*0.393701); 
    result = heightInCm.toFixed(2);
    $("#height_m").val(result);
    $("#convert-inches").hide();
    $("#convert-cm").removeClass('hidden');
    $('.cm_show').hide();
    $('.inches_show').removeClass('hidden');
    $('input[name="heightUnit"]').val('inches');


});
$('#convert-cm').click(function()
{
    var height = parseFloat($("#height_m").val());
    heightInInches = (height/0.393701);
    result = heightInInches.toFixed(0);
    $("#height_m").val(result);
    $("#convert-inches").show();
    $('.cm_show').show();
    $('.inches_show').addClass('hidden');

    $("#convert-cm").addClass('hidden');
    $('input[name="heightUnit"]').val('cm');


});



$(document).on('click','.editdata',function(){
 var hydrationJournalId = $(this).data('id');
 var hydrationJournalTime = $(this).data('time');
  StatsModal.find($(this).closest('.non-edit').after("<span class='edit'><input type='hidden' name='hydrationJournalId' value='"+hydrationJournalId+"'><select class='name'><option value='1'>water</option><option value='2'>Coffee</option><option value='3'>Tea</option><option value='4'>Juice</option><option value='5'>Soda</option><option value='6'>Milk</option><option value='7'>Alcohal</option><option value='8'>Sports Drink</option></select><label class='time'><span class='showtime'> "+moment(hydrationJournalTime, 'HH:mm a').format('hh:mm A')+" </span>&nbsp;<a href='javascript:void(0)' class='nav-link hydrationEditDatetimePicker'>Change</a></label><select class='ml-l ml'><option value='100'>100</option><option value='200'>200</option><option value='300'>300</option><option value='400'>400</option><option value='500'>500</option><option value='600'>600</option><option value='700'>700</option><option value='800'>800</option><option value='900'>900</option><option value='1000'>1000</option></select><span class='value-l'>ml</span><button class='update-btn updateHydration'>Update</button></span>"));
     StatsModal.find($(this).closest('.non-edit').remove());

     $('.hydrationEditDatetimePicker').bootstrapMaterialDatePicker({
	date: false,
	shortTime: true,
	format: 'HH:mm:ss',
	currentDate: '09:00 AM'
}).change(function(e, date) {
	var time = date.format("hh:mm A");
	var timeHH = date.format("HH:mm:ss");

     var displayTime= $(this).closest('.time');
     displayTime.find('.showtime').text(time);
			
});

   
});


$('body').on('click','.updateHydration',function(){
 var editForm= $(this).closest('.edit');
 var formData={};
   
    formData['id'] = $('input[name="hydrationJournalId"]').val();
	formData['liquidType'] = editForm.find('.name').val();
	formData['drank'] = editForm.find('.ml').val();
	formData['time'] = moment(editForm.find('.showtime').text(), 'HH:mm a').format('HH:mm:ss');
	
		toggleWaitShield('show');
		$.post(public_url+"update-hydration-data",formData,function(response){
			toggleWaitShield('hide');
			if(response.status == 'ok'){
				swal({
					type: 'success',
					title: 'Success!',
					showCancelButton: false,
					allowOutsideClick: false,
					text: 'Data update successfully',
					showConfirmButton: true,     
				}, 
				function(isConfirm){
					if(isConfirm)
						StatsModal.modal('hide');
				});
			}else{
				swal({
					type: 'error',
					title: 'Error!',
					showCancelButton: false,
					allowOutsideClick: false,
					text: response.message,
					showConfirmButton: true,     
				}, 
				function(isConfirm){
					if(!isConfirm)
						StatsModal.modal('hide');
				});
			}
		});
	
});

