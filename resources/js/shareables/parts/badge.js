(function () {
  'use strict';

  var fabric = require('fabric').fabric;
  var Deferred = require('../../deferred');

  module.exports = function badge(badgeData, showTitle, defs) {
    var d = new Deferred();
    defs.promises.push(d.promise);

    var group = new fabric.Group([], {});

    if (showTitle) {
      var title = new fabric.Text(badgeData.title.toUpperCase(), {
        fontFamily: 'League Gothic',
        fill: '#fff',
        fontSize: 40,
        top: 60,
        left: 122,
      });

      group.addWithUpdate(title);
    }

    fabric.Image.fromURL('/badges/' + badgeData.image, function(img) {
      img.set({
        top: 0,
        left: 0,
        width: 130,
        height: 147
      });

      group.addWithUpdate(img);
      d.resolve();
    });

    return group;
  };

})();