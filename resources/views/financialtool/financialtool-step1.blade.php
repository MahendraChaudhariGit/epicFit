<div class="sucMes hidden">
    {!! displayAlert()!!}
</div>
<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>Your Tax Details</legend>
        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    <label class="strong">Select Type : </label>
                    <div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input name="tax_type" class="is_company" id="is_company" 
                            value="company" type="radio" {{ !empty($financeData->tax_type) && $financeData->tax_type == 'company' ? 'checked' : ''}}>
                            <label for="is_company">
                                Company
                            </label>
                        </div>

                         <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input name="tax_type" class="is_sole_trader" id="is_sole_trader" value="sole-trader" type="radio" 
                            {{ !empty($financeData->tax_type) && $financeData->tax_type == 'sole-trader' ? 'checked' : ''}}>
                            <label for="is_sole_trader">
                                Sole Trader
                            </label>
                        </div>

                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input name="tax_type" class="is_partnership" 
                            id="is_partnership" value="partnership" type="radio" 
                            {{ !empty($financeData->tax_type) && $financeData->tax_type == 'partnership' ? 'checked' : ''}}>
                            <label for="is_partnership">
                                Partnership
                            </label>
                        </div>
                        {{-- <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                            <input id="preferredTraingDaysMonAm" value="true" type="checkbox" class="preferredTraingDays is_company" {{ !empty($financeData->is_company) && $financeData->is_company == 1 ? 'checked' : ''}}>
                            <label for="preferredTraingDaysMonAm"> Yes </label>
                        </div>

                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                            <input id="preferredTraingDaysMonPm" value="pm" type="checkbox" class="preferredTraingDays is_company" {{ empty($financeData->is_company)  ? 'checked' : ''}}>
                            <label for="preferredTraingDaysMonPm"> No </label>
                        </div> --}}
                    </div>
                </div>
               {{--  <div class="form-group">
                    <label class="strong"> Sole Trader </label>
                    <div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                            <input id="checkbox" type='checkbox' value='true' class="is_sole_trader" name="is_sole_trader"
                            {{ !empty($financeData->is_sole_trader) && $financeData->is_sole_trader == 1 ? 'checked' : ''}}
                            >
                            <label for="checkbox"> Yes </label>
                        </div>

                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                             <input id="checkbox" type='checkbox' value='false' class="is_sole_trader" name="is_sole_trader"
                            {{ empty($financeData->is_sole_trader) ? 'checked' : ''}}>
                            <label for="checkbox"> No </label>
                        </div>
                    </div>
                </div> --}}
                <div class="form-group">
                    <label class="strong"> GST ( Goods & Services Tax ) Registered </label>
                    <div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                            <input id="checkbox1" type='checkbox' value='1' class="is_gst_registered" 
                            name="is_gst_registered"
                            {{ !empty($financeData->is_gst_registered) && $financeData->is_gst_registered == 1 ? 'checked' : ''}}>
                            <label for="checkbox1"> Yes </label>
                        </div>

                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                             <input id="checkbox2" type='checkbox' value='0' 
                             class="is_gst_registered" name="is_gst_registered"
                            {{ empty($financeData->is_gst_registered) ? 'checked' : ''}}>
                            <label for="checkbox2"> No </label>
                        </div>
                    </div>
                </div>

                 <div class="gst-input form-group" style="display : none;">
                     <input type="hidden" name="gst_no" class="form-control gst-no" value="{!! !empty($settingPrefData->tax_code)  ? $settingPrefData->tax_code : '' !!}" placeholder="Gst No" readonly="true">
                     <br>
                        <input type="hidden"
                        name="gst_percentage" class="form-control gst-percentage"
                        value="{!! !empty($settingPrefData->tax_amount)  ? $settingPrefData->tax_amount : '' !!}" placeholder="Gst %"
                         readonly="true">
                 </div>
            </div>
        </div>
    </fieldset>
</div>

<div class="col-md-6">

    <fieldset class="padding-17">
        <legend>Setup Expense</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Estimate : <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#setup-modal' role='button' value="Small Default">Click to Calculate</a> </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='setup_exp_ets_total'
                           id='setup_exp_est'
                           value="{{ !empty($financeData->setup_exp_est)  ? $financeData->setup_exp_est : ''}}"
                           placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="strong"> GST Incl : </label>--}}
                    {{--<div>--}}
                        {{--<input class='form-control'--}}
                           {{--data-rule-number='true' data-rule-required='true'--}}
                           {{--name='setup_exp_gst_incl'--}}
                           {{--id='setup_exp_gst_incl'--}}
                           {{--value="{{ !empty($financeData->setup_exp_gst_incl)  ? $financeData->setup_exp_gst_incl : ''}}"--}}
                           {{--placeholder='$11,200.00' type='text'>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> GST Inclusive : </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='setup_exp_gst_incl'
                           id='setup_exp_gst_incl'
                           value="{{ !empty($financeData->setup_exp_gst_incl)  ? $financeData->setup_exp_gst_incl : ''}}"
                           placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="padding-17">
        <legend>Business Expense</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Estimate : <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#business-modal' role='button' value="Small Default">Click to Calculate</a> </label>
                    <div>
                      <input class='form-control'
                        id='business_exp_est'
                        data-rule-number='true' data-rule-required='true'
                        name='business_exp_est_total'
                        value="{{ !empty($financeData->business_exp_est)  ? $financeData->business_exp_est : ''}}"
                        placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="strong"> Income tax inclusive : </label>--}}
                    {{--<div>--}}
                        {{--<input class='form-control'--}}
                           {{--data-rule-number='true' data-rule-required='true'--}}
                           {{--name='business_exp_gst_incl'--}}
                           {{--id='business_exp_gst_incl'--}}
                           {{--value="{{ !empty($financeData->business_exp_gst_incl)  ? $financeData->business_exp_gst_incl : ''}}"--}}
                           {{--placeholder='$11,200.00' type='text'>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> GST Inclusive : </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='business_exp_gst_incl'
                           id='business_exp_gst_incl'
                           value="{{ !empty($financeData->business_exp_gst_incl)  ? $financeData->business_exp_gst_incl : ''}}"
                           placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="padding-19">
        <legend>Estimate living Expense</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Estimate :  <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#living-modal' role='button' value="Small Default">Click to Calculate</a> </label>
                    <div>
                      <input class='form-control'
                        name='living_exp_est_total'
                        data-rule-number='true' data-rule-required='true'
                        id='living_exp_est'
                        value="{{ !empty($financeData->living_exp_est)  ? $financeData->living_exp_est : ''}}"
                        placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="strong"> Income tax inclusive: </label>--}}
                    {{--<div>--}}
                        {{--<input class='form-control'--}}
                           {{--data-rule-number='true' data-rule-required='true'--}}
                           {{--name='living_exp_gst_incl'--}}
                           {{--id='living_exp_gst_incl'--}}
                           {{--value="{{ !empty($financeData->living_exp_gst_incl)  ? $financeData->living_exp_gst_incl : ''}}"--}}
                           {{--placeholder='$11,200.00' type='text'>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Income Tax Inclusive : </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='living_exp_gst_incl'
                           id='living_exp_gst_incl'
                           value="{{ !empty($financeData->living_exp_gst_incl)  ? $financeData->living_exp_gst_incl : ''}}"
                           placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>


{{-- ----------  business expenses   ----------- --}}
<div class="col-md-6">

    <fieldset class="padding-17">
        <legend>Business Expense</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Estimate : <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#business-modal' role='button' value="Small Default">Click to Calculate</a> </label>
                    <div>
                      <input class='form-control'
                        id='business_exp_est'
                        data-rule-number='true' data-rule-required='true'
                        name='business_exp_est_total'
                        value="{{ !empty($financeData->business_exp_est)  ? $financeData->business_exp_est : ''}}"
                        placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="strong"> Income tax inclusive : </label>--}}
                    {{--<div>--}}
                        {{--<input class='form-control'--}}
                           {{--data-rule-number='true' data-rule-required='true'--}}
                           {{--name='business_exp_gst_incl'--}}
                           {{--id='business_exp_gst_incl'--}}
                           {{--value="{{ !empty($financeData->business_exp_gst_incl)  ? $financeData->business_exp_gst_incl : ''}}"--}}
                           {{--placeholder='$11,200.00' type='text'>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> GST Inclusive : </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='business_exp_gst_incl'
                           id='business_exp_gst_incl'
                           value="{{ !empty($financeData->business_exp_gst_incl)  ? $financeData->business_exp_gst_incl : ''}}"
                           placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="padding-19">
        <legend>Estimate living Expense</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Estimate :  <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#living-modal' role='button' value="Small Default">Click to Calculate</a> </label>
                    <div>
                      <input class='form-control'
                        name='living_exp_est_total'
                        data-rule-number='true' data-rule-required='true'
                        id='living_exp_est'
                        value="{{ !empty($financeData->living_exp_est)  ? $financeData->living_exp_est : ''}}"
                        placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="strong"> Income tax inclusive: </label>--}}
                    {{--<div>--}}
                        {{--<input class='form-control'--}}
                           {{--data-rule-number='true' data-rule-required='true'--}}
                           {{--name='living_exp_gst_incl'--}}
                           {{--id='living_exp_gst_incl'--}}
                           {{--value="{{ !empty($financeData->living_exp_gst_incl)  ? $financeData->living_exp_gst_incl : ''}}"--}}
                           {{--placeholder='$11,200.00' type='text'>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Income Tax Inclusive : </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='living_exp_gst_incl'
                           id='living_exp_gst_incl'
                           value="{{ !empty($financeData->living_exp_gst_incl)  ? $financeData->living_exp_gst_incl : ''}}"
                           placeholder='$11,200.00' type='text'>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
