@extends('Result.masters.app')



@section('required-styles')
<!-- start: Bootstrap datepicker --> 
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}

{!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!} 

<!-- end: Bootstrap datepicker --> 
{!! Html::style('result/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}

{!! Html::style('result/css/goal-buddy.css?v='.time()) !!}
{!! Html::style('result/css/custom.css?v='.time()) !!}

@stop

@section('page-title')
<span>Calendar</span>
@stop

@section('content') 
   <!-- Start: Waiting Shield   23-07-2021 -->

<!-- End: Waiting Shield    23-07-2021   -->

<!-- start: Delete Form -->
@include('includes.partials.delete_form')
<!-- end: Delete Form --> 
<!-- start: content row -->
<div class="row">
  <div class="col-md-12">
    <!-- start: CALENDAR PANEL -->
    <div class="panel panel-default"><!--id="epic-accordionn"-->
      <!-- start: PANEL HEADING -->

      <!-- start: PANEL HEADING -->
      <!-- start: PANEL BODY -->
      <div class="panel-body">

     

        <!-- start: CALENDAR HEADING ROW -->
        <div class="row">
          <div class="col-md-12">

            @if((isset($goals))&&(count($goals) > 0))
            <select id="goal_filter" name="goal_filter" class="form-control goalbuddy-goal-dd">
              <option value="0">All Goals</option>
              @foreach($goals as $goal)
              <option value="{{$goal->id}}">{{$goal->gb_goal_name}}</option>
              @endforeach
            </select>
            @endif

            {!! Form::select('type', ['' => 'All', 'goal' => 'Goal', 'milestone' => 'Milestone', 'habit' => 'Habit', 'task' => 'Task'], null,  ['class' => 'form-control goalbuddy-event-dd']) !!}

            <div id='full-calendar'></div>

          </div>
        </div>
        <!-- end: CALENDAR HEADING ROW -->



        <!--goal model-->
        @include('Result.goal-buddy.goalmodel')    
        <!--goal model-->
        <!--goal model-->
        @include('Result.goal-buddy.milestonemodel')    
        <!--goal model-->

      </div>
      <!-- start: PANEL BODY -->
    </div>
    <!-- start: CALENDAR PANEL -->
  </div>
</div>
<!--task model-->
@include('Result.goal-buddy.taskmodel')    
<!--task model-->
<!--habit model-->
@include('Result.goal-buddy.habitmodel')  
<!--habit model-->
<!-- MODAL FOR CALENDAR SELECT EVENTS -->

@stop

@section('required-script')
<!-- {!! Html::script('result/js/jquery-ui.min.js') !!} -->
<!-- start: Moment Library -->
{!! Html::script('result/plugins/moment/moment.min.js') !!}
<!-- end: Moment Library --> 
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('result/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
{!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}

{!! Html::script('result/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}

{!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!} 

{!! Html::script('result/js/helper.js?v='.time()) !!}
{!! Html::script('result/js/main-client.js?v='.time()) !!} 
{!! Html::script('result/js/goal-buddy-calendar.js?v='.time()) !!}
{!! Html::script('result/js/form-wizard-goal-buddy.js?v='.time()) !!} 
{!! Html::script('result/js/goal-buddy.js?v='.time()) !!}
<script>
  $('document').ready(function(){
    $('thead.fc-head').addClass('sticky-header');
  })
  $(window).scroll(function() {
    if ($(this).scrollTop() > 200){  
      $('.alt-header').show();
    }
    else{
      $('.alt-header').hide();
    }
  });

  $('document').ready(function(){
    // $('#waitingShield').removeClass('hidden');
    var html = $('.fc-view .fc-head').html();
    $('.fc-toolbar').append('<table class="alt-header" style="display:none;"><thead class="fc-head" style="margin-right: 29px;">'+html+'</thead></table>');
    // $(window).on('load', function(){
    //   setTimeout(function(){ 
    //       $('#waitingShield').addClass('hidden');
    //    }, 500);
    //     //wait for page load PLUS two seconds.
    //  });
 

  $('body').on('click','.fc-prev-button,.fc-next-button',function(){
    // $('#waitingShield').removeClass('hidden');
        var html = $('.fc-view .fc-head').html();
        // console.log(html);
        var head = $('.fc-toolbar').find('.fc-head');
        if(head.length > 0){ 
          head.empty();
          head.append(html);  
        }
  });

})

// Add remove loading class on body element based on Ajax request status
$(document).on({
    ajaxStart: function(){
      $('#waitingShield').removeClass('hidden');
    },
    ajaxStop: function(){ 
      $('#waitingShield').addClass('hidden');
    }    
});
</script>
@stop
