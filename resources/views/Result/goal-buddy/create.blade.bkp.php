@extends('Result.masters.app')

@section('page-title')
    <span >Goal Buddy </span>
@stop

@section('required-styles')
    {{-- {!! Html::style('result/plugins/tooltipster-master/tooltipster.css?v='.time()) !!} --}}
    {!! Html::style('result/plugins/tipped-tooltip/css/tipped/tipped.css') !!}

    {!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}

    {!! Html::style('result/plugins/bootstrap-datepicker/css/datepicker.css') !!}
    {!! Html::style('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!}

    {!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    {!! Html::style('result/plugins/DataTables/media/css/DT_bootstrap.css') !!}

    {!! Html::style('result/css/custom.css?v='.time()) !!}
    {!! Html::style('result/css/goal-buddy.css?v='.time()) !!}

    <!-- VpForm -->
    {!! Html::style('result/vendor/vp-form/css/vp-form.css') !!}

@stop


@section('header-scripts')
    <!-- start: VpForm -->
    {!! Html::script('result/vendor/vp-form/js/jquery.windows.js') !!}
    {!! Html::script('result/vendor/vp-form/js/angular.js') !!}
    {!! Html::script('result/vendor/vp-form/js/autogrow.js') !!}
    {!! Html::script('result/vendor/vp-form/js/vp-form.js') !!}
    {!! Html::script('result/plugins/tipped-tooltip/js/tipped/tipped.js') !!}
    <!-- end: VpForm -->
@stop

@section('content')
    <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->
    <div class="panel panel-white vp-form leftcolomn" id="set-acc1" ng-app="vp-form">


        <div class="starting-screen fade-in goal-start-page" ng-controller="GBController">
            <div class="enter-btn active">
                <div class="goal-img">
                    <img src="{{asset('result/vendor/vp-form/images/goal-icon.png')}}">
                </div>
                <button type="button" class="btn btn-primary" ng-click="startFormInput()">
                    Start <i class="fa fa-check" aria-hidden="true"></i>
                </button>
                <br>
                <span class="press-enter">press <b>ENTER</b> to continue</span>

                {{-- <div class="starting-screen-input-container">
                    <div class="starting-screen-input-overlay"></div>
                    <input id="input-starting-screen" type="text" ng-keypress="pressEnter($event)">
                </div> --}}
            </div>
        </div>


        <!-- start: PANEL HEADING -->
        <div class="panel-heading showdiv" style="display: none;">
            <h5 class="panel-title"> <span class="icon-group-left"> <i class="clip-menu"></i> </span> Set Your Goals <span class="icon-group-right">

            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal"> <i class="fa fa-wrench"></i> </a> <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="client-overview"> <i class="fa fa-chevron-up"></i> </a> </span> </h5>
        </div>
        <!-- start: PANEL HEADING -->

        <!-- start: PANEL BODY -->
        <div class="panel-body showdiv" style="display: none;">
            <input id="m-selected-step" type="hidden" value="1">

            <form name="goalBuddy" action="#" role="form" class="smart-wizard" id="form">
                <!-- start: Pic crop Model -->
            @include('includes.partials.pic_crop_model')
            <!-- end: Pic crop Model -->
                <div id="set_goal" >

                    <!--form-horizontal-->
                    <div id="wizard" class="swMain goal-buddy-wizard parqForm">
                        <ul id="wizard-ul" class="top-step">
                            <li>
                                <a href="#step-1">
                                    <div class="stepNumber"> 1 </div>
                                    <span class="stepDesc"><small>DEFINE YOUR GOAL</small></span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-2">
                                    <div class="stepNumber"> 2 </div>
                                    <span class="stepDesc"><small>ESTABLISH YOUR MILE STONES</small></span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-3">
                                    <div class="stepNumber"> 3 </div>
                                    <span class="stepDesc"><small>ESTABLISH NEW HABITS</small></span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-4">
                                    <div class="stepNumber"> 4 </div>
                                    <span class="stepDesc"><small>CREATE TASKS</small></span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-5">
                                    <div class="stepNumber"> 5 </div>
                                    <span class="stepDesc"><small>SMART REVIEW</small></span>
                                </a>
                            </li>
                            <!--li> <a href="#step-6">
                              <div class="stepNumber"> 6 </div>
                              <span class="stepDesc"><small>FIND GOAL BUDDIES</small></span> </a> </li-->
                        </ul>


                        <!-- start: WIZARD STEP 1 -->
                        <div class="gb-step-1" id="step-1" ng-controller="GBWidgetOne">

                            @include('Result.goal-buddy.creategoal')

                            <div class="row row-btn-step-container">
                                <div class="col-sm-6 col-md-offset-6 res-btn-next" ng-click="validateWidgetInputs()">
                                    <button class="btn btn-primary btn-o btn-wide submit-first-form" style="display:none;">Set a goal</button>
                                    <span id="chakra" style="display:none;">Please wait..</span>

                                    <button id="goalNextButton" ng-disabled=" goalBuddy.name_goal.$invalid && goalBuddy.due_date.$invalid && goalBuddy.describe_achieve.$invalid && goalBuddy.goal_year.$invalid && goalBuddy.change_life.$invalid && goalBuddy.accomplish.$invalid && goalBuddy.fail-description.$invalid && goalBuddy.gb_relevant_goal.$invalid && goalBuddy.goal_seen.$invalid && goalBuddy.send_msgss.$invalid " class="btn btn-primary btn-o btn-wide pull-right btn-step next-step first-form-next"> Next <i class="fa fa-arrow-circle-right"></i> </button>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-wide pull-right margin-right-15 saveStep" ng-disabled="goalBuddy.name_goal.$invalid && goalBuddy.due_date.$invalid && goalBuddy.describe_achieve.$invalid && goalBuddy.goal_year.$invalid && goalBuddy.change_life.$invalid && goalBuddy.accomplish.$invalid && goalBuddy.fail-description.$invalid && goalBuddy.gb_relevant_goal.$invalid && goalBuddy.goal_seen.$invalid && goalBuddy.send_msgss.$invalid" data-step="1">Save as Draft</a>
                                </div>
                            </div> <!-- end row -->
                        </div>
                        <!-- end: WIZARD STEP 1 -->




                        <!-- start: WIZARD STEP 2 -->
                        <div class="gb-step-2" id="step-2" ng-controller="GBWidgetTwo"> <br />

                            <div class="row milestone-form">

                                <h6 class ="padding-15">
                                    <p class="pli-23"><em>What Milestone Do I need to Develop to Accomplish This Goal?</em></p>
                                </h6>

                                @include('Result.goal-buddy.createmilestone')

                            </div>



                            <div class="row row-btn-step-container" style="display: flex;flex-wrap: nowrap; margin-left: 0px; margin-right: 0px;">
                                <div class="col-sm-6 col-xs-12 res-pl-0" style="margin-bottom: 15px; flex: 1;">
                                    <button class="btn btn-primary btn-o back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back </button>
                                </div> <!-- end col6 -->

                                <div class="col-sm-6 m-b-0 col-xs-12 res-pl-0">
                                    <div class="form-group m-b-0" ng-click="validateWidgetInputs()" style="flex: 1;">
                                        <!--div class="col-sm-2 pull-right cancel_habbit_btn" style="display: none;">
                                           <button class="btn btn-danger  btn-wide  " id="cancel_habbit" > Cancel </button>
                                        </div-->
                                        <button ng-disabled="isMilestoneInvalid() || goalBuddy.gb_milestones_seen.$inavlid || goalBuddy.gb_milestones_reminder.$inavlid" class="btn btn-primary btn-o btn-wide pull-right btn-step next-step "> Next <i class="fa fa-arrow-circle-right"></i> </button>
                                        <!--button class="btn btn-danger btn-o btn-wide pull-right margin-right-15 cancel_milestone_btn hidden" id="cancel_milestone" type="button"> Cancel </button-->
                                        <a href="javascript:void(0)" class="btn btn-primary btn-wide pull-right margin-right-15 saveStep" ng-disabled="isMilestoneInvalid() || goalBuddy.gb_milestones_seen.$inavlid || goalBuddy.gb_milestones_reminder.$inavlid" data-step="2">Save as Draft</a>
                                    </div>
                                </div> <!-- end col6 -->
                            </div> <!-- end row -->
                        </div>

                        <!-- end: WIZARD STEP 2 -->


                        <!-- start: WIZARD STEP 3 -->
                        <div  class="gb-step-3"id="step-3" ng-controller="GBWidgetThree"> <br />
                            <div class="row habit-listing">
                                <div class ="col-md-12 p-l-0 padding-right-0">
                                    <h6 class="m-b-0 m-t-0"><em class="habit-name"></em></h6>
                                </div>
                                <div class ="row" style="margin-bottom: 15px">
                                    <div class ="col-md-8">
                                        <h6 class="m-t-5 m-b-5"><em>What Habits Do I need to Develop to Accomplish This Goal?</em></h6>
                                    </div>
                                    <div class ="col-md-4" style="margin-top:-20px;">
                                        <a class ="btn btn-primary pull-right add-habit">Establish New Habit</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover m-t-10 m-b-20" id="client-datatable">
                                        <thead>
                                        <tr>
                                            <th class="center mw-70 w70">Habit Name</th>
                                            <th>Frequency</th>
                                            <th>Milestone</th>
                                            <th>Shared</th>
                                            <th class="center">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody id="habitlist">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row habit-form" id="habit_form">
                                <h6 class="step-heading"><em>What Habits Do I need to Develop to Accomplish This Goal?</em></h6>

                                @include('Result.goal-buddy.createhabits')

                            </div>

                            <div class="row row-btn-step-container" style="display: flex; flex-wrap: wrap;">
                                <div class="col-sm-6 col-xs-12" style="margin-bottom: 15px; padding-left: 0px;flex: 1;">
                                    <button class="btn btn-primary btn-o back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back </button>
                                </div>
                                <div class="col-sm-6 col-xs-12 res-button" style="flex: 2;">
                                    <div class="form-group m-b-0">
                                        <!--div class="col-sm-2 pull-right cancel_habbit_btn" style="display: none;">
                                            <button class="btn btn-danger  btn-wide  " id="cancel_habbit" > Cancel </button>
                                        </div-->

                                        <div ng-click="validateWidgetInputs()">
                                            <button id="goalHabitNextButton" ng-disabled="goadBuddy.SYG_habits.$invalid || goadBuddy.milestone_value.$invalid || goadBuddy.SYG_notes.$invalid || goadBuddy.syg2_see_habit.$invalid || goadBuddy.syg2_send_msg.$invalid" class="btn btn-primary btn-o btn-wide pull-right btn-step next-step res-next"> Next <i class="fa fa-arrow-circle-right"></i> </button>
                                            <a href="javascript:void(0)" class="btn btn-primary btn-wide pull-right margin-right-15 saveStep" ng-disabled="goadBuddy.SYG_habits.$invalid || goadBuddy.milestone_value.$invalid || goadBuddy.SYG_notes.$invalid || goadBuddy.syg2_see_habit.$invalid || goadBuddy.syg2_send_msg.$invalid" data-step="3">Save as Draft</a>
                                        </div>


                                       {{--  <button class="btn btn-danger btn-o btn-wide pull-right margin-right-15 mt-15 cancel_habbit_btn hidden" id="cancel_habbit" type="button"> Cancel </button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end: WIZARD STEP 3 -->



                        <!-- start: WIZARD STEP 4 -->
                        <div class="gb-step-4" id="step-4" ng-controller="GBWidgetFour"> <br />
                            <div class="row task-listing">
                                <div class ="col-md-12 p-l-0 padding-right-0">
                                    <h6 class="m-b-0"><em class="task-name"></em></h6>
                                </div>

                                <div class ="row">
                                    <div class ="col-md-8" style="margin-bottom: 10px;">
                                        <h6 class="m-t-5"><em>Would you like to establish another task?</em></h6>
                                    </div>
                                    <div class ="col-md-4" style="margin-bottom: 10px;"> <a class ="btn btn-primary  pull-right add-task"  style = "margin-top:-20px;">Schedule New Task</a> </div>
                                </div>
                                <div class="table-responsive" style="border: none;">
                                    <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable-task">
                                        <thead>
                                        <tr>
                                            <th class="">Task Name</th>
                                            <th class="center mw-70 w70 no-sort">Priority</th>
                                            <!--th class="hidden-xxs">Due Date</th-->
                                            <th class="">Habit</th>
                                            <th class="center mw-70 w70 no-sort">Shared</th>
                                            <th class="center mw-70 w70 no-sort">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tasklist">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="task-form">
                                @include('Result.goal-buddy.createtask')
                            </div>
                            <div class="row row-btn-step-container" style="display: flex;flex-wrap: nowrap; margin-left: 0px; margin-right: 0px;">
                                <div class="col-sm-6 col-xs-12 res-pl-0" style="flex: 1;margin-bottom: 15px;">
                                    <button class="btn btn-primary btn-o back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back </button>
                                </div>
                                <div class="col-sm-6 m-b-0 col-xs-12 res-pl-0">
                                    <div ng-click="validateWidgetInputs()" style="flex: 1;">
                                        <button id="goalTaskNextButton" ng-disabled="goalBuddy.habit_value.$invalid || goalBuddy.SYG3_task.$invalid ||
                                    goalBuddy.SYG3_priority.$invalid || goalBuddy.note.$invalid || goalBuddy.SYG3_send_msg.$invalid || goalBuddy.SYG3_see_task.$invalid" class="btn btn-primary btn-o btn-wide pull-right btn-step next-step res-next"> Next <i class="fa fa-arrow-circle-right"></i> </button>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-wide pull-right margin-right-15 saveStep" ng-disabled="goalBuddy.habit_value.$invalid || goalBuddy.SYG3_task.$invalid ||
                                    goalBuddy.SYG3_priority.$invalid || goalBuddy.note.$invalid || goalBuddy.SYG3_send_msg.$invalid || goalBuddy.SYG3_see_task.$invalid" data-step="4">Save as Draft</a>
                                    </div>

                                   {{--  <button class="btn btn-danger btn-o btn-wide pull-right margin-right-15 cancel_task_btn hidden" id="cancel_task" type="button"> Cancel </button> --}}
                                </div>
                            </div>
                        </div>
                        <!-- end: WIZARD STEP 4 -->

                        <!-- start: WIZARD STEP 5 -->
                        <div class="gb-step-5" id="step-5" class="smart-review">

                            @include('Result.goal-buddy.smartreview')

                            <div class="row row-btn-step-container" style="display: flex;flex-wrap: nowrap; margin-left: 0px; margin-right: 0px;">
                                <div class="col-sm-6 col-xs-12 res-pl-0" style="flex: 1;margin-bottom: 15px;">
                                    <button class="btn btn-primary btn-o back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back </button>
                                </div>
                                <div class="col-sm-6 col-xs-12 res-pl-0" style="flex: 1;">
                                    <button class="btn btn-primary btn-o btn-wide pull-right final-step-goalbuddy"> Finish <i class="fa fa-arrow-circle-right"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- end: PANEL BODY -->
                </div>
            </form>
        </div> <!-- end: PANEL BODY -->
    </div>

    <div class="rightimagefixed" id="rightimage" style="background-image: url('{{ asset('result/images/calcul-2.jpeg') }}');display:none;">
        <div class="note_area">
           <textarea rows="5" data-autoresize id="goal_notes" name="describe_achieve" placeholder="GENERAL NOTES" class="form-control"></textarea>
        </div>
    </div>

@endsection


@section('required-script')
    {!! Html::script('result/js/jquery-ui.min.js') !!}

    <!-- start: Moment Library -->
    {!! Html::script('result/plugins/moment/moment.min.js') !!}
    <!-- end: Moment Library -->
    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}
    <!-- end: Bootstrap Typeahead -->

    {!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}

    <!-- start: Bootstrap timepicker -->
    {!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
    <!-- end: Bootstrap timepicker -->
    {!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}
    {!! Html::script('result/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}


    {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.js') !!}

    {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
    {!! Html::script('result/js/form-wizard-goal-buddy.js?v='.time()) !!}
    {!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('result/plugins/Jcrop/js/script.js') !!}

    {!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}

    {!! Html::script('result/js/helper.js?v='.time()) !!}
    {!! Html::script('result/js/goal-buddy.js?v='.time()) !!}

    <script>
        $(document).ready(function () {
            $('.add-habit').click(function() {
                $('#m-selected-step').val(3).trigger('change');
            });

            $('.add-task').click(function() {
                $('#m-selected-step').val(4).trigger('change');
            });


            $('.add-habit').click(function() {
                window.gbs3ResetForm();

                setTimeout(function() {
                    jQuery('html, body').stop().animate({
                        scrollTop: $('#viewport-3').find('li[data-index=0]').offset().top - 50
                    }, 'slow');
                }, 500);
            })

            $('.add-task').click(function() {
                window.gbs4ResetForm();

                setTimeout(function() {
                    jQuery('html, body').stop().animate({
                        scrollTop: $('#viewport-4').find('li[data-index=0]').offset().top - 50
                    }, 'slow');
                }, 500);
            });
        });
    </script>

    <script type="text/javascript">
        $.each(jQuery('textarea[data-autoresize]'), function() {
            var offset = this.offsetHeight - this.clientHeight;
             
            var resizeTextarea = function(el) {
                $(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };
            jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
        });

        $(document).ready(function() {
            Tipped.create('[data-toggle="tooltip"]', {
                skin: 'light', 
                radius: true, 
                size:'large',
            });

            function isMilestoneInvalid() {
                console.log('valid');
            }
        });
        
        $(window).bind('beforeunload', function(){
            if($('#m-selected-step').val() != 5){
                return 'Are you sure you want to leave?';
            }
        });
        
    </script>
@stop

@section('script-handler-for-this-page')
    $( ".panel-collapse.closed" ).trigger( "click" );
    FormWizard.init();

@stop()