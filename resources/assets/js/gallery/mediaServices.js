/**
 * @typedef {object} MediaService
 * @property {string} key
 */
/**
 * @method
 * @name MediaService#getImageURLForShare
 * @param {object} btn
 * @param {object} item
 * @returns {string}
 */
/**
 * @method
 * @name MediaService#mapImageData
 * @param {object} item
 * @returns {object}
 */

(function() {
    'use strict';

    const shutterfly = require('./shutterfly');
    const cloudinary = require('./cloudinary');

    const services = {
        [shutterfly.key]: shutterfly,
        [cloudinary.key]: cloudinary,
    }

    module.exports = {
        getImageURLForShare(btn, item) {
            return services[item.__service.key].getImageURLForShare(btn, item)
        },

        mapImageData(items) {
            return items.map(item => services[item.__service.key].mapImageData(item));
        }
    }
})();