(function () {
  'use strict';

  var fabric = require('fabric').fabric;

  module.exports = function updateMeta(data, defs) {

    function makeStyles(data) {
      var offset = data.opponent.length + 3;
      var score = data.score[0] + '-' + data.score[1];

      return score.split('').reduce((acc, letter, i) => {
        acc[i + offset] = {fontWeight: '700'};
        return acc;
      }, {});
    }

    var str = data.opponent + '   ' + data.score[0] + '-' + data.score[1] + '   ' + data.quarterTitle;

    return new fabric.Text(str, {
      fontFamily: 'Play',
      fill: '#d5d5d5',
      fontSize: 29,
      charSpacing: -6,
      width: defs.canvas.width - (defs.padding * 2.5),
      textAlign: 'center',
      originY: 'center',
      originX: 'center',
      // styles: {
      //   0: makeStyles(data)
      // }
    })

  }


})();