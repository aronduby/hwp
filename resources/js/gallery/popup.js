import PhotoSwipe from 'photoswipe';
import PhotoSwipeUI from './photoswipe-ui';

function trackEvent(type, item) {
    if (typeof ga === 'function') {
        ga('send', 'event', 'Photos', type, item.file, item.id);
    }
}

export default class PopupGallery {
    constructor(url, services) {
        this.url = url;
        this.services = services;
    }

    load(url = this.url) {
        return fetch(url)
            .then(rsp => rsp.json())
            .then(this.loaded.bind(this))
            .catch(this.error.bind(this));
    }

    loaded(items) {
        const pswpElement = document.querySelectorAll('.pswp')[0];

        // noinspection JSValidateTypes
        /**
         * @var {object} gallery.currItem
         */
        const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI, this.services.mapImageData(items), {
            shareButtons: [
                {id: 'download', label: 'Download image', url: '{{raw_image_url}}', download: true, fa: 'fa-download'}
            ],
            getImageURLForShare: (btn) => this.services.getImageURLForShare(btn, gallery.currItem),
            getFilenameForShare: () => `${gallery.currItem.file}.jpg`
        });

        gallery.listen('afterChange', function () {
            trackEvent('View', this.currItem);
        });
        gallery.init();

        return gallery;
    }

    error(err) {
        console.error(err);
        alert('Error loading the recent content');
    }
}
