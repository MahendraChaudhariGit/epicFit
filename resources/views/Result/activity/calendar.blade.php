@extends('Result.masters.app')

@section('required-styles')
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
  <!-- {!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('css/bootstrap-timepicker.min.css') !!} -->
    <!-- Start: NEW datetimepicker css -->
    {{-- {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css?v='.time()) !!} --}}
    {{-- {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css?v='.time()) !!} --}}
    <!-- End: NEW datetimepicker css -->

    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    {!! Html::style('result/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!} 
    {!! Html::style('result/css/custom.css?v='.time()) !!}
    {!! Html::style('result/css/owl.carousel.css') !!}
    <style type="text/css">
    #calendar .calendEvent .eventTimeRange{
      color: #fff;
    }
    /*** VIDEO CONTROLS CSS ***/
    /* control holder */
    .control{
      background:#333;
      color:#ccc;
      position:absolute;
      bottom:0;
      left:0;
      width:100%;
      z-index:5;
      display:none;
    }
    /* control top part */
    .topControl{
      height:11px;
      border-bottom:1px solid #404040;
      padding:1px 5px;
      background:#1F1F1F; /* fallback */
      background:-moz-linear-gradient(top,#242424 50%,#1F1F1F 50%,#171717 100%);
      background:-webkit-linear-gradient(top,#242424 50%,#1F1F1F 50%,#171717 100%);
      background:-o-linear-gradient(top,#242424 50%,#1F1F1F 50%,#171717 100%);
    }
    /* control bottom part */
    .btmControl{
      clear:both;
      background: #1F1F1F; /* fallback */
      background:-moz-linear-gradient(top,#242424 50%,#1F1F1F 50%,#171717 100%);
      background:-webkit-linear-gradient(top,#242424 50%,#1F1F1F 50%,#171717 100%);
      background:-o-linear-gradient(top,#242424 50%,#1F1F1F 50%,#171717 100%);
      float: left;
      width: 100%;
      padding-bottom: 2px;
    }
    .control div.btn {
      float:left;
      width:34px;
      height:30px;
      padding:0 5px;
      border-right:1px solid #404040;
      cursor:pointer;
    }
    .control div.text{
      font-size:12px;
      font-weight:bold;
      line-height:30px;
      text-align:center;
      font-family:verdana;
      width:20px;
      border:none;
      color:#777;
    }
    .control div.btnPlay{
      background:url('/result/images/control.png') no-repeat -5px -30px !important;
      border-left:1px solid #404040;
    }
    .control div.paused{
      background:url('/result/images/control.png') no-repeat -7px 0px !important;
    }
    .control div.btnStop{
      background:url('/result/images/control.png') no-repeat -7px -31px !important;
      display:none;
    }
    .control div.spdText{
      border:none;
      font-size:14px;
      line-height:30px;
      font-style:italic;
      width: 60px;
      color: white;
    }
    .control div.selected{
      font-size:15px;
      color:#ccc;
      width: 30px;
    }
    .control div.sound{
      background:url('/result/images/control.png') no-repeat -88px -30px;
      border:none;
      float:right;
    }
    .control div.sound2{
      background:url('/result/images/control.png') no-repeat -88px -60px !important;
    }
    .control div.muted{
      background:url('/result/images/control.png') no-repeat -88px 0 !important;
    }
    .control div.btnFS{
      background:url('/result/images/control.png') no-repeat -44px 0;
      float:left;      
      border:0px;
    }
    .control div.btnLight{
      background:url('/result/images/control.png') no-repeat -44px -60px;
      border-left:1px solid #404040;
      float:right;
    }
    .control div.lighton{
      background:url('/result/images/control.png') no-repeat -49px -60px !important;
    }

    /* PROGRESS BAR CSS */
    /* Progress bar */
    .progress {
      width:75%;
      height:10px;
      position:relative;
      float:left;
      cursor:pointer;
      background: #444; /* fallback */
      background:-moz-linear-gradient(top,#666,#333);
      background:-webkit-linear-gradient(top,#666,#333);
      background:-o-linear-gradient(top,#666,#333);
      box-shadow:0 2px 3px #333 inset;
      -moz-box-shadow:0 2px 3px #333 inset;
      -webkit-box-shadow:0 2px 3px #333 inset;
      border-radius:10px;
      -moz-border-radius:10px;
      -webkit-border-radius:10px;
      margin-bottom: 10px;
    }
    .progress span {
      height:100%;
      position:absolute;
      top:0;
      left:0;
      display:block;
      border-radius:10px;
      -moz-border-radius:10px;
      -webkit-border-radius:10px;
    }
    .timeBar{
      z-index:10;
      width:0;
      background: #ff4401 /* fallback */
     /* background:-moz-linear-gradient(top,#A0DCFF 50%,#3FB7FC 50%,#16A9FF 100%);
      background:-webkit-linear-gradient(top,#A0DCFF 50%,#3FB7FC 50%,#16A9FF 100%);
      background:-o-linear-gradient(top,#A0DCFF 50%,#3FB7FC 50%,#16A9FF 100%)*/;
      box-shadow:0 0 1px #fff;
      -moz-box-shadow:0 0 1px #fff;
      -webkit-box-shadow:0 0 1px #fff;
    }
    .bufferBar{
      z-index:5;
      width:0;
      background: #777;
      background:-moz-linear-gradient(top,#999,#666);
      background:-webkit-linear-gradient(top,#999,#666);
      background:-o-linear-gradient(top,#999,#666);
      box-shadow:2px 0 5px #333;
      -moz-box-shadow:2px 0 5px #333;
      -webkit-box-shadow:2px 0 5px #333;
    }
    /* time and duration */
    .time{
      width:25%;
      float:right;
      text-align:center;
      font-size:11px;
      line-height:12px;
    }

    /* VOLUME BAR CSS */
    /* volume bar */
    .volume{
      position:relative;
      cursor:pointer;
      width:70px;
      height:10px;
      float:right;
      margin-top:10px;
      margin-right:0px;
    }
    .volumeBar{
      display:block;
      height:100%;
      position:absolute;
      top:0;
      left:0;
      background-color:#ff4401;
      z-index:10;
    }
  </style>
  @stop()

  @section('page-title')
  Activity Calendar
  @stop  

  @section('content')
  <input type="hidden" value="{{ isset($parq)?$parq->gender:'' }}" name="gender">
  <input type="hidden" value="{{ isset($parq)?$parq->client_id:'' }}" name="clientId">
  <input type="hidden" value="{{ $weight }}" name="weight">
  <div id="waitingShield" class="hidden text-center">
    <div>
      <i class="fa fa-circle-o-notch"></i>
    </div>
  </div>
  <div class="well well-sm hidden" id="rescoureNotFound">
    <!-- Add on load Error message here -->
  </div>

  <!-- Start: Calender -->
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <!-- start: Calendar Jumper -->
      <div class="btn-group calJumper">
        <a class="btn btn-primary btn-o dropdown-toggle hidden" data-toggle="dropdown" href="#">
          <i class="fa fa-angle-double-left"></i>
        </a>
        <ul role="menu" class="dropdown-menu dropdown-light">
          <li>
            <a href="#" data-jump-amount="1" data-jump-unit="weeks">
              1 week
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="2" data-jump-unit="weeks">
              2 weeks
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="3" data-jump-unit="weeks">
              3 weeks
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="4" data-jump-unit="weeks">
              4 weeks
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="5" data-jump-unit="weeks">
              5 weeks
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="6" data-jump-unit="weeks">
              6 weeks
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="7" data-jump-unit="weeks">
              7 weeks
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="8" data-jump-unit="weeks">
              8 weeks
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="6" data-jump-unit="months">
              6 months
            </a>
          </li>
          <li>
            <a href="#" data-jump-amount="1" data-jump-unit="years">
              1 year
            </a>
          </li>
        </ul>
      </div>
      <!-- end: Calendar Jumper -->

      <!-- Start: Calender body -->
      <input type="hidden" name="calendarSettingInput" value='{{ json_encode($calendar_settings) }}'>
      <div id='calendar'></div>
      <!-- Start: Calender body -->
    </div>
  </div>
  <!-- End: Calender -->

  <!-- Start: Activity modal -->
  <div class="modal fade" id="activityModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-capitalize"></h4>
        </div>
        <div class="modal-body bg-white">
          <div class="row">
            <form class="treningSeg-date-form" action="">


              <input type="hidden" name="date_id" value="" />
              <input type="hidden" name="clientplan_id" value="" />
              <input type="hidden" name="plan_start_date" value="" />
              <div class="col-md-12 col-xs-12">
                <div class="msgAlert hidden"> <!-- Append Message --> </div>
              </div>
              <div class="col-md-12 col-xs-12">
                <div class="btn btn-primary" id="fullScreenButton" type="button"> <span class="glyphicon glyphicon-fullscreen"></span></div>
                <!-- video section start -->
                <div class="left-video-section">
                  <div id="activityVideoCarousal" class="owl-carousel">
                  </div>
                  <div class="video-button">
                    <div class="play-bt">Play</div>
                    <div class="pause-bt" style="display:none;">Pause</div>
                  </div>
                </div>
                <!-- video section end -->
                <div class="panel-group accordion tabaccordian-right" id="caledar-exe-accordion">

                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                        <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#warm_up" aria-expanded="false">
                          <i class="icon-arrow"></i> Warm-Up
                          <button type="button" class="btn btn-xs btn-default pull-right" data-workout="1">Add exercise</button>
                        </a></h5>
                      </div>
                      <div id="warm_up" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;" data-workout-id="1">
                        <div class="panel-body">


                        </div>
                      </div>
                    </div>

                    <div class="panel panel-white">
                      <div class="panel-heading">
                        <h5 class="panel-title">
                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#cardio" aria-expanded="false">
                            <i class="icon-arrow"></i> Cardiovascular Training
                            <button type="button" class="btn btn-xs btn-default pull-right" data-workout="2">Add exercise</button>
                          </a></h5>
                        </div>
                        <div id="cardio" class="panel-collapse collapse" aria-expanded="false" data-workout-id="2">
                          <div class="panel-body">

                            <!-- video details end -->
                            {{-- <div class="video-details">
                              <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    <h4 class="panel-title">
                                      <a class="colorstyle" href="#content-1" data-toggle="collapse" data-parent="#accordion" aria-expanded="true">
                                        <div class="video-data">
                                          <div class="video-title">
                                            Cardio Video 1
                                          </div>
                                          <div class="video-value">
                                            5:00
                                          </div>
                                        </div> 
                                      </a>
                                    </h4>
                                  </div>
                                  <div class="panel-colapse collapse in" id="content-1">
                                    <div class="panel-body">
                                      <div class="video-data">
                                        <div class="video-title">
                                          Alternating Lunge
                                        </div>
                                        <div class="video-value">
                                          0:45
                                        </div>
                                      </div>
                                      <div class="video-data">
                                        <div class="video-title">
                                          Transition
                                        </div>
                                        <div class="video-value">
                                          0:15
                                        </div>
                                      </div> 
                                      <div class="video-data">
                                        <div class="video-title">
                                          Pushups
                                        </div>
                                        <div class="video-value">
                                          0:45
                                        </div>
                                      </div> 
                                      <div class="video-data">
                                        <div class="video-title">
                                          Dead Bug
                                        </div>
                                        <div class="video-value">
                                          5:00
                                        </div>
                                      </div> 
                                    </div>
                                  </div>
                                </div>

                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    <h4 class="panel-title">
                                      <a class="colorstyle" href="#content-2" data-toggle="collapse" data-parent="#accordion">
                                        <div class="video-data">
                                          <div class="video-title">
                                            Cardio Video 2
                                          </div>
                                          <div class="video-value">
                                            5:00
                                          </div>
                                        </div> 
                                      </a>
                                    </h4>
                                  </div>
                                  <div class="panel-colapse collapse" id="content-2">
                                    <div class="panel-body">
                                      <div class="video-data">
                                        <div class="video-title">
                                          Alternating Lunge
                                        </div>
                                        <div class="video-value">
                                          0:45
                                        </div>
                                      </div>
                                      <div class="video-data">
                                        <div class="video-title">
                                          Transition
                                        </div>
                                        <div class="video-value">
                                          0:15
                                        </div>
                                      </div> 
                                      <div class="video-data">
                                        <div class="video-title">
                                          Pushups
                                        </div>
                                        <div class="video-value">
                                          0:45
                                        </div>
                                      </div> 
                                      <div class="video-data">
                                        <div class="video-title">
                                          Dead Bug
                                        </div>
                                        <div class="video-value">
                                          5:00
                                        </div>
                                      </div> 
                                    </div>
                                  </div>
                                </div>

                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    <h4 class="panel-title">
                                      <a  class="colorstyle" href="#content-3" data-toggle="collapse" data-parent="#accordion">
                                        <div class="video-data">
                                          <div class="video-title">
                                            Cardio Video 3
                                          </div>
                                          <div class="video-value">
                                            5:00
                                          </div>
                                        </div> 
                                      </a>
                                    </h4>
                                  </div>
                                  <div class="panel-colapse collapse" id="content-3">
                                    <div class="panel-body">
                                      <div class="video-data">
                                        <div class="video-title">
                                          Alternating Lunge
                                        </div>
                                        <div class="video-value">
                                          0:45
                                        </div>
                                      </div>
                                      <div class="video-data">
                                        <div class="video-title">
                                          Transition
                                        </div>
                                        <div class="video-value">
                                          0:15
                                        </div>
                                      </div> 
                                      <div class="video-data">
                                        <div class="video-title">
                                          Pushups
                                        </div>
                                        <div class="video-value">
                                          0:45
                                        </div>
                                      </div> 
                                      <div class="video-data">
                                        <div class="video-title">
                                          Dead Bug
                                        </div>
                                        <div class="video-value">
                                          5:00
                                        </div>
                                      </div> 
                                    </div>
                                  </div>
                                </div>


                              </div>
                            </div> --}}
                            <!-- video details end -->

                          </div>
                        </div>
                      </div>

                      <div class="panel panel-white">
                        <div class="panel-heading">
                          <h5 class="panel-title">
                            <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#exercises" aria-expanded="false">
                              <i class="icon-arrow"></i> Resistance Training
                              <button type="button" class="btn btn-xs btn-default pull-right" data-workout="3">Add exercise</button>
                            </a></h5>
                          </div>
                          <div id="exercises" class="panel-collapse collapse" aria-expanded="false" data-workout-id="3">
                            <div class="panel-body">

                              <!-- exercises area -->

                            </div>
                          </div>
                        </div>

                        <div class="panel panel-white">
                          <div class="panel-heading">
                            <h5 class="panel-title">
                              <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#skill" aria-expanded="false">
                                <i class="icon-arrow"></i> Skill Training
                                <button type="button" class="btn btn-xs btn-default pull-right" data-workout="4">Add exercise</button>
                              </a></h5>
                            </div>
                            <div id="skill" class="panel-collapse collapse" aria-expanded="false" data-workout-id="4">
                              <div class="panel-body">

                                <!-- skill area -->

                              </div>
                            </div>
                          </div>

                          <div class="panel panel-white">
                            <div class="panel-heading">
                              <h5 class="panel-title">
                                <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#abdominal_training" aria-expanded="false">
                                  <i class="icon-arrow"></i> Abdominal Training
                                  <button type="button" class="btn btn-xs btn-default pull-right" data-workout="5">Add exercise</button>
                                </a></h5>
                              </div>
                              <div id="abdominal_training" class="panel-collapse collapse" aria-expanded="false" data-workout-id="5">
                                <div class="panel-body">

                                 <!-- core area -->

                               </div>
                             </div>
                           </div>

                           <div class="panel panel-white">
                            <div class="panel-heading">
                              <h5 class="panel-title">
                                <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#cool_down" aria-expanded="false">
                                  <i class="icon-arrow"></i> Cool-Down
                                  <button type="button" class="btn btn-xs btn-default pull-right" data-workout="6">Add exercise</button>
                                </a></h5>
                              </div>
                              <div id="cool_down" class="panel-collapse collapse" aria-expanded="false" data-workout-id="6">
                                <div class="panel-body">

                                  <!-- cool_down area -->

                                </div>
                              </div>
                            </div>

                            <div class="panel panel-white">
                              <div class="panel-heading">
                                <h5 class="panel-title">
                                  <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#stretch" aria-expanded="false">
                                    <i class="icon-arrow"></i> Recovery Routine/Stretching</span>
                                    <button type="button" class="btn btn-xs btn-default pull-right" data-workout="7">Add exercise</button>
                                  </a></h5>
                                </div>
                                <div id="stretch" class="panel-collapse collapse" aria-expanded="false" data-workout-id="7">
                                  <div class="panel-body">

                                    <!-- stretch area -->

                                  </div>
                                </div>
                              </div>
                              <div class="panel panel-white">
                                <div class="panel-heading">
                                  <h5 class="panel-title">
                                    <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#stretching" aria-expanded="false">
                                      <i class="icon-arrow"></i> Stretching</span>
                                      <button type="button" class="btn btn-xs btn-default pull-right" data-workout="8">Add exercise</button>
                                    </a></h5>
                                  </div>
                                  <div id="stretching" class="panel-collapse collapse" aria-expanded="false" data-workout-id="8">
                                    <div class="panel-body">

                                      <!-- stretching area -->

                                    </div>
                                  </div>
                                </div>
                                <div class="panel panel-white">
                                  <div class="panel-heading">
                                    <h5 class="panel-title">
                                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#ploymetric-metcon" aria-expanded="false">
                                        <i class="icon-arrow"></i> Ploymetric/MetCon</span>
                                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="9">Add exercise</button>
                                      </a></h5>
                                    </div>
                                    <div id="ploymetric-metcon" class="panel-collapse collapse" aria-expanded="false" data-workout-id="9">
                                      <div class="panel-body">

                                        <!-- Ploymetric/MetCon area -->

                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-white">
                                    <div class="panel-heading">
                                      <h5 class="panel-title">
                                        <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#olympic-lifts" aria-expanded="false">
                                          <i class="icon-arrow"></i> Olympic Lifts</span>
                                          <button type="button" class="btn btn-xs btn-default pull-right" data-workout="10">Add exercise</button>
                                        </a></h5>
                                      </div>
                                      <div id="olympic-lifts" class="panel-collapse collapse" aria-expanded="false" data-workout-id="10">
                                        <div class="panel-body">

                                          <!-- Olympic Lifts area -->

                                        </div>
                                      </div>
                                    </div>

                                    <div class="activity-video" style="display:none">

                                    </div>
                                    {{-- Activity Video slider start --}}
                                    {{-- <div class="activity-video-slider">
                                      <div id="carousel-example" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                          <div class="item active">
                                            <video width="100%" height="400px" controls="">                              
                                              <source src="https://result.epictrainer.com/uploads/89ffadbc30292002f92018618fb3084c.m4v" type="video/mp4">
                                              </video>
                                            </div>
                                            <div class="item">
                                              <video width="100%" height="400px" controls="">                              
                                                <source src="https://result.epictrainer.com/uploads/89ffadbc30292002f92018618fb3084c.m4v" type="video/mp4">
                                                </video>
                                              </div>
                                              <div class="item">
                                                <video width="100%" height="400px" controls="">                              
                                                  <source src="https://result.epictrainer.com/uploads/89ffadbc30292002f92018618fb3084c.m4v" type="video/mp4">
                                                  </video>
                                                </div>
                                              </div>

                                              <a class="left carousel-control" href="#carousel-example" data-slide="prev">
                                                <i class="fa fa-angle-left" aria-hidden="true"></i>
                                              </a>
                                              <a class="right carousel-control" href="#carousel-example" data-slide="next">
                                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                                              </a>
                                            </div>
                                          </div> --}}
                                          {{-- Activity Video slider start --}}
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                  <button type="button" class="btn btn-primary" id="saveDateTrainingSeg" data-event-time="future">Save</button>
                                  <a class="btn btn-red pull-left" href="#" id="deleteClientClass" data-no-of-week="">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    Delete from <span class="bookingTypeName">Calendar</span>
                                  </a>

                                </div>
                              </div> 
                            </div>
                          </div>
                          <!-- End: Activity modal -->

                          <!-- Start: Exercise add modal -->
                          @include('includes.partials.add_exercise_modal',['exerciseData'=>$exerciseData,'abWorkouts' => $abWorkouts])
                          <!-- End: Exercise add modal --> 

                          <!-- Start: Daily Log Modal -->
                          {{-- @include('Result.partials.dailyLogModal',['catType' => $mealsCategoryArr]) --}}
                          <!-- End: Daily Log Modal -->

                          <!--****** Program edit popup start **** -->

                          @if($data)
                          <div class="modal" id="editPrograme" role="dialog" style="display: block">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">A new program added for you</h4>
                                  <button type="button" class="close close m-t--10 close-btn" data-dismiss="modal">×</button>
                                </div>
                                <div class="inWeekData">


                                  <div class="modal-body bg-white">
                                    <div class="row">
                                      <div class="col-md-12 col-xs-12">
                                        <div class="row">
                                          <div class="col-md-12 col-xs-12 mb-2">
                                           @foreach($data as $key)
                                            <div class="radio clip-radio radio-primary radio-inline m-b-0 programWeek">
                                            <input type="radio" name="program" data-client-planid="{{ $key['id'] }}" data-no-of-days="{{ $key['noOfDaysInWeek'] }}" data-title="{{ $key['title'] }}" data-start-date="{{ $key['start_date'] }}" data-option-value="{{ $key['dayOption']}}" id="{{ $key['id'] }}" value="{{ $key['title'] }}" data-plan-type="{{$key['planType']}}" data-phase-data="{{$key['phaseData']}}" data-week-days="{{$key['weeksToExercise']}}" >
                                            <label for="{{ $key['id'] }}">
                                              {{ $key['title'] }}
                                            </label>
                                          </div>
                                          @endforeach

                                        </div>
                                        <input type="hidden" name="programId" value="">
                                        <input type="hidden" name="dayOptionValue" value="">
                                        <input type="hidden" name="planType" value="">
                                        <input type="hidden" name="sameDaysAcrossProgram" value="1">

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 desc">
                                          <div class="form-group">
                                            <h4>Name of program: <span class="showTitle"></span></h4>
                                          </div>
                                          <div class="form-group daysWeek" style="display: none;">
                                            <h4>Days in week required: <span class="weekProgram"></span></h4>
                                          </div>
                                          <div class="form-group startDateSelect" style="display: none;">
                                            <h4>Start Date: <input type="date" id="startDateSelect" name="startDate" value="" class="startDateValue" readonly></h4> 
                                          </div>

                                          <div class="form-group startDateShow" style="display: none;">
                                           <h4>Start Date: <input type="date" id="startDateShow" name="startDate" class="startDateValue" value="" min="{{ date("Y-m-d") }}"></h4>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="selectProgramDays" style="display: none;">
                                      <h4>Please select days</h4>
                                    </div>
                                    <div class="multiphaseProgramDays" style="display:none">

                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary daysInWeek">Save</button>
                              </div>
                            </div>



                          </div>
                        </div>
                      </div>
                      @endif
{{-- modal --}}


  <!-- Modal -->
  <div class="modal fade" id="myModalPdf" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          {{-- <h4 class="modal-title">Modal Header</h4> --}}
        </div>
        <div class="modal-body">
          <iframe class="pdf-href" src="" width="100%" height="500px"> </iframe>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  {{-- end modal --}}
                      <!--****** Program edit popup end **** --> 

                      @stop

                      @section('required-script')
                      {!! Html::script('result/js/jquery-ui.min.js') !!}
                      {!! Html::script('result/plugins/moment/moment.min.js') !!}
                      {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
                      {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
                      <!-- Start:  NEW datetimepicker js -->
                      {!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}
                      <!-- End: NEW datetimepicker js --> 

  <!-- {!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script('js/bootstrap-timepicker.js') !!} -->

    {!! Html::script('result/js/main-client.js?v='.time()) !!}

    {!! Html::script('result/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
    {!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!}

    {!! Html::script('result/js/fitness-planner/bodymapper.js?v='.time()) !!} 
    {!! Html::script('result/js/helper.js?v='.time()) !!}
    {!! Html::script('result/js/owl.carousel.js') !!}
    {!! Html::script('assets/js/activity-calendar.js') !!}
    <script>
   /* var loggedInUser = {
      //type: '{{ Session::get('userType') }}',
      type: '{{ Auth::user()->account_type }}',
      id: {{ Auth::user()->account_id }},
      userId: {{ Auth::id() }},
      name: '{{ Auth::user()->fullName }}'
    },*/
    popoverContainer = $('#calendar');

    $("#activityVideoCarousal").each(function() {
      $(this).owlCarousel({
        autoplay:false,
        margin:30,
        loop:false,
        dots:false,
        nav:true,
        items :1,
        responsive:{
          0:{
            items:1,
          },
          768:{
            items:1,
          },
          992:{
            items:1,
          }
        }
      });
    });
    $("#foodSizeCarousal").each(function() {
      $(this).owlCarousel({
        autoplay:false,
        margin:0,
        loop:false,
        dots:false,
        nav:false,
        items :1,
        responsive:{
          0:{
            items:1,
          },
          768:{
            items:1,
          },
          992:{
            items:1,
          }
        }
      });
    });
  </script>
  <script type="text/javascript">
      var BASE_URL = "{{ url('/') }}";
    $(document).ready(function(){
      $("#editPrograme .close-btn").click(function(){
        $("#editPrograme").hide();
      });
    });
  </script>
  <!-- {!! Html::script('js/events-client.js?v='.time()) !!} -->
  {{-- {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!} --}}
  {!! Html::script('result/js/activity-calendar.js?v='.time()) !!}
  {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
  {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
  {!! Html::script('result/js/activity-calendar-helper.js?v='.time()) !!}
  {{-- {!! Html::script('result/js/webcam.js?v='.time()) !!} --}}
  {{-- {!! Html::script('result/js/daily-log.js?v='.time()) !!} --}}

  @stop()


  @section('script-handler-for-this-page')
  @stop()

  @section('script-after-page-handler')
  @stop()


