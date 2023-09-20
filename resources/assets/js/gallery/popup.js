/**
 * The type definition that the photo gallery needs the image data mapped to
 *
 * @typedef {Object} ImageData
 * @property {string} src the URL path to the image
 * @property {string} msrc the URL path to the thumb image
 * @property {int} w the width of the image
 * @property {int} h the height of the image
 * @property {int} id the id of the image, used for tracking
 * @property {file} string the file of the image, used for tracking
 */
(function () {
    'use strict';

    global.jQuery = require('jquery');

    const PhotoSwipe = require('photoswipe');
    const PhotoSwipeUI = require('./photoswipe-ui.js');

    const $ = jQuery;

    function trackEvent(type, item) {
        if (ga) {
            ga('send', 'event', 'Photos', type, item.file, item.id);
        }
    }

    function PopupGallery(url, services) {
        this.url = url;
        this.services = services;
    }

    PopupGallery.prototype.load = function (url) {
        const self = this;

        if (!url) {
            url = this.url;
        }

        return $.getJSON(url)
            .done(this.loaded.bind(self))
            .fail(this.error.bind(self));
    };

    PopupGallery.prototype.loaded = function (items) {
        const self = this;
        const pswpElement = document.querySelectorAll('.pswp')[0];

        // noinspection JSValidateTypes
        /**
         * @var {object} gallery.currItem
         */
        const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI, self.services.mapImageData(items), {
            shareButtons: [
                {id: 'download', label: 'Download image', url: '{{raw_image_url}}', download: true, fa: 'fa-download'}
            ],
            getImageURLForShare: function (btn) {
                return self.services.getImageURLForShare(btn, gallery.currItem);
            },
            getFilenameForShare: function () {
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