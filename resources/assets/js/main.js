// noinspection JSUnusedLocalSymbols

(function () {
	'use strict';

	const _ = require('lodash');
	const mlPushMenu = require('./mlpushmenu');
	const matchMenuHeight = _.debounce(require('./matchMenuHeight'), 300);
	const mediaServices = require('./gallery/mediaServices');
	const PopupGallery = require('./gallery/popup');
	const FullGallery = require('./gallery/full');
	const Note = require('./note');
	const shareable = require('./shareables');
	const seasonSwitching = require('./seasonSwitching');

	global.jQuery = require('jquery');
	const $ = jQuery;


	// noinspection JSPotentiallyInvalidConstructorUsage
	new mlPushMenu(document.getElementById('mp-menu'), document.getElementById('trigger'));

	window.onresize = matchMenuHeight;
	document.addEventListener('DOMContentLoaded', matchMenuHeight);

	// popup galleries
	$(document).ready(function () {
		$('body').on('click', '.popup-gallery', function () {
			const url = $(this).data('gallery-path');
			const el = $(this);
			const gallery = new PopupGallery(url, mediaServices);

			el.addClass('loading');
			gallery.load()
				.always(function () {
					el.removeClass('loading');
				});

			return false;
		});
	});

	// full galleries
	$(document).ready(function () {
		$('.full-gallery').each(function (el) {
			$(this).data.gallery = new FullGallery(this, mediaServices);
		});
	});

})();