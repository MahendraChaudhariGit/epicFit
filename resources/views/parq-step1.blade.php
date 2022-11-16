<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="Address Modal" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Address Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="addressline1" class="strong">Address Line 1 *</span></label>
                            <input type="text" class="form-control" id="autocomplete" name="addressline1" value="{{$parq->addressline1}}" onFocus ='geolocate()' autocomplete = 'off'>
                        </div>
    
                        <div class="form-group">
                            <label for="addressline2" class="strong">Address Line 2</span></label>
                            <input type="text" class="form-control" id="addressline2" name="addressline2" value="{{$parq->addressline2}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="city" class="strong">City *</span></label>
                            <input type="text" class="form-control" id="city" value="{{$parq->city}}" name="city" required>
                        </div>
    
                        <div class="form-group">
                            <label for="postal_code" class="strong">Postal code *</span></label>
                            <input type="tel" class="form-control" id="postal_code" name="postal_code" value="{{$parq->postal_code}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country" class="strong">Country *</span></label>
                            <select id="country" name="country" class="form-control" required>
                                <option value="">-- Select --</option>
                                <?php 
                                foreach($countries as $shortCode => $country)
                                    echo '<option value="'.$shortCode.'" '.($parq->country == $shortCode?'selected':'').'>'.$country.'</option>';
                                ?>
                            </select>
                        </div>
    
                        <div class="form-group">
                            <label for="addrState" class="strong">State / Region *</span></label>
                            <select class="form-control" id="addrState" name="addrState" required data-selected="{{$parq->addrState}}">
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone" class="strong">Time Zone *</span></label>
                            <select id="timezone" name="timezone" class="form-control" required data-live-search = 'true'>
                                <option value="">-- Select --</option>
                                <?php 
                                foreach($timezones as $country => $timezone){
                                    echo '<optgroup label="'.$country.'">';
                                    foreach($timezone as $key => $value){
                                    echo '<option value="'.$key.'" '.($parq->timezone == $key?'selected':'').'>'.$value.'</option>';
                                }
                                echo '</optgroup>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="currency" class="strong">Currency *</span></label>
                            <select id="currency" name="currency" class="form-control" required data-live-search = 'true'>
                                <option value=""></option>
                                <?php 
                                foreach($currencies as $shortCode => $currency)
                                echo '<option value="'.$shortCode.'" '.($parq->currency == $shortCode?'selected':'').'>'.$currency.'</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
        	</div>
    		<div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal" id="addModalCanc">Cancel</button>
            	<button type="button" class="btn btn-primary" id="addModalOk">Ok</button>
            </div>
    	</div>
    </div>
</div>

<div class="modal fade" id="ecrelationModal" tabindex="-1" role="dialog" aria-labelledby="ecRelation Modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Relationship Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="relation" class="strong">Relationship to Emergency Contact</span></label>
                            <input type="text" class="form-control" id="ecRelation" name="ecRelation" value="{{$parq->ecRelation}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<div class="sucMes hidden">
	{!! displayAlert()!!}
</div>
<fieldset class="padding-15">
    <legend>General Details</legend>
    <div class="row">
        <div class="col-md-6">
            <div data-realtime="referralNetwork">
                <div class="form-group">
                    <label class="strong" for="referrer">Where did you hear about EPIC?</label>
                    <select id="referrer" name="referrer" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="onlinesocial" <?php echo $parq->hearUs == 'onlinesocial'?'selected':''; ?>>Online &amp; Social Media</option>
                        <option value="mediapromotions" <?php echo $parq->hearUs == 'mediapromotions'?'selected':''; ?>>Media &amp; Promotions</option>
                        <option value="referral" <?php echo $parq->hearUs == 'referral'?'selected':''; ?>>Referral</option>
                        <option value="socialmedia" <?php echo $parq->hearUs == 'socialmedia'?'selected':''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group referencewhere">
                    <label class="strong">From where?</label>
                    <input type="text" name="referencewhere" value="{{$parq->referencewhere}}" class="form-control">
                </div>

                <div class="form-group otherName hidden">
                    <label class="strong">Source</label>
                    <input type="text" name="otherName" value="{{$parq->referrerother}}" class="form-control">
                </div>
                
                <div class="form-group" id="refNetwork">
                    <label class="strong">Which company or person referred you to EPIC?</label>
                    <div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork0" class="{{ ($parq->referralNetwork == 'Client') ? 'referrerNet' : '' }}" value="Client" <?php echo ($parq->referralNetwork == 'Client')?'checked':''; ?>>
                            <label for="referralNetwork0">
                                Client
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork1" class="{{ ($parq->referralNetwork == 'Staff') ? 'referrerNet' : '' }}" value="Staff" <?php echo ($parq->referralNetwork == 'Staff')?'checked':''; ?>>
                            <label for="referralNetwork1">
                                Staff
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="referralNetwork" id="referralNetwork2" class="{{ ($parq->referralNetwork == 'Professional network') ? 'referrerNet' : '' }}" value="Professional network" <?php echo ($parq->referralNetwork == 'Professional network')?'checked':''; ?>>
                            <label for="referralNetwork2">
                                Professional network
                            </label>
                        </div>
                    </div>
                   
                    <input id="clientList" type="text" class="form-control" value="{{ $parq->ref_Name }}" autocomplete="off" name="clientName">
                    <input id="staffList" type="text" class="form-control" value="{{ $parq->ref_Name }}" autocomplete="off" name="staffName">
                    <input id="proList" type="text" class="form-control" value="{{ $parq->ref_Name }}" autocomplete="off" name="proName">
                    <input type="hidden" name="clientId" value="{{$parq->clientId}}">
                    <input type="hidden" name="staffId" value="{{$parq->staffId}}">
                    <input type="hidden" name="proId" value="{{$parq->proId}}">
                    <!-- <input type="hidden" name="isReferenceDeleted" value="{{$parq->isReferenceDeleted}}"> -->
                </div>
            </div>
            <div class="form-group">
                <label class="strong" for="firstName">What is your first name? *</label>
                <input id="firstName" name="firstName" type="text" class="form-control" required value="{{$parq->firstName}}" data-realtime="firstName">
            </div>
        
            <div class="form-group">
                <label class="strong" for="lastName">What is your last name? *</label>
                <input id="lastName" name="lastName" type="text" class="form-control" required value="{{$parq->lastName}}" data-realtime="lastName">
            </div>
        
            <div class="form-group">
                <label class="strong">I Identify my gender as *</label>
                <div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                        <input type="radio" name="gender" id="parq-male" required value="Male" <?php echo ($parq->gender == 'Male')?'checked':''; ?> data-realtime="gender" class="onchange-set-neutral">
                        <label for="parq-male">
                            Male
                        </label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                        <input type="radio" name="gender" id="parq-female" required value="Female" <?php echo ($parq->gender == 'Female')?'checked':''; ?> data-realtime="gender" class="onchange-set-neutral">
                        <label for="parq-female">
                            Female
                        </label>
                    </div>
                </div>
                <span class="help-block m-b-0"></span>
            </div>
            
            <div class="form-group">
            	<label class="strong">How tall are you? <button type="button" class="btn btn-primary btn-o btn-sm p-y-0" id="heightUnit">Show Imperial</button></label>
                <input type="hidden" name="heightUnit" value="{{ isset($parq->heightUnit) && $parq->heightUnit !=null?$parq->heightUnit:'Metric' }}">
                <div class="input-group heightMetric {{ isset($parq->heightUnit) && $parq->heightUnit =='Imperial'?'hidden':'' }}">
                    <input type="text" name="height_metric" class="form-control" value="{{isset($parq->height_metric)?$parq->height_metric:''}}">
                    <span class="input-group-addon">cm</span>
                </div>
                <div class="row heightImperial {{ isset($parq->heightUnit) && ($parq->heightUnit =='Metric' || $parq->heightUnit ==null) ?'hidden':'' }}">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="height_imperial_ft" class="form-control" value="{{isset($parq->height_imperial_ft)?$parq->height_imperial_ft:''}}">
                            <span class="input-group-addon">ft</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="height_imperial_inch" class="form-control" value="{{isset($parq->height_imperial_inch)?$parq->height_imperial_inch:''}}">
                            <span class="input-group-addon">inch</span>
                        </div>
                    </div>
                </div>
                <span class="help-block m-b-0"></span>
        	</div>
        
        	<div class="form-group">
                <label class="strong">What is your current weight? <button type="button" class="btn btn-primary btn-o btn-sm p-y-0" id="weightUnit">Show Imperial</button></label>
                <input type="hidden" name="weightUnit" value="{{ isset($parq->weightUnit) && $parq->weightUnit !=null?$parq->weightUnit:'Metric' }}">
                <div class="input-group weightMetric {{ isset($parq->weightUnit) && $parq->weightUnit =='Imperial'?'hidden':'' }}">
                    <input type="text" name="weight_metric" class="form-control" value="{{isset($parq->weight_metric)?$parq->weight_metric:''}}">
                    <span class="input-group-addon">Kg</span>
                </div>
                <div class="input-group weightImperial {{ isset($parq->weightUnit) && ($parq->weightUnit =='Metric' || $parq->weightUnit ==null) ?'hidden':'' }}">
                    <input type="text" name="weight_imperial" class="form-control" value="{{isset($parq->weight_imperial)?$parq->weight_imperial:''}}">
                    <span class="input-group-addon">Pounds</span>
                </div>
                <span class="help-block m-b-0"></span>
            </div>
            
            <div class="form-group upload-group">
                <input type="hidden" name="prePhotoName" value="{{ $clients->profilepic }}">
                <input type="hidden" name="entityId" value="{{$parq->client_id}}">
                <input type="hidden" name="saveUrl" value="client/photo/save">
                <input type="hidden" name="photoHelper" value="client">
                <input type="hidden" name="cropSelector" value="square">
            	<label class="strong">Please upload a recent headshot photo of yourself, as a profile picture</label>
                <div>
                    <label class="btn btn-primary btn-file">
                        <span><i class="fa fa-plus"></i> Select Photo</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                    </label>
                    <div class="m-t-10">
                    	@if($clients->profilepic)
                        <a href="{{ asset('uploads/thumb_'.$clients->profilepic) }}" data-lightbox="image-1">
                            <img src="{{ asset('uploads/thumb_'.$clients->profilepic) }}" class="clientPreviewPics previewPics" /></a>
                        @else
                        <a data-lightbox="image-1">
                            <img class="hidden clientPreviewPics previewPics" /></a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!--<div class="form-group">
            <label class="col-md-12 control-label" for="country">Country</label>
            <div class="col-md-12">
            <select id="country" name="country" class="form-control">
            <option value="">Select</option>
            <?php 
            /*foreach($countries as $shortCode => $country)
            echo '<option value="'.$shortCode.'" '.($parq->country == $shortCode?'selected':'').'>'.$country.'</option>';*/
            ?>
            </select>
            </div>
            </div>
            <div class="form-group">
            <label class="col-md-12 control-label" for="timezone">Time Zone</label>
            <div class="col-md-12">
            <select id="timezone" name="timezone" class="form-control">
            <option value="">Select</option>
            <?php 
            /*foreach($timezones as $country => $timezone){
            echo '<optgroup label="'.$country.'">';
            foreach($timezone as $key => $value){
            echo '<option value="'.$key.'" '.($parq->country == $key?'selected':'').'>'.$value.'</option>';
            }
            echo '</optgroup>';
            }*/
            ?>
            </select>
            </div>
            </div>
            <div class="form-group">
            <label class="col-md-12 control-label" for="currency">Currency</label>
            <div class="col-md-12">
            <select id="currency" name="currency" class="form-control">
            <option value="">Select</option>
            <?php 
            /*foreach($currencies as $shortCode => $currency)
            echo '<option value="'.$shortCode.'" '.($parq->currency == $shortCode?'selected':'').'>'.$currency.'</option>';*/
            ?>
            </select>
            </div>
            </div>-->
    	</div>
        <div class="col-md-6">
        	<div class="form-group clearfix">
            	<label class="strong" for="dd">What is your birthdate? *</label>
                <div class="row">
                	<div class="col-md-4 col-xs-4">
                        <select class="form-control js-day" id="dd" name="dd" data-realtime="dob">
                            <option data-hidden="true">DAY</option>
                        	<option value="01" <?php echo $parq->birthDay == '01'?'selected':''; ?>>1</option>
                            <option value="02" <?php echo $parq->birthDay == '02'?'selected':''; ?>>2</option>
                            <option value="03" <?php echo $parq->birthDay == '03'?'selected':''; ?>>3</option>
                            <option value="04" <?php echo $parq->birthDay == '04'?'selected':''; ?>>4</option>
                            <option value="05" <?php echo $parq->birthDay == '05'?'selected':''; ?>>5</option>
                            <option value="06" <?php echo $parq->birthDay == '06'?'selected':''; ?>>6</option>
                            <option value="07" <?php echo $parq->birthDay == '07'?'selected':''; ?>>7</option>
                            <option value="08" <?php echo $parq->birthDay == '08'?'selected':''; ?>>8</option>
                            <option value="09" <?php echo $parq->birthDay == '09'?'selected':''; ?>>9</option>
                            <option value="10" <?php echo $parq->birthDay == '10'?'selected':''; ?>>10</option>
                            <option value="11" <?php echo $parq->birthDay == '11'?'selected':''; ?>>11</option>
                            <option value="12" <?php echo $parq->birthDay == '12'?'selected':''; ?>>12</option>
                            <option value="13" <?php echo $parq->birthDay == '13'?'selected':''; ?>>13</option>
                            <option value="14" <?php echo $parq->birthDay == '14'?'selected':''; ?>>14</option>
                            <option value="15" <?php echo $parq->birthDay == '15'?'selected':''; ?>>15</option>
                            <option value="16" <?php echo $parq->birthDay == '16'?'selected':''; ?>>16</option>
                            <option value="17" <?php echo $parq->birthDay == '17'?'selected':''; ?>>17</option>
                            <option value="18" <?php echo $parq->birthDay == '18'?'selected':''; ?>>18</option>
                            <option value="19" <?php echo $parq->birthDay == '19'?'selected':''; ?>>19</option>
                            <option value="20" <?php echo $parq->birthDay == '20'?'selected':''; ?>>20</option>
                            <option value="21" <?php echo $parq->birthDay == '21'?'selected':''; ?>>21</option>
                            <option value="22" <?php echo $parq->birthDay == '22'?'selected':''; ?>>22</option>
                            <option value="23" <?php echo $parq->birthDay == '23'?'selected':''; ?>>23</option>
                            <option value="24" <?php echo $parq->birthDay == '24'?'selected':''; ?>>24</option>
                            <option value="25" <?php echo $parq->birthDay == '25'?'selected':''; ?>>25</option>
                            <option value="26" <?php echo $parq->birthDay == '26'?'selected':''; ?>>26</option>
                            <option value="27" <?php echo $parq->birthDay == '27'?'selected':''; ?>>27</option>
                            <option value="28" <?php echo $parq->birthDay == '28'?'selected':''; ?>>28</option>
                            <option value="29" <?php echo $parq->birthDay == '29'?'selected':''; ?>>29</option>
                            <option value="30" <?php echo $parq->birthDay == '30'?'selected':''; ?>>30</option>
                            <option value="31" <?php echo $parq->birthDay == '31'?'selected':''; ?>>31</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <select class="form-control js-month" name="mm" data-realtime="dob">
                            <option data-hidden="true">MONTH</option>
                        	{!! monthDdOptions($parq->birthMonth) !!}
                        	<!--<option value="01" <?php //echo $parq->birthMonth == '01'?'selected':''; ?>>1</option>
                            <option value="02" <?php //echo $parq->birthMonth == '02'?'selected':''; ?>>2</option>
                            <option value="03" <?php //echo $parq->birthMonth == '03'?'selected':''; ?>>3</option>
                            <option value="04" <?php //echo $parq->birthMonth == '04'?'selected':''; ?>>4</option>
                            <option value="05" <?php //echo $parq->birthMonth == '05'?'selected':''; ?>>5</option>
                            <option value="06" <?php //echo $parq->birthMonth == '06'?'selected':''; ?>>6</option>
                            <option value="07" <?php //echo $parq->birthMonth == '07'?'selected':''; ?>>7</option>
                            <option value="08" <?php //echo $parq->birthMonth == '08'?'selected':''; ?>>8</option>
                            <option value="09" <?php //echo $parq->birthMonth == '09'?'selected':''; ?>>9</option>
                            <option value="10" <?php //echo $parq->birthMonth == '10'?'selected':''; ?>>10</option>
                            <option value="11" <?php //echo $parq->birthMonth == '11'?'selected':''; ?>>11</option>
                            <option value="12" <?php //echo $parq->birthMonth == '12'?'selected':''; ?>>12</option>-->
                        </select>
                    </div> 
                    <div class="col-md-4 col-xs-4">
                    	<select class="form-control js-year" name="yyyy" data-realtime="dob">
                            <option data-hidden="true">YEAR</option>
                        	{!! yearDdOptions($parq->birthYear) !!}
                        </select>
                        <!--<input placeholder="YEAR" class="form-control" required name="yyyy" type="text" value="{{$parq->birthYear}}">-->
                    </div>
                </div>
                <span class="help-block m-b-0"></span>
            </div>
                    
            <div class="form-group">
                <label class="strong" for="occupation">What is your occupation?</label>
                <input id="occupation" name="occupation" type="text" class="form-control" value="{{$parq->occupation}}" data-realtime="occupation">
            </div>
        
            <div class="form-group">
                <label class="strong" for="primEm">Please provide your primary email address *</label>
                <input id="primEm" name="primEm" type="email" class="form-control" required value="{{$parq->email}}" data-realtime="email">
                
            </div>
        
            <div class="form-group">
                <label class="strong" for="contactNo">Please provide your phone number * & address</label>
                <input id="contactNo" name="contactNo" type="tel" class="form-control cntryCode numericField" maxlength="16" minlength="5" required value="{{$parq->contactNo}}" data-realtime="phone">
                <!--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="isAddress" id="isAddress" value="1" <?php //echo $parq->isAddress == 1?'checked':''; ?>>
                    <label for="isAddress">
                        Address
                    </label>
                </div>-->
                <a class="btn-add-more add-address" href="javascript:void(0)" data-toggle="modal" data-target="#addressModal">+ Add Address</a>
            </div>
        
            <div class="form-group">
                <label class="strong" for="ecName">Please provide the name of your emergency contact *</label>
                <input id="ecName" name="ecName" type="text" class="form-control" required value="{{$parq->ecName}}">
                <a class="btn-add-more add-address" href="javascript:void(0)" data-toggle="modal" data-target="#ecrelationModal">+ Add Relationship</a>
            </div>
            
            <div class="form-group">
                <label class="strong" for="ecNumber">Please provide the phone number of your emergency contact *</label>
                <input id="ecNumber" name="ecNumber" type="tel" class="form-control cntryCode numericField" maxlength="16" minlength="5" required value="{{$parq->ecNumber}}">
            </div>
        
            <div class="form-group">
                <label class="strong" for="notes">Please provide any additional notes you think are relevant</label>
                <div>
                <textarea class="form-control" rows="10" cols="50"  id="notes" name="notes">{{$parq->notes}}</textarea>
                </div>
            </div>
        
            <!--<div class="control-group">
            <div class="controls">
            <input type="submit" class="submitParqForm next-step btn green-haze btn-large btn-block" name="saveNskip" value="Save">
            </div>
            </div>-->
    	</div>
    </div>
</fieldset>

<script type="text/javascript">
    $('input[name="referralNetwork"]').click(function(){
       var checkClass = $(this).hasClass('referrerNet');
    $('input[name="referralNetwork"]').removeClass('referrerNet');
    $(this).toggleClass('referrerNet');
     if(checkClass){
         $(this).prop('checked',false);
     }
     else{
        $(this).prop('checked',true);
     }
    })
</script>
