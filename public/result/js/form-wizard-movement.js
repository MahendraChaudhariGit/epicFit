var mapArray = {Squat:'1',Lunge:'2',Bend:'3',Pull:'4',Push:'5',Rotation:'6'},
    goStep = 1,
    no_ofstep,
    elemNameArray=[],
    GScore = 0,
    move_modal = $('#movementModal'),
    stepHedding = move_modal.find('#stepHedding');

var FormWizardMovement = function () {
    "use strict";
    var wizardContent = "";
    var wizardForm = $('#movement-form');
    var numberOfSteps = no_ofstep;
    var ifSendAjax = false;
   
    var initWizard = function () {

        move_modal.find(".next-step").unbind("click").click(function (e) {
            e.preventDefault();
            
            if(goStep < no_ofstep){
                var pre_stepnum = stepHedding.find('#m-stp-'+goStep).data('stepnum');
                goStep++;
                var curr_stepnum = stepHedding.find('#m-stp-'+goStep).data('stepnum');

                var tab = $("#movement-step-"+pre_stepnum).find('.viewTab li.active a'),
                activeTab = tab.text(),
                value = tab.attr('href').split('#');
                var id =$('#'+value[1]);
                if(activeTab == 'Front video'){
                var frontRecordTab = id.find('.uploadVideo')[0];
                var frontSideTab = id.find('.recordedVideo')[0];
                frontRecordTab.pause();
                frontSideTab.pause();
                }else if(activeTab == 'Side video'){
                    var frontRecordTab = id.find('.uploadVideo')[0];
                    var frontSideTab = id.find('.recordedVideo')[0];
                    frontRecordTab.pause();
                    frontSideTab.pause();
                }
                nextStepFunction(pre_stepnum, curr_stepnum);
                setupStepManual(goStep-1, goStep, 'next')
                showMovementList(curr_stepnum);
                //wizardContent.smartWizard("goToStep", goStep);
            }
        });
        move_modal.find(".back-step").unbind("click").click(function (e) {
            e.preventDefault();
            
            if(goStep > 1){
                var pre_stepnum = stepHedding.find('#m-stp-'+goStep).data('stepnum');
                goStep--;
                var curr_stepnum = stepHedding.find('#m-stp-'+goStep).data('stepnum');
               
               

                var tab = $("#movement-step-"+pre_stepnum).find('.viewTab li.active a'),
                activeTab = tab.text(),
                value = tab.attr('href').split('#');
                var id =$('#'+value[1]);
                if(activeTab == 'Front video'){
                var frontRecordTab = id.find('.uploadVideo')[0];
                var frontSideTab = id.find('.recordedVideo')[0];
                frontRecordTab.pause();
                frontSideTab.pause();
                }else if(activeTab == 'Side video'){
                    var frontRecordTab = id.find('.uploadVideo')[0];
                    var frontSideTab = id.find('.recordedVideo')[0];
                    frontRecordTab.pause();
                    frontSideTab.pause();
                }
                
                backStepFunction(pre_stepnum, curr_stepnum);
                setupStepManual(goStep+1, goStep, 'back')
                //showMovementList(curr_stepnum);
                //wizardContent.smartWizard("goToStep", goStep);
            }
        });
        move_modal.find(".finish-btn").unbind("click").click(function (e) {
            e.preventDefault();
            $(this).prop('disabled',true);
            var client_id = $(this).data('client-id')
            submitFunction(client_id,'finish','client-side');
        });
        // move_modal.find(".save-draft").unbind("click").click(function (e) {
        //     e.preventDefault();
        //     var client_id = $(this).data('client-id')
        //     submitFunction(client_id,'draft','client-side');
        // });
    };

    var onShowStep = function (obj, context) {

    };
    var leaveAStepCallback = function (obj, context) {
        return true;
    };
    var onFinish = function (obj, context) { 
        if (validateAllSteps()) {
            alert('form submit function');
            $('.anchor').children("li").last().children("a").removeClass('wait').removeClass('selected').addClass('done').children('.stepNumber').addClass('animated tada');
        }
    };
    var validateShowStep = function(stepnumber,isclick) {
       
    }
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

/* Start : document load function */
    $(document).ready(function(){
        /* Start : on click radio button */
        move_modal.find(".save-draft").prop('disabled',true)
            move_modal.find('input[type=radio]').unbind('click').click(function(){
                move_modal.find(".save-draft").prop('disabled',false)
                var $this = $(this),
                fieldset = $this.closest('fieldset'),
                step_name = fieldset.data('stepname'),
                step_number = mapArray[step_name];
                if($this.closest('.injuryList').hasClass('lower'))
                    radioBtnAction(step_number, 'onlylower');
                else
                    radioBtnAction(step_number, 'all');
                scoreCalc($this);
            })
        /* End : on click radio button */

        /* Start: modal hide event */
            $("#movementModal").on('hide.bs.modal', function () {
                $('#movementStepSetupModal').modal("hide");
                no_ofstep = 0;
                goStep = 1;

                var form = move_modal.find('#movement-form');
                move_modal.find(':input').each(function(){
                    var $this = $(this);

                    if($this.attr("type") == "radio")
                        $this.attr('checked', false);
                    else if($this.attr("type") == "checkbox")
                        $this.attr('checked', false);
                    else if($this.is('textarea'))
                        $this.val('');
                    else if($this.attr("type")=='hidden') 
                        $this.val('');   
                });
                form.trigger("reset");
                finishBtnShowHide(1);
                //modal.find('.move-content').hide();
                //modal.find('#stepHedding').empty();
                //modal.find('.step-Squat').addClass('hidden');
            });
            /* End: modal hide event */
    })
/* End : document load function */

/* Start : show dd value in small divice and body part hide */
    function showMovementList(step){
        var selectedStep = move_modal.find('#movement-step-'+step);
        if(selectedStep.length > 0){
            selectedStep.find('.injuryList').addClass('hidden');
            selectedStep.find('.lower').removeClass('hidden');
        }
    }
/* End : show dd value in small divice and body part hide */

/* Start: add/remove selected, done and disabled class in step header manually */
    function setupStepManual(prestep, currentStep, type){
        finishBtnShowHide(currentStep);
        if(type=='next'){
            move_modal.find("#m-stp-"+currentStep).addClass('selected');
            move_modal.find("#m-stp-"+currentStep).removeClass('disabled').removeClass('done').attr("isDone",1);
            move_modal.find("#m-stp-"+prestep).addClass('done');
            move_modal.find("#m-stp-"+prestep).removeClass('selected');
        }else if(currentStep > 0){
            move_modal.find("#m-stp-"+prestep).addClass('done');
            move_modal.find("#m-stp-"+prestep).removeClass('selected');
            move_modal.find("#m-stp-"+currentStep).addClass('selected');
            move_modal.find("#m-stp-"+currentStep).removeClass('done').attr("isDone",1);
        }
        
    }
/* End: add/remove selected, done and disabled class in step header manually */

/* Start: show/hide finish button */
    function finishBtnShowHide(step){
        var nextBtn = move_modal.find(".next-step"),
            // draftBtn = move_modal.find(".save-draft"),
            finishBtn = move_modal.find('.finish-btn');
        if(step==no_ofstep){
            finishBtn.removeClass("hidden");
            nextBtn.addClass("hidden");
            // draftBtn.addClass("hidden");
        }
        else{
            finishBtn.addClass("hidden");
            nextBtn.removeClass("hidden");
            // draftBtn.removeClass("hidden");
        }
    }        
/* End: show/hide finish button */            

/* Start: next step function */ 
    function nextStepFunction(preStep, currStep){
        if(preStep == 1)
            move_modal.find('#movement-step-'+preStep).addClass('hidden').hide();
        else
            move_modal.find('#movement-step-'+preStep).hide(); 
        move_modal.find('#movement-step-'+currStep).show();
        // radioBtnAction(currStep, 'all');
        // scoreLoadFunction(currStep);
    }
/* End: next step function */

/* Start: back step function */ 
    function backStepFunction(preStep, currStep){
        if(currStep == 1)
            move_modal.find('#movement-step-'+currStep).removeClass('hidden').show();
        else
            move_modal.find('#movement-step-'+currStep).show();
        move_modal.find('#movement-step-'+preStep).hide();
        // radioBtnAction(currStep, 'all');
        // scoreLoadFunction(currStep);  
    }
/* End: back step function */

/* Start: call function when modal open */
    function callLoadFunction(){
        var step_id = $('#stepHedding').find('.selected').attr('href');
        var fieldset = move_modal.find(step_id).find('fieldset');
        var st_name = fieldset.data('stepname');
        var st_score = move_modal.find('input[name="'+st_name+'StepVal"]').val();
        if(st_score == '')
            st_score = 0;
        sacoreBar(st_name, st_score, fieldset);
        if(st_score == 0)
            move_modal.find('.next-step').attr("disabled", "disabled");
        else
            move_modal.find('.next-step').removeAttr("disabled");
    }
/* End : call function when modal open */

/* Start: Form Submit function */
    function submitFunction($client_id,$save_status,$data_from){
        var form = move_modal.find('#movement-form'),
            movementid = form.find('input[name="movementId"]').val(),
            formData = {},
            linkUrl = '';

            formData['clientId']=$client_id;
            move_modal.find(':input').each(function(){
                var $this = $(this),
                    redName = $this.attr('name');
                    console.log(redName,$this.val());
                if(($this.attr("type") == "radio") && $this.is(':checked'))
                    formData[redName] = $this.val();
                else if($this.attr("type") == "checkbox" && $this.is(':checked'))
                    formData[redName] = $this.val();
                else if($this.is('textarea') && $this.val() != '')
                    formData[redName] = $this.val();
                else if($this.attr("type")=='hidden' && $this.val() != '') 
                    formData[redName] = $this.val();   
            });
            formData['save_status'] = $save_status;  
            formData['data_from'] = $data_from;  
            if (movementid != ''){
                linkUrl = public_url+'clients/movement/update/'+movementid;
            }else{
                linkUrl = public_url+'clients/movement/save';
            }

            $.ajax({
                url: linkUrl,
                method: "POST",
                data: formData,
                success: function(data) {
                    myObj=JSON.parse(data);
                    if(myObj.status=='succsess'){
                        //form[0].reset();
                        form.trigger("reset");
                        reloadPageWithTab('#movements');
                        //location.reload();
                    }
                }
            });
    }
/* End: Form Submit function */
