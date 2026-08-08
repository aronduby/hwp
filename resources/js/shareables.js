const fabric = require('fabric').fabric;
const gridPattern = require('./shareables/parts/gridPattern');
const gradients = require('./shareables/parts/gradients');

const gameSquare = require('./shareables/game.square');
const gameRectangle = require('./shareables/game.rectangle');
const gamePlayerSquare = require('./shareables/game-player.square');
const gamePlayerRectangle = require('./shareables/game-player.rectangle');
const playerSquare = require('./shareables/player.square');
const playerRectangle = require('./shareables/player.rectangle');
const updateSquare = require('./shareables/update.square');
const updateRectangle = require('./shareables/update.rectangle');

const holder = require('./shareables/interface');

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
    return $.getJSON(url);
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
        getUrl: (e) => dataUrlFromSVG(e.currentTarget.href),
        square: gameSquare,
        rectangle: gameRectangle
    },
    gamePlayer: {
        getUrl: (e) => dataUrlFromSVG(e.currentTarget.href),
        square: gamePlayerSquare,
        rectangle: gamePlayerRectangle
    },
    player: {
        getUrl: (e) => dataUrlFromSVG(e.currentTarget.href),
        square: playerSquare,
        rectangle: playerRectangle
    },
    update: {
        getUrl: (e) => dataUrlFromSVG(e.currentTarget.href),
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

$(document).ready(function () {

    $('body').on('click', '.shareable', function (e) {
        lastClicked = $(this);

        const type = $(this).data('shareable-type');
        const timer = Date.now();

        holder.show();

        Promise.all([
            init(),
            fetchData(types[type].getUrl(e))
        ])
            .then(function ([_, data]) {
                canvas.setDimensions(data.dimensions);
                canvas.setBackgroundColor('#ff0000');

                types[type][size](data, defs, e);

                Promise.all(defs.promises)
                    .then(() => {
                        canvas.renderAll();

                        let dataUrl = canvas.toDataURL({multiplier: 1, format: 'png'});
                        holder.load(dataUrl);

                        ga('send', {
                            hitType: 'event',
                            eventCategory: 'shareables',
                            eventAction: type,
                            eventLabel: size,
                            eventValue: Date.now() - timer
                        });
                    })
                    .catch((e) => {
                        console.error(e);
                        alert('sorry, something went wrong');
                    });
            });

        return false;
    });

});
