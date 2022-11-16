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
                                        {{-- <li class="dm-none active"><a data-toggle="tab" href="#image-view">Image view</a></li> --}}
                                        <li class="front-tab active"><a data-toggle="tab" href="#front-video" data-side="front">Front video</a></li>
                                        <li class=""><a data-toggle="tab" href="#side-video" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        
                                      <div id="front-video" class="front-video tab-pane fade active in">
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
                                                        <input type="file" onChange="fileSelectHandlerVideo(this,'video')" accept="video/*"  style="display: none;" name="fileToUpload"/>
                                                        <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                                        </video>
                                                        {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'video')" accept="video/*"  name="fileToUpload"/> --}}
                                                        {{-- <button type="button" class="btn btn-primary uploadVideo">Save</button> --}}

                                                    </div>
                                                </div>
                                            </div>
                                      <div id="side-video" class="side-video tab-pane fade">
                                        <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-side-video btn-start-recording" href="#record-side-video" data-video="record">Record video</a></li>
                                            <li class="video-btn">
                                                <div class="upload-btn-wrapper upload-side-video">
                                                    <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                                   
                                                </div>
                                            </li>
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
                                                    
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video')" style="display: none;"  accept="video/*"  name="fileToUpload"/>
                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'side-video')" accept="video/*"  name="fileToUpload"/> --}}
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </fieldset>
                        
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
                                        {{-- <li class="dm-none active"><a data-toggle="tab" href="#image-view1">Image view</a></li> --}}
                                        <li class="front-tab active"><a data-toggle="tab" href="#front-video1" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video1" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        
                                      <div id="front-video1" class="front-video tab-pane fade active in">
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
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video1')" accept="video/*" style="display: none;"  name="fileToUpload"/>

                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'video1')" accept="video/*"  name="fileToUpload"/> --}}
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
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video1')" style="display: none;"  accept="video/*"  name="fileToUpload"/>
                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'side-video1')" accept="video/*"  name="fileToUpload"/> --}}
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
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
                                        {{-- <li class="dm-none active"><a data-toggle="tab" href="#image-view2">Image view</a></li> --}}
                                        <li class="front-tab active"><a data-toggle="tab" href="#front-video2" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video2" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                       
                                      <div id="front-video2" class="front-video tab-pane fade active in">
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
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video2')" accept="video/*" style="display: none;"   name="fileToUpload"/>
                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'video2')" accept="video/*"  name="fileToUpload"/> --}}
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
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video2')" style="display: none;"  accept="video/*"  name="fileToUpload"/>
                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'side-video2')" accept="video/*"  name="fileToUpload"/> --}}
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
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
                                        {{-- <li class="dm-none active"><a data-toggle="tab" href="#image-view3">Image view</a></li> --}}
                                        <li class="front-tab active"><a data-toggle="tab" href="#front-video3" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video3" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                       
                                      <div id="front-video3" class="front-video tab-pane fade active in">
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
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video3')" accept="video/*" style="display: none;"   name="fileToUpload"/>
                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'video3')" accept="video/*"  name="fileToUpload"/> --}}
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
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video3')" style="display: none;"  accept="video/*"  name="fileToUpload"/>
                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'side-video3')" accept="video/*"  name="fileToUpload"/> --}}
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
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
                                        {{-- <li class="dm-none active"><a data-toggle="tab" href="#image-view4">Image view</a></li> --}}
                                        <li class="front-tab active"><a data-toggle="tab" href="#front-video4" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video4" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        
                                      <div id="front-video4" class="front-video tab-pane fade active in">
                                         <ul class="nav nav-pills videoTab">
                                            <li class="video-btn"><a data-toggle="pill" class="record-video4 btn-start-recording" href="#record-video4" data-video="record">Record video</a></li>
                                            <li class="video-btn">
                                                <div class="upload-btn-wrapper upload-video4">
                                                    <button type="button" class="btn uploadVideoNew">Upload Video</button>
                                                    
                                                </div>
                                            </li>
                                            <li class="video-btn"><a data-toggle="pill" class="remove-video4 hidden" onclick="removeVideo(this,'video4')" href="javascript:void(0)">Remove video</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="record-video4" class="tab-pane fade">
                                                <video width="100%" class="recordedVideo" controls  playsinline loop>                                      </video>
                                      {{-- <button type="button" class="btn btn-primary btn-start-recording">Start Recording</button>&nbsp;&nbsp;  --}}
                                      <button type="button" class="btn btn-primary btn-stop-recording" data-hide="video4">Save Recording</button>
                                          </div>
                                          <div id="upload-video4" class="uploadTabActive tab-pane">
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video4')" accept="video/*" style="display: none;"  name="fileToUpload"/>
                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'video4')" accept="video/*"  name="fileToUpload"/> --}}
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
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video4')" style="display: none;"  accept="video/*"  name="fileToUpload"/>
                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'side-video4')" accept="video/*"  name="fileToUpload"/> --}}
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
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
                                        {{-- <li class="dm-none active"><a data-toggle="tab" href="#image-view5">Image view</a></li> --}}
                                        <li class="front-tab active"><a data-toggle="tab" href="#front-video5" data-side="front">Front video</a></li>
                                        <li><a data-toggle="tab" href="#side-video5" data-side="side">Side video</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        
                                      <div id="front-video5" class="front-video tab-pane fade active in">
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
                                            <input type="file" onChange="fileSelectHandlerVideo(this,'video5')" accept="video/*" style="display: none;"  name="fileToUpload"/>
                                            <video width="100%" class="uploadVideo frontVideo" controls  playsinline loop>
                                            </video>
                                            {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'video5')" accept="video/*"  name="fileToUpload"/> --}}
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
                                                    <input type="file" onChange="fileSelectHandlerVideo(this,'side-video5')" style="display: none;"  accept="video/*"  name="fileToUpload"/>
                                                    <video width="100%" class="uploadVideo sideVideo" controls  playsinline loop>
                                                    </video>
                                                    {{-- <input type="file" onChange="fileSelectHandlerVideo(this,'side-video5')" accept="video/*"  name="fileToUpload"/> --}}
                                                </div>
                                         </div>
                                      </div>


                                  </div>
                                       
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
              
              <button type="button" class="btn btn-primary btn-o back-step pull-left"> <i class="fa fa-arrow-circle-left"></i> Back </button>
              {{-- <button class="btn btn-primary save-draft btn-wide" data-client-id="{{ Auth::user()->account_id }}"> Save & Draft </button> --}}
              <button type="button" class="btn btn-primary next-step act-btn">Next <i class="fa fa-arrow-circle-right"></i></button>
              <button type="button" class="btn btn-primary finish-btn hidden" data-client-id="{{ Auth::user()->account_id }}">Finish</button>
            </div>
    </div>
    </div>
</div>
<script type="text/javascript">
 
</script>