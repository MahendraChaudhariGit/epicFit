var FormWizardBusinessPlan = function () {
    var wizardContent = '';
    var wizardForm = $('#businessplan-form');
    var numberOfSteps = $('.swMain > ul > li').length;
    var ifSendAjax = false;
    var initWizard = function () {
    	
        // function to initiate Wizard Form
        wizardContent.smartWizard({
            //selected: 1,
            keyNavigation: false,
            onLeaveStep: leaveAStepCallback,
            onShowStep: onShowStep,
        });
        var numberOfSteps = 0;
        initValidator();
    };
      
    var initValidator = function () {
        
        $.validator.setDefaults({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            ignore: ':hidden',
            rules: {
                /*firstName: {
                    minlength: 2,
                    required: true
                },
                lastName: {
                    minlength: 2,
                    required: true
                },
                 email: {
                    required: true,
                    email: true
                },
                password: {
                    minlength: 6,
                    required: true
                },
                password2: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                }*/
            },
            messages: {
                firstName: "First Name is required",
                lastName: "Last Name is required"
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });
    };
    /*var displayConfirm = function () {
        $('.display-value', form).each(function () {
            var input = $('[name="' + $(this).attr("data-display") + '"]', form);
            if (input.attr("type") == "text" || input.attr("type") == "email" || input.is("textarea")) {
                $(this).html(input.val());
            } else if (input.is("select")) {
                $(this).html(input.find('option:selected').text());
            } else if (input.is(":radio") || input.is(":checkbox")) {

                $(this).html(input.filter(":checked").closest('label').text());
            } else if ($(this).attr("data-display") == 'card_expiry') {
                $(this).html($('[name="card_expiry_mm"]', form).val() + '/' + $('[name="card_expiry_yyyy"]', form).val());
            }
        });
    };*/
    var onShowStep = function (obj, context) {
    	if(context.toStep == numberOfSteps){
    		$('.anchor').children("li:nth-child(" + context.toStep + ")").children("a").removeClass('wait');
            //displayConfirm();
    	}
        $(".next-step").unbind("click").click(function (e) {
            e.preventDefault();
            var field = $(this);
            //if((field).hasClass('bp-first-step'))
            setBusinessPlan(field);
            //stepController(field,'next');
            wizardContent.smartWizard("goForward");
        });
        $(".back-step").unbind("click").click(function (e) {
            e.preventDefault();
            var field = $(this);
            //stepController(field,'back');
            wizardContent.smartWizard("goBackward");
        });
        $(".go-first").unbind("click").click(function (e) {
            e.preventDefault();
            wizardContent.smartWizard("goToStep", 1);
        });
        $(".finish-btn").unbind("click").click(function (e) {
            e.preventDefault();
            var field = $(this);
            setBusinessPlan(field);
            //page reload.
        });
        $(".finish-step").unbind("click").click(function (e) {
            e.preventDefault();
            onFinish(obj, context);
        });
    };
    var leaveAStepCallback = function (obj, context) {
        // return false to stay on step and true to continue navigation
        var isValid = validateSteps(context.fromStep, context.toStep);

        return isValid;
    };

    var onFinish = function (obj, context) {
        if (validateAllSteps()) {
            alert('form submit function');
            $('.anchor').children("li").last().children("a").removeClass('wait').removeClass('selected').addClass('done').children('.stepNumber').addClass('animated tada');
            //wizardForm.submit();
        }
    };

    var validateSteps = function (stepnumber, nextstep) {
        var isStepValid = false;
        
        
        if (numberOfSteps >= nextstep && nextstep > stepnumber) {
        	
            // cache the form element selector
            if (wizardForm.valid()) { // validate the form
                wizardForm.validate().focusInvalid();
                for (var i=stepnumber; i<=nextstep; i++){
        		$('.anchor').children("li:nth-child(" + i + ")").not("li:nth-child(" + nextstep + ")").children("a").removeClass('wait').addClass('done').children('.stepNumber').addClass('animated tada');
        		}
                //focus the invalid fields
                isStepValid = true;
                return true;
            };
        } else if (nextstep < stepnumber) {
        	for (i=nextstep; i<=stepnumber; i++){
        		$('.anchor').children("li:nth-child(" + i + ")").children("a").addClass('wait').children('.stepNumber').removeClass('animated tada');
        	}
            
            return true;
        } 
    };
    var validateAllSteps = function () {
        var isStepValid = true;
        // all step validation logic
        return isStepValid;
    };
    return {
        init: function (wizard) {
        	 wizardContent = $(wizard);
            initWizard();
        }
    };
}();


/*Start: business accordian setup */
$(document).ready(function(){
    

    setTimeout(function() {
        $('.panel-collapse').trigger('click');
    }, 100);

    setTimeout(function() {
        $('.first-col').trigger('click');
    }, 100);
    //$('.panel-collapse').trigger('click');
     
 	/*$('.bp-next-step').click(function(e){
 	    e.preventDefault();
        stepController($(this),'next');
   	     //setBusinessPlan($(this));      

    });*/

    /*$('.bp-back-step').click(function(e){
     e.preventDefault();
   	  //var current_step = parseInt($(this).data('stepval'));
   	  //var next_step= current_step-1;
      stepController($(this),'back');
   	  /*$('#bp-step-'+next_step).removeClass('hidden');
   	  $('#bp-step-'+current_step).addClass('hidden');
    });*/
});
/*End: business accordian setup */

function stepController(field,step){
    var current_step = parseInt(field.data('stepval'));
    var next_step;
    if(step=='next')
        next_step= current_step+1;
    else if(step=='back')
        next_step= current_step-1;
    
    $('#bp-step-'+next_step).removeClass('hide');
    $('#bp-step-'+current_step).addClass('hide');  
}

function setBusinessPlan(field){
    
        /*var current_step = parseInt(field.data('stepval'));
        var next_step= current_step+1;
        var fieldName=$('#bp-step-'+current_step).find('textarea');
        var fieldId = $('[name="businessplan_id"]');
        var businessPlanid=fieldId.val();*/
        //var form = $('#businessplan-form');
        /*formData['text_data']=fieldName.val();
        formData['col_name']=fieldName.attr('name');*/
        var formData={};
        //var form = field.closest('.panel-group');
        var form = field.closest('.content');
        var group_name=form.data('group');
        formData['group_name']=group_name;

        var idField = $('[name="businessplan_id"]');
        var businessPlanid=idField.val();

        form.find(':input').each(function(){
            formData[$(this).attr('name')] = $(this).val()
        });
    
        //console.log(form);
        
        if(businessPlanid)
            formData['businessplan_id']=businessPlanid;

    
        $.ajax({
            url: public_url+'business-plan',
            method: "POST",
            data: formData,
            success: function(data) {
                myObj=JSON.parse(data);
                if(myObj.status=='succsess'){
                    if(group_name=='ex_summary')
                        idField.val(myObj.insertedId);
                    if(group_name=='financial_plan_group')
                        location.reload();
                }
            }
        });
}


