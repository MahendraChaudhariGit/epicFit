
var DOMAIN = $('meta[name=public_url]').attr("content"),
    API = {
      URL: DOMAIN+'Planner/',
      CUSTOM_PROGRAM_URL: DOMAIN+'CustomPlan/',

      ajaxSkeleton: function(url, params, cb){ 
        $.getJSON(url, params, function(data){
          if(typeof cb != 'undefined')
            cb(data);
        });
      },

      ajax: function(url, params, cb){ 
        $.getJSON(API.URL+url, params, function(data){
          if(typeof cb != 'undefined')
            cb(data);
        });
      },

      customPlanAjax: function(url, params, cb){
       API.ajaxSkeleton(API.CUSTOM_PROGRAM_URL + url, params, cb);
      }
    };

var FX = {
  SessionGuid: 'd95a16d3-3a54-4598-a55a-af4abc086a29',
  gender: 0,
  genderString:'',
  clientid:0,
  ClientPlanId:0,
  ExersiseId:0,
  EditWorkoutId:0,
  PlanType:0,
  IMAGE_DIR_URL:'',
  NO_IMAGE: '',
  DifficultyLevels: {1:'Rehabilitation',2:'Beginner',3:'Intermediate',4:'Advanced'},
  ExBodyParts: ['Shoulders', 'Chest', 'Anterior Arms', 'Core', 'Forearms', 'Anterior Thigh', 'Neck and Upper Back', 'Posterior Arms', 'Lats and Middle Back', 'Lower Back', 'Hips and Glutes', 'Posterior Thigh', 'Lower Leg'],
  

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

  /*Set plan type */
  setPlanType: function(plan_type){
    FX.PlanType = parseInt(plan_type);
  },
  
  /*Set client Id */
  setClientid: function(clientid){
    FX.clientid = parseInt(clientid);
  },

  /* Set client plan id */
  setClientPlanId: function(clientPlanId){
    FX.ClientPlanId = parseInt(clientPlanId);
  },

  /* Set Exersise id */
  setExersiseId: function(exe_id){
    FX.ExersiseId = parseInt(exe_id);
  },

  /*Set Edit plan_workout_exersise_id */
  setPlanWorkExeId: function(id){
    FX.EditWorkoutId = parseInt(id);
  },

  /* End : Setter functions */

  /* Start: FX object functions as helper function for actvity plan */

  /**
   * Display all created program
   * @param client id
   * @return
  **/
  loadProgramsList: function(programChoosed){
    var param = { Clientid : FX.clientid};
    if(typeof programChoosed != 'undefined')
        param['PlanType'] = programChoosed;

    API.customPlanAjax('GetUsersPlans', param, function(response){
      $('#design-program-datatable').DataTable().destroy();
      $('.pts .my_pt tbody').empty()

      var plans = response.Plans/*,
          listProgramdata = []*/;
      if(plans != undefined){
          for(var i=0; i<plans.length; i++){
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
  loadProgram: function(id, callback){
      data = {fixedProgramId:id};
    API.customPlanAjax('GetUsersPlanDetail', data, callback); 
  },
  /* Start: Load Existing program */

  /** 
   * display design program list
   * @param program
   * @return list program
  **/
  addProgramInList: function(p){
    var name = $('input[name="fit-clientName"]').val();
    if(typeof name == 'undefined' || name == 'undefined')
        var name_ad = 'Admin';
    else
        name_ad = name;

    var timeStamp = parseInt(p.DateChanged.substring(p.DateChanged.lastIndexOf("(")+1,p.DateChanged.lastIndexOf(")")), 10);
    jQuery(FX.UI.isMobile?'<a href="javascript:void(0)">' :'<tr>')
        .attr({'data-id':p.FixedProgramId, 'data-desc':p.ProgramDesc, 'data-name':p.ProgramName})
        .append('<td>' + p.ProgramName + '</td>\
            <td>' + moment(timeStamp).format('ddd, D MMM YYYY') + '</td>\
            <td class="hidden-xs">' +name_ad+ '</td>\
            <td class="center">\
                <a href="#" class="btn btn-xs btn-primary tooltips open-step" data-placement="top" data-original-title="View" data-target-step="trainingSegment">\
                    <i class="fa fa-share link-btn"></i>\
                </a>\
                <a class="btn btn-xs btn-primary tooltips customPlanUpdateModalCls" href="#" data-placement="top" data-original-title="Edit"  >\
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
  getFilterPlan: function(formData, callback){
    formData['gender'] = FX.gender;
    formData['plan_type'] = FX.PlanType;
    API.ajax('GetFilterPlan', formData, callback);
  },

  /** 
   * create program(program design)
   * @param form
   * @return response
  **/
  createProgram: function(f){
      var formData={},
          pn = f.pname.value;

          formData.image = '';
          formData.curr_ability = '';
          formData.Gender = FX.gender;
          formData.name = pn;
          formData.description = '';
          formData.snippet = '';
          formData.Clientid = FX.clientid;

      if(pn.replace(/ /g,'').length > 0){
          API.customPlanAjax('CreateProgram', formData, function(response){
              if(response.MessageId == 0){
                  FX.ClientPlanId = response.Program.FixedProgramId;
                  f.pname.value = '';
                  FX.loadProgramsList(FX.PlanType);
                  resetTrainingSegments();
                  openStep($(f.btn));
              }
          });
      }
      else 
          FX.showErrorDiv('.pts .error-msg');
  },

  /* Start: Add exercise to program */
  addExToProgram: function(exercise, callback){
    API.customPlanAjax('AddExerciseToProgram', exercise, callback);
  },
  /* End: Add exercise to program */

  /* start: Save plan to API */
  planPreview: function(data, callback){
    toggleWaitShield("show");
    API.customPlanAjax('PlanPreview', data, callback);
  },
  /* end: Save plan to API */

  /* start: Save plan to API */
  savePlan: function(data, callback){
    data.GetPreWritten = false;
    data.Clientid = FX.clientid; 
    data.ClientPlanId = FX.ClientPlanId;
    data.PlanType = FX.PlanType;

    API.ajax('SavePlan', data, callback);
  },
  /* end: Save plan to API */

  /** Save fielter for generate program section
   * @param form data
   * @return 
  **/
  addFilterToGenPlan: function(formData, callback){
    formData.exercise_id = FX.ExersiseId;
    API.customPlanAjax('AddFilterToGenPlan', formData, callback)
  },

  /* start: Calculate work out day pattern from days */
  calcWorkoutdaysPattern: function(checkboxes){
    var dayPattern = [];
    checkboxes.each(function(){
      if($(this).is(':checked'))
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
  minsToHourMin: function(mins){
    var hours = Math.floor(mins / 60),
        minutes = Math.floor(mins % 60);

    return hours + ':' + ((minutes < 10) ? '0' + minutes : minutes);
  },
  /* end: Convert minutes to hour and minutes */

  /* start: Convert numeric string such as '10' to numeric eg: 10 */
  numericStringToInt: function(numericstring){
    var parsed = parseInt(numericstring, 10);
    if(isNaN(parsed))
      return 0;
    return parsed;
  },
  /* end: Convert numeric string such as '10' to numeric eg: 10 */

  /* start: Set value to 0 if provided value is empty or zero string '0' */
  reinitIfNotVal: function(newVal){
    if(!newVal || newVal == 0)
      return 0;
    return newVal;
  },
  /* end Set value to 0 if provided value is empty */

  /* start: Calculate per day workout time */
  calcPerDayWorkoutTime: function(minsPerWeek, daysTraining){
    if(!daysTraining)
      var minsPerDay = 0;
    else
      var minsPerDay = Math.floor(minsPerWeek / daysTraining);

    return FX.minsToHourMin(minsPerWeek) + " (~" + minsPerDay + " mins daily)";
  },
  /* end: Calculate per day workout time */

  /* start: Determine alerts background color based on the type */
  getAlertsColor: function(type){
    if(type == 'error')
      return 'danger';
    if(type == 'success')
      return 'success';
    if(type == 'warning')
      return 'warning';
  },
  /* end: Determine alerts background color based on the type */

  /* start: Clear notification messages */
  clearNotific: function(parentElem, alertGroup){
    if(typeof parentElem == 'undefined')
      parentElem = $('body');

    if(typeof alertGroup == 'undefined')
      parentElem.children('.alert').remove();
    else
      parentElem.find('.'+alertGroup).remove();
  },
  /* start: Clear notification messages */

  /* start: Prepare html for notification messages */
  prepareNotific: function(type, message, alertGroup){
    if(typeof alertGroup == 'undefined')
      alertGroup = '';

    return '<div class="alert alert-'+FX.getAlertsColor(type)+' '+alertGroup+'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+message+'</div>';
  },
  /* end: Prepare html for notification messages */

  /* start: Prepare html for alert messages */
  prepareAlert: function(type, message, alertGroup){
    if(typeof alertGroup == 'undefined')
      alertGroup = '';

    return '<div class="alert alert-'+FX.getAlertsColor(type)+' '+alertGroup+'">'+message+'</div>';
  },
  /* end: Prepare html for alert messages */

  /* start: Get slider value */
  getSliderValue: function(slider){
    return slider.labeledslider('option', 'value');
  },
  /* end: Get slider value */

  /* Start: Show error div */
  showErrorDiv: function(selector){
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