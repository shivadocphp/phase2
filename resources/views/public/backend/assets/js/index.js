(function($) {
    "use strict";

	/*---ChartJS ---*/
	var ctx = document.getElementById("chart");
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: ["2012", "2013", "2014", "2015", "2016", "2017", "2018"],
			type: 'line',
			datasets: [{
				label: "Vistors",
				data: [10, 60, 30, 90, 120, 76, 35],
				backgroundColor: 'transparent',
				borderColor: '#6512ae',
				borderWidth: 3,
				pointStyle: 'circle',
				pointRadius: 5,
				pointBorderColor: 'transparent',
				pointBackgroundColor: '#6512ae',
			}, {
				label: "PageViews",
				data: [15, 70, 80, 120, 45, 68, 78],
				backgroundColor: 'transparent',
				borderColor: '#f2574c',
				borderWidth: 3,
				pointStyle: 'circle',
				pointRadius: 5,
				pointBorderColor: 'transparent',
				pointBackgroundColor: '#f2574c',
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			tooltips: {
				mode: 'index',
				titleFontSize: 12,
				titleFontColor: '#000',
				bodyFontColor: '#000',
				backgroundColor: '#fff',
				cornerRadius: 3,
				intersect: false,
			},
			legend: {
				display: false,
				labels: {
					usePointStyle: true,
					fontFamily: 'Montserrat',
				},
			},
			scales: {
				xAxes: [{
					ticks: {
						fontColor: "rgba(0,0,0,0.5)",
					 },
					display: true,
					gridLines: {
						display: true,
						drawBorder: false
					},
					scaleLabel: {
						display: false,
						labelString: 'Month',
						fontColor: 'rgba(0,0,0,0.61)'
					}
				}],
				yAxes: [{
					ticks: {
						fontColor: "rgba(0,0,0,0.5)",
					 },
					display: true,
					gridLines: {
						display: true,
						drawBorder: false
					},
					scaleLabel: {
						display: true,
						labelString: 'Value',
						fontColor: 'rgba(0,0,0,0.61)'
					}
				}]
			},
			title: {
				display: false,
				text: 'Normal Legend'
			}
		}
	});
	/*---ChartJS (#sales-chart) closed---*/

})(jQuery);

