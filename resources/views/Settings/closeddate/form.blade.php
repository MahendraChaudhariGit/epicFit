<div class="sucMes hidden"></div>
<div class="alert alert-block alert-danger fade in hidden" id="overlapErr">
    <h4 class="alert-heading margin-bottom-10">Dates overlap with following <span class="text"></span> dates!</h4>
    <ul class="lh-22"></ul>
</div>

<fieldset class="padding-15">
    <legend>
        General
    </legend>
    <div class="form-group">
        {!! Form::label('startDate', 'Start date *', ['class' => 'strong']) !!}
        {!! Form::text('startDate', isset($closedDate)?$closedDate->StartDate:null, ['class' => 'form-control startDatepicker onchange-set-neutral' ,'required' => 'required' ,'autocomplete'=>'off','readonly']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('endDate', 'End date *', ['class' => 'strong']) !!}
        {!! Form::text('endDate', isset($closedDate)?$closedDate->EndDate:null, ['class' => 'form-control endDatepicker onchange-set-neutral','required' => 'required','autocomplete'=>'off','readonly']) !!}
        <span class="help-block"></span>
    </div>
    <div class="form-group">
        {!! Form::label('Description', 'Description *', ['class' => 'strong']) !!}
        {!! Form::textarea('Description', isset($closedDate)?$closedDate->cd_description:null, ['class' => 'form-control textarea' ,'id'=>'description','required' => 'required']) !!}
    </div>
</fieldset>