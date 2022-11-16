$(document).on('click','.add-friend',function(){
    var add_client_id = $(this).data('client-id');
    swal({
        type: 'warning',
        title: 'Are you sure you want to send friend request?',
        allowOutsideClick: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        cancelButtonText: "No"
    }, 
    function(isConfirm){
        if(isConfirm){
            // toggleWaitShield('show');

            $.get(public_url+'social/add/friend/'+add_client_id, function(resp){
                // var resp = JSON.parse(resp);
                if(resp.status = 'success'){
                    swal({
                        type: 'success',
                        title: resp.message,
                        allowOutsideClick: true,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            window.location.reload();
                        }
                    });
                }
                // toggleWaitShield("hide");
            });
        }
    });
})

$(document).on('click','.cancel-friend',function(){
    var add_client_id = $(this).data('client-id');
    var message = $(this).data('message');
    swal({
        type: 'warning',
        title: message,
        allowOutsideClick: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        cancelButtonText: "No"
    }, 
    function(isConfirm){
        if(isConfirm){
            $.get(public_url+'social/cancel/friend/'+add_client_id, function(resp){
                if(resp.status = 'success'){
                    swal({
                        type: 'success',
                        title: resp.message,
                        allowOutsideClick: true,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            if($('.cancel-friend').hasClass('unfriend')){
                                var count_friend = $('.my-friend span').html();
                                count_friend = count_friend -1;
                                $('#send-friend-'+ add_client_id).remove();
                                $('.my-friend span').html(count_friend);
                                $('.friend_count').html(count_friend);
                            } else {
                                var count = $('.send-count').html();
                                count = count - 1;
                                $('#send-friend-'+ add_client_id).remove();
                                $('.send-count').html(count);
                            }
                        
                            // window.location.reload();
                        }
                    });
                }
                // toggleWaitShield("hide");
            });
        }
    });
})

$(document).on('click','.confirm-friend',function(){
    var count = $('.request-count').html();
    var add_client_id = $(this).data('client-id');
    swal({
        type: 'warning',
        title: 'Are you sure you want to accept this friend request?',
        allowOutsideClick: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        cancelButtonText: "No"
    }, 
    function(isConfirm){
        if(isConfirm){
            // toggleWaitShield('show');

            $.get(public_url+'social/confirm/friend/'+add_client_id, function(resp){
                // var resp = JSON.parse(resp);
                if(resp.status = 'success'){
                    swal({
                        type: 'success',
                        title: resp.message,
                        allowOutsideClick: true,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            count = count - 1;
                            $('#request-friend-'+ add_client_id).remove();
                            $('.request-count').html(count);
                            // window.location.reload();
                        }
                    });
                }
                // toggleWaitShield("hide");
            });
        }
    });
})

$(document).on('click','.reject-friend',function(){
    // request-count
    var count = $('.request-count').html();
    var add_client_id = $(this).data('client-id');
    var message = $(this).data('message');
    swal({
        type: 'warning',
        title: message,
        allowOutsideClick: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        cancelButtonText: "No"
    }, 
    function(isConfirm){
        if(isConfirm){
            $.get(public_url+'social/reject/friend/'+add_client_id, function(resp){
                if(resp.status = 'success'){
                    swal({
                        type: 'success',
                        title: resp.message,
                        allowOutsideClick: true,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){

                            count = count - 1;
                            $('#request-friend-'+ add_client_id).remove();
                            $('.request-count').html(count);
                            // window.location.reload();
                        }
                    });
                }
                // toggleWaitShield("hide");
            });
        }
    });
})

$(document).on("keyup",".search-from-all-my-friends",function(event){
    var search = $(this).val();
    $.post(public_url+'social/filter/my/friend', {'search':search}, function(resp){
        $(".all-my-friends").html(resp);
        // $(".search-from-all-my-friends").val(search);
    });
})

$(document).on("keyup",".search-from-requested-friends",function(event){
    var search = $(this).val();
    $.post(public_url+'social/filter/requested/friend', {'search':search}, function(resp){
        $("#filter-requested-friends").html(resp);
    });
})

$(document).on("keyup",".search-from-sended-friends",function(event){
    var search = $(this).val();
    $.post(public_url+'social/filter/sended/friend', {'search':search}, function(resp){
        $("#filter-sended-friends").html(resp);
    });
})

$(document).on('click','.my-photo',function(){
    pauseVideo();
    $(".main-post-div").addClass('hidden');
    $(".my-video-div").addClass('hidden');
    $("#message-div").hide();
    $(".my-friend-div").addClass('hidden');
    $(".my-photo-div").removeClass('hidden');
})
$(document).on('click','.my-video',function(){
    pauseVideo();
    $(".main-post-div").addClass('hidden');
    $("#message-div").hide();
    $(".my-photo-div").addClass('hidden');
    $(".my-friend-div").addClass('hidden');
    $(".my-video-div").removeClass('hidden');
    
})
// $(document).on('click','.my-post',function(){
//     pauseVideo();
//     $(".my-video-div").addClass('hidden');
//     $(".my-photo-div").addClass('hidden');
//     $("#message-div").hide();
//     $(".my-friend-div").addClass('hidden');
//     $(".main-post-div").removeClass('hidden');
// })
$(document).on('click','.my-friend',function(){
    pauseVideo();
    $(".my-video-div").addClass('hidden');
    $(".my-photo-div").addClass('hidden');
    $("#message-div").hide();
    $(".main-post-div").addClass('hidden');
    $(".my-friend-div").removeClass('hidden');
})
function pauseVideo() {
    var videos = document.getElementsByClassName('video-status');
    for (var i = 0; i < videos.length; i++) {
        videos[i].pause();
    }
}
$('.video-status').attr('controls',false)
$(document).on('click','.video-status',function() {
    if($('.video-status').hasClass('active')){
        $('.video-status').removeClass('active');
        $('.video-status').addClass('inactive');
        $('.video-status').attr('controls',false);
    }
    $(this).removeClass('inactive');
    $(this).addClass('active');
    $(this).attr('controls',true);
    var videos = document.getElementsByClassName('video-status inactive');
    for (var i = 0; i < videos.length; i++) {
        videos[i].pause();
    }
    $(this).paused ? $(this).play() : $(this).pause();
 })
 $('.video-status1').attr('controls',false)
$(document).on('mouseover','.video-status1',function() {
    if($('.video-status1').hasClass('active')){
        $('.video-status1').removeClass('active');
        $('.video-status1').addClass('inactive');
        $('.video-status1').attr('controls',false);
    }
    $(this).removeClass('inactive');
    $(this).addClass('active');
    $(this).attr('controls',true);
    var videos = document.getElementsByClassName('video-status1 inactive');
    for (var i = 0; i < videos.length; i++) {
        videos[i].pause();
    }
    $(this).paused ? $(this).play() : $(this).pause();
 })

 var cover_image;
 function fileSelectHandlerNew(elem){
	// get selected file
	var oFile = elem.files[0];

	var ifCroppedImgSaved = false,
		public_url = $('meta[name="public_url"]').attr('content');

	var formGroup = $(elem).closest('.upload-group')
	prePhotoName = formGroup.find('input[name="prePhotoName"]');
	entityIdVal = formGroup.find('input[name="entityId"]').val();
	cover_image = formGroup.find('input[name="cover_image"]').val();
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
            $(".cover_input").val("");
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
        // alert(`${image.naturalWidth} x ${image.naturalHeight}`);
        //  alert(`${image.width} x ${image.height}`);
        // alert(`${image.width()} x ${image.height()}`);
        if(cover_image != undefined){
            // width =  `${image.width}` * 3;
            // height = 220;
            // cropper = new Cropper(image, {
            //     viewMode: 3,
            //     dragMode: 'move',
            //     //  autoCropArea: 0.65,
            //      autoCropArea: 0,
            //     restore: false,
            //     guides: false,
            //     center: false,
            //     highlight: false,
            //     // zoomable:false,
            // //    cropBoxMovable: false,
            //    cropBoxResizable: false,
            // // toggleDragModeOnDblclick: false,
            //     imageSmoothingEnabled: true,
            //     imageSmoothingQuality: 'high',
            //     aspectRatio: 851/315,
            //         data:{ //define cropbox size
            //          width: width,
            //          height: height,
            //         },
                
            //     ready: function () {
            //         //Should set crop box data first here
            //         //   cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
            //         cropper.setCropBoxData({ width: width, height: height }).setCanvasData(canvasData);
            //       },
            //   });
            cropper = new Cropper(image, {
                autoCropArea: 0.5,
                ready: function() {
                    cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                //   cropper.setCropBoxData({ width: width, height: height }).setCanvasData(canvasData);

                },
                viewMode: 2,
                // autoCropArea: 1,
                // aspectRatio: 1,
                aspectRatio: 2.75
            //    aspectRatio: 16/9
    
            });
        
           
        }else{
            $("#cropperModal").addClass("add-css-for-dp");
            width = 250;
            height = 250;
            cropper = new Cropper(image, {
                viewMode: 0,
                dragMode: 'move',
                //  autoCropArea: 0.65,
                autoCropArea: 0,
                restore: false,
                guides: false,
                modal:true,
                center: false,
                highlight: false,
                // zoomable:false,
               cropBoxMovable: false,
               cropBoxResizable: false,
            // toggleDragModeOnDblclick: false,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
              
                ready: function () {
                    //Should set crop box data first here
                    //   cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                    // $image.cropper("setCropBoxData", { left: 0, top: 0, width: 160, height: 80 });
                    cropper.setCropBoxData({left: 390, top: 27, width: width, height: height }).setCanvasData(canvasData);
                  },
              });
        
        }

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
                if(cover_image != undefined){
                    $(".preview_cover_image").prop('src', public_url+'uploads/thumb_'+response);
                }else{
                    previewPics.prop('src', public_url+'uploads/thumb_'+response);
                    $(".preview_profile_image").prop('src', public_url+'uploads/thumb_'+response);
                }
				
				if(previewPics.hasClass('hidden'))
					previewPics.removeClass('hidden');
				prePhotoName.val(response);
				formData = {};
				formData['id'] = entityIdVal;
				formData['photoName'] = response;
                if(cover_image != undefined){
				formData['cover_image'] = cover_image;
                }
                $('#waitingShield').removeClass('hidden');
				$.ajax({
					url: public_url+'client/photo/save',
					data: formData,                         
					method: 'POST',
                    success: function(response){ 
                        $('#waitingShield').addClass('hidden');
                        cover_image = undefined;
                        // window.location.reload();
                        $("#cropperModal").removeClass("add-css-for-dp");
                          
                    }
				});
			  }
		  });
	  })
	}).on('hidden.bs.modal', function () {
        // $('#waitingShield').addClass('hidden');
	  cropBoxData = cropper.getCropBoxData();
	  canvasData = cropper.getCanvasData();
	  cropper.destroy();
	});
  });

  $(document).on('click','.user-privacy',function(){
    var client_id = $(this).data('client-id');
    var privacy = $(this).data('privacy');
    swal({
        type: 'warning',
        title: 'Are you sure you want to show profile to '+privacy+'?',
        allowOutsideClick: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        cancelButtonText: "No"
    }, 
    function(isConfirm){
        if(isConfirm){
            $.post(public_url+'social/privacy',{client_id:client_id,privacy:privacy}, function(resp){
                
                if(resp.status = 'success'){
                    swal({
                        type: 'success',
                        title: resp.message,
                        allowOutsideClick: true,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff4401',
                    });
                }
                
            });
        }
    });
})
