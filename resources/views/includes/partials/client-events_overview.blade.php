<div>
    <h5><strong>Last appointment</strong></h5>
    @if($latestPastEvent->count())
        <?php $modelName = class_basename($latestPastEvent); ?>
        @if($modelName == 'StaffEvent')
            {!! renderClientAppointment($latestPastEvent, 'past') !!}
        @else
            {!! renderClientEventClass($latestPastEvent, 'past') !!}
        @endif
    @else
        <div class="m-b-20">This client has no previous appointments.</div>
    @endif                             
</div>
<div>
    <h5><strong>Next appointment</strong></h5>
     @if($oldestFutureEvent->count())
        <?php $modelName = class_basename($oldestFutureEvent); ?>
        @if($modelName == 'StaffEvent')
            {!! renderClientAppointment($oldestFutureEvent, 'future') !!}
        @else
            {!! renderClientEventClass($oldestFutureEvent, 'future') !!}
        @endif
    @else
        This client has no future appointments.
    @endif  
</div>
