<style type="text/css">
    .nav-tabs.tab > li.active a, .nav-tabs.tab > li.active a:hover, .nav-tabs.tab > li.active a:focus{
        border-width:1px;
    }
    .tab{
        display: flex;
        margin-top: 15px;

    }
    @media(min-width: 768px)
    {
        .d-md-none{
            display: none;
        }
    }
</style><div id="appointments" class="tab-pane {{$activeTab == 'appointments'?'active' : ''}}">
    <div class="page-header">
        <h1>Appointments</h1>
    </div>
    <div>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-default" href="#" data-toggle="modal" data-target="#printmodal">
                    <i class="fa fa-print"></i> Print appointments
                </a>
            </div>
            <div class="col-md-6">
                <!-- @if(isUserType(['Admin']) && $makeUpCount)
                    <a class="btn btn-primary pull-right js-createMakeup" href="#">
                        {{ $makeUpCount }} Make up remaining
                    </a>
                @endif -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-md-none">
                <ul class="nav nav-tabs tab">
  <li class="active"><a data-toggle="tab" href="#past-appointments">Past appointments</a></li>
  <li><a data-toggle="tab" href="#future-appointments">Future appointments</a></li>
</ul>
 </div>

<!--   <div class="tab-pane fade in active">
    <h3>HOME</h3>
    <p>Some content.</p>
  </div>
  <div class="tab-pane fade">
    <h3>Menu 1</h3>
    <p>Some content in menu 1.</p>
  </div> -->

           
<div class="tab-content">
            <div id="past-appointments"  class="col-md-6 col-sm-6">
                <h3 class="m-y-20">
                    Past appointments
                    <span class="checkbox clip-check check-primary m-b-0 m-t-0 pull-right">
                        <input type="checkbox" name="pastCancel" id="pastCanc" value="1"  class="no-clear" >
                        <label for="pastCanc" class="m-r-0">Show Cancelled</label>
                    </span>
                </h3>
                @if(count($pastEvents))
                    @foreach($pastEvents as $pastEvent)
                        <?php $modelName = class_basename($pastEvent); ?>
                        @if($modelName == 'StaffEventSingleService')
                            {!! renderClientAppointment($pastEvent, 'past') !!}
                        @else
                            {!! renderClientEventClass($pastEvent, 'past') !!}
                        @endif
                    @endforeach
                @else
                    @if(isUserType(['Staff']))
                        You do not have any previous appointments.
                    @else
                        This client has no previous appointments.
                    @endif
                @endif                             
            </div>
            <div id="future-appointments" class="col-md-6 col-sm-6">
                <h3 class="m-y-20">
                    Future appointments
                    <span class="checkbox clip-check check-primary m-b-0 m-t-0 pull-right">
                        <input type="checkbox" name="futureCancel" id="futureCanc" value="1"  class="no-clear pull-right" >
                        <label for="futureCanc" class="m-r-0 strong">Show Cancelled</label>
                    </span>
                </h3>
                @if(count($futureEvents))
                    @foreach($futureEvents as $futureEvent)
                        <?php $modelName = class_basename($futureEvent); ?>
                        @if($modelName == 'StaffEventSingleService')
                            {!! renderClientAppointment($futureEvent, 'future') !!}
                        @else
                            {!! renderClientEventClass($futureEvent, 'future') !!}
                        @endif
                    @endforeach
                @else
                    @if(isUserType(['Staff']))
                        You do not have any future appointments.
                    @else
                        This client has no future appointments.
                    @endif
                @endif   
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Print model -->
<div class="modal fade" id="printmodal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title categorylabel" id="myModalLabel">Print Options</h4>
            </div>
            <div class="modal-body bg-white">
                {!! Form::open(['url' => '', 'role' => 'form', 'id'=>'printForm']) !!}
                <!--<input type="hidden" name="hiddenCategId" value="">-->
                <div class="row">
                    <div class="col-md-6">
                    <fieldset class="padding-15"><legend> Section 1 &nbsp;&nbsp;&nbsp;&nbsp;</legend>
                        <strong>The clients appointments will be printed as a day sheet report</strong>

                            <div class="form-group m-b-0 m-t-0">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" value="" id="checkbox1">
                                    <label for="checkbox1">Don't show notes entered about the client </label>
                                </div>
                            </div>
                            <div class="form-group m-b-0">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" value="" id="checkbox2">
                                    <label for="checkbox2">Add a space for staff to write in their own notes </label>
                                </div>
                            </div>
                            <div class="form-group m-b-0">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" value="" id="checkbox3">
                                    <label for="checkbox3">Print each appointment on its own page </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset class="padding-15"><legend> Section 2 &nbsp;&nbsp;&nbsp;&nbsp;</legend>
                            <div class="form-group">
                                {!! Form::label('appointmentStatus', 'Appointments Status *', ['class' => 'strong']) !!}
                                {!! Form::select('appointmentStatus',array('-- Select --','All statuses','Booked','Attended','Did not show','Pencilled-in'), null, ['class' => 'form-control onchange-set-neutral','required' => 'required', 'id'=>'appointmentstatusid']) !!}
                            </div>
                            <!--<div class="form-group">
                                {!! Form::label('printFormat', 'Print Format *', ['class' => 'strong']) !!}
                                {!! Form::select('printFormat', array(' Select ','Pdf','Excel') , null, ['class' => 'form-control onchange-set-neutral','required' => 'required', 'id'=>'printformatid']) !!}
                            </div>-->
                            <div class="form-group">
                                {!! Form::label('dateFrom', 'Date From *', ['class' => 'strong']) !!}
                                {!! Form::text('dateFrom', null, ['class' => 'form-control  onchange-set-neutral', 'autocomplete' => 'off', 'required', 'id'=> 'dateFrom']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('dateTo', 'Date To *', ['class' => 'strong']) !!}
                                {!! Form::text('dateTo', null, ['class' => 'form-control  onchange-set-neutral', 'autocomplete' => 'off', 'required', 'id'=> 'dateTo']) !!}
                            </div>  
                        </fieldset>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Cancel
                </button>
                <!--<button type="button" class="btn btn-primary" id ="printbtn" ><i class="fa fa-print"></i> Print
                </button>-->
                 {!! Form::submit('Print', ['class' => 'btn btn-primary' , 'id' => 'printbtn']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
    <script type="text/javascript">
$(window).resize(function () {
    var widthWindow = $(window).width();
    if (widthWindow <= '768') {
        $('#past-appointments').addClass('tab-pane in active');
         $('#future-appointments').addClass('tab-pane fade');
    }
    else
    {
        $('#future-appointments').removeClass('tab-pane fade');
    }
});
$(window).trigger('resize');
</script>
