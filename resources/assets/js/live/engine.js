(function() {
	'use strict';

	var Game   = require('./game'),
		_      = require('lodash'),
		moment = require('moment');

	var games = {};

	var engine = {
		process: process,
		game: getOrCreateGame,
		getGame: getGame,
		createGame: createGame,
		hasGame: hasGame
	};

	/**
	 * Takes the supplied data and does all the necessary processing and triggers the game events
	 * @param data
	 */
	function process(data) {
		var game = getOrCreateGame(data.game_id);
		var quarterRegex = /Start of the (\d\w+( .+)?) --/i;

		data.moment = moment.unix(data.ts);

		// catch old and new ways
		data.msg = data.body || data.msg;

		// trigger different events based on the update
		// TODO - this should eventually be based on control messages from the live scoring panel

		// start of game
		if (_.startsWith(data.msg, 'Start of Hudsonville')) {
			game.started.dispatch(data);
			game.quarterStarted.dispatch(data, '1st');
		}

		// end of quarter
		if (_.startsWith(data.msg, 'At the end of the')) {
			game.quarterEnded.dispatch(data);
		}

		// start of quarter
		if (_.startsWith(data.msg, 'Start of the')) {
			var matched = data.msg.match(quarterRegex);
			game.quarterStarted.dispatch(data, matched[1]);
		}

		// end of the game
		if (_.startsWith(data.msg, 'Final Result')) {
			game.quarterEnded.dispatch(data);
			game.ended.dispatch(data);
		}

		// start of shoot-out
		if (_.endsWith(data.msg, 'Shoot-Out!')) {
			game.shootOutStarted.dispatch(data);
		}

		game.updated.dispatch(data);
	}

	/**
	 * Get's or creates a Game with the supplied id
	 * @param gameId
	 * @returns {Game}
	 */
	function getOrCreateGame(gameId) {
		return getGame(gameId) || createGame(gameId);
	}

	/**
	 * Get's the game with the specified id
	 * @param gameId
	 * @returns {Game|undefined}
	 */
	function getGame(gameId) {
		return _.get(games, gameId, undefined);
	}

	/**
	 * Creates a game with the specified id
	 * @param gameId
	 * @returns {Game}
	 */
	function createGame(gameId) {
		var game = new Game(gameId);
		_.set(games, gameId, game);

		return game;
	}

	/**
	 * Checks for the game in the game cache
	 * @param gameId
	 * @returns {boolean}
	 */
	function hasGame(gameId) {
		return _.has(games, gameId);
	}


	module.exports = engine;

})()