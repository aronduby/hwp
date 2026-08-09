import { fabric } from 'fabric';
import BackgroundWithStripe from './parts/backgroundWithStripe';
import * as logo from './parts/logo';
import scores from './parts/scores';
import badge from './parts/badge';
import stat from './parts/stat';

export default function draw(data, defs) {
    return new Promise(function (resolve, reject) {

        const canvas = defs.canvas;

        const bg = new BackgroundWithStripe(data.photo, defs);
        bg.stripe.top = 338;
        bg.stripe.height = 172;
        canvas.add(bg);

        logo.build(logo.TOP, defs)
            .then(function (img) {
                img.set({
                    top: 0,
                    left: 0
                });

                canvas.add(img);
            });

        const scoreGroup = scores(data.game, defs);
        scoreGroup.set({
            scaleX: 0.8399,
            scaleY: 0.8399,
            top: 420,
            left: defs.canvas.width / 2
        });
        canvas.add(scoreGroup);

        if (data.game.badge) {
            const badgeGroup = badge(data.game.badge, true, defs);
            badgeGroup.set({
                transformMatrix: [.84, 0, 0, .84, 153, 527]
            });
            canvas.add(badgeGroup);
        }

        if (data.charts.length) {
            const gradientBG = new fabric.Rect({
                left: 0,
                top: defs.canvas.height - 258,
                height: 258,
                width: defs.canvas.width
            });

            defs.gradients.blueTransBottom.coords.y2 = gradientBG.height;
            gradientBG.set('fill', defs.gradients.blueTransBottom);

            defs.canvas.add(gradientBG);

            const padding = 57;
            const circleWidth = 204;
            const spacing = ((defs.canvas.width - (padding * 2)) - (circleWidth * 4)) / 3;
            data.charts.forEach(function (statData, i) {
                const statGroup = stat(statData);
                statGroup.set({
                    top: 771,
                    left: 57 + (i * (204 + spacing))
                });

                defs.canvas.add(statGroup);
            });
        }


    });
}
