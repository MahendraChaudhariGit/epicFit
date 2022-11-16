<div class="step data-step milestoneData" data-step="14" data-value="0" >

                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="13"><span>13.</span></div>
                        <label class="steps-head">What <strong>milestones</strong> do I need to accomplish before I reach my <strong>EPIC</strong> Goal? </label>
                      </div>

                      <div class="tooltip-sign mb-10">
                        <a href="javascript:void(0)" class="goal-step tooltip-diff" 
                           data-message="Milestones can be seen as mini <span style='color:#f64c1e;'><b>EPIC</b> Goals</span> which you set and need to achieve along the way. These are a set target and on a set date, to ensure you reach these milestones we recommend setting simple and achievable milestones. 
                           <br/><br/>
                           Milestones help breakdown your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> into bite sized chunks giving you a less daunting and more achievable end result. If milestones are not met as required to meet your individual goal timeline, your timeline or priority of tasks can be adjusted as required without effecting your positive outlook on the overall goal and your ability to achieve it.
                            <br/><br/>
                           Below are brief examples of milestones:
                            <br/><br/>
                               • Measurements cm or inches, dropping to a specific weight or loss of a specific weight in increments<br/>
                               • Body Fat Percentages, dropping to a specific BFP or loss of a specific BFP in increments<br/>
                               • Clothing sizes in inches or dress size, dropping to a specific size or loss of a specific size in increments"
                           data-message1="Milestones can be seen as mini <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> which you set and need to achieve along the way. These are a set target and on a set date, to ensure you reach these milestones we recommend setting simple and achievable milestones. 
                            <br/><br/>
                           Below are brief examples of milestones:
                            <br/><br/>
                           <b>Measurements -</b> cm or inches, dropping to a specific weight or loss of a specific weight in increments<br/>
                           <b>Body Fat Percentages -</b> dropping to a specific BFP or loss of a specific BFP in increments<br/>
                           <b>Clothing sizes -</b> in inches or dress size, dropping to a specific size or loss of a specific size in increments
                           "><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                    <div class="row dd-item m-30">
                      <div class="col-xs-5">
                         <div class="form-group">
                        <input type="text" class="form-control milestones-name" id="Milestones"  name="Milestones" >
                        </div>
                      </div>
                      <div class="col-xs-5">
                        <div class="form-group">
                         <input type="text" id="milestones-date-pickup" class="form-control milestones-date milestone_date create-milestones-date" name ="milestones-date">
                      </div>
                      </div>
                    </div>
                     <div class="row">
                     <div class="form-group new_Btn_milestone col-xs-10" data-new-milestones-id="">
                        <label class="btn btn-primary"> 
                        <span> Add Milestone</span>
                        </label>
                     </div>
                     </div>
                     <?php
                        $milestonesSeen='';
                        $milestonesReminder='';
                        $milestonesGoalId='';
                        ?>
                     <div class="dd mile_section row" >
                        <ul class="dd-list">
                           @if((isset($milestonesData))&&($milestonesData->count() > 0))
                           @foreach($milestonesData as $milestones)
                           <li class="dd-item row mb-10" data-milestones-id="{{$milestones->id}}" style="line-height: 20px; !important">
                              <div class="milestones-form">
                                 <div class="col-xs-5 milestones-date-cls">
                                    <input data-toggle="tooltip" title="Milestone Name" name="milestones" class="form-control milestones-name" value="{{$milestones->gb_milestones_name}}" data-milestones-id="{{$milestones->id}}" type="text" disabled="disabled" />
                                 </div>
                                 <div class="col-xs-5 milestones-date-cls date">
                                    <input data-toggle="tooltip" title="Milestone Due Date" class="form-control milestones-date datepicker_SYG4" autocomplete="off" name="milestones-date" required="" value="{{$milestones->gb_milestones_date}}" type="text" disabled="disabled" />
                                 </div>
                                 <div class="col-xs-2 pencil_find_sibling">

                                    <a><i class="fa fa-times new-delete-milestone-info" style="margin-right: 5px" data-milestones-id="{{$milestones->id}}"></i></a>
                                    <!-- <a><i class="fa fa-pencil edit-milestone-info hidden" style="display:inline; font-size: 16px"></i></a> -->
                                    <a><i class="fa fa-save save-milestone-info" data-milestones-id="{{$milestones->id}}" style="display:none"></i></a>
                                 </div>
                              </div>
                           </li>
                           <?php $milestonesSeen =isset($milestonesData)?$milestones->gb_milestones_seen:'';
                              $milestonesReminder =isset($milestonesData)?$milestones->gb_milestones_reminder:'';
                              $milestonesGoalId =isset($milestonesData)?$milestones->goal_id:'';
                              ?>
                           @endforeach
                           @endif
                        </ul>
                     </div>
                     <!--  <div class="form-group">
                        <div class="row">
                            <a><i class="fa fa-times delete-milestone-info" style="margin-right: 5px" data-milestones-id="{{$milestones->id}}"></i></a>
                            <a><i class="fa fa-pencil edit-milestone-info" style="display:inline; font-size: 16px"></i></a>
                            <a><i class="fa fa-save save-milestone-info" data-milestones-id="{{$milestones->id}}" style="display:none"></i></a>
                        </div>
                        
                        </div> -->
                  </div>



<script type="text/javascript">

  $(document).ready(function() {
    loadFormData();
    
    var goal_buddy_id = $('#goal_buddy_saved_id').val();
    if(goal_buddy_id !== ''){
        $('#goal_buddy_id').val(goal_buddy_id);
    }
    
  });

  function loadFormData(){
     var current_step = $('.step').data('step');
     $.ajax({
         url: public_url + 'goal-buddy/load-form-data',
         type: "POST",
         data: {'current_step': current_step},
         success: function (data) {
            console.log(data);

            if(data.data && data.data.Milestones != undefined ){
            
                if (data.data['milestones-names-id'] != undefined) {
                    
                    var milestones_ids =  data.data['milestones-names-id'];  
                    milestones_ids = milestones_ids.split(",");

                    var milestones_dates =  data.data['milestones-dates'];  
                    milestones_dates = milestones_dates.split(",");
                    
                    console.log(milestones_ids);
                    console.log(milestones_dates);

                    if(milestones_ids.length > 0){

                        var last_milestone_date = '';

                        milestones_ids.forEach((value,key) => {

                            var milestones = value.split(":");

                            var milestones_id = milestones[0];
                            var milestoneValue = milestones[1];

                            var date = moment(milestones_dates[key]).format("ddd, D MMM YYYY");

                           // last_milestone_date = milestones_dates[key];

                           //  console.log(milestones_id," : ",milestoneValue);
                           //  console.log(date);
                            


                            var milstones_li = '<li class="dd-item row" data-milestones-id="'+milestones_id+'" style="line-height: 20px; !important"><div class="milestones-form">'
                            +'<div class=" col-md-5 col-xs-5 milestones-date-cls">'
                            +'<input type="text" name ="milestones" class = "form-control milestones-name edit-milestones-name" value="' + milestoneValue + '" data-milestones-id="'+milestones_id+'" disabled>'
                            +'</div><div class="col-md-5 col-xs-5 milestones-date-cls" >'
                            +'<input type="text" class="form-control milestones-date edit-milestones-date datepicker_SYG4" autocomplete="off" name ="milestones-date" value="'+ date +'" disabled required>'
                            +'</div><div class="col-md-2 col-xs-2 p-0 pencil_find_sibling"><a><i class="fa fa-times new-delete-milestone-info"  style="margin-right: 5px" data-milestones-id="'+milestones_id+'" ></i></a>'
                            +'<a><i class="fa fa-save hidden"  data-milestones-id=" "></i></a></div></li>';
                            $('.mile_section ul').append(milstones_li);
                        });

                        if(milestones_dates){

                           var max_date = milestones_dates.reduce(function (a, b) { return a > b ? a : b; });
                           console.log(max_date);
                           var startDateMilestone = new Date(max_date);
                           startDateMilestone.setDate(startDateMilestone.getDate() + 1);
                           $('.create-milestones-date').datepicker('setStartDate', startDateMilestone);
                        }
                        
                    }
                }   
                
            }    
        }    

          
     });
  }

   $('.milestones-date').datepicker({
      todayHighlight: 'TRUE',
      startDate: '-0d',
      autoclose: true,
      minDate: moment(),
      format: 'D, d M yyyy',
      // endDate: '+'+days+'d',
   });

    $('.create-milestones-date').datepicker('setEndDate', new Date($("#goal_due_date").val()));

   function new_str_pad(n) {
     return String("00" + n).slice(-2);
   }

    $('body').on('change', '.milestones-date', function(e) {
    
    // console.log('==============>');
    $('.new_Btn_milestone').prop('disabled', true);
    e.stopImmediatePropagation();
    var send = true,
        isSend = true;
    if (typeof(event) == 'object')
        if (event.timeStamp - lastJQueryTS < 300) {
            send = false;
            lastJQueryTS = event.timeStamp;
        }
    if (send) {
        var obj = $(this),
            ddi = obj.closest('.dd-item'),
            mValue = ddi.find('.milestones-name').val(),
            mDateValue = dateStringToDbDate(ddi.find('.milestones-date').val()),
            milestonesId = ddi.find('.save-milestone-info').data('milestones-id');
        /*clientId = $('input[name="goalClientId"]').val();*/
        // ddi.find('.milestones-name').attr('disabled', true);
        // ddi.find('.milestones-date').attr('disabled', true);
        ddi.find('.edit-milestones-name').attr('disabled', true);
        ddi.find('.edit-milestones-date').attr('disabled', true);

        ddi.find('.save-milestone-info').hide();
        ddi.find('.edit-milestone-info').show();
        var pre_goalId = $('#last-insert-id').val();
        if (typeof pre_goalId == 'undefined' || pre_goalId == '') pre_goalId = $('#goal_milestones_id').val();
        clearTimeout(timeoutReference);

        // console.log('timeoutReference above');
        //testing mahendra
        //pre_goalId = '1279';


        timeoutReference = setTimeout(function() {
            if (isSend) {
                isSend = false;

                $.ajax({
                    url: public_url + 'goal-buddy/updatemilestones',
                    type: 'POST',
                    data: {
                        'milestonesId': milestonesId,
                        'mValue': mValue,
                        'mDateValue': mDateValue,
                        'goalId': pre_goalId
                    },
                    success: function(response) {

                        // console.log('timeoutReference');

                        var data = JSON.parse(response);
                        if (data.status == 'true') {

                            console.log('save milstone', data)
                            if (data.id > 0){
                                console.log('milll', ddi, data.id);
                                ddi.find('.milestones-name').attr('data-milestones-id', data.id);
                                ddi.attr('data-milestones-id', data.id);
                                $('.new_Btn_milestone').attr('data-new-milestones-id',data.id);
                                $('.new_Btn_milestone').prop('disabled', false);

                                // var milestoneSaved = '<input type="hidden" class="milestones-dates-save form-control" name ="milestones-dates-save" data-milestones-id="'+data.id+'" data-milestones-name="'+mValue+'" data-milestones-date="'+mDateValue+'">';
                                // $('.milestoneData').append(milestoneSaved);
                                // ddi.data('milestones-id', data.id);
                            } 

                            // showMIlestoneDd();
                        }
                    }
                });
                
            }
        }, 500);
    }
    // $('#Milestones').val(''); // 02-07-2021
});


    $('body').on('click','.new_Btn_milestone',function(){
   
        var milestones_id = $(this).attr('data-new-milestones-id');
        console.log('milestones_id', milestones_id);
        var milestoneValue = $('#Milestones').val();
        var date = $('#milestones-date-pickup').val();
        if(milestoneValue == '' || date == ''){      
            return false;
        }
        // console.log(milestoneValue, date);
        // console.log(date);
        $('.mile_section ul').append('<li class="dd-item row" data-milestones-id="'+milestones_id+'" style="line-height: 20px; !important"><div class="milestones-form"><div class=" col-md-5 col-xs-5 milestones-date-cls"><input type="text" name ="milestones" class = "form-control milestones-name edit-milestones-name" value="' + milestoneValue + '" data-milestones-id="'+milestones_id+'" disabled></div><div class="col-md-5 col-xs-5 milestones-date-cls" ><input type="text" class="form-control milestones-date edit-milestones-date datepicker_SYG4" autocomplete="off" name ="milestones-date" value="'+ date +'" disabled required><input type="hidden" class="milestones-date-save" autocomplete="off" name ="milestones-date-save" value="'+ date +'"></div><div class="col-md-2 col-xs-2 p-0 pencil_find_sibling"><a><i class="fa fa-times new-delete-milestone-info"  style="margin-right: 5px" data-milestones-id="'+milestones_id+'" ></i></a><a><i class="fa fa-save hidden"  data-milestones-id=" "></i></a></div></li>');
        var objget = $('li.dd-item:last').prev();
        var dueDate = $('#goalDueDate').val();

        $('#Milestones').val('');
        $('#milestones-date-pickup').val('');
        // updateMilestones(id, value)
        var start = new Date(),
            end   = new Date(dueDate),
            diff  = new Date(end - start),
            days  = diff/1000/60/60/24;
            days = Math.round(days);
            if(objget.length == 0)
            {
                $('.datepicker_SYG4 ').datepicker({
                    // todayHighlight: 'TRUE',
                    // startDate: '-0d',
                    // autoclose: true,
                    // minDate: moment(),
                    // format: 'D, d M yyyy'
                    todayHighlight: 'TRUE',
                startDate: '-0d',
                autoclose: true,
                minDate: moment(),
                format: 'D, d M yyyy',
                endDate: '+'+days+'d',
                beforeShowDay: function(date) {
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var Year = date.getFullYear();
                    eventDate = Year+'-'+new_str_pad(month)+'-'+new_str_pad(day);
                    if (dueDate == eventDate) {
                        return {classes: 'highlight', tooltip: 'Title'};
                    }
                }
                });
            }
            var prevMileDate = new Date(objget.find('.milestones-date').val());
            var m_date = prevMileDate != null && prevMileDate != undefined ? new moment(prevMileDate).add(1, 'days') : new moment(prevMileDate);
            var objset = $('li.dd-item:last');
            $('.datepicker_SYG4').datepicker({
                todayHighlight: 'TRUE',
                startDate: m_date.toDate(),
                autoclose: true,
                format: "D, d M yyyy",
                endDate: '+'+days+'d',
                beforeShowDay: function(date) {
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var Year = date.getFullYear();
                    eventDate = Year+'-'+new_str_pad(month)+'-'+new_str_pad(day);
                    if (dueDate == eventDate) {
                        return {classes: 'highlight', tooltip: 'Title'};
                    }
                }
            });
             showMIlestoneDd();
            //disable past date for first input datepicker
            // var last_date = $('li.dd-item:last').find('.edit-milestones-date').val();
            var startDateMilestone = new Date(date);
            startDateMilestone.setDate(startDateMilestone.getDate() + 1);
            $('.create-milestones-date').datepicker('setStartDate', startDateMilestone);
            // $('.milestones-date').datepicker('setStartDate', startDateMilestone);
            //end
       
    });

function showMIlestoneDd(defaults) {
    var milestones = $('.mile_section .dd-item');
    $('.milestone-div').show();
    var optionValue = '<select id="milestone_div" name="milestone_value" class="selectpicker form-control onchange-set-neutral milestone_div_class" multiple="" data-actions-box="true">';
    var url = window.location.href;
    var result = url.split('/');
    var Param = result[result.length - 3];
    var Param1 = result[result.length - 2];
    $.each(milestones, function(k, obj) {
        if ((Param == 'goal-buddy') && (Param1 == 'edit')) {
        // var v = $(obj).find('.save-milestone-info').data('milestones-id'),
         var v = $(obj).find('.milestones-name').data('milestones-id'),
             t = $(obj).find('.milestones-name').val();

        } else {
           var v = $(obj).closest('.dd-item').data('milestones-id'),
             t = $(obj).find('.milestones-name').val();
        }
        console.log('alert=== ',obj, v , t);
        if (defaults && $.inArray(v + "", defaults) >= 0){
            optionValue += '<option value="' + v + '" selected>' + t + '</option>';
        }else{
            optionValue += '<option value="' + v + '">' + t + '</option>';
        } 
    });
    optionValue += '</select>';
    $('.milestone-dropdown').html($(optionValue));
    initSelectpicker($('.milestone-dropdown select'));

    $('.milestone_div_class').selectpicker('refresh');
    
}

$('body').on('click', '.new-delete-milestone-info', function() {
        //enable past date for first input datepicker
        var li_length = $('li.dd-item').find('.edit-milestones-date').length;
        if(li_length == 1){
            var startDateMilestone = new Date();
            $('.create-milestones-date').datepicker('setStartDate', startDateMilestone);
         }
        //end enable past date for first input datepicker
        var currentRow = $(this).closest('.dd-item');
        var milestonesId = $(this).data('milestones-id');
        $(currentRow).remove();
        if (milestonesId != '') {
            $.ajax({
                url: public_url + 'goal-buddy/deletemilestones',
                type: 'POST',
                data: {
                    'eventId': milestonesId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == 'true') {
                     //   createHabitDd(data.habitData);
                      //  showHabitList(data.habitData);
                      //  showTaskList(data.taskData);
                        if($('.milestones-date').length <= 1){
                            var startDateMilestone = new Date();
                            startDateMilestone.setDate(startDateMilestone.getDate());
                            $('.milestones-date').datepicker('setStartDate', startDateMilestone);
                        }
                    }
                }
            });
        }
    });

</script>