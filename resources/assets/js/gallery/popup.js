(function () {
    'use strict';

    global.jQuery = require('jquery');

    var PhotoSwipe = require('photoswipe');
    var PhotoSwipeUI = require('./photoswipe-ui.js');
    var idToDownload = require('./shutterflyIdToUrl');

    var $ = jQuery;

    function trackEvent(type, item) {
        if (ga) {
            ga('send', 'event', 'Photos', type, item.file, item.id);
        }
    }

    function PopupGallery(url) {
        this.url = url;
    }

    PopupGallery.prototype.load = function (url) {
        var self = this;

        if (!url) {
            url = this.url;
        }

        return $.getJSON(url)
            .done(this.loaded.bind(self))
            .fail(this.error.bind(self));
    };

    PopupGallery.prototype.loaded = function (items) {
        var pswpElement = document.querySelectorAll('.pswp')[0];
        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI, items, {
            shareButtons: [
                {id: 'download', label: 'Download image', url: '{{raw_image_url}}', download: true, fa: 'fa-download'}
            ],
            getImageURLForShare: function (btn) {
                var item = gallery.currItem;

                if (btn.download && item.media_id) {
                    return idToDownload(item.media_id);
                } else {
                    return item.src;
                }
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

    PopupGallery.prototype.error = function (err) {
        console.error(err);
        alert('Error loading the recent content');
    };

    module.exports = PopupGallery;

})();