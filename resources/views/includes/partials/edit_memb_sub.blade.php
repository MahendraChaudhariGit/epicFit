{{-- Start: Edit Membership Subscription Modal --> --}}
@php 
use Carbon\Carbon;
$membEndDate = $clientMembership ? new Carbon($clientMembership->cm_end_date) : null;
@endphp
<style type="text/css">
 #editMembSub .serviceFinalDiscount h5, #editMembSub .serviceDiscountPerAmount h5{
      position: absolute;
    top: 40px;
    font-size: 12px;
 }
  #editMembSub .new-row{
  margin-bottom: 20px;
 }
@media(max-width: 767px){
  .deleteDiscountService, .applyDiscount {
    margin-top: 9px;
    display: inherit;
  }
  .remove-member-service-row{
      margin-top: 15px;
    display: inherit
  }
}
</style>
<div class="modal fade" id="editMembSub" role="dialog">
  <div class="modal-dialog modal-lg">   
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Membership</h4>
      </div>
      <div class="modal-body bg-white">
        {{-- {{ dd($clientMembership->toArray()) }} --}}
        <div class="alert alert-danger hidden" id="editMembStatus">This event is in past. You can not update any details unless it is client related.</div>
   
        {!! Form::open(['url' => 'clients/membership/update', 'role' => 'form']) !!}
          {!! Form::hidden('clientId', $clientId) !!}
          {!! Form::hidden('clientMembId', $clientMembership?$clientMembership->id:null) !!}
          {!! Form::hidden('membEndDate', $membEndDate ? $membEndDate->toDateString() :null) !!}
          {!! Form::hidden('cb') !!}
          {!! Form::hidden('stepNumb') !!}
          {!! Form::hidden('discountType', $clientMembership->cm_discount_type) !!}
          {!! Form::hidden('discountAmount', $clientMembership->cm_discounted_amount) !!}
          {{-- Form::hidden('mode', $clientMembership?'edit':'create') --}}
          <div class="form-group">
            {!! Form::label('membership', 'Membership Option *', ['class' => 'strong']) !!}
            <select name="membership" id="membership" class="form-control onchange-set-neutral" required>
              <option value="">-- Select --</option>
              @if(count($memberships))
                <?php $selMemb = $clientMembership?$clientMembership->cm_membership_id:'';?>
                @foreach($memberships as $membId => $membership)
                <?php
                  $category='';
                  if($membership['memCatgory']){
                    $category = ' - '.implode(', ',$membership['memCatgory']);
                  } 
                ?>
                  <option value="{{$membId}}" {{($selMemb == $membId)?'selected':''}} data-length="{{$membership['length']}}" data-length-unit="{{$membership['lengthUnit']}}" data-instamount="{{$membership['instAmount']}}" data-membership-unit-price="{{$membership['memUnitPrice']}}" data-class-limit="{{$membership['classLimit']}}" data-membership-tax="{{$membership['membershipTax']}}">{{$membership['name']}}  {{ $category }}</option>
                @endforeach
              @endif
            </select>
          </div>
          
          <div class="form-group">
          <div class="row">
            <div class="form-group col-md-6">
            {!! Form::label('membTotalPrice', 'Membership price(Including tax) *', ['class' => 'strong']) !!}
            {!! Form::text('membTotalPrice', 0, ['class' => 'form-control price-field', 'required', 'readonly']) !!}
            <span class="help-block"></span>
          </div>
          <div class="form-group col-md-6">
            {!! Form::label('membUnitPrice', 'Membership Unit price *', ['class' => 'strong']) !!}
            {!! Form::text('membUnitPrice', $clientMembership && (($clientMembership->cm_number <= $clientMembership->cm_discount_dur) || ($clientMembership->cm_discount_dur == -1)) &&$clientMembership->cm_discount_dur && $clientMembership->cm_disc_amnt_type == 'total'?$clientMembership->cm_disc_per_class_amnt:$clientMembership->cm_per_clas_amnt, ['class' => 'form-control price-field', 'required', 'readonly']) !!}
            <span class="help-block"></span>
          </div>
            
          </div> 
           <div class="checkbox clip-check check-primary m-b-0 m-t-0">
              <input type="checkbox" value="1" id="applyDisc" class="onchange-block-membstatus" {{($clientMembership && (($clientMembership->cm_number <= $clientMembership->cm_discount_dur) || ($clientMembership->cm_discount_dur == -1)) && $clientMembership->cm_discount_dur)?'checked':''}}>
              <label for="applyDisc"><strong>Apply discounted rate plan</strong></label>
            </div>
          </div>

          <div id="discFields">
 
            <div class="row">
              <div class="form-group col-md-6">
                {!! Form::label('discAmountType', 'Discounted Amount Type *', ['class' => 'strong']) !!}
                <div>
                  <select name="discAmountType" id="discAmountType" class="form-control onchange-set-neutral" required>
                    <option value="total" {{ $clientMembership->cm_disc_amnt_type == 'total'? 'selected': ''}}>Total</option>
                    <option value="unit" {{ $clientMembership->cm_disc_amnt_type == 'unit'? 'selected' : ''}}> Unit</option>

                  </select>
                </div>
              </div>

              <div class="form-group col-md-6">
                {!! Form::label('discType', 'Discounted Type *', ['class' => 'strong']) !!}
                <div>
                  <select name="discType" id="discType" class="form-control onchange-set-neutral" required>
                    <option value="fixed" {{ $clientMembership->cm_discount_type == 'fixed'? 'selected' : ''}}> Fixed</option>
                    <option value="percent" {{ $clientMembership->cm_discount_type == 'percent'? 'selected': ''}}>Percent</option>
                  </select>
                </div>
              </div>

            </div>
           <div class="row">
            <div class="col-md-6 form-group discAmt {{ $clientMembership->cm_discount_type == 'fixed'? '' : 'hide'}}" >
              {!! Form::label('discAmt', 'Discounted Amount *', ['class' => 'strong']) !!}
              {!! Form::text('discAmt',$clientMembership->cm_disc_amnt_type == 'total' && $clientMembership->cm_discount_type == 'fixed' && $clientMembership?$clientMembership->cm_discounted_amount:($clientMembership->cm_disc_amnt_type == 'unit' && $clientMembership->cm_discount_type == 'fixed'? $clientMembership->cm_disc_per_class_amnt:null), ['class' => 'form-control price-field', 'required']) !!}

            </div>

            
          
            <div class="col-md-6 form-group disPercent {{ $clientMembership->cm_discount_type == 'percent'? '' : 'hide'}}">
              {!! Form::label('disPercent', 'Discounted Percent *', ['class' => 'strong']) !!}
              <div class="input-group">
              <input id="disPercent" type="text" class="form-control" name="disPercent" value="{{$clientMembership->cm_discount_type == 'percent' && $clientMembership?$clientMembership->cm_disc_percentage:null}}">
                <span class="input-group-addon">%</span>
              </div>
          </div>
          <div class="col-md-6 form-group discUnitTotal {{ $clientMembership->cm_disc_amnt_type == 'unit'? '' : 'hide'}}">
            {!! Form::label('discUnitTotal', 'Discounted Total', ['class' => 'strong']) !!}
            {!! Form::text('discUnitTotal',$clientMembership->cm_disc_amnt_type == 'unit' && $clientMembership->cm_discounted_amount?$clientMembership->cm_discounted_amount:null, ['class' => 'form-control price-field', 'required','readonly']) !!}

          </div>
          <div class="col-md-6 form-group discPerAmt {{ $clientMembership->cm_discount_type == 'percent'? '' : 'hide'}}" >
            {!! Form::label('discPerAmt', 'Discounted Percent Amount *', ['class' => 'strong']) !!}
            {!! Form::text('discPerAmt',$clientMembership->cm_disc_amnt_type == 'unit' &&  $clientMembership->cm_discount_type == 'percent'? $clientMembership->cm_disc_per_class_amnt: ($clientMembership->cm_disc_amnt_type == 'total' && $clientMembership->cm_discount_type == 'percent'?$clientMembership->cm_discounted_amount:null), ['class' => 'form-control price-field', 'required','readonly'],['id' => 'discPerAmt']) !!}

          </div>
          
          <div class="col-md-6 form-group"> 
            {!! Form::label('discDur', 'Discount Duration *', ['class' => 'strong']) !!} 
            <div>
              <select name="discDur" id="discDur" class="form-control onchange-set-neutral mw-82p" required>
                <option value="">-- Select --</option>
                <?php
                $discDur = ($clientMembership)?$clientMembership->cm_discount_dur:'';
                ?>
                @for($i=1; $i<=12; $i++)
                  <option value="{{$i}}" {{($discDur == $i)?'selected':''}}>{{$i}}</option>
                @endfor
                <option value="-1" {{($discDur == -1)?'selected':''}}>Forever</option>
              </select>
              
              {{-- Form::select('discDur', $options, $clientMembership?$clientMembership->cm_discount_dur:null, ['class' => 'form-control onchange-set-neutral mw-93p', 'required']) --}}
              
              times
            </div>
          </div>
          </div>
        </div>

        <!-- Start: membership class -->
        <fieldset class="padding-15 hidden" id="session-group">
          <legend> 
            Sessions&nbsp;
            <a class="btn btn-xs btn-primary" href="#" id="add-member-session">
                <i class=" fa fa-plus fa fa-white"></i>
            </a>
          </legend>
          <div class="row m-b-10 hidden-xs">
            <div class="col-sm-2">
              <strong>Name</strong>
            </div>
            <div class="col-sm-1">
              <strong>Limit</strong>
            </div>
            <div class="col-sm-2">
              <strong>Type</strong>
            </div>
            <div class="col-sm-2">
              <strong>Unit price</strong>
            </div>
            <div class="col-sm-2">
              <strong>Total price</strong>
            </div>
            <div class="col-sm-1">
              <strong>Discount</strong>
            </div>
            
            <div class="col-sm-1">
              
            </div>
          </div>
          <div class="row hidden">
            <div class="col-sm-2 col-xs-6">
              <div class="form-group sessionGroup">
                  {!! Form::select('mem_session', [], null, ['class' => 'form-control mem_session ', 'data-title'=>'-- Select -- ', 'required'=>'required']) !!}
              </div>
            </div>
            <div class="col-sm-1 col-xs-6">
              <div class="form-group">
                  {!! Form::number('session_mem_limit', null, ['class' => 'form-control numericField session_mem_limit', 'min'=>'1', 'required'=>'required']) !!}
              </div>
            </div>
            <div class="col-sm-2 col-xs-6">
              <div class="form-group limitTypeGroup">
                {!! Form::select('session_mem_type', ['every_week'=>'no of classes every week','every_fortnight'=>'no of classes every fortnight','every_month'=>'no of classes every month'], null, ['class' => 'form-control session_mem_type','data-title'=>'-- Select -- ']) !!}
              </div>
            </div>
            <div class="col-sm-2 col-xs-6">
              <div class="form-group">
                {!! Form::text('session_mem_unit_price', null, ['class' => 'form-control price-field session_mem_unit_price', 'required'=>'required','data-session-original-unit'=>'', 'readonly']) !!}
                
              </div>
            </div>
            
            <div class="col-sm-2 col-xs-6">
              <div class="form-group">
                {!! Form::text('session_mem_price', null, ['class' => 'form-control price-field session_mem_price', 'required'=>'required', 'data-row-count'=>'','data-session-discount-type'=>'', 'data-session-discount-amount'=>'','data-session-original-total'=>'','data-session-discount-price'=>'' ,'readonly']) !!}
              </div>
            </div>
            <div class="col-sm-2 col-xs-6">
              <div class="form-group">
                <div class="sessionFinalDiscount"></div>
                <div class="input-group">
                  <input type="text" class="form-control discount-type-data" name="discountTypeData" value="" readonly>
                    <span class="input-group-addon discountName"></span>
                </div>
              </div>
            </div>

            <div class="col-sm-1 col-xs-6">
              <div class="form-group sessionDiscountPerAmount hide">
                <div class="sessionPercentFinal"></div>
                <div class="input-group">
                  <input type="text" class="form-control session-discount-per-data" name="sessionDiscountPerData" value="" readonly>
                </div>
              </div>
            </div>
            <div class="col-sm-2 col-xs-8">
              <div class="form-group">
              <a class="deleteDiscountSession" href="#"><i class="m-t-10 fa fa-trash"></i> Delete Discount</a>
            </div>
            </div>

            <div class="col-sm-1 col-xs-2">
               <div class="form-group">
              <a class="applyDiscount" name="discountRow" data-toggle="popover" >
            <i class="m-t-10 fa fa-scissors fa-lg fa-fw apply-session-discount"></i>
                      </a>
                  </div>
                </div>
                  
            <div class="col-sm-1 col-xs-2">
               <div class="form-group">
              <a class="btn btn-xs btn-red remove-member-service-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
            </div>
          </div>
          </div>
        </fieldset>
        <!-- End: membership class -->
            
          <!-- Start: membership service -->
          <fieldset class="padding-15 hidden" id="service-group">
            <legend> 
              Services&nbsp;
              <a class="btn btn-xs btn-primary" href="#" id="add-member-service">
                  <i class=" fa fa-plus fa fa-white"></i>
              </a>
            </legend>
            <div class="row m-b-10 hidden-xs">
              <div class="col-sm-2">
                <strong>Name</strong>
              </div>
              <div class="col-sm-1">
                <strong>Limit</strong>
              </div>
              <div class="col-sm-2">
                <strong>Type</strong>
              </div>
              <div class="col-sm-2">
                <strong>Unit price</strong>
              </div>
              <div class="col-sm-2">
                <strong>Total price</strong>
              </div>
              <div class="col-sm-2">
                <strong>Discount</strong>
              </div>
              <div class="col-sm-1">
                
              </div>
            </div>
            <div class="row hidden">
              <div class="col-sm-2 col-xs-6">
                <div class="form-group serviceGroup">
                    {!! Form::select('mem_service', [], null, ['class' => 'form-control mem_service ', 'data-title'=>'-- Select -- ', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-1 col-xs-6">
                <div class="form-group">
                    {!! Form::number('mem_limit', null, ['class' => 'form-control numericField mem_limit', 'min'=>'1', 'required'=>'required']) !!}
                </div>
              </div>
              <div class="col-sm-2 col-xs-6">
                <div class="form-group limitTypeGroup">
                  {!! Form::select('mem_type', ['every_week'=>'no of services every week','every_fortnight'=>'no of services every fortnight', 'every_month'=>'no of services every month'], null, ['class' => 'form-control mem_type','data-title'=>'-- Select -- ']) !!}
                </div>
              </div>
              <div class="col-sm-2 col-xs-6">
                <div class="form-group">
                  {!! Form::text('mem_unit_price', null, ['class' => 'form-control price-field mem_unit_price','data-service-unit-total'=>'', 'required'=>'required', 'readonly']) !!}
                  
                </div>
              </div>
              <div class="col-sm-2 col-xs-6">
                <div class="form-group">
                  {!! Form::text('mem_price', null, ['class' => 'form-control price-field mem_price', 'required'=>'required', 'data-row-count'=>'','data-discount-type'=>'', 'data-discount-amount'=>'', 'data-service-discount-price'=>'','data-service-original-total'=>'', 'readonly']) !!}
                </div>
              </div>

              <div class="col-sm-2 col-xs-6">
                <div class="form-group">
                  <div class="serviceFinalDiscount"></div>
                  <div class="input-group">
                    <input type="text" class="form-control service-discount-type-data" name="serviceDiscountTypeData" value="" readonly>
                      <span class="input-group-addon serviceDiscountName"></span>
                  </div>
                </div>
              </div>

              <div class="col-sm-1 col-xs-6">
                <div class="form-group serviceDiscountPerAmount hide">
                  <div class="servicePercentFinal"></div>
                  <div class="input-group">
                    <input type="text" class="form-control service-discount-per-data" name="serviceDiscountPerData" value="" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-2 col-xs-2">
                 <div class="form-group">
                <a class="deleteDiscountService" href="#"><i class="m-t-10 fa fa-trash"></i> Delete Discount</a>
              </div>
            </div>
              <div class="col-sm-1 col-xs-2">
                 <div class="form-group">
                <a class="applyDiscount" name="discountRow" data-toggle="popover" >
              <i class="m-t-10 fa fa-scissors fa-lg fa-fw apply-service-discount"></i>
                        </a>
                      </div>
                    </div>
              <div class="col-sm-1 col-xs-2">
                 <div class="form-group">
                <a class="btn btn-xs btn-red remove-member-service-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
              </div>
            </div>
            </div>
          </fieldset>
          <!-- End : membership service -->
         
          <div class="form-group">
            {!! Form::label('grandtotal', 'Grand total *', ['class' => 'strong']) !!}
            {!! Form::text('grandtotalprice', 0, ['class' => 'form-control total-field', 'required' , 'readonly']) !!}
            <span class="help-block"></span>
          </div>
          <div class="form-group"> 
            {!! Form::label('payPlan', 'Payment Plan *', ['class' => 'strong']) !!} 
            <select name="payPlan" id="payPlan" class="form-control onchange-set-neutral" required>
              <option value="">-- Select --</option>
              <?php 
              $payPlan = $clientMembership?$clientMembership->cm_pay_plan:'month'; 
              $plans = memberShipPayPlans();
              ?>
              @foreach($plans as $planId => $plan)
                <option value="{{$planId}}" {{($payPlan == $planId)?'selected':''}} data-unit="{{$plan['unit']}}" data-amount="{{$plan['amount']}}">{{$plan['name']}}</option>
              @endforeach
            </select>
          </div>
         
          
            <div class="form-group">
              {!! Form::label('paymentAmount', 'Payment amount *', ['class' => 'strong']) !!}
              {!! Form::text('paymentAmount', 0, ['class' => 'form-control paymentAmount', 'required' , 'readonly']) !!}
              <span class="help-block"></span>
            </div>
            <div class="form-group"> 
              {!! Form::label('payBy', 'Payment Option *', ['class' => 'strong']) !!} 
              <select name="payBy" id="payBy" class="form-control onchange-set-neutral" required>
                <option value="">-- Select --</option>
                @if(isset($paymenttype) && count($paymenttype))
                <?php $memPayType = $clientMembership?$clientMembership->cm_payment_option:0; ?>
                @foreach($paymenttype as $value)
                <option value="{{ $value->stipt_payment_types }}" data-paytype-id="{!! $value->stipt_id !!}" {{($memPayType == $value->stipt_id)?'selected':''}} >{{ $value->stipt_payment_types }}</option>
                @endforeach
                @endif
              </select>
            </div>
          

          

        
          
          <div class="form-group">
            {!! Form::label('membStartDate', 'Commencement Date *', ['class' => 'strong']) !!}
             {!! Form::text('membStartDate', $clientMembership?$clientMembership->cm_start_date:null, ['class' => 'form-control onchange-set-neutral', 'required', 'autocomplete' => 'off', 'readonly']) !!}
            {{-- {!! Form::text('membStartDate', $clientMembership?calcMembStartDate($clientMembership->cm_pay_plan, $clientMembership->cm_due_date):null, ['class' => 'form-control onchange-set-neutral', 'required', 'autocomplete' => 'off', 'readonly']) !!} --}}
            <span class="help-block"></span>
          </div>
         
        {!! Form::close() !!}  
      </div>
      <div class="modal-footer clearfix">
        @if($clientMembership)
          <a class="btn btn-red pull-left memeber-xs-btn" id="cancelMembOpt" href="#">
            <i class="glyphicon glyphicon-trash"></i>
            Cancel Membership
          </a>
        @endif
        <button type="button" class="btn btn-default memeber-xs-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary memeber-xs-btn" id="editMembSubModelSubmit">Submit</button>
      </div>
    </div>
  </div>      
</div>
<!-- End: Edit Membership Subscription Modal -->

<!-- Start: Update Membership Subscription Modal -->
<div class="modal fade both-modal" id="updateClientMemb" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update membership</h4>
      </div>
      <div class="modal-body bg-white">
        {!! Form::open(['url' => '', 'role' => 'form']) !!}
          <div class="form-group">
            <div>
              <div class="radio clip-radio radio-primary radio-inline m-b-0">
                <input name="updateOpt" id="cm-with-pro-rate" value="1" type="radio" class="onchange-set-neutral">
                <label for="cm-with-pro-rate">
                  Change membership immediately with pro-rate.
                </label>
              </div>
            </div>
            <div>
              <div class="radio clip-radio radio-primary radio-inline m-b-0">
                <input name="updateOpt" id="cm-on-next-cycle" value="2" type="radio" class="onchange-set-neutral">
                <label for="cm-on-next-cycle">
                  Change membership on next cycle.
                </label>
              </div>
            </div>
            <span class="help-block"></span>
          </div>
        {!! Form::close()!!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateClientMembSubmit">Submit</button>
      </div>
    </div>    
  </div>
</div>
<!-- End: Update Membership Subscription Modal -->

<!-- Start: makeup modal -->
<!--Start: Make-up session count Model-->

<div class="modal fade" id="raiseMakeUpModelForMemebership" role="dialog">
  <div class="modal-dialog">   
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EPIC Credit</h4>
      </div>
      <div class="modal-body bg-white">
        {!! Form::open(['url' => '', 'role' => 'form']) !!}
        {!! Form::hidden('clientId') !!}
        {!! Form::hidden('makeupCreate') !!}
          <div class="row m-b-10">
              <div class="col-sm-12 sucMes hidden"></div>    
          </div>
          <div class="form-group">
              {!! Form::label('purpose', 'Purpose *', ['class' => 'strong']) !!}
              <div>
                {!! Form::select('purpose',[''=>'-- Select --','class'=>'Class Makeup','service'=>'Service Makeup','manual'=>'Manual by Admin'], null, ['class' => 'form-control onchange-set-neutral', 'required'=>'required']) !!}
              </div>
          </div>
          <div class="form-group">
              
              {!! Form::label('amount', 'Amount *', ['class' => 'strong']) !!}
              {!! Form::text('amount', null, ['class' => 'form-control price-field','required','id'=>'amount']) !!}
              <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                <input type="checkbox" name="makeupCheck" id="makeup-check" value="1"  class="no-clear" >
                <label for="makeup-check" class="m-r-0"><strong>Amount to be deducted</strong></label>
              </div>
          </div>
          <div class="form-group">
              <div>
              <span class="strong">Total EPIC Credit</span>
              </div>
              <div>
                $<span id="netamount">0.00</span><br>
              </div>  
          </div>      
          <div class="form-group">
              <div>
                {!! Form::label('notes', 'Notes', ['class' => 'strong']) !!}
              </div>
              {!! Form::textarea('notes', null, ['class' => 'form-control textarea']) !!}
          </div>
          
       {!! Form::close() !!}  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="delete-memeb">Cancel</button>
        <button type="button" class="btn btn-primary submit">Submit</button>
      </div>
    </div>      
  </div>
</div>
<!--End: Make-up session count Model-->
<!-- End: makeup modal