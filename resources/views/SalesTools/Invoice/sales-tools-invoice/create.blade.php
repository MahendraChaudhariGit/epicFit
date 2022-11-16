@extends('layouts.app')

@section('page-title')
    Add SalesToolsInvoice
@stop()

@section('content')
    {!! Form::open(['url' => 'salestoolsinvoice', 'class' => 'container-fluid container-fullw bg-white scrollToTop']) !!}

            <div class="form-group">
                {!! Form::label('payTerms', 'Payterms', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::select('payTerms', ['' => '-- Select --', '1' => 'Immediately', '2' => 'Within 7 days', '3' => 'Within 14 days', '4' => 'Within 21 days', '5' => 'Within 30 days', '6' => '20th of the following month'], null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('invTitle', 'Invtitle', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('invTitle', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('bussReg', 'Bussreg', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('bussReg', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('bussRegType', 'Bussregtype', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('bussRegType', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('payInst', 'Payinst', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::textarea('payInst', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('nxtInvNum', 'Nxtinvnum', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('nxtInvNum', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('invType1', 'Invtype1', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::select('invType1', ['' => '-- Select --', '1' => 'PDF', '2' => 'Thermal receipt'], null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('invType2', 'Invtype2', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::select('invType2', ['' => '-- Select --', '1' => 'PDF', '2' => 'Thermal receipt'], null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('taxName', 'Taxname', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('taxName', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('taxRate', 'Taxrate', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('taxRate', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('taxUsuage', 'Taxusuage', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('taxUsuage', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('overrideAll', 'Overrideall', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::select('overrideAll', ['' => '-- Select --', '1' => 'GST'], null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('payType1', 'Paytype1', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('payType1', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('payType2', 'Paytype2', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('payType2', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('payType3', 'Paytype3', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('payType3', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('payType4', 'Paytype4', ['class' => 'strong']) !!}
                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left"  title=""><i class="fa fa-question-circle"></i></span>
                {!! Form::text('payType4', null, ['class' => 'form-control']) !!}
            </div>


    <div class="row">
        <div class="col-sm-12 text-right">
            <button class="btn btn-primary btn-wide">
                <i class="fa fa-plus"></i> Add SalesToolsInvoice
            </button>
        </div>
    </div>

    {!! Form::close() !!}
@endsection