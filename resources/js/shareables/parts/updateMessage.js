import { fabric } from 'fabric';
import { finder } from '@/nameLinker.js';

const yellow = '#f5d100';
const grey = '#cfcfcf';
const colors = [yellow, grey];

export default function updateMessage(msg, defs, paddingMultiplier) {

    /**
     * [
     *  [
     *    0 => '#21 Ian Worst',
     *    1 => '#21',
     *    2 => 'Ian Worst',
     *    index => 18 // offset in str
     *  ]
     * ]
     */
    const mentions = finder(msg);

    function makeStyles(mentions) {
        const obj = {};

        mentions.forEach((match, i) => {
            for (let j = 0; j < match[0].length; j++) {
                obj[j + match.index] = {fill: colors[i] ?? colors[colors.length - 1]};
            }
        });

        return obj;
    }

    paddingMultiplier = paddingMultiplier || 2.5;

    return new fabric.Textbox(msg.toUpperCase(), {
        fontFamily: 'League Gothic',
        fill: '#fff',
        fontSize: 82,
        lineHeight: 1.05,
        width: defs.canvas.width - (defs.padding * paddingMultiplier),
        textAlign: 'center',
        originY: 'center',
        originX: 'center',
        shadow: defs.shadow,
        styles: {
            0: makeStyles(mentions)
        }
    })

}
