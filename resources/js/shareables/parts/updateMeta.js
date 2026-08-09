import { fabric } from 'fabric';

export default function updateMeta(data, defs) {

    function makeStyles(data) {
        const offset = data.opponent.length + 3;
        const score = `${data.score[0]}-${data.score[1]}`;

        return score.split('').reduce((acc, letter, i) => {
            acc[i + offset] = {fontWeight: '700'};
            return acc;
        }, {});
    }

    const str = `${data.opponent}   ${data.score[0]}-${data.score[1]}   ${data.quarterTitle}`;

    return new fabric.Text(str, {
        fontFamily: 'Play',
        fill: '#d5d5d5',
        fontSize: 29,
        charSpacing: -6,
        width: defs.canvas.width - (defs.padding * 2.5),
        textAlign: 'center',
        originY: 'center',
        originX: 'center',
        // styles: {
        //   0: makeStyles(data)
        // }
    })

}
