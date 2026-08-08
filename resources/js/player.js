(function() {
	'use strict';

	const charting = require('./charting');

	charting.initCharts()
		.then(() => charting.drawCharts(stats));
})();