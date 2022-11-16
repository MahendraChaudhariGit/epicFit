       <form class="smart-wizard">
        <div id="wizard1" class="swMain parqForm">
          <ul>
            <li>
              <a href="#step1">
                <div class="stepNumber">
                  1
                </div>
                <span class="stepDesc"><small>Goal</small></span>
              </a>
            </li>
            <li>
              <a href="#step2">
                <div class="stepNumber">
                  2
                </div>
                <span class="stepDesc"><small>Sleep form</small></span>
              </a>
            </li>
            <li>
              <a href="#step3">
                <div class="stepNumber">
                  3
                </div>
                <span class="stepDesc"><small>Chronotype Survey</small></span>
              </a>
            </li>
            <li>
              <a href="#step4">
                <div class="stepNumber">
                  4
                </div>
                <span class="stepDesc"><small>Gallery</small></span>
              </a>
            </li>
            
          </ul>
          <!-- start: WIZARD STEP 1 -->
          <div id="step1">
          <div class="row">
             <div class="col-md-12">
              <span class="icon-group-right">
              
             <a class="btn btn-xs pull-right" href="#" id="showcalendardiv"> <i class="fa fa-calendar-o"></i></a>
             <a class="btn btn-xs pull-right" href="#" id="clientGoalList"> <i class="fa fa-list"></i> </a> 
             <a class="btn btn-xs pull-right" href="#" id="clientUser"> <i class="fa fa-user"></i> </a>
           </span>
           </div>
          </div>

           <!-- start: goal buddy calendar panal -->
           <div class="row " id="showcalendar">
            <div class="col-md-12"> 
              {!! Form::select('type', ['all'=>'All', 'goal' => 'Goal', 'habit' => 'Habit', 'task' => 'Task'], null,  ['class' => 'form-control goalbuddy-event-dd', 'id' => "eventTypeDD"]) !!}
              {!! Form::hidden('current-client-id', isset($clients)?$clients->id:'') !!}
              <div id='full-calendar'></div>
            </div>
          </div>    
          <!-- End: goal buddy calendar panal -->

          <!-- start: goal buddy create/edit panal -->
          <div class="row clientGoalListCls hidden" >
            @if(isset($goalListData) && $goalListData->count() > 0) 
            @include('goal-buddy.clientgoallisting', ['goalListData' => $goalListData])
            @endif 
            @include('goal-buddy.createClientGoalBuddy', ['goalListData' => $goalListData])   
          </div>
          <!-- end: goal buddy create/edit panal -->

          <!-- start: goal buddy create/edit panal -->
          <div class="row clientshowListCls hidden" > 
            @include('goal-buddy.goalbuddy_client', ['allClientArray' => $allClientArray,'countries' => $countries]) 
          </div>
          <!-- end: goal buddy create/edit panal -->
        </div>
        <div id="step2">
          <div class="row review-mode1">
            @include('preview-sleep')
          </div>
          <div class="row editable-mode1" style="display: none">
              @include('edit-sleep')
          </div>
       </div>
       <div id="step3">
        <div class="row review-mode2">
          @include('preview-Survey')
        </div>
        <div class="row editable-mode2" style="display: none">
            @include('edit-Survey')
        </div>
     </div>
       <div id="step4">
        <div id="GalleryBeforeAfter">
          @if(count($progress_image)>0)
          <ul class="gallerylist">
              
          @foreach($progress_image as $value)
              <li>
          <div class="galleyIMG">
          <h3>{{ucfirst($value->title)}}</h3>
           <div class="show-gallery-img" data-id="{{$value->id}}" data-item="{{$value->image}}" style="background-image: url({{asset('result/final-progress-photos')}}/{{$value->image}});">
                 
             </div>
             <div class="date"> {{date('d-m-Y',strtotime($value->date))}}</div>
          <div class="pose">   {{ucfirst($value->pose_type)}}</div>
            
          <h3>{{ucfirst($value->image_type)}}</h3>
          </div>
          </li>
           
          @endforeach
          
          </ul>
          @else
          <center><h3>No Image found</h3></center>
          @endif
          </div>
      </div>
      
    </div>
  </form>


  <!-- Start: modal section -->
  @include('goal-buddy.habitmodel')    
  @include('goal-buddy.taskmodel')    
  @include('goal-buddy.goalmodel')
  <!-- End: modal section -->
