import 'jquery.loadtemplate';
import { engine } from "./live/engine";
import { linker, matcher } from "./nameLinker";
import _ from 'lodash';

var game = engine.game(recap.game_id);
game.quarterStarted.add(quarterStarted);
game.quarterEnded.add(quarterEnded);
game.updated.add(updated);
game.ended.add(gameEnded);

var quarterTitles = {
    '1st': 'First Quarter',
    '2nd': 'Second Quarter',
    '3rd': 'Third Quarter',
    '4th': 'Fourth Quarter',
    '1st OT': 'First Overtime',
    '2nd OT': 'Second Overtime',
    'Shootout': 'Shootout'
};
var started = false;
var score = [0, 0];
var recapHolder;
var loader;
var currentQuarter;
var currentQuarterTitle;
var quarterTmpl;
var updateTmpl;

window.addEventListener('DOMContentLoaded', function () {
    recapHolder = $('.recap').first();
    quarterTmpl = $('#quarter-tmpl');
    updateTmpl = $('#update-tmpl');
    loader = $('.recap-loader');

    processUpdates();
});

function processUpdates() {
    _.forEach(recap.updates, function (update) {
        engine.process(update);
    });
}

function createNewQuarter(data, titleKey) {
    var newQuarter, titleSplit, scope, title;

    title = quarterTitles[titleKey];
    titleSplit = title.split(' ');

    scope = {
        quarterNameFirst: titleSplit[0],
        quarterNameRemaining: titleSplit[1],
        status: '',
        scoreUs: data.score[0],
        scoreThem: data.score[1],
        opponent: data.opponent
    };

    newQuarter = $('<div></div>');
    newQuarter.loadTemplate(quarterTmpl, scope);
    recapHolder.append(newQuarter);
    currentQuarter = newQuarter;
    currentQuarterTitle = title;
}

function updateScore(score) {
    var classUs, classThem;

    if (score[0] > score[1]) {
        classUs = 'result--win';
        classThem = 'result--loss';
    } else if (score[0] < score[1]) {
        classUs = 'result--loss';
        classThem = 'result--win';
    } else {
        classUs = classThem = 'result--tie';
    }

    currentQuarter
        .find('.score--us')
        .removeClass('result--win result--loss result--tie')
        .addClass(classUs)
        .find('h2')
        .text(score[0])
        .end()
        .end()
        .find('.score--them')
        .removeClass('result--win result--loss result--tie')
        .addClass(classThem)
        .find('h2')
        .text(score[1])
        .end()
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

    var newUpdate = $('<div></div>');
    var scope = {
        msg: linker(data.msg || ''),
        score: data.score[0] + '-' + data.score[1],
        timestampFormatted: data.moment.format('LT'),
        json: JSON.stringify(data),
        shareable: '/shareables/square/update?game_id=' + data.game_id + '&mentions=' + JSON.stringify(data.mentions)
    };

    // retweet?
    if (data.twitter_id) {
        scope.retweet = 'https://twitter.com/intent/retweet?tweet_id=' + data.twitter_id;
    }

    newUpdate.loadTemplate(updateTmpl, scope);
    currentQuarter.find('.body.container').append(newUpdate);
    updateScore(data.score);
}

function updateQuarterStatus(data, title) {
    isQuarterStarted(data);

    currentQuarter.find('.recap-quarter-status')
        .text(title);

    updateScore(data.score);
}

function quarterEnded(data) {
    var title = 'End of the ' + currentQuarterTitle.replace('Quarter', '');
    updateQuarterStatus(data, title);
}

function gameEnded(data) {
    var title = 'Final Result';
    updateQuarterStatus(data, title);
}
