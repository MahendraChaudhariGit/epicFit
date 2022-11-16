<div class="col-md-12">
    <fieldset class="padding-15">
        <legend>Your First Three Month Cashflow Requirements</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> How long are you forcasting for? </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty ($financeData->cashflow_forecast_time) ? $financeData->cashflow_forecast_time : '' }}"
                        id='cashflow_forecast_time' name='cashflow_forecast_time' placeholder='' type='text'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Monthly Business Expenses (Excluding setup costs) </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty ($financeData->business_exp_est) ? $financeData->business_exp_est :'' }}"
                        id='monthly_business_cost' name='monthly_business_cost' placeholder='' type='text' readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Monthly Personal Expenses (Excludes savings) </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty ($financeData->living_exp_est) ? $financeData->living_exp_est :'' }}"
                        id='monthly_personal_cost' name='monthly_personal_cost' placeholder='' type='text' readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Total Monthly Expenses (Excluding any taxes) </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{ !empty ($financeData->living_exp_est && $financeData->business_exp_est) ? $financeData->business_exp_est + $financeData->living_exp_est :'' }}"
                        id='total_monthly_expense' name='total_monthly_expense' placeholder='' type='text' readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Cashflow Setup Expenses </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty ($financeData->setup_exp_est) ? $financeData->setup_exp_est :'' }}"
                        id='cashflow_setup_expense' name='cashflow_setup_expense' placeholder='' type='text' readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Setup Loan Repayment Period Months </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty ($financeData->setup_loan_repayment_period_month_time) ? $financeData->setup_loan_repayment_period_month_time : '' }}"
                        id='setup_loan_repayment_period_month_time' name='setup_loan_repayment_period_month_time' placeholder='$11,200.00' type='text'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Setup Loan Repayment Per Month </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{ !empty ($financeData->setup_loan_repayment_per_month_amt) ? $financeData->setup_loan_repayment_per_month_amt :'' }}"
                        id='setup_loan_repayment_per_month_amt' 
                        name='setup_loan_repayment_per_month_amt' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong">Capital required in the first 3 months</label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->cap_req_first_three_months) ? $financeData->cap_req_first_three_months :'' }}"
                    id='cap_req_first_three_months' name='cap_req_first_three_months' placeholder='$11,200.00' type='text' readonly="true"></div>
                </div>

                <div class="form-group">
                    <label class="strong">Personal capital available</label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->presonal_capital_available) ? $financeData->presonal_capital_available :'' }}"
                    id='presonal_capital_available' name='presonal_capital_available' placeholder='$11,200.00' type='text'>
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> Loan Required </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->loan_req) ? $financeData->loan_req :'' }}"
                    id='loan_req' name='loan_req' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> Monthly Required </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->monthly_req) ? $financeData->monthly_req :'' }}"
                    id='monthly_req' name='monthly_req' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
