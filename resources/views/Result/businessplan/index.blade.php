
@extends('masters.app')

<!--Start: Page title/ heading name --> 
@section('page-title')
    Business Plan
@stop
<!--Start: Page title/ heading name -->


@section('required-styles')
    <!-- VpForm -->
    {!! Html::style('vendor/vp-form/css/vp-form.css?v='.time()) !!}
@stop

@section('header-scripts')
    {!! Html::script('js/helper.js?v='.time()) !!}

    <!-- start: VpForm -->
    {!! Html::script('vendor/vp-form/js/jquery.windows.js') !!}
    {!! Html::script('vendor/vp-form/js/angular.js') !!}
    {!! Html::script('vendor/vp-form/js/autogrow.js') !!}
    {!! Html::script('vendor/vp-form/js/vf-business-plan.js') !!}
    <!-- end: VpForm -->

@stop

<!--Start: Main body of business plan --> 
@section('content')
 @include('businessplan.form')
@stop
<!--End: Main body of business plan -->

<!--Start: Script --> 
@section('required-script-for-this-page')


@stop

@section('required-script')
    <!-- {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('js/set-moment-timezone.js') !!} -->

    {!! Html::script('plugins/jquery-validation/jquery.validate.min.js') !!}
    {!! Html::script('plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}

    {!! Html::script('plugins/jquery-ui/jquery-ui.min.js') !!}

    <!-- start: Bootstrap datepicker -->
    <!--{!! Html::script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}-->
    <!-- end: Bootstrap datepicker -->

    <!-- start: Bootstrap timepicker -->
    <!--{!! Html::script('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}-->
    <!-- end: Bootstrap timepicker -->

    <!-- Start:  NEW timepicker js -->
    {!! Html::script('plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!}
    <!-- End: NEW timepicker js -->

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::script('plugins/bootstrap-daterangepicker/daterangepicker.js') !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: Bootstrap Select Master -->
    {!! Html::script('plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Country Code Selector -->
    {!! Html::script('plugins/intl-tel-input-master/build/js/utils.js') !!}
    {!! Html::script('plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('plugins/bootstrap3-typeahead.min.js') !!}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: JCrop -->
    {!! Html::script('plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('plugins/Jcrop/js/script.js') !!}
    <!-- end: JCrop -->
    {!! Html::script('js/form-wizard-businessplan.js?v='.time()) !!}


    <!-- start: CK EDITOR -->
    {!! Html::script('plugins/ckeditor/ckeditor.js') !!}
    {!! Html::script('plugins/ckeditor/adapters/jquery.js') !!}
{{--        {!! Html::script('js/ckeditor.js?v='.time()) !!}--}}
    <!-- end: CK EDITOR -->
@stop

<style>
    .type-popover {
        cursor: pointer;
    }
</style>

@section('script-handler-for-this-page')
<script>
    $('.type-popover').click(function() {
        var content = $(this).attr('data-content');
        console.log(content);

        $('#tooltipModel').find('.modal-body').html(content);

        $('#tooltipModel').modal('show');
    });

	FormWizardBusinessPlan.init('#bunisesspalnWizard');

	var e1 = CKEDITOR.replace('company', {
        height: 120,
        enterMode: CKEDITOR.ENTER_BR
    });

    CKEDITOR.replace('services_products', {
        height: 120
    });

    CKEDITOR.replace('market_analysis', {
        height: 120
    });

    CKEDITOR.replace('business_stratergy', {
        height: 120
    });

    CKEDITOR.replace('management', {
        height: 120
    });

    CKEDITOR.replace('financial_plan', {
        height: 120
    });

    CKEDITOR.replace('company_ownership_location', {
        height: 120
    });
    CKEDITOR.replace('management_structure', {
        height: 120
    });

    // start: STEP 3
    CKEDITOR.replace('description', {
        height: 120
    });
    CKEDITOR.replace('features_benefits', {
        height: 120
    });
    CKEDITOR.replace('competitors', {
        height: 120
    });
    CKEDITOR.replace('competitive_advantage', {
        height: 120
    });
    CKEDITOR.replace('future_expansion', {
        height: 120
    });
    // end: STEP 3


    // start: STEP 4
    CKEDITOR.replace('niche_market', {
        height: 120
    });
    CKEDITOR.replace('market_size', {
        height: 120
    });
    CKEDITOR.replace('current_trends', {
        height: 120
    });
    CKEDITOR.replace('swot_analysis', {
        height: 120
    });
    // end: STEP 4

    // start: STEP 5
    CKEDITOR.replace('business_philosophy', {
        height: 120
    });
    CKEDITOR.replace('web_presence', {
        height: 120
    });
    CKEDITOR.replace('marketing_strategy', {
        height: 120
    });
    CKEDITOR.replace('sales_strategy', {
        height: 120
    });
    CKEDITOR.replace('strategic_alliances', {
        height: 120
    });
    CKEDITOR.replace('company_objectives_and_vision', {
        height: 120
    });
    CKEDITOR.replace('exit_strategy', {
        height: 120
    });
    // end: STEP 5


    // start: STEP 6
    CKEDITOR.replace('startup_req_and_alloc_capital', {
        height: 120
    });
    CKEDITOR.replace('cash_flow_proj_and_bal_sheets', {
        height: 120
    });
    CKEDITOR.replace('assumptions', {
        height: 120
    });
    // end: STEP 6

    var timer = setInterval(updateTextArea, 1000);
    function updateTextArea(){
        $('#company').html(CKEDITOR.instances.company.getData());
        $('#services_products').html(CKEDITOR.instances.services_products.getData());
        $('#market_analysis').html(CKEDITOR.instances.market_analysis.getData());
        $('#business_stratergy').html(CKEDITOR.instances.business_stratergy.getData());
        $('#management').html(CKEDITOR.instances.management.getData());
        $('#financial_plan').html(CKEDITOR.instances.financial_plan.getData());
        $('#company_ownership_location').html(CKEDITOR.instances.company_ownership_location.getData());
        $('#management_structure').html(CKEDITOR.instances.management_structure.getData());

        // start: STEP 3
        $('#description').html(CKEDITOR.instances.description.getData());
        $('#features_benefits').html(CKEDITOR.instances.features_benefits.getData());
        $('#competitors').html(CKEDITOR.instances.competitors.getData());
        $('#competitive_advantage').html(CKEDITOR.instances.competitive_advantage.getData());
        $('#future_expansion').html(CKEDITOR.instances.future_expansion.getData());
        // end: STEP 3

        // start: STEP 4
        $('#niche_market').html(CKEDITOR.instances.niche_market.getData());
        $('#market_size').html(CKEDITOR.instances.market_size.getData());
        $('#current_trends').html(CKEDITOR.instances.current_trends.getData());
        $('#swot_analysis').html(CKEDITOR.instances.swot_analysis.getData());
        // end: STEP 4

        // start: STEP 5
        $('#business_philosophy').html(CKEDITOR.instances.business_philosophy.getData());
        $('#web_presence').html(CKEDITOR.instances.web_presence.getData());
        $('#marketing_strategy').html(CKEDITOR.instances.marketing_strategy.getData());
        $('#sales_strategy').html(CKEDITOR.instances.sales_strategy.getData());
        $('#strategic_alliances').html(CKEDITOR.instances.strategic_alliances.getData());
        $('#company_objectives_and_vision').html(CKEDITOR.instances.company_objectives_and_vision.getData());
        $('#exit_strategy').html(CKEDITOR.instances.exit_strategy.getData());
        // end: STEP 5

        // start: STEP 6
        $('#startup_req_and_alloc_capital').html(CKEDITOR.instances.startup_req_and_alloc_capital.getData());
        $('#cash_flow_proj_and_bal_sheets').html(CKEDITOR.instances.cash_flow_proj_and_bal_sheets.getData());
        $('#assumptions').html(CKEDITOR.instances.assumptions.getData());
        // end: STEP 6
    }

</Script>
    {{--TextEditor.init();--}}
@stop
<!--End: Script --> 