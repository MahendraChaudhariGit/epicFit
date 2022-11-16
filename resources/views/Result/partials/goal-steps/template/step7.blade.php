<div class="step data-step" data-step="7">
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1" data-id="6"><span>6.</span></div>

                        <label class="steps-head">Why is it <strong>important</strong> to you to accomplish this <strong>EPIC </strong>Goal? *
                        </label>
                      </div>

                      <div class="tooltip-sign mb-10">

                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="Why is this an intrinsic Goal? What makes this Goal important to you and how long have you wanted to achieve this Goal for? What does it mean to achieve this goal? Does it affect others in your life positively? 
                           <br/><br/>
                           If this Goal is important to you and you are doing it for yourself and not to please or cater for someone else, you are more likely to achieve the desired <span style='color:#f64c1e;'>RESULT</span>. 
                           <br/><br/>
                           Make your WHY big enough to overcome the How and the What!
                           <br/><br/>
                           Include all the positive effects related to all aspects of your life will result from achieving this Goal?
                           <br/><br/>
                           Examples include:
                           <br/><br/>
                               • Feeling Confident,<br/> 
                               • Looking Healthier <br/>
                               • Feeling stronger.
                               <br/><br/>
                           Explain why this is an intrinsic goal and how long have you wanted to achieve this goal for?"
                           data-message1="Why is this an intrinsic Goal? What makes this <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> important to you and how long have you wanted to achieve this Goal for? 
                           <br/><br/>
                           If this <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> is important to you and you are doing it for yourself and not to please or cater for someone else, you are more likely to achieve the desired Result. 
                           <br/><br/>
                           Make your WHY big enough to overcome the How and the What!
                           <br/><br/>
                           What are the positive effects on all aspects of your life as a result of achieving it?
                           <br/><br/>
                           <b>Example includes</b> feeling confident, looking, and feeling stronger.
                           "><i class="fa fa-question-circle question-mark"></i></a>
                     </div>

                   </div>

                     <div class="form-group template-accomplish">
                      <!-- <div class="outborder">
                        <textarea ong-blur="pressEnter($event)" data-toggle="tooltip" data-html="true" title="" data-autoresize id="accomplish" name="accomplish" ng-model="accomplish" ng-init="accomplish='{{ isset($goalDetails) ? $goalDetails->gb_important_accomplish: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_important_accomplish:null}}</textarea>
                      </div> -->
                     </div>
                  </div>
<script type="text/javascript">
  $(document).ready(function() {
    templateLoad()
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
            if(data.data && data.data.accomplish != undefined ){
              if(data.data.accomplish.length > 0){
                data.data.accomplish.map(value => {
                  $("input[type=checkbox][value='"+value+"']").attr("checked",true);

                  if(value == "Other"){
                    $('#accomplish_other').removeClass('hidden');
                    $('#accomplish_other').attr('required',true);
                    $('#accomplish_other').attr('placeholder','Describe your achievement Here...');
                    $('#accomplish_other').text(data.data.gb_important_accomplish_other);
                  }
                });
              }
            }
            // if(data.data && data.data.gb_important_accomplish_other != undefined && data.data.gb_important_accomplish_other != ""){
               
            // }
          }
     });
  }
  $(document).on('change',".accomplish_other",function(){
      var other = $('.accomplish_other').prop('checked');
      if(other == true){
         $('#accomplish_other').removeClass('hidden');
         $('#accomplish_other').attr('required',true);
         $('#accomplish_other').attr('placeholder','Input Your Specific Goal Here...');
      }else{
         $('#accomplish_other').addClass('hidden');
         $('#accomplish_other').attr('required',false);
         $("#accomplish_other-error").html('');
         $('#accomplish_other').val('');
      }
   })

  function templateLoad(){

    var data = JSON.parse(sessionStorage.getItem("templateData"));
    var html3 = "";
    var accomplish = data.goal_template.gb_important_accomplish.split(",");
    $(".template-accomplish").html("");
    var radio_val = "";
    var accomplish_other = null;
    //var fetch_data = data.fetch_data;
    var other3 = "accomplish";
    for (var i = 0; i < accomplish.length; i++) {
      // if (fetch_data != null) {
      //   var accomplish_check_array = fetch_data.gb_important_accomplish.split(",");
      //   for (var j = 0; j < accomplish_check_array.length; j++) {
      //     radio_val =
      //       accomplish_check_array[j] == accomplish[i] ? "checked" : "";
      //     if (radio_val == "checked") {
      //       break;
      //     }
      //   }
      //   accomplish_other = fetch_data.gb_important_accomplish_other;
      // }
      if (accomplish[i].trim() == "Other") {
        accomplish[i] = accomplish[i].trim()
        other3 = "accomplish_other";
        if (accomplish_other != null) {
          html3 =
            '<textarea rows="7" class="form-control" id="accomplish_other" name="gb_important_accomplish_other">' +
            accomplish_other +
            "</textarea>";
        } else {
          html3 =
            '<textarea rows="7" class="form-control hidden" id="accomplish_other" name="gb_important_accomplish_other"></textarea>';
        }
      }
      $(".template-accomplish").append(
        '<div class="form-group">\
                        <label class="container_check version_2">' +
          accomplish[i] +
          '\
                        <input type="checkbox" class="' +
          other3 +
          '" name="accomplish[]" required value="' +
          accomplish[i] +
          '" ' +
          radio_val +
          '>\
                        <span class="checkmark"></span>\
                        ' +
          html3 +
          "\
                        </label>\
                    </div>"
      );
    }
  }
</script>