import Game from "./game";
import moment from 'moment';

const games = {};

export const engine = {
    process,
    game: getOrCreateGame,
    getGame,
    createGame,
    hasGame
};

/**
 * Takes the supplied data and does all the necessary processing and triggers the game events
 * @param data
 */
function process(data) {
    const game = getOrCreateGame(data.game_id);
    const quarterRegex = /Start of the (\d\w+( .+)?) --/i;

    data.moment = moment.unix(data.ts);

    // catch old and new ways
    data.msg = data.body || data.msg;

    // trigger different events based on the update
    // TODO - this should eventually be based on control messages from the live scoring panel

    // start of game
    if (data.msg.startsWith('Start of Hudsonville')) {
        game.started.dispatch(data);
        game.quarterStarted.dispatch(data, '1st');
    }

    // end of quarter
    if (data.msg.startsWith('At the end of the')) {
        game.quarterEnded.dispatch(data);
    }

    // start of quarter
    if (data.msg.startsWith('Start of the')) {
        const matched = data.msg.match(quarterRegex);
        game.quarterStarted.dispatch(data, matched[1]);
    }

    // end of the game
    if (data.msg.startsWith('Final Result')) {
        game.quarterEnded.dispatch(data);
        game.ended.dispatch(data);
    }

    // start of shoot-out
    if (data.msg.endsWith('Shoot-Out!')) {
        game.shootOutStarted.dispatch(data);
    }

    game.updated.dispatch(data);
}

/**
 * Gets or creates a Game with the supplied id
 * @param {int} gameId
 * @returns {Game}
 */
function getOrCreateGame(gameId) {
    return getGame(gameId) || createGame(gameId);
}

/**
 * Gets the game with the specified id
 * @param gameId
 * @returns {Game|undefined}
 */
function getGame(gameId) {
    return games?.[gameId];
}

/**
 * Creates a game with the specified id
 * @param gameId
 * @returns {Game}
 */
function createGame(gameId) {
    const game = new Game(gameId);
    games[gameId] = game;

    return game;
}

/**
 * Checks for the game in the game cache
 * @param gameId
 * @returns {boolean}
 */
function hasGame(gameId) {
    return games.hasOwnProperty(gameId);
}
