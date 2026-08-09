import { fabric } from 'fabric';
import Deferred from '@/deferred';

export default function badge(badgeData, showTitle, defs) {
    const d = new Deferred();
    defs.promises.push(d.promise);

    const group = new fabric.Group([], {});

    if (showTitle) {
        const title = new fabric.Text(badgeData.title.toUpperCase(), {
            fontFamily: 'League Gothic',
            fill: '#fff',
            fontSize: 40,
            top: 60,
            left: 122,
        });

        group.addWithUpdate(title);
    }

    fabric.Image.fromURL(`/badges/${badgeData.image}`, function (img) {
        img.set({
            top: 0,
            left: 0,
            width: 130,
            height: 147
        });

        group.addWithUpdate(img);
        d.resolve();
    });

    return group;
}
