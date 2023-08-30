import TableFilter from './filters/table-filter';
import Filter from './filters/filter';
import SelectFilter from './filters/select-filter';
import CheckboxFilter from './filters/checkbox-filter';
import RadioFilter from './filters/radio-filter';
import DateFilter from './filters/date-filter';

export default function filterableInit() {
	window.addEventListener('DOMContentLoaded', function () {

		var table = document.querySelector('table.schedule');
		var tableFilter = new TableFilter(table);

		tableFilter.add(0, new CheckboxFilter([
			{value: 'tournament', label: 'Tournament'},
			{value: 'tournament-game', label: 'Tournament Game'},
			{value: 'game', label: 'Game'}
		]));
		tableFilter.add(1, new DateFilter());
		tableFilter.add(2, new RadioFilter([
			{value: 'V', label: 'Varsity'},
			{value: 'JV', label: 'JV'}
		]));
		tableFilter.add(3, new Filter());
		tableFilter.add(4, new Filter());
		tableFilter.add(5, new CheckboxFilter([
			{value: 'win', label: 'Win'},
			{value: 'loss', label: 'Loss'},
			{value: 'tie', label: 'Tied'}
		]));

	});
}