(function () {
	'use strict';

	var vex = require('vex-js');

	global.jQuery = require('jquery');
	var $ = jQuery;

	vex.defaultOptions.className = 'vex-theme-default';

	$(document).ready(function() {
		$(document).on('click', '[data-note-id]', function() {
			var self = $(this);
			var id = $(this).data('note-id');

			$(this).addClass('loading');
			$.get('/notes/' + id)
				.done(function(rsp) {
					vex.open({
						unsafeContent: rsp,
						className: 'vex-theme-note'
					});

					ga('send', 'event', 'notes', 'view', id);
				})
				.fail(function(err) {
					console.error(err);
					alert('could not load note');
				})
				.always(function() {
					self.removeClass('loading');
				});

			return false;
		});
	});

})();