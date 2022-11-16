<div class="modal fade picCropModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Choose Headshot </h4>
            </div>
            <div class="modal-body">
                <div class="btn-group m-b-10">
                    <a class="btn btn-primary btn-o toggle-ratio" href="#" data-ratio="1" data-crop-selector="square">Square</a>
                    <a class="btn btn-primary btn-o toggle-ratio" href="#" data-ratio="0" data-crop-selector="rectangle">Rectangle</a>
                </div>
                <div class="center">
                	<img alt="Loading..." class="preview" />
                    <input type="hidden" name="ui-x1" />
                    <input type="hidden" name="ui-y1" />
                    <input type="hidden" name="ui-w" />
                    <input type="hidden" name="ui-h" />
                    <input type="hidden" name="widthScale" />
                    <input type="hidden" name="heightScale" />
                    <input type="hidden" name="photoName">
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary save">Save</button>
            </div>
        </div>
    </div>
</div>