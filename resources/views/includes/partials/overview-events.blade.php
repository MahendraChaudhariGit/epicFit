<div>
    <h5><strong>Past Appointments</strong></h5>
    @if(count($latestPastEvent))
        <?php $modelName = class_basename($latestPastEvent); ?>
        @if($modelName == 'StaffEventSingleService')
            {!! renderClientAppointment($latestPastEvent, 'past') !!}
        @else
            {!! renderClientEventClass($latestPastEvent, 'past') !!}
        @endif
    @else
        <div class="m-b-20">
            @if(isUserType(['Staff']))
                <?php $message = 'You do not have any previous services.'; ?>
            @else
                <?php $message = 'This client has no previous services.'; ?>
            @endif
            {!! displayNonClosingAlert('warning', $message) !!}
        </div>
    @endif                             
</div>
<div>
    <h5><strong>Future Appointments</strong></h5>
    @if(count($oldestFutureEvent))
        <?php $modelName = class_basename($oldestFutureEvent); ?>
        @if($modelName == 'StaffEventSingleService')
            {!! renderClientAppointment($oldestFutureEvent, 'future') !!}
        @else
            {!! renderClientEventClass($oldestFutureEvent, 'future') !!}
        @endif
    @else
        @if(isUserType(['Staff']))
            <?php $message = 'You do not have any future services.'; ?>
        @else
            <?php $message = 'This client has no future services.'; ?>
        @endif
        {!! displayNonClosingAlert('warning', $message) !!}
    @endif  
</div>
