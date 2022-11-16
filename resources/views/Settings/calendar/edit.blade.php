@extends('layouts.app')

@section('required-styles-for-this-page')
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
    {!! Html::style('assets/plugins/nestable-cliptwo/jquery.nestable.css') !!}
@stop

@section('page-title')
   Calendar settings 
@stop()

@section('content')
    {!! Form::open(['url' => '', 'role' => 'form', 'id' =>'caledarSettingForm','class'=>'container-fluid container-fullw bg-white scrollToTop']) !!}
    {!! Form::hidden('caledarSettingId', $allcaledarVal->id , ['class' => 'no-clear']) !!}
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
                              
                            <div class="input-group bootstrap-timepicker timepicker col-xs-6">
                                <input type="text" name="startTime" class="form-control input-sm no-clear timepicker1" data-default-time="9:00 AM" <?php if(isset($allcaledarVal) && $allcaledarVal->cs_use_current==1)echo "disabled=true";?> data-timevalue="<?php if(isset($allcaledarVal))echo $allcaledarVal->cs_start_time;?>"><!-- -->
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
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
                    <fieldset class="padding-15 ">
                            <legend>
                                Appointment settings
                            </legend>
                            <div class="form-group">
                                {!! Form::label('futExpi', 'Disable booking editing prior:', ['class' => 'strong']) !!}
                                {!! Form::select('futExpi', ['2' => '2 hours', '3' => '3 hours', '6' => '6 hours', '12' => '12 Hours', '24' => '24 hours', '48' => '48 Hours', '72' => '72 Hours', '168' => '1 week'], isset($allcaledarVal)?$allcaledarVal->cs_booking_active:24, ['class' => 'form-control onchange-set-neutral','required' => 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('redRate', 'Default reduced rate:', ['class' => 'strong']) !!}
                                 {!! Form::text('redRate', $allcaledarVal->cs_reduced_rate, ['class' => 'form-control price-field','required' => 'required']) !!}
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
                  <!-- Start: Appointment status -->
                    <fieldset class="padding-15 ">
                      <legend>
                        Appointment status
                      </legend>
                      <div class="form-group">
                          {!! Form::label('initial_status', 'Initial status for new appointments:', ['class' => 'strong']) !!}
                         
                          {!! Form::select('initial_status', ['Confirmed' => 'Confirmed', 'Pencilled-In' => 'Pencilled-in'],isset($allcaledarVal)?$allcaledarVal->cs_initial_status:null, ['class' => 'form-control','required' => 'required']) !!}
                      </div>
                      <div class="form-group">
                          {!! Form::label('initial_status_consultation', 'Initial status for consultation:', ['class' => 'strong']) !!}
                         
                          {!! Form::select('initial_status_consultation', ['Confirmed' => 'Confirmed', 'Pencilled-In' => 'Pencilled-in'],isset($allcaledarVal)?$allcaledarVal->cs_initial_status_consultation:null, ['class' => 'form-control','required' => 'required']) !!}
                      </div>
                      <div class="form-group">
                          {!! Form::label('initial_status_benchmarking', 'Initial status for new benchmarking:', ['class' => 'strong']) !!}
                         
                          {!! Form::select('initial_status_benchmarking', ['Confirmed' => 'Confirmed', 'Pencilled-In' => 'Pencilled-in'],isset($allcaledarVal)?$allcaledarVal->cs_initial_status_benchmarking:null, ['class' => 'form-control','required' => 'required']) !!}
                      </div>
                    </fieldset>
                  <!-- End: Appointment status   -->
                </div>
           <!-- right field set....-->
           <div class="col-md-6">
               <fieldset class="padding-15 ">
                            <legend>
                                Cancellation reasons
                            </legend>
                            <div class="row m-b-5" id="sucId">
                            <div class="sucMes col-md-12"></div>
                                <div class="col-md-11 col-xs-11">
                                    <strong>Reason</strong>
                                </div>
                                <div class="col-md-1 col-xs-1 p-l-0">
                                    <a class="btn btn-xs btn-primary" href="#" id="reason-row-add"><i class=" fa fa-plus fa fa-white"></i></a>
                                </div>
                            </div>
                             <!-- Start: HIDDEN REASION FOR ONLY CLONE WHEN REASION IS NULL -->
                            <div class="row reason-row hidden">
                               <div class="col-md-11 col-xs-11">
                                  <div class="form-group">
                                      {!! Form::text('',null, ['class' => 'form-control']) !!}
                                  </div>
                               </div>
                               <div class="col-md-1 col-xs-1 p-l-0">
                                <a class="btn btn-xs btn-red remove-reason-row" href="#" '=""><i class="fa fa-times fa fa-white"></i></a>
                               </div>
                            </div>
                            <!-- End:HIDDEN REASION -->
                            <?php $i=0; ?>
                            @foreach($allcaledarVal->reason as $reasons)
                            <div class="row rowCount">
                               <div class="col-md-11 col-xs-11">
                                  <div class="form-group">
                                        {!! Form::text('reason'.$i,$reasons->cscr_reason, ['class' => 'form-control','required'=>'required']) !!}
                                        <input type="hidden" name="resId<?php echo $i;?>" value="<?php echo $reasons->id;?>">
                                  </div>
                               </div>
                               <div class="col-md-1 col-xs-1 p-l-0">
                                <a class="btn btn-xs btn-red remove-reason-row" href="#" '=""><i class="fa fa-times fa fa-white"></i></a>
                               </div>
                            </div>
                            <?php $i++;?>
                            @endforeach
                                    
                </fieldset>
                <div class="sales-process-div">
                  <div class="col-md-6">
                    <fieldset class="padding-15">
                      <legend>Sales Process Settings</legend>
                      <input type="hidden" name="salesProccesSettingsData" value="{{isset($allcaledarVal)?$allcaledarVal->sales_process_settings:''}}">
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="checkbox clip-check check-primary m-b-0">
                            <input id="saleStep4" type="checkbox" name="saleStepGen[]" value="4" class="sales_checkbox">
                            <label for="saleStep4">Book Benchmark</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="checkbox clip-check check-primary m-b-0">
                            <input id="saleStep5" type="checkbox" name="saleStepGen[]" value="5" class="sales_checkbox">
                            <label for="saleStep5">Benchmark</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="checkbox clip-check check-primary m-b-0">
                            <input id="saleStepBookTeam" type="checkbox" name="saleStepSession[]" value="bookTeam" class="sales_checkbox">
                            <label for="saleStepBookTeam">Book Team</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="checkbox clip-check check-primary m-b-0">
                            <input id="saleStepBookIndiv" type="checkbox" name="saleStepSession[]" value="bookIndiv" class="sales_checkbox">
                            <label for="saleStepBookIndiv">Book Individual</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="checkbox clip-check check-primary m-b-0">
                            <input id="saleStep18" type="checkbox" name="saleStepGen[]" value="18" class="sales_checkbox">
                            <label for="saleStep18">Email pricing</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <fieldset class="padding-15" id="sessionOrder" style="display: block;">
                      <legend>Order</legend>
                      <div class="form-group">
                        <label class="strong" for="referrer">Drag the following session steps relating to your specific needs</label>
                        <div class="dd" id="salesNestable">
                          <ol class="dd-list">
                            @php
                            $datas = json_decode($allcaledarVal->sales_process_settings,1);
                            $orders = [];
                            @endphp
                            @if(count($datas['order']))
                                @foreach($datas['order'] as $key => $value)
                                    @php
                                    $orders[] = $value['id'];
                                    @endphp
                                @endforeach
                            @endif
                            @foreach($orders as $order)
                            <?php 
                            $value = explode('-', $order); 
                            if($value[0] == 'team')
                              $stepName = 'Team';
                            else if($value[0] == 'indiv')
                              $stepName = 'Individual';
                            ?>
                            <li class="dd-item {{$value[0]}}" data-id="{{$value[0]}}-{{$value[1]}}">
                              <div class="dd-handle">Book {{$stepName}}</div>
                            </li>
                            @endforeach
                          </ol>
                        </div>
                        <input type="hidden" name="salesNestable">
                      </div>    
                    </fieldset>
                  </div> 
                  <div class="col-md-6">
                    <fieldset class="padding-15" id="bookTeam">
                      <legend>Team</legend>
                      <div class="form-group clearfix">
                        <label for="teamNumb" class="strong">Number</label>
                        <select class="form-control onchange-set-neutral" name="teamNumb" tabindex="-98">
                          <option value=""> -- Select -- </option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                        </select>
                      </div>
                      <div class="form-group team-checkbox-field">
                        <div class="checkbox clip-check check-primary m-b-0">
                          <input id="saleStep11" type="checkbox" name="saleStepGen[]" value="11" class="sales_checkbox team ">
                          <label for="saleStep11">T.E.A.M</label>
                        </div>
                      </div>
                    </fieldset>
                    <fieldset class="padding-15" id="bookIndiv" style="display: block;">
                      <legend>Individual</legend>
                      <div class="form-group clearfix">
                        <label for="indivNumb" class="strong">Number</label>
                        <select class="form-control onchange-set-neutral" name="indivNumb" tabindex="-98">
                          <option value=""> -- Select -- </option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                        </select>
                      </div>
                      <div class="form-group indiv-checkbox-field">
                        <div class="checkbox clip-check check-primary m-b-0">
                          <input id="saleStep17" type="checkbox" name="saleStepGen[]" value="17" class="sales_checkbox indiv ">
                          <label for="saleStep17">Individual Completed</label>
                        </div>
                      </div>
                    </fieldset>
                    {{-- <fieldset class="padding-15" id="bookIndiv">
                      <legend>Consultation</legend>
                      <div class="form-group clearfix">
                        <div class="form-group">
                          <label for="consultExpDate" class="strong">Expiration Date *</label>
                          <input class="form-control onchange-set-neutral hasDatepicker" required="" autocomplete="off" readonly="" name="consultExpDate" type="text" value="Sun, 12 Jan 2020 " id="consultExpDate">
                          <span class="help-block"></span>
                        </div>
                      </div>
                    </fieldset> --}}
                  </div>
                </div>
                <!--Daily appointment summary fieldset-->
                <div class="col-md-12">
                   <fieldset class="padding-15 ">
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
             <!-- Appointment statuses fieldset-->
             <!--
                <fieldset class="padding-15 ">
                    <legend>
                        Appointment statuses&nbsp;
                        <a class="btn btn-xs btn-primary " href="#" id="status-row-add"><i class=" fa fa-plus fa fa-white"></i></a> 
                    </legend>
                         <div class="row m-b-5">
                            <div class="col-md-11">
                                <strong>Status</strong>
                            </div>
                            <div class="col-md-1 p-l-0">
                               &nbsp;
                            </div>
                             
                         </div>
                        <div class="row status-row">
                               <div class="col-md-11">
                                  <div class="form-group">
                                        {!! Form::text('status0','Not started', ['class' => 'form-control','disabled'=>'disabled']) !!}
                                  </div>
                               </div>
                               <div>
                                <a class="font-14 pull-left" data-content="This is an internal status used by the system and can't be deleted or modified" data-placement="left" data-toggle="popover" data-trigger="hover" data-title="<strong>Editing locked</strong>" data-html="true">
                                        <i class="fa ti-lock" href="#"></i>
                                    </a>
                               </div>
                            </div>
                            <div class="row">
                               <div class="col-md-11">
                                  <div class="form-group">
                                        {!! Form::text('status1','Arrived', ['class' => 'form-control','disabled'=>'disabled']) !!}
                                  </div>
                               </div>
                               <div>
                                <a class="font-14 pull-left" data-content="This is an internal status used by the system and can't be deleted or modified" data-placement="left" data-toggle="popover" data-trigger="hover" data-title="<strong>Editing locked</strong>" data-html="true">
                                        <i class="fa ti-lock" href="#"></i>
                                    </a>
                               </div>
                            </div>
                            <div class="row">
                               <div class="col-md-11">
                                  <div class="form-group">
                                        {!! Form::text('status2','Started', ['class' => 'form-control','disabled'=>'disabled']) !!}
                                  </div>
                               </div>
                               <div>
                                <a class="font-14 pull-left" data-content="This is an internal status used by the system and can't be deleted or modified" data-placement="left" data-toggle="popover" data-trigger="hover" data-title="<strong>Editing locked</strong>" data-html="true">
                                        <i class="fa ti-lock" href="#"></i>
                                    </a>
                               </div>
                            </div>
                            <div class="row">
                               <div class="col-md-11">
                                  <div class="form-group">
                                        {!! Form::text('status3','Completed', ['class' => 'form-control','disabled'=>'disabled']) !!}
                                  </div>
                               </div>
                               <div>
                                <a class="font-14 pull-left" data-content="This is an internal status used by the system and can't be deleted or modified" data-placement="left" data-toggle="popover" data-trigger="hover" data-title="<strong>Editing locked</strong>" data-html="true">
                                        <i class="fa ti-lock" href="#"></i>
                                    </a>
                               </div>
                            </div>
                            <div class="row">
                               <div class="col-md-11">
                                  <div class="form-group">
                                        {!! Form::text('status4','Did not show', ['class' => 'form-control','disabled'=>'disabled']) !!}
                                  </div>
                               </div>
                               <div>
                                <a class="font-14 pull-left" data-content="This is an internal status used by the system and can't be deleted or modified" data-placement="left" data-toggle="popover" data-trigger="hover" data-title="<strong>Editing locked</strong>" data-html="true">
                                        <i class="fa ti-lock" href="#"></i>
                                    </a>
                               </div>
                            </div>
                </fieldset> 
                -->
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

@section('required-script-for-this-page')
    
    {!! Html::script('assets/js/jquery-ui.min.js') !!} 

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}

    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js') !!}  -->

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}

    <script>
      var rowCreateReason=<?php echo $i;?>;
    </script>

    {!! Html::script('assets/js/calendar-setting.js?v='.time()) !!}
    {!! Html::script('assets/plugins/nestable-cliptwo/jquery.nestable.js') !!}
    {!! Html::script('assets/plugins/nestable-cliptwo/nestable.js') !!}
@stop