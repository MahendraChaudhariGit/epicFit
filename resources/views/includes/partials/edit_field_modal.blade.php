<div class="modal fade" id="editFieldModal" role="dialog">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit information</h4>
            </div>
            <div class="modal-body bg-white">
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
                    <!--{!! Form::hidden('origEntity', $entity) !!}
                    {!! Form::hidden('origEntityId', $entityId) !!}-->
                    {!! Form::hidden('entity', $entity) !!}
                    {!! Form::hidden('entityId', $entityId) !!}
                    {!! Form::hidden('entityProperty') !!}
                    {!! Form::hidden('otherTargets') !!}
                    {!! Form::hidden('ldcSessions') !!}

                    <div class="form-group">
                        {!! Form::label(null, null, ['class' => 'strong']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            	<button type="button" class="btn btn-primary submit">Submit</button>
            </div>
		</div>
    </div>
</div>