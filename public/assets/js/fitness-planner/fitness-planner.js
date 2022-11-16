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
  isMultiPhase = false;
  planFilter = {};
startData = '';
/* Start: Document load function */
$(document).ready(function () {

  /* Hiding panels */
  $('.panel[data-step]:not(.pinned)').addClass('hidden');

  /* Set Client Id to api */
  FX.setClientid($('input[name="fit_clientId"]').val());

  /* Set Client gender to api */
  FX.setGender($('input[name="fit_gender"]').val());
  /* Initialize custom vailidation */
  initCustomValidator();

  /*Initializing datatable */
  $('#design-program-datatable').dataTable({
    "searching": false,
    "paging": false,
    "info": false
  });
  //if($('#client-datatable').length > 0)
  //customPlanDatatable = $('#client-datatable').DataTable();

  /* start: Initialize slider */
  $("#weekSlider-crm").labeledslider({
    min: 1,
    tickInterval: 1,
    range: "min"
  });

  var timeLabels = new Array();
  for (var i = 0; i <= 360; i++)
    timeLabels.push(FX.minsToHourMin(i));
  $("#timeSlider-crm").labeledslider({
    max: 360,
    tickInterval: 30,
    value: 60,
    tickLabels: timeLabels,
    range: "min",
    min: 0,
    create: function (event, ui) {
      updatePerDayWorkoutTime(60);
    },
    slide: function (event, ui) {
      updatePerDayWorkoutTime(ui.value);
    }
  });
  /* End: Initialize slider */

  /* start: Make html field editable */
  $(".editable").click(function (e) {
    var $this = $(this);

    $this.hide();
    $this.next().removeClass('hidden')
  });
  /* end: Make html field editable */

  /* Start: Activating clicked option and deactivating sibling options */
  $('body').on("click", 'a.inactive', function (e) {
    e.preventDefault();
    var $this = $(this);
    $this.closest('[data-name]:not([data-name=""])').find('a:not(.inactive)').removeClass("crm-select-plan").addClass("inactive");
    $this.removeClass("inactive");
    $this.addClass("crm-select-plan");
  });
  /* End: Activating clicked option and deactivating sibling options */

  /* Start: Run validation over options and open related accordian */
  $('body').on("click", '.open-step', function (e) {
    e.preventDefault();
    var $this = $(this),
      panel = $this.closest('.panel'),
      panelBody = panel.children('.panel-body'),
      runValidation = $this.data('run-validation'),
      isValid = true;
    FX.clearNotific(panelBody);
    if (panel.data('step') == 'programChoose') {
      FX.setPlanType(FX.numericStringToInt($this.children('input').val()))
      activityChoosed = 0;
    } else if (panel.data('step') == 'activityChoose') {
      var newActivityChoosed = FX.numericStringToInt($this.children('input').val());
      if (newActivityChoosed != activityChoosed) {
        $('[data-step="currentAbility"]').nextAll(':not(.pinned)').addClass('hidden')
      }
      activityChoosed = newActivityChoosed;
    } else if (panel.data('step') == 'designProgram') {
      checkExeWorkExist = {}; // reset initial
      var id = $this.closest('tr').data('id');
      FX.setClientPlanId(id);
      FX.loadProgram(id, resetAndPopulateTrainingSegments);
      loadExerciseList();
    } else if (panel.data('step') == 'programWant') {
      checkExeWorkExist = {}; // reset initial
      var form_values = {};
      var id = $this.closest('a').data('clientplan-id');
      form_values.progrmId = id;
      form_values.clientId = $('input[name="clientId"]').val();
      $.get(`${public_url}CustomPlan/replicate-program`,form_values,function(response){
          FX.setClientPlanId(response.clientPlanId);
          FX.loadProgram(response.clientPlanId, resetAndPopulateTrainingSegments);
          loadExerciseList();
      },'json');
    } else if (panel.data('step') == 'planMyProgram') {
      var daysWrapper = $('#days-crm'),
        daysField = daysWrapper.find('input'),
        daysRequired = daysWrapper.data('days-required'),
        checkedDays = daysField.filter(':checked').length;

      FX.clearNotific($this.closest('.panel-body'));
      if (checkedDays < daysRequired) {
        setFieldInvalid(daysWrapper.closest('.form-group'), 'Please select required days.', daysWrapper.next());
        isValid = false;
        return false;
      }
    } else if (panel.data('step') == 'acticityVideo') {
      var id = $this.closest('tr').data('id');
      FX.setClientVideId(id);
    }

    if ($this.data('target-step') == 'acticityVideo') {
      $.get(API.CUSTOM_PROGRAM_URL + 'activityViedos', function (response) {
        var response = JSON.parse(response);
        var videosList = response.videos;
        var tableBody = $('#acticity-video-datatable').find('tbody');
        var html = '';
        $('#design-program-datatable').DataTable().destroy();
        $('.pts .my_pt tbody').empty();
        $.each(videosList, function (key, value) {
          html += '<tr data-id="' + value.id + '" data-name="' + value.title + '">\
            <td>' + value.title + '</td>\
            <td>' + value.created_at + '</td>\
            <td class="center">\
            <a href="#" class="btn btn-xs btn-primary tooltips open-step" data-placement="top" data-original-title="View" data-target-step="planMyProgram"><i class="fa fa-share link-btn"></i></a></td>\
            </tr>';
        });
        tableBody.append(html);
        FX.setPlanType('8');
      });
    }

    if (runValidation) {
      var fieldGroups = panelBody.find('[data-name]:not([data-name=""])');
      if (fieldGroups.length) {
        fieldGroups.each(function () {
          var selectedOpt = $(this).find('a:not(.inactive)');
          if (!selectedOpt.length) {
            isValid = false;
            showNotific(FX.prepareNotific('error', "Please choose one option per section."), panelBody);
            return false;
          }
        });
      }
    }

    if (isValid) {
      if (panel.data('step') == 'programChoose' && FX.PlanType == 7) {
        FX.loadProgramsList(FX.PlanType);
      } else if (panel.data('step') == 'activityChoose') {
        planFilter['purpose'] = FX.numericStringToInt($this.children('input').val());
      } else if (panel.data('step') == 'activityHabits') {
        planFilter['curr_phy_act'] = getSelectedOptionValue('Habit');
        planFilter['prev_phy_act'] = getSelectedOptionValue('Experience');
        planFilter['next_phy_act'] = getSelectedOptionValue('Intensity');
        planFilter['curr_intensity_phy_act'] = getSelectedOptionValue('temp');
      } else if (panel.data('step') == 'currentAbility') {
        if (FX.PlanType != 6)
          planFilter['habit'] = FX.numericStringToInt($this.children('input').val());
      } else if (panel.data('step') == 'equipmentHave') {
        planFilter['equipment'] = getSelectedOptionValue('Method');
      } else if (panel.data('step') == 'programWant') {
        // set for program want..
      } else
        $('#plansPreviewAccordion-crm').html('');

      openStep($this)
    }
  });
  /* End: Run validation over options and open related accordian */

  /* Start: Onchange workout open training segement */
  $(".choosetrainingSegment").change(function () {
    var $this = $(this),
      accordId = $this.attr('id'),
      panel = $('#accord_' + accordId).closest('.panel'),
      choosedTrainingsAccordionSection = $('#choosedTrainingsAccordion').parent('div');

    if ($this.is(':checked')) {
      choosedTrainingsAccordionSection.show();
      panel.removeClass('marked-to-delete').show();
    } else {
      if (!$(".choosetrainingSegment").filter(':checked').length)
        choosedTrainingsAccordionSection.hide();
      panel.addClass('marked-to-delete').hide();
    }
    programTotalTime();
  });
  /* End: Onchange workout checkbox open training segment */

  /* Start: Opening add exercise modal */
  $('body').on('click','.accordion-toggle button', $('#choosedTrainingsAccordion'),function (e) {
    e.stopPropagation();
    e.preventDefault();
    workout_id = $(this).data('workout');
   

    var modal = $('#addexercise');
  
    var choosedExercRow = $(this).closest('.panel').find('.choosedExercRow'),
      exerciseList = $('#exerciseList');
    exerciseVideoList = $('#exerciseVideoListing');

    exerciseList.data('program-id', choosedExercRow.data('program-id'));
    exerciseList.data('work-out', choosedExercRow.data('work-out'));
    exerciseVideoList.data('program-id', choosedExercRow.data('program-id'));
    exerciseVideoList.data('work-out', choosedExercRow.data('work-out'));

    loadBodyAreasForExercise(FX.genderString == 'male' ? maleAreasForEx : femaleAreasForEx, modal);

    toggleHeart(modal.find('#favSearch'), 'remove');
    modal.find('#keySearch').val('');
    modal.find('#muscle_group').val('');
    modal.find('#ability').val('');
    modal.find('#equipment').val('');
    modal.find('#category').val('');
    modal.find('#movement_type').val('');
    modal.find('#movement_pattern').val('');

    modal.modal('show');
    var formData = {};
    formData['clientPlanId'] = FX.ClientPlanId;
    $.get(public_url + 'CustomPlan/exercise-type', formData, function (response) {
      var exerciseType = response;
      setActiveTab(modal, exerciseType);
    }, 'json');
    getExercisesMobile();
    getAllActivityVideoMobile(workout_id);
    //FX.UI.searchScroll = new FX.InfiniteScroller($('#exerciseList').parent(), getExercises);
    //getExercises();
  });
  /* End: Opening add exercise modal */

  /* start: Switching plus to tick and adding exercise */
  $('body').on("click", '.toggle-exercise', function (e) {
    e.stopPropagation();
    toggleWaitShield("show");
    var $this = $(this),
      formData = {},
      exerciseDetailsModalFooter = $("#lungemodal").find('.modal-footer');
    var targetModal = $(this).closest('.modal');
    if (targetModal.length > 0 && targetModal.attr('id') == 'addexercise') {
      var exerciseList = $('#exerciseList');
      var openPanel = '';
      openPanel = $(this);
      if (typeof (exerciseList.data('program-id')) == 'undefined') {
        exerciseList.data('program-id', (FX.ClientPlanId));
       
      }
      var order = 1;
      var orderData = [];
      var exeOrder = 0;
      openPanel.find('.exeRow').each(function () {
        if ($(this).hasClass('dropDiv')) {
          exeOrder = order;
        } else {
          orderData.push({
            'planWorkoutExerciseId': $(this).data('plan-workout-exercise-id'),
            'order': order
          });
        }
        order = order + 1;
      });

      FX.setExersiseId($this.parent().data('exercise-id'));
      formData.WorkOutName = exerciseList.data('work-out');
      if (isMultiPhase || (FX.PlanType != undefined && FX.PlanType == 9)) {
        formData.ClientPlanId = $('#clientPlanProgramId').val();
      } else {
        formData.ClientPlanId = FX.ClientPlanId;
      }
      formData.ExerciseId = FX.ExersiseId;
      formData.PlanType = exerciseList.data('plan-type');
      formData.exeOrder = exeOrder;
      formData.orderData = orderData;
      if(isMultiPhase == true){
        formData.ClientPlanType = 9;
      }else{
        formData.ClientPlanType = FX.PlanType;
      }

      FX.addExToProgram(formData, function (response) {
        if (response.status == 'success') {
          FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));

          var modalId = $this.closest('.modal').attr('id');
          if (modalId == 'addexercise') {
            var sourceAddLnk = $this;
          } else if (modalId == 'lungemodal') {
            $this.hide();

            var sourceAddLnk = $('#addexercise').find('[data-exercise-type-id="' + exerciseDetailsModalFooter.data('exercise-type-id') + '"] .toggle-exercise');
          }
          sourceAddLnk.removeClass('toggle-exercise')
          sourceAddLnk.children().removeClass('fa-plus').addClass('fa-check');
          populateTrainingSegments(response.Exercises, response.isVideo);
          var modal = $('#' + modalId);
          setActiveTab(modal, "1");
          /*FX.loadProgram(FX.ClientPlanId, populateTrainingSegments);*/

          if (FX.PlanType != 'undefined' && FX.PlanType == 5) {
            var generatorModal = $('#generatorModal');
            generatorModal.modal({
              backdrop: 'static',
              keyboard: false
            })
            generatorModal.find('input[name="workout_name"]').val(formData.WorkOutName);
          }

          $('#choosedTrainingsAccordion').find('a[href="#accord_' + formData.WorkOut + '"]').trigger('click');

          toggleWaitShield("hide");
        }
      });
    } else {
      var exerciseList = $('.exerciseVideoList');
      var workoutName = '';
      var openPanel = '';
      var trainingSegmentPanel = $('#choosedTrainingsAccordion').find('.panel-collapse');
      trainingSegmentPanel.each(function () {
        if ($(this).hasClass('in') && $(this).attr('aria-expanded') == 'true') {
          workoutName = $(this).find('.choosedExercRow').data('work-out');
          openPanel = $(this);
        }
      });
      var order = 1;
      var orderData = [];
      var exeOrder = 0;
      openPanel.find('.exeRow').each(function () {
        if ($(this).hasClass('dropDiv')) {
          exeOrder = order;
        } else {
          orderData.push({
            'planWorkoutExerciseId': $(this).data('plan-workout-exercise-id'),
            'order': order
          });
        }
        order = order + 1;
      });
      if (workoutName != '' && workoutName != undefined) {
        if ($this.parent().data('exeid') != undefined && $this.parent().data('exeid') != "") {
          FX.setExersiseId($this.parent().data('exeid'));
        }
        formData.WorkOutName = workoutName;
        if (isMultiPhase || (FX.PlanType != undefined && FX.PlanType == 9)) {
          formData.ClientPlanId = $('#clientPlanProgramId').val();
        } else {
          formData.ClientPlanId = FX.ClientPlanId;
        }
        formData.ExerciseId = FX.ExersiseId;
        formData.PlanType = exerciseList.data('plan-type');
        formData.exeOrder = exeOrder;
        formData.orderData = orderData;
        if(isMultiPhase == true){
          formData.ClientPlanType = 9;
        }else{
          formData.ClientPlanType = FX.PlanType;
        }

        FX.addExToProgram(formData, function (response) {
          if (response.status == 'success') {
            FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));

            var modalId = $this.closest('.modal').attr('id');
            if (modalId != undefined && modalId == 'lungemodal') {
              var modal = $('#' + modalId);
              modal.modal('hide');
              $this.hide();
              var sourceAddLnk = $('#addexercise').find('[data-exercise-type-id="' + exerciseDetailsModalFooter.data('exercise-type-id') + '"] .toggle-exercise');
            } else {
              var sourceAddLnk = $this;
            }
            sourceAddLnk.removeClass('toggle-exercise')
            sourceAddLnk.children().removeClass('fa-plus').addClass('fa-check');

            /*var newExercise = response.NewExercise;
            var x = [{WeekIndex:newExercise.WeekIndex, DayIndex:newExercise.DayIndex, WorkOut:newExercise.WorkOut, Priority:newExercise.Priority, ExerciseTypeID:newExercise.ExerciseTypeID,Name:newExercise.ExerciseType.ExerciseName,  ExerciseDesc:newExercise.ExerciseType.ExerciseDesc, Sets:newExercise.Sets, Repetition:newExercise.Repetition, RepOrSeconds:newExercise.RepOrSeconds, TempoDesc:newExercise.TempoDesc, TempoTiming:newExercise.TempoTiming, RestSeconds:newExercise.RestSeconds, ExerciseID:newExercise.ExerciseID, EditWorkoutId:newExercise.EditWorkoutId, FixedProgramID:newExercise.FixedProgramID, EstimatedTime:newExercise.EstimatedTime, Resistance:newExercise.Resistance}] //, IsReps:newExercise.ExerciseType.IsReps, HasWeight:newExercise.ExerciseType.HasWeight*/

            populateTrainingSegments(response.Exercises, response.isVideo);
            // var modal = $('#'+modalId);
            // setActiveTab(modal,"1");
            /*FX.loadProgram(FX.ClientPlanId, populateTrainingSegments);*/

            if (FX.PlanType != 'undefined' && FX.PlanType == 5) {
              var generatorModal = $('#generatorModal');
              generatorModal.modal({
                backdrop: 'static',
                keyboard: false
              })
              generatorModal.find('input[name="workout_name"]').val(formData.WorkOutName);
            }
            $('#choosedTrainingsAccordion').find('a[href="#accord_' + formData.WorkOut + '"]').trigger('click');
            toggleWaitShield("hide");
          }
        });
      } else {
        toggleWaitShield("hide");
        swal('Please Select any training segment');
      }
    }
  });
  /* end: Switching plus to tick and adding exercise

  /* start: Searching exercise by favorite */
  $(".add-exercises #favSearch").on("click", function (e) {
    e.preventDefault();
    toggleHeart($(this));
    getExercises();
  });
  /* end: Searching exercise by favorite */

  /* start: Searching exercise by keyword */
  $("#keySearch").on("keyup", function (e) {
    clearTimeout(keySearchTimeoutId);
    keySearchTimeoutId = setTimeout(getExercisesMobile, 1000);
  });
  $("#keySearchExercise").on("keyup", function (e) {
    var exeType = $('.exerciseVideoList').data('plan-type');
    if (exeType == '1') {
      clearTimeout(keySearchTimeoutId);
      keySearchTimeoutId = setTimeout(getExercises, 1000);
    } else {
      clearTimeout(keySearchTimeoutId);
      keySearchTimeoutId = setTimeout(getAllActivityVideo, 1000);
    }
  });
  $("#addexercise .searchExercise").on("change", function (e) {
    getExercisesMobile();
  });
  $(".add-exercises .searchExercise").on("change", function (e) {
    getExercises();
  });
  /* end: Searching exercise by keyword */

  /* Start: Trainning Segment area hide and show */
  $('body').on('click', '.showTrainingSeg', function (e) {
    e.preventDefault();
    var $this = $(this),
      id = $this.parent().data('editworkout');
    $('#txt_' + id).removeClass('col-sm-9 col-xs-10 col-md-9');
    $('#txt_' + id).addClass('col-sm-12 col-xs-12 col-md-12');
    $this.closest('div').hide();
    $('#segment_' + id).removeClass('hidden');
  })
  /* End: Trainning Segment area hide and showb */

  /* start: Delete exercise */
  $('body').on('click', '.delExercise', function (e) {
    e.preventDefault();
    var $this = $(this),
      formData = {},
      planWorkoutId = $this.parent().data('editworkout');
    //formData['ExerciseId'] = $this.parent().data('exercise-id');
    formData['planWorkoutExercise'] = $this.parent().data('editworkout');
    formData['plan_type'] = FX.PlanType;
    //formData['WorkoutName'] = $this.closest('.choosedExercRow').data('work-name');
    confirmEntityDelete('exercise', 'RemoveExerciseFromProgram', formData, function (response) {
      if (response.status == 'success') {
        $this.closest('.exercise').remove();
        $('#segment_' + planWorkoutId).remove();
        if (response.type == '2') {
          programTotalVideoTime();
        } else {
          programTotalTime();
        }
      }
    });
  });
  /* end: Delete exercise */

  /* Start: hide plan workout exercise  */
  $('body').on('click', '.hidePlanWorkoutExe', function (e) {
    e.preventDefault();
    var $this = $(this),
      planWorkoutId = $this.data('planeworkexe-id');

    $('#txt_' + planWorkoutId).removeClass('col-sm-12 col-xs-12 col-md-12');
    $('#txt_' + planWorkoutId).addClass('col-sm-9 col-xs-10 col-md-9');

    $('#segment_editbtn_' + planWorkoutId).show();
    $('#segment_' + planWorkoutId).addClass('hidden');
  });
  /* End: hide plan workout exercise  */

  /* Start: Save Training segment edited data */
  $('body').on('click', '.saveTrainingSeg', function (e) {
    e.preventDefault();
    var $this = $(this),
      formData = {},
      form = $this.closest('.treningSeg-form'),
      isFormValid = form.valid(),
      workoutExerciseSetId = $this.closest('div').data('exercise-set-id'),
      workoutExerciseId = $this.closest('div').data('exercise-id'),
      EditWorkoutId = $this.closest('div').data('planeworkexe-id');

    formData['workoutExerciseSetId'] = workoutExerciseSetId;
    formData['workoutExerciseId'] = workoutExerciseId;
    formData['editWorkoutId'] = EditWorkoutId;
    formData['planType'] = FX.PlanType;
    if (isFormValid) {
      $.each($(form).find(':input').serializeArray(), function (i, field) {
        formData[field.name] = field.value;
      });
      API.customPlanAjax('EditTrainingSegment', formData, function (response) {
        if (response.status == 'success') {
          $('#segment_' + EditWorkoutId).addClass('hidden');
          $('#segment_editbtn_' + EditWorkoutId).show();
          $this.closest('div').data('exercise-set-id',response.workoutId);
          programTotalTime();
        }
      });
    }
  })
  /* End: Save Training segemnt edited data */

  /* start: Deleting unchecked training segment exercises and open related accordian */
  $("#trainingSegmentSubmit").click(function () {
    $(this).attr('disabled', true);

    var exers = $('#choosedTrainingsAccordion').find('.marked-to-delete [data-exercise-id]'),
      exersLength = exers.length;
    trainingSegmentSubmit();
  });
  /* end: Deleting unchecked training segment exercises and open related accordian */

  /* Start: Populating custom plan title update modal */
  $('body').on("click", '.customPlanUpdateModalCls', function (e) {
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
  $("#customPlanUpdate").click(function () {
    var modal = $(this).closest('.modal'),
      form = modal.find('form'),
      isFormValid = form.valid(),
      formData = {};

    if (isFormValid) {
      $.each(form.find(':input').serializeArray(), function (i, field) {
        formData[field.name] = field.value;
      });

      API.customPlanAjax('UpdateProgram', formData, function (response) {
        if (response.status == 'success') {
          FX.loadProgramsList(FX.PlanType);
          modal.modal('hide');
        }
      });
    }
  });

  $('body').on('click', '#updateProgramNameSubmit', function () {
    var form = $('#updateProgramName');
    var formData = {};
    formData['progName'] = form.find('input[name="programname"]').val();
    formData['progId'] = FX.ClientPlanId;
    API.customPlanAjax('UpdateProgram', formData, function (response) {
      if (response.status == 'success') {
        form.find('input[name="programname"]').val('');
        swal('Plan name updated successfully');
      }
    });
  });
  /* End: Submitting custom plan title update modal */

  /* Start: custom plan delete */
  $('body').on('click', '.planDelete', function (e) {
    e.preventDefault();
    var $this = $(this),
      row = $this.closest('tr'),
      formData = {};

    formData.progId = row.data('id');
    confirmEntityDelete('program', 'RemoveProgram', formData, function (response) {
      if (response.status == 'success') {
        row.remove();
      }
    });
  });
  /* End: custom plan delete */

  /* start: Populating add to program link and favorite icon of exercise detail modal */
  $('body').on('click', '.lungemodalCls', function (e) {
    e.preventDefault();
    var sourceLnk = $(this);
    if (sourceLnk.data('type') == 'video') {
      var exerciseDetailsModal = $("#videoModal");
      var videoId = $(this).data('video-id');
      var exercise_id = sourceLnk.data('exeid');
      toggleWaitShield("show");
      $.get(public_url + "/activity-builder/videos/view/" + exercise_id, function (response) {
        toggleWaitShield("hide");
        if (response.status == 'ok') {
          exerciseDetailsModal.find('.modal-title').html(response.title);
          exerciseDetailsModal.find('#description').text(response.description);
          var movementHtml = '';
          $.each(response.movementData, function (key, value) {
            movementHtml += '<tr>\
            <td>' + value.name + '</td>\
            <td>' + value.time + '</td>\
            </tr>';
          });
          exerciseDetailsModal.find('.tb-movement').empty();
          exerciseDetailsModal.find('.tb-movement').append(movementHtml);
          var videoPath = sourceLnk.data('video-url');
          var video = $('#myVideo')[0];
          video.src = videoPath;
          video.load();
          $('#viewModal').modal('show');
        }
      }, 'json');
      /*start: Add exercise Section */
      FX.setExersiseId(exercise_id);
      var sourceAddLnk = sourceLnk.find('.toggle-video'),
        addLnk = exerciseDetailsModal.find('.toggle-video');
      exerciseDetailsModal.find('.modal-footer').data('exercise-type-id', sourceAddLnk.parent().data('exercise-type-id'));
      exerciseDetailsModal.find('.modal-footer').data('exercise-id', exercise_id);
      if (sourceAddLnk.length) {
        //Exercise has not been added yet
        addLnk.show();
      } else {
        addLnk.hide();
      }
      exerciseDetailsModal.modal('show');
    } else {
      var exerciseDetailsModal = $("#lungemodal");

      exerciseDetailsModal.find('.modal-title').html(sourceLnk.data('exercise-name'))

      var exercise_id = sourceLnk.data('exeid');
      getExercisesAndPopulateData(exercise_id, exerciseDetailsModal);
      FX.setExersiseId(exercise_id);

      /*start: Add exercise Section */
      var sourceAddLnk = sourceLnk.find('.toggle-exercise'),
        addLnk = exerciseDetailsModal.find('.toggle-exercise');
      if (sourceAddLnk.length) {
        //Exercise has not been added yet
        addLnk.show();
      } else {
        addLnk.hide();
      }
      /*start: Add exercise Section */

      /*start: Favorite Section */
      var sourceFavLnk = sourceLnk.find('.toggle-fav'),
        isExercFav = sourceFavLnk.data('is-fav'),
        toggleFavLnk = $(this).find('.toggle-fav');

      exerciseDetailsModal.find('.modal-footer').data('exercise-type-id', sourceFavLnk.parent().data('exercise-type-id'));
      exerciseDetailsModal.find('.modal-footer').data('exercise-id', exercise_id);

      if (isExercFav)
        toggleHeart(toggleFavLnk, 'add')
      else
        toggleHeart(toggleFavLnk, 'remove')
      /*end: Favorite Section */
      exerciseDetailsModal.removeClass('hidden');
      exerciseDetailsModal.modal('show');
    }
  });
  $("#viewModal").on('hide.bs.modal', function () {
    var video = $('#myVideo')[0];
    video.pause();
  });
  /* end: Populating add to program link and favorite icon of exercise detail modal */

  /* start: Mark exercise as favorite or vice-versa */
  $('body').on("click", '.toggle-fav', function (e) {
    e.stopPropagation();
    var targetModal = $(this).closest('.modal');
    if (targetModal.length > 0 && targetModal.attr('id') == 'addexercise') {
      var toggleFavLnk = $(this),
        exercId = toggleFavLnk.parent().data('exercise-id'),
        action = detectHeartCase(toggleFavLnk);

      if (action == 'add')
        var url = 'AddFavExercise';
      else
        var url = 'RemoveFavExercise';

      var values = {
        exerciseId: exercId,
        Clientid: FX.clientid
      };
      FX.setExersiseId(exercId);

      API.customPlanAjax(url, values, function (response) {
        if (response.status == 'success') {
          var modalId = toggleFavLnk.closest('.modal').attr('id'),
            isFav = toggleHeart(toggleFavLnk),
            modalFooter = $('#exerciseList').find('.modal-footer');

          if (modalId == 'lungemodal') {
            var sourceFavLnk = $('#addexercise').find('[data-exercise-id="' + modalFooter.data('exercise-id') + '"] .toggle-fav');
            lnk = sourceFavLnk;
            toggleHeart(sourceFavLnk);
          } else
            var lnk = toggleFavLnk;

          if (isFav)
            lnk.data('is-fav', true)
          else
            lnk.data('is-fav', false)
        }
      });
    } else {
      var toggleFavLnk = $(this),
        exercId = toggleFavLnk.data('exercise-id'),
        action = detectHeartCase(toggleFavLnk);
      if (action == 'add')
        var url = 'AddFavExercise';
      else
        var url = 'RemoveFavExercise';

      var values = {
        exerciseId: exercId,
        Clientid: FX.clientid
      };
      FX.setExersiseId(exercId);

      API.customPlanAjax(url, values, function (response) {
        if (response.status == 'success') {
          var modalId = toggleFavLnk.closest('.modal').attr('id'),
            isFav = toggleHeart(toggleFavLnk);
          // modalFooter = $('#exerciseList').find('.modal-footer');

          if (modalId == 'lungemodal') {
            var sourceFavLnk = $('#addexercise').find('[data-exercise-id="' + exercId + '"] .toggle-fav');
            lnk = sourceFavLnk;
            toggleHeart(sourceFavLnk);
          } else
            var lnk = toggleFavLnk;

          if (isFav)
            lnk.data('is-fav', true)
          else
            lnk.data('is-fav', false)
        }
      });
    }
  });
  /* end: Mark exercise as favorite or vice-versa */

  /* Start: Validate and save plan  */
  $('#savePlan').click(function () {
    var isValid = true,
      $this = $(this),
      alertDiv = $('#alertDiv'),
      formData = {};
    var choosedTrainingSegment = [];
    $('.choosetrainingSegment').each(function () {
      if ($(this).is(':checked')) {
        choosedTrainingSegment.push(parseInt($(this).val()));
      }
    });


    // clear alert
    $this.attr('disabled', true);
    FX.clearNotific($this.closest('.panel-body'));
    FX.clearNotific(alertDiv);

    FX.savePlan(formData, function (response) {
      if (response.status == 'success') {
        $this.attr('disabled', false);
        FX.setClientPlanId(response.newClientPlanId);
        alertDiv.removeClass('hidden');
        window.scrollTo(0, 0);
        if (response.page == 'admin') {
          showNotific(FX.prepareNotific('success', "Plan has been saved successfully."), alertDiv);
          if (FX.PlanType == 6)
            window.location.href = public_url + "activity-builder/library-program/single-phase";
          else if (FX.PlanType == 5)
            window.location.href = public_url + "activity-builder/generate-program";
        } else {
          showNotific(FX.prepareNotific('success', "Plan has been saved successfully."), alertDiv);
          window.location.href = public_url + "client/" + FX.clientid + "#activity-plan";
          location.reload(true);
        }
      } else {
        showNotific(FX.prepareNotific('error', "Plan could not be saved."), $this.closest('.panel-body'));
      }
    })
  });
  /* End: Validate and save plan  */

  /* Start: desable/enable exercise field */
  // $('body').on('change','input[name="exercReps"]', function(){
  //   var $this = $(this),
  //   form = $this.closest('form');

  //   form.find('input[name="exercDur"]').val(0);
  // })
  /* End: desable/enable exercise field*/

  /* Start: desable/enable exercise field */
  // $('body').on('change','input[name="exercDur"]', function(){
  //   var $this = $(this),
  //   form = $this.closest('form');

  //   form.find('input[name="exercReps"]').val(0);
  // })
  /* End: desable/enable exercise field*/

})
/* End: Document load function */

/* Start: Plan Preview function */
function planPreviewFn($this) {
  var isValid = true,
    formData = {},
    daysField = $('#days-crm').find('input'),
    exerciseList = $('#exerciseList'),
    weekSlider = $("#weekSlider-crm"),
    timeSlider = $("#timeSlider-crm"),
    heightField = $("select#fit_height"),
    weightField = $("select#fit_weight"),
    dayOption = $('input[name="dayOption"]:checked'),
    daysInWeek = $('select[name="daysInWeek"]'),
    startDate = $('#startDate'),
    ageField = $('#fit_age');

  // var restSec =$('select[name ="restValue"] option:selected').text();
  //  var rest = [];
  // $('.showRest').each(function(){
  //   if($(this).is(':selected')){
  //     restSec.push(parseInt($(this).val()));
  //   }
  // });
  var choosedTrainingSegment = [];
  $('.choosetrainingSegment').each(function () {
    if ($(this).is(':checked')) {
      choosedTrainingSegment.push(parseInt($(this).val()));
    }
  });
  var i = 1;
  var order = [];
  var orderExercise = [];
  $('.add-exercise-btn').each(function () {
    if (jQuery.inArray($(this).data('workout'), choosedTrainingSegment) != -1) {
      var workotId = $(this).data('workout');
      order.push({
        'workout_id': $(this).data('workout'),
        'order': i
      });
      var parentPanel = $(this).closest('.panel');
      var j = 1;
      parentPanel.find('.exeRow').each(function () {
        var planWorkoutExerciseId = $(this).data('plan-workout-exercise-id');
        orderExercise.push({
          'planWorkoutExerciseId': planWorkoutExerciseId,
          'order': j
        });
        j = j + 1;
      });
      i = i + 1;
    }
  });
  var selectedDayOption = dayOption.val();
  var daysInWeek = $('select[name="daysInWeek"]').val();
  var selectedStartDate = startDate.val();
  if (FX.PlanType == 7) {
    formData = {
      PlanType: FX.PlanType,
      ClientPlanId: FX.ClientPlanId,
      DaysOfWeek: FX.calcWorkoutdaysPattern(daysField),
      WeeksToExercise: FX.getSliderValue(weekSlider),
      Order: order,
      OrderExercise: orderExercise,
      dayOption: selectedDayOption,
      daysInWeek: daysInWeek,
      selectedStartDate: selectedStartDate
    };
  } else if (FX.PlanType == 6) {

    formData = {
      PlanType: FX.PlanType,
      ClientPlanId: FX.ClientPlanId,
      Habit: getSelectedOptionValue('Habit'),
      WeeksToExercise: FX.getSliderValue(weekSlider),
      DaysOfWeek: FX.calcWorkoutdaysPattern(daysField),
      Order: order,
      OrderExercise: orderExercise,
      dayOption: selectedDayOption,
      daysInWeek: daysInWeek,
      selectedStartDate: selectedStartDate
    };

  } else if (FX.PlanType == 5 && FX.clientid != 0) {
    formData = {};
    formData = planFilter;
    formData['gender'] = FX.gender;
    formData['client_id'] = FX.clientid;
    formData['PlanType'] = FX.PlanType;
    formData['PlanType'] = FX.PlanType;
    formData['Height'] = withoutUnit(heightField.val());
    formData['Weight'] = withoutUnit(weightField.val());
    formData['WeeksToExercise'] = FX.getSliderValue(weekSlider);
    formData['DaysOfWeek'] = FX.calcWorkoutdaysPattern(daysField);
    formData['Order'] = order;
    formData['OrderExercise'] = orderExercise;
    formData['dayOption'] = selectedDayOption;
    formData['daysInWeek'] = daysInWeek;
    formData['selectedStartDate'] = selectedStartDate;
  } else {
    formData = {
      PlanType: FX.PlanType,
      ClientPlanId: FX.ClientPlanId,
      client_id: FX.clientid,
      Method: getSelectedOptionValue('Method'),
      Intensity: getSelectedOptionValue('Intensity'),
      Experience: getSelectedOptionValue('Experience'),
      TimePerWeek: FX.getSliderValue(timeSlider),
      Height: withoutUnit(heightField.val()),
      Weight: withoutUnit(weightField.val()),
      Age: ageField.val(),
      WeeksToExercise: FX.getSliderValue(weekSlider),
      DaysOfWeek: FX.calcWorkoutdaysPattern(daysField),
      Order: order,
      OrderExercise: orderExercise,
      dayOption: selectedDayOption,
      daysInWeek: daysInWeek,
      selectedStartDate: selectedStartDate
    };
    if (FX.PlanType == 8) {
      formData['clientVideoId'] = FX.ClientVideoId;
    }
  }
  FX.planPreview(formData, rendorPlanPreview);
}
/* End: Plan Preview function */

/* start: Run validation for 'trainingSegment' and open related accordian */
function trainingSegmentSubmit() {
  var trainingSegmentSubmitBtn = $("#trainingSegmentSubmit"),
    trainingSegmentPanelBody = $('.panel[data-step="trainingSegment"] > .panel-body');
  trainingSegmentSubmitBtn.attr('disabled', false);
  FX.clearNotific(trainingSegmentPanelBody);
  var response = validateTrainingSegment();
  if (response.setFormValid) {
    openStep(trainingSegmentSubmitBtn);
  } else {
    showNotific(FX.prepareNotific('error', response.message), trainingSegmentPanelBody);
  }
}
/* end: Run validation for 'trainingSegment' and open related accordian */

/* Start: Resetting Flow if needed and Open related accordian */
function openStep(elem) {
  FX.clearNotific($('#alertDiv'));
  var openNextStep = true;

  var changeFlow = elem.data('change-flow');
  if (changeFlow)
    elem.closest('.panel').nextAll(':not(.pinned)').addClass('hidden')

  var targetStepName = elem.data('target-step');
  if (targetStepName) {
    var targetStep = $('[data-step="' + targetStepName + '"]');
    if (targetStepName == 'currentAbility') {
      if (FX.PlanType == 6) //Library Program 
        targetStep.find('a.open-step').data('target-step', "equipmentHave");
      else //Generator Program 

        targetStep.find('a.open-step').data('target-step', "programWant");
    } else if (targetStepName == 'equipmentHave') {
      //call function and fatch exercise
      targetStep.find('a.open-step').data('target-step', "programWant");
    } else if (targetStepName == 'programWant') { // Fetching programs and show for choose
      targetStep.find('.item_class').empty();
      if (FX.PlanType == 6) {
        var data = {};
        data['habit'] = getSelectedOptionValue('Habit');
        data['equipment'] = getSelectedOptionValue('Method'),
          FX.getFilterPlan(data, setPrgramImages);
      }
    } else if (targetStepName == 'trainingSegment') {
      $('#trainingSegmentSubmit').data('plan-type',elem.data('plan-type'));
      $('#trainingSegmentSubmit').data('weeks',elem.data('weeks'));
      $('#trainingSegmentSubmit').data('day-option',elem.data('day-option'));
      $('#trainingSegmentSubmit').data('days-in-week',elem.data('days-in-week'));
      $('#trainingSegmentSubmit').data('day-pattern',elem.data('day-pattern'));
      FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));
    } else if (targetStepName == 'planMyProgram') {
      FX.clearNotific(targetStep.children('.panel-body'));
      setFieldNeutral($('#days-crm'))
      $("#timeSelection").hide();
      //Start: Days Option show hide in Library Program
      var pathname = window.location.pathname;
      if (pathname.includes("library-program")) {
        $("#daySelect").hide();
      } else {
        if (startData != '') {
          $("#daySelect").find('input[name="startDate"]').val(startData);
        }
        $("#daySelect").show();
      }
      //End
      //Set default data for week slider
        var defaultValForWeek = 12,
        maxWeeks = 16,
        maxTime = 6,
        text = 'at least 2',
        minDays = 0
      //End

      if (FX.PlanType == 7) {
        targetStep.find('button.open-step').data('target-step', "planPreview");
      } 
      if (FX.clientid != 0) {
        let dayOption = elem.data('day-option');
        if(dayOption != undefined && dayOption != '' && dayOption == 1){
          $('select[name=daysInWeek]').val('');
          $('select[name=daysInWeek]').attr('disabled', false);
          $('select[name=daysInWeek]').selectpicker('refresh');
          $('input[name="noOfDaysWeek"]').val('');
          $('#Choosedays').trigger('click');
          $('#showWeekday').show();
          $('#selectOneday').hide();
        }else if(dayOption != undefined && dayOption != '' && dayOption == 2){
          $('select[name=daysInWeek]').val(elem.data('days-in-week'));
          $('select[name=daysInWeek]').attr('disabled', true);
          $('select[name=daysInWeek]').selectpicker('refresh');
          $('input[name="noOfDaysWeek"]').val(elem.data('days-in-week'));
          $('#daysInWeek').trigger('click');
          $('#showWeekday').hide();
          $('#selectOneday').show();
          text = elem.data('days-in-week');
        }else{
          $('select[name=daysInWeek]').val('');
          $('select[name=daysInWeek]').attr('disabled', false);
          $('select[name=daysInWeek]').selectpicker('refresh');
          $('input[name="noOfDaysWeek"]').val('');
          $('#Choosedays').trigger('click');
          $('#showWeekday').show();
          $('#selectOneday').hide();
        }
        var dayPatterns = elem.data('day-pattern');
        if (!dayPatterns)
          dayPatterns = '0000000';
        else
          dayPatterns = dayPatterns.toString();

        var dayPattern = dayPatterns.split('');
        /*var sunday = dayPattern.shift();
        dayPattern.push(sunday)*/
        var checkedDaysCount = 0;
        $.each(dayPattern, function (index, value) {
          var day = $('#days-crm').find('input').eq(index);
          if (value == 1) {
            day.prop('checked', true)
            checkedDaysCount++;
          } else
            day.prop('checked', false)
        });
        if (checkedDaysCount) {
          var text = 'at least ' + checkedDaysCount,
            minDays = checkedDaysCount;
        }
        if (FX.PlanType == 6) {
          targetStep.find('button.open-step').data('target-step', "planPreview");
          $("#timeSelection").hide();
        } else if (FX.PlanType == 5) {
          var time = elem.data('time');
          if (!time)
            time = 3;

          $("#timeSelection").show();
          targetStep.find('button.open-step').data('target-step', "personalInfo");
        }
      } else {
        targetStep.find('button.open-step').data('target-step', "planPreview");
        $('#days-crm').find('input').prop('checked', false);
        let dayOption = elem.data('day-option');
        if(dayOption != undefined && dayOption != '' && dayOption == 1){
          $('select[name=daysInWeek]').val('');
          $('select[name=daysInWeek]').attr('disabled', false);
          $('select[name=daysInWeek]').selectpicker('refresh');
          $('input[name="noOfDaysWeek"]').val('');
          $(".chooseDays").hide();
          $(".chooseWeek").show();
          $(".startDateOption").hide();
          $("#showWeekday").hide();
          $("#selectOneday").show();
          $('.letClientSelect').hide();
          $("#daySelect").show();
        }else if(dayOption != undefined && dayOption != '' && dayOption == 2){
          $('select[name=daysInWeek]').val(elem.data('days-in-week'));
          $('select[name=daysInWeek]').attr('disabled', false);
          $('select[name=daysInWeek]').selectpicker('refresh');
          $('input[name="noOfDaysWeek"]').val(elem.data('days-in-week'));
          $(".chooseDays").hide();
          $(".chooseWeek").show();
          $(".startDateOption").hide();
          $("#showWeekday").hide();
          $("#selectOneday").show();
          $('.letClientSelect').hide();
          $("#daySelect").show();
        }else{
          $('select[name=daysInWeek]').val('');
          $('select[name=daysInWeek]').attr('disabled', false);
          $('select[name=daysInWeek]').selectpicker('refresh');
          $('input[name="noOfDaysWeek"]').val('');
          $(".chooseDays").hide();
          $(".chooseWeek").show();
          $(".startDateOption").hide();
          $("#showWeekday").hide();
          $("#selectOneday").show();
          $('.letClientSelect').hide();
          $("#daySelect").show();
        }
      }
     
      var weeks = elem.data('weeks');
      if (typeof weeks != 'undefined' && weeks) {
        defaultValForWeek = weeks;
      }
      $( "#weekSlider-crm" ).labeledslider("destroy");
      if (FX.PlanType == 6){
        if (FX.clientid != 0){
          $("#weekSlider-crm").labeledslider({
            min: 1,
            max: maxWeeks,
            tickInterval: 1,
            value: defaultValForWeek,
            range: "min",
            disabled:true
          });
        }else{
          $("#weekSlider-crm").labeledslider({
            min: 1,
            max: maxWeeks,
            tickInterval: 1,
            value: defaultValForWeek,
            range: "min"
          });
        }
      }else{
        $("#weekSlider-crm").labeledslider({
          min: 1,
          max: maxWeeks,
          tickInterval: 1,
          value: defaultValForWeek,
          range: "min"
        });
      }
      //$("#weekSlider-crm").labeledslider("option", "max", maxWeeks).labeledslider("value", defaultValForWeek);
      $('#daySelectionTextCRM').html(text);
      $('#days-crm').data('days-required', minDays);
    } else if (targetStepName == 'personalInfo') {
      //planPreviewFn(elem); 
    } else if (targetStepName == 'planPreview') {
      var pathname = window.location.pathname;
      if (pathname.includes("library-program")) {
        planPreviewFn(elem);
      } else if (pathname.includes("fitness")) {
        planPreviewFn(elem);
      } else {
        var dayOption = $('input[name="dayOption"]:checked').val(),
          checkbox = $('input[name="weekDay"]:checked').length;
        noOfDaysWeek = $('input[name="noOfDaysWeek"]').val();
        if (dayOption == 1) {
          if (checkbox == noOfDaysWeek) {
            planPreviewFn(elem);
          } else {
            swal({
              type: "warning",
              title: 'Please select days',
              allowOutsideClick: true,
              showCancelButton: false,
              confirmButtonText: 'OK',
              confirmButtonColor: '#ff4401',
            });
            openNextStep = false;
          }
        } else {
          planPreviewFn(elem);
        }
      }
    }
    if (openNextStep) {
      targetStep.removeClass('hidden');
      targetStep.find('.panel-heading .panel-collapse').trigger('click');
    }
  }
}
/* End: Resetting Flow if needed and Open related accordian */

/* start: Update per day workout time in slider */
function updatePerDayWorkoutTime(minsPerWeek) {
  var daysTraining = $('#days-crm').find('input').filter(':checked').length;
  text = FX.calcPerDayWorkoutTime(minsPerWeek, daysTraining);

  $(".ui-slider-handle", $("#timeSlider-crm")).html("<span class='handleText'>" + text + "</span>");
}
/* end: Update per day workout time in slider */

/* Start: Reset 'trainingSegment' accordian */
function resetTrainingSegments() {
  $(".choosetrainingSegment").prop('checked', false).trigger('change');
  $('#choosedTrainingsAccordion').children('.panel').removeClass('marked-to-delete')
  $('.choosedExercRow').empty();
}
/* End: Reset 'trainingSegment' accordian */

/* start: Loading exercises and populating them */
function getExercises(contnue) {
  toggleWaitShield("show");
  var exerciseList = $('.exerciseVideoList'),
    addExerciseModal = $('.add-exercises'),
    loading = exerciseList.next();

  // loading.show();
  var pageNumb,
    iss = FX.UI.searchScroll;

  if (typeof contnue != 'undefined' && contnue) {
    pageNumb = ++FX.UI.currentPage - 1;
    //iss.enabled = false;
  } else {
    pageNumb = FX.UI.currentPage = 1;
    //iss.enabled = true;
    exerciseList.html('')
  }

  var options = {
    clientId: FX.clientid,
    workoutId: workout_id,
    category: '',
    equipment: '',
    ability: '',
    bodypart: '',
    movement_type: '',
    movement_pattern: '',
    perPage: 4,
    pageNumber: pageNumb
  };

  var favKase = detectHeartCase(addExerciseModal.find('#favSearch'));
  if (favKase == 'add')
    options.myFavourites = false;
  else if (favKase == 'remove')
    options.myFavourites = true;

  options.keyWords = addExerciseModal.find('#keySearchExercise').val();
  options.bodypart = addExerciseModal.find('#muscle_group').val();
  options.ability = addExerciseModal.find('#ability').val();
  options.equipment = addExerciseModal.find('#equipment').val();
  options.category = addExerciseModal.find('#category').val();
  options.movement_type = addExerciseModal.find('#movement_type').val();
  options.movement_pattern = addExerciseModal.find('#movement_pattern').val();
  API.customPlanAjax('SearchExercises', options, function (response) {
    var exercises = response.Exercises;
    exerciseList.empty();
    if ((exercises == undefined)) {
      //iss.enabled = false;
      var text = FX.prepareAlert('warning', 'No ' + (pageNumb == 1 ? '' : 'more') + ' exercise found.');
      exerciseList.append('<div class="col-md-12">' + text + '</div>');
    } else if (exercises.length) {
      var html = '';
      exerciseList.empty();
      $.each(exercises, function (index, value) {
        var desc = value.ExerciseDesc;

        if (desc.length > 19)
          var descUi = desc.substring(0, 19) + '...';
        else
          var descUi = desc;

        var imagePath = '';
        if (value.thumbnailProgram != '') {
          imagePath = public_url + 'uploads/' + value.thumbnailProgram;
        } else {
          imagePath = public_url + 'result/images/epic-icon-orenge.png';
        }
        html += '<div class="col-md-3" draggable="true" ondragstart="drag(event)" ondragleave="dragLeave(event)" data-exercise-name="' + value.name + '" data-exeid="' + value.id + '"><a data-toggle="modal" class="lungemodalCls" data-exercise-name="' + value.name + '" data-exeid="' + value.id + '"> <img src="' + imagePath + '" class="mw-100p">\
          <h5> ' + value.name + '<br/> (' + value.sub_heading + ') </h5>\
          <button class="btn btn-xs btn-primary m-b-2 toggle-fav" data-exercise-type-id="' + value.ExerciseTypeID + '" data-exercise-id="' + value.id + '" data-is-fav="' + value.IsFav + '">' +
          ((value.IsFav == true) ? '<i class="fa fa-heart"></i>' : '<i class="fa fa-heart-o"></i>') +
          '</button>\
          <button class="btn btn-xs btn-primary toggle-exercise"><i class="fa fa-plus"></i> </button> <button class="btn btn-xs btn-primary"><i class="fa fa-arrows"></i> </button></a>\
          </div>';
      });

      exerciseList.append(html);
      //iss.enabled = true;
    } else {
      //iss.enabled = false;
      var text = FX.prepareAlert('warning', 'No ' + (pageNumb == 1 ? '' : 'more') + ' exercise found.');
      exerciseList.append('<div class="col-md-12">' + text + '</div>');
    }
    // loading.hide();
    $('.exerciseVideoList').data('plan-type', '1');
    toggleWaitShield("hide");
  });
  FX.UI.currentPage++;
}
/* end: Loading exercises and populating them */

/**
 * Darg And Drop Function Start
 */
function drag(ev) {
  if (event.target.nodeName != "IMG") {
    var exeId = ev.target.attributes['data-exeid'].nodeValue;
    ev.dataTransfer.setData("exeid", exeId);
  } else {
    ev.preventDefault();
  }
}

function allowDrop(ev, event) {
  if ($(event).hasClass('exeRow')) {
    $('.dropDiv').remove();
    $(event).after('<div class="row exeRow dropDiv"></div>');
  }
  ev.preventDefault();
}

function dragLeave(ev) {
  $('.dropDiv').remove();
}

function drop(ev, event) {
  ev.preventDefault();
  var exeId = ev.dataTransfer.getData("exeid");
  toggleWaitShield("show");
  formData = {},
    exerciseList = $('.exerciseVideoList');
  FX.setExersiseId(exeId);
  var panel = $(event).closest('.panel-body');
  var order = 1;
  var orderData = [];
  var exeOrder = 0;
  panel.find('.exeRow').each(function () {
    if ($(this).hasClass('dropDiv')) {
      exeOrder = order;
    } else {
      orderData.push({
        'planWorkoutExerciseId': $(this).data('plan-workout-exercise-id'),
        'order': order
      });
    }
    order = order + 1;
  });
  formData.WorkOutName = $(event).data('work-out');
  if (isMultiPhase || (FX.PlanType != undefined && FX.PlanType == 9)) {
    formData.ClientPlanId = $('#clientPlanProgramId').val();
  } else {
    formData.ClientPlanId = FX.ClientPlanId;
  }
  formData.ExerciseId = FX.ExersiseId;
  formData.PlanType = exerciseList.data('plan-type');
  formData.exeOrder = exeOrder;
  formData.orderData = orderData;
  if(isMultiPhase == true){
    formData.ClientPlanType = 9;
  }else{
    formData.ClientPlanType = FX.PlanType;
  }
  FX.addExToProgram(formData, function (response) {
    if (response.status == 'success') {
      FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));
      populateTrainingSegments(response.Exercises, response.isVideo);
      if (FX.PlanType != 'undefined' && FX.PlanType == 5) {
        var generatorModal = $('#generatorModal');
        generatorModal.modal({
          backdrop: 'static',
          keyboard: false
        })
        generatorModal.find('input[name="workout_name"]').val(formData.WorkOutName);
      }
      $('#choosedTrainingsAccordion').find('a[href="#accord_' + formData.WorkOut + '"]').trigger('click');
      toggleWaitShield("hide");
    }
  });
}

/* start: Render exercises in 'trainingSegment' accordian */
function populateTrainingSegments(exercises, isVideo,response= undefined) {
  if (isVideo) {
    $('.exeTypeRow').find('#ExerciseType2').attr('checked', true);
  } else {
    $('.exeTypeRow').find('#ExerciseType1').attr('checked', true);
  }
  //Sort Training Segments
  if(response != undefined){
    $.each(response.workoutData,function(key,obj){
      var workoutName = obj.name;
      var workoutElement = $('#choosedTrainingsAccordion').find('#accord_'+workoutName).closest('.panel').clone();
      $('#choosedTrainingsAccordion').find('#accord_'+workoutName).closest('.panel').remove();
      $('#choosedTrainingsAccordion').prepend(workoutElement);
    });
    
      $(".sortExe").sortable({
        placeholder: "ui-sortable-placeholder"
      });
   
  }
  if (exercises.length) {
    var html = {},
      programId = 0;
    $.each(exercises, function (index, value) {
      if (!programId)
        programId = value.FixedProgramID;

      if (value.TempoDesc == null)
        value.TempoDesc = '';
      if (value.Resistance == null)
        value.Resistance = '';
      if (value.planType == '1') {
        if (value.isRest == '0') {
          var rowHtml = '';
          if (value.exercise_sets.length > 0) {
            $.each(value.exercise_sets, function (key, obj) {
              rowHtml += '<div class="row treningSegCls">\
                  <form class="treningSeg-form" action="">\
                    <div class="col-md-8 col-sm-8 col-xs-8 form-inline row ml-0 mr-0 pr-0">\
                      <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
                        <input type="number" value="' + obj.sets + '" class="form-control custom-form-control numericField" id="exercSets" name="exercSets" min="0" required="required" readonly placeholder="SETS">\
                      </div>\
                      <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
                        <input type="text" onclick="this.select()" value="' + obj.tempoDesc + '" class="form-control custom-form-control numb-colon" id="exercTempo" name="exercTempo" required="required" placeholder="TEMPO">\
                      </div>\
                      <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
                        <input type="number" onclick="this.select()" value="' + obj.repetition + '" class="form-control custom-form-control numericField" id="exercReps" name="exercReps" min="0" required="required" placeholder="REPETITION">\
                      </div>\
                      <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
                        <input type="number" onclick="this.select()" value="' + obj.estimatedTime + '" class="form-control numericField custom-form-control" id="exercDur" name="exercDur" min="0" required="required" placeholder="OR DURATION">\
                      </div>\
                      <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
                        <input type="text" onclick="this.select()" value="' + obj.resistance + '" class="form-control custom-form-control" id="exercResist" name="exercResist" required="required" placeholder="RESISTANCE">\
                      </div>\
                      <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
                        <input type="number" onclick="this.select()" value="' + obj.restSeconds + '" class="form-control numericField custom-form-control" id="exercRest" name="exercRest" min="0" required="required" placeholder="REST">\
                      </div>\
                    </div>\
                    <div class="col-md-4 col-sm-4 col-xs-4 p-t-20 text-center" data-exercise-set-id="' + obj.id + '" data-exercise-id="' + value.ExerciseID + '" data-planeworkexe-id="' + value.EditWorkoutId + '">\
                      <a class="btn btn-sm btn-default tooltips saveTrainingSeg" href="#" data-placement="top" data-original-title="Save">\
                        <i class="fa fa-save link-btn"></i>\
                      </a>\
                      <a href="#" class="btn btn-sm btn-default tooltips deleteExeSet" data-placement="top" data-original-title="Delete">\
                        <i class="fa fa-times link-btn"></i>\
                      </a>\
                    </div>\
                  </form>\
                </div>';
            });
          } else {
            rowHtml = '<div class="row treningSegCls">\
                <form class="treningSeg-form" action="">\
                  <div class="col-md-8 col-sm-8 col-xs-8 form-inline row ml-0 mr-0 pr-0">\
                    <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
                      <input type="number" value="1" class="form-control custom-form-control numericField" id="exercSets" name="exercSets" min="0" required="required" readonly placeholder="SETS">\
                    </div>\
                    <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
                      <input type="text" value="" class="form-control custom-form-control numb-colon" id="exercTempo" name="exercTempo" required="required" placeholder="TEMPO" onclick="this.select()">\
                    </div>\
                    <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
                      <input type="number" value="" class="form-control custom-form-control numericField" id="exercReps" name="exercReps" min="0" required="required" placeholder="REPETITION" onclick="this.select()">\
                    </div>\
                    <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
                      <input type="number" value="" class="form-control numericField custom-form-control" id="exercDur" name="exercDur" min="0" required="required" placeholder="OR DURATION" onclick="this.select()">\
                    </div>\
                    <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
                      <input type="text" value="" class="form-control custom-form-control" id="exercResist" name="exercResist" required="required" placeholder="RESISTANCE" onclick="this.select()">\
                    </div>\
                    <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
                    <input type="number" value="" class="form-control numericField custom-form-control" id="exercRest" name="exercRest" min="0" required="required" placeholder="REST" onclick="this.select()">\
                    </div>\
                  </div>\
                  <div class="col-md-4 col-sm-4 col-xs-4 pl-0 text-center" data-exercise-id="' + value.ExerciseID + '" data-planeworkexe-id="' + value.EditWorkoutId + '">\
                    <a class="btn btn-sm btn-default tooltips saveTrainingSeg" href="#" data-placement="top" data-original-title="Save">\
                      <i class="fa fa-save link-btn"></i>\
                    </a>\
                    <a href="#" class="btn btn-sm btn-default tooltips deleteExeSet" data-placement="top" data-original-title="Delete">\
                      <i class="fa fa-times link-btn"></i>\
                    </a>\
                  </div>\
                </form>\
              </div>';
          }
          var imagePathNew = '';
          if (value.thumbnail_program != '' && value.thumbnail_program != null) {
            imagePathNew = public_url + 'uploads/' + value.thumbnail_program;
          } else {
            imagePathNew = public_url + 'result/images/epic-icon-orenge.png';
          }
          var string = '<div class="exeRow" ondragover="allowDrop(event,this)" data-is-rest="' + value.isRest + '" data-plan-workout-exercise-id="' + value.EditWorkoutId + '">\
              <div class="panel panel-white exercise" data-duration="' + value.EstimatedTime + '">\
                <div class="panel-body">\
                  <div class="row">\
                    <div class="col-md-9 col-sm-9 col-xs-10 " id="txt_' + value.EditWorkoutId + '">\
                      <div class="custom-checkbox"></div>\
                      <img src="' + imagePathNew + '" class="sideimg float-left">\
                      <h2 class="heading-training">' + value.Name + '<br/> (' + value.sub_heading + ')</h2>\
                      <div class="hidden float-left width-100" id="segment_' + value.EditWorkoutId + '">\
                        <button class="addMoreRow" data-exercise-id="' + value.ExerciseID + '" data-planeworkexe-id="' + value.EditWorkoutId + '" data-segment-id="segment_' + value.EditWorkoutId + '">&#10010;</button>\
                        <button class="hidePlanWorkoutExe" data-exercise-id="' + value.ExerciseID + '" data-planeworkexe-id="' + value.EditWorkoutId + '" data-segment-id="segment_' + value.EditWorkoutId + '">&#10005;</button>\
                        ' + rowHtml + '\
                      </div>\
                    </div>\
                    <div id="segment_editbtn_' + value.EditWorkoutId + '" class="col-md-3 col-sm-3 col-xs-2 pl-0" data-exercise-id="' + value.ExerciseID + '" data-editworkout="' + value.EditWorkoutId + '">\
                      <a class="btn btn-sm btn-default tooltips showTrainingSeg" href="#" data-placement="top" data-original-title="Edit" data-idnumber="' + value.FixedProgramID + '" data-toggle="modal" >\
                        <i class="fa fa-pencil link-btn"></i>\
                      </a>\
                      <a href="#" class="btn btn-sm btn-default tooltips delExercise" data-placement="top" data-original-title="Delete">\
                        <i class="fa fa-trash-o link-btn"></i>\
                      </a><i class="fa fa-arrows drag-drop-btn" ></i>\
                    </div>\
                  </div>\
                </div>\
              </div>\
            </div>';
        } else {

          var string = '<div class="row exeRow" ondragover="allowDrop(event,this)" data-is-rest="' + value.isRest + '" data-plan-workout-exercise-id="' + value.EditWorkoutId + '"><div class="panel panel-white exercise" data-duration="null"><div class="panel-body"><div class="row"><div class="col-md-9 col-sm-9 col-xs-10 exe-rest-btn" id="txt_' + value.EditWorkoutId + '"><div class="custom-checkbox"></div><img src="' + public_url + 'assets/images/hand.png" class="mw-70p"><select name="exeRest" class="selectpickerRest exeRest"> <option value="10" ' + (value.RestSeconds == '10' ? 'selected' : '') + '>10 Sec</option> <option value="20" ' + (value.RestSeconds == '20' ? 'selected' : '') + '>20 Sec</option> <option value="30" ' + (value.RestSeconds == '30' ? 'selected' : '') + '>30 Sec</option> <option value="40" ' + (value.RestSeconds == '40' ? 'selected' : '') + '>40 Sec</option> <option value="50" ' + (value.RestSeconds == '50' ? 'selected' : '') + '>50 Sec</option> <option value="60" ' + (value.RestSeconds == '60' ? 'selected' : '') + '>60 Sec</option> <option value="70" ' + (value.RestSeconds == '70' ? 'selected' : '') + '>70 Sec</option><option value="80" ' + (value.RestSeconds == '80' ? 'selected' : '') + '>80 Sec</option><option value="90" ' + (value.RestSeconds == '90' ? 'selected' : '') + '>90 Sec</option> <option value="120" ' + (value.RestSeconds == '120' ? 'selected' : '') + '>2 Min</option> <option value="180" ' + (value.RestSeconds == '180' ? 'selected' : '') + '>3 Min</option> <option value="240" ' + (value.RestSeconds == '240' ? 'selected' : '') + '>4 Min</option> <option value="300" ' + (value.RestSeconds == '300' ? 'selected' : '') + '>5 Min</option> <option value="600" ' + (value.RestSeconds == '600' ? 'selected' : '') + '>10 Min</option> <option value="900" ' + (value.RestSeconds == '900' ? 'selected' : '') + '>15 Min</option> <option value="1200" ' + (value.RestSeconds == '1200' ? 'selected' : '') + '>20 Min</option> <option value="1500" ' + (value.RestSeconds == '1500' ? 'selected' : '') + '>25 Min</option> <option value="1800" ' + (value.RestSeconds == '1800' ? 'selected' : '') + '>30 Min</option></select></div><div id="segment_editbtn_' + value.EditWorkoutId + '" class="col-md-3 col-sm-3 col-xs-2 pl-0" data-exercise-id="' + value.ExerciseID + '" data-editworkout="' + value.EditWorkoutId + '"><a href="#" class="btn btn-sm btn-default tooltips delExercise" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o link-btn"></i></a><i class="fa fa-arrows drag-drop-btn" ></i></div></div></div></div></div></div>';
        }
      } else {
        if (value.isRest == '0') {
          var imagePathVideo = '';
          if (value.thumbnail != '' && value.thumbnail != null) {
            imagePathVideo = public_url + 'uploads/' + value.thumbnail;
          } else {
            imagePathVideo = public_url + 'result/images/epic-icon-orenge.png';
          }
          var string = '<div class="exeRow" ondragover="allowDrop(event,this)" data-is-rest="' + value.isRest + '">\
              <div class="panel panel-white exercise" data-duration="' + value.EstimatedTime + '">\
                <div class="panel-body">\
                  <div class="row">\
                    <div class="col-md-9 col-sm-9 col-xs-10">\
                    <div class="custom-checkbox"></div>\
                      <img src="' + imagePathVideo + '" class="sideimg float-left">\
                      <h2 class="video-heading">' + value.Name + '</h2>\
                    </div>\
                    <div id="segment_editbtn_' + value.EditWorkoutId + '" class="col-md-3 col-sm-3 col-xs-2 p-t-10 pl-0" data-exercise-id="' + value.ExerciseID + '" data-editworkout="' + value.EditWorkoutId + '">\
                      <a href="#" class="btn btn-sm btn-default tooltips delExercise" data-placement="top" data-original-title="Delete">\
                        <i class="fa fa-trash-o link-btn"></i>\
                      </a><i class="fa fa-arrows drag-drop-btn" ></i>\
                    </div>\
                  </div>\
                </div>\
              </div>\
            </div>';
        } else {
          var string = '<div class="row exeRow" ondragover="allowDrop(event,this)" data-is-rest="' + value.isRest + '" data-plan-workout-exercise-id="' + value.EditWorkoutId + '"><div class="panel panel-white exercise" data-duration="' + value.RestSeconds + '"><div class="panel-body"><div class="row"><div class="col-md-9 col-sm-9 col-xs-10" id="txt_' + value.EditWorkoutId + '"><div class="custom-checkbox"></div><img src="' + public_url + 'assets/images/hand.png" class="mw-70p"><select name="exeRest" class="selectpickerRest exeRest"> <option value="10" ' + (value.RestSeconds == '10' ? 'selected' : '') + '>10 Sec</option> <option value="20" ' + (value.RestSeconds == '20' ? 'selected' : '') + '>20 Sec</option> <option value="30" ' + (value.RestSeconds == '30' ? 'selected' : '') + '>30 Sec</option> <option value="40" ' + (value.RestSeconds == '40' ? 'selected' : '') + '>40 Sec</option> <option value="50" ' + (value.RestSeconds == '50' ? 'selected' : '') + '>50 Sec</option> <option value="60" ' + (value.RestSeconds == '60' ? 'selected' : '') + '>60 Sec</option> <option value="70" ' + (value.RestSeconds == '70' ? 'selected' : '') + '>70 Sec</option><option value="80" ' + (value.RestSeconds == '80' ? 'selected' : '') + '>80 Sec</option><option value="90" ' + (value.RestSeconds == '90' ? 'selected' : '') + '>90 Sec</option> <option value="120" ' + (value.RestSeconds == '120' ? 'selected' : '') + '>2 Min</option> <option value="180" ' + (value.RestSeconds == '180' ? 'selected' : '') + '>3 Min</option> <option value="240" ' + (value.RestSeconds == '240' ? 'selected' : '') + '>4 Min</option> <option value="300" ' + (value.RestSeconds == '300' ? 'selected' : '') + '>5 Min</option> <option value="600" ' + (value.RestSeconds == '600' ? 'selected' : '') + '>10 Min</option> <option value="900" ' + (value.RestSeconds == '900' ? 'selected' : '') + '>15 Min</option> <option value="1200" ' + (value.RestSeconds == '1200' ? 'selected' : '') + '>20 Min</option> <option value="1500" ' + (value.RestSeconds == '1500' ? 'selected' : '') + '>25 Min</option> <option value="1800" ' + (value.RestSeconds == '1800' ? 'selected' : '') + '>30 Min</option></select></div><div id="segment_editbtn_' + value.EditWorkoutId + '" class="col-md-3 col-sm-3 col-xs-2 p-t-10 pl-0" data-exercise-id="' + value.ExerciseID + '" data-editworkout="' + value.EditWorkoutId + '"><a href="#" class="btn btn-sm btn-default tooltips delExercise" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o link-btn"></i></a><i class="fa fa-arrows drag-drop-btn" ></i></div></div></div></div></div></div>';
        }
      }
      if (value.WorkOut in html)
        html[value.WorkOut] += string;
      else
        html[value.WorkOut] = string;
    });

    $.each(html, function (index, value) {
      var choosedExercRow = $('.choosedExercRow[data-work-out="' + index + '"]');
      if (choosedExercRow.length) {
        if (choosedExercRow.find('.dropDiv').length > 0) {
          choosedExercRow.find('.dropDiv').after(value).data('program-id', programId);
          choosedExercRow.find('.dropDiv').remove();
        } else {
          choosedExercRow.append(value).data('program-id', programId);
        }
        var accordId = choosedExercRow.closest('.panel-collapse').attr('id'),
          cbxId = accordId.split('accord_');
        $(".choosetrainingSegment").filter('[id="' + cbxId[1] + '"]').prop('checked', true).trigger('change');
      }
    });
  }
  $('#proExTime').show();
  $('#proVidTime').hide();
  setTimeout(programTotalTime, 3000);
  if (isVideo == '1') {
    $('#proExTime').hide();
    $('#proVidTime').show();
    setTimeout(programTotalVideoTime, 3000);
  }
  $('.selectpickerRest').selectpicker();
}
/* end: Render exercises in 'trainingSegment' accordian */

$('body').on('click', '.addMoreRow', function () {
  var segmentId = $(this).data('segment-id');
  var sets = $('#' + segmentId).find('.treningSegCls').length + 1;
  var html = '<div class="row treningSegCls">\
    <form class="treningSeg-form" action="">\
      <div class="col-md-8 col-sm-8 col-xs-8 form-inline row ml-0 mr-0 pr-0">\
        <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
          <input type="number" value="' + sets + '" class="form-control custom-form-control numericField" id="exercSets" name="exercSets" min="0" required="required" readonly placeholder="SETS">\
        </div>\
        <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
          <input type="text" value="" class="form-control custom-form-control numb-colon" id="exercTempo" name="exercTempo" required="required" placeholder="TEMPO" onclick="this.select()">\
        </div>\
        <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
          <input type="number" value="" class="form-control custom-form-control numericField" id="exercReps" name="exercReps" min="0" required="required" placeholder="REPETITION" onclick="this.select()">\
        </div>\
        <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
          <input type="number" value="" class="form-control numericField custom-form-control" id="exercDur" name="exercDur" min="0" required="required" placeholder="OR DURATION" onclick="this.select()">\
        </div>\
        <div class="form-group col-md-6 col-xs-6 col-sm-6 pr-7">\
          <input type="text" value="" class="form-control custom-form-control" id="exercResist" name="exercResist" required="required" placeholder="RESISTANCE" onclick="this.select()">\
        </div>\
        <div class="form-group col-md-6 col-xs-6 col-sm-6 pl-7">\
          <input type="number" value="" class="form-control numericField custom-form-control" id="exercRest" name="exercRest" min="0" required="required" placeholder="REST" onclick="this.select()">\
        </div>\
      </div>\
      <div class="col-md-4 col-sm-4 col-xs-4 p-t-20 text-center" data-exercise-id="' + $(this).data('exercise-id') + '" data-planeworkexe-id="' + $(this).data('planeworkexe-id') + '">\
        <a class="btn btn-sm btn-default tooltips saveTrainingSeg" href="#" data-placement="top" data-original-title="Save">\
          <i class="fa fa-save link-btn"></i>\
        </a>\
        <a href="#" class="btn btn-sm btn-default tooltips deleteExeSet" data-placement="top" data-original-title="Delete">\
          <i class="fa fa-times link-btn"></i>\
        </a>\
      </div>\
    </form>\
  </div>';
  var segmentId = $(this).data('segment-id');
  $('#' + segmentId).append(html);
});

/**
 * Delete Exercise Sets
 */
$('body').on('click', '.deleteExeSet', function (e) {
  e.preventDefault();
  var $this = $(this),
    formData = {},
    form = $this.closest('.treningSeg-form'),
    workoutExerciseSetId = $this.closest('div').data('exercise-set-id'),
    workoutExerciseId = $this.closest('div').data('exercise-id'),
    EditWorkoutId = $this.closest('div').data('planeworkexe-id');

  formData['workoutExerciseSetId'] = workoutExerciseSetId;
  formData['workoutExerciseId'] = workoutExerciseId;
  formData['editWorkoutId'] = EditWorkoutId;
  API.customPlanAjax('DeleteTrainingSegment', formData, function (response) {
    if (response.status == 'success') {
      $this.closest('.treningSegCls').remove();
      $('#segment_' + EditWorkoutId).addClass('hidden');
      $('#segment_editbtn_' + EditWorkoutId).show();
      programTotalTime();
      $('#txt_' + EditWorkoutId).removeClass('col-sm-12 col-xs-12 col-md-12');
      $('#txt_' + EditWorkoutId).addClass('col-sm-9 col-xs-10 col-md-9');
    }
  });
});

/* start: Detect if heart is clicked to add exercise to favorite or vice-versa */
function detectHeartCase(elem) {
  var ital = elem.children();

  if (ital.hasClass('fa-heart-o'))
    return 'add'
  else
    return 'remove'
}
/*end: Detect if heart is clicked to add exercise to favorite or vice-versa*/

/* start: Calculate and display plan total time */
function programTotalTime() {
  var totSecs = 0;
  if ($('#choosedTrainingsAccordion:visible').length > 0) {
    $('#choosedTrainingsAccordion').find('.exeRow').each(function () {
      if ($(this).data('is-rest') == '0') {
        $(this).find('form.treningSeg-form').each(function () {
          var panal = $(this).closest('.panel:visible');
          if (panal.length > 0 && !$(this).closest('.panel').hasClass('marked-to-delete')) {
            var $this = $(this),
              dur = parseInt($this.find('input[name="exercDur"]').val());
            dur = isNaN(dur) ? 0 : dur;
            var restSec = parseInt($this.find('input[name="exercRest"]').val());
            restSec = isNaN(restSec) ? 0 : restSec;
            if (restSec > 0) {
              totSecs = totSecs + restSec;
            }
            if (dur > 0) {
              totSecs = totSecs + dur;
            }
          }
        });
      } else {
        var restSec = parseInt($(this).find('select[name="exeRest"]').val());
        if (restSec > 0) {
          totSecs = totSecs + restSec;
        }
      }
    });
  }
  if (totSecs) {
    var totalMin = Math.floor(totSecs / 60);
    var seconds = totSecs % 60;
  } else {
    var totalMin = 0;
    var seconds = 0;
  }
  $('#programTotalTime').text(totalMin);
  $('#programTotalTimeSec').text(seconds);
}

function programTotalVideoTime() {
  var totalSeconds = 0;
  if ($('#choosedTrainingsAccordion:visible').length > 0) {
    $('#choosedTrainingsAccordion').find('.exeRow').each(function () {
      if ($(this).data('is-rest') == '0') {
        var duration = $(this).find('.exercise').data('duration');
        var a = duration.split(':'); // split it at the colons
        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
        totalSeconds = totalSeconds + seconds;
      } else {
        var duration = parseInt($(this).find('.exercise').data('duration'));
        totalSeconds = totalSeconds + duration;
      }
    });
  }
  var totalMin = Math.floor(totalSeconds / 60);
  var pSeconds = totalSeconds % 60;
  $('#programTotalVideoTime').text(totalMin);
  $('#programTotalVideoTimeSec').text(pSeconds);
}
/* end: Calculate and display plan total time */

/* start: Turn heart on/off */
function toggleHeart(elem, kase) {
  var ital = elem.children();

  if (typeof kase == 'undefined')
    kase = detectHeartCase(elem);

  if (kase == 'add') {
    ital.removeClass('fa-heart-o').addClass('fa-heart')
    return true;
  } else {
    ital.removeClass('fa-heart').addClass('fa-heart-o')
    return false;
  }
}
/* end: Turn heart on/off */

/* start: Reset and Render exercises in 'trainingSegment' accordian as per the API response */
function resetAndPopulateTrainingSegments(response) {
  resetTrainingSegments();
  populateTrainingSegments(response.Exercises, response.isVideo,response);
}
/* end: Reset and Render exercises in 'trainingSegment' accordian as per the API response */

/* start: Get selected value from specified group */
function getSelectedOptionValue(dataName) {
  var value = FX.reinitIfNotVal($('[data-name="' + dataName + '"]').closest('.panel:visible').find('[data-name="' + dataName + '"] a:not(.inactive) input').val());
  return FX.numericStringToInt(value);
}
/* end: Get selected value from specified group */

/* start: Return value from value-unit pair such as 120 from 120 cm */
function withoutUnit(value) {
  if (typeof value != 'undefined' && value != '') {
    var arr = value.split(' ');
    return arr[0];
  }
  return 0;
}
/* end: Return value from value-unit pair such as 120 from 120 cm */

/* start: Render plans list in 'plans' accordian as per the API response */
function populatePlansList(response) {
  var html = '',
    plansPreviewAccordion = $('#plansPreviewAccordion-crm');
  if (Response in response)
    var plans = response.Response.Plans;
  else
    var plans = {};

  if (Object.keys(plans).length)
    html = populatePlanHelper(plans, 'plansPreviewAccordion', 'plans');

  plansPreviewAccordion.html(html)
}
/* end: Render plans list in 'plans' accordian as per the API response */

/* start: Helper function to generate html for plan list */
function populatePlanHelper(plans, parentAccordId, collapseId) {
  var html = '';
  $.each(plans, function (index, value) {
    html += '<div class="panel panel-white"><div class="panel-heading"><h5 class="panel-title"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#' + parentAccordId + '" href="#' + collapseId + '-' + index + '"><i class="icon-arrow"></i>' + index + '</a></h5></div><div id="' + collapseId + '-' + index + '" class="panel-collapse collapse"><div class="panel-body"><div class="row">';

    $.each(value.SubGroups, function (index, value) {
      html += '<div class="col-md-6 m-b-5"><div class="strong">' + value.Key + '</div>';

      $.each(value.Value, function (index, value) {
        html += '<div class="row m-l-0 m-b-5"><div class="col-md-6">' + value.Item1 + '</div><div class="col-md-6">' + value.Item2 + '</div></div>';
      });

      html += '</div>';
    });

    html += '</div></div></div></div>';
  });
  return html;
}
/* end: Helper function to generate html for plan list */

/* start: Render images in 'programWant' accordian as per the API response */
function setPrgramImages(response) {
  var imagesCount = 0;
  if (response.status == 'success') {
    var programs = response.plan,
      colCount = 0,
      html = '';

    $.each(programs, function (index, value) {
      if (colCount == 0)
        html += '<div class="row m-b-20">';

      html += '<div class="col-md-3 program-list-box"><a class="open-step nextStepButton inactive" data-target-step="planMyProgram" data-plan-type="'+value.PlanType+'" data-current-step="programWant" href="#" data-weeks="' + value.DefaultWeeks + '" data-day-option="'+value.dayOption+'" data-days-in-week="'+value.noOfDaysInWeek+'" data-time="' + value.TimePerWeek + '" data-day-pattern="' + value.DayPattern + '" data-clientplan-id="' + value.FixedProgramId + '"><input type="image" value="' + value.FixedProgramId + '" class="image_class program_img" src="' + public_url + 'uploads/thumb_' + value.Image + '" alt="' + value.ProgramName + '"><h3>' + value.ProgramName + '</h3></a><div class="program-action"><a href="#" data-target-step="planMyProgram" data-plan-type="'+value.PlanType+'" data-current-step="programWant" data-weeks="' + value.DefaultWeeks + '"  data-day-option="'+value.dayOption+'" data-days-in-week="'+value.noOfDaysInWeek+'" data-time="' + value.TimePerWeek + '" data-day-pattern="' + value.DayPattern + '" data-clientplan-id="' + value.FixedProgramId + '" class="btn btn-xs btn-primary open-step nextStepButton inactive"><i class="fa fa-share link-btn"></i></a><a href="#" class="btn btn-xs btn-primary tooltips open-step nextStepButton" data-plan-type="'+value.PlanType+'" data-current-step="programWant" data-placement="top" data-original-title="View" data-target-step="trainingSegment" data-clientplan-id="' + value.FixedProgramId + '" data-weeks="' + value.DefaultWeeks + '"  data-day-option="'+value.dayOption+'" data-days-in-week="'+value.noOfDaysInWeek+'" data-time="' + value.TimePerWeek + '" data-day-pattern="' + value.DayPattern + '"><i class="fa fa-pencil link-btn"></i></a></div></div>';

      colCount++;
      if (colCount >= 4 || imagesCount - 1 == index) {
        html += '</div>';
        colCount = 0;
      }
    });
  } else {
    html = '';
    html += '<div class="col-md-12"><div class="alert alert-warning">No any program yet.</div></div>';
  }
  $('#program-want').find('.item_class').html(html);
}
/* end: Render images in 'programWant' accordian as per the API response */

/* Start: get Exercises And Populate Data in modal */
function getExercisesAndPopulateData(exeid, $modal) {
  toggleWaitShield("show", 'exercise-detail');

  if (typeof exeid != 'undefined' && exeid != '') {
    $.ajax({
      url: public_url + 'CustomPlan/SearchExercisesById/' + exeid,
      type: 'GET',
      success: function (response) {

        var data = JSON.parse(response);

        if (data.status == 'success') {
          var imgArea = $modal.find('#exe-img-area');
          imgArea.empty();
          var all_images = data.Image;
          if (all_images.length) {
            $imgHTML = '<div id="myCarousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="false" >\
            <div class="carousel-inner">';

            $.each(all_images, function (i, value) {
              if (i == 0) {
                $imgHTML += '<div class="item active" style="width:100%">\
                <img src="' + public_url + 'uploads/thumb_' + value + '" width="100%">\
                </div>'
              } else {
                $imgHTML += '<div class="item" style="width:100%">\
                <img src="' + public_url + 'uploads/thumb_' + value + '" width="100%">\
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

          } else {
            var exeImgTab = $modal.find("#exerciseImageTab");
            exeImgTab.addClass('hidden');
          }

          var vidArea = $modal.find('#exe-vid-area');
          vidArea.empty();
          var program = data.Program;
          if (program != null && program != '' && program != undefined) {
            $vidHTML = '<div class="item" style="width:100%;">\
            <video src="' + public_url + 'uploads/' + program + '" width="100%" controls>\
            </div>';
            vidArea.append($vidHTML);
          } else {
            var exeVidTab = $modal.find("#exerciseVideoTab");
            exeVidTab.addClass('hidden');
          }
          var vidArea = $modal.find('#exe-tutorial-area');
          vidArea.empty();
          var tutorial = data.Tutorial;
          if (tutorial != null && tutorial != '' && tutorial != undefined) {
            $vidHTML = '<div class="item" style="width:100%">\
            <video src="' + public_url + 'uploads/' + tutorial + '" width="100%" controls>\
            </div>';
            vidArea.append($vidHTML);
          } else {
            var exeVidTab = $modal.find("#exerciseTutorialTab");
            exeVidTab.addClass('hidden');
          }
          if (tutorial == null || tutorial == '' || tutorial == undefined) {
            if (program != null && program != '' && program != undefined) {
              var vidArea = $modal.find('#exe-vid-area');
              vidArea.removeClass('hidden');
            } else {
              if (all_images.length) {
                showExerciseImages();
              }
            }
          }
          if (!$.isEmptyObject(data.exercise)) {
            $.each(data.exercise, function (key, value) {
              $modal.find('#' + key).html(value)
            })
          }

          toggleWaitShield("hide");
        }
      },
    });
  }
}
/* End: get Exercises And Populate Data in modal */
function showExerciseVideo() {
  $modal = $("#lungemodal");
  var imgArea = $modal.find('#exe-img-area');
  imgArea.addClass('hidden');
  var tutorialArea = $modal.find('#exe-tutorial-area');
  tutorialArea.addClass('hidden');
  var vidArea = $modal.find('#exe-vid-area');
  vidArea.removeClass('hidden');
}

function showTutorialVideo() {
  $modal = $("#lungemodal");
  var imgArea = $modal.find('#exe-img-area');
  imgArea.addClass('hidden');
  var vidArea = $modal.find('#exe-vid-area');
  vidArea.addClass('hidden');
  var tutorialArea = $modal.find('#exe-tutorial-area');
  tutorialArea.removeClass('hidden');

}

function showExerciseImages() {
  $modal = $("#lungemodal");
  var imgArea = $modal.find('#exe-img-area');
  imgArea.removeClass('hidden');
  var vidArea = $modal.find('#exe-vid-area');
  vidArea.addClass('hidden');
  var tutorialArea = $modal.find('#exe-tutorial-area');
  tutorialArea.addClass('hidden');
}

$(document).ready(function () {
  $("#lungemodal").on("hidden.bs.modal", function () {
    showExerciseVideo();
    $modal = $("#lungemodal");
    var exeVidTab = $modal.find("#exerciseVideoTab");
    exeVidTab.removeClass('hidden');
    var exeImgTab = $modal.find("#exerciseImageTab");
    exeImgTab.removeClass('hidden');
  });
});

/* Start: Delete confirmation function */
function confirmEntityDelete(entity, url, param, callback) {
  swal({
      title: "Are you sure to delete this " + entity + "?",
      text: (typeof warningText != 'undefined' && warningText) ? warningText : '',
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d43f3a",
      confirmButtonText: "Yes, delete it!",
      allowOutsideClick: true,
      customClass: 'delete-alert'
    },
    function (isConfirm) {
      if (isConfirm) {
        API.customPlanAjax(url, param, function (response) {
          callback(response);
        });
      }
    });
}
/* End: Delete conformation function */

/* Start: Plan Preview */
function rendorPlanPreview(response) {
  var html = "",
    previewId = $('#plan-preview-area');

  previewId.empty();
  if (response.status == 'success') {
    if (FX.PlanType == 5)
      FX.setClientPlanId(response.clientPlanId);
    else if (FX.PlanType == 8)
      FX.setClientPlanId(response.clientPlanId);

    $.each(response.data, function (day, value) {
      html += '<div class="col-md-5 m-t-20">\
      <h4 class="plan-day-style">' + day + '</h4>'
      $.each(value, function (workout, workoutData) {
        html += '<div class="row"><div class="col-md-12">' + workout + '</div></div>';
        $.each(workoutData, function (i, exercise) {
          html += '<div class="row"><div class="col-sm-4 col-xs-6"><small>' + exercise.Name + '</small></div>';
          if (exercise.sets && exercise.repes)
            html += '<div class="col-sm-4 col-xs-6"><small>' + exercise.sets + ' &times; ' + exercise.repes + ' sets</small></div>';
          html += '</div>';
        })
      })
      html += '</div>';
    })
  } else {
    html = '';
    html += '<div class="col-md-12"><div class="alert alert-warning">No any program match.</div></div>';
  }
  previewId.append(html);
  toggleWaitShield("hide");
}
/* End: Plan Preview */

/* start: Switching plus to tick and adding exercise */
$('body').on("click", '.toggle-video', function (e) {
  e.stopPropagation();
  toggleWaitShield("show");
  var $this = $(this),
    formData = {};
  var targetModal = $(this).closest('.modal');
  if (targetModal.length > 0 && targetModal.attr('id') == 'addexercise') {
    var exerciseVideoList = $('#exerciseVideoListing');
    var exerciseDetailsModalFooter = $("#videoModal").find('.modal-footer');
    if (typeof (exerciseVideoList.data('program-id')) == 'undefined') {
      exerciseVideoList.data('program-id', (FX.ClientPlanId));
    }
    FX.setExersiseId($this.parent().data('exercise-id'));
    formData.WorkOutName = exerciseVideoList.data('work-out');
    formData.ClientPlanId = FX.ClientPlanId;
    formData.ExerciseId = FX.ExersiseId;
    formData.PlanType = exerciseVideoList.data('plan-type');
    FX.addExToProgram(formData, function (response) {
      if (response.status == 'success') {
        FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));
        var modalId = $this.closest('.modal').attr('id');
        if (modalId == 'addexercise') {
          var sourceAddLnk = $this;
        } else if (modalId == 'videoModal') {
          $this.hide();

          var sourceAddLnk = $('#addexercise').find('[data-exercise-type-id="' + exerciseDetailsModalFooter.data('exercise-type-id') + '"] .toggle-video');
        }
        sourceAddLnk.removeClass('toggle-video')
        sourceAddLnk.children().removeClass('fa-plus').addClass('fa-check');
        populateTrainingSegments(response.Exercises, response.isVideo);
        var modal = $('#' + modalId);
        setActiveTab(modal, "2");
        if (FX.PlanType != 'undefined' && FX.PlanType == 5) {
          var generatorModal = $('#generatorModal');
          generatorModal.modal({
            backdrop: 'static',
            keyboard: false
          })
          generatorModal.find('input[name="workout_name"]').val(formData.WorkOutName);
        }
      }

      $('#choosedTrainingsAccordion').find('a[href="#accord_' + formData.WorkOut + '"]').trigger('click');

      toggleWaitShield("hide");
    });
  } else {
    var exerciseVideoList = $('.exerciseVideoList');
    var exerciseDetailsModalFooter = $("#videoModal").find('.modal-footer');

    if (typeof (exerciseVideoList.data('program-id')) == 'undefined') {
      exerciseVideoList.data('program-id', (FX.ClientPlanId));
    }
    var workoutName = '';
    var trainingSegmentPanel = $('#choosedTrainingsAccordion').find('.panel-collapse');
    trainingSegmentPanel.each(function () {
      if ($(this).hasClass('in') && $(this).attr('aria-expanded') == 'true') {
        workoutName = $(this).find('.choosedExercRow').data('work-out');
      }
    });
    if (workoutName != '' && workoutName != undefined) {
      if ($this.parent().data('exercise-id') != undefined && $this.parent().data('exercise-id') != "") {
        FX.setExersiseId($this.parent().data('exercise-id'));
      }

      formData.WorkOutName = workoutName;
      formData.ClientPlanId = FX.ClientPlanId;
      formData.ExerciseId = FX.ExersiseId;
      formData.PlanType = exerciseVideoList.data('plan-type');
      FX.addExToProgram(formData, function (response) {
        if (response.status == 'success') {
          FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));
          var modalId = $this.closest('.modal').attr('id');
          if (modalId == 'videoModal') {
            $this.hide();

            var sourceAddLnk = $('#addexercise').find('[data-exercise-type-id="' + exerciseDetailsModalFooter.data('exercise-type-id') + '"] .toggle-video');
            var modal = $('#' + modalId);
            modal.modal('hide');
          } else {
            var sourceAddLnk = $this;
          }
          sourceAddLnk.removeClass('toggle-video')
          sourceAddLnk.children().removeClass('fa-plus').addClass('fa-check');
          populateTrainingSegments(response.Exercises, response.isVideo);
          if (FX.PlanType != 'undefined' && FX.PlanType == 5) {
            var generatorModal = $('#generatorModal');
            generatorModal.modal({
              backdrop: 'static',
              keyboard: false
            })
            generatorModal.find('input[name="workout_name"]').val(formData.WorkOutName);
          }
        }

        $('#choosedTrainingsAccordion').find('a[href="#accord_' + formData.WorkOut + '"]').trigger('click');

        toggleWaitShield("hide");
      });
    } else {
      toggleWaitShield("hide");
      swal('Please select any training segment');
    }
  }
});
/* end: Switching plus to tick and adding exercise

/**
 * Get All Activity Video
 */
// $('#addexercise a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
//   var target = $(e.target).attr("href") // activated tab
//   if(target == '#addexerciseVideo'){
//     getAllActivityVideo();
//   }
// });

function getAllActivityVideo(filter = '') {
  toggleWaitShield("show");
  var formData = {};
  if (filter != '') {
    $('#filterVideo').val(filter).selectpicker('refresh');
    formData['filter'] = filter;
  }
  formData['keyword'] = $('.add-exercises').find('#keySearchExercise').val();
  $.get(public_url + 'CustomPlan/activityViedos', formData, function (response) {
    var videoHtml = $('.exerciseVideoList');
    videoHtml.empty();
    var videoListingHtml = '';
    $.each(response.videos, function (key, value) {
      var imagePath = '';
      if (value.thumbnail != '' && value.thumbnail != null) {
        imagePath = public_url + 'uploads/' + value.thumbnail;
      } else {
        imagePath = public_url + 'result/images/epic-icon-orenge.png';
      }
      videoListingHtml += '<div class="col-md-4" draggable="true" ondragstart="drag(event)" data-exercise-name="' + value.title + '" data-exeid="' + value.id + '">\
      <a data-toggle="modal" class="lungemodalCls" data-type="video" data-exercise-name="' + value.title + '" data-exeid="' + value.id + '" data-video-url="' + public_url + 'uploads/' + value.video + '">\
      <div class="panel panel-white m-b-0">\
      <div class="row">\
      <div class="col-md-12">\
      <img src="' + imagePath + '" class="mw-80p">\
      </div>\
      <div class="col-md-12">\
      <h5> ' + value.title + ' </h5>\
      </div>\
      <div class="col-md-12" data-exercise-type-id="' + value.workout_id + '" data-exercise-id="' + value.id + '">\
      <button class="btn btn-xs btn-primary toggle-video">\
      <i class="fa fa-plus"></i>\
      </button><button class="btn btn-xs btn-primary"><i class="fa fa-arrows"></i> </button>\
      </div>\
      </div>\
      </div>\
      </a>\
      </div>';
    });
    videoHtml.append(videoListingHtml);
    $('.exerciseVideoList').data('plan-type', '2');
    toggleWaitShield("hide");
  }, 'json');
}

$('body').on('change', '#filterVideo', function () {
  var filter = $(this).val();
  getAllActivityVideoMobile(filter);
});

$('body').on('change', '#filterVideoDesktop', function () {
  var filter = $(this).val();
  getAllActivityVideo(filter);
});

function setActiveTab(modal, exerciseType = "0") {
  if (exerciseType == '1') {
    modal.find('.nav-tabs a[href="#addexerciseImage"]').parent('li').removeClass('disabled');
    modal.find('.nav-tabs a[href="#addexerciseImage"]').attr('data-toggle', 'tab');
    modal.find('.nav-tabs a[href="#addexerciseImage"]').tab('show');
    modal.find('.nav-tabs a[href="#addexerciseVideo"]').removeAttr('data-toggle');
    modal.find('.nav-tabs a[href="#addexerciseVideo"]').parent('li').addClass('disabled');
  } else if (exerciseType == '2') {
    modal.find('.nav-tabs a[href="#addexerciseVideo"]').parent('li').removeClass('disabled');
    modal.find('.nav-tabs a[href="#addexerciseVideo"]').attr('data-toggle', 'tab');
    modal.find('.nav-tabs a[href="#addexerciseVideo"]').tab('show');
    modal.find('.nav-tabs a[href="#addexerciseImage"]').removeAttr('data-toggle');
    modal.find('.nav-tabs a[href="#addexerciseImage"]').parent('li').addClass('disabled');
  } else {
    modal.find('.nav-tabs a[href="#addexerciseImage"]').parent('li').removeClass('disabled');
    modal.find('.nav-tabs a[href="#addexerciseImage"]').attr('data-toggle', 'tab');
    modal.find('.nav-tabs a[href="#addexerciseImage"]').tab('show');
    modal.find('.nav-tabs a[href="#addexerciseVideo"]').attr('data-toggle', 'tab');
    modal.find('.nav-tabs a[href="#addexerciseVideo"]').parent('li').removeClass('disabled');
  }
}
$(function () {
  $(".sortExe").sortable({
    placeholder: "ui-sortable-placeholder"
  });
});

function loadExerciseList() {
  var formData = {};
  formData['clientPlanId'] = FX.ClientPlanId;
  $.get(public_url + 'CustomPlan/exercise-type', formData, function (response) {
    var exerciseType = response;
    if (exerciseType == 1) {
      getExercises();
      $('.favShow').show();
      showFilter(exerciseType);
    } else if (exerciseType == 2) {
      getAllActivityVideo();
      $('.favShow').hide();
      showFilter(exerciseType);
    } else {
      getExercises();
      $('.favShow').show();
      showFilter('1');
    }
    var $radios = $('input[name="exerciseType"]');
    if (exerciseType == 1 || exerciseType == 2) {
      $radios.filter('[value=' + exerciseType + ']').prop('checked', true);
    } else {
      $radios.filter('[value="1"]').prop('checked', true);
    }
  }, 'json');
}

function showFilter(exerciseType) {
  $('#mySidepanel .filter-section').find('.form-group').each(function () {
    if (exerciseType == '1') {
      if ($(this).hasClass('video-filter')) {
        $(this).hide();
      } else {
        $(this).show();
      }
    } else {
      if ($(this).hasClass('video-filter')) {
        $(this).show();
      } else {
        $(this).hide();
      }
    }
  });
}

$('body').on('click', '.exeTypeRow input[name="exerciseType"]', function () {
  $this = $(this);
  var formData = {};
  formData['clientPlanId'] = FX.ClientPlanId;
  $.get(public_url + 'CustomPlan/exercise-type', formData, function (response) {
    var exerciseType = response;
    if (exerciseType == 0) {
      if ($('input[name="exerciseType"]:checked').val() == 1) {
        $('.favShow').show();
        getExercises();
        showFilter(exerciseType);
      } else {
        $('.favShow').hide();
        getAllActivityVideo();
        showFilter(exerciseType);
      }
    } else if ($('input[name="exerciseType"]:checked').val() == 1 && exerciseType == 1) {

      getExercises();
      showFilter(exerciseType);
    } else if ($('input[name="exerciseType"]:checked').val() == 2 && exerciseType == 2) {

      getAllActivityVideo();
      showFilter(exerciseType);
    } else {
      swal('Can not Select this');
      var $radios = $('input[name="exerciseType"]');
      $radios.filter('[value=' + exerciseType + ']').prop('checked', true);
    }
  }, 'json');
});

/**
 * Mobile Js
 */
/* start: Loading exercises and populating them */
function getExercisesMobile(contnue) {
  toggleWaitShield("show");
  var exerciseList = $('#exerciseList'),
    addExerciseModal = $('#addexercise'),
    loading = exerciseList.next();
  loading.show();
  var pageNumb,
    iss = FX.UI.searchScroll;

  if (typeof contnue != 'undefined' && contnue) {
    pageNumb = ++FX.UI.currentPage - 1;
    //iss.enabled = false;
  } else {
    pageNumb = FX.UI.currentPage = 1;
    //iss.enabled = true;
    exerciseList.html('')
  }

  var options = {
    workoutId: workout_id,
    category: '',
    equipment: '',
    ability: '',
    bodypart: '',
    movement_type: '',
    movement_pattern: '',
    perPage: 4,
    pageNumber: pageNumb
  };

  var favKase = detectHeartCase(addExerciseModal.find('#favSearch'));
  if (favKase == 'add')
    options.myFavourites = false;
  else if (favKase == 'remove')
    options.myFavourites = true;

  options.keyWords = addExerciseModal.find('#keySearch').val();
  options.bodypart = addExerciseModal.find('#muscle_group').val();
  options.ability = addExerciseModal.find('#ability').val();
  options.equipment = addExerciseModal.find('#equipment').val();
  options.category = addExerciseModal.find('#category').val();
  options.movement_type = addExerciseModal.find('#movement_type').val();
  options.movement_pattern = addExerciseModal.find('#movement_pattern').val();

  API.customPlanAjax('SearchExercises', options, function (response) {
    var exercises = response.Exercises;
    exerciseList.empty();
    if ((exercises == undefined)) {
      //iss.enabled = false;
      var text = FX.prepareAlert('warning', 'No ' + (pageNumb == 1 ? '' : 'more') + ' exercise found.');
      exerciseList.append('<div class="col-md-12">' + text + '</div>');
      loading.hide();
    } else if (exercises.length) {
      var html = '';
      exerciseList.empty();
      $.each(exercises, function (index, value) {
        var desc = value.ExerciseDesc;

        if (desc.length > 19)
          var descUi = desc.substring(0, 19) + '...';
        else
          var descUi = desc;

        html += '<div class="col-md-4">\
        <a data-toggle="modal" class="lungemodalCls" data-exercise-name="' + value.name + '" data-exeid=' + value.id + '>\
        <div class="panel panel-white m-b-0">\
        <div class="panel-body">\
        <div class="row">\
        <div class="col-md-5">\
        <img src="' + public_url + 'uploads/thumb_' + value.img + '" class="mw-60p">\
        </div>\
        <div class="col-md-4 nip-x-0">\
        <h5> ' + value.name + ' </h5>\
        <small>' +
          descUi +
          '<br/>\
        <b>' +
          FX.DifficultyLevels[value.DifficultyLevel] +
          '</b> \
        </small>\
        </div>\
        <div class="col-md-3" data-exercise-type-id="' + value.ExerciseTypeID + '" data-exercise-id="' + value.id + '">\
        <button class="btn btn-xs btn-primary m-b-2 toggle-fav" data-is-fav="' + value.IsFav + '">' +
          ((value.IsFav == true) ? '<i class="fa fa-heart"></i>' : '<i class="fa fa-heart-o"></i>') +
          '</button>\
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
    } else {
      //iss.enabled = false;
      var text = FX.prepareAlert('warning', 'No ' + (pageNumb == 1 ? '' : 'more') + ' exercise found.');
      exerciseList.append('<div class="col-md-12">' + text + '</div>');
    }
    loading.hide();
    toggleWaitShield("hide");
  });
  FX.UI.currentPage++;
}
/* end: Loading exercises and populating them */

/** Mobile Activity video **/
function getAllActivityVideoMobile(filter = '') {
  toggleWaitShield("show");
  var formData = {};
  if (filter != '') {
    $('#filterVideo').val(filter).selectpicker('refresh');
    formData['filter'] = filter;
  }
  $.get(public_url + 'CustomPlan/activityViedos', formData, function (response) {
    var videoHtml = $('.exerciseVideoListing');
    videoHtml.empty();
    var videoListingHtml = '';
    $.each(response.videos, function (key, value) {
      videoListingHtml += '<div class="col-md-4">\
      <a data-toggle="modal" class="lungemodalCls" data-type="video" data-exercise-name="' + value.title + '" data-exeid="' + value.id + '" data-video-url="' + public_url + 'uploads/' + value.video + '">\
      <div class="panel panel-white m-b-0">\
      <div class="row">\
      <div class="col-md-5">\
      <img src="' + public_url + 'assets/plugins/fitness-planner/images/video-icon.png" class="mw-80p">\
      </div>\
      <div class="col-md-4 nip-x-0">\
      <h5> ' + value.title + ' </h5>\
      </div>\
      <div class="col-md-3" data-exercise-type-id="' + value.workout_id + '" data-exercise-id="' + value.id + '">\
      <button class="btn btn-xs btn-primary toggle-video">\
      <i class="fa fa-plus"></i>\
      </button>\
      </div>\
      </div>\
      </div>\
      </a>\
      </div>';
    });
    videoHtml.append(videoListingHtml);
    toggleWaitShield("hide");
  }, 'json');
}
/** Mobile Activity video **/


$(function () {
  $(".sortable").sortable({
    placeholder: "ui-sortable-placeholder"
  });
});

/**
 * Get Duration
 */
$('body').on('input', 'input[name="exercTempo"]', function () {
  $form = $(this).closest('.treningSeg-form');
  getDuration($form);
});

$('body').on('input', 'input[name="exercDur"]', function () {
  $form = $(this).closest('.treningSeg-form');
  getRepitition($form);
});

$('body').on('input', 'input[name="exercReps"]', function () {
  $form = $(this).closest('.treningSeg-form');
  getDuration($form);
});

function getDuration($form) {
  var exercTempo = $form.find('input[name="exercTempo"]').val();
  var exercReps = $form.find('input[name="exercReps"]').val();
  if (exercTempo != undefined && exercReps != '' && exercReps != undefined && exercReps != '') {
    exercTempo = exercTempo.split("");
    var sum = 0;
    $.each(exercTempo, function (key, value) {
      sum = sum + parseInt(value);
    });
    var duration = exercReps * sum;
    $form.find('input[name="exercDur"]').val(duration);
  }
}

function getRepitition($form) {
  var exercTempo = $form.find('input[name="exercTempo"]').val();
  var exercDur = $form.find('input[name="exercDur"]').val();
  if (exercTempo != undefined && exercTempo != '' && exercDur != undefined && exercDur != '') {
    exercTempo = exercTempo.split("");
    var sum = 0;
    $.each(exercTempo, function (key, value) {
      sum = sum + parseInt(value);
    });
    var exercReps = Math.round(exercDur / sum);
    $form.find('input[name="exercReps"]').val(exercReps);
  }
}

// Add Rest to Exercise
$('body').on('click', '.showRes', function () {
  toggleWaitShield("show");
  var exerciseList = $('.exerciseVideoList');
  var panel = $(this).closest('.panel');
  var order = 1;
  var orderData = [];
  var exeOrder = 0;
  panel.find('.exeRow').each(function () {
    if ($(this).hasClass('dropDiv')) {
      exeOrder = order;
    } else {
      orderData.push({
        'planWorkoutExerciseId': $(this).data('plan-workout-exercise-id'),
        'order': order
      });
    }
    order = order + 1;
  });
  formData = {};
  formData.WorkOutName = panel.find('.choosedExercRow').data('work-out');
  formData.ClientPlanId = FX.ClientPlanId;
  formData.ExerciseId = 0;
  formData.PlanType = exerciseList.data('plan-type');
  formData.isRest = 1;
  formData.restSeconds = 10;
  formData.exeOrder = exeOrder;
  formData.orderData = orderData;
  formData.ClientPlanType = FX.PlanType;


  FX.addExToProgram(formData, function (response) {
    if (response.status == 'success') {
      FX.clearNotific($('.panel[data-step="trainingSegment"] > .panel-body'));
      populateTrainingSegments(response.Exercises, response.isVideo);
      $('.selectpickerRest').selectpicker();
      $('#choosedTrainingsAccordion').find('a[href="#accord_' + formData.WorkOut + '"]').trigger('click');
      toggleWaitShield("hide");
    }
  });
});

$('body').on('change', 'select[name="exeRest"]', function () {
  toggleWaitShield("show");
  formData = {};
  formData.planWorkoutExeId = $(this).closest('.exeRow').data('plan-workout-exercise-id');
  formData.restSeconds = $(this).val();
  $.get(public_url + 'CustomPlan/updateRest', formData, function (response) {
    programTotalTime();
    toggleWaitShield("hide");
  });
});

$('body').on('click', '.delAllExercise', function () {
  toggleWaitShield("show");
  var panel = $(this).closest('.panel');
  var formData = {};
  var exeIdToDelete = [];
  panel.find('.custom-checkbox').each(function () {
    if ($(this).hasClass('customcheck')) {
      var planWorkoutExeId = $(this).closest('.exeRow').data('plan-workout-exercise-id');
      $(this).closest('.exeRow').remove();
      exeIdToDelete.push(planWorkoutExeId);
    }
  });

  formData['exeIdToDelete'] = exeIdToDelete;
  formData['PlanType'] = exerciseList.data('plan-type');
  formData['ClientPlanType'] = FX.PlanType;
  $.get(public_url + 'CustomPlan/DeleteMultipleExercise', formData, function (response) {
    response = JSON.parse(response);
    if (response.status == 'success') {
      swal({
          type: 'success',
          title: 'Success!',
          showCancelButton: false,
          allowOutsideClick: false,
          text: response.message,
          showConfirmButton: true,
        },
        function (isConfirm) {
          if (isConfirm) {
            programTotalTime();
          }
        });
    } else {
      swal({
        type: 'error',
        title: 'Error!',
        showCancelButton: false,
        allowOutsideClick: false,
        text: response.message,
        showConfirmButton: true,
      });
    }
    toggleWaitShield("hide");
  });
});

$('body').on('click', '.custom-checkbox', function () {
  $(this).toggleClass('customcheck');
});

/**
 * New Design Js
 */
var trainingStepModal = $('#modalpopup1');
var programChooseModal = $('#modalpopup2');
var designProgramModal = $('#modalpopup3');
var trainingSegmentModal = $('#modalpopup4');
var planMyProgramModal = $('#modalpopup5');
var previewModal = $('#modalpopup5');
var planPreviewModal = $('#modalpopup6');
var currentAbilityModal = $('#current-ability');
var equipmentModal = $('#equipmentModal');
var programWantModal = $('#program-want');
var libraryOptionModal = $('#libraryOptionModal');
var multiPhaseProgramModal = $('#createProgramModal');
var planPreviewPhase = $('#planPreview');
trainingStepModal.find('.trainingStep').click(function () {
  trainingStepModal.find('.trainingStep').removeClass('active');
  $(this).addClass('active');
});
programChooseModal.find('.chooseProgram').click(function () {
  programChooseModal.find('.chooseProgram').removeClass('active');
  $(this).addClass('active');
});

libraryOptionModal.find('.chooseProgram').click(function () {
  libraryOptionModal.find('.chooseProgram').removeClass('active');
  $(this).addClass('active');
});

currentAbilityModal.find('.currentAbilityOption').click(function () {
  currentAbilityModal.find('.currentAbilityOption').removeClass('active');
  $(this).addClass('active');
});
equipmentModal.find('.equipmentOption').click(function () {
  equipmentModal.find('.equipmentOption').removeClass('active');
  $(this).addClass('active');
});
$('body').on('click', '.nextStepButton', function () {
  let view = $('#programView').val();
  if(view != undefined && view != '' && view == 'modal'){
    var currentStep = $(this).data('current-step');
    if (currentStep == 'stepChooseTraining') {
      var activeElement = $(this).closest('.modal').find('.trainingStep.active');
      if (activeElement.length > 0) {
        var targetStep = activeElement.find('a').data('target-step');
        if (targetStep == 'programChoose') {
          programChooseModal.find('.backStepButton').data('prev-step', 'modalpopup1');
          programChooseModal.modal('show');
          trainingStepModal.modal('hide');
        }
      } else {
        swalAlert('Please Choose any Training!');
      }
    } else if (currentStep == 'stepChooseProgram') {
      var activeElement = $(this).closest('.modal').find('.chooseProgram.active');
      if (activeElement.length > 0) {
        var targetStep = activeElement.find('a').data('target-step');
        FX.setPlanType(FX.numericStringToInt(activeElement.find('a input').val()))
        activityChoosed = 0;
        if (targetStep == 'designProgram') {
          designProgramModal.find('.backStepButton').data('prev-step', 'modalpopup2');
          FX.loadProgramsList(FX.PlanType);
          if(activeElement.find('a input').data('multiphase') == '1'){
            isMultiPhase = true;
            designProgramModal.modal('show');
            multiPhaseProgramModal.modal('hide');
            trainingStepModalPhase.modal('hide');
          }else{
            isMultiPhase = false;
            designProgramModal.modal('show');
            programChooseModal.modal('hide');
          }
        } else if(targetStep == 'libraryOption'){
          libraryOptionModal.find('.backStepButton').data('prev-step', 'modalpopup2');
          libraryOptionModal.modal('show');
          programChooseModal.modal('hide');
        } else if(targetStep == 'currentAbility'){
          currentAbilityModal.find('.backStepButton').data('prev-step', 'createProgramModal');
          currentAbilityModal.modal('show');
          trainingStepModalPhase.modal('hide');
          multiPhaseProgramModal.modal('hide');
        }
      } else {
        swalAlert('Please Choose any Program!');
      }
    } else if (currentStep == 'libraryOption') {
      var activeElement = $(this).closest('.modal').find('.chooseProgram.active');
      if (activeElement.length > 0) {
        FX.setPlanType(FX.numericStringToInt(activeElement.find('a input').val()));
        if(FX.PlanType == 9){
          isMultiPhase = true;
        }else{
          isMultiPhase = false;
        }
        currentAbilityModal.find('.backStepButton').data('prev-step', 'libraryOptionModal');
        currentAbilityModal.modal('show');
        libraryOptionModal.modal('hide');
      } else {
        swalAlert('Please Choose any Library Option!');
      }
    } else if (currentStep == 'stepChooseCurrentAbility') {
      var activeElement = $(this).closest('.modal').find('.currentAbilityOption.active');
      if (activeElement.length > 0) {
        planFilter['habit'] = FX.numericStringToInt(activeElement.find('a input').val());
        equipmentModal.find('.backStepButton').data('prev-step', 'current-ability');
        equipmentModal.modal('show');
        currentAbilityModal.modal('hide');
      } else {
        swalAlert('Please Choose any ability!');
      }
    } else if (currentStep == 'stepEquipmentModal') {
      var activeElement = $(this).closest('.modal').find('.equipmentOption.active');
      if (activeElement.length > 0) {
        var targetStep = activeElement.find('a').data('target-step');
        planFilter['equipment'] = FX.numericStringToInt(activeElement.find('a input').val());
        var data = {};
        data['habit'] = planFilter['habit'];
        data['equipment'] = planFilter['equipment'],
          FX.getFilterPlan(data, setPrgramImages);
        programWantModal.find('.backStepButton').data('prev-step', 'equipmentModal');
        equipmentModal.modal('hide');
        programWantModal.modal('show');
      } else {
        swalAlert('Please Choose any equipment!');
      }
    } else if (currentStep == 'programWant') {
      var targetStep = $(this).data('target-step');
      var planType = $(this).data('plan-type');
      if (targetStep == 'planMyProgram') {
        if(planType == 9){
          var form_datas = {};
          var libraryProgramId = $(this).data('clientplan-id');
          form_datas.libraryProgramId = libraryProgramId;
          form_datas.clientId = $('input[name="clientId"]').val();
          toggleWaitShield('show');
          $.get(public_url+'CustomPlan/client-multiphase-program',form_datas,function(response){
            toggleWaitShield('hide');
            let clientPlan = response.clientPlan;
            isChooseDays = clientPlan.dayOption == 1 ? true:false;
            if(response.status == 'ok'){
              FX.setClientPlanId(response.clientProgramId);
              $('input[name="clientPlanId"]').val(response.clientProgramId);
              let previewHtml = '<div class="row">';
              $.get(`${public_url}activity-builder/library-program/multi-phase/get-phase-data`,{id:response.clientProgramId},function(response){
                var phaseData = response.data;
                $.each(phaseData,function(key,phase){
                  previewHtml += `<div class="column">					
                  <b class="plan-day-style"> Phase ${key}</b>`;
                  $.each(phase,function(keyWeek,week){
                    previewHtml += `<div class="col-md-12">
                                    <b> Week ${keyWeek}</b>
                                  </div>`;
                    $.each(week,function(keyDay,day){
                      previewHtml += `<div class="col-md-12">
                                <span> <b> Day ${keyDay} </b>${day.day != ''?'- '+day.day:''}</span>
                              </div>`;
                      $.each(day, function(keySession,session){
                        if(session.is_session_program == 1){
                          previewHtml += `<div class="col-md-12">
                                    <span> <b> Session Program 1 </b>-${session.title}</span>
                                  </div>`;
                        }
                      });
                    });
                  });
                  previewHtml += '</div>';
                });
                previewHtml += '</div>';
                $('#plan-preview').empty().append(previewHtml);
                planPreviewPhase.find('.backStepButton').data('prev-step', 'program-want');
                planPreviewPhase.modal('show');
                programWantModal.modal('hide');
              });
            }
          });
        }else{
          setPlanMyProgram($(this));
          var form_data = {};
          var id = $(this).data('clientplan-id');
          form_data.progrmId = id;
          form_data.clientId = $('input[name="clientId"]').val();
          $.get(`${public_url}CustomPlan/replicate-program`,form_data,function(response){
              FX.setClientPlanId(response.clientPlanId);
              // FX.loadProgram(response.clientPlanId, setDaysOfWeek);
          },'json');
          planMyProgramModal.find('.backStepButton').data('prev-step', 'program-want');
          planMyProgramModal.modal('show');
          programWantModal.modal('hide');
        }
      } else if (targetStep == 'trainingSegment') {
        if(planType == 6){
          setPlanMyProgram($(this));
          var id = $(this).data('clientplan-id');
          var form_data = {};
          if(isMultiPhase){
            form_data.progrmId = id;
            form_data.clientPlanId = $('input[name="clientPlanId"]').val();
            form_data.programType = 1;
            FX.loadProgram(id, resetAndPopulateTrainingSegments);
            loadExerciseList();
            $.get(public_url + 'activity-builder/library-program/multi-phase/getProgramDetails', form_data, function (response) {
              if (response.status == 'error') {
                swal({
                  type: 'error',
                  title: 'Something went wrong!',
                  showCancelButton: false,
                  allowOutsideClick: false,
                  showConfirmButton: true,
                });
              } else {
                $('input[name="clientPlanProgramId"]').val(response.clientProgramId);
              }
            }, 'json')
          }else{
            form_data.progrmId = id;
            form_data.clientId = $('input[name="clientId"]').val();
            $.get(`${public_url}CustomPlan/replicate-program`,form_data,function(response){
                FX.setClientPlanId(response.clientPlanId);
                FX.loadProgram(response.clientPlanId, resetAndPopulateTrainingSegments);
                loadExerciseList();
            },'json');
          }
          trainingSegmentModal.find('.backStepButton').data('prev-step', 'program-want');
          programWantModal.modal('hide');
          trainingSegmentModal.modal('show');
        }else if(planType == 9){
          var form_datas = {};
          var libraryProgramId = $(this).data('clientplan-id');
          form_datas.libraryProgramId = libraryProgramId;
          form_datas.clientId = $('input[name="clientId"]').val();
          toggleWaitShield('show');
          $.get(public_url+'CustomPlan/client-multiphase-program',form_datas,function(response){
            toggleWaitShield('hide');
            let clientPlan = response.clientPlan;
            isChooseDays = clientPlan.dayOption == 1 ? true:false;
            if(response.status == 'ok'){
              FX.setClientPlanId(response.clientProgramId);
              $('input[name="clientPlanId"]').val(response.clientProgramId);
              var html = '';
              $.get(public_url+'activity-builder/library-program/multi-phase/get-phase-data',{id:response.clientProgramId},function(response){
                var data = response.data;
                $.each(data,function(key,phase){
                  html += '<div class="column">\
                  <div class="card">\
                  <h3 class="phase-row"><span class="phaseActions"><a class="nav-link copyPhase" href="javascript:void(0)"><i class="fa fa-files-o" aria-hidden="true"></i></a> &nbsp;</span> Phase <span class="phaseNo">' + key + '</span> <i class="fa fa-times removePhase pull-right"></i>\</h3>\
                  <div class="weekSection">';
                  $.each(phase,function(keyWeek,week){
                    html += '<div class="row weekRow">\
                    <div class="col-md-6">\
                      <p>Week <span class="weekNo">'+keyWeek+'</span></p>\
                    </div>\
                    <div class="col-md-6">\
                      '+(keyWeek == 1 ? '<a class="addWeek nav-link" href="javascript:void(0)">+ Add Week</a>':'<a class="removeWeek nav-link" href="javascript:void(0)">- Remove</a>')+'\
                    </div>\
                    <div class="col-md-12">\
                      <div class="daysSection">';
                      $.each(week,function(keyDay,day){
                        html += '<div class="row dayRow">\
                        <div class="col-md-6">\
                        <p>Day <span class="dayNo">'+keyDay+'</span></p>\
                        </div>\
                        <div class="col-md-6">\
                        '+(keyDay == 1 ? '<a class="addDay nav-link" href="javascript:void(0)">+ Add Day</a>':'<a class="removeDay nav-link" href="javascript:void(0)">- Remove</a>')+'\
                        </div>\
                        '+(clientPlan.dayOption == 1 ? getDaysHtml(key,keyWeek,keyDay,day.day):'')+'\
                        <div class="col-md-12">\
                        <div class="sessionSection">';
                        $.each(day, function(keySession,session){
                          if(session.is_session_program == 1){
                            html += '<div class="row sessionRow">\
                              <div class="col-md-6">\
                              <p>Session <span class="sessionNo">'+keySession+'</span></p>\
                              </div>\
                              <div class="col-md-6">\
                              '+(keySession == 1 ? '<a class="addSession nav-link" href="javascript:void(0)">+ Add Session</a>':'<a class="removeSession nav-link" href="javascript:void(0)">- Remove</a>')+'\
                              </div>\
                              <div class="col-md-12">\
                              <p draggable="true" ondragstart="dragnew(event, this)" class="session-row" ondrop="dropnew(event, this)" ondragover="allowDropnew(event, this)" data-title="'+ session.title +'" data-id="' + session.programId + '" >'+(session.title == ''?'<a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a>':'<span class="addedSessionProgram" data-client-program-id="' + session.programId + '">' + session.title + '</span> &nbsp;&nbsp; <a class="editProgram nav-link" href="javascript:void(0)"> <i class="fa fa-pencil" aria-hidden="true"></i> </a> <a class="removeProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-trash-o" aria-hidden="true"></i></a> <a class="copyProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-clone" aria-hidden="true"></i> </a> &nbsp;<i class="fa fa-bars nav-link" aria-hidden="true"></i>')+'</p>\
                              </div>\
                            </div>';
                          }
                        });
                        html += '</div>\
                          </div>\
                        </div>';
                      });
                      html += '</div>\
                          </div>\
                        </div>';
                  });
                  html += '</div>\
                  </div>\
                  </div>';
                });
                multiPhaseProgramModal.find('.backStepButton').data('prev-step', 'program-want');
                multiPhaseProgramModal.find('.phaseDiv').empty().append(html);
                multiPhaseProgramModal.modal('show');
                programWantModal.modal('hide');
              });
            }
          });
        }
      }
    } else if (currentStep == 'designProgram') {
      var targetStep = $(this).data('target-step');
      if (targetStep == 'trainingSegment') {
        checkExeWorkExist = {}; // reset initial
        var id = $(this).closest('tr').data('id');
        FX.setClientPlanId(id);
        FX.loadProgram(id, resetAndPopulateTrainingSegments);
        loadExerciseList();
        if(isMultiPhase){
          var form_data = {};
          form_data.progrmId = id;
          form_data.clientPlanId = $('input[name="clientPlanId"]').val();
          form_data.programType = 2;
          $.get(public_url + 'activity-builder/library-program/multi-phase/getProgramDetails', form_data, function (response) {
            if (response.status == 'error') {
              swal({
                type: 'error',
                title: 'Something went wrong!',
                showCancelButton: false,
                allowOutsideClick: false,
                showConfirmButton: true,
              });
            } else {
              $('input[name="clientPlanProgramId"]').val(response.clientProgramId);
            }
          }, 'json')
        }
        setPlanMyProgram($(this));
        trainingSegmentModal.find('.backStepButton').data('prev-step', 'modalpopup3');
        designProgramModal.modal('hide');
        trainingSegmentModal.modal('show');
      }
    } else if (currentStep == 'trainingSegment') {
      
      trainingSegmentPanelBody = trainingSegmentModal.find('.modal-body');
      FX.clearNotific(trainingSegmentPanelBody);
      var response = validateTrainingSegment();
      if (response.setFormValid) {
        if(isMultiPhase){
          let programId = $('#clientPlanProgramId').val();
          toggleWaitShield('show');
          $.get(public_url + 'activity-builder/library-program/multi-phase/update-program', {
            id: programId
          }, function (response) {
            toggleWaitShield('hide');
            if (response.status == 'ok') {
              $('#clientPlanProgramId').val('');
              currentSessionElement.parent('p').empty().append('<span class="addedSessionProgram" data-client-program-id="' + programId + '">' + response.title + '</span> <a class="removeProgram nav-link" href="javascript:void(0)">- Remove</a>');
              trainingSegmentModal.modal('hide');
              multiPhaseProgramModal.modal('show');
            } else {
              swal({
                type: 'error',
                title: 'Something went wrong!',
                showCancelButton: false,
                allowOutsideClick: false,
                showConfirmButton: true,
              });
            }
          });
        }else{
          planMyProgramModal.find('.backStepButton').data('prev-step', 'modalpopup4');
          planMyProgramModal.modal('show');
          trainingSegmentModal.modal('hide');
        }
      } else {
        showNotific(FX.prepareNotific('error', response.message), trainingSegmentPanelBody);
      }
    } else if (currentStep == 'planMyProgram') {
      var targetStep = $(this).data('target-step');
      if (targetStep == 'planPreview') {
        planMyProgramPanelBody = planMyProgramModal.find('.modal-body');
        FX.clearNotific(planMyProgramPanelBody);
        var nextStepAllowed = true;
        var checkedOption = planMyProgramModal.find('input[name="dayOption"]:checked').val();
        if (FX.PlanType == 7) {
          if (checkedOption == '1') {
            if (planMyProgramModal.find('input[name="weekDay"]:checked').length < 2) {
              nextStepAllowed = false;
              message = 'Please select atleast two days';
            }
          } else {
            if (planMyProgramModal.find('select[name="daysInWeek"]').val() == "") {
              nextStepAllowed = false;
              message = 'Please select no of days in week';
            }
          }
        } else if (FX.PlanType == 6) {
          noOfDaysInWeek = planMyProgramModal.find('select[name="daysInWeek"]').val();
          if (checkedOption == '1') {
            var checkbox = planMyProgramModal.find('input[name="weekDay"]:checked').length;
            if (checkbox > noOfDaysInWeek || checkbox < noOfDaysInWeek) {
              nextStepAllowed = false;
              message = 'Please select ' + noOfDaysInWeek + ' days';
            }
          }
        }
        if (nextStepAllowed) {
          planPreviewFn($(this));
          planPreviewModal.find('.backStepButton').data('prev-step', 'modalpopup5');
          planMyProgramModal.modal('hide');
          planPreviewModal.modal('show');
        } else {
          showNotific(FX.prepareNotific('error', message), planMyProgramPanelBody);
        }
      }
    }
  }
});

const getDaysHtml = (phase = 1,week = 1,day = 1,dayName='') => {
  var daysHtml = '<div class="col-md-12">\
  <div class="radio clip-radio radio-primary radio-inline m-b-0">\
    <input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Sun" required value="sun" '+(dayName != '' && dayName == 'sun'? 'checked':'')+' class="onchange-set-neutral">\
    <label for="phase' + phase + 'week'+ week +'day'+ day +'Sun">Sun</label>\
  </div>\
  <div class="radio clip-radio radio-primary radio-inline m-b-0">\
    <input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Mon" required value="mon" '+(dayName != '' && dayName == 'mon'? 'checked':'')+' class="onchange-set-neutral">\
    <label for="phase' + phase + 'week'+ week +'day'+ day +'Mon">Mon</label>\
  </div>\
  <div class="radio clip-radio radio-primary radio-inline m-b-0">\
    <input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Tue" required value="tue" '+(dayName != '' && dayName == 'tue'? 'checked':'')+' class="onchange-set-neutral">\
    <label for="phase' + phase + 'week'+ week +'day'+ day +'Tue">Tue</label>\
  </div>\
  <div class="radio clip-radio radio-primary radio-inline m-b-0">\
    <input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Wed" required value="wed" '+(dayName != '' && dayName == 'wed'? 'checked':'')+' class="onchange-set-neutral">\
    <label for="phase' + phase + 'week'+ week +'day'+ day +'Wed">Wed</label>\
  </div>\
  <div class="radio clip-radio radio-primary radio-inline m-b-0">\
    <input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Thu" required value="thu" '+(dayName != '' && dayName == 'thu'? 'checked':'')+' class="onchange-set-neutral">\
    <label for="phase' + phase + 'week'+ week +'day'+ day +'Thu">Thu</label>\
  </div>\
  <div class="radio clip-radio radio-primary radio-inline m-b-0">\
    <input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Fri" required value="fri" '+(dayName != '' && dayName == 'fri'? 'checked':'')+' class="onchange-set-neutral">\
    <label for="phase' + phase + 'week'+ week +'day'+ day +'Fri">Fri</label>\
  </div>\
  <div class="radio clip-radio radio-primary radio-inline m-b-0">\
    <input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Sat" required value="sat" '+(dayName != '' && dayName == 'sat'? 'checked':'')+' class="onchange-set-neutral">\
    <label for="phase' + phase + 'week'+ week +'day'+ day +'Sat">Sat</label>\
  </div>\
  </div>';
  return daysHtml;
}

function setPlanMyProgram(elem) {
  setFieldNeutral($('#days-crm'));
  $("#timeSelection").hide();

  //Start: Days Option show hide in Library Program
  var pathname = window.location.pathname;
  if (pathname.includes("library-program")) {
    $("#daySelect").hide();
  } else {
    if (startData != '') {
      $("#daySelect").find('input[name="startDate"]').val(startData);
    }
    $("#daySelect").show();
  }
  //End

  //Set default data for week slider
  var defaultValForWeek = 12,
    maxWeeks = 16,
    maxTime = 6,
    text = 'at least 2',
    minDays = 0
  //End

  if (FX.clientid != 0) {
    let dayOption = elem.data('day-option');
    if(dayOption != undefined && dayOption != '' && dayOption == 1){
      $('select[name=daysInWeek]').val('');
      $('select[name=daysInWeek]').attr('disabled', false);
      $('select[name=daysInWeek]').selectpicker('refresh');
      $('input[name="noOfDaysWeek"]').val('');
      $('#Choosedays').trigger('click');
      $('#showWeekday').show();
      $('#selectOneday').hide();
    }else{
      $('select[name=daysInWeek]').val(elem.data('days-in-week'));
      $('select[name=daysInWeek]').attr('disabled', true);
      $('select[name=daysInWeek]').selectpicker('refresh');
      $('input[name="noOfDaysWeek"]').val(elem.data('days-in-week'));
      $('#daysInWeek').trigger('click');
      $('#showWeekday').hide();
      $('#selectOneday').show();
      text = elem.data('days-in-week');
    }
    var dayPatterns = elem.data('day-pattern');
    if (!dayPatterns)
      dayPatterns = '0000000';
    else
      dayPatterns = dayPatterns.toString();

    var dayPattern = dayPatterns.split('');
    /*var sunday = dayPattern.shift();
    dayPattern.push(sunday)*/
    var checkedDaysCount = 0;
    $.each(dayPattern, function (index, value) {
      var day = $('#days-crm').find('input').eq(index);
      if (value == 1) {
        day.prop('checked', true)
        checkedDaysCount++;
      } else
        day.prop('checked', false)
    });
    if (FX.PlanType == 6) {
      $("#timeSelection").hide();
      if (checkedDaysCount) {
        var text = 'at least ' + checkedDaysCount,
          minDays = checkedDaysCount;
      }
    } else if (FX.PlanType == 5) {
      var time = $(this).data('time');
      if (!time)
        time = 3;
      $("#timeSelection").show();
    }
  } else {
    $('#days-crm').find('input').prop('checked', false);
    let dayOption = elem.data('day-option');
    if(dayOption != undefined && dayOption != '' && dayOption == 1){
      $('select[name=daysInWeek]').val('');
      $('select[name=daysInWeek]').attr('disabled', false);
      $('select[name=daysInWeek]').selectpicker('refresh');
      $('input[name="noOfDaysWeek"]').val('');
      $(".chooseDays").hide();
      $(".chooseWeek").show();
      $(".startDateOption").hide();
      $("#showWeekday").hide();
      $("#selectOneday").show();
      $('.letClientSelect').hide();
      $("#daySelect").show();
    }else{
      $('select[name=daysInWeek]').val(elem.data('days-in-week'));
      $('select[name=daysInWeek]').attr('disabled', false);
      $('select[name=daysInWeek]').selectpicker('refresh');
      $('input[name="noOfDaysWeek"]').val(elem.data('days-in-week'));
      $(".chooseDays").hide();
      $(".chooseWeek").show();
      $(".startDateOption").hide();
      $("#showWeekday").hide();
      $("#selectOneday").show();
      $('.letClientSelect').hide();
      $("#daySelect").show();
    }
  }
  var weeks = elem.data('weeks');
  if (typeof weeks != 'undefined' && weeks) {
    defaultValForWeek = weeks;
  }
  $( "#weekSlider-crm" ).labeledslider("destroy");
  if (FX.PlanType == 6){
    $("#weekSlider-crm").labeledslider({
      min: 1,
      max: maxWeeks,
      tickInterval: 1,
      value: defaultValForWeek,
      range: "min",
      disabled:true
    });
  }else{
    $("#weekSlider-crm").labeledslider({
      min: 1,
      max: maxWeeks,
      tickInterval: 1,
      value: defaultValForWeek,
      range: "min"
    });
  }
  //$("#weekSlider-crm").labeledslider("option", "max", maxWeeks).labeledslider("value", defaultValForWeek);
  $('#daySelectionTextCRM').html(text);
  $('#days-crm').data('days-required', minDays);
}

$('body').on('click', '.backStepButton', function () {
  var prevModal = $(this).data('prev-step');
  $('#' + prevModal).modal('show');
  $(this).closest('.modal').modal('hide');
});

function validateTrainingSegment() {
  var response = {};
  var setFormValid = true;
  var exercises = $('#choosedTrainingsAccordion').find('.exercise');
  if (exercises.length) {
    exercises.each(function () {
      if ($(this).find('.treningSeg-form').length) {
        var form = $(this).find('.treningSeg-form');
        if ((form.find('input[name="exercTempo"]').val() == '' || form.find('input[name="exercTempo"]').val() == undefined) || (form.find('input[name="exercReps"]').val() == '' || form.find('input[name="exercReps"]').val() == undefined) || (form.find('input[name="exercDur"]').val() == '' || form.find('input[name="exercDur"]').val() == undefined) || (form.find('input[name="exercResist"]').val() == '' || form.find('input[name="exercResist"]').val() == undefined) || (form.find('input[name="exercRest"]').val() == '' || form.find('input[name="exercRest"]').val() == undefined)) {
          setFormValid = false;
          response['message'] = 'All Sets Fields are required';
        }
      }
    });
  } else {
    response['message'] = 'Please choose at lease one exercise under any training segment.';
  }
  response['setFormValid'] = setFormValid;
  return response;
}

function swalAlert(message) {
  swal({
    type: 'warning',
    title: message,
    showCancelButton: false,
    allowOutsideClick: false,
    showConfirmButton: true,
  });
}

$('body').on('click', '.addActivityPlan', function () {
  trainingStepModal.modal('show');
  startData = $(this).closest('td').data('date');
});
$('input[name="weekDay"]').click(function () {
  var noOfDaysWeek = $('select[name="daysInWeek"]').val();
  if (noOfDaysWeek != '' && FX.PlanType == 6) {
    var checkbox = $('input[name="weekDay"]:checked').length;
    if (checkbox == noOfDaysWeek || checkbox < noOfDaysWeek) {

    } else {
      $(this).prop("checked", false);
    }
  }
});

function setDaysOfWeek(response) {
  $('#daysInWeek').trigger('click');
  $('#showWeekday').hide();
  $('#selectOneday').show();
  $.each(response.Exercises, function (index, value) {
    if (FX.PlanType == 6) {
      if (value.noOfDaysInWeek != null) {
        $('.bootstrap-select .filter-option').text(value.noOfDaysInWeek);
        $('select[name=daysInWeek]').val(value.noOfDaysInWeek);
        $('select[name=daysInWeek]').attr('disabled', true);
        $('input[name="noOfDaysWeek"]').val(value.noOfDaysInWeek);

      } else {
        $('select[name=daysInWeek]').val('');
        $('select[name=daysInWeek]').attr('disabled', false);
        $('input[name="noOfDaysWeek"]').val('');
      }
    }
  });
}

$('body').on('click', '#letClientSelect', function () {
  if ($(this).is(':checked')) {
    $('#startDate').val('');
  } else {
    $('#startDate').val(startData);
  }
});

$(document).ready(function () {
  $('#deleteClientClass').click(function (e) {
    e.preventDefault();
    modal = $('#activityModal');
    var clientId = modal.find('input[name="clientplan_id"]').val();
    if ($(this).data('no-of-week') == '1') {
      text = "<a class='btn btn-primary removeClient' href='#' data-target-event='this' data-client-id='" + clientId + "'>This only</a>";
    } else {
      text = "<a class='btn btn-primary m-r-10 removeClient' href='#' data-target-event='future' data-client-id='" + clientId + "'>This and future</a><a class='btn btn-primary removeClient' href='#' data-target-event='this' data-client-id='" + clientId + "'>This only</a>";
    }
    swal({
      title: 'Delete From?',
      showCancelButton: true,
      html: true,
      text: text,
      showConfirmButton: false,
      allowOutsideClick: true,
      customClass: 'classClientUnlinkAlert',
    });
  });

  $('body').on("click", '.removeClient', function (e) {
    e.preventDefault();
    modal = $('#activityModal');
    var formData = {};
    if(modal.find('input[name="clientplan_id"]').val() == undefined || modal.find('input[name="clientplan_id"]').val() ==''){
      formData['clientplan_id'] = FX.PlanId;
    }else{
      formData['clientplan_id'] = modal.find('input[name="clientplan_id"]').val();
    }
    if(modal.find('input[name="date_id"]').val() == undefined || modal.find('input[name="date_id"]').val() == ''){
      formData['dateId'] = FX.DateId;
    }else{
      formData['dateId'] = modal.find('input[name="date_id"]').val();
    }
    formData['targetEvents'] = $(this).data('target-event');
    $.ajax({
      url: public_url + 'activity/delete',
      type: 'Post',
      data: formData,
      success: function (response) {
        getClientPlan();
        swal.close();
        modal.modal('hide');
      },
    });
  });

  $('body').on('click','.cancelSwal',function(){
    swal.close();
  })
});

jQuery(document).ready(function ($) {
  var path = public_url + 'CustomPlan/SearchExercisesByKeywords';
  $('.search-input').typeahead({
    minLength: 3,
    source: function (query, process) {
      return $.get(path, {
        keyWords: query
      }, function (data) {
        var data = JSON.parse(data);
        return process(data);
      });
    },
    afterSelect: function (data) {
      //print the id to developer tool's console
      $('#exerciseId').val(data.id);
    }
  });
});