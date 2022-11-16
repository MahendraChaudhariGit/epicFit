@php
// dd($pastMonth);
$filterDataPoints = [];
$filterXaxis = [];
foreach ($data as $key => $value) {
	// if($duration != 1){
	$filterXaxis[] = $value['date'];
	// }
	$filterDataPoints[] = $value['value'];
}
if($duration == 1){
	// dd(date("t",strtotime($pastMonth)));
	$unit = 'day';
	// for($i=1;$i<=date("t",strtotime($pastMonth));$i++){
	// 	$filterXaxis[] = date('Y-m-'.$i,strtotime($pastMonth));
	// }
}else{
	$unit = 'month';
}
if($body_part == 'Weight'){
	$yaxis_label_suffix = $weight_unit;
}elseif($body_part == 'Height'){
	$yaxis_label_suffix = $height_unit;
}else{
	$yaxis_label_suffix = 'cm';
}
// dd($filterXaxis);
// $minVal = min($filterDataPoints);
// $maxVal = max($filterDataPoints);
// dd($minVal,$maxVal);
@endphp
<div class="health-section-area dash-graph">
    <canvas id="filterChart" height="250"></canvas>
</div>

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
	
	$(".filterGraph").attr('month','{{ $pastMonth }}');
	$(".filterGraph").attr('duration','{{ $duration }}');
	$(".view-calendar").html('{{ $viewCalendar }}')
	</script>