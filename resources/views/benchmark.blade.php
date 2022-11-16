
<div id="form-container" class="container-fluid container-fullw bg-white">
    <div class="row">
        <div class="col-md-12">
            <!-- start: WIZARD FORM -->
            <div>
                <div id="benchmarkWizard" class="swMain">
                    <!-- start: WIZARD SEPS -->
                    <ul class="hidden">
                        <li>
                            <a href="#step-1">
                                <div class="stepNumber">
                                    1
                                </div>
                                <span class="stepDesc"><small> New Progression Session </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-2">
                                <div class="stepNumber">
                                    2
                                </div>
                                <span class="stepDesc"><small> External Factors </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-3">
                                <div class="stepNumber">
                                    3
                                </div>
                                <span class="stepDesc"><small> Measurements </small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-4">
                                <div class="stepNumber">
                                    4
                                </div>
                                <span class="stepDesc"><small> Fitness Testing </small></span>
                            </a>
                        </li>
                    </ul>
                    <!-- end: WIZARD SEPS -->

                    <!-- start: FORM WIZARD ACCORDION -->
                    <div class="panel-group epic-accordion" id="epic-accordion">
                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="1">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> New Progression Session <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>

                            </div>
                            <div class="panel-body">
                                <div id="step-1">
                                    {!! Form::open(['url' => '', 'id' => 'form-1']) !!}
                                      {!! displayAlert('', true)!!}
                                       <input type="hidden" name="benchmarkEditId" value="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="padding-15">
                                                    <legend>
                                                        New Progression Session
                                                    </legend>

                                                    <div class="row progression">
                                                        <ul class="bm_time_selectable">
                                                            <li class="col-xs-6 ui-widget-content ui-selected manual">Manual Time Entry</li>
                                                            <li class="col-xs-6 ui-widget-content automatic">Automatic Time Entry</li>
                                                            {!! Form::hidden('bm_time_opt', 'Manual Time Entry') !!}
                                                        </ul>
                                                    </div>

                                                    <div class="form-group bm_time_manual">
                                                        {!! Form::label('bm_time_day', 'Day *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_time_day', null, ['class' => 'form-control', 'required' => 'required','autocomplete' => 'off','readonly' => 'true']) !!}
                                                     <span class ="error"></span>   
                                                    </div>

                                                    <div class="form-group bm_time_manual clearfix">
                                                        {!! Form::label(null, 'Time *', ['class' => 'strong']) !!}
                                                        <div class="row">
                                                            <div class="col-md-6 form-group time_hour">
                                                                <select class="form-control hour-value" name="bm_time_hour" id="time_hour">
                                                                    <option data-hidden="true" value =" ">HOUR</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                </select>
                                                                <span class=" error hour-error "></span>

                                                            </div>
                                                            <div class="col-md-6 form-group time_min">
                                                                <select class="form-control min-value" name="bm_time_min" id="time_min">
                                                                    <option data-hidden="true" value = "">MINUTES</option>
                                                                    <option value="00">00</option>
                                                                    <option value="05">05</option>
                                                                    <option value="10">10</option>
                                                                    <option value="15">15</option>
                                                                    <option value="20">20</option>
                                                                    <option value="25">25</option>
                                                                    <option value="30">30</option>
                                                                    <option value="35">35</option>
                                                                    <option value="40">40</option>
                                                                    <option value="45">45</option>
                                                                    <option value="50">50</option>
                                                                    <option value="55">55</option>
                                                                </select>
                                                                <span class="min-error error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6"></div>
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-o bm_next-step btn-wide pull-right next-1">
                                                        Next <i class="fa fa-arrow-circle-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="2">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> External Factors <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <div id="step-2">
                                    {!! Form::open(['url' => '', 'id' => 'form-2']) !!}
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="padding-15">
                                                    <legend>
                                                        External Factors
                                                    </legend>
                                                    <div class="form-group">
                                                        {!! Form::label('bm_stress', 'Stress (Rate from 1 high to 10 low) *', ['class' => 'strong']) !!}
                                                        <div class="text-extra-large">
                                                            {!! Form::hidden(null, null, ['class' => 'rating-tooltip stress', 'data-filled' => 'fa fa-star m-r-18 text-primary', 'data-empty' => 'fa fa-star-o m-r-18']) !!}
                                                            <span class="label label-default"></span>
                                                            <span class="help-block "></span>
                                                          

                                                        </div>
                                                         
                                                     </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_sleep', 'Sleep (Rate from 1 bad to 10 good) *', ['class' => 'strong']) !!}
                                                        <div class="text-extra-large">
                                                            {!! Form::hidden(null, null, ['class' => 'rating-tooltip sleep', 'data-filled' => 'fa fa-star m-r-18 text-primary', 'data-empty' => 'fa fa-star-o m-r-18']) !!}
                                                            <span class="label label-default"></span>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_nutrit', 'Nutrition (Rate from 1 bad to 10 good) *', ['class' => 'strong']) !!}
                                                        <div class="text-extra-large">
                                                            {!! Form::hidden(null, null, ['class' => 'rating-tooltip nutrition', 'data-filled' => 'fa fa-star m-r-18 text-primary', 'data-empty' => 'fa fa-star-o m-r-18']) !!}
                                                            <span class="label label-default"></span>
                                                            <span class="help-block"></span>
                                                        </div>
                                                       
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_hydr', 'Hydration (Rate from 1 bad to 10 good) *', ['class' => 'strong']) !!}
                                                        <div class="text-extra-large">
                                                            {!! Form::hidden(null, null, ['class' => 'rating-tooltip hydration', 'data-filled' => 'fa fa-star m-r-18 text-primary', 'data-empty' => 'fa fa-star-o m-r-18']) !!}
                                                            <span class="label label-default"></span>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        
                                                        
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_humid', 'Humidity (Rate from 1 low to 10 high) *', ['class' => 'strong']) !!}
                                                        <div class="text-extra-large">
                                                            {!! Form::hidden(null, null, ['class' => 'rating-tooltip humidity', 'data-filled' => 'fa fa-star m-r-18 text-primary', 'data-empty' => 'fa fa-star-o m-r-18']) !!}
                                                            <span class="label label-default"></span>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_temp', 'Temperature *', ['class' => 'strong']) !!}
                                                        {!! Form::selectRange('bm_temp', 35, -10,null, ['class' => 'form-control temperature','id'=>'temperatureEdit']) !!}
                                                        <span></span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-o bm_back-step btn-wide pull-left">
                                                        <i class="fa fa-circle-arrow-left"></i> Back
                                                    </button>
                                                </div>
                                                <span></span>
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-o bm_next-step btn-wide pull-right">
                                                        Next <i class="fa fa-arrow-circle-right"></i>
                                                    </button>
                                                    <span></span>
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="3">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Measurements <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <div id="step-3">
                                    {!! Form::open(['url' => '', 'id' => 'form-3']) !!}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="padding-15">
                                                    <legend>
                                                        Measurements
                                                    </legend>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_waist', 'Waist (cm) *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_waist', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                                                        <span class="error"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_hips', 'Hips (cm) *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_hips', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                                                        <span class="error"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_height', 'Height (cm) *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_height', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                                                        
                                                        <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                                            <input type="checkbox" id="prevHeight" class="">
                                                            <label for="prevHeight" class="no-error-label">
                                                                <strong >Use previous height</strong>
                                                            </label>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_weight', 'Weight (kg) *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_weight', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                                                          
                                                        <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                                            <input type="checkbox" id="prevWeight" class="">
                                                            <label for="prevWeight" class="no-error-label">
                                                                <strong  >Use previous weight</strong>
                                                            </label>
                                                        </div>
                                                      
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-o bm_back-step btn-wide pull-left">
                                                        <i class="fa fa-circle-arrow-left"></i> Back
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-o bm_next-step btn-wide pull-right">
                                                        Next <i class="fa fa-arrow-circle-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-white">
                            <div class="panel-heading" data-step="4">
                                <h5 class="panel-title">
                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Fitness Testing <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i><i class="fa fa-chevron-down pull-right"></i></span>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <div id="step-4">
                                    {!! Form::open(['url' => '', 'id' => 'form-4']) !!}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="padding-15">
                                                    <legend>
                                                        Fitness Testing
                                                    </legend>

                                                    <div class="form-group">
                                                    <input type = "hidden" value ="" id = "last-insert-id-bm" name = "last_insert_id">
                                                        {!! Form::label('bm_pressups', 'Pressups (reps) *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_pressups', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                         </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_plank', 'Plank (min:sec) *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_plank', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_timetrial3k', '3km Time Trial Bike (min:sec) *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_timetrial3k', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>

                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <fieldset class="padding-15">
                                                    <legend>
                                                        Cardio Test (sec)
                                                    </legend>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_bpm1', 'Cardio Test BPM1 *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_bpm1', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                     </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_bpm2', 'Cardio Test BPM2 *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_bpm2', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                     </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_bpm3', 'Cardio Test BPM3 *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_bpm3', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_bpm4', 'Cardio Test BPM4 *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_bpm4', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_bpm5', 'Cardio Test BPM5 *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_bpm5', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('bm_bpm6', 'Cardio Test BPM6 *', ['class' => 'strong']) !!}
                                                        {!! Form::text('bm_bpm6', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                        <span class ="error"></span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-o bm_back-step btn-wide pull-left">
                                                        <i class="fa fa-circle-arrow-left"></i> Back
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary bm_finish-step btn-o btn-wide pull-right">
                                                        Finish
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <div class="clear-widget"></div>
                    </div>
                    <!-- end: FORM WIZARD ACCORDION -->
                </div>
            </div>
            <!-- end: WIZARD FORM -->
        </div>
    </div>
</div>