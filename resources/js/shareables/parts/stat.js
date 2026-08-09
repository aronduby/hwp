import { defaults } from 'lodash';
import { fabric } from 'fabric';

// 'negative' => false,
// 'slices' => [33],
// 'prefix' => '+',
// 'value' => '3',
// 'suffix' => '%',
// 'subvalue' => '12/9',
// 'title' => 'Kickouts',
// 'subtitle' => 'Drawn/Called'
export default function makeStat(stat, defs) {
    const colors = ['#2a82c9', '#f29800', '#2ac95b'];
    const baseColor = '#b2b2b2';

    stat.slices = stat.slices || [0];

    // Math.PI * 2 allows us to specify angles as percents of the chart
    const StatCircle = fabric.util.createClass(fabric.Circle, {

        initialize: function (options) {
            const _defaults = {
                radius: 92,
                left: 0,
                top: 195,
                angle: -90,
                startAngle: 0,
                endAngle: 0,
                stroke: baseColor,
                strokeWidth: 10,
                fill: '',
                width: 204,
                height: 204,
            };

            options = defaults(options, _defaults);

            this.callSuper('initialize', options);
        },

        startAnglePercent: function (percent) {
            this.startAngle = (percent > 1 ? percent / 100 : percent) * Math.PI * 2;
        },

        endAnglePercent: function (percent) {
            this.endAngle = (percent > 1 ? percent / 100 : percent) * Math.PI * 2;
        },
    });

    const parts = [];

    const base = new StatCircle({
        endAngle: Math.PI * 2
    });
    parts.push(base);

    // if it's negative swap some colors so it looks like it was drawn backwards
    // this will get really weird if we try to do multiple values
    // but none of our negative-able values do that so we're good
    if (stat.negative) {
        base.set('stroke', colors[0]);
        colors[0] = baseColor;
    }

    let i = 0;
    let offset = 0;
    stat.slices.forEach(function (val) {
        const slice = new StatCircle({
            stroke: colors[i]
        });

        slice.startAnglePercent(offset);
        slice.endAnglePercent(offset + val);

        parts.push(slice);
        offset += val;
        i++;
    });

    const valueText = new fabric.Text(`${stat.value}`, {
        fontFamily: 'League Gothic',
        fontSize: stat.value.length > 3 ? 76 : 95,
        top: 100,
        left: 98,
        fill: '#fff',
        textAlign: 'center',
        originX: 'center',
        originY: 'center',
    });
    parts.push(valueText);

    // used to position prefix/suffix
    const bounding = valueText.getBoundingRect();

    if (stat.prefix) {
        parts.push(new fabric.Text(`${stat.prefix}`, {
            fontFamily: 'League Gothic',
            fontSize: 58.5,
            fill: '#fff',
            top: bounding.top,
            left: bounding.left,
            originX: 'right',
            originY: 'top'
        }));
    }

    if (stat.suffix) {
        parts.push(new fabric.Text(`${stat.suffix}`, {
            fontFamily: 'League Gothic',
            fontSize: 41,
            fill: '#fff',
            top: bounding.top + 15,
            left: bounding.left + bounding.width,
            originX: 'left',
            originY: 'top'
        }));
    }

    if (stat.subvalue) {
        parts.push(new fabric.Text(`${stat.subvalue}`, {
            fontFamily: 'League Gothic',
            fontSize: 24,
            fill: '#fff',
            top: bounding.top + 95,
            left: 98,
            originX: 'center',
            originY: 'top'
        }));
    }


    const baseBounding = base.getBoundingRect();

    function makeSubStyles(str) {
        return str.split('').reduce((acc, letter, i) => {
            acc[i] = {fontSize: 25};
            return acc;
        }, {});
    }

    const joinedTitle = stat.title + (stat.subtitle ? `\n${stat.subtitle}` : '');
    const titleText = new fabric.Text(joinedTitle.toUpperCase(), {
        fontFamily: 'League Gothic',
        fontSize: 38,
        fill: '#fff',
        lineHeight: .8,
        textAlign: 'center',
        top: baseBounding.top + baseBounding.height + 10,
        left: baseBounding.width / 2,
        originX: 'center',
        originY: 'top',
        styles: {
            1: makeSubStyles(`${stat.subtitle}`)
        }
    });
    parts.push(titleText);

    return new fabric.Group(parts);
}
