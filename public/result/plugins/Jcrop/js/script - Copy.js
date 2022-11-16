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

// Create variables (in this scope) to hold the Jcrop API and image size
var jcrop_api, boundx, boundy;

function fileSelectHandler(elem) {
	// get selected file
	var oFile = elem.files[0];
	var picCropModel = $('.picCropModel');
	var widthScale = $('input[name="widthScale"]');
	var heightScale = $('input[name="heightScale"]');
	widthScale.val('');
	heightScale.val('');
	
    var form_data = new FormData();                  
    form_data.append('fileToUpload', oFile);
    $.ajax({
		url: $('meta[name="public_url"]').attr('content')+'client/photo/save',
		dataType: 'text',  
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         
		type: 'post',
		success: function(response){
			$('input[name="photoName"]').val(response);
		}
     });
    
	//var picCropModel = $('.picCropModel');
	var $oImage = picCropModel.find('img.preview');
	picCropModel.find('button.save').click(function(){
		if($('input[name="ui-w"]').val() != ''){
			picCropModel.modal('hide');
			var form_data = new FormData();                  
			form_data.append('photoName', $('input[name="photoName"]').val());
			form_data.append('widthScale', $('input[name="widthScale"]').val());
			form_data.append('x1', $('input[name="ui-x1"]').val());
			form_data.append('w', $('input[name="ui-w"]').val());
			form_data.append('heightScale', $('input[name="heightScale"]').val());
			form_data.append('y1', $('input[name="ui-y1"]').val());
			form_data.append('h', $('input[name="ui-h"]').val());
			form_data.append('prePhotoName', $('input[name="prePhotoName"]').val());
			form_data.append('id', $('#client_id').val());
			$.ajax({
				url: $('meta[name="public_url"]').attr('content')+'client/photo/save',
				dataType: 'text',  
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(response){
					$('.clientPreviewPics').prop('src', response);
					$('.clientPreviewPics').removeClass('hidden');
					//cont.find('label span').html('Change Photo');
				}
			 });
			 if(jcrop_api){
				jcrop_api.destroy();
				jcrop_api = null;
			 }
			$oImage.src = '';
		}
	});
	
	var oImage = $oImage[0];
    // prepare HTML5 FileReader
    var oReader = new FileReader();
        oReader.onload = function(e) {

        // e.target.result contains the DataURL which we can use as a source of the image
        oImage.src = e.target.result;
		picCropModel.modal('show');
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
                    aspectRatio : 1, // keep aspect ratio 1:1
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
			} ,300);
        };
    };

    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
};