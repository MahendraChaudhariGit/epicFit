@php
 $smartReview = [];
if(!empty($goalDetails) && $goalDetails) {
    if($goalDetails->gb_goal_review && isset($goalDetails->gb_goal_review))
      $smartReview = explode(',', $goalDetails->gb_goal_review);

      //dd($smartReview);
}
@endphp

<div class="step-smart-review-wrapper">
  <div class="alert alert-danger hidden" id="reqMsgSmart">
    Please carefully review your goal and make sure to check all the criteria of a S.M.A.R.T. goal.
  </div>
  <div id="formFinalSubmitError">
    
  </div>
 
  <fieldset class="padding-15">
    <div class="col-md-12">
      <!--div class="container Smart-review-session"-->
      <div class=" Smart-review-session">
        <h5 class="text-center m-t-20 m-b-0">Let's review your goal. Is your goal<span> S.M.A.R.T.?</span> </h5>
        <br>
        <div class="row">
          <div class="col-md-12 vp-form-input-list p-l-0 padding-right-0">
            {{--<div class="col-md-1"></div>--}}
            <div class="col-md-3 Smart-review-check">
              <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('specific', $smartReview)) active @endif">
                <div class="lable">
                  Specific <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
                </div>
                
                <input id="Specific" name="Specific" class="goalsmart hidden" data-is-checked="{{in_array('specific', $smartReview)?'yes' : 'no'}}" value="specific" required type="checkbox" @if(in_array('specific', $smartReview)) checked @endif>
              </div>
            </div>
            
            <div class="col-md-2 Smart-review-check">
              <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('measurable', $smartReview)) active @endif">
                <div class="lable">
                  Measurable <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
                </div>
                
                <input id="Measurable" name="Measurable" class="goalsmart hidden" data-is-checked="{{in_array('measurable', $smartReview)?'yes' : 'no'}}" value="measurable" required type="checkbox" @if(in_array('measurable', $smartReview)) checked @endif>
              </div>
            </div>
            <div class="col-md-2  Smart-review-check">
              <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('attainable', $smartReview)) active @endif">
                <div class="lable">
                  Attainable <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
                </div>
                
                <input id="Attainable" name="Attainable" class="goalsmart hidden" data-is-checked="{{in_array('attainable', $smartReview)?'yes' : 'no'}}" value="attainable" required type="checkbox" @if(in_array('attainable', $smartReview)) checked @endif>
              </div>
            </div>
            <div class="col-md-2  Smart-review-check">
              <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('relevant', $smartReview)) active @endif">
                <div class="lable">
                  Relevant <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
                </div>
                
                <input id="Relevant" name="Relevant" class="goalsmart hidden" data-is-checked="{{in_array('relevant', $smartReview)?'yes' : 'no'}}" value="relevant" required type="checkbox"  @if(in_array('relevant', $smartReview)) checked @endif>
              </div>
            </div>
            <div class="col-md-3 Smart-review-check">
              <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('time_Bound', $smartReview)) active @endif">
                <div class="lable">
                  Time-Bound <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
                </div>
                
                <input id="Time-Bound" name="Time-Bound" class="goalsmart hidden" data-is-checked="{{in_array('time_Bound', $smartReview)?'yes' : 'no'}}" value="time_Bound" required type="checkbox" @if(in_array('time_Bound', $smartReview)) checked @endif>
              </div>
            </div>
            {{-- {{ dd($goalDetails->toArray()) }} --}}
            <script>
              $(document).ready(function() {
                $('.prefTrainSlot').click(function() {
                  var $this = $(this),
                  input = $(this).find('input');                  
                  var attr = input.attr('data-is-checked');                
                  if (attr == 'no') {
                    $this.addClass('active');
                    $this.attr('data-status', 'active');
                    input.attr('data-is-checked', 'yes');
                    input.prop('checked', true);
                  } else {
                    $this.removeClass('active');
                    $this.attr('data-status', 'inactive');                    
                    input.attr('data-is-checked', 'no');
                    input.prop('checked' , false);
                  }
                });
              });
            </script>
          </div>
  
          <div class="col-md-12 well show_task-section m-t-20">
            <div class="col-md-4  task_declare">
              <p class="achieve-description-label"><strong> Name of goal:</strong></p>
              <p class="goal-name">{{ !empty($goalDetails) && isset($goalDetails->gb_goal_name) ? ucwords($goalDetails->gb_goal_name) : ''}}</p>
              <a href="" id="Step-your-goal1" class="Step-your-goal1  edit_goal_element"><i class="fa fa-pencil editable-pencil" style="color:#ff4401; position: absolute;left: 95%;top: 0%;font-size: 17px;"></i></a>
              <p class="achieve-description-label"><strong> I want to accomplish:</strong></p>
              <p class ="achieve-description">{{ !empty($goalDetails) && isset($goalDetails->gb_achieve_description) ? ucwords($goalDetails->gb_achieve_description) : ''}}</p>
             
              <p class="fail-description-label"><strong> Why is this important:</strong></p>
              <p class ="fail-description">{{ !empty($goalDetails) && isset($goalDetails->gb_important_accomplish) ? ucwords($goalDetails->gb_important_accomplish) : ''}}</p>
            </div>

            <div class="col-md-4 task_declare ">
              <p class="goal-seen"><strong></strong></p>
              <img id="smartReviewImg" src="" class="img-responsive SYGPreviewPics previewPics hidden"/>
              <p class="goal-due-date">
                {{ !empty($goalDetails) && isset($goalDetails->gb_goal_seen) ? 'Shared: '.ucwords($goalDetails->gb_goal_seen) : ''}}<br/>
                {{ !empty($goalDetails) && isset($goalDetails->gb_due_date) ? 'Due date: '.date('D, d F Y', strtotime($goalDetails->gb_due_date)) : ''}}
                <strong></strong>
              </p>
            </div>
            {{-- {{ dd($milestonesData->toArray()) }} --}}
            <div class="col-md-4 ">
              <div class="show_task-section  ">
                <h4>Milestone: <a href="" id="Step-your-goal4" class="Step-your-goal4  edit_goal_element"> <i class="fa fa-pencil editable-pencil" style="color:#ff4401;"></i></a></h4>
                  <ul class="milestone-label">
                    @if(!empty($milestonesData))
                    @foreach($milestonesData as $milestone)
                    <a class="Step-your-goal4 milestone-text"><li>{{ isset($milestone->gb_milestones_name) ? ucwords($milestone->gb_milestones_name): ''}}
                      </li></a>
                    <p>{{ isset($milestone->gb_milestones_seen) ? ucwords($milestone->gb_milestones_seen): ''}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ isset($milestone->gb_milestones_date) ? ucwords($milestone->gb_milestones_date): ''}}</p>
                    @endforeach
                    @endif
                  </ul>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 ">
                <div class="show_task-section well habbit-section ">
                  <h4>Habits: <a href="" id="Step-your-goal3" class="Step-your-goal3 edit_goal_element"> <i class="fa fa-pencil editable-pencil " style="color:#ff4401;"></i></a></h4>
                  <ul class ="habit-label">
                    @if(!empty($habitData))
                    @foreach($habitData as $habbit)
                    <a class="Step-your-goal3 habit-text" data="132"><li>{{ isset($habbit->gb_habit_name) ? ucwords($habbit->gb_habit_name): ''}}</li></a>
                      @if(!empty($habbit->gb_habit_seen))
                        <p>{{ isset($habbit->gb_habit_seen) ? ucwords($habbit->gb_habit_seen): ''}}</p>
                      @endif  
                    @endforeach
                    @endif
                  </ul>
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="show_task-section well task-section">
                  <h4>Tasks: <a href="" id="Step-your-goal2" class="Step-your-goal2 edit_goal_element"><i class="fa fa-pencil editable-pencil" style="color:#ff4401;"></i></a></h4>
                  <ul class="tasks-label">
                    @if(!empty($taskData))
                    @foreach($taskData as $task)
                    <a class="Step-your-goal2 task-text" data="202"><li>{{ isset($task->gb_task_name) ? ucwords($task->gb_task_name): ''}}</li></a>
                      @if(!empty($task->gb_task_seen))
                        <p>{{ isset($task->gb_task_seen) ? ucwords($task->gb_task_seen): ''}}</p>
                      @endif  
                    @endforeach
                    @endif
                  </ul>
                </div>
              </div>
            </div>

            <!-- Start for notes -->
            <div class="row">
              <div class="col-md-12 ">
                <div class="show_task-section well notes-section">
                  <h4>Notes:</h4>                 
                     <p class="gb_goal_notes">{{ !empty($goalDetails) && isset($goalDetails->gb_goal_notes) ? ucwords($goalDetails->gb_goal_notes) : ''}}</p>
                </div>
              </div>               
            </div>
            <!--  End notes -->  
          </div>
        </div>
        <br />
        <br />
      </div>
    </fieldset>
  </div>