import { fabric } from 'fabric';
import * as scorebox from './scorebox.js';

function result(a, b) {
    return a > b ? scorebox.WIN : (
        a < b ? scorebox.LOSS : scorebox.TIE
    )
}

export default function scores(game, defs) {
    const us = scorebox.build({
        team: game.us,
        score: game.score_us,
        result: result(game.score_us, game.score_them)
    }, defs);

    const them = scorebox.build({
        team: game.opponent,
        score: game.score_them,
        result: result(game.score_them, game.score_us)
    }, defs);

    them.set('left', 348);

    return new fabric.Group([us, them], {
        originX: 'center',
        originY: 'center'
    });
}
