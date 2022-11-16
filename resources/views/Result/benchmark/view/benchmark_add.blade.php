

<div id="form-container" class="container-fluid container-fullw bg-white">
    <div class="row">
        <div class="col-md-12">

            <!-- start: WIZARD FORM -->
            <div>
                <div id="benchmarkWizard" class="swMain">
                    <!-- start: WIZARD SEPS -->
                    <ul class="hidden">
                        <li>
                            <a href="#step-1">
                                <div class="stepNumber">
                                    1
                                </div>
                                <span class="stepDesc"><small> New Progression Session </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-2">
                                <div class="stepNumber">
                                    2
                                </div>
                                <span class="stepDesc"><small> External Factors </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-3">
                                <div class="stepNumber">
                                    3
                                </div>
                                <span class="stepDesc"><small> Measurements </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-4">
                                <div class="stepNumber">
                                    4
                                </div>
                                <span class="stepDesc"><small> Fitness Testing </small></span>
                            </a>
                        </li>
                    </ul>
                    <!-- end: WIZARD SEPS -->

                    <!-- start: FORM WIZARD ACCORDION -->
                    <div class="panel-group epic-accordion" id="epic-accordion">
                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="1">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> New Progression Session <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>

                            </div>
                            <div class="panel-body">
                                <input id="m-selected-step" type="hidden" value="1">

                                <div id="step-1" ng-controller="BMWidgetOne">
                                    <?php
                                   //dd($benchmarks);
                                        if(count($benchmarks) > 0) {
                                            $m_benchmark = $benchmarks[0];
                                        }
                                    //dd($m_benchmark);
                                    ?>

                                    

                                    {!! Form::open(['url' => '', 'id' => 'form-1', 'name' => 'benchmarkOne']) !!}
                                    {!! displayAlert('', true)!!}
                                    <div class="row vp-form-container container-bm-step-1">
                                        <div class="col-sm-12" style="height: 100%">

                                            <input type="hidden" name="benchmarkEditId" id="abc" value="">
                                            
                                           
                                    
                                            <ul id="viewport-1" class="vp-form-input-list">
                                                <!-- start: goal_modal | 0 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="0" data-sub-index="null" data-type="null" data-valid="@{{ ((benchmarkOne.bm_time_day.$valid && benchmarkOne.bm_time_hour.$valid) || bm_time_opt == 'Automatic Time Entry') ? true : false }}" style="min-height: 100px">
                                                    <div class="vp-input input-yes-no-btn">
                                                        {{--<h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>--}}

                                                        <div class="input-header">
                                                            {{--<h3><i class="fa fa-arrow-right" aria-hidden="true"></i> <span>New Progression Session</span></h3>--}}
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb ml-0" style="margin: 50px 18px 0px">
                                                            <div class="row progression">
                                                                <ul class="bm_time_selectable">
                                                                    <li  class="col-xs-6 ui-widget-content ui-selected manual" >Manual Time Entry</li>
                                                                    <li class="col-xs-6 ui-widget-content automatic">Automatic Time Entry</li>

                                                                    <input type="hidden" name="bm_time_opt" value="Manual Time Entry" autocomplete="off">
                                                                </ul>
                                                            </div>

                                                            <div class="form-group bm_time_manual">
                                                                {!! Form::label('bm_time_day', 'Day *', ['class' => 'strong']) !!}
                                                                {!! Form::text('bm_time_day', null, ['class' => 'form-control mli-0', 'required' => 'required','autocomplete' => 'off','readonly' => 'true', 'ng-model' => 'bm_time_day']) !!}
                                                                <span class ="error"></span>
                                                            </div>

                                                            <div class="form-group bm_time_manual clearfix">
                                                                {!! Form::label(null, 'Time *', ['class' => 'strong']) !!}
                                                                <div class="row">
                                                                    <div class="col-md-6 form-group time_hour">
                                                                        <select class="form-control hour-value" name="bm_time_hour" ng-model="bm_time_hour" id="time_hour" required="required">
                                                                            <option data-hidden="true" value ="">HOUR</option>
                                                                            <option value="00">00</option>
                                                                            <option value="01">01</option>
                                                                            <option value="02">02</option>
                                                                            <option value="03">03</option>
                                                                            <option value="04">04</option>
                                                                            <option value="05">05</option>
                                                                            <option value="06">06</option>
                                                                            <option value="07">07</option>
                                                                            <option value="08">08</option>
                                                                            <option value="09">09</option>
                                                                            <option value="10">10</option>
                                                                            <option value="11">11</option>
                                                                            <option value="12">12</option>
                                                                            <option value="13">13</option>
                                                                            <option value="14">14</option>
                                                                            <option value="15">15</option>
                                                                            <option value="16">16</option>
                                                                            <option value="17">17</option>
                                                                            <option value="18">18</option>
                                                                            <option value="19">19</option>
                                                                            <option value="20">20</option>
                                                                            <option value="21">21</option>
                                                                            <option value="22">22</option>
                                                                            <option value="23">23</option>
                                                                        </select>
                                                                        <span class=" error hour-error "></span>

                                                                    </div>
                                                                    <div class="col-md-6 form-group time_min">
                                                                        <select class="form-control min-value" name="bm_time_min" id="time_min">
                                                                            <option data-hidden="true" value = "">MINUTES</option>
                                                                            <option value="00">00</option>
                                                                            <option value="05">05</option>
                                                                            <option value="10">10</option>
                                                                            <option value="15">15</option>
                                                                            <option value="20">20</option>
                                                                            <option value="25">25</option>
                                                                            <option value="30">30</option>
                                                                            <option value="35">35</option>
                                                                            <option value="40">40</option>
                                                                            <option value="45">45</option>
                                                                            <option value="50">50</option>
                                                                            <option value="55">55</option>
                                                                        </select>
                                                                        <span class="min-error error"></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" ng-keypress="pressEnter($event)" id="goal-template" name="goal_modal" ng-value="true" ng-model="bench_modal" placeholder="" class="form-control mb">

                                                            <div ng-if="benchmarkOne.bench_modal.$touched && benchmarkOne.bench_modal.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT MALE FEMALE -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: goal_modal | 0 -->
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row mb-50-vh row-btn-step-container">
                                        <div class="col-sm-6"></div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-o bm_next-step btn-wide pull-right next-1">
                                                    Next <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="2">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> External Factors <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <div id="step-2" ng-controller="BMWidgetTwo">
                                    {!! Form::open(['url' => '', 'id' => 'form-2', 'name' => 'benchmarkTwo']) !!}

                                    <div class="row vp-form-container container-bm-step-2">
                                        <div class="col-sm-12" style="height: 100%">

                                            <ul id="viewport-2" class="vp-form-input-list">

                                                <!-- start: stress | 0 -->
                                                <li class="vp-item vp-form-active" ng-init="itemIndexZero = 0" data-index="@{{ itemIndexZero }}" data-sub-index="@{{ itemIndexZero }}" data-type="rating" data-valid="@{{ benchmarkTwo.stress.$valid }}" style="margin-top: 50px;">
                                                    <div class="vp-input input-star-rating" ng-init="inputZero = data.rating[itemIndexZero]">
                                                        <h3 class="vp-index pull-left">@{{ itemIndexZero + 1 }}. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span ng-bind-html="inputZero.label"></span> <sup ng-if="inputZero.isRequired">*</sup>
                                                                <!-- description -->
                                                                <span ng-if="inputZero.description" class="description"><br>@{{ inputZero.description }}</span>
                                                            </h3>
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <script>
                                                                $(document).ready(function() {
                                                                    var m_stress = "{{ isset($m_benchmark) ? $m_benchmark->stress : 'null' }}";
//                                                                    console.log(m_stress, " Stress...")

//                                                                    if(m_goal_year !== 'null') {
//                                                                        window.gbData.radio[1].value = m_goal_year;
//
//                                                                        if(m_goal_year === 'yes') {
//                                                                            window.gbData.radio[1].activeOption = 0;
//                                                                        } else if(m_goal_year === 'no') {
//                                                                            window.gbData.radio[1].activeOption = 1;
//                                                                        }
//
//                                                                        window.digestGb();
//                                                                    }
                                                                });
                                                            </script>
                                                            <ul class="star-rating">
                                                                <li ng-repeat="i in range(1, inputZero.itemCount + 1)">
                                                                    <i ng-if="i <= inputZero.value" class="@{{ inputZero.iconFill }}" aria-hidden="true" ng-click="setRatingValue(itemIndexZero, i)"></i>
                                                                    <i ng-if="i > inputZero.value" class="@{{ inputZero.icon }}" aria-hidden="true" ng-click="setRatingValue(itemIndexZero, i)"></i>

                                                                    <br><br> <span class="select-multiple"><b>@{{ i }}</b></span>
                                                                </li>
                                                            </ul> <!-- end: STAR RATING -->

                                                            <input type="hidden" class="stress form-control mb" autocomplete="off" ng-keypress="pressEnter($event)" name="@{{ inputZero.name }}" value="@{{ inputZero.value }}" ng-model="inputZero.value" placeholder="@{{ inputZero.placeholder }}"  required>

                                                            <div ng-if="benchmarkTwo.stress.$touched && benchmarkTwo.stress.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                        </div> <!-- end: INPUT BODY -->
                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: stress | 0 -->

                                                <!-- start: sleep | 1 -->
                                                <li class="vp-item vp-form-active" ng-init="itemIndexOne = 1" data-index="@{{ itemIndexOne }}" data-sub-index="@{{ itemIndexOne }}" data-type="rating" data-valid="@{{ benchmarkTwo.sleep.$valid }}">
                                                    <div class="vp-input input-star-rating" ng-init="inputOne = data.rating[itemIndexOne]">
                                                        <h3 class="vp-index pull-left">@{{ itemIndexOne + 1 }}. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span ng-bind-html="inputOne.label"></span> <sup ng-if="inputOne.isRequired">*</sup>
                                                                <!-- description -->
                                                                <span ng-if="inputOne.description" class="description"><br>@{{ inputOne.description }}</span>
                                                            </h3>
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <ul class="star-rating">
                                                                <li ng-repeat="i in range(1, inputOne.itemCount + 1)">
                                                                    <i ng-if="i <= inputOne.value" class="@{{ inputOne.iconFill }}" aria-hidden="true" ng-click="setRatingValue(itemIndexOne, i)"></i>
                                                                    <i ng-if="i > inputOne.value" class="@{{ inputOne.icon }}" aria-hidden="true" ng-click="setRatingValue(itemIndexOne, i)"></i>

                                                                    <br><br> <span class="select-multiple"><b>@{{ i }}</b></span>
                                                                </li>
                                                            </ul> <!-- end: STAR RATING -->

                                                            <input type="hidden" class="sleep form-control mb" autocomplete="off" ng-keypress="pressEnter($event)" name="@{{ inputOne.name }}" value="@{{ inputOne.value }}" ng-model="inputOne.value" placeholder="@{{ inputOne.placeholder }}" required>

                                                            <div ng-if="benchmarkTwo.sleep.$touched && benchmarkTwo.sleep.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                        </div> <!-- end: INPUT BODY -->
                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: sleep | 1 -->

                                                <!-- start: nutrition | 2 -->
                                                <li class="vp-item vp-form-active" ng-init="itemIndexTwo = 2" data-index="@{{ itemIndexTwo }}" data-sub-index="@{{ itemIndexTwo }}" data-type="rating" data-valid="@{{ benchmarkTwo.nutrition.$valid }}">
                                                    <div class="vp-input input-star-rating" ng-init="inputTwo = data.rating[itemIndexTwo]">
                                                        <h3 class="vp-index pull-left">@{{ itemIndexTwo + 1 }}. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span ng-bind-html="inputTwo.label"></span> <sup ng-if="inputTwo.isRequired">*</sup>
                                                                <!-- description -->
                                                                <span ng-if="inputTwo.description" class="description"><br>@{{ inputTwo.description }}</span>
                                                            </h3>
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <ul class="star-rating">
                                                                <li ng-repeat="i in range(1, inputTwo.itemCount + 1)">
                                                                    <i ng-if="i <= inputTwo.value" class="@{{ inputTwo.iconFill }}" aria-hidden="true" ng-click="setRatingValue(itemIndexTwo, i)"></i>
                                                                    <i ng-if="i > inputTwo.value" class="@{{ inputTwo.icon }}" aria-hidden="true" ng-click="setRatingValue(itemIndexTwo, i)"></i>

                                                                    <br><br> <span class="select-multiple"><b>@{{ i }}</b></span>
                                                                </li>
                                                            </ul> <!-- end: STAR RATING -->

                                                            <input type="hidden" class="nutrition form-control mb" autocomplete="off" ng-keypress="pressEnter($event)" name="@{{ inputTwo.name }}" value="@{{ inputTwo.value }}" ng-model="inputTwo.value" placeholder="@{{ inputTwo.placeholder }}" required>

                                                            <div ng-if="benchmarkTwo.nutrition.$touched && benchmarkTwo.nutrition.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                        </div> <!-- end: INPUT BODY -->
                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: nutrition | 2 -->

                                                <!-- start: hydration | 3 -->
                                                <li class="vp-item vp-form-active" ng-init="itemIndexThree = 3" data-index="@{{ itemIndexThree }}" data-sub-index="@{{ itemIndexThree }}" data-type="rating" data-valid="@{{ benchmarkTwo.hydration.$valid }}">
                                                    <div class="vp-input input-star-rating" ng-init="inputThree = data.rating[itemIndexThree]">
                                                        <h3 class="vp-index pull-left">@{{ itemIndexThree + 1 }}. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span ng-bind-html="inputThree.label"></span> <sup ng-if="inputThree.isRequired">*</sup>
                                                                <!-- description -->
                                                                <span ng-if="inputThree.description" class="description"><br>@{{ inputThree.description }}</span>
                                                            </h3>
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <ul class="star-rating">
                                                                <li ng-repeat="i in range(1, inputThree.itemCount + 1)">
                                                                    <i ng-if="i <= inputThree.value" class="@{{ inputThree.iconFill }}" aria-hidden="true" ng-click="setRatingValue(itemIndexThree, i)"></i>
                                                                    <i ng-if="i > inputThree.value" class="@{{ inputThree.icon }}" aria-hidden="true" ng-click="setRatingValue(itemIndexThree, i)"></i>

                                                                    <br><br> <span class="select-multiple"><b>@{{ i }}</b></span>
                                                                </li>
                                                            </ul> <!-- end: STAR RATING -->

                                                            <input type="hidden" class="hydration form-control mb" autocomplete="off" ng-keypress="pressEnter($event)" name="@{{ inputThree.name }}" value="@{{ inputThree.value }}" ng-model="inputThree.value" placeholder="@{{ inputThree.placeholder }}"  required>

                                                            <div ng-if="benchmarkTwo.hydration.$touched && benchmarkTwo.hydration.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                        </div> <!-- end: INPUT BODY -->
                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: hydration | 3 -->

                                                <!-- start: humidity | 4 -->
                                                <li class="vp-item vp-form-active" ng-init="itemIndexFour = 4" data-index="@{{ itemIndexFour }}" data-sub-index="@{{ itemIndexFour }}" data-type="rating" data-valid="@{{ benchmarkTwo.humidity.$valid }}">
                                                    <div class="vp-input input-star-rating" ng-init="inputFour = data.rating[itemIndexFour]">
                                                        <h3 class="vp-index pull-left">@{{ itemIndexFour + 1 }}. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span ng-bind-html="inputFour.label"></span> <sup ng-if="inputFour.isRequired">*</sup>
                                                                <!-- description -->
                                                                <span ng-if="inputFour.description" class="description"><br>@{{ inputFour.description }}</span>
                                                            </h3>
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <ul class="star-rating">
                                                                <li ng-repeat="i in range(1, inputFour.itemCount + 1)">
                                                                    <i ng-if="i <= inputFour.value" class="@{{ inputFour.iconFill }}" aria-hidden="true" ng-click="setRatingValue(itemIndexFour, i)"></i>
                                                                    <i ng-if="i > inputFour.value" class="@{{ inputFour.icon }}" aria-hidden="true" ng-click="setRatingValue(itemIndexFour, i)"></i>

                                                                    <br><br> <span class="select-multiple"><b>@{{ i }}</b></span>
                                                                </li>
                                                            </ul> <!-- end: STAR RATING -->

                                                            <input type="hidden" class="humidity form-control mb" autocomplete="off" ng-keypress="pressEnter($event)" name="@{{ inputFour.name }}" value="@{{ inputFour.value }}" ng-model="inputFour.value" placeholder="@{{ inputFour.placeholder }}" required>

                                                            <div ng-if="benchmarkTwo.humidity.$touched && benchmarkTwo.humidity.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                        </div> <!-- end: INPUT BODY -->
                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: humidity | 4 -->

                                                <!-- start: bm_temp | 5 -->
                                                <li class="vp-item vp-form-active" data-index="5" data-sub-index="null" data-type="select" data-valid="@{{ benchmarkTwo.bm_temp.$valid }}" style="min-height: 200px">
                                                    <div class="vp-input input-star-rating">
                                                        <h3 class="vp-index pull-left">6. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Temperature</span> <sup>*</sup>
                                                            </h3>
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb ml-0">
                                                            <div class="col-sm-10 form-group ml-23 custom-select">
                                                                <select class="form-control temperature mb mli-21" id="temperatureEdit" name="bm_temp" tabindex="-98" ng-keypress="pressEnter($event)" required>
                                                                    <option value="35">35</option>
                                                                    <option value="34">34</option>
                                                                    <option value="33">33</option>
                                                                    <option value="32">32</option>
                                                                    <option value="31">31</option>
                                                                    <option value="30">30</option>
                                                                    <option value="29">29</option>
                                                                    <option value="28">28</option>
                                                                    <option value="27">27</option>
                                                                    <option value="26">26</option>
                                                                    <option value="25">25</option>
                                                                    <option value="24">24</option>
                                                                    <option value="23">23</option>
                                                                    <option value="22">22</option>
                                                                    <option value="21">21</option>
                                                                    <option value="20">20</option><option value="19">19</option><option value="18">18</option><option value="17">17</option><option value="16">16</option><option value="15">15</option><option value="14">14</option><option value="13">13</option><option value="12">12</option><option value="11">11</option><option value="10">10</option><option value="9">9</option><option value="8">8</option><option value="7">7</option><option value="6">6</option><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option><option value="1">1</option><option value="0">0</option><option value="-1">-1</option><option value="-2">-2</option><option value="-3">-3</option><option value="-4">-4</option><option value="-5">-5</option><option value="-6">-6</option><option value="-7">-7</option><option value="-8">-8</option><option value="-9">-9</option><option value="-10">-10</option>
                                                                </select>
                                                            </div>

                                                            <div ng-if="benchmarkTwo.bm_temp.$touched && benchmarkTwo.bm_temp.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                        </div> <!-- end: INPUT BODY -->
                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_temp | 5 -->

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row mb-50-vh row-btn-step-container">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-o bm_back-step btn-wide pull-left">
                                                    <i class="fa fa-circle-arrow-left"></i> Back
                                                </button>
                                            </div>
                                            <span></span>
                                        </div>
                                        <div class="col-sm-6" ng-click="validateWidgetInputs()">
                                            <div class="form-group">
                                                <button ng-disabled="benchmarkTwo.stress.$invalid ||
                                                        benchmarkTwo.sleep.$invalid ||
                                                        benchmarkTwo.nutrition.$invalid ||
                                                        benchmarkTwo.hydration.$invalid ||
                                                        benchmarkTwo.humidity.$invalid ||
                                                        benchmarkTwo.bm_temp.$invalid"
                                                        class="btn btn-primary btn-o bm_next-step btn-wide pull-right">
                                                    Next <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}

                                    <div class="row">
                                        <div class="vp-progress-bar">
                                            <div class="col-sm-10 col-sm-offset-2 vp-progress">
                                                <div class="vp-progress-content">
                                                    <p>@{{ percentCompleted }}% complete</p>
                                                    <progress value="@{{ percentCompleted }}" max="100"> </progress>
                                                </div> <!--  -->
                                                <div class="create-type-form">
                                                    <a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
                                                    <a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                                </div> <!--  -->
                                            </div> <!-- end: COL8 || SUBMIT -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="3">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Measurements <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <div id="step-3" ng-controller="BMWidgetThree">
                                    {!! Form::open(['url' => '', 'id' => 'form-3', 'name' => 'benchmarkThree']) !!}

                                    <div class="row vp-form-container container-bm-step-3">
                                        <div class="col-sm-12" style="height: 100%">
                                            <ul id="viewport-3" class="vp-form-input-list">

                                                <!-- start: bm_waist | 0 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="0" data-sub-index="null" data-type="number" data-valid="@{{benchmarkThree.bm_waist.$valid}}" style="margin-top: 50px;">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Waist</b> (cm)</span> <sup>*</sup>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->
                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="number" class="form-control" id="bm_waist" ng-model="bm_waist"  ng-init="bm_waist='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_waist" required>
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkThree.bm_waist.$touched && benchmarkThree.bm_waist.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkThree.bm_waist.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_waist | 0 -->

                                                <!-- start: bm_hips | 1 -->
                                                <li class="vp-item vp-dg-item" data-index="1" data-sub-index="null" data-type="number" data-valid="@{{benchmarkThree.bm_hips.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Hips</b> (cm)</span> <sup>*</sup>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="number" class="form-control" id="bm_hips" ng-model="bm_hips"  ng-init="bm_hips='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_hips" required>
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkThree.bm_hips.$touched && benchmarkThree.bm_hips.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkThree.bm_hips.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_hips | 1 -->

                                                <!-- start: bm_height | 2 -->
                                                <li class="vp-item vp-dg-item" data-index="2" data-sub-index="null" data-type="number" data-valid="@{{benchmarkThree.bm_height.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Height</b> (cm)</span> <sup>*</sup>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="number" class="form-control" id="bm_height" ng-model="bm_height"  ng-init="bm_height='{{ isset($goalDetails) ? $goalDetails->gb_goal_name : null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_height" required>

                                                                    <!-- <div class="checkbox clip-check check-primary m-b-0 m-t-5 pli-23">
                                                                        <input type="checkbox" id="prevHeight" class="">
                                                                        <label for="prevHeight" class="no-error-label">
                                                                            <strong >Use previous height</strong>
                                                                        </label>
                                                                    </div> -->
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkThree.bm_height.$touched && benchmarkThree.bm_height.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkThree.bm_height.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_height | 2 -->

                                                <!-- start: bm_weight | 3 -->
                                                <li class="vp-item vp-dg-item" data-index="3" data-sub-index="null" data-type="number" data-valid="@{{benchmarkThree.bm_weight.$valid}}" style="min-height: 200px;">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Weight</b> (kg)</span> <sup>*</sup>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="number" class="form-control" id="bm_weight" ng-model="bm_weight"  ng-init="bm_weight='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_weight" required>

                                                                    <!-- <div class="checkbox clip-check check-primary m-b-0 m-t-5 pli-23">
                                                                        <input type="checkbox" id="prevWeight" class="" name="prevWeight">
                                                                        <label for="prevWeight" class="no-error-label">
                                                                            <strong  >Use previous weight</strong>
                                                                        </label>
                                                                    </div> -->
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkThree.bm_weight.$touched && benchmarkThree.bm_weight.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkThree.bm_weight.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_weight | 3 -->


                                            </ul> <!-- end viewport -->
                                        </div> <!-- end col12 -->
                                    </div> <!-- end row -->

                                    <div class="row mb-50-vh row-btn-step-container">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-o bm_back-step btn-wide pull-left">
                                                    <i class="fa fa-circle-arrow-left"></i> Back
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" ng-click="validateWidgetInputs()">
                                            <div class="form-group">
                                                <button ng-disabled="benchmarkThree.bm_waist.$invalid ||
                                                        benchmarkThree.bm_hips.$invalid ||
                                                        benchmarkThree.bm_weight.$invalid ||
                                                        benchmarkThree.bm_height.$invalid" class="btn btn-primary btn-o bm_next-step btn-wide pull-right">
                                                    Next <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {!! Form::close() !!}

                                    <div class="row">
                                        <div class="vp-progress-bar">
                                            <div class="col-sm-10 col-sm-offset-2 vp-progress">
                                                <div class="vp-progress-content">
                                                    <p>@{{ percentCompleted }}% complete</p>
                                                    <progress value="@{{ percentCompleted }}" max="100"> </progress>
                                                </div> <!--  -->
                                                <div class="create-type-form">
                                                    <a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
                                                    <a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                                </div> <!--  -->
                                            </div> <!-- end: COL8 || SUBMIT -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="4">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Fitness Testing <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <div id="step-4" ng-controller="BMWidgetFour">
                                    {!! Form::open(['url' => '', 'id' => 'form-4', 'name' => 'benchmarkFour']) !!}

                                    <input type = "hidden" value ="" id = "last-insert-id-bm" name = "last_insert_id">

                                    <div class="row vp-form-container container-bm-step-4">
                                        <div class="col-sm-12" style="height: 100%">
                                            <ul id="viewport-4" class="vp-form-input-list">

                                                <!-- start: bm_waist | 0 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_pressups.$valid}}" style="margin-top: 50px;">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Pressups</b> (reps)</span> <sup>*</sup>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="bm_pressups" ng-model="bm_pressups"  ng-init="bm_pressups='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_pressups" required>
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkFour.bm_pressups.$touched && benchmarkFour.bm_pressups.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_pressups.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_pressups | 0 -->

                                                <!-- start: bm_waist | 1 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_plank_min.$valid && benchmarkFour.bm_plank_sec.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Plank</b> (min:sec)</span> <sup>*</sup>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <?php
                                                                //print_r($m_benchmark->plank);
                                                                    $plank_min = '';
                                                                    $plank_sec = '';

                                                        
                                                                    if(isset($m_benchmark)) {
                                                                        $plank = $m_benchmark->plank;

                                                                        $planks = explode(':', $plank);

                                                                        if(count($planks) > 0) {
                                                                            $plank_min = $planks[0];
                                                                        }

                                                                        if(count($planks) > 1) {
                                                                            $plank_sec = $planks[1];
                                                                        }
                                                                    }
                                                                ?>
                                                                <div class="col-sm-8">

                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="bm_plank_min" ng-model="bm_plank_min"  ng-init="bm_plank_min='{{ $plank_min }}'" ng-keypress="pressEnter($event)" value="{{ $plank_min }}" name="bm_plank_min" ng-minlength="0" type="number" placeholder="min" required>
                                                                            <div class="input-group-addon">:</div>
                                                                            <input class="form-control" id="bm_plank_sec" ng-model="bm_plank_sec"  ng-init="bm_plank_sec='{{ $plank_sec }}'" ng-keypress="pressEnter($event)" value="{{ $plank_min }}" name="bm_plank_sec" ng-minlength="0" ng-maxlength="59" type="number" placeholder="sec" required>
                                                                        </div>

                                                                        <input type="hidden" class="form-control" id="bm_plank" ng-model="bm_plank" ng-keypress="pressEnter($event)" value="@{{ bm_plank = bm_plank_min +':'+ bm_plank_sec }}" name="bm_plank" placeholder="min" required>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div ng-if="(benchmarkFour.bm_plank_min.$touched && benchmarkFour.bm_plank_min.$invalid) || (benchmarkFour.bm_plank_sec.$touched && benchmarkFour.bm_plank_sec.$invalid)" class="vp-tooltip">
                                                                <span>Please, insert a correct value!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_plank_min.$valid && benchmarkFour.bm_plank_sec.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_plank | 1 -->

                                                <!-- start: bm_timetrial3k | 2 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="2" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_timetrial3k_min.$valid && benchmarkFour.bm_timetrial3k_sec.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>3km Time Trial Bike</b> (min:sec)</span> <sup>*</sup>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <?php
                                                                    $timetrial3k_min = '';
                                                                    $timetrial3k_sec = '';

                                                                    if(isset($m_benchmark)) {
                                                                        $timetrial3k = $m_benchmark->timetrial3k;

                                                                        $timetrial3ks = explode(':', $timetrial3k);

                                                                        if(count($timetrial3ks) > 0) {
                                                                            $timetrial3k_min = $timetrial3ks[0];
                                                                        }

                                                                        if(count($timetrial3ks) > 1) {
                                                                            $timetrial3k_sec = $timetrial3ks[1];
                                                                        }
                                                                    }
                                                                ?>
                                                                <div class="col-sm-8">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="bm_timetrial3k_min" ng-model="bm_timetrial3k_min"  ng-init="bm_timetrial3k_min='{{ $timetrial3k_min }}'" ng-keypress="pressEnter($event)" value="{{ $timetrial3k_min }}" name="bm_timetrial3k_min" ng-minlength="0" type="number" placeholder="min" required>
                                                                            <div class="input-group-addon">:</div>
                                                                            <input class="form-control" id="bm_timetrial3k_sec" ng-model="bm_timetrial3k_sec"  ng-init="bm_timetrial3k_sec='{{ $timetrial3k_sec }}'" ng-keypress="pressEnter($event)" value="{{ $timetrial3k_sec }}" name="bm_timetrial3k_sec" ng-minlength="0" ng-maxlength="59" type="number" placeholder="sec" required>
                                                                        </div>

                                                                        <input type="hidden" class="form-control" id="bm_timetrial3k" ng-model="bm_timetrial3k"  ng-keypress="pressEnter($event)" value="@{{ bm_timetrial3k = bm_timetrial3k_min +':'+ bm_timetrial3k_sec }}" name="bm_timetrial3k" required>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div ng-if="(benchmarkFour.bm_timetrial3k_min.$touched && benchmarkFour.bm_timetrial3k_min.$invalid) || (benchmarkFour.bm_timetrial3k_sec.$touched && benchmarkFour.bm_timetrial3k_sec.$invalid)" class="vp-tooltip">
                                                                <span>Please, insert a correct value!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_timetrial3k_min.$valid && benchmarkFour.bm_timetrial3k_sec.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_timetrial3k | 2 -->

                                                <!-- start: bm_bpm1 | 3 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="3" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_bpm1.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Cardio</b> Test BPM1</span>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="bm_bpm1" ng-model="bm_bpm1"  ng-init="bm_bpm1='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_bpm1">
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkFour.bm_bpm1.$touched && benchmarkFour.bm_bpm1.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_bpm1.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_bpm1 | 3 -->

                                                <!-- start: bm_bpm2 | 4 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="4" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_bpm2.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">5. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Cardio</b> Test BPM2</span>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="bm_bpm2" ng-model="bm_bpm2"  ng-init="bm_bpm2='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_bpm2">
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkFour.bm_bpm2.$touched && benchmarkFour.bm_bpm2.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_bpm2.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_bpm2 | 4 -->

                                                <!-- start: bm_bpm3 | 5 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="5" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_bpm3.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">6. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Cardio</b> Test BPM3</span>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="bm_bpm3" ng-model="bm_bpm3"  ng-init="bm_bpm3='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_bpm3">
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkFour.bm_bpm3.$touched && benchmarkFour.bm_bpm3.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_bpm3.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_bpm3 | 5 -->

                                                <!-- start: bm_bpm4 | 6 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="6" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_bpm4.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">7. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Cardio</b> Test BPM4</span>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="bm_bpm4" ng-model="bm_bpm4"  ng-init="bm_bpm4='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_bpm4">
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkFour.bm_bpm4.$touched && benchmarkFour.bm_bpm4.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_bpm4.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_bpm4 | 6 -->

                                                <!-- start: bm_bpm5 | 7 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="7" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_bpm5.$valid}}">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">8. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Cardio</b> Test BPM5</span>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="bm_bpm5" ng-model="bm_bpm5"  ng-init="bm_bpm5='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_bpm5">
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkFour.bm_bpm5.$touched && benchmarkFour.bm_bpm5.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_bpm5.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_bpm5 | 7 -->

                                                <!-- start: bm_bpm6 | 8 -->
                                                <li class="vp-item vp-dg-item vp-form-active" data-index="8" data-sub-index="null" data-type="text" data-valid="@{{benchmarkFour.bm_bpm6.$valid}}" style="min-height: 200px">
                                                    <div class="vp-input input-text-name">

                                                        <h3 class="vp-index pull-left">9. &nbsp;&nbsp;</h3>

                                                        <div class="input-header">
                                                            <h3>
                                                                <!-- label -->
                                                                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Cardio</b> Test BPM6</span>
                                                            </h3>

                                                            <!-- <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i> -->

                                                        </div> <!-- end: INPUT HEADER -->

                                                        <div class="input-body mb">
                                                            <div class="row">
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="bm_bpm6" ng-model="bm_bpm6"  ng-init="bm_bpm6='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" ng-keypress="pressEnter($event)" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="bm_bpm6">
                                                                </div>
                                                            </div>

                                                            <div ng-if="benchmarkFour.bm_bpm6.$touched && benchmarkFour.bm_bpm6.$invalid" class="vp-tooltip">
                                                                <span>This field is required!</span>
                                                            </div>

                                                            <div ng-show="benchmarkFour.bm_bpm6.$valid" class="enter-btn active">
                                                                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                                                                    OK <i class="fa fa-check" aria-hidden="true"></i>
                                                                </button>
                                                                <span class="press-enter">press <b>ENTER</b></span>
                                                            </div>
                                                        </div> <!-- end: INPUT BODY -->

                                                    </div> <!-- end: INPUT TEXT NAME -->
                                                    <div class="clear-both"></div>
                                                </li>
                                                <!-- end: bm_bpm6 | 8 -->

                                            </ul>
                                        </div> <!-- end col12 -->
                                    </div> <!-- end row -->
                                    <div class="row mb-50-vh row-btn-step-container">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-o bm_back-step btn-wide pull-left">
                                                    <i class="fa fa-circle-arrow-left"></i> Back
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" ng-click="validateWidgetInputs()">
                                            <div class="form-group">
                                                <button ng-disabled="benchmarkFour.bm_pressups.$invalid ||
                                                        benchmarkFour.bm_plank_min.$invalid ||
                                                        benchmarkFour.bm_plank_sec.$invalid ||
                                                        benchmarkFour.bm_timetrial3k.$invalid ||
                                                        benchmarkFour.bm_bpm1.$invalid ||
                                                        benchmarkFour.bm_bpm2.$invalid ||
                                                        benchmarkFour.bm_bpm3.$invalid ||
                                                        benchmarkFour.bm_bpm4.$invalid ||
                                                        benchmarkFour.bm_bpm5.$invalid ||
                                                        benchmarkFour.bm_bpm6.$invalid" class="btn btn-primary bm_finish-step btn-o btn-wide pull-right">
                                                    Finish
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}

                                    <div class="row">
                                        <div class="vp-progress-bar">
                                            <div class="col-sm-10 col-sm-offset-2 vp-progress">
                                                <div class="vp-progress-content">
                                                    <p>@{{ percentCompleted }}% complete</p>
                                                    <progress value="@{{ percentCompleted }}" max="100"> </progress>
                                                </div> <!--  -->
                                                <div class="create-type-form">
                                                    <a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
                                                    <a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                                </div> <!--  -->
                                            </div> <!-- end: COL8 || SUBMIT -->
                                        </div>
                                    </div>




                                    {{--{!! Form::open(['url' => '', 'id' => 'form-4']) !!}--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-md-6">--}}
                                                {{--<fieldset class="padding-15">--}}
                                                    {{--<legend>--}}
                                                        {{--Fitness Testing--}}
                                                    {{--</legend>--}}

                                                    {{--<div class="form-group">--}}
                                                    {{--<input type = "hidden" value ="" id = "last-insert-id-bm" name = "last_insert_id">--}}
                                                        {{--{!! Form::label('bm_pressups', 'Pressups (reps) *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_pressups', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                         {{--</div>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_plank', 'Plank (min:sec) *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_plank', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                    {{--</div>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_timetrial3k', '3km Time Trial Bike (min:sec) *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_timetrial3k', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}

                                                    {{--</div>--}}
                                                {{--</fieldset>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-6">--}}
                                                {{--<fieldset class="padding-15">--}}
                                                    {{--<legend>--}}
                                                        {{--Cardio Test (sec)--}}
                                                    {{--</legend>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_bpm1', 'Cardio Test BPM1 *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_bpm1', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                     {{--</div>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_bpm2', 'Cardio Test BPM2 *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_bpm2', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                     {{--</div>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_bpm3', 'Cardio Test BPM3 *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_bpm3', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                    {{--</div>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_bpm4', 'Cardio Test BPM4 *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_bpm4', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                    {{--</div>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_bpm5', 'Cardio Test BPM5 *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_bpm5', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                    {{--</div>--}}

                                                    {{--<div class="form-group">--}}
                                                        {{--{!! Form::label('bm_bpm6', 'Cardio Test BPM6 *', ['class' => 'strong']) !!}--}}
                                                        {{--{!! Form::text('bm_bpm6', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
                                                        {{--<span class ="error"></span>--}}
                                                    {{--</div>--}}
                                                {{--</fieldset>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-sm-6">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<button class="btn btn-primary btn-o bm_back-step btn-wide pull-left">--}}
                                                        {{--<i class="fa fa-circle-arrow-left"></i> Back--}}
                                                    {{--</button>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-sm-6">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<button class="btn btn-primary bm_finish-step btn-o btn-wide pull-right">--}}
                                                        {{--Finish--}}
                                                    {{--</button>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--{!! Form::close() !!}--}}
                                </div>
                            </div>
                        </div>

                        <div class="clear-widget"></div>
                    </div>
                    <!-- end: FORM WIZARD ACCORDION -->
                </div>
            </div>
            <!-- end: WIZARD FORM -->
        </div>
    </div>
</div>