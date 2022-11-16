<div class="step data-step" data-step="8">
                     <div class="row">
                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1" data-id="7"><span>7.</span></div>

                        <label class="steps-head">What happen if you do <strong>not achieve</strong> your <strong>EPIC </strong>Goal? * </label>

                      </div>
                      <div class="tooltip-sign mb-10">

                         <a href="javascript:void(0)" class="goal-step" 
                           data-message="Some simple questions to ask yourself:
                           <br/><br/>
                           • How does maintaining the status quo affect you? <br/>
                           • Can you maintain your current lifestyle for the next 5, 10, 15 years and be happy? <br/>
                           • Will you be able to perform at an event and in life? <br/>
                           • Will you be able to enjoy your holiday/event and feel good/participate?<br/>
                           • Is your current situation negatively affecting your loved ones?<br/>
                           • Are your loved ones around you willing to put up with your failure to change?"
                           data-message1="Questions to ask yourself:<br/>
                           How does maintaining the status quo affect you?<br/>
                           Can you maintain your current lifestyle for the next 5, 10, 15 years?<br/>
                           Are your loved ones around you willing to put up with your failure to change?<br/>
                           Is it negatively affecting them?
                           "><i class="fa fa-question-circle question-mark"></i></a>
                     </div>

                    </div>
                     <div class="form-group fail_description">
                      <!-- <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize id="fail-description" name="fail-description" ng-model="fail_description" ng-init="fail_description='{{ isset($goalDetails) ? $goalDetails->gb_fail_description: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_fail_description:null}}</textarea>
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
            var other = false;
            if(data.data && data.data['fail-description'] != undefined ){
               if(data.data['fail-description'].length > 0){
                  data.data['fail-description'].map(value => {
                     if(value == "Other"){
                        other = true; 
                     }
                     $("input[type=checkbox][value='"+value+"']").attr("checked",true);
                  });
               }
            }
            if(other){
               $('#fail_description_other').removeClass('hidden');
               $('#fail_description_other').attr('required',true);
               $('#fail_description_other').attr('placeholder','Describe your achievement Here...');
               $('#fail_description_other').text(data.data.gb_fail_description_other);
            }
            
          }
     });
  }
  $(document).on('change',".fail_description_other",function(){
      var other = $('.fail_description_other').prop('checked');
      if(other == true){
         $('#fail_description_other').removeClass('hidden');
         $('#fail_description_other').attr('required',true);
         $('#fail_description_other').attr('placeholder','Input Your Specific Goal Here...');
      }else{
         $('#fail_description_other').addClass('hidden');
         $('#fail_description_other').attr('required',false);
         $("#fail_description_other-error").html('');
         $('#fail_description_other').val('');
      }
   })

  function templateLoad(){

      var data = JSON.parse(sessionStorage.getItem("templateData"));
      /*  */
      var fail_description = data.goal_template.gb_fail_description.split(",");
      //var fetch_data = data.fetch_data;

      $(".fail_description").html("");
      var radio_val = "";
      var fail_description_other = null;
      var html6 = "";
      // if (fetch_data != null) {
      //    var fail_check_array = fetch_data.gb_fail_description.split(",");
      // }
      // console.log('fail_check_array=======',fail_check_array, fail_description);

      for (var i = 0; i < fail_description.length; i++) {
         // if (fetch_data != null) {
         //    for (var j = 0; j < fail_check_array.length; j++) {
         //       radio_val =
         //          fail_check_array[j] == fail_description[i] ? "checked" : "";
         //       if (radio_val == "checked") {
         //          break;
         //       }
         //    }
         //    fail_description_other = fetch_data.gb_fail_description_other;
         // }
         if (fail_description[i].trim() == "Other") {
            fail_description[i] = fail_description[i].trim(); 
            var other6 = "fail_description_other";
            if (fail_description_other != null) {
               html6 =
                  '<textarea rows="7" class="form-control" id="fail_description_other" name="gb_fail_description_other">' +
                  fail_description_other +
                  "</textarea>";
            } else {
               html6 =
                  '<textarea rows="7" class="form-control hidden" id="fail_description_other" name="gb_fail_description_other"></textarea>';
            }
         }
         if (fail_description[i].includes("_")) {
         fail_description[i] = fail_description[i].replace("_", ", ");
         }
         // var fail_description = fail_description[i].replace('-', ',')
         $(".fail_description").append(
         '<div class="form-group">\ <label class="container_check version_2">' + fail_description[i] + '\ <input type="checkbox"  class="' + other6 + '" name="fail-description[]" required value="' + fail_description[i] + '" ' + radio_val + '>\ <span class="checkmark"></span>\ ' + html6 + "\ </label>\ </div>"
         );
      }
  }   
</script>