var wizardForm = $('#wizard-form');


$('.showRelation').click(function()
{
	$('#ecrelationModal').hide();

var ecRelation=$('#ecRelation').val();
					var html = '<div class="form-group ">\
					<label class="strong">Relationship</label>\
					<input class="form-control" value="'+ecRelation+'">\
					</div>';
					$('.showNotes').append(html);
				
});


            $('#changeRelation').on('input',function(){
	        var changeRelation=$('#changeRelation').val();
	        $('#ecRelation').val(changeRelation);
                });

            $('body').on('click', '.remove-img', function(e){
			e.preventDefault();
			var $this = $(this),
				preImgPath = $this.find('img').attr('src'),
				preImg = preImgPath.substring(preImgPath.lastIndexOf("/")+1);
			var client_id = $(this).data('id');
			$.post(public_url+'remove/photos', {
				photoName:preImg,
				entityId:client_id,
				entityName: 'clients'
			}, function(response){
				var data = JSON.parse(response);
				if(data.status == 'success')
				$('.clientpicPreviewPics').attr('src','');
             	$('input[name="clientpic"]').val('');
				$('#userPic').removeClass('hide');
			
				$('.openCamera').removeClass('hide');
				$('.takePic').addClass('hide');
				$('.picRemove').addClass('hide');


			});
		});

$('.genderShow').on('click',function()
{
	$('.genderValidation').hide();
})
Webcam.set({
    width: 320,
    height: 240,
    image_format: 'jpeg',
	jpeg_quality: 90
});
$('body').on('click','.openCamera',function(e) {
	$('#webcam-modal').modal('show');
		Webcam.attach('#camera');

		// start();

});
$('body').on('click','.snap',function(){
	Webcam.snap(function(data_uri) {
		$('#imageCrop').attr('src',data_uri);
		$.post(public_url+'photo/capture-save',{data:data_uri},function(file, response){
			// $('#imageCrop').attr('src',data_uri);
			$('#cropperModal').find('input[name="photoName"]').val(file);
			$('#cropperModal').modal('show');
		});
		Webcam.reset();
		$('#webcam-modal').modal('hide');
		$('#cropperModal').modal('show');
	});
});
$('.close-webcam').click(function(){
	Webcam.reset();
	$('#webcam-modal').modal('hide');
})
$('.openStep').on('click',function(){
	var url=$(this).data('url');
	window.location.href= url;

})

$('document').ready(function(){

	
	function setHighlight(step){
		$('#step-'+step+' .bodyPartsDd .dropdown-menu li a').removeClass('highlight');
		$('#step-'+step+' #bodyParts'+step+' option').each(function(){
			$this = $(this);
			var section = $(this).data('part');
			if(section != ''){
				var count = 0;
				$('#step-'+step+' .'+section).find('input[type="checkbox"]').each(function(){
					if($(this).is(':checked')){
						count = count + 1;
					}
				});
				if(count > 0){
					var optionText = $this.text();
					$('#step-'+step+' .bodyPartsDd .dropdown-menu li a').each(function(){
						if($(this).find('span').text() == optionText){
							$(this).addClass('highlight');
						}
					});
				}else{
					if($('#step-'+step+' .'+section).find('textarea').val() != '' && $('#step-'+step+' .'+section).find('textarea').val() != undefined){
						var optionText = $this.text();
						$('#step-'+step+' .bodyPartsDd .dropdown-menu li a').each(function(){
							if($(this).find('span').text() == optionText){
								$(this).addClass('highlight');
							}
						});
					}
				}
			}
			$('#step-'+step+' #bodyParts'+step+'').selectpicker();
		});
	}
	$('#step-3 #bodyParts3').on('show.bs.select', function() {
		setHighlight(3);
	});
	$('#step-5 #bodyParts5').on('show.bs.select', function() {
		setHighlight(5);
	});
});

// $('document').ready(function(){
// 	$('html, body').animate({
//         scrollTop: $("#wizard").offset().top
//     }, 1000);
// })

$('.alertHide').click(function(){
	var Modal= $(this);
	Modal.closest('.modal').find('.injuryShowAlert').hide();
})

$('.hightlightNext').click(function(){
	$('.nextStepBtn').attr('style', 'background-color:#f94211');
})

$('.hightlightFinish').click(function(){
	$('#finish-parq').attr('style', 'background-color:#f94211 !important; color:#ffffff;');
})

$('input[name="referrer"]').on('click',function(){
var value = $(this).val();
if(value == 'onlinesocial' || value == 'mediapromotions' || value == 'socialmedia'){
	$('.fromWhere').removeClass('hide');
	if($('.referredCompany').hasClass('hide')){
		// $('.referredCompany').removeClass('hide');
	}
}else{
	$('.fromWhere').addClass('hide');
	// $('.referredCompany').addClass('hide');
}
});

	$('.submit-step').on('click',function(){
		var formValid = true;
		var formData={};
		var instance = $(this);
		var stepNumb = $('.submit-step').data('step');
		var stepId= $('.submit-step').data('step');
		jQuery.each(jQuery(wizardForm).find(':input').serializeArray(), function(i, obj){
					var value = obj.value;
					formData[obj.name] = value.toString();
				});
				if(stepNumb == 1){
					$(".cntryCode").each(function(){
						var cntryData = $(this).intlTelInput("getNumber")
						if(cntryData)
							formData[$(this).attr('name')] = cntryData;
					});
				}else if(stepNumb == 2){
				// 	formData['preferredTraingDays'] = [];
				//    $('.preferredTraingDays:checked').each(function(){
				// 		if($(this).val() != null && $(this).val() != '')
				// 			formData['preferredTraingDays'].push($(this).val());
						
				// 	});
					// $("select.intensity").each(function(){
					// 	if($(this).val() != null)
					// 		formData[$(this).attr('name')] = $(this).val();
					// 	else
					// 		formData[$(this).attr('name')] = '';
					// });
				}else if(stepNumb == 3){
					formData['smokingPerDay'] = $('input[name="smokingPer"]:checked').val();
					formData['smoking'] = $('input[name="smoke"]:checked').val();
					formData['allergies'] = $('input[name="allergy"]:checked').val();
					formData['chronicMedication'] = $('input[name="chronic"]:checked').val();
				}else if(stepNumb == 5){
					var fitgoalKey = '';
					var fitgoalVals = JSON.parse($('input[name="goalFitnessComponents"]').val())
					
					$.each(fitgoalVals, function(key,value){
						$.each(value, function(k,v){
							if(k == 'id')
								fitgoalKey = v;
							if(k == 'children'){
								$.each(v, function(x,y){
									if(!formData[fitgoalKey])
										formData[fitgoalKey] = y.id;
									else
										formData[fitgoalKey] += ','+y.id;
								});
							}
						});
					});
					formData['goalWantfeel'] =[];
					$('input[name="goalWantfeel"]:checked').each(function(){
						if($(this).val() != null && $(this).val() != '')
						formData['goalWantfeel'].push($(this).val());
					
					});
					formData['lifestyleImprove'] =[];
					$('input[name="lifestyleImprove"]:checked').each(function(){
						if($(this).val() != null && $(this).val() != '')
						formData['lifestyleImprove'].push($(this).val());
						});
					formData['goalWantTobe'] =[];
					$('input[name="goalWantTobe"]:checked').each(function(){
						if($(this).val() != null && $(this).val() != '')
						formData['goalWantTobe'].push($(this).val());
						
					});
					formData['goalWantHave'] =[];
					$('input[name="goalWantHave"]:checked').each(function(){
						if($(this).val() != null && $(this).val() != '')
						formData['goalWantHave'].push($(this).val());
						
					});
					formData['motivationImprove'] =[];
					$('input[name="motivationImprove"]:checked').each(function(){
						if($(this).val() != null && $(this).val() != '')
						formData['motivationImprove'].push($(this).val());
						
					});
					formData['healthGoals'] =[];
					$('input[name="healthGoals"]:checked').each(function(){
						if($(this).val() != null && $(this).val() != '')
						formData['healthGoals'].push($(this).val());
						
					});
		
					
		   //        var smartGoalNotesOPtion=$('select[name="smart_goal_option"]').val();
					// formData['smart_goal_option'] =smartGoalNotesOPtion;
		
					 
				}
	    formData['client_id'] = jQuery(wizardForm).find('[name="client_id"]').val();
		formData['_token'] = jQuery(wizardForm).find('[name="_token"]').val();
		formData['stepNumb'] = stepNumb;
		if(formValid){
			jQuery.ajax({
				url: public_url+'saveparq',
				type: 'POST',
				data: {
					'formData':formData,
					'_token':formData['_token']
				},
				success: function(data){
					var data = JSON.parse(data);
					console.log('data', data);
					if(data.status == "updated"){
						if(instance.hasClass('injuryAlert')){
							instance.closest('.modal').find('.injuryShowAlert').show();
						}
						else if(stepNumb != 5)
						{
							swal({
								title: 'Success',
								text:'Data Added Successfully \n Now redirecting you to next step.',
								allowOutsideClick: false,
								showCancelButton: false,
								confirmButtonText: 'ok',
								confirmButtonColor: '#ff4401',
								animation: false
							}, 
							function(isConfirm){
								if(isConfirm){
									window.location.href = instance.data('step-url');
									}
								
							});
						}else if(stepNumb == 5){
							$('#waiverModal').show();
						}
					
						if(stepNumb == 1){
	
							if(formData['referrer'] != 'onlinesocial' && formData['referrer'] != 'mediapromotions')
								wizardForm.find('input[name="referencewhere"]').val('')
							realTimeUpdate('email', formData['primEm']);
							realTimeUpdate('phone', formData['contactNo']);
							realTimeUpdate('firstName', formData['firstName']);
							realTimeUpdate('lastName', formData['lastName']);
							realTimeUpdate('occupation', formData['occupation']);
							realTimeUpdate('gender', formData['gender']);
							realTimeUpdate('dob', formData['yyyy']+'-'+formData['mm']+'-'+formData['dd']);
							realTimeUpdate('referralNetwork', formData['referralNetwork']+'|'+formData['referralId']+'|'+formData['referralName']);
						}
	
						// if(stepNumb > stepCompleted){
						// 	//realTimeUpdate('parqStatus', 5-stepNumb);
						// 	stepCompleted = stepNumb;
						// }
	
						var $sucMesElem = $(stepId).find('.sucMes');
						if(data.message != undefined && data.message == ''){
							$sucMesElem.html(data.message);
							$sucMesElem.removeClass('hidden');
						}
						else{
							$sucMesElem.html('');
							$sucMesElem.addClass('hidden');
						}
						// if(stepNumb == 5)
						// 	window.location.reload();
					}
					else if(data.status == "error"){
						$.each(data.errorData, function(key, val){
							$.each(this, function(errorType, message){
								if(errorType == "emailExist" )
									var field = $('input[name="primEm"]');	
	
								setFieldInvalid(field.closest('.form-group'), field.next("span"), message)
							});							
						});
						wizardContent.smartWizard("goBackward");
	
						if(!isNextStepDone)
							setTimeout(function(){wizardContent.smartWizard('disableStep', toStep)}, 300);
					}
					// scrollToTop(stepId);
				}
			});
		}
})

$('input[name="smoke"]').on('click',function(){
	var smoke = $(this).val();
	if(smoke == 'Yes'){
		$('.smokeData').removeClass('hide');
		$('input[name="smoking"]').val(smoke);
		$('input[name="smokingPerDay"]').val($('input[name="smokingPer"]').val());
	}else{
		if(!$('.smokeData').hasClass('hide')){
			$('.smokeData').addClass('hide');
			$('input[name="smoking"]').val(smoke);
		}
	}
	
});


$('input[name="allergy"]').on('click',function(){
	var allergy = $(this).val();
	if(allergy == 'Yes'){
		$('.AllergyDetails').removeClass('hide');
		$('input[name="allergies"]').val(allergy);
	}else{
		if(!$('.AllergyDetails').hasClass('hide')){
			$('.AllergyDetails').addClass('hide');
			$('input[name="allergies"]').val(allergy);
		}
	}
	
});

$('input[name="chronic"]').on('click',function(){
	var chronicMedication = $(this).val();
	if(chronicMedication == 'Yes'){
		$('.chronicMedication').removeClass('hide');
		$('input[name="chronicMedication"]').val(chronicMedication);
	}else{
		if(!$('.chronicMedication').hasClass('hide')){
			$('.chronicMedication').addClass('hide');
			$('input[name="chronicMedication"]').val(chronicMedication);
		}
	}
	
});

$('.medication').click(function(){
	var option = $(this).val(),
	step= $(this).closest('.step').data('index');
		if(option == `ansYes${step}`){
		$('.avada_modal').show();
	}
})

$('.awadaModalClose').click(function(){
	$('.avada_modal').hide();
})
jQuery(document).ready(function() {
	UINestable.init();
});

$("#wantToBeCheckAll").click(function () {
    $('input[name="goalWantTobe"]').prop('checked', $(this).prop('checked'));
});

$("#goalWantHaveCheckAll").click(function () {
    $('input[name="goalWantfeel"]').prop('checked', $(this).prop('checked'));
});
$("#goalWantHaveCheck").click(function () {
    $('input[name="goalWantHave"]').prop('checked', $(this).prop('checked'));
});

$('.preferredTraingDays').on('change', function() {
	var $this = $(this),
	preferredTraingDaysField = $this.closest('.preferDayTime').find('.preferredTraingDays'),
	preferredTraingDaysValHolder = $('input[name="preferredTraingDays"]');
	prefTrainDaysVal = {};
	$.each(preferredTraingDaysField, function(index, preferredDay) {

		var attr = $(preferredDay).attr('checked');
		if($(preferredDay).is(':checked')) {
			var day = $(preferredDay).attr('data-day'),
			time = $(preferredDay).val();

			if(day in prefTrainDaysVal){
				var preferredTime = prefTrainDaysVal[day];

				preferredTime.push(time);
				prefTrainDaysVal[day] = preferredTime;
			}
			else {
				prefTrainDaysVal[day] = [time];
			}
		} // end if
	}); // end each loop
	preferredTraingDaysValHolder.val(JSON.stringify(prefTrainDaysVal));
});

$('document').ready(function() {
	var mPrefTrainDays = $('input[name="preferredTraingDays"]').val();
	if(mPrefTrainDays !== 'null') {
		mPrefTrainDays = JSON.parse(mPrefTrainDays);
		for(var key in mPrefTrainDays) {
			if (mPrefTrainDays.hasOwnProperty(key)) {
			    var mPrefTimes = mPrefTrainDays[key];

			    for(var i = 0; i < mPrefTimes.length; i++) {
			        var mPrefTime = mPrefTimes[i];

			        var timeFiled = $('input[data-day="'+key+'"][value="'+mPrefTime+'"]');
			        timeFiled.attr('checked', 'checked');

			        // timeFiled.closest('.prefTrainSlot').addClass('active');
			    }
			} 
		} 
	} 
})

$('.backto_dashboard').click(function(){
	var stepNumb = $('.submit-step').data('step');
	var stepStatus = $('input[name="step_status"]');
	if(stepStatus.length){
		var step_status = stepStatus.val().split(',');
	
			if((stepNumb == 1 || stepNumb == 2 || stepNumb == 3||stepNumb == 4 || stepNumb == 5) && $.trim(step_status) == 'completed'){
				window.location =public_url+'new-dashboard';
			}else{
				swal({
					title: 'Warning',
					text:'Are you sure you want to leave the page? Any unsaved changes will be Lost.',
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'ok',
					confirmButtonColor: '#ff4401',
					animation: false
				}, 
				function(isConfirm){
					if(isConfirm){
						window.location =public_url+'new-dashboard';
						}
					
				});
			}
		
	}


})