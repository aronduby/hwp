// noinspection SpellCheckingInspection

/**
 * Cloudinary image service data
 *
 * @typedef {object} CloudinaryImageServiceData *
 * @property {string} key - 'cloudinary'
 * @property {string} cloudName
 */

/**
 * Cloudinary User Data that is a part of the image data
 *
 * @typedef {Object} CloudinaryUserData
 * @property {string} access_key
 * @property {string} custom_id email address
 * @property {string} external_id
 */

/**
 * Cloudinary Image Data
 *
 * @typedef {Object} CloundaryImageData
 * @property {string} asset_id "80d6ba97ed509c7092d2cbe90995865a"
 * @property {string} public_id "23-24/Test Subfolder/photo-1693560332243-4e6c9cf23f7f_kygmob",
 * @property {string} folder "23-24/Test Subfolder",
 * @property {string} filename "photo-1693560332243-4e6c9cf23f7f_kygmob",
 * @property {string} format "jpg",
 * @property {int} version 1694138823,
 * @property {'image'|'video'} resource_type "image",
 * @property {'upload'} type "upload",
 * @property {string} created_at "2023-09-08T02:07:03+00:00",
 * @property {string} uploaded_at "2023-09-08T02:07:03+00:00",
 * @property {int} bytes 1426719,
 * @property {int} backup_bytes 0,
 * @property {int} width 3600,
 * @property {int} height 2400,
 * @property {number} aspect_ratio 1.5,
 * @property {int} pixels 8640000,
 * @property {string[]} tags [ ],
 * @property {string} url "http://res.cloudinary.com/dvwbixlxj/image/upload/v1694138823/23-24/Test%20Subfolder/photo-1693560332243-4e6c9cf23f7f_kygmob.jpg",
 * @property {string} secure_url "https://res.cloudinary.com/dvwbixlxj/image/upload/v1694138823/23-24/Test%20Subfolder/photo-1693560332243-4e6c9cf23f7f_kygmob.jpg",
 * @property {'active'} status "active",
 * @property {'public'} access_mode "public",
 * @property {null} access_control null,
 * @property {string} etag "22227ba3132aba18a4a210edafe67075",
 * @property {CloudinaryUserData} created_by
 * @property {CloudinaryUserData} uploaded_by
 *
 * @property {CloudinaryImageServiceData} __service
 */

/**
 * Cloudinary image URL format
 * <CLOUDINARY_DOMAIN><CLOUDINARY_CLOUD_NAME>/<asset_type>/<delivery_type>/<transformations>/<version>/<public_id_full_path>.<extension>
 */
(function (){
    'use strict';

    const THUMB_TRANSFORM = 't_media_lib_thumb';

    /**
     * Get the URL for the download button
     *
     * @param btn
     * @param item
     * @returns {string|*}
     */
    function getImageURLForShare(btn, item) {
        if (btn.download) {
            return item.secure_url;
        }
    }

    /**
     * Map from cloudinary specific data to gallery specific
     *
     * @param {CloundaryImageData} item
     * @returns {any & ImageData}
     */
    function mapImageData(item) {
        return {
            ...item,
            w: item.width,
            h: item.height,
            src: item.secure_url,
            msrc: `https://res.cloudinary.com/${item.__service.cloudName}/${item.resource_type}/${item.type}/${THUMB_TRANSFORM}/v${item.version}/${item.public_id}.${item.format}`,
            file: item.filename,
            id: item.public_id,
        };
    }

    module.exports = {
        key: 'cloudinary',
        getImageURLForShare,
        mapImageData
    };

})();