(function () {
    'use strict';

    global.jQuery = require('jquery');

    var PhotoSwipe = require('photoswipe');
    var PhotoSwipeUI = require('./photoswipe-ui.js');

    var $ = jQuery;

    function trackEvent(type, item) {
        if (ga) {
            ga('send', 'event', 'Photos', type, item.file, item.id);
        }
    }

    function _PopupGallery(url) {
        this.url = url;
    }

    _PopupGallery.prototype.load = function (url) {
        var self = this;

        if (!url) {
            url = this.url;
        }

        return $.getJSON(url)
            .done(this.loaded.bind(self))
            .fail(this.error.bind(self));
    };

    _PopupGallery.prototype.loaded = function (items) {
        const self = this;
        var pswpElement = document.querySelectorAll('.pswp')[0];
        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI, items, {
            shareButtons: [
                {id: 'download', label: 'Download image', url: '{{raw_image_url}}', download: true, fa: 'fa-download'}
            ],
            getImageURLForShare: function (btn) {
                return self.getImageURLForShare(btn, self.gallery.currItem);
            },
            getFilenameForShare: function (btn) {
                return gallery.currItem.file + '.jpg';
            }
        });

        gallery.listen('afterChange', function() {
            trackEvent('View', this.currItem);
        });
        gallery.init();

        return gallery;
    };

    _PopupGallery.prototype.getImageURLForShare = function(btn, item) {
        console.error('You forgot to override getImageURLForShare');
    };

    _PopupGallery.prototype.error = function (err) {
        console.error(err);
        alert('Error loading the recent content');
    };

    module.exports = _PopupGallery;

})();