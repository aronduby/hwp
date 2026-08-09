import BackgroundWithStripe from './parts/backgroundWithStripe';
import * as logo from './parts/logo';
import scores from './parts/scores';
import badge from './parts/badge';

export default function draw(data, defs) {
    return new Promise(function (resolve, reject) {

        const canvas = defs.canvas;

        const bg = new BackgroundWithStripe(data.photo, defs);
        bg.stripe.top = 338;
        canvas.add(bg);

        logo.build(logo.BOTTOM, defs)
            .then(function (img) {
                img.set({
                    top: 1080 - img.height,
                    left: 0
                });

                canvas.add(img);
            });

        const scoreGroup = scores(data.game, defs);
        scoreGroup.set({
            top: 459,
            left: canvas.width / 2
        });
        canvas.add(scoreGroup);

        if (data.game.badge) {
            const badgeGroup = badge(data.game.badge, true, defs);
            badgeGroup.set({
                transformMatrix: [1, 0, 0, 1, 125, 598]
            });
            canvas.add(badgeGroup);
        }

    });
}
