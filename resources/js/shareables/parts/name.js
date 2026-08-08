(function () {
  'use strict';

  var fabric = require('fabric').fabric;

  var yellow = '#f5d100';

  module.exports = function name(player, padding, defs) {
    // apply styles to the last name only
    function makeSubStyles(first, last) {
      var offset = first.length + 1;// +1 for the space
      return last.split('').reduce((acc, letter, i) => {
        acc[offset + i] = {fill: yellow};
        return acc;
      }, {});
    }

    // use a textbox for line breaks
    return new fabric.Textbox((player.first_name + ' ' + player.last_name).toUpperCase(), {
      width: defs.canvas.width - (padding * 2),
      fontFamily: 'League Gothic',
      fill: '#fff',
      fontSize: 150,
      lineHeight: .75,
      lockScalingX: true,
      styles: {
        0: makeSubStyles(player.first_name, player.last_name)
      },
      shadow: defs.shadow
    });

  }


})();