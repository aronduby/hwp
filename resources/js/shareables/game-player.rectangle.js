(function() {

  var _ = require('lodash');
  var fabric = require('fabric').fabric;

  var BackgroundWithStripe = require('./parts/backgroundWithStripe');
  var logo = require('./parts/logo');
  var scores = require('./parts/scores');
  var badge = require('./parts/badge');
  var name = require('./parts/name');
  var stat = require('./parts/stat');

  module.exports = function draw(data, defs) {
    return new Promise(function(resolve, reject) {

      var canvas = defs.canvas;

      var bg = new BackgroundWithStripe(data.photo, defs);
      bg.stripe.top = 603;
      bg.stripe.height = 240;
      canvas.add(bg);

      logo(logo.TOP, defs)
        .then(function(img) {
          img.set({
            top: 0,
            left: 0
          });

          canvas.add(img);
        });

      var scoreGroup = scores(data.game, defs);
      scoreGroup.set({
        // top: 420,
        top: 722,
        left: defs.canvas.width / 2
      });
      canvas.add(scoreGroup);

      if (data.game.badge) {
        var badgeGroup = badge(data.game.badge, true, defs);
        badgeGroup.set({
          transformMatrix: [.84, 0, 0, .84, 100, 865.5]
        });
        canvas.add(badgeGroup);
      }

      var padding = 57;
      if (data.player) {
        var nameBox = name(data.player.player, padding, defs);
        nameBox.set({
          fontSize: 150,
          originY: 'bottom',
          top: 1580,
          left: padding
        });

        canvas.add(nameBox);
      }

      if (data.charts.length) {
        var gradientBG = new fabric.Rect({
          left: 0,
          top: defs.canvas.height - 258,
          height: 258,
          width: defs.canvas.width
        });

        defs.gradients.blueTransBottom.coords.y2 = gradientBG.height;
        gradientBG.set('fill', defs.gradients.blueTransBottom);
        defs.canvas.add(gradientBG);


        var circleWidth = 204;
        var spacing = ((defs.canvas.width - (padding * 2)) - (circleWidth * 4)) / 3;
        data.charts.forEach(function(statData, i) {
          var statGroup = stat(statData);
          statGroup.set({
            top: 1590,
            left: 57 + (i * (204 + spacing))
          });

          defs.canvas.add(statGroup);
        });
      }


    });
  }

})();