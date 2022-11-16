<div class="step data-step newGoalStep" data-step="1" data-value="0">
     <div class="form-group">
        <label class="container_radio version_2">CREATE NEW GOAL 
        <input type="radio" name="chooseGoal" class="choose-create-new-goal-first form-control" id="create_new_goal" value="create_new_goal" required>
        <span class="checkmark"></span>
        </label>
     </div>
     <div class="form-group">
        <label class="container_radio version_2">CHOOSE FROM ONE OF OUR TEMPLATES 
        <input type="radio" name="chooseGoal" id="choose_form_template" value="choose_form_template" class="form-control" required>
        <span class="checkmark"></span>
        </label>
     </div>
</div>

<script type="text/javascript">

$(document).ready(function() {
   loadFormData();
});

function loadFormData(){
   var current_step = $('.step').data('step');

   console.log(current_step);
   
   $.ajax({
       url: public_url + 'goal-buddy/load-form-data',
       type: "POST",
       data: {'current_step': current_step},
       success: function (data) {
            console.log(data);
            if(data.data && data.data.chooseGoal != undefined ){
               $("input[type=radio][value='"+data.data.chooseGoal+"']").attr("checked",true);
            }
        }
   });
}

</script>