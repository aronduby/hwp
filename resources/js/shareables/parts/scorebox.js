(function () {
  'use strict';

  var fabric = require('fabric').fabric;

  var scorebox = function scorebox(data, defs) {
    var width = 318;

    var bg = new fabric.Rect({
      width: width,
      height: 384,
      fill: '#fff',
      shadow: defs.shadow
    });

    var headerBg = new fabric.Rect({
      width: width,
      height: 130,
      fill: data.result || this.TIE
    });

    var headerGrid = new fabric.Rect({
      width: width,
      height: 130,
      fill: defs.gridPattern,
      opacity: .25
    });

    var team = new fabric.Textbox(data.team.toUpperCase(), {
      fontFamily: 'League Gothic',
      fill: '#fff',
      fontSize: 58,
      top: 65,
      left: 159,
      width: width,
      lineHeight: .8,
      textAlign: 'center',
      originX: 'center',
      originY: 'center'
    });

    var score = new fabric.Text(data.score + '', {
      fontFamily: 'League Gothic',
      fill: '#575242',
      fontSize: 153,
      top: 177,
      left: 159,
      originX: 'center'
    });

    var header = new fabric.Group([headerBg, headerGrid, team]);

    return new fabric.Group([bg, header, score]);
  }

  scorebox.WIN = '#92db00';
  scorebox.LOSS = '#ff291c';
  scorebox.TIE = '#f29800';

  module.exports = scorebox;

})();