
@extends('Result.masters.app')

@section('page-title')
<span >Calculators</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}

{!! Html::style('result/css/custom.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')
<div class="row pos-of-acc pos-of-accc">
	<!-- Start: Invoice Section -->
	<div class="col-md-12 col-sm-12">
		<div class="panel panel-white no-radius load1 panel-white-me" id="visits">
			<div class="panel-heading panel-heading-me border-light">
				<h4 class="panel-title"> Calculators </h4>
			</div>
			<div class="panel-wrapper">
				<div class="panel-body">
					<div class="minh-350">
						<div class="panel-group no-margin">
							<div class="panel no-radius">
								<div class="">
									<div class="panel-body no-padding table-top-border">
										<table class="table">
											<thead>
												<tr role="row">
													<th>Name</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
                                                <tr>
                                                    <td>Body Mass Index</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/body-mass-index') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Basal Metabolism Rate</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/basal-metabolism-rate') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Target Heart Rate</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/target-heart-rate') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Ideal Weight</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/ideal-weight') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Calorie Breakdown</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/calorie-breakdown') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Resting Metabolism</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/resting-metabolism') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Advanced Resting Metabolism</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/advanced-resting-metabolism') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Daily Metabolism</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/daily-metabolism') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Body Fat Navy</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/body-fat-navy') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Body Fat YMCA</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/body-fat-ymca') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Lean Body Mass</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/lean-body-mass') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Waist Hip Ratio</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/waist-hip-ratio') }}">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Full Body Analysis</td>
                                                    <td><a class="nav-link" href="{{ url('calculators/full-body-analysis') }}">View</a></td>
                                                </tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End: Invoice Section -->
</div>
{{-- <div class="panel panel-white panel-white-new">
    <div class="container calculator_result">
        <ul class="">
            <li><a href="{{ url('calculators/body-mass-index') }}">Body Mass Index</a></li>
            <li><a href="{{ url('calculators/basal-metabolism-rate') }}">Basal Metabolism Rate</a></li>
            <li><a href="{{ url('calculators/target-heart-rate') }}">Target Heart Rate</a></li>
            <li><a href="{{ url('calculators/ideal-weight') }}">Ideal Weight</a></li>
            <li><a href="{{ url('calculators/calorie-breakdown') }}">Calorie Breakdown</a></li>
            <li><a href="{{ url('calculators/resting-metabolism') }}">Resting Metabolism</a></li>
            <li><a href="{{ url('calculators/advanced-resting-metabolism') }}">Advanced Resting Metabolism</a></li>
            <li><a href="{{ url('calculators/daily-metabolism') }}">Daily Metabolism</a></li>
            <li><a href="{{ url('calculators/body-fat-navy') }}">Body Fat Navy</a></li>
            <li><a href="{{ url('calculators/body-fat-ymca') }}">Body Fat YMCA</a></li>
            <li><a href="{{ url('calculators/lean-body-mass') }}">Lean Body Mass</a></li>
            <li><a href="{{ url('calculators/waist-hip-ratio') }}">Waist Hip Ratio</a></li>
            <li><a href="{{ url('calculators/full-body-analysis') }}">Full Body Analysis</a></li>
        </ul>
    </div>
</div> --}}
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




{{-- {!! Html::script('result/js/form-wizard-clients.js?v='.time()) !!} --}}
{{-- {!! Html::script('result/js/form-wizard-benchmark.js?v='.time()) !!} --}}
{{-- {!! Html::script('result/js/benchmark.js?v='.time()) !!} --}}
{{-- {!! Html::script('result/js/helper.js?v='.time()) !!} --}}
<!--{!! Html::script('js/events.js') !!}-->
{{-- {!! Html::script('result/js/bench.js?v='.time()) !!} --}}
{{-- {!! Html::script('result/js/clients.js?v='.time()) !!} --}}




<script>
    var loggedInUser = {
        type: '{{ Auth::user()->account_type }}',
        id: '{{ Auth::user()->account_id }}',
        name: '{{ Auth::user()->name }}'
    },
            popoverContainer = $('#container');
</script>

@stop