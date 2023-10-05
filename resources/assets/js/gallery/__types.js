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
 * @typedef {Object} CloudinaryImageData
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
 * @property {?Object} metadata
 *
 * @property {CloudinaryImageServiceData} __service
 */

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

/**
 * Shutterfly image service data
 *
 * @typedef {object} ShutterflyImageServiceData
 * @property {string} key - 'shutterfly'
 * @property {string} domain
 */

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