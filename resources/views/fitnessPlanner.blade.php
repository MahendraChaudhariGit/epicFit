	<!-- start - FITNESS PLANNER CSS -->
    {!! HTML::style('fitness-planner/css/pt-planner.css?v='.time()) !!}
    {!! HTML::style('fitness-planner/css/jquery.ui.labeledslider.css?v='.time()) !!}
    {!! HTML::style('fitness-planner/custom/style.css?v='.time()) !!}
    <link rel='stylesheet' href='http://api.onesportevent.com/devapi/css/v1/core_t5.css?ver=4.3.5' type='text/css' />
    {!! Html::style('fitness-planner/css/api.css?v='.time()) !!}
    <!-- end - FITNESS PLANNER CSS -->
		
    <!-- start - FITNESS PLANNER JS -->
    {!! Html::script('assets/plugins/jquery-json-master/src/jquery.json.js?v='.time()) !!}
    {!! Html::script('fitness-planner/custom/js/jquery.placeholder.js?v='.time()) !!}
    {!! Html::script('fitness-planner/custom/js/jquery.ui.touch-punch.min.js?v='.time()) !!}
    {!! Html::script('fitness-planner/custom/jwplayer/jwplayer.js?v='.time()) !!}
    {!! Html::script('fitness-planner/js/jquery.ui.labeledslider.js?v='.time()) !!}
    {!! Html::script('fitness-planner/custom/js/popup.js?v=1') !!}
    <!-- end - FITNESS PLANNER JS -->
    
    <script type='text/javascript' src="{{asset('fitness-planner/js/PTPlanner.js?v=10.439725001478090901')}}"></script>

    <!-- <script type='text/javascript' src='{{asset('fitness-planner/api.corets.js')}}'></script> -->
    <script type='text/javascript' src='{{asset('fitness-planner/api.core.non.js')}}'></script>
    <div id="page" class="hfeed site">
        <div id="main" class="site-main">

                        <script type='text/javascript'>
        jQuery(document).ready( function() {
            //PT.Start( 1, '73e46a7f-6f0f-46c8-ab4b-00ba64ae6d83' );
        });
    </script>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

                        
                <article id="post-6" class="post-6 page type-page status-publish hentry">
                    <header class="entry-header">
                        
                        <h1 class="entry-title">Protected: Fitness Planner</h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        

<div id="traingPlannerMain" class="plannerContainer fit-ui">

    <input type="hidden" id="fit_session" value="d95a16d3-3a54-4598-a55a-af4abc086a29"/>
   
    
   
    <input type="hidden" id="fit_gender" value="1" />
    <input type="hidden" id="fit_height" value="162" />
    <input type="hidden" id="fit_weight" value="62" />
    <input type="hidden" id="fit_age" value="28" />
    <input type="hidden" id="fit_email" value="tester@epicstride.com" />

    <div class="plannerNavigator">
        <div class="plannerProgress">
            <div id="progressBar">
            </div>

            <button id="prevNav">Back</button>
            <button id="nextNav">Next</button>
  
        </div>
        <div class="plannerSelector">
            <div id="planSelector" class="selectorContainer">
                <div id="sliderItem1" class="sliderItem item1 fit-plan-gender">
                    <div class="itemHeader" data-name="Gender">
                        <span>What is your gender?</span></div>
                    <div class="itemBody">
                        <input id="maleButton" type="image" src="{{asset('fitness-planner/css/plan_images/female.png')}}" value="1" alt="Female" title="Female" />
                        <input id="femaleButton" type="image" src="{{asset('fitness-planner/css/plan_images/man.png')}}" value="2" alt="Male" title="Male" />
                    </div>
                </div>
                <div id="sliderItem2" class="sliderItem item2">
                    <div class="itemHeader" data-name="Plan">
                        <span>What do you want to do?</span></div>
                    <div id="planChoice" class="itemBody">
                        <!--format "{imagename}-gender" -->
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/strength-male.png')}}" value="1" alt="Mens Strength Program" title="Mens Strength Program" />
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/weightloss-male.png')}}" value="2" alt="Mens Weightloss Program" title="Mens Weightloss Program" />
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/health-male.png')}}" value="3" alt="Mens Health Program" title="Mens Health Program" />

                        <!-- Future release
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/plan_custom-male.png')}}" value="4" alt="Mens Custom Program" title="Mens Custom Program" />
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/plan_cardio-male.png')}}" value="5" alt="Mens Cardio Programs" title="Mens Cardio Programs" />
                            -->
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/plan_structured-male.png')}}" value="6" alt="Mens Program Library" title="Mens Program Library" class="custom-only" />
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/custom_build-male.png')}}" value="7" alt="Mens Custom Builder" title="Mens Custom Builder" class="custom-only" />

                    </div>
                </div>
                <div id="sliderItem3" class="sliderItem item3 fit-plan-habits">

                    <!-- By default only dynamic programs shown and fixed are hidden -->
                    <div class="program-dynamic">
                        <div class="itemHeader" data-name="Habit">
                            <span>What are your current exercise habits?</span></div>
                        <div class="itemBody">
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/habit_none.png')}}" value="3" alt="None" title="None" />
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/habit_infrequent.png')}}" value="2" alt="Infrequent" title="Infrequent" />
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/habit_frequent.png')}}" value="1" alt="Frequent" title="Frequent" />
                        </div>
                    </div>

                    <div class="program-fixed">
                        <div class="itemHeader" data-name="Habit">
                            <span>What is your current exercise level?</span></div>
                        <div class="itemBody">
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/level_beginner.png')}}" value="3" alt="Beginner" title="Beginner" />
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/level_intermediate.png')}}" value="2" alt="Intermediate" title="Intermediate" />
                            <input type="image" src="{{asset('fitness-planner/css/plan_images/level_advanced.png')}}" value="1" alt="Advanced" title="Advanced" />
                        </div>
                    </div>

                </div>
                <div id="sliderItem3-custom" class="sliderItem item3 fit-plan-program">
                    <div class="itemHeader" data-name="FixedProgramId">
                        <span>Create a program</span></div>
                    <div class="itemBody">
                        <div class="content_box pg_width">
                            <div class="left mob_view">
                            </div>
                            <div class="left pt">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                <div class="create_pt">
                                    <div class="error-msg">Please enter a program name.</div>
                                    <form action="javascript:void(0)" onsubmit="CB.UI.createProgram(this)">
                                        <label class="prg_name">Program Name:</label>
                                        &nbsp;
                                <input type="text" name="pname" class="txtbox" />
                                        &nbsp;
                                <input type="submit" name="btn" value="Create" class="btn_black" />
                                    </form>
                                </div>

                                <h1>Your Programs</h1>
                                <div class="my_pt"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                </div>
                <div id="sliderItem4" class="sliderItem item4 fit-plan-equipment">
                    <div class="itemHeader" data-name="Method">
                        <span>What equipment do you have?</span></div>
                    <div class="itemBody">
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/equip_gym.png')}}" value="1" alt="Gym Equipment" title="Gym Equipment" />
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/equip_freeweight.png')}}" value="2" alt="Dumbbells" title="Dumbbells" />
                        <input id="fit_equipment_bodyweight" type="image" src="{{asset('fitness-planner/css/plan_images/equip_bodyweight.png')}}" value="3" alt="Just me! - Bodyweight" title="Just me! - Bodyweight" />
                        <input id="fit_equipment_swiss" type="image" src="{{asset('fitness-planner/css/plan_images/equip_bodyweight.png')}}" value="4" alt="Swissball" title="Swissball" />
                    </div>
                </div>
                <div id="sliderItem4-prewritten" class="sliderItem item4 fit-plan-program">
                    <div class="itemHeader" data-name="FixedProgramId">
                        <span>Which programe do you want?</span></div>
                    <div class="itemBody">
                    </div>
                </div>

                <div id="sliderItem4-custom" class="sliderItem item4 fit-plan-program">
                    <div class="itemHeader" data-name="FixedProgramId">
                        <span>Build Your Program</span></div>
                    <div class="itemBody">
                        <div class="content_box pg_width">
                            <div class="left mob_view">
                            </div>
                            <div class="left pt">
                                <h1>Manage a Program</h1>
                                <div class="manage_det">
                                    <div>
                                        <div class="left">
                                            <label>Program name</label>
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                        </div>
                                        <a class="right btn_white" href="javascript:void(0)" onclick="CB.UI.showEPForm()">edit details</a>
                                        <div class="clr"></div>
                                    </div>
                                    <form action="javascript:void(0)" onsubmit="return CB.UI.updateProgram(this)">
                                        <div class="error-msg">Please enter a program name.</div>
                                        <div class="left">
                                            <input type="text" name="pname" placeholder="Program name" class="txtbox" />
                                            <p>
                                                <textarea name="pdesc" placeholder="Description (if any)" class="txtbox"></textarea></p>
                                        </div>
                                        <div class="right actbtns">
                                            <div class="right">
                                                <input type="submit" value="save" class="btn_white" /></div>
                                            <div class="right"><a href="javascript:void(0)" class="btn_white" onclick="CB.UI.hideEPForm()">cancel</a></div>
                                            <div class="clr"></div>
                                        </div>
                                        <div class="clr"></div>
                                    </form>
                                </div>

                                <p id="pt-build-instruction">Build your custom program by clicking the plus symbol to add exercies to each category.</p>
                                <div id="pt-accordion" class="mbtm20">
                                    <h3 class="warm-up">
                                        <div class="left">Warm Up</div>
                                        <div class="right"><a href="javascript:void(0)">
                                            <img src="{{asset('fitness-planner/custom/images/expand.png')}}" width="23"></a></div>
                                        <div class="clr"></div>
                                    </h3>
                                    <ul class="cat_det"></ul>

                                    <h3 class="exercises">
                                        <div class="left">Exercises</div>
                                        <div class="right"><a href="javascript:void(0)">
                                            <img src="{{asset('fitness-planner/custom/images/expand.png')}}" width="23"></a></div>
                                        <div class="clr"></div>
                                    </h3>
                                    <div class="cat_det"></div>

                                    <h3 class="cool-down">
                                        <div class="left">Cool Down</div>
                                        <div class="right"><a href="javascript:void(0)">
                                            <img src="{{asset('fitness-planner/custom/images/expand.png')}}" width="23"></a></div>
                                        <div class="clr"></div>
                                    </h3>
                                    <div class="cat_det"></div>

                                    <h3 class="cardio">
                                         <div class="left">Cardio</div>
                                        <div class="right"><a href="javascript:void(0)">
                                            <img src="{{asset('fitness-planner/custom/images/expand.png')}}" width="23"></a></div>
                                        <div class="clr"></div>
                                    </h3>
                                    <ul class="cat_det"></ul>

                                    <h3 class="core">
                                        <div class="left">Core</div>
                                        <div class="right"><a href="javascript:void(0)">
                                            <img src="{{asset('fitness-planner/custom/images/expand.png')}}" width="23"></a></div>
                                        <div class="clr"></div>
                                    </h3>
                                    <ul class="cat_det"></ul>

                                </div>

                                <h3 align="right" id="pt-time-estimate">Total Estimated Time: <span>0 minutes</span></h3>

                                <div>
                                    <input type="button" class="planner-next-bottom" value="Next" />
                                    <div class="clr"></div>
                                </div>
                                <!--
                                <div>
                                    <a href="index.html" class="left btn_white">&lsaquo; back to programs list</a>
                                    <a href="#" class="right btn_white">plan program &rsaquo;</a>
                                </div>
                                -->
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                </div>

                <div id="sliderItem5" class="sliderItem item5">
                    <div class="itemHeader" data-name="Intensity">
                        <span>How hard do you want to work out now?</span>
                        <select id="intensitySelector">
                            <option value="100">Not very</option>
                            <option value="200">A little</option>
                            <option value="300">Somewhat</option>
                            <option value="400">Very</option>
                            <option value="500">Extremely</option>
                        </select>
                    </div>
                    <div class="itemBody ui-slider-alignlabel">
                        <div id="intensitySlider">
                        </div>
                    </div>
                     <div class="itemHeader" data-name="Experience">
                         <span>How much have you exercised in the past?</span>
                         <select id="experienceSelector">
                             <option value="100">Not much</option>
                             <option value="200">A little</option>
                             <option value="300">Some</option>
                             <option value="400">A lot</option>
                             <option value="500">A ton</option>
                         </select>
                     </div>
                      <div class="itemBody ui-slider-alignlabel">
                          <div id="experienceSlider">
                          </div>
                     </div>
                      <div class="itemHeader" data-name="Age">
                         <span>What is your age?</span>
                         <select id="ageSelector">
                         </select>
                     </div>
                    <div class="itemBody">
                        <div id="ageSlider">
                        </div>
                    </div>

                    <!-- Extra Sliders. Please note that Ajax is listening to these sliders-->
                    <div class="itemHeader" data-name="Weight">
                        <span>How much do you weigh in <span id="weightSpan">kg</span>?</span> <span class="switchto">
                            Switch to</span>
                        <input type="button" id="weightButton" value="lbs" />
                        <select id="weightKGSelector">
                        </select>
                        <select style="display: none" id="weightLBSSelector">
                        </select>
                    </div>
                    <div class="itemBody">
                        <div id="weightSlider">
                        </div>
                    </div>
                    <div class="itemHeader" data-name="Height">
                        <span>How tall are you in <span id="heightSpan">cm</span>?</span> <span class="switchto">
                            Switch to</span>
                        <input type="button" id="heightButton" value="feet & inch" />
                        <select id="heightCMSSelector">
                        </select>
                        <select style="display: none" id="heightFISelector">
                        </select>
                    </div>
                    <div class="itemBody">
                        <div id="heightSlider">
                        </div>
                    </div>

                    <input type="button" id="customNextButton" value="Next" />
                   
                </div>
                <div id="sliderItem6" class="sliderItem item6 fit-plan-schedule">
                    <div class="itemHeader" data-name="ScheduleType">
                        <span>How should we plan your workout schedule? </span>
                    </div>
                    <div class="itemBody">
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/plan_for_me.png')}}" title="Plan it for me" alt="Plan it for me" value="1" />
                        <input type="image" src="{{asset('fitness-planner/css/plan_images/plan_manage.png')}}" title="I'll manage it" alt="I'll manage it" value="2" />
                    </div>
                </div>
                <div id="sliderItem7" class="sliderItem item7">
                    <span id="weekSelection">
                        <div class="itemHeader" data-name="Weeks">
                            <span>How many weeks do you want to do this plan for?</span>
                        </div>
                        <div class="itemBody">
                            <div id="weekSlider">
                            </div>
                        </div>
                    </span>
                    <span id="timeSelection">
                        <div class="itemHeader" data-name="TimePerWeek">
                            <span>How long can you work out each week?</span>
                        </div>
                        <div class="itemBody">
                            <div id="timeSlider">
                            </div>
                        </div>
                    </span>
                    <div class="itemHeader" data-name="DaysOfWeek">
                        <span id="daySelection">When can you work out?</span>
                    </div>
                    <div id="weekDays" class="itemBody">
                        <input type="checkbox" id="mon" /><label for="mon">Mon</label>
                        <input type="checkbox" id="tue" /><label for="tue">Tue</label>
                        <input type="checkbox" id="wed" /><label for="wed">Wed</label>
                        <input type="checkbox" id="thr" /><label for="thr">Thu</label>
                        <input type="checkbox" id="fri" /><label for="fri">Fri</label>
                        <input type="checkbox" id="sat" /><label for="sat">Sat</label>
                        <input type="checkbox" id="sun" /><label for="sun">Sun</label>
                    </div>
                    <div>
                        <input type="button" value="Create Training Programme" id="doneButton" class="addButton ui-button ui-widget ui-state-default ui-corner-all ui-button-success" role="button" aria-disabled="false">
                        <div style="display: none"  class="fit_saveplan_ajax"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="planInfo" class="plannerInfo">
        <div id="planMessage" class="plannerDetail"></div>
        <h2 id="planPreviewTitle">Plan Preview</h2>
        <h2 id="planFinalTitle">Your Personal Plan</h2>
        <p id="planLink" style="display: none;">Visit <a href="/member/home/">your homepage</a> to see your new training calendar</p>
        <div id="planSchedule" class="plannerSchedule">
        </div>
    </div>
      <script type='text/javascript'>
                jQuery(document).ready( function() {
                    //fitCore.fitMyPlans.start( email = 'tester@epicstride.com', sessionGuid = 'd95a16d3-3a54-4598-a55a-af4abc086a29' );
                });
            </script>
            <script>

            </script>
             <br/>
             <br/>

     <div id='fitMyPlanList'></div>
            <div class='fitLoader' style='display: none;'></div>
            <div id='fitMyPlanDetails'></div>

    <div class="clear">
    </div>
</div>

                        <br />                                          </div><!--              </article><!-- #post -->

        </div><!-- #content -->
    </div><!-- #primary -->


        </div><!-- #main -->
    </div><!-- #page -->