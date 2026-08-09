import { fabric } from 'fabric';

export function build(data, defs) {
    const width = 318;

    const bg = new fabric.Rect({
        width: width,
        height: 384,
        fill: '#fff',
        shadow: defs.shadow
    });

    const headerBg = new fabric.Rect({
        width: width,
        height: 130,
        fill: data.result || this.TIE
    });

    const headerGrid = new fabric.Rect({
        width: width,
        height: 130,
        fill: defs.gridPattern,
        opacity: .25
    });

    const team = new fabric.Textbox(data.team.toUpperCase(), {
        fontFamily: 'League Gothic',
        fill: '#fff',
        fontSize: 58,
        top: 65,
        left: 159,
        width: width,
        lineHeight: .8,
        textAlign: 'center',
        originX: 'center',
        originY: 'center'
    });

    const score = new fabric.Text(`${data.score}`, {
        fontFamily: 'League Gothic',
        fill: '#575242',
        fontSize: 153,
        top: 177,
        left: 159,
        originX: 'center'
    });

    const header = new fabric.Group([headerBg, headerGrid, team]);

    return new fabric.Group([bg, header, score]);
}

export const WIN = '#92db00';
export const LOSS = '#ff291c';
export const TIE = '#f29800';
