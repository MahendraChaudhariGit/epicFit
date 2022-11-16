@extends('Result.masters.app')

@section('page-title')
<span >Add Meal Category</span> 
@stop
@section('required-styles')
{!! Html::style('assets/plugins/tooltipster-master/tooltipster.css') !!}

{!! Html::style('assets/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('assets/plugins/sweetalert/sweet-alert.css') !!} 

{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

{!! Html::style('result/css/custom.css?v='.time()) !!}
<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}

@stop
@section('content')

<div id="panel_edit_account" class="tab-pane active">
    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
     <div>

    <div class="row swMain">
                @include('mealplanner.meal-category.form')       
    </div>
    </div>
    </div>

@endsection

@section('script')
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
{!! Html::script('assets/plugins/tooltipster-master/jquery.tooltipster.min.js' !!}


{!! Html::script('assets/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('assets/plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('assets/js/meal-planner.js?v='.time()) !!}
{!! Html::script('assets/plugins/sweetalert/sweet-alert.min.js') !!}
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}

{!! Html::script('result/js/helper.js?v='.time()) !!}

@stop