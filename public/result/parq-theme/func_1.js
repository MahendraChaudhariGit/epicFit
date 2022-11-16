/*  Wizard */
jQuery(function($) {
    "use strict";
    // Chose below which method to send the email, available:
    // Simple phpmail text/plain > send_email_1.php (default)
    // PHPMailer text/html > phpmailer/send_email_1_phpmailer.php
    // PHPMailer text/html SMTP > phpmailer/send_email_1_phpmailer_smtp.php
    // PHPMailer with html template > phpmailer/send_email_1_phpmailer_template.php
    // PHPMailer with html template SMTP > phpmailer/send_email_1_phpmailer_template_smtp.php
    $('form#wrapped').attr('action', 'send_email_1.php');
    $("#wizard_container").wizard({
        stepsWrapper: "#wrapped",
        submit: ".submit",
        animations: {
            show: {
                options: {
                    duration: 0
                },
                properties: {
                    opacity: "show"
                }
            },
            hide: {
                options: {
                    duration: 0
                },
                properties: {
                    opacity: "hide"
                }
            }
        },
        unidirectional: false,
        beforeSelect: function(event, state) {
    
            // console.log($(this).wizard('fastForward',2));
            // console.log($(this).wizard('forward',1));
            if ($('input#website').val().length != 0) {
                return false;
            }
            if (!state.isMovingForward)
                return true;
            var inputs = $(this).wizard('state').step.find(':input');
            return !inputs.length || !!inputs.valid();
        },
        afterForward: function(event, state){
            var stepIndex = $(this).wizard('state').stepIndex;
            var refer = $('input[name="referrer"]:checked').val();
            console.log(stepIndex, refer);
            var step_complete = $("#selected-step").val();
            var current_step = $('.submit-step').attr('data-step');
            console.log(step_complete,current_step);
            // if(step_complete == 4 && current_step == 5){
            //     if($('.submit-step').is(':disabled')){
            //         $('.forward').prop("disabled", false);
            //      }
            // }
            if(stepIndex > 9){
                $(".question-step").text(stepIndex);
            }else{
                $(".question-step").text('0'+stepIndex);
            }
          
            if(stepIndex == 1 && refer != 'referral' && refer != undefined){
                $('.forward').trigger('click');
            }
        },
        afterBackward: function(event, state){
            var stepIndex = $(this).wizard('state').stepIndex;
            var refer = $('input[name="referrer"]:checked').val();
            var step_complete = $("#selected-step").val();
            var current_step = $('.submit-step').attr('data-step');
            console.log(step_complete,current_step);
            // if(step_complete == 4 && current_step == 5){
            //     if($('.submit-step').is(':disabled')){
            //         $('.forward').prop("disabled", false);
            //      }
            // }
            console.log(stepIndex, refer);
            if(stepIndex > 9){
                $(".question-step").text(stepIndex);
            }else{
                $(".question-step").text('0'+stepIndex);
            }
            if(stepIndex == 1 && refer != 'referral' && refer != undefined){
                $('.backward').trigger('click');
            }
        },
    })
    //  progress bar
    $("#progressbar").progressbar();
    $("#wizard_container").wizard({
        afterSelect: function(event, state) {
           
            $("#progressbar").progressbar("value", state.percentComplete);
            $("#location").text("" + state.stepsComplete + " of " + state.stepsPossible + " completed");
        }
    });
});


// Input name and email value
function getVals(formControl, controlType) {
    switch (controlType) {

        case 'name_field':
            // Get the value for input
            var value = $(formControl).val();
            $("#name_field").text(value);
            break;

        case 'email_field':
            // Get the value for input
            var value = $(formControl).val();
            $("#email_field").text(value);
            break;
    }
}

$(document).on('click', '.step-back',function(){
    var data_val = $(this).data("step-url");
    var data_step = $(this).data("step-no");
    console.log('$data_val == ', data_val, data_step);
   var check = $('input[name = step_status]').attr('data-parq'+data_step);
    if(check == "completed"){
        window.location.href = data_val;
    }
});

$(document).on('click', '.step-forward',function(){
    var data_val = $(this).data("step-url");
    var data_step = $(this).data("step-no");
    console.log('$data_val == ', data_val, data_step);
   var check = $('input[name = step_status]').attr('data-parq'+data_step);
    if(check == "completed"){
        window.location.href = data_val;
    }

});