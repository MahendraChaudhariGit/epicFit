<style type="text/css">
    #movementModal .nav-tabs > li.active a,#movementModal  .nav-tabs > li.active a:hover,#movementModal .nav-tabs > li.active a:focus{
        border-width: 1px;
    }
    .video-btn a{
        background: #253746;
        color: white;
    }
    .video-btn a:hover{
        background-color: #ff4401 !important;
    }
    @media(max-width: 991px){
        .dm-none{
            display: none !important;
        }
        .col-md-8>div{
            margin-bottom: 20px;
        }
        .viewTab{
            display: flex;
        }

    .btn{
        padding: 6px 8px;
    }
    }
    .front-video, .side-video{
        padding-right: 0px !important;
        padding-left: 0px !important;
    }
    .front-video .tab-pane, .side-video .tab-pane{
        padding-right: 0px !important;
        padding-left: 0px !important;
    }
 
    @media (min-width: 992px){
        #movementModal .modal-lg {
            width: 80%;

        }
    }
    .upload-btn-wrapper {
      position: relative;
      overflow: hidden;
      display: inline-block;
  }

  .upload-btn-wrapper input[type=file] {
      font-size: 28px;
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
  }
  .video-btn a, .upload-btn-wrapper .btn{
        background: #253746;
        color: white;
        cursor: pointer;
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 12px;
    }
    .video-btn a:hover,.upload-btn-wrapper .btn:hover{
        background-color: #ff4401 !important;
    }
</style>

<div class="modal fade" id="movementModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-uppercase">Movement</h4>
                <p>Please choose the area by selecting with the mouse or by using the drop down menu </p>
            </div>
            <div class="modal-body white-bg">
              <form action="#" role="form" class="smart-wizard" id="movement-form" novalidate="novalidate">
                {!! Form::hidden('stepName') !!}
                {!! Form::hidden('movementId') !!}
                <?php /*<input type="hidden" name="businessplan_id" value="{{($businessplan)?$businessplan->bp_id:0}}" /> */ ?>
                <div id="movementWizard" class="swMain movement_wizard" >
                    <!-- start: WIZARD SEPS -->
                    <ul class="anchor custom-anchor" id="stepHedding">
                        <!-- <li>
                            <a href="#movement-step-1" class="selected" isdone="1" rel="1">
                                <div class="stepNumber">
                                    1
                                </div>
                                <span class="stepDesc"><small> Squat </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#movement-step-2" class="disabled" isdone="0" rel="2">
                                <div class="stepNumber">
                                    2
                                </div>
                                <span class="stepDesc"> <small> Lunge </small></span>
                            </a>
                        </li> -->
                    </ul>
                    
            <div class="stepContainer">
                    <div id="movement-step-1" class="content step-Squat hidden move-content" data-group='squat_ex'>
                       <input type="hidden" name="sqFront" class="front">
                        <input type="hidden" name="sqBack" class="side"> 
                        <!-- <div class="sucMes hidden">
                            {!! displayAlert()!!}
                        </div> -->
                        <fieldset class="padding-15" data-stepname="Squat">
                            <legend>Squat Express</legend>
                            {{-- <button type="button" id="btn-start-recording">Start Recording</button> --}}
                            {{-- <button type="button" id="btn-stop-recording" disabled>Save Recording</button> --}}
                            {{-- <video controls  playsinline loop></video> --}}
                            <div class="row">
                                <div class="col-md-8 move-check">
                                  
                                    <div class="">
                                      <ul class="nav nav-tabs viewTab">
                                        <li class="dm-none active"><a data-toggle="tab" href="#image-view" data-side="image">Image view</a></li>
                                        <li class="front-tab"><a data-toggle="tab" href="#front-video" data-side="front">Front video</a></li>
                                        <li class=""><a data-toggle="tab" href="#side-video" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="image-view" class="dm-none tab-pane fade in active">
                                          @if($parq->gender == 'Male')
                                          <img src="{{ asset('bodytool/male/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @else
                                          <img src="{{ asset('bodytool/female/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @endif
                                      </div>
                                      <div id="front-video" class="front-video tab-pane fade">
                                         <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-video btn-start-recording" href="#record-video" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-video">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                               
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-video hidden" onclick="removeVideo(this,'video')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-video" class="tab-pane fade">
                                               <video width="100%" class="recordedVideo"  controls  playsinline loop>
                                                </video>
                                               {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                               <button type="button" class="btn btn-primary btn-stop-recording" data-hide="video">Save Recording</button>
                                            </div>
                                            <div id="upload-video" class="uploadTabActive tab-pane">
                                                <input type="file" onChange="fileSelectHandlerVideo(this,'video')" accept="video/*" style="display:none;"  name="fileToUpload"/>
                                                <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                                </video>
                                                
                                                {{-- <button type="button" class="btn btn-primary uploadVideo">Save</button> --}}

                                            </div>
                                        </div>
                                    </div>
                                      <div id="side-video" class="side-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-side-video btn-start-recording" href="#record-side-video" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-side-video">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                                
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-side-video hidden" onclick="removeVideo(this,'side-video')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-side-video" class="tab-pane fade">
                                                <video width="100%" class="recordedVideo" controls  playsinline loop>
                                                    </video>
                                                {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                                <button type="button" class="btn btn-primary btn-stop-recording" data-hide="side-video">Save Recording</button>
                                            </div>
                                            <div id="upload-side-video" class="uploadTabActive tab-pane">
                                                <input type="file" onChange="fileSelectHandlerVideo(this,'side-video')" style="display:none;" accept="video/*"  name="fileToUpload"/>
                                                <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                </video>
                                                
                                            </div>
                                         </div>
                                      </div>


                                  </div>
                                       
                                    </div>
                                    <div class="form-group hidden-md hidden-lg">
                                        {!! Form::label('squat', 'Body view', ['class' => 'strong']) !!}
                                        <select class="form-control bodyPartsDd" id="squat">
                                            <option data-part="lower">Lower</option>
                                            <option data-part="upper">Upper</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lower injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Squat Express</h4>
                                            <div class="text-uppercase">Lower Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_FSWidth" value="Y_FSWidth" name="squatLower0" data-score="0"/> 
                                                    <label for="Y_FSWidth" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatLower0" value="N_FSWidth" id="N_FSWidth" data-score="1"/> 
                                                    <label for="N_FSWidth" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Shoulder Width
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_FNeutral" value="Y_FNeutral" name="squatLower1" data-score="0" />
                                                    <label for="Y_FNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatLower1" value="N_FNeutral" id="N_FNeutral" data-score="1"/>
                                                    <label for="N_FNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_AStable" value="Y_AStable" name="squatLower2" data-score="0"/> 
                                                    <label for="Y_AStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatLower2" value="N_AStable" id="N_AStable" data-score="2"/> 
                                                    <label for="N_AStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Ankles Stable
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_KStable" value="Y_KStable" name="squatLower3" data-score="0" data-reducescore="5"/> 
                                                    <label for="Y_KStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatLower3" value="N_KStable" id="N_KStable" data-score="2" data-reducescore="5"/> 
                                                    <label for="N_KStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Stable
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Lower Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_FWDN" value="Y_FWDN" name="squatLower4" data-score="0"/> 
                                                    <label for="Y_FWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatLower4" value="N_FWDN" id="N_FWDN" data-score="2"/> 
                                                    <label for="N_FWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Wt Distributed Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_KRLimited" value="Y_KRLimited" name="squatLower5" data-score="1"/> 
                                                    <label for="Y_KRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatLower5" value="N_KRLimited" id="N_KRLimited" data-score="0"/> 
                                                    <label for="N_KRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees ROM Limited
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="SQ_lowarOther" value="SQ_lowarOther" name="squatLower6" /> 
                                                    <label for="SQ_lowarOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesSELowerMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesSELowerMovement" name="notesSELowerMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="upper injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Squat Express</h4>
                                            <div class="text-uppercase">Upper Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_TInstable" value="Y_TInstable" name="squatUpper0" data-score="0"/> 
                                                    <label for="Y_TInstable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatUpper0" value="N_TInstable" id="N_TInstable" data-score="0" data-reducescore="5"/> 
                                                    <label for="N_TInstable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Instability
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_TRotation" value="Y_TRotation" name="squatUpper1" data-score="0"/> 
                                                    <label for="Y_TRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatUpper1" value="N_TRotation" id="N_TRotation"  data-score="0"/> 
                                                    <label for="N_TRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Rotation
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_FHNeutral" value="Y_FHNeutral" name="squatUpper2" data-score="0"/> 
                                                    <label for="Y_FHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatUpper2" value="N_FHNeutral" id="N_FHNeutral" data-score="1"/> 
                                                    <label for="N_FHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Upper Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_HiNeutral" value="Y_HiNeutral" name="squatUpper3" data-score="0"/> 
                                                    <label for="Y_HiNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatUpper3" value="N_HiNeutral" id="N_HiNeutral" data-score="3"/> 
                                                    <label for="N_HiNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BNeutral" value="Y_BNeutral" name="squatUpper4" data-score="0"/> 
                                                    <label for="Y_BNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatUpper4" value="N_BNeutral" id="N_BNeutral" data-score="3"/> 
                                                    <label for="N_BNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Back Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_SHNeutral" value="Y_SHNeutral" name="squatUpper5" data-score="0"/> 
                                                    <label for="Y_SHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="squatUpper5" value="N_SHNeutral" id="N_SHNeutral" data-score="1"/> 
                                                    <label for="N_SHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="SQ_upperOther" value="SQ_upperOther" name="squatUpper6" /> 
                                                    <label for="SQ_upperOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesSEUpperMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesSEUpperMovement" name="notesSEUpperMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary movement-body-toggle hidden-xs hidden-sm">Upper Body</button>
                                    </div>

                                    <div class="form-group">
                                        <div class="progress custom-progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <strong><span class="scoreStyle m-r-30">SCORE : <span class="scoreCard">0</span></span></strong>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- <div class="row">
                            <div class="col-sm-12">
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                    <input type="checkbox" name="noInjury" id="noInjury" class="closeModal" value="1" {{ $parq->noInjury == 1?'checked':'' }}>
                                    <label for="noInjury">
                                        <strong>No Injury</strong>
                                    </label>
                                </div>
                                <button class="btn btn-primary btn-o next-step btn-wide pull-right"> Next <i class="fa fa-arrow-circle-right"></i> </button>
                                <button class="btn btn-primary submit-step btn-wide pull-right margin-right-15" data-step="1"> Save </button>
                            </div>
                        </div> -->
                    </div>

                    <div id="movement-step-2" class="content step-Lunge move-content" data-group='lunge_ex'>
                       <input type="hidden" name="luFront" class="front">
                        <input type="hidden" name="luBack" class="side"> 
                        <fieldset class="padding-15" data-stepname="Lunge">
                            <legend>Lunge Express</legend>
                            <div class="row">
                                <div class="col-md-8 move-check">
                                    <div class="">
                                      <ul class="nav nav-tabs viewTab">
                                        <li class="dm-none active"><a data-toggle="tab" href="#image-view1" data-side="image">Image view</a></li>
                                        <li class="front-tab"><a data-toggle="tab" href="#front-video1" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video1" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="image-view1" class="dm-none tab-pane fade in active">
                                          @if($parq->gender == 'Male')
                                          <img src="{{ asset('bodytool/male/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @else
                                          <img src="{{ asset('bodytool/female/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @endif
                                      </div>
                                      <div id="front-video1" class="front-video tab-pane fade">
                                         <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-video1 btn-start-recording" href="#record-video1" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-video1">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-video1 hidden" onclick="removeVideo(this,'video1')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-video1" class="tab-pane fade">
                                                <video width="100%" class="recordedVideo" controls  playsinline loop>                                      </video>
                                      {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                      <button type="button" class="btn btn-primary btn-stop-recording" data-hide="video1">Save Recording</button>
                                          </div>
                                          <div id="upload-video1" class="uploadTabActive tab-pane">
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video1')" accept="video/*" style="display:none;"  name="fileToUpload"/>

                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            
                                          </div>
                                      </div>
                                         
                                      </div>
                                      <div id="side-video1" class="side-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-side-video1 btn-start-recording" href="#record-side-video1" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-side-video1">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-side-video1 hidden" onclick="removeVideo(this,'side-video1')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                                <div id="record-side-video1" class="tab-pane fade">
                                                    <video width="100%" class="recordedVideo" controls  playsinline loop>
                                                        </video>
                                                    {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                                    <button type="button" class="btn btn-primary btn-stop-recording" data-hide="side-video1">Save Recording</button>
                                                </div>
                                                <div id="upload-side-video1" class="uploadTabActive tab-pane">
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video1')" style="display:none;" accept="video/*"  name="fileToUpload"/>

                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
                                    </div>
                                    <div class="form-group hidden-md hidden-lg">
                                        {!! Form::label('lunge', 'Body view', ['class' => 'strong']) !!}
                                        <select class="form-control bodyPartsDd" id="lunge">
                                            <option data-part="lower">Lower</option>
                                            <option data-part="upper">Upper</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lower injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Lunge Express</h4>
                                            <div class="text-uppercase">Lower Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LFNeutral" value="Y_LFNeutral" name="lungeLower0" data-score="0" /> 
                                                    <label for="Y_LFNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeLower0" value="N_LFNeutral" id="N_LFNeutral" data-score="2"/> 
                                                    <label for="N_LFNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LAStable" value="Y_LAStable" name="lungeLower1" data-score="0" /> 
                                                    <label for="Y_LAStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeLower1" value="N_LAStable" id="N_LAStable" data-score="2" /> 
                                                    <label for="N_LAStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Ankles Stable
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LKStable" value="Y_LKStable" name="lungeLower2" data-score="0" /> 
                                                    <label for="Y_LKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeLower2" value="N_LKStable" id="N_LKStable" data-score="2" /> 
                                                    <label for="N_LKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Stable
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Lower Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LFWDN" value="Y_LFWDN" name="lungeLower3" data-score="0" /> 
                                                    <label for="Y_LFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeLower3" value="N_LFWDN" id="N_LFWDN" data-score="2" /> 
                                                    <label for="N_LFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Wt Distributed Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LKRLimited" value="Y_LKRLimited" name="lungeLower4" data-score="1"  /> 
                                                    <label for="Y_LKRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeLower4" value="N_LKRLimited" id="N_LKRLimited" data-score="0"  /> 
                                                    <label for="N_LKRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees ROM Limited
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LKDecelerated" value="Y_LKDecelerated" name="lungeLower5" data-score="0" /> 
                                                    <label for="Y_LKDecelerated" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeLower5" value="N_LKDecelerated" id="N_LKDecelerated" data-score="3" /> 
                                                    <label for="N_LKDecelerated" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Deceleration
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="LU_lowerOther" value="LU_lowerOther" name="lungeLower6" /> 
                                                    <label for="LU_lowerOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesLELowerMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesLELowerMovement" name="notesLELowerMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="upper injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Lunge Express</h4>
                                            <div class="text-uppercase">Upper Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LTInstable" value="Y_LTInstable" name="lungeUpper0" data-score="2"/> 
                                                    <label for="Y_LTInstable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeUpper0" value="N_LTInstable" id="N_LTInstable" data-score="0"/> 
                                                    <label for="N_LTInstable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Instability
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LTRotation" value="Y_LTRotation" name="lungeUpper1" data-score="2"/> 
                                                    <label for="Y_LTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeUpper1" value="N_LTRotation" id="N_LTRotation" data-score="0"/> 
                                                    <label for="N_LTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Rotation
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LFHNeutral" value="Y_LFHNeutral" name="lungeUpper2" data-score="0"/> 
                                                    <label for="Y_LFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeUpper2" value="N_LFHNeutral" id="N_LFHNeutral" data-score="1" /> 
                                                    <label for="N_LFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Upper Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LHiNeutral" value="Y_LHiNeutral" name="lungeUpper3" data-score="0"/> 
                                                    <label for="Y_LHiNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeUpper3" value="N_LHiNeutral" id="N_LHiNeutral" data-score="2"/> 
                                                    <label for="N_LHiNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LBNeutral" value="Y_LBNeutral" name="lungeUpper4" data-score="0"/> 
                                                    <label for="Y_LBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeUpper4" value="N_LBNeutral" id="N_LBNeutral" data-score="2"/> 
                                                    <label for="N_LBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Back Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_LSHNeutral" value="Y_LSHNeutral" name="lungeUpper5" data-score="0"/> 
                                                    <label for="Y_LSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="lungeUpper5" value="N_LSHNeutral" id="N_LSHNeutral" data-score="1"/> 
                                                    <label for="N_LSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="LU_upperOther" value="LU_upperOther" name="lungeUpper6" /> 
                                                    <label for="LU_upperOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesLEUpperMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesLEUpperMovement" name="notesLEUpperMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary movement-body-toggle hidden-xs hidden-sm">Upper Body</button>
                                    </div>

                                    <div class="form-group">
                                        <div class="progress custom-progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <strong><span class="scoreStyle m-r-30">SCORE : <span class="scoreCard">0</span></span></strong>
                                    </div>

                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div id="movement-step-3" class="content step-Bend move-content" data-group='bend_ex'>
                       <input type="hidden" name="beFront" class="front">
                        <input type="hidden" name="beBack" class="side"> 
                        <fieldset class="padding-15" data-stepname="Bend">
                            <legend>Bend Express</legend>
                            <div class="row">
                                <div class="col-md-8 move-check">
                                    <div class="">
                                      <ul class="nav nav-tabs viewTab">
                                        <li class="dm-none active"><a data-toggle="tab" href="#image-view2" data-side="image">Image view</a></li>
                                        <li class="front-tab"><a data-toggle="tab" href="#front-video2" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video2" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="image-view2" class="dm-none tab-pane fade in active">
                                          @if($parq->gender == 'Male')
                                          <img src="{{ asset('bodytool/male/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @else
                                          <img src="{{ asset('bodytool/female/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @endif
                                      </div>
                                      <div id="front-video2" class="front-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-video2 btn-start-recording" href="#record-video2" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-video2">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-video2 hidden" onclick="removeVideo(this,'video2')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-video2" class="tab-pane fade">
                                                <video width="100%" class="recordedVideo" controls  playsinline loop>                                      </video>
                                      {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                      <button type="button" class="btn btn-primary btn-stop-recording" data-hide="video2">Save Recording</button>
                                          </div>
                                          <div id="upload-video2" class="uploadTabActive tab-pane">
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video2')" accept="video/*" style="display:none;"  name="fileToUpload"/>

                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            
                                        </div>
                                      </div>
                                      </div>
                                      <div id="side-video2" class="side-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-side-video2 btn-start-recording" href="#record-side-video2" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-side-video2">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-side-video2 hidden" onclick="removeVideo(this,'side-video2')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                                <div id="record-side-video2" class="tab-pane fade">
                                                    <video width="100%" class="recordedVideo" controls  playsinline loop>
                                                        </video>
                                                    {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                                    <button type="button" class="btn btn-primary btn-stop-recording" data-hide="side-video2">Save Recording</button>
                                                </div>
                                                <div id="upload-side-video2" class="uploadTabActive tab-pane">
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video2')" style="display:none;" accept="video/*"  name="fileToUpload"/>

                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
                                    </div>
                                    <div class="form-group hidden-md hidden-lg">
                                        {!! Form::label('bend', 'Body view', ['class' => 'strong']) !!}
                                        <select class="form-control bodyPartsDd" id="bend">
                                            <option data-part="lower">Lower</option>
                                            <option data-part="upper">Upper</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lower injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Bend Express</h4>
                                            <div class="text-uppercase">Lower Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BFSWidth" value="Y_BFSWidth" name="bendLower0" data-score="0"/> 
                                                    <label for="Y_BFSWidth" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendLower0" value="N_BFSWidth" id="N_BFSWidth" data-score="1"/> 
                                                    <label for="N_BFSWidth" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Shoulder Width
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BFNeutral" value="Y_BFNeutral" name="bendLower1" data-score="0" data-reducescore="5"/> 
                                                    <label for="Y_BFNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendLower1" value="N_BFNeutral" id="N_BFNeutral" data-score="1" data-reducescore="5"/> 
                                                    <label for="N_BFNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BKStable" value="Y_BKStable" name="bendLower2" data-score="0" data-reducescore="5"/> 
                                                    <label for="Y_BKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendLower2" value="N_BKStable" id="N_BKStable" data-score="2" data-reducescore="5"/> 
                                                    <label for="N_BKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Stable
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Lower Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BFWDN" value="Y_BFWDN" name="bendLower3" data-score="0"/> 
                                                    <label for="Y_BFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendLower3" value="N_BFWDN" id="N_BFWDN" data-score="2"/> 
                                                    <label for="N_BFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Wt Distributed Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_KNeutral" value="Y_KNeutral" name="bendLower4" data-score="0" /> 
                                                    <label for="Y_KNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendLower4" value="N_KNeutral" id="N_KNeutral" data-score="2" /> 
                                                    <label for="N_KNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Neutral
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="BE_lowerOther" value="BE_lowerOther" name="bendLower5" /> 
                                                    <label for="BE_lowerOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesBELowerMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesBELowerMovement" name="notesBELowerMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="upper injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Bend Express</h4>
                                            <div class="text-uppercase">Upper Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BSRLimited" value="Y_BSRLimited" name="bendUpper0" data-score="1" /> 
                                                    <label for="Y_BSRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper0" value="N_BSRLimited" id="N_BSRLimited" data-score="0" /> 
                                                    <label for="N_BSRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Shoulders ROM Limited
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BSNeutral" value="Y_BSNeutral" name="bendUpper1" data-score="0" /> 
                                                    <label for="Y_BSNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper1" value="N_BSNeutral" id="N_BSNeutral" data-score="1" /> 
                                                    <label for="N_BSNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Shoulders Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BTRotation" value="Y_BTRotation" name="bendUpper2" data-score="2" /> 
                                                    <label for="Y_BTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper2" value="N_BTRotation" id="N_BTRotation" data-score="0" /> 
                                                    <label for="N_BTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Rotation
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BFHNeutral" value="Y_BFHNeutral" name="bendUpper3" data-score="0"/> 
                                                    <label for="Y_BFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper3" value="N_BFHNeutral" id="N_BFHNeutral" data-score="1"/> 
                                                    <label for="N_BFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Upper Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BHSBack" value="Y_BHSBack" name="bendUpper4" data-score="0" /> 
                                                    <label for="Y_BHSBack" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper4" value="N_BHSBack" id="N_BHSBack" data-score="3"/> 
                                                    <label for="N_BHSBack" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips Slide Back
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BHRLimited" value="Y_BHRLimited" name="bendUpper5" data-score="3"/> 
                                                    <label for="Y_BHRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper5" value="N_BHRLimited" id="N_BHRLimited" data-score="0"/> 
                                                    <label for="N_BHRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips ROM Limited
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BBNeutral" value="Y_BBNeutral" name="bendUpper6" data-score="0"/> 
                                                    <label for="Y_BBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper6" value="N_BBNeutral" id="N_BBNeutral" data-score="3"/> 
                                                    <label for="N_BBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Back Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_BSHNeutral" value="Y_BSHNeutral" name="bendUpper7" data-score="0" /> 
                                                    <label for="Y_BSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="bendUpper7" value="N_BSHNeutral" id="N_BSHNeutral" data-score="2"/> 
                                                    <label for="N_BSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="BE_upperOther" value="BE_upperOther" name="bendUpper8" /> 
                                                    <label for="BE_upperOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesBEUpperMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesBEUpperMovement" name="notesBEUpperMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary movement-body-toggle hidden-xs hidden-sm">Upper Body</button>
                                    </div>

                                    <div class="form-group">
                                        <div class="progress custom-progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <strong><span class="scoreStyle m-r-30">SCORE : <span class="scoreCard">0</span></span></strong>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div id="movement-step-4" class="content step-Pull move-content" data-group='pull_ex'>
                       <input type="hidden" name="puFront" class="front">
                        <input type="hidden" name="puBack" class="side"> 
                        <fieldset class="padding-15" data-stepname="Pull">
                            <legend>Pull Express</legend>
                            <div class="row">
                                <div class="col-md-8 move-check">
                                    <div class="">
                                      <ul class="nav nav-tabs viewTab">
                                        <li class="dm-none active"><a data-toggle="tab" href="#image-view3" data-side="image">Image view</a></li>
                                        <li class="front-tab"><a data-toggle="tab" href="#front-video3" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video3" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="image-view3" class="dm-none tab-pane fade in active">
                                           @if($parq->gender == 'Male')
                                          <img src="{{ asset('bodytool/male/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @else
                                          <img src="{{ asset('bodytool/female/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @endif
                                      </div>
                                      <div id="front-video3" class="front-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-video3 btn-start-recording" href="#record-video3" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-video3">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-video3 hidden" onclick="removeVideo(this,'video3')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-video3" class="tab-pane fade">
                                                <video width="100%" class="recordedVideo" controls  playsinline loop>                                      </video>
                                      {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                      <button type="button" class="btn btn-primary btn-stop-recording" data-hide="video3">Save Recording</button>
                                          </div>
                                          <div id="upload-video3" class="uploadTabActive tab-pane">
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video3')" accept="video/*" style="display:none;" name="fileToUpload"/>

                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            
                                        </div>
                                      </div>
                                      </div>
                                      <div id="side-video3" class="side-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-side-video3 btn-start-recording" href="#record-side-video3" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-side-video3">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-side-video3 hidden" onclick="removeVideo(this,'side-video3')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                                <div id="record-side-video3" class="tab-pane fade">
                                                    <video width="100%" class="recordedVideo" controls  playsinline loop>
                                                        </video>
                                                    {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                                    <button type="button" class="btn btn-primary btn-stop-recording" data-hide="side-video3">Save Recording</button>
                                                </div>
                                                <div id="upload-side-video3" class="uploadTabActive tab-pane">
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video3')" style="display:none;" accept="video/*"  name="fileToUpload"/>

                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
                                    </div>
                                    <div class="form-group hidden-md hidden-lg">
                                        {!! Form::label('pull', 'Body view', ['class' => 'strong']) !!}
                                        <select class="form-control bodyPartsDd" id="pull">
                                            <option data-part="lower">Lower</option>
                                            <option data-part="upper">Upper</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lower injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Pull Express</h4>
                                            <div class="text-uppercase">Lower Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PFSWidth" value="Y_PFSWidth" name="pullLower0" data-score="0"/> 
                                                    <label for="Y_PFSWidth" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullLower0" value="N_PFSWidth" id="N_PFSWidth" data-score="1"/> 
                                                    <label for="N_PFSWidth" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Shoulder Width
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PKStable" value="Y_PKStable" name="pullLower1" data-score="0"/> 
                                                    <label for="Y_PKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullLower1" value="N_PKStable" id="N_PKStable" data-score="2"/> 
                                                    <label for="N_PKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Stable
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Lower Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PFWDN" value="Y_PFWDN" name="pullLower2" data-score="0" /> 
                                                    <label for="Y_PFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullLower2" value="N_PFWDN" id="N_PFWDN" data-score="2"/> 
                                                    <label for="N_PFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Wt Distributed Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PKNSBend" value="Y_PKNSBend" name="pullLower3" data-score="0"/> 
                                                    <label for="Y_PKNSBend" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullLower3" value="N_PKNSBend" id="N_PKNSBend" data-score="1"/> 
                                                    <label for="N_PKNSBend" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Neutral Soft Bend
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="PUL_lowerOther" value="PUL_lowerOther" name="pullLower4" /> 
                                                    <label for="PUL_lowerOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesPELowerMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesPELowerMovement" name="notesPELowerMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="upper injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Pull Express</h4>
                                            <div class="text-uppercase">Upper Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PSRRom" value="Y_PSRRom" name="pullUpper0" data-score="1" /> 
                                                    <label for="Y_PSRRom" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper0" value="N_PSRRom" id="N_PSRRom" data-score="0" /> 
                                                    <label for="N_PSRRom" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Scapula Retraction ROM
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PESBalanced" value="Y_PESBalanced" name="pullUpper1" data-score="0" /> 
                                                    <label for="Y_PESBalanced" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper1" value="N_PESBalanced" id="N_PESBalanced" data-score="3" /> 
                                                    <label for="N_PESBalanced" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Elbow/Shoulders Balanced
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PHRotation" value="Y_PHRotation" name="pullUpper2" data-score="2" /> 
                                                    <label for="Y_PHRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper2" value="N_PHRotation" id="N_PHRotation" data-score="0" /> 
                                                    <label for="N_PHRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hip Rotation
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PTRotation" value="Y_PTRotation" name="pullUpper3" data-score="2"/> 
                                                    <label for="Y_PTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper3" value="N_PTRotation" id="N_PTRotation" data-score="0" /> 
                                                    <label for="N_PTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Rotation
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PFHNeutral" value="Y_PFHNeutral" name="pullUpper4" data-score="0" /> 
                                                    <label for="Y_PFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper4" value="N_PFHNeutral" id="N_PFHNeutral" data-score="1" /> 
                                                    <label for="N_PFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Upper Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PHSBack" value="Y_PHSBack" name="pullUpper5" data-score="0" /> 
                                                    <label for="Y_PHSBack" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper5" value="N_PHSBack" id="N_PHSBack" data-score="1" /> 
                                                    <label for="N_PHSBack" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips Slide Back
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PHRLimited" value="Y_PHRLimited" name="pullUpper6" data-score="1" /> 
                                                    <label for="Y_PHRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper6" value="N_PHRLimited" id="N_PHRLimited" data-score="0"/> 
                                                    <label for="N_PHRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips ROM Limited
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PBNeutral" value="Y_PBNeutral" name="pullUpper7" data-score="0" /> 
                                                    <label for="Y_PBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper7" value="N_PBNeutral" id="N_PBNeutral" data-score="3" /> 
                                                    <label for="N_PBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Back Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_PSHNeutral" value="Y_PSHNeutral" name="pullUpper8" data-score="0" /> 
                                                    <label for="Y_PSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pullUpper8" value="N_PSHNeutral" id="N_PSHNeutral" data-score="2" /> 
                                                    <label for="N_PSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="PUL_upperOther" value="PUL_upperOther" name="pullUpper9" /> 
                                                    <label for="PUL_upperOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesPEUpperMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesPEUpperMovement" name="notesPEUpperMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary movement-body-toggle hidden-xs hidden-sm">Upper Body</button>
                                    </div>

                                    <div class="form-group">
                                        <div class="progress custom-progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <strong><span class="scoreStyle m-r-30">SCORE : <span class="scoreCard">0</span></span></strong>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div id="movement-step-5" class="content step-Push move-content" data-group='push_ex'>
                       <input type="hidden" name="pusFront" class="front">
                        <input type="hidden" name="pusBack" class="side"> 
                        <fieldset class="padding-15" data-stepname="Push">
                            <legend>Push Express</legend>
                            <div class="row">
                                <div class="col-md-8 move-check">
                                    <div class="">
                                      <ul class="nav nav-tabs viewTab">
                                        <li class="dm-none active"><a data-toggle="tab" href="#image-view4" data-side="image">Image view</a></li>
                                        <li class="front-tab"><a data-toggle="tab" href="#front-video4" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video4" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="image-view4" class="dm-none tab-pane fade in active">
                                           @if($parq->gender == 'Male')
                                          <img src="{{ asset('bodytool/male/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @else
                                          <img src="{{ asset('bodytool/female/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @endif
                                      </div>
                                      <div id="front-video4" class="front-video tab-pane fade">
                                         <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-video4 btn-start-recording" href="#record-video4" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-video4">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-video4 hidden" onclick="removeVideo(this,'video4')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-video4" class="tab-pane fade">
                                                <video width="100%" class="recordedVideo" controls  playsinline loop>                                      </video>
                                      {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                      <button type="button" class="btn btn-primary btn-stop-recording" data-hide="video4">Save Recording</button>
                                          </div>
                                          <div id="upload-video4" class="uploadTabActive tab-pane">
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video4')" accept="video/*" style="display:none;" name="fileToUpload"/>

                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            
                                        </div>
                                      </div>
                                      </div>
                                      <div id="side-video4" class="side-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-side-video4 btn-start-recording" href="#record-side-video4" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-side-video4">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-side-video4 hidden" onclick="removeVideo(this,'side-video4')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                                <div id="record-side-video4" class="tab-pane fade">
                                                    <video width="100%" class="recordedVideo" controls  playsinline loop>
                                                        </video>
                                                    {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                                    <button type="button" class="btn btn-primary btn-stop-recording" data-hide="side-video4">Save Recording</button>
                                                </div>
                                                <div id="upload-side-video4" class="uploadTabActive tab-pane">
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video4')" style="display:none;" accept="video/*"  name="fileToUpload"/>

                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
                                    </div>
                                    <div class="form-group hidden-md hidden-lg">
                                        {!! Form::label('push', 'Body view', ['class' => 'strong']) !!}
                                        <select class="form-control bodyPartsDd" id="push">
                                            <option data-part="lower">Lower</option>
                                            <option data-part="upper">Upper</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lower injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Push Express</h4>
                                            <div class="text-uppercase">Lower Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UFTogether" value="Y_UFTogether" name="pushLower0" data-score="0" /> 
                                                    <label for="Y_UFTogether" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushLower0" value="N_UFTogether" id="N_UFTogether" data-score="1" /> 
                                                    <label for="N_UFTogether" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Together
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UHTogether" value="Y_UHTogether" name="pushLower1" data-score="0" /> 
                                                    <label for="Y_UHTogether" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushLower1" value="N_UHTogether" id="N_UHTogether" data-score="1" /> 
                                                    <label for="N_UHTogether" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Heels Together
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Lower Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UKNStraight" value="Y_UKNStraight" name="pushLower2" data-score="0" /> 
                                                    <label for="Y_UKNStraight" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushLower2" value="N_UKNStraight" id="N_UKNStraight" data-score="1" /> 
                                                    <label for="N_UKNStraight" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Neutral Straight
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="PUS_lowerOther" value="PUS_lowerOther" name="pushLower3" /> 
                                                    <label for="PUS_lowerOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesUELowerMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesUELowerMovement" name="notesUELowerMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="upper injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Push Express</h4>
                                            <div class="text-uppercase">Upper Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_USRLimited" value="Y_USRLimited" name="pushUpper0" data-score="1" /> 
                                                    <label for="Y_USRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper0" value="N_USRLimited" id="N_USRLimited" data-score="0" /> 
                                                    <label for="N_USRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Scapula Ret ROM Limited
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UESAngle" value="Y_UESAngle" name="pushUpper1" data-score="0" /> 
                                                    <label for="Y_UESAngle" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper1" value="N_UESAngle" id="N_UESAngle" data-score="2" /> 
                                                    <label for="N_UESAngle" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Elbow/Shoulder Angle
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UHRotation" value="Y_UHRotation" name="pushUpper2" data-score="2" /> 
                                                    <label for="Y_UHRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper2" value="N_UHRotation" id="N_UHRotation" data-score="0" /> 
                                                    <label for="N_UHRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hip Rotation/Imbalance
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UTRotation" value="Y_UTRotation" name="pushUpper3" data-score="3" /> 
                                                    <label for="Y_UTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper3" value="N_UTRotation" id="N_UTRotation" data-score="0" /> 
                                                    <label for="N_UTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Rotation/Imbalance
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UFHNeutral" value="Y_UFHNeutral" name="pushUpper4" data-score="0" /> 
                                                    <label for="Y_UFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper4" value="N_UFHNeutral" id="N_UFHNeutral" data-score="1" /> 
                                                    <label for="N_UFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Upper Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UHNCE" value="Y_UHNCE" name="pushUpper5" data-score="0" /> 
                                                    <label for="Y_UHNCE" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper5" value="N_UHNCE" id="N_UHNCE" data-score="3" /> 
                                                    <label for="N_UHNCE" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips Neutral Core Engaged
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_UBNeutral" value="Y_UBNeutral" name="pushUpper6" data-score="0" /> 
                                                    <label for="Y_UBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper6" value="N_UBNeutral" id="N_UBNeutral" data-score="3" /> 
                                                    <label for="N_UBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Back Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_USHNeutral" value="Y_USHNeutral" name="pushUpper7" data-score="0"/> 
                                                    <label for="Y_USHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="pushUpper7" value="N_USHNeutral" id="N_USHNeutral" data-score="2"/> 
                                                    <label for="N_USHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="PUS_upperOther" value="PUS_upperOther" name="pushUpper8" /> 
                                                    <label for="PUS_upperOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesUEUpperMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesUEUpperMovement" name="notesUEUpperMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary movement-body-toggle hidden-xs hidden-sm">Upper Body</button>
                                    </div>

                                    <div class="form-group">
                                        <div class="progress custom-progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <strong><span class="scoreStyle m-r-30">SCORE : <span class="scoreCard">0</span></span></strong>
                                    </div>
                            </div>
                        </fieldset>
                    </div>

                    <div id="movement-step-6" class="content step-Rotation move-content" data-group='rotation_ex' >
                       <input type="hidden" name="roFront" class="front">
                        <input type="hidden" name="roBack" class="side"> 
                        <fieldset class="padding-15" data-stepname="Rotation">
                            <legend>Rotation Express</legend>
                            <div class="row">
                                <div class="col-md-8 move-check">
                                    <div class="">
                                      <ul class="nav nav-tabs viewTab">
                                        <li class="dm-none active"><a data-toggle="tab" href="#image-view5" data-side="image">Image view</a></li>
                                        <li class="front-tab"><a data-toggle="tab" href="#front-video5" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video5" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="image-view5" class="dm-none tab-pane fade in active">
                                             @if($parq->gender == 'Male')
                                          <img src="{{ asset('bodytool/male/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @else
                                          <img src="{{ asset('bodytool/female/movement.jpg') }}" usemap="#Map" class="body" width="600" />
                                        @endif
                                      </div>
                                      <div id="front-video5" class="front-video tab-pane fade">
                                       <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-video5 btn-start-recording" href="#record-video5" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-video5">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-video5 hidden" onclick="removeVideo(this,'video5')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-video5" class="tab-pane fade">
                                                <video width="100%" class="recordedVideo" controls  playsinline loop>                                      </video>
                                      {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                      <button type="button" class="btn btn-primary btn-stop-recording" data-hide="video5">Save Recording</button>
                                          </div>
                                          <div id="upload-video5" class="uploadTabActive tab-pane">
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video5')" accept="video/*" style="display:none;" name="fileToUpload"/>

                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            
                                        </div>
                                      </div>
                                      </div>
                                      <div id="side-video5" class="side-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-side-video5 btn-start-recording" href="#record-side-video5" data-video="record">Record video</a></li>
                                            <li class="video-btn"><div class="upload-btn-wrapper upload-side-video5">
                                                <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                            </div></li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-side-video5 hidden" onclick="removeVideo(this,'side-video5')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                                <div id="record-side-video5" class="tab-pane fade">
                                                    <video width="100%" class="recordedVideo" controls  playsinline loop>
                                                        </video>
                                                    {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                                    <button type="button" class="btn btn-primary btn-stop-recording" data-hide="side-video5">Save Recording</button>
                                                </div>
                                                <div id="upload-side-video5" class="uploadTabActive tab-pane">
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video5')" style="display:none;" accept="video/*"  name="fileToUpload"/>

                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
                                    </div>
                                    <div class="form-group hidden-md hidden-lg">
                                        {!! Form::label('rotation', 'Body view', ['class' => 'strong']) !!}
                                        <select class="form-control bodyPartsDd" id="rotation">
                                            <option data-part="lower">Lower</option>
                                            <option data-part="upper">Upper</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lower injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Rotation Express</h4>
                                            <div class="text-uppercase">Lower Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RFNeutral" value="Y_RFNeutral" name="rotationLower0" data-score="0" /> 
                                                    <label for="Y_RFNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationLower0" value="N_RFNeutral" id="N_RFNeutral" data-score="2" /> 
                                                    <label for="N_RFNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RAStable" value="Y_RAStable" name="rotationLower1" data-score="0" /> 
                                                    <label for="Y_RAStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationLower1" value="N_RAStable" id="N_RAStable" data-score="2" /> 
                                                    <label for="N_RAStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Ankles Stable
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RKStable" value="Y_RKStable" name="rotationLower2" data-score="0" /> 
                                                    <label for="Y_RKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationLower2" value="N_RKStable" id="N_RKStable" data-score="2" /> 
                                                    <label for="N_RKStable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Stable
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Lower Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RFWDN" value="Y_RFWDN" name="rotationLower3" data-score="0" /> 
                                                    <label for="Y_RFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationLower3" value="N_RFWDN" id="N_RFWDN" data-score="2" /> 
                                                    <label for="N_RFWDN" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Feet Wt Distribution
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RKRLimited" value="Y_RKRLimited" name="rotationLower4" data-score="1" /> 
                                                    <label for="Y_RKRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationLower4" value="N_RKRLimited" id="N_RKRLimited" data-score="0" /> 
                                                    <label for="N_RKRLimited" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees ROM Limited
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RKDecelerated" value="Y_RKDecelerated" name="rotationLower5" data-score="0" /> 
                                                    <label for="Y_RKDecelerated" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationLower5" value="N_RKDecelerated" id="N_RKDecelerated" data-score="3" /> 
                                                    <label for="N_RKDecelerated" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Knees Deceleration
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="RO_lowerOther" value="RO_lowerOther" name="rotationLower6" /> 
                                                    <label for="RO_lowerOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesRELowerMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesRELowerMovement" name="notesRELowerMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="upper injuryList hidden">
                                        <div class="form-group">
                                            <h4 class="text-uppercase">Rotation Express</h4>
                                            <div class="text-uppercase">Upper Body Front View</div>
                                            &nbsp;Y &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N<br/>
                                            <?php
                                            //if(!count($parq->footInjury))
                                            //    $parq->footInjury = [];
                                            ?>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RTInstable" value="Y_RTInstable" name="rotationUpper0" data-score="2" /> 
                                                    <label for="Y_RTInstable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationUpper0" value="N_RTInstable" id="N_RTInstable" data-score="0" /> 
                                                    <label for="N_RTInstable" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Instability
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RTRotation" value="Y_RTRotation" name="rotationUpper1" data-score="3" /> 
                                                    <label for="Y_RTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationUpper1" value="N_RTRotation" id="N_RTRotation" data-score="0" /> 
                                                    <label for="N_RTRotation" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Torso Rotation Limited
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RFHNeutral" value="Y_RFHNeutral" name="rotationUpper2" data-score="0" /> 
                                                    <label for="Y_RFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationUpper2" value="N_RFHNeutral" id="N_RFHNeutral" data-score="1" /> 
                                                    <label for="N_RFHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-uppercase">Upper Body Side View</div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RHiNeutral" value="Y_RHiNeutral" name="rotationUpper3" data-score="0" /> 
                                                    <label for="Y_RHiNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationUpper3" value="N_RHiNeutral" id="N_RHiNeutral" data-score="2" /> 
                                                    <label for="N_RHiNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Hips Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RBNeutral" value="Y_RBNeutral" name="rotationUpper4" data-score="0" /> 
                                                    <label for="Y_RBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationUpper4" value="N_RBNeutral" id="N_RBNeutral" data-score="2" /> 
                                                    <label for="N_RBNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Back Neutral
                                            </div>
                                            <div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" id="Y_RSHNeutral" value="Y_RSHNeutral" name="rotationUpper5" data-score="0" /> 
                                                    <label for="Y_RSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                <div class="radio clip-radio radio-primary radio-inline m-b-0 m-r-0">
                                                    <input type="radio" name="rotationUpper5" value="N_RSHNeutral" id="N_RSHNeutral" data-score="1" /> 
                                                    <label for="N_RSHNeutral" class="m-r-0 m-b-0"></label>
                                                </div>
                                                Head Neutral
                                            </div>
                                            <div>
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                                    <input type="checkbox" id="RO_upperOther" value="RO_upperOther" name="rotationUpper6" /> 
                                                    <label for="RO_upperOther" class="m-r-0"></label>
                                                </div>
                                                Other
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="strong" for="notesREUpperMovement">Please add the relevant notes relating to movement selected above</label>
                                            <textarea class="form-control" id="notesREUpperMovement" name="notesREUpperMovement"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary movement-body-toggle hidden-xs hidden-sm">Upper Body</button>
                                    </div>

                                    <div class="form-group">
                                        <div class="progress custom-progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <strong><span class="scoreStyle m-r-30">SCORE : <span class="scoreCard">0</span></span></strong>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                <!-- End: step-6  Management Summary  FORM WIZARD ACCORDION -->
                </div>
            </div>
                {!! Form::hidden('SquatStepVal') !!}
                {!! Form::hidden('LungeStepVal') !!}
                {!! Form::hidden('BendStepVal') !!}
                {!! Form::hidden('PullStepVal') !!}
                {!! Form::hidden('PushStepVal') !!}
                {!! Form::hidden('RotationStepVal') !!}
            </form>
            <!-- end: WIZARD FORM -->
                  
            </div>
            <div class="modal-footer">
              <!-- <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="noInjury" id="noInjury" class="closeModal" value="1" {{ $parq->noInjury == 1?'checked':'' }}>
                    <label for="noInjury">
                        <strong>No Injury</strong>
                    </label>
                </div> -->
              <button type="button" class="btn btn-primary btn-o back-step pull-left"> <i class="fa fa-arrow-circle-left"></i> Back </button>
              <button class="btn btn-primary save-draft btn-wide"> Save & Draft </button>
              <button type="button" class="btn btn-primary next-step act-btn">Next <i class="fa fa-arrow-circle-right"></i></button>
              <button type="button" class="btn btn-primary finish-btn hidden">Finish</button>
            </div>
    </div>
    </div>
</div>
<script type="text/javascript">
 
</script>