@extends('Result.masters.app')

@section('required-styles') 
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    {!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
    {!! Html::style('result/css/custom.css?v='.time()) !!}
    {!! Html::style('result/css/owl.carousel.css?v='.time()) !!}
    {{-- <!-- Start: NEW datetimepicker css -->
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
    <!-- End: NEW datetimepicker css --> --}}
    <style type="text/css">
@media(max-width: 767px){
#ui-datepicker-div{
    left: 5px !important;
    width: 97% !important;
}
.ui-datepicker-multi-2 .ui-datepicker-group {
    width: 49%;
    font-size: 11px;
}
.dropdown-menu.dropdown-light{
    min-width: 91px;

}
.dropdown-menu.dropdown-light li a{
    font-size: 13px;
    padding: 3px 0px;
}
.fc-toolbar .fc-left{
       order: initial;
}
.fc-toolbar .fc-right{
    order: initial;
    height: 15px;
}
.fc-button-group{
    float: right !important;
    width: auto;
}
.fc-left .fc-button{
    float: left !important;
}
.fc-left .btn.fc-button{
     height: 41px !important;
}
.fc-button-group{
    bottom: 41px !important;
}
.fc-center>div{
    width: 100%;
    margin: 0;
}
.col-md-12.fc{
    padding: 0px;
}
.fc-center{
    margin-bottom: 10px !important;
}

}
/*.fc-day-grid-event{
        width: 75%;
    margin-left: auto;
    margin-right: auto;
}*/
.custom-calendar.fixed{
width: 100% !important;
padding-right: 0px !important;
position: sticky !important;

}
.fc-day-grid-container{
 overflow: hidden !important;   
}
.fc-widget-content{
    padding-bottom: 16px;
}
#calendar .open-modalp1{
    bottom: 0px !important;
}
</style>
@stop

@section('page-title')
<span >Meal Calendar</span> 
@stop

@section('content')
@include('includes.partials.pic_crop_model')

@include('includes.partials.waiting_shield')

<!-- Start: Calender -->
<div class="row">
    <div class="col-md-12">
        <!-- start: Calendar Jumper -->
        <div class="btn-group calJumper">
            <a class="btn btn-primary btn-o dropdown-toggle hidden" data-toggle="dropdown" href="#">
                <i class="fa fa-angle-double-left"></i>
            </a>
            <ul role="menu" class="dropdown-menu dropdown-light">
                <li>
                    <a href="#" data-jump-amount="1" data-jump-unit="weeks">1 week</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="2" data-jump-unit="weeks">2 weeks</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="3" data-jump-unit="weeks">3 weeks</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="4" data-jump-unit="weeks">4 weeks</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="5" data-jump-unit="weeks">5 weeks</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="6" data-jump-unit="weeks">6 weeks</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="7" data-jump-unit="weeks">7 weeks</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="8" data-jump-unit="weeks">8 weeks</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="6" data-jump-unit="months">6 months</a>
                </li>
                <li>
                    <a href="#" data-jump-amount="1" data-jump-unit="years">1 year</a>
                </li>
            </ul>
        </div>
        <!-- end: Calendar Jumper -->

        <!-- Start: Calender body -->
        <?php unset($mealsCategory[""]); ?>
        <input type="hidden" name="category-data" value='{{ json_encode($mealsCategory) }}'>
        <input type="hidden" name="calendarSettingInput" value='{{ json_encode($calendar_settings) }}'>
        <div id='calendar' class="col-md-12"></div>

        @php
        $timestamp = \Carbon\CarbonImmutable::now();;
        @endphp
        <!-- New Day calendar -->
        <div id="dayCalender" class="day-calender" style="display: none">
            <div class="open-modalp1 mealDayModal" type="button" data-toggle="modal" data-date="" data-target="#myModal1">Add Daily log</div>       
            <table class="calender-table">
                <tbody>
                    <tr>
                        <td>
                            <!-- Meal Type start -->
                            <table border="1" class="mealtype-table">
                                <tr>
                                    <td>
                                        <h5 class="breakfastData dayMeal" data-event-date="">BREAKFAST 
                                            <a class="btn btn-primary addBtnCat t-right mtype" data-meal-category="Breakfast" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Breakfast']:''}}">
                                                Add Meal <i class="fa fa-plus"></i>
                                            </a>
                                            
                                            <div class="mealDeatil"></div>  
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="morningSnackData dayMeal" data-event-date="">SNACK
                                            <a class="btn btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Snack']:''}}" data-snack-type="1">
                                                Add Meal <i class="fa fa-plus"></i>
                                            </a>
                                            <div class="mealDeatil"></div>  
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="lunchData dayMeal" data-event-date="">LUNCH
                                            <a class="btn btn-primary addBtnCat t-right mtype" data-meal-category="Lunch" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Lunch']:''}}">
                                                Add Meal <i class="fa fa-plus"></i>
                                            </a>
                                            <div class="mealDeatil"></div>  
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="eveningSnackData dayMeal" data-event-date="">SNACK
                                            <a class="btn btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Snack']:''}}" data-snack-type="2">
                                                Add Meal <i class="fa fa-plus"></i>
                                            </a>
                                            <div class="mealDeatil"></div> 
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="dinnerData dayMeal" data-event-date="">DINNER
                                            <a class="btn btn-primary addBtnCat t-right mtype" data-meal-category="Dinner" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Dinner']:''}}">
                                                Add Meal <i class="fa fa-plus"></i>
                                            </a>
                                            <div class="mealDeatil"></div> 
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="nightSnackData dayMeal" data-event-date="">SNACK 
                                            <a class="btn btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Snack']:''}}" data-snack-type="3">
                                                Add Meal <i class="fa fa-plus"></i>
                                            </a>
                                            <div class="mealDeatil"></div>  
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="dayMeal" data-event-date="">
                                            <a class="btn btn-primary" id="mealCustomBtn" href="#" data-toggle="modal" data-target="#customMealplanmodal" style="margin-top: 10px;">Add Custom
                                              <i class="fa fa-plus"></i>
                                            </a> 
                                        </h5>
                                    </td>
                                </tr>
                            </table>
                            <!-- Meal Type end --> 
                        </td>
                    </tr>
                </tbody>
            </table>
            
        </div>

        <!-- New Week Calendar -->
        <div id="weekCalender" class="week-calender" style="display: none">
            <table class="calender-table">
                <thead class="custom-header">
                    <tr>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @for($i=0;$i<=6;$i++)
                        <td>
                            <!-- Meal Type start -->
                            <table border="1" class="mealtype-table">
                                <tr>
                                    <td>
                                        <h5 class="breakfastData weekMeal" data-event-date="">BREAKFAST 
                                            <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Breakfast" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Breakfast']:''}}">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            <div class="mealDeatil"></div> 
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="morningSnackData weekMeal" data-event-date="">SNACK
                                            <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Snack']:''}}" data-snack-type="1">
                                                <i class="fa fa-plus"></i>
                                            </a> 
                                            <div class="mealDeatil"></div>
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="lunchData weekMeal" data-event-date="">LUNCH
                                            <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Lunch" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Lunch']:''}}">
                                                <i class="fa fa-plus"></i>
                                            </a> 
                                            <div class="mealDeatil"></div>
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="eveningSnackData weekMeal" data-event-date="">SNACK
                                            <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Snack']:''}}" data-snack-type="2">
                                                <i class="fa fa-plus"></i>
                                            </a> 
                                            <div class="mealDeatil"></div>
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="dinnerData weekMeal" data-event-date="">DINNER
                                            <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Dinner" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Dinner']:''}}">
                                                <i class="fa fa-plus"></i>
                                            </a> 
                                            <div class="mealDeatil"></div>
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="nightSnackData weekMeal" data-event-date="">SNACK
                                            <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($mealsCategoryArr)?$mealsCategoryArr['Snack']:''}}" data-snack-type="3">
                                                <i class="fa fa-plus"></i>
                                            </a> 
                                            <div class="mealDeatil"></div>
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="weekMeal" data-event-date="">
                                            <a class="btn btn-primary" id="mealCustomBtn" href="#" data-toggle="modal" data-target="#customMealplanmodal" style="margin-top: 10px;">Add Custom
                                              <i class="fa fa-plus"></i>
                                            </a> 
                                        </h5>
                                    </td>
                                </tr>
                            </table>
                            <!-- Meal Type end -->
                            
                        </td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Start: Calender body -->
    </div>
</div>
<!-- End: Calender -->

<!-- Start:all modal includes here -->
@include('includes.partials.meal-plan-modal',['mealsCategory'=>$mealsCategory,'catType' => $mealsCategoryArr])
<!-- End:all modal includes here -->
<!-- Start: Daily Log Modal -->
{{-- @include('Result.partials.dailyLogModal',['catType' => $mealsCategoryArr]) --}}
<!-- End: Daily Log Modal -->
@endsection

@section('script')
{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('assets/js/validator-helper.js') !!}
{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}
{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
<!-- Start:  NEW datetimepicker js -->
{{-- {!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!} --}}
<!-- End: NEW datetimepicker js --> 
{!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}
{!! Html::script('result/js/owl.carousel.js') !!}
{{-- {!! Html::script('result/js/webcam.js') !!} --}}
{!! Html::script('assets/js/helper.js') !!}
{!! Html::script('assets/js/meal-plan-calendar.js') !!}
{!! Html::script('assets/js/meal-plan-event.js') !!}
{{-- {!! Html::script('result/js/daily-log.js') !!} --}}
<script>
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
<script>
    popoverContainer = $('#calendar');
</script>
<script type="text/javascript">
  function monthSection(){
      $('#weekCalender').hide();
      $('#dayCalender').hide();
      $('#calendar').show();
  }
  $(document).ready(function() {
    // var referrer =  document.referrer;
    // var result = referrer.split('/');
    // var Param = result[result.length - 3];
    // var Param1 = result[result.length - 2];
    // if ((Param == 'meal-planner') && (Param1 == 'recipe-details')) {
    //     $('.shopping-list-btn').trigger('click');
    // }

  });
</script>
<script>
  $('document').ready(function(){
    if($('#calendar').find('.fc-agendaDay-button').hasClass('fc-state-active')){
      $('#calendar').find('.fc-agendaDay-button').trigger('click');
    }
  })
</script>
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
     $('.fc-toolbar').append('<table class="alt-header" style="display:none;"><thead class="fc-head" style="margin-right: 18px;"><tr><td class="fc-head-container fc-widget-header"><div class="fc-row fc-widget-header"><table><thead><tr><th class="fc-day-header fc-widget-header fc-mon">Mon</th><th class="fc-day-header fc-widget-header fc-tue">Tue</th><th class="fc-day-header fc-widget-header fc-wed">Wed</th><th class="fc-day-header fc-widget-header fc-thu">Thu</th><th class="fc-day-header fc-widget-header fc-fri">Fri</th><th class="fc-day-header fc-widget-header fc-sat">Sat</th><th class="fc-day-header fc-widget-header fc-sun">Sun</th></tr></thead></table></div></td></tr></thead></table>');
   })
  
  </script>

@stop