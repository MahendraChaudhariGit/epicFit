@extends('Result.masters.app')

@section('required-styles')
    {!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('result/css/bootstrap-timepicker.min.css') !!}
@stop

@section('page-title')
   Calendar settings 
@stop()

@section('content')
    {!! Form::open(['url' => '', 'role' => 'form', 'id' =>'caledarSettingForm','class'=>'container-fluid container-fullw bg-white scrollToTop']) !!}
    {!! Form::hidden('caledarSettingId', $allcaledarVal->id , ['class' => 'no-clear']) !!}
    {!! Form::hidden('clientCaledarSettingId', $client_id , ['class' => 'no-clear']) !!}
        {!! displayAlert('', true)!!}
       <div class="row">
          <!-- left area fieldset-->
          <div class="col-md-6">
            <fieldset class="padding-15 ">
              <legend>
                Display settings 
              </legend>
              <div class="form-group">
                {!! Form::label('firstDay', 'First day of the week:', ['class' => 'strong']) !!}
                {!! Form::select('firstDay', ['0' => 'Sunday', '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday','6'=>'Saturday'],isset($allcaledarVal)?$allcaledarVal->cs_first_day:null, ['class' => 'form-control','required' => 'required']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('startTime', 'Calendar start time:', ['class' => 'strong']) !!}
                <div class="input-group col-xs-6">
                  <input type="text" name="startTime" class="form-control input-sm no-clear" <?php if(isset($allcaledarVal) && $allcaledarVal->cs_use_current==1)echo "disabled=true";?> value="<?php if(isset($allcaledarVal)&& isset($allcaledarVal->cs_start_time))echo $allcaledarVal->cs_start_time; else echo "12:00 AM";?>" readonly><!-- -->
                  <span class="input-group-addon"><a href="javascript:void(0)" style="color:white" class="<?php if(isset($allcaledarVal) && $allcaledarVal->cs_use_current==0)echo "newTimePicker";?> timeLink"><span class="glyphicon glyphicon-time"></span></a></span>
                </div>
                <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                  <input type="checkbox" id="useCurrentTime" class="selAllDd" name="useCurrentTime" <?php if(isset($allcaledarVal) && $allcaledarVal->cs_use_current==1)echo "checked=checked";?> >
                  <label for="useCurrentTime" class="no-error-label">
                    <strong>Use current time</strong>
                  </label>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('CalendarInterval', 'Calendar intervals:', ['class' => 'strong']) !!}
                {!! Form::select('calendarInterval', ['5' => '5 minutes', '10' => '10 minutes', '15' => '15 minutes', '20' => '20 minutes', '30' => '30 minutes','45'=>'45 minutes'],isset($allcaledarVal)?$allcaledarVal->cs_intervals:null, ['class' => 'form-control','required' => 'required']) !!}
              </div>
              <div class="form-group">
                {!! Form::label('view', ' Default View:', ['class' => 'strong']) !!}
                {!! Form::select('view',['monthly'=>'Monthly','weekly'=>'Weekly','daily'=>'Daily'] ,isset($allcaledarVal)?$allcaledarVal->cs_view:null, ['class' => 'form-control','required' => 'required']) !!}
              </div>             
              <div class="form-group"> 
                <div class="checkbox clip-check check-primary m-b-0">
                  <input name="disp_calendar" value="1" type="checkbox" id="disp_calendar_high-contrast" <?php if(isset($allcaledarVal) && $allcaledarVal->cs_display_calendar==1){echo "checked";}?> >
                  <label for="disp_calendar_high-contrast">
                    <strong>Display the calendar in high contrast mode </strong> 
                  </label>
                </div>
              </div>
            </fieldset>
            <!-- Appointment settings fieldset-->
            <fieldset class="padding-15 hidden">
              <legend>
                  Appointment settings
              </legend>
              <div class="form-group">
                {!! Form::label('initial_status', 'Initial status for new appointments: :', ['class' => 'strong']) !!}
                {!! Form::select('initial_status', ['Confirmed' => 'Confirmed', 'Pencilled-In' => 'Pencilled-in'],isset($allcaledarVal)?$allcaledarVal->cs_initial_status:null, ['class' => 'form-control','required' => 'required']) !!}
              </div>             
              <div class="form-group">
                <div class="checkbox clip-check check-primary m-b-0">
                  <input name="add_company_name" value="1" type="checkbox" id="add_company_name_field" <?php if(isset($allcaledarVal) && $allcaledarVal->cs_add_company_name==1){echo "checked";}?> >
                  <label for="add_company_name_field">
                      <strong>Add company name field for customers </strong> 
                  </label>  
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox clip-check check-primary m-b-0">
                  <input name="allow_appointments" value="1" type="checkbox" id="allow_appointments_to_deleted" <?php if(isset($allcaledarVal) && $allcaledarVal->cs_allow_appointments==1){echo "checked";}?> >
                  <label for="allow_appointments_to_deleted">
                      <strong>Allow appointments to be deleted </strong> 
                  </label>
                </div>
              </div>
            </fieldset>
          </div>
           <!-- right field set....-->
          <div class="col-md-6">
                <!--Daily appointment summary fieldset-->
                     <fieldset class="padding-15 hidden">
                            <legend>
                                Daily appointment summary
                            </legend>
                             <div class="form-group">
                               <div class="checkbox clip-check check-primary m-b-0">
                                <input name="receive_email_summary" value="1" type="checkbox" id="email_subscribe_for_appointement" <?php if(isset($allcaledarVal) && $allcaledarVal->cs_receive_email_summary==1){echo "checked";}?>>
                                <label for="email_subscribe_for_appointement">
                                    <strong>Receive an email summary of all appointments for the day </strong> 
                                </label>  
                                </div>
                            </div>
                    </fieldset>      
          </div>              
       </div> 
       <!-- submit button-->
        <div class="row">
              <div class="col-sm-12">
                  <div class="form-group text-right">
                      <button class="btn btn-primary btn-wide btn-add-more-form submitcalendar">
                        <i class="fa fa-edit"></i> Update Settings
                            </button>
                           <!-- ajax loader
                            <div id="loading">
                            </div>
                            -->
                        </div>
              </div>
          </div>

    {!! Form::close() !!}
@stop

@section('custom-script')
    
    {!! Html::script('result/plugins/jquery/jquery-ui.min.js') !!} 

    {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}

    {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}

    {!! Html::script('result/plugins/moment/moment.min.js') !!}
    <!-- {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!} -->
    <!-- {!! Html::script('assets/js/set-moment-timezone.js') !!}  -->

    {!! Html::script('result/js/helper.js?v='.time()) !!}

    {!! Html::script('result/js/bootstrap-timepicker.js?v='.time()) !!}

    <script>
      var rowCreateReason = <?php echo $i = 0;?>;
    </script>

    {!! Html::script('result/js/calendar-setting.js?v='.time()) !!}
    


@stop