/**
 * Shutterfly image service data
 *
 * @typedef {object} ShutterflyImageServiceData
 * @property {string} key - 'shutterfly'
 * @property {string} domain
 */

(function (){
    'use strict';

    /**
     *
     * @param btn
     * @param {object} item
     * @param {int} item.media_id
     * @param {string} item.src
     * @returns {*|string}
     */
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

    function mapImageData(item) {
        return {
            ...item,
            w: item.width,
            h: item.height,
            src: `${item.__service.domain}/${item.file}.jpg`,
            msrc: `${item.__service.domain}/thumbs/${item.file}.jpg`,
        };
    }

    module.exports = {
        key: 'shutterfly',
        getImageURLForShare,
        mapImageData
    };
})();