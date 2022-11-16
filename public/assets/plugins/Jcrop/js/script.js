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
var jcrop_api, boundx, boundy, $oImage;

function fileSelectHandler(elem) {
	// get selected file
	var oFile = elem.files[0];

	var ifCroppedImgSaved = false,
		public_url = $('meta[name="public_url"]').attr('content');

	var formGroup = $(elem).closest('.form-group')
	var prePhotoName = formGroup.find('input[name="prePhotoName"]');
	var entityIdVal = formGroup.find('input[name="entityId"]').val();
	var photoHelperVal = formGroup.find('input[name="photoHelper"]').val();
	var previewPics = $('.'+photoHelperVal+'PreviewPics');
	var cropSelector = formGroup.find('input[name="cropSelector"]').val();
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
	var widthScale = picCropModel.find('input[name="widthScale"]');
	var heightScale = picCropModel.find('input[name="heightScale"]');
	photoName.val('');
	ui_w.val('');
	ui_h.val('');
	ui_x1.val('');
	ui_y1.val('');
	widthScale.val('');
	heightScale.val('');
	$oImage.src = '';
	
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
			photoName.val(response);
			picCropModel.modal('show');
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
			form_data.append('photoHelper', $('input[name="photoHelper"]').val());
			$.ajax({
				url: public_url+'photo/save',
				dataType: 'text',  
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(response){
					ifCroppedImgSaved = true;
					picCropModel.modal('hide');

					if(entityIdVal != null && entityIdVal != ''){
						formData = {};
						formData['id'] = entityIdVal;
						formData['photoName'] = response;
						$.ajax({
							url: public_url+formGroup.find('input[name="saveUrl"]').val(),
							data: formData,                         
							method: 'POST'
						});
					}
					else 
						formGroup.find('input[name="'+photoHelperVal+'"]').val(response);
					
					previewPics.prop('src', public_url+'uploads/thumb_'+response);
					previewPics.closest('a').attr('href',public_url+'uploads/thumb_'+response);
					if(previewPics.hasClass('hidden'))
						previewPics.removeClass('hidden');
                     $('.image-uploder-label-style').css('display','none');
					var html='<a class="remove-preview"><i class="fa fa-times removeImage" style="color:#ff4401;">Remove</i></a>';
					formGroup.find('.removeImage').remove();
					formGroup.find('.clone-image-row').append(html);
					prePhotoName.val(response)

					setUploadFieldValid(formGroup);
				}
			});
			destroyJcrop();
		}
	});
	

	picCropModel.off('hide.bs.modal');
	picCropModel.on('hide.bs.modal', function(){
		if(!ifCroppedImgSaved){
			var formData = {};

			formData['photoName'] = photoName.val();
			$.post(public_url+'photos/delete', formData);	
		}
		 var modal = $(this).closest('.modal');
           modal.find('.preview').removeAttr('src')
           modal.find('.preview').removeAttr('style');
		destroyJcrop();
    });

	picCropModel.off('show.bs.modal');
    picCropModel.on('show.bs.modal', function(){
    	toggleRatio.show();
    	// if(cropSelector.length == 1)
    	// 	toggleRatio.hide();
    	// else if(cropSelector.length > 1){
    	// 	toggleRatio.each(function(){
    	// 		var $this = $(this);
    	// 		if(jQuery.inArray($this.data('crop-selector'), cropSelector) < 0)
    	// 			$this.hide();
    	// 	})
    	// }

		var oImage = $oImage[0];
	    // prepare HTML5 FileReader
	    var oReader = new FileReader();
	        oReader.onload = function(e) {

	        // e.target.result contains the DataURL which we can use as a source of the image
	        oImage.src = e.target.result;
			//picCropModel.modal('show');
	        oImage.onload = function () { // onload event handler
				var oImageNW = oImage.naturalWidth;
				var oImageNH = oImage.naturalHeight;
	            // destroy Jcrop if it is existed
					
	            setTimeout(function(){
	                // initialize Jcrop
					var oImageW = $oImage.width();
					var oImageH = $oImage.height();
					if(oImageNW > oImageW)
						widthScale.val(oImageNW/oImageW);
					if(oImageNH > oImageH)
						heightScale.val(oImageNH/oImageH);
	            
					/*if (jcrop_api) {
						jcrop_api.destroy();
						jcrop_api = null;
						$oImage.width(oImageNW);
						$oImage.height(oImageNH);
					}*/

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
	    };

	    // read selected file as DataURL
	    oReader.readAsDataURL(oFile);
    });
};

