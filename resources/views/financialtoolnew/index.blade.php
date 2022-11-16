@extends('blank')
@section('page-title')
<span > Epic Process </span>
@stop
@section('required-styles')
{!! Html::style('assets/css/custom.css?v='.time()) !!}
@stop

@section('content')
<div class="panel panel-white">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span> 
            Work out your finances
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" 
                data-target="#configModal">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-process">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
         <form action="#" role="form" class="smart-wizard" id="form" data-form-mode="view">
        {!! Form::token() !!}
            <map name="Map" id="Map"></map>
            <div id="wizard" class="swMain parqForm">
                <ul>
                    <li>
                        <a href="#step-1">
                            <div class="stepNumber">
                                1
                            </div>
                            <span class="stepDesc"><small> Business Structure </small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-2">
                            <div class="stepNumber">
                                2
                            </div>
                            <span class="stepDesc"><small>Setup Expenses</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-3">
                            <div class="stepNumber">
                                3
                            </div>
                            <span class="stepDesc"><small>Operation Expenses</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-4">
                            <div class="stepNumber">
                                4
                            </div>
                            <span class="stepDesc"><small>Sales Projections</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-5">
                            <div class="stepNumber">
                                5
                            </div>
                            <span class="stepDesc"><small>Financial Summary</small></span>
                        </a>
                    </li>
                </ul>
                
                <!-- start: WIZARD STEP 1 -->
                 <div id="step-1">
                    @include('financialtoolnew.financialtool-step1')
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o next-step btn-wide pull-right" data-step="1">
                                Save & Continue <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            <button class="btn btn-primary submit-step btn-wide pull-right margin-right-15" data-step="1">
                                Save 
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 1 -->


                <!-- start: WIZARD STEP 2 -->
                <div id="step-2">
                     <div class="sucMes hidden">
                        {!! displayAlert()!!}
                    </div>
                    <div class="row">
                        @include('financialtoolnew.financialtool-step2')
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o back-step btn-wide pull-left">
                                <i class="fa fa-circle-arrow-left"></i> Back
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o next-step btn-wide pull-right" data-step="2">
                                Save & Continue <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            <button class="btn btn-primary submit-step btn-wide pull-right margin-right-15" data-step="2">
                               Save 
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 2 -->
                    
                <!-- start: WIZARD STEP 3 -->
                <div id="step-3">
                    @include('financialtoolnew.financialtool-step3')
                        <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o back-step btn-wide pull-left">
                                <i class="fa fa-circle-arrow-left"></i> Back
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o next-step btn-wide pull-right" data-step="3">
                                Save & Continue <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            <button class="btn btn-primary submit-step btn-wide pull-right margin-right-15"  data-step="3">
                                Save 
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 3 -->
                        
                <!-- start: WIZARD STEP 4 -->
                <div id="step-4">
                    <div class="row">
                        @include('financialtoolnew.financialtool-step4')
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o back-step btn-wide pull-left">
                                <i class="fa fa-circle-arrow-left"></i> Back
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o next-step btn-wide pull-right" data-step="4">
                                Save & Continue <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            <button class="btn btn-primary submit-step btn-wide pull-right margin-right-15"  data-step="4">
                                Save 
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 4 -->

                <!-- start: WIZARD STEP 5 -->
                <div id="step-5">
                    <div class="row">
                        @include('financialtoolnew.financialtool-step5')
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o back-step btn-wide pull-left">
                                <i class="fa fa-circle-arrow-left"></i> Back
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-o btn-wide pull-right" id="finish-parq">
                                Save & Finish <i class="fa fa-arrow-circle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end: WIZARD STEP 4 -->
                
            </div>
        </form>
    </div>
    <!-- end: PANEL BODY -->

     <!-- Required Modals -->
        @include('financialtoolnew.setup-expenses.expense-modal')
        @include('financialtoolnew.business-expenses.expense-modal')
        @include('financialtoolnew.personal-living-expenses.expense-modal')
        @include('financialtoolnew.partnership-expenses.expense-modal')
    <!-- End : Required Modals -->

</div>
@stop
@section('script')
        
    @if(!empty($timeframe))
        <script>
            var setting_pref_timeframe = '{!! $timeframe->financial_time_frame !!}';
            var get_slab_url = "{{ route('get.slab') }}"
        </script>
    @else   
        <script type="text/javascript">
            var setting_pref_timeframe = null;
        </script> 
    @endif

    @if(isset($financeData->is_gst_registered))
        <script>
            var is_gst_registered = '{!! $financeData->is_gst_registered !!}';
            if(is_gst_registered == 0)
            {
                $('.gst-no').val(0);
                $('.gst-percentage').val(0);
            }
        </script>
    @endif

    @if(!empty($financeData->setup_expenses))
        <script>
            $(document).ready(function () {
                localStorage.setItem('setup_expenses', JSON.stringify({!! $financeData->setup_expenses !!}));
            });
        </script>
    @endif
    @if(!empty($financeData->business_expenses))
        <script>
            localStorage.setItem('business_expenses',JSON.stringify({!! $financeData->business_expenses !!}));
        </script>
    @endif

    @if(!empty($financeData->living_expenses))
        <script>
            localStorage.setItem('living_expenses',JSON.stringify({!! $financeData->living_expenses !!}));
        </script>
    @endif

    @if(!empty($financeData->partnership_expenses))
        <script>
            localStorage.setItem('partnership_expenses',JSON.stringify({!! $financeData->partnership_expenses !!}));
        </script>
    @endif


    @if(!empty($settingPrefData->tax_amount))
        <script type="text/javascript">
           var gst_percetage = "{!! @$settingPrefData->tax_amount !!}";
        </script>
    @endif

   
    {!! Html::script('assets/js/form-wizard-financial-modal1.js') !!}
    {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}   
    {!! Html::script('assets/js/helper.js') !!}

     <script type="text/javascript">
        $(document).on('change','#invested_amount_0 ,.equity_ratio',function(){
            var no_of_partner = $('#no_of_partner').val();
            var partnership_exp_gst_incl = $('#invested_amount_0').val();
            if($(this).attr('id') == 'invested_amount_0')
            {
                var modal = 'partnership';
            } else {
                var modal = null;
            }
            $('#partnership_total').trigger('change');
            calculateAllPartners(no_of_partner , partnership_exp_gst_incl , partnership_exp_gst_incl , modal);
        });
     </script>

     {!! Html::script('assets/js/form-wizard-financial1.js') !!}

     <script type="text/javascript">

            function autoPopulateDrawing(amount)
            {
                // Auto Populate Values for Business Expense
                // $('.business_fill_data').filter(function(){
                //        return $(this).attr('data-business-input-id') === 'Living expenses / drawings';
                // }).val(amount);

                // /**** UPDATE STORAGE FOR AUTOPOPULATE ******/
                //     var BusinessStorage = JSON.parse(localStorage.getItem('business_expenses'));
                //     var businessPID = 'MISCELANEOUS';
                //     var businessINPUTID = 'Living expenses / drawings';
                //     var businessINTPUTVAL = amount;

                //        if (BusinessStorage.hasOwnProperty(businessPID)){
                //            for (i = 0; i < BusinessStorage[businessPID].length; i++) {
                //                if (BusinessStorage[businessPID] && BusinessStorage[businessPID][i].hasOwnProperty(businessINPUTID)) {
                //                    BusinessStorage[businessPID][i][businessINPUTID] = businessINTPUTVAL;
                //                }
                //            }
                //        }
                //        localStorage.setItem('business_expenses',JSON.stringify(BusinessStorage));
                // /**** End : UPDATE STORAGE FOR AUTOPOPULATE ******/
                //  
                  var drawingsAmount = 0;
                   var BusinessStorage = JSON.parse(localStorage.getItem('business_expenses'));
                   var businessPID = 'MISCELANEOUS';
                   var businessINPUTID = 'Living expenses / drawings';
                   var businessINTPUTVAL = amount;
                   var businessCHKID = 'Living expenses / drawings';

                  if (BusinessStorage.hasOwnProperty(businessPID)){
                      for (i = 0; i < BusinessStorage[businessPID].length; i++) {
                          if (BusinessStorage[businessPID] && BusinessStorage[businessPID][i].hasOwnProperty(businessINPUTID)) {

                            var drawingTimeFrame = BusinessStorage[businessPID][i]['timeframe'];
                            var weeklyMnth = 52.1429 , fortnightlyMnth = 26.0714 , monthlyMnth = 12 , annuallyMnth = 0;
                             if(drawingTimeFrame == 'weekly') // check modal time frame
                             {
                               var annualBusinessByLiving =businessINTPUTVAL / weeklyMnth;
                             }
                             else if (drawingTimeFrame == 'fortnightly')
                             {
                               var annualBusinessByLiving =businessINTPUTVAL / fortnightlyMnth
                             }
                             else if (drawingTimeFrame == 'monthly')
                             {
                               var annualBusinessByLiving =businessINTPUTVAL / monthlyMnth;
                             }
                             else if (drawingTimeFrame == 'annually')
                             {
                               var annualBusinessByLiving =businessINTPUTVAL;
                             }
                             BusinessStorage[businessPID][i][businessINPUTID] = annualBusinessByLiving;
                             drawingsAmount += parseFloat(annualBusinessByLiving);
                          }
                      }
                  }
                localStorage.setItem('business_expenses', JSON.stringify(BusinessStorage));
           /**** End : UPDATE STORAGE FOR AUTOPOPULATE ******/

                       // Auto Populate Values for Business Expense
                       $('.business_fill_data').filter(function(){
                              return $(this).attr('data-business-input-id') === 'Living expenses / drawings';
                       }).val((drawingsAmount).toFixed(2));
                $('.business_expense_submit').click();
                
            }

            //  Function to calculate partners dynamically
            function calculateAllPartners(no_of_partner , partnership_amount_sum , partnership_exp_gst_incl , modal = null)
            {
                console.log(no_of_partner , partnership_amount_sum , partnership_exp_gst_incl , modal ,'inside calculateAllPartners');
                var no_of_partner = $('#no_of_partner').val();
                var equity_ratio_count = $('input[name*="equity_ratio[]"]').length;
                var invested_amount_count = $('input[name*="invested_amount[]"]').length;
                var gst_excl_count = $('input[name*="gst_excl[]"]').length;
                var gst_paid_count = $('input[name*="gst_paid[]"]').length;
                var partnership_total = 0;

                var tax_category = $('.business_type :selected').val();
                var amount = partnership_amount_sum;
                $.ajax({
                    type: 'get',
                    url: get_slab_url,
                    data: {
                      tax_category : tax_category,
                      amount : amount
                    },
                    success: function (res) {
                        $('.gst_paid').first().val(res.tax_amount);

                        var gst_paid = $('#gst_paid_0').val();
                        var partnership_exp_gst_excl  = parseFloat(amount) - parseFloat(gst_paid);
                        if(isNaN(partnership_exp_gst_excl)){
                            partnership_exp_gst_excl = 0;
                        }
                        $('.gst_excl').first().val((partnership_exp_gst_excl).toFixed(2));

                        $('.invested_amount').first().val((parseFloat(partnership_amount_sum)).toFixed(2));
                        for(i = 0; i < no_of_partner;i++)
                        {
                            if(i > 0)
                            {
                              var k = i - 1;

                              var first_percentage = $('#equity_ratio_0').val();
                              var last_percentage = $('#equity_ratio_'+i+'').val();
                              
                              // console.log(first_percentage , 'first_percentage');
                              // console.log(last_percentage ,'last_percentage');

                              var invested_amount = (partnership_exp_gst_incl * last_percentage) / first_percentage;

                              // console.log(invested_amount ,'invested_amount');

                              if(invested_amount > 0)
                              {
                                partnership_total += parseFloat(invested_amount);
                                $('#invested_amount_'+i+'').val((invested_amount).toFixed(2));
                              }
                              var excl = (partnership_exp_gst_excl * last_percentage) / first_percentage;
                              // var excl = (partnership_exp_gst_excl) * (last_percentage / first_percentage );
                              // console.log(excl ,'excl');

                              if(excl > 0)
                              {
                                $('#gst_excl_'+i+'').val((excl).toFixed(2));
                              }

                              var gst_paid = parseFloat(invested_amount) - parseFloat(excl);
                              
                              // console.log(gst_paid , 'gst_paid');

                              if(gst_paid > 0)
                              {
                                $('#gst_paid_'+i+'').val((gst_paid).toFixed(2));
                              }
                            }
                        }  // End of for loop
                        if(partnership_total > 0){ 
                            partnership_total += parseFloat($('#invested_amount_0').val());
                            $('#partnership_total').val((partnership_total).toFixed(2));
                            if(modal == 'partnership'){ 
                                calculateAllPartnerCallback(partnership_total);
                            }
                        }  else {
                            $('#partnership_total').val((parseFloat(amount)).toFixed(2)); // totle if no of partner 1
                            if(modal == 'partnership'){ 
                                calculateAllPartnerCallback(amount);
                            }
                        }
                    }
                });

                function calculateAllPartnerCallback(partnership_total)
                {
                    if(partnership_total > 0)
                    {
                        autoPopulateDrawing(partnership_total);
                    }
                }

                

               // $('.invested_amount').first().val(partnership_amount_sum);
               //  for(i = 0; i < no_of_partner;i++)
               //  {
               //      if(i > 0)
               //      {
                      
               //        var partnership_exp_gst_excl = $('#gst_excl_0').val();

               //        var k = i - 1;
               //        var first_percentage = $('#equity_ratio_0').val();
               //        var last_percentage = $('#equity_ratio_'+i+'').val();
                      
               //        console.log(first_percentage , 'first_percentage');
               //        console.log(last_percentage ,'last_percentage');

               //        var invested_amount = (partnership_exp_gst_incl * last_percentage) / first_percentage;

               //        console.log(invested_amount ,'invested_amount');

               //        if(invested_amount > 0)
               //        {
               //          partnership_total += parseFloat(invested_amount);
               //          $('#invested_amount_'+i+'').val((invested_amount).toFixed(2));
               //        }
               //        var excl = (partnership_exp_gst_excl * last_percentage) / first_percentage;
               //        // var excl = (partnership_exp_gst_excl) * (last_percentage / first_percentage );
               //        console.log(excl ,'excl');
               //        if(excl > 0)
               //        {
               //          $('#gst_excl_'+i+'').val((excl).toFixed(2));
               //        }

               //        var gst_paid = parseFloat(invested_amount) - parseFloat(excl);
               //        // console.log(gst_paid , 'gst_paid');
               //        if(gst_paid > 0)
               //        {
               //          $('#gst_paid_'+i+'').val((gst_paid).toFixed(2));
               //        }
               //      }
               //    }  // End of for loop
                  
               //  $('#partnership_total').val((partnership_total).toFixed(2));

            }

            $(document).on('change blur','#partnership_total',function(){
                autoPopulateDrawing($('#partnership_total').val());
            });

            // function toFixedValue(value)
            // {

            // }


        var recordId = "{!! auth()->user()->business_id !!}";
        $(document).ready(function(){

            setTimeout(function(){ enableCheckbox(); }, 3000);
            
            function enableCheckbox() {
                $('.checkbox').click(function (event) {
                  if (!$(event.target).is('input')) {
                     $('input:checkbox', this).prop('checked', function (i, value) {
                      if(value == false) {
                        $(this).val(1);
                        $(this).closest('tr').find('td.input_value input').attr('gst-incl','true');
                      } else {
                        $(this).val(0);
                        $(this).closest('tr').find('td.input_value input').attr('gst-incl','false');
                      }
                      return !value;
                     });
                  }
                });
              }


            // Start : Dynamic Gst No and Percetage Set 
            $(document).on('change','.is_gst_registered',function(){
                var is_gst_registered = $('.is_gst_registered :selected').val();
                var gst_no = "{!! @$settingPrefData->tax_code !!}";
                var gst_percetage = "{!! @$settingPrefData->tax_amount !!}";
                if(is_gst_registered == 1) {
                    $('.gst-no').val(gst_no);
                    $('.gst-percentage').val(gst_percetage);
                } else if(is_gst_registered == 0){
                    $('.gst-no').val(0);
                    $('.gst-percentage').val(0);
                }
            });
            // End : Dynamic Gst No and Percetage Set 

            /********** Start : Setup Expenses *****************/ 
                $(document).on('change blur' , '#se_calculated , #se_est_capital , #se_repayment_monthly_period_capital_req , #se_interest_rate_fr_loan ' ,function(){
                    
                      var currentVal = $(this).val();
                      var currentId = $(this).attr('id');
                      $('#'+currentId+'').val(parseFloat(currentVal).toFixed(2));

                    if($(this).attr('id') == 'se_calculated') {

                      var se_calculated = $('#se_calculated').val();
                      if(!se_calculated){ // Set Value if deleted
                        $('#se_calculated').val(0.00);
                      }

                   
                      if($('#is_gst_registered').val() == 1){ // IF GST IS YES 
                            var countGst = (parseFloat($('.gst-percentage').val()) + 100) / 100;
                            var setup_exp_gst_excl = parseFloat(se_calculated) / countGst;
                            var se_gst_paid  = parseFloat(se_calculated) - parseFloat(setup_exp_gst_excl);
                      } else {
                            var setup_exp_gst_excl = 0.00;
                            var se_gst_paid  = 0.00;

                      }
                      if(isNaN(se_gst_paid)) {
                        se_gst_paid = 0.00;
                      }
                      
                      if(isNaN(setup_exp_gst_excl)) {
                        setup_exp_gst_excl = 0.00;
                      }

                      $('#se_gst_excl').val((setup_exp_gst_excl).toFixed(2));
                      $('#se_gst_paid').val((se_gst_paid).toFixed(2)); 

                    }



                    var se_calculated = $('#se_calculated').val();
                    var se_est_capital = $('#se_est_capital').val();
                    if(!se_est_capital) { // Set Value if deleted
                      $('#se_est_capital').val(0.00)
                    }
                    var se_repayment_monthly_period_capital_req = 
                            $('#se_repayment_monthly_period_capital_req').val();
                    var se_interest_rate_fr_loan = $('#se_interest_rate_fr_loan').val();
                    
                    //  function call calculation 
                    setAdditionalCapReq($('#se_add_capital_req') , se_calculated , se_est_capital );

                    // setIntereset
                    var se_add_capital_req = $('#se_add_capital_req').val();
                    setIntereset($('#se_interest') , se_add_capital_req , se_interest_rate_fr_loan , se_repayment_monthly_period_capital_req);

                    // setTotal
                    var se_interest = $('#se_interest').val();
                    setTotal($('#se_total') , se_add_capital_req , se_interest);

                    // setMonthlyRepayment
                    var se_total = $('#se_total').val();
                    setMonthlyRepayment($('#se_monthly_repayment') ,se_total , se_repayment_monthly_period_capital_req);

                    var se_monthly_repayment = $('#se_monthly_repayment').val();
                    setLoanRepayment( $('#be_loan_repayment_amt') , se_monthly_repayment , se_repayment_monthly_period_capital_req )
                });

                // Set Additional Capital Required 
                function setAdditionalCapReq( attr , se_calculated , se_est_capital )
                {
                    if(se_calculated >= 0 && se_est_capital >= 0) {
                      var finalVal = parseFloat(se_calculated) - parseFloat(se_est_capital);
                      attr.val((finalVal).toFixed(2));
                    }
                }

                // Set Set Intereset
                function setIntereset( attr ,  se_add_capital_req , se_interest_rate_fr_loan , se_repayment_monthly_period_capital_req)
                {
                    if(se_add_capital_req >= 0 && se_interest_rate_fr_loan >= 0 && se_repayment_monthly_period_capital_req > 0)
                    {
                      var finalVal = ((parseFloat(se_add_capital_req) / 100) * parseFloat(se_interest_rate_fr_loan) / 12) * se_repayment_monthly_period_capital_req;
                      attr.val((finalVal).toFixed(2));
                    }

                }


                // Set Total
                function setTotal( attr , se_add_capital_req , se_interest )
                {
                    if(se_add_capital_req >= 0 && se_interest >= 0){
                      var finalVal = parseFloat(se_add_capital_req) + parseFloat(se_interest);
                      attr.val((finalVal).toFixed(2));
                    }
                }

                // Set Monthly Repayment
                function setMonthlyRepayment( attr , se_total , se_repayment_monthly_period_capital_req )
                {
                    if(se_total > 0 && se_repayment_monthly_period_capital_req > 0){
                      var finalVal = parseFloat(se_total) / parseFloat(se_repayment_monthly_period_capital_req);
                    } else {
                      var finalVal = 0;
                    }
                    attr.val((finalVal).toFixed(2));
                    // $('#be_loan_repayment_amt').val((finalVal).toFixed(2));
                }

                //  TO calculate Loan Repayment 
                function setLoanRepayment( attr , se_monthly_repayment , se_repayment_monthly_period_capital_req )
                {
                    if(se_monthly_repayment >= 0 && se_repayment_monthly_period_capital_req >= 0){
                      if(se_repayment_monthly_period_capital_req >= 12){
                        se_repayment_monthly_period_capital_req = 12;
                      }
                      var finalVal = parseFloat(se_monthly_repayment) * parseFloat(se_repayment_monthly_period_capital_req);
                    } else {
                      var finalVal = 0;
                    }
                    attr.val((finalVal).toFixed(2));
                    // $('#be_loan_repayment_amt').val((finalVal).toFixed(2));
                }

            /********** End : Setup Expenses *****************/     

            /********** Start : Business Expenses *****************/
                $(document).on('change blur' , '#be_calculated , #be_profit_req_after_tax , #be_loan_repayment_amt , #be_tax_payable' ,function(){
                    
                    var currentVal = $(this).val();
                    var currentId = $(this).attr('id');
                    $('#'+currentId+'').val(parseFloat(currentVal).toFixed(2));

                    if($(this).attr('id') == 'be_calculated') { // Manually Changes Estimated for business expense

                      var be_calculated = $('#be_calculated').val();

                        if($('#is_gst_registered').val() == 1){ // IF GST IS YES 

                          var countGst = (parseFloat($('.gst-percentage').val()) + 100) / 100;
                          var business_exp_gst_excl = parseFloat(be_calculated) / countGst;
                          var be_gst_paid  = parseFloat(be_calculated) - parseFloat(business_exp_gst_excl);
                        } else {
                            var business_exp_gst_excl = 0.00;
                            var be_gst_paid = 0.00;
                        }

                        $('#be_gst_excl').val((business_exp_gst_excl).toFixed(2));
                        $('#be_gst_paid').val((be_gst_paid).toFixed(2)); 

                    }

                    var be_calculated = $('#be_calculated').val();
                    var be_loan_repayment_amt = $('#be_loan_repayment_amt').val();
                    var be_profit_req_after_tax = $('#be_profit_req_after_tax').val();


                    // setTotalExpPerMnth
                    setTotalExpPerMnth($('#be_total_exp_per_mnth') , be_calculated , be_loan_repayment_amt);
                    var be_total_exp_per_mnth =  $('#be_total_exp_per_mnth').val();

                    // setTaxPayable
                    countSlab(be_profit_req_after_tax , 'step-3');

                    var be_tax_payable = $('#be_tax_payable').val();

                    // setProfitAfterTax
                    setProfitAfterTax($('#be_profit_after_tax') , be_profit_req_after_tax , be_tax_payable);
                    

                    setTotalExpPerAnnum( $('#be_total_exp_per_annum') , be_calculated , be_loan_repayment_amt , be_profit_req_after_tax);

                    var be_total_exp_per_annum = $('#be_total_exp_per_annum').val();

                    var be_gst_paid = $('#be_gst_paid').val();
                    setGstPaidPerAnnum( $('#be_gst_paid_per_annum') , be_gst_paid);

                }); 

                function setTotalExpPerMnth( attr ,  be_calculated , be_loan_repayment_amt) {

                    if(be_calculated >= 0 && be_loan_repayment_amt >= 0)
                    {
                        var finalVal = parseFloat(be_calculated) + parseFloat(be_loan_repayment_amt);
                        attr.val(finalVal.toFixed(2));
                    }
                } 

               
                function setProfitAfterTax( attr , be_profit_req_after_tax , be_tax_payable){
                    if(be_profit_req_after_tax >= 0 && be_tax_payable >= 0)
                    {
                        var finalVal = parseFloat(be_profit_req_after_tax) - parseFloat(be_tax_payable);
                        attr.val(finalVal.toFixed(2));
                    }
                }

                function setTotalExpPerAnnum( attr , be_calculated , be_loan_repayment_amt , be_profit_req_after_tax){
                    if( be_calculated >= 0 && be_profit_req_after_tax >= 0)
                    {
                        var finalVal = (parseFloat(be_calculated) * 12) + parseFloat(be_loan_repayment_amt) + parseFloat(be_profit_req_after_tax);
                        attr.val(finalVal.toFixed(2));
                    }
                }

                function setGstPaidPerAnnum( attr , be_gst_paid){
                    if( be_gst_paid >= 0)
                    {
                        var finalVal = (parseFloat(be_gst_paid) * 12);
                        attr.val(finalVal.toFixed(2));
                    }
                }

                function countSlab(amount, step_name) {
                    var tax_category = $('.business_type :selected').val();
                    var amount = amount;
                    $.ajax({
                        type: 'get',
                        url: get_slab_url,
                        data: {
                          tax_category : tax_category,
                          amount : amount
                        },
                        success: function (res) {
                            //console.log(amount);

                            setTaxPayable(res, step_name);
                        }
                    });
                }

                function setTaxPayable( res, step_name ){
                    if(step_name == 'step-3'){
                        $('#be_tax_payable').val(res.tax_amount);
                        
                        var be_profit_req_after_tax = $('#be_profit_req_after_tax').val();
                        var be_tax_payable = $('#be_tax_payable').val();
                        setProfitAfterTax($('#be_profit_after_tax') , be_profit_req_after_tax , be_tax_payable);
                    }

                    if(step_name == 'step-5'){
                        // if($('#is_gst_registered').val() == 1){
                        //     $('#projected_gst_owing').val(res.tax_amount);
                        // }else{
                        //     $('#projected_gst_owing').val(0.00);
                        // }
                        monthlyExpenses();
                        monthlyProfitAndLoss();
                        $('#total').trigger('blur');
                    }

                    if(step_name == 'gst-to-pay'){
                        $('#business_taxes').val(res.tax_amount);
                        var gst_to_pay = $('#gst_to_pay').val();
                        var business_taxes =  $('#business_taxes').val();
                        var fs_total_prof_befr_tax = $('#fs_total_prof_befr_tax').val();
                        var fs_profit_after_tax = parseFloat(fs_total_prof_befr_tax) - parseFloat(business_taxes);
                        $('#fs_profit_after_tax').val((fs_profit_after_tax).toFixed(2));
                    }

                    // if(step_name == 'partner-financial-summary')
                    // {
                    //     var fsp_business_tax = res.tax_amount
                    //     callback(fsp_business_tax);
                    //     // rendorFsForPartner(fsp_business_tax);
                    // }
                }

                $(document).on('change','#le_calculated',function(){
                    var tax_category = $('.business_type :selected').val();
                    var amount = $('#le_calculated').val();
                    $.ajax({
                        type: 'get',
                        url: get_slab_url,
                        data: {
                          tax_category : tax_category,
                          amount : amount
                        },
                        success: function (res) {
                            $('#le_gst_paid').val(res.tax_amount);
                            var le_gst_paid = $('#le_gst_paid').val();
                            var le_gst_excl  = parseFloat(amount) - parseFloat(le_gst_paid);
                            $('#le_gst_excl').val((le_gst_excl).toFixed(2));
                            autoPopulateDrawing(amount);
                        }
                    });
                });

            /********** End : Business Expenses *****************/   

            /********** Start : Partnership *****************/   

                $(document).on('change','#no_of_partner',function(){
                    
                    var db_no_of_partner = {!! !empty($financeData->no_of_partner) ? $financeData->no_of_partner : 0 !!};
                    var no_of_partner = $('#no_of_partner').val(); 


                    // Remove boxes Dynamically 
                    $('.equity_ratio:not(:eq(0))').remove();
                    $('.equity_label:not(:eq(0))').remove();
                    $('.invested_amount:not(:eq(0))').remove();
                    $('.invested_label:not(:eq(0))').remove();
                    $('.gst_excl:not(:eq(0))').remove();
                    $('.gst_excl_label:not(:eq(0))').remove();
                    $('.gst_paid:not(:eq(0))').remove();
                    $('.gst_paid_label:not(:eq(0))').remove();
                    // End : Remove boxes Dynamically 

                    var equity_ratio_count = $('input[name*="equity_ratio[]"]').length;
                    var invested_amount_count = $('input[name*="invested_amount[]"]').length;
                    var gst_excl_count = $('input[name*="gst_excl[]"]').length;
                    var gst_paid_count = $('input[name*="gst_paid[]"]').length;

                    // Values 
                    var percentage_values = {!! json_encode(@$ftPartnershipData['profit_percentage']) !!};
                    var invested_amount_values = {!! json_encode(@$ftPartnershipData['invested_amount']) !!};
                    var excl_gst_values = {!! json_encode(@$ftPartnershipData['excl_gst']) !!};
                    var gst_paid_values = {!! json_encode(@$ftPartnershipData['gst_paid']) !!};
                   
                    addDynamicEqaulityRatio(no_of_partner , equity_ratio_count , percentage_values);
                    addDynamicFields(no_of_partner , db_no_of_partner , invested_amount_count , invested_amount_values , excl_gst_values , gst_paid_values);

                    // addDynamicGstExcl(no_of_partner , gst_excl_count , excl_gst_values);
                    // addDynamicGstPaid(no_of_partner , gst_paid_count , gst_paid_values);
                });

                 // function to add percentage input
                function addDynamicEqaulityRatio(no_of_partner , equity_ratio_count , values)
                {
                    if(no_of_partner > equity_ratio_count)
                    {
                        for(var i= 0; $('.equity_ratio').size() < no_of_partner; i++)
                        {
                           var id =  $('.equity_ratio').size();
                            if((id != no_of_partner)) {
                                var inputFieldKey = id;
                                var key = id + 1;
                                if(values.length >= no_of_partner)
                                {
                                    var value = values[i + 1];
                                }else{
                                    var value = 0.00;
                                }
                                $('.equity_ratio_div').append('<div class="form-group">' + 
                                '<label class="strong equity_label"> EQUITY RATIO (PARTNER '+key+')' + ':  </label>' +
                                '<div>' +
                                '<input class="form-control equity_ratio" name="equity_ratio[]" ' +
                                'data-rule-number="true"' +
                                'data-rule-required="true" id="equity_ratio_'+inputFieldKey+'"' + 
                                'value="'+parseFloat(value).toFixed(2)+'"' + 'placeholder="$11,200.00" type="text" data-rule-custom="true">' + 
                                '</div>' +
                              '</div>');
                            }
                        }

                        if(values.length < 0 )
                        {

                            $('.equity_ratio').each(function(){
                                $(this).val(0.00);
                            });
                            $('.equity_ratio:last').val(100);
                        }
                    }
                    $('.equity_ratio').trigger('change');
                }

                function addDynamicFields(no_of_partner , db_no_of_partner , invested_amount_count , values , excl_gst_values , gst_paid_values)
                {
                    if(no_of_partner > invested_amount_count)
                    {
                        for(var i= 0; $('.invested_amount').size() < no_of_partner; i++)
                        {
                            var id =  $('.invested_amount').size();
                            if((id != no_of_partner)) {
                                var inputFieldKey = id;
                                var key = id + 1;
                                var equity_ratio = $('.equity_ratio').last().val();
                                if((values.length >= no_of_partner) && (equity_ratio > 0))
                                {
                                    var value = values[i + 1];
                                    var value1 = excl_gst_values[i + 1];
                                    var value2 = gst_paid_values[i + 1];
                                }else{
                                    var value = 0;
                                    var value1 = 0;
                                    var value2 = 0;
                                }
                                var estimateField = '<div class="form-group">' + 
                                        '<label class="strong invested_label"> PARTNER '+key+' :' +
                                        '</label><div>' +
                                        '<input class="form-control invested_amount" name="invested_amount[]"' +
                                            'data-rule-number="true" data-rule-required="true"' + 
                                            'id="invested_amount_'+inputFieldKey+'"' +
                                            'value="'+value+'"' + 
                                            'placeholder="$11,200.00" type="text" readonly="true"></div></div>';

                                var gstExclField = '<div class="form-group">' +
                                '<label class="strong gst_excl_label"> INCOME TAX EXCLUSIVE (PARTNER '+key+'):' + '</label>' + 
                                '<div>'+
                                    '<input class="form-control gst_excl" data-rule-number="true" data-rule-required="true"' +
                                       'name="gst_excl[]" id="gst_excl_'+inputFieldKey+'"' +
                                       'value="'+value1+'"' +
                                       'placeholder="$11,200.00" type="text" readonly="true">' + 
                                '</div></div>';

                                var gstPaidField = '<div class="form-group">' +
                                '<label class="strong gst_paid_label"> INCOME TAX PAID (PARTNER '+key+'):'+ 
                                '</label>' + 
                                '<div>'+
                                    '<input class="form-control gst_paid" data-rule-number="true" data-rule-required="true"' +
                                       'name="gst_paid[]" id="gst_paid_'+inputFieldKey+'"' +
                                       'value="'+value2+'"' +
                                       'placeholder="$11,200.00" type="text" readonly="true">' + 
                                '</div></div>';

                                $('.gst_paid_div').append(estimateField + gstExclField + gstPaidField);
                            }
                        }
                    }         
                }

                // function to add estiamte partnership
                // function addDynamicEstimate(no_of_partner , invested_amount_count , values )
                // {
                //     if(no_of_partner > invested_amount_count)
                //     {
                //         for(var i= 0; $('.invested_amount').size() < no_of_partner; i++)
                //         {
                //             var id =  $('.invested_amount').size();
                //             if((id != no_of_partner)) {
                //                 var inputFieldKey = id;
                //                 var key = id + 1;
                //                 if(values.length >= no_of_partner)
                //                 {
                //                     var value = values[i + 1];
                //                 }else{
                //                     var value = 0;
                //                 }
                //                var estimateField = '<div class="form-group">' + 
                //                         '<label class="strong invested_label"> Partner '+key+' :' +
                //                         '</label><div>' +
                //                         '<input class="form-control invested_amount" name="invested_amount[]"' +
                //                             'data-rule-number="true" data-rule-required="true"' + 
                //                             'id="invested_amount_'+inputFieldKey+'"' +
                //                             'value="'+value+'"' + 
                //                             'placeholder="$11,200.00" type="text" readonly="true"></div></div>';

                //                 $('.invested_amount_div').append(estimateField);
                //             }
                //         }
                //     }         
                // }

                // function to add gst exclusive
                // function addDynamicGstExcl(no_of_partner , gst_excl_count , values)
                // {
                //     if(no_of_partner > gst_excl_count)
                //     {
                //         for(var i= 0; $('.gst_excl').size() < no_of_partner; i++)
                //         {
                //             var id =  $('.gst_excl').size();
                //             if((id != no_of_partner)) {

                //                var inputFieldKey = id;
                //                var key = id + 1;
                //                 if(values.length >= no_of_partner)
                //                 {
                //                     var value = values[i + 1];
                //                 }else{
                //                     var value = 0;
                //                 }
                //                var gstExclField = '<div class="form-group">' +
                //             '<label class="strong gst_excl_label"> Income Tax Exclusive (Partner '+key+'):' + '</label>' + 
                //             '<div>'+
                //                 '<input class="form-control gst_excl" data-rule-number="true" data-rule-required="true"' +
                //                    'name="gst_excl[]" id="gst_excl_'+inputFieldKey+'"' +
                //                    'value="'+value+'"' +
                //                    'placeholder="$11,200.00" type="text" readonly="true">' + 
                //             '</div></div>';

                //                 $('.gst_excl_div').append(gstExclField);

                //             }
                //         }
                //     }         
                // }

                // function to add gst Paid
                // function addDynamicGstPaid(no_of_partner , gst_paid_count , values)
                // {
                //     if(no_of_partner > gst_paid_count)
                //     {
                //         for(var i= 0; $('.gst_paid').size() < no_of_partner; i++)
                //         {
                //             var id =  $('.gst_paid').size();
                //             if((id != no_of_partner)) {

                //                var inputFieldKey = id;
                //                var key = id + 1;
                //                if(values.length >= no_of_partner)
                //                 {
                //                     var value = values[i + 1];
                //                 }else{
                //                     var value = 0;
                //                 }
                //                var gstExclField = '<div class="form-group">' +
                //             '<label class="strong gst_paid_label"> Income Tax Paid (Partner '+key+'):'+ 
                //             '</label>' + 
                //             '<div>'+
                //                 '<input class="form-control gst_paid" data-rule-number="true" data-rule-required="true"' +
                //                    'name="gst_paid[]" id="gst_paid_'+inputFieldKey+'"' +
                //                    'value="'+value+'"' +
                //                    'placeholder="$11,200.00" type="text" readonly="true">' + 
                //             '</div></div>';

                //                 $('.gst_paid_div').append(gstExclField);

                //             }
                //         }
                //     }         
                // }
                // Calculate Percentage Dynamically
                $(document).on('change','.equity_ratio',function(){
                     var currentVal = $(this).val();
                    var currentId = $(this).attr('id');
                    $('#'+currentId+'').val(parseFloat(currentVal).toFixed(2));


                    var serA = $('.equity_ratio').serializeArray();
                    var lastIndex = serA.length - 1;
                    var percentage = 0.00;
                    for(var i in serA)
                    {
                        if(i != lastIndex)
                        {
                            if(serA[i].value > 100){
                                $('#equity_ratio_'+i+'').val(0.00);
                                // Add Validtion for max 100
                            }
                            percentage += parseFloat(serA[i].value);
                        }
                        if(i == lastIndex)
                        {
                            if(percentage < 100) {
                                var lastPercentage = 100 - percentage;
                               $('.equity_ratio').last().val(parseFloat(lastPercentage).toFixed(2));
                            } else {
                               $('.equity_ratio').last().val(parseFloat(0).toFixed(2));
                            }
                        }
                    }
                    // IF TOTAL PERCENTAGE > 100
                    if(percentage > 100)
                    {
                         jQuery.validator.addMethod("data-rule-custom", function ( element ) {
                              return false;
                          }, "Equity Ratio can not be more than 100");
                    } else {
                        jQuery.validator.addMethod("data-rule-custom", function ( element ) {
                              return true;
                          }, "Equity Ratio shouldn't max than 100");
                    }
                });
            /********** End : Partnership *****************/   

            /********** Start : Session Required *****************/   
                $(document).on('change blur' , '#session_rate , #ann_working_weeks , #no_of_clients , #avg_sess_pweek_pclient' ,function(){
                        console.log($(this).attr('id'));

                          var currentVal = $(this).val();
                    var currentId = $(this).attr('id');
                    $('#'+currentId+'').val(parseFloat(currentVal).toFixed(2));

                    if($(this).attr('id') == 'no_of_clients')
                    {
                        $('label.clients_req_6_per_session_label').text('Client Required @ '+$(this).val()+' Per Session:');
                    }
                    var session_rate  = $('#session_rate').val();
                    var ann_working_weeks  = $('#ann_working_weeks').val();
                    var no_of_clients  = $('#no_of_clients').val();
                    var avg_sess_pweek_pclient  = $('#avg_sess_pweek_pclient').val();

                    //var partnership_total = $('#partnership_total').val();
                    var be_total_exp_per_annum = $('#be_total_exp_per_annum').val();

                    console.log('partnership_total: '+partnership_total);
                    console.log('be_total_exp_per_annum: '+be_total_exp_per_annum);

                    setSessionReq( $('#session_req') , be_total_exp_per_annum , session_rate);

                    var session_req = $('#session_req').val();
                    setWeeklyReq($('#weekly_sess_req') , session_req , ann_working_weeks);

                    var weekly_sess_req = $('#weekly_sess_req').val();
                    setSessionSpotReq($('#session_spots_req') , weekly_sess_req , no_of_clients);

                    var session_spots_req = $('#session_spots_req').val();
                    setClientReqToBrkEven($('#clients_req_to_break_even') , session_spots_req, avg_sess_pweek_pclient);

                    var be_total_exp_per_annum = $('#be_total_exp_per_annum').val();
                    var clients_req_to_break_even = $('#clients_req_to_break_even').val();
                    setAvgSpend( $('#average_client_spend') , be_total_exp_per_annum , clients_req_to_break_even );

                    // ?maunally trigger blue for lead require section
                    $('#client_active_current').trigger('blur');
                });

                function setSessionReq( attr ,  partnership_total , session_rate)
                {
                    if(partnership_total > 0 && session_rate > 0)
                    {
                        var finalVal = partnership_total / session_rate;
                    } else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }

                function setWeeklyReq( attr , session_req , ann_working_weeks)
                {
                    if(session_req > 0 && ann_working_weeks > 0)
                    {
                        var finalVal = parseFloat(session_req) / parseFloat(ann_working_weeks);
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }

                function setSessionSpotReq( attr , weekly_sess_req , no_of_clients)
                {
                    if(weekly_sess_req > 0 && no_of_clients > 0)
                    {
                        var finalVal = parseFloat(weekly_sess_req) * parseFloat(no_of_clients);
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }    

                function setClientReqToBrkEven( attr , session_spots_req , avg_sess_pweek_pclient)
                {
                    if(session_spots_req > 0 && avg_sess_pweek_pclient > 0)
                    {
                        var finalVal = parseFloat(session_spots_req) / parseFloat(avg_sess_pweek_pclient);
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }

                function setAvgSpend( attr , be_total_exp_per_annum , clients_req_to_break_even )
                {
                    if(be_total_exp_per_annum > 0 && clients_req_to_break_even > 0)
                    {
                        var finalVal = parseFloat(be_total_exp_per_annum) / parseFloat(clients_req_to_break_even);
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }
            /********** End : Session Required *****************/ 

            /********** Start : Client Required *****************/  
                $(document).on('change blur','#client_active_current , #timeframe_capicity , #projected_cons_conv_rate , #contact_conv_rate ,#weekly_sess_req,#clients_req_to_break_even' , function(){
                     var currentVal = $(this).val();
                    var currentId = $(this).attr('id');
                    $('#'+currentId+'').val(parseFloat(currentVal).toFixed(2));

                    var client_active_current = $('#client_active_current').val();
                    var timeframe_capicity = $('#timeframe_capicity').val();
                    var projected_cons_conv_rate = $('#projected_cons_conv_rate').val();
                    var contact_conv_rate = $('#contact_conv_rate').val();

                    $('#session_req_per_week').val($('#weekly_sess_req').val());

                    var clients_req_to_break_even = $('#clients_req_to_break_even').val();
                    setClienReqPerSess($('#clients_req_6_per_session') , clients_req_to_break_even , client_active_current);

                   var clients_req_6_per_session = $('#clients_req_6_per_session').val();

                   setConsReq($('#consultations_req') ,clients_req_6_per_session , projected_cons_conv_rate);
                   var consultations_req = $('#consultations_req').val();

                   setLeadReq($('#leads_req') , consultations_req, contact_conv_rate);
                   var leads_req = $('#leads_req').val();

                   setAvgLeadGeneration($('#avg_lead_gen_req_per_week_fr_52') , leads_req , timeframe_capicity);

                   var avg_lead_gen_req_per_week_fr_52 = $('#avg_lead_gen_req_per_week_fr_52').val();

                   setAvgConsultWeek($('#avg_consult_week') ,consultations_req ,timeframe_capicity);

                    var avg_consult_week = $('#avg_consult_week').val();

                    setAvgSigned($('#avg_singed_client_week') , clients_req_6_per_session,timeframe_capicity );
                });

                function setClienReqPerSess(attr , clients_req_to_break_even , client_active_current)
                {
                    if(clients_req_to_break_even  && client_active_current )
                    {
                        var finalVal = clients_req_to_break_even - client_active_current;
                        if(!isFinite(finalVal))
                        {
                            var finalVal = 0;
                        }
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }

                function setConsReq(attr , clients_req_6_per_session , projected_cons_conv_rate)
                {
                    if(clients_req_6_per_session  && projected_cons_conv_rate )
                    {
                        var finalVal = clients_req_6_per_session / projected_cons_conv_rate * 100;
                        if(!isFinite(finalVal))
                        {
                            var finalVal = 0;
                        }
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }

                function setLeadReq(attr , consultations_req, contact_conv_rate)
                {
                    if(consultations_req  && contact_conv_rate )
                    {
                        var finalVal = (100 * consultations_req) / contact_conv_rate;
                        if(!isFinite(finalVal))
                        {
                            var finalVal = 0;
                        }
                    }else {
                        var finalVal = 0;
                    }
                    attr.val((finalVal).toFixed(2));
                }

                function setAvgLeadGeneration(attr , leads_req , timeframe_capicity)
                {
                    if(leads_req  && timeframe_capicity )
                    {
                        var finalVal = leads_req / timeframe_capicity;
                        if(!isFinite(finalVal))
                        {
                            var finalVal = 0;
                        }
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }

                function setAvgConsultWeek(attr , consultations_req ,timeframe_capicity)
                {
                    if(consultations_req  && timeframe_capicity )
                    {
                        var finalVal = consultations_req / timeframe_capicity;
                        if(!isFinite(finalVal))
                        {
                            var finalVal = 0;
                        }
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }

                function setAvgSigned(attr , clients_req_6_per_session,timeframe_capicity)
                {
                    if(clients_req_6_per_session  && timeframe_capicity )
                    {
                        var finalVal = clients_req_6_per_session / timeframe_capicity;
                        if(!isFinite(finalVal))
                        {
                            var finalVal = 0;
                        }
                    }else {
                        var finalVal = 0;
                    }
                        attr.val((finalVal).toFixed(2));
                }
            /********** End : Client Required *****************/  

            /********** Start : PROJECTED INCOME  *****************/  
                $(document).on('change blur' , '#projected_client_per_mnth, #sum_mnth1, #sum_mnth2, #sum_mnth3, #sum_mnth4, #sum_mnth5, #sum_mnth6, #sum_mnth7, #sum_mnth8, #sum_mnth9, #sum_mnth10,#sum_mnth11,#sum_mnth12' , function(){

                      var currentVal = $(this).val();
                    var currentId = $(this).attr('id');
                    $('#'+currentId+'').val(parseFloat(currentVal).toFixed(2));


                        var projected_client_per_mnth = $('#projected_client_per_mnth').val();
                        var projected_avg_client_spend = $('#projected_avg_client_spend').val();
                        var client_active_current = $('#client_active_current').val();
                        var manualSumMnth1 = parseFloat(projected_client_per_mnth) + 
                                                parseFloat(client_active_current);
                        $('#sum_mnth1').val((manualSumMnth1).toFixed(2));
                        var sum_mnth1 = $('#sum_mnth1').val();

                        // Set Into month 1 manually
                        var month1 = parseFloat(sum_mnth1) * parseFloat(projected_avg_client_spend);
                        $('#into_mnth1').val((month1).toFixed(2));

                        // Fetch  ESTIMATED CLIENT CAPACITY
                        var estimated_client_cap = $('#estimated_client_cap').val(); 

                        // Loop for other months
                        var projected_income_total = parseFloat((month1).toFixed(2));

                        for (var i = 2 ; i < 13 ; i++)
                        {
                            var last = i - 1;
                            
                            var lastMnth = $('#sum_mnth'+last+'').val(); 
                            var finalVal =  parseFloat(lastMnth) + parseFloat(projected_client_per_mnth);
                            if(finalVal > estimated_client_cap)
                            {
                                var projected_client_per_mnth = estimated_client_cap + 1; // manually to made projected clienr per month greater
                                var finalVal = parseFloat(estimated_client_cap);
                            }
                            $('#sum_mnth'+i+'').val((finalVal).toFixed(2));

                            console.log('Sum Month '+i+'  : '+finalVal);
                            
                            var SumMnth = $('#sum_mnth'+i+'').val()
                            var finalVal =  parseFloat(SumMnth) * parseFloat(projected_avg_client_spend);
                            $('#into_mnth'+i+'').val((finalVal).toFixed(2));

                            projected_income_total += parseFloat($('#into_mnth'+i+'').val());

                            console.log('Into Month '+i+' : '+parseFloat($('#into_mnth'+i+'').val()));
                        }
                        
                        $('#projected_income_total').val((projected_income_total).toFixed(2));
                       
                        // Check for Gst Recevied amount if gst is yes
                        var projected_income_total_aft_gst = 0;
                        if($('#is_gst_registered').val() == 1)
                        {
                            var countGst = (parseFloat($('.gst-percentage').val()) + 100) / 100;
                            var projected_gst_received = parseFloat(projected_income_total) / countGst;
                            projected_income_total_aft_gst += parseFloat(projected_income_total) - parseFloat(projected_gst_received);
                        } else if($('#is_gst_registered').val() == 0){
                            var projected_gst_received = 0;
                            projected_gst_received += projected_income_total;
                        }
                        $('#projected_gst_received').val((projected_income_total_aft_gst).toFixed(2));

                        $('#projected_income_total_aft_gst').val((projected_gst_received).toFixed(2));

                        var projected_income_total = $('#projected_income_total').val();

                        countSlab(projected_income_total, 'step-5');
                });
        

            /********** End : PROJECTED INCOME  *****************/  

            /********** Start :  MONTHLY EXPENSES   *****************/

                function monthlyExpenses(){
                    var mnthly_exp_total = 0.00;
                    var se_repayment_monthly_period_capital_req = 
                        $('#se_repayment_monthly_period_capital_req').val();
                    var loop = parseFloat(se_repayment_monthly_period_capital_req) + 1;

                    // Fetch  ESTIMATED CLIENT CAPACITY
                    var estimated_client_cap = $('#estimated_client_cap').val(); 

                    for (var i = 1 ; i < 13 ; i++)
                    {
                        // Must needed value allocation ** do not remove 
                        var be_calculated = $('#be_calculated').val();
                        var be_loan_repayment_amt = $('#be_loan_repayment_amt').val();
                        if(loop <= i){
                            var be_loan_repayment_amt = 0.00;
                        }
                        $('#be_mnth'+i+'').val(parseFloat(be_calculated).toFixed(2));
                        $('#loan_repay_mnth'+i+'').val(parseFloat(be_loan_repayment_amt).toFixed(2)); 
                        var be_mnth = $('#be_mnth'+i+'').val()
                        var loan_repay_mnth = $('#loan_repay_mnth'+i+'').val()
                        var finalVal =  parseFloat(be_mnth) + parseFloat(loan_repay_mnth);
                        $('#exp_mnth'+i+'').val((finalVal).toFixed(2));

                        mnthly_exp_total += parseFloat($('#exp_mnth'+i+'').val());
                    }
                    $('#mnthly_exp_total').val((mnthly_exp_total).toFixed(2));
                     // Check for Gst Recevied amount if gst is yes
                    var mnthly_total_aft_gst = 0.00;
                    if($('#is_gst_registered').val() == 1)
                    {
                        var countGst = (parseFloat($('.gst-percentage').val()) + 100) / 100;
                        var mnthly_gst_received = parseFloat(mnthly_exp_total) / countGst;
                        mnthly_total_aft_gst += parseFloat(mnthly_exp_total) - parseFloat(mnthly_gst_received);
                    }
                    else {
                        var mnthly_gst_received = 0.00;
                        // mnthly_total_aft_gst += mnthly_exp_total;
                        mnthly_gst_received += mnthly_exp_total;
                    }

                    $('#mnthly_gst_received').val((mnthly_total_aft_gst).toFixed(2));
                    $('#mnthly_total_aft_gst').val((mnthly_gst_received).toFixed(2));

                    var se_repayment_monthly_period_capital_req = 0.00;
                }

            /********** End :  MONTHLY EXPENSES   *****************/  

            /********** Start : MONTHLY PROFIT AND LOSS  *****************/

                function monthlyProfitAndLoss(){
                    var prof_loss_total = 0.00;
                    for (var i = 1 ; i < 13 ; i++)
                    {
                        var sum_mnth = $('#into_mnth'+i+'').val()
                        var exp_mnth = $('#exp_mnth'+i+'').val()

                        var finalVal =  parseFloat(sum_mnth) - parseFloat(exp_mnth);
                        $('#prof_loss_mnth'+i+'').val((finalVal).toFixed(2));

                        prof_loss_total += parseFloat($('#prof_loss_mnth'+i+'').val());
                    }

                    $('#prof_loss_total').val((prof_loss_total).toFixed(2));
                }


            /********** End : MONTHLY PROFIT AND LOSS  *****************/ 

            /********** Start : GST TO PAY SECTIOn  *****************/  
                $(document).on('change blur' ,'#total,#gst_to_pay' ,function(){
                    
                    var business_type = $('.business_type :selected').val();

                    if(business_type == "sole-trader")
                    {
                        $('label.business_taxes_label').text('INCOME TAX');
                    }

                    var projected_income_total = $('#projected_income_total').val();
                    var mnthly_exp_total = $('#mnthly_exp_total').val();

                    var projected_income_total_aft_gst = $('#projected_income_total_aft_gst').val();
                    var mnthly_total_aft_gst = $('#mnthly_total_aft_gst').val();

                    var mnthly_gst_received = $('#mnthly_gst_received').val();
                    var projected_gst_received = $('#projected_gst_received').val();
                    var business_taxes = $('#business_taxes').val();


                    // allocation for : FS ANNUAL PROFIT REUIQRED FIELD
                    var fs_annual_prof_req = $('#be_profit_req_after_tax').val(); 
                    $('#fs_annual_prof_req').val(fs_annual_prof_req);

                    var total = parseFloat(projected_income_total_aft_gst) - parseFloat(mnthly_total_aft_gst);
                    var gst_to_pay =  parseFloat(projected_gst_received)  - parseFloat(mnthly_gst_received);
                    
                    if($('#is_gst_registered').val() != 1)
                    {
                        var gst_to_pay = 0.00;
                    }

                    $('#total').val((total).toFixed(2));
                    $('#gst_to_pay').val((gst_to_pay).toFixed(2));

                    // allocation for : FS PROFIT BEFORE TAX FIELD
                    var total = $('#total').val();
                    var gst_to_pay = $('#gst_to_pay').val();
                    var fs_profit_before_tax = parseFloat(total) - parseFloat(gst_to_pay); 
                    $('#profit_befr_tax').val((fs_profit_before_tax).toFixed(2));


                    // allocation for : FS TOTAL PROFIT BEFORE TAX FIELD
                    var fs_total_prof_befr_tax = parseFloat(fs_annual_prof_req) + parseFloat(fs_profit_before_tax);
                    $('#fs_total_prof_befr_tax').val((fs_total_prof_befr_tax).toFixed(2));


                    if(business_type == 'partnership')
                    {
                        $('.if-partner').hide();
                        $('.partner-header').show();
                        $('.fsp-partner-data').remove();
                        rendorFsForPartner();
                    }else{
                        $('.fsp-partner-data').remove();
                        $('.partner-header').hide();
                        $('.if-partner').show();
                    }

                    if(business_type != 'partnership')
                    {
                        countSlab(fs_total_prof_befr_tax ,'gst-to-pay');
                    }
   
                });

                function rendorFsForPartner()
                {
                    var no_of_partner = $('#no_of_partner').val();
                    var fs_total_prof_befr_tax = $('#fs_total_prof_befr_tax').val();
                    console.log(fs_total_prof_befr_tax , 'first row - PROFIT AFTER TAX');
                    var loopTill = no_of_partner - 1;
                    for(var i = 0;i<=loopTill;i++)
                    {
                        var equity_ratio = $('#equity_ratio_'+i+'').val();
                        var partner_profit_before_tax = (parseFloat(fs_total_prof_befr_tax) * equity_ratio) / 100; 
                        var tax_category = $('.business_type :selected').val();
                        var amount = partner_profit_before_tax;


                        console.log(equity_ratio  ,  'equity_ratio');
                        console.log(partner_profit_before_tax  ,  'partner_profit_before_tax');

                        $.ajax({
                            type: 'get',
                            url: get_slab_url,
                            data: {
                              tax_category : tax_category,
                              amount : amount
                            },
                            async: false,
                            success: function (res) {
                                // callbackRendorFsForPartner(res , i , equity_ratio , partner_profit_before_tax);
                               var fsp_pro_after_tax =  parseFloat(partner_profit_before_tax) - res.tax_amount;

                               console.log(fsp_pro_after_tax , 'fsp_pro_after_tax');
                               var partnerNO = i + 1;
                               var html = '<tr class="fsp-partner-data">' +
                                    '<td>  </td>' +
                                    '<td> PARTNER '+partnerNO+' (EQUITY RATIO: '+equity_ratio+'%)</td>' +
                                    '<td><div class="form-group"><input class="form-control" data-rule-number="true"' +
                                    'data-rule-required="true" id="fsp_total_prof_befr_tax_'+i+'" type="text"' +
                                    'readonly="true" value="'+(partner_profit_before_tax).toFixed(2)+'"></div>' + '</td>' +
                                    '<td><div class="form-group"><input class="form-control" data-rule-number="true"' +
                                    'data-rule-required="true" id="fsp_business_tax_'+i+'" type="text"' +
                                    'readonly="true" value="'+res.tax_amount+'"></div>' + '</td>' +
                                    '<td><div class="form-group"><input class="form-control" data-rule-number="true"' +
                                    'data-rule-required="true" id="fsp_business_tax_'+i+'" type="text"' +
                                    'readonly="true" value="'+(fsp_pro_after_tax).toFixed(2)+'"></div>' + '</td>' +
                                    '</tr>';
                                $(html).insertAfter('#financialSummaryTable tr:last');
                            }
                        });


                    }
                }
            /********** End : GST TO PAY SECTIOn  *****************/

        });

     </script>

    @if(!empty($financeData->no_of_partner) && @$financeData->business_type == 'partnership')
        <script>
            $(document).ready(function(){

               // var tax_category = $('.business_type :selected').val();
               //  if(tax_category == 'partnership') // 
               //  { 
               //      $('#no_of_partner').trigger('change');
               //  }
            });
        </script>
    @endif

@stop()
@section('script-handler-for-this-page')
 FormWizard.init();
 <!-- UINestable.init(); -->
@stop()