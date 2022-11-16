
@extends('Result.masters.app')

@section('page-title')
<span >Meal Planner</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}

{!! Html::style('result/css/custom.css') !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')

<div class="panel panel-white panel-white-new">
    <div class="container calculator_result">
        <ul class="">
            <li><a href="{{ url('meal-planner/meals') }}">Add Meal</a></li> 
            <li><a href="{{ url('meal-planner/meals') }}">Add Food</a></li> 
            <li><a href="{{ url('meal-planner/meals') }}">Meal Category</a></li> 
            <li><a href="{{ url('meal-planner/meals') }}">Shopping Category</a></li> 
            <li><a href="{{ url('meal-planner/meals') }}">Shopping List</a></li> 
            <li><a href="{{ url('meal-planner/meals') }}">Serving Size</a></li> 
            <li><a href="{{ url('meal-planner/meals') }}">Meal Logs</a></li>            
        </ul>
    </div>
</div>
@endsection

@section('required-script')
{!! Html::script('result/js/jquery-ui.min.js') !!}

<!-- start: Moment Library -->
{!! Html::script('result/plugins/moment/moment.min.js') !!}
<!-- end: Moment Library -->

<!-- start: Summernote -->
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
<!-- end: Summernote -->
<!-- start: Rating -->
{!! Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
<!-- end: Rating -->
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap timepicker -->

{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
<!-- end: Bootstrap timepicker -->
{!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}


{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
<!-- start: image upload js -->
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
<!-- start: image upload js -->




{!! Html::script('result/js/form-wizard-clients.js') !!}
{!! Html::script('result/js/form-wizard-benchmark.js') !!}
{!! Html::script('result/js/benchmark.js') !!}
{!! Html::script('result/js/helper.js') !!}
<!--{!! Html::script('js/events.js') !!}-->
{!! Html::script('result/js/bench.js') !!}
{!! Html::script('result/js/clients.js') !!}




<script>
    var loggedInUser = {
        type: '{{ Auth::user()->account_type }}',
        id: '{{ Auth::user()->account_id }}',
        name: '{{ Auth::user()->name }}'
    },
            popoverContainer = $('#container');
</script>

@stop