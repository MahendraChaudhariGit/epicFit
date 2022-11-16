<!-- booking.blade --><div id="addClient" class="modal fade" tabindex="-1" data-width="760">
				            <div class="modal-header">
				                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				                <h4 class="modal-title">Booking Details</h4>
				            </div>
				            <div class="modal-body">
				                <form action="#" id="homeBookClient" method="POST"><!-- bookClient-->
				                <input type="hidden" name="_token" value="{{ csrf_token() }}">
				                <div class="row">
					                <div id="bookingModalDate" class="col-md-12 col-xs-12" style="">
				                        <div class="" style="">
				                            <label class="control-label col-md-2 col-xs-2" style="">Date</label>
				                            <div class="col-md-4 col-xs-4" style="">
				                                <input id="homeBookBookingDate" class="date-picker form-control form-control-inline" value="" name="bookingDate" size="16">
				                            </div>
				                        </div>
				                    </div>
				                    <div id="bookingModalTimes">
		                                <span id="bookingModalStart" class="col-md-12 col-xs-12" style="padding-top: 5px;padding-bottom: 5px;">
		                                	<label class="col-md-2 col-xs-2">Start</label>
		                                    <div class="col-md-2 col-xs-5" style="">
		                                    	<select id="homeBookClientStartHour" class="hour form-control form-control-inline " style="min-height: 24px;" name="bookingStartHour">
		                                        	<option value="">hour</option>
		                                        	<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>				                                        
		                                    	</select>
		                                    </div>

		                                    <div class="col-md-2 col-xs-5" style="padding-left: 0px;">
		                                    	<select id="homeBookClientStartMin" class="minute form-control" style="min-height: 24px;" name="bookingStartMin">
		                                        	<!--<option value="">minute</option>-->
		                                        	<option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option>				                                        
		                                        </select>
		                                    </div>
		                                </span>
		                                <span id="bookingModalEnd" class="col-md-12 col-xs-12" style="">
		                                    <label class="col-md-2 col-xs-2" style="min-height: 24px;">End</label>
		                                    <div class="col-md-2 col-xs-5" style="">
		                                    	<select id="homeBookClientEndHour" class="hour form-control" style="min-height: 24px;" name="bookingEndHour">
		                                        	<option value="">hour</option>
		                                        	<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>				                                        
		                                    	</select>
		                                    </div>
		                                    <div class="col-md-2 col-xs-5" style="padding-left: 0px;">
		                                    	<select id="homeBookClientEndMin" class="minute form-control" style="min-height: 24px;" name="bookingEndMin">
		                                        	<!--<option value="">minute</option>-->
		                                        	<option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option>				                                        
		                                        </select>
		                                    </div>
		                                </span>
				                    </div>
				                    <div id="bookingModalLocation" class="col-md-12 col-xs-12" style="">
				                        <div class="" style="">
				                            <label class="control-label col-md-2 col-xs-2" style="">Location</label>
				                            <div class="col-md-4 col-xs-5" style="">
				                                <!--<input class="form-control form-control-inline " value="" name="bookingLocation" size="16">-->
				                                <?php
				                                	//$userClinicList = array('default'=>'Select Location');
				                                
					                                $userClinicList = array('default'=>'Location','und'=>'Other');
					                                foreach ($clinics as $clinicId) {
														$userClinicList[$clinicId->id] = $clinicId->clinic_name;
													}
													
					                                echo Form::select('bookingLocationSelect', $userClinicList,null,array('class'=>'form-control','id'=>'bookingLocationSelect'));
												?>
				                            </div>
				                        </div>
				                    </div>

				                    <div id="bookingClientError" class="col-md-12 col-xs-12" style="display:none;"></div>
				                    <div id="bookingModalClient" class="col-md-12 col-xs-12" style="">
				                        <div class="" style="">
				                            <label class="control-label col-md-2 col-xs-2" style="">Client</label>
				                            <div class="example col-md-4" id="client_search">
											        <div class="demo">
											          <span id="demo1" class="twitter-typeahead" style="position: relative; display: inline-block;">
											            <!--<input class="typeahead tt-hint" type="text" readonly="" autocomplete="off" spellcheck="false" tabindex="-1" dir="ltr" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 1; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);">-->
											            <input class="typeahead tt-input" type="text" placeholder="Find Client" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top; background-color: transparent;">
											          </span>
											        </div>
											     </div>
				                            <!--<div class="col-md-4 col-xs-5" style="">

				                            	
											    <div id="setValue" style="display:none;"></div>

				                            <select id="clientFill" class="form-control" name="clientId"></select>
				                            </div>-->
				                        </div>
				                    </div>
				                    <div id="bookingModalType" class="col-md-12 col-xs-12" style="">
				                        <div class="" style="">
				                            <label class="control-label col-md-2 col-xs-2" style="">Booking Type</label>
				                            <div class="col-md-4 col-xs-5" style="">
				                                <!--<input class="form-control form-control-inline " value="" name="bookingLocation" size="16">-->
				                                <?php
				                                	$bookingTypeList = array('default'=>'Type','cs'=>'Consult','bm'=>'Benchmarking','oo'=>'1on1');//,'rc'=>'Team'
				                                	/*
					                                $userClinicList = array('default'=>'Select Location','und'=>'Other');
					                                foreach ($clinics as $clinicId) {
														$userClinicList[$clinicId->id] = $clinicId->clinic_name;
													}
													*/
					                                echo Form::select('bookingType', $bookingTypeList,null,array('class'=>'form-control','id'=>'bookingType'));
												?>
				                            </div>
				                        </div>
				                    </div>
				                </div>
				                <!--<div class="row">
					                <div class="col-md-3 col-xs-12">
				                        <div class="form-group" style="">
				                            <label class="control-label" style="display:inline-block;float:left;width:30%;">Date</label>
				                            <div class="" style="display:inline-block;float:left;width:65%;">
				                                <input class="form-control form-control-inline input-medium date-picker" size="16" type="text" value="" name="bookingDate">
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-md-9 col-xs-12">
				                    <span class="combodate">
	                                    <span class="col-md-6">
	                                    	<label class="col-md-3">Start</label>
	                                        <select class="form-control hour col-md-4" style="min-height: 24px;" name="bookingHour">
	                                            <option value="">hour</option>
	                                            <?php
	                                            	for ($i=0; $i < 24; $i++) { 
	                                            		if($i <10){
	                                            			echo "<option value='0".$i."'>0".$i."</option>";
	                                            		}else {
	                                            			echo "<option value='".$i."'>".$i."</option>";
	                                            		}
	                                            	}
	                                            ?>
	                                        </select>
	                                        <select class="form-control minute col-md-4" style="display:inline-block;width:auto;float:left;min-height: 24px;" name="bookingMin">
	                                            <option value="">minute</option>
	                                            <?php
	                                            	for ($i=0; $i <= 55; $i+=5) { 
	                                            		if($i <10){
	                                            			echo "<option value='0".$i."'>0".$i."</option>";
	                                            		}else {
	                                            			echo "<option value='".$i."'>".$i."</option>";
	                                            		}
	                                            	}
	                                            ?>
	                                        </select>
	                                    </span>
	                                    <span class="col-md-6" style="">
	                                        <label class="col-md-3">End</label>
	                                        <select class="form-control hour col-md-4" style="min-height: 24px;" name="bookingHour">
	                                            <option value="">hour</option>
	                                            <?php
	                                            	for ($i=0; $i < 24; $i++) { 
	                                            		if($i <10){
	                                            			echo "<option value='0".$i."'>0".$i."</option>";
	                                            		}else {
	                                            			echo "<option value='".$i."'>".$i."</option>";
	                                            		}
	                                            	}
	                                            ?>
	                                        </select>
	                                        <select class="form-control minute col-md-4" style="display:inline-block;width:auto;float:left;min-height: 24px;" name="bookingMin">
	                                            <option value="">minute</option>
	                                            <?php
	                                            	for ($i=0; $i <= 55; $i+=5) { 
	                                            		if($i <10){
	                                            			echo "<option value='0".$i."'>0".$i."</option>";
	                                            		}else {
	                                            			echo "<option value='".$i."'>".$i."</option>";
	                                            		}
	                                            	}
	                                            ?>
	                                        </select>
	                                    </span>
	                                </span>
				                    </div>
				                </div>--><!-- edn row -->
				                </form>
				            </div><!-- end modal body -->
				            <div class="modal-footer">
				                <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
				                <button type="button" class="btn blue" id="homeBookClientPost">Save Booking</button>
				            </div>
				        </div><!-- end addClient -->

				    <!--</div>
				</div>-->

			</div>