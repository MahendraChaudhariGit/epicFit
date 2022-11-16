<div class="step data-step goalName" data-step="3" data-value='0'>                    
                       <div class="row">
                         <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                        <div class="heading-text border-head mb-10">

                          <div class="watermark1" data-id="2"><span>2.</span></div>


                           <label class="steps-head">Name Your <strong>EPIC</strong> Goal? *  </label>
                        </div>

                        <div class="tooltip-sign mb-10">

                          <a href="javascript:void(0)" class="goal-step" 
                           data-message="You have selected to create your own unique <span style='color:#f64c1e'><b>EPIC</b> Goal</span>. Please provide a short and definitive name for the Goal you want achieve, this may assist in making it easier to bring it to life and make it reality.
                           <br/><br/>
                           Example may include: <br/>
                              • compete in an event<br/>
                              • complete a marathon/half marathon<br/>
                              • improve relationships<br/>
                              • Master Box Jump<br/>
                              • Improve 100m Sprint
                               <br/><br/>
                           Choose a descriptive name that best describes your Goal and the way you envision it."
                           data-message1="You have selected weight management as your template, a short and definitive name of what you want to achieve can help bring it to life and make it a reality.
                           <br/><br/>
                           Example may include: <br/>
                           ·<b> Lose Weight </b>(This may be a certain weight loss or get down to a certain weight) <br/>
                           ·<b> Gain Weight </b>(This may be a certain weight gain or get up to a certain weight) <br/>
                           ·<b> Build Muscle </b>(This may be a related to a certain body composition) <br/>
                           ·<b> Manage & Maintain Weight </b>(This may be maintaining a certain weight that you are happy with)
                           <br/><br/>
                           If you would like to select something unique to you, then choose a name that best describe your Goal and the way you envision it.
                           "><i class="fa fa-question-circle question-mark"></i></a>
                        </div>
                         
                       </div>
                     <div class="form-group tooltip-hover append-template-goal-name">
                      <!-- <div class="outborder">
                        <textarea ng-mouseenter="pressEnter($event)" rows="3" data-toggle="tooltip"  data-html="true" title="" data-autoresize id="name_goal" name="name_goal" ng-model="name_goal" ng-init="name_goal='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}</textarea>
                     </div> -->
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
            if(data.data && data.data.name_goal != undefined ){
               $("input[type=radio][value='"+data.data.name_goal+"']").attr("checked",true);
            }
            if(data.data && data.data.name_goal_other != undefined && data.data.name_goal_other != ""){
               $('#name_goal_other').removeClass('hidden');
               $('#name_goal_other').attr('required',true);
               $('#name_goal_other').attr('placeholder','Input Your Specific Goal Here...');
               $('#name_goal_other').text(data.data.name_goal_other);
            }
         }
      });
   }
   $(document).on('change',"input[name=name_goal]",function(){
      var other = $('input[name=name_goal][value=Other]').prop('checked');
      if(other == true){
         $('#name_goal_other').removeClass('hidden');
         $('#name_goal_other').attr('required',true);
         $('#name_goal_other').attr('placeholder','Input Your Specific Goal Here...');
      }else{
         $('#name_goal_other').addClass('hidden');
         $('#name_goal_other').attr('required',false);
         $("#name_goal_other-error").html('');
         $('#name_goal_other').val('');
      }
   })

   function templateLoad(){

      var data = JSON.parse(sessionStorage.getItem("templateData"));

      var html1 = "";
      var goal_name = data.goal_template.gb_goal_name.split(",");
      $(".append-template-goal-name").html("");
      var radio_val = "";
      var goal_other = null;
      //var fetch_data = data.fetch_data;
      
      for (var i = 0; i < goal_name.length; i++) {
         // if (fetch_data != null) {
         //    radio_val = fetch_data.gb_goal_name == goal_name[i] ? "checked" : "";
         //    goal_other = fetch_data.gb_goal_name_other;
         // }

         if (goal_name[i] == "Other") {
            var other1 = "name_goal_other";
            if (goal_other != null) {
               html1 =
                  '<textarea rows="7" class="form-control" id="name_goal_other" name="name_goal_other">' +
                  goal_other +
                  "</textarea>";
            } else {
               html1 =
                  '<textarea rows="7" class="form-control hidden" id="name_goal_other" name="name_goal_other" placeholder="Input Your Specific Goal Here..."></textarea>';
            }
         }

         $(".append-template-goal-name").append(
         '<div class="form-group">\
                              <label class="container_radio version_2">' +
            goal_name[i] +
            '\
                              <input type="radio" class="' +
            other1 +
            '" name="name_goal" required value="' +
            goal_name[i] +
            '" ' +
            radio_val +
            '>\
                              <span class="checkmark"></span>\
                              ' +
            html1 +
            "\
                              </label>\
                        </div>"
         );
      }
   }
</script>