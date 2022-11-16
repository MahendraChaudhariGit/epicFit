<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>
            General
        </legend>
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            {!! Form::label('title', 'Video Title *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is the video title"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::text('title', isset($video)?$video->title:null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('workout_id') ? 'has-error' : ''}}">
            {!! Form::label('workout_id', 'Workout Type *', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="This is workout type"><i class="fa fa-question-circle"></i></span>
            <div>
                {!! Form::select('workout_id',$abWorkouts, isset($video)?$video->workout_id:null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('workout_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-6">  
    {{-- Start exercise video --}}
    <fieldset class="padding-15">
         <legend>
            Videos
         </legend>
         <div class="imgError hidden"> </div>
         <div class="row">
            <div class="form-group upload-group col-md-12" id="video-area">
               <div>
                     {!! Form::label(null, 'Upload Video *', ['class' => 'strong']) !!}
                     <span class="epic-tooltip" data-toggle="tooltip" title="Upload Video"><i class="fa fa-question-circle"></i></span>
               </div>
               @if(isset($video) && isset($video->video) && $video->video != '')
               <div class="set-error clone-image-row col-md-3 col-xs-6 remove-video">
                     <div class="img-row-design">
                        <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                           <video controls class="video" height="100" width="100" id="myVideo">
                              <source src="{{ asset('uploads/'.$video->video) }}" type="video/mp4">
                                 Your browser does not support the video tag.
                              </video>
                              <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span>
                           </label>
                     </div>
                     <input type="hidden" name="video" id="activityVideo" value="{{$video->video}}">
                  </div>
                  @else
                  <div class="set-error clone-image-row col-md-3 col-xs-6 btn-file" id="uploadVideo">
                     <div class="img-row-design">
                        <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                           <video controls class="video" height="100" width="100"></video>
                           <span class="image-uploder-label-style" id="addFile"><i class="fa fa-plus"></i> Add File</span> 
                           <input type="file" class="hidden" onChange="fileSelectHandlerVideo(this)" accept="video/*" id="$request" name="fileToUpload">
                        </label>
                        <input type="hidden" name="video" id="activityVideo">
                        {!! $errors->first('video', '<p class="help-block">:message</p>') !!}
                     </div>
               </div>
               @endif 
            </div>
            <div class="form-group upload-group col-md-12" id="image-area">
               <div>
                     {!! Form::label(null, 'Upload Video Tumbnail *', ['class' => 'strong']) !!}
                     <span class="epic-tooltip" data-toggle="tooltip" title="Upload Video Tumbnail"><i class="fa fa-question-circle"></i></span>
               </div>
               @if(isset($video) && $video->thumbnail)
               <div class="set-error clone-image-row col-md-3 col-xs-6 remove-img">
                  <div class="img-row-design">
                     <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                           <img class="image-uploder-img-style" src="{{ dpSrc($video->thumbnail) }}" />
                           <span class="image-uploder-label-style"><i class="fa fa-times"></i> Remove</span> 
                           <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                     </label>
                     <input type="hidden" name="thumbnail" id="thumbnail" value="{{ $video->thumbnail }}">
                  </div>
               </div>
               @endif
               <div class="set-error clone-image-row col-md-3 col-xs-6 btn-file">
                     <input type="hidden" name="prePhotoName" value="" class="no-clear">
                     <input type="hidden" name="entityId" value="" class="no-clear">
                     <input type="hidden" name="saveUrl" value="" class="no-clear">
                     <input type="hidden" name="photoHelper" value="thumbnail" class="no-clear">
                     <input type="hidden" name="cropSelector" value="square">
                     <div class="img-row-design">
                        <label class="btn btn-img-uplode" style="margin:0px; padding:0px;">
                           <img class="thumbnailPreviewPics previewPics image-uploder-img-style" src="{{ asset('assets\images\no-image.jpg') }}" />
                           <span class="image-uploder-label-style"><i class="fa fa-plus"></i> Add File</span> 
                           <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        
                        <input type="hidden" name="thumbnail" id="thumbnail" value="">
                     </div>
               </div>
            </div>
         </div> 
    </fieldset>
</div>
<div class="col-md-12">
    <fieldset class="padding-15">
       <legend>
          Overview
       </legend>
       <div class="col-md-6">
          <div class="form-group">
             {!! Form::label('description', 'Description', ['class' => 'strong']) !!}
             <div> <textarea id="description" class="ta" name="description" required>{{isset($video)?$video->description:null}}</textarea></div>
          </div>
       </div>
       <div class="col-md-6 border-r">
          <div class="col-md-12">
             <div class="form-group">
                <button type="button" class="btn btn-danger add-movement-btn">
                Add Movement
                </button>
             </div>
          </div>
          <div class="movementRow">
              @if(count($video->videoMovements))
              @foreach ($video->videoMovements as $movement)
              <div class="add-movement-section col-md-12 alert">
                <div class="col-md-6 pr-0">
                   <div class="form-group ">
                      <input type="text" class="form-control" name="movement_name" value="{{$movement->name}}" placeholder="Enter movement" required>
                   </div>
                </div>
                @php
                    $time = explode(':',$movement->time);
                @endphp
                <div class="col-md-2 pr-0">
                   <div class="form-group ">
                    <input type="text" class="form-control" name="movement_time_h" value="{{$time[0]}}" required>
                   </div>
                </div>
                <div class="col-md-2 pr-0">
                   <div class="form-group ">
                      <div class="form-group">
                         <span class="time">:</span>
                         <input type="text" class="form-control ms" name="movement_time_m" value="{{$time[1]}}" required>
                      </div>
                   </div>
                </div>
                <div class="col-md-2 pr-0">
                   <div class="form-group">
                      <span class="time">:</span>
                      <input type="text" class="form-control ms" name="movement_time_s" value="{{$time[2]}}" required>
                   </div>
                </div>
                <a href="#" class="close close-movement-section" data-dismiss="alert" aria-label="close">&times;</a>
             </div>
              @endforeach
              @else
             <div class="add-movement-section col-md-12 alert">
                <div class="col-md-6 pr-0">
                   <div class="form-group ">
                      <input type="text" class="form-control" name="movement_name" value="" placeholder="Enter movement" required>
                   </div>
                </div>
                <div class="col-md-2 pr-0">
                   <div class="form-group ">
                      <input type="text" class="form-control" name="movement_time_h" value="00" required>
                   </div>
                </div>
                <div class="col-md-2 pr-0">
                   <div class="form-group ">
                      <div class="form-group">
                         <span class="time">:</span>
                         <input type="text" class="form-control ms" name="movement_time_m" value="00" required>
                      </div>
                   </div>
                </div>
                <div class="col-md-2 pr-0">
                   <div class="form-group">
                      <span class="time">:</span>
                      <input type="text" class="form-control ms" name="movement_time_s" value="00" required>
                   </div>
                </div>
                <!-- <a href="#" class="close close-movement-section" data-dismiss="alert" aria-label="close">&times;</a> -->
             </div>
             @endif
          </div>
       </div>
    </fieldset>
    <fieldset class="padding-15" style="display:none;">
       <legend>
          Video Duration
       </legend>
       <div class="form-group {{ $errors->has('video_duration') ? 'has-error' : ''}}">
          <div>
             <input id="appt-time" type="time" name="video_duration" step="2" class="form-control" value="{{isset($video)?$video->video_duration:"00:00:00"}}" readonly>
             {!! $errors->first('video_duration', '
             <p class="help-block">:message</p>
             ') !!}
          </div>
       </div>
    </fieldset>
    {{-- End exercise video --}}
    <div class="row">
       <div class="col-sm-12 col-xs-11">
          <div class="form-group">
             <button type="button" class="btn btn-primary btn-wide pull-left saveVideoBtn">
             @if(isset($video))
             <i class="fa fa-edit"></i> Update Video
             @else
             <i class="fa fa-plus"></i> Add Video
             @endif
             </button>
          </div>
       </div>
    </div>
 </div>
{!! Form::close() !!}