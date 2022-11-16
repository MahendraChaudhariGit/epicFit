// update info by cropping (onChange and onSelect events handler)
function updateInfo(e) {
    $('input[name="ui-x1"]').val(e.x);
    $('input[name="ui-y1"]').val(e.y);
    /*$('#x2').val(e.x2);
    $('#y2').val(e.y2);*/
    $('input[name="ui-w"]').val(e.w);
    $('input[name="ui-h"]').val(e.h);
};

// clear info by cropping (onRelease event handler)
function clearInfo(e) {
    $('input[name="ui-w"]').val('');
    $('input[name="ui-h"]').val('');
};

function destroyJcrop(){
	if(jcrop_api){
		jcrop_api.destroy();
		jcrop_api = null;
	}
	$oImage.src = '';
}

var toggleRatio = $('.picCropModel').find('a.toggle-ratio');
toggleRatio.click(function(e){
	e.preventDefault();
	var $this = $(this);

	toggleRatio.removeClass('active');
	$this.addClass('active');
	jcrop_api.setOptions({aspectRatio:$this.data('ratio')});
});

function setUploadFieldValid($formGroup){
	if($formGroup.hasClass('has-error'))
		$formGroup.removeClass('has-error')
	$formGroup.addClass('has-success');
	var $helpBlock = $formGroup.find("span.help-block")
	$helpBlock.html('');
	$helpBlock.hide();
}
// Create variables (in this scope) to hold the Jcrop API and image size
var jcrop_api, boundx, boundy, $oImage,$data_from,oFile,heightScale,widthScale,cropSelector;

function postureFileSelectHandler(elem) {
	// get selected file
	oFile = elem.files[0];
	
	var ifCroppedImgSaved = false,
		public_url = $('meta[name="public_url"]').attr('content');

	var formGroup = $(elem).closest('.posture-button')
	var image_name = formGroup.find('input[name="image_name"]').val();
	var prePhotoName = formGroup.find('input[name="postureimage"]');
	var entityIdVal = formGroup.find('input[name="client_id"]').val();
	var posture_id = formGroup.find('input[name="posture_id"]').val();
	var previewPics = $('.'+image_name+'-posture-pre');
	cropSelector = formGroup.find('input[name="posturecropSelector"]').val();
	if(cropSelector)
		cropSelector = cropSelector.split(',');
	//console.log(cropSelector)
	//console.log(cropSelector.length)

	var picCropModel = $('.picCropModel');
	$oImage = picCropModel.find('img.preview');
	var photoName = picCropModel.find('input[name="photoName"]');
	var ui_w = picCropModel.find('input[name="ui-w"]');
	var ui_h = picCropModel.find('input[name="ui-h"]');
	var ui_x1 = picCropModel.find('input[name="ui-x1"]');
	var ui_y1 = picCropModel.find('input[name="ui-y1"]');
	widthScale = picCropModel.find('input[name="widthScale"]');
	heightScale = picCropModel.find('input[name="heightScale"]');
	$(".crop-selector").css('display','none');
	$('.rectangleRatio').data('ratio',0.41);
	photoName.val('');
	ui_w.val('');
	ui_h.val('');
	ui_x1.val('');
	ui_y1.val('');
	widthScale.val('');
	heightScale.val('');
	$oImage.src = '';
	$data_from = 'trainer';
    var form_data = new FormData();                  
    form_data.append('fileToUpload', oFile);
    $.ajax({
		url: public_url+'posture/image',
		dataType: 'text',  
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         
		type: 'post',
		beforeSend: function(){
			$('#waitingShield').removeClass('hidden');
		},
		success: function(response){
			photoName.val(response);
			picCropModel.modal('show');
		},
		complete: function(){
			setTimeout(function() {
				$('#waitingShield').addClass('hidden');
			},2000);
		}
     });
    
	picCropModel.find('button.save').unbind("click").click(function(){
		if(ui_w.val() != ''){
			//picCropModel.modal('hide');
			var form_data = new FormData();                  
			form_data.append('photoName', photoName.val());
			form_data.append('widthScale', widthScale.val());
			form_data.append('x1', ui_x1.val());
			form_data.append('w', ui_w.val());
			form_data.append('heightScale', heightScale.val());
			form_data.append('y1', ui_y1.val());
			form_data.append('h', ui_h.val());
			form_data.append('prePhotoName', prePhotoName.val());
			form_data.append('photoHelper', $('input[name="posturephotoHelper"]').val());
			$.ajax({
				url: public_url+'posture/image',
				dataType: 'text',  
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				beforeSend: function(){
                    $('#waitingShield').removeClass('hidden');
                },
				success: function(response){
					
					ifCroppedImgSaved = true;
					picCropModel.modal('hide');

					if(entityIdVal != null && entityIdVal != ''){
						formData = {};
						formData['posture_id'] = posture_id;
						formData['client_id'] = entityIdVal;
						formData['photoName'] = response;
						formData['image_name'] = image_name;
						$.ajax({
							url: public_url+'save/posture/image',
							data: formData,                         
							method: 'POST',
							beforeSend: function(){
								$('#waitingShield').removeClass('hidden');
							},
							success:function(res) {
								$(".delete-image").attr('data-posture-id',res.posture_id);
								$(elem).parent().siblings(".delete-image").attr('data-image-name',image_name);
								if(res.status == 'create'){
									$('input[name="posture_id"]').val(res.posture_id);
									$('.image-note').attr('data-posture-id',res.posture_id);
									$("#posture-analysis").attr('data-posture-id',res.posture_id);
								}
								if(res.status == 'update'){

								}
								destroyJcrop()
							},
							complete: function(){
								setTimeout(function() {
									$('#waitingShield').addClass('hidden');
								},2000);
							}
						});
					}
					else 
						formGroup.find('input[name="posturephotoHelper"]').val(response);

					previewPics.prop('src', public_url+'posture-images/thumb_'+response);

				},
				complete: function(){
					setTimeout(function() {
						$('#waitingShield').addClass('hidden');
					},2000);
				}
			});
		}
	});
};
var $cImage;
var posture_id;
$(document).on('click','.posture-update',function() {
	var client_id = $(this).attr('data-client-id');
	posture_id = $(this).attr('data-posture-id');
	var edit_from = $(this).attr('edit-from');
	$.ajax({
		url: public_url + "edit/analysis",
		method: 'post',
		data: {
			'client_id': client_id,
			'posture_id': posture_id,
		},
		beforeSend: function(){
			$('#waitingShield').removeClass('hidden');
		},
		success:function(res) {
			// console.log(res);
			if(res.status == 'crop'){
				var prePhotoName = $('input[name="postureimage"]');
				var previewPics = $('.'+res.image_type+'-posture-pre');
				cropSelector = $('input[name="posturecropSelector"]').val();
				if(cropSelector)
					cropSelector = cropSelector.split(',');
				var picCropModel1 = $('.picCropModel');
				$oImage = picCropModel1.find('img.preview');
				var photoName = picCropModel1.find('input[name="photoName"]');
				var ui_w = picCropModel1.find('input[name="ui-w"]');
				var ui_h = picCropModel1.find('input[name="ui-h"]');
				var ui_x1 = picCropModel1.find('input[name="ui-x1"]');
				var ui_y1 = picCropModel1.find('input[name="ui-y1"]');
				widthScale = picCropModel1.find('input[name="widthScale"]');
				heightScale = picCropModel1.find('input[name="heightScale"]');
				$(".crop-selector").css('display','none');
				$('.rectangleRatio').data('ratio',0.41);
				photoName.val('');
				ui_w.val('');
				ui_h.val('');
				ui_x1.val('');
				ui_y1.val('');
				widthScale.val('');
				heightScale.val('');
				$oImage.src = '';
				$cImage = public_url+'posture-images/'+res.image_name;
				$data_from = 'client';
				photoName.val(res.image_name);
				picCropModel1.modal('show');
				picCropModel1.find('button.save').unbind("click").click(function(){
					if(ui_w.val() != ''){
						picCropModel1.modal('hide');
						var form_data = new FormData();                  
						form_data.append('photoName', photoName.val());
						form_data.append('widthScale', widthScale.val());
						form_data.append('x1', ui_x1.val());
						form_data.append('w', ui_w.val());
						form_data.append('heightScale', heightScale.val());
						form_data.append('y1', ui_y1.val());
						form_data.append('h', ui_h.val());
						form_data.append('prePhotoName', prePhotoName.val());
						form_data.append('photoHelper', $('input[name="posturephotoHelper"]').val());
						$.ajax({
							url: public_url+'posture/image',
							dataType: 'text',  
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,                         
							type: 'post',
							beforeSend: function(){
								$('#waitingShield').removeClass('hidden');
							},
							success: function(response){
								picCropModel1.modal('hide');
								if(res.posture_id != null && res.posture_id != ''){
									formData = {};
									formData['posture_id'] = res.posture_id;
									// formData['client_id'] = entityIdVal;
									formData['photoName'] = res.image_name;
									formData['image_name'] = res.image_type;
									$.ajax({
										url: public_url+'save/posture/image',
										data: formData,
										method: 'POST',
										beforeSend: function(){
											$('#waitingShield').removeClass('hidden');
										},
										success:function(res) {
											// console.log(res);
											$(".delete-image").attr('data-posture-id',res.posture_id);
											$(".delete-image").attr('data-image-name',res.image_type);
											if(res.status == 'create'){
												$('input[name="posture_id"]').val(res.posture_id);
												$("#posture-analysis").attr('data-posture-id',res.posture_id);
											}
											if(res.status == 'update'){
												
											}
											destroyJcrop()
											$('.posture-update[data-posture-id='+posture_id+']').trigger('click');
											
										},
										complete: function(){
											setTimeout(function() {
												$('#waitingShield').addClass('hidden');
											},2000);
										}
									});
								}
								// else 
								// 	formGroup.find('input[name="posturephotoHelper"]').val(response);
			
									// previewPics.prop('src', public_url+'posture-images/thumb_'+response);
			
							},
							complete: function(){
								setTimeout(function() {
									$('#waitingShield').addClass('hidden');
								},2000);
							}
						});
					}
				});
				
				
			}else{
				$(".posture-list").addClass('hidden');
				$(".create-posture").addClass('hidden');
				$(".view-posture").addClass('hidden');
				$(".edit-posture").html(res);
				$(".edit-posture").removeClass('hidden');
				if(edit_from == 'preview-page'){
					$('.go-back').attr('data-from','preview-page');
				}else{
					$('.go-back').attr('data-from','');
				}
			}
		},
		complete: function(){
			setTimeout(function() {
				$('#waitingShield').addClass('hidden');
			},2000);
		}
	});
});

$('.picCropModel').off('show.bs.modal');
$('.picCropModel').on('show.bs.modal', function(){
	toggleRatio.show();
	var oImage = $oImage[0];
	// prepare HTML5 FileReader
	if($data_from != 'client'){
		var oReader = new FileReader();
		oReader.onload = function(e) {

		// e.target.result contains the DataURL which we can use as a source of the image
		oImage.src = e.target.result;
		//picCropModel.modal('show');
		oImage.onload = function () { // onload event handler
			var oImageNW = oImage.naturalWidth;
			var oImageNH = oImage.naturalHeight;
			var minheight = 120;
			// if(oImageNH < 553){
			// 	aspectRatio = 226/oImageNH;
			// 	$('.rectangleRatio').data('ratio',aspectRatio);
			// 	console.log(aspectRatio);
			// 	minheight = $oImage.height();
			// }
			// destroy Jcrop if it is existed
				
			setTimeout(function(){
				// initialize Jcrop
				var oImageW = $oImage.width();
				var oImageH = $oImage.height();
				if(oImageNW > oImageW)
					widthScale.val(oImageNW/oImageW);
				if(oImageNH > oImageH)
					heightScale.val(oImageNH/oImageH);
			
				$oImage.Jcrop({
					minSize: [minheight, 120], // min crop size
					//aspectRatio : 1, // keep aspect ratio 1:1
					bgFade: true, // use fade effect
					bgOpacity: .3, // fade opacity
					onChange: updateInfo,
					onSelect: updateInfo,
					setSelect:   [ 0, 0, minheight, 120 ],
					onRelease: clearInfo,
					allowSelect: false
				}, function(){

					// use the Jcrop API to get the real image size
					var bounds = this.getBounds();
					boundx = bounds[0];
					boundy = bounds[1];

					// Store the Jcrop API in the jcrop_api variable
					jcrop_api = this;
				});
				if(cropSelector.length)
					toggleRatio.filter("[data-crop-selector='"+cropSelector[0]+"']").trigger('click');
				else
					toggleRatio.first().trigger('click');
			} ,300);
		};
	};

	// read selected file as DataURL
	oReader.readAsDataURL(oFile);
	}else{
		console.log('here2');
		oImage.src = $cImage;
		//picCropModel.modal('show');
		oImage.onload = function () { // onload event handler
			var oImageNW = oImage.naturalWidth;
			var oImageNH = oImage.naturalHeight;
			// if(oImageNH < 553){
			// 	aspectRatio = 226/oImageNH;
			// 	$('.rectangleRatio').data('ratio',aspectRatio);
			// 	console.log(aspectRatio);
			// }
			// destroy Jcrop if it is existed
				
			setTimeout(function(){
				// initialize Jcrop
				var oImageW = $oImage.width();
				var oImageH = $oImage.height();
				if(oImageNW > oImageW)
					widthScale.val(oImageNW/oImageW);
				if(oImageNH > oImageH)
					heightScale.val(oImageNH/oImageH);
			
				$oImage.Jcrop({
					minSize: [120, 120], // min crop size
					//aspectRatio : 1, // keep aspect ratio 1:1
					bgFade: true, // use fade effect
					bgOpacity: .3, // fade opacity
					onChange: updateInfo,
					onSelect: updateInfo,
					setSelect:   [ 0, 0, 120, 120 ],
					onRelease: clearInfo,
					allowSelect: false
				}, function(){

					// use the Jcrop API to get the real image size
					var bounds = this.getBounds();
					boundx = bounds[0];
					boundy = bounds[1];

					// Store the Jcrop API in the jcrop_api variable
					jcrop_api = this;
				});
				if(cropSelector.length)
					toggleRatio.filter("[data-crop-selector='"+cropSelector[0]+"']").trigger('click');
				else
					toggleRatio.first().trigger('click');
			} ,300);
		};
	}
	
});