<!--Start: movement step setup Modal -->
<div id="movementStepSetupModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Choose movement step </h4>
      </div>
      <div class="modal-body bg-white">
        {!! Form::open(['url' => '', 'role' => 'form', 'id'=>'movement-steps-form']) !!}
          <input type="hidden" name="stepsNameArray" value="<?php //echo implode(',',$movementStep); ?>">
          <div class="errorMsgDisp"></div>
          <div class="row">
            <div class="col-md-6">
                <a class="btn btn-primary select-all-movement-steps" href="#">Select all</a>
            </div>
          </div>
          <div class="row m-t-10">
            <div class="col-md-6">
              <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                  <input type="checkbox" id="SquatStep" value="Squat" name="squatStep" class='moveStep' /> 
                  <label for="SquatStep" class="m-r-0"></label>
              </div>
              Squat
            </div>
            <div class="col-md-6">
              <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                  <input type="checkbox" id="LungeStep" value="Lunge" name="lungeStep" class='moveStep' /> 
                  <label for="LungeStep" class="m-r-0"></label>
              </div>
              Lunge
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                  <input type="checkbox" id="BendStep" value="Bend" name="bendStep" class='moveStep' /> 
                  <label for="BendStep" class="m-r-0"></label>
              </div>
              Bend
            </div>
            <div class="col-md-6">
              <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                  <input type="checkbox" id="PullStep" value="Pull" name="pullStep" class='moveStep' /> 
                  <label for="PullStep" class="m-r-0"></label>
              </div>
              Pull
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                  <input type="checkbox" id="PushStep" value="Push" name="pushStep" class='moveStep' /> 
                  <label for="PushStep" class="m-r-0"></label>
              </div>
              Push
            </div>
            <div class="col-md-6">
              <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                  <input type="checkbox" id="RotationStep" value="Rotation" name="rotationStep" class='moveStep' /> 
                  <label for="RotationStep" class="m-r-0"></label>
              </div>
              Rotation
            </div>
          </div>
        {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" data-client-id="{{ $client_id }}" id="openMovementModal">Done</button>      
      </div>
    </div>
  </div>
</div>
<!--End: movement step setup Modal -->
