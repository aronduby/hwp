import { fabric } from 'fabric';
import Deferred from '@/deferred';

const path = '/images/shareables/';

export function build(position, defs) {
    const d = new Deferred();
    defs.promises.push(d.promise);

    fabric.Image.fromURL(`${path}${position}`, function (img) {
        d.resolve(img);
    });

    return d.promise;
};

export const BOTTOM = 'logo-url-bottom.png';
export const TOP = 'logo-url-top.png';
export const STACKED = 'logo-url-stacked.png';
