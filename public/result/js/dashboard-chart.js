'use strict';
var DashboardChart = function() {
	var bmiChartHandler = function(){
		var data = {
			        labels: ["January", "February", "March", "April", "May", "June", "July"],
			        datasets: [{
			            label: "Body Mass Index",
			            backgroundColor: 'rgb(151,187,205, 0.6)',
			            borderColor: 'rgb(151,187,205, 1)',
			            data: [0, 10, 5, 2, 20, 30, 10],
			        }]
		};

		var options = {
			responsive: true,
			spanGaps: false,

			/*maintainAspectRatio: false,*/
			/*title: {
				text: 'BMI',
				display: true
			},*/
			elements: {
				line: {
					tension: 0.0001
				}
			},
			plugins: {
				filler: {
					propagate: false
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						autoSkip: false,
						maxRotation: 0
					}
				}]
			},
			legend: {
				position: 'bottom',
			}
		};

		var ctx = document.getElementById('bmiChart').getContext('2d');
		var chart = new Chart(ctx, {
		    type: 'line',
		    data: data,
		    options: options
		});
	}

	var bmrChartHandler = function(){
		var data = {
			        labels: ["January", "February", "March", "April", "May", "June", "July"],
			        datasets: [{
			            label: "Basal Metabolism Rate",
			            backgroundColor: 'rgb(151,187,205, 0.6)',
			            borderColor: 'rgb(151,187,205, 1)',
			            data: [0, 10, 20, 5, 2, 30, 4],
			        }]
		};

		var options = {
			responsive: true,
			spanGaps: false,

			/*maintainAspectRatio: false,*/
			/*title: {
				text: 'BMI',
				display: true
			},*/
			elements: {
				line: {
					/*tension: 0.000001*/
				}
			},
			plugins: {
				filler: {
					propagate: false
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						autoSkip: false,
						maxRotation: 0
					}
				}]
			},
			legend: {
				position: 'bottom',
			}
		};

		var ctx = document.getElementById('bmrChart').getContext('2d');
		var chart = new Chart(ctx, {
		    type: 'line',
		    data: data,
		    options: options
		});
	}
	return {
		init: function() {
			bmiChartHandler();
			bmrChartHandler();
		}
	};
}();
