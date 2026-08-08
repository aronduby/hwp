import {Filter} from "./filter";

var FlatPickr = require('flatpickr');

class DateFilter extends Filter {

	constructor(options) {
		super(true);

		this.options = options || {};

		this.setup();
	}

	getPanelContent() {
		return `<input class="form-control" type="text" placeholder="search" />`;
	}

	initPanel(panel) {
		let input = panel.querySelector('input');

		this.options.inline = true;
		this.options.mode = 'range';
		this.options.onChange = (selectedDates, dateStr, instance) => {
			if (selectedDates.length == 2) {
				this.enable();
			} else {
				this.disable();
			}

			this.triggerUpdate();
		};

		this.picker = new FlatPickr(input, this.options);
	}

	// clear the filter
	clear() {
		this.picker.clear();
		this.disable();
	}

	// get ready to filter cells, set variables for matching against, etc.
	prepare() {
		this.selected = this.picker.selectedDates;
		this.startTime = this.selected[0].getTime() / 1000 | 0;
		this.endTime = this.selected[1] || this.selected[0];
		this.endTime = new Date(
			this.endTime.getFullYear(),
			this.endTime.getMonth(),
			this.endTime.getDate(),
			23, 59, 59
		).getTime() / 1000 | 0;
	}

	// return bool on weather or not the cell passes
	filter(cell) {
		let val = Filter.getCellValue(cell);

		// console.log(this.startTime, val, this.endTime, this.startTime <= val && val <= this.endTime);

		// return true;

		return this.startTime <= val && val <= this.endTime;
	}
}

export {DateFilter};
export default DateFilter;