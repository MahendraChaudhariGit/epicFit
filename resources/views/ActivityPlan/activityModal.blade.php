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
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_warm_up" aria-expanded="false">
                        <i class="icon-arrow"></i> Warm-Up
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="1">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_warm_up" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;" data-workout-id="1">
                      <div class="panel-body">
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_cardio" aria-expanded="false">
                        <i class="icon-arrow"></i> Cardiovascular Training
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="2">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_cardio" class="panel-collapse collapse" aria-expanded="false" data-workout-id="2">
                      <div class="panel-body">
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_exercises" aria-expanded="false">
                        <i class="icon-arrow"></i> Resistance Training
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="3">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_exercises" class="panel-collapse collapse" aria-expanded="false" data-workout-id="3">
                      <div class="panel-body">
                        
                        <!-- exercises area -->

                      </div>
                    </div>
                  </div>
                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_skill" aria-expanded="false">
                        <i class="icon-arrow"></i> Skill Training
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="4">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_skill" class="panel-collapse collapse" aria-expanded="false" data-workout-id="4">
                      <div class="panel-body">

                        <!-- skill area -->

                      </div>
                    </div>
                  </div>

                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_abdominal_training" aria-expanded="false">
                        <i class="icon-arrow"></i> Abdominal Training
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="5">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_abdominal_training" class="panel-collapse collapse" aria-expanded="false" data-workout-id="5">
                      <div class="panel-body">
                         
                         <!-- core area -->

                      </div>
                    </div>
                  </div>

                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_cool_down" aria-expanded="false">
                        <i class="icon-arrow"></i> Cool-Down
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="6">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_cool_down" class="panel-collapse collapse" aria-expanded="false" data-workout-id="6">
                      <div class="panel-body">
                        
                        <!-- cool_down area -->

                      </div>
                    </div>
                  </div>

                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_stretch" aria-expanded="false">
                        <i class="icon-arrow"></i> Recovery Routine/Stretching</span>
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="7">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_stretch" class="panel-collapse collapse" aria-expanded="false" data-workout-id="7">
                      <div class="panel-body">
                        
                        <!-- stretch area -->
                        
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_stretching" aria-expanded="false">
                        <i class="icon-arrow"></i> Stretching</span>
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="8">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_stretching" class="panel-collapse collapse" aria-expanded="false" data-workout-id="8">
                      <div class="panel-body">
                        
                        <!-- stretching area -->
                        
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_ploymetric-metcon" aria-expanded="false">
                        <i class="icon-arrow"></i> Ploymetric/MetCon</span>
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="9">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_ploymetric-metcon" class="panel-collapse collapse" aria-expanded="false" data-workout-id="9">
                      <div class="panel-body">
                        
                        <!-- Ploymetric/MetCon area -->
                        
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-white">
                    <div class="panel-heading">
                      <h5 class="panel-title">
                      <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#caledar-exe-accordion" href="#seg_olympic-lifts" aria-expanded="false">
                        <i class="icon-arrow"></i> Olympic Lifts</span>
                        <button type="button" class="btn btn-xs btn-default pull-right" data-workout="10">Add exercise</button>
                      </a></h5>
                    </div>
                    <div id="seg_olympic-lifts" class="panel-collapse collapse" aria-expanded="false" data-workout-id="10">
                      <div class="panel-body">
                        
                        <!-- Olympic Lifts area -->
                        
                      </div>
                    </div>
                  </div>
                  <div class="activity-video" style="display:none">
                  </div>
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