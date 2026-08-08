import load from '../loader';
import {bgImg} from "./step1-bg-img";
import {TxtType as typer} from "./step1-typer";
import * as hunt from './scavenger';


const bgImgSrc = 'https://photos.hudsonvillewaterpolo.com/natatorium.jpg';

const clues = [
    `First you'll need to figure out the answer to the ultimate question of life`,
    `(I told you it wouldn't be easy)`,
    `Then "begin at the beginning, and go on till you come to the end: then stop." -- Alice in Wonderland`,
    `Good Luck`
];

const lines = [
    [
        `Don't worry, no creepy puppets`,
        `But if you get all the way to the end there is a prize`,
        `Think you're smart enough to figure out all the clues?`,
        `I have to warn you, it's not gonna be easy`,
        ...clues
    ],
    [
        `Seriously? I just told you all that a second ago...`,
    ],
    [
        `OMG -- FOR REAL?!?!?!`,
        `Fine, but if you're this bad at the first step you might just want to give up now`,
        ...clues,
        `I think you're going to need it`
    ],
    [
        `Nope, I'm done`,
        ``,
        ``,
        ``,
        `Seriously, why are you still waiting here?`,
        `I'm not telling you again`,
        `Now shoo`
    ],
    [
        `Bye Felicia`
    ]
];
const period = 2000;

let inited = false;
let yesCount = 0;

let glitcher, writer;

function init() {
    if (inited) {
        return Promise.resolve([glitcher, writer]);
    }

    // create our dom elements and start loading
    glitcher = document.createElement('div');
    glitcher.id = `glitcher`;
    glitcher.classList.add('glitcher--loading');
    glitcher.innerHTML = `
            <div id="glitcher-load">
                <h1>Transmission Incoming</h1>
            </div>
            <canvas id="glitcher-canvas"></canvas>
            <div id="glitcher-typewriter">
                <div>
                    <span class="glitcher-wrap"></span>    
                </div>
            </div>`;

    document.body.appendChild(glitcher);

    return Promise.all([
        load.js('https://cdnjs.cloudflare.com/ajax/libs/three.js/84/three.min.js'),
        load.img(bgImgSrc, true)
    ])
        .then(() => {

            bgImg(bgImgSrc);
            writer = new typer(glitcher, lines[yesCount], period);

            glitcher.addEventListener(typer.CompleteEventName, () => {
                glitcher.classList.add('glitcher--hidden');
                yesCount = Math.min(++yesCount, lines.length - 1);
                writer.lines = lines[yesCount];
            });

            inited = true;

            return [glitcher, writer];
        })
}

function yes() {
    init().then(([g, w]) => {
        g.classList.remove('glitcher--loading', 'glitcher--hidden');
        w.start();
        hunt.trackStep('1', '', yesCount);
    });
}

function no() {
    console.log('fine... come back when the answer is yes');
}

function maybe() {
    console.log(`I don't like that`);
}

Object.defineProperties(window, {
    yes: {
        get: yes
    },
    no: {
        get: no
    },
    maybe: {
        get: maybe
    }
});

console.log('%cDo you want to play a game...', 'font-weight: bold;');

