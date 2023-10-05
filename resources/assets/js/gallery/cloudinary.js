// noinspection SpellCheckingInspection

/**
 * Cloudinary image URL format
 * <CLOUDINARY_DOMAIN><CLOUDINARY_CLOUD_NAME>/<asset_type>/<delivery_type>/<transformations>/<version>/<public_id_full_path>.<extension>
 */

export const key = 'cloudinary';

const THUMB_TRANSFORM = 't_media_lib_thumb';
const DPR_AUTO = 'dpr_auto';
const W_AUTO = 'w_auto';

/**
 * Get the URL for the download button
 *
 * @param btn
 * @param item
 * @returns {string|*}
 */
export function getImageURLForShare(btn, item) {
    if (btn.download) {
        return item.secure_url;
    }
}

/**
 * Map from cloudinary specific data to gallery specific
 *
 * @param {CloudinaryImageData} item
 * @returns {any & ImageData}
 */
export function mapImageData(item) {
    return {
        ...item,
        w: item.width,
        h: item.height,
        src: toUrl([DPR_AUTO, W_AUTO], item),
        msrc: toUrl([THUMB_TRANSFORM], item),
        file: item.filename,
        id: item.public_id,
        // title: item.metadata.title || null,
        title: item.metadata ? item.metadata.title || null : null,
    };
}

/**
 *
 * @param {(string|string[])[]} transforms
 * @param {CloudinaryImageData} item
 * @return {string}
 */
function toUrl(transforms, item) {
    const transformStr = transforms.reduce((acc, transformation) => {
        if (Array.isArray(transformation)) {
            acc.push(transformation.join(','));
        } else {
            acc.push(transformation);
        }
        return acc;
    }, []).join('/');

    return `https://res.cloudinary.com/${item.__service.cloudName}/${item.resource_type}/${item.type}/${transformStr}/v${item.version}/${item.public_id}.${item.format}`
}