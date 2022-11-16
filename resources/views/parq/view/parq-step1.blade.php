<fieldset class="padding-15">
    <legend>General Details</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="parq-view" data-realtime="referralNetwork">
                <div class="form-group">
                    <label class="strong">Where did you hear about EPIC?</label>
                    </br>
                    <span class="referrerCls">
                        <?php   
                            if($parq->hearUs=="onlinesocial")
                                echo "Online & Social Media";
                            elseif($parq->hearUs=="mediapromotions")
                                echo "Media & Promotions";
                            elseif($parq->hearUs=="socialmedia")
                                echo "Other";
                            elseif($parq->hearUs=="referral")
                                echo "Referral";
                            else
                                echo "--";
                        ?>        
                        </span>
    			</div>

                <div class="promosionCls">
                    <?php 
                    $hidden1='';$hidden2='';
                    if($parq->hearUs == 'onlinesocial' || $parq->hearUs == 'mediapromotions')
                        $hidden2='hidden';
                    elseif($parq->hearUs == 'socialmedia')
                        $hidden1='hidden';
                    else{
                        $hidden2='hidden';
                        $hidden1='hidden';
                    }
                    ?>
                        <div class="form-group <?php echo $hidden1;?>">
                            <label class="strong">From where?</label>
                            </br>
                            <span class="referencewhereCls">{{ $parq->referencewhere ? $parq->referencewhere : '--'}} </span>
                        </div>
                    
                        <div class="form-group <?php echo $hidden2;?>">
                            <label class="strong">Source</label>
                            </br>
                            <span class="referrerotherCls">{{ $parq->referrerother ? $parq->referrerother : '--'}}</span> 
                        </div>
                    
                </div>
                <?php if($parq->hearUs == 'referral')
                {
                    ?>
                
                <div class="form-group">
                    <label class="strong">Which company or person referred you to EPIC?</label>
                    <br>
                    <span class="referralNetworkCls">{!! $parq->referralNetwork !!}</span>
                    <?php
                    if($parq->ref_Name)
                {
                    ?><span class="referralName">,{!! $parq->ref_Name?$parq->ref_Name: '--' !!}</span>
                    <?php

                }
               ?>

    			</div>
            <?php } ?>
            </div>
            <div class="form-group">
                <label class="strong">What is your first name?</label>
                </br>
                <span data-realtime="firstName">{{ $parq->firstName ? $parq->firstName : '--'}}</span>
            </div>
        
            <div class="form-group">
                <label class="strong">What is your last name?</label>
                </br>
                <span data-realtime="lastName">{{ $parq->lastName ? $parq->lastName : '--'}}</span> 
            </div>
        
            <div class="form-group">
                <label class="strong">I Identify my gender as </label>
                </br>
                <div class = "form-group" data-realtime="gender">
                 	{{ $parq->gender ? $parq->gender : '--'}} 
				 </div>
            </div>
            
            <div class="form-group">
            	<label class="strong">How tall are you? </label>
                </br>
                <?php if($parq->heightUnit == 'Metric' && $parq->height != '')
                {
                    echo $parq->height."&nbsp;cm";

                }
                    elseif($parq->heightUnit == 'Imperial' && $parq->height != '')
                    {
                         isset($parq) && isset($parq->height)?$height = explode('-',$parq->height):$height=[];
                          echo $height[0]."&nbsp;ft ".$height[1]."&nbsp;inch";

                    }
                    else
                    {
                
                echo '--';
            }
                ?>
            </div>
        
        	<div class="form-group">
                <label class="strong">What is your current weight?</label>
                </br>
                <?php

                  if($parq->weightUnit == 'Metric' && $parq->weight != ''){
                    echo $parq->weight."&nbsp;kg";

                }
                elseif($parq->weightUnit == 'Imperial' && $parq->weight != ''){
                        
                    echo $parq->weight."&nbsp;pound";
                }
                    else{
                
                echo '--';
            }
                ?>
            </div>
            
            <div class="form-group">
            	<label class="strong">Profile picture</label>
                <br>
            <a href="{{ dpSrc($clients->profilepic, $clients->gender) }}" data-lightbox="image-1">
                    <img src="{{ dpSrc($clients->profilepic, $clients->gender) }}" class="img-responsive  previewPics" alt="{{ $clients->firstname }} {{ $clients->lastname }}"/></a>
            </div>
        </div>
        <div class="col-md-6">
        	<div class="form-group">
            	<label class="strong">What is your birthdate? </label>
                </br>
                <span data-realtime="dob">{{ $overviewDob }}</span>
            </div>
                    
            <div class="form-group">
                <label class="strong">What is your occupation?</label></br>
                <span data-realtime="occupation">{{ $parq->occupation ? $parq->occupation : '--'}}</span>
            </div>
        
            <div class="form-group">
                <label class="strong">Please provide your primary email address</label></br>
                <a href="mailto:{{ $parq->email ?? '' }}" data-realtime="email">{{ $parq->email ?? '' }}</a>
             </div>
        
            <div class="form-group">
                <label class="strong">Please provide your phone number</label></br>
                <a href="tel:{!! $parq->contactNo !!}" data-realtime="phone">{!! $parq->contactNo !!}</a>
            </div>

            <div class="form-group">
                <label class="strong">Please provide your address</label></br>
                @if($parq->addressline1)
                    {{ $parq->addressline1.', '.$parq->addressline2.', '.$parq->city.', '.$parq->stateName.', '.$countries[$parq->country].', '.$parq->postal_code.','.$parq->timezone.','. $parq->currency  }}
                @endif
            </div>
        
             <div class="form-group">
                <label class="strong">Please provide the name of your emergency contact</label></br>
                 {{ $parq->ecName ? $parq->ecName : '--'}} as {{ $parq->ecRelation ? $parq->ecRelation : '--'}}
            </div>
            <div class="form-group">
                <label class="strong">Please provide the phone number of your emergency contact</label></br>
                {{ $parq->ecNumber ? $parq->ecNumber : '--'}}     
            </div>
        
            <div class="form-group">
                <label class="strong">Please provide any additional notes you think are relevant</label></br>
             {{ $parq->notes ? $parq->notes : '--'}}   
             </div>
    	</div>
    </div>
</fieldset>