<div id="goal-modal" class="modal fade " role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body bg-white">
        <div class="row">
          <div class="col-md-6">
            <div class="pull-left"><h2 class="goal-name"></h2></div>
          </div>
          <div class="col-md-6">
            <div class="pull-right">
              <a class="btn btn-xs btn-default tooltips popup-edit-goal" data-goal-id=""  data-placement="top" data-original-title="Edit" href="#">
                <i class="fa fa-pencil " style="color:#ff4401;" ></i>
              </a>

              <a class="btn btn-xs btn-default tooltips popup-delete-goal" data-entity="goal" href="#" data-placement="top" data-original-title="delete" data-goal-id = "">
                <i class="fa fa-times" style="color:#ff4401;"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="row">
          <div  class="col-md-6">
            <div class="form-group">
              <label class="strong">I want to accomplish :</label>
              <span class="accomplish"></span> 
            </div>
            <div class="form-group">
              <label class="strong">Why is this important:</label>
              <span class="goal-important"></span>
            </div>
          </div>
          <div  class="col-md-6">
            <div class="form-group"><label class="strong">Shared :</label><span class="shared"></span> </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div> 
</div>