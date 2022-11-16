<div class="sucMes hidden">
    {!! displayAlert()!!}
</div>


  {{-- 1 BUSINESS STRUCTURE --}}
  <fieldset class="padding-15">
      <legend>YOUR TAX PREFERENCE</legend>
      <div class="row">
          <div class="col-md-12">

              <div class="form-group">
                  <label for="business_type" class="strong"> 
                      BUSINESS TYPE / STRUCTURE:
                  </label>
                  <select id="business_type" name="business_type" class="business_type form-control" required>
                      <option value="">-- Select Business Type/Structure --</option>
                        <option value="sole-trader" 
                        {!! (!empty($financeData->business_type) && $financeData->business_type == "sole-trader") ? "selected" : '' !!}> Sole Trader </option>
                        <option value="partnership" {!! (!empty($financeData->business_type) && $financeData->business_type == "partnership") ? "selected" : '' !!}> Partnership </option>
                        <option value="company" {!! (!empty($financeData->business_type) && $financeData->business_type == "company") ? "selected " : '' !!}> Company </option>
                  </select>
              </div>

              <div class="form-group">
                  <label for="business_type" class="strong">
                      GST / VAT REGISTERED: 
                  </label>
                  <select id="is_gst_registered" name="is_gst_registered" class="is_gst_registered form-control" required>
                        <option value="">-- Select GST / VAT Registered --</option>
                        <option value="1" {!! (!empty($financeData->is_gst_registered) && $financeData->is_gst_registered == 1) ? "selected" : '' !!}> Yes </option>
                        <option value="0" {!! isset($financeData->is_gst_registered) && $financeData->is_gst_registered == 0 ? "selected" : '' !!}> No </option>
                  </select>
                  <input type="hidden" name="gst_no" class="form-control gst-no"
                  value="{!! !empty($settingPrefData->tax_code)  ? $settingPrefData->tax_code : '' !!}" placeholder="Gst %">
                  <input type="hidden" name="gst_percentage" class="form-control gst-percentage"
                  value="{!! !empty($settingPrefData->tax_amount)  ? $settingPrefData->tax_amount : '' !!}" placeholder="Gst %">
              </div>
          </div>
      </div>
  </fieldset>







