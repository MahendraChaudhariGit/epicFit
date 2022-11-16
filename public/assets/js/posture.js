var can = '';
var xData = '';
var yData = '';
var x1 = '';
var y1 = '';
var x2 = '';
var y2 = '';
var midX1,midX2,midY1,midY2,midLine,topCordY,topCordX;

var alertMsg ={ 
    rightEye: {nextAlert: 'leftEye', msg: 'Place a marker on a left eye',img:public_url +'assets/images/left-eye.png' },
    leftEye: {  nextAlert: 'nose', msg: 'Mark the center upper lip',img:public_url +'assets/images/nose.png' },
    nose: {  nextAlert: 'neck', msg: 'Mark the EPISTERNIAL NOTCH',img:public_url +'assets/images/neck.png' },
    neck: {  nextAlert: 'rightShoulder', msg: 'Place a marker on the RIGHT AC JOINT',img:public_url +'assets/images/right-shoulder.png' },
    rightShoulder: {  nextAlert: 'leftShoulder', msg: 'Place a marker on the LEFT AC JOINT',img:public_url +'assets/images/left-shoulder.png' },
    leftShoulder: { nextAlert: 'rightChest', msg: 'Mark the RIGHT LATERAL RIB T8 LEVEL',img:public_url +'assets/images/right-chest.png' },
    rightChest: {  nextAlert: 'leftChest', msg: 'Mark the LEFT LATERAL RIB T8 LEVEL',img:public_url +'assets/images/left-chest.png' },
    leftChest: { nextAlert: 'rightWaist', msg: 'Mark the RIGHT ASIS approximation',img:public_url +'assets/images/right-waist.png' },
    rightWaist: {  nextAlert: 'leftWaist', msg: 'Mark the LEFT ASIS approximation',img:public_url +'assets/images/left-waist.png' },
    leftWaist: {  nextAlert: 'rightAnkle', msg: 'Place a marker on the RIGHT ANKLE center',img:public_url +'assets/images/right-ankle.png' },
    rightAnkle: {  nextAlert: 'leftAnkle', msg: 'Place a marker on the LEFT ANKLE center',img:public_url +'assets/images/left-ankle.png' },
    leftAnkle: { nextAlert: '', msg: '',img:'' },
    sideEar: {  nextAlert: 'sideShoulder', msg: 'Cervical thoracic junction level of AC joint',img:public_url +'assets/images/side-shoulder.png' },
    sideShoulder: {  nextAlert: 'sideArm', msg: 'Place a marker on the HIP',img:public_url +'assets/images/side-arm.png' },
    sideArm: {  nextAlert: 'sideKnee', msg: 'Place a marker on the KNEE',img:public_url +'assets/images/side-knee.png' },
    sideKnee: {  nextAlert: 'sideAnkle', msg: 'Place a marker on the ANKLE',img:public_url +'assets/images/side-ankle.png' },
    sideAnkle: { nextAlert: '', msg: '',img:'' },
    backRightEar: {  nextAlert: 'backLeftEar', msg: 'Mark the left INFERIOR EAR LOBE',img:public_url +'assets/images/back-left-neck.png' },
    backLeftEar: {  nextAlert: 't1', msg: 'Please Click',img:public_url +'assets/images/t1.png' },
    t1: {  nextAlert: 't2', msg: 'Please Click',img:public_url +'assets/images/t2.png' },
    t2: {  nextAlert: 't3', msg:  'Please Click',img:public_url +'assets/images/t3.png' },

    t3: {  nextAlert: 't4', msg:  'Please Click ',img:public_url +'assets/images/t4.png' },
    t4: {  nextAlert: 'backRightJoint', msg:  'Place a marker on the back RIGHT AC JOINT',img:public_url +'assets/images/back-right-shoulder.png' },

    backRightJoint: {  nextAlert: 'backLeftJoint', msg: 'Place a marker on the back Left AC JOINT',img:public_url +'assets/images/back-left-shoulder.png' },
    backLeftJoint: {  nextAlert: 'backRightChest', msg: 'Please Click Next',img:public_url +'assets/images/back-right-chest.png' },
    backRightChest: {  nextAlert: 'backLeftChest', msg: 'Please Click Next',img:public_url +'assets/images/back-left-chest.png' },
    backLeftChest: {  nextAlert: 'backRightHip', msg: 'Please Click Next',img:public_url +'assets/images/back-right-west.png' },
    backRightHip: {  nextAlert: 'backLeftHip', msg: 'Please Click Next',img:public_url +'assets/images/back-left-west.png' },
    backLeftHip: {  nextAlert: 'backRightAnkle', msg: 'Place a marker on the Right ANKLE',img:public_url +'assets/images/back-right-ankle.png' },
    backRightAnkle: {  nextAlert: 'backLeftAnkle', msg: 'Place a marker on the Left ANKLE',img:public_url +'assets/images/back-left-ankle.png' },
    backLeftAnkle: { nextAlert: '', msg: '',img:'' },
    leftSideEar: {  nextAlert: 'leftSideShoulder', msg: 'Cervical thoracic junction level of AC joint',img:public_url +'assets/images/left-side-shoulder.png' },
    leftSideShoulder: {  nextAlert: 'leftSideArm', msg: 'Place a marker on the HIP',img:public_url +'assets/images/left-side-arm.png' },
    leftSideArm: {  nextAlert: 'leftSideKnee', msg: 'Place a marker on the KNEE',img:public_url +'assets/images/left-side-knee.png' },
    leftSideKnee: {  nextAlert: 'leftSideAnkle', msg: 'Place a marker on the ANKLE',img:public_url +'assets/images/left-side-ankle.png' },
    leftSideAnkle: { nextAlert: '', msg: '',img:'' },

};
// var client_id = '';
// var image_type = '';
var count_mark_point;
$(document).on('click',".gridLine",function(e) {
    count_mark_point++;
    var image_name = $(this).attr('data-image-name');
    if(image_name == 'image1'){
        var max_mark_point = 12;
    }else if(image_name == 'image2'){
        var max_mark_point = 5;
    }else if(image_name == 'image3'){
        var max_mark_point = 14;
    }else if(image_name == 'image4'){
        var max_mark_point = 5;
    }
    if(max_mark_point >= count_mark_point){
        var client_id = $('#canvasPic').attr('client-id');
        var posture_id = $('#canvasPic').attr('posture-id');
        var image_type = $('#canvasPic').attr('image-type');
        getPosition(e, client_id, image_type,posture_id);
        drawLine(e, client_id, image_type,posture_id);
    }
    
});

var pointSize = 4;

function getPosition(event, client_id, image_type,posture_id) {
    var canvas = document.getElementById("canvasPic");

    var rect = canvas.getBoundingClientRect();
    var x = event.clientX - rect.left;
    var y = event.clientY - rect.top;
    xData = x;
    yData = y;
    drawCoordinates(x, y, client_id, image_type,posture_id);
}

$('.gridLine').mousemove(function(e){
    var canv = document.getElementById("canvasPic");
    var ctx = canv.getContext("2d");
    UndoCanvas.enableUndo(ctx)
    
    var rect = canv.getBoundingClientRect();
    var x = e.clientX - rect.left;
    var y = e.clientY - rect.top;
    ctx.beginPath();
    ctx.arc(x, y, 2, 0, 2 * Math.PI);
    ctx.fillStyle = "#ff2626";
    ctx.fill();
    ctx.stroke();
    var imgData = ctx.getImageData(x-25, y-25,50, 50);
    var canvasDup = document.getElementById("canvasDup");
    var ctxd = canvasDup.getContext("2d");
    ctxd.putImageData(imgData, 0, 0,0,0,150,150);
    document.getElementById('zoomImage').src = canvasDup.toDataURL();
    ctx.undo();
    ctx.undo();
    
    })


function drawCoordinates(x, y, client_id, image_type,posture_id) {
    var ctx = document.getElementById("canvasPic").getContext("2d");
    can = ctx;
    ctx.fillStyle = "#ff2626"; // Red color
    ctx.beginPath();
    ctx.arc(x, y, pointSize, 0, Math.PI * 2, true);
    ctx.fill();
    var alertType = $('#posMsg').data('alert-type');
    let value = alertMsg[alertType];
    alertMsg[alertType].x1 = parseFloat(x);
    alertMsg[alertType].y1 = parseFloat(y);
    
    // console.log(x, y);
    $.ajax({
        method: 'Post',
        url: public_url + 'store/coordinates',
        data: {
            'client_id': client_id,
            'posture_id': posture_id,
            'image_type': image_type,
            'xPos': x,
            'yPos': y,
        }
    })
}


var clicks = 0;
var lastClick = [0, 0];

function setAlertMsg(alertType){
       let value = alertMsg[alertType];
            if(value.nextAlert == ''){
                if(alertType == 'leftAnkle'){
                    let leftAnkle = alertMsg['leftAnkle'];
                    let rightAnkle = alertMsg['rightAnkle'];
                    calculateMidPoint(leftAnkle,rightAnkle,'');
                    let leftEye = alertMsg['leftEye'];
                    let rightEye = alertMsg['rightEye'];
                    createGreenLine(leftEye,rightEye,midLine,'');
                }else if(alertType == 'sideAnkle'){
                    let sideKnee = alertMsg['sideKnee'];
                    calculateMidPoint(sideKnee,'','');
                    let sideEar = alertMsg['sideEar'];
                    let sideAnkle =alertMsg['sideAnkle'];
                    createGreenLine(sideEar,sideAnkle,'','');
                }else if(alertType == 'leftSideAnkle'){
                    let LeftSideKnee = alertMsg['leftSideKnee'];
                    calculateMidPoint(LeftSideKnee,'','');
                    let LeftSideEar = alertMsg['leftSideEar'];
                    let LeftSideAnkle =alertMsg['leftSideAnkle'];
                    createGreenLine(LeftSideEar,LeftSideAnkle,'','');
                }else if(alertType == 'backLeftAnkle'){
                    let backRightEar = alertMsg['backRightEar'];
                    let backLeftEar =alertMsg['backLeftEar'];
                    let backRightAnkle =alertMsg['backRightAnkle'];
                    let backLeftAnkle =alertMsg['backLeftAnkle'];
                    createGreenLine(backRightEar,backLeftEar,backRightAnkle,backLeftAnkle);
                }
                $('#posMsg').hide();
                $('.size_zoom_image').hide();

            }else{
                console.log(alertType);
                if(alertType == 'nose'){
                    let leftEye = alertMsg['leftEye'];
                    let rightEye = alertMsg['rightEye'];
                    calculateMidPoint(leftEye,rightEye,'');

                }else if(alertType == 'leftChest'){
                    let leftChest = alertMsg['leftChest'];
                    let rightChest = alertMsg['rightChest'];
                    let neck = alertMsg['neck'];

                    calculateMidPoint(leftChest,rightChest,neck);
                }else if(alertType == 'leftWaist'){
                    let leftWaist = alertMsg['leftWaist'];
                    let rightWaist = alertMsg['rightWaist'];
                    calculateMidPoint(leftWaist,rightWaist,'');

                }else if(alertType == 'sideArm'){
                    let sideShoulder = alertMsg['sideShoulder'];
                    calculateMidPoint(sideShoulder,'','');
                }else if(alertType == 'leftSideArm'){
                    let leftSideShoulder = alertMsg['leftSideShoulder'];
                    calculateMidPoint(leftSideShoulder,'','');
                }
                $('#posMsg').text(value.msg).show();
                $('#posMsg').data('alert-type',value.nextAlert);
                $('.size_zoom_image').show();
             
                $('.size_zoom_image').find('img').attr('src',value.img);
            }
        
    
}

function calculateSideInch(x,y,valueX,valueY){
    var data=[];
    a=valueX.y1-valueY.y1;
    b=valueY.x1-valueX.x1;
    c=valueY.x1 *(valueY.y1-valueX.y1);
    var d = ( a*valueX.x1 + b*valueX.y1 + c ) / ( Math.sqrt( a*a + b*b));
    console.log(x,y,valueX,valueY,d,a*a + b*b,a,b,c);
    // if(d<0){
    //     data[0] = 'left';
    // }else if(d>0){
    //     data[0] = 'right';
    // }
    // data[1] = d;
    // data[1] =d/25.4;
    if(isNaN(d)){
        return 0;
    }
    console.log(d);
    return d/25.4;

}
function calculateInch(x1,y1,x2,y2,x3,y3,x4,y4){
    var data=[];
    var inter = intersect(x1,y1,x2,y2,x3,y3,x4,y4);
    var mid = middlePoint(x3,y3,x4,y4);
   
    var d =((inter.x - x1)*(y2 - y1)) - ((inter.y - y1)*(x2 - x1));
    var xDist = inter.x - mid[0];
    var yDist = inter.y -mid[1];
    console.log(d);
    if(isNaN(d)){
        return 0;
    }
    if(d<0){
        // data[0] = 'left';
        var dist = Math.sqrt(xDist * xDist + yDist * yDist) * -1;
    }else{
        // data[0] = 'right';
        var dist = Math.sqrt(xDist * xDist + yDist * yDist);
    }
    // data[1]= dist/25.4;

    return dist/25.4;
}

function middlePoint(x1,y1,x2,y2) {
	var x = (x1+x2)/2;
    var y = (y1+y2)/2;


    //-- Return result
    return [x,y];
}

function intersect(x1, y1, x2, y2, x3, y3, x4, y4) {
    var ua, ub, denom = (y4 - y3)*(x2 - x1) - (x4 - x3)*(y2 - y1);
    if (denom == 0) {
        return null;
    }
    ua = ((x4 - x3)*(y1 - y3) - (y4 - y3)*(x1 - x3))/denom;
    ub = ((x2 - x1)*(y1 - y3) - (y2 - y1)*(x1 - x3))/denom;
    return {
        x: x1 + ua * (x2 - x1),
        y: y1 + ua * (y2 - y1),
        seg1: ua >= 0 && ua <= 1,
        seg2: ub >= 0 && ub <= 1
    };
  }

  function drawLine(e, client_id = '', image_type = '',posture_id = '') {
    context = can;
    var alertType = $('#posMsg').data('alert-type');
    setAlertMsg(alertType);

    if (clicks != 1) {
        x1= xData;
        y1 = yData;
        clicks++;
    } else {
        x2= xData;
        y2 = yData;
        var dy = y2 - y1;
        var dx = x2 - x1;
        console.log("theta",x1,x2,y1,y2,dx,dy);
        var theta = Math.atan2(dy, dx); // range (-PI, PI]
        theta *= 180 / Math.PI;
        $.ajax({
            method: 'Post',
            url: public_url + 'store/angle',
            data: {
                'client_id': client_id,
                'posture_id': posture_id,
                'image_type': image_type,
                'angle': theta,
            }
        })
        console.log(theta);
        var xDist = x2 - x1;
        var yDist = y2 -y1;
        var dist = Math.sqrt(xDist*xDist + yDist*yDist);
        context.beginPath();
        context.moveTo(lastClick[0], lastClick[1]);
        context.lineWidth = 1;
        context.lineTo(xData, yData, 6);

        context.strokeStyle = '#0e0be8';
        context.stroke();
        clicks = 0;

    }
    lastClick = [xData, yData];
};



public_url = $('meta[name="public_url"]').attr('content');
var image_name, prePhotoName, user_id,posture_id, previewPics, cropSelector,picfrom;

window.addEventListener('DOMContentLoaded', function() {
    var image = document.getElementById('imageCrop');
    var cropBoxData;
    var canvasData;
    var cropper;

    $('#cropperModal').on('shown.bs.modal', function() {
        image = document.getElementById('imageCrop');
        cropper = new Cropper(image, {
            autoCropArea: 0.5,
            ready: function() {
                //Should set crop box data first here
                cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
            },
            viewMode: 2,
            autoCropArea: 1,
            aspectRatio: 0.41

        });
        // public_url = $('meta[name="public_url"]').attr('content');

        // if (cropSelector)
        //     cropSelector = cropSelector.split(',');

        $(document).on('click','.cropImg',function() {
            var cropData = cropper.getData();
            var form_data = {};
            form_data['photoName'] = previewPics.val();
            form_data['widthScale'] = cropData.scaleX;
            form_data['x1'] = cropData.x;
            form_data['w'] = cropData.width;
            form_data['heightScale'] = cropData.scaleY;
            form_data['y1'] = cropData.y;
            form_data['h'] = cropData.height;
            $.ajax({
                url: public_url + 'posture/image',
                data: form_data,
                type: 'post',
                beforeSend: function(){
                    $('#waitingShield').removeClass('hidden');
                },
                success: function(response) {
                    $('#cropperModal').modal('hide');
                    previewPics.prop('src', public_url + 'posture-images/thumb_' + response);
                    if (previewPics.hasClass('hidden'))
                        previewPics.removeClass('hidden');
                    prePhotoName.val(response);
                    formData = {};
                    formData['client_id'] = user_id;
                    formData['posture_id'] = posture_id;
                    formData['photoName'] = response;
                    formData['image_name'] = image_name;
                    $.ajax({
                        url: public_url + 'save/posture/image',
                        data: formData,
                        method: 'POST',
                        beforeSend: function(){
                            $('#waitingShield').removeClass('hidden');
                        },
                        success:function(res) {
                            console.log(res.posture_id);
                            $(".delete-image").attr('data-posture-id',res.posture_id);
                            $(".delete-image").attr('data-image-name',image_name);
                            if(res.status == 'create'){
                                $('input[name="posture_id"]').val(res.posture_id);
                                $(".image-note").attr('data-posture-id',res.posture_id)
                                $("#posture-analysis").attr('data-posture-id',res.posture_id);
                            }
                            if(res.status == 'update'){

                            }
                            
                        },
                        complete: function(){
                            setTimeout(function() {
                                $('#waitingShield').addClass('hidden');
                            },2000);
                        }
                    });
                },
                complete: function(){
                    setTimeout(function() {
							$('#waitingShield').addClass('hidden');
						},2000);
                }
            });
        })
    }).on('hidden.bs.modal', function() {
        cropBoxData = cropper.getCropBoxData();
        canvasData = cropper.getCanvasData();
        cropper.destroy();
    });
});

var constraints = {
    video: true,
    facingMode: "environment"
};
Webcam.set({
    width: 320,
    height: 320,
    dest_width: 550,
    dest_height: 550,
    image_format: 'jpeg',
    jpeg_quality: 90,
    constraints: constraints,
});
$('#toggleCamera').on('click',function(){
    var cam =$(this).data('toggle');
    Webcam.reset( '#webcamera' );
        if(cam == 'environment'){
            $(this).empty('').text('Change to rear');
            $(this).data('toggle','user');
   var constraints = {
    video: true,
    facingMode: "user"
};
}else{
      $(this).empty('').text('Change to front');
      $(this).data('toggle','environment');
    var constraints = {
    video: true,
    facingMode: "environment"
};
}
   Webcam.set({
    width: 320,
    height: 320,
    dest_width: 531,
    dest_height: 531,
    image_format: 'jpeg',
    jpeg_quality: 90,
    constraints: constraints,
});
$(this).data('toggle','user');
        Webcam.attach('#webcamera');
})
$(document).on('click','.openWebcamera', function(e) {
    $('#webcamera-modal').modal('show');
    Webcam.attach('#webcamera');
    var formGroup = $(this).closest('.posture-button')
    image_name = formGroup.find('input[name="image_name"]').val();
    prePhotoName = formGroup.find('input[name="postureimage"]');
    user_id = formGroup.find('input[name="client_id"]').val();
    posture_id = formGroup.find('input[name="posture_id"]').val();
    previewPics = $('.' + image_name + '-posture-pre');
    picfrom = 'webcamera';
    
    // cropSelector = formGroup.find('input[name="posturecropSelector"]').val();
});


$(document).on('click','.takesnap', function() {
    Webcam.snap(function(data_uri) {
        $('#imageCrop').attr('src', data_uri);
        $.post(public_url + 'captcha/image', { data: data_uri,picfrom:picfrom }, function(file, response) {
            // $('#imageCrop').attr('src',data_uri);
            previewPics.val(file);
            $('#cropperModal').modal('show');
        });
        Webcam.reset();
        $('#webcamera-modal').modal('hide');
        $('#cropperModal').modal('show');
    });
});
$('.close-webcam').click(function() {
    Webcam.reset();
    $('#webcamera-modal').modal('hide');
})

var posture_image;

$(document).on('click','#posture-analysis', function() {
    
    var weight = $('#posture-analysis-modal').data('weight'),
    height =$('#posture-analysis-modal').data('height');
    if(weight == '' || weight == null || weight == undefined || height == null || height == '' || height ==undefined){
        $('#measurement-model').modal('show');
    }else{
        var client_id = $(this).attr('data-client-id');
        var posture_id = $(this).attr('data-posture-id');
        // var image_name = $(this).attr('data-image-name');
        var posture_mode = $(this).attr('data-posture-mode');
        $.ajax({
            url: public_url + "posture/analysis",
            method: 'post',
            data: {
                'client_id': client_id,
                // 'image_name': image_name,
                'posture_id':posture_id,
            },
            beforeSend: function(){
                $('#waitingShield').removeClass('hidden');
            },
            success: function(res) {
                console.log(res);
                if (res.status == true) {
                    $("#back-analysis").addClass('hidden');
                    $("#next-analysis").attr('data-image-name', res.next_image);
                    $("#next-analysis").attr('data-posture-mode', posture_mode);
                    $("#next-analysis").attr('data-client-id', res.client_id);
                    $("#next-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-client-id', res.client_id);
                    $("#reset-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-image-name', res.current_image);
                    $("#undo-analysis").attr('data-client-id', res.client_id);
                    $("#undo-analysis").attr('data-posture-id', res.posture_id);
                    $("#undo-analysis").attr('data-image-name', res.current_image);
                    $(".gridLine").attr('data-image-name', res.current_image);
                    
                    
                    $('#posture-analysis-modal').data('posture-image',res.image_name);
                    
                
                    $('#canvasPic').attr('client-id', res.client_id);
                    $('#canvasPic').attr('posture-id', res.posture_id);
                    $('#canvasPic').attr('image-type', res.current_image);
                
                
                    $('#posMsg').show();
                    // $("#posture-analysis-modal").find('.posture-pre').prop('src', public_url+'posture-images/thumb_'+res.image_name)
                    if (res.xPos != '' && res.yPos != '') {
                        $('#posMsg').hide();
                        // setPosition(res.xPos, res.yPos);

                    }
                    $("#posture-analysis-modal").data('view',res.view);

                    var canvas = document.getElementById('canvasPic');
                    var context = canvas.getContext('2d');
                    var imageObj = new Image();
                    var height = 531;
                    var width = 337;

                    imageObj.onload = function() {
                        var aspectRatio = this.width/this.height;
                        width = aspectRatio * height;
                        $('#posture-analysis-modal').find('.posture-d').css('width',width);
                        $('#canvasPic').attr('width', width);
                        $('#canvasPic').attr('height', 531);
                        $('#posture-analysis-modal').find('.tablenumber').css('width',width);
                        context.drawImage(imageObj, 0, 0, width, height);
                    };
                    if(posture_mode == 'edit'){
                        $("#undo-analysis").hide();
                        if(res.image_path != null && res.image_path != ''){
                            imageObj.src =public_url + 'uploads/' + res.image_path;
                        }else{
                            imageObj.src =public_url + 'posture-images/thumb_' + res.image_name;
                            $('#posture-analysis-modal').find('#reset-analysis').trigger('click');
                        }
                        console.log(res.xPos.split(',').length);
                    if (res.xPos != '' && res.yPos != '') {
                        count_mark_point = res.xPos.split(',').length;
                    }else{
                        count_mark_point = 0;
                    }
                    }else{
                        $('#posture-analysis-modal').find('#reset-analysis').trigger('click');
                        imageObj.src =public_url + 'posture-images/thumb_' + res.image_name;
                        $('#posture-analysis-modal').find('#reset-analysis').trigger('click');
                        count_mark_point = 0;
                    }
                    $("#posture-analysis-modal").show();
                    // posture_image = public_url+'posture-images/thumb_'+res.image_name;
                    // canvas();
                } else {

                }

            },
            complete: function(){
                setTimeout(function() {
                    $('#waitingShield').addClass('hidden');
                },2000);
            }

        })
    }

})

$(document).on('click',"#next-analysis", function() {
    var client_id = $(this).attr('data-client-id');
    var posture_id = $(this).attr('data-posture-id');
    var image_name = $(this).attr('data-image-name');
    var posture_mode = $(this).attr('data-posture-mode');
   
    var inchData =[];
    var image = $('#posture-analysis-modal').data('view');
    var weight = $('#posture-analysis-modal').data('weight'),
    weightUnit = $('#posture-analysis-modal').data('weight-unit'),
    height =$('#posture-analysis-modal').data('height'),
    heightUnit =$('#posture-analysis-modal').data('height-unit');
    gender =$('#posture-analysis-modal').data('gender');
    if(image == 'front'){
        let leftAnkle = alertMsg['leftAnkle'];
        let rightAnkle = alertMsg['rightAnkle'];
        var mid = middlePoint(leftAnkle.x1,leftAnkle.y1,rightAnkle.x1,rightAnkle.y1);
        nose=alertMsg['nose'];
        var head = {};
        head.x1 = mid[0];
        head.y1 =mid[1];
        inchData[0] = calculateSideInch(topCordX,topCordY,head,nose);
        console.log( inchData[0]);
        var greenLineX1 = mid[0],
        greenLineY1 = mid[1];
        let leftEye = alertMsg['leftEye'];
        let rightEye = alertMsg['rightEye'];
        var mid = middlePoint(leftEye.x1,leftEye.y1,rightEye.x1,rightEye.y1);
        var greenLineX2 = mid[0],
        greenLineY2 = mid[1],
        rightShoulder=alertMsg['rightShoulder'],
        leftShoulder=alertMsg['leftShoulder'];
      

        inchData[1] = calculateInch(greenLineX1,greenLineY1,greenLineX2,greenLineY2,rightShoulder.x1,rightShoulder.y1,leftShoulder.x1,leftShoulder.y1);
        rightChest=alertMsg['rightChest'],
        leftChest=alertMsg['leftChest'];
        inchData[2] = calculateInch(greenLineX1,greenLineY1,greenLineX2,greenLineY2,rightChest.x1,rightChest.y1,leftChest.x1,leftChest.y1);
      
        rightWaist=alertMsg['rightWaist'],
        leftWaist=alertMsg['leftWaist'];
        inchData[3] = calculateInch(greenLineX1,greenLineY1,greenLineX2,greenLineY2,rightWaist.x1,rightWaist.y1,leftWaist.x1,leftWaist.y1);

        
     }else if(image == 'right'){
   
        let sideAnkle = alertMsg['sideAnkle'];
        var sideEar=alertMsg['sideEar'];
        var sideShoulder=alertMsg['sideShoulder'];
        inchData[0] = calculateSideInch(topCordX,topCordY,sideAnkle,sideEar);

        inchData[1] = calculateSideInch(topCordX,topCordY,sideAnkle,sideShoulder);

        let sideArm=alertMsg['sideArm'];
        inchData[2] = calculateSideInch(topCordX,topCordY,sideAnkle,sideArm);  
        let knee=alertMsg['sideKnee'];
        inchData[3] = calculateSideInch(topCordX,topCordY,sideAnkle,knee); 
        if(weight != undefined && weight != ''){
            console.log(weightUnit);
            if(weightUnit == 'Metric'){
                weight = weight * 2.2046226218;
            }
            if(heightUnit == 'Imperial'){
               var h =  height.split('-');
               var ft = parseFloat(h[0] * 30.48);
               var inches= parseFloat(h[1] * 2.54);
               height = parseFloat(ft + inches).toFixed(1);
            }
            inchData[4] =  weight * .075;
            inchData[5] =   calculateHeadWeight(height,gender);
            
        }
     }else if(image == 'left'){
   
        let leftSideAnkle = alertMsg['leftSideAnkle'];
        var leftSideEar=alertMsg['leftSideEar'];
        var leftSideShoulder=alertMsg['leftSideShoulder'];
        inchData[0] = calculateSideInch(topCordX,topCordY,leftSideAnkle,leftSideEar);

        inchData[1] = calculateSideInch(topCordX,topCordY,leftSideAnkle,leftSideShoulder);

        let leftSideArm=alertMsg['leftSideArm'];
        inchData[2] = calculateSideInch(topCordX,topCordY,leftSideAnkle,leftSideArm);  
        let leftSideKnee=alertMsg['leftSideKnee'];
        inchData[3] = calculateSideInch(topCordX,topCordY,leftSideAnkle,leftSideKnee);
        if(weight != undefined && weight != ''){
            console.log(weightUnit);
            if(weightUnit == 'Metric'){
                weight = weight * 2.2046226218;
            }
            if(heightUnit == 'Imperial'){
                var h =  height.split('-');
                var ft = parseFloat(h[0] * 30.48);
                var inches= parseFloat(h[1] * 2.54);
                height = parseFloat(ft + inches).toFixed(1);
            }
            inchData[4] =  weight * .075;
            inchData[5] =   calculateHeadWeight(height,gender);    
            
        }
    }else if(image == 'back'){
        let backLeftAnkle = alertMsg['backLeftAnkle'];
        let backRightAnkle = alertMsg['backRightAnkle'];
        var mid = middlePoint(backLeftAnkle.x1,backLeftAnkle.y1,backRightAnkle.x1,backRightAnkle.y1);
        var head = {};
        head.x1 = mid[0];
        head.y1 =mid[1];
        nose=alertMsg['t1'];

        inchData[0] = calculateSideInch(topCordX,topCordY,head,nose);
        var greenLineX1 = mid[0],
        greenLineY1 = mid[1];
        let backLeftEar = alertMsg['backLeftEar'];
        let backRightEar = alertMsg['backRightEar'];
        var mid = middlePoint(backLeftEar.x1,backLeftEar.y1,backRightEar.x1,backRightEar.y1);
        var greenLineX2 = mid[0],
        greenLineY2 = mid[1],
        backRightJoint=alertMsg['backRightJoint'],
        backLeftJoint=alertMsg['backLeftJoint'];
        inchData[1] = calculateInch(greenLineX1,greenLineY1,greenLineX2,greenLineY2,backRightJoint.x1,backRightJoint.y1,backLeftJoint.x1,backLeftJoint.y1);
        backRightChest=alertMsg['backRightChest'],
        backLeftChest=alertMsg['backLeftChest'];
        inchData[2] = calculateInch(greenLineX1,greenLineY1,greenLineX2,greenLineY2,backRightChest.x1,backRightChest.y1,backLeftChest.x1,backLeftChest.y1);
      
        backRightHip=alertMsg['backRightHip'],
        backLeftHip=alertMsg['backLeftHip'];
        inchData[3] = calculateInch(greenLineX1,greenLineY1,greenLineX2,greenLineY2,backRightHip.x1,backRightHip.y1,backLeftHip.x1,backLeftHip.y1);
        
    }
     var canvas = document.querySelector('#canvasPic');
     var dataURL = canvas.toDataURL("image/jpeg", 1.0);
     console.log(inchData);

     
    $.ajax({
        url: public_url + "posture/analysis",
        method: 'post',
        // dataType: 'JSON',
        data: {
            'client_id': client_id,
            'posture_id': posture_id,
            'image_name': image_name,
            'data_url':dataURL,
            'image': image,
            'inch_data': inchData,
        },
        beforeSend: function(){
            $('#waitingShield').removeClass('hidden');
        },
        success: function(res) {
            if (res.status == true) {
                resetCanvas();
                $('#posture-analysis-modal').data('posture-image',res.image_name);
               
                if (image_name == 'image2') {
                    $('#posMsg').data('alert-type','sideEar');
                    $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/side-ear.png');
                    $("#back-analysis").removeClass('hidden');
                    $("#back-analysis").attr('data-image-name', res.previous_image);
                    $("#back-analysis").attr('data-posture-mode', posture_mode)
                    $("#back-analysis").attr('data-client-id', res.client_id);
                    $("#back-analysis").attr('data-posture-id', res.posture_id);
                    $("#next-analysis").attr('data-image-name', res.next_image);
                    $("#next-analysis").attr('data-posture-mode', posture_mode)
                    $("#next-analysis").attr('data-client-id', res.client_id);
                    $("#next-analysis").attr('data-posture-id', res.posture_id);
                    $("#posture-analysis-modal").data('view',res.view);
                    $("#reset-analysis").attr('data-client-id', res.client_id);
                    $("#reset-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-image-name', res.current_image);
                    $("#undo-analysis").attr('data-client-id', res.client_id);
                    $("#undo-analysis").attr('data-posture-id', res.posture_id);
                    $("#undo-analysis").attr('data-image-name', res.current_image);
                    $(".gridLine").attr('data-image-name', res.current_image);
                }
                if (image_name == 'image3') {
                    $('#posMsg').data('alert-type','backRightEar');
                    $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/back-right-neck.png');
                    $("#back-analysis").removeClass('hidden');
                    $("#back-analysis").attr('data-image-name', res.previous_image);
                    $("#back-analysis").attr('data-posture-mode', posture_mode)
                    $("#back-analysis").attr('data-client-id', res.client_id);
                    $("#back-analysis").attr('data-posture-id', res.posture_id);
                    $("#next-analysis").attr('data-image-name', res.next_image);
                    $("#next-analysis").attr('data-posture-mode', posture_mode)
                    $("#next-analysis").attr('data-client-id', res.client_id);
                    $("#next-analysis").attr('data-posture-id', res.posture_id);
                    $("#posture-analysis-modal").data('view',res.view);
                    $("#reset-analysis").attr('data-client-id', res.client_id);
                    $("#reset-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-image-name', res.current_image);
                    $("#undo-analysis").attr('data-client-id', res.client_id);
                    $("#undo-analysis").attr('data-posture-id', res.posture_id);
                    $("#undo-analysis").attr('data-image-name', res.current_image);
                    $(".gridLine").attr('data-image-name', res.current_image);
                }
                if (image_name == 'image4') {
                    $('#posMsg').data('alert-type','leftSideEar');
                     $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/left-side-ear.png');
                    $("#posture-analysis-modal").data('view',res.view);
                    $("#back-analysis").removeClass('hidden');
                    $("#back-analysis").attr('data-image-name', res.previous_image)
                    $("#back-analysis").attr('data-posture-mode', posture_mode)
                    $("#back-analysis").attr('data-client-id', res.client_id)
                    $("#back-analysis").attr('data-posture-id', res.posture_id)
                    $("#next-analysis").attr('data-image-name', res.next_image)
                    $("#reset-analysis").attr('data-client-id', res.client_id);
                    $("#reset-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-image-name', res.current_image);
                    $("#undo-analysis").attr('data-client-id', res.client_id);
                    $("#undo-analysis").attr('data-posture-id', res.posture_id);
                    $("#undo-analysis").attr('data-image-name', res.current_image);
                    $(".gridLine").attr('data-image-name', res.current_image);
                        // $("#next-analysis").addClass('hidden');
                }
                $('#canvasPic').attr('client-id', res.client_id);
                $('#canvasPic').attr('posture-id', res.posture_id);
                $('#canvasPic').attr('image-type', image_name);

                var canvas = document.getElementById('canvasPic');
                var context = canvas.getContext('2d');
                var imageObj = new Image();
                 var height = 531;

                 var width = 337;

                 imageObj.onload = function() {
                     var aspectRatio = this.width/this.height;
                    width = aspectRatio * height;
                    $('#posture-analysis-modal').find('.posture-d').css('width',width);
                    $('#canvasPic').attr('width', width);
                    $('#canvasPic').attr('height', 531);
                    $('#posture-analysis-modal').find('.tablenumber').css('width',width);
                     context.drawImage(imageObj, 0, 0, width, height);
                 };
                if(posture_mode == 'edit'){
                    $("#undo-analysis").hide();
                    if(res.image_path != null && res.image_path != ''){
                        imageObj.src =public_url + 'uploads/' + res.image_path;
                    }else{
                        $('#posture-analysis-modal').find('#reset-analysis').trigger('click');
                        imageObj.src =public_url + 'posture-images/thumb_' + res.image_name;
                    }
                    if (res.xPos != '' && res.yPos != '') {
                        count_mark_point = res.xPos.split(',').length;
                    }else{
                        count_mark_point = 0;
                    }
                }else{
                    $('#posture-analysis-modal').find('#reset-analysis').trigger('click');
                    imageObj.src =public_url + 'posture-images/thumb_' + res.image_name;
                    count_mark_point = 0;
                }
                $("#posture-analysis-modal").show();
            } else {
               
                $("#posture-analysis-modal").hide();
                // $(".remove-analysis").hide();
                $(".view-posture").html(res);
                $(".view-posture").removeClass('hidden');
                $(".create-posture").addClass('hidden');
                $(".posture-list").addClass('hidden');
                $(".edit-posture").addClass('hidden');
            }

        },
        complete: function(){
            setTimeout(function() {
                $('#waitingShield').addClass('hidden');
            },2000);
        }

    })
})
$(document).on('click',"#back-analysis", function() {
    resetCanvas();
    var client_id = $(this).attr('data-client-id');
    var posture_id = $(this).attr('data-posture-id');
    var image_name = $(this).attr('data-image-name');
    var posture_mode = $(this).attr('data-posture-mode');
    $.ajax({
        url: public_url + "posture/analysis",
        method: 'post',
        data: {
            'client_id': client_id,
            'posture_id': posture_id,
            'image_name': image_name
        },
        beforeSend: function(){
            $('#waitingShield').removeClass('hidden');
        },
        success: function(res) {
            // console.log(res)
            if (res.status == true) {
                $('#posture-analysis-modal').data('posture-image',res.image_name);
                if (image_name == 'image1') {
                    $("#posture-analysis-modal").data('view',res.view);
                    $("#back-analysis").addClass('hidden');
                    $("#next-analysis").attr('data-image-name', res.next_image)
                    $("#next-analysis").attr('data-posture-mode', posture_mode)
                    $("#next-analysis").attr('data-client-id', res.client_id)
                    $("#next-analysis").attr('data-posture-id', res.posture_id)
                    $("#reset-analysis").attr('data-client-id', res.client_id);
                    $("#reset-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-image-name', res.current_image);
                    $("#undo-analysis").attr('data-client-id', res.client_id);
                    $("#undo-analysis").attr('data-posture-id', res.posture_id);
                    $("#undo-analysis").attr('data-image-name', res.current_image);
                    $(".gridLine").attr('data-image-name', res.current_image);
                }
                if (image_name == 'image2') {
                    $("#posture-analysis-modal").data('view',res.view);
                    $("#back-analysis").removeClass('hidden');
                    $("#back-analysis").attr('data-image-name', res.previous_image)
                    $("#back-analysis").attr('data-posture-mode', posture_mode)
                    $("#back-analysis").attr('data-client-id', res.client_id)
                    $("#back-analysis").attr('data-posture-id', res.posture_id)
                    $("#next-analysis").attr('data-image-name', res.next_image)
                    $("#next-analysis").attr('data-posture-mode', posture_mode)
                    $("#next-analysis").attr('data-client-id', res.client_id)
                    $("#next-analysis").attr('data-posture-id', res.posture_id)
                    $("#reset-analysis").attr('data-client-id', res.client_id);
                    $("#reset-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-image-name', res.current_image);
                    $("#undo-analysis").attr('data-client-id', res.client_id);
                    $("#undo-analysis").attr('data-posture-id', res.posture_id);
                    $("#undo-analysis").attr('data-image-name', res.current_image);
                    $(".gridLine").attr('data-image-name', res.current_image);
                }
                if (image_name == 'image3') {
                    $("#back-analysis").removeClass('hidden');
                    $("#back-analysis").attr('data-image-name', res.previous_image)
                    $("#back-analysis").attr('data-posture-mode', posture_mode)
                    $("#back-analysis").attr('data-client-id', res.client_id)
                    $("#back-analysis").attr('data-posture-id', res.posture_id)
                    $("#next-analysis").attr('data-image-name', res.next_image)
                    $("#next-analysis").attr('data-posture-mode', posture_mode)
                    $("#next-analysis").attr('data-client-id', res.client_id)
                    $("#next-analysis").attr('data-posture-id', res.posture_id)
                    $("#next-analysis").removeClass('hidden');
                    $("#posture-analysis-modal").data('view',res.view);
                    $("#reset-analysis").attr('data-client-id', res.client_id);
                    $("#reset-analysis").attr('data-posture-id', res.posture_id);
                    $("#reset-analysis").attr('data-image-name', res.current_image);
                    $("#undo-analysis").attr('data-client-id', res.client_id);
                    $("#undo-analysis").attr('data-posture-id', res.posture_id);
                    $("#undo-analysis").attr('data-image-name', res.current_image);
                    $(".gridLine").attr('data-image-name', res.current_image);
                }
                $('#canvasPic').attr('client-id', res.client_id);
                $('#canvasPic').attr('posture-id', res.posture_id);
                $('#canvasPic').attr('image-type', image_name);

                var canvas = document.getElementById('canvasPic');
                var context = canvas.getContext('2d');
                var imageObj = new Image();
                 var height = 531;
                 var width = 337;

                 imageObj.onload = function() {
                     var aspectRatio = this.width/this.height;
                      width = aspectRatio * height;
                      $('#posture-analysis-modal').find('.posture-d').css('width',width);
                     $('#canvasPic').attr('width', width);
                     $('#canvasPic').attr('height', 531);
                     $('#posture-analysis-modal').find('.tablenumber').css('width',width);
                     context.drawImage(imageObj, 0, 0, width, height);
                 };
                if(posture_mode == 'edit'){
                    $("#undo-analysis").hide();
                    if(res.image_path != null && res.image_path != ''){
                        imageObj.src =public_url + 'uploads/' + res.image_path;
                    }else{
                        imageObj.src =public_url + 'posture-images/thumb_' + res.image_name;
                        $('#posture-analysis-modal').find('#reset-analysis').trigger('click');
                    }
                    if (res.xPos != '' && res.yPos != '') {
                        count_mark_point = res.xPos.split(',').length;
                    }else{
                        count_mark_point = 0;
                    }
                }else{
                    $('#posture-analysis-modal').find('#reset-analysis').trigger('click');
                    imageObj.src =public_url + 'posture-images/thumb_' + res.image_name;
                    count_mark_point = 0;
                }
                // if (res.xPos != '' && res.yPos != '') {
                //     setPosition(res.xPos, res.yPos);
                // }
                
                // magnify('canvasPic',3,public_url+'posture-images/thumb_'+res.image_name);
                // $("#posture-analysis-modal").find('.posture-pre').prop('src', public_url + 'posture-images/thumb_' + res.image_name)
                $("#posture-analysis-modal").show();
                // posture_image = public_url+'posture-images/thumb_'+res.image_name;
                // canvas();
            } else {

            }

        },
        complete: function(){
            setTimeout(function() {
							$('#waitingShield').addClass('hidden');
						},2000);
        }

    })
})
$(document).on('click',"#hide-analysis", function() {
    $('#posture-analysis-modal').hide();
})

function setPosition(x, y) {
    // console.log(x.split(',').length, y.split(',').length);
    var xcoordinate = x.split(',');
    var ycoordinate = y.split(',');
    for (var i = 0; i < xcoordinate.length; i++) {
        // var canvas = document.getElementById("canvasPic");
        // var rect = canvas.getBoundingClientRect();
        xData = xcoordinate[i];
        yData = ycoordinate[i];
        drawCoordinates1(xcoordinate[i], ycoordinate[i]);
        drawLine(e);
    }

}

function drawCoordinates1(x, y) {
    var ctx = document.getElementById("canvasPic").getContext("2d");
    can = ctx;
    // console.log(ctx);
    ctx.fillStyle = "#ff2626"; // Red color
    ctx.beginPath();
    ctx.arc(x, y, pointSize, 0, Math.PI * 2, true);
    ctx.fill();
    // console.log(x, y);

}

$(document).on('click','#reset-analysis',function(){
    resetCanvas();
    count_mark_point = 0;
    var imageData=  $('#posture-analysis-modal').data('posture-image');
    var image = $('#posture-analysis-modal').data('view');
    // console.log(imageData);
    if(image == 'right'){
        $('#posMsg').text('Mark the E.A.M').show();
        $('#posMsg').data('alert-type','sideEar');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/side-ear.png');

    }else if(image == 'back'){
        $('#posMsg').text(' Mark the right INFERIOR EAR LOBE').show();
        $('#posMsg').data('alert-type','backRightEar');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/back-right-neck.png');
    }else if(image == 'left'){
        $('#posMsg').text('Mark the E.A.M').show();
        $('#posMsg').data('alert-type','leftSideEar');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/left-side-ear.png');

    }else{
        $('#posMsg').text('Place a marker on a right eye').show();
        $('#posMsg').data('alert-type','rightEye');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/right-eye.png');

    }
    $('#canvasPic').attr('width', 338);
    $('#canvasPic').attr('height', 531);
    var canvas = document.getElementById('canvasPic');
    var context = canvas.getContext('2d');
    var imageObj = new Image();
    var height = 531;

    var width = 337;

    imageObj.onload = function() {
        var aspectRatio = this.width/this.height;
        width = aspectRatio * height;
        $('#posture-analysis-modal').find('.posture-d').css('width',width);
        $('#canvasPic').attr('width', width);
        $('#canvasPic').attr('height', 531);
        $('#posture-analysis-modal').find('.tablenumber').css('width',width);
        context.drawImage(imageObj, 0, 0, width, height);
    };
    imageObj.src = public_url + 'posture-images/thumb_' + imageData;
    $("#undo-analysis").show();
    $('.size_zoom_image').show();
    var client_id = $(this).attr('data-client-id');
    var posture_id = $(this).attr('data-posture-id');
    var image_name = $(this).attr('data-image-name');
    $.ajax({
        url: public_url + "reset/analysis",
        method: 'post',
        data: {
            'client_id': client_id,
            'posture_id': posture_id,
            'image_name': image_name
        }
    });

})

$(document).on('click','#undo-analysis',function(){
    resetCanvas();
    var imageData=  $('#posture-analysis-modal').data('posture-image');
    var image = $('#posture-analysis-modal').data('view');
    // console.log(imageData);
    if(image == 'right'){
        $('#posMsg').text('Mark the E.A.M').show();
        $('#posMsg').data('alert-type','sideEar');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/side-ear.png');

    }else if(image == 'back'){
        $('#posMsg').text(' Mark the right INFERIOR EAR LOBE').show();
        $('#posMsg').data('alert-type','backRightEar');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/back-right-neck.png');
    }else if(image == 'left'){
        $('#posMsg').text('Mark the E.A.M').show();
        $('#posMsg').data('alert-type','leftSideEar');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/left-side-ear.png');

    }else{
        $('#posMsg').text('Place a marker on a right eye').show();
        $('#posMsg').data('alert-type','rightEye');
        $('.size_zoom_image').find('img').attr('src',public_url +'assets/images/right-eye.png');

    }
    $('#canvasPic').attr('width', 338);
    $('#canvasPic').attr('height', 531);
    var canvas = document.getElementById('canvasPic');
    var context = canvas.getContext('2d');
    var imageObj = new Image();
    var height = 531;

    var width = 337;

    imageObj.onload = function() {
        var aspectRatio = this.width/this.height;
        width = aspectRatio * height;
        $('#posture-analysis-modal').find('.posture-d').css('width',width);
        $('#canvasPic').attr('width', width);
        $('#canvasPic').attr('height', 531);
        $('#posture-analysis-modal').find('.tablenumber').css('width',width);
        context.drawImage(imageObj, 0, 0, width, height);
    };
    imageObj.src = public_url + 'posture-images/thumb_' + imageData;
    $('.size_zoom_image').show();
    var client_id = $(this).attr('data-client-id');
    var posture_id = $(this).attr('data-posture-id');
    var image_name = $(this).attr('data-image-name');
    // var xpos = $(this).attr('data-xpos');
    // var ypos = $(this).attr('data-ypos');
    $.ajax({
        url: public_url + "undo/analysis",
        method: 'post',
        data: {
            'client_id': client_id,
            'posture_id': posture_id,
            'image_type': image_name,
            // 'xPos': xpos,
            // 'yPos': ypos
        },
        beforeSend: function(){
            $('#waitingShield').removeClass('hidden');
        },
        success: function(res) {
            // console.log(res);
            // resetCanvas();
            // $("#undo-analysis").attr('data-xpos', res.xpos);
            // $("#undo-analysis").attr('data-ypos', res.ypos);
            if (res.xPos != '' && res.yPos != '') {
                count_mark_point = res.xPos.split(',').length;
                setPosition(res.xPos, res.yPos);
            }
            else{
                count_mark_point = 0;
            }
        },
        complete: function(){
			$('#waitingShield').addClass('hidden');
        }
    });

})

function resetCanvas(){
    canvas = document.getElementById("canvasPic");
    const context = canvas.getContext('2d');
    clicks = 0;
    lastClick = [0, 0];
    context.clearRect(0, 0, canvas.width, canvas.height);
    xData = '';
    yData = '';
    x1 = '';
    y1 = '';
    x2 = '';
    y2 = '';
   
}

function calculateMidPoint(value1,value2,value3){
    var image = $('#posture-analysis-modal').data('view');
    var alertType = $('#posMsg').data('alert-type');
    can.beginPath();
    console.log(image != 'right' && image != 'left');
   if(image != 'right' && image != 'left'){ 
    cox3 = value1.x1,
    coy3 = value1.y1,
    cox4 = value2.x1,
    coy4 =value2.y1;

   var mid = middlePoint(cox3,coy3,cox4,coy4);
  can.moveTo(mid[0], mid[1]);
   }else{
    can.moveTo(value1.x1, value1.y1);
   }
  
  can.lineWidth = 1;
  console.log(value3);
  if(value3 != ''){
    console.log(value3.x1, value3.y1);
    can.lineTo(value3.x1, value3.y1, 6);
    midLine = mid;

}else if(alertType == 'leftWaist' || alertType == 'leftAnkle'){
    can.lineTo(midLine[0], midLine[1], 6);
    midLine = mid;
  }
  else{
    console.log('jii',xData, yData);
    can.lineTo(xData, yData, 6);
  }

  can.strokeStyle = '#0e0be8';
  can.stroke();
  return mid;
}

    
function createGreenLine(value1,value2,value3,value4){
    var dx = 531 - 0;
    var dy = 0 - 0;
    var image = $('#posture-analysis-modal').data('view');
    if(image == 'right' || image == 'left'){ 
        can.beginPath();
        can.moveTo(value2.x1,value2.y1);
        can.lineWidth = 2;
        topCordX = value2.x1 + dy;
        topCordY =value2.y1-dx;
        can.lineTo(topCordX ,topCordY,6);
        midLine = mid;
  
    }else{
        cox3 = value1.x1,
        coy3 = value1.y1,
        cox4 = value2.x1,
        coy4 =value2.y1;
        
        var mid = middlePoint(cox3,coy3,cox4,coy4);
        var mid2 = middlePoint(value3.x1,value3.y1,value4.x1,value4.y1);
        if(image == 'back'){
            can.beginPath();
            can.moveTo(mid2[0], mid2[1]);
            can.lineWidth = 2;
            topCordX = mid2[0]+dy;
            topCordY =mid2[1]-dx;
            can.lineTo(topCordX, topCordY, 6);
        }else{
            var alertType = $('#posMsg').data('alert-type');
            can.beginPath();
            can.moveTo(midLine[0],midLine[1]);
            can.lineWidth = 2;
            topCordX = midLine[0]+dy;
            topCordY =midLine[1]-dx;
            if(alertType == 'leftAnkle'){
                can.lineTo(topCordX, topCordY, 6);
                midLine = mid;
            }
        }


    }
    can.strokeStyle = '#0bec07';
    can.stroke();
   
  
    }

    $(document).on('click','.new-posture',function(){
        resetCanvas();
        $(".posture-list").addClass('hidden');
        $(".create-posture").removeClass('hidden');
        $(".edit-posture").addClass('hidden');
        $(".view-posture").addClass('hidden');
    });

    

    $(document).on('click','.go-back',function(){
        var data_from = $(this).attr('data-from');
        if(data_from == "preview-page"){
            $(".posture-list").addClass('hidden');
            $(".create-posture").addClass('hidden');
            $(".edit-posture").addClass('hidden');
            $(".view-posture").removeClass('hidden');
            $('.go-back').removeAttr('data-from');
        }else{
            window.location.reload();
        }
        
    });
    
    $(document).on('click','.posture-preview',function() {
        var client_id = $(this).attr('data-client-id');
        var posture_id = $(this).attr('data-posture-id');
        $.ajax({
            url: public_url + "preview/analysis",
            method: 'post',
            data: {
                'client_id': client_id,
                'posture_id': posture_id,
            },
            beforeSend: function(){
                $('#waitingShield').removeClass('hidden');
            },
            success:function(res) {
                $(".posture-list").addClass('hidden');
                $(".create-posture").addClass('hidden');
                $(".edit-posture").addClass('hidden');
                $(".view-posture").html(res);
                $(".view-posture").removeClass('hidden');
            },
            complete: function(){
                setTimeout(function() {
                    $('#waitingShield').addClass('hidden');
                },2000);
            }
        });
    });
    function calculateHeadWeight(height,gender){
        var sex = gender;
       
        if (sex == 'Male') {
            effWeight= Math.round(56.2 * 1 + (height * .39 - 152.4 * .39) * 1.41) * 1 
        } else if (sex == 'Female') {
            effWeight = Math.round(53.1 * 1 + (height * .39 - 152.4 * .39) * 1.36) * 1 
        }else{
            effWeight = Math.round(53.1 * 1 + (height * .39 - 152.4 * .39) * 1.36) * 1 
        }
            
     var   W= effWeight+10* 0.075;
        
     return W;
    }

$(document).on('click','.mailReport',function(){
    var id = $(this).data('posture-id');
    var clientId = $(this).data('client-id');
    console.log(clientId);
    swal({
		title: 'Are you sure?',
		type: 'warning',
		allowEscapeKey: false,
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		confirmButtonColor: '#ff4401',
		closeOnConfirm: false
	}, 
	function(isConfirm){
		if(isConfirm){
            swal.close();
			$.ajax({
                url: public_url + "preview/mailpdf",
                method: 'post',
                data: {
                    'id': id,
                    'clientId':clientId
                },
                beforeSend: function(){
                    $('#waitingShield').removeClass('hidden');
                },
                success:function(res) {
                  swal(res.message);
                },
                complete: function(){
                    $('#waitingShield').addClass('hidden');
                }
            });
		}
		else{
			swal.close();
		}
	});
    
})

$('.deleteReport').on('click',function(){
    var id = $(this).data('posture-id');
    swal({
		title: 'Are you sure?',
		type: 'warning',
		allowEscapeKey: false,
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		confirmButtonColor: '#ff4401',
		closeOnConfirm: false
	}, 
	function(isConfirm){
		if(isConfirm){
            swal.close();
			$.ajax({
                url: public_url + "preview/deleteReport",
                method: 'post',
                data: {
                    'id': id
                },
                beforeSend: function(){
                    $('#waitingShield').removeClass('hidden');
                },
                success:function(res) {
                    // console.log(res);
                  swal(res.status);
                  if(isConfirm){
                      window.location.reload();
                  }
                },
                complete: function(){
                    $('#waitingShield').addClass('hidden');
                }
            });
		}
		else{
			swal.close();
		}
	});
    
})

$(document).on('click','.delete-image',function(){
    var posture_id = $(this).attr('data-posture-id');
    var image_name = $(this).attr('data-image-name');
    swal({
		title: 'Are you sure?',
		type: 'warning',
		allowEscapeKey: false,
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		confirmButtonColor: '#ff4401',
		closeOnConfirm: false
	}, 
	function(isConfirm){
		if(isConfirm){
            swal.close();
			$.ajax({
                url: public_url + "remove/image",
                method: 'post',
                data: {
                    'id': posture_id,
                    'image_name': image_name,
                },
                success:function(res) {
                    // console.log(res);
                    swal(res.msg);
                    if(res.status == true){
                        if(isConfirm){
                            $('.'+image_name+"-posture-pre").prop('src','');
                        }
                    }else if(res.status == 'delete-record'){
                        window.location.reload();
                    }
                    
                }
            });
		}
		else{
			swal.close();
		}
	});
    
})

$('.deleteReport').on('click',function(){
    var id = $(this).data('posture-id');
    swal({
		title: 'Are you sure?',
		type: 'warning',
		allowEscapeKey: false,
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		confirmButtonColor: '#ff4401',
		closeOnConfirm: false
	}, 
	function(isConfirm){
		if(isConfirm){
            swal.close();
			$.ajax({
                url: public_url + "preview/deleteReport",
                method: 'post',
                data: {
                    'id': id
                },
                success:function(res) {
                    // console.log(res);
                  swal(res.status);
                  if(isConfirm){
                      window.location.reload();
                  }
                }
            });
		}
		else{
			swal.close();
		}
	});
    
})


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

$('body').on('click','#heightUnit',function(){
	var currUnit = $('input[name="heightPosture"]').val();
    var model = $('#measurement-model');
	if(currUnit == 'Metric'){
		$(this).text('Show Metric');
		model.find('input[name="heightPosture"]').val('Imperial');
		var heightMetric=model.find('input[name="height_metric"]').val(); 
        var inches = (heightMetric*0.393700787).toFixed(0);
        // var feet = Math.floor(inches / 12);
        // inches %= 12;
       
        
        model.find('input[name="height_imperial_inch"]').val(inches);
         
        model.find('div.heightMetric').addClass('hidden');
	    model.find('div.heightImperial').removeClass('hidden');

	}else{
		$(this).text('Show Imperial');
		model.find('input[name="heightPosture"]').val('Metric');
		var height_imperial_inch=model.find('input[name="height_imperial_inch"]').val(); 
        var inches= parseFloat(height_imperial_inch * 2.54);

        
            model.find('input[name="height_metric"]').val(inches);
         
         model.find('div.heightMetric').removeClass('hidden');
         model.find('div.heightImperial').addClass('hidden');

	}
	
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

$('.saveHeightWeight').on('click',function(){
    var formData ={};
     formData['clientId'] = $('#clientId').val();
    var model = $('#measurement-model');
    formData['weightUnit'] = model.find('input[name="weightPosture"]').val();
    formData['heightUnit'] = model.find('input[name="heightUnit"]').val();
    formData['height']=model.find("#height_m").val(); 
    formData['weight'] = parseFloat($("#weight_save").val());

    $.ajax({
        url: public_url + "save/heightWeight",
        method: 'post',
        data: formData,
        success:function(res) {
            $('#measurement-model').modal('hide');
            $('#posture-analysis-modal').data('weight',formData['weight']),
            $('#posture-analysis-modal').data('weight-unit',formData['weightUnit']),
            $('#posture-analysis-modal').data('height',formData['height']),
            $('#posture-analysis-modal').data('height-unit',formData['heightUnit']);
            // $('#posture-analysis').trigger('click');
            swal({
                title: 'Data save successfully',
                type: 'success',
                allowEscapeKey: false,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonColor: '#ff4401',
                closeOnConfirm: false
            }, 
            function(isConfirm){
                if(isConfirm){
                    swal.close();
                   
                }
                else{
                    swal.close();
                }
            });
          
        }
    });


})

function fileSelectHandlerClick(elem){
    var formGroup = $(elem).closest('.posture-button');
    console.log(formGroup,$(elem));
    image_name = formGroup.find('input[name="image_name"]').val();
    prePhotoName = formGroup.find('input[name="postureimage"]');
    user_id = formGroup.find('input[name="client_id"]').val();
    posture_id = formGroup.find('input[name="posture_id"]').val();
    previewPics = $('.' + image_name + '-posture-pre');
	var fileInput = elem.files[0];
	var fileUrl = window.URL.createObjectURL(elem.files[0]);
	
	var oFile = elem.files[0];
	var form_data = new FormData();                  
	form_data.append('fileToUpload', oFile);
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': $('meta[name=_token]').attr('content')
		}
	});
   
	$.ajax({
		url: public_url+'captcha/image', // point to server-side PHP script
		data: form_data,
		dataType: 'text', 
		type: 'POST',
		contentType: false, // The content type used when sending data to the server.
		cache: false, // To unable request pages to be cached
		processData: false,
        beforeSend: function(){
            $('#waitingShield').removeClass('hidden');
        },
		success: function(data) {
            console.log(data);
			// toggleWaitShield('hide');
			// var file = document.querySelector('.chooseFileBtn');
     		// file.value = '';
             $('#imageCrop').attr('src', fileUrl);

             previewPics.val(data);
             $('#cropperModal').modal('show');
			// activeForm.find("#clickedPic").val(data);
		},
        complete: function(){
            $('#waitingShield').addClass('hidden');
        },
	});
}

$('#convertM').click(function()
{
    var $this = $(this);
    var client_id = $this.attr('data-client-id');
    var unit_data = 'Imperial';
   
    // $('#convertI').show();
    convert_unit($this,client_id,unit_data);
});
$('#convertI').click(function()
{
    var $this = $(this);
    var client_id = $this.attr('data-client-id');
    var unit_data = 'Metric';
    
    // $('#convertM').show();
    convert_unit($this,client_id,unit_data);
});
function convert_unit($this,client_id,unit_data) {
    if(unit_data == 'Imperial'){
        var from = 'Metric';
    }
    if(unit_data == 'Metric'){
        var from = 'Imperial';
    }
    
    $.ajaxSetup({
		headers: {
			'X-CSRF-Token': $('meta[name=_token]').attr('content')
		}
	});
    swal({
        title: 'Are  you sure you want to change measurements from '+from+' to '+unit_data+'?',
        type: 'warning',
        allowEscapeKey: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        confirmButtonColor: '#ff4401',
        closeOnConfirm: false
    }, 
    function(isConfirm){
        if(isConfirm){
            $.ajax({
                url: public_url+'save/unit',
                data: {
                    'client_id':client_id,
                    'unit_data':unit_data,
                },
                type: 'POST',
                success: function(data) {
                    if(data == true){
                        if(unit_data == 'Metric'){
                            $this.removeClass('btn-default');
                            $this.addClass('btn-primary');
                            $this.attr('disabled',true);
                            $('#convertM').removeClass('btn-primary');
                            $('#convertM').addClass('btn-default');
                            $('#convertM').attr('disabled',false);
                        }
                        if(unit_data == 'Imperial'){
                            $this.removeClass('btn-default');
                            $this.addClass('btn-primary');
                            $this.attr('disabled',true);
                            $('#convertI').removeClass('btn-primary');
                            $('#convertI').addClass('btn-default');
                            $('#convertI').attr('disabled',false);
                        }
                    }
                }
            });
            swal.close();
           
        }
        else{
            swal.close();
        }
    });

}

$(document).on('keyup','.image-note',function() {
    var $this = $(this);
    var client_id = $(this).data('client-id');
    var posture_id = $(this).data('posture-id');
    var image = $(this).data('image');
    var note = $(this).val();
    $.ajax({
        url: public_url + "save/note",
        method: 'post',
        data: {
            'client_id': client_id,
            'posture_id': posture_id,
            'image': image,
            'note': note,
        },
        success:function(res) {
            console.log(res);
            if(res.status == 'error'){
                $this.val('');
                swal({
                    title: res.msg,
                    type: 'warning',
                    allowEscapeKey: false,
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    // cancelButtonText: 'No',
                    confirmButtonColor: '#ff4401',
                    // closeOnConfirm: false
                });
            }
        },
    });
});
$(document).on('change','#hideshowgrid',function() {
	if(this.checked) {
        $('.grid-table  tr').each(function() {
           $(this).hide();
        });
	}else {
        $('.grid-table  tr').each(function() {
            $(this).show();
        });
    }	
});