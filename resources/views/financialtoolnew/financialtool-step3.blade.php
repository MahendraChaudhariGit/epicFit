{{------------  3 OPERATING EXPENSES   ----------- --}}

  {{-- Business Expense --}}
    <div class="col-sm-6 business_div">
      <fieldset class="padding-15">
          <legend>BUSINESS EXPENSE</legend>
          {{-- <div class="row"> --}}
              {{-- <div class="col-md-6"> --}}
                  <div class="form-group">
                      <label class="strong"> BUSINESS EXPENSE (Monthly) : <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#business-modal' role='button' value="Small Default"> Click to Calculate </a> </label>
                      <div>
                        <input class='form-control'
                          id='be_calculated'
                          data-rule-number='true' data-rule-required='true'
                          name='be_calculated'
                          value="{{ !empty($financeData->be_calculated)  ? $financeData->be_calculated : 0.00}}"
                          placeholder='' type='text'>
                      </div>
                  </div>
              
                  <div class="form-group">
                      <label class="strong"> GST EXCLUSIVE: </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_gst_excl'
                             id='be_gst_excl'
                             value="{{ !empty($financeData->be_gst_excl)  ? $financeData->be_gst_excl : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>
             
                  <div class="form-group">
                      <label class="strong"> GST PAID: </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_gst_paid'
                             id='be_gst_paid'
                             value="{{ !empty($financeData->be_gst_paid)  ? $financeData->be_gst_paid : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>
             
                  <div class="form-group">
                      <label class="strong"> LOAN REPAYMENT AMOUNT (1st YEAR): </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_loan_repayment_amt'
                             id='be_loan_repayment_amt'
                             value="{{ !empty($financeData->se_monthly_repayment)  ? $financeData->se_monthly_repayment : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>

                  <div class="form-group" style="display: none;">
                      <label class="strong"> TOTAL EXPENSES PER MONTH: </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_total_exp_per_mnth'
                             id='be_total_exp_per_mnth'
                             value="{{ !empty($financeData->be_total_exp_per_mnth)  ? $financeData->be_total_exp_per_mnth : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>
              
                  <div class="form-group">
                      <label class="strong"> ANNUAL PROFIT REQUIRED: </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_profit_req_after_tax'
                             id='be_profit_req_after_tax'
                             value="{{ !empty($financeData->be_profit_req_after_tax)  ? $financeData->be_profit_req_after_tax : 0.00}}"
                             placeholder='' type='text'>
                      </div>
                  </div>
             
                  <div class="form-group" style="display: none;">
                      <label class="strong"> TAX PAYABLE: </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_tax_payable'
                             id='be_tax_payable'
                             value="{{ !empty($financeData->be_tax_payable)  ? $financeData->be_tax_payable : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>

                   <div class="form-group" style="display: none;">
                      <label class="strong"> PROFIT AFTER TAX: </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_profit_after_tax'
                             id='be_profit_after_tax'
                             value="{{ !empty($financeData->be_profit_after_tax)  ? $financeData->be_profit_after_tax : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>
              
              
                  <div class="form-group">
                      <label class="strong"> TOTAL EXPENSES PER ANNUM: </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_total_exp_per_annum'
                             id='be_total_exp_per_annum'
                             value="{{ !empty($financeData->be_total_exp_per_annum)  ? $financeData->be_total_exp_per_annum : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label class="strong"> GST PAID PER ANNUM (EXCL SETUP): </label>
                      <div>
                          <input class='form-control'
                             data-rule-number='true' data-rule-required='true'
                             name='be_gst_paid_per_annum'
                             id='be_gst_paid_per_annum'
                             value="{{ !empty($financeData->be_gst_paid_per_annum)  ? $financeData->be_gst_paid_per_annum : 0.00}}"
                             placeholder='' type='text' readonly="true">
                      </div>
                  </div>
             
                 
              {{-- </div> --}}

          {{-- </div> --}}
      </fieldset>
    </div>

  {{-- Living Expense --}}
    <div class="col-sm-6 living_div">
      <fieldset class="padding-15">
          <legend> LIVING EXPENSES (ANNUALLY) </legend>
          {{-- <div class="row"> --}}
              {{-- <div class="col-md-6"> --}}
              <div class="form-group">
                <label class="strong"> ESTIMATED :  <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#living-modal' role='button' value="Small Default"> Click to Calculate </a> </label>
                <div>
                  <input class='form-control'
                    name='le_calculated'
                    data-rule-number='true' data-rule-required='true'
                    id='le_calculated'
                    value="{{ !empty($financeData->le_calculated)  ? $financeData->le_calculated : 0.00}}"
                    placeholder='' type='text'>
                </div>
              </div>

               <div class="form-group">
                    <label class="strong"> INCOME TAX EXCLUSIVE: </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='le_gst_excl'
                           id='le_gst_excl'
                           value="{{ !empty($financeData->le_gst_excl)  ? $financeData->le_gst_excl : 0.00}}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>
             
                <div class="form-group">
                    <label class="strong"> INCOME TAX PAID: </label>
                    <div>
                        <input class='form-control'
                           data-rule-number='true' data-rule-required='true'
                           name='le_gst_paid'
                           id='le_gst_paid'
                           value="{{ !empty($financeData->le_gst_paid)  ? $financeData->le_gst_paid : 0.00}}"
                           placeholder='' type='text' readonly="true">
                    </div>
                </div>
                 
              {{-- </div> --}}

          {{-- </div> --}}
      </fieldset>
    </div>

  {{-- Partnerships --}}
  <div class="col-sm-6 partnership_div">
    <fieldset class="padding-15 partnership_fieldset">
        <legend> PARTNERSHIP (REQUIRED INCOME PER ANNUM) </legend>

            <div class="form-group">
              <label class="strong"> HOW MANY PARTNER?   </label>
              <div>
                <input class='form-control'
                  name='no_of_partner'
                  data-rule-number='true' data-rule-required='true'
                  id='no_of_partner'
                  value="{{ !empty($financeData->no_of_partner)  ? $financeData->no_of_partner : 1 }}"
                  placeholder='' type='text'>
              </div>
            </div>

            <div class="form-group equity_ratio_div">
              <label class="strong equity_label"> EQUITY RATIO (PARTNER 1) :  </label>
              <div>
                <input class='form-control equity_ratio'
                  name='equity_ratio[]'
                  data-rule-number='true' data-rule-required='true'
                  id='equity_ratio_0'
                  value="{{ !empty($ftPartnershipData['profit_percentage'])  ? $ftPartnershipData['profit_percentage'][0] : 0 }}"
                  placeholder='' type='text' data-rule-custom="equity_ratio">
              </div>
            </div>

             <div class="form-group invested_amount_div">
              <label class="strong invested_label"> PARTNER 1:  <a class='btn btn-primary btn-o btn-sm p-y-0' data-toggle='modal' href='#partnership-modal' role='button' value="Small Default"> Click to Calculate </a> </label>
              <div>
                <input class='form-control invested_amount'
                  name='invested_amount[]'
                  data-rule-number='true' data-rule-required='true'
                  id='invested_amount_0'
                  value="{{ !empty($ftPartnershipData['invested_amount'])  ? $ftPartnershipData['invested_amount'][0] : 0.00}}"
                  placeholder='' type='text'>
              </div>
            </div>

            <div class="form-group gst_excl_div">
                  <label class="strong gst_excl_label"> INCOME TAX EXCLUSIVE(PARTNER 1): </label>
                  <div>
                      <input class='form-control gst_excl'
                         data-rule-number='true' data-rule-required='true'
                         name='gst_excl[]'
                         id='gst_excl_0'
                         value="{{ !empty($ftPartnershipData['excl_gst'])  ? $ftPartnershipData['excl_gst'][0] : 0.00}}"
                         placeholder='' type='text' readonly="true">
                  </div>
            </div>

            <div class="form-group gst_paid_div">
                  <label class="strong gst_paid_label"> INCOME TAX PAID (PARTNER 1): </label>
                  <div>
                      <input class='form-control gst_paid'
                         data-rule-number='true' data-rule-required='true'
                         name='gst_paid[]'
                         id='gst_paid_0'
                         value="{{ !empty($ftPartnershipData['gst_paid'])  ? $ftPartnershipData['gst_paid'][0] : 0.00}}"
                         placeholder='' type='text' readonly="true">
                  </div>
            </div>

            <div class="form-group">
                  <label class="strong"> PARTNERSHIP TOTAL: </label>
                  <div>
                      <input class='form-control'
                         data-rule-number='true' data-rule-required='true'
                         name='partnership_total'
                         id='partnership_total'
                         value="{{ !empty($financeData->partnership_total)  ? $financeData->partnership_total : 0.00}}"
                         placeholder='' type='text' readonly="true">
                  </div>
            </div>
   </fieldset>
  </div>

