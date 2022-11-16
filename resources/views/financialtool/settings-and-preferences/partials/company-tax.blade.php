<div class='modal fade' id='company-tax-modal' tabindex='-1'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
                <h4 class='modal-title' id='myModalLabel'>Company Tax</h4>
            </div>
            <div class='modal-body'>
                <input id="country" type="hidden" name="country" value="NZ">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" class="tax_category" value="company">
                        <div class="form-group">
                            <label class="strong" for="company_tax_type">Type</label>
                            <select id="company_tax_type" name="tax_type" class="form-control tax_type" disabled>
                                <option value="income" selected>Income tax</option>
                                <option value="gst">GST</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="companyIncomeTax">
                            <div class="form-group">
                                <label class="strong" for="tax_type">Tax code</label>
                                <input type="text" class="form-control tax_code" id="company_tax_code" name="tax_code">
                            </div>
                        </div>
                    </div>
                    <div class="dynamicSec companyDynamicSec">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="Tax">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="strong" for="tax_type">From</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="strong" for="tax_type">To</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group col-md-6">
                                            <label class="strong" for="tax_type">%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="Tax">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control cfrom_amount" name="from_amount">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control cto_amount" name="to_amount">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group col-md-6">
                                            <input type="text" class="form-control ctax_percentages"
                                                   name="tax_percentages">
                                        </div>
                                        <button  type="button" class="btn btn-primary btn-o btn-sm p-y-0 pull-right
                                        add_income_tax_field_btn incTaxBtn" role="button"
                                                 value="Small Default" title="Add" data-id="0">
                                            <i class="fa fa-plus-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button class='btn btn-default overflow' data-dismiss='modal' type='button'>Close</button>
                    <button class='btn btn-primary overflow' id="save-company-tax" data-dismiss='modal'
                            type='button'>Save </button>
                    <button class='btn btn-primary overflow' 
                            id="update-company-tax"
                            class='close' data-dismiss='modal'  type='button'>Update</button>
                </div>
            </div>
        </div>
    </div>
</div>