<!--sidebar -->
<?php
use App\ClientMenu;

$clientSelectedMenus = [];

if(Auth::user()->account_type == 'Client') {
$selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
$clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
}
?>

<div class="sidebar app-aside" id="sidebarNew">
    <div class="sidebar-container perfect-scrollbar sidebar-left" data-perfect-scrollbar="" data-suppress-scroll-x="true">
        <nav class="">

            <!-- start: MAIN NAVIGATION MENU -->
            <ul class="main-navigation-menu">
                <li class="<?php echo e(Request::is('new-dashboard')?'active':''); ?> nav-item">
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
                    <div class="triangle"></div>
                </li>
                <!-- Calendars start -->
                <li class="<?php echo e(Request::is('dashboard/calendar')?'active':''); ?> nav-item"  data-item="calendar">
                    <a href="#">
                        <div class="item-content">
                            <div class="item-media">
                                <img src="<?php echo e(asset('result/images/calendar.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Calendars </span>
                            </div>
                        </div>
                        <div class="triangle"></div>
                    </a>                    
                </li>
                <!-- Calendars end -->

                <!-- Body Measurements start -->
                
                <?php if(in_array('measurement', $clientSelectedMenus)): ?>
                    <li class="nav-item" data-item="measurements">
                        <a href="#">
                            <div class="item-content">
                                <div class="item-media">
                                    <img src="<?php echo e(asset('result/images/measurement_icon.jpg')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Body Measurements</span>
                                </div>
                            </div>
                            <div class="triangle"></div>
                        </a> 
                    </li>
                <?php endif; ?>
               
                
                    <!-- Body Measurements end -->

                <!-- Epic Process start -->
             
                <li class="<?php echo e(Request::is('parq')?'active':''); ?> nav-item" data-item="process">
                    <a href="#">
                        <div class="item-content">
                            <div class="item-media">
                                <img src="<?php echo e(asset('result/images/Reward.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Epic Process</span>
                            </div>
                        </div>
                        <div class="triangle"></div>
                    </a>                    
                </li>
                <!-- Epic Process end -->

                 <!-- Epic Process start -->
             
                 <?php if(in_array('epic_social', $clientSelectedMenus)): ?>
                    <li class="nav-item" data-item="epic_social">
                        <a href="#">
                            <div class="item-content">
                                <div class="item-media">
                                    <img src="<?php echo e(asset('result/images/share_icon.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Epic Social </span>
                                </div>
                            </div>
                            <div class="triangle"></div>
                        </a> 
                    </li>
                <?php endif; ?>
                <!-- Epic Process end -->

                <!-- Membership start -->
                <li class="nav-item" data-item="membership">
                    <a href="">
                        <div class="item-content">
                            <div class="item-media">
                                <img src="<?php echo e(asset('result/images/Client.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Membership </span>
                            </div>
                        </div>
                        <div class="triangle"></div>
                    </a>                    
                </li>
                <!-- Membership end -->
                <!-- Settings start -->
                <li class="nav-item" data-item="settings">
                    <a href="#">
                        <div class="item-content">
                            <div class="item-media">
                                <img src="<?php echo e(asset('result/images/Settings.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Settings </span>
                            </div>
                        </div>
                        <div class="triangle"></div>
                    </a>                    
                </li>
                <!-- Settings end -->
                <!-- Tools start -->
                <li class="nav-item" data-item="tools">
                    <a href="#">
                        <div class="item-content">
                            <div class="item-media">
                                <img src="<?php echo e(asset('result/images/Resources.png')); ?>">
                            </div>
                            <div class="item-inner">
                                <span class="title"> Tools </span>
                            </div>
                        </div>
                        <div class="triangle"></div>
                    </a>                    
                </li>
                <!-- Tools end -->
            </ul>
            <!-- end: MAIN NAVIGATION MENU -->            
        </nav>
    </div>
    <!--  Sidebar menu second step start -->
    <div class="sidebar-left-secondary perfect-scrollbar" data-perfect-scrollbar="" data-suppress-scroll-x="true">
        <!-- Epic social submenu start -->

        <ul class="topsub-menu childNav" data-parent="epic_social">
            <div class="submenu_back">
                <li class="">
                    <a href="<?php echo e(url('social/home')); ?>">
                        <div class="menuicon">
                            <img src="<?php echo e(asset('result/images/home.jpeg')); ?>">
                        </div>
                        <div class="item-inner">
                            <span class="title"> Home </span>
                        </div>
                        
                    </a>
                </li> 
                <li class="">                    
                    <a href="<?php echo e(url('social/messages')); ?>">
                        <div class="menuicon">
                            <img src="<?php echo e(asset('result/images/messages-icon.png')); ?>">
                        </div>
                        <div class="item-inner">
                            <span class="title"> Direct Messages </span>
                        </div>
                        
                    </a>
                   
                </li> 
                
                
            </div>
        </ul>

        <!-- Epic social submenu start -->
        
        
        <!-- Calendars submenu start -->
        <ul class="topsub-menu childNav" data-parent="calendar">
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
                <?php if(in_array('activity_calendar', $clientSelectedMenus)): ?>
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
                <?php endif; ?>
                <?php if(in_array('meal_planner', $clientSelectedMenus)): ?>
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
            <?php endif; ?>
            <?php if(in_array('epic_goal', $clientSelectedMenus)): ?>

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
             <?php endif; ?>
            <li class="">
                <a href="javascript:void(0)" class="open-modalp1" data-toggle="modal" data-target="#myModal1">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Diary.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Daily Diary </span>
                    </div>
                </a>
            </li>
        </div>
    </ul>
    <!-- Calendars submenu end -->

    <ul class="topsub-menu childNav" data-parent="measurements">
        <div class="submenu_back">
            <li class="">
                <a href="<?php echo e(url('measurement/height')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Height </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/chest')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Chest </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/neck')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Neck </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/bicepR')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Bicep R </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/bicepL')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Bicep L </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/forearmR')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Forearm R </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/forearmL')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Formearm L </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/abdomen')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Abdomen </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/hip')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Hip </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/thighR')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Thigh R </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/thighL')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Thigh L </span>
                    </div>
                </a>
            </li>
        
            <li class="">
                <a href="<?php echo e(url('measurement/calfR')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Calf R </span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="<?php echo e(url('measurement/calfL')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Progression Session.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Calf L </span>
                    </div>
                </a>
            </li>
        
            <li class="">
                <a href="<?php echo e(url('measurement/weight')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Weight </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/bfp')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> BFP </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/smm')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> SMM </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/bmr')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> BMR </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/bmi')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> BMI </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/bfm')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> BFM </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/hw')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> H/W Ratio </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/vis_fat')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Vis Fat </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/pulse')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Pulse </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="<?php echo e(url('personal-stastic/bp')); ?>">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Stretching.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Blood Pressure </span>
                    </div>
                </a>
            </li>
        </div>
    </ul>
    <!-- Epic Process submenu start -->
    <ul class="topsub-menu childNav" data-parent="process">
        <div class="submenu_back">
    <?php if(in_array('parq', $clientSelectedMenus)): ?>

            <li class="<?php echo e(in_array('parq', $clientSelectedMenus)?'':'disable'); ?> nav-item dropdown-sidemenu">
                <a href="#">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Questionaire.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Assess & Progress </span>
                    </div>
                    <i class="fa fa-angle-down"></i>
                </a>

                <!-- start submenu submenu -->
                <ul class="submenu">
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
                                    <span class="title">Injury Profile & Family History </span>
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
                   
                    <!-- end submenu submenu -->
                </li>
                 <?php endif; ?>
                <li class="nav-item dropdown-sidemenu">
                    <a href="#">
                        <div class="menuicon">
                            <img src="<?php echo e(asset('result/images/Time Management.png')); ?>">
                        </div>
                        <div class="item-inner">
                            <span class="title"> Weight & Date </span>
                        </div>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <!-- start submenu submenu -->
                    <ul class="submenu">
                        <div class="submenu_back"> 
                            <li class="">
                                <a href="<?php echo e(url('epic/measurements')); ?>">
                                    <div class="menuicon">
                                        <img src="<?php echo e(asset('result/images/Weight Management.png')); ?>">
                                    </div>
                                    <div class="item-inner">
                                        <span class="title"> Measurements </span>
                                    </div>
                                </a>
                            </li>
                          
                            <?php if(in_array('benchmark', $clientSelectedMenus)): ?>
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
                            <?php endif; ?>
                            <li class="">
                             <a href="<?php echo e(url('epic/WeightAndDate/smarater')); ?>">
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/BE SMARTER.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title">Be Smarter</span>
                                </div>
                            </a>
                        </li>
                        <?php if(in_array('epic_goal', $clientSelectedMenus)): ?>

                        <li class="">
                            <a href="<?php echo e(in_array('epic_goal', $clientSelectedMenus)?url('goals'):"#"); ?>" class="<?php echo e(in_array('epic_goal', $clientSelectedMenus)?'':'disable'); ?>">
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/Goal.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Epic Goal </span>
                                </div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="">
                            <a href="<?php echo e(url('epic/WeightAndDate/motivation')); ?>">
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
            <li class="nav-item dropdown-sidemenu">                    
                <a href="#">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Muscle Description.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title">Train & Gain </span>
                    </div>
                    <i class="fa fa-angle-down"></i>
                </a>
                <!-- start submenu submenu -->
                <ul class="submenu">
                    <div class="submenu_back"> 
                        <li class="">
                            <a href="<?php echo e(url('epic/train-gain/strength-in-numbers')); ?>">                                         
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/Strength In Numbers.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Strength in Numbers </span>
                                </div>
                            </a>
                        </li>
                        <?php if(in_array('activity_planner', $clientSelectedMenus)): ?>
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
                        <?php endif; ?>
                        <?php if(in_array('fitness_mapper', $clientSelectedMenus)): ?>
                        <li class="">
                            <a href="<?php echo e((Auth::id() == 78 || 1==1) && in_array('fitness_mapper', $clientSelectedMenus)?url('epic/train-gain/fitness-mapper'):"#"); ?>" class="<?php echo e((Auth::id() == 78 || 1==1) && in_array('fitness_mapper', $clientSelectedMenus)?'':'disable'); ?>">                                                 
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/Maps.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title">Fitness Mapper </span>
                                </div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="">
                            <a href="<?php echo e(url('epic/train-gain/rest-in-recovery')); ?>">
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
            <li class="nav-item dropdown-sidemenu">                    
                <a href="#">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Balanced Diet.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Trace & Replace </span>
                    </div>
                    <i class="fa fa-angle-down"></i>
                </a>
                <!-- start submenu submenu -->
                <ul class="submenu">
                    <div class="submenu_back"> 
                        <li class="">
                            <a href="<?php echo e(url('epic/trace-and-replace/nutritional-journal')); ?>">
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/Meal Description.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Nutritional Journal </span>
                                </div>
                            </a>
                        </li>

                        <?php if(in_array('recipes', $clientSelectedMenus)): ?>
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
                        <?php endif; ?>
                        <li class="">
                            <a href="<?php echo e(url('epic/trace-and-replace/portion-distrortion')); ?>">
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
                                    <span class="title"> 
                                    Nutritional Calender</span>
                                </div>
                            </a>
                        </li> 
                        <li class="">
                            <a href="<?php echo e(url('epic/trace-and-replace/limiting-vices')); ?>">
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
            <li class="nav-item dropdown-sidemenu">                    
                <a href="#">
                    <div class="menuicon">
                        <img src="<?php echo e(asset('result/images/Single Person.png')); ?>">
                    </div>
                    <div class="item-inner">
                        <span class="title"> Diarise & Prioritise </span>
                    </div>
                    <i class="fa fa-angle-down"></i>
                </a>
                <!-- start submenu submenu -->
                <ul class="submenu">
                    <div class="submenu_back"> 
                        <li class="">
                            <a href="<?php echo e(url('epic/diarise-and-prioritise/stay-focused')); ?>">
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/Stay Focussed.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Stay Focussed </span>
                                </div>
                            </a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(url('epic/diarise-and-prioritise/revise-adjust')); ?>">
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/To Do List.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title">Revise & Adjust </span>
                                </div>
                            </a>
                        </li> 
                        <li class="">
                            <a href="<?php echo e(url('epic/diarise-and-prioritise/consistency-beats')); ?>">
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/Check List.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Consistency Beats Intensity </span>
                                </div>
                            </a>
                        </li> 
                        <li class="">
                            <a href="<?php echo e(url('epic/diarise-and-prioritise/bucket-list')); ?>">
                                <div class="menuicon">
                                    <img src="<?php echo e(asset('result/images/Bucket List.png')); ?>">
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Create a bucket list</span>
                                </div>
                            </a>
                        </li> 
                        <li class="">
                            <a href="<?php echo e(url('epic/diarise-and-prioritise/celebrate')); ?>">
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
    <!-- Membership submenu start -->
    <ul class="topsub-menu childNav" data-parent="membership">
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
        <?php if(in_array('invoice', $clientSelectedMenus)): ?>
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
        <?php endif; ?>
    </div>
</ul>
<!-- Membership submenu end -->
<!-- Settings submenu start -->
<ul class="topsub-menu childNav" data-parent="settings">
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
        <?php if(in_array('calendar_settings', $clientSelectedMenus)): ?>
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
        <?php endif; ?>
    </div>
</ul>
<!-- Settings submenu end -->
<!-- Tools submenu start -->
<ul class="topsub-menu childNav" data-parent="tools">
    <div class="submenu_back"> 
       <?php if(in_array('calculator', $clientSelectedMenus)): ?>
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
    <?php endif; ?>
</div>
</ul>
<!-- Tools submenu end -->
</div>
<!--  Sidebar menu second step end -->
<div class="sidebar-overlay"></div>
<div class="mobile-sidebar-overlay"></div>
</div>
<!-- / sidebar-->
<script type="text/javascript">

   //   $('#sidebarNew').hide(); 
   //  $(document).ready(function() {

   //     setTimeout(function(){ 
   //      $('#sidebarNew').fadeIn(); 
   //   },100);
   // });

</script><?php /**PATH /Users/mahendra/Documents/projects/epicfitlaravelv6/resources/views/Result/partials/includes/sidebar.blade.php ENDPATH**/ ?>