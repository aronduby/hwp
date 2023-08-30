(function (){
    'use strict';

    const _FullGallery = require('../_full');
    const _PopupGallery = require('../_popup');

    function getImageURLForShare(btn, item) {
        if (btn.download && item.media_id) {

            const url = 'https://im1.shutterfly.com/procgtaserv/';
            let id = item.media_id;

            // this make it the largest size publicly available
            id = id.split('');
            id[35] = 0;
            id = id.join('');

            return url + id;
        } else {
            return item.src;
        }
    }

    class FullGallery extends _FullGallery {
        getImageURLForShare
    }

    class PopupGallery extends _PopupGallery {
        getImageURLForShare
    }

    window.FullGallery = FullGallery;
    window.PopupGaller = PopupGallery;
})();