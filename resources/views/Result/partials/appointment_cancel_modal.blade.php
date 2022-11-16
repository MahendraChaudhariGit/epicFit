<!-- start: Appoinment Cancel Modal -->
<div class="modal fade" id="appointCancelModal" role="dialog" >
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Cancel Appointment</h4>
            </div>
            <div class="modal-body bg-white">
            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
	            	<div class="row">
						<div class="col-md-12">
							<h4>The following services will be cancelled:</h4>
							<ul class="list-group" id="services">
							</ul>
							<div class="form-group">
								{!! Form::label('cancelReas', 'Reason for cancelation *', ['class' => 'strong']) !!}
								{!! Form::select('cancelReas', ['' => '-- Select --', 'Did not Specify' => 'Did not Specify', 'Other commitments' => 'Other commitments', 'Not necessary now' => 'Not necessary now', 'Did not show' => 'Did not show', 'Appointment made in error' => 'Appointment made in error', 'Other' => 'Other'], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
				            </div>
						</div>
					</div>
				{!! Form::close() !!}
        	</div>
    		<div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            	<button type="button" class="btn btn-primary submit">Cancel Appointment</button>
            </div>
    	</div>
    </div>
</div>
<!-- end: Appoinment Cancel Modal -->