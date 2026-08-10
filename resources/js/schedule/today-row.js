export default function todayRowInit() {

	window.addEventListener('DOMContentLoaded', () => {

		// Add today to the proper spot in the table
		const trs = document.querySelectorAll('tr[data-timestamp]');
		const now = Date.now();

        // first TR with a timestamp greater than now
        const before = [...trs].find(tr => parseTS(tr.dataset.timestamp) > now);

		if (before) {
			// create the today row and inject it
			const tr = document.createElement('tr');
			const td = document.createElement('td');

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
