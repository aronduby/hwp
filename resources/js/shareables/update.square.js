import BackgroundWithStripe from './parts/backgroundWithStripe';
import * as logo from './parts/logo';
import message from './parts/updateMessage';
import meta from './parts/updateMeta';

export default function draw(data, defs, trigger) {
    return new Promise(function (resolve, reject) {

        const canvas = defs.canvas;
        const padding = defs.padding;
        const update = JSON.parse(
            trigger.closest('.update').querySelector('.json').textContent
        );

        // Background
        // bg and stripe height is relative to the update
        const bg = new BackgroundWithStripe(data.photo, defs);
        canvas.add(bg);

        // Logo
        logo.build(logo.BOTTOM, defs)
            .then(function (img) {
                img.set({
                    top: canvas.height - img.height
                });

                canvas.add(img);
            });

        // Message
        const msg = message(update.msg, defs);
        msg.set({
            top: (canvas.height / 2) - 68,
            left: canvas.width / 2
        });
        canvas.add(msg);

        // bounds needed for stripe and the meta
        const msgBounding = msg.getBoundingRect();

        // update the stripe bg
        bg.stripe.height = msgBounding.height + padding;
        bg.stripe.top = msgBounding.top - padding / 2;

        // Meta Info
        const metaInfo = meta(update, defs);
        metaInfo.set({
            top: msgBounding.top + msgBounding.height + metaInfo.height * 1.5,
            left: canvas.width / 2
        });
        canvas.add(metaInfo);

    });
}
