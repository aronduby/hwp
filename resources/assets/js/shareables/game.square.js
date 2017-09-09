(function() {

  var fabric = require('fabric').fabric;
  var BackgroundWithStripe = require('./parts/backgroundWithStripe');
  var logo = require('./parts/logo');
  var scores = require('./parts/scores');
  var badge = require('./parts/badge');

  module.exports = function draw(data, defs) {
    return new Promise(function(resolve, reject) {

      var canvas = defs.canvas;

      var bg = new BackgroundWithStripe(data.photo, defs);
      bg.stripe.top = 338;
      canvas.add(bg);

      logo(logo.BOTTOM, defs)
        .then(function(img) {
          img.set({
            top: 1080 - img.height,
            left: 0
          });

          canvas.add(img);
        });

      var scoreGroup = scores(data.game, defs);
      scoreGroup.set({
        top: 267,
        left: 208
      });
      canvas.add(scoreGroup);

      if (data.game.badge) {
        var badgeGroup = badge(data.game.badge, true, defs);
        badgeGroup.set({
          transformMatrix: [1, 0, 0, 1, 125, 598]
        });
        canvas.add(badgeGroup);
      }

      canvas.renderAll();

    });
  }

})();