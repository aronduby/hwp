import PhotoSwipe from 'photoswipe';
import PhotoSwipeUI from './photoswipe-ui';
import { template } from "lodash";

function trackEvent(type, item) {
    // noinspection JSUnresolvedReference
    if (ga) {
        // noinspection JSUnresolvedReference
        ga('send', 'event', 'Photos', type, item.file, item.id);
    }
}

/** @type HTMLTemplateElement */
const imgTmpl = document.getElementById('gallery-thumb-tmpl');
const thumbnailTemplate = template(imgTmpl.innerHTML);

/** @type HTMLTemplateElement */
const emptyTmpl = document.getElementById('gallery-no-photos-found-tmpl');

/** @type HTMLTemplateElement */
const btnTmpl = document.getElementById('load-more-btn');

export default class FullGallery {

    constructor(el, services) {

        /** @var {HTMLElement} */
        this.el = el;

        /** @var {HTMLElement} */
        this.btn = btnTmpl.content.firstElementChild;

        /** @type ImageData[] */
        this.items = null;

        this.gallery = null;
        this.perPage = 48;
        this.page = 1;
        this.totalPages = 1;
        this.offset = 0;
        this.services = services;

        this.attachEvents();
        this.load(this.el.dataset.galleryPath);
    }

    attachEvents() {
        // delegated click listener for thumbnail images
        this.el.addEventListener('click', (e) => {
            const target = e.target.closest('a.gallery-photo--thumb');
            if (target) {
                this.imageClick(target);
            }
        });

        this.btn.addEventListener('click', (e) => this.loadMore(e));
    }

    /**
     * When a thumbnail image is clicked
     *
     * @param {HTMLElement} target
     * @return {boolean}
     */
    imageClick(target) {
        const item = target.closest('[data-offset]');
        const offset = parseInt(item.attr('data-offset'), 10);
        const self = this;

        const photoSwipeElement = document.querySelectorAll('.pswp')[0];
        // noinspection JSValidateTypes
        this.gallery = new PhotoSwipe(photoSwipeElement, PhotoSwipeUI, this.items, {
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
            getImageURLForShare: function (btn) {
                return self.services.getImageURLForShare(btn, self.gallery.currItem);
            },
            getFilenameForShare: function () {
                return `${self.gallery.currItem.file}.jpg`;
            }
        });

        this.gallery.listen('afterChange', function () {
            trackEvent('View', this.currItem);
        });

        this.gallery.init();
        return false;
    }

    /**
     * Loads the JSON for the gallery data
     * @param {string} url
     */
    load(url) {
        fetch(url)
            .then(rsp => rsp.json())
            .then((json) => this.loaded(json))
            .catch((err) => this.error(err));
    }

    /**
     * Item data has loaded
     * @param {ImageData[]} items
     */
    loaded(items) {
        this.items = this.services.mapImageData(items);
        this.totalPages = Math.ceil(items.length / this.perPage);

        this.el.replaceChildren();
        this.drawPage();
        if (this.totalPages > 1) {
            this.el.after(this.btn);
        }
    }

    /**
     * Error loading the item data
     * @param err
     */
    error(err) {
        alert('Could not load gallery');
        console.error(err);
        this.el.replaceChildren();
    }

    /**
     * Draws the current page of thumbnails
     */
    drawPage() {
        if (this.items.length) {
            // all the items are loaded, but we're only drawing a single page at a time
            // a little bit weird, but it's a set number of items, and the data is light to load compared to the images
            // so this saves the big bandwidth, and still allows getting to the content below the gallery

            // page is 1 based, slice is 0 based
            const pageStart = (this.perPage * (this.page - 1));
            // slice end is exclusive, so add one
            const pageEnd = pageStart + this.perPage + 1;
            const pageItems = this.items.slice(pageStart, pageEnd);

            const newContent = pageItems.reduce((acc, item) => {
                // noinspection JSUnusedAssignment
                return acc += thumbnailTemplate({
                    ...item,
                    offset: this.offset
                });
            }, '');

            this.el.insertAdjacentHTML('beforeend', newContent);
        } else {
            this.el.replaceChildren(emptyTmpl.content);
        }

        if (this.perPage * this.page >= this.items.length) {
            this.btn.remove();
        }
    }

    /**
     * Increments draws the next page
     */
    loadMore() {
        this.page++;
        this.drawPage();
    };
}
