var _ = require('lodash');

// setup the colors for the charts
var base = '#cfcfcf',
	accent = '#2a82c9',
	accentAlt = '#f29800',
	colors = {
		default: [accent, base],
		negative: [base, accent],
		multiple: [accent, accentAlt, base]
	};

// the default google chart options
var defaultOptions = {
	width: '100%',
	height: '100%',
	chartArea: {
		top: '5%',
		left: '5%',
		width: '90%',
		height: '90%'
	},
	pieHole: 0.9,
	legend: 'none',
	backgroundColor: 'none',
	pieSliceText: 'none',
	pieSliceBorderColor: 'none'
};

var charts = {};
var chartElements = [];


// and now for the google
function initCharts() {

	_.forEach(stats, function(data, key) {
		var id = _.uniqueId('chart');
		var selector = '.stat--' + _.kebabCase(key);
		var statEl = document.querySelector(selector);
		var chartEl = statEl.querySelector('.stat-chart');
		var chartOptions = _.extend({}, defaultOptions);
		var chartData;
		var chart;

		if (data.options) {
			if (data.options.negative) {
				chartOptions.colors = colors.negative;
			} else if (data.options.multiple) {
				chartOptions.colors = colors.multiple;
			}
		} else {
			chartOptions.colors = colors.default;
		}

		chartData = google.visualization.arrayToDataTable(data.data);
		chart = new google.visualization.PieChart(chartEl);

		statEl.dataset.chartId = id;
		charts[id] = {};
		charts[id].chart = chart;
		charts[id].chartOptions = chartOptions;
		charts[id].chartData = chartData;

		chartElements.push(statEl);
	});

}

function drawCharts() {

	_.each(chartElements, function(el) {
		var id = el.dataset.chartId;
		var chart = charts[id].chart;
		var options = charts[id].chartOptions;
		var data = charts[id].chartData;

		el.classList.remove('stat--loading');
		chart.draw(data, options);
	});

}

google.charts.load('current', {'packages':['corechart']});

// when google is loaded, make the charts
google.charts.setOnLoadCallback(function(){
	initCharts();
	drawCharts();

	// redraw charts when the window size changes
	window.onresize = drawCharts;
});