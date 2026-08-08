import 'jquery.loadtemplate';
import PhotoSwipe from 'photoswipe';
import PhotoSwipeUI from './photoswipe-ui';

const $ = jQuery;
const imgTmpl = $('#gallery-thumb-tmpl');
const emptyTmpl = $('#gallery-no-photos-found-tmpl');
const btnTmpl = $('#load-more-btn');

function trackEvent(type, item) {
    if (ga) {
        ga('send', 'event', 'Photos', type, item.file, item.id);
    }
}

function FullGallery(el, services) {
    this.el = $(el);
    this.btn = $(btnTmpl.text());
    this.gallery = null;
    this.items = null;
    this.perPage = 48;
    this.page = 1;
    this.totalPages = 1;
    this.offset = 0;
    this.services = services;

    this.attachEvents();
    this.load(this.el.data('gallery-path'));
}

FullGallery.prototype.attachEvents = function () {
    const self = this;

    this.el.on('click', 'a.gallery-photo--thumb', this.imageClick.bind(self));
    this.btn.on('click', this.loadMore.bind(self));
};

FullGallery.prototype.load = function (url) {
    const self = this;

    $.getJSON(url)
        .done(this.loaded.bind(self))
        .fail(this.error.bind(self));
};

FullGallery.prototype.loaded = function (items) {
    this.items = this.services.mapImageData(items);
    this.totalPages = Math.ceil(items.length / this.perPage);

    this.el.empty();
    this.drawPage();
    if (this.totalPages > 1) {
        this.btn.insertAfter(this.el);
    }
};

FullGallery.prototype.error = function (err) {
    alert('Could not load gallery');
    console.log(err);
    this.el.empty();
};

FullGallery.prototype.imageClick = function (e) {
    const item = $(e.target).parent('[data-offset]');
    const offset = parseInt(item.attr('data-offset'), 10);
    const self = this;

    const pswpElement = document.querySelectorAll('.pswp')[0];
    // noinspection JSValidateTypes
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
        getImageURLForShare: function (btn) {
            return self.services.getImageURLForShare(btn, self.gallery.currItem);
        },
        getFilenameForShare: function () {
            return self.gallery.currItem.file + '.jpg';
        }
    });

    this.gallery.listen('afterChange', function () {
        trackEvent('View', this.currItem);
    });

    this.gallery.init();
    return false;
};

FullGallery.prototype.drawPage = function () {
    const self = this;

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

FullGallery.prototype.loadMore = function () {
    this.page++;
    this.drawPage();
};

FullGallery.prototype.addOffset = function (el) {
    el.attr('data-offset', this.offset);
    this.offset++;
};

export default FullGallery;