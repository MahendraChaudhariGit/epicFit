<div class="row ">
  <input type="hidden" class="form-control" id="milestones_id" value="{{isset($mileStoneIdStr)?$mileStoneIdStr:null}}" name="milestones_id">
  <div class="col-md-6">
    <fieldset class="padding-15">
      <legend>Milestone Details</legend>
      <div class="form-group">
        <label class="strong" for="Milestones">What Milestones I've Got to Accomplish Before I Reach My Goal? </label>
        <div class="row">
          <div class="col-md-8">
            <input type="text" class="form-control" id="Milestones" value="" name="Milestones" >
          </div>
          <div class="col-md-4">
            <button class="btn btn-primary Btn_milestone" > <span>Add Milestone</span> </button>
          </div>
        </div>
          <?php $milestonesSeen=''; $milestonesReminder=''; $milestonesGoalId=''; ?>

        <div class="dd mile_section row" >
          <ul class="dd-list row" >
          @if((isset($milestonesData))&&($milestonesData->count() > 0))
            @foreach($milestonesData as $milestones)
              <li class="dd-item row m-t-10" style="line-height: 20px; !important" data-milestones-id="{{$milestones->id}}">
                <div class="col-md-5" style="padding:0px 5px 0px 0px;">
                  <div class="show_edit texts">
                    <p class="milestones-text">{{$milestones->gb_milestones_name}}</p>
                    <form class="milestones-form">
                      <input name="milestones" style="display:none; " class="milestones-name form-control"  value="{{$milestones->gb_milestones_name}}" type="text"> 
                    </form>
                  </div>
                </div>
                <div class="col-md-4" style="padding:0px 5px 0px 0px;">
                  <input class="form-control milestones-date" autocomplete="off" name="milestones-date"  required="" value="{{ $milestones->gb_milestones_date }}" type="text">
                </div>
                <div class="col-md-2 m-t-10 pencil_find_sibling" >
                  <a><i class="fa fa-pencil edit-milestone-info "></i></a>
                  <a><i class="fa fa-save save-milestone-info " ></i></a>
                  <a> <i class="fa fa-times delete-milestone-info " data-entity="milestone" ></i></a>
                </div>
              </li>
              <?php $milestonesSeen =isset($milestonesData)?$milestones->gb_milestones_seen:''; 
                    $milestonesReminder =isset($milestonesData)?$milestones->gb_milestones_reminder:''; 
                    $milestonesGoalId =isset($milestonesData)?$milestones->goal_id:''; 
              ?>
            @endforeach 
          @endif
          </ul> 
        </div>
      </div>
    </fieldset>
  </div>
  <input type="hidden" class="form-control" id="goal_milestones_id" value="{{isset($milestonesGoalId)?$milestonesGoalId:null}}" name="goalmilestones_id">
  <div class="col-md-6">
    <fieldset class="padding-15">
      <legend>Sharing</legend>
      <div class="form-group">
        <label class="strong">Who can view your milestones? </label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_seen" id="SYG3_see_milestones0" value="everyone" {{ isset($milestonesData) && $milestonesSeen == 'everyone'?'checked':'' }} @if((!isset($milestonesData)) || $milestonesData->count() < 1) checked @endif>
            <label for="SYG3_see_milestones0"> Everyone </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_seen" id="SYG3_see_milestones1" value="milestones-friends" {{ isset($milestonesData) && $milestonesSeen  == 'milestones-friends'?'checked':'' }}>
            <label for="SYG3_see_milestones1"> My Friends </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_seen" id="SYG3_see_milestones2" value="Just_Me" {{ isset($milestonesData) && $milestonesSeen == 'Just_Me'?'checked':'' }}>
            <label for="SYG3_see_milestones2"> Just Me </label>
          </div>
          <div class ="milestones-friends-section" style="display: none;">
            <table>
              <tr>
                <td>
                  <button type="button" class="btn btn-wide btn-o btn-success" style="margin-bottom: 5px;" data-toggle="modal" data-target="#milestonesfriendModal">Choose</button>
                  <br>
                  <button type="button" class="btn btn-wide btn-o btn-success" data-toggle="modal" data-target="#milestonesfriendModal">Friends</button>
                </td>
                <td>
                  <span>
                    <a href="javascript:choose_friends()" id="view_count" style="margin: 0px 0px 5px 5px;">0 Friends can view</a>
                    <br>
                    <a href="javascript:choose_friends()" id="edit_count" style="margin: 0px 0px 5px 5px;">0 Friends can edit</a>
                  </span>
                </td>
              </tr>
            </table>
          </div>
            <div id="milestonesfriendModal" class="modal fade" role="dialog">
              <div class="modal-dialog"> 
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <div class ="row">
                      <div class ="col-md-6">
                        <h4> Who can see this task?</h4>
                      </div>
                      <div class ="col-md-6">
                        <input type ="checkbox" name ="all-friends">
                        All my friends can view this task 
                      </div>
                    </div>
                    <div class ="row">
                      <div class ="col-md-6">
                        <h4>Your friends:</h4>
                      </div>
                      <div class ="col-md-6">
                        <h4>Friends with access to this task:</h4>
                      </div>
                      <button type="button" class="btn btn-info btn-lg" style ="margin: 10px 0px 13px 15px;" data-dismiss="modal" data-size="l">Ok</button>
                    </div>
                  <div class="modal-footer"> </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>
      </div>
    
      <div class="form-group">
        <label class="strong">Send e-mail / SMS reminders </label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_reminder" id="send_msgss_milestones1" value="when_overdue" {{ isset($milestonesData) && $milestonesReminder == 'when_overdue'?'checked':'' }} @if(!isset($milestonesData) || $milestonesData->count() < 1) checked @endif>
            <label for="send_msgss_milestones1">When Overdue</label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_reminder" id="send_msgss_milestones2" value="daily" {{ isset($milestonesData) && $milestonesReminder == 'daily'?'checked':'' }}>
            <label for="send_msgss_milestones2"> Daily </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_reminder" id="send_msgss_milestones3" value="weekly" {{ isset($milestonesData) && $milestonesReminder == 'weekly'?'checked':'' }}>
            <label for="send_msgss_milestones3"> Weekly </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_reminder" id="send_msgss_milestones4" value="monthly" {{ isset($milestonesData) && $milestonesReminder == 'monthly'?'checked':'' }}>
            <label for="send_msgss_milestones4"> Monthly </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="gb_milestones_reminder" id="send_msgss_milestones5" value="none" {{ isset($milestonesData) && $milestonesReminder == 'none'?'checked':'' }}>
            <label for="send_msgss_milestones5"> None </label>
          </div>
        </div>
      </div>  
    </fieldset>
  </div>
</div>