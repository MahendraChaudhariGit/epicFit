<div class="row vp-form-container container-gb">
  <div class="col-sm-12" style="height: 100%">

    <input type="hidden" class="form-control" id="update-record" value="" name="update_value">
    <input type="hidden" value ="{{isset($goalDetails)?$goalid:null}}" name ="lastId" id ="last-insert-id">

    <ul id="viewport-1" class="vp-form-input-list">

      @if(!strrpos(Request::url(), '/goal-buddy/edit'))
      <li class="vp-item vp-dg-item vp-form-active" data-index="0" data-sub-index="0" data-type="radio">
        <div class="vp-input input-yes-no-btn">
          <div class="input-header text-center">
              <button type="button"  id="create-new-goal" value="create-new-goal" class="btn btn-primary create-new-goal" ng-click="jumpToCreateGoal()">
                CREATE NEW GOAL <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <button type="button" class="btn btn-primary choose-from-templates" ng-click="jumpToNextInput()">
                CHOOSE FROM ONE OF OUR TEMPLATES <i class="fa fa-check" aria-hidden="true"></i>
              </button>
          </div> <!-- end: INPUT HEADER -->
        </div> <!-- end: INPUT MALE FEMALE -->
        <div class="clear-both"></div>
      </li>
      @else
       <li class="vp-item vp-dg-item vp-form-active adjust-goal-create-options " data-index="0" data-sub-index="0">
       </li>
      @endif

      <!-- start: goal_modal | 0 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="1" data-sub-index="1" data-type="radio" data-valid="@{{goalBuddy.goal_modal.$valid}}">
        <div class="vp-input input-yes-no-btn" style="opacity: 1">
          {{-- <h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3><i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Don't know how to define your goal?</span></h3>

            {{--<i ng-if="input.icon" class="{{ input.icon }} title-icon" aria-hidden="true"></i>--}}
            {{--<img ng-if="input.iconUrl" class="title-icon" src="{{ input.iconUrl }}" alt="USER">--}}

            <div class="description mb">
              Choose one from a template:
            </div> <!-- end: DESCRIPTION -->
          </div> <!-- end: INPUT HEADER -->


          <div class="input-body mb">
            <script>
                $(document).ready(function() {
                    var m_gb_template = "{{ isset($goalDetails) ? $goalDetails->gb_template : null }}";
                    if(m_gb_template !== 'null') {
                        window.gbData.radio[0].value = m_gb_template;
                        var mGBTmpActiveOption = -1;

                        if(m_gb_template === '1') {
                            mGBTmpActiveOption = 0;
                        } else if(m_gb_template === '2') {
                            mGBTmpActiveOption = 1;
                        } else if(m_gb_template === '3') {
                            mGBTmpActiveOption = 2;
                        } else if(m_gb_template === '4') {
                            mGBTmpActiveOption = 3;
                        } else if(m_gb_template === '5') {
                            mGBTmpActiveOption = 4;
                        } else if(m_gb_template === '6') {
                            mGBTmpActiveOption = 5;
                        } else if(m_gb_template === '7') {
                            mGBTmpActiveOption = 6;
                        } else if(m_gb_template === '8') {
                            mGBTmpActiveOption = 7;
                        } else if(m_gb_template === '9') {
                            mGBTmpActiveOption = 8;
                        } else if(m_gb_template === '10') {
                            mGBTmpActiveOption = 9;
                        } else if(m_gb_template === '11') {
                            mGBTmpActiveOption = 10;
                        } else if(m_gb_template === '12') {
                            mGBTmpActiveOption = 11;
                        } else if(m_gb_template === '13') {
                            mGBTmpActiveOption = 12;
                        } else if(m_gb_template === '14') {
                            mGBTmpActiveOption = 13;
                        } else if(m_gb_template === '15') {
                            mGBTmpActiveOption = 14;
                        } else if(m_gb_template === '16') {
                            mGBTmpActiveOption = 15;
                        } 


                        window.gbData.radio[0].activeOption = mGBTmpActiveOption;

                        window.digestGb();

                    }

                    $('.mainheading').show();
                    $('.subheading').show();
                    $('.option_value').show();
                   
                });
            </script>
            <ul class="click-box clear-both dib goal-predifine-template">
              <li  ng-repeat="option in data.radio[0].options" id="bgUrl" data-goal-template-id="@{{option.goal_template_id}}"  ng-click="setRadioValue(0, $index)" class="goalTemplate @if(strrpos(Request::url(), '/goal-buddy/edit')) disabled_task_recurrence @endif @{{ (data.radio[0].activeOption == $index) ? 'active' : '' }}" data-m-active="@{{ data.radio[0].activeOption }}" data-m-index="@{{ $index }}" style="background-image: url('{{ url('/').'/' }}@{{option.image_url}}');">


      
                <div class="box-content">
                  <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value option_value">
                        <span style="display:none" contenteditable strip-br="true" required ng-model="option.customValue">@{{ option.value }}</span><br>
                    <span class="btn btn-success btn-xs" ng-click="updateRadioOptionValue($event, 0, $index)">Ok</span>
                  </p>
                  <p style="display:none" ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value option_value">@{{ option.value }}</p>
                </div>              
                <strong style="display:none" class="mainheading">@{{ option.label.split(' ')[0] }}</strong>
                <span  style="display:none" class="subheading">@{{ option.label.split(' ')[1] }} @{{ option.label.split(' ')[2] }}</span>
              </li>
            </ul>
            {{-- <ul class="click-box clear-both dib">
              <li ng-repeat="option in data.radio[0].options" ng-click="setRadioValue(0, $index)" class="@{{ (data.radio[0].activeOption == $index) ? 'active' : '' }}" data-m-active="@{{ data.radio[0].activeOption }}" data-m-index="@{{ $index }}">
                <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                <div class="box-content">
                  <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                  <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>

                  <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                        <span contenteditable
                              strip-br="true"
                              required ng-model="option.customValue">@{{ option.value }}</span><br>
                    <span class="btn btn-success btn-xs" ng-click="updateRadioOptionValue($event, 0, $index)">Ok</span>
                  </p>
                  <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                </div> <!-- end: BOX CONTENT -->

                <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                @{{ option.label }}
              </li>
            </ul> --}} <!-- end: CLICK BOX -->

            <input type="hidden" ng-keypress="pressEnter($event)" id="goal-template" name="goal_modal" ng-value="data.radio[0].value" ng-model="goal_modal" placeholder="" class="form-control mb">

          {{--   <div ng-if="goalBuddy.goal_modal.$touched && goalBuddy.goal_modal.$invalid" class="vp-tooltip">
              <span>This field is required!</span>
            </div> --}}
          </div> <!-- end: INPUT BODY -->

        </div> <!-- end: INPUT MALE FEMALE -->
        <div class="clear-both"></div>
      </li>
      <!-- end: goal_modal | 0 -->


      <!-- start: name_goal | 1 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="2" data-sub-index="null" data-type="text" data-valid="@{{goalBuddy.name_goal.$valid}}">
        <div class="vp-input input-text-name">
          {{-- <h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span></i> Name Your <b>Goal</b>?</span> <sup>*</sup>
              <!-- description -->
              {{--<span class="description"><br>Here will be the description of the form!</span>--}}
            </h3>

            <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i>
          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                {{-- <input type="text" data-toggle="tooltip" title="Name Your Goal" class="form-control" id="name_goal" ng-model="name_goal"  ng-init="name_goal='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="name_goal" required> --}}
                <textarea ng-mouseenter="pressEnter($event)" rows="3" data-toggle="tooltip"  data-html="true" title="You have selected to create your own unique goal, a short and<br> definitive name of the goal you want achieve can help <br>bring it to life and make it reality. <br>Example may include: <b style='color:#f64c1e;'>Master Box Jump</b>, <b style='color:#f64c1e;'>Improve 100m Sprint</b>.<br> Choose a name that best describe your goal and the way you envision it." data-autoresize id="name_goal" name="name_goal" ng-model="name_goal" ng-init="name_goal='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}</textarea>
              </div>
            </div>

            {{-- <div ng-if="goalBuddy.name_goal.$touched && goalBuddy.name_goal.$invalid && goalBuddy.name_goal.$dirty" class="vp-tooltip">
              <span>This field is required!</span>
            </div> --}}

            <div id="goal_btn" ng-show="goalBuddy.name_goal.$valid" class="enter-btn mti-15 active">
              <button  type="button" class="btn btn-primary"  ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">press <b>ENTER</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->
        <div class="clear-both"></div>
      </li>
      <!-- end: name_goal | 1 -->


      <!-- start: describe_achieve | 2 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="3" data-sub-index="null" data-type="textarea" data-valid="@{{goalBuddy.describe_achieve.$valid}}">
        <div class="vp-input input-textarea">
          {{-- <h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Describe what you want to achieve</span>
              <!-- description -->
              {{--<span ng-if="input.description" class="description"><br>{{ input.description }}</span>--}}
            </h3>

            <i class="fa fa-trophy fa-5x title-icon" aria-hidden="true"></i>
            {{--<img ng-if="input.iconUrl" class="title-icon" src="{{ input.iconUrl }}" alt="Image">--}}
          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb" >
            <div class="row">
              <div class="col-sm-8">
                <textarea ng-blur="pressEnter($event)" rows="3" id="description" data-toggle="tooltip" data-html="true" title="Describe your desired result or outcome, including the changes you wish to<br> see along the way, and what aspect of the goal matters to you most. <b style='color:#f64c1e;'>For example,<br> if your goal is to limit stress, <br>describe exactly what type of stress you are wanting to work on.<br><br> Is that what you wanting to remove self from 50% of the stressful <br>situation you currently find yourself in weekly?<br><br> Do you want to learn to switch off, rather than bringing<br> work stress into home environment?<b>" data-autoresize id="describe_achieve" name="describe_achieve" ng-model="describe_achieve" ng-init="describe_achieve='{{ isset($goalDetails) ? $goalDetails->gb_achieve_description: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_achieve_description:null}}</textarea>
              </div>
            </div>

            {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}

            <div class="vp-tooltip checkValue" style="display:none;">
              <span>Please, insert the correct value! <br></span>
            </div>

            <!-- start: PHOTO CROPPER -->
            <div class="col-xs-12 padding-left-20 custom-padding">
              <div class="form-group upload-group m-t-10" style="padding-left: 3px">
                <input type="hidden" name="prePhotoName" value="{{isset($goalDetails)?$goalDetails->gb_image_url:null}}">
                <input type="hidden" name="entityId" value="">
                <input type="hidden" name="saveUrl" value="photo/save" >
                <input type="hidden" name="photoHelper" value="SYG" >
                <input type="hidden" name="cropSelector" value="">
                <label class="btn btn-primary btn-file add-photo"> <span><i class="fa fa-plus"></i> Add Photo</span>
                  <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
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
            </div> <!-- end col12 -->
            <!-- end: PHOTO CROPPER -->

            <div id="btn_achieve" ng-show="goalBuddy.describe_achieve.$valid" class="enter-btn active">
              <button  type="button" class="btn btn-primary"  ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->

        <div class="clear-both"></div>
      </li>
      <!-- end: describe_achieve | 2 -->



      <!-- start: goal_year | 3 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="4" data-sub-index="1" data-type="radio" data-valid="@{{goalBuddy.goal_year.$valid}}">
        <div class="vp-input input-male-female">
          {{-- <h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3 data-toggle="tooltip" data-html="true" title="Are there are other more pressing or important goals that<br> may have immidiate and negative affects relating to this choosen goal?<br> And are you willing to prioritise this goal and<br> associated tasks over other daily tasks and life events?<br><br>Example of prioritising your goal include: Sacrificing 2-3 coffees per week,<br>as a financial sacrifice to cover an extra training session per week.<br>Or Sacrificing time spent with friends who may not<br>be currently positive contributing to your new lifestyle."><i class="fa fa-arrow-right"  aria-hidden="true"></i> <span>Is this goal an immediate priority for you? (maximum 3)</span></h3>
          </div> <!-- end: INPUT HEADER -->


          <div class="input-body mb">
            <ul class="click-box clear-both dib">
              <script>
                $(document).ready(function() {
                   var m_goal_year = "{{ isset($goalDetails) ? $goalDetails->gb_is_top_goal : 'null' }}";

                   if(m_goal_year !== 'null') {
                      window.gbData.radio[1].value = m_goal_year;

                      if(m_goal_year === 'yes') {
                          window.gbData.radio[1].activeOption = 0;
                      } else if(m_goal_year === 'no') {
                          window.gbData.radio[1].activeOption = 1;
                      }

                       window.digestGb();
                   }
                });
              </script>
              <li  class="is-this-goal-imidiate-priority @{{ (data.radio[1].activeOption == $index) ? 'active' : '' }}" ng-repeat="option in data.radio[1].options" ng-click="setRadioValue(1, $index)" data-m-active="@{{ data.radio[1].activeOption }}" data-m-index="@{{ $index }}">
                <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                <div class="box-content">
                  <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                  <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>

                  <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                        <span contenteditable
                              strip-br="true"
                              required ng-model="option.customValue">@{{ option.value }}</span><br>
                    <span class="btn btn-success btn-xs" ng-click="updateRadioOptionValue($event, 1, $index)">Ok</span>
                  </p>
                  <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                </div> <!-- end: BOX CONTENT -->

                <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                @{{ option.label }}
              </li>
            </ul> <!-- end: CLICK BOX -->

            <input type="hidden" ng-keypress="pressEnter($event)" id="goal_year0" name="goal_year" ng-value="data.radio[1].value" ng-model="goal_year" placeholder="">


            <div ng-if="goalBuddy.goal_year.$touched && goalBuddy.goal_year.$invalid" class="vp-tooltip">
              <span>Please, insert the correct value!</span>
            </div>
          </div> <!-- end: INPUT BODY -->

        </div> <!-- end: INPUT MALE FEMALE -->
      </li>
      <!-- start: goal_year | 3 -->

      <!-- start: change_life | 4 -->
      <?php $index = 5; $sub_index = 0;?>
      <li class="vp-item vp-form-active" data-index="{{ $index }}" data-sub-index="{{ $sub_index }}" data-type="checkbox" data-valid="@{{goalBuddy.change_life.$valid}}">
        <div class="vp-input input-yes-no-btn">
          {{-- <h3 class="vp-index pull-left">{{ $index + 1 }}. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3 data-toggle="tooltip" data-html="true" title="Select or add any additional aspect of your life that<br> will be positively affected as a direct result of making positive lifestyle changes and<br> working towards your goal, and eventually reaching the ultimate end goal.<br><br>Example includes: Improved financial situation as a result of increased productivity,<br>directly linked to increased energy levels.<br><br>Improved family environment as a direct result of a healthier, happier you<br>Who no longer brings work stress into the home environment.<br><b style='color:#f64c1e;'>LIST ALL IN ORDER</b>">
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>By  <b>accomplish Your goal </b>how will your life change? <sup>*</sup></span>
            </h3>
          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb ml-0">

              <?php
              $gb_change_life_reason_details = [];

              if(isset($goalDetails)) {
                  $gb_change_life_reason_details = $goalDetails->gb_change_life_reason_details;
              }

              ?>

            <script>
                $(document).ready(function() {
                    setTimeout(function () {
                        var selected_change_life = {!! json_encode($gb_change_life_reason_details) !!};
                      
                        var checkboxIndex = {{ $sub_index }};

                        var options = getCheckBoxOptions(checkboxIndex);

                        for(var i = 0; i < selected_change_life.length; i++) {
                            var item = selected_change_life[i];

                            if(options.indexOf(item) > -1) {
                                setCheckboxValue(checkboxIndex, options.indexOf(item))
                            }
                        }
                    }, 1000);
                });
            </script>

            <div class="col-sm-8 res-pl-0">
              <ul class="yes-no-content mli-10">
                <li ng-repeat="option in data.checkbox[{{ $sub_index }}].options" ng-click="setCheckboxValue({{ $sub_index }}, $index)" class="@{{ (data.checkbox[0].activeOptions.indexOf(option.value) != '-1') ? 'active' : '' }}">

                  <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                  @{{ option.label }} &nbsp;&nbsp;&nbsp;
                  <i ng-if="option.icon" class="@{{ option.icon }}" aria-hidden="true"></i>
                  <img ng-if="option.iconUrl" src="@{{ option.iconUrl }}" alt="Image">

                <!-- -->
                 <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value block">
                    <textarea class="form-control" name="gb_change_life_reason_other">{{isset($goalDetails)?$goalDetails->gb_change_life_reason_other:''}}</textarea><br>
                  <!-- -->
                 
                </li>
              </ul> <!-- end: CLICK BOX -->
            </div> <!-- end col8 -->
            <div class="clear-both"></div>

            <input type="hidden" ng-keypress="pressEnter($event)" id="change_life" name="change_life" value="@{{ data.checkbox[0].value }}" ng-model="change_life" placeholder="" class="form-control mb" required>

            {{--<div ng-if="goalBuddy.change_life.$touched && goalBuddy.change_life.$invalid && goalBuddy.change_life.$dirty" class="vp-tooltip">--}}
              {{--<span>This field is required!</span>--}}
            {{--</div>--}}

            <div id="btn_life_change" ng-show="true" class="enter-btn mti--12 active">
              <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>

          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->
        <div class="clear-both"></div>
      </li>
      <!-- end: change_life | 4 -->

      <!-- start: accomplish | 5 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="6" data-sub-index="null" data-type="textarea" data-valid="@{{goalBuddy.accomplish.$valid}}">
        <div class="vp-input input-textarea">
          {{-- <h3 class="vp-index pull-left">6. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Why is it important to <b>accomplish</b> this goal?</span>
            </h3>

            <i class="fa fa-key fa-5x title-icon" aria-hidden="true"></i>

          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <textarea ong-blur="pressEnter($event)" data-toggle="tooltip" data-html="true" title="Why is this an intrinsic goal?, how long have you wanted to achieve this goal for?<br>And what are the positive effects on all aspects of your life as a result of achieving it?<br><br>Example includes: Feeling Confident, Looking and feeling stronger." data-autoresize rows="3" id="accomplish" name="accomplish" ng-model="accomplish" ng-init="accomplish='{{ isset($goalDetails) ? $goalDetails->gb_important_accomplish: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_important_accomplish:null}}</textarea>
              </div>
            </div>

            {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}

            <div class="vp-tooltip imp_accomplish" style="display: none;">
              <span>Please, insert the correct value! <br></span>
            </div>

            <div id="btn_accomplish" ng-show="goalBuddy.accomplish.$valid" class="enter-btn active">
              <button  type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->

        <div class="clear-both"></div>
      </li>
      <!-- end: accomplish | 5 -->



      <!-- start: fail-description | 6 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="7" data-sub-index="null" data-type="textarea" data-valid="@{{true}}">
        <div class="vp-input input-textarea">
          {{-- <h3 class="vp-index pull-left">7. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>What happen if you do not <b>achieve your goal</b>?</span>
            </h3>

            <i class="fa fa-circle-o-notch fa-5x title-icon" aria-hidden="true"></i>

          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="Questions to ask your self:<br>How does maintaining the status quo affect you?<br>Can you maintain your current lifestyle for the next 5, 10, 15 years?<br>Are your loved ones around you willing to put up with your failure to change?<br>Is it negatively affecting them?" data-autoresize rows="3" id="fail-description" name="fail-description" ng-model="fail_description" ng-init="fail_description='{{ isset($goalDetails) ? $goalDetails->gb_fail_description: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_fail_description:null}}</textarea>
              </div>
            </div>

            {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}

            <div class="vp-tooltip description_details" style="display: none;">
              <span>Please, insert the correct value! <br></span>
            </div>

            <div id="btn_fail-description" ng-show="true" class="enter-btn active">
              <button  type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->

        <div class="clear-both"></div>
      </li>
      <!-- end: fail-description | 6 -->



      <!-- start: gb_relevant_goal | 7 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="8" data-sub-index="null" data-type="textarea" data-valid="@{{goalBuddy.gb_relevant_goal.$valid}}">
        <div class="vp-input input-textarea">
          {{-- <h3 class="vp-index pull-left">8. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Why is this goal <b>relevant?</b></span>
            </h3>

            <i class="fa fa-link fa-5x title-icon" aria-hidden="true"></i>
          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <textarea ng-blur="pressEnter($event)" id="relevant_goal" data-toggle="tooltip" title="Why is this goal more important to you than everyone else around you?<br>What internal chnages will you fill that other may not realise initially?<br>(Why is it specific your current position)<br>Are you doing this for you are as a request from someone else?" data-autoresize rows="3" id="gb_relevant_goal" name="gb_relevant_goal" ng-model="gb_relevant_goal" ng-init="gb_relevant_goal='{{ isset($goalDetails) ? $goalDetails->gb_relevant_goal: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_relevant_goal:null}}</textarea>
              </div>
            </div>

            {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}

            <div class="vp-tooltip goal_relevant" style="display: none;">
              <span>Please, insert the correct value! <br></span>
            </div>

            <div id="btn_relevant_goal" ng-show="goalBuddy.gb_relevant_goal.$valid" class="enter-btn active">
              <button  type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->

        <div class="clear-both"></div>
      </li>
      <!-- end: gb_relevant_goal | 7 -->



      <!-- start: gb_relevant_goal_event | 8 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="9" data-sub-index="null" data-type="textarea" data-valid="@{{goalBuddy.gb_relevant_goal_event.$valid}}">
        <div class="vp-input input-textarea">
          {{-- <h3 class="vp-index pull-left">9. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Is this goal associated with a <b>life event or special occasion</b>?</span>
            </h3>

            <i class="fa fa-calendar-o fa-5x title-icon" aria-hidden="true"></i>
          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="Do you have any special occasion coming up?<br>Do you need to achieve this goal in time for the event?(Wedding)<br><br>Is their a larger life event or stage of life that you may be reaching that has<br>shifted you midst and helped you decide to take action?<br>(Closing in on 50's)(Possibly of children or grandchildren)" data-autoresize rows="3" id="gb_relevant_goal_event" name="gb_relevant_goal_event" ng-model="gb_relevant_goal_event" ng-init="gb_relevant_goal_event='{{ isset($goalDetails) ? $goalDetails->gb_relevant_goal_event: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_relevant_goal_event:null}}</textarea>
              </div>
            </div>

            {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}
           <div class="vp-tooltip gb_relevant_event" style="display: none;">
              <span>Please, insert the correct value! <br></span>
            </div> 

            <div id="btn_relevant_goal_event" ng-show="goalBuddy.gb_relevant_goal_event.$valid" class="enter-btn active">
              <button  type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->

        <div class="clear-both"></div>
      </li>
      <!-- end: gb_relevant_goal_event | 8 -->



      <!-- start: due_date | 9 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="10" data-sub-index="null" data-type="text" data-valid="@{{goalBuddy.due_date.$valid}}">
        <div class="vp-input input-text-name">
          {{-- <h3 class="vp-index pull-left">10. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span></i> What is the <b>due date</b> for this goal?</span> <sup>*</sup>
            </h3>

            <i class="fa fa-calendar fa-5x title-icon" aria-hidden="true"></i>
          </div> <!-- end: INPUT HEADER -->

          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <div class="date">
                  <input type="text" data-toggle="tooltip" title="Date Selector - Be sure to select a realistic due date for your goal and<br> do nou set your self up failure before beginning. You must take into account<br> your willingness to commit to your habit related tasks." class="form-control vdp" name="due_date" id='datepicker_SYG' ng-model="due_date" ng-init="due_date='{{ isset($goalDetails) ? $goalDetails->goal_due_date : null }}'" ng-keypress="pressEnter($event)" autocomplete="off" value="{{isset($goalDetails)? $goalDetails->goal_due_date:null}}"  required>
                </div>
              </div>
            </div>


           {{--  <div ng-if="goalBuddy.due_date.$touched && goalBuddy.due_date.$invalid && goalBuddy.due_date.$dirty" class="vp-tooltip">
              <span>This field is required!</span>
            </div> --}}

            <div id="btn_duedate" ng-show="goalBuddy.due_date.$valid" class="enter-btn mti-15 active">
              <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->
        <div class="clear-both"></div>
      </li>
      <!-- end: due_date | 9 -->



      <!-- start: goal_seen | 10 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="11" data-sub-index="2" data-type="radio" data-valid="@{{goalBuddy.goal_seen.$valid}}">
        <div class="vp-input input-male-female">
          {{-- <h3 class="vp-index pull-left">11. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3 data-toggle="tooltip" title="T.E.A.M Together Everyone Active More. The EPIC RESULT online platform is designed to connect<br> and interact with fellow team members and like-minded individuals on the same or<br> similar journey to your own. Having the others view your goal gives<br> you apportunity to be given feedback and encouragement throughout your journey.<br> Allow others to allow you to celebrate your success with you!<br><br><ul><li>Accountability is key, fellow result members can help hold you accountable<br> when it comes to attending training sessions and completing your goal related which directly<br>relate to your goal progression.</li><li><b style='color:#f64c1e;'>Everyone </b>- Share details and goal with friends and family<br>OR <b style='color:#f64c1e;'>Just Me</b> - Only show me details and goal</li></ul>"><i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Who can view <b>your goal</b>?</span></h3>

            {{--<!--<i ng-if="input.icon" class="{{ input.icon }} title-icon" aria-hidden="true"></i>-->--}}
            {{--<!--<img ng-if="input.iconUrl" class="title-icon" src="{{ input.iconUrl }}" alt="USER">-->--}}

            {{--<div ng-if="input.description" class="description mb">--}}
            {{--{{ input.description }}--}}
            {{--</div> <!-- end: DESCRIPTION -->--}}
          </div> <!-- end: INPUT HEADER -->


          <div class="input-body mb">
            <ul class="click-box clear-both dib">
              <script>
                  $(document).ready(function() {
                      var goal_seen = "{{ isset($goalDetails) ? $goalDetails->gb_goal_seen : 'null' }}";
                      if(goal_seen !== 'null') {
                          window.gbData.radio[2].value = goal_seen;

                          if(goal_seen === 'Everyone') {
                              window.gbData.radio[2].activeOption = 0;
                          } else if(goal_seen === 'Just Me') {
                              window.gbData.radio[2].activeOption = 1;
                          }

                          window.digestGb();
                      }
                  });
              </script>
              <li class="who-can-view @{{ (data.radio[2].activeOption == $index) ? 'active' : '' }}" ng-repeat="option in data.radio[2].options" ng-click="setRadioValue(2, $index)">
                <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                <div class="box-content">
                  <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                  <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>

                  <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                        <span contenteditable
                              strip-br="true"
                              required ng-model="option.customValue">@{{ option.value }}</span><br>
                    <span class="btn btn-success btn-xs" ng-click="updateRadioOptionValue($event, 2, $index)">Ok</span>
                  </p>
                  <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                </div> <!-- end: BOX CONTENT -->

                <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                @{{ option.label }}
              </li>
            </ul> <!-- end: CLICK BOX -->

            <input type="hidden" ng-keypress="pressEnter($event)" id="see_task0" name="goal_seen" ng-value="data.radio[2].value" ng-model="goal_seen" placeholder="" class="form-control mb">

            <div ng-if="goalBuddy.goal_seen.$touched && goalBuddy.goal_seen.$invalid" class="vp-tooltip">
              <span>Please, insert the correct value!</span>
            </div>
          </div> <!-- end: INPUT BODY -->

        </div> <!-- end: INPUT MALE FEMALE -->
      </li>
      <!-- start: goal_seen | 10 -->


      <!-- start: send_msgss | 11 -->
      <li class="vp-item vp-dg-item vp-form-active" data-index="12" data-sub-index="3" data-type="radio" data-valid="@{{goalBuddy.send_msgss.$valid}}">
        <div class="vp-input input-male-female">
          {{-- <h3 class="vp-index pull-left">12. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3 data-toggle="tooltip" title="At EPIC we know that life can sometimes get the better of the best of us,<br>reminders keep you accountable for you actions, time management, and overall results. Its <br> important to come to the realisation that actions that may seem insignificant such as missing a training session,<br>skipping a meal or altering guidelines can have adverse accumulative effects<br> on your<br> over progress, the earlier it is noticed by you by being given a reminder from <br> the EPIC team the sooner you can get back on track.<br><br>Approach every goal related task with the utmost importance as if others<br> are relying on you, because YOU are relying on YOU!<br><br><b style='color:#f64c1e;'>When Overdue</b> - If goals has not been met<br><b style='color:#f64c1e;'>Daily</b> - Send me message related to goal daily<br><b style='color:#f64c1e;'>Weekly</b> - Send me message related to goal weekly<br><b style='color:#f64c1e;'>Monthly</b> - Send me message related to goal monthly<br><b style='color:#f64c1e;'>None</b> - Don't send me anything"><i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Send <b>e-mail / SMS</b> reminders</span></h3>

            {{--<!--<i ng-if="input.icon" class="{{ input.icon }} title-icon" aria-hidden="true"></i>-->--}}
            {{--<!--<img ng-if="input.iconUrl" class="title-icon" src="{{ input.iconUrl }}" alt="USER">-->--}}

            {{--<div ng-if="input.description" class="description mb">--}}
            {{--{{ input.description }}--}}
            {{--</div> <!-- end: DESCRIPTION -->--}}
          </div> <!-- end: INPUT HEADER -->         
          
            <div class="input-body mb">
                <ul class="click-box clear-both dib">              
                    <li class="send-reminders goal-reminders">
                        <input type="radio" id="goal_send_Overdue" name="goal-Send-mail" class="form-control mb" value="when_overdue" @if(isset($goalDetails) && ($goalDetails->gb_reminder_type == "when_overdue")) checked @endif>
                        <label for="goal_send_Overdue">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">A</span>
                            <span class="yes-active">key <b>A</b></span>
                            <span>When Overdue</span>
                        </label>
                    </li>
                     <li class="send-reminders goal-reminders">
                        <input type="radio" id="goal_send_Daily" name="goal-Send-mail" class="form-control mb"value="daily" @if(isset($goalDetails) && ($goalDetails->gb_reminder_type == "daily")) checked @endif>
                        <label for="goal_send_Daily">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">B</span>
                            <span class="yes-active">key <b>B</b></span>
                            <span>Daily</span>
                            <div class="showTimeBox">
                                <select id="daily_time_goal">                                  
                                    <option value="1" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 1)) selected @endif>1:00 am</option>
                                    <option  value="2"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 2)) selected @endif>2:00 am</option>
                                    <option value="3"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 3)) selected @endif>3:00 am</option>
                                    <option value="4"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 4)) selected @endif>4:00 am</option>
                                    <option value="5"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 5)) selected @endif>5:00 am</option>
                                    <option value="6"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 6)) selected @endif>6:00 am</option>
                                    <option value="7"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 7)) selected @endif>7:00 am</option>
                                    <option value="9"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 9)) selected @endif>9:00 am</option>
                                    <option value="10" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 10)) selected @endif>10:00 am</option>
                                    <option value="11" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 11)) selected @endif>11:00 am</option>
                                    <option value="12" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 12)) selected @endif>12:00 PM</option>
                                    <option value="13" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 13)) selected @endif>1:00 PM</option>
                                    <option value="14" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 14)) selected @endif>2:00 PM</option>
                                    <option value="15" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 15)) selected @endif>3:00 PM</option>
                                    <option value="16" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 16)) selected @endif>4:00 PM</option>
                                    <option value="17" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 17)) selected @endif>5:00 PM</option>
                                    <option value="18" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 18)) selected @endif>6:00 PM</option>
                                    <option value="19" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 19)) selected @endif>7:00 PM</option>
                                    <option value="20" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 20)) selected @endif>8:00 PM</option>
                                    <option value="21" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 21)) selected @endif>9:00 PM</option>
                                    <option value="22" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 22)) selected @endif>10:00 PM</option>
                                    <option value="23" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 23)) selected @endif>11:00 PM</option>
                                    <option value="24" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 24)) selected @endif>12:00 am</option>
                                </select>
                            </div>
                        </label>
                    </li>
                     <li class="send-reminders goal-reminders">
                        <input type="radio" id="goal_send_Weekly" name="goal-Send-mail" class="form-control mb" value="weekly" @if(isset($goalDetails) && ($goalDetails->gb_reminder_type == "weekly")) checked @endif>
                        <label for="goal_send_Weekly">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">C</span>
                            <span class="yes-active">key <b>C</b></span>
                            <span>Weekly</span>
                            <div class="showDayBox">
                                <select id="weekly_day_goal">
                                    <option value="Mon" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Mon")) selected @endif>Mon</option>
                                    <option value="Tue" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Tue")) selected @endif>Tue</option>
                                    <option value="Wed" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Wed")) selected @endif>Wed</option>
                                    <option value="Thu" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Thu")) selected @endif>Thu</option>
                                    <option value="Fri" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Fri")) selected @endif>Fri</option>
                                    <option value="Sat" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Sat")) selected @endif>Sat</option>
                                    <option value="Sun" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Sun")) selected @endif>Sun</option>
                                </select>
                            </div>
                        </label>
                    </li>
                    <li class="send-reminders goal-reminders">
                        <input type="radio" id="goal_send_Monthly" name="goal-Send-mail" class="form-control mb"value="monthly" @if(isset($goalDetails) && ($goalDetails->gb_reminder_type == "monthly")) checked @endif>
                        <label for="goal_send_Monthly">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">D</span>
                            <span class="yes-active">key <b>D</b></span>
                            <span>Monthly</span>
                            <div class="showMonthBox">
                                <select id="month_date_goal">
                                  <option value="1" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "1")) selected @endif>1</option>
                                  <option value="2" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "2")) selected @endif>2</option>
                                  <option value="3" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "3")) selected @endif>3</option>
                                  <option value="4" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "4")) selected @endif>4</option>
                                  <option value="5" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "5")) selected @endif>5</option>
                                  <option value="6" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "6")) selected @endif>6</option>
                                  <option value="7" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "7")) selected @endif>7</option>
                                  <option value="8" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "8")) selected @endif>8</option>
                                  <option value="9" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "9")) selected @endif>9</option>
                                  <option value="10" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "10")) selected @endif>10</option>
                                  <option value="11" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "11")) selected @endif>11</option>
                                  <option value="12" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "12")) selected @endif>12</option>
                                  <option value="13" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "13")) selected @endif>13</option>
                                  <option value="14" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "14")) selected @endif>14</option>
                                  <option value="15" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "15")) selected @endif>15</option>
                                  <option value="16" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "16")) selected @endif>16</option>
                                  <option value="17" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "17")) selected @endif>17</option>
                                  <option value="18" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "18")) selected @endif>18</option>
                                  <option value="19" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "19")) selected @endif>19</option>
                                  <option value="20" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "20")) selected @endif>20</option>
                                  <option value="21" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "21")) selected @endif>21</option>
                                  <option value="22" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "22")) selected @endif>22</option>
                                  <option value="23" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "23")) selected @endif>23</option>
                                  <option value="24" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "24")) selected @endif>24</option>
                                  <option value="25" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "25")) selected @endif>25</option>
                                  <option value="26" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "26")) selected @endif>26</option>
                                  <option value="27" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "27")) selected @endif>27</option>
                                  <option value="28" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "28")) selected @endif>28</option>
                                  <option value="29" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "29")) selected @endif>29</option>
                                  <option value="30" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "30")) selected @endif>30</option>
                                </select>
                            </div>
                        </label>
                    </li>
                    <li class="send-reminders goal-reminders">
                        <input type="radio" id="goal_send_None" name="goal-Send-mail" class="form-control mb" value="none" @if(isset($goalDetails) && ($goalDetails->gb_reminder_type == "none")) checked @endif>
                        <label for="goal_send_None">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">E</span>
                            <span class="yes-active">key <b>E</b></span>
                            <span>None</span>
                        </label>
                    </li>
                </ul>              
            <div ng-if="goalBuddy.send_msgss.$touched && goalBuddy.send_msgss.$invalid" class="vp-tooltip">
              <span>Please, insert the correct value!</span>
            </div>
          </div> <!-- end: INPUT BODY -->

        </div> <!-- end: INPUT MALE FEMALE -->
      </li>
      <!-- start: send_msgss | 11 -->
    </ul> <!-- end: VP FORM INPUT LIST -->
  </div> <!-- end col12 -->

  <div class="row">
    {{--<div class="vp-form-input-list" style="display: inline-block; width: 100%; margin-top: 150px">--}}
      {{--<div class="col-sm-8 col-sm-offset-2 submit">--}}
        {{--<a href="javascript:void(0)" ng-click="jumpToNextInput()" class="btn btn-lg btn-primary">Submit</a> <span class="press-enter">press <b>ENTER</b></span>--}}

        {{--<p>Never submit passwords! - <a href="#">Report abuse</a></p>--}}
      {{--</div> <!-- end: COL8 || SUBMIT -->--}}
    {{--</div>--}}

    <div class="vp-progress-bar">
      <div class="col-sm-10 col-sm-offset-2 vp-progress">
        <div class="vp-progress-content">
          <p>@{{ percentCompleted }}% complete</p>
          <progress value="@{{ percentCompleted }}" max="100"> </progress>
        </div> <!--  -->
        <div class="create-type-form">
          <a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
          <a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true">Back</i></a>
          <a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true">Next</i></a>
        </div> <!--  -->
      </div> <!-- end: COL8 || SUBMIT -->
    </div>
  </div>
</div> <!-- end vp form container | end row -->
