import { engine } from "./live/engine";
import { linker, matcher } from "@/nameLinker";
import { template } from "lodash";

// noinspection JSUnresolvedReference
/** @type {{game_id: int, updates: {}[]}} */
const recap = window.recap;

const game = engine.game(recap.game_id);
game.quarterStarted.add(quarterStarted);
game.quarterEnded.add(quarterEnded);
game.updated.add(updated);
game.ended.add(gameEnded);

const quarterTitles = {
    '1st': 'First Quarter',
    '2nd': 'Second Quarter',
    '3rd': 'Third Quarter',
    '4th': 'Fourth Quarter',
    '1st OT': 'First Overtime',
    '2nd OT': 'Second Overtime',
    'Shootout': 'Shootout'
};
let started = false;
let recapHolder;
let loader;
let currentQuarter;
let currentQuarterTitle;
let quarterTmpl;
let updateTmpl;

window.addEventListener('DOMContentLoaded', () => {
    // actual elements we will be using later
    recapHolder = document.querySelector('.recap');
    loader = document.querySelector('.recap-loader');

    // template functions/elements
    quarterTmpl = template(document.getElementById('quarter-tmpl').innerHTML);
    updateTmpl = template(document.getElementById('update-tmpl').innerHTML);

    // kick everything off
    processUpdates();
});

function processUpdates() {
    recap.updates.forEach((update) => {
        engine.process(update);
    });
}

function createNewQuarter(data, titleKey) {
    const title = quarterTitles[titleKey];
    const titleSplit = title.split(' ');

    const scope = {
        quarterNameFirst: titleSplit[0],
        quarterNameRemaining: titleSplit[1],
        status: '',
        scoreUs: data.score[0],
        scoreThem: data.score[1],
        opponent: data.opponent
    };

    const newQuarter = document.createElement('div');
    newQuarter.innerHTML = quarterTmpl(scope);
    recapHolder.append(newQuarter);
    currentQuarter = newQuarter;
    currentQuarterTitle = title;
}

function updateScore(score) {
    let classUs, classThem;

    if (score[0] > score[1]) {
        classUs = 'result--win';
        classThem = 'result--loss';
    } else if (score[0] < score[1]) {
        classUs = 'result--loss';
        classThem = 'result--win';
    } else {
        classUs = classThem = 'result--tie';
    }

    const parts = [
        ['us', classUs, score[0]],
        ['them', classThem, score[1]]
    ];
    parts.forEach(([selector, classes, score]) => {
        const scoreEl = currentQuarter.querySelector(`.score--${selector}`);
        scoreEl.classList.remove('result--win', 'result--loss', 'result--tie');
        scoreEl.classList.add(classes);
        scoreEl.querySelector('h2').textContent = score;
    });
}

function isQuarterStarted(data) {
    if (!started) {
        createNewQuarter(data, '1st');
        loader.remove();
        loader = false;
        started = true;
    }
}

function quarterStarted(data, title) {
    createNewQuarter(data, title);
    started = true;

    if (loader) {
        loader.remove();
        loader = false;
    }
}

function updated(data) {
    isQuarterStarted(data);

    data.quarterTitle = currentQuarterTitle;
    data.mentions = matcher(data.msg);

    const scope = {
        msg: linker(data.msg || ''),
        score: `${data.score[0]}-${data.score[1]}`,
        timestampFormatted: data.moment.format('LT'),
        json: JSON.stringify(data),
        shareable: `/shareables/square/update?game_id=${data.game_id}&mentions=${JSON.stringify(data.mentions)}`
    };

    // retweet?
    if (data.twitter_id) {
        scope.retweet = `https://twitter.com/intent/retweet?tweet_id=${data.twitter_id}`;
    }

    currentQuarter.querySelector('.body.container').insertAdjacentHTML('beforeend', updateTmpl(scope));
    updateScore(data.score);
}

function updateQuarterStatus(data, title) {
    isQuarterStarted(data);

    currentQuarter.find('.recap-quarter-status')
        .text(title);

    updateScore(data.score);
}

function quarterEnded(data) {
    const title = `End of the ${currentQuarterTitle.replace('Quarter', '')}`;
    updateQuarterStatus(data, title);
}

function gameEnded(data) {
    const title = 'Final Result';
    updateQuarterStatus(data, title);
}
