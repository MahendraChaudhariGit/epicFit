<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>Financial Timeframe</legend>
	     <div class="form-group">
	     	<label> Financial Timeframe : </label>
	        <select id="financial_time_frame" name="financial_time_frame" class="form-control financial_time_frame">
	            <option value="weekly" 
	            {!! ($timeFrame) && $timeFrame->financial_time_frame == 'weekly' ? 'selected' : '' !!}>Weekly</option>
	            <option value="fortnightly" {!! ($timeFrame) && $timeFrame->financial_time_frame == 'fortnightly' ? 'selected' : '' !!}>Fortnightly</option>
	            <option value="monthly" {!! ($timeFrame) && $timeFrame->financial_time_frame == 'monthly' ? 'selected' : '' !!}>Monthly</option>
	            <option value="annually" {!! ($timeFrame) && $timeFrame->financial_time_frame == 'annually' ? 'selected' : '' !!}>Annually</option>
	        </select>
	    </div>
    </fieldset>
</div>