
<!-- Start: Activity Form  -->
@if(isset($libraryProgram))
    {!! Form::model($libraryProgram, ['method' => 'patch', 'route' => ['libraryprogram.update', $libraryProgram->id], 'id'=>'libraryPro-form', 'class'=>'margin-bottom-30','data-form-mode' => 'standAlone']) !!}
@else
    {!! Form::open(['route' => ['libraryprogram.store'], 'id' => 'libraryPro-form', 'class'=>'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
@endif
{{ Form::hidden('libraryProgramId', isset($libraryProgram)?$libraryProgram->id:'') }}
<div class="row">
    <div class="col-md-6">
        <fieldset class="padding-15">
            <legend>
             General
            </legend>
            <div class="form-group gender-class">
                {!! Form::label(null, 'Gender *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="Choose gender for exercise."><i class="fa fa-question-circle"></i></span>
                <div class="radio clip-radio radio-primary m-b-0">
                    <input type="radio" name="genderAdmin" id="maleGender" value="Male" class="radioError" <?php if(isset($libraryProgram) && $libraryProgram->gender == 2) echo "checked"; else echo "";?>>
                    <label for="maleGender">
                        Male
                    </label>

                    <input type="radio" name="genderAdmin" id="femaleGender" value="Female" class="radioError" <?php if(isset($libraryProgram) && $libraryProgram->gender == 1) echo "checked"; else echo "";?> >
                    <label for="femaleGender">
                        Female
                    </label>

                    <input type="radio" name="genderAdmin" id="unisexGender" value="Unisex" class="radioError" <?php if(isset($libraryProgram) && $libraryProgram->gender == 3) echo "checked"; else echo "";?> >
                    <label for="unisexGender">
                        Unisex
                    </label>
                </div>
            </div>  
            <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', 'Program Name *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the name of program."><i class="fa fa-question-circle"></i></span>
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group {{ $errors->has('discription') ? 'has-error' : ''}}">
                {!! Form::label('discription', 'Discription *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the discription of program."><i class="fa fa-question-circle"></i></span>
                {!! Form::textarea('discription', null, ['class' => 'form-control textarea', 'required' => 'required']) !!}
                {!! $errors->first('discription', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group {{ $errors->has('habit') ? 'has-error' : ''}}">
                {!! Form::label('habit', 'Current Ability *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the current ability."><i class="fa fa-question-circle"></i></span>
                {!! Form::select('habit',isset($exerciseData)?$exerciseData['abilitys']:[], null, ['class' => 'form-control onchange-set-neutral', 'required' => 'required']) !!}
                {!! $errors->first('habit', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group">
                {!! Form::label('equipmentvalue', 'Training Equipment *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise category"><i class="fa fa-question-circle"></i></span>
                 {!! Form::text('equipmentvalue', Bodyweight, ['class' => 'form-control', 'required' => 'required','readonly']) !!}
                
            </div>
             <div class="form-group {{ $errors->has('equipment') ? 'has-error' : ''}}">
                {!! Form::label('equipment', 'Training Equipment *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise category"><i class="fa fa-question-circle"></i></span>
                {!! Form::select('equipment', isset($exerciseData)?$exerciseData['equipments']:[], null, ['class' => 'form-control onchange-set-neutral exeEquipment', 'required' => 'required','title'=>"--select--"]) !!}
                {!! $errors->first('equipment', '<p class="help-block">:message</p>') !!}

            </div>
            <div class="form-group upload-group">
                {!! Form::label(null, 'Upload Program Image *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This allows for images of the program to be uploaded"><i class="fa fa-question-circle"></i></span>
                <input type="hidden" name="prePhotoName" value="{{ isset($libraryProgram)?$libraryProgram->image:'' }}" class="no-clear">
                <input type="hidden" name="entityId" value="" class="no-clear">
                <input type="hidden" name="saveUrl" value="" class="no-clear">
                <input type="hidden" name="photoHelper" value="programImage" class="no-clear">
                <input type="hidden" name="cropSelector" value="square">
                <div>
                    <label class="btn btn-primary btn-file">
                        <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                    </label>
                    <div class="m-t-10">
                        @if(isset($libraryProgram))
                            <img class="programImagePreviewPics previewPics" src="{{ dpSrc($libraryProgram->image) }}" width="100px" />
                        @else 
                            <img class="hidden programImagePreviewPics previewPics" />
                        @endif
                    </div>
                </div>
                <span class="help-block m-b-0"></span>
                <input type="hidden" name="programImage" value="">
            </div> 
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            @if(isset($libraryProgram))
            <button type="button" class="btn btn-primary btn-wide pull-right nextMultPhaseEditButton" data-target-step="trainingSegment">
                Next <i class="fa fa-arrow-circle-right"></i>
            </button>
            @else
            <button type="button" class="btn btn-primary btn-wide pull-right nextMultPhaseButton" data-target-step="trainingSegment">
                Next <i class="fa fa-arrow-circle-right"></i>
            </button>
            @endif
        </div>
    </div>
</div>
{!! Form::close() !!}
<!-- Start: Activity Form  -->            