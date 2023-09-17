(function () {
	'use strict';

	global.jQuery = require('jquery');
	require('jquery.loadtemplate');

	var PhotoSwipe = require('photoswipe');
	var PhotoSwipeUI = require('./photoswipe-ui.js');

	var $ = jQuery;
	var imgTmpl = $('#gallery-thumb-tmpl');
	var emptyTmpl = $('#gallery-no-photos-found-tmpl');
	var btnTmpl = $('#load-more-btn');

	function trackEvent(type, item) {
		if (ga) {
			ga('send', 'event', 'Photos', type, item.file, item.id);
		}
	}

	function _FullGallery(el) {
		this.el = $(el);
		this.btn = $(btnTmpl.text());
		this.gallery = null;
		this.items = null;
		this.perPage = 48;
		this.page = 1;
		this.totalPages = 1;
		this.offset = 0;

		this.attachEvents();
		this.load(this.el.data('gallery-path'));
	}

	_FullGallery.prototype.attachEvents = function() {
		var self = this;

		this.el.on('click', 'a.gallery-photo--thumb', this.imageClick.bind(self));
		this.btn.on('click', this.loadMore.bind(self));
	};

	_FullGallery.prototype.load = function(url) {
		var self = this;

		$.getJSON(url)
			.done(this.loaded.bind(self))
			.fail(this.error.bind(self));
	};

	_FullGallery.prototype.loaded = function(items) {
		this.items = this.mapImageData(items);
		this.totalPages = Math.ceil(items.length / this.perPage);

		this.el.empty();
		this.drawPage();
		if (this.totalPages > 1) {
			this.btn.insertAfter(this.el);
		}
	};

	_FullGallery.prototype.error = function(err) {
		alert('Could not load gallery');
		console.log(err);
		this.el.empty();
	};

	_FullGallery.prototype.imageClick = function(e) {
		var item = $(e.target).parent('[data-offset]');
		var offset = parseInt(item.attr('data-offset'), 10);
		var self = this;

		var pswpElement = document.querySelectorAll('.pswp')[0];
		this.gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI, this.items, {
			index: offset,
			shareButtons: [
				{
					id: 'download',
					label: 'Download image',
					url: '{{raw_image_url}}',
					download: true,
					fa: 'fa-download'
				}
			],
			getImageURLForShare: function(btn) {
				return self.getImageURLForShare(btn, self.gallery.currItem);
			  },
			getFilenameForShare: function(btn) {
				return self.gallery.currItem.file + '.jpg';
			}
		});

		this.gallery.listen('afterChange', function() {
			trackEvent('View', this.currItem);
		});

		this.gallery.init();
		return false;
	};

	/**
	 * Take the items returned from the ajax request and maps them into the fields that are required, as outlined below
	 * @param items
	 * @returns ImageData[]
	 */
	_FullGallery.prototype.mapImageData = function(items) {
		console.log('did you intentionally not override the mapImageData method?');
		return items;
	};

	_FullGallery.prototype.getImageURLForShare = function(btn, item) {
		console.error('You forgot to override getImageURLForShare');
	};

	_FullGallery.prototype.drawPage = function() {
		var self = this;

		if (this.items.length) {
			this.el.loadTemplate(imgTmpl, this.items, {
				beforeInsert: self.addOffset.bind(self),
				paged: true,
				elemPerPage: this.perPage,
				append: true,
				pageNo: this.page
			});
		} else {
			this.el.loadTemplate(emptyTmpl);
		}

		if (this.perPage * this.page >= this.items.length) {
			this.btn.remove();
		}
	};

	_FullGallery.prototype.loadMore = function(e) {
		this.page++;
		this.drawPage();
	};

	_FullGallery.prototype.addOffset = function(el) {
		el.attr('data-offset', this.offset);
		this.offset++;
	};
	
	module.exports = _FullGallery;

})();