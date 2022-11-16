<div class="step data-step newTask" data-step="18" data-value="0">
  <div class="row">

    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
    <div class="heading-text border-head mb-10">

      <div class="watermark1" data-id="17"><span>17.</span></div>

      {{-- <label class="steps-head">What Habits Do I need to Develop to Accomplish This   <strong>Goal</strong> ?</label> --}}
      <label class="steps-head">What Habits Do I need to Develop to Accomplish This Goal?</label>
   </div>

    <div class="tooltip-sign mb-10">

     <a href="javascript:void(0)" class="goal-step" 
         data-message="new_goal"
         data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
    </div>

  </div>
   <div class="form-group">
     
      {{-- <span> Establish New Habit</span> --}}
      <a class ="btn btn-primary add-habit">Establish New Habit</a>
      <div class="tooltip-sign mb-10 tooltip_btn">

         <a href="javascript:void(0)" class="goal-step" 
             data-message="Please provide tooltip content"
             data-message1="Please provide tooltip content"><i class="fa fa-question-circle question-mark"></i></a>
        </div>
      
   </div>
   <div class="form-group">
      <div class="table-responsive">
         <table class="table table-striped table-bordered table-hover" id="client-datatable">
            <thead>
               <tr>
                  <th class="">Habit Name</th>
                  <th>Frequency</th>
                  <th class="hidden-xs">Milestone</th>
                  <!-- <th class="hidden-xs">Shared</th> -->
                  <th class="center">Actions</th>
               </tr>
            </thead>
            <tbody id="habitlist">
            </tbody>
         </table>
            {{-- <tbody>
               <tr>
                  <td>Physical Activity</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="center">
                     <a class="btn btn-xs btn-default tooltips habit-edit" data-placement="top" data-original-title="Edit" data-habit-id = "{{$habits->id}}">
                        <i class="fa fa-pencil" style="color:#ff4401;"></i>
                    </a>
                     <a class="btn btn-xs btn-default tooltips delete-habit" data-entity="habit" data-placement="top" data-original-title="Delete" data-habit-id="1059">
                     <i class="fa fa-times" style="color:#ff4401;"></i></a>
                  </td>
               </tr>
            </tbody> --}}
         {{-- </table> --}}
      </div>
      {{-- <div class="row habit-form" id="habit_form">
         <h6 class="step-heading"><em>What Habits Do I need to Develop to Accomplish This Goal?</em></h6>

         @include('Result.goal-buddy.createhabits')

     </div> --}}
   </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
      
$.ajax({
    url: public_url + 'goal-buddy/load-habit-data',
    type: "GET",
    dataType: 'json',
    processData: false,
    contentType: false,
    success: function (data) {
     var listValue = "";
      var habitLabel = "";
      var habitRecurrence = "";
      $('#client-datatable tbody tr').remove();
      $.each(data.listData, function(key, value) {
         if (value.mile_stone_name != null) var milname = value.mile_stone_name;
         else var milname = '';
         $('.habit-name').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your Habit ' + value.gb_habit_name + ' has been saved.</div>');
         if (value.gb_habit_recurrence_type == 'weekly') {
             var recurrence_week =  value.gb_habit_recurrence_week.replace(/,/g,", "); // for design 
                 habitRecurrence = 'Every ' + recurrence_week;  
             //  habitRecurrence = 'Every ' + value.gb_habit_recurrence_week;
         } else if (value.gb_habit_recurrence_type == 'monthly') {
             habitRecurrence = 'Day ' + value.gb_habit_recurrence_month + ' of every month';
         } else {
             habitRecurrence = value.gb_habit_recurrence_type;
         }
         var hidden = '';
         var primary_habit = false;

         if(value.is_primary){
            hidden = 'hidden';
            primary_habit = true;
         }
         // if(key < 3){
         //    hidden = 'hidden';
         //    primary_habit = true;
         // }

         listValue += '<tr><td >' + value.gb_habit_name + '</td><td>' + habitRecurrence + '</td><td >' + milname + '</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-primary="'+primary_habit+'" data-habit-id = "' + value.id + '"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-habit '+hidden+'" data-placement="top"data-original-title="Delete" data-entity="habit" data-habit-id = "' + value.id + '"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
         habitLabel += '<a class="Step-your-goal3 habit-text" data =' + value.id + '><li>' + value.gb_habit_name + '</li></a>';
         habitLabel += '<p>' + value.gb_habit_seen + '</p>';
     });
     $('.habit-label a').remove();
     $('.habit-label p').remove();
     $('.habit-label').append(habitLabel);
     $('#client-datatable tbody').append(listValue);
    }
});

});


$(document).on('click','.add-habit',function(){

   var goal_type = $("#goal_type").val();
   
    $.ajax({
       url: public_url + 'goal-buddy/load-custom-habit-step?goal_type=' + goal_type,
       type: "GET",
       dataType: 'json',
       processData: false,
       contentType: false,
       success: function (data) {
         $('form').html('');
         $('form').html(data.html);
       }
   });
})

$(document).on('click', '.delete-habit', function() {
        var processbarDiv = $(this).closest('tr');
        var habitId = $(this).data('habit-id');
        var entity = $(this).data('entity');
        var thisObj = $(this);
        swal({
            title: "Are you sure to delete this " + entity + "?",
            text: (typeof warningText != 'undefined' && warningText) ? warningText : '',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d43f3a",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: true,
            customClass: 'delete-alert'
        }, function() {
            $(document).on('click', '.confirm', function(e) {
                $.ajax({
                    url: public_url + 'goal-buddy/deletehabit',
                    type: 'POST',
                    data: {
                        'eventId': habitId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status == 'true') {
                           thisObj.closest('tr').remove();
                            /*createHabitDd(data.habitData);
                            showHabitList(data.habitData);
                            showTaskList(data.taskData);
                            processbarDiv.remove();*/
                            //location.reload();
                        }
                    }
                });
            });
        });
    });
</script>