var interval;
var currentVideoPlayBackTime = 0;
var videoDuration = 0;
var totalPlayTime = 0;
var replayCount = 0;
var currentTime = 0;
var videoPlayed = 0;
var DOMAIN = $('meta[name=public_url]').attr("content"),
    API = {
        getAjax: function(url, params, callback){ 
          $.getJSON(DOMAIN+url, params, function(response){
              if(typeof callback != 'undefined')
                callback(response);
          });
        },
        postAjax: function (url, params, callback){
          $.ajax({
                url : public_url+url,
                type : 'POST',
                data : params,
                success : function(response) {
                    var data = JSON.parse(response);
                    if(typeof callback != 'undefined')
                  callback(data);
                },
            });
        }
    };

var FX = {
      gender: 0,
      genderString:'',
      PlanId:0,
      DateId:0,
      WorkOutId:0,
      keySearchTimeoutId:null,
      DifficultyLevels: ['Beginner', 'Intermediate', 'Advanced','Rehaberg'],
      /* Start : UI Setup (use FX.UI.UIname) */
      UI:{
          popupParams: { className: 'pt-popup', modal: true, width: 720 },
          aexTo: null, isMobile: false, currentPage: 1, searchScroll: null,
          start: function () {
            FX.UI.isMobile = jQuery(document).width() <= 480;
            var wid = jQuery(document).width();
            if(wid < 720){
                jQuery( "<style> .pt-popup .popup_content { width:"+(wid-24)+"px; margin:5px 3px 3px 3px; }</style>" ).appendTo( "head" );
                FX.UI.popupParams.width = wid - 20;
            }
          },
      },
      /* End : UI Setup */

    /* Start : Setter functions */

    /* Set gender */
    setGender: function(gender){
      if(typeof gender != 'undefined' && gender != ''){
          var gender = gender.toLowerCase();
          if(gender == 'male')
              var Gender = 2;
          else if(gender == 'female')
              Gender = 1;

          FX.genderString = gender;
          FX.gender = Gender;
        }
    },

    /* Set client plan id */
    setPlanId: function(Id){
      FX.PlanId = Id;
    },

    /* Set Date id */
    setDateId: function(Id){
      FX.DateId = Id;
    },

    /* Set workout id */
    setWorkOutId: function(Id){
      FX.WorkOutId = Id;
    },

    /* End : Setter functions */


    /* Start: FX object functions as helper function for actvity plan */

    /**
     * Get workout with exercise and randor on modal
     * @param
     * @return
    **/
    GetWorkoutWithExercise: function(modal){
      var formData = {'clientPlanId':FX.PlanId, 'eventDateId':FX.DateId};
      API.getAjax('activity/date/planDetail', formData, function(response){
        if(response.Status == 'success'){
          var htmlBody = {};
          var modalBody = $('#caledar-exe-accordion').find('.activity-video');
          modalBody.empty();
          var i = 1;
          var j = 1;
          $('#saveDateTrainingSeg').show();
          if(response.isActivityVideo == 1){
            $('.left-video-section').hide();
            var videoHtml = '<video width="400" controls>\
                              <source src="'+DOMAIN+'uploads/'+response.activityVideo.video+'" type="video/mp4">\
                              Your browser does not support HTML5 video.\
                            </video>';
            modalBody.append(videoHtml);
            modalBody.show();
            $('#saveDateTrainingSeg').hide();
            
          }else{
            $.each(response.workoutData,function(key,obj){
              var workoutName = obj.name;
              var workoutElement = $('#caledar-exe-accordion').find('#'+workoutName).closest('.panel').clone();
              $('#caledar-exe-accordion').find('#'+workoutName).closest('.panel').remove();
              $('#caledar-exe-accordion').prepend(workoutElement);
            });
            var videoSliderHtml = "";

            $.each(response.Exercise, function(key, workout){
              var videoAccrodianHtml = '';
              var count = 1;
              $.each(workout, function(exekey, exercise){
                  var rowHtml = "";
                  if(exercise.Resistance == null)
                      exercise.Resistance = '';
                  if(exercise.TempoDesc == null)
                      exercise.TempoDesc = '';
                  if(exercise.Type == 1){
                    if(exercise.isRest == '0'){
                      if(exercise.exercise_sets.length > 0){
                        $.each(exercise.exercise_sets,function(key,obj){
                          rowHtml += '<div class="col-md-10 setRow" data-set-duration="'+obj.estimatedTime+'" data-rest-duration="'+obj.restSeconds+'" data-is-finished="0">\
                            <div class="form-inline m-t-5 treningSegClsDate" data-exercise-id="'+exercise.ExeId+'" data-clientexe-id="'+exercise.ClientExeId+'" data-client-exe-set-id="'+obj.id+'">\
                                <div class="form-group">\
                                    <label for="exercSets" class="custom-label">SETS</label>\
                                    <input type="number" value="'+obj.sets+'" class="form-control custom-form-control numericField" id="exercSets" name="exercSets" min="0" required="required" readonly>\
                                </div>\
                                <div class="form-group">\
                                    <label for="exercReps" class="custom-label">REPETITION</label>\
                                    <input type="number" value="'+obj.repetition+'" class="form-control numericField custom-form-control" id="exercReps" name="exercReps" min="0" required="required">\
                                </div>\
                                <div class="form-group">\
                                    <label for="exercDur" class="custom-label">OR DURATION</label>\
                                    <input type="number" value="'+obj.estimatedTime+'" class="form-control numericField custom-form-control" id="exercDur" name="exercDur" min="0" required="required">\
                                </div>\
                                <div class="form-group">\
                                    <label for="exercResist" class="custom-label">RESISTANCE</label>\
                                    <input type="text" value="'+obj.resistance+'" class="form-control custom-form-control" id="exercResist" name="exercResist" required="required">\
                                </div>\
                                <div class="form-group">\
                                    <label for="exercTempo" class="custom-label">TEMPO</label>\
                                    <input type="text" value="'+obj.tempoDesc+'" class="form-control custom-form-control" id="exercTempo" name="exercTempo" required="required">\
                                </div>\
                                <div class="form-group">\
                                    <label for="exercRest" class="custom-label">REST</label>\
                                    <input type="number" value="'+obj.restSeconds+'" class="form-control numericField custom-form-control" id="exercRest" name="exercRest" min="0" required="required">\
                                </div>\
                            </div>\
                          </div>\
                          <div class="col-md-2 m-t-20" >\
                            <a href="#" class="btn btn-sm btn-default tooltips deleteDateExe" data-placement="top" data-entity="exercise">\
                              <i class="fa fa-times link-btn"></i>\
                            </a>\
                          </div>';
                        });
                      }else{
                        rowHtml = '<div class="col-md-10 setRow" data-set-duration="0" data-rest-duration="0" data-is-finished="0">\
                            <div class="form-inline m-t-5 treningSegClsDate" data-exercise-id="'+exercise.ExeId+'" data-clientexe-id="'+exercise.ClientExeId+'" data-client-exe-set-id="">\
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
                      var html = '<div class="panel panel-default" data-is-rest="'+exercise.isRest+'">\
                        <div class="panel-heading">\
                          <h4 class="panel-title">\
                            <a class="colorstyle collapsed" href="#content-'+key+exercise.ExeId+count+'" data-toggle="collapse" data-parent="#accordion'+j+'" aria-expanded="false">\
                              <div class="video-data">\
                                <div class="video-title">'+exercise.Name+'</div>\
                              </div>\
                            </a>\
                          </h4>\
                        </div>\
                        <div class="panel-colapse collapse" id="content-'+key+exercise.ExeId+count+'">\
                          <div class="panel-body">\
                            <div class="row">\
                            '+rowHtml+'\
                            </div>\
                          </div>\
                        </div>\
                      </div>';
                      videoAccrodianHtml += html;
                      var videoItemHtml = '<div class="item" data-is-video="1">\
                          <div class="video-loader"><h2>Rest</h2><div class="loaderinner"></div></div>\
                          <div class="video-duration">0</div>\
                          <video class="ban_video" id="video_'+key+exercise.ExeId+count+'" data-training-segment="'+key+'" data-exe-id="'+exercise.ExeId+'" data-count="'+count+'" width="100%" height="100%" data-is-rest="'+exercise.isRest+'">\
                            <source src="'+DOMAIN+'uploads/'+exercise.VideoUrl+'" type="video/mp4">\
                          </video>\
                          <div class="control">\
                            <div class="topControl">\
                              <div class="progress">\
                                <span class="bufferBar"></span>\
                                <span class="timeBar"></span>\
                              </div>\
                              <div class="time">\
                                <span class="current"></span> /\
                                <span class="duration"></span>\
                              </div>\
                            </div>\
                            <div class="btmControl">\
                              <div class="btnPlay btn" title="Play/Pause video"></div>\
                              <div class="btnStop btn" title="Stop video"></div>\
                              <div class="btnFS btn" title="Switch to full screen"></div>\
                              <div class="volume" title="Set volume">\
                                <span class="volumeBar"></span>\
                              </div>\
                              <div class="sound sound2 btn" title="Mute/Unmute sound"></div>\
                            </div>\
                          </div>\
                        </div>';
                      videoSliderHtml += videoItemHtml;
                    }else{
                      var html = '<div class="panel panel-default" data-is-rest="'+exercise.isRest+'">\
                        <div class="panel-heading">\
                          <h4 class="panel-title">\
                            <a class="colorstyle collapsed" href="#content-'+key+exercise.ExeId+count+'" data-toggle="collapse" data-parent="#accordion'+j+'" aria-expanded="false">\
                              <div class="video-data">\
                                <div class="video-title">'+exercise.Name+'</div>\
                              </div>\
                            </a>\
                          </h4>\
                        </div>\
                        <div class="panel-colapse collapse" id="content-'+key+exercise.ExeId+count+'">\
                          <div class="panel-body">\
                            <div class="row">\
                              <div class="col-md-12 restPanel" data-duration="'+exercise.RestSeconds+'">Time: '+exercise.RestSeconds+'</div>\
                            </div>\
                          </div>\
                        </div>\
                      </div>';
                      videoAccrodianHtml += html;
                      var videoItemHtml = '<div class="item" data-is-video="0">\
                          <div class="image-duration">0</div>\
                          <image src="'+public_url+'result/images/hand.png" data-training-segment="'+key+'" data-exe-id="'+exercise.ExeId+'" data-count="'+count+'" width="100%" height="100%" class="ban_video" data-is-rest="'+exercise.isRest+'" data-duration="'+exercise.RestSeconds+'">\
                        </div>';
                      videoSliderHtml += videoItemHtml;
                    }
                  }else{
                    if(exercise.isRest == '0'){
                      var innerHtml = '';
                      $.each(exercise.MovementData,function(index, obj){
                        innerHtml += '<div class="video-data">\
                                        <div class="video-title">'+obj.name+'</div>\
                                        <div class="video-value">'+obj.time+'</div>\
                                      </div>';
                      });
                      var html = '<div class="panel panel-default">\
                        <div class="panel-heading">\
                          <h4 class="panel-title">\
                            <a class="colorstyle collapsed" href="#content-'+key+exercise.ExeId+count+'" data-toggle="collapse" data-parent="#accordion'+j+'" aria-expanded="false">\
                              <div class="video-data">\
                                <div class="video-title">'+exercise.Name+'</div>\
                                <div class="video-value">'+exercise.EstimatedTime+'</div>\
                              </div>\
                            </a>\
                          </h4>\
                        </div>\
                        <div class="panel-colapse collapse" id="content-'+key+exercise.ExeId+count+'">\
                          <div class="panel-body">\
                          '+innerHtml+'\
                          </div>\
                        </div>\
                      </div>';
                      videoAccrodianHtml += html;
                      var videoItemHtml = '<div class="item" data-is-video="1">\
                          <video class="ban_video" controls="" data-training-segment="'+key+'" data-exe-id="'+exercise.ExeId+'" data-count="'+count+'" width="100%" height="400px" data-is-rest="'+exercise.isRest+'">\
                            <source src="'+DOMAIN+'uploads/'+exercise.VideoUrl+'" type="video/mp4">\
                          </video>\
                        </div>';
                        videoSliderHtml += videoItemHtml;
                    }else{
                      var html = '<div class="panel panel-default">\
                        <div class="panel-heading">\
                          <h4 class="panel-title">\
                            <a class="colorstyle collapsed" href="#content-'+key+exercise.ExeId+count+'" data-toggle="collapse" data-parent="#accordion'+j+'" aria-expanded="false">\
                              <div class="video-data">\
                                <div class="video-title">'+exercise.Name+'</div>\
                              </div>\
                            </a>\
                          </h4>\
                        </div>\
                        <div class="panel-colapse collapse" id="content-'+key+exercise.ExeId+count+'">\
                          <div class="panel-body">\
                            <div class="row">\
                              <div class="col-md-12">Time: '+exercise.RestSeconds+'</div>\
                            </div>\
                          </div>\
                        </div>\
                      </div>';
                      videoAccrodianHtml += html;
                      var videoItemHtml = '<div class="item" data-is-video="0">\
                      <div class="image-duration"></div>\
                          <image src="'+public_url+'result/images/hand.png" data-training-segment="'+key+'" data-exe-id="'+exercise.ExeId+'" data-count="'+count+'" width="100%" height="400px" class="ban_video" data-is-rest="'+exercise.isRest+'" data-duration="'+exercise.RestSeconds+'">\
                        </div>';
                      videoSliderHtml += videoItemHtml;
                    }
                  }
                  i = i+1;
                  count = count + 1;
              });
              if(key in htmlBody){
                var videoDataHtml = '<div class="video-details">\
                  <div class="panel-group" id="accordion'+j+'">\
                  '+videoAccrodianHtml+'\
                  <div>\
                <div>';
                htmlBody[key] += videoDataHtml;
              }
              else{
                var videoDataHtml = '<div class="video-details">\
                  <div class="panel-group" id="accordion'+j+'">\
                  '+videoAccrodianHtml+'\
                  <div>\
                <div>';
                htmlBody[key] = videoDataHtml;
              }
              j = j + 1;
            });
          
            $.each(htmlBody, function(workoutid, workouts){
                var appendArea = $('#'+workoutid).find('.panel-body');
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
              autoplay:false,
              margin:30,
              loop:false,
              dots:false,
              nav:true,
              items :1,
              video:true,
              responsive:{
                0:{
                  items:1,
                },
                768:{
                  items:1,
                },
                992:{
                  items:1,
                }
              }
            });
            $('#activityVideoCarousal').prepend('<div class="btn btn-primary" id="closefullScreenButton" type="button"> <span class="glyphicon glyphicon-resize-full"></span></div>');
            var trainingSegment = "";
            $("#activityVideoCarousal .owl-item").each(function(key,obj){
              if(key == 0){
                trainingSegment = $(this).find('.ban_video').data('training-segment');
              }
            });
            if($('#caledar-exe-accordion').find("#"+trainingSegment).closest('.panel').find('a[href="#'+trainingSegment+'"]').attr('aria-expanded') == 'false'){
              $('#caledar-exe-accordion').find("#"+trainingSegment).closest('.panel').find('a[href="#'+trainingSegment+'"]').trigger('click');
            }
            if($("#"+trainingSegment).find('.panel a.colorstyle:first-child').attr('aria-expanded') == 'false'){
              $("#"+trainingSegment).find('.panel a.colorstyle:first-child').trigger('click');
            }
            $("#"+trainingSegment).find('.panel a').each(function(key,obj){
              if(key == 0){
                $(this).removeClass('collapsed');
                $(this).attr('aria-expanded',"true");
              }
            });
            playVideoAccordingCondition();
            var videoSection = $("#activityVideoCarousal .owl-item .ban_video");
            setVideoControlInit();
            for (i = 0; i < videoSection.length; i++) {
              if(videoSection[i].dataset['isRest'] == '0'){
                videoSection[i].addEventListener('ended',myHandler,false);
              }
            }
          }
          $('#deleteClientClass').data('no-of-week',response.noOfWeek)
          toggleWaitShield('hide');
          modal.modal('show');
        }else{
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

    /**
     * Get exercise detail and randor on modal
     * @param
     * @return
    **/
    GetExerciseDetail: function(exeid, modal){
      $.ajax({
        url : public_url+'CustomPlan/SearchExercisesById/'+exeid,
        type : 'GET',
        success : function(response) {
          var data = JSON.parse(response);
          if(data.status == 'success'){
            var imgArea = modal.find('#exe-img-area');
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
                modal.find('#'+key).html(value)
              })
            }
            modal.modal('show');
            toggleWaitShield("hide");
          }
        },
      });
    }, 

    /**
     * Get Exercise 
     * @param continue or not
     * @return
    **/
    GetExercises: function(contnue){
      var exerciseList = $('#exerciseList'),
          addExerciseModal = $('#addexercise'),
          loading = exerciseList.next();

      loading.show();
      var pageNumb,
          iss = FX.UI.searchScroll;

      if(typeof contnue != 'undefined' && contnue){
        pageNumb = ++FX.UI.currentPage;
        //iss.enabled = false;
      }
      else{
        pageNumb = FX.UI.currentPage = 1;
        //iss.enabled = true;
        exerciseList.html('')
      }

      var options = {workoutId:FX.WorkOutId, perPage:10, pageNumber:pageNumb};
      /*var favKase = detectHeartCase(addExerciseModal.find('#favSearch'));
      if(favKase == 'add')
        options.myFavourites = false;
      else if(favKase == 'remove')
        options.myFavourites = true;*/

      options.keyWords = addExerciseModal.find('#keySearch').val();
      options.bodypart = addExerciseModal.find('#muscle_group').val();
      options.ability = addExerciseModal.find('#ability').val();
      options.equipment = addExerciseModal.find('#equipment').val();
      options.category = addExerciseModal.find('#category').val();
      options.movement_type = addExerciseModal.find('#movement_type').val();
      options.movement_pattern = addExerciseModal.find('#movement_pattern').val();

      API.getAjax('CustomPlan/SearchExercises', options, function(response){
          var exercises = response.Exercises;
          exerciseList.empty();
          if(exercises == undefined){
            //iss.enabled = false;
            var text = prepareNotific('warning', 'No '+(pageNumb == 1?'':'more')+' exercise found.');
            exerciseList.append('<div class="col-md-12">'+text+'</div>');
            loading.hide();
          }
          else if(exercises.length){
            var html = '';
            exerciseList.empty();
            $.each(exercises, function(index, value){
                var desc = value.ExerciseDesc;
                if(desc.length > 15)
                  var descUi = desc.substring(0, 15)+'...';
                else
                  var descUi = desc;

              html += '<div class="col-md-4">\
                          <a data-toggle="modal" class="lungemodalCls" data-exercise-name="'+value.name+'" data-exercise-id='+value.id+'>\
                            <div class="panel panel-white m-b-0">\
                              <div class="panel-body">\
                                <div class="row">\
                                  <div class="col-md-5">\
                                    <img src="'+value.img+'" class="mw-60p">\
                                  </div>\
                                  <div class="col-md-4 p-x-0">\
                                  <h5> '+value.name+' </h5>\
                                    <small>'
                                      +descUi
                                      +'<br/>\
                                      <b>'
                                        +FX.DifficultyLevels[value.DifficultyLevel]
                                      +'</b> \
                                    </small>\
                                  </div>\
                                  <div class="col-md-3">\
                                    <button class="btn btn-xs btn-primary m-b-2 toggle-fav" data-is-fav="'+value.IsFav+'">'
                                      +((value.IsFav == true)?'<i class="fa fa-heart"></i>':'<i class="fa fa-heart-o"></i>')
                                    +'</button>\
                                    <button class="btn btn-xs btn-primary add-exercise">\
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
            var text = prepareNotific('warning', 'No '+(pageNumb == 1?'':'more')+' exercise found.');
            exerciseList.append('<div class="col-md-12">'+text+'</div>');
          }
          loading.hide();
          toggleWaitShield("hide");
      });
      FX.UI.currentPage++;
    },

    /**
     * Add Exerice
     * @param
     * @return
    **/
    AddExercise: function(exerice_id, btn,isVideo = "0"){
      if(isVideo){
        var modal = $('#addexercise'),
        sourceLink = modal.find('[data-exercise-id="'+exerice_id+'"]').find('.toggle-video'),
        formData = {'exerice_id':exerice_id, 'plan_id':FX.PlanId, 'date_id':FX.DateId, 'workout_id':FX.WorkOutId,'isVideo': isVideo};
        API.postAjax('activity/exercise/add', formData, function(response){
          toggleWaitShield("hide");
          if(response.status == "success"){
            sourceLink.children().removeClass('fa-plus').addClass('fa-check');

            if(btn.hasClass('toggle-video')){
              btn.closest('.modal').modal('hide');
            }

            if(response.record == 'new')
              FX.GetWorkoutWithExercise($('#activityModal'));   
          }
        });
      }else{
        var modal = $('#addexercise'),
        sourceLink = modal.find('[data-exercise-id="'+exerice_id+'"]').find('.add-exercise'),
        formData = {'exerice_id':exerice_id, 'plan_id':FX.PlanId, 'date_id':FX.DateId, 'workout_id':FX.WorkOutId,'isVideo':isVideo};

        API.postAjax('activity/exercise/add', formData, function(response){
          if(response.status == "success"){
            sourceLink.children().removeClass('fa-plus').addClass('fa-check');

            if(btn.hasClass('toggle-exercise')){
              btn.closest('.modal').modal('hide');
            }

            if(response.record == 'new')
              FX.GetWorkoutWithExercise($('#activityModal'));

            toggleWaitShield("hide");   
          }
        })
      }
    },

    /**
     * Save Client calendar program
     * @param alert status from AlertPlanSave function
     * @return 
    **/
    SaveCalendarPlan: function (status){
        var modal = $('#activityModal'),
            formData = {},
            form = modal.find('.treningSeg-date-form'),
            isFormValid = form.valid();
        if(isFormValid){
            var i = 0;
            form.find('.treningSegClsDate').each(function(){
              var fieldName = {};
              clientexe_id = $(this).data('clientexe-id');
              clientExeSetId = $(this).data('client-exe-set-id');
              fieldName['clientExeSetId'] = clientExeSetId;
              fieldName['clientExeId'] = clientexe_id;
              $(this).find('input').each(function(){
                var inputField = $(this),
                    name = inputField.attr('name');
                if(typeof clientexe_id != 'undefined'){
                  fieldName[name] = inputField.val();
                }
              });
              formData[i] = fieldName;
              i = i + 1;
            });
            if(status)
                formData['status'] = 'complete';
            else
                formData['status'] = 'incomplete';
            API.postAjax('activity/clientPlan/edit', formData, function(response){
              if(response.status == "success"){
                    modal.modal('hide');   
                }
            }) 
        }
    },

    /**
     * Delete client Exercise from calendar
     * @param deleted element
     * @return 
    **/
    DeleteClientExe: function (elem){
        var row = elem.closest('.treningSegClsDate'),
            clientExeId = row.data('clientexe-id'),
            formData = {};

        formData['clientExeId'] = clientExeId; 
        API.getAjax('activity/clientPlan/delete', formData, function(response){
            if(response.status == 'success'){
                row.remove();
            }
        })
    },

    /**
     * Ask plan complete or incomplete befor savining palan
     * @param type(success,warning, etc), message, collback function
     * @return return confirm or not
    **/
    AlertPlanSave: function (type, msg, callback){
      var text = "<a class='btn btn-default swal-close-btn w245'>Cancel</a>";
        swal({
            title: msg,
            type: type,
            html: true,
            showCancelButton: (typeof callback != 'undefined'? true : false),
            confirmButtonColor: "#d43f3a",
            confirmButtonText: (typeof callback != 'undefined'? "Yes, completed!" : "Ok"),
            cancelButtonText: "Save as incomplete",
            allowOutsideClick: true,
            customClass: 'delete-alert',
            text:(typeof callback != 'undefined'? text : ''),
        }, 
        function(isConfirm){
            if(typeof callback != 'undefined')
                callback(isConfirm);
        });
    },

    /**
     * For confirmation befor delete
     * @param delete element, call back function, warning text
     * @return response
    **/
    ConfirmDelete: function (elem, callback, warningText){
      var entity = elem.data('entity');
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
      function(){
          if(typeof callback != 'undefined')
            callback(elem);
      });
    },

    /**
     * Toggel heart symbole
     * @param
     * @Return
    **/
    ToggleHeart: function(elem, kase){
      var ital = elem.children();
      if(typeof kase == 'undefined'){
        if(ital.hasClass('fa-heart-o'))
          kase = 'add'
        else
          kase = 'remove'
      }

      if(kase == 'add'){
        ital.removeClass('fa-heart-o').addClass('fa-heart')
        return true;
      }
      else{
        ital.removeClass('fa-heart').addClass('fa-heart-o')
        return false;
      }
    },

    /** 
     * Start: Infinite Scroll function
     * @param
     * @return
    **/
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

function playVideoAccordingCondition(){
  $("#activityVideoCarousal .owl-item").each(function(key,obj){
    var $this = $(this);
    if(key == 0){
      if($(this).find('.ban_video').data('is-rest') == '0'){
        $(this).find('.ban_video')[0].play();
        $(".play-bt").hide();
        $(".pause-bt").show();
      }else{
        var timeleft =  $(this).find('.ban_video').data('duration');
        interval = setInterval(function(){
          if(timeleft <= 0){
            clearInterval(interval);
            myHandler();
          }
          $this.find('.image-duration').text(timeleft);
          timeleft -= 1;
        }, 1000);
      }
    }
  });
  var videoPlayedTime = 0;
  $(".ban_video").on(
    "timeupdate", 
    function(event){
      videoPlayedTime = parseInt(this.currentTime);
      videoDuration = parseInt(this.duration);
      var activeElement = $("#activityVideoCarousal .owl-item.active");
      var trainingSegment = activeElement.find('.ban_video').data('training-segment');
      var exeId = activeElement.find('.ban_video').data('exe-id');
      var count = activeElement.find('.ban_video').data('count');
      var setPanel = $("#"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]');
      var length = setPanel.closest('.panel-default').find('.setRow').length;
      if(currentTime != videoPlayedTime){
        currentTime = videoPlayedTime;
        if(videoPlayedTime == videoDuration){
          console.log('here');
          replayCount = replayCount + 1;
        }else{
          currentVideoPlayBackTime = ((replayCount * videoDuration)  + videoPlayedTime) - videoPlayed;
        }
      }
      setPanel.closest('.panel-default').find('.setRow').each(function(key,element){
        $this = $(this);
        if($(this).data('is-finished') == '0'){
          var playTime = parseInt($(this).data('set-duration'));
          if(playTime > 0){
            if(playTime == currentVideoPlayBackTime){
              var videoElement = activeElement.find('.ban_video')[0];
              videoElement.pause();
              currentVideoPlayBackTime = 0;
              activeElement.find('.item .video-duration').text('0');
              var timeleft = $(this).data('rest-duration');
              interval = setInterval(function(){
                activeElement.find('.video-loader').show();
                if(timeleft <= 0){
                  activeElement.find('.video-loader').hide();
                  clearInterval(interval);
                  $this.data('is-finished','1');
                  replayCount = 0;
                  if(key === (length - 1)){
                    if(!$('.owl-next').hasClass('disabled')){
                      $('.owl-next').trigger('click');
                    }
                    videoPlayed = 0;
                  }else{
                    videoElement.play();
                    videoPlayed = videoPlayedTime;
                  }
                }
                activeElement.find('.item .video-duration').text(timeleft);
                timeleft -= 1;
              }, 1000);
            }else{
              activeElement.find('.item .video-duration').text(playTime - currentVideoPlayBackTime);
            }
            return false;
          }
        }
      });
        
  });
}


/**
 * Get All Activity Video
 */
$('#addexercise a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  var target = $(e.target).attr("href") // activated tab
  if(target == '#addexerciseVideo'){
    getAllActivityVideo();
  }
});

function getAllActivityVideo(filter = ''){
  toggleWaitShield("show");
  var formData = {};
  if(filter != ''){
    formData['filter'] = filter;
  }
  $.get(public_url+'CustomPlan/activityViedos',formData,function(response){
    var videoHtml = $('.exerciseVideoListing');
    videoHtml.empty();
    var videoListingHtml = '';
    $.each(response.videos,function(key,value){
      videoListingHtml += '<div class="col-md-4">\
                      <a data-toggle="modal" class="lungemodalCls" data-type="video" data-exercise-name="'+value.title+'" data-exeid="'+value.id+'" data-video-url="'+public_url+'uploads/'+value.video+'">\
                          <div class="panel panel-white m-b-0">\
                              <div class="row">\
                                  <div class="col-md-5">\
                                    <img src="'+public_url+'assets/plugins/fitness-planner/images/video-icon.png" class="mw-80p">\
                                  </div>\
                                  <div class="col-md-4 nip-x-0">\
                                      <h5> '+value.title+' </h5>\
                                  </div>\
                                  <div class="col-md-3" data-exercise-type-id="'+value.workout_id+'" data-exercise-id="'+value.id+'">\
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
  },'json');
}

$('body').on('change','#filterVideo',function(){
  var filter = $(this).val();
  getAllActivityVideo(filter);
})

function myHandler() {
  if(!$('.owl-next').hasClass('disabled')){
    if(currentVideoPlayBackTime == 0){
      $('.owl-next').trigger('click');
    }else{
      var activeElement = $("#activityVideoCarousal .owl-item.active");
      playVideo(activeElement);
    }
  }else{
    if(currentVideoPlayBackTime != 0){
      var activeElement = $("#activityVideoCarousal .owl-item.active");
      if(activeElement.find('.ban_video').data('is_rest') == 0){
        playVideo(activeElement);
      }
    }
  }
}

function playVideo(activeElement){
  var isRest = activeElement.find('.ban_video').data('is-rest');
  if(isRest == '0'){
      var videoElement = activeElement.find('.ban_video')[0];
      if(videoElement != undefined){
          videoElement.currentTime = 0;
          videoElement.play();
      }
      $(".play-bt").hide();
      $(".pause-bt").show();
  }else{
      var timeleft = activeElement.find('.ban_video').data('duration');
      interval = setInterval(function(){
      if(timeleft <= 0){
          clearInterval(interval);
          myHandler();
      }
      activeElement.find('.image-duration').text(timeleft);
      timeleft -= 1;
      }, 1000);
  }
}

function setVideoControlInit(){
  $("#activityVideoCarousal .owl-item .item").each(function(){
    if($(this).data('is-video') == '1'){
      var activeElement = $(this);
      var videoId = $(this).find('.ban_video').attr('id');
      var video = $('#'+videoId);
    
      //remove default control when JS loaded
      // video[0].removeAttribute("controls");
      activeElement.find('.control').show().css({'bottom':-40});
      activeElement.find('.loading').fadeIn(500);
      activeElement.find('.caption').fadeIn(500);
    
      //before everything get started
      video.on('loadedmetadata', function() {
        activeElement.find('.caption').animate({'top':-45},300);
          
        //set video properties
        activeElement.find('.current').text(timeFormat(0));
        activeElement.find('.duration').text(timeFormat(video[0].duration));
        updateVolume(0, 0.7);
          
        //start to get video buffering data 
        setTimeout(startBuffer, 150);
          
        //bind video events
        activeElement
        .append('<div id="init"></div>')
        .hover(function() {
          activeElement.find('.control').stop().animate({'bottom':0}, 500);
          activeElement.find('.caption').stop().animate({'top':0}, 500);
        }, function() {
          if(!volumeDrag && !timeDrag){
            activeElement.find('.control').stop().animate({'bottom':-40}, 500);
            activeElement.find('.caption').stop().animate({'top':-45}, 500);
          }
        })
        .on('click', function() {
          $('#init').remove();
          activeElement.find('.btnPlay').addClass('paused');
          $(this).unbind('click');
          video[0].play();
        });
        $('#init').fadeIn(200);
      });
      
      //display video buffering bar
      var startBuffer = function() {
        var currentBuffer = video[0].buffered.end(0);
        var maxduration = video[0].duration;
        var perc = 100 * currentBuffer / maxduration;
        activeElement.find('.bufferBar').css('width',perc+'%');
          
        if(currentBuffer < maxduration) {
          setTimeout(startBuffer, 500);
        }
      };	
      
      //display current video play time
      video.on('timeupdate', function() {
        var currentPos = video[0].currentTime;
        var maxduration = video[0].duration;
        var perc = 100 * currentPos / maxduration;
        activeElement.find('.timeBar').css('width',perc+'%');	
        activeElement.find('.current').text(timeFormat(currentPos));	
      });
      
      //CONTROLS EVENTS
      //video screen and play button clicked
      // video.on('click', function() { playpause(); } );
      // activeElement.find('.btnPlay').on('click', function() { playpause(); } );
      // var playpause = function() {
      //   if(video[0].paused || video[0].ended) {
      //     $('.btnPlay').addClass('paused');
      //     video[0].play();
      //   }
      //   else {
      //     $('.btnPlay').removeClass('paused');
      //     video[0].pause();
      //   }
      // };
      
      //speed text clicked
      activeElement.find('.btnx1').on('click', function() { fastfowrd(this, 1); });
      activeElement.find('.btnx3').on('click', function() { fastfowrd(this, 3); });
      var fastfowrd = function(obj, spd) {
        activeElement.find('.text').removeClass('selected');
        $(obj).addClass('selected');
        video[0].playbackRate = spd;
        video[0].play();
      };
      
      //stop button clicked
      $('.btnStop').on('click', function() {
        activeElement.find('.btnPlay').removeClass('paused');
        updatebar($('.progress').offset().left);
        video[0].pause();
      });
      
      //fullscreen button clicked
      $('.btnFS').on('click', function() {
        if($.isFunction(video[0].webkitEnterFullscreen)) {
          video[0].webkitEnterFullscreen();
        }	
        else if ($.isFunction(video[0].mozRequestFullScreen)) {
          video[0].mozRequestFullScreen();
        }
        else {
          alert('Your browsers doesn\'t support fullscreen');
        }
      });
      
      //light bulb button clicked
      $('.btnLight').click(function() {
        $(this).toggleClass('lighton');
        
        //if lightoff, create an overlay
        if(!$(this).hasClass('lighton')) {
          $('body').append('<div class="overlay"></div>');
          activeElement.find('.overlay').css({
            'position':'absolute',
            'width':100+'%',
            'height':$(document).height(),
            'background':'#000',
            'opacity':0.9,
            'top':0,
            'left':0,
            'z-index':999
          });
          activeElement.css({
            'z-index':1000
          });
        }
        //if lighton, remove overlay
        else {
          activeElement.find('.overlay').remove();
        }
      });
      
      //sound button clicked
      activeElement.find('.sound').click(function() {
        video[0].muted = !video[0].muted;
        $(this).toggleClass('muted');
        if(video[0].muted) {
          activeElement.find('.volumeBar').css('width',0);
        }
        else{
          activeElement.find('.volumeBar').css('width', video[0].volume*100+'%');
        }
      });
      
      //VIDEO EVENTS
      //video canplay event
      video.on('canplay', function() {
        activeElement.find('.loading').fadeOut(100);
      });
      
      //video canplaythrough event
      //solve Chrome cache issue
      var completeloaded = false;
      video.on('canplaythrough', function() {
        completeloaded = true;
      });
      
      //video ended event
      video.on('ended', function() {
        activeElement.find('.btnPlay').removeClass('paused');
        video[0].pause();
      });

      //video seeking event
      video.on('seeking', function() {
        //if video fully loaded, ignore loading screen
        if(!completeloaded) { 
          activeElement.find('.loading').fadeIn(200);
        }	
      });
      
      //video seeked event
      video.on('seeked', function() { });
      
      //video waiting for more data event
      video.on('waiting', function() {
        activeElement.find('.loading').fadeIn(200);
      });
      
      //VIDEO PROGRESS BAR
      //when video timebar clicked
      var timeDrag = false;	/* check for drag event */
      $('.progress').on('mousedown', function(e) {
        timeDrag = true;
        updatebar(e.pageX);
      });
      $(document).on('mouseup', function(e) {
        if(timeDrag) {
          timeDrag = false;
          updatebar(e.pageX);
        }
      });
      $(document).on('mousemove', function(e) {
        if(timeDrag) {
          updatebar(e.pageX);
        }
      });
      var updatebar = function(x) {
        var progress = activeElement.find('.progress');
        
        //calculate drag position
        //and update video currenttime
        //as well as progress bar
        var maxduration = video[0].duration;
        var position = x - progress.offset().left;
        var percentage = 100 * position / progress.width();
        if(percentage > 100) {
          percentage = 100;
        }
        if(percentage < 0) {
          percentage = 0;
        }
        activeElement.find('.timeBar').css('width',percentage+'%');	
        video[0].currentTime = maxduration * percentage / 100;
      };

      //VOLUME BAR
      //volume bar event
      var volumeDrag = false;
      activeElement.find('.volume').on('mousedown', function(e) {
        volumeDrag = true;
        video[0].muted = false;
        $('.sound').removeClass('muted');
        updateVolume(e.pageX);
      });
      $(document).on('mouseup', function(e) {
        if(volumeDrag) {
          volumeDrag = false;
          updateVolume(e.pageX);
        }
      });
      $(document).on('mousemove', function(e) {
        if(volumeDrag) {
          updateVolume(e.pageX);
        }
      });
      var updateVolume = function(x, vol) {
        var volume = activeElement.find('.volume');
        var percentage;
        //if only volume have specificed
        //then direct update volume
        if(vol) {
          percentage = vol * 100;
        }
        else {
          var position = x - volume.offset().left;
          percentage = 100 * position / volume.width();
        }
        
        if(percentage > 100) {
          percentage = 100;
        }
        if(percentage < 0) {
          percentage = 0;
        }
        
        //update volume bar and video volume
        activeElement.find('.volumeBar').css('width',percentage+'%');	
        video[0].volume = percentage / 100;
        
        //change sound icon based on volume
        if(video[0].volume == 0){
          activeElement.find('.sound').removeClass('sound2').addClass('muted');
        }
        else if(video[0].volume > 0.5){
          activeElement.find('.sound').removeClass('muted').addClass('sound2');
        }
        else{
          activeElement.find('.sound').removeClass('muted').removeClass('sound2');
        }
        
      };

      //Time format converter - 00:00
      var timeFormat = function(seconds){
        var m = Math.floor(seconds/60)<10 ? "0"+Math.floor(seconds/60) : Math.floor(seconds/60);
        var s = Math.floor(seconds-(m*60))<10 ? "0"+Math.floor(seconds-(m*60)) : Math.floor(seconds-(m*60));
        return m+":"+s;
      };
    }
  });
}

$('body').on('click','#fullScreenButton',function(){
  $('#activityVideoCarousal').addClass('full');
  var owlEl = document.getElementById('activityVideoCarousal');
  console.log(owlEl);
  if (owlEl.requestFullscreen) {
    owlEl.requestFullscreen();
  }
});

$('body').on('click','#closefullScreenButton',function(){
  $('#activityVideoCarousal').removeClass('full');
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) { /* Firefox */
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE/Edge */
    document.msExitFullscreen();
  }
});

// video.on('click', function() { playpause(); } );
$('body').on('click','.btnPlay', function() {
  var activeElement = $("#activityVideoCarousal .owl-item.active");
  console.log('here');
  var video = activeElement.find('.ban_video');
  if(video[0].paused || video[0].ended) {
    $(this).removeClass('paused');
    video[0].play();
  }
  else {
    $(this).addClass('paused');
    video[0].pause();
  }
});

$('body').on('click','.cancelSwal',function(){
  swal.close();
})
$('body').on('click','#fullScreenButton',function(){
    $('div.video-duration').addClass('font_size60');
});

$('body').on('click','#closefullScreenButton',function(){
  $('div.video-duration').removeClass('font_size60');
})

$('body').on('click','.pdf-popup-open',function(){
  var url= $(this).attr('data-url');
  // if($(window).width() <= 767) {
  if($(window).width() <= 800) {
      url="https://docs.google.com/viewer?url=" + url + "&embedded=true";
    }
  $('#myModalPdf').modal('show');
  $('.pdf-href').attr('src', url);
});