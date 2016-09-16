(function() {
	
	var Signal = require('signals');

	function Game(gameId) {
		this.connected = new Signal();
		this.started = new Signal();
		this.ended = new Signal();
		this.quarterStarted = new Signal();
		this.quarterEnded = new Signal();
		this.shootOutStarted = new Signal();
		this.updated = new Signal();
	}

	module.exports = Game;


})();