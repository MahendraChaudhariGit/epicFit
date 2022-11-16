<div class="step data-step" data-step="9">

                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="8"><span>8.</span></div>

                        <label class="steps-head">Why is this <strong>EPIC</strong> Goal <strong>relevant</strong>? *  </label>

                      </div>

                      <div class="tooltip-sign mb-10">
                     <a href="javascript:void(0)" class="goal-step" 
                           data-message="To address this question, you need to ask simple question such as:
                           <br/><br/>
                           What makes this goal intrinsic and important to you specifically?<br/>
                           Why is this Goal more important to you than everyone else around you?<br/>
                           How does it relate directly to you and your current position in life?<br/>
                           What aspect of your life does it affect? <br/>
                           Why is it specific to your current position?<br/>
                           What internal changes will you experience that may not be realised by other individuals initially?<br/>
                           Are you doing this for you are as a request from someone else?"
                           data-message1="Why is this <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> more important to you than everyone else around you?<br/>
                           What internal changes will you fill that other may not realise initially?<br/>
                           (Why is it specific to your current position)<br/>
                           Are you doing this for you are as a request from someone else?
                           "><i class="fa fa-question-circle question-mark"></i></a>
                     </div>

                    </div>

                     <div class="form-group template-relevant-goal">
                      <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" id="relevant_goal" data-toggle="tooltip" title="" data-autoresize id="gb_relevant_goal" name="gb_relevant_goal" ng-model="gb_relevant_goal" ng-init="gb_relevant_goal='{{ isset($goalDetails) ? $goalDetails->gb_relevant_goal: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_relevant_goal:null}}</textarea>
                      </div>
                     </div>
                  </div>

<script type="text/javascript">
  $(document).ready(function() {
      templateLoad();
      loadFormData();
  });

  function loadFormData(){
     var current_step = $('.step').data('step');
     $.ajax({
         url: public_url + 'goal-buddy/load-form-data',
         type: "POST",
         data: {'current_step': current_step},
         success: function (data) {
            console.log(data);
            if(data.data && data.data.gb_relevant_goal != undefined ){
               if(data.data.gb_relevant_goal.length > 0){
                  data.data.gb_relevant_goal.map(value => {
                     $("input[type=checkbox][value='"+value+"']").attr("checked",true);
                  });
               }
            }
            if(data.data && data.data.gb_relevant_goal_other != undefined && data.data.gb_relevant_goal_other != ""){
               $('#gb_relevant_goal_other').removeClass('hidden');
               $('#gb_relevant_goal_other').attr('required',true);
               $('#gb_relevant_goal_other').attr('placeholder','Describe your achievement Here...');
               $('#gb_relevant_goal_other').text(data.data.gb_relevant_goal_other);
            }
            
          }
     });
  }
  $(document).on('change',".gb_relevant_goal_other",function(){
      var other = $('.gb_relevant_goal_other').prop('checked');
      if(other == true){
         $('#gb_relevant_goal_other').removeClass('hidden');
         $('#gb_relevant_goal_other').attr('required',true);
         $('#gb_relevant_goal_other').attr('placeholder','Input Your Specific Goal Here...');
      }else{
         $('#gb_relevant_goal_other').addClass('hidden');
         $('#gb_relevant_goal_other').attr('required',false);
         $("#gb_relevant_goal_other-error").html('');
         $('#gb_relevant_goal_other').val('');
      }
   })

  function templateLoad(){
      /*  */
      var data = JSON.parse(sessionStorage.getItem("templateData"));
      var gb_relevant_goal = data.goal_template.gb_relevant_goal.split(",");
      // var fetch_data = data.fetch_data;
      $(".template-relevant-goal").html("");
      // if (fetch_data != null) {
      //    var relevant_check_array = fetch_data.gb_relevant_goal.split(",");
      // }
      var html4 = "";

      var relevant_other = null;
      var radio_val = "";
      for (var i = 0; i < gb_relevant_goal.length; i++) {
         // if (fetch_data != null) {
         // for (var j = 0; j < relevant_check_array.length; j++) {
         //    radio_val =
         //       relevant_check_array[j] == gb_relevant_goal[i] ? "checked" : "";
         //    if (radio_val == "checked") {
         //       break;
         //    }
         // }
         // relevant_other = fetch_data.gb_relevant_goal_other;
         // }
         if (gb_relevant_goal[i].trim() == "Other") {
            gb_relevant_goal[i] = gb_relevant_goal[i].trim(); 
            var other4 = "gb_relevant_goal_other";
            if (relevant_other != null) {
               html4 =
                  '<textarea rows="7" class="form-control" id="gb_relevant_goal_other" name="gb_relevant_goal_other">' +
                  relevant_other +
                  "</textarea>";
            } else {
               html4 =
                  '<textarea rows="7" class="form-control hidden" id="gb_relevant_goal_other" name="gb_relevant_goal_other"></textarea>';
            }
         }
         $(".template-relevant-goal").append(
         '<div class="form-group">\
                        <label class="container_check version_2">' +
            gb_relevant_goal[i] +
            '\
                        <input type="checkbox" class="' +
            other4 +
            '" name="gb_relevant_goal[]" required value="' +
            gb_relevant_goal[i] +
            '" ' +
            radio_val +
            '>\
                        <span class="checkmark"></span>\
                        ' +
            html4 +
            "\
                        </label>\
                     </div>"
         );
      }
  }   
</script>