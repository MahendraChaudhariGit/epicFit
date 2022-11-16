<div class="col-md-12">
    <fieldset class="padding-15">
        <legend>Lead generation requirements:</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Sessions required per week </label>
                    <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->weekly_sess_req) ? $financeData->weekly_sess_req :'' }}"
                            id='lead_gen_session_req_per_week' name='lead_gen_session_req_per_week' placeholder='' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> Clients required @ 6 per session </label>
                    <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->clients_req_to_break_even) ? $financeData->clients_req_to_break_even :'' }}"
                            id='total_client_req' name='total_client_req' placeholder='13 (weekly sessions required / average sessions per week per client' type='text' readonly="true">
                    </div>
                </div>

                 <div class="form-group">
                    <label class="strong"> Timeframe to capacity in weeks or months </label>
                    <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->lead_gen_timeframe_capicity) ? $financeData->lead_gen_timeframe_capicity :'' }}"
                            id='lead_gen_timeframe_capicity' name='lead_gen_timeframe_capicity' placeholder='' type='text'>
                    </div>
                </div>


                <div class="form-group">
                    <label class="strong">Projected Consultation conversion rate</label>
                    <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->projected_conv_rate) ? $financeData->projected_conv_rate :'' }}"
                        id='projected_conv_rate' name='projected_conv_rate' placeholder='50%' type='text'>
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="strong">Consultations required</label>
                        <div>
                            <input class='form-control'
                                data-rule-number='true' data-rule-required='true'
                                value="{{!empty($financeData->consultations_req) ? $financeData->consultations_req :'' }}"
                            id='consultations_req' name='consultations_req' placeholder='27 (total clients required / PCR 50%)' type='text' readonly="true">
                        </div>
                    </div>


                 <div class="form-group">
                    <label class="strong"> Contact conversion rate </label>
                    <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->lead_gen_contact_conv_rate) ? $financeData->lead_gen_contact_conv_rate :'' }}"
                            id='lead_gen_contact_conv_rate' name='lead_gen_contact_conv_rate' placeholder='' type='text'>
                    </div>
                </div>

                    <div class="form-group">
                        <label class="strong">Leads required</label>
                        <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->leads_req) ? $financeData->leads_req :'' }}"
                        id='leads_req' name='leads_req' placeholder='133 (consultations * 5)' type='text' readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                    <label class="strong">Average lead generation required per week for 52 weeks</label>
                        <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->avg_lead_gen_req_per_week_twlv) ? $financeData->avg_lead_gen_req_per_week_twlv :'' }}"
                        id='avg_lead_gen_req_per_week_twlv' name='avg_lead_gen_req_per_week_twlv' placeholder='11 (leads required/12)' type='text' readonly="true">
                        </div>
                    </div>


                 <div class="form-group">
                    <label class="strong"> Average consults a week </label>
                    <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->lead_gen_avg_consult_week) ? $financeData->lead_gen_avg_consult_week :'' }}"
                            id='lead_gen_avg_consult_week' name='lead_gen_avg_consult_week' placeholder='' type='text' readonly="true">
                    </div>
                </div>
                
                 <div class="form-group">
                    <label class="strong"> Average signed clients a week </label>
                    <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->lead_gen_avg_singed_client_week) ? $financeData->lead_gen_avg_singed_client_week :'' }}"
                            id='lead_gen_avg_singed_client_week' name='lead_gen_avg_singed_client_week' placeholder='' type='text' readonly="true">
                    </div>
                </div>
               
            </div>
        </div>
    </fieldset>
</div>
