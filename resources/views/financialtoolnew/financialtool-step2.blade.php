{{-- 2 SETUP EXPENSES --}}
  <fieldset class="padding-15">
      
        <legend>SETUP EXPENSE</legend>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="strong"> SETUP EXPENSE: <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#setup-modal' role='button' value="Small Default">Click to Calculate</a> </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_calculated'
                           id='se_calculated'
                           value="{{ !empty($financeData->se_calculated)  ? $financeData->se_calculated : 0.00 }}"
                           placeholder='$0.00' type='text'>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="strong"> GST EXCLUSIVE : </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_gst_excl'
                           id='se_gst_excl'
                           value="{{ !empty($financeData->se_gst_excl)  ? $financeData->se_gst_excl : 0.00 }}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>
           
                <div class="form-group">
                    <label class="strong"> GST PAID : </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_gst_paid'
                           id='se_gst_paid'
                           value="{{ !empty($financeData->se_gst_paid)  ? $financeData->se_gst_paid : 0.00 }}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>
           
                <div class="form-group">
                    <label class="strong"> ESTIMATED CAPITAL: </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_est_capital'
                           id='se_est_capital'
                           value="{{ !empty($financeData->se_est_capital)  ? $financeData->se_est_capital : 0.00}}"
                           placeholder='$0.00' type='text'>
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> ADDITIONAL CAPITAL REQUIRED: </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_add_capital_req'
                           id='se_add_capital_req'
                           value="{{ !empty($financeData->se_add_capital_req)  ? $financeData->se_add_capital_req : 0.00}}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>


            </div>

            {{-- right side  --}}
            <div class="col-md-6">

                <div class="form-group">
                    <label class="strong">
                      REPAYMENT PERIOD FOR ADDITIONAL CAPITAL (MONTHLY):
                    </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_repayment_monthly_period_capital_req'
                           id='se_repayment_monthly_period_capital_req'
                           value="{{ !empty($financeData->se_repayment_monthly_period_capital_req)  ? $financeData->se_repayment_monthly_period_capital_req : 0}}"
                           placeholder='6 or 12' type='text'>
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> 
                      INTEREST RATE FOR LOAN:
                    </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_interest_rate_fr_loan'
                           id='se_interest_rate_fr_loan'
                           value="{{ !empty($financeData->se_interest_rate_fr_loan)  ? $financeData->se_interest_rate_fr_loan : 0.00}}"
                           placeholder='10%' max="100" min="0" type='text'>
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> INTEREST: </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_interest'
                           id='se_interest'
                           value="{{ !empty($financeData->se_interest)  ? $financeData->se_interest : 0.00}}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> TOTAL: </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_total'
                           id='se_total'
                           value="{{ !empty($financeData->se_total)  ? $financeData->se_total : 0.00}}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> MONTHLY REPAYMENT: </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='se_monthly_repayment'
                           id='se_monthly_repayment'
                           value="{{ !empty($financeData->se_monthly_repayment)  ? $financeData->se_monthly_repayment : 0.00}}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>
            </div>
        </div>

  </fieldset>

