@extends('masters.app')
@section('page-title')
<span >Epic process</span>
@stop
@section('required-styles')
{!! Html::style('plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('plugins/bootstrap-datepicker/css/datepicker.css') !!}
{!! Html::style('plugins/nestable-cliptwo/jquery.nestable.css') !!}


{!! Html::style('plugins/sweetalert/sweet-alert.css') !!}

{!! Html::style('plugins/Jcrop/css/jquery.Jcrop.min.css') !!}


{!! Html::style('css/custom.css?v='.time()) !!}

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
                            <span class="stepDesc"><small>Expenses</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-2">
                            <div class="stepNumber">
                                2
                            </div>
                            <span class="stepDesc"><small>Income</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-3">
                            <div class="stepNumber">
                                3
                            </div>
                            <span class="stepDesc"><small>Cashflow</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-4">
                            <div class="stepNumber">
                                4
                            </div>
                            <span class="stepDesc"><small>Lead Generation</small></span>
                        </a>
                    </li>
                </ul>
                <!-- start: WIZARD STEP 1 -->
                 <div id="step-1">
                    @include('financialtool.financialtool-step1')
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
                        @include('financialtool.financialtool-step2')
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
                    @include('financialtool.financialtool-step3')
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
                        @include('financialtool.financialtool-step4')
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
                        
                
                <!--<div class="clear-widget"></div>-->
            </div>
        </form>
    </div>
    <!-- end: PANEL BODY -->

    <!-- Required Modals -->
        @include('financialtool.setup-expenses.expense-modal')
        @include('financialtool.business-expenses.expense-modal')
        @include('financialtool.personal-living-expenses.expense-modal')
    <!-- End : Required Modals -->
</div>
@stop
@section('required-script')

     @if(!empty($timeframe))
        <script>
            var setting_pref_timeframe = '{!! $timeframe->financial_time_frame !!}';
        </script>
    @endif

    {!! Html::script('js/jquery-ui.min.js') !!}
    {!! Html::script('js/form-wizard-financial-modal.js?v='.time()) !!}

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
    <!-- end: Bootstrap Typeahead --> 
    <!-- start: Bootstrap Typeahead -->
        {!! Html::script('plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}  
     {!! Html::script('plugins/jquery-validation/jquery.validate.min.js') !!}

      {!! Html::script('plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}

     {!! Html::script('js/helper.js?v='.time()) !!}
     {!! Html::script('plugins/sweetalert/sweet-alert.min.js') !!}


     
    {!! Html::script('js/form-wizard-financial.js?v='.time()) !!}


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


   <script type="text/javascript">
        var get_slab_url = "{{ route('get.slab') }}"
        var recordId = "{!! auth()->user()->business_id !!}";

             // checkboxes like radio
            // $(document).on('click','.is_company',function(){
            //     $('.is_company').prop('checked', false);
            //     $(this).prop('checked', true);
            // });

            // $(document).on('click','.is_sole_trader',function(){
            //     $('.is_sole_trader').prop('checked', false);
            //     $(this).prop('checked', true);
            // });
           
            $(document).on('click','.is_gst_registered',function(){
                $('.is_gst_registered').prop('checked', false);
                $(this).prop('checked', true);
            });
             // end : checkboxes like radio

            // dynamic field on checkbox
            $(window).load(function(){
                var tax_type = "{!! @$financeData->tax_type !!}";
                if(tax_type.length == 0) {
                    $('#is_sole_trader').attr('checked','true');
                }

                var is_gst_registered = "{!! @$financeData->is_gst_registered !!}";
                var gst_no = "{!! @$settingPrefData->tax_code !!}";
                var gst_percetage = "{!! @$settingPrefData->tax_amount !!}";
                console.log(is_gst_registered);
                if(is_gst_registered == 1) {
                    $('.gst-no').val(gst_no);
                    $('.gst-percentage').val(gst_percetage);
                }else{
                    $('.gst-no').val(0);
                    $('.gst-percentage').val(0);
                }
            });

            $(document).on('click','#checkbox1',function(){
                var gst_no = "{!! @$settingPrefData->tax_code !!}";
                var gst_percetage = "{!! @$settingPrefData->tax_amount !!}";
                $(this).val(1);
                $('.gst-no').val(gst_no);
                $('.gst-percentage').val(gst_percetage);
                // Set Gst Incl Field
                var setup_exp_gst_incl = localStorage.getItem('setup_exp_gst_incl');
                var business_exp_gst_incl = localStorage.getItem('business_exp_gst_incl');

                if(setup_exp_gst_incl > 0) {

                $('#setup_exp_gst_incl').val(parseFloat(setup_exp_gst_incl) + (parseFloat(setup_exp_gst_incl) / 100) * $('.gst-percentage').val());
                }

                if(business_exp_gst_incl > 0) {

                $('#business_exp_gst_incl').val(parseFloat(business_exp_gst_incl) + (parseFloat(business_exp_gst_incl) / 100) * $('.gst-percentage').val());    
                }

                // Set GST TAX field for INCOME TAB
                setGstTax();
                // End : Set GST TAX field for INCOME TAB
                
                // Set GST TAX label
                var tax_amount = "{{ !empty($settingPrefData->tax_amount) ? $settingPrefData->tax_amount.'%' : 0 }}";
                $('.gst-label').text('GST( '+tax_amount+' )');


            });

            
            $(document).on('click','#checkbox2',function(){
                $(this).val(0);
                $('.gst-no').val(0);
                $('.gst-percentage').val(0);
              var setup_exp_gst_incl = localStorage.getItem('setup_exp_gst_incl');
              var business_exp_gst_incl = localStorage.getItem('business_exp_gst_incl');
              $('#setup_exp_gst_incl').val(parseFloat(setup_exp_gst_incl) + (parseFloat(setup_exp_gst_incl) / 100) * $('.gst-percentage').val());
              $('#business_exp_gst_incl').val(parseFloat(business_exp_gst_incl) + (parseFloat(business_exp_gst_incl) / 100) * $('.gst-percentage').val());

              setGstTax();

              // Set GST TAX label
              $('.gst-label').text('GST( 0% )');

            });

            // Function : Set GST TAX field for INCOME TAB
            function setGstTax() {
                
                var setup_exp_gst_incl = 0;

                $('.setup_fill_data').each(function(){
                   if(!$(this).val()){
                      $(this).val() = 0;
                   } 
                    if($(this).attr('gst-incl') == 'true'){
                      setup_exp_gst_incl += parseFloat($(this).val());
                    }
                });

                var business_exp_gst_incl = 0;
                $('.business_fill_data').each(function(){
                   if(!$(this).val()){
                      $(this).val() = 0;
                   }
                    if($(this).attr('gst-incl') == 'true'){
                     business_exp_gst_incl += parseFloat($(this).val());
                    }
                });

                var setup_exp_gst_incl_tax = 
                    (parseFloat(setup_exp_gst_incl) / 100) * $('.gst-percentage').val();
                var business_exp_gst_incl_tax = 
                    (parseFloat(business_exp_gst_incl) / 100) * $('.gst-percentage').val()

              localStorage.setItem('setup_exp_gst_incl_tax',setup_exp_gst_incl_tax);
              localStorage.setItem('business_exp_gst_incl_tax',business_exp_gst_incl_tax);

                var provisional_tax_gst = parseFloat(setup_exp_gst_incl_tax) + parseFloat(business_exp_gst_incl_tax);
                $('#gst').val(provisional_tax_gst.toFixed(2));

            }


            /*********** If modal total changed Manully *********/

             $(document).on('change','#setup_exp_est , #business_exp_est',function(){
                if($(this).attr('id') == 'setup_exp_est') {
                    var key = 'setup_exp_gst_incl_tax';
                    var setId = '#setup_exp_gst_incl';
                } else if ($(this).attr('id') == 'business_exp_est') {
                    var key = 'business_exp_gst_incl_tax';
                    var setId = '#business_exp_gst_incl';
                }

                var total = $(this).val();
                var totalGst = 0;
                if(total != null && total != 0) {
                    if(key != null) {

                  localStorage.setItem(key,(parseFloat(total) / 100) * $('.gst-percentage').val());
                    }
                  totalGst = parseFloat(total) + (parseFloat(total) / 100) * $('.gst-percentage').val();
                }
                $(setId).val(totalGst);
            });

            /*********** END : If modal total changed Manully *********/



            /***** Income calucaltion : 2 tab *****/
            var tax_type = "{!! @$financeData->tax_type !!}";

            $(document).on('change','#profit_req',function(){
                var profit_req = $(this).val();

                var provisional_tax_gst = (parseFloat(profit_req) / 100) * 15;
                var company_tax_gst = (parseFloat(profit_req) / 100) * 30;
                if(tax_type == 'sole-trader') {
                    var personal_tax = (parseFloat(profit_req) / 100) * 19.5;
                }else {
                    var personal_tax = (parseFloat(profit_req) / 100) * 0;
                }
                $('#gst').val(provisional_tax_gst.toFixed(2));
                // $('#company_tax').val(company_tax_gst.toFixed(2));
                // $('#personal_tax').val(personal_tax.toFixed(2));
            });

            $(document).on('change','#turnover_and_taxes',function(){
                var turnover_and_taxes = $(this).val();
                var eqv_full_sess_cor = $('#eqv_full_sess_cor').val();
                if(eqv_full_sess_cor > 0) {
                    sessionReqTotalIncomeCal($('#eqv_full_sess_req_for_tot_inc') ,turnover_and_taxes , eqv_full_sess_cor);

                }
            });

            $(document).on('blur','#eqv_full_sess_cor',function(){
                var turnover_and_taxes = $('#turnover_and_taxes').val();
                var eqv_full_sess_cor = $('#eqv_full_sess_cor').val();
                if(eqv_full_sess_cor > 0) {
                    sessionReqTotalIncomeCal($('#eqv_full_sess_req_for_tot_inc') ,turnover_and_taxes , eqv_full_sess_cor);
                    $("#eqv_full_sess_req_for_tot_inc").trigger("blur");
                }
            });

            $(document).on('blur','#clients_req_eqv_full_sess_cor',function(){
                var turnover_and_taxes = $('#turnover_and_taxes').val();
                var client_req_eqv_full_sess_cor = $(this).val();
                if(client_req_eqv_full_sess_cor > 0) {
                    sessionReqTotalIncomeCal($('#clients_req_eqv_full_sess_req_for_tot_inc') ,turnover_and_taxes , client_req_eqv_full_sess_cor);
                    $("#clients_req_eqv_full_sess_req_for_tot_inc").trigger("blur");

                }
            });

            function sessionReqTotalIncomeCal( attr , total_turnover , session_charge ) {
                var finalVal = parseFloat(total_turnover) / parseFloat(session_charge);
                attr.val(Math.floor(finalVal));
            }

            $(document).on('blur','#ann_working_weeks , #eqv_full_sess_req_for_tot_inc',function(){
                var ann_working_weeks = $('#ann_working_weeks').val();
                var eqv_full_sess_req_for_tot_inc = $('#eqv_full_sess_req_for_tot_inc').val();
                if(eqv_full_sess_req_for_tot_inc > 0 && ann_working_weeks > 0) {
                    sessionReqWeeklyCal($('#weekly_sess_req') ,eqv_full_sess_req_for_tot_inc , ann_working_weeks);
                    $("#weekly_sess_req").trigger("blur");
                    $("#no_of_clients").trigger("blur");
                }
            });

            $(document).on('blur','#clients_req_ann_working_weeks , #clients_req_eqv_full_sess_req_for_tot_inc',function(){

                var clients_req_ann_working_weeks = $('#clients_req_ann_working_weeks').val();

                var clients_req_eqv_full_sess_req_for_tot_inc =
                    $('#clients_req_eqv_full_sess_req_for_tot_inc').val();

                if(clients_req_eqv_full_sess_req_for_tot_inc > 0 &&
                    clients_req_ann_working_weeks > 0) {
                    sessionReqWeeklyCal($('#clients_req_weekly_sess_req') ,clients_req_eqv_full_sess_req_for_tot_inc , clients_req_ann_working_weeks);
                    $("#clients_req_no_of_clients").trigger("blur");
                }
            });


            function sessionReqWeeklyCal( attr , val1 , val2 ) {
                var finalVal = parseFloat(val1) / parseFloat(val2);
                attr.val(Math.floor(finalVal));
            }


            $(document).on('blur','#weekly_sess_req',function(){
                var weekly_sess_req = $(this).val();
                var no_of_clients = $('#no_of_clients').val();
                if(no_of_clients > 0 && weekly_sess_req > 0) {
                    averageSessionPerWeekPerClient($('#avg_sess_pweek_pclient') ,weekly_sess_req , no_of_clients );
                    $("#avg_sess_pweek_pclient").trigger("blur");

                }
            });

            $(document).on('blur','#no_of_clients',function(){
                var no_of_clients = $(this).val();
                var weekly_sess_req = $('#weekly_sess_req').val();
                if(weekly_sess_req > 0 && no_of_clients > 0) {
                    sessionSpotsRequired($('#session_spots_req') ,weekly_sess_req , no_of_clients);
                    $("#session_spots_req").trigger("blur");
                    $("#avg_sess_pweek_pclient").trigger("blur");

                }
            });

            $(document).on('blur','#clients_req_no_of_clients',function(){
                var clients_req_no_of_clients = $(this).val();
                var clients_req_weekly_sess_req =
                $('#clients_req_weekly_sess_req').val();
                if(clients_req_weekly_sess_req > 0 && clients_req_no_of_clients > 0) {
                    sessionSpotsRequired($('#client_session_spots_req') ,clients_req_weekly_sess_req , clients_req_no_of_clients);
                    $("#client_session_spots_req").trigger("blur");
                    $("#clients_req_avg_sess_pweek_pclient").trigger("blur");
                }
            });

            $(document).on('blur','#session_spots_req , #avg_sess_pweek_pclient',function(){
                var session_spots_req = $('#session_spots_req').val();
                var avg_sess_pweek_pclient = $('#avg_sess_pweek_pclient').val();
                if(session_spots_req > 0 && avg_sess_pweek_pclient > 0) {
                    clientsRequiredToBrkEven($('#clients_req_to_break_even') ,session_spots_req , avg_sess_pweek_pclient);
                    $("#clients_req_to_break_even").trigger("blur");
                }
            });

            $(document).on('blur','#client_session_spots_req , #clients_req_avg_sess_pweek_pclient',function(){
                var session_spots_req = $('#client_session_spots_req').val();
                var avg_sess_pweek_pclient = $('#clients_req_avg_sess_pweek_pclient').val();
                if(session_spots_req > 0 && avg_sess_pweek_pclient > 0) {
                    clientsRequiredToBrkEven($('#client_clients_req_to_break_even') ,session_spots_req , avg_sess_pweek_pclient);
                    $("#client_clients_req_to_break_even").trigger("blur");
                }
            });

            $(document).on('blur','#avg_sess_pweek_pclient',function()
            {
              var eqv_full_sess_cor = $('#eqv_full_sess_cor').val();
              var no_of_clients = $('#no_of_clients').val();
              var avg_sess_pweek_pclient = $('#avg_sess_pweek_pclient').val();
              var ann_working_weeks = $('#ann_working_weeks').val();
              countAvgClientSpend($('#average_client_spend') ,eqv_full_sess_cor ,
                no_of_clients , avg_sess_pweek_pclient , ann_working_weeks)
                $("#average_client_spend").trigger("blur");

            });

            $(document).on('blur','#clients_req_avg_sess_pweek_pclient',function()
            {
              var eqv_full_sess_cor = $('#clients_req_eqv_full_sess_cor').val();
              var no_of_clients = $('#clients_req_no_of_clients').val();
              var avg_sess_pweek_pclient = $('#clients_req_avg_sess_pweek_pclient').val();
              var ann_working_weeks = $('#clients_req_ann_working_weeks').val();
              countAvgClientSpend($('#client_average_client_spend') ,eqv_full_sess_cor , no_of_clients , avg_sess_pweek_pclient , ann_working_weeks)
                $("#client_average_client_spend").trigger("blur");


            });


            function averageSessionPerWeekPerClient( attr , val1 , val2 ) {
                var finalVal = parseFloat(val1) / parseFloat(val2);
                attr.val(Math.floor(finalVal));
            }

            function sessionSpotsRequired( attr , val1 , val2 ) {
                var finalVal = parseFloat(val1) * parseFloat(val2);
                attr.val(Math.floor(finalVal));
            }

            function clientsRequiredToBrkEven( attr , val1 , val2 ) {
                var finalVal = parseFloat(val1) / parseFloat(val2);
                attr.val(finalVal.toFixed(2));
            }

            function countAvgClientSpend( attr , rate , no_of_clients , avg_client , annual_wrk_week ) {
                var finalVal = (parseFloat(rate) / parseFloat(no_of_clients)) *
                            parseFloat(avg_client) * parseFloat(annual_wrk_week);
                attr.val(Math.floor(finalVal));
            }

      

            /**************************  Count Gst Slab on Income ************/
            $(document).on('change','#living_exp_est',function(){
                var tax_category = $('input[name="tax_type"]:checked').val();
                var amount = $(this).val();
                $.ajax({
                    type: 'get',
                    url: get_slab_url,
                    data: {
                      tax_category : tax_category,
                      amount : amount
                    },
                    success: function (res) {
                        $('#living_exp_gst_incl').val((res.amount).toFixed(2));
                        if(tax_category == 'company') {
                            $('#company_tax').val((res.tax_amount).toFixed(2));
                            $('#partnership_tax').val(0);
                            $('#personal_tax').val(0);
                        } else if (tax_category == 'sole-trader') {
                            $('#personal_tax').val((res.tax_amount).toFixed(2));
                            $('#partnership_tax').val(0);
                            $('#company_tax').val(0);

                        } else {
                            $('#partnership_tax').val((res.tax_amount).toFixed(2));
                            $('#company_tax').val(0);
                            $('#personal_tax').val(0);

                        }
                    }
                });
            });
            
            $(document).on('change','input[name="tax_type"]',function(){
                $('#living_exp_est').trigger('change');
            });


            /***** Cashflow calucaltion : 3 tab *****/
            $(document).on('blur','#cashflow_forecast_time',function(){
                var total_monthly_expense = $('#total_monthly_expense').val();
                var cashflow_forecast_time = $('#cashflow_forecast_time').val();
                var setup_loan_repayment_per_month_amt = $('#setup_loan_repayment_per_month_amt').val();
                var cap_req_first_three_months =
                    (parseFloat(total_monthly_expense) * cashflow_forecast_time) + (parseFloat(setup_loan_repayment_per_month_amt) * cashflow_forecast_time) ;
                $('#cap_req_first_three_months').val(cap_req_first_three_months.toFixed(2));
                $('#presonal_capital_available').trigger('blur');
            });

            $(document).on('blur','#setup_loan_repayment_period_month_time',function(){
                var setup_loan_repayment_period_month_time = $(this).val();
                var cashflow_setup_expense = $('#cashflow_setup_expense').val();
                var cal = parseFloat(cashflow_setup_expense) / parseFloat(setup_loan_repayment_period_month_time);
                $('#setup_loan_repayment_per_month_amt').val(cal.toFixed(2));
                $('#presonal_capital_available').trigger('blur');
                $('#cashflow_forecast_time').trigger('blur');
            });

            $(document).on('blur','#presonal_capital_available',function(){
                var presonal_capital_available = $(this).val();
                var cap_req_first_three_months = $('#cap_req_first_three_months').val();
                var loan_req = parseFloat(cap_req_first_three_months) + parseFloat(presonal_capital_available);
                $('#loan_req').val(loan_req.toFixed(2));
                $('#loan_req').trigger('blur');
            });

            $(document).on('blur','#loan_req',function(){
                var loan_req = $(this).val();
                var monthly_req = parseFloat(loan_req) / 3;
                $('#monthly_req').val(monthly_req.toFixed(2));
            });

            /************* Calculation  step 4 : lead generation *********/

            $(document).on('blur','#lead_gen_timeframe_capicity',function(){
                var lead_gen_timeframe_capicity = $(this).val();
                var total_client_req = $('#total_client_req').val();
                if(lead_gen_timeframe_capicity > 0 && total_client_req > 0 ) {
                    finalVal =  total_client_req / lead_gen_timeframe_capicity;
                    $('#lead_gen_avg_singed_client_week').val(finalVal.toFixed(2));
                }
                $('#projected_conv_rate').trigger('blur');
                $('#lead_gen_contact_conv_rate').trigger('blur');
            });

            $(document).on('blur','#consultations_req',function(){
                $('#lead_gen_contact_conv_rate').trigger('blur');
            });

            $(document).on('blur','#projected_conv_rate',function(){
                var projected_conv_rate = $('#projected_conv_rate').val();
                var total_client_req = $('#total_client_req').val();
                if(total_client_req > 0 && projected_conv_rate > 0){
                    setConsulantReq($('#consultations_req') , total_client_req , projected_conv_rate
                        );
                    $('#lead_gen_contact_conv_rate').trigger('blur');
                }
            });

            $(document).on('blur','#lead_gen_contact_conv_rate',function(){
                var lead_gen_contact_conv_rate = $('#lead_gen_contact_conv_rate').val();
                var consultations_req = $('#consultations_req').val();
                if(consultations_req > 0 && lead_gen_contact_conv_rate > 0){
                setLeadReq($('#leads_req') , consultations_req , lead_gen_contact_conv_rate);
                }
            });

            function setConsulantReq(attr , total_client_req , projected_conv_rate){
                var finalVal = (parseFloat(total_client_req) / parseFloat(projected_conv_rate)) * 100;
                attr.val(finalVal.toFixed(2));
                var lead_gen_avg_consult_week = parseFloat(finalVal) / ($('#lead_gen_timeframe_capicity').val());
                $('#lead_gen_avg_consult_week').val(lead_gen_avg_consult_week);
            }

            function setLeadReq(attr , consultations_req , lead_gen_contact_conv_rate){
                var finalVal = (parseFloat(consultations_req) / parseFloat(lead_gen_contact_conv_rate)) * 100;
                attr.val(finalVal.toFixed(2));
                var avg_lead_gen_req_per_week_twlv = parseFloat(finalVal) / ($('#lead_gen_timeframe_capicity').val());
                $('#avg_lead_gen_req_per_week_twlv').val(avg_lead_gen_req_per_week_twlv);

            }

   </script>



@stop()
@section('script-handler-for-this-page')
 FormWizard.init();
 <!-- UINestable.init(); -->
@stop()