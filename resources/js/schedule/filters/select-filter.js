import {Filter} from "./filter";

class SelectFilter extends Filter {

	constructor(options) {
		super(true);

		this.options = options || [];

		this.setup();
	}

	getPanelContent() {
		let opts = '<option></option>';
		opts += this.options.map((o) => `<option value="${o.value}">${o.label}</option>`).join('');

		return `<select class="form-control">${opts}</select>`;
	}

	initPanel(panel) {
		let input = panel.querySelector('select');
		input.addEventListener('input', (e) => {
			if (!!e.target.value) {
				this.enable();
			} else {
				this.disable()
			}

			this.triggerUpdate();
		});
	}

	// clear the filter
	clear() {
		this.panelEl.querySelector('select').value = '';
		this.disable();
	}

	// get ready to filter cells, set variables for matching against, etc.
	prepare() {
		this.val = this.panelEl.querySelector('select').value;
	}

	// return bool on weather or not the cell passes
	filter(cell) {
		let val = Filter.getCellValue(cell);

		return val.toUpperCase() == this.val.toUpperCase();
	}
}

export {SelectFilter};
export default SelectFilter;