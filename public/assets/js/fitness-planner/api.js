var DOMAIN = $('meta[name=public_url]').attr("content"),
  API = {
    URL: DOMAIN + 'Planner/',
    CUSTOM_PROGRAM_URL: DOMAIN + 'CustomPlan/',

    ajaxSkeleton: function (url, params, cb) {
      $.getJSON(url, params, function (data) {
        if (typeof cb != 'undefined')
          cb(data);
      });
    },

    ajax: function (url, params, cb) {
      $.getJSON(API.URL + url, params, function (data) {
        if (typeof cb != 'undefined')
          cb(data);
      });
    },

    customPlanAjax: function (url, params, cb) {
      API.ajaxSkeleton(API.CUSTOM_PROGRAM_URL + url, params, cb);
    },

    getAjax: function (url, params, callback) {
      $.getJSON(DOMAIN + url, params, function (response) {
        if (typeof callback != 'undefined')
          callback(response);
      });
    },
    postAjax: function (url, params, callback) {
      $.ajax({
        url: public_url + url,
        type: 'POST',
        data: params,
        success: function (response) {
          var data = JSON.parse(response);
          if (typeof callback != 'undefined')
            callback(data);
        },
      });
    }
  };

var FX = {
  SessionGuid: 'd95a16d3-3a54-4598-a55a-af4abc086a29',
  gender: 0,
  genderString: '',
  clientid: 0,
  ClientPlanId: 0,
  ClientVideoId: 0,
  ExersiseId: 0,
  EditWorkoutId: 0,
  PlanType: 0,
  IMAGE_DIR_URL: '',
  NO_IMAGE: '',
  PlanId: 0,
  DateId: 0,
  WorkOutId: 0,
  DifficultyLevels: {
    1: 'Rehabilitation',
    2: 'Beginner',
    3: 'Intermediate',
    4: 'Advanced'
  },
  ExBodyParts: ['Shoulders', 'Chest', 'Anterior Arms', 'Core', 'Forearms', 'Anterior Thigh', 'Neck and Upper Back', 'Posterior Arms', 'Lats and Middle Back', 'Lower Back', 'Hips and Glutes', 'Posterior Thigh', 'Lower Leg'],


  /* Start : UI Setup (use FX.UI.UIname) */
  UI: {
    popupParams: {
      className: 'pt-popup',
      modal: true,
      width: 720
    },
    aexTo: null,
    isMobile: false,
    currentPage: 1,
    searchScroll: null,
    start: function () {
      FX.UI.isMobile = jQuery(document).width() <= 480;
      var wid = jQuery(document).width();
      if (wid < 720) {
        jQuery("<style> .pt-popup .popup_content { width:" + (wid - 24) + "px; margin:5px 3px 3px 3px; }</style>").appendTo("head");
        FX.UI.popupParams.width = wid - 20;
      }
      var mplist = jQuery('.pts .my_pt');
      if (mplist[0]) {
        //FX.loadProgramsList();
        return;
      }
    },
  },
  /* End : UI Setup */

  /* Start : Setter functions */
  /* Set gender */
  setGender: function (gender) {
    if (typeof gender != 'undefined' && gender != '') {
      var gender = gender.toLowerCase();
      if (gender == 'male')
        var Gender = 2;
      else if (gender == 'female')
        Gender = 1;
      else if(gender == 'unisex')
        Gender = 3;

      FX.genderString = gender;
      FX.gender = Gender;
    }
  },

  /*Set plan type */
  setPlanType: function (plan_type) {
    FX.PlanType = parseInt(plan_type);
  },

  /*Set client Id */
  setClientid: function (clientid) {
    FX.clientid = parseInt(clientid);
  },

  /* Set client plan id */
  setClientPlanId: function (clientPlanId) {
    FX.ClientPlanId = parseInt(clientPlanId);
  },

  /* Set client plan id */
  setClientVideId: function (videoId) {
    FX.ClientVideoId = parseInt(videoId);
  },

  /* Set Exersise id */
  setExersiseId: function (exe_id) {
    FX.ExersiseId = parseInt(exe_id);
  },

  /*Set Edit plan_workout_exersise_id */
  setPlanWorkExeId: function (id) {
    FX.EditWorkoutId = parseInt(id);
  },

  /* Set client plan id */
  setPlanId: function (Id) {
    FX.PlanId = Id;
  },

  /* Set Date id */
  setDateId: function (Id) {
    FX.DateId = Id;
  },

  /* Set workout id */
  setWorkOutId: function (Id) {
    FX.WorkOutId = Id;
  },
  /* End : Setter functions */

  /* Start: FX object functions as helper function for actvity plan */

  /**
   * Display all created program
   * @param client id
   * @return
   **/
  loadProgramsList: function (programChoosed) {
    var param = {
      Clientid: FX.clientid
    };
    if (typeof programChoosed != 'undefined')
      param['PlanType'] = programChoosed;

    API.customPlanAjax('GetUsersPlans', param, function (response) {
      $('#design-program-datatable').DataTable().destroy();
      $('.pts .my_pt tbody').empty()

      var plans = response.Plans
      /*,
                listProgramdata = []*/
      ;
      if (plans != undefined) {
        for (var i = 0; i < plans.length; i++) {
          FX.addProgramInList(plans[i]);
        }
      }
      //customPlanDatatable = $('#client-datatable').DataTable();
    });
  },

  /**
   * Get program
   * @param client plan id
   * @return
   **/
  loadProgram: function (id, callback) {
    data = {
      fixedProgramId: id
    };
    API.customPlanAjax('GetUsersPlanDetail', data, callback);
  },
  /* Start: Load Existing program */

  /** 
   * display design program list
   * @param program
   * @return list program
   **/
  addProgramInList: function (p) {
    var name = $('input[name="fit-clientName"]').val();
    if (typeof name == 'undefined' || name == 'undefined')
      var name_ad = 'Admin';
    else
      name_ad = name;

    var timeStamp = parseInt(p.DateChanged.substring(p.DateChanged.lastIndexOf("(") + 1, p.DateChanged.lastIndexOf(")")), 10);
    jQuery(FX.UI.isMobile ? '<a href="javascript:void(0)">' : '<tr>')
      .attr({
        'data-id': p.FixedProgramId,
        'data-desc': p.ProgramDesc,
        'data-name': p.ProgramName
      })
      .append('<td>' + p.ProgramName + '</td>\
            <td>' + moment(timeStamp).format('ddd, D MMM YYYY') + '</td>\
            <td class="hidden-xs">' + name_ad + '</td>\
            <td class="center">\
                <a href="#" class="btn btn-xs btn-primary tooltips open-step nextStepButton" data-placement="top" data-original-title="View" data-target-step="trainingSegment" data-current-step="designProgram" data-weeks="'+p.weeksToExercise+'" data-day-option="'+p.dayOption+'" data-day-pattern="'+p.daysOfWeek+'" data-days-in-week="'+p.noOfDaysInWeek+'">\
                    <i class="fa fa-share link-btn"></i>\
                </a>\
                <a class="btn btn-xs btn-primary tooltips customPlanUpdateModalCls" href="#" data-placement="top" data-original-title="Edit" data-weeks="'+p.weeksToExercise+'" data-day-option="'+p.dayOption+'" data-day-pattern="'+p.daysOfWeek+'" data-days-in-week="'+p.noOfDaysInWeek+'">\
                    <i class="fa fa-pencil link-btn"></i>\
                </a>\
                <a class="btn btn-xs btn-primary tooltips planDelete" href="#" data-placement="top" data-original-title="Delete"  >\
                    <i class="fa fa-trash-o link-btn"></i>\
                </a>\
            </td>\
        </tr>')
      .appendTo(jQuery('.pts .my_pt tbody'));
  },

  /** 
   * filter program for generator program option
   * @param filter value
   * @return response
   **/
  getFilterPlan: function (formData, callback) {
    formData['gender'] = FX.gender;
    formData['plan_type'] = FX.PlanType;
    API.ajax('GetFilterPlan', formData, callback);
  },

  /** 
   * create program(program design)
   * @param form
   * @return response
   **/
  createProgram: function (f) {
    var formData = {},
      pn = f.pname.value;

    formData.image = '';
    formData.curr_ability = '';
    formData.Gender = FX.gender;
    formData.name = pn;
    formData.description = '';
    formData.snippet = '';
    formData.Clientid = FX.clientid;

    if (pn.replace(/ /g, '').length > 0) {
      API.customPlanAjax('CreateProgram', formData, function (response) {
        if (response.MessageId == 0) {
          FX.ClientPlanId = response.Program.FixedProgramId;
          f.pname.value = '';
          FX.loadProgramsList(FX.PlanType);
          resetTrainingSegments();
          openStep($(f.btn));
          loadExerciseList();
        }
      });
    } else
      FX.showErrorDiv('.pts .error-msg');
  },

  /* Start: Add exercise to program */
  addExToProgram: function (exercise, callback) {
    API.customPlanAjax('AddExerciseToProgram', exercise, callback);
  },
  /* End: Add exercise to program */

  /* start: Save plan to API */
  planPreview: function (data, callback) {
    toggleWaitShield("show");
    API.customPlanAjax('PlanPreview', data, callback);
  },
  /* end: Save plan to API */

  /* start: Save plan to API */
  savePlan: function (data, callback) {
    data.GetPreWritten = false;
    data.Clientid = FX.clientid;
    data.ClientPlanId = FX.ClientPlanId;
    data.PlanType = FX.PlanType;

    API.ajax('SavePlan', data, callback);
  },
  /* end: Save plan to API */

  /**
   * Get workout with exercise and randor on modal
   * @param
   * @return
   **/
  GetWorkoutWithExercise: function (modal) {
    var formData = {
      'clientPlanId': FX.PlanId,
      'eventDateId': FX.DateId
    };
    API.getAjax('activity/date/planDetail', formData, function (response) {
      if (response.Status == 'success') {
        var htmlBody = {};
        var modalBody = $('#caledar-exe-accordion').find('.activity-video');
        modalBody.empty();
        var i = 1;
        var j = 1;
        $('#saveDateTrainingSeg').show();
        if (response.isActivityVideo == 1) {
          $('.left-video-section').hide();
          var videoHtml = '<video width="400" controls>\
                            <source src="' + DOMAIN + 'uploads/' + response.activityVideo.video + '" type="video/mp4">\
                            Your browser does not support HTML5 video.\
                          </video>';
          modalBody.append(videoHtml);
          modalBody.show();
          $('#saveDateTrainingSeg').hide();

        } else {
          $.each(response.workoutData, function (key, obj) {
            var workoutName = obj.name;
            var workoutElement = $('#caledar-exe-accordion').find('#seg_' + workoutName).closest('.panel').clone();
            $('#caledar-exe-accordion').find('#seg_' + workoutName).closest('.panel').remove();
            $('#caledar-exe-accordion').prepend(workoutElement);
          });
          var videoSliderHtml = "";

          $.each(response.Exercise, function (key, workout) {
            var videoAccrodianHtml = '';
            var count = 1;
            $.each(workout, function (exekey, exercise) {
              var rowHtml = "";
              if (exercise.Resistance == null)
                exercise.Resistance = '';
              if (exercise.TempoDesc == null)
                exercise.TempoDesc = '';
              if (exercise.Type == 1) {
                if (exercise.isRest == '0') {
                  if (exercise.exercise_sets.length > 0) {
                    $.each(exercise.exercise_sets, function (key, obj) {
                      rowHtml += '<div class="col-md-10 setRow" data-set-duration="' + obj.estimatedTime + '" data-rest-duration="' + obj.restSeconds + '" data-is-finished="0">\
                          <div class="form-inline m-t-5 treningSegClsDate" data-exercise-id="' + exercise.ExeId + '" data-clientexe-id="' + exercise.ClientExeId + '" data-client-exe-set-id="' + obj.id + '">\
                              <div class="form-group">\
                                  <label for="exercSets" class="custom-label">SETS</label>\
                                  <input type="number" value="' + obj.sets + '" class="form-control custom-form-control numericField" id="exercSets" name="exercSets" min="0" required="required" readonly>\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercReps" class="custom-label">REPETITION</label>\
                                  <input type="number" value="' + obj.repetition + '" class="form-control numericField custom-form-control" id="exercReps" name="exercReps" min="0" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercDur" class="custom-label">OR DURATION</label>\
                                  <input type="number" value="' + obj.estimatedTime + '" class="form-control numericField custom-form-control" id="exercDur" name="exercDur" min="0" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercResist" class="custom-label">RESISTANCE</label>\
                                  <input type="text" value="' + obj.resistance + '" class="form-control custom-form-control" id="exercResist" name="exercResist" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercTempo" class="custom-label">TEMPO</label>\
                                  <input type="text" value="' + obj.tempoDesc + '" class="form-control custom-form-control" id="exercTempo" name="exercTempo" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercRest" class="custom-label">REST</label>\
                                  <input type="number" value="' + obj.restSeconds + '" class="form-control numericField custom-form-control" id="exercRest" name="exercRest" min="0" required="required">\
                              </div>\
                          </div>\
                        </div>\
                        <div class="col-md-2 m-t-20" >\
                          <a href="#" class="btn btn-sm btn-default tooltips deleteDateExe" data-placement="top" data-entity="exercise">\
                            <i class="fa fa-times link-btn"></i>\
                          </a>\
                        </div>';
                    });
                  } else {
                    rowHtml = '<div class="col-md-10 setRow" data-set-duration="0" data-rest-duration="0" data-is-finished="0">\
                          <div class="form-inline m-t-5 treningSegClsDate" data-exercise-id="' + exercise.ExeId + '" data-clientexe-id="' + exercise.ClientExeId + '" data-client-exe-set-id="">\
                              <div class="form-group">\
                                  <label for="exercSets" class="custom-label">SETS</label>\
                                  <input type="number" value="1" class="form-control custom-form-control numericField" id="exercSets" name="exercSets" min="0" required="required" readonly>\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercReps" class="custom-label">REPETITION</label>\
                                  <input type="number" value="" class="form-control numericField custom-form-control" id="exercReps" name="exercReps" min="0" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercDur" class="custom-label">OR DURATION</label>\
                                  <input type="number" value="" class="form-control numericField custom-form-control" id="exercDur" name="exercDur" min="0" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercResist" class="custom-label">RESISTANCE</label>\
                                  <input type="text" value="" class="form-control custom-form-control" id="exercResist" name="exercResist" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercTempo" class="custom-label">TEMPO</label>\
                                  <input type="text" value="" class="form-control custom-form-control" id="exercTempo" name="exercTempo" required="required">\
                              </div>\
                              <div class="form-group">\
                                  <label for="exercRest" class="custom-label">REST</label>\
                                  <input type="number" value="" class="form-control numericField custom-form-control" id="exercRest" name="exercRest" min="0" required="required">\
                              </div>\
                          </div>\
                      </div>\
                      <div class="col-md-2 m-t-20" >\
                        <a href="#" class="btn btn-sm btn-default tooltips deleteDateExe" data-placement="top" data-entity="exercise">\
                          <i class="fa fa-times link-btn"></i>\
                        </a>\
                      </div>';
                  }
                  var html = '<div class="panel panel-default" data-is-rest="' + exercise.isRest + '">\
                      <div class="panel-heading">\
                        <h4 class="panel-title">\
                          <a class="colorstyle collapsed" href="#content-' + key + exercise.ExeId + count + '" data-toggle="collapse" data-parent="#accordion' + j + '" aria-expanded="false">\
                            <div class="video-data">\
                              <div class="video-title">' + exercise.Name + '</div>\
                            </div>\
                          </a>\
                        </h4>\
                      </div>\
                      <div class="panel-colapse collapse" id="content-' + key + exercise.ExeId + count + '">\
                        <div class="panel-body">\
                          <div class="row">\
                          ' + rowHtml + '\
                          </div>\
                        </div>\
                      </div>\
                    </div>';
                  videoAccrodianHtml += html;
                  var videoItemHtml = '<div class="item" data-is-video="1">\
                        <div class="video-loader"><h2>Rest</h2><div class="loaderinner"></div></div>\
                        <div class="video-duration">0</div>\
                        <video class="ban_video" controls="" data-training-segment="' + key + '" data-exe-id="' + exercise.ExeId + '" data-count="' + count + '" width="100%" height="400px" data-is-rest="' + exercise.isRest + '">\
                          <source src="' + DOMAIN + 'uploads/' + exercise.VideoUrl + '" type="video/mp4">\
                        </video>\
                      </div>';
                  videoSliderHtml += videoItemHtml;
                } else {
                  var html = '<div class="panel panel-default" data-is-rest="' + exercise.isRest + '">\
                      <div class="panel-heading">\
                        <h4 class="panel-title">\
                          <a class="colorstyle collapsed" href="#content-' + key + exercise.ExeId + count + '" data-toggle="collapse" data-parent="#accordion' + j + '" aria-expanded="false">\
                            <div class="video-data">\
                              <div class="video-title">' + exercise.Name + '</div>\
                            </div>\
                          </a>\
                        </h4>\
                      </div>\
                      <div class="panel-colapse collapse" id="content-' + key + exercise.ExeId + count + '">\
                        <div class="panel-body">\
                          <div class="row">\
                            <div class="col-md-12 restPanel" data-duration="' + exercise.RestSeconds + '">Time: ' + exercise.RestSeconds + '</div>\
                          </div>\
                        </div>\
                      </div>\
                    </div>';
                  videoAccrodianHtml += html;
                  var videoItemHtml = '<div class="item" data-is-video="0">\
                        <div class="image-duration">0</div>\
                        <image src="' + public_url + 'result/images/hand.png" data-training-segment="' + key + '" data-exe-id="' + exercise.ExeId + '" data-count="' + count + '" width="100%" height="400px" class="ban_video" data-is-rest="' + exercise.isRest + '" data-duration="' + exercise.RestSeconds + '">\
                      </div>';
                  videoSliderHtml += videoItemHtml;
                }
              } else {
                if (exercise.isRest == '0') {
                  var innerHtml = '';
                  $.each(exercise.MovementData, function (index, obj) {
                    innerHtml += '<div class="video-data">\
                                      <div class="video-title">' + obj.name + '</div>\
                                      <div class="video-value">' + obj.time + '</div>\
                                    </div>';
                  });
                  var html = '<div class="panel panel-default">\
                      <div class="panel-heading">\
                        <h4 class="panel-title">\
                          <a class="colorstyle collapsed" href="#content-' + key + exercise.ExeId + count + '" data-toggle="collapse" data-parent="#accordion' + j + '" aria-expanded="false">\
                            <div class="video-data">\
                              <div class="video-title">' + exercise.Name + '</div>\
                              <div class="video-value">' + exercise.EstimatedTime + '</div>\
                            </div>\
                          </a>\
                        </h4>\
                      </div>\
                      <div class="panel-colapse collapse" id="content-' + key + exercise.ExeId + count + '">\
                        <div class="panel-body">\
                        ' + innerHtml + '\
                        </div>\
                      </div>\
                    </div>';
                  videoAccrodianHtml += html;
                  var videoItemHtml = '<div class="item" data-is-video="1">\
                        <video class="ban_video" controls="" data-training-segment="' + key + '" data-exe-id="' + exercise.ExeId + '" data-count="' + count + '" width="100%" height="400px" data-is-rest="' + exercise.isRest + '">\
                          <source src="' + DOMAIN + 'uploads/' + exercise.VideoUrl + '" type="video/mp4">\
                        </video>\
                      </div>';
                  videoSliderHtml += videoItemHtml;
                } else {
                  var html = '<div class="panel panel-default">\
                      <div class="panel-heading">\
                        <h4 class="panel-title">\
                          <a class="colorstyle collapsed" href="#content-' + key + exercise.ExeId + count + '" data-toggle="collapse" data-parent="#accordion' + j + '" aria-expanded="false">\
                            <div class="video-data">\
                              <div class="video-title">' + exercise.Name + '</div>\
                            </div>\
                          </a>\
                        </h4>\
                      </div>\
                      <div class="panel-colapse collapse" id="content-' + key + exercise.ExeId + count + '">\
                        <div class="panel-body">\
                          <div class="row">\
                            <div class="col-md-12">Time: ' + exercise.RestSeconds + '</div>\
                          </div>\
                        </div>\
                      </div>\
                    </div>';
                  videoAccrodianHtml += html;
                  var videoItemHtml = '<div class="item" data-is-video="0">\
                    <div class="image-duration"></div>\
                        <image src="' + public_url + 'result/images/hand.png" data-training-segment="' + key + '" data-exe-id="' + exercise.ExeId + '" data-count="' + count + '" width="100%" height="400px" class="ban_video" data-is-rest="' + exercise.isRest + '" data-duration="' + exercise.RestSeconds + '">\
                      </div>';
                  videoSliderHtml += videoItemHtml;
                }
              }
              i = i + 1;
              count = count + 1;
            });
            if (key in htmlBody) {
              var videoDataHtml = '<div class="video-details">\
                <div class="panel-group" id="accordion' + j + '">\
                ' + videoAccrodianHtml + '\
                <div>\
              <div>';
              htmlBody[key] += videoDataHtml;
            } else {
              var videoDataHtml = '<div class="video-details">\
                <div class="panel-group" id="accordion' + j + '">\
                ' + videoAccrodianHtml + '\
                <div>\
              <div>';
              htmlBody[key] = videoDataHtml;
            }
            j = j + 1;
          });

          $.each(htmlBody, function (workoutid, workouts) {
            var appendArea = $('#seg_' + workoutid).find('.panel-body');
            appendArea.empty();
            appendArea.closest('.panel').show();
            appendArea.append(workouts);
          });
          $('.left-video-section').show();
          $('#caledar-exe-accordion').removeClass('without-video');
          $('#activityVideoCarousal').empty();
          $('#activityVideoCarousal').append(videoSliderHtml);
          // trigger owl carousal
          $('#activityVideoCarousal').trigger('destroy.owl.carousel');
          $("#activityVideoCarousal").owlCarousel({
            autoplay: false,
            margin: 30,
            loop: false,
            dots: false,
            nav: true,
            items: 1,
            video: true,
            responsive: {
              0: {
                items: 1,
              },
              768: {
                items: 1,
              },
              992: {
                items: 1,
              }
            }
          });
          var trainingSegment = "";
          $("#activityVideoCarousal .owl-item").each(function (key, obj) {
            if (key == 0) {
              trainingSegment = $(this).find('.ban_video').data('training-segment');
            }
          });
          $('#caledar-exe-accordion').find("#seg_" + trainingSegment).closest('.panel').find('a[href="#seg_' + trainingSegment + '"]').trigger('click');
          $("#seg_" + trainingSegment).find('.panel a.colorstyle:first-child').trigger('click');
          $("#seg_" + trainingSegment).find('.panel a').each(function (key, obj) {
            if (key == 0) {
              $(this).removeClass('collapsed');
              $(this).attr('aria-expanded', "true");
            }
          });
          playVideoAccordingCondition();
          var videoSection = $("#activityVideoCarousal .owl-item .ban_video");
          for (i = 0; i < videoSection.length; i++) {
            if (videoSection[i].dataset['isRest'] == '0') {
              videoSection[i].addEventListener('ended', myHandler, false);
            }
          }
        }
        $('#deleteClientClass').data('no-of-week', response.noOfWeek)
        toggleWaitShield('hide');
        modal.modal('show');
      } else {
        toggleWaitShield('hide');
        if (response.noOfWeek == '1') {
          text = "<a class='btn btn-danger cancelSwal' href='#'>Cancel</a> <a class='btn btn-primary removeClient' href='#' data-target-event='this'>This only</a>";
        } else {
          text = "<a class='btn btn-danger cancelSwal' href='#'>Cancel</a> <a class='btn btn-primary m-r-10 removeClient' href='#' data-target-event='future'>This and future</a><a class='btn btn-primary removeClient' href='#' data-target-event='this'>This only</a>";
        }
        swal({
            type: 'error',
            title: 'Error!',
            html: true,
            showCancelButton: false,
            allowOutsideClick: false,
            text: "Exercise Or Activity Video may have been deleted.<br> Do you want to delete this Activity Plan?<br><br>"+text,
            showConfirmButton: false,
            ConfirmButtonText: "Delete",
            cancelButtonText: "cancel"
        });
      }
    });
  },

  /** Save fielter for generate program section
   * @param form data
   * @return 
   **/
  addFilterToGenPlan: function (formData, callback) {
    formData.exercise_id = FX.ExersiseId;
    API.customPlanAjax('AddFilterToGenPlan', formData, callback)
  },

  /* start: Calculate work out day pattern from days */
  calcWorkoutdaysPattern: function (checkboxes) {
    var dayPattern = [];
    checkboxes.each(function () {
      if ($(this).is(':checked'))
        dayPattern.push(1)
      else
        dayPattern.push(0)
    });

    /*var sunday = dayPattern.pop();
    dayPattern.unshift(sunday);*/
    dayPattern = dayPattern.join('');
    return dayPattern;
  },
  /* end: Calculate work out day pattern from days */

  /* start: Convert minutes to hour and minutes */
  minsToHourMin: function (mins) {
    var hours = Math.floor(mins / 60),
      minutes = Math.floor(mins % 60);

    return hours + ':' + ((minutes < 10) ? '0' + minutes : minutes);
  },
  /* end: Convert minutes to hour and minutes */

  /* start: Convert numeric string such as '10' to numeric eg: 10 */
  numericStringToInt: function (numericstring) {
    var parsed = parseInt(numericstring, 10);
    if (isNaN(parsed))
      return 0;
    return parsed;
  },
  /* end: Convert numeric string such as '10' to numeric eg: 10 */

  /* start: Set value to 0 if provided value is empty or zero string '0' */
  reinitIfNotVal: function (newVal) {
    if (!newVal || newVal == 0)
      return 0;
    return newVal;
  },
  /* end Set value to 0 if provided value is empty */

  /* start: Calculate per day workout time */
  calcPerDayWorkoutTime: function (minsPerWeek, daysTraining) {
    if (!daysTraining)
      var minsPerDay = 0;
    else
      var minsPerDay = Math.floor(minsPerWeek / daysTraining);

    return FX.minsToHourMin(minsPerWeek) + " (~" + minsPerDay + " mins daily)";
  },
  /* end: Calculate per day workout time */

  /* start: Determine alerts background color based on the type */
  getAlertsColor: function (type) {
    if (type == 'error')
      return 'danger';
    if (type == 'success')
      return 'success';
    if (type == 'warning')
      return 'warning';
  },
  /* end: Determine alerts background color based on the type */

  /* start: Clear notification messages */
  clearNotific: function (parentElem, alertGroup) {
    if (typeof parentElem == 'undefined')
      parentElem = $('body');

    if (typeof alertGroup == 'undefined')
      parentElem.children('.alert').remove();
    else
      parentElem.find('.' + alertGroup).remove();
  },
  /* start: Clear notification messages */

  /* start: Prepare html for notification messages */
  prepareNotific: function (type, message, alertGroup) {
    if (typeof alertGroup == 'undefined')
      alertGroup = '';

    return '<div class="alert alert-' + FX.getAlertsColor(type) + ' ' + alertGroup + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + message + '</div>';
  },
  /* end: Prepare html for notification messages */

  /* start: Prepare html for alert messages */
  prepareAlert: function (type, message, alertGroup) {
    if (typeof alertGroup == 'undefined')
      alertGroup = '';

    return '<div class="alert alert-' + FX.getAlertsColor(type) + ' ' + alertGroup + '">' + message + '</div>';
  },
  /* end: Prepare html for alert messages */

  /* start: Get slider value */
  getSliderValue: function (slider) {
    return slider.labeledslider('option', 'value');
  },
  /* end: Get slider value */

  /* Start: Show error div */
  showErrorDiv: function (selector) {
    jQuery(selector).show();
    setTimeout("jQuery('" + selector + "').hide()", 2000);
  },
  /* End: Show error div */

  /* Start: Infinite Scroll function */
  InfiniteScroller: function (obj, callback) {
    _self = this;
    this.obj = obj;
    this.callback = callback;
    this.ticker = setInterval('_self.test()', 100);
    this.height = 265;
    this.enabled = true;

    this.test = function () {
      if (!this.enabled) return;
      var ib = this.obj.scrollTop();
      var xs = (this.obj[0].scrollHeight - this.height);
      if (ib > 0 && ib >= xs) this.callback(true);
    }
  }
  /* End: Infinite Scroll function */
};