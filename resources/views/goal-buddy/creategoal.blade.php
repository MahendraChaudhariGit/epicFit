<div class="row">
  <div class="col-sm-6">
    <fieldset class="padding-15">
      <legend>Goal Details</legend>
      <input type="hidden" id="update-record" value="" name="update_value">
      <input type="hidden" value ="{{isset($goalDetails)?$goalid:null}}" name ="lastId" id="last-insert-id">
      <div class="form-group">
        <div class="row">
          <div class="col-md-12 define_habit">
            <h5><span class="glyphicon glyphicon-question-sign question-sign"></span> Don't know how to define your habit?</h5>
            <input type ="hidden" name = "goal_modal" value ="" id ="goal-template"/>
            <button type="button" class="btn btn-o btn-default btn-block btn_demo" data-toggle="modal" data-target="#demotemplate">Choose one from a template</button>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="name_goal" class="strong">Name Your Goal  *</label>
        <input type="text" class="form-control" id="name_goal" value="{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}" name="name_goal" required />
      </div>
      <div class="form-group">
        <label class="strong" for="describe_achieve">Describe what you want to achieve </label>
        <div>
          {!! Form::textarea('describe_achieve', isset($goalDetails)?$goalDetails->gb_achieve_description:null, ['class'=>'form-control', 'id'=>'describe_achieve']) !!}
        </div>
      </div>
      <div class="form-group upload-group m-t-10">
        <input type="hidden" name="prePhotoName" value="{{isset($goalDetails)?$goalDetails->gb_image_url:''}}" class="no-clear" id="goalBodyLogo">
        <input type="hidden" name="entityId" value="" class="no-clear">
        <input type="hidden" name="saveUrl" value="photo/save" class="no-clear">
        <input type="hidden" name="photoHelper" value="SYG" class="no-clear">
        <input type="hidden" name="cropSelector" value="" class="no-clear">
        <label class="btn btn-primary btn-file add-photo"> 
          <span><i class="fa fa-plus"></i> Add Photo</span>
          <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
        </label>
        <div class="m-t-10"> 
          @if(isset($goalDetails->gb_image_url) &&($goalDetails->gb_image_url!=''))
            <img src="{{ dpSrc($goalDetails->gb_image_url) }}" class="SYGPreviewPics previewPics"  /> 
          @else
            <img class="hidden SYGPreviewPics previewPics" />
          @endif
        </div>
        <span class="help-block m-b-0"></span>
        <input type="hidden" name="goallogo" value="" class="SYGPreviewPics" value="{{isset($goalDetails)?$goalDetails->gb_image_url:''}}">
      </div>
      <div class="form-group">
        <label class="strong">Is this goal an immediate priority for you? (maximum 3) </label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="goal_year" id="goal_year0" value="yes" {{ isset($goalDetails) && $goalDetails->gb_is_top_goal == 'yes'?'checked':'' }}>
            <label for="goal_year0"> Yes </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="goal_year" id="goal_year1" value="no" {{ isset($goalDetails) && $goalDetails->gb_is_top_goal == 'no'?'checked':'' }}  @if(!isset($goalDetails)) checked @endif >
            <label for="goal_year1"> No </label>
          </div>
        </div>
      </div>
      <div class="form-group btn_dd">
        <label for="change_life" class="strong">By accomplishing your goal, how will your life change?  *</span></label>
        <div>
          {!! Form::select('change_life', ['Improve_health'=>'Improve health','Improve_mental_and_emotional_wellness'=>'Improve mental and emotional wellness','Improve_lifestyle'=>'Improve lifestyle','Improve_self_image'=>'Improve self image','Improve_family_home_environment'=>'Improve family/home environment','Improve_personal_relationships'=>'Improve personal relationships','improve_career_satisfaction'=>'Improve career satisfaction','improve_financial_situation'=>'Improve financial situation','Other'=>'Other'], isset($goalDetails)?explode(',' , $goalDetails->gb_change_life_reason):[], ['class'=>'selectpicker form-control onchange-set-neutral goal-change-life','id'=>'change_life', 'multiple', 'required']) !!}
        </div>
      </div>
      <div class="form-group">
        <label class="strong" for="Accomplish">Why is it important to accomplish this goal? </label>
        <div class="row">
          <div class="col-md-12">
            {!! Form::textarea('accomplish', isset($goalDetails)?$goalDetails->gb_important_accomplish:null, ['class'=>'form-control', 'id'=>'accomplish']) !!}
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="strong" for="fail-description"> What happen if you do not achieve your goal? </label>
        <div class="row">
          <div class="col-md-12">
            {!! Form::textarea('fail-description', isset($goalDetails)?$goalDetails->gb_fail_description:null, ['class'=>'form-control', 'id'=>'fail-description']) !!}
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="strong" for="gb_relevant_goal">Why is this goal relevant? Is this goal associated with a life event or special occasion? </label>
        <div class="row">
          <div class="col-md-12">
            {!! Form::textarea('gb_relevant_goal', isset($goalDetails)?$goalDetails->gb_relevant_goal:null, ['class'=>'form-control', 'id'=>'gb_relevant_goal']) !!}
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="strong" for="date-time">What is the due date for this goal? *</label>
        <div class="date">
          <input type="text" class="form-control" name="due_date" id='datepicker_SYG' autocomplete="off" value="{{isset($goalDetails)? $goalDetails->goal_due_date:null}}" readonly="readonly"  required="required" > 
        </div>
      </div>
    </fieldset>
  </div>
  <div class="col-md-6">
    <fieldset class="padding-15">
      <legend>Sharing</legend>
      <div class="form-group">
        <label class="strong">Who can view your goal? </label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="goal_seen" id="see_task0" value="Everyone" {{ isset($goalDetails) && $goalDetails->gb_goal_seen == 'Everyone'?'checked':'' }} @if(!isset($goalDetails)) checked @endif>
            <label for="see_task0"> Everyone </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="goal_seen" id="see_task1" value="Just Me" {{ isset($goalDetails) && $goalDetails->gb_goal_seen == 'Just Me'?'checked':'' }}>
            <label for="see_task1"> Just Me </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="strong">Send e-mail / SMS reminders </label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="send_msgss" id="send_msgss1" value="when_ overdue" {{ isset($goalDetails) && $goalDetails->gb_reminder_type == 'when_ overdue'?'checked':'' }} @if(!isset($goalDetails)) checked @endif>
            <label for="send_msgss1">When Overdue</label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="send_msgss" id="send_msgss2" value="daily" {{ isset($goalDetails) && $goalDetails->gb_reminder_type == 'daily'?'checked':'' }}>
            <label for="send_msgss2"> Daily </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="send_msgss" id="send_msgss3" value="weekly" {{ isset($goalDetails) && $goalDetails->gb_reminder_type == 'weekly'?'checked':'' }}>
            <label for="send_msgss3"> Weekly </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="send_msgss" id="send_msgss4" value="monthly" {{ isset($goalDetails) && $goalDetails->gb_reminder_type == 'monthly'?'checked':'' }}>
            <label for="send_msgss4"> Monthly </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="send_msgss" id="send_msgss5" value="none" {{ isset($goalDetails) && $goalDetails->gb_reminder_type == 'none'?'checked':'' }}>
            <label for="send_msgss5"> None </label>
          </div>
        </div>
      </div>
    </fieldset>
  </div>
</div>

<!-- Modal -->
<div id="demotemplate" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"> <a href="#myTab2_example1" data-toggle="tab" aria-expanded="true"> Goals Templates </a>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="tabbable">
          <ul id="myTab2" class="nav nav-tabs nav-justified">
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="myTab2_example1">
              <ul class ="goal-template">
                <li><a class ="template-value">Lose weight</a></li>
                <li><a class ="template-value">Drop a size </a></li>
                <li><a class ="template-value">Eat healthier </a></li>
                <li><a class="template-value">Improve hydration</a></li>
                <li><a class="template-value">Improve sleep</a></li>
                <li><a class="template-value">Limit stress</a></li>
                <li><a class="template-value">Improve career</a></li>
                <li><a class="template-value">Improve health</a></li>
                <li><a class="template-value">Injury recovery</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
