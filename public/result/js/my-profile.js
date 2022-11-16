$(document).ready(function(){
	initCustomValidator();

	$('.update-client').on('click', function(){
		var submitBtn = $(this),
			form = submitBtn.closest('form');

		saveSuccess(form, {action:'hide'});

		var isFormValid = form.valid();

		// var password= $(form).find('input[name="clientNewPwd"]').val();

			//if(isUserType(['Client'])){

			var /*emailFieldVal = $(form).find('input[name="email"]').val().trim(),
				phoneNumbField = $(form).find('input[name="numb"]'),
				phoneNumbFieldVal = phoneNumbField.val().trim(),*/
				newClientReqMsg = $('#reqMsg'),
				newPwdField = $(form).find("input[name='clientNewPwd']"),
				newPwdCnfmField = $(form).find("input[name='clientNewPwdCnfm']");

			/*if(emailFieldVal == '' && phoneNumbFieldVal == ''){
				newClientReqMsg.removeClass('hidden');
				isFormValid = false;
			}
			else if(emailFieldVal != '' || phoneNumbFieldVal != ''){
				newClientReqMsg.addClass('hidden');
				if(phoneNumbFieldVal != '' && !(validateContryCode(phoneNumbField)))
					isFormValid = false;
			}	*/

			if(!validateNewPassword({newPwdField:newPwdField, newPwdCnfmField:newPwdCnfmField}))
				isFormValid = false;
			else{
				setFieldNeutral(newPwdField);
				setFieldNeutral(newPwdCnfmField);
			}
			// else if((password != '') || (password != undefined))
			// {
			// if(!validateNewPassword({newPwdField:form.find("input[name='clientNewPwd']"), newPwdCnfmField:form.find("input[name='clientNewPwdCnfm']")}))
			// 	isFormValid = false;
						

			// }
		
    //         if(!validateNewPassword({newPwdField:form.find("input[name='clientNewPwd']"), newPwdCnfmField:form.find("input[name='clientNewPwdCnfm']")}))
				// isFormValid = false;
			// if(!validateGender(form, 'gender'))
			// 	isFormValid = false;

			// if(!validateDob(form, {day:'day', month:'month', year:'year'}))
			// 	isFormValid = false;

			
		   //}

		if(isFormValid){
			var formData = {}
			$.each(form.find(':input').serializeArray(), function(i, obj){
				formData[obj.name] = obj.value
			});

			
				
				formData['numb'] = getFullNumb($(form), $(form).find('input[name="numb"]'));
				//alert($('#clientList').val());
			
			
			   /*var $referralNetworkFieldVal = $(form).find('input[name="referralNetwork"]:checked').val();
				if($referralNetworkFieldVal == 'Client')   {                  
					formData['referralId'] = $(form).find('input[name="clientId"]').val() ,
					formData['referralName'] = $('#clientList').val() 
					}                     
				else if($referralNetworkFieldVal == 'Staff'){
					formData['referralId'] = $(form).find('input[name="staffId"]').val(),
					formData['referralName'] = $('#staffList').val() 
				}
				else if($referralNetworkFieldVal == 'Professional network'){
					formData['referralId'] = $(form).find('input[name="proId"]').val(),
					formData['referralName'] = $('#proList').val() 
				}
				else
					formData['referralId'] = '';*/
                    formData['goalHealthWellness'] = prepareMultiSel(form.find('select[name="goalHealthWellness"]'));

			$.ajax({
				url: form.attr('action'),
				method: "POST",
				data: formData,
				success: function(data){
					
					var data = JSON.parse(data);
       
					if(data.status == "updated"){
					
						
							realTimeUpdate('firstName', formData['first_name'], form.find("input[name='first_name']").data('other-targets'));
							realTimeUpdate('lastName', formData['last_name']);
							/*realTimeUpdate('email', formData['email']);
							realTimeUpdate('phone', formData['numb']);*/
							//realTimeUpdate('accStatus', formData['client_status']+'|'+$('select[name="client_status"] option:selected').text()); 
							
							
                            if( 'year' in formData ) 
							realTimeUpdate('dob', formData['year']+'-'+formData['month']+'-'+formData['day']);
							//realTimeUpdate('referralNetwork', formData['referralNetwork']+'|'+formData['referralId']+'|'+formData['referralName']);
						
						clearPassword({newPwdField:form.find("input[name='clientNewPwd']"), newPwdCnfmField:form.find("input[name='clientNewPwdCnfm']")});
						saveSuccess(form, {action:'show', type:'update'});
					}
					else if(data.status == "error"){
						$.each(data.errorData, function(key, val){
							$.each(this, function(errorType, message){
								if(errorType == "emailExist" )
									var field = form.find("input[name='email']");									
								
								setFieldInvalid(field.closest('.form-group'), message, field.next("span"))
							});							
						});
					}
				}
			});
		}

		scrollToTop(form);
		return false;
	});
	if(isUserType(['Client'])){
		/* start: Open specific tab on page load */
		var defaultTab = window.location.hash;
		if(defaultTab)
			switchTab(defaultTab)
		/* end: Open specific tab on page load */
	}
});
var entityIdVal = '';
var previewPics = '';
var prePhotoName = '';
function fileSelectHandlerNew(elem){
	// get selected file
	var oFile = elem.files[0];

	var ifCroppedImgSaved = false,
		public_url = $('meta[name="public_url"]').attr('content');

	var formGroup = $(elem).closest('.form-group')
	prePhotoName = formGroup.find('input[name="prePhotoName"]');
	entityIdVal = formGroup.find('input[name="entityId"]').val();
	var photoHelperVal = formGroup.find('input[name="photoHelper"]').val();
	previewPics = $('.'+photoHelperVal+'PreviewPics');
	var cropSelector = formGroup.find('input[name="cropSelector"]').val();
	var picCropModel = $('#cropperModal');
	var form_data = new FormData();                  
	form_data.append('fileToUpload', oFile);
	$.ajax({
		url: public_url+'photo/save',
		dataType: 'text',  
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         
		type: 'post',
		success: function(response){
			$('#imageCrop').attr('src',public_url+'uploads/'+response);
			picCropModel.find('input[name="photoName"]').val(response);
            picCropModel.modal('show');
		}
	});
}

window.addEventListener('DOMContentLoaded', function () {
	var image = document.getElementById('imageCrop');
	var cropBoxData;
	var canvasData;
	var cropper;

	$('#cropperModal').on('shown.bs.modal', function () {
		image = document.getElementById('imageCrop');
	  cropper = new Cropper(image, {
		autoCropArea: 0.5,
		ready: function () {
		  //Should set crop box data first here
		  cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
		},
		viewMode: 2,
		autoCropArea: 1,
		aspectRatio: 1 / 1
	  });
	  console.log($('#imageCrop').attr('src'));
	  console.log(image,cropper);
	  var entityIdVal = '';
			var previewPics = '';
			var prePhotoName = '';
			var ifCroppedImgSaved = false,
					public_url = $('meta[name="public_url"]').attr('content');
			
				prePhotoName = $('input[name="prePhotoName"]');
				entityIdVal = $('input[name="entityId"]').val();
				var photoHelperVal = $('input[name="photoHelper"]').val();
				previewPics = $('.'+photoHelperVal+'PreviewPics');
				var cropSelector = $('input[name="cropSelector"]').val();
	  $('.cropImg').click(function(){
		  var cropData = cropper.getData();
		  var form_data = {};             
		  form_data['photoName'] = $('#cropperModal').find('input[name="photoName"]').val();
		  form_data['widthScale'] = cropData.scaleX;
		  form_data['x1'] = cropData.x;
		  form_data['w'] = cropData.width;
		  form_data['heightScale'] = cropData.scaleY;
		  form_data['y1'] = cropData.y;
		  form_data['h'] = cropData.height;
		  $.ajax({
			  url: public_url+'photo/save',
			  data: form_data,                         
			  type: 'post',
			  success: function(response){
				$('#cropperModal').modal('hide');
				previewPics.prop('src', public_url+'uploads/thumb_'+response);
				if(previewPics.hasClass('hidden'))
					previewPics.removeClass('hidden');
				prePhotoName.val(response);
				formData = {};
				formData['id'] = entityIdVal;
				formData['photoName'] = response;
				$.ajax({
					url: public_url+'client/photo/save',
					data: formData,                         
					method: 'POST'
				});
			  }
		  });
	  })
	}).on('hidden.bs.modal', function () {
	  cropBoxData = cropper.getCropBoxData();
	  canvasData = cropper.getCanvasData();
	  cropper.destroy();
	});
  });

  Webcam.set({
    width: 320,
    height: 240,
    image_format: 'jpeg',
	jpeg_quality: 90,
	constraints: constraints,
});
$('#openWebcam').on('click',function(e) {
	$('#webcam-modal').modal('show');
	Webcam.attach('#camera');
});


$('.snap').on('click',function(){
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