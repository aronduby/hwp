import {Filter} from "./filter";

class CheckboxFilter extends Filter {

	constructor(options) {
		super(true);

		this.options = options || [];

		this.setup();
	}

	getInputType() {
		return 'checkbox';
	}

	static getInputName() {
		return '_' + Math.random().toString(36).substr(2, 9);
	}

	getPanelContent() {
		let type = this.getInputType();
		let name = CheckboxFilter.getInputName();
		let opts = this.options
			.map((o) => `
				<li class="list-group-item">
					<label><input type="${type}" name="${name}" value="${o.value}">${o.label}</label>
				</li>
			`)
			.join('');

		return `<ul class="list-group">${opts}</ul>`;
	}

	initPanel(panel) {
		panel.querySelectorAll('input').forEach((el) => {
			el.addEventListener('change', () => {
				let checked = document.querySelectorAll('input:checked');

				if (checked.length) {
					this.enable();
				} else {
					this.disable();
				}

				this.triggerUpdate();
			});
		});
	}

	// clear the filter
	clear() {
		this.panelEl.querySelectorAll('input:checked').forEach((e) => e.checked = false);
		this.disable();
	}

	// get ready to filter cells, set variables for matching against, etc.
	prepare() {
		let checked = this.panelEl.querySelectorAll('input:checked');
		checked = [].slice.call(checked);
		checked = checked.map((e) => e.value);

		this.selected = checked;
	}

	// return bool on weather or not the cell passes
	filter(cell) {
		let val = Filter.getCellValue(cell);

		return this.selected.includes(val);
	}
}

export {CheckboxFilter};
export default CheckboxFilter;