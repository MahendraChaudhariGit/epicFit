<style type="text/css">
  .dtp {
    z-index: 99999999999;
}
.error{
    color: red;
}
</style>
<!-- start:model personel diay-->


<div id="myModal1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content" id="modalContent">
            <div class="modal-header">
                <button type="button" class="close close m-t--10" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body personal-diary-popup">
                <input type="hidden" id="fetchWeight" value="" name="weight">
                <input type="hidden" name="eventDate" value="">
                <div class="tabbable">
                    <ul  class="nav nav-tabs">
                        <li class="active">
                            <a  href="#PersonalDiary" data-toggle="tab">
                                <img src="{{asset('result/images/Diary.png')}}">
                            </a>
                        </li>
                        <li>
                            <a href="#Measurements" data-toggle="tab">
                                <img src="{{asset('result/images/Weight Management.png')}}">
                            </a>
                        </li>
                        <li>
                            <a href="#Statistics" data-toggle="tab">
                                <img src="{{asset('result/images/Benchmark.png')}}">
                            </a>
                        </li>
                        <li>
                            <a href="#NutritionalJournal" data-toggle="tab">
                                <img src="{{asset('result/images/Eating Healthier.png')}}">
                            </a>
                        </li>
                        <li>
                            <a href="#HydrationJournal" data-toggle="tab" id="checkweight">
                                <img src="{{asset('result/images/Hydration.png')}}">
                            </a>


                        </li>
                        <li>
                            <a href="#SleepJournal" data-toggle="tab">
                                <img src="{{asset('result/images/Improve Sleep.png')}}">
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content clearfix">
                        <div class="tab-pane active" id="PersonalDiary">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Personal Diary</h5>
                                    <div class="form-group"> 
                                        <textarea name="diary_content" id="diaryContent" class="textarea_height"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div  id="starBox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="stressSection">
                                            <h3>Stress (Rate from 1 high to 10 low)</h3>
                                            <div class="bottle-scroll-bar common-scrollbar arrowicon">
                                                  <span class="smallwidth stress-range-value">0</span><span class="smallwidth">&nbsp;Star </span>
                                                <div class="number">
                                                    <div class="">
                                                        <span class="minus" id="stressMinus">-</span>
                                                        <input class="stress_rate" name="stress_rate" type="range" value="1" min="1" max="10" step="1">
                                                        <span class="plus" id="stressplus">+</span>
                                                    </div>
                                                </div>
                                              
                                            </div>
                                            {{-- <div class="star-rate"  id="stress_rate">
                                                <input type="radio" id="star10" name="stress_rate" value="10" />
                                                <label for="star10" title="text">
                                                    <span class="select-multiple">10</span>
                                                </label>
                                                <input type="radio" id="star9" name="stress_rate" value="9" />
                                                <label for="star9" title="text">
                                                    <span class="select-multiple">9</span>
                                                </label>
                                                <input type="radio" id="star8" name="stress_rate" value="8" />
                                                <label for="star8" title="text">
                                                    <span class="select-multiple">8</span>
                                                </label>
                                                <input type="radio" id="star7" name="stress_rate" value="7" />
                                                <label for="star7" title="text">
                                                    <span class="select-multiple">7</span>
                                                </label>
                                                <input type="radio" id="star6" name="stress_rate" value="6" />
                                                <label for="star6" title="text">
                                                    <span class="select-multiple">6</span>
                                                </label>
                                                <input type="radio" id="star5" name="stress_rate" value="5" />
                                                <label for="star5" title="text">
                                                    <span class="select-multiple">5</span>
                                                </label>
                                                <input type="radio" id="star4" name="stress_rate" value="4" />
                                                <label for="star4" title="text">
                                                    <span class="select-multiple">4</span>
                                                </label>
                                                <input type="radio" id="star3" name="stress_rate" value="3" />
                                                <label for="star3" title="text">
                                                    <span class="select-multiple">3</span>
                                                </label>
                                                <input type="radio" id="star2" name="stress_rate" value="2" />
                                                <label for="star2" title="text">
                                                    <span class="select-multiple">2</span>
                                                </label>
                                                <input type="radio" id="star1" name="stress_rate" value="1" />
                                                <label for="star1" title="text">
                                                    <span class="select-multiple">1</span>
                                                </label>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="humiditySection">
                                            <h3>Humidity (Rate from 1 low to 10 high)</h3>
                                            <div class="bottle-scroll-bar common-scrollbar arrowicon">
                                                <span class="smallwidth humidity-range-value">0</span><span class="smallwidth">&nbsp;Star </span>
                                                <div class="number">
                                                    <div class="">
                                                        <span class="minus" id="humidityMinus">-</span>
                                                        <input class="humidity" name="humidity" type="range" value="1" min="1" max="10" step="1">
                                                        <span class="plus" id="humidityplus">+</span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            {{-- <div class="star-rate" id="humidity">
                                                <input type="radio" id="humidity10" name="humidity" value="10" />
                                                <label for="humidity10" title="text">
                                                    <span class="select-multiple">10</span>
                                                </label>
                                                <input type="radio" id="humidity9" name="humidity" value="9" />
                                                <label for="humidity9" title="text">
                                                    <span class="select-multiple">9</span>
                                                </label>
                                                <input type="radio" id="humidity8" name="humidity" value="8" />
                                                <label for="humidity8" title="text">
                                                    <span class="select-multiple">8</span>
                                                </label>
                                                <input type="radio" id="humidity7" name="humidity" value="7" />
                                                <label for="humidity7" title="text">
                                                    <span class="select-multiple">7</span>
                                                </label>
                                                <input type="radio" id="humidity6" name="humidity" value="6" />
                                                <label for="humidity6" title="text">
                                                    <span class="select-multiple">6</span>
                                                </label>
                                                <input type="radio" id="humidity5" name="humidity" value="5" />
                                                <label for="humidity5" title="text">
                                                    <span class="select-multiple">5</span>
                                                </label>
                                                <input type="radio" id="humidity4" name="rate" value="4" />
                                                <label for="humidity4" title="text">
                                                    <span class="select-multiple">4</span>
                                                </label>
                                                <input type="radio" id="humidity3" name="humidity" value="3" />
                                                <label for="humidity3" title="text">
                                                    <span class="select-multiple">3</span>
                                                </label>
                                                <input type="radio" id="humidity2" name="humidity" value="2" />
                                                <label for="humidity2" title="text">
                                                    <span class="select-multiple">2</span>
                                                </label>
                                                <input type="radio" id="humidity1" name="humidity" value="1" />
                                                <label for="humidity1" title="text">
                                                    <span class="select-multiple">1</span>
                                                </label>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h3>Temperature</h3>
                                        <select class="form-control" id="temperatureEdit" name="temp">
                                            <option value="35">35</option>
                                            <option value="34">34</option>
                                            <option value="33">33</option>
                                            <option value="32">32</option>
                                            <option value="31">31</option>
                                            <option value="30">30</option>
                                            <option value="29">29</option>
                                            <option value="28">28</option>
                                            <option value="27">27</option>
                                            <option value="26">26</option>
                                            <option value="25">25</option>
                                            <option value="24">24</option>
                                            <option value="23">23</option>
                                            <option value="22">22</option>
                                            <option value="21">21</option>
                                            <option value="20">20</option>
                                            <option value="19">19</option>
                                            <option value="18">18</option>
                                            <option value="17">17</option>
                                            <option value="16">16</option>
                                            <option value="15">15</option>
                                            <option value="14">14</option>
                                            <option value="13">13</option>
                                            <option value="12">12</option>
                                            <option value="11">11</option>
                                            <option value="10">10</option>
                                            <option value="9">9</option>
                                            <option value="8">8</option>
                                            <option value="7">7</option>
                                            <option value="6">6</option>
                                            <option value="5">5</option>
                                            <option value="4">4</option>
                                            <option value="3">3</option>
                                            <option value="2">2</option>
                                            <option value="1">1</option>
                                            <option value="0">0</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 col-ms-12 col-xs-12 text-center">
                                        <button type="button" class="cancl-btn" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="save-btn saveStats">Save</button>
                                        <img src="{{asset('result/images/epic-icon-orenge.png')}}" class="bottom_logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="Measurements">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Personal Measurements</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">                
                                        

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Chest</label>
                                                <input type="number" name="chest" id="chest" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Neck</label>
                                                <input type="number" name="neck" id="neck" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Bicep R</label>
                                                <input type="number" name="bicep_r" value="" id="bicepR">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Bicep L</label>
                                                <input type="number" name="bicep_l" id="bicepL" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box disablefield">
                                                <label>Forearm R</label>
                                                <input type="number" name="forearm_r" value="" id="forearmR">
                                            </div>
                                        </div>
                                                                               
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box disablefield">
                                                <label>Formearm L</label>
                                                <input type="number" name="forearm_l" value="" id="forearmL">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Abdomen</label>
                                                <input type="number" name="waist" value="" id="waist">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Hip</label>
                                                <input type="number" name="hip" value="" id="hip">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Thigh R</label>
                                                <input type="number" name="thigh_r" value="" id="thighR">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Thigh L</label>
                                                <input type="number" name="thigh_l" value="" id="thighL">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box disablefield">
                                                <label>Calf R</label>
                                                <input type="number" name="calf_r" value="" id="calfR">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box disablefield">
                                                <label>Calf L</label>
                                                <input type="number" name="calf_l" value="" id="calfL">
                                            </div>
                                        </div>
                                        <div class="showallbtn">
                                            <label>Show All</label>
                                        </div>
                                    </div>            
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <img src="{{asset('result/images/MALE-BODY.png')}}" class="img-responsive">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Weight<span class="kg_show">(Kg)</span>
                                                <span class="pound_show hidden">(Pound)</span></label>
                                                <button type="button" class="btn btn-primary btn-o btn-sm p-y-0 bg-none mli-10 pull-right" id="convertP">Show Imperial</button>
                                                <button type="button" class="btn btn-primary hidden btn-o btn-sm p-y-0 bg-none mli-10 pull-right" id="conKg">Show Metric</button>
                                                <input type="number" name="weight" value="" id="weight_m">
                                                
                                                <input type="hidden" name="weightUnit" value="{{ isset($measurementData->weightUnit) && $measurementData->weightUnit !=null?$measurementData->weightUnit:'Metric' }}">
                                                  
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="measurements_box">
                                                <label>Height<span class="cm_show">(cm)</span>
                                                <span class="inches_show hidden">(inches)</span></label>
                                                <button type="button" class="btn btn-primary btn-o btn-sm p-y-0 bg-none mli-10 pull-right" id="convert-inches">Show in inches</button>
                                                <button type="button" class="btn btn-primary hidden btn-o btn-sm p-y-0 bg-none mli-10 pull-right" id="convert-cm">Show in cm</button>
                                                <input type="number" name="height" value="" id="height_m">
                                                
                                                <input type="hidden" name="heightUnit" value="{{ isset($measurementData->heightUnit) && $measurementData->heightUnit !=null?$measurementData->heightUnit:'cm' }}">
                                                  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 col-ms-12 col-xs-12 text-center">
                                        <button type="button" class="cancl-btn" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="save-btn saveStats">Save</button>
                                        <img src="{{asset('result/images/epic-icon-orenge.png')}}" class="bottom_logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="Statistics">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Personal Stastic</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>BFP (%)</label>
                                        <input type="number" name="bfp_kg" value="00" id="bfp_kg">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>SMM (kg)</label>
                                        <input type="number"  name="smm_kg" value="00" id="smm_kg">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>BMR (KCal)</label>
                                        <input type="number"name="bmr_kg" value="00" id="bmr_kg">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>BMI (kg/m2)</label>
                                        <input type="number" name="bmi_kg" id="bmi_kg" value="00">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>BFM (kg)</label>
                                        <input type="number" name="sleep_kg" id="sleep_kg" value="00">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>H/W Ratio</label>
                                        <input type="number"  name="h_w_ratio" id="h_w_ratio" value="00">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>Vis Fat</label>
                                        <input type="number"  name="vis_eat_kg" id="vis_eat_kg" value="00">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box">
                                        <label>Pulse (bpm)</label>
                                        <input type="number" name="pulsed_kg" id="pulsed_kg" value="00">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="stastic_box ">
                                        <label>Blood Pressure (mmHg)</label>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="number" id="bp_mm" name="bp_mm" class="form-control" style="background: #fff;border: 1px solid;">
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="number" id="bp_hg" name="bp_hg" class="form-control" style="background: #fff;border: 1px solid;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group"> 
                                        <textarea name="extra_input" id="extra_input" class="textarea_height"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 col-ms-12 col-xs-12 text-center">
                                        <button type="button" class="cancl-btn" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="save-btn saveStats">Save</button>
                                        <img src="{{asset('result/images/epic-icon-orenge.png')}}" class="bottom_logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="NutritionalJournal">
                            <form id="nutritionalFormModal">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Nutritional Journal</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="strong">When</label>
                                            <select class="form-control" name="cat_id" id="catId">
                                                <option value="{{isset($catType)?$catType['Breakfast']:''}}" data-is-snack="0">Breakfast</option>
                                                <option value="{{isset($catType)?$catType['Snack']:''}}" data-is-snack="1" data-snack-type="1">Snack 1</option>
                                                <option value="{{isset($catType)?$catType['Lunch']:''}}" data-is-snack="0">Lunch</option>
                                                <option value="{{isset($catType)?$catType['Snack']:''}}" data-is-snack="1" data-snack-type="2">Snack 2</option>
                                                <option value="{{isset($catType)?$catType['Dinner']:''}}" data-is-snack="0">Dinner</option>
                                                <option value="{{isset($catType)?$catType['Snack']:''}}" data-is-snack="1" data-snack-type="3">Snack 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="add_time_section">
                                            <ul class="time_selectable">
                                                <li  class="manual_time time_opt" data-time-opt="manual">Manual Time Entry</li>
                                                <li class="automatic_time active time_opt" data-time-opt="automatic">Automatic Time Entry</li>
                                            </ul>
                                            <input type="hidden" name="time_opt" id="time_opt" value="">
                                            <input type="hidden" name="nutritionalTime" id="automaticTime" value="">
                                        </div>
                                        <div class="form-group add_time_manual clearfix">
                                            <label>Time <a href="javascript:void(0)" class="nav-link nutritionDatetimePicker">Change</a></label>
                                            <span class="nutri-time-span" data-val="09:00:00">09:00 AM</span>
                                            {{-- <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <select class="form-control hour-value" name="bm_time_hour" id="time_hour" required="required">
                                                        <option data-hidden="true" value ="">HOUR</option>
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
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <select class="form-control min-value" name="bm_time_min" id="time_min" required>
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
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group" id="hungerSection">
                                            <h3>Hunger Level (Rated from 1 Nit Hungry to 10 Famished)</h3>
                                            <div class="bottle-scroll-bar common-scrollbar arrowicon">
                                                <span class="smallwidth hunger-range-value">1</span><span class="smallwidth">&nbsp;Star</span>
                                                <div class="number">
                                                    <div class="">
                                                        <span class="minus" id="hungerMinus">-</span>
                                                        <input class="hunger_rate" name="hunger_rate" type="range" value="1" min="1" max="10" step="1">
                                                        <span class="plus" id="hungerplus">+</span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            {{-- <div class="star-rate">
                                                <input type="radio" id="hungerStar10" name="hunger_rate" value="10" required/>
                                                <label for="hungerStar10" title="text">
                                                    <span class="select-multiple">10</span>
                                                </label>
                                                <input type="radio" id="hungerStar9" name="hunger_rate" value="9" required/>
                                                <label for="hungerStar9" title="text">
                                                    <span class="select-multiple">9</span>
                                                </label>
                                                <input type="radio" id="hungerStar8" name="hunger_rate" value="8" required/>
                                                <label for="hungerStar8" title="text">
                                                    <span class="select-multiple">8</span>
                                                </label>
                                                <input type="radio" id="hungerStar7" name="hunger_rate" value="7" required/>
                                                <label for="hungerStar7" title="text">
                                                    <span class="select-multiple">7</span>
                                                </label>
                                                <input type="radio" id="hungerStar6" name="hunger_rate" value="6" required/>
                                                <label for="hungerStar6" title="text">
                                                    <span class="select-multiple">6</span>
                                                </label>
                                                <input type="radio" id="hungerStar5" name="hunger_rate" value="5" required/>
                                                <label for="hungerStar5" title="text">
                                                    <span class="select-multiple">5</span>
                                                </label>
                                                <input type="radio" id="hungerStar4" name="hunger_rate" value="4" required/>
                                                <label for="hungerStar4" title="text">
                                                    <span class="select-multiple">4</span>
                                                </label>
                                                <input type="radio" id="hungerStar3" name="hunger_rate" value="3" required/>
                                                <label for="hungerStar3" title="text">
                                                    <span class="select-multiple">3</span>
                                                </label>
                                                <input type="radio" id="hungerStar2" name="hunger_rate" value="2" required/>
                                                <label for="hungerStar2" title="text">
                                                    <span class="select-multiple">2</span>
                                                </label>
                                                <input type="radio" id="hungerStar1" name="hunger_rate" value="1" required/>
                                                <label for="hungerStar1" title="text">
                                                    <span class="select-multiple">1</span>
                                                </label>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row margin-top-30">
                                    <div class="col-md-12">
                                        <h3>Custom Meal plan</h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="field" class="strong">Recipe Name</label>
                                            <input type="text" name="recipe_name" class="form-control" required>                      
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group meal-images-section">
                                            <label class="strong">Click Meal Image </label>
                                            {{-- <button type="button" class="camera-btn">
                                                <img src="{{asset('result/images/camera-icon.png')}}">
                                            </button> --}}
                                            <input type="hidden" name="clickedPic" id="clickedPic">
                                            <input type="file" accept="image/*" capture onchange="fileSelectHandlerClick(this)" class="chooseFileBtn" id="chooseFileBtn">
                                            <label for="chooseFileBtn">
                                                <img src="{{asset('result/images/camera-icon.png')}}">
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <div id="mealImageBox" style="display:none">
                                                <div class="meal_image">
                                                    <img src="{{asset('result/images/food-img.jpg')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row-box">
                                            <div class="form-group">                       
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <label class="strong">Ingredient</label>
                                                        <input type="text" class="form-control" name="ingredients" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="strong">Quantity </label>
                                                        <input type="text" name="quantity" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary addMoreRow">Add More <i class="fa fa-plus" aria-hidden="true"></i></button>    
                                        </div>
                                    </div>                                                        
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="strong">Serving size</label>
                                            <select class="form-control" name="serving_size" required title="Choose one of the following...">
                                                <option value="Small">Small</option>
                                                <option value="Medium">Medium</option>
                                                <option value="Large">Large</option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="strong">How healthy</label>
                                            <select class="form-control" name="meal_rating" required title="Choose one of the following...">
                                                <option value="Healthy">Healthy</option>
                                                <option value="Average">Average</option>
                                                <option value="Unhealthy">Unhealthy</option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="strong">How much enjoyed the meal</label>
                                            <select class="form-control" name="enjoyed_meal" required title="Choose one of the following...">
                                                <option value="Very">Very</option>
                                                <option value="Somewhat">Somewhat</option>
                                                <option value="Not">Not</option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="strong">Activity Lebel, note down your activity including workout intensity and duration</label>
                                            <textarea class="form-control" name="activity_label" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="strong">General Notes</label>
                                            <textarea class="form-control" name="general_notes" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12 col-ms-12 col-xs-12 text-center">
                                            <button type="button" class="cancl-btn pull-left" data-dismiss="modal">Cancel</button>
                                            {{-- <button type="button" class="camera-btn">
                                                <img src="{{asset('result/images/camera-icon.png')}}">
                                            </button> --}}
                                            <button type="button" class="save-btn saveNutritionalFormModal pull-right">Save</button>
                                            {{-- <img src="{{asset('result/images/epic-icon-orenge.png')}}" class="bottom_logo"> --}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Weight Model -->
                

                        <!-- Hydration Journal start -->
                        <div class="tab-pane" id="HydrationJournal">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Hydration Journal</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="water-select-type">
                                     
                                        <input type="radio" name="liquidtype" value="{{\App\HydrationJournal::WATER}}" id="waterType">
                                        <label for="waterType">Water</label>

                                        <input type="radio" name="liquidtype" id="coffeeType" value="{{\App\HydrationJournal::COFFEE}}">
                                        <label for="coffeeType">Coffee</label>

                                        <input type="radio" name="liquidtype" id="teaType" value="{{\App\HydrationJournal::TEA}}">
                                        <label for="teaType">Tea</label>

                                        <input type="radio" name="liquidtype" id="JuiceType" value="{{\App\HydrationJournal::JUICE}}">
                                        <label for="JuiceType">Juice</label>

                                        <input type="radio" name="liquidtype" id="SodaType" value="{{\App\HydrationJournal::SODA}}">
                                        <label for="SodaType">Soda</label>

                                        <input type="radio" name="liquidtype" id="MilkType" value="{{\App\HydrationJournal::MILK_ALCOHAL}}">
                                        <label for="MilkType">Milk</label>

                                        <input type="radio" name="liquidtype" id="AlcohalType" value="{{\App\HydrationJournal::ALCOHAL}}">
                                        <label for="AlcohalType">Alcohal</label>

                                        <input type="radio" name="liquidtype" id="DrinkType" value="{{\App\HydrationJournal::SPORTS_DRINKS}}">
                                        <label for="DrinkType">Sports Drink</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="water-bottle-section">
                                        <div class="bottle-img">
                                            <div id="bottle" class="fill-bottle-img" style="height:20%;"></div>
                                            <div class="blank-bottle-img"></div>
                                        </div>
                                        <input type="hidden" name="required" value="">
                                        <input type="hidden" name="consumed" value="">
                                        <div class="dateright">
                                            <div class="required-text">
                                                <span>Required</span>
                                                <strong class="requiredDrinkVolume">3.5 L</strong> 
                                            </div>
                                            <div class="consumed-text">
                                                <strong>Consumed</strong>
                                                <h3 style="float:none;" class="consumedDrink">2.5 L</h3>
                                                <div class="consume-history">
                                                    <span>6:20 am - 200ml</span>
                                                    <span>8:40 am - 300ml</span>
                                                    <span>19:30 am - 200ml</span>
                                                    <span>12:20 pm - 300ml</span>
                                                </div>
                                            </div>
                                            <h2 class="consumed-per">71%</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="Hydr_journal" name="hydration"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="bottle-scroll-bar">
                                        <span class="range-slider-title">I Drank</span>
                                          <span class="range-slider_value">0</span><span>Millilitres</span>
                                        <div class="number">
                                            <div class="">
                                                <span class="minus" id="minus">-</span>
                                                <input class="range-slider_range" type="range" value="0" min="0" max="1000" step="100">
                                                <span class="plus" id="plus">+</span>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 col-ms-12 col-xs-12 text-center">
                                        <button type="button" class="cancl-btn pull-left" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="save-btn saveHydration pull-right">Save</button>
                                      <img src="{{asset('result/images/epic-icon-orenge.png')}}" class="bottom_logo"> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Hydration Journal end -->
                        <!-- Sleep Journal start -->
                        <div class="tab-pane" id="SleepJournal">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Sleep Journal</h5>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="time-box-wrapper">
                                       
                                        <li class="orange-bg">
                                          <a href="javascript:void(0)" class="go_to_bed event-date-timepicker">
                                            <div class="time-title" >
                                                What time did you go to bed?
                                            </div>
                                            <div class="time-value ">
                                                <div class="time-icon">
                                                   <i class="fa fa-bed" aria-hidden="true"></i> 
                                                </div>
                                                <input type="hidden" name="go_to_bed" class="form-control go_to_bed timepicker-btn event-date-timepicker" value="">
                                                <span class="event-time-span1">10:15 PM</span>
                                            </div>
                                          </a>
                                        </li>
                                        <li>
                                           <a href="javascript:void(0)" class="go_to_sleep event-date-timepicker">
                                            <div class="time-title">
                                                What time did you go to sleep?
                                            </div>
                                            <div class="time-value">
                                                <div class="time-icon">
                                                    <i class="fa fa-moon-o" aria-hidden="true"></i>
                                                </div>
                                                 <input type="hidden" name="go_to_sleep" class="form-control go_to_sleep" value="">
                                                <span class="event-time-span2">11:15 PM</span>
                                            </div>
                                          </a>
                                        </li>
                                        <li>
                                           <a href="javascript:void(0)" class="wake_up event-date-timepicker">
                                            <div class="time-title">
                                                What time did you go wake up?
                                            </div>
                                            <div class="time-value">
                                                <div class="time-icon">
                                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                </div>
                                                 <input type="hidden" name="wake_up" class="form-control wake_up" value="">
                                                <span class="event-time-span3">06:15 AM</span>
                                            </div>
                                        </li>
                                      </a>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="feelbox" id="wokeUp">
                                        <p>How did you feel when you woke up?</p>
                                        {{-- <input class="range-slider_range morning_woke_up" type="range" value="50" min="0" max="100" step="100" > --}}
                                        <div class="bottle-scroll-bar arrowicon">
                                            <span class="smallwidth wokeup-range-value">Mediocre</span>
                                            <div class="number">
                                                <div class="">
                                                    <span class="minus" id="wokeupMinus">-</span>
                                                    <input class="morning_woke_up" name="morning_woke_up" type="range" value="50" min="0" max="100" step="0">
                                                    <span class="plus" id="wokeupplus">+</span>
                                                </div>
                                            </div>
                                        </div>
                                       <!--  <ul class="feelvalue">
                                            <li>Tired</li>
                                            <li>Mediocre</li>
                                            <li>Awake</li>
                                        </ul> -->
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="feelbox" id="endOfday">
                                        <p>How did you feel at the end of the day?</p>
                                        {{-- <input class="range-slider_range end_of_day" type="range" value="50" min="0" max="100" step="50"> --}}
                                        <div class="bottle-scroll-bar arrowicon">
                                              <span class="smallwidth endOfday-range-value">Mediocre</span>
                                            <div class="number">
                                                <div class="">
                                                    <span class="minus" id="endOfdayMinus">-</span>
                                                    <input class="end_of_day" type="range" value="50" min="0" max="100" step="0">
                                                    <span class="plus" id="endOfdayplus">+</span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                       <!--  <ul class="feelvalue">
                                            <li>Tired</li>
                                            <li>Mediocre</li>
                                            <li>Awake</li>
                                        </ul> -->
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>General Notes</label>
                                        <textarea name="general_notes" class="form-control general_notes"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 col-ms-12 col-xs-12 text-center">
                                        <button type="button" class="cancl-btn pull-left" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="save-btn saveSleep pull-right">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sleep Journal end -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cameraModalLabel">Capture Meal Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="select" style="display:none">
                <label for="audioSource">Audio input source: </label><select id="audioSource"></select>
            </div>
        
            <div class="select" style="display:none">
                <label for="audioOutput">Audio output destination: </label><select id="audioOutput"></select>
            </div>
            <div class="select" style="margin-bottom:10px;">
                <label for="videoSource">Video source: </label><select id="videoSource"></select>
            </div>
          {{-- <div id="my_camera" style="margin: 0px auto;"></div> --}}
            <canvas id="canvas" height="240" width="320" style="display:none">
            </canvas>
            <video id="videoCamera" playsinline autoplay></video>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary captureImage">Capture</button>
        </div>
      </div>
    </div>
</div>

<!-- Camera capture image modal start  -->
<div id="captureImageModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close m-t--10" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body food-size-section">
                <div id="foodSizeCarousal" class="owl-carousel">
                    <div class="item">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="my-food-heading">
                                    <img src="{{asset('result/images/my-food-icon.png')}}">
                                    <div class="foodtitle">
                                        Rate my <span>Meal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="crop-images">
                                    <img class="captured-image" src="{{asset('result/images/food-img.jpg')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="size-title">
                                    How Healthy is this meal
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="select-size">
                                    <input type="radio" name="meal_rate" data-name="Unhealthy" value="{{\App\MpClientMealplan::UNHEALTHY}}" id="UnhealthyMeal">
                                    <label for="UnhealthyMeal">Unhealthy</label>

                                    <input type="radio" name="meal_rate" data-name="Average" value="{{\App\MpClientMealplan::AVERAGE}}" id="AverageMeal">
                                    <label for="AverageMeal">Average</label>

                                    <input type="radio" name="meal_rate" data-name="Healthy" value="{{\App\MpClientMealplan::HEALTHY}}" id="HealthyMeal">
                                    <label for="HealthyMeal">Healthy</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="my-food-heading">
                                    <img src="{{asset('result/images/my-food-icon.png')}}">
                                    <div class="foodtitle">
                                        Rate my <span>portion</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="crop-images">
                                    <img class="captured-image" src="{{asset('result/images/food-img.jpg')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="size-title">
                                    What size portion
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="select-size">
                                    <input type="radio" name="portionsize" data-name="Small" value="{{\App\MpClientMealplan::SMALL}}" id="smallPsortion">
                                    <label for="smallPsortion">Small</label>

                                    <input type="radio" name="portionsize" data-name="Medium" value="{{\App\MpClientMealplan::MEDIUM}}" id="MediumPsortion">
                                    <label for="MediumPsortion">Medium</label>

                                    <input type="radio" name="portionsize" data-name="Large" value="{{\App\MpClientMealplan::LARGE}}" id="LargePsortion">
                                    <label for="LargePsortion">Large</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger saveRateForm">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h5 class="modal-title" id="staticBackdropLabel">Please Enter Your Weight</h5>


        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-primary btn-o btn-sm p-y-0 bg-none mli-10 pull-right" id="convertPound">Show Imperial</button>
         <button type="button" class="btn btn-primary hidden btn-o btn-sm p-y-0 bg-none mli-10 pull-right" id="convertKg">Show Metric</button>
         <div class="input-group">
         
         <input type="text" class="form-control" id="weight_save" name="weight" value="">
         <span class="input-group-addon kg">Kg</span>
         <span class="input-group-addon pound hidden">Pounds</span>
         <input type="hidden" name="weightUnit" value="{{ isset($measurementData->weightUnit) && $measurementData->weightUnit !=null?$measurementData->weightUnit:'Metric' }}">

         </div>
        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary saveWeight">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Camera capture image modal start  -->

<script type="text/javascript">
    $('body').on('click', '.showallbtn', function(){
        $('.showallbtn').addClass('active');
        $('.measurements_box').removeClass('disablefield');
    });

    $('body').on('click', '#myModal1 .manual_time', function(){
        $('#myModal1 .automatic_time').removeClass('active');
        $('#myModal1 .manual_time').addClass('active');
        $('#myModal1 .add_time_manual').show();
    });

    $('body').on('click', '#myModal1 .automatic_time', function(){
        $('#myModal1 .manual_time').removeClass('active');
        $('#myModal1 .automatic_time').addClass('active');
        $('#myModal1 .add_time_manual').hide();
    });

     $(".select-size input").click(function () {
        if ($(this).is(":checked")) {
            $( ".food-size-section button.owl-next" ).trigger("click");
        } else {

        }
    });


    $(document).ready(function() {
        $('#minus').click(function () {
            var $input = $(this).parent().find('input');
            var count = parseInt($input.val()) - 100;
            count = count < 0 ? 0 : count;
            $input.val(count);
            $input.trigger('input');
            $('#HydrationJournal').find('.range-slider_value').text(count);
            return false;
        });
        $('#plus').click(function () {
            var $input = $(this).parent().find('input');
            var count = parseInt($input.val()) + 100;
            count = count > 1000 ? 1000 : count;
            $input.val(count);
            $input.trigger('input');
            $('#HydrationJournal').find('.range-slider_value').text(count);

            return false;
        });
    });

    

</script>
<!-- End:model personel diay-->