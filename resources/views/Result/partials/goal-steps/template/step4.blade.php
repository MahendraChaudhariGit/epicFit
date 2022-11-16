<div class="step data-step" data-step="4">
                    <div class="row">
                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="3"><span>3.</span></div>

                        <label class="steps-head">Describe what you want to <strong>achieve</strong>? *  </label>
                      </div>

                      <div class="tooltip-sign mb-10">

                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="Describe your desired result or outcome, including the changes you wish to see along the way, and what aspect of the Goal matters most to you.
                           <br/><br/>
                           Examples may be:<br/>
                              • Limit stress<br/>
                              • To complete a certain event in a certain time or in a certain way<br/>
                              • performing at a competitive level in a sport<br/>
                              • leaving work stress in the work environment<br/>
                              • achieving a balanced and sustainable diet/ training routine<br/>
                              • reaching 10% bodyfat
                               <br/><br/>
                           An example of your description may be if your goal is to limit stress: <br/>
                              • describe exactly what type of stress you are wanting to work on. <br/>
                              • Is that what you are wanting to remove yourself from 50% of the stressful situation you currently find yourself in weekly?<br/>
                              • Do you want to learn to switch off, rather than bringing work stress into home environment?
                               <br/><br/>
                           Additional information is critical as the more information and the more effort you put into this the more likely you are to succeed.
                           <br/><br/>
                           Add a Photo of what you are wanting to achieve, a visual idea may be more enticing instead of just words or saying it."
                           data-message1="Describe your desired result or outcome, including the changes you wish to see along the way, and what aspect of the Goal matters most to you.
                           <br/><br/>
                           <b>For example</b>
                           <br/><br/>
                           <b>Lose weight,</b> describe exactly how much weight you would like to lose and in what area?<br/>
                           <b>Gain muscle,</b> describe exactly how much and focus on what area?<br/>
                           <b>Tone up,</b> describe exactly which areas and how much you would like to tone up?<br/>
                           <b>Lower BFP,</b> describe exactly how much you want to lose or what you want to be?
                           <br/><br/>
                           Additional information is critical as the more information and the more effort you put into this the more likely you are to succeed.
                           <br/><br/>
                           Add a Photo of what you are wanting to achieve, a visual idea may be more enticing instead of just words or saying it.
                           "><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>
                       
                     <div class="form-group template-description-achieve">
                      <!-- <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" id="description" data-toggle="tooltip" data-html="true" title="" data-autoresize id="describe_achieve" name="describe_achieve" ng-model="describe_achieve" ng-init="describe_achieve='{{ isset($goalDetails) ? $goalDetails->gb_achieve_description: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_achieve_description:null}}</textarea>
                      </div> -->
                     </div>
                     <div class="">
                      <div class="row d-flex">
                        <span class="qodef-grid-line-center heading-border"></span>
                        <div class="heading-text border-head mb-10">
                        <div class="padding-left-20 custom-padding">
                           <div class="form-group upload-group m-t-10" style="padding-left: 3px">
                              <input type="hidden" name="prePhotoName" value="{{isset($goalDetails)?$goalDetails->gb_image_url:null}}">
                              <input type="hidden" name="entityId" value="">
                              <input type="hidden" name="saveUrl" value="photo/save" >
                              <input type="hidden" name="photoHelper" value="SYG" >
                              <input type="hidden" name="cropSelector" value="">
                              <label class="btn btn-primary btn-file add-photo"> <span><i class="fa fa-plus"></i> Add Photo</span>
                              <input type="file" class="hidden filePreviewCls" onChange="fileSelectHandler(this)" accept="image/*">
                              </label>
                              
                             
                              <div class="m-t-10">
                                 @if(isset($goalDetails->gb_image_url) && ($goalDetails->gb_image_url != ''))
                                 <img src="{{ dpSrc($goalDetails->gb_image_url) }}" class="SYGPreviewPics previewPics"  />
                                 @else
                                 <img class="hidden SYGPreviewPics previewPics" />
                                 @endif
                              </div>
                              <span class="help-block m-b-0"></span>
                              <input type="hidden" name="logo" value="">
                           </div>
                        </div>
                          </div>
                        <div class="tooltip-sign mb-10">
                                 <a href="javascript:void(0)" class="goal-step" 
                                    data-message="Please provide an accurate image or photo of what you are aiming to achieve, please ensure the image is achievable and realistic to your expectations."
                                    data-message1="Please provide an accurate image or photo of what you are aiming to achieve, please ensure the image is achievable and realistic to your expectations."><i class="fa fa-question-circle question-mark"></i></a>
                              </div> 
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
      var hostname = window.location.origin;
      $.ajax({
          url: public_url + 'goal-buddy/load-form-data',
          type: "POST",
          data: {'current_step': current_step},
          success: function (data) {
            console.log(data);
               if(data.data && data.data.describe_achieve != undefined ){
                 $("input[type=radio][value='"+data.data.describe_achieve+"']").attr("checked",true);
               }
               if(data.data && data.data.prePhotoName != undefined && data.data.prePhotoName != ''){
                  $(".SYGPreviewPics").show();
                  $(".SYGPreviewPics").removeClass('hidden');
                  $(".SYGPreviewPics").attr('src', hostname+'/uploads/thumb_'+data.data.prePhotoName);
                  $("input[name=prePhotoName]").val(data.data.prePhotoName);
               }
               if(data.data && data.data.gb_achieve_description_other != undefined && data.data.gb_achieve_description_other != "" ){
                  $('#describe_achieve_other').removeClass('hidden');
                  $('#describe_achieve_other').attr('required',true);
                  $('#describe_achieve_other').attr('placeholder','Describe your achievement Here...');
                  $('#describe_achieve_other').text(data.data.gb_achieve_description_other);
               }
           }
      });
   }
   $(document).on('change',"input[name=describe_achieve]",function(){
      var other = $('input[name=describe_achieve][value=Other]').prop('checked');
      if(other == true){
         $('#describe_achieve_other').removeClass('hidden');
         $('#describe_achieve_other').attr('required',true);
         $('#describe_achieve_other').attr('placeholder','Input Your Specific Goal Here...');
      }else{
         $('#describe_achieve_other').addClass('hidden');
         $('#describe_achieve_other').attr('required',false);
         $("#describe_achieve_other-error").html('');
         $('#describe_achieve_other').val('');
      }
   })
   function templateLoad(){

      var data = JSON.parse(sessionStorage.getItem("templateData"));
      var html2 = "";

      var describe_achieve = data.goal_template.gb_achieve_description.split(",");
      $(".template-description-achieve").html("");
      var radio_val = "";
      var achieve_other = null;
      //var fetch_data = data.fetch_data;

      for (var i = 0; i < describe_achieve.length; i++) {
         // if (fetch_data != null) {
         // radio_val =
         //    fetch_data.gb_achieve_description == describe_achieve[i]
         //       ? "checked"
         //       : "";
         // achieve_other = fetch_data.gb_achieve_description_other;
         // }

         if (describe_achieve[i].trim() == "Other") {
            describe_achieve[i] = describe_achieve[i].trim();
            var other2 = "describe_achieve_other";
            if (achieve_other != null) {
               html2 =
                  '<textarea rows="7" class="form-control" id="describe_achieve_other" name="gb_achieve_description_other">' +
                  achieve_other +
                  "</textarea>";
            } else {
               html2 =
                  '<textarea rows="7" class="form-control hidden" id="describe_achieve_other" name="gb_achieve_description_other"></textarea>';
            }
         }
         $(".template-description-achieve").append(
         '<div class="form-group">\
                              <label class="container_radio version_2">' +
            describe_achieve[i] +
            '\
                              <input type="radio" class="' +
            other2 +
            '" name="describe_achieve" required value="' +
            describe_achieve[i] +
            '" ' +
            radio_val +
            '>\
                              <span class="checkmark"></span>\
                              ' +
            html2 +
            "\
                              </label>\
                        </div>"
         );
      }
   }

</script>