<div class="modal fade" id="editFieldFromViewModal" role="dialog">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit information</h4>
            </div>
            <div class="modal-body bg-white">
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
                    {!! Form::hidden('entityId', isset($entityId)?$entityId:'') !!}
                    {!! Form::hidden('entityType', isset($entityType)?$entityType:'') !!}
                    {!! Form::hidden('fieldType') !!}
                    {!! Form::hidden('log') !!}
                    <div class="form-group">
                        
                    </div>
                    <div class="form-group" >
                        <label class="strong"></label>
                        <div id="field-area">
                            
                        </div>   
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