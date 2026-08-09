import { fabric } from 'fabric';
import BackgroundWithStripe from './parts/backgroundWithStripe';
import * as logo from './parts/logo';
import badge from './parts/badge';
import name from './parts/name';
import stat from './parts/stat';

export default function draw(data, defs) {
    return new Promise(function (resolve, reject) {

        const canvas = defs.canvas;
        const padding = defs.padding;
        let badgeY = 400;

        // bg - no stripe
        const bg = new BackgroundWithStripe(data.photo, defs);
        bg.stripe.height = 0;
        canvas.add(bg);

        // logo
        logo.build(logo.STACKED, defs)
            .then(function (img) {
                img.set({
                    top: canvas.height - img.height - padding,
                    left: padding
                });

                canvas.add(img);
            });

        // Charts
        if (data.charts.length) {
            const gradientBG = new fabric.Rect({
                top: 0,
                left: 784,
                height: defs.canvas.height,
                width: 304
            });

            defs.gradients.blueTransRight.coords.x2 = 304;
            gradientBG.set('fill', defs.gradients.blueTransRight);
            defs.canvas.add(gradientBG);


            const circleWidth = 204;
            const statHeight = ((circleWidth + 82.8) * .9);
            const spacing = ((defs.canvas.height - (padding * 2)) - (statHeight * 4)) / 3;
            data.charts.forEach(function (statData, i) {
                const statGroup = stat(statData);
                statGroup.set({
                    scaleX: .9,
                    scaleY: .9,
                    originX: 'center',
                    originY: 'top',
                    top: padding + (i * statHeight + spacing),
                    left: 845 + circleWidth / 2
                });

                defs.canvas.add(statGroup);
            });
        }

        // Name
        if (data.player) {
            const nameBox = name(data.player.player, padding, defs);
            nameBox.set({
                fontSize: 195,
                originY: 'top',
                top: padding / 2,
                left: padding,
                width: canvas.width - defs.padding - 304
            });

            canvas.add(nameBox);

            const bounding = nameBox.getBoundingRect();
            badgeY = bounding.top + bounding.height;
        }

        // Badges
        if (data.badges && data.badges.length) {
            const maxPerRow = 6;
            let countPerRow = 0;

            data.badges.forEach(function (badgeData, i) {
                const badgeGroup = badge(badgeData, false, defs);
                const x = (padding / 2) + (countPerRow * 105);
                badgeGroup.set({
                    transformMatrix: [1, 0, 0, 1, x, badgeY]
                });
                canvas.add(badgeGroup);

                countPerRow++;
                if (countPerRow > maxPerRow) {
                    countPerRow = 0;
                    badgeY += 125;
                }
            });
        }

    });
}
