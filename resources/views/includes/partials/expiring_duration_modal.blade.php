<div class="modal fade" id="expiringDurationModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit information</h4>
            </div>
            <div class="modal-body bg-white">
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
                <div class="form-group">
                    {!! Form::label('duration','Dutation *',['class'=>'strong']) !!} 
                    {!! Form::number('duration',null,['class'=>'form-control numericField','required'=>'required','min'=>'1']) !!}
                    
                </div>
                <div class="form-group">
                    {!! Form::label('durationType','Type *',['class'=>'strong']) !!} 
                    {!! Form::select('durationType',[''=>' -- Select -- ','day'=>'Day(s)','week'=>'Week(s)','month'=>'Month(s)'],null,['class'=>'form-control onchange-set-neutral','required']) !!}
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