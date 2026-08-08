(function () {
	'use strict';

	global.jQuery = require('jquery');
	var $ = jQuery;
	var _ = require('lodash');

	/**
	 * ADD/REMOVE STAT ROWS
 	 */
	(function($, document) {
		$(document).ready(function() {

			$('button.add-row').on('click', function() {
				var tbody = $(this).parents('section').find('tbody');
				var newRow = tbody.find('tr:last-child').clone();
				var oldI = newRow.data('i');
				var newI = oldI + 1;

				newRow.attr('data-i', newI);

				newRow.find('input, select').each(function() {
					var name = $(this).attr('name');
					name = name.replace(oldI, newI);
					$(this).attr('name', name);

					$(this).val('');
				});

				newRow.appendTo(tbody);

				return false;
			});

			$(document).on('click', 'button.remove-row', function() {
				var row = $(this).parents('tr');
				removeRow(row);

				return false;
			});

		});

		function emptyInputs(parent) {
			parent.find('input, select').each(function() {
				$(this).val('');
			});
		}

		function removeRow(row) {
			// trigger change to update the totals row
			row.find('input[type="number"]').each(function() {
				$(this).val('').trigger('input');
            });

			if (row.is(':only-child')) {
				emptyInputs(row);

			} else {
				row.remove();
			}
		}
	})($, document);

	/**
	 * Autogenerate Scores
	 */
	(function($, document, _) {
		var autogenerateScore = true;
		var keys = ['score_us', 'score_them'];
		var values = {};

		$('input.autogenerate-score-toggle').on('change', function() {
			autogenerateScore = this.checked;

			$('[data-autogenerate-score-status]')
				.attr('data-autogenerate-score-status', autogenerateScore ? 'on' : 'off');

			// turning on should update everything
			if (autogenerateScore) {
				_.forEach(keys, function(key) {
					updateValuesForKey(key);
					updateDisplayForKey(key);
				});

				updateScoreHeaders();
			}
		}).change();

		$(document).on('input', 'input[data-autogenerate-score-source]', function() {
			if (autogenerateScore) {
				var key = $(this).attr('data-autogenerate-score-source');

				updateValuesForKey(key);
				updateDisplayForKey(key);
				updateScoreHeaders();
			}
		});

		$('input.game-score').on('input', function() {
			var key = $(this).attr('data-autogenerate-score-value');

			values[key] = $(this).val();

			updateScoreHeaders();
		});

		function updateValuesForKey(key) {
			values[key] = $('input[data-autogenerate-score-source="' + key + '"]')
				.map(function() {
					return Number($(this).val());
				})
				.toArray()
				.reduce(function(acc, val) {
					return acc + val;
				}, 0);
		}

		function updateDisplayForKey(key) {
			var val = values[key];
			$('[data-autogenerate-score-value="' + key + '"]')
				.each(function() {
					if ($(this).is('input, select')) {
						$(this).val(val)
					} else {
						$(this).text(val);
					}
				});
		}

		function updateScoreHeaders() {
			var us, them;

			if (values.score_us > values.score_them) {
				us = "win";
				them = "loss";
			} else if (values.score_us < values.score_them) {
				us = "loss";
				them = "win";
			} else {
				us = them = "tie";
			}

			$('.result--us').attr('data-result', us);
			$('.result--them').attr('data-result', them);
		}

	})($, document, _);

    /**
	 * Autogenerate totals at the bottom of the table
     */
	(function($, document, _) {
		$('.stats-table--hasTotals').on('input', 'td input[type="number"]', function() {
			var td = this.parentElement;
			var tdIdx = [...td.parentElement.children].indexOf(td);

			var $table = $(this).parents('table').eq(0);
			var sum = 0;
            $table.find('tbody td:nth-child('+ (tdIdx + 1) +') input')
				.each(function() {
					var val = parseInt($(this).val(), 10);
            		if (!isNaN(val)) {
            			sum += val;
					}
				});

            $table.find('tfoot td:nth-child('+ (tdIdx + 1) +')')
				.text(sum ? sum : '');
		});

		// trigger the handler to catch prefilled forms
        jQuery('.stats-table--hasTotals tr:first-child td input[type="number"]').trigger('input');
	})($, document, _);

})();