class TableFilter {

	constructor(table) {
		this.table = table;
		this.table.classList.add('tableFilter');

		this.filters = [];

		this.tbody = this.table.querySelector('tbody');
		this.heads = this.table.querySelectorAll('thead > tr > th');
		this.rows = [].slice.call(this.table.querySelectorAll('tbody > tr'));

		document.addEventListener('click', (event) => {
			let toClose = this.filters.slice();

			const filterEl = event.target.closest('.filter');
			if (filterEl) {
				const exclude = Number(filterEl.dataset.filterIndex);
				const removeIdx = toClose.findIndex((filter) => filter.index === exclude);

				toClose.splice(removeIdx, 1);
			}

			toClose.forEach((filter) => filter.hide());
		});
	}

	add(idx, filter) {
		const th = this.heads[idx];
		th.dataset.filterIndex = idx;
		filter.attach(idx, th, this.update.bind(this));
		this.filters.push(filter);
	}

	update() {
		let enabled = this.filters.filter(f => f.enabled);
		let filtered = this.rows;

		if (enabled.length) {
			enabled.forEach((filter) => {
				filter.prepare();
			});

			filtered = this.rows.filter(function(row) {
				if ('skipFilter' in row.dataset) {
					return true;
				}

				for(let i = 0; i < enabled.length; i++) {
					let filter = enabled[i],
						cell = row.cells[filter.index];

					if (cell === undefined || !filter.filter(cell)) {
						return false;
					}
				}

				return true;
			});
		}

		if (!filtered.length) {
			let holder = document.createElement('table');
			holder.innerHTML = `<tr><th colspan="${this.heads.length}" class="tableFilter--empty">no matching entries found</th>`;
			filtered[0] = holder.firstChild;
		}

		this.tbody.innerHTML = filtered.map((e) => e.outerHTML)
			.join('');

	}
}

export { TableFilter };
export default TableFilter;
