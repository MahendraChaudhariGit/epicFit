var public_url = $('meta[name="public_url"]').attr('content'), 
	initList = null,
    countDoun = 300,
    countDounMsg = 250,
    isLock = false;

    /* Start: unlock user */
	$(document).on('submit','#form-unlock', function(e){
		e.preventDefault();
        $('#unlock-btn').prop('disabled', true);
		var form = $(this),
			formData = {},
			lock_screen_div = $('#lock-screen-div'),
			error_field = lock_screen_div.find('.error');

			error_field.empty();
			
			formData['password'] = form.find('input[name="password"]').val();
			formData['username'] = form.find('input[name="username"]').val();
		if(typeof formData['password'] != 'undefined' && formData['password'] != ''){
			$.ajax({
                url : public_url+'unlock/user',
                type : 'POST',
                data : formData,
                success : function(response) {
                    var data = JSON.parse(response);
                    $('#unlock-btn').prop('disabled', false);
                    var ts = Math.round((new Date()).getTime() / 1000);
                    createCookie("timestamp", ts , 1);
                    isLock = false;
                    if(data.status=='success'){
                        $('.modal').css('position','fixed');
                    	$('body').css('overflow-y','scroll');
                        lock_screen_div.addClass('hidden');
                    }
                    else{
                    	error_field.append(data.msg);
                    }
                },
            });
		}
		else{
			error_field.append('Please enter password');
		}
    })
	/* End: unlock user */

    /* Start: Lock screen auto when moseover desable upto 5 min */
    $(window).on('mousemove keyup',function(){
        $('#lock-alert').slideUp('slow');
        $('#lock-alert').find('span').text(0);
        var ts = Math.round((new Date()).getTime() / 1000);
        createCookie("timestamp", ts , 1);
        if(initList)
            clearInterval(initList);
        initList = setInterval(function(){
            checkCounter();
         }, 1000) ;
    });
    /* End: Lock screen auto when moseover desable upto 5 min  300000*/


/* Start: Check counter */
function checkCounter(){
    var counterTime = readCookie("timestamp");
    var currentTime = Math.round((new Date()).getTime() / 1000);
    var diff = currentTime - counterTime;
    if(diff >= countDounMsg && diff < countDoun){
        var lockAlert = $('#lock-alert'); 
        lockAlert.slideDown("slow");
        lockAlert.find('span').text(countDoun - diff);
    }
    else if(diff >= countDoun){
        lockScreen();
        $('#lock-alert').slideUp('fast');
        $('#lock-alert').find('span').text(0); 
    } 
}
/* End: Check Counter */

/* Start: Lock Screen Function */
function lockScreen(){
	var lock_screen_div = $('#lock-screen-div');
    if(!isLock){
    	$.ajax({
            url : public_url+'lock/user',
            type : 'GET',
            success : function(response) {
                var data = JSON.parse(response);
                if(data.status=='success'){
                    $('.modal').css('position','unset');
                    lock_screen_div.find('input[name="password"]').val('');
                    $('body').css('overflow','hidden');
                    window.history.forward(1);
                    preventBack();
                    lock_screen_div.removeClass('hidden');
                }
            },
        });
        isLock = true;
    }
}
/* End: Lock Screen Function */

/* Start: browser back button disabaled Function */
function preventBack() { 
	/*window.history.forward(1); 
	$('#lock-screen-div').removeClass('hidden');*/
}
/* End: browser back button disabaled Function */

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}