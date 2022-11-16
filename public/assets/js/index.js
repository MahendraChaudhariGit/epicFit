'use strict';
var Index = function() {
	var chart1Handler = function() {
		var data = {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			datasets: [{
				label: 'My First dataset',
				fillColor: 'rgba(220,220,220,0.2)',
				strokeColor: 'rgba(220,220,220,1)',
				pointColor: 'rgba(220,220,220,1)',
				pointStrokeColor: '#fff',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(220,220,220,1)',
				data: [65, 59, 80, 81, 56, 55, 40, 84, 64, 120, 132, 87]
			}, {
				label: 'My Second dataset',
				fillColor: 'rgba(151,187,205,0.2)',
				strokeColor: 'rgba(151,187,205,1)',
				pointColor: 'rgba(151,187,205,1)',
				pointStrokeColor: '#fff',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(151,187,205,1)',
				data: [28, 48, 40, 19, 86, 27, 90, 102, 123, 145, 60, 161]
			}]
		};

		var options = {

			maintainAspectRatio: false,

			// Sets the chart to be responsive
			responsive: true,

			///Boolean - Whether grid lines are shown across the chart
			scaleShowGridLines: true,

			//String - Colour of the grid lines
			scaleGridLineColor: 'rgba(0,0,0,.05)',

			//Number - Width of the grid lines
			scaleGridLineWidth: 1,

			//Boolean - Whether the line is curved between points
			bezierCurve: false,

			//Number - Tension of the bezier curve between points
			bezierCurveTension: 0.4,

			//Boolean - Whether to show a dot for each point
			pointDot: true,

			//Number - Radius of each point dot in pixels
			pointDotRadius: 4,

			//Number - Pixel width of point dot stroke
			pointDotStrokeWidth: 1,

			//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
			pointHitDetectionRadius: 20,

			//Boolean - Whether to show a stroke for datasets
			datasetStroke: true,

			//Number - Pixel width of dataset stroke
			datasetStrokeWidth: 2,

			//Boolean - Whether to fill the dataset with a colour
			datasetFill: true,

			// Function - on animation progress
			onAnimationProgress: function() {
			},

			// Function - on animation complete
			onAnimationComplete: function() {
			},

			//String - A legend template
			legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
		};
		// Get context with jQuery - using jQuery's .get() method.
		var ctx = $("#chart1").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		var chart1 = new Chart(ctx).Line(data, options);
		//generate the legend
		
		var legend = chart1.generateLegend();
		//and append it to your page somewhere
		$('#chart1Legend').append(legend);
	};
	var chart2Handler = function() {
		var nextMonthMoment = new moment().subtract(12, 'M'),
 			monthsArr = [];
		for(var i=0; i<12; i++)
			monthsArr.push(nextMonthMoment.add(1, 'M').format('MMM YYYY'));

		// Chart.js Data
		var data = {
			labels: monthsArr,//['January', 'February', 'March', 'April', 'May', 'June', 'July'],
			datasets: [{
				// 
				label: 'Active Clients',
				backgroundColor: 'rgba(66,134,244,0.5)',
				borderColor: 'rgba(66,134,244,0.8)',
				hoverBackgroundColor: 'rgba(66,134,244,0.75)',
				hoverBorderColor: 'rgba(66,134,244,1)',
				data: total_new_client_permonth.reverse()
				//data: [28, 48, 40, 19, 86, 27, 90]
			} ,{
				label: 'Inactive Clients',
				backgroundColor: 'rgba(151,187,205,0.5)',
				borderColor: 'rgba(151,187,205,0.8)',
				hoverBackgroundColor: 'rgba(151,187,205,0.75)',
				hoverBorderColor: 'rgba(151,187,205,1)',
				//data: [65, 59, 80, 81, 56, 55]
				data: total_inactive_clients_permonth.reverse()
			} ,{
				label: 'On-Hold Clients',
				backgroundColor: 'rgba(224,204,130,0.5)',
				borderColor: 'rgba(224,204,130,0.8)',
				hoverBackgroundColor: 'rgba(224,204,130,0.75)',
				hoverBorderColor: 'rgba(224,204,130,1)',
				//data: [65, 59, 80, 81, 56, 55]
				data: total_onhold_clients_permonth.reverse()
			}]
		};
		/*MaxNumofClients=50;*/
		var step=Math.ceil(MaxNumofClients/10);
		var maxval=MaxNumofClients;
		if(MaxNumofClients > 10){
			var step=Math.ceil(MaxNumofClients/9);
			maxval=(step*9);
		}
		
		// Chart.js Options
		var options = {
			maintainAspectRatio: false,

			// Sets the chart to be responsive
			responsive: true,

			//Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
			scaleBeginAtZero: true,

			//Boolean - Whether grid lines are shown across the chart
			scaleShowGridLines: true,

			//String - Colour of the grid lines
			scaleGridLineColor: "rgba(0,0,0,.05)",

			//Number - Width of the grid lines
			scaleGridLineWidth: 1,

			//Boolean - If there is a stroke on each bar
			barShowStroke: true,

			//Number - Pixel width of the bar stroke
			barStrokeWidth: 2,

			//Number - Spacing between each of the X value sets
			barValueSpacing: 5,

			//Number - Spacing between data sets within X values
			barDatasetSpacing: 1,
			/*scaleOverride: true,
            scaleSteps: 10,
            scaleStepWidth:MaxNumofClients,*/
            scales: {
                     
                yAxes: [{
                    ticks: {
                        max: maxval,
                        min: 0,
                        stepSize:step,
                    },     
                }]
            },
            //barThickness:100,
			//String - A legend template
			legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
		    legend: {
            display: false
            } 
 		};
		// Get context with jQuery - using jQuery's .get() method.
		var ctx = $("#chart2").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		//var chart2 = new Chart(ctx).Bar(data, options);
		var chart2  = new Chart(ctx, {
                type: 'bar',
                data: data,
                options:options 
            });

		 
        
		//generate the legend
		//var legend = chart2.generateLegend();
		//and append it to your page somewhere
		//$('#chart2Legend').append(legend);
	};
	var chart3Handler = function() {

		// Chart.js Data
		/*
		var data = [{
			value: count_active,
			color: '#F7464A',
			highlight: '#FF5A5E',
			label: 'Active'
		}, {
			value: count_contra,
			color: '#46BFBD',
			highlight: '#5AD3D1',
			label: 'Contra'
		}, {
			value: count_inactive,
			color: '#E4E42C',
			highlight: '#E4E42C',
			label: 'Inactive'
		}, {
			value: count_onhold,
			color: '#1aec1a',
			highlight: '#1aec1a',
			label: 'On Hold'
		}, {
			value: count_pending,
			color: '#3b3bd1',
			highlight: '#5AD3D1',
			label: 'Pending'
		}, {
			value: count_other,
			color: '#FDB45C',
			highlight: '#FFC870',
			label: 'Other'
		}];
		*/

		var dataVal=[];
		var backgroundColor=[];
		var labels=[];
		var i=0;
		$.each( clients_chart, function( key, value ) {
			//backgroundColor[j]=value;
		   if(key=='active'){
		   		dataVal[i]=count_active;
		   		labels[i]='Active';
		   		backgroundColor[i]=value;
		   }
		   else if(key=='contra'){
		   		dataVal[i]=count_contra;
		   		labels[i]='Contra';
		   		backgroundColor[i]=value;
		   }
		   else if(key=='inactive'){
		   		dataVal[i]=count_inactive;
		   		labels[i]='Inactive';
		   		backgroundColor[i]=value;
		   }
		   else if(key=='on_hold'){
		   		dataVal[i]=count_onhold;
		   		labels[i]='On Hold';
		   		backgroundColor[i]=value;
		   }
		   else if(key=='pending'){
		   		dataVal[i]=count_pending;
		   		labels[i]='Pending';
		   		backgroundColor[i]=value;
		   }
		   else if(key=='other'){
		   		dataVal[i]=count_other;
		   		labels[i]='Other';
		   		backgroundColor[i]=value;
		   }
		 i++;
		});
		var data = {
		    datasets: [{
		        data: dataVal,
		        backgroundColor: backgroundColor ,
		        label: 'dataset 1'
		    }],
		     labels: labels
		    
		};
		/*var data = {
		    datasets: [{
		        data: [count_active, count_contra, count_inactive, count_onhold, count_pending, count_other],
		        backgroundColor: ['#F7464A','#46BFBD','#E4E42C','#1aec1a','#3b3bd1','#FDB45C'],
		        hoverBackgroundColor:['#FF5A5E','#5AD3D1','#E4E42C','#1aec1a','#5AD3D1','#FFC870'],
		        label: 'dataset 1'
		    }],
		     labels: ['Active','Contra','Inactive','On Hold','Pending','Other']
		    
		};*/

		//Start: data2 for pie chart 2
		/*
		var data2 = [{
			value: count_lead,
			color: '#F7464A',
			highlight: '#FF5A5E',
			label: 'Pending'
		}, {
			value: count_pre_preconsult,
			color: '#46BFBD',
			highlight: '#5AD3D1',
			label: 'Pre-Consultation'
		}, {
			value: count_pre_benchmark,
			color: '#E4E42C',
			highlight: '#E4E42C',
			label: 'Pre-Benchmark'
		}, {
			value: count_pre_training,
			color: '#1aec1a',
			highlight: '#1aec1a',
			label: 'Pre-Training'
		}/*, {
			value: count_consulted_custom,
			color: '#3b3bd1',
			highlight: '#5AD3D1',
			label: 'Custom'
		}];*/

		var dataVal2=[];
		var backgroundColor2=[];
		var labels2=[];
		var i=0;
		$.each(sales_chart, function( key, value ) {
			//backgroundColor[j]=value;
		   if(key=='sales_pending'){
		   		dataVal2[i]=count_lead;
		   		labels2[i]='Pending';
		   		backgroundColor2[i]=value;
		   }
		   else if(key=='pre_consultation'){
		   		dataVal2[i]=count_pre_preconsult;
		   		labels2[i]='Pre-Consultation';
		   		backgroundColor2[i]=value;
		   }
		   else if(key=='pre_benchmark'){
		   		dataVal2[i]=count_pre_benchmark;
		   		labels2[i]='Pre-Benchmark';
		   		backgroundColor2[i]=value;
		   }
		   else if(key=='pre_training'){
		   		dataVal2[i]=count_pre_training;
		   		labels2[i]='Pre-Training';
		   		backgroundColor2[i]=value;
		   }
		   
		 i++;
		});

		var data2 = {
		    datasets: [{
		        data: dataVal2,
		        backgroundColor: backgroundColor2 ,
		        label: 'dataset 1'
		    }],
		     labels: labels2
		    
		};

		/*var data2 = {
		    datasets: [{
		        data: [count_lead, count_pre_preconsult, count_pre_benchmark, count_pre_training],
		        backgroundColor: ['#F7464A','#46BFBD','#E4E42C','#1aec1a'],
		        hoverBackgroundColor:['#FF5A5E','#5AD3D1','#E4E42C','#1aec1a']
		    }],
		    labels: ['Pending','Pre-Consultation','Pre-Benchmark','Pre-Training']
		};*/
		
		//End: data2 for pie chart 2
		

		// Chart.js Options
		
		var options = {
            circumference:2 * Math.PI, 
			// Sets the chart to be responsive
			responsive: false,

			//Boolean - Whether we should show a stroke on each segment
			segmentShowStroke: true,

			//String - The colour of each segment stroke
			segmentStrokeColor: '#fff',

			//Number - The width of each segment stroke
			segmentStrokeWidth: 2,

			//Number - The percentage of the chart that we cut out of the middle
			percentageInnerCutout: 50, // This is 0 for Pie charts

			//Number - Amount of animation steps
			animationSteps: 100,

			//String - Animation easing effect
			animationEasing: 'easeOutBounce',

			//Boolean - Whether we animate the rotation of the Doughnut
			animateRotate: true,

			//Boolean - Whether we animate scaling the Doughnut from the centre
			animateScale: false,
            tooltipFontSize: 8,
            
			//String - A legend template
			//legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
            legendCallback: function(chart) {
                var text = [];
                text.push('<ul class="tc-chart-js-legend clearfix text-center">');
                for (var i=0; i<chart.data.datasets[0].data.length; i++) {
                    text.push('<li style="float:none">');
                   // text.push('<span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"></span>');
                    if (chart.data.labels[i]) {
                    	text.push('<span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"></span>'+chart.data.labels[i]);
                        //text.push(chart.data.labels[i]);
                    }
                    text.push('</li>');
                }
                text.push('</ul>');
                return text.join("");
            },
          
		     legend: {
            display: false,
            }
		};
		
/*
		var options= {
            responsive: true,
            radiusPercentage: 2,
		    circumference: 2*Math.PI,
		    segmentStrokeColor: '#fff',
		    segmentStrokeWidth: 2,
		    tooltipFontSize: 9,
		    labelFontSize : 12,
            animation: {
                animateScale: false,
                animateRotate: true
            },
            legend: {
                position: 'bottom',
                
            },
            
        };
    */

		//Start : piechart 1
		// Get context with jQuery - using jQuery's .get() method.
		var ctx = $("#chart3").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		//var chart3 = new Chart(ctx).Doughnut(data, options);
		var chart3 = new Chart(ctx, {
		    type: 'doughnut',
		    data: data,
		    options: options
		});
		//generate the legend
		var legend = chart3.generateLegend();
		//and append it to your page somewhere
		$('#chart3Legend').append(legend);
		//End : piechart 1

		//Start : piechart 2
		// Get context with jQuery - using jQuery's .get() method.
		var ctx2 = $("#pie_chart2").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		//var pie_chart2 = new Chart(ctx2).Doughnut(data2, options);
		//generate the legend
		var pie_chart2 = new Chart(ctx2, {
		    type: 'doughnut',
		    data: data2,
		    options: options
		});
		var legend2 = pie_chart2.generateLegend();
		//and append it to your page somewhere
	    $('#pie_2_Legend').append(legend2);
		//End : piechart 2
	};
	var chart4Handler = function() {
		// Chart.js Data
		var data = {
			labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
			datasets: [{
				label: 'My First dataset',
				fillColor: 'rgba(220,220,220,0.2)',
				strokeColor: 'rgba(220,220,220,1)',
				pointColor: 'rgba(220,220,220,1)',
				pointStrokeColor: '#fff',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(220,220,220,1)',
				data: [65, 59, 90, 81, 56, 55, 40]
			}, {
				label: 'My Second dataset',
				fillColor: 'rgba(151,187,205,0.2)',
				strokeColor: 'rgba(151,187,205,1)',
				pointColor: 'rgba(151,187,205,1)',
				pointStrokeColor: '#fff',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(151,187,205,1)',
				data: [28, 48, 40, 19, 96, 27, 100]
			}]
		};

		// Chart.js Options
		var options = {

			// Sets the chart to be responsive
			responsive: true,

			//Boolean - Whether to show lines for each scale point
			scaleShowLine: true,

			//Boolean - Whether we show the angle lines out of the radar
			angleShowLineOut: true,

			//Boolean - Whether to show labels on the scale
			scaleShowLabels: false,

			// Boolean - Whether the scale should begin at zero
			scaleBeginAtZero: true,

			//String - Colour of the angle line
			angleLineColor: 'rgba(0,0,0,.1)',

			//Number - Pixel width of the angle line
			angleLineWidth: 1,

			//String - Point label font declaration
			pointLabelFontFamily: '"Arial"',

			//String - Point label font weight
			pointLabelFontStyle: 'normal',

			//Number - Point label font size in pixels
			pointLabelFontSize: 10,

			//String - Point label font colour
			pointLabelFontColor: '#666',

			//Boolean - Whether to show a dot for each point
			pointDot: true,

			//Number - Radius of each point dot in pixels
			pointDotRadius: 3,

			//Number - Pixel width of point dot stroke
			pointDotStrokeWidth: 1,

			//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
			pointHitDetectionRadius: 20,

			//Boolean - Whether to show a stroke for datasets
			datasetStroke: true,

			//Number - Pixel width of dataset stroke
			datasetStrokeWidth: 2,

			//Boolean - Whether to fill the dataset with a colour
			datasetFill: true,

			//String - A legend template
			legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
		};
		// Get context with jQuery - using jQuery's .get() method.
		var ctx = $("#chart4").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		var chart4 = new Chart(ctx).Radar(data, options);
		//generate the legend
		var legend = chart4.generateLegend();
		//and append it to your page somewhere
		$('#chart4Legend').append(legend);
	};
	// function to initiate Sparkline
	var sparkResize;
	$(window).resize(function(e) {
		clearTimeout(sparkResize);
		sparkResize = setTimeout(sparklineHandler, 500);
	});
	var sparklineHandler = function() {
		$(".sparkline-1 span").sparkline([300, 523, 982, 811, 1300, 1125, 1487], {
			type: "bar",
			barColor: "#D43F3A",
			barWidth: "5",
			height: "24",
			tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{value}}',
			tooltipValueLookups: {
				names: {
					0: 'Sunday',
					1: 'Monday',
					2: 'Tuesday',
					3: 'Wednesday',
					4: 'Thursday',
					5: 'Friday',
					6: 'Saturday'

				}
			}
		});
		$(".sparkline-2 span").sparkline([400, 650, 886, 443, 502, 412, 353], {
			type: "bar",
			barColor: "#5CB85C",
			barWidth: "5",
			height: "24",
			tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{value}}',
			tooltipValueLookups: {
				names: {
					0: 'Sunday',
					1: 'Monday',
					2: 'Tuesday',
					3: 'Wednesday',
					4: 'Thursday',
					5: 'Friday',
					6: 'Saturday'

				}
			}
		});
		$(".sparkline-3 span").sparkline([4879, 6567, 5022, 8890, 9234, 7128, 4811], {
			type: "bar",
			barColor: "#46B8DA",
			barWidth: "5",
			height: "24",
			tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{value}}',
			tooltipValueLookups: {
				names: {
					0: 'Sunday',
					1: 'Monday',
					2: 'Tuesday',
					3: 'Wednesday',
					4: 'Thursday',
					5: 'Friday',
					6: 'Saturday'

				}
			}
		});
		$(".sparkline-4 span").sparkline([1122, 1735, 559, 2534, 1600, 2860, 1345, 1987, 2675, 457, 3965, 3765], {
			type: "line",
			lineColor: '#8e8e93',
			width: "80%",
			height: "47",
			fillColor: "",
			spotRadius: 4,
			lineWidth: 1,
			resize: true,
			spotColor: '#ffffff',
			minSpotColor: '#D9534F',
			maxSpotColor: '#5CB85C',
			highlightSpotColor: '#CE4641',
			highlightLineColor: '#c2c2c5',
			tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{y:val}}',
			tooltipValueLookups: {
				names: {
					0: 'January',
					1: 'February',
					2: 'March',
					3: 'April',
					4: 'May',
					5: 'June',
					6: 'July',
					7: 'August',
					8: 'September',
					9: 'October',
					10: 'November',
					11: 'December'

				}
			}
		});
		$(".sparkline-5 span").sparkline([422, 1335, 1059, 2235, 1300, 1860, 1126, 1387, 1675, 1357, 2165, 1765], {
			type: "line",
			lineColor: '#8e8e93',
			width: "80%",
			height: "47",
			fillColor: "",
			spotRadius: 4,
			lineWidth: 1,
			resize: true,
			spotColor: '#ffffff',
			minSpotColor: '#D9534F',
			maxSpotColor: '#5CB85C',
			highlightSpotColor: '#CE4641',
			highlightLineColor: '#c2c2c5',
			tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}}: {{y:val}}',
			tooltipValueLookups: {
				names: {
					0: 'January',
					1: 'February',
					2: 'March',
					3: 'April',
					4: 'May',
					5: 'June',
					6: 'July',
					7: 'August',
					8: 'September',
					9: 'October',
					10: 'November',
					11: 'December'

				}
			}
		});
	};
	return {
		init: function() {
			//chart1Handler();
			chart2Handler();
			chart3Handler();
			//chart4Handler();
			sparklineHandler();
		}
	};
}();
