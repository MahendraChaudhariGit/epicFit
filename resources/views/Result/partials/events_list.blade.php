<div id="appointments" class="tab-pane">
    <div class="page-header">
        <h1>Appointments</h1>
    </div>
    <div>
        <a class="btn btn-default hide" href="#">
            <i class="fa fa-print"></i> Print appointments
        </a>
        <div class="row">
            <div class="col-md-6">
                <h3 class="m-y-20">Past appointments</h3>
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
                    You do not have any previous appointments.
                @endif                             
            </div>
            <div class="col-md-6">
                <h3 class="m-y-20">Future appointments</h3>
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
                    You do not have any future appointments.
                @endif   
            </div>
        </div>
    </div>
</div>