@extends('Result.masters.app')

@section('page-title')
<span data-realtime="firstName">{{ Auth::user()->name }}</span> <span data-realtime="lastName">{{ Auth::user()->last_name }}</span>
@stop

@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
{!! Html::style('result/css/custom.css') !!}
{!! Html::style('result/css/bootstrap-timepicker.min.css') !!}


@stop

@section('content')

<!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->
<style type="text/css">
	a.disabledform {
  /* Make the disabled links grayish*/
  color: gray;
  /* And disable the pointer events */
  pointer-events: none;
}
#spaceless{
	display: none;
}
@media(min-width: 768px){
	.b-measurement canvas{
		max-height: 55vh !important;
		width: auto !important;
		max-width: 100% !important;
	}
}
</style>
<!-- Start: Waiting Shield -->
<div id="waitingShield" class="hidden text-center">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>
<!-- End: Waiting Shield -->
@php
// dd($data);
$dataPoints = [];
$dataPoints1 = [];
$xaxis = [];
		
foreach ($data as $key => $value) {
	
	if($bodypart == 'bp'){
		$xaxis[] = $value['date'];
		$dataPoints[] = $value['bp_mm'];
		$dataPoints1[] = $value['bp_hg'];
	}else{
		$xaxis[] = $value['date'];
		$dataPoints[] = $value['value'];
	}
	

}
if($duration == 1){
	$unit = 'day';
}else{
	$unit = 'month';
}

if($bodypart == 'smm' || $bodypart == 'bfm' || $bodypart == 'vis_fat'){
	$yaxis_label_suffix = $label_suffix;
}
if($bodypart == 'bfp'){
	$yaxis_label_suffix = $label_suffix;
}
if($bodypart == 'bmr'){
	$yaxis_label_suffix = $label_suffix;
}
if($bodypart == 'bmi'){
	$yaxis_label_suffix = $label_suffix;
}
if($bodypart == 'hw'){
	$yaxis_label_suffix = $label_suffix;
}
if($bodypart == 'pulse'){
	$yaxis_label_suffix = $label_suffix;
}
if($bodypart == 'bp'){
	$yaxis_label_suffix = $label_suffix;
}
// dd($xaxis);
// $minVal = min($dataPoints);
// $maxVal = max($dataPoints)
@endphp
<section class="measurement-header">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h4>{{ $body_part }}</h4>
			</div>
			<div class="col-md-6 text-right">
				<div class="reactRadioButtonGroup">
					<div class="ant-radio-group">
						{{-- <a href="{{ url('measurement') }}/{{ $bodypart }}/1">  --}}
							<input type="radio" id="radio1" checked bodypart="{{ $bodypart }}" name="filterDate" value="1" class="ant-radio-button-input" @if($duration == 1) checked @endif>
							<label for="radio1" class="ant-radio-button-wrapper">1M</label>
						{{-- </a> --}}

						{{-- <a href="{{ url('measurement') }}/{{ $bodypart }}/3">  --}}
							<input type="radio" id="radio2" bodypart="{{ $bodypart }}" name="filterDate" value="3" class="ant-radio-button-input" @if($duration == 3) checked @endif>
							<label for="radio2" class="ant-radio-button-wrapper">3M</label>
						{{-- </a> --}}

						{{-- <a href="{{ url('measurement') }}/{{ $bodypart }}/6">  --}}
							<input type="radio" id="radio3" bodypart="{{ $bodypart }}" name="filterDate" value="6" class="ant-radio-button-input" @if($duration == 6) checked @endif>
							<label for="radio3" class="ant-radio-button-wrapper">6M</label>
						{{-- </a> --}}

						{{-- <a href="{{ url('measurement') }}/{{ $bodypart }}/12">  --}}
							<input type="radio" id="radio4" bodypart="{{ $bodypart }}" name="filterDate" value="12" class="ant-radio-button-input" @if($duration == 12) checked @endif>
							<label for="radio4" class="ant-radio-button-wrapper">1Y</label>
						{{-- </a> --}}

						{{-- <a href="{{ url('measurement') }}/{{ $bodypart }}/24">  --}}
							<input type="radio" id="radio5" bodypart="{{ $bodypart }}" name="filterDate" value="24" class="ant-radio-button-input" @if($duration == 24) checked @endif>
							<label for="radio5" class="ant-radio-button-wrapper">2Y</label>
						{{-- </a> --}}

						{{-- <a href="{{ url('measurement') }}/{{ $bodypart }}/36">  --}}
							<input type="radio" id="radio6" bodypart="{{ $bodypart }}" name="filterDate" value="36" class="ant-radio-button-input" @if($duration == 36) checked @endif>
							<label for="radio6" class="ant-radio-button-wrapper">3Y</label>
						{{-- </a> --}}
					</div>
				</div>
	  
			</div>
			<div class="col-md-12">
				
				<div class="currentDate flex">
					<div class="pr16 view-calendar">
						<script type="text/javascript">
						const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
						"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
						];

						const d = new Date();
						document.write("" + monthNames[d.getMonth()]);
					</script>  
					 <script>
						now = new Date
						theYear=now.getYear()
						if (theYear < 1900)
							theYear=theYear+1900
						document.write(theYear)
					</script>
				</div>
				<div>
					<a href="javascript:void(0)" class="filterGraph" bodypart="{{ $bodypart }}" month="{{ date("Y-m-d") }}" duration="{{ $duration }}" status="previous"><i class="fa fa-angle-left"></i></a>
				</div>
				<div>
					<img src="{{asset('assets/images/calendar1.png')}}">
				</div>
				<div>
					<a href="javascript:void(0)" class="filterGraph" bodypart="{{ $bodypart }}" month="{{ date("Y-m-d") }}" duration="{{ $duration }}" status="next"><i class="fa fa-angle-right"></i></a>
				</div>

			</div>
				</div>
		</div>
		
	</div>
</section>
<!-- Start: First row -->
<div class="row pos-of-acc pos-of-acc1 pos-of-accc b-measurement" style="">

	<!-- Start: Helath section -->
    <div class="col-md-12 col-sm-12" id="health-section-row">
		<div class="panel panel-white no-radius load1 panel-white-me" id="visits">
			<div collapse="visits" class="panel-wrapper">
				<div class="panel-body">
					@if($bodypart == 'bp')
					@if (count($dataPoints) > 0)
					<div class="minh-350 my-div graph">
						<div class="health-section-area">
							<canvas id="myChart1" height="100"></canvas>
						</div>
					</div>
					@else
					<div class="row my-div graph" style="padding: 10%">
						<canvas id="myChart1" height="100" class="hidden"></canvas>
						<center><img src="{{ asset('no-graph.png') }}" alt="no-graph" height="100" width="100" srcset=""></center>
						<center><h4>There are no stats to graph right now.</h4></center>
						<center><h6>Try changing the timeframe, or adding new stats.</h6></center>
					</div>
					@endif
					@endif
					@if($bodypart != 'bp')
					@if (count($dataPoints) > 0)
					<div class="minh-350 my-div graph">
						<div class="health-section-area">
							<canvas id="myChart" height="100"></canvas>
						</div>
					</div>
					@else
					<div class="row my-div graph" style="padding: 10%">
						<canvas id="myChart" height="100" class="hidden"></canvas>
						<center><img src="{{ asset('no-graph.png') }}" alt="no-graph" height="100" width="100" srcset=""></center>
						<center><h4>There are no stats to graph right now.</h4></center>
						<center><h6>Try changing the timeframe, or adding new stats.</h6></center>
					</div>
					@endif
					@endif
					
					<div class="minh-350 my-div filter-div" style="display: none">
						<div class="health-section-area">
							{{-- <canvas id="filterChart" height="100"></canvas> --}}
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<!-- End: Helath section -->
</div>

@endsection

@section('script')
{!! Html::script('result/js/jquery-ui.min.js') !!}
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
{!! Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
{!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('result/js/bootstrap-timepicker.js') !!}
{!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}
{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
{!! Html::script('result/plugins/chartjs/dist/Chart.bundle.min.js') !!}
{!! Html::script('result/js/helper.js?v='.time()) !!}
{!! Html::script('result/js/dashboard.js?v='.time()) !!}
{!! Html::script('result/js/dashboard-chart.js?v='.time()) !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"> </script>
<script>
	var fontSize =14;
	if($(window).width() > 767){
		fontSize = 30;
	}
	let draw = Chart.controllers.line.prototype.draw;
	Chart.controllers.line = Chart.controllers.line.extend({
		draw: function() {
			draw.apply(this, arguments);
			let ctx = this.chart.chart.ctx;
			let _stroke = ctx.stroke;
			ctx.stroke = function() {
				ctx.save();
				ctx.shadowColor = '#D7D4D4';
				ctx.shadowBlur = 6;
				ctx.shadowOffsetX = 0;
				ctx.shadowOffsetY = 10;
				_stroke.apply(this, arguments)
				ctx.restore();
			}
		}
	});

	if('{{ $bodypart }}' != 'bp'){
	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($xaxis); ?>,
			datasets: [{
				backgroundColor:'transparent',
				borderColor: '#FF0000',
				// borderColor: '#E56590',
				data:  <?php echo json_encode($dataPoints); ?>,
				label: '{{ $body_part }}',
			}]
		},
		options: {
			legend: {
				display: true,
				position: 'bottom',
			},
			tooltips: {
				mode: 'index',
			},
			scales: {
				yAxes: [{
					ticks: {
						fontSize:fontSize,
						beginAtZero: false,
						// stepSize: 1,
						// min: @php echo $minVal - 1.0; @endphp,
						// max: @php echo $maxVal + 1.0; @endphp,
						callback: function (value, index, values) {
                           return value + ' {{ $yaxis_label_suffix }}'  ;
						},
						// type: 'time',
						// time: {
						// 	stepSize: 1,
						// 	// tooltipFormat: 'DD/MM/YY',
						// }
					}
				}],
				xAxes: [{
					gridLines: { 
						display: false 
					} ,
					ticks: {
						fontSize:fontSize,
						autoskip: true,
						// maxTicksLimit:6
					},
					type: 'time',
                    time: {
						unit: '{{ $unit }}',
						stepSize: '{{$stepSize}}',
						min:'{{$startOfTheMonth}}',
						max:'{{$endOfTheMonth}}',
                    }
				}]
			}
		}
	});
}

	$(document).on('click','input[name=filterDate]', function(){
		var duration = $(this).val();
		var bodypart = $(this).attr('bodypart');
		$.ajax({
			url: public_url + 'filter-personal-stastic/' + bodypart + '/' + duration,
			method: "get",
			success: function (data) {
				$(".graph").hide();
				$(".filter-div").show();
				$(".filter-div").html(data)
			}
		});
	})

	$(document).on('click','.filterGraph', function(){
		var duration = $(this).attr('duration');
		var bodypart = $(this).attr('bodypart');
		var status = $(this).attr('status');
		var month = $(this).attr('month');
		$.ajax({
			url: public_url + 'filter-personal-stastic',
			method: "post",
			data:{
				'duration' : duration,
				'bodypart' : bodypart,
				'status' : status,
				'month' : month,
			},
			success: function (data) {
				$(".graph").hide();
				$(".filter-div").html(data);
				$(".filter-div").show();
				
			}
		});
	})
	if('{{ $bodypart }}' == 'bp'){
	var ctx1 = document.getElementById('myChart1').getContext('2d');
	var myChart = new Chart(ctx1, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($xaxis); ?>,
			datasets: [
				{
					backgroundColor:'transparent',
					borderColor: '#FF0000',
					// borderColor: '#E56590',
					data:  <?php echo json_encode($dataPoints); ?>,
					label: 'BP mm',
				},
				{
					backgroundColor:'transparent',
					borderColor: '#FF0000',
					// borderColor: '#E56590',
					data:  <?php echo json_encode($dataPoints1); ?>,
					label: 'BP hg',
				}
			]
		},
		options: {
			legend: {
				display: true,
				position: 'bottom',
			},
			tooltips: {
				mode: 'index',
			},
			scales: {
				yAxes: [{
					ticks: {
						fontSize:fontSize,
						beginAtZero: false,
						// stepSize: 1,
						// min: @php echo $minVal - 1.0; @endphp,
						// max: @php echo $maxVal + 1.0; @endphp,
						callback: function (value, index, values) {
                           return value + ' {{ $yaxis_label_suffix }}'  ;
						},
						// type: 'time',
						// time: {
						// 	stepSize: 1,
						// 	// tooltipFormat: 'DD/MM/YY',
						// }
					}
				}],
				xAxes: [{
					gridLines: { 
						display: false 
					} ,
					ticks: {
						fontSize:fontSize,
						autoskip: true,
						// maxTicksLimit:6
					},
					type: 'time',
                    time: {
						unit: '{{ $unit }}',
						stepSize: '{{$stepSize}}',
						min:'{{$startOfTheMonth}}',
						max:'{{$endOfTheMonth}}',
                    }
				}]
			}
		}
	});
}
	</script>

@stop

@section('script-handler-for-this-page')
	
@stop