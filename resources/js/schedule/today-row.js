import _ from 'lodash';

export default function todayRowInit() {

	window.addEventListener('DOMContentLoaded', function () {

		// Add today to the proper spot in the table
		var trs = document.querySelectorAll('tr[data-timestamp]');
		var now = Date.now();

		var before = _.find(trs, function (tr) {
			return parseTS(tr.dataset.timestamp) > now;
		});

		if (before) {
			// create the today row and inject it
			var tr = document.createElement('tr');
			var td = document.createElement('td');

			tr.classList.add('schedule-today');
			tr.dataset.skipFilter = true;
			td.innerHTML = 'today';
			td.colSpan = document.querySelectorAll('table.schedule thead th').length;
			tr.appendChild(td);

			before.parentNode.insertBefore(tr, before);
		}

		function parseTS(ts) {
			return parseInt(ts, 10) * 1000;
		}

	});
}