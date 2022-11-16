@if(!isset($businessId))
    {!! Form::open(['url' => 'settings/staff', 'id' => 'form-3', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison']) !!}
    {!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}

    <div class="row">
        <div class="col-xs-12">
            <p class="margin-top-5 italic">This is a brief summary of the location of your venue or venues.</p>
        </div>
    </div>
    <div class="row margin-top-90">
@else
    @if(isset($staff))
        {!! Form::model($staff, ['method' => 'patch', 'route' => ['staffs.update', $staff->id], 'id' => 'form-3', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone', 'autocomplete' => 'off']) !!}
        {!! Form::hidden('prevServices', count($staffServices)?implode(',', $staffServices):'' , ['class' => 'no-clear']) !!}
    @else
        {!! Form::open(['route' => ['staffs.store'], 'id' => 'form-3', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone',
         'enctype' =>  'multipart/form-data']) !!}
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}

    @if(isset($areasToLink))
        {!! Form::hidden('areasToLink', $areasToLink , ['class' => 'no-clear']) !!}
    @elseif(isset($locToLink))
        {!! Form::hidden('locToLink', $locToLink , ['class' => 'no-clear']) !!}
    @endif
    <div class="row">
@endif
    <div class="sucMes hidden"></div>
    @include('includes.partials.staff_form')
</div>

@if(!isset($businessId))
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <button class="btn btn-primary btn-o back-step btn-wide pull-left">
                    <i class="fa fa-circle-arrow-left"></i> Back
                </button>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <button class="btn btn-primary btn-o next-step btn-wide pull-right">
                    Next <i class="fa fa-arrow-circle-right"></i>
                </button>
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 btn-add-more-form">
                    <i class="fa fa-plus"></i> Add Staff
                </button>
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 skipnextbutton skipbutton hidden">
                    Skip to next
                </button>
                @if(isset($subview))
                    <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                        Close
                    </button>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-primary btn-wide pull-right btn-add-more-form">
                    @if(isset($staff))
                        <i class="fa fa-edit"></i> Update Staff
                    @else
                        <i class="fa fa-plus"></i> Add Staff
                    @endif
                </button>
                @if(isset($subview))
                    <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                        Close
                    </button>
                @endif
            </div>
        </div>
    </div>
@endif
{!! Form::close() !!}

