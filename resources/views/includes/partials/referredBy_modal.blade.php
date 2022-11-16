<div class="modal fade" id="referredByModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit information</h4>
            </div>
            <div class="modal-body bg-white" data-realtime="referralNetwork" data-modal="true">
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
            
                <div class="form-group">
                    <label class="strong" for="modalReferer">Where did you hear about EPIC?</label>
                    <select id="modalReferer" name="referrer" class="form-control">
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

                <div class="form-group" >
                    <label class="strong">Which company or person referred you to EPIC?</label>
                    <div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="refereModalReferralNetwork" id="refereModalReferralNetwork0" value="Client" <?php echo ($parq->referralNetwork == 'Client')?'checked':''; ?>>
                            <label for="refereModalReferralNetwork0">
                                Client
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="refereModalReferralNetwork" id="refereModalReferralNetwork1" value="Staff" <?php echo ($parq->referralNetwork == 'Staff')?'checked':''; ?>>
                            <label for="refereModalReferralNetwork1">
                                Staff
                            </label>
                        </div>
                        <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input type="radio" name="refereModalReferralNetwork" id="refereModalReferralNetwork2" value="Professional network" <?php echo ($parq->referralNetwork == 'Professional network')?'checked':''; ?>>
                            <label for="refereModalReferralNetwork2">
                                Professional network
                            </label>
                        </div>
                    </div>
                 
                    <input id="refereModalClientList" type="text" class="form-control" value="{{$parq->referralNetwork == 'Client' ?$parq->ref_Name:''}}" autocomplete="off" name="refereModalClientName">
                    <input id="refereModalStaffList" type="text" class="form-control" value="{{$parq->referralNetwork == 'Staff' ?$parq->ref_Name:''}}" autocomplete="off" name="refereModalStaffName">
                    <input id="refereModalProList" type="text" class="form-control" value="{{$parq->referralNetwork == 'Professional network' ?$parq->ref_Name:''}}" autocomplete="off" name="refereModalProName">
                    <input type="hidden" name="clientId" value="{{$parq->clientId}}">
                    <input type="hidden" name="staffId" value="{{$parq->staffId}}">
                    <input type="hidden" name="proId" value="{{$parq->proId}}">
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary submit">Submit</button>
            </div>
        </div>
    </div>
</div>