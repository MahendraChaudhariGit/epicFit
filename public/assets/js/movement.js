
$(document).ready(function(){
	/* Start: movement step setup */
		$('#openMovementModal').click(function(e){
			var form = $('#movement-steps-form');
			var formData={};
			var stepsVal = [];
			var i=0;

			clearNotific('amountOverFlow');
			$(".moveStep").each(function() {
				if(this.checked){
			    	stepsVal[i] = $(this).val();
			    	i++;
				}
			});
			formData['client_id']=location.href.substr(location.href.lastIndexOf('/') + 1);
			formData['steps'] = stepsVal;
			if (stepsVal.length !== 0) {
				$.ajax({
	                url : public_url+'clients/movement/steps',
	                type : 'POST',
	                data : formData,
	                success : function(response) {
	                   	var data = JSON.parse(response);
	                   	if(data.status == "updated"){
	                   		//$('#movementStepSetupModal').modal("hide");
	                   		//$('input[name="noOfStep"]').val(stepsVal.length);
	                   		no_ofstep = stepsVal.length;
							if(no_ofstep == 1){
								finishBtnShowHide(no_ofstep);
							}
	                   		$('input[name="stepName"]').val(stepsVal);
	                   		if(createMovementStep(stepsVal)){
	                   			FormWizardMovement.init('#movementWizard');
	                   	 		$('#movementModal').modal("show");
	                   		}
	                	}
	                },
        		});
			}
			else{
				showNotific(prepareNotific('error', "Select at least one step.", 'amountOverFlow'), form)
                    return false;
			}
		});
	/* End: movement step setup */

	/* Start: Edit movement */
		$('.movement-edit').click(function(e){
			e.preventDefault();
			move_modal.find(".save-draft").prop('disabled',false)
			var $this = $(this),
				modal = $('#movementModal'),
				form = modal.find('#movement-form'),
				movementId = $this.data('movementid'),
				steps = [];

			if(typeof movementId != 'undefined' && movementId){
				$.ajax({
	                url : public_url+'clients/movement/edit/'+movementId,
	                type : 'GET',
	                data : {},
	                success : function(response) {
	                   	var data = JSON.parse(response);
						   console.log(response,data);
	                   	if(data.status == "success"){
							//    $(".save-draft").hide();
							no_ofstep = data.step_name.length;
							if(no_ofstep == 1){
								finishBtnShowHide(no_ofstep);
							}
	                   		var i=0;
	                   		$.each(data.scoreVal, function(i, sc){
	                   			modal.find('input[name="'+i+'StepVal"]').val(sc);
	                   		});

	                   		$.each(data.stepsData, function(key, value) {
							  	// steps[i] = key;
							  	$.each(value, function(index, name) {
									  if(name != ""){
										form.find('#'+name).attr('checked',true);
									  }
									if(value.front != null){
										$(".record-"+value.front_side).hide();
										$(".upload-"+value.front_side).hide();
										$(".remove-"+value.front_side).removeClass('hidden');
										$('fieldset[data-stepname="'+key+'"]').find('.frontVideo').parent('div').addClass('active');
										$('fieldset[data-stepname="'+key+'"]').find('.frontVideo').attr('src',public_url+'movement-videos/'+value.front);
									}
									if(value.back != null){
										$(".record-"+value.back_side).hide();
										$(".upload-"+value.back_side).hide();
										$(".remove-"+value.back_side).removeClass('hidden');
										$('fieldset[data-stepname="'+key+'"]').find('.sideVideo').parent('div').addClass('active');
										$('fieldset[data-stepname="'+key+'"]').find('.sideVideo').attr('src',public_url+'movement-videos/'+value.back);
									}
									
								});
								// i++;
							});

							$.each(data.step_name, function(key, value) {
								steps[i] = value;
								i++;
							});

							$.each(data.notes, function(key, value) {
								if(key != null){
							  		form.find('#'+key).val(value);
								}
							});
							//GScore = data.score;
	                   		modal.find('#scoreCard').text(data.score);
	                   		no_ofstep = steps.length;
	                   		//form.find('input[name="noOfStep"]').val(steps.length);
	                   		form.find('input[name="stepName"]').val(steps);
	                   		form.find('input[name="movementId"]').val(movementId);
	                   		if(createMovementStep(steps)){
	                   			FormWizardMovement.init('#movementWizard');
	                   	 		modal.modal("show");
	                   		}
	                	}
	                },
        		});
			}
		})
	/* End: Edit movement */

	/* Start: Movement step setup modal show */
		$("#movementStepSetupModal").on('show.bs.modal', function () {
			clearNotific('amountOverFlow');
			var $this = $(this);
				stepsArray=[],
				stepsName = $this.find('input[name="stepsNameArray"]').val();
				if(stepsName != ''){
					stepsArray = stepsName.split(',');
				 	$.each(stepsArray, function(index, name){
				 		$this.find('#'+name+'Step').attr('checked',true);
				 	})
				}
				/*$this.find(':checkbox').each(function(){
					var $checkbox = $(this);
					 	$checkbox.attr('checked',true);
				})*/
		});
	/* End: Movement step setup modal show */

	/* Start: Select all movement step button */
		$('.select-all-movement-steps').click(function(e){
			e.preventDefault();
			var modal = $("#movementStepSetupModal");
			modal.find(':checkbox').each(function(){
				var $checkbox = $(this);
				 	$checkbox.attr('checked',true);
			})
		})
	/* End : Select all movement step button */

	/* Start: Movement step setup modal hide */
		$("#movementStepSetupModal").on('hide.bs.modal', function () {
			var $this = $(this);
			$this.find(':checkbox').attr('checked',false);
		})
	/* End: Movement step setup modal hide */
	
})

$(document).on('click',".close",function() {
	window.location.reload();
})
$(document).on('click',".close-modal",function() {
	window.location.reload();
})

/* Start: Create movement random step function */
	function createMovementStep(stepName){
		var flag = true;
		var hiddenHtml = '';
		var stepHead = $('#stepHedding');
		stepHead.empty();
		$('.move-content').hide();
		var i = 1;
		no_ofstep = stepName.length;
		$.each(stepName, function(index, step){
			if(step == 'Squat')
				$('.step-Squat').removeClass('hidden');
		 	if(flag){
		 		hiddenHtml += "<li><a href='#movement-step-"+mapArray[step]+"' class='selected' isdone='1' rel='1' id='m-stp-1' data-stepnum='"+mapArray[step]+"'><div class='stepNumber'>"+i+"</div><span class='stepDesc' data-stepname='"+step+"'><small> "+step+" </small></span></a></li>";
		 		$('.step-'+step).show();
		 		flag = false;
		 	}
		 	else{
		 		hiddenHtml += "<li><a href='#movement-step-"+mapArray[step]+"' class='disabled' isdone='0' rel='"+i+"' id='m-stp-"+i+"' data-stepnum='"+mapArray[step]+"'><div class='stepNumber'>"+i+"</div><span class='stepDesc' data-stepname='"+step+"'> <small> "+step+" </small></span></a></li>";
		 	}
		 	i++;                  			
		})
		stepHead.append(hiddenHtml);
		return true;
	}
/* End: Create movement random step function */

var video;
var clientId = $('input[name="client_id"]').val();
console.log(video);

var timeout = 10; // 10 seconds
function roundRect(ctx, x, y, width, height, radius, fill, stroke) {
    if (typeof stroke == 'undefined') {
        stroke = true;
    }
    if (typeof radius === 'undefined') {
        radius = 5;
    }
    if (typeof radius === 'number') {
        radius = {
            tl: radius,
            tr: radius,
            br: radius,
            bl: radius
        };
    } else {
        var defaultRadius = {
            tl: 0,
            tr: 0,
            br: 0,
            bl: 0
        };
        for (var side in defaultRadius) {
            radius[side] = radius[side] || defaultRadius[side];
        }
    }
    ctx.beginPath();
    ctx.moveTo(x + radius.tl, y);
    ctx.lineTo(x + width - radius.tr, y);
    ctx.quadraticCurveTo(x + width, y, x + width, y + radius.tr);
    ctx.lineTo(x + width, y + height - radius.br);
    ctx.quadraticCurveTo(x + width, y + height, x + width - radius.br, y + height);
    ctx.lineTo(x + radius.bl, y + height);
    ctx.quadraticCurveTo(x, y + height, x, y + height - radius.bl);
    ctx.lineTo(x, y + radius.tl);
    ctx.quadraticCurveTo(x, y, x + radius.tl, y);
    ctx.closePath();
    if (fill) {
        ctx.fill();
    }
    if (stroke) {
        ctx.stroke();
    }

}
function captureCamera(callback) {
    console.log('hii',DetectRTC.videoInputDevices);
    var streams = [];
    var donotDuplicateDevices = {};
    DetectRTC.videoInputDevices.forEach(function(device, idx) {
        console.log(device,idx);
     if((DetectRTC.videoInputDevices.length == 2 && idx == 1) || DetectRTC.videoInputDevices.length == 1){

        navigator.mediaDevices.getUserMedia({
             audio: true,
            video: {
                mandatory: {},
                optional: [{
                    sourceId: device.id
                }]
            }
        }).then(function(stream) {
            if (!donotDuplicateDevices[device.id]) {
                donotDuplicateDevices[device.id] = true;

                // on-video-render:
                // called as soon as this video stream is drawn (painted or recorded) on canvas2d surface
                stream.onRender = function(context, x, y, width, height) {
                    context.font = '20px Georgia';

                   
                };

                streams.push(stream);
            }

            allCameraStreams.push(stream);

            if (idx == DetectRTC.videoInputDevices.length - 1) {
                callback(streams);
            }
        }).catch(function(e) {
            console.error(e);
        });
     }
        
    })
}

function stopRecordingCallback() {
	var data='';
	
		var blob = recorder.getBlob();

		video.muted = false;
console.log(allCameraStreams);
		allCameraStreams.forEach(function(stream) {
			stream.getTracks().forEach(function(track) {
			    console.log('ji');
				track.stop();
			});
		});
        
		video.srcObject = null;
		video.src = URL.createObjectURL(blob);
	
   
	var blob = recorder.getBlob();
	var fileName = 'video.webm';
	var file = new File([blob], fileName, {
        type: 'video/webm'
	});
    var reader = new FileReader();
	reader.readAsDataURL(blob); 
	reader.onloadend = function() {
		var base64data = reader.result;                
		$.post(public_url+'save/record-video', {data:base64data}, function(data){
			if(data){
				step = element.closest('.move-content');
				val = step.find('.viewTab li.active a').text();
				console.log(data,val);

				if(val == 'Front video'){
					step.find('.front').val(data);

				}else if(val == 'Side video'){
					step.find('.side').val(data);
				}
				video.play();
				// recorder.camera.stop();
				// recorder.destroy();
				recorder = null;
			}
		});
	}
}

var recorder; // globally accessible
var element;
var allCameraStreams = [];

DetectRTC.load(function() {
    
})
$('.btn-start-recording').on('click',function() {
	element = $(this);
	element.attr('disabled',true);
	video = element.closest('div').find('.recordedVideo')[0];
	video.play();
	console.log(video);
    captureCamera(function(camera) {
        video.muted = true;
        video.volume = 0;
        // video.srcObject = camera;

        recorder = RecordRTC(camera, {
            type: 'video',
            mimeType: 'video/webm',
			videoBitsPerSecond: 204800000,
            previewStream: function(s) {
               
                video.srcObject = s;
            }
        });
        recorder.startRecording();

        // release camera on stopRecording
        recorder.camera = camera;

		element.closest('div').find('.btn-stop-recording').attr('disabled',false);
    });
});

$('.btn-stop-recording').on('click', function() {
	var upload_btn_hide = $(this).data('hide');
	$(".record-"+upload_btn_hide).hide();
	$(".upload-"+upload_btn_hide).hide();
	$(".remove-"+upload_btn_hide).removeClass('hidden');
    $(this).attr('disabled',true);
	$(this).closest('div').find('.btn-start-recording').attr('disabled',false);
	move_modal.find(".save-draft").prop('disabled',false)
    recorder.stopRecording(stopRecordingCallback);
});


// $('.uploadVideo').on('click',function(){
// 	var fileToUpload = $(this).closest('div').find('input').prop('files');
// 	console.log(fileToUpload);
// 	// $.post(public_url+'video/save', {fileToUpload}, function(data){
// 	// 	if(data){
// 	// 		step =  $(this).closest('.move-content');
// 	// 		val = step.find('.viewTab li.active a').text();
// 	// 		console.log(data,val);

// 	// 		if(val == 'Front video'){
// 	// 			step.find('.front').val(data);

// 	// 		}else if(val == 'Side video'){
// 	// 			step.find('.side').val(data);
// 	// 		}
// 	// 		// recorder.camera.stop();
// 	// 		// recorder.destroy();
// 	// 		// recorder = null;
// 	// 	}
// 	// });
// 	$.ajax({
//         url: public_url+'video/save', 
//         type: 'POST',
//         data: fileToUpload,
//         success: function(data) {
//             console.log(data);
//         }
//     });

// })

function fileSelectHandlerVideo(elem,hide_btn) {
	$(".record-"+hide_btn).hide();
	$(".upload-"+hide_btn).hide();
	$(".remove-"+hide_btn).removeClass('hidden');
	toggleWaitShield('show');
	var fileInput = elem.files[0];
	var fileUrl = window.URL.createObjectURL(elem.files[0]);
	
	var oFile = elem.files[0];
	var form_data = new FormData();                  
	form_data.append('fileToUpload', oFile);
	// form_data.append('source', 'actvityVideo');

	// var file_data = $('#fileToUpload').val();

	// $.ajaxSetup({
	// 	headers: {
	// 		'X-CSRF-Token': $('meta[name=_token]').attr('content')
	// 	}
	// });

	$.ajax({
		url: public_url+'save/record-video', // point to server-side PHP script
		data: form_data,
		dataType: 'text', 
		type: 'POST',
		contentType: false, // The content type used when sending data to the server.
		cache: false, // To unable request pages to be cached
		processData: false,
		success: function(data) {
			move_modal.find(".save-draft").prop('disabled',false)
			step = $(elem).closest('.move-content');
				val = step.find('.viewTab li.active a').text();
				uploadV = step.find('.viewTab li.active');
				console.log(data,val);

				if(val == 'Front video'){
					step.find('.front').val(data);
					uploadV.find('button.uploadVideoNew').hide();

				}else if(val == 'Side video'){
					step.find('.side').val(data);
					uploadV.find('button.uploadVideoNew').hide();
				}
				$(elem).closest('div').find('video').attr("src", fileUrl);
				$(elem).closest('div').find('video')[0].play();
		
		   toggleWaitShield('hide');
		}
	});     
}

function removeVideo(elem,remove_video) {
	$("#record-"+remove_video).find('video').removeAttr('src');
	$("#upload-"+remove_video).find('video').attr('src','');
	$("#upload-"+remove_video).find('input[type=file]').val('');
	var remove_value = $(elem).closest('.move-content');
	var value = remove_video.split('-');
	if(value[0] == 'side'){
		remove_value.find('.side').val('null');
	}else{
		remove_value.find('.front').val('null');
	}
	
	$("#record-"+remove_video).removeClass('active');
	$("#upload-"+remove_video).removeClass('active');
	$(elem).closest('.videoTab').find('.uploadVideoNew').show();
	$(".record-"+remove_video).show();
	$(".upload-"+remove_video).show();
	$(".remove-"+remove_video).addClass('hidden');
}

var viewTab;
$('.viewTab a').on('click',function(){
	viewTab = $(this).data('side')
	
	// console.log($(this).data('side'),recordedVideo,uploadVideo);
	if($(this).data('side') == 'front'){
		var recordedVideo =  $($(this).attr('href')).find('.recordedVideo')[0];
		var uploadVideo =  $($(this).attr('href')).find('.uploadVideo')[0];  
		uploadVideo.currentTime = 0;
		recordedVideo.currentTime = 0; 
	   	var value = $(this).attr('href').split('-');
		console.log(value);
		let id = "#side-"+value[1];
		var recordedSideVideo =  $(id).find('.recordedVideo')[0];
		var uploadSideVideo =  $(id).find('.uploadVideo')[0];
		recordedSideVideo.pause();
		uploadSideVideo.pause();
        uploadVideo.play();   
		recordedVideo.play(); 
	}else if($(this).data('side') == 'side'){
		var recordedVideo =  $($(this).attr('href')).find('.recordedVideo')[0];
		var uploadVideo =  $($(this).attr('href')).find('.uploadVideo')[0];  
		uploadVideo.currentTime = 0;
		recordedVideo.currentTime = 0; 
		var value = $(this).attr('href').split('-');
		console.log(value);
		let id = "#front-"+value[1];
		var recordedFrontVideo =  $(id).find('.recordedVideo')[0];
		var uploadFrontVideo =  $(id).find('.uploadVideo')[0];
		recordedFrontVideo.pause();
		uploadFrontVideo.pause();
		uploadVideo.play();   
		recordedVideo.play(); 
	}else if($(this).data('side') == 'image'){
		var value = $(this).attr('href').split('-');
		console.log("#image-"+value[1])
		if(value[1] == 'view'){
			var video = 'video';
		}if(value[1] == 'view1'){
			var video = 'video1';
		}if(value[1] == 'view2'){
			var video = 'video2';
		}if(value[1] == 'view3'){
			var video = 'video3';
		}if(value[1] == 'view4'){
			var video = 'video4';
		}if(value[1] == 'view5'){
			var video = 'video5';
		}
		console.log("#front-"+video);
		$("#front-"+video).find('.recordedVideo')[0].pause();
		$("#front-"+video).find('.uploadVideo')[0].pause();
		$("#side-"+video).find('.recordedVideo')[0].pause();
		$("#side-"+video).find('.uploadVideo')[0].pause();
	}
})

$('.videoTab li a').on('click',function(){
	
	if(viewTab == 'front'){
		if($(this).data('video') == 'record'){
			var recordedVideo =  $($(this).attr('href')).find('.recordedVideo')[0];
			recordedVideo.currentTime = 0;
			recordedVideo.play();
			var value = $(this).attr('href').split('-');
			 let id = "#upload-"+value[1];
			 console.log(id)
			 id.currentTime = 0;
			 console.log($(id).find('.uploadVideo')[0])
			 $(id).find('.uploadVideo')[0].pause();
		 }else if($(this).data('video') == 'upload'){
			var uploadVideo =  $($(this).attr('href')).find('.uploadVideo')[0];
			uploadVideo.currentTime = 0;
			uploadVideo.play();
			 var value = $(this).attr('href').split('-');
			 let id = "#record-"+value[1];
			 console.log(id)
			 id.currentTime = 0; 
			 console.log($(id).find('.recordedVideo')[0])
			 $(id).find('.recordedVideo')[0].pause();
		 }
	}else if(viewTab == 'side'){
		if($(this).data('video') == 'record'){
			var recordedVideo =  $($(this).attr('href')).find('.recordedVideo')[0];
			recordedVideo.currentTime = 0;
			recordedVideo.play();
			var value = $(this).attr('href').split('-');
			let id = "#upload-side-"+value[2];
			console.log(id)
			id.currentTime = 0;
			console.log($(id).find('.uploadVideo')[0])
			$(id).find('.uploadVideo')[0].pause();
		 }else if($(this).data('video') == 'upload'){
			var uploadVideo =  $($(this).attr('href')).find('.uploadVideo')[0];
			uploadVideo.currentTime = 0;
			uploadVideo.play();
			var value = $(this).attr('href').split('-');
			let id = "#record-side-"+value[2];
			console.log(id)
			id.currentTime = 0; 
			console.log($(id).find('.recordedVideo')[0])
			$(id).find('.recordedVideo')[0].pause();
		 }
	}
	
})

$('.uploadVideoNew').on('click',function(){
	var element = $(this).closest('.tab-pane');
	var active =element.find('.tab-pane.active');
	active.removeClass('active');
	var activeTab = element.find('.uploadTabActive');
	activeTab.addClass('active');
	activeTab.find('input').trigger('click');
	console.log(element,activeTab);
})
