<?php
    use App\ClientMenu;

    $clientSelectedMenus = [];

    if(Auth::user()->account_type == 'Client') {
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
    }
?>
<div id="mainnav" class="top_navigation">
    <ul class="main-navigation-menu">
        <li class="<?php echo e(Request::is('new-dashboard')?'active':''); ?>">
            <a href="<?php echo e(route('dashboard')); ?>">
                <div class="item-content">
                    <div class="item-media">
                        <img src="<?php echo e(asset('result/images/dashboard.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Dashboard </span>
                    </div>
                </div>
            </a>
        </li>
        <!-- Calendars start -->
        <li class="<?php echo e(Request::is('dashboard/calendar')?'active':''); ?>">
            <a href="#">
                <div class="item-content">
                    <div class="item-media">
                        <img src="<?php echo e(asset('result/images/calendar.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Calendars </span>
                    </div>
                </div>
            </a>
            <!-- Calendars submenu start -->
            <ul class="topsub-menu">
                <div class="submenu_back"> 
                    <li class="">
                        <a href="<?php echo e(url('dashboard/calendar')); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/calendar.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Booking Calender </span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(in_array('activity_calendar', $clientSelectedMenus)?url('activity/calender'):"#"); ?>" class="<?php echo e(in_array('activity_calendar', $clientSelectedMenus)?'':'disable'); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Activity Calendar.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Activity Calender </span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(in_array('meal_planner', $clientSelectedMenus)?url('meal-planner/calendar'):"#"); ?>" class="<?php echo e(in_array('meal_planner', $clientSelectedMenus)?'':'disable'); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Nutritional Calendar.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Nutritional Calender </span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(in_array('epic_goal', $clientSelectedMenus)?route('goals.calendar'):"#"); ?>" class="<?php echo e(in_array('epic_goal', $clientSelectedMenus)?'':'disable'); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Goal Calendar.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Goal Calender </span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="open-modalp1" data-toggle="modal" data-target="#myModal1">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Diary.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Daily <br>Diary</span>
                            </div>
                        </a>
                    </li>
                </div>
            </ul>
            <!-- Calendars submenu end -->
        </li>
        <!-- Calendars end -->
        <!-- Epic Process end -->
        <li class="<?php echo e(Request::is('parq')?'active':''); ?>">
            <a href="#">
                <div class="item-content">
                    <div class="item-media">
                        <img src="<?php echo e(asset('result/images/Reward.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Epic Process</span>
                    </div>
                </div>
            </a>
            <!-- Epic Process submenu start -->
            <ul class="topsub-menu">
                <div class="submenu_back"> 
                    <li class="<?php echo e(in_array('parq', $clientSelectedMenus)?'':'disable'); ?>">
                        <a href="#">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Questionaire.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Assess & Progress </span>
                            </div>
                        </a>
                        <!-- start submenu submenu -->
                        <?php if(in_array('parq', $clientSelectedMenus)): ?>
                        <ul class="topsub-sub-menu">
                            <div class="submenu_back"> 
                                <li class="">
                                    <a  href="<?php echo e(url('epicprogress/AssessAndProgress/PersonalDetails')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Personal Details.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Personal Details </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <?php
                                    $parq = DB::table('parqs')->where('client_id', Auth::user()->account_id)->select('parq1', 'parq2', 'parq3', 'parq4')->first();
                                    ?>
                                    <?php if($parq->parq1 == 'pending'): ?>
                                    <a href="#" class="disable">
                                    <?php else: ?> 
                                    <a  href="<?php echo e(url('epicprogress/AssessAndProgress/ExercisePreference')); ?>">
                                    <?php endif; ?>
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Meal Description.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Exercise Preference </span>
                                        </div>
                                    </a>
                                </li> 
                                <li class="">
                                    <?php if($parq->parq2 == 'pending'): ?>
                                    <a href="#" class="disable">
                                    <?php else: ?> 
                                        <a href="<?php echo e(url('epicprogress/AssessAndProgress/IllnessAndInjury ')); ?>">
                                            <?php endif; ?>
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Illness & Injury.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Injury Profile & Family History</span>
                                        </div>
                                    </a>
                                </li> 
                                <li class="">
                                        <?php if($parq->parq3 == 'pending'): ?>
                                    <a href="#" class="disable">
                                        <?php else: ?> 
                                            <a href="<?php echo e(url('epicprogress/AssessAndProgress/PARQ')); ?>">
                                            <?php endif; ?>
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Services.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> PARQ </span>
                                        </div>
                                    </a>
                                </li> 
                                <li class="">
                                    <?php if($parq->parq4 == 'pending'): ?>
                                    <a href="#" class="disable">
                                    <?php else: ?> 
                                    <a href="<?php echo e(url('epicprogress/AssessAndProgress/GoalAndMotivation')); ?>">
                                    <?php endif; ?>
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Healthy Happy Living.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Goals & Motivation </span>
                                        </div>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <?php endif; ?>
                        <!-- end submenu submenu -->
                    </li>
                    <li class="">
                        <a href="#">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Time Management.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Weight & <br>Date </span>
                            </div>
                        </a>
                        <!-- start submenu submenu -->
                        <ul class="topsub-sub-menu">
                            <div class="submenu_back"> 
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/WeightAndDate/Measurements')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Weight Management.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Measurements </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(in_array('benchmark', $clientSelectedMenus)?url('benchmark'):"#"); ?>" class="<?php echo e(in_array('benchmark', $clientSelectedMenus)?'':'disable'); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Benchmark.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Benchmark </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/WeightAndDate/smarater')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/BE SMARTER.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Be <br>Smarter</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(in_array('epic_goal', $clientSelectedMenus)?url('goals'):"#"); ?>" class="<?php echo e(in_array('epic_goal', $clientSelectedMenus)?'':'disable'); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Goal.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Epic <br>Goal </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/WeightAndDate/motivation')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Motivation.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Motivation</span>
                                        </div>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <!-- end submenu submenu -->
                    </li> 
                    <li class="">
                        <a href="#">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Muscle Description.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title">Train & <br>Gain </span>
                            </div>
                        </a>
                        <!-- start submenu submenu -->
                        <ul class="topsub-sub-menu">
                            <div class="submenu_back"> 
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/train-gain/strength-in-numbers')); ?>">                                      
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Strength In Numbers.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Strength in Numbers </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e((Auth::id() == 78 || 1==1) && in_array('activity_planner', $clientSelectedMenus)?url('fitness/tools'):"#"); ?>" class="<?php echo e((Auth::id() == 78 || 1==1) && in_array('activity_planner', $clientSelectedMenus)?'':'disable'); ?>">                                                
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Add Exercise.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Activity Planner </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e((Auth::id() == 78 || 1==1) && in_array('fitness_mapper', $clientSelectedMenus)?url('epicprogress/train-gain/fitness-mapper'):"#"); ?>" class="<?php echo e((Auth::id() == 78 || 1==1) && in_array('fitness_mapper', $clientSelectedMenus)?'':'disable'); ?>">                                                
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Maps.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Fitness Mapper </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/train-gain/rest-in-recovery')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Rest & Recovery </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(in_array('benchmark', $clientSelectedMenus)?url('benchmark'):"#"); ?>" class="<?php echo e(in_array('benchmark', $clientSelectedMenus)?'':'disable'); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Benchmark </span>
                                        </div>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <!-- end submenu submenu -->
                    </li> 
                    <li class="">
                        <a href="#">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Balanced Diet.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Trace & <br>Replace </span>
                            </div>
                        </a>
                        <!-- start submenu submenu -->
                        <ul class="topsub-sub-menu">
                            <div class="submenu_back"> 
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/trace-and-replace/nutritional-journal')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Meal Description.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Nutritional Journal </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(in_array('recipes', $clientSelectedMenus)?url('meal-planner/recipes'):"#"); ?>" class="<?php echo e(in_array('recipes', $clientSelectedMenus)?'':'disable'); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Recipes.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Recipes </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/trace-and-replace/portion-distrortion')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Eating Healthier.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Portion Distortion </span>
                                        </div>
                                    </a>
                                </li> 
                                <li class="">
                                    <a href="<?php echo e(in_array('meal_planner', $clientSelectedMenus)?url('meal-planner/calendar'):"#"); ?>" class="<?php echo e(in_array('meal_planner', $clientSelectedMenus)?'':'disable'); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Meal Planner.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Nutritional Calender</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/trace-and-replace/limiting-vices')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Limiting Vices.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Limiting Vices </span>
                                        </div>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <!-- end submenu submenu -->
                    </li> 
                    <li class="">
                        <a href="#">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Single Person.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Diarise & Prioritise </span>
                            </div>
                        </a>
                        <!-- start submenu submenu -->
                        <ul class="topsub-sub-menu">
                            <div class="submenu_back"> 
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/diarise-and-prioritise/stay-focused')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Stay Focussed.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Stay Focussed </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/diarise-and-prioritise/revise-adjust')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/To Do List.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title">Revise & Adjust </span>
                                        </div>
                                    </a>
                                </li> 
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/diarise-and-prioritise/consistency-beats')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Check List.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Consistency Beats Intensity </span>
                                        </div>
                                    </a>
                                </li> 
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/diarise-and-prioritise/bucket-list')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Bucket List.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Create a bucket list</span>
                                        </div>
                                    </a>
                                </li> 
                                <li class="">
                                    <a href="<?php echo e(url('epicprogress/diarise-and-prioritise/celebrate')); ?>">
                                        <div class="menuicon">
                                            <img src="<?php echo e(asset('result/images/Celebrate.png')); ?>">
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> Celebrate Success </span>
                                        </div>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <!-- end submenu submenu -->
                    </li>
                </div>
            </ul>
            <!-- Epic Process submenu end -->
        </li>
        <!-- Epic Process end -->
        <!-- Membership start -->
        <li class="">
            <a href="">
                <div class="item-content">
                    <div class="item-media">
                        <img src="<?php echo e(asset('result/images/Client.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Membership </span>
                    </div>
                </div>
            </a>
            <!-- Membership submenu start -->
            <ul class="topsub-menu">
                <div class="submenu_back"> 
                    <li class="">
<a href="<?php echo e(route('profile.edit')); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Appointments.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Appointments </span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="#">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Client List.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Membership Details </span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(in_array('invoice', $clientSelectedMenus)?url('invoices'):"#"); ?>" class="<?php echo e(in_array('invoice', $clientSelectedMenus)?'':'disable'); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/10.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Invoices </span>
                            </div>
                        </a>
                    </li>
                </div>
            </ul>
            <!-- Membership submenu end -->
        </li>
        <!-- Membership end -->
        <!-- Settings start -->
        <li class="">
            <a href="#">
                <div class="item-content">
                    <div class="item-media">
                        <img src="<?php echo e(asset('result/images/Settings.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Settings </span>
                    </div>
                </div>
            </a>
            <!-- Settings submenu start -->
            <ul class="topsub-menu">
                <div class="submenu_back"> 
                    <li class="">
              <a href="<?php echo e(route('profile.edit')); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Edit Account.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Edit Account </span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(in_array('calendar_settings', $clientSelectedMenus)?url('calendar/settings'):"#"); ?>" class="<?php echo e(in_array('calendar_settings', $clientSelectedMenus)?'':'disable'); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Calendar Settings.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Calender Settings </span>
                            </div>
                        </a>
                    </li>
                </div>
            </ul>
            <!-- Settings submenu end -->
        </li>
        <!-- Settings end -->
        <!-- Tools start -->
        <li class="">
            <a href="#">
                <div class="item-content">
                    <div class="item-media">
                        <img src="<?php echo e(asset('result/images/Resources.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Tools </span>
                    </div>
                </div>
            </a>
            <!-- Tools submenu start -->
            <ul class="topsub-menu">
                <div class="submenu_back"> 
                    <li class="">
                        <a href="<?php echo e(in_array('calculator', $clientSelectedMenus)?url('calculators'):"#"); ?>" class="<?php echo e(in_array('calculator', $clientSelectedMenus)?'':'disable'); ?>">
                            <div class="menuicon">
                                <img src="<?php echo e(asset('result/images/Business Plan.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Calculators </span>
                            </div>
                        </a>
                    </li>
                </div>
            </ul>
            <!-- Tools submenu end -->
        </li>
        <!-- Tools end -->
    </ul>
</div><?php /**PATH /Users/mahendra/Documents/projects/epicFit/epicfitlaravelv6/resources/views/Result/partials/includes/topmenu.blade.php ENDPATH**/ ?>