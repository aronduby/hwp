import { fabric } from 'fabric';
import gridPattern from '@/shareables/parts/gridPattern';
import gradients from '@/shareables/parts/gradients';
import gameSquare from '@/shareables/game.square';
import gameRectangle from '@/shareables/game.rectangle';
import gamePlayerSquare from '@/shareables/game-player.square';
import gamePlayerRectangle from '@/shareables/game-player.rectangle';
import playerSquare from '@/shareables/player.square';
import playerRectangle from '@/shareables/player.rectangle';
import updateSquare from '@/shareables/update.square';
import updateRectangle from '@/shareables/update.rectangle';
import * as holder from '@/shareables/interface';

let size = localStorage.getItem('shareableSize') || 'square';

let canvas, defs, lastClicked;

function init() {
    if (canvas) {
        canvas.clear();
        return Promise.resolve();
    }

    return new Promise((resolve, reject) => {
        fabric.Object.prototype.set({
            selectable: false,
            fontFamily: 'sans-serif'
        });

        canvas = new fabric.StaticCanvas();
        canvas.selection = false;
        canvas.renderOnAddRemove = false;
        canvas.stateful = false;
        canvas.enableRetinaScaling = false;

        defs = {
            canvas: canvas,
            shadow: new fabric.Shadow({
                color: 'rgba(0,0,0,.8)',
                offsetX: 0,
                offsetY: 2,
                blur: 5
            }),
            padding: 57,
            gridPattern: null,
            gradients: gradients,
            promises: []
        };

        gridPattern()
            .then(pattern => {
                defs.gridPattern = pattern
            })
            .then(resolve)
            .catch(reject);
    });
}

function fetchData(url) {
    return fetch(url).then(rsp => rsp.json());
}

function setSize(s) {
    size = s;
    localStorage.setItem('shareableSize', s);
}

const shapeRegex = /(rectangle|square)/g;

function dataUrlFromSVG(url) {
    return url.replace(shapeRegex, size)
        .replace('.svg', '');
}

const types = {
    game: {
        getUrl: (trigger) => dataUrlFromSVG(trigger.href),
        square: gameSquare,
        rectangle: gameRectangle
    },
    gamePlayer: {
        getUrl: (trigger) => dataUrlFromSVG(trigger.href),
        square: gamePlayerSquare,
        rectangle: gamePlayerRectangle
    },
    player: {
        getUrl: (trigger) => dataUrlFromSVG(trigger.href),
        square: playerSquare,
        rectangle: playerRectangle
    },
    update: {
        getUrl: (trigger) => dataUrlFromSVG(trigger.href),
        square: updateSquare,
        rectangle: updateRectangle
    }
};


holder.setSize(size);

holder.sizeChanged.add((size) => {
    setSize(size);
    if (holder.isShowing()) {
        lastClicked.click();
    }
});

holder.closed.add(() => {
    canvas.dispose();
    canvas = undefined;
});

document.addEventListener('DOMContentLoaded', () => {

    document.body.addEventListener('click', (e) => {
        const trigger = e.target.closest('.shareable');
        if (!trigger) {
            return;
        }

        e.preventDefault();

        lastClicked = trigger;

        const type = trigger.dataset.shareableType;
        const timer = Date.now();

        holder.show();

        Promise.all([
            init(),
            fetchData(types[type].getUrl(trigger))
        ])
            .then(([_, data]) => {
                canvas.setDimensions(data.dimensions);
                canvas.setBackgroundColor('#ff0000');

                types[type][size](data, defs, trigger);

                Promise.all(defs.promises)
                    .then(() => {
                        canvas.renderAll();

                        const dataUrl = canvas.toDataURL({multiplier: 1, format: 'png'});
                        holder.load(dataUrl);

                        ga('send', {
                            hitType: 'event',
                            eventCategory: 'shareables',
                            eventAction: type,
                            eventLabel: size,
                            eventValue: Date.now() - timer
                        });
                    })
                    .catch((err) => {
                        console.error(err);
                        alert('sorry, something went wrong');
                    });
            });
    });

});
