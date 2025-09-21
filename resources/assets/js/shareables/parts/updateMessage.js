(function () {
  'use strict';

  var fabric = require('fabric').fabric;
  var finder = require('../../nameLinker').finder;

  var yellow = '#f5d100';
  var grey = '#cfcfcf';
  var colors = [yellow, grey];

  module.exports = function updateMessage(msg, defs, paddingMultiplier) {

    /**
     * [
     *  [
     *    0 => '#21 Ian Worst',
     *    1 => '#21',
     *    2 => 'Ian Worst',
     *    index => 18 // offset in str
     *  ]
     * ]
     */
    var mentions = finder(msg);

    function makeStyles(mentions) {
      var obj = {};

      mentions.forEach((match, i) => {
        for(var j = 0; j < match[0].length; j++) {
          obj[j + match.index] = {fill: colors[i] ?? colors[colors.length - 1]};
        }
      });

      return obj;
    }

    paddingMultiplier = paddingMultiplier || 2.5;

    return new fabric.Textbox(msg.toUpperCase(), {
      fontFamily: 'League Gothic',
      fill: '#fff',
      fontSize: 82,
      lineHeight: 1.05,
      width: defs.canvas.width - (defs.padding * paddingMultiplier),
      textAlign: 'center',
      originY: 'center',
      originX: 'center',
      shadow: defs.shadow,
      styles: {
        0: makeStyles(mentions)
      }
    })

  }


})();