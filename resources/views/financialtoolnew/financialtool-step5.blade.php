
{{------------  5 FINANCIAL SUMMARY   ----------- --}}
  {{-- PROJECTED CLIENT GROWTH --}}
  <div class="col-md-12">
      <fieldset class="padding-15">
          <legend> PROJECTED CLIENT GROWTH </legend>
            <div class="col-sm-6 form-group">
                <label class="strong"> AVERAGE CLIENT SPEND (MONTHLY): </label>
                <div>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->average_client_spend) ? number_format(($financeData->average_client_spend / 12), 2, '.', '') : 0.00 }}"
                  id='projected_avg_client_spend' name='projected_avg_client_spend' placeholder='' type='text' readonly="true">
                </div>
            </div>

            <div class="col-sm-6 form-group">
                <label class="strong"> NEW CLIENTS PER MONTH: </label>
                <div>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->avg_singed_client_week) ? number_format(($financeData->avg_singed_client_week * 52 / 12), 2, '.', '') : 0.00 }}"
                  id='projected_client_per_mnth' name='projected_client_per_mnth' placeholder='' type='text' readonly="true">
                </div>
            </div>
      </fieldset>
  </div>
  {{-- PROJECTED INCOME --}}
  <div class="col-md-6">
      <fieldset class="padding-15">
          <legend> PROJECTED INCOME </legend>
        <table class="table table-responsive">

            <tr>
              <th><label class="strong"> MONTH 1 : </label></th>
              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->client_active_current) ? number_format(($financeData->client_active_current + $financeData->avg_singed_client_week * 52 / 12), 2, '.', '') : 0.00 }}"
                  id='sum_mnth1' name='sum_mnth1' placeholder='' type='text' readonly="true">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth1) ? number_format(($financeData->into_mnth1 + $financeData->avg_singed_client_week * 52 / 12), 2, '.', '') : 0.00 }}"
                  id='into_mnth1' name='into_mnth1' placeholder='' type='text' readonly="true">
                </div>    

              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 2 : </label></th>
              <td>
              <div class="form-group">
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->sum_mnth2) ? number_format(($financeData->sum_mnth2), 2, '.', '') : 0.00 }}"
                  id='sum_mnth2' name='sum_mnth2' placeholder='' type='text' readonly="true">
              </div>
              </td>
              <td>
              <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth2) ? number_format(($financeData->into_mnth2), 2, '.', '') : 0.00 }}"
                  id='into_mnth2' name='into_mnth2' placeholder='' type='text' readonly="true">
              </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 3 : </label></th>
              <td>
                <div class="form-group">
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{{!empty($financeData->sum_mnth3) ? number_format(($financeData->sum_mnth3), 2, '.', '') : 0.00 }}"
                    id='sum_mnth3' name='sum_mnth3' placeholder='' type='text' readonly="true">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth3) ? number_format(($financeData->into_mnth3), 2, '.', '') : 0.00 }}"
                  id='into_mnth3' name='into_mnth3' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 4 : </label></th>
              <td>
                  <div class="form-group">
                      <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="{{!empty($financeData->sum_mnth4) ? number_format(($financeData->sum_mnth4), 2, '.', '') : 0.00 }}"
                      id='sum_mnth4' name='sum_mnth4' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                     <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth4) ? number_format(($financeData->into_mnth4), 2, '.', '') : 0.00 }}"
                  id='into_mnth4' name='into_mnth4' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 5 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->sum_mnth5) ? number_format(($financeData->sum_mnth5), 2, '.', '') : 0.00 }}"
                  id='sum_mnth5' name='sum_mnth5' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth5) ? number_format(($financeData->into_mnth5), 2, '.', '') : 0.00 }}"
                  id='into_mnth5' name='into_mnth5' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 6 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->sum_mnth6) ? number_format(($financeData->sum_mnth6), 2, '.', '') : 0.00 }}"
                  id='sum_mnth6' name='sum_mnth6' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth6) ? number_format(($financeData->into_mnth6), 2, '.', '') : 0.00 }}"
                  id='into_mnth6' name='into_mnth6' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 7 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->sum_mnth7) ? number_format(($financeData->sum_mnth7), 2, '.', '') : 0.00 }}"
                  id='sum_mnth7' name='sum_mnth7' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth7) ? number_format(($financeData->into_mnth7), 2, '.', '') : 0.00 }}"
                  id='into_mnth7' name='into_mnth7' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 8 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->sum_mnth8) ? number_format(($financeData->sum_mnth8), 2, '.', '') : 0.00 }}"
                  id='sum_mnth8' name='sum_mnth8' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth8) ? number_format(($financeData->into_mnth8), 2, '.', '') : 0.00 }}"
                  id='into_mnth8' name='into_mnth8' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 9 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{{!empty($financeData->sum_mnth9) ? number_format(($financeData->sum_mnth9), 2, '.', '') : 0.00 }}"
                    id='sum_mnth9' name='sum_mnth9' placeholder='' type='text' readonly="true">                 
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth9) ? number_format(($financeData->into_mnth9), 2, '.', '') : 0.00 }}"
                  id='into_mnth9' name='into_mnth9' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 10 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->sum_mnth10) ? number_format(($financeData->sum_mnth10), 2, '.', '') : 0.00 }}"
                  id='sum_mnth10' name='sum_mnth10' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth10) ? number_format(($financeData->into_mnth10), 2, '.', '') : 0.00 }}"
                  id='into_mnth10' name='into_mnth10' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>
            
            <tr>
              <th><label class="strong"> MONTH 11 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{{!empty($financeData->sum_mnth11) ? number_format(($financeData->sum_mnth11), 2, '.', '') : 0.00 }}"
                    id='sum_mnth11' name='sum_mnth11' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{{!empty($financeData->into_mnth11) ? number_format(($financeData->into_mnth11), 2, '.', '') : 0.00 }}"
                    id='into_mnth11' name='into_mnth11' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 12 : </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->sum_mnth12) ? number_format(($financeData->sum_mnth12), 2, '.', '') : 0.00 }}"
                  id='sum_mnth12' name='sum_mnth12' placeholder='' type='text' readonly="true">
                  </div>
              </td>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->into_mnth12) ? number_format(($financeData->into_mnth12), 2, '.', '') : 0.00 }}"
                  id='into_mnth12' name='into_mnth12' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>
          
          <tr>
            <th><label class="strong"> TOTAL : </label></th>
            <td>
            </td>
            <td>
              <div class="form-group">
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{{!empty($financeData->projected_income_total) ? number_format(($financeData->projected_income_total), 2, '.', '') : 0.00 }}"
                    id='projected_income_total' name='projected_income_total' placeholder='' type='text' readonly="true">
              </div>
            </td>
          </tr>


          <tr>
            <th><label class="strong"> GST RECEIVED : </label></th>
            <td>
            </td>
            <td>
              <div class="form-group">
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->projected_gst_received) ? number_format(($financeData->projected_gst_received), 2, '.', '') : 0.00 }}"
                  id='projected_gst_received' name='projected_gst_received' placeholder='' type='text' readonly="true">
              </div>
            </td>
          </tr>

          <tr>
            <th><label class="strong"> TOTAL AFTER GST: </label></th>
            <td>
            </td>
            <td>
              <div class="form-group">
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{{!empty($financeData->projected_income_total_aft_gst) ? number_format(($financeData->projected_income_total_aft_gst), 2, '.', '') : 0.00 }}"
                    id='projected_income_total_aft_gst' name='projected_income_total_aft_gst' placeholder='' type='text' readonly="true">
              </div>
            </td>
          </tr>
          </table>
      </fieldset>
    </div>

  {{-- MONTHLY EXPENSES --}}
  <div class="col-md-6">
      <fieldset class="padding-15">
          <legend> MONTHLY EXPENSES </legend>
           <table class="table table-responsive" style="width: 100%;">
              <tr>
                <th><label class="strong"> MONTH 1  </label></th>
                <td>
                  <div class="form-group">
                      <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="{!! !empty($financeData->be_calculated) ? $financeData->be_calculated : 0.00 !!}"
                      id='be_mnth1' name='be_mnth1' placeholder='Business Expense' type='text' readonly="true">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{!! !empty($financeData->be_loan_repayment_amt) ? $financeData->be_loan_repayment_amt : 0.00 !!}"
                    id='loan_repay_mnth1' name='loan_repay_mnth1' placeholder='' type='text' readonly="true">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='exp_mnth1' name='exp_mnth1' placeholder='' type='text' readonly="true">
                  </div>
                </td>
              </tr>

              <tr>
                <th><label class="strong"> MONTH 2  </label></th>
                <td>
                  <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='be_mnth2' name='be_mnth2' placeholder='' type='text' readonly="true">
                  </div>
                </td>

                <td>
                  <div class="form-group">
                      <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='loan_repay_mnth2' name='loan_repay_mnth2' placeholder='' type='text' readonly="true">
                  </div>
                </td>

                <td>
                  <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='exp_mnth2' name='exp_mnth2' placeholder='' type='text' readonly="true">
                  </div>
                </td>
              </tr>
            
            <tr>
              <th><label class="strong"> MONTH 3  </label></th>
              <td>
                <div class="form-group">
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='be_mnth3' name='be_mnth3' placeholder='' type='text' readonly="true">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='loan_repay_mnth3' name='loan_repay_mnth3' placeholder='' type='text' readonly="true">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                    id='exp_mnth3' name='exp_mnth3' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>
            
            <tr>
              <th><label class="strong"> MONTH 4  </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='be_mnth4' name='be_mnth4' placeholder='' type='text' readonly="true">
                  </div>
              </td>

              <td>
                  <div class="form-group">
                      <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='loan_repay_mnth4' name='loan_repay_mnth4' placeholder='' type='text' readonly="true">
                  </div>
              </td>

              <td>
                  <div class="form-group">
                      <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='exp_mnth4' name='exp_mnth4' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>
            
            <tr>
              <th><label class="strong"> MONTH 5  </label></th>
              <td>
                
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='be_mnth5' name='be_mnth5' placeholder='' type='text' readonly="true">
                </div>
              </td>

              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='loan_repay_mnth5' name='loan_repay_mnth5' placeholder='' type='text' readonly="true">
                </div>
              </td>

              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='exp_mnth5' name='exp_mnth5' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 6  </label></th>
              <td>
                  <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='be_mnth6' name='be_mnth6' placeholder='' type='text' readonly="true">
                  </div>
              </td>

              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='loan_repay_mnth6' name='loan_repay_mnth6' placeholder='' type='text' readonly="true">
                </div>
              </td>

              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='exp_mnth6' name='exp_mnth6' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 7  </label></th>
              <td>
                  <div class="form-group">
                      <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="0.00"
                        id='be_mnth7' name='be_mnth7' placeholder='' type='text' readonly="true">

                  </div>
               </td>

               <td>
                  <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='loan_repay_mnth7' name='loan_repay_mnth7' placeholder='' type='text' readonly="true">
                  </div>
                </td>

                <td>
                  <div class="form-group">
                      <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='exp_mnth7' name='exp_mnth7' placeholder='' type='text' readonly="true">
                  </div>
                </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 8  </label></th>
              <td>  
                <div class="form-group">
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='be_mnth8' name='be_mnth8' placeholder='' type='text' readonly="true">
                </div>
              </td>

              <td>  
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='loan_repay_mnth8' name='loan_repay_mnth8' placeholder='' type='text' readonly="true">
                </div>
              </td>
              
              <td>
                  <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='exp_mnth8' name='exp_mnth8' placeholder='' type='text' readonly="true">
                  </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 9  </label></th>
              <td>  
                <div class="form-group">
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='be_mnth9' name='be_mnth9' placeholder='' type='text' readonly="true">
                </div>
              </td>  
                      
              <td>  
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='loan_repay_mnth9' name='loan_repay_mnth9' placeholder='' type='text' readonly="true">
                </div>
              </td>  

              <td>  
                <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='exp_mnth9' name='exp_mnth9' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 10  </label></th>
              <td>  
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='be_mnth10' name='be_mnth10' placeholder='' type='text' readonly="true">      
                </div>
              </td>
              <td>
                <div class="form-group">
                    <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="0.00"
                    id='loan_repay_mnth10' name='loan_repay_mnth10' placeholder='' type='text' readonly="true">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='exp_mnth10' name='exp_mnth10' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>
              <th><label class="strong"> MONTH 11  </label></th>
              <td> 
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='be_mnth11' name='be_mnth11' placeholder='' type='text' readonly="true">
                </div>
              </td>  

              <td> 
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='loan_repay_mnth11' name='loan_repay_mnth11' placeholder='' type='text' readonly="true">
                </div>
              </td>  

              <td>  
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='exp_mnth11' name='exp_mnth11' placeholder='' type='text' readonly="true">
                </div>
              </td>  
            </tr>

            <tr> 
              
              <th><label class="strong"> MONTH 12  </label></th>
              <td> 
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='be_mnth12' name='be_mnth12' placeholder='' type='text' readonly="true">
                </div>
              </td>

              <td>
                <div class="form-group">
                 <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="0.00"
                      id='loan_repay_mnth12' name='loan_repay_mnth12' placeholder='' type='text' readonly="true">
                </div>
              </td>

              <td>
                <div class="form-group">
                  <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="0.00"
                  id='exp_mnth12' name='exp_mnth12' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>  
              <td><label class="strong"> TOTAL : </label></td>
              <td></td>
              <td></td>
              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="{{!empty($financeData->mnthly_exp_total) ? $financeData->mnthly_exp_total : 0.00 }}"
                      id='mnthly_exp_total' name='mnthly_exp_total' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>  
              <td width="30%">
                <label class="strong"> GST PAID: </label>
              </td>
              <td></td>
              <td></td>
              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="{{!empty($financeData->mnthly_gst_received) ? $financeData->mnthly_gst_received : 0.00 }}"
                      id='mnthly_gst_received' name='mnthly_gst_received' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>

            <tr>  
              <td width="35%">
                <label class="strong"> TOTAL AFTER GST: </label>
              </td>
              <td></td>
              <td></td>
              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value="{{!empty($financeData->mnthly_total_aft_gst) ? $financeData->mnthly_total_aft_gst : 0.00 }}"
                      id='mnthly_total_aft_gst' name='mnthly_total_aft_gst' placeholder='' type='text' readonly="true">
                </div>
              </td>
            </tr>
        </table>
      </fieldset>
  </div>

  {{-- GST PAY SECTIOn --}}
  <div class="col-md-12">
      <fieldset class="padding-15">
          <legend> FINANCIAL SUMMARY </legend>
          <table id="financialSummaryTable" class="table table-responsive">
            <thead>
              <th> <label class="strong"> TOTAL </label> </th>
              <th> <label class="strong"> GST TO PAY </label> </th>
              <th> <label class="strong"> PROFIT BEFORE TAX </label> </th>
              <th> <label class="strong"> ANNUAL PROFIT REQUIRED </label> </th> 
              <th> <label class="strong"> TOTAL PROFIT BEFORE TAX </label> </th>
              {{-- @if($financeData->business_type != 'partnership') --}}
                <th class="if-partner"> <label class="strong business_taxes_label"> BUSINESS TAXES </label> </th>
                <th class="if-partner"> <label class="strong"> PROFIT AFTER TAX </label> </th>
              {{-- @endif --}}
            </thead>
            <tbody class="fs-table-body">
              <tr class="first-row"> 

                <td>
                  <div class="form-group">
                      <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value=""
                        id='total' name='total' placeholder='' type='text' readonly="true">
                  </div>
                </td>

              <td>
                  <div class="form-group">
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value=""
                        id='gst_to_pay' name='gst_to_pay' placeholder='' type='text' readonly="true">
                  </div>
              </td>

              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value=""
                      id='profit_befr_tax' name='profit_befr_tax' placeholder='' type='text' readonly="true">
                </div>
              </td>

              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value=""
                      id='fs_annual_prof_req' name='fs_annual_prof_req' placeholder='' type='text' readonly="true">
                </div>
              </td> 
              <td>
                <div class="form-group">
                    <input class='form-control'
                      data-rule-number='true' data-rule-required='true'
                      value=""
                      id='fs_total_prof_befr_tax' name='fs_total_prof_befr_tax' placeholder='' type='text' readonly="true">
                </div>
              </td>
              
              {{-- @if($financeData->business_type != 'partnership') --}}
                <td class="if-partner">
                  <div class="form-group">
                    <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value=""
                          id='business_taxes' name='business_taxes' placeholder='' type='text' readonly="true">
                  </div>
                </td>

                <td class="if-partner">
                  <div class="form-group">
                    <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value=""
                          id='fs_profit_after_tax' name='fs_profit_after_tax' placeholder='' type='text' readonly="true">
                  </div>
                </td>
              {{-- @endif --}}
              </tr>

              {{-- @if($financeData->business_type == 'partnership') --}}
              <tr class="partner-header">
                <th></th>
                <th></th>
                <th>PROFIT BEFORE TAX</th>
                <th>BUSINESS TAX</th>
                <th>PROFIT AFTER TAX</th>
              </tr>
              {{-- @endif --}}

              


            </tbody>
          </table>
      </fieldset>
  </div>

  {{-- MONTHLY PROFIT AND LOSS --}}
  <div class="col-md-12">
    <fieldset class="padding-15">
      <legend> MONTHLY PROFIT & LOSS </legend>

      <table class="table table-responsive">
        <tbody>
            {{-- <legend> MONTHLY PROFIT AND LOSS </legend> --}}
            <tr>
              <th>MONTH 1</th>
              <th>MONTH 2</th>
              <th>MONTH 3</th>
              <th>MONTH 4</th>
            </tr>
            <tr>
              <td>
                <input class='form-control'
                data-rule-number='true' data-rule-required='true'
                value=""
                id='prof_loss_mnth1' name='prof_loss_mnth1' placeholder='' type='text' readonly="true">
              </td>
            {{-- </tr> --}}

            {{-- <tr> --}}
              <td>
                  <input class='form-control'
                    data-rule-number='true' data-rule-required='true'
                    value="{{!empty($financeData->prof_loss_mnth2) ? $financeData->prof_loss_mnth2 : 0.00 }}"
                    id='prof_loss_mnth2' name='prof_loss_mnth2' placeholder='' type='text' readonly="true">
              </td>
            {{-- </tr> --}}


            {{-- <tr> --}}
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth3) ? $financeData->prof_loss_mnth3 : 0.00 }}"
                  id='prof_loss_mnth3' name='prof_loss_mnth3' placeholder='' type='text' readonly="true">
              </td>
            {{-- </tr> --}}
            
            {{-- <tr> --}}
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth4) ? $financeData->prof_loss_mnth4 : 0.00 }}"
                  id='prof_loss_mnth4' name='prof_loss_mnth4' placeholder='' type='text' readonly="true">
                </td>
            </tr>

            <tr>
              <th>MONTH 5</th>
              <th>MONTH 6</th>
              <th>MONTH 7</th>
              <th>MONTH 8</th>
            </tr>

            <tr>
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth5) ? $financeData->prof_loss_mnth5 : 0.00 }}"
                  id='prof_loss_mnth5' name='prof_loss_mnth5' placeholder='' type='text' readonly="true">
             </td>
            {{-- </tr> --}}

            {{-- <tr> --}}
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth6) ? $financeData->prof_loss_mnth6 : 0.00 }}"
                  id='prof_loss_mnth6' name='prof_loss_mnth6' placeholder='' type='text' readonly="true">
               </td>
            {{-- </tr> --}}

            {{-- <tr> --}}
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth7) ? $financeData->prof_loss_mnth7 : 0.00 }}"
                  id='prof_loss_mnth7' name='prof_loss_mnth7' placeholder='' type='text' readonly="true">
              </td>
            {{-- </tr> --}}

            {{-- <tr> --}}
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth8) ? $financeData->prof_loss_mnth8 : 0.00 }}"
                  id='prof_loss_mnth8' name='prof_loss_mnth8' placeholder='' type='text' readonly="true">
              </td>
            </tr>
            
            <tr>
              <th>MONTH 9</th>
              <th>MONTH 10</th>
              <th>MONTH 11</th>
              <th>MONTH 12</th>
            </tr>

            <tr>
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth9) ? $financeData->prof_loss_mnth9 : 0.00 }}"
                  id='prof_loss_mnth9' name='prof_loss_mnth9' placeholder='' type='text' readonly="true">
              </td>
            {{-- </tr> --}}

            {{-- <tr> --}}
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth10) ? $financeData->prof_loss_mnth10 : 0.00 }}"
                  id='prof_loss_mnth10' name='prof_loss_mnth10' placeholder='' type='text' readonly="true">
              </td>
            {{-- </tr> --}}

            {{-- <tr> --}}
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth11) ? $financeData->prof_loss_mnth11 : 0.00 }}"
                  id='prof_loss_mnth11' name='prof_loss_mnth11' placeholder='' type='text' readonly="true">
              </td>
            {{-- </tr> --}}
             
            {{-- <tr> --}}
              <td> 
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_mnth12) ? $financeData->prof_loss_mnth12 : 0.00 }}"
                  id='prof_loss_mnth12' name='prof_loss_mnth12' placeholder='' type='text' readonly="true">
              </td>
            </tr>

            <tr>
              <th>  </th>
              <th>  </th>
              <th>  </th>
              <th> TOTAL : </th>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td>
                <input class='form-control'
                  data-rule-number='true' data-rule-required='true'
                  value="{{!empty($financeData->prof_loss_total) ? $financeData->prof_loss_total : 0.00 }}"
                  id='prof_loss_total' name='prof_loss_total' placeholder='' type='text' readonly="true">
              </td>
            </tr>
        </tbody>
      
      </table>
    </fieldset>
  </div>



