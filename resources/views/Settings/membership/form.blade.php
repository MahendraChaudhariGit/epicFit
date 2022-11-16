@if(!isset($businessId))
    {!! Form::open(['url' => 'settings/memberships', 'id' => 'form-8', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison']) !!}
    {!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}
    <div class="row"> 
  <!--div class="col-xs-12">
            <p class="margin-top-5 italic">This is a brief summary of the location of your venue or venues.</p>
        </div--> 
</div>
    <div class="row">
@else
    @if(isset($membership))
        {!! Form::model($membership, ['method' => 'patch', 'route' => ['membership.update', $membership->id], 'id' => 'form-8', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @else
        {!! Form::open(['route' => ['membership.store'], 'id' => 'form-8', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
    <div class="row"> @endif
  <div class="sucMes hidden"></div>
  <div class="col-md-6">
        <fieldset class="padding-15">
      <legend> Membership Info </legend>
      <div class="form-group {{ $errors->has('membership_label') ? 'has-error' : ''}}"> {!! Form::label('membership_label', 'Label  *', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <div> {!! Form::text('membership_label', isset($membership)?$membership->me_membership_label:null, ['class' => 'form-control', 'required' => 'required']) !!}
          {!! $errors->first('membership_label', '
          <p class="help-block">:message</p>
          ') !!} </div>
          </div>
      <div class="form-group {{ $errors->has('member_category') ? 'has-error' : ''}}">
        {!! Form::label('member_category', 'Category  *', ['class' => 'strong']) !!} 
        <span class="epic-tooltip" data-toggle="tooltip" title=" "><i class="fa fa-question-circle"></i></span> 
        <!-- <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Category</a> --> 
        <a href="{{ route('membership.getCat') }}" class="pull-right add-more" data-modal-title="Membership Categories" data-field="membershipCat">Manage Categories</a>

          {!! Form::hidden('btn-add-more-action', 'memberships/membershipcategory', ['class' => 'no-clear']) !!}
            
            {!! Form::select('member_category', isset($memberCate)?$memberCate:[], isset($membership) && count($memberCategory)?$memberCategory:null, ['class' => 'form-control membershipCat','multiple', 'required']) !!} 
          {!! $errors->first('member_category', '<p class="help-block">:message</p>') !!} 
      </div>
        
      <div class="form-group {{ $errors->has('me_validity_length') ? 'has-error' : ''}}">  {!! Form::label('me_validity_length', 'Length *', ['class' => 'strong']) !!} 
        <span class="epic-tooltip" data-toggle="tooltip" title=" "><i class="fa fa-question-circle"></i></span>
            <div class="row">
              <div class="col-md-6">
                 {!! Form::number('me_validity_length', isset($membership)?$membership->me_validity_length:null, ['class' => 'form-control numericField', 'min' => 1,'required']) !!}
              {!! $errors->first('me_validity_length', '<p class="help-block">:message</p>') !!} 
              </div>
              <div class="col-md-6">
                 {!! Form::select('me_validity_type', ['day' => 'day(s)','week'=>'week','month' => 'month(s)','year'=>'year'], isset($membership)?$membership->me_validity_type:null, ['class' => 'form-control']) !!} 
              </div>
            </div>
      </div>

      <div class="form-group {{ $errors->has('class_limit') ? 'has-error' : ''}}">
      <div class="moveErrMsg"> 
       {!! Form::label('class_limit', 'Class Limits *', ['class' => 'strong']) !!} 
       <span class="epic-tooltip" data-toggle="tooltip" title=" "><i class="fa fa-question-circle"></i></span>
      
        <div class="row">
          <div class="col-md-4">
                {!! Form::select('class_limit', isset($businessId)?['limited' => 'Limited ','unlimited'=>'Unlimited']:'', isset($membership)?$membership->me_class_limit:null, ['class' => 'form-control class-limit']) !!}
              {!! $errors->first('class_limit', '
              <p class="help-block">:message</p>
              ') !!} 
          </div>
          <div class="col-md-2 class-limit-div <?php if(isset($membership) && $membership->me_class_limit=='unlimited')echo "hidden"; ?>">
               {!! Form::number('class_limit_length', isset($membership)?$membership->me_class_limit_length:0, ['class' => 'form-control numericField ','required','min' => 0]) !!}
              {!! $errors->first('class_limit_length', '
              <p class="help-block">:message</p>
              ') !!} 
          </div>
          <div class="col-md-6 class-limit-div <?php if(isset($membership) && $membership->me_class_limit=='unlimited')echo "hidden"; ?>">
                  {!! Form::select('class_limit_type', isset($businessId)?['every_week'=>'no of classes every week','every_month'=>'no of classes every month', 'every_fortnight'=>'no of classes every fortnight']:'', isset($membership)?$membership->me_class_limit_type:null, ['class' => 'form-control']) !!}
                {!! $errors->first('class_limit_type', '<p class="help-block">:message</p>') !!} <!--'class_card' => 'class card (expire when full)',-->
          </div>
        </div> 
        </div>
        <span class="help-block placeErrMsg"></span>
       </div> 
      <div class="form-group {{ $errors->has('auto_renewal') ? 'has-error' : ''}}"> {!! Form::label('auto_renewal', 'Auto-Renewal *', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=" "><i class="fa fa-question-circle"></i></span>
            <div class="row">
              <div class="col-md-5">
                    <div> {!! Form::select('auto_renewal', isset($businessId)?['on' => 'On','off'=>'Off']:'', isset($membership)?$membership->me_auto_renewal:null, ['class' => 'form-control Auto-Renewal']) !!}
                  {!! $errors->first('auto_renewal', '<p class="help-block">:message</p>') !!} </div>
              </div>
              <div class="col-md-7 Auto-Renewal-div">
                <div> {!! Form::select('renewal_type', isset($businessId)?array_merge(array('Copy Current Membership'), $businessmember):array('Copy Current Membership'), isset($membership)?$membership->me_auto_renewal_type:null, ['class' => 'form-control Auto-Renewal']) !!}
              {!! $errors->first('renewal_type', '<p class="help-block">:message</p>') !!} </div>
              </div>
        </div>
          </div>
    </fieldset>
    <fieldset class="padding-15">
      <legend>Classes 
         
          <!--button type="button" class="btn btn-primary btn-xs">
               Edit Classes
            </button--> 
      </legend>
      <div class="form-group {{ $errors->has('staffClasses') ? 'has-error' : ''}}">
            {!! Form::label('mem_Classes', 'Classes', ['class' => 'strong']) !!}
            <span class="epic-tooltip" data-toggle="tooltip" title="Here will be tooltip text!"><i class="fa fa-question-circle"></i></span>
             @if(!isset($subview)) 
               <a href="#" class="pull-right callSubview" data-target-subview="class"> + Add New Class</a>
             @endif 
            <div>
                {!! Form::select('mem_Classes', isset($businessId)?$clses:[], isset($membership) && count($memberClass)?$memberClass:null, ['class' => 'form-control class', 'multiple']) !!}
                {!! $errors->first('mem_Classes', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
      <!-- <div class="form-group"> {!! Form::label('enrollments', 'Enrollments', ['class' => 'strong']) !!} </div> -->
    
      
      <!--div class="form-group {{ $errors->has('one_on_one_call_client_online') ? 'has-error' : ''}}">
            
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="one_on_one_call_client_online" id="one_on_one_call_client_online2" value="1">
                        <label for="one_on_one_call_client_online2">
                            <strong>Group</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows clients to book this trainer and services from the online hub"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('one_on_one_call_client_online', '<p class="help-block">:message</p>') !!}
                    </div>
                </div-->
      
      <!-- <div class="form-group {{ $errors->has('enrollment_limit') ? 'has-error' : ''}}"> {!! Form::label('enrollment_limit', 'Enrollment Limit  *', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
            <div class="moveErrMsg"> 
              {{-- Form::select('enrollment_limit', isset($businessId)?['' => '-- Select --','2' => 'Upto 2 Classes','3'=>'Upto 3 Classes','4'=>'Upto 4 Classes','5'=>'Upto 5 Classes','6'=>'Upto 6 Classes','7'=>'Upto 7 Classes','8'=>'Upto 8 Classes','9'=>'Upto 9 Classes','10'=>'Upto 10 Classes']:['' => '-- Select --'], isset($membership)?$membership->me_enrollment_limit:null, ['class' => 'form-control']) --}}
              Upto
              {!! Form::number('enrollment_limit', isset($membership)?$membership->me_enrollment_limit:null, ['class' => 'form-control numericField resetDisp mw-82p', 'required', 'min' => 1]) !!}
              classes
              {!! $errors->first('enrollment_limit', '<p class="help-block">:message</p>') !!} 
            </div>
            <span class="help-block placeErrMsg"></span>
          </div> -->
    </fieldset>
    <fieldset class="padding-15 service-fieldset">
      <legend> Services&nbsp;
        @if(!isset($subview))
          <!-- <a href="#" class="btn btn-sm p-y-0 callSubview" data-target-subview="service">+ Add New Service</a> -->
        @endif
      </legend>
      <div class="row m-b-10">
        <div class="col-sm-4 col-xs-5">
          <strong>Name</strong>
        </div>
        <div class="col-sm-2 col-xs-5">
          <strong>Limit</strong>
        </div>
        <div class="col-sm-4 col-xs-5">
          <strong>Type</strong>
        </div>
        <div class="col-sm-2 col-xs-2">
          <a class="btn btn-xs btn-primary" href="#" id="add-memb-service"><i class=" fa fa-plus fa fa-white"></i></a>
        </div>
      </div>
      <div class="row hidden" id="service-clone-row">
        <div class="col-sm-4 col-xs-4">
          <div class="form-group serviceDDGroup">
              {!! Form::select('clone_serv', isset($businessId)?$serv:[], null, ['class' => 'form-control mem_service','data-title'=>'-- Select -- ']) !!}
          </div>
        </div>
        <div class="col-sm-2 col-xs-2">
          <div class="form-group">
              {!! Form::number('clone_limt', null, ['class' => 'form-control numericField mem_service_limit', 'min'=>'1']) !!}
          </div>
        </div>
        <div class="col-sm-4 col-xs-4">
          <div class="form-group limitTypeDDGroup">
            {!! Form::select('clone_type', ['every_week'=>'no of services every week','every_month'=>'no of services every month','every_fortnight'=>'no of services every fortnight'], null, ['class' => 'form-control mem_type','data-title'=>'-- Select -- ']) !!}
          </div>
        </div>
        <div class="col-sm-2 col-xs-2">
          <a class="btn btn-xs btn-red remove-memb-service-row" href="#" '=""><i class="fa fa-times fa fa-white"></i></a>
        </div>
      </div>
      
      @if(isset($membership) && $membership->serviceMemberWithPivot->count())
      <?php  $i = 0; ?>
      @foreach($membership->serviceMemberWithPivot as $service)
      <div class="row">
        <div class="col-sm-4 col-xs-4">
          <div class="form-group">
              {!! Form::select('mem_services'.$i, isset($businessId)?$serv:[], $service['pivot']->sme_service_id, ['class' => 'form-control service mem-service-cls', 'data-title'=>'-- Select -- ', 'required'=>'required']) !!}
          </div>
        </div>
        <div class="col-sm-2 col-xs-2">
          <div class="form-group">
              {!! Form::number('mem_limit'.$i, $service['pivot']->sme_service_limit, ['class' => 'form-control numericField mem-service-cls', 'min'=>'1', 'required'=>'required']) !!}
          </div>
        </div>
        <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            {!! Form::select('mem_type'.$i, ['every_week'=>'no of services every week','every_month'=>'no of services every month','every_fortnight'=>'no of services every fortnight'], $service['pivot']->sme_service_limit_type, ['class' => 'form-control mem_type','data-title'=>'-- Select -- ']) !!}
          </div>
        </div>
        <div class="col-sm-2 col-xs-2">
          <a class="btn btn-xs btn-red remove-memb-service-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
        </div>
      </div>
      <?php  $i++; ?>
      @endforeach
      @endif
      <div class="row service-warning <?php if(isset($membership) && $membership->servicemember->count())echo'hidden';else echo''; ?>">
        <div class="col-md-12">
          <div class="alert alert-warning">
            No any service in this membership.
          </div>
        </div>
      </div>
     
    </fieldset>
        <fieldset class="padding-15">
      <legend> Billing Details </legend>
      
      
      <div class="form-group"> 
        {!! Form::label('tax','Tax',['class' => 'strong']) !!} 
         <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <select class="form-control gstTaxable" name="taxable" value="">
            <option value="">--select--</option>        
              <option value="Excluding" {{isset($membership) && $membership->me_tax == 'Excluding'?selected :'' }}>Excluding</option>
            <option value="Including" {{isset($membership) && $membership->me_tax == 'Including'?selected :'' }}>Including</option>
            <option value="N/A" {{isset($membership) && $membership->me_tax == 'N/A'?selected :'' }}>N/A</option>
          </select>
        
       </div>

          
      <div class="form-group {{ $errors->has('installment_amount') ? 'has-error' : ''}}"> {!! Form::label('installment_amount', 'Unit Amount *', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
        <div> {!! Form::text('installment_amount', isset($membership)?$membership->me_installment_amt:null, ['class' => 'form-control price-field installment_amount', 'required' => 'required']) !!}
            {!! $errors->first('installment_amount', '<p class="help-block">:message</p>') !!}

              <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                <input type="checkbox" id="me_prorate"  name="me_prorate" value="1" {{ isset($membership) && $membership->me_prorate?'checked':'' }}>
                <label for="me_prorate"> <strong>Prorate first month(using start date)</strong> </label>
              </div>
        </div>
      </div>

      <div class="form-group"> {!! Form::label('unit_amount', 'Amount*', ['class' => 'strong']) !!} 
        <div> {!! Form::text('unit_amount',isset($membership)?$membership->me_unit_amt:null, ['class' => 'form-control total-price']) !!}
        </div>
      </div>  
     
      <div class="form-group {{ $errors->has('signup_fee') ? 'has-error' : ''}}"> {!! Form::label('signup_fee', 'Signup Fee *', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
            <div> {!! Form::text('signup_fee', isset($membership)?$membership->me_signup_fee:null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
          {!! $errors->first('signup_fee', '
          <p class="help-block">:message</p>') !!}

          <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                <input type="checkbox" id="member_change_signup"  name="member_change_signup" value="1" {{ isset($membership) && $membership->me_change_signup_fee?'checked':'' }}>
                <label for="member_change_signup" > <strong>Charge signup fee again upon renewal</strong> </label>
              </div>
        </div>
          </div>


         
      
           


      <div class="form-group {{ $errors->has('income_category') ? 'has-error' : ''}}"> 
      {!! Form::label('income_category', 'Income Category  ', ['class' => 'strong']) !!} 
          <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <a href="{{ route('income.getCat') }}" class="pull-right add-more" data-modal-title="Income Categories" data-field="incomeCat">Manage Categories</a>
        	<!-- <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Category</a>  -->
          	{!! Form::hidden('btn-add-more-action', 'memberships/incomecategory', ['class' => 'no-clear']) !!}
            <div> {!! Form::select('income_category', isset($businessId)?$incomeCategory:['' => '-- Select --'], isset($membership)?$membership->me_income_category:null, ['class' => 'form-control incomeCat']) !!}
          {!! $errors->first('income_category', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
    </fieldset>
  </div>
  <div class="col-md-6">
        <fieldset class="padding-15">
      <legend> Online </legend>
      
      <div class=" form-group {{ $errors->has('public_member') ? 'has-error' : ''}}"> 
      {!! Form::label('public', 'Public', ['class' => 'strong']) !!}
            <div class="checkbox clip-check check-primary m-b-0">
          <input type="checkbox" name="public_member" id="public_member_website" value="1" {{ isset($membership) && $membership->me_public?'checked':'' }}">
          <label for="public_member_website"> <strong>Yes, display this option on my member website</strong> <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span> </label>
        </div>
          </div>
      <div class="online_div  {{ isset($membership) && $membership->me_public?'':'hidden' }}" >
            <div class=" form-group {{ $errors->has('public_description') ? 'has-error' : ''}}"> {!! Form::label('public_description', ' Description *', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <div> {!! Form::textarea('public_description', isset($membership)?$membership->me_public_description:null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('public_description', '
                <p class="help-block">:message</p>
                ') !!} </div>
        </div>
            <div class="form-group {{ $errors->has('due_at_signup') ? 'has-error' : ''}}"> {!! Form::label('due_at_signup', 'Due at Signup  ', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <div> {!! Form::select('due_at_signup', isset($businessId)?['no_payment_required' => 'No Payment Required ','signupfee_only'=>'Signup fee only','signupfee1'=>'signup fee + first','signupfee_last'=>'signup fee first + last']:'', isset($membership)?$membership->me_due_signup:null, ['class' => 'form-control']) !!}
                {!! $errors->first('due_at_signup', '
                <p class="help-block">:message</p>
                ') !!} </div>
        </div>
            <div class="row">
          <div class="col-md-6">
                <div class="form-group {{ $errors->has('enrollment_start_date') ? 'has-error' : ''}}"> {!! Form::label('enrollment_start_date', 'Enrollment Begins', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
              <div> {!! Form::text('enrollment_start_date', null, ['class' => 'form-control datepicker']) !!}
                    {!! $errors->first('enrollment_start_date', '
                    <p class="help-block">:message</p>
                    ') !!} </div>
            </div>
              </div>
          <div class="col-md-6">
                <div class="form-group {{ $errors->has('enrollment_end_date') ? 'has-error' : ''}}"> {!! Form::label('enrollment_end_date', 'Enrollment Ends', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
              <div> {!! Form::text('enrollment_end_date', null, ['class' => 'form-control datepicker']) !!}
                    {!! $errors->first('enrollment_end_date', '
                    <p class="help-block">:message</p>
                    ') !!} </div>
            </div>
              </div>
        </div>
            <div class="form-group {{ $errors->has('begin_date') ? 'has-error' : ''}}"> 
            {!! Form::label('begin_date', 'Begin Date', ['class' => 'strong']) !!} 
            <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                <input type="checkbox" id="mem_begins_on_date" name="mem_begins_on_date" value="1" {{ isset($membership) && $membership->mem_begins_on_date?'checked':'' }}>
                <label for="mem_begins_on_date" > <strong>membership begins on date it is created</strong> </label>
              </div>
          <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                <input type="checkbox" id="mem_begins_on" name="mem_begins_on" value="1" {{ isset($membership) && $membership->mem_begins_on?'checked':'' }}>
                <label for="mem_begins_on" > <strong>membership begins on</strong> </label>
              </div>
          <div>
                <div class="checkbox clip-check check-primary m-b-0 m-t-5"> 
                {!! Form::text('me_begin_date', isset($membership)?$membership->me_begin_date:null, ['class' => 'form-control datepicker']) !!}
              {!! $errors->first('begin_date', '<p class="help-block">:message</p>') !!} </div>
              </div>
        </div>
          </div>
    </fieldset>
        <fieldset class="padding-15">
      <legend> Triggers </legend>
      <div class=" form-group {{ $errors->has('member_added_group') ? 'has-error' : ''}}"> 
      {!! Form::label('member_added_group', 'Add To Group(s)  ', ['class' => 'strong']) !!} 
      <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span> 
      <a href="javascript:void(0)" class="pull-right btn-add-more">+ Add Group</a> 
      {!! Form::hidden('btn-add-more-action', 'memberships/membershipgroup', ['class' => 'no-clear']) !!}
            <div> {!! Form::select('member_added_group', isset($businessId)?$memberGroup:['' => '-- Select --'], isset($membership) && count($selectedmemberGroup)?$selectedmemberGroup:null, ['class' => 'form-control', 'multiple']) !!}
          {!! $errors->first('member_added_group', '<p class="help-block">:message</p>') !!}
           </div>
            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
          <input type="checkbox" id="select_multiple_dd" class="selAllDd" >
          <label for="select_multiple_dd" class="no-error-label"> <strong>Select All</strong> </label>
        </div>
          </div>
      <div class="form-group {{ $errors->has('addOn_member') ? 'has-error' : ''}}"> 
      {!! Form::label('addOn_member', 'Add On  ', ['class' => 'strong']) !!} 
      <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>

            <div> {!! Form::select('addOn_member', isset($businessId)?array_merge(array(''=>' -- Select -- '),$businessmember):['' => '-- Select --'], isset($membership)?$membership->addOn_member:null, ['class' => 'form-control']) !!}
          {!! $errors->first('addOn_member', '<p class="help-block">:message</p>') !!} 
          </div>
          </div>
      <div class="form-group {{ $errors->has('notify_staff') ? 'has-error' : ''}}"> 
      {!! Form::label('notify_staff', 'Notify Staff', ['class' => 'strong']) !!} 
      <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
            <div> {!! Form::select('notify_staff', isset($businessId)?$memberStaff:['' => '-- Select --'], isset($membership) && count($selectedmemberStaff)?$selectedmemberStaff:null, ['class' => 'form-control','multiple']) !!}
          {!! $errors->first('notify_staff', '
          <p class="help-block">:message</p>
          ') !!} </div>
          </div>
      <div class="form-group {{ $errors->has('me_visible') ? 'has-error' : ''}}"> {!! Form::label('visible', 'Visible', ['class' => 'strong hidden']) !!}
            <div class="checkbox clip-check check-primary m-b-0">
          <input type="checkbox" name="me_visible" id="me_visible" value="1" {{ isset($membership) && $membership->me_visible?'checked':'' }}>
          <label for="me_visible"> <strong>Show this membership on the 'Add Membership' signup page<br>
            (studio only)</strong> <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span> </label>
          {!! $errors->first('me_visible', '<p class="help-block">:message</p>') !!} </div>
          </div>
      <div class="form-group {{ $errors->has('me_show_on_kiosk') ? 'has-error' : ''}}">
       {!! Form::label('show_on_kiosk', 'Show on Kiosk', ['class' => 'strong hidden']) !!}
            <div class="checkbox clip-check check-primary m-b-0">
          <input type="checkbox" name="me_show_on_kiosk" id="me_show_on_kiosk" value="1" {{ isset($membership) && $membership->me_show_on_kiosk?'checked':'' }}>
          <label for="me_show_on_kiosk"> <strong>Show this membership on ipad Kiosk app </strong> <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span> </label>
          {!! $errors->first('me_show_on_kiosk', '<p class="help-block">:message</p>') !!} </div>
          </div>
    </fieldset>
      </div>
</div>
    <div class="row">
  <div class="col-sm-12">
        <div class="form-group">
      <button class="btn btn-primary btn-wide pull-right btn-add-more-form"> 
      @if(isset($membership)) 
      <i class="fa fa-edit"></i> Update Membership
          @else 
          <i class="fa fa-plus"></i> Add Membership
          @endif 
          </button>
      @if(isset($subview))
      <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button"> Close </button>
      @endif </div>
      </div>
</div>
{!! Form::close() !!}

{{-- <div class="modal fade" id="addTaxModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="myModalLabel">Add Tax Rate</h4>
      </div>
      <div class="modal-body bg-white">
      {!! Form::open(['url' => 'settings/business/memberships/addmembershiptax', 'id' => 'add-membership-tax-label', 'class' => 'margin-bottom-30']) !!}
       {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
        <div class="form-group {{ $errors->has('tax_label') ? 'has-error' : ''}}"> 
        {!! Form::label('tax_label', 'Tax Name  *', ['class' => 'strong']) !!} 
         <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <div> {!! Form::text('tax_label', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('tax_label', '<p class="help-block">:message</p>
            ') !!} </div>
        </div>
        <div class="form-group {{ $errors->has('tax_rate') ? 'has-error' : ''}}"> 
        {!! Form::label('tax_rate', 'Rate *', ['class' => 'strong']) !!} <span class="epic-tooltip" data-toggle="tooltip" title=""><i class="fa fa-question-circle"></i></span>
          <div> {!! Form::text('tax_rate', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
            {!! $errors->first('tax_rate', '
            <p class="help-block">:message</p>
            ') !!} </div>
        </div>
        {!! Form::close() !!}
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-o" data-dismiss="modal"> Close </button>
        <button type="button" class="btn btn-primary membershit-tax-save"> Save  </button>
      </div>
    </div>
  </div>
</div> --}}
<!-- /Default Modal -->
