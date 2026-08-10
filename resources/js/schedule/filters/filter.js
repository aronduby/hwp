const classes = {
	holder: 'filter',
	button: {
		base: 'filter-btn'
	},
	filter: {
		base: 'filter',
		enabled: 'filter--enabled',
		disabled: 'filter--disabled'
	},
	panel: {
		base: 'filter-panel',
		content: 'filter-panel-content',
		open: 'filter-panel--open',
		closed: 'filter-panel--closed'
	},
	clear: 'filter-clear'
};

class Filter {

	getPanelContent() {
		return `<input class="form-control" type="text" placeholder="search" />`;
	}

	initPanel(panel) {
		let input = panel.querySelector('input');
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
		this.panelEl.querySelector('input').value = '';
		this.disable();
	}

	// get ready to filter cells, set variables for matching against, etc.
	prepare() {
		this.regex = new RegExp(this.panelEl.querySelector('input').value, 'i');
	}

	// return bool on weather or not the cell passes
	filter(cell) {
		let val = Filter.getCellValue(cell);

		return val.match(this.regex);
	}

	constructor(skipSetup) {
		this.enabled = false;
		this.index = 0;
		this.triggerUpdate = () => {};

		if (skipSetup !== true) {
			this.setup();
		}
	}

	setup() {
		this.holderEl = document.createElement('div');
		this.holderEl.className = [
			classes.filter.base,
			classes.filter.disabled
		].join(' ');

		this.triggerEl = this.createTrigger();
		this.holderEl.appendChild(this.triggerEl);

		this.panelEl = this.createPanel();
		this.holderEl.appendChild(this.panelEl);
	}

	attach(index, el, updater) {
		this.index = index;
		this.holderEl.dataset.filterIndex = index;
		this.triggerUpdate = updater;

		el.appendChild(this.holderEl);
	}

	// enable the filter
	enable() {
		this.enabled = true;
		this.holderEl.classList.remove(classes.filter.disabled);
		this.holderEl.classList.add(classes.filter.enabled);
		this.triggerUpdate();
	}

	// disable the filter
	disable() {
		this.enabled = false;
		this.holderEl.classList.remove(classes.filter.enabled);
		this.holderEl.classList.add(classes.filter.disabled);
		this.triggerUpdate();
	}

	// show the filter panel
	show() {
		let focus;

		this.panelEl.classList.remove(classes.panel.closed);
		this.panelEl.classList.add(classes.panel.open);

		focus = this.panelEl.querySelector('input,select');
		if (focus) {
			focus.focus();
		}
	}

	// hide the filter panel
	hide() {
		this.panelEl.classList.remove(classes.panel.open);
		this.panelEl.classList.add(classes.panel.closed);
	}

	toggle() {
		if (this.panelEl.classList.contains(classes.panel.open)) {
			this.hide();
		} else {
			this.show();
		}
	}

	createTrigger() {
		let trigger =  document.createElement('button');
		trigger.innerHTML = '<i class="fa fa-filter"></i>';
		trigger.className = [
			'btn',
			'btn--small',
			classes.button.base
		].join(' ');

		trigger.addEventListener('click', (e) => this.toggle(e));

		return trigger;
	}

	createPanel() {
		let panel = document.createElement('section');
		let content = this.getPanelContent();
		let markup = `
			<div class="${classes.panel.content}">
				${content}
			</div>
			<button class="btn text--loss ${classes.clear}">clear</button>
		`;

		panel.classList.add(classes.panel.base);
		panel.classList.add(classes.panel.closed);
		panel.innerHTML = markup;

		this.initClear(panel);
		this.initPanel(panel);

		return panel;
	}

	initClear(panel) {
		let btn = panel.querySelector('button.'+classes.clear);
		btn.addEventListener('click', (e) => this.clear(e));
	}

	static getCellValue(cell) {
		return 'filterValue' in cell.dataset ?
			cell.dataset.filterValue :
			cell.textContent.trim();
	}
}

export { Filter, classes };
export default Filter;
