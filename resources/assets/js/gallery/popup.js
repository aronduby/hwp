import PhotoSwipe from 'photoswipe';
import PhotoSwipeUI from './photoswipe-ui';

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

    gallery.listen('afterChange', function () {
        trackEvent('View', this.currItem);
    });
    gallery.init();

    return gallery;
};

PopupGallery.prototype.error = function (err) {
    console.error(err);
    alert('Error loading the recent content');
};

export default PopupGallery;