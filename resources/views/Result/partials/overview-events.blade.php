<div>
    <h5><strong>Last service</strong></h5>

   
    @if(count($latestPastEvent))
        <?php $modelName = class_basename($latestPastEvent); ?>
        @if($modelName == 'StaffEventSingleService')
            {!! renderClientAppointment($latestPastEvent, 'past') !!}
        @else
            {!! renderClientEventClass($latestPastEvent, 'past') !!}
        @endif
    @else
        <div class="m-b-20">
            {!! displayNonClosingAlert('warning', 'You do not have any previous services.') !!}
        </div>
    @endif                             
</div>
<div>
    <h5><strong>Next service</strong></h5>
    @if(count($oldestFutureEvent))
        <?php $modelName = class_basename($oldestFutureEvent); ?>
        @if($modelName == 'StaffEventSingleService')
            {!! renderClientAppointment($oldestFutureEvent, 'future') !!}
        @else
            {!! renderClientEventClass($oldestFutureEvent, 'future') !!}
        @endif
    @else
       {!! displayNonClosingAlert('warning', 'You do not have any future services.') !!}
    @endif  
</div>
