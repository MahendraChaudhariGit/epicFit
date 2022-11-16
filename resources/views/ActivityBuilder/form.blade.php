
<!-- Start: Activity Form  -->
@if(isset($exercises))
    @if(isset($isClone) && $isClone)
    {!! Form::model($exercises, ['method' => 'post', 'route' => ['exercise.update', $exercises->id], 'id'=>'exercise-form', 'class'=>'margin-bottom-30','data-form-mode' => 'standAlone']) !!}
    <input type="hidden" name="isClone" value="1">
    @else
    {!! Form::model($exercises, ['method' => 'patch', 'route' => ['exercise.update', $exercises->id], 'id'=>'exercise-form', 'class'=>'margin-bottom-30','data-form-mode' => 'standAlone']) !!}
    @endif
@else
    {!! Form::open(['route' => ['exercise.store'], 'id' => 'exercise-form', 'class'=>'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
@endif
{{ Form::hidden('exerciseId', isset($exercises)?$exercises->id:'') }}
<div class="row">

<div class="sucMes hidden"></div>

<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>
             General
        </legend>
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            {!! Form::label('name', 'Movement Name *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise name"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('name', null, ['class' => 'form-control exerciseName', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                <span class="help-block"></span>
            </div>
        </div>
          <div class="form-group {{ $errors->has('sub_heading') ? 'has-error' : ''}}">
            {!! Form::label('sub_heading', 'Sub Heading*', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise name"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('sub_heading', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('sub_heading', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('alsoname') ? 'has-error' : ''}}">
            {!! Form::label('alsoname', 'Also Known As *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the Also Known As"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('alsoname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('alsoname', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        
        <div class="form-group {{ $errors->has('equipment') ? 'has-error' : ''}}">
            {!! Form::label('equipment', 'Training Equipment *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise category"><i class="fa fa-question-circle"></i></span>
            <a href="{{ route('exercise.getEquip') }}" class="pull-right added-more-item" data-modal-title="Manage Equipments" data-field="exeEquipment">Training Equipments</a>
            <div>
                {!! Form::select('equipment', isset($data)?$data['equipments']:[], null, ['class' => 'form-control onchange-set-neutral exeEquipment', 'required' => 'required','title'=>"--select--"]) !!}
                {!! $errors->first('equipment', '<p class="help-block">:message</p>') !!}
            </div>
            <a href="javascript:void(0)" class="pull-right addEquipment" data-options="{{isset($data)?json_encode($data['equipments']):[]}}" data-modal-title="Add Equipments" data-field="addEquipment">Add Equipments</a>
            
        </div>
         <div class="equipment-row">
            <?php 

            isset($exercises) && isset($exercises->equipmentextra)?$equipmentextra = explode(',', $exercises->equipmentextra):$equipmentextra=[];
                    $equipmentSelect = []; 
                    if(count($equipmentextra)){
                        foreach ($equipmentextra as $key => $value) {

                            ?>
                            <div class="form-group {{ $errors->has('equipmentextra') ? 'has-error' : ''}}">
            {!! Form::label('equipmentextra', 'Training Equipment *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise category"><i class="fa fa-question-circle"></i></span>
            <div class="removebtn">Remove</div>
           
            <div>
                {!! Form::select('equipmentextra', isset($data)?$data['equipments']:[], $value, ['class' => 'form-control onchange-set-neutral exeEquipmentClone', 'required' => 'required']) !!}
                {!! $errors->first('equipmentextra', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <?php

                            
                        }
                    }
                      ?>  </div>
        <div class="form-group {{ $errors->has('ability') ? 'has-error' : ''}}">
            {!! Form::label('ability', 'Ability level *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise ability"><i class="fa fa-question-circle"></i></span>
           <!-- <a href="{{ route('exercise.getAblity') }}" class="pull-right added-more-item" data-modal-title="Manage Abilities" data-field="exeAbility">Manage Abilities</a> -->
            
            <div>
                {!! Form::select('ability', isset($data)?$data['abilitys']:[], null, ['class' => 'form-control onchange-set-neutral exeAbility', 'required' => 'required']) !!}
                {!! $errors->first('ability', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('bodypart') ? 'has-error' : ''}}">
            {!! Form::label('bodypart', 'Muscle group *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise bodypart"><i class="fa fa-question-circle"></i></span>
            <!-- <a href="{{ route('exercise.getBodypart') }}" class="pull-right added-more-item" data-modal-title="Manage Body Parts" data-field="exeBodypart">Manage Body Parts</a> -->
            
            <div>
                <?php isset($exercises)?$bodyPart = explode(',', $exercises->bodypart):$bodyPart=[];
                    $bodySelect = []; 
                    if(count($bodyPart)){
                        foreach ($bodyPart as $key => $value) {
                            $bodySelect[] = (int)$value;
                        }
                    }
                ?>
                
                {!! Form::select('bodypart', isset($data)?$data['bodyparts']:[], $bodySelect, ['class' => 'form-control onchange-set-neutral exeBodypart', 'required' => 'required', 'multiple']) !!}
                {!! $errors->first('bodypart', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('exerciseTypeID') ? 'has-error' : ''}}">
            {!! Form::label('exerciseTypeID', 'Training Segments *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the exercise type"><i class="fa fa-question-circle"></i></span>
            <a href="{{ route('exercise.getExeType') }}" class="pull-right added-more-item" data-modal-title="Manage Exercise Type" data-field="exeType">Manage Training Segments</a>
            
            <div>
                {!! Form::select('exerciseTypeID', isset($data)?$data['exetype']:[], null, ['class' => 'form-control onchange-set-neutral exeType', 'required' => 'required']) !!}
                {!! $errors->first('exerciseTypeID', '<p class="help-block">:message</p>') !!}
            </div>
        </div>   
        
        <div class="form-group {{ $errors->has('movement_pattern') ? 'has-error' : ''}}">
            {!! Form::label('movement_load', 'Movement load *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the movement load"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('movement_load', [''=>' -- Select -- ','1'=>'Assisted','2'=>'Unassisted','3'=>'Loaded'], null, ['class' => 'form-control onchange-set-neutral', 'required' => 'required']) !!}
                {!! $errors->first('movement_load', '<p class="help-block">:message</p>') !!}
            </div>
        </div>   

        <div class="form-group {{ $errors->has('movement_pattern') ? 'has-error' : ''}}">
            {!! Form::label('movement_type', 'Movement type *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the movement pattern"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('movement_type', [''=>' -- Select -- ','1'=>'Compound','2'=>'Isolated','3'=>'Isometric'], null, ['class' => 'form-control onchange-set-neutral', 'required' => 'required']) !!}
                {!! $errors->first('movement_type', '<p class="help-block">:message</p>') !!}
                
            </div>
        </div>   
       
        <div class="form-group {{ $errors->has('movement_pattern') ? 'has-error' : ''}}">
            {!! Form::label('movement_pattern', 'Movement Pattern *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the movement pattern"><i class="fa fa-question-circle"></i></span>
            <a href="{{ route('exercise.getMovement') }}" class="pull-right added-more-item" data-modal-title="Manage Movement Pattern" data-field="exeMovement">Manage Movement Pattern</a>

            
            <div>
                {!! Form::select('movement_pattern', isset($data)?$data['movepattern']:[], null, ['class' => 'form-control onchange-set-neutral exeMovement', 'required' => 'required' ,'title'=>"--select--"]) !!}
                {!! $errors->first('movement_pattern', '<p class="help-block">:message</p>') !!}
            </div>
            <a href="javascript:void(0)" class="pull-right addPattern" data-options="{{isset($data)?json_encode($data['movepattern']):[]}}" data-modal-title="Add Pattern" data-field="addPattern">Add Pattern</a>
        </div>   
        <div class="pattern-row">
            <?php 

            isset($exercises) && isset($exercises->patternExtra)?$patternExtra = explode(',', $exercises->patternExtra):$patternExtra=[];
                    $patternSelect = []; 
                    if(count($patternExtra)){
                        foreach ($patternExtra as $key => $valuePattern) {
                            ?>

                             <div class="form-group {{ $errors->has('patternExtra') ? 'has-error' : ''}}">
            {!! Form::label('patternExtra', 'Movement Pattern *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the movement pattern"><i class="fa fa-question-circle"></i></span>
            <div class="removebtn">Remove</div>
            

            
            <div>
                {!! Form::select('patternExtra', isset($data)?$data['movepattern']:[], $valuePattern, ['class' => 'form-control onchange-set-neutral exePatternClone', 'required' => 'required']) !!}
                {!! $errors->first('patternExtra', '<p class="help-block">:message</p>') !!}
            </div>
           
        </div>   
        <?php
    }
}
?>
        </div>
        <div class="form-group {{ $errors->has('exerciseDesc') ? 'has-error' : ''}}">
            {!! Form::label('exerciseDesc', 'Exercise Description *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is a detailed description of the exercise">
                            <a class="pull-right generate_desc" data-modal-title="Generate Description">Generate Description</a>

             
                <i class="fa fa-question-circle"></i></span>
            <div>

                {!! Form::textarea('exerciseDesc', null, ['class' => 'form-control textarea exe_desc', 'required' => 'required']) !!}
                {!! $errors->first('exerciseDesc', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </fieldset>
    <!-- End: General -->
</div>
<div class="col-md-6">
    <!-- Start: Exercise Images -->
    <fieldset class="padding-15">
        <legend>
            Trainning segment icon
            <a class="btn btn-xs btn-primary" href="#" id="add-img-row"><i class=" fa fa-plus fa fa-white"></i></a> 
        </legend>
        <div class="imgError hidden"> </div> 
        <div class="form-group upload-group" id="image-area">
            <div>
                {!! Form::label(null, 'Upload Exercise Picture *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="Upload Exercise Picture"><i class="fa fa-question-circle"></i></span>
            </div>
            @if(isset($exercises) && $exercises->exeimages->count())
                <?php $i = 1; ?>
                @foreach($exercises->exeimages as $exeImage)
                <div class="set-error clone-image-row col-md-3 col-xs-6 remove-img">
                    <div class="img-row-design">
                        <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                            <img class="image-uploder-img-style" src="{{ dpSrc($exeImage->aei_image_name) }}" />
                            <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span> 
                            <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <input type="hidden" name="exercisePicture{{$i}}" value="{{ $exeImage->aei_image_name }}">
                    </div>
                </div>
                <?php $i++; ?>
                @endforeach
            @endif
            <div class="set-error clone-image-row col-md-3 col-xs-6 btn-file">
                <input type="hidden" name="prePhotoName" value="" class="no-clear">
                <input type="hidden" name="entityId" value="" class="no-clear">
                <input type="hidden" name="saveUrl" value="" class="no-clear">
                <input type="hidden" name="photoHelper" value="exercisePicture" class="no-clear">
                <input type="hidden" name="cropSelector" value="square">
                <div class="img-row-design">
                    <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                        <img class="exercisePicturePreviewPics previewPics image-uploder-img-style" src="{{ asset('assets\images\no-image.jpg') }}" />
                        <span class="image-uploder-label-style"><i class="fa fa-plus"></i> Add File</span> 
                        <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                    </label>
                    
                    <input type="hidden" name="exercisePicture" value="">
                </div>
            </div>
        </div>
    </fieldset>
    <!-- End: Exercise Images -->
    {{-- Start exercise video --}}
        <fieldset class="padding-15">
            <legend>
                Exercise Videos
                {{-- <a class="btn btn-xs btn-primary" href="#" id="add-video-row"><i class=" fa fa-plus fa fa-white"></i></a> --}} 
            </legend>
            <!-- <div class="hidden"> </div> --> 
            <div class="row">
                <div class="col-md-6 col-xs-6">
                     <div class="form-group upload-group" id="tutorial-area">
                        <div>
                            {!! Form::label(null, 'Upload Tutorial Video *', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Upload Exercise Video"><i class="fa fa-question-circle"></i></span>
                        </div>
                        
                        @if(isset($exercises) && $tutorialVideo->aei_video_name)
                            <div class="set-error clone-image-row remove-tutorial">
                                <div class="img-row-design">
                                    <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                                        <video controls class="tutorial" height="100" width="100">
                                          <source src="{{ asset('uploads/'.$tutorialVideo->aei_video_name) }}" type="video/mp4">
                                          Your browser does not support the video tag.
                                        </video>
                                        <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span>
                                        {{-- <img class="image-uploder-img-style" src="{{ dpSrc($exeImage->aei_image_name) }}" /> --}}
                                        {{-- <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span>  --}}
                                         {{-- <span class="image-uploder-label-style"><i class="fa fa-plus"></i> Add File</span>  --}}
                                        {{-- <input type="file" class="hidden" onChange="fileSelectHandlerVideo(this)" accept="video/*"> --}}
                                    </label>
                                    {{-- <input type="hidden" name="exercisePicture{{$i}}" value="{{ $exeImage->aei_image_name }}"> --}}
                                </div>
                            </div>
                        @else
                        <div class="set-error clone-image-row btn-file" id="uploadProgram">
                            <div class="img-row-design">
                                <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                              {{--       <img class="exercisePicturePreviewPics previewPics image-uploder-img-style" src="{{ asset('assets\images\no-image.jpg') }}" /> --}}
                              <video controls class="tutorial" height="100" width="100"></video>
                                    <span class="image-uploder-label-style" id="addProgram"><i class="fa fa-plus"></i> Add File</span> 
                                    <input type="file" class="hidden" onChange="fileSelectProgramVideo(this)" accept="video/*"  name="fileToUpload">
                                    <!-- <input type="file" class="hidden" id="exerciseVideoFile" accept="video/*"> -->
                                </label>
                                
                                <input type="hidden" name="tutorialVideo" id="tutorialVideo">
                            </div>
                        </div>
                       @endif 
                    </div>
                   
                </div>
                <div class="col-md-6 col-xs-6">
                    <div class="form-group upload-group" id="video-area">
                        <div>
                            {!! Form::label(null, 'Upload Program Video *', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="Upload Exercise Video"><i class="fa fa-question-circle"></i></span>
                        </div>
                        @if(isset($exercises) && $programVideo->aei_video_name)
                            <div class="set-error clone-image-row  remove-video">
                                <div class="img-row-design">
                                    <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                                        <video controls class="video" height="100" width="100">
                                          <source src="{{ asset('uploads/'.$programVideo->aei_video_name) }}" type="video/mp4">
                                          Your browser does not support the video tag.
                                        </video>
                                        <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span>
                                        {{-- <img class="image-uploder-img-style" src="{{ dpSrc($exeImage->aei_image_name) }}" /> --}}
                                        {{-- <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span>  --}}
                                         {{-- <span class="image-uploder-label-style"><i class="fa fa-plus"></i> Add File</span>  --}}
                                        {{-- <input type="file" class="hidden" onChange="fileSelectHandlerVideo(this)" accept="video/*"> --}}
                                    </label>
                                    {{-- <input type="hidden" name="exercisePicture{{$i}}" value="{{ $exeImage->aei_image_name }}"> --}}
                                </div>
                            </div>
                        @else
                        <div class="set-error clone-image-row btn-file" id="uploadVideo">
                            <div class="img-row-design">
                                <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                              {{--       <img class="exercisePicturePreviewPics previewPics image-uploder-img-style" src="{{ asset('assets\images\no-image.jpg') }}" /> --}}
                              <video controls class="video" height="100" width="100"></video>
                                    <span class="image-uploder-label-style" id="addFile"><i class="fa fa-plus"></i> Add File</span> 
                                    <input type="file" class="hidden" onChange="fileSelectHandlerVideo(this)" accept="video/*"  name="fileToUpload">
                                    <!-- <input type="file" class="hidden" id="exerciseVideoFile" accept="video/*"> -->
                                </label>
                                
                                <input type="hidden" name="exerciseVideo" id="exerciseVideo">
                            </div>
                        </div>
                       @endif 
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group upload-group col-md-12" id="image-thumbnail-area">
                        <div>
                              {!! Form::label(null, 'Upload Program Video Tumbnail *', ['class' => 'strong']) !!}
                              <span class="epic-tooltip" data-toggle="tooltip" title="Upload Program Video Tumbnail"><i class="fa fa-question-circle"></i></span>
                        </div>
                        @if(isset($exercises) && $programVideo->thumbnail_program)
                        <div class="set-error clone-image-row col-md-3 col-xs-6 remove-img">
                           <div class="img-row-design">
                              <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                                    <img class="image-uploder-img-style" src="{{ dpSrc($programVideo->thumbnail_program) }}" />
                                    <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span> 
                                    <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                              </label>
                              <input type="hidden" name="thumbnailProgram" id="thumbnailProgram" value="{{ $programVideo->thumbnail_program }}">
                           </div>
                        </div>
                        @endif
                        <div class="set-error clone-image-row col-md-3 col-xs-6 btn-file">
                              <input type="hidden" name="prePhotoName" value="" class="no-clear">
                              <input type="hidden" name="entityId" value="" class="no-clear">
                              <input type="hidden" name="saveUrl" value="" class="no-clear">
                              <input type="hidden" name="photoHelper" value="thumbnailProgram" class="no-clear">
                              <input type="hidden" name="cropSelector" value="square">
                              <div class="img-row-design">
                                 <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                                    <img class="thumbnailProgramPreviewPics previewPics image-uploder-img-style" src="{{ asset('assets\images\no-image.jpg') }}" />
                                    <span class="image-uploder-label-style"><i class="fa fa-plus"></i> Add File</span> 
                                    <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                                 </label>
                                 
                                 <input type="hidden" name="thumbnailProgram" id="thumbnailProgram" value="{{ $programVideo->thumbnail_program }}">
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    {{-- End exercise video --}}
    <fieldset class="padding-15">
        <legend>Descriptions</legend>

        <div class="panel-group accordion" id="accordion">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false">
                        <i class="icon-arrow"></i> Muscles Description
                    </a></h5>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('muscles') ? 'has-error' : ''}}">
                            <textarea class="ckeditor form-control" cols="10" rows="10" name='muscles'>
                                {{ isset($exercises)?$exercises->muscles:'' }}
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false">
                        <i class="icon-arrow"></i> Benefit Description
                    </a></h5>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('benifits') ? 'has-error' : ''}}"> 
                            <textarea class="ckeditor form-control" cols="10" rows="10" name='benifits'>
                                {{ isset($exercises)?$exercises->benifits:'' }}
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false">
                        <i class="icon-arrow"></i> Cues Description
                    </a></h5>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('cues') ? 'has-error' : ''}}">
                            <textarea class="ckeditor form-control" cols="10" rows="10" name='cues'>
                                {{ isset($exercises)?$exercises->cues:'' }}
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
             <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false">
                        <i class="icon-arrow"></i> Movement Description
                    </a></h5>
                </div>
                <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('movement_desc') ? 'has-error' : ''}}">
                            <textarea class="ckeditor form-control" cols="10" rows="10" name='movement_desc'>
                                {{ isset($exercises)?$exercises->movement_desc:'' }}
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
             <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false">
                        <i class="icon-arrow"></i> Common Mistakes
                    </a></h5>
                </div>
                <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('common_mistekes') ? 'has-error' : ''}}">
                            <textarea class="ckeditor form-control" cols="10" rows="10" name='common_mistekes'>
                                {{ isset($exercises)?$exercises->common_mistekes:'' }}
                            </textarea>
                        </div>progress
                    </div>
                </div>
            </div>
             <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false">
                        <i class="icon-arrow"></i> Progress Description
                    </a></h5>
                </div>
                <div id="collapseSix" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('progress') ? 'has-error' : ''}}">
                            <textarea class="ckeditor form-control" cols="10" rows="10" name='progress'>
                                {{ isset($exercises)?$exercises->progress:'' }}
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </fieldset>
</div>
      <div class="col-md-6">
            <fieldset class="padding-15 ">
                <legend>
                    Exercise Listing
                </legend>
                <div class="form-group">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input name="listing_status" value="0" type="checkbox" id="listingStatus" {{isset($exercises) && $exercises->listing_status == \App\Exercise::HIDE?'checked':''}}>
                        <label for="listingStatus">
                            <strong>Remove activity from listing for client </strong> 
                        </label>  
                    </div>
                </div>
            </fieldset>
        </div> 
</div>



<div class="row">
        <div class="col-sm-12 col-xs-11">
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-wide pull-right saveExerciseBtn">
                    @if(isset($exercises))
                        <i class="fa fa-edit"></i> Update Exercise
                    @else
                        <i class="fa fa-plus"></i> Add Exercise
                    @endif
                </button>
            </div>
        </div>
    </div>
{!! Form::close() !!}
<!-- Start: Activity Form  -->            