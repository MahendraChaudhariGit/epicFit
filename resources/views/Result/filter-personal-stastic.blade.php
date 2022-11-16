@php
// dd($pastMonth);
$filterDataPoints = [];
$filterDataPoints1 = [];
$filterXaxis = [];
foreach ($data as $key => $value) {
	if($bodypart == 'bp'){
		$filterXaxis[] = $value['date'];
		$filterDataPoints[] = $value['bp_mm'];
		$filterDataPoints1[] = $value['bp_hg'];
	}else{
		$filterXaxis[] = $value['date'];
		$filterDataPoints[] = $value['value'];
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
// dd($filterXaxis);
// $minVal = min($filterDataPoints);
// $maxVal = max($filterDataPoints);
// dd($minVal,$maxVal);
@endphp
@if($bodypart != 'bp')
<div class="health-section-area">
    <canvas id="filterChart" height="200"></canvas>
</div>
@endif

@if($bodypart == 'bp')
<div class="health-section-area">
    <canvas id="filterChart1" height="200"></canvas>
</div>
@endif
<script>
	var fontSize =14;
	if(window.location.pathname != '/new-dashboard'){
		if($(window).width() > 767){
			fontSize = 30;
		}
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
	var ctx = document.getElementById('filterChart').getContext('2d');
	
	    var filterChart = new Chart(ctx, {
			
		type: 'line',
		data: {
			labels: <?php echo json_encode($filterXaxis); ?>,
			datasets: [{
				
				backgroundColor:'transparent',
				borderColor: '#FF0000',
				data:  <?php echo json_encode($filterDataPoints); ?>,
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
						// max: @php echo $minVal + 1.0; @endphp,
						callback: function (value, index, values) {
                           return value + ' {{ $yaxis_label_suffix }}'  ;
                         }
						
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
						max:'{{ $endOfTheMonth }}'
                    }
				}]
			}
		}
	});
}

if('{{ $bodypart }}' == 'bp'){
	var ctx = document.getElementById('filterChart1').getContext('2d');
	    var filterChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($filterXaxis); ?>,
			datasets: [
				{
				backgroundColor:'transparent',
				borderColor: '#FF0000',
				data:  <?php echo json_encode($filterDataPoints); ?>,
				label: 'BP mm',
				},{
					backgroundColor:'transparent',
					borderColor: '#FF0000',
					data:  <?php echo json_encode($filterDataPoints1); ?>,
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
						// max: @php echo $minVal + 1.0; @endphp,
						callback: function (value, index, values) {
                           return value + ' {{ $yaxis_label_suffix }}'  ;
                         }
						
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
						max:'{{ $endOfTheMonth }}'
                    }
				}]
			}
		}
	});
}
	
	$(".filterGraph").attr('month','{{ $pastMonth }}');
	$(".filterGraph").attr('duration','{{ $duration }}');
	$(".view-calendar").html('{{ $viewCalendar }}')
	</script>