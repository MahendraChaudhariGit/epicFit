/*  Wizard */
jQuery(function($) {
    "use strict";
    // Chose below which method to send the email, available:
    // Simple phpmail text/plain > send_email_1.php (default)
    // PHPMailer text/html > phpmailer/send_email_1_phpmailer.php
    // PHPMailer text/html SMTP > phpmailer/send_email_1_phpmailer_smtp.php
    // PHPMailer with html template > phpmailer/send_email_1_phpmailer_template.php
    // PHPMailer with html template SMTP > phpmailer/send_email_1_phpmailer_template_smtp.php
    // $('form#wrapped').attr('action', 'send_email_1.php');
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
            var selectGoal = $('input[name="chooseGoal"]:checked').val();
            if(selectGoal == 'choose_form_template' && selectGoal != undefined){
            var check = $('#wrapped .wizard-step.current');
            
            if(check.hasClass('goal-predifine-template')){
                if(!$('input[name=template]:checked').length){
                    return false;
                }
            }

            if(check.hasClass('newHabitForm')){
                var habit_index = $('#habit-index').val();
                var new_habit_index = parseInt(habit_index);
                var no_of_index = $('#habitlist').find('tr').length;
                
            if(no_of_index < 4 && no_of_index != new_habit_index &&  $('#editFormWizard').val() != 1 && new_habit_index != 0){
                if(new_habit_index == parseInt(no_of_index)-1){
                    $('#habit-index').val(parseInt(new_habit_index)+1);
                }
               
                scrollToGoalTop();
                return false;
            }else{
                return true;
            }
            }else if(check.hasClass('taskNext')){
                var task_index = $('#task-index').val();
                var new_task_index = parseInt(task_index);
                var no_of_index = $('#tasklist').find('tr').length;
                if(no_of_index < 10 && no_of_index != new_task_index && new_task_index != 0 && $('#editFormWizard').val() != 1){
                   console.log('no_of_index', no_of_index);
                   console.log('new_task_index', new_task_index);
                   console.log('no_of_index', $('#editFormWizard').val());
                    if(new_task_index == parseInt(no_of_index)-1){
                        $('#task-index').val(parseInt(new_task_index)+1);
                    }
                    scrollToGoalTop();
                return false;
                }else{
                    return true;
                }
            }
        }
            if ($('input#website').val().length != 0) {
                return false;
            }
            if (!state.isMovingForward)
                return true;
            var inputs = $(this).wizard('state').step.find(':input');
            return !inputs.length || !!inputs.valid();
        },
       afterForward: function(event, state){
        var check =$('#wrapped .wizard-step.current');
        $('.milestoneData').data('next',0); 
        $('.newHabitForm').data('next',0);
        $('.newTaskData').data('next',0);
        $('.reviewGoal').data('next',0);
        $('.milestoneData').data('value',0); 
        $('.goalName').data('value',0);
        $('.newHabitForm').data('value',0);
        $('.newTaskData').data('value',0);
        $('.showNext').data('value',0);
        $('.showNext').data('next',0);
        $('.newTask').data('next',0);
        $('.newTask').data('value',0);
        

        if(check.hasClass('milestoneData')){
         $('.milestoneData').data('next',1);
         $('.milestoneData').data('value',1);
            return false;
        }
      
        if(check.hasClass('newHabitForm')){
            $('.newHabitForm').data('next',1);
            $('.newHabitForm').data('value',1);
            return false;
        }
        if(check.hasClass('showNext')){
            $('.showNext').data('next',1);
            $('.showNext').data('value',1);
               return false;
           }
        if(check.hasClass('newTask')){
            $('.newTask').data('next',1);
            $('.newTask').data('value',1);
            return false;
        }
        if(check.hasClass('reviewGoal')){
            $('.reviewGoal').data('next',0);
            $('.reviewGoal').data('value',0);
            return false;
        }
            var stepIndex = $(this).wizard('state').stepIndex;
            var refer = $('input[name="referrer"]:checked').val();
            var selectGoal = $('input[name="chooseGoal"]:checked').val();
            
            if((stepIndex == 1 && refer != 'referral' && refer != undefined) || (stepIndex == 1 && selectGoal != 'choose_form_template' && selectGoal != undefined)){
                $('.forward').trigger('click');
            }
        },
        afterBackward: function(event, state){
            scrollToGoalTop();
            console.log('hii');
            var check =$('#wrapped .wizard-step.current');
            /* temp 1 and 4 */
            // var stepIndex = $(this).wizard('state').stepIndex;
            // var templet_id = $('input[name="template"]:checked').data('id');
            // console.log('templet_id', templet_id);
            // if(templet_id == 1 || templet_id == 4){
            //     if(stepIndex == 0 || stepIndex == 1){
            //         switch (templet_id) {
            //             case 1:
            //                 var image_path = BASE_URL + '/assets/images/next-weightmanagement.png';
            //                 $(".slide-img").attr("src",image_path); 
            //             break;
            //             case 4:
            //                 var image_path = BASE_URL + '/assets/images/next-improve_h.png';
            //                 $(".slide-img").attr("src",image_path); 
            //             break;
            //         }
            //     }
            //  }
             /* temp 1 and 4 */
            console.log('hiii===stepIndex',  stepIndex);
            $('.milestoneData').data('value',0); 
            $('.reviewGoal').data('value',0); 
            $('.goalName').data('value',0);
            $('.newHabitForm').data('value',0);
            $('.newTask').data('value',0);
            $('.newGoalStep').data('value',0);
            $('.newTaskData').data('value',0);
            $('.newTask').data('next',0);
            $('.showNext').data('value',0);
            $('.showNext').data('next',0);
            if(check.hasClass('milestoneData')){
                $('.milestoneData').data('value',1);
                return false;
            }
            if(check.hasClass('newGoalStep')){
                $('.newGoalStep').data('value',1);
                return false;
            }
            if(check.hasClass('reviewGoal')){
                $('.reviewGoal').data('value',0);
                return false;
            }
            if(check.hasClass('goalName')){
                $('.goalName').data('value',1);
                return false;
            }
            if(check.hasClass('newHabitForm')){
                $('.newHabitForm').data('value',1);
                return false;
            }
            if(check.hasClass('newTask')){
                $('.newTask').data('value',1);
                $('.newTask').data('next',1);
                return false;
            }
            if(check.hasClass('showNext')){
                $('.showNext').data('value',1);
                $('.showNext').data('next',1);
                return false;
            }
            if(check.hasClass('newTaskData')){
                $('.newTaskData').data('value',1);
                return false;
            }
            
            var stepIndex = $(this).wizard('state').stepIndex;
            var refer = $('input[name="referrer"]:checked').val();
            var selectGoal = $('input[name="chooseGoal"]:checked').val();
            if((stepIndex == 1 && refer != 'referral' && refer != undefined) || (stepIndex == 1 && selectGoal != 'choose_form_template' && selectGoal != undefined)){
                $('.backward').trigger('click');
            }
        },
    })
    //  progress bar
    $("#progressbar").progressbar();
    $("#wizard_container").wizard({
        afterSelect: function(event, state) {
           console.log("step----"+ state.stepsComplete);
            $("#progressbar").progressbar("value", state.percentComplete);
            $("#location").text("" + state.stepsComplete + " of " + state.stepsPossible + " completed");

            // Add classed to move next and prev
            if(state.stepsComplete == 13){
                removeClasses();
                $('.step-back').addClass('formStepfirst');
                $('.step-forward').addClass('formStepThird');
            }

            if(state.stepsComplete == 16){
                removeClasses();
                $('.step-back').addClass('formStepSecond');
                $('.step-forward').addClass('formStepFourth');
            }

            if(state.stepsComplete == 17){
                removeClasses();
                $('.step-back').addClass('formStepSecond');
                $('.step-forward').addClass('formStepFourth');
            }

            if(state.stepsComplete == 18){
                removeClasses();
                $('.step-back').addClass('formStepThird');
                $('.step-forward').addClass('formStepFive');
            }

            if(state.stepsComplete == 19){
                removeClasses();
                $('.step-back').addClass('formStepThird');
                $('.step-forward').addClass('formStepFive');
            }

            if(state.stepsComplete == 20){
                removeClasses();
                $('.step-back').addClass('formStepFourth');
                $('.step-forward').addClass('formStepFive');
            }

            if(state.stepsComplete > 13 && state.stepsComplete < 16){
                removeClasses();
                $('.step-back').addClass('formStepSecond');
                $('.step-forward').addClass('formStepThird');
            }
            // Add classed to move next and prev - end

            if(state.stepsComplete < 13){
                if(state.stepsComplete < 10){
                    $(".question-step").text('0'+state.stepsComplete);
                } else {
                    $(".question-step").text(state.stepsComplete);
                }
                //define names
                $('.prev-name').text('DEFINE YOUR GOAL');     
                $('.current-section').text('DEFINE YOUR GOAL');
                $('.next-name').text('ESTABLISH YOUR MILE STONES');
                $(".section-step").text('01');
                //define names - end
                removeClasses();
                $('.step-back').addClass('formStepfirst');
                $('.step-forward').addClass('formStepSecond');
            }else{
                $(".question-step").text(state.stepsComplete);
            }
            // $(".question-step").text('0'.state.stepsComplete);
            $(".all-question-step").text(state.stepsPossible);

            if(state.stepsComplete > 12 && state.stepsComplete < 16){

                //add data filled or not
                /*$('.step').each(function(){
                    if($(this).is(":visible")){
                        $(this).attr('data-filled', 1);
                    }
                });*/
                //add data filled or not - end

                $('.prev-name').text('DEFINE YOUR GOAL');     
                $('.current-section').text('ESTABLISH YOUR MILE STONES');
                $('.next-name').text('ESTABLISH NEW HABIT');
               
                $(".section-step").text('02');
                $(".all-section-step").text('05');
            }
            if(state.stepsComplete > 15 && state.stepsComplete < 18){

                //add data filled or not
               /* $('.step').each(function(){
                    if($(this).is(":visible")){
                        $(this).attr('data-filled', 1);
                    }
                });*/
                //add data filled or not - end

                $('.prev-name').text('ESTABLISH YOUR MILE STONES');
                $('.current-section').text('ESTABLISH NEW HABIT');
                $('.next-name').text('CREATE TASKS');

                $(".section-step").text('03');
                $(".all-section-step").text('05');
            }
            if(state.stepsComplete > 17 && state.stepsComplete < 20){

                //add data filled or not
                /*$('.step').each(function(){
                    if($(this).is(":visible")){
                        $(this).attr('data-filled', 1);
                    }
                });*/
                //add data filled or not - end

                $('.prev-name').text('ESTABLISH NEW HABIT');
                $('.current-section').text('CREATE TASKS');
                $('.next-name').text('SMART REVIEW');
                $(".section-step").text('04');
                $(".all-section-step").text('05');
            }
            if(state.stepsComplete > 19){

                //add data filled or not
                /*$('.step').each(function(){
                    if($(this).is(":visible")){
                        $(this).attr('data-filled', 1);
                    }
                });*/
                //add data filled or not - end

                $('.prev-name').text('CREATE TASKS');
                $('.current-section').text('SMART REVIEW');
                $('.next-name').text('SMART REVIEW');
                $(".section-step").text('05');
                $(".all-section-step").text('05');
            }
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
function scrollWin(x, y) {
    window.scrollBy(x, y);
  }

function removeClasses(){
    $('.step-back').removeClass('formStepfirst'); 
    $('.step-back').removeClass('formStepSecond'); 
    $('.step-back').removeClass('formStepThird'); 
    $('.step-back').removeClass('formStepFourth');
    $('.step-back').removeClass('formStepFive');

    $('.step-forward').removeClass('formStepfirst'); 
    $('.step-forward').removeClass('formStepSecond'); 
    $('.step-forward').removeClass('formStepThird'); 
    $('.step-forward').removeClass('formStepFourth');
    $('.step-forward').removeClass('formStepFive');
}