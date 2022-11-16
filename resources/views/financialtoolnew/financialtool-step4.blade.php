{{------------  4 SALES PROJECTIONS   ----------- --}}

  {{-- Session Required --}}
    <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>SESSION REQUIRED</legend>
                  <div class="form-group">
                      <label class="strong"> SESSION RATE: </label>
                      <div>
                      <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->session_rate) ? $financeData->session_rate :  0.00 }}"
                        id='session_rate' name='session_rate' placeholder='$0.00' type='text'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> SESSION REQUIRED: </label>
                      <div>
                      <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->session_req) ? $financeData->session_req : 0.00 }}"
                        id='session_req' name='session_req' placeholder='' type='text' readonly="true">
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> ANNUAL WORKING WEEKS: </label>
                      <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->ann_working_weeks)
                          ? $financeData->ann_working_weeks : 0.00 }}"
                          id='ann_working_weeks' name='ann_working_weeks' placeholder='' 
                          type='text'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> WEEKLY SESSION REQUIRED: </label>
                      <div>
                      <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->weekly_sess_req) ?
                        $financeData->weekly_sess_req : 0.00 }}"
                        id='weekly_sess_req' name='weekly_sess_req' placeholder='' type='text' readonly="true">
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> NUMBER OF CLIENTS PER SESSION: </label>
                      <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->no_of_clients) ? $financeData->no_of_clients : 0.00 }}"
                          id='no_of_clients' name='no_of_clients' placeholder='' type='text'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> SESSION SPOTS REQUIRED: </label>
                      <div>
                      <input readonly="true" class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->session_spots_req) ? $financeData->session_spots_req : 0.00 }}"
                          id='session_spots_req' name='session_spots_req' placeholder='' type='text'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> AVERAGE SESSIONS PER WEEK PER CLIENT: </label>
                      <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->avg_sess_pweek_pclient) ? $financeData->avg_sess_pweek_pclient : 0.00 }}"
                          id='avg_sess_pweek_pclient' name='avg_sess_pweek_pclient' placeholder='' type='text'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> 
                        CLIENTS REQUIRED TO BREAK EVEN:
                      </label>
                      <div>
                      <input readonly="true" class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->clients_req_to_break_even) ? $financeData->clients_req_to_break_even : 0.00 }}"
                          id='clients_req_to_break_even' name='clients_req_to_break_even' placeholder='' type='text'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> 
                          AVERAGE CLIENT SPEND:
                      </label>
                      <div>
                      <input readonly="true" class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->average_client_spend) ? $financeData->average_client_spend : 0.00 }}"
                        id='average_client_spend' name='average_client_spend' placeholder='' type='text'>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="strong"> 
                          ESTIMATED CLIENT CAPACITY:
                      </label>
                      <div>
                      <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->estimated_client_cap) ? $financeData->estimated_client_cap : 0 }}"
                        id='estimated_client_cap' name='estimated_client_cap' placeholder='' 
                        type='text'>
                      </div>
                  </div>
            </fieldset>
    </div>


  {{-- Client Required --}}

    <div class="col-md-6">
        <fieldset class="padding-15">
            <legend>LEAD :</legend>
              <div class="form-group">
                  <label class="strong"> CLIENTS ACTIVE CURRENT: </label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->client_active_current) ? $financeData->client_active_current : 0.00 }}"
                          id='client_active_current'  min="0" name='client_active_current' placeholder='' type='text'>
                  </div>
              </div>
              
              <div class="form-group">
                  <label class="strong"> SESSION REQUIRED PER WEEK: </label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->session_req_per_week) ? $financeData->session_req_per_week : 0.00 }}"
                          id='session_req_per_week'  min="0" name='session_req_per_week' placeholder='' type='text' readonly="true">
                  </div>
              </div>

              <div class="form-group">
                  <label class="strong clients_req_6_per_session_label">CLIENT REQUIRED @ {{ (int) @$financeData->no_of_clients }} PER SESSION: </label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->clients_req_6_per_session) ? $financeData->clients_req_6_per_session : 0.00 }}"
                          id='clients_req_6_per_session'  min="0" name='clients_req_6_per_session' placeholder='' type='text' readonly="true">
                  </div>
              </div>

               <div class="form-group">
                  <label class="strong"> TIMEFRAME TO CAPACITY IN WEEKS: </label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->timeframe_capicity) ? $financeData->timeframe_capicity : 0.00 }}"
                          id='timeframe_capicity'  min="0" name='timeframe_capicity' placeholder='' type='text'>
                  </div>
              </div>


              <div class="form-group">
                  <label class="strong">PROJECTED CONSULTATION CONVERSION RATE (%):</label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->projected_cons_conv_rate) ? $financeData->projected_cons_conv_rate : 0.00 }}"
                      id='projected_cons_conv_rate'  min="0" name='projected_cons_conv_rate' placeholder='50%' type='text'>
                  </div>
                  </div>
                  <div class="form-group">
                  <label class="strong">CONSULTATIONS REQUIRED:</label>
                      <div>
                          <input class='form-control'
                              data-rule-number='true' data-rule-required='true'
                              value="{{!empty($financeData->consultations_req) ? $financeData->consultations_req : 0.00 }}"
                          id='consultations_req'  min="0" name='consultations_req' placeholder='27 (total clients required / PCR 50%)' type='text' readonly="true">
                      </div>
                  </div>


               <div class="form-group">
                  <label class="strong"> CONTACT CONVERSION RATE (%): </label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->contact_conv_rate) ? $financeData->contact_conv_rate : 0.00 }}"
                          id='contact_conv_rate'  min="0" name='contact_conv_rate' min="0" max="100" placeholder='%' type='text'>
                  </div>
              </div>

                  <div class="form-group">
                      <label class="strong">LEADS REQUIRED:</label>
                      <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->leads_req) ? $financeData->leads_req : 0.00 }}"
                      id='leads_req'  min="0" name='leads_req' placeholder='133 (consultations * 5)' type='text' readonly="true">
                      </div>
                  </div>
                  <div class="form-group">
                  <label class="strong">AVERAGE LEAD GENERATION REQUIRED PER WEEK FOR 52 WEEKS:</label>
                      <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->avg_lead_gen_req_per_week_fr_52) ? $financeData->avg_lead_gen_req_per_week_fr_52 : 0.00 }}"
                      id='avg_lead_gen_req_per_week_fr_52'  min="0" name='avg_lead_gen_req_per_week_fr_52' placeholder='11 (leads required/12)' type='text' readonly="true">
                      </div>
                  </div>


               <div class="form-group">
                  <label class="strong"> AVERAGE CONSULTS A WEEK: </label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->avg_consult_week) ? $financeData->avg_consult_week : 0.00 }}"
                          id='avg_consult_week'  min="0" name='avg_consult_week' placeholder='' type='text' readonly="true">
                  </div>
              </div>
              
               <div class="form-group">
                  <label class="strong"> AVERAGE SIGNED CLIENTS A WEEK: </label>
                  <div>
                      <input class='form-control'
                          data-rule-number='true' data-rule-required='true'
                          value="{{!empty($financeData->avg_singed_client_week) ? $financeData->avg_singed_client_week : 0.00 }}"
                          id='avg_singed_client_week'  min="0" name='avg_singed_client_week' placeholder='' type='text' readonly="true">
                  </div>
              </div>
                   
        </fieldset>
    </div>

