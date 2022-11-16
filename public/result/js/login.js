var Login = function() {
	"use strict";
	
	var runSetDefaultValidation = function() {
		$.validator.setDefaults({
			errorElement : "span", // contain the error msg in a small tag
			errorClass : 'help-block',
			errorPlacement : function(error, element) {// render error placement for each input type
				if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {// for chosen elements, need to insert the error after the chosen container
					error.insertAfter($(element).closest('.form-group').children('div').children().last());
				} else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
					error.appendTo($(element).closest('.form-group').children('div'));
				} else {
					error.insertAfter(element);
					// for other inputs, just perform default behavior
				}
			},
			ignore : ':hidden',
			success : function(label, element) {
				label.addClass('help-block valid');
				// mark the current input as valid and display OK icon
				$(element).closest('.form-group').removeClass('has-error');
			},
			highlight : function(element) {
				$(element).closest('.help-block').removeClass('valid');
				// display OK icon
				$(element).closest('.form-group').addClass('has-error');
				// add the Bootstrap error class to the control group
			},
			unhighlight : function(element) {// revert the change done by hightlight
				$(element).closest('.form-group').removeClass('has-error');
				// set error class to the control group
			}
		});
	};
	var runLoginValidator = function() {
		var form = $('.form-login');
		var errorHandler = $('.errorHandler', form);
		form.validate({
			rules : {
				username : {
					minlength : 2,
					required : true
				},
				password : {
					minlength : 6,
					required : true
				}
			},
			submitHandler : function(form) {
				errorHandler.hide();
				form.submit();
			},
			invalidHandler : function(event, validator) {//display error alert on form submit
				errorHandler.show();
			}
		});
	};
	var runForgotValidator = function() {
		var form2 = $('.form-forgot');
		var errorHandler2 = $('.errorHandler', form2);
		form2.validate({
			rules : {
				email : {
					required : true
				}
			},
			submitHandler : function(form) {
				errorHandler2.hide();
				form2.submit();
			},
			invalidHandler : function(event, validator) {//display error alert on form submit
				errorHandler2.show();
			}
		});
	};
	var runRegisterValidator = function() {
		var form3 = $('.form-register');
		var errorHandler3 = $('.errorHandler', form3);
		form3.validate({
			rules : {
				name : {
					minlength : 1,
					required : true
				},
				last_name : {
					minlength : 1,
					required : true
				}/*,
				telephone : {
					minlength : 4,
					required : true
				},
				referral : {
					minlength : 2,
					required : true
				},
				address : {
					minlength : 2,
					required : true
				},
				city : {
					minlength : 2,
					required : true
				},
				gender : {
					required : true
				}*/,
				email : {
					required : true
				},
				password : {
					minlength : 6,
					required : true
				}/*,
				password_again : {
					required : true,
					minlength : 5,
					equalTo : "#password"
				},
				agree : {
					minlength : 2,
					required : true,
					equalTo : '0'
				}*/
			},
			submitHandler : function(form) {
				errorHandler3.hide();
				form3.submit();
			},
			invalidHandler : function(event, validator) {//display error alert on form submit
				errorHandler3.show();
			}
		});
	};
	return {
		//main function to initiate template pages
		init : function() {
			runSetDefaultValidation();
			runLoginValidator();
			runForgotValidator();
			runRegisterValidator();
		}
	};
}();

var public_url = window.location.origin+'/';
var modal = $('#myModal');
$(document).keypress(function(event){
    if (event.keyCode === 13) {
		console.log('hii');
        $('.clientLogin').click();
    }
});



$('.clientLogin').click(function(){
	var formData = {}
	formData['email'] = $('input[name="uname"]').val();
	formData['password'] = $('input[name="password"]').val();
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
	});
	$.post(public_url+'check-client',formData,function(response){
		if(response.openModal){
			var listHtml = '';
			$.each(response.businessData,function(key,obj){
				listHtml += '<div class="listShow radio clip-radio radio-primary radio-inline m-b-0 onchange-set-neutral">\
					<input type="radio" name="business" id="business'+obj.businessId+'" value="'+obj.url+'" required>\
					<label for="business'+obj.businessId+'"> '+obj.name +'</label>\
				</div>';
			});
			modal.find('.listShow').remove();
			modal.find('.businessList').append(listHtml);
			modal.modal('show');
		}else{
			$('.form-login').submit();
		}
	})
});

$('body').on('click','.modelloginbutton',function(){
	var url = modal.find('input[name="business"]:checked').val();
	console.log(url);
	$('input[name="businessUrl"]').val(url);
	var locationUrl = window.location.origin+"/login/"+url;
	if (history.pushState) {
		window.history.pushState({path:locationUrl},'',locationUrl);
		console.log(locationUrl);
	

		$('.form-login').submit();
	}
});
