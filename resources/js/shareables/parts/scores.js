(function () {
  'use strict';

  var fabric = require('fabric').fabric;
  var scorebox = require('./scorebox');

  function result(a, b) {
    return a > b ? scorebox.WIN : (
      a < b ? scorebox.LOSS : scorebox.TIE
    )
  }

  module.exports = function scores(game, defs) {
    var us = scorebox({
      team: game.us,
      score: game.score_us,
      result: result(game.score_us, game.score_them)
    }, defs);

    var them = scorebox({
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

})();