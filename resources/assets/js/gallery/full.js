(function () {
	'use strict';

	global.jQuery = require('jquery');
	require('jquery.loadtemplate');

	var PhotoSwipe = require('photoswipe');
	var PhotoSwipeUI = require('./photoswipe-ui.js');
	var idToDownload = require('./shutterflyIdToUrl');

	var $ = jQuery;
	var imgTmpl = $('#gallery-thumb-tmpl');
	var emptyTmpl = $('#gallery-no-photos-found-tmpl');
	var btnTmpl = $('#load-more-btn');

	function trackEvent(type, item) {
		if (ga) {
			ga('send', 'event', 'Photos', type, item.file, item.id);
		}
	}

	function FullGallery(el) {
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

	FullGallery.prototype.attachEvents = function() {
		var self = this;

		this.el.on('click', 'a.gallery-photo--thumb', this.imageClick.bind(self));
		this.btn.on('click', this.loadMore.bind(self));
	};

	FullGallery.prototype.load = function(url) {
		var self = this;

		$.getJSON(url)
			.done(this.loaded.bind(self))
			.fail(this.error.bind(self));
	};

	FullGallery.prototype.loaded = function(items) {
		this.items = items;
		this.totalPages = Math.ceil(items.length / this.perPage);

		this.el.empty();
		this.drawPage();
		if (this.totalPages > 1) {
			this.btn.insertAfter(this.el);
		}
	};

	FullGallery.prototype.error = function(err) {
		alert('Could not load gallery');
		console.log(err);
		this.el.empty();
	};

	FullGallery.prototype.imageClick = function(e) {
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
				var item = self.gallery.currItem;

				if (btn.download && item.media_id) {
					return idToDownload(item.media_id);
				} else {
					return item.src;
				}
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

	FullGallery.prototype.drawPage = function() {
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

	FullGallery.prototype.loadMore = function(e) {
		this.page++;
		this.drawPage();
	};

	FullGallery.prototype.addOffset = function(el) {
		el.attr('data-offset', this.offset);
		this.offset++;
	};
	
	module.exports = FullGallery;

})();