/********************* table of content *****************************/
/**                                                                **/
/* RESISTANCE TRAINING : step = 8  (FX.PlanType = 8)             */
/* CARDIOVASCULAR ACTIVITY  : step = 9  (FX.PlanType = 9)        */
/* PROGRAM GENERATOR  : step = 5  (FX.PlanType = 5)              */
/* PROGRAM LIBRARY  : step = 6  (FX.PlanType = 6)                */
/* PROGRAM DESIGNER : step = 7  (FX.PlanType = 7)                */
/* male = 2, female = 1                                          */
/**                                                                **/
/************************* End **************************************/

var customPlanDatatable = {},
    activityChoosed = 0;
    workout_id = 0,
    keySearchTimeoutId = null,
    checkExeWorkExist = {},
    planFilter = {};

/* Start: Document load function */
$(document).ready(function(){

  /* Hiding panels */
  $('.panel[data-step]:not(.pinned)').addClass('hidden');

  /* Set Client Id to api */
  FX.setClientid($('input[name="fit_clientId"]').val());

  /* Set Client gender to api */
  FX.setGender($('input[name="fit_gender"]').val());

  /* Initialize custom vailidation */
  initCustomValidator();

  /*Initializing datatable */
  $('#design-program-datatable').dataTable({"searching": false, "paging": false, "info": false }); 
  //if($('#client-datatable').length > 0)
    //customPlanDatatable = $('#client-datatable').DataTable();

  /* start: Initialize slider */
    $("#weekSlider-crm").labeledslider({
      min: 1,
      tickInterval: 1,
      range: "min"
    });

    var timeLabels = new Array();
    for(var i = 0; i <= 360; i++)
      timeLabels.push(FX.minsToHourMin(i));
    $("#timeSlider-crm").labeledslider({
      max: 360,
      tickInterval: 30,
      value: 60,
      tickLabels: timeLabels,
      range: "min",
      min: 0,
      create: function(event, ui){
        updatePerDayWorkoutTime(60);
      },
      slide: function(event, ui){
        updatePerDayWorkoutTime(ui.value);
      }
    });
  /* End: Initialize slider */

  /* start: Make html field editable */
  $(".editable").click(function(e){
    var $this = $(this);

    $this.hide();
    $this.next().removeClass('hidden')
  });
  /* end: Make html field editable */

  /* Start: Activating clicked option and deactivating sibling options */
    $('body').on("click", 'a.inactive', function(e){
      e.preventDefault();
      var $this = $(this);
      $this.closest('[data-name]:not([data-name=""])').find('a:not(.inactive)').removeClass("crm-select-plan").addClass("inactive");
      $this.removeClass("inactive");
      $this.addClass("crm-select-plan");
    });
  /* End: Activating clicked option and deactivating sibling options */

  /* Start: Run validation over options and open related accordian */
    $('body').on("click", '.open-step', function(e){
      e.preventDefault();
      var $this = $(this),
          panel = $this.closest('.panel'),
          panelBody = panel.children('.panel-body'),
          runValidation = $this.data('run-validation'),
          isValid = true;

      FX.clearNotific(panelBody);
      if(panel.data('step') == 'programChoose'){
        FX.setPlanType(FX.numericStringToInt($this.children('input').val()))
        activityChoosed = 0;
      }
      else if(panel.data('step') == 'activityChoose'){
        var newActivityChoosed = FX.numericStringToInt($this.children('input').val());
        if(newActivityChoosed != activityChoosed){
          $('[data-step="currentAbility"]').nextAll(':not(.pinned)').addClass('hidden')
        }
        activityChoosed = newActivityChoosed;
      }
      else if(panel.data('step') == 'designProgram'){
        checkExeWorkExist = {}; // reset initial
        var id = $this.closest('tr').data('id');
        FX.setClientPlanId(id);
        FX.loadProgram(id, resetAndPopulateTrainingSegments);
      }

      else if(panel.data('step') == 'planMyProgram'){
        var daysWrapper = $('#days-crm'),
          daysField = daysWrapper.find('input'),
          daysRequired = daysWrapper.data('days-required'),
          checkedDays = daysField.filter(':checked').length;

        FX.clearNotific($this.closest('.panel-body'));
        if(checkedDays < daysRequired){
          setFieldInvalid(daysWrapper.closest('.form-group'), 'Please select required days.', daysWrapper.next());
          isValid = false;
          return false;
        }
      }

      if(runValidation){
        var fieldGroups = panelBody.find('[data-name]:not([data-name=""])');
        if(fieldGroups.length){
          fieldGroups.each(function(){
            var selectedOpt = $(this).find('a:not(.inactive)');
            if(!selectedOpt.length){
              isValid = false;
              showNotific(FX.prepareNotific('error', "Please choose one option per section."), panelBody);
              return false;
            }
          });
        }
      }

      if(isValid){
        if(panel.data('step') == 'programChoose' && FX.PlanType == 7){
            FX.loadProgramsList(FX.PlanType);
        }
        else if(panel.data('step') == 'activityChoose'){ 
          planFilter['purpose'] = FX.numericStringToInt($this.children('input').val());
        }
        else if(panel.data('step') == 'activityHabits'){
          planFilter['curr_phy_act'] = getSelectedOptionValue('Habit');
          planFilter['prev_phy_act'] = getSelectedOptionValue('Experience');
          planFilter['next_phy_act'] = getSelectedOptionValue('Intensity');
          planFilter['curr_intensity_phy_act'] = getSelectedOptionValue('temp');
        }
        else if(panel.data('step') == 'currentAbility'){
          if(FX.PlanType != 6)
            planFilter['habit'] = FX.numericStringToInt($this.children('input').val()); 
        }
        else if(panel.data('step') == 'equipmentHave'){
          planFilter['equipment'] = getSelectedOptionValue('Method');
        }
        else if(panel.data('step') == 'programWant'){
          // set for program want..
        }
        else
          $('#plansPreviewAccordion-crm').html('');
        
        openStep($this)
      }
    });
  /* End: Run validation over options and open related accordian */

  /* Start: Onchange workout open training segement */
    $(".choosetrainingSegment").change(function(){
      var $this = $(this),
          accordId = $this.attr('id'),
          panel = $('#accord_'+accordId).closest('.panel'),
          choosedTrainingsAccordionSection = $('#choosedTrainingsAccordion').parent('div');

      if($this.is(':checked')){
        choosedTrainingsAccordionSection.show();
        panel.removeClass('marked-to-delete').show();
      }
      else{
        if(!$(".choosetrainingSegment").filter(':checked').length)
          choosedTrainingsAccordionSection.hide();
        panel.addClass('marked-to-delete').hide();
      }
      programTotalTime();
    });
  /* End: Onchange workout checkbox open training segment */

  /* Start: Opening add exercise modal */
    $('.accordion-toggle button', $('#choosedTrainingsAccordion')).click(function(e){
      e.stopPropagation();
      e.preventDefault();
      workout_id = $(this).data('workout');

      var modal = $('#addexercise');

      var choosedExercRow = $(this).closest('.panel').find('.choosedExercRow'),
          exerciseList = $('#exerciseList');
          
      exerciseList.data('program-id', choosedExercRow.data('program-id'));
      exerciseList.data('work-out', choosedExercRow.data('work-out'));

      loadBodyAreasForExercise(FX.genderString == 'male'?maleAreasForEx:femaleAreasForEx, modal);

      toggleHeart(modal.find('#favSearch'), 'remove');
      modal.find('#keySearch').val('');
      modal.find('#muscle_group').val('');
      modal.find('#ability').val('');
      modal.find('#equipment').val('');
      modal.find('#category').val('');
      modal.find('#movement_type').val('');
      modal.find('#movement_pattern').val('');

      modal.modal('show');
      getExercises();
      //FX.UI.searchScroll = new FX.InfiniteScroller($('#exerciseList').parent(), getExercises);
      //getExercises();
    })
  /* End: Opening add exercise modal */

  /* start: Switching plus to tick and adding exercise */
    $('body').on("click", '.toggle-exercise', function(e){
      e.stopPropagation();
      toggleWaitShield("show");
      var $this = $(this),
          formData = {},
          exerciseList = $('#exerciseList'),
          exerciseDetailsModalFooter = $("#lungemodal").find('.modal-footer');
          
      if(typeof(exerciseList.data('program-id')) == 'undefined'){
        exerciseList.data('program-id',(FX.ClientPlanId));
      }
      
      FX.setExersiseId($this.parent().data('exercise-id'));
      formData.WorkOutName = exerciseList.data('work-out');
      formData.ClientPlanId = FX.ClientPlanId;
      formData.ExerciseId = FX.ExersiseId;

      FX.addExToProgram(formData, function(response){
          if(response.status == 'success'){
            FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));

            var modalId = $this.closest('.modal').attr('id');
            if(modalId == 'addexercise'){
              var sourceAddLnk = $this;
            }
            else if(modalId == 'lungemodal'){
              $this.hide();

              var sourceAddLnk = $('#addexercise').find('[data-exercise-type-id="'+exerciseDetailsModalFooter.data('exercise-type-id')+'"] .toggle-exercise');
            }
            sourceAddLnk.removeClass('toggle-exercise')
            sourceAddLnk.children().removeClass('fa-plus').addClass('fa-check');

            /*var newExercise = response.NewExercise;
            var x = [{WeekIndex:newExercise.WeekIndex, DayIndex:newExercise.DayIndex, WorkOut:newExercise.WorkOut, Priority:newExercise.Priority, ExerciseTypeID:newExercise.ExerciseTypeID,Name:newExercise.ExerciseType.ExerciseName,  ExerciseDesc:newExercise.ExerciseType.ExerciseDesc, Sets:newExercise.Sets, Repetition:newExercise.Repetition, RepOrSeconds:newExercise.RepOrSeconds, TempoDesc:newExercise.TempoDesc, TempoTiming:newExercise.TempoTiming, RestSeconds:newExercise.RestSeconds, ExerciseID:newExercise.ExerciseID, EditWorkoutId:newExercise.EditWorkoutId, FixedProgramID:newExercise.FixedProgramID, EstimatedTime:newExercise.EstimatedTime, Resistance:newExercise.Resistance}] //, IsReps:newExercise.ExerciseType.IsReps, HasWeight:newExercise.ExerciseType.HasWeight*/

            populateTrainingSegments(response.Exercises);
            /*FX.loadProgram(FX.ClientPlanId, populateTrainingSegments);*/
            
            if(FX.PlanType != 'undefined' && FX.PlanType == 5){
              var generatorModal = $('#generatorModal');
              generatorModal.modal({backdrop: 'static', keyboard: false })
              generatorModal.find('input[name="workout_name"]').val(formData.WorkOutName);
            }

            $('#choosedTrainingsAccordion').find('a[href="#accord_'+formData.WorkOut+'"]').trigger('click'); 

            toggleWaitShield("hide");
          }
      });
    });
  /* end: Switching plus to tick and adding exercise 

  /* start: Searching exercise by favorite */
    $("#favSearch").on( "click", function(e){
      e.preventDefault();
      toggleHeart($(this));
      getExercises();
    });
  /* end: Searching exercise by favorite */

  /* start: Searching exercise by keyword */
    $("#keySearch").on("keyup", function(e){
      clearTimeout(keySearchTimeoutId);
      keySearchTimeoutId = setTimeout(getExercises, 1000);
    });
    $(".searchExercise").on("change", function(e){
      getExercises();
    });
  /* end: Searching exercise by keyword */

  /* Start: Trainning Segment area hide and show */
    $('body').on('click','.showTrainingSeg', function(e){
      e.preventDefault();
      var $this = $(this),
          treningSegCls = $('.treningSegCls'),
          id = $this.parent().data('editworkout');
          
          treningSegCls.addClass('hidden');
      $('#segment_'+id).removeClass('hidden');
    })
  /* End: Trainning Segment area hide and showb */

  /* start: Delete exercise */
    $('body').on('click', '.delExercise', function(e){
      e.preventDefault();
      var $this = $(this),
          formData = {},
          planWorkoutId =  $this.parent().data('editworkout');

          //formData['ExerciseId'] = $this.parent().data('exercise-id');
          formData['planWorkoutExercise'] = $this.parent().data('editworkout');
          formData['plan_type'] = FX.PlanType;
          //formData['WorkoutName'] = $this.closest('.choosedExercRow').data('work-name');

          confirmEntityDelete('exercise', 'RemoveExerciseFromProgram', formData, function(response){
            if(response.status == 'success'){
              $this.closest('.exercise').remove();
              $('#segment_'+planWorkoutId).remove();
              programTotalTime();
            }
          });
    });
  /* end: Delete exercise */

  /* Start: hide plan workout exercise  */
    $('body').on('click', '.hidePlanWorkoutExe', function(e){
      e.preventDefault();
      var $this = $(this),
          planWorkoutId = $this.parent().data('planeworkexe-id');
          
          $('#segment_'+planWorkoutId).addClass('hidden');
    });
  /* End: hide plan workout exercise  */

  /* Start: Save Training segment edited data */
    $('body').on('click','.saveTrainingSeg', function(e){
      e.preventDefault();
      var $this = $(this),
          formData = {},
          form = $this.closest('.treningSeg-form'),
          isFormValid = form.valid(),
          workoutExerciseId = $this.closest('div').data('exercise-id'),
          EditWorkoutId = $this.closest('div').data('planeworkexe-id');
          

        formData['workoutExerciseId'] = workoutExerciseId;
        formData['editWorkoutId'] = EditWorkoutId;
        if(isFormValid){
          $.each($(form).find(':input').serializeArray(), function(i, field){
            formData[field.name] = field.value;
          });
          API.customPlanAjax('EditTrainingSegment', formData, function(response){
            if(response.status == 'success'){
              $('.treningSegCls').addClass('hidden');
              programTotalTime();
            }
          });
        }
    })
  /* End: Save Training segemnt edited data */

  /* start: Deleting unchecked training segment exercises and open related accordian */
    $("#trainingSegmentSubmit").click(function(){
      $(this).attr('disabled', true);

      var exers = $('#choosedTrainingsAccordion').find('.marked-to-delete [data-exercise-id]'),
          exersLength = exers.length;
        trainingSegmentSubmit();
    });
  /* end: Deleting unchecked training segment exercises and open related accordian */

  /* Start: Populating custom plan title update modal */
    $('body').on( "click",'.customPlanUpdateModalCls', function(e){
      e.preventDefault();
      var tr = $(this).closest('tr'),
          $this = $('#customPlanUpdateModal');

      $this.find('[name="progId"]').val(tr.data('id'));

      var nameField = $this.find('[name="progName"]');
      nameField.val(tr.data('name'));
      setFieldNeutral(nameField);

      $this.find('[name="progDesc"]').val(tr.data('desc'));

      $this.removeClass('hidden');
      $this.modal('show');
    });
  /* End: Populating custom plan title update modal */

  /* Start: Submitting custom plan title update modal */
    $("#customPlanUpdate").click(function(){
      var modal = $(this).closest('.modal'),
          form = modal.find('form'),
          isFormValid = form.valid(),
          formData = {};

      if(isFormValid){
        $.each(form.find(':input').serializeArray(), function(i, field){
            formData[field.name] = field.value; 
        });
        
        API.customPlanAjax('UpdateProgram', formData, function(response){
          if(response.status == 'success'){
            FX.loadProgramsList(FX.PlanType);
            modal.modal('hide');
          }
        });
      }
    });
  /* End: Submitting custom plan title update modal */

  /* Start: custom plan delete */
    $('body').on('click', '.planDelete', function(e){
      e.preventDefault();
      var $this = $(this),
          row = $this.closest('tr'),
          formData = {};

          formData.progId = row.data('id');
          confirmEntityDelete('program', 'RemoveProgram', formData, function(response){
            if(response.status == 'success'){
              row.remove();
              console.log(row);
            }
          });
    });
  /* End: custom plan delete */

  /* start: Populating add to program link and favorite icon of exercise detail modal */
    $('body').on('click','.lungemodalCls', function(e){
      e.preventDefault();
      var sourceLnk = $(this),
      exerciseDetailsModal = $("#lungemodal");

      exerciseDetailsModal.find('.modal-title').html(sourceLnk.data('exercise-name'))

      var exercise_id = sourceLnk.data('exeid');
      getExercisesAndPopulateData(exercise_id ,exerciseDetailsModal);
      FX.setExersiseId(exercise_id);
      
      /*start: Add exercise Section */
      var sourceAddLnk = sourceLnk.find('.toggle-exercise'),
          addLnk = $(this).find('.toggle-exercise');
      if(sourceAddLnk.length){
        //Exercise has not been added yet
        addLnk.show();
      }
      else{
        addLnk.hide();
      }
      /*start: Add exercise Section */

      /*start: Favorite Section */
      var sourceFavLnk = sourceLnk.find('.toggle-fav'),
          isExercFav = sourceFavLnk.data('is-fav'),
          toggleFavLnk = $(this).find('.toggle-fav');

      exerciseDetailsModal.find('.modal-footer').data('exercise-type-id', sourceFavLnk.parent().data('exercise-type-id'));
      exerciseDetailsModal.find('.modal-footer').data('exercise-id', exercise_id);
      
      if(isExercFav)
        toggleHeart(toggleFavLnk, 'add')
      else
        toggleHeart(toggleFavLnk, 'remove')
      /*end: Favorite Section */
      exerciseDetailsModal.removeClass('hidden');
      exerciseDetailsModal.modal('show');
    });
  /* end: Populating add to program link and favorite icon of exercise detail modal */

  /* start: Mark exercise as favorite or vice-versa */
    $('body').on("click", '.toggle-fav', function(e){
      e.stopPropagation();
      var toggleFavLnk = $(this),
          exercId = toggleFavLnk.parent().data('exercise-id'),
          action = detectHeartCase(toggleFavLnk);

      if(action == 'add')
        var url = 'AddFavExercise';
      else
        var url = 'RemoveFavExercise';

      var values = {exerciseId:exercId, Clientid: FX.clientid};
      FX.setExersiseId(exercId);

      API.customPlanAjax(url, values, function(response){
        if(response.status == 'success'){
          var modalId = toggleFavLnk.closest('.modal').attr('id'),
              isFav = toggleHeart(toggleFavLnk),
              modalFooter = $('#exerciseList').find('.modal-footer');

          if(modalId == 'lungemodal'){
            var sourceFavLnk = $('#addexercise').find('[data-exercise-id="'+modalFooter.data('exercise-id')+'"] .toggle-fav');
                lnk = sourceFavLnk;
            toggleHeart(sourceFavLnk);
          }
          else
            var lnk = toggleFavLnk;

          if(isFav)
            lnk.data('is-fav', true)
          else
            lnk.data('is-fav', false)
        }
      });
    });
  /* end: Mark exercise as favorite or vice-versa */

  /* Start: Validate and save plan  */
    $('#savePlan').click(function(){
      var isValid = true,
          $this = $(this),
          alertDiv = $('#alertDiv'),
          formData = {};
          

      // clear alert
      $this.attr('disabled', true);    
      FX.clearNotific($this.closest('.panel-body'));
      FX.clearNotific(alertDiv);
      
      FX.savePlan(formData, function(response){
        if(response.status == 'success'){
          $this.attr('disabled', false);
          FX.setClientPlanId(response.newClientPlanId);
          alertDiv.removeClass('hidden');
          window.scrollTo(0,0);
          if(response.page == 'admin'){
            showNotific(FX.prepareNotific('success', "Plan has been saved successfully."), alertDiv);
            if(FX.PlanType == 6)
              window.location.href = public_url+"activity-builder/library-program";
            else if(FX.PlanType == 5)
              window.location.href = public_url+"activity-builder/generate-program";
          }
          else{
            showNotific(FX.prepareNotific('success', "Plan has been saved successfully."), alertDiv);
            window.location.href = public_url+"client/"+FX.clientid+"#activity-plan";
            location.reload(true);
          }
        }
        else{
          showNotific(FX.prepareNotific('error', "Plan could not be saved."), $this.closest('.panel-body'));
        }
      }) 
    });
  /* End: Validate and save plan  */

})
/* End: Document load function */

/* Start: Plan Preview function */ 
  function planPreviewFn($this){
      var isValid = true,
          formData = {},
          daysField = $('#days-crm').find('input'),
          exerciseList = $('#exerciseList'),
          weekSlider = $("#weekSlider-crm"),
          timeSlider = $("#timeSlider-crm"),
          heightField = $("select#fit_height"),
          weightField = $("select#fit_weight"),
          ageField = $('#fit_age');

        if(FX.PlanType == 7){
          formData = {PlanType:FX.PlanType, ClientPlanId:FX.ClientPlanId, DaysOfWeek:FX.calcWorkoutdaysPattern(daysField), WeeksToExercise:FX.getSliderValue(weekSlider)};
        }
        else if(FX.PlanType == 6){
          formData = {PlanType:FX.PlanType, ClientPlanId:FX.ClientPlanId, Habit:getSelectedOptionValue('Habit'), WeeksToExercise:FX.getSliderValue(weekSlider), DaysOfWeek:FX.calcWorkoutdaysPattern(daysField)};
        }
        else if(FX.PlanType == 5 && FX.clientid != 0){
          formData={};
          formData = planFilter;
          formData['gender'] = FX.gender;
          formData['client_id'] = FX.clientid;
          formData['PlanType'] = FX.PlanType;
          formData['TimePerWeek'] = FX.getSliderValue(timeSlider);
          formData['Height'] = withoutUnit(heightField.val());
          formData['Weight'] = withoutUnit(weightField.val());
          formData['WeeksToExercise'] = FX.getSliderValue(weekSlider);
          formData['DaysOfWeek'] = FX.calcWorkoutdaysPattern(daysField);
        }
        else{
          formData = {PlanType:FX.PlanType, ClientPlanId:FX.ClientPlanId, client_id:FX.clientid, Method:getSelectedOptionValue('Method'), Intensity:getSelectedOptionValue('Intensity'), Experience:getSelectedOptionValue('Experience'), TimePerWeek:FX.getSliderValue(timeSlider), Height:withoutUnit(heightField.val()), Weight:withoutUnit(weightField.val()), Age:ageField.val(), WeeksToExercise:FX.getSliderValue(weekSlider), DaysOfWeek:FX.calcWorkoutdaysPattern(daysField)};
        }
      
        FX.planPreview(formData, rendorPlanPreview);
  }
/* End: Plan Preview function */ 

/* start: Run validation for 'trainingSegment' and open related accordian */
  function trainingSegmentSubmit(){
    var trainingSegmentSubmitBtn = $("#trainingSegmentSubmit"), 
        trainingSegmentPanelBody = $('.panel[data-step="trainingSegment"] > .panel-body');
    trainingSegmentSubmitBtn.attr('disabled', false);
    FX.clearNotific(trainingSegmentPanelBody);

    var exercises = $('#choosedTrainingsAccordion').find('.exercise');
    if(exercises.length)
      openStep(trainingSegmentSubmitBtn)
    
    else
      showNotific(FX.prepareNotific('error', "Please choose at lease one exercise under any training segment."), trainingSegmentPanelBody);
  }
/* end: Run validation for 'trainingSegment' and open related accordian */

/* Start: Resetting Flow if needed and Open related accordian */
  function openStep(elem){
    FX.clearNotific($('#alertDiv'));
    
    var changeFlow = elem.data('change-flow');
    if(changeFlow)
      elem.closest('.panel').nextAll(':not(.pinned)').addClass('hidden')

    var targetStepName = elem.data('target-step');
    if(targetStepName){
      var targetStep = $('[data-step="'+targetStepName+'"]');
      if(targetStepName == 'currentAbility'){
        if(FX.PlanType == 6) //Library Program 
          targetStep.find('a.open-step').data('target-step', "programWant");
        else //Generator Program 
          targetStep.find('a.open-step').data('target-step', "equipmentHave");
      }

      else if(targetStepName == 'equipmentHave'){
        //call function and fatch exercise
        targetStep.find('a.open-step').data('target-step', "planMyProgram");
      }

      else if(targetStepName == 'programWant'){ // Fetching programs and show for choose
        targetStep.find('.item_class').empty();
        if(FX.PlanType == 6){
          var data = {};
          data['habit'] = getSelectedOptionValue('Habit');
          FX.getFilterPlan(data, setPrgramImages);
        }
      }

      else if(targetStepName == 'trainingSegment'){
        FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));
      }

      else if(targetStepName == 'planMyProgram'){ 
        FX.clearNotific(targetStep.children('.panel-body'));
        setFieldNeutral($('#days-crm'))
        $("#timeSelection").hide();
        var defaultValForWeek = 12,
            maxWeeks = 16,
            maxTime = 6,
            text = 'at least 2',
            minDays = 2;
            clientplanId = elem.data('clientplan-id');

        //set client plan id globaly on click program(only generator and library)
        if(typeof clientplanId != 'undefined' && clientplanId)
          FX.setClientPlanId(clientplanId);

        if(FX.PlanType == 7){
          targetStep.find('button.open-step').data('target-step', "planPreview");
          $('#days-crm').find('input').prop('checked', false); 
        }
        else if(FX.clientid != 0){
          var dayPatterns = elem.data('day-pattern');
          if(!dayPatterns)
            dayPatterns = '0000000';
          else
            dayPatterns = dayPatterns.toString();

          var dayPattern = dayPatterns.split(''); 
          /*var sunday = dayPattern.shift();
          dayPattern.push(sunday)*/
          
          var checkedDaysCount = 0;
          $.each(dayPattern, function(index, value){
            var day = $('#days-crm').find('input').eq(index);
            if(value == 1){
              day.prop('checked', true)
              checkedDaysCount++;
            }
            else
              day.prop('checked', false)
          });
          if(checkedDaysCount){
            var text = 'at least '+checkedDaysCount,
                minDays = checkedDaysCount;
          }

          var weeks = elem.data('weeks');
          if(typeof weeks != 'undefined' && weeks){
            maxWeeks = weeks;
            defaultValForWeek = weeks;
          }

          if(FX.PlanType == 6){
            targetStep.find('button.open-step').data('target-step', "planPreview");
            $("#timeSelection").hide();
          }
          else if(FX.PlanType == 5){
            var time = elem.data('time');
            if(!time)
              time = 3;
            
            $("#timeSelection").show();
            targetStep.find('button.open-step').data('target-step', "personalInfo");
          }
        }
        else{
          targetStep.find('button.open-step').data('target-step', "planPreview");
          $('#days-crm').find('input').prop('checked', false);
        }
        $("#weekSlider-crm").labeledslider({
            min: 1,
            max:maxWeeks,
            tickInterval: 1,
            value:defaultValForWeek,
            range: "min"
          });
        //$("#weekSlider-crm").labeledslider("option", "max", maxWeeks).labeledslider("value", defaultValForWeek);
        $('#daySelectionTextCRM').html(text);
        $('#days-crm').data('days-required', minDays);
      }

      else if(targetStepName == 'personalInfo'){
          //planPreviewFn(elem); 
      }

      else if(targetStepName == 'planPreview'){
          planPreviewFn(elem);
      }

      targetStep.removeClass('hidden');
      targetStep.find('.panel-heading .panel-collapse').trigger('click');
    }
  }
/* End: Resetting Flow if needed and Open related accordian */

/* start: Update per day workout time in slider */
  function updatePerDayWorkoutTime(minsPerWeek){
    var daysTraining = $('#days-crm').find('input').filter(':checked').length;
        text = FX.calcPerDayWorkoutTime(minsPerWeek, daysTraining);

    $(".ui-slider-handle", $("#timeSlider-crm")).html("<span class='handleText'>" + text + "</span>");
  }
/* end: Update per day workout time in slider */

/* Start: Reset 'trainingSegment' accordian */
  function resetTrainingSegments(){
    $(".choosetrainingSegment").prop('checked', false).trigger('change');
    $('#choosedTrainingsAccordion').children('.panel').removeClass('marked-to-delete')
    $('.choosedExercRow').empty();
  }
/* End: Reset 'trainingSegment' accordian */

/* start: Loading exercises and populating them */
  function getExercises(contnue){
    toggleWaitShield("show");
    var exerciseList = $('#exerciseList'),
        addExerciseModal = $('#addexercise'),
        loading = exerciseList.next();

    loading.show();
    var pageNumb,
        iss = FX.UI.searchScroll;

    if(typeof contnue != 'undefined' && contnue){
      pageNumb = ++FX.UI.currentPage-1;
      //iss.enabled = false;
    }
    else{
      pageNumb = FX.UI.currentPage = 1;
      //iss.enabled = true;
      exerciseList.html('')
    }
   
    var options = {workoutId:workout_id ,category:'', equipment:'', ability:'', bodypart:'', movement_type:'', movement_pattern:'', perPage:4, pageNumber:pageNumb};
    
    var favKase = detectHeartCase(addExerciseModal.find('#favSearch'));
    if(favKase == 'add')
      options.myFavourites = false;
    else if(favKase == 'remove')
      options.myFavourites = true;

    options.keyWords = addExerciseModal.find('#keySearch').val();
    options.bodypart = addExerciseModal.find('#muscle_group').val();
    options.ability = addExerciseModal.find('#ability').val();
    options.equipment = addExerciseModal.find('#equipment').val();
    options.category = addExerciseModal.find('#category').val();
    options.movement_type = addExerciseModal.find('#movement_type').val();
    options.movement_pattern = addExerciseModal.find('#movement_pattern').val();

    API.customPlanAjax('SearchExercises', options, function(response){
      var exercises = response.Exercises;
      exerciseList.empty();
      if((exercises == undefined))
      {
        //iss.enabled = false;
        var text = FX.prepareAlert('warning', 'No '+(pageNumb == 1?'':'more')+' exercise found.');
        exerciseList.append('<div class="col-md-12">'+text+'</div>');
        loading.hide();
      }
      else if(exercises.length){
        var html = '';
        exerciseList.empty();
        $.each(exercises, function(index, value){
          var desc = value.ExerciseDesc;

          if(desc.length > 19)
            var descUi = desc.substring(0, 19)+'...';
          else
            var descUi = desc;

          html += '<div class="col-md-4">\
                    <a data-toggle="modal" class="lungemodalCls" data-exercise-name="'+value.name+'" data-exeid='+value.id+'>\
                      <div class="panel panel-white m-b-0">\
                        <div class="panel-body">\
                          <div class="row">\
                            <div class="col-md-5">\
                              <img src="'+value.img+'" class="mw-60p">\
                            </div>\
                            <div class="col-md-4 nip-x-0">\
                            <h5> '+value.name+' </h5>\
                              <small>'
                                +descUi
                                +'<br/>\
                                <b>'
                                  +FX.DifficultyLevels[value.DifficultyLevel]
                                +'</b> \
                              </small>\
                            </div>\
                            <div class="col-md-3" data-exercise-type-id="'+value.ExerciseTypeID+'" data-exercise-id="'+value.id+'">\
                              <button class="btn btn-xs btn-primary m-b-2 toggle-fav" data-is-fav="'+value.IsFav+'">'
                                +((value.IsFav == true)?'<i class="fa fa-heart"></i>':'<i class="fa fa-heart-o"></i>')
                              +'</button>\
                              <button class="btn btn-xs btn-primary toggle-exercise">\
                                <i class="fa fa-plus"></i>\
                              </button>\
                            </div>\
                          </div>\
                        </div>\
                      </div>\
                    </a>\
                  </div>';
        });

        exerciseList.append(html);
        //iss.enabled = true;
      }
      else{
        //iss.enabled = false;
        var text = FX.prepareAlert('warning', 'No '+(pageNumb == 1?'':'more')+' exercise found.');
        exerciseList.append('<div class="col-md-12">'+text+'</div>');
      }
      loading.hide();
      toggleWaitShield("hide");
    });
    FX.UI.currentPage++;
  }
/* end: Loading exercises and populating them */

/* start: Render exercises in 'trainingSegment' accordian */
  function populateTrainingSegments(exercises){
    console.log(exercises);
    if(exercises.length){
      var html = {},
          programId = 0;

      $.each(exercises, function(index, value){
        if(!programId)
          programId = value.FixedProgramID;

        if(value.TempoDesc == null)
            value.TempoDesc = '';
          if(value.Resistance == null)
            value.Resistance = '';
          var string = '<div class="row">\
                    <div class="panel panel-white exercise" data-duration="'+value.EstimatedTime+'">\
                      <div class="panel-body">\
                        <div class="row">\
                          <div class="col-md-1">\
                            <img src="'+public_url+'fitness-planner/images/lunge.png" class="mw-70p">\
                          </div>\
                          <div class="col-md-9 p-t-10">\
                            <h2>'
                              +value.Name
                            +'</h2>\
                          </div>\
                          <div class="col-md-2 p-t-10" data-exercise-id="'+value.ExerciseID+'" data-editworkout="'+value.EditWorkoutId+'">\
                            <a class="btn btn-sm btn-default tooltips m-b-2 showTrainingSeg" href="#" data-placement="top" data-original-title="Edit" data-idnumber="'+value.FixedProgramID+'" data-toggle="modal" >\
                              <i class="fa fa-pencil link-btn"></i>\
                            </a>\
                            <a href="#" class="btn btn-sm btn-default tooltips delExercise" data-placement="top" data-original-title="Delete">\
                              <i class="fa fa-trash-o link-btn"></i>\
                            </a>\
                          </div>\
                        </div>\
                      </div>\
                    </div>\
                  </div>\
                  <div class="row treningSegCls hidden" id="segment_'+value.EditWorkoutId+'">\
                    <form class="treningSeg-form" action="">\
                    <div class="col-md-1">\
                      <img src="'+public_url+'fitness-planner/images/lunge.png" class="mw-70p">\
                    </div>\
                    <div class="col-md-9 form-inline">\
                      <div class="form-group">\
                        <label for="exercSets" class="custom-label">SETS</label>\
                        <input type="number" value="'+value.Sets+'" class="form-control custom-form-control numericField" id="exercSets" name="exercSets" min="0" required="required">\
                      </div>\
                      <div class="form-group">\
                        <label for="exercReps" class="custom-label">REPETITION</label>\
                        <input type="number" value="'+value.Repetition+'" class="form-control numericField custom-form-control" id="exercReps" name="exercReps" min="0" required="required">\
                      </div>\
                      <div class="form-group">\
                        <label for="exercDur" class="custom-label">OR DURATION</label>\
                        <input type="number" value="'+value.EstimatedTime+'" class="form-control numericField custom-form-control" id="exercDur" name="exercDur" min="0" required="required">\
                      </div>\
                      <div class="form-group">\
                        <label for="exercResist" class="custom-label">RESISTANCE</label>\
                        <input type="text" value="'+value.Resistance+'" class="form-control custom-form-control" id="exercResist" name="exercResist" required="required">\
                      </div>\
                      <div class="form-group">\
                        <label for="exercTempo" class="custom-label">TEMPO</label>\
                        <input type="text" value="'+value.TempoDesc+'" class="form-control custom-form-control" id="exercTempo" name="exercTempo" required="required">\
                      </div>\
                      <div class="form-group">\
                        <label for="exercRest" class="custom-label">REST</label>\
                        <input type="number" value="'+value.RestSeconds+'" class="form-control numericField custom-form-control" id="exercRest" name="exercRest" min="0" required="required">\
                      </div>\
                    </div>\
                    <div class="col-md-2 p-t-20" data-exercise-id="'+value.ExerciseID+'" data-planeworkexe-id="'+value.EditWorkoutId+'">\
                      <a class="btn btn-sm btn-default tooltips m-b-2 saveTrainingSeg" href="#" data-placement="top" data-original-title="Save">\
                        <i class="fa fa-save link-btn"></i>\
                      </a>\
                      <a href="#" class="btn btn-sm btn-default tooltips hidePlanWorkoutExe" data-placement="top" data-original-title="Delete">\
                        <i class="fa fa-times link-btn"></i>\
                      </a>\
                    </div>\
                    </form>\
                  </div>';
           
            if(value.WorkOut in html)
              html[value.WorkOut] += string;
            else
              html[value.WorkOut] = string;
      });

      $.each(html, function(index, value){
        var choosedExercRow = $('.choosedExercRow[data-work-out="'+index+'"]');
        if(choosedExercRow.length){
          choosedExercRow.append(value).data('program-id', programId);

          var accordId = choosedExercRow.closest('.panel-collapse').attr('id'),
              cbxId = accordId.split('accord_');
          $(".choosetrainingSegment").filter('[id="'+cbxId[1]+'"]').prop('checked', true).trigger('change');
        }
      });
    }
    programTotalTime();
  }
/* end: Render exercises in 'trainingSegment' accordian */

/* start: Detect if heart is clicked to add exercise to favorite or vice-versa */
  function detectHeartCase(elem){
    var ital = elem.children();

    if(ital.hasClass('fa-heart-o'))
      return 'add'
    else
      return 'remove'
  }
/*end: Detect if heart is clicked to add exercise to favorite or vice-versa*/

/* start: Calculate and display plan total time */
  function programTotalTime(){
    var totSecs = 0;
    /*$('#choosedTrainingsAccordion').find('.panel:visible .exercise').each(function(){
      totSecs+= parseInt($(this).data('duration'));
    })*/

    $('#choosedTrainingsAccordion').find('input[name="exercDur"]').each(function(){
      var duration = parseInt($(this).val());
      if(isNaN(duration))
        duration = 0;
      totSecs+= duration;
    })
    
    if(totSecs)
      var totalMin = Math.ceil(totSecs/60);
    else
      var totalMin = 0;

    $('#programTotalTime').text(totalMin)
  }
/* end: Calculate and display plan total time */

/* start: Turn heart on/off */
  function toggleHeart(elem, kase){
    var ital = elem.children();

    if(typeof kase == 'undefined')
      kase = detectHeartCase(elem);

    if(kase == 'add'){
      ital.removeClass('fa-heart-o').addClass('fa-heart')
      return true;
    }
    else{
      ital.removeClass('fa-heart').addClass('fa-heart-o')
      return false;
    }
  }
/* end: Turn heart on/off */

/* start: Reset and Render exercises in 'trainingSegment' accordian as per the API response */
  function resetAndPopulateTrainingSegments(response){
    resetTrainingSegments();
    populateTrainingSegments(response.Exercises);
  }
/* end: Reset and Render exercises in 'trainingSegment' accordian as per the API response */

/* start: Get selected value from specified group */
  function getSelectedOptionValue(dataName){
    var value = FX.reinitIfNotVal($('[data-name="'+dataName+'"]').closest('.panel:visible').find('[data-name="'+dataName+'"] a:not(.inactive) input').val());
    return FX.numericStringToInt(value);
  }
/* end: Get selected value from specified group */

/* start: Return value from value-unit pair such as 120 from 120 cm */
  function withoutUnit(value){
    if(typeof value != 'undefined' && value != ''){
      var arr = value.split(' ');
      return arr[0];
    }
    return 0;
  }
/* end: Return value from value-unit pair such as 120 from 120 cm */

/* start: Render plans list in 'plans' accordian as per the API response */
  function populatePlansList(response){
    var html = '',
        plansPreviewAccordion = $('#plansPreviewAccordion-crm');
        if(Response in response)
          var plans = response.Response.Plans;
        else
          var plans={};

    if(Object.keys(plans).length)
      html = populatePlanHelper(plans, 'plansPreviewAccordion', 'plans');
    
    plansPreviewAccordion.html(html)
  }
/* end: Render plans list in 'plans' accordian as per the API response */

/* start: Helper function to generate html for plan list */
  function populatePlanHelper(plans, parentAccordId, collapseId){
    var html = '';
    $.each(plans, function(index, value){
      html += '<div class="panel panel-white"><div class="panel-heading"><h5 class="panel-title"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#'+parentAccordId+'" href="#'+collapseId+'-'+index+'"><i class="icon-arrow"></i>'+index+'</a></h5></div><div id="'+collapseId+'-'+index+'" class="panel-collapse collapse"><div class="panel-body"><div class="row">';

      $.each(value.SubGroups, function(index, value){
        html += '<div class="col-md-6 m-b-5"><div class="strong">'+value.Key+'</div>';

        $.each(value.Value, function(index, value){
          html += '<div class="row m-l-0 m-b-5"><div class="col-md-6">'+value.Item1+'</div><div class="col-md-6">'+value.Item2+'</div></div>';
        });

        html += '</div>';
      });
              
      html += '</div></div></div></div>';
    });
    return html;
  }
/* end: Helper function to generate html for plan list */

/* start: Render images in 'programWant' accordian as per the API response */
  function setPrgramImages(response){
    var imagesCount = 0;

    if(response.status == 'success'){
      var programs = response.plan,
          colCount = 0,
          html = '';

      $.each(programs, function(index, value){
        if(colCount == 0)
          html += '<div class="row m-b-20">';

        html += '<div class="col-md-2 text-center"><a class="open-step inactive" data-target-step="planMyProgram" href="#" data-weeks="'+value.DefaultWeeks+'" data-time="'+value.TimePerWeek+'" data-day-pattern="'+value.DayPattern+'" data-clientplan-id="'+value.FixedProgramId+'"><input type="image" value="'+value.FixedProgramId+'" class="image_class mw-100p" src="'+value.Image+'" alt="'+value.ProgramName+'"></br><span><strong>'+value.ProgramName+'</strong></span></a></div>';

        colCount++;
        if(colCount >= 3 || imagesCount-1 == index){
          html += '</div>';
          colCount = 0;
        }
      });
    }
    else{
      html = '';
      html += '<div class="col-md-12"><div class="alert alert-warning">No any program yet.</div></div>';
    }
    $('[data-step="programWant"]').find('.item_class').html(html);
  }
/* end: Render images in 'programWant' accordian as per the API response */

/* Start: get Exercises And Populate Data in modal */
  function getExercisesAndPopulateData(exeid, $modal){
    toggleWaitShield("show", 'exercise-detail');

    if(typeof exeid != 'undefined' && exeid != ''){
      $.ajax({
        url : public_url+'CustomPlan/SearchExercisesById/'+exeid,
        type : 'GET',
        success : function(response) {
          var data = JSON.parse(response);
        
          if(data.status == 'success'){
            var imgArea = $modal.find('#exe-img-area');
            imgArea.empty();
            var all_images = data.Image;
            if(all_images.length){
              $imgHTML = '<div id="myCarousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="false" >\
                          <div class="carousel-inner">';

              $.each(all_images, function(i, value){
                if(i== 0){
                  $imgHTML += '<div class="item active" style="width:100%">\
                                <img src="'+value+'" width="100%">\
                              </div>'
                  }
                  else{
                    $imgHTML += '<div class="item" style="width:100%">\
                                <img src="'+value+'" width="100%">\
                              </div>'
                  }
              })
              $imgHTML += '</div><a class="left carousel-control" href="#myCarousel" data-slide="prev">\
                            <span class="glyphicon glyphicon-chevron-left"></span>\
                            <span class="sr-only">Previous</span>\
                          </a>\
                          <a class="right carousel-control" href="#myCarousel" data-slide="next">\
                            <span class="glyphicon glyphicon-chevron-right"></span>\
                            <span class="sr-only">Next</span>\
                          </a>\
                        </div>';
              imgArea.append($imgHTML);
            }

            
            if(!$.isEmptyObject(data.exercise)){
              $.each(data.exercise, function(key, value){
                $modal.find('#'+key).html(value)
              })
            }
            
            toggleWaitShield("hide");
          }
        },
      });
    } 
  }
/* End: get Exercises And Populate Data in modal */

/* Start: Delete confirmation function */
  function confirmEntityDelete(entity, url, param, callback){
    swal({
        title: "Are you sure to delete this "+entity+"?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
      }, 
        function(isConfirm){
          if(isConfirm){
            console.log(param);
            API.customPlanAjax(url, param, function(response){
                callback(response);
            });
          }
      });
  }
/* End: Delete conformation function */

/* Start: Plan Preview */ 
  function rendorPlanPreview(response){
    var html = "",
        previewId = $('#plan-preview-area');

    previewId.empty();
    if(response.status == 'success'){
      if(FX.PlanType == 5)
        FX.setClientPlanId(response.clientPlanId);

      $.each(response.data, function(day, value){
        html += '<div class="col-md-5 m-t-20">\
                  <h4 class="plan-day-style">'+day+'</h4>'
        $.each(value, function(workout, workoutData){
          html += '<div class="row">'+workout+'</div>';
          $.each(workoutData, function(i, exercise){
            html += '<div class="row"><div class="col-sm-4 col-xs-6"><small>'+exercise.Name+'</small></div>';
                if(exercise.sets && exercise.repes)
                    html += '<div class="col-sm-4 col-xs-6"><small>'+exercise.sets+' &times; '+exercise.repes+' sets</small></div>';
            html += '</div>';
          })
        })
        html += '</div>';   
      })
    }
    else{
      html = '';
      html += '<div class="col-md-12"><div class="alert alert-warning">No any program match.</div></div>';
    }
    previewId.append(html);
    toggleWaitShield("hide");
  }
/* End: Plan Preview */
