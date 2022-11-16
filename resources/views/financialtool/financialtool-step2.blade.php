<div class="col-md-12">
    <fieldset class="padding-15">
        <legend>Turnover & profit required</legend>
        <div class="row">
        	<div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Turnover Required (Excl. Profit and Tax) </label>
                    <div>
                    <input class='form-control'
						data-rule-number='true' data-rule-required='true'
						id='turnover_req' name='turnover_req'
						value="{{ !empty($financeData->turnover_req)  ? $financeData->turnover_req : ''}}"
						placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> Profit Required (Excl. Tax) </label>
                    <div>
                    <input class='form-control'  id='profit_req'
						data-rule-number='true' data-rule-required='true'
						value="{{ !empty($financeData->profit_req)  ? $financeData->profit_req : ''}}"
						name='profit_req' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-12">
    <fieldset class="padding-15">
        <legend>Provision for tax</legend>
        <div class="row">
        	<div class="col-md-12">
                <div class="form-group">
                    <label class="strong gst-label"> Gst ( 0% ) </label>
                    <div>
                    <input class='form-control'
						data-rule-number='true' data-rule-required='true'
						value="{{!empty($financeData->gst) ? $financeData->gst :'' }}"
						id='gst' name='gst' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> Company tax </label>
                    <div>
                    <input class='form-control'  id='company_tax'
						data-rule-number='true' data-rule-required='true'
						value="{{ !empty($financeData->company_tax)  ? $financeData->company_tax : 0 }}"
						name='company_tax' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> Personal tax </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->personal_tax) ? $financeData->personal_tax : 0 }}"
                        id='personal_tax' name='personal_tax' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>

                <div class="form-group">
                    <label class="strong"> Partnership tax </label>
                    <div>
                        <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->partnership_tax) ? $financeData->partnership_tax : 0 }}"
                        id='partnership_tax' name='partnership_tax' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-12">
    <fieldset class="padding-15">
        <legend>Total income required</legend>
        <div class="row">
        	<div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Add turnover and taxes </label>
                    <div>
                    <input class='form-control'
						data-rule-number='true' data-rule-required='true'
						value="{{!empty ($financeData->turnover_and_taxes) ? $financeData->turnover_and_taxes :'' }}"
						id='turnover_and_taxes' name='turnover_and_taxes' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-6">
        <fieldset class="padding-15">
            <legend>Sesions required</legend>
            <div class="row">
            	<div class="col-md-12">
                    <div class="form-group">
                        <label class="strong"> Equivalent full session charge out rate </label>
                        <div>
                        <input class='form-control'
    						data-rule-number='true' data-rule-required='true'
    						value="{{!empty($financeData->eqv_full_sess_cor) ? $financeData->eqv_full_sess_cor :'' }}"
    						id='eqv_full_sess_cor' name='eqv_full_sess_cor' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> Equivalent full sessions required for total income </label>
                        <div>
                        <input class='form-control'
    						data-rule-number='true' data-rule-required='true'
    						value="{{!empty($financeData->eqv_full_sess_req_for_tot_inc) ? $financeData->eqv_full_sess_req_for_tot_inc :'' }}"
    						id='eqv_full_sess_req_for_tot_inc' name='eqv_full_sess_req_for_tot_inc' placeholder='$11,200.00' type='text' readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> Annual working weeks </label>
                        <div>
                        <input class='form-control'
    						data-rule-number='true' data-rule-required='true'
    						value="{{!empty($financeData->ann_working_weeks)
    						? $financeData->ann_working_weeks :'' }}"
    						id='ann_working_weeks' name='ann_working_weeks' placeholder='$11,200.00' 
                            type='text'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> Weekly sessions required </label>
                        <div>
                        <input class='form-control'
    						data-rule-number='true' data-rule-required='true'
    						value="{{!empty($financeData->weekly_sess_req) ?
    						$financeData->weekly_sess_req :'' }}"
    						id='weekly_sess_req' name='weekly_sess_req' placeholder='$11,200.00' type='text' readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> Number of clients required </label>
                        <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->no_of_clients) ? $financeData->no_of_clients :'' }}"
                            id='no_of_clients' name='no_of_clients' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> Session Spots Required </label>
                        <div>
                        <input readonly="true" class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->session_spots_req) ? $financeData->session_spots_req :'' }}"
                            id='session_spots_req' name='session_spots_req' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> Average sessions per week per client </label>
                        <div>
                        <input class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->avg_sess_pweek_pclient) ? $financeData->avg_sess_pweek_pclient :'' }}"
                            id='avg_sess_pweek_pclient' name='avg_sess_pweek_pclient' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> 
                            Clients Required To Break Even
                        </label>
                        <div>
                        <input readonly="true" class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->clients_req_to_break_even) ? $financeData->clients_req_to_break_even :'' }}"
                            id='clients_req_to_break_even' name='clients_req_to_break_even' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> 
                            Average Client Spend
                        </label>
                        <div>
                        <input readonly="true" class='form-control'
    						data-rule-number='true' data-rule-required='true'
    						value="{{!empty($financeData->average_client_spend) ? $financeData->average_client_spend :'' }}"
    						id='average_client_spend' name='average_client_spend' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
                    
                </div>
            </div>
        </fieldset>
</div>

<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>Clients required</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="strong"> Equivalent full session charge out rate </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->clients_req_eqv_full_sess_cor) ? $financeData->clients_req_eqv_full_sess_cor :'' }}"
                        id='clients_req_eqv_full_sess_cor' name='clients_req_eqv_full_sess_cor' placeholder='$11,200.00' type='text'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Equivalent full sessions required for total income </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->clients_req_eqv_full_sess_req_for_tot_inc) ? $financeData->clients_req_eqv_full_sess_req_for_tot_inc :'' }}"
                        id='clients_req_eqv_full_sess_req_for_tot_inc' name='clients_req_eqv_full_sess_req_for_tot_inc' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Annual working weeks </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->clients_req_ann_working_weeks)
                        ? $financeData->clients_req_ann_working_weeks :'' }}"
                        id='clients_req_ann_working_weeks' name='clients_req_ann_working_weeks' placeholder='$11,200.00' type='text'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Weekly sessions required </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->clients_req_weekly_sess_req) ?
                        $financeData->clients_req_weekly_sess_req :'' }}"
                        id='clients_req_weekly_sess_req' name='clients_req_weekly_sess_req' placeholder='$11,200.00' type='text' readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Number of clients required </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->clients_req_no_of_clients) ? $financeData->clients_req_no_of_clients :'' }}"
                        id='clients_req_no_of_clients' name='clients_req_no_of_clients' placeholder='$11,200.00' type='text'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Session Spots Required </label>
                    <div>
                    <input readonly="true" class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->client_session_spots_req) ? $financeData->client_session_spots_req :'' }}"
                        id='client_session_spots_req' name='client_session_spots_req' placeholder='$11,200.00' type='text'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="strong"> Average sessions per week per client </label>
                    <div>
                    <input class='form-control'
                        data-rule-number='true' data-rule-required='true'
                        value="{{!empty($financeData->clients_req_avg_sess_pweek_pclient) ? $financeData->clients_req_avg_sess_pweek_pclient :'' }}"
                        id='clients_req_avg_sess_pweek_pclient' name='clients_req_avg_sess_pweek_pclient' placeholder='$11,200.00' type='text'>
                    </div>
                </div>
                    <div class="form-group">
                        <label class="strong"> 
                            Clients Required To Break Even
                        </label>
                        <div>
                        <input readonly="true" class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->client_clients_req_to_break_even) ? $financeData->client_clients_req_to_break_even :'' }}"
                            id='client_clients_req_to_break_even' name='client_clients_req_to_break_even' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="strong"> 
                            Average Client Spend
                        </label>
                        <div>
                        <input readonly="true" class='form-control'
                            data-rule-number='true' data-rule-required='true'
                            value="{{!empty($financeData->client_average_client_spend) ? $financeData->client_average_client_spend :'' }}"
                            id='client_average_client_spend' name='client_average_client_spend' placeholder='$11,200.00' type='text'>
                        </div>
                    </div>
            </div>
        </div>
    </fieldset>
</div>

