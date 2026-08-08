import {CheckboxFilter} from "./checkbox-filter";

class RadioFilter extends CheckboxFilter {

	getInputType() {
		return 'radio';
	}
}

export {RadioFilter};
export default RadioFilter;