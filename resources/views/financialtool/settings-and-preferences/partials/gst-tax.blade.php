<div class='modal fade' id='gst-tax-modal' tabindex='-1'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
                <h4 class='modal-title' id='myModalLabel'>GST Tax</h4>
            </div>
            <div class='modal-body'>
                <input id="country" type="hidden" name="country" value="NZ">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" class="tax_category" value="gst">
                        <div class="form-group">
                            <label class="strong" for="gst_tax_type">Type</label>
                            <select id="gst_tax_type" name="tax_type" class="form-control tax_type" disabled> 
                                <option value="gst" selected>GST</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row gstDynamicSec">
                    <div class="col-md-6">
                        <div class="companyIncomeTax">
                            <div class="form-group">
                                <label class="strong" for="tax_type">GST Number</label>
                                <input type="text" class="form-control tax_code" 
                                id="gst_no" name="tax_code">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="companyIncomeTax">
                            <div class="form-group">
                                <label class="strong" for="tax_type">Tax %</label>
                                <input type="text" class="form-control tax_amount" id="gst_tax_amount" name="tax_amount">
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button class='btn btn-default overflow' data-dismiss='modal' type='button'>Close</button>
                    <button class='btn btn-primary overflow' id="save-gst-tax" data-dismiss='modal'
                            type='button'>Save </button>
                    <button class='btn btn-primary overflow' id="update-gst-tax"
                            class='close' data-dismiss='modal'  type='button'>Update</button>
                </div>
            </div>
        </div>
    </div>
</div>