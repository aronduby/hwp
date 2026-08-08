import * as shutterfly from './shutterfly';
import * as cloudinary from './cloudinary';

const services = {
    [shutterfly.key]: shutterfly,
    [cloudinary.key]: cloudinary,
}

export default {
    getImageURLForShare(btn, item) {
        return services[item.__service.key].getImageURLForShare(btn, item)
    },

    mapImageData(items) {
        return items.map(item => services[item.__service.key].mapImageData(item));
    }
}