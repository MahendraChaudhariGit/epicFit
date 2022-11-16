<input id="client_id" type="hidden" name="client_id" value="{{ $clients->id }}">
<input id="time_type" type="hidden" name="time_type" value="auto">
<div class="row">
    <div class="col-md-6 col-xs-12 update-benchmarks-form">
        <a class="btn btn-sm btn-default btn-squared benchmarkManualTime"> Manual Time Entry</a>
        <a class="btn btn-sm btn-default btn-squared benchmarkAutoTime remove"> Automatic Time Entry</a>
        <div class="benchmarkTimeManual remove">
            <h3 class="m-t-20">Benchmark Time</h3>
             <!--<div class="form-group">
           	<div id="benchmarkDay" class="col-md-12 col-xs-12" style="">
                <div class="" style="">
                    <label class="control-label col-md-2 col-xs-2" style="">Day</label>
                    <div class="col-md-4 col-xs-10" style="">
                        <input class="date-picker form-control form-control-inline benchmarkDay" value="" name="benchmarkDay" size="16">
                    </div>
                </div>
            </div>
            <div id="benchmarkTime">
                <span id="benchmarkTimeStart" class="col-md-12 col-xs-12" style="padding-top: 5px;padding-bottom: 5px;">
                    <label class="col-md-2 col-xs-2">Time</label>
                    <div class="col-md-2 col-xs-5" style="">
                        <select class="benchmarkHour form-control form-control-inline " style="min-height: 24px;" name="benchmarkStartHour">
                            <option value="">hour</option>
                            <option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>                                                        
                        </select>
                    </div>
    
                    <div class="col-md-2 col-xs-5" style="padding-left: 0px;">
                        <select class="benchmarkMinute form-control" style="min-height: 24px;" name="benchmarkStartMin">
                            <option value="">minute</option>
                            <option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option>                                                        
                        </select>
                    </div>
                </span>
            </div>
            </div>-->
            <div class="form-group">
                {!! Form::label('Day',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                    {!! Form::text('benchmarkDay', null, array('class'=>'date-picker form-control benchmarkDay')) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3">
                    Time
                </label>
                <div class="col-md-4">
                    <select class="benchmarkHour form-control" style="min-height: 24px;" name="benchmarkStartHour">
                        <option value="">hour</option><option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>                                                        
                    </select>
                </div>
                <div class="col-md-5">
                    <select class="benchmarkMinute form-control" style="min-height: 24px;" name="benchmarkStartMin">
                        <option value="">minute</option><option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option>                                                        
                    </select>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        
        <h3 class="m-t-20">External Factors</h3>
        <div class="form-group">
        	{!! Form::label('Stress', null, array('class'=>'col-md-3')) !!}
          	<div class="col-sm-9">
            	{!! Form::radio('stress', 'high',null,array('class'=>'benchmark_stress')) !!}
                {!! Form::label('High',null) !!}
       
                {!! Form::radio('stress', 'average',null,array('class'=>'benchmark_stress')) !!}
                {!! Form::label('Average',null) !!}
        
                {!! Form::radio('stress', 'low',null,array('class'=>'benchmark_stress')) !!}
                {!! Form::label('Low',null) !!}
          	</div>
        </div>
        <div class="form-group">
            {!! Form::label('Sleep',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::radio('sleep', 'good',null,array('class'=>'benchmark_sleep')) !!}
                {!! Form::label('Good',null) !!}
        
                {!! Form::radio('sleep', 'average',null,array('class'=>'benchmark_sleep')) !!}
                {!! Form::label('Average',null) !!}
        
                {!! Form::radio('sleep', 'poor',null,array('class'=>'benchmark_sleep')) !!}
                {!! Form::label('Poor',null) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Nutrition',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::radio('nutrition', 'good',null,array('class'=>'benchmark_nutrition')) !!}
                {!! Form::label('Good',null) !!}
        
                {!! Form::radio('nutrition', 'average',null,array('class'=>'benchmark_nutrition')) !!}
                {!! Form::label('Average',null) !!}
        
                {!! Form::radio('nutrition', 'poor',null,array('class'=>'benchmark_nutrition')) !!}
                {!! Form::label('Poor',null) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Hydration',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::radio('hydration', 'good',null,array('class'=>'benchmark_hydration')) !!}
                {!! Form::label('Good',null) !!}
        
                {!! Form::radio('hydration', 'average',null,array('class'=>'benchmark_hydration')) !!}
                {!! Form::label('Average',null) !!}
        
                {!! Form::radio('hydration', 'poor',null,array('class'=>'benchmark_hydration')) !!}
                {!! Form::label('Poor',null) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Humidity',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::radio('humidity', 'yes',null,array('class'=>'benchmark_humidity')) !!}
                {!! Form::label('Yes',null) !!}
        
                {!! Form::radio('humidity', 'no',null,array('class'=>'benchmark_humidity')) !!}
                {!! Form::label('No',null) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Temperature°C',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::selectRange('benchmarkTemperature', 35, -10,'10',array('class'=>'col-md-3 col-xs-12 form-control')) !!}<!--bday-control-->
            </div>
        </div>
    
        <h3 class="m-t-20">Measurements</h3>
        <div class="form-group">
            {!! Form::label('Waist (cm)',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::text('waist', null, array('class'=>'form-control measurements_waist')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Hips (cm)',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::text('hips', null, array('class'=>'form-control measurements_hips')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Height (cm)',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::text('height', null, array('class'=>'form-control measurements_height')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Weight (kg)',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::text('weight', null, array('class'=>'form-control measurements_weight')) !!}
            </div>
        </div>
        <hr>
    
        <a class="btn btn-sm btn-default btn-squared extraBenchmarks">Extra measurements</a>
        <div class="extraMeasurements m-t-10 remove">
            <div class="form-group">
                {!! Form::label('Neck',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('neck', null, array('class'=>'form-control measurements_neck')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Shoulders',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('shoulders', null,array('class'=>'form-control measurements_shoulders')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Chest',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('chest', null,array('class'=>'form-control measurements_chest')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Bicep',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('bicep', null,array('class'=>'form-control measurements_bicep')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Forearm',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('forearm', null,array('class'=>'form-control measurements_forearm')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Bellybutton',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('bellybutton', null,array('class'=>'form-control measurements_bellybutton')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Thighs',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('thighs', null,array('class'=>'form-control measurements_thighs')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('Calves',null,array('class'=>'col-md-3')) !!}
                <div class="col-md-9">
                	{!! Form::text('calves', null,array('class'=>'form-control measurements_calves')) !!}
                </div>
            </div>
        </div>

        <h3 class="m-t-20">Fitness test</h3>
        <div class="form-group">
            {!! Form::label('Pressups (reps)',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::text('pressups', null,array('class'=>'form-control measurements_pressups')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Plank (min:sec)',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::text('plank', null,array('class'=>'form-control measurements_plank')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('3km time trial bike (min:sec)',null,array('class'=>'col-md-3')) !!}
            <div class="col-md-9">
            	{!! Form::text('timetrial3k', null,array('class'=>'form-control measurements_3ktimetrial')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Cardio test (sec)',null,array('class'=>'col-md-12')) !!}
            
            <p class="col-md-1">BPM1</p>
            <div class="col-md-2">
    
            {!! Form::text('cardiobpm1', null,array('class'=>'form-control measurements_cardiobpm1')) !!}
            </div>
            <p class="col-md-1">BPM2</p>
            <div class="col-md-2">
    
            {!! Form::text('cardiobpm2', null,array('class'=>'form-control measurements_cardiobpm2')) !!}
            </div>
            <p class="col-md-1">BPM3</p>
            <div class="col-md-2">
    
            {!! Form::text('cardiobpm3', null,array('class'=>'form-control measurements_cardiobpm3')) !!}
            </div>
            <p class="col-md-1">BPM4</p>
            <div class="col-md-2">
    
            {!! Form::text('cardiobpm4', null,array('class'=>'form-control measurements_cardiobpm4')) !!}
            </div>
            <p class="col-md-1">BPM5</p>
            <div class="col-md-2">
    
            {!! Form::text('cardiobpm5', null,array('class'=>'form-control measurements_cardiobpm5')) !!}
            </div>
            <p class="col-md-1">BPM6</p>
            <div class="col-md-2">
    
            {!! Form::text('cardiobpm6', null,array('class'=>'form-control measurements_cardiobpm6')) !!}
            </div>
        </div>
        <!--<div style="clear:both;"></div>-->
        
        <?php
        $occupations = array('banker'=>false,'builder'=>false,'tailer'=>false);
        /*
        if(isset($clients)){
        echo '<pre>';
        var_dump($clients->occupation);
        $multipleOccupations = false;
        /
        $banker = null;
        $builder = null;
        $tailer = null;
        /
          var_dump($occupations);
          if(isset($clients->occupation) && ($clients->occupation <> '')){
            $occ = explode(',',$clients->occupation);
            if(count($occ)>0){
              $multipleOccupations = true;
              foreach ($occ as $occV) {
                $occupations[$occV] = true;
              }
            }
    
          }
          echo '</pre>';
        }
        */
        /*
        $banker = ($occupations['banker'] == true) ? "true" : "false";
        $builder = ($occupations['builder'] == true) ? "true" : "false";
        $tailer = ($occupations['tailer'] == true) ? "true" : "false";
        */
        /*var_dump($occupations);
        var_dump($banker);
        var_dump($builder);
        var_dump($tailer);
        */
        ?>
        <!--<div style="clear:both;"></div>-->
    
        <div class="benchmarkError remove">
            <h3>Please make sure the following fields aren't empty:</h3>
            <span class="benchmarkDayError">Day</span>
            <span class="benchmarkHourError">Hour</span>
            <span class="benchmarkMinError">Min</span>
            <span class="benchmarkWaistError">Waist</span>
            <span class="benchmarkHipsError">Hips</span>
            <span class="benchmarkHeightError">Height</span>
            <span class="benchmarkWeightError">Weight</span>
            <span class="benchmarkStressError">Stress</span>
            <span class="benchmarkSleepError">Sleep</span>
            <span class="benchmarkNutritionError">Nutrition</span>
            <span class="benchmarkHydrationError">Hydration</span>
            <span class="benchmarkHumidityError">Humidity</span>
        </div>
        <div class="form-group">
        	{!! Form::button('<i class="fa fa-refresh fa-spin remove"></i> Add benchmarks',array('type' => 'submit', 'class' => 'btn btn-primary add-benchmark-submit pull-right')) !!}
        </div>
    </div>
</div>
